<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentAttempt;
use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class CourseController extends Controller
{
    public function show($id)
    {
        $studentId = auth()->id();

        $isEnrolled = Enrollment::where('student_id', $studentId)
            ->where('training_id', $id)
            ->exists();

        if (! $isEnrolled) {
            abort(403, 'No estás inscrito en esta capacitación.');
        }

        $training = Training::with(['course', 'teacher.person', 'assessments', 'tasks', 'announcements'])
            ->where('training_id', $id)
            ->firstOrFail();

        $enrollment = Enrollment::where('student_id', $studentId)
            ->where('training_id', $id)
            ->firstOrFail();

        $attempts = AssessmentAttempt::where('enrollment_id', $enrollment->enrollment_id)
            ->with('assessment')
            ->orderByDesc('created_at')
            ->get();

        $attendances = Attendance::with('schedule')
            ->where('enrollment_id', $enrollment->enrollment_id)
            ->orderByDesc('created_at')
            ->get();

        $taskIds = $training->tasks->pluck('task_id')->toArray();
        $submissions = TaskSubmission::whereIn('task_id', $taskIds)
            ->where('student_id', $studentId)
            ->get()
            ->keyBy('task_id');

        return view('student.courses.show', compact('training', 'attempts', 'attendances', 'submissions'));
    }

    public function takeExam(Request $request, $assessment_id)
    {
        $studentId = auth()->id();

        // Actualizado: Cambiado 'questions.options' a 'questions.alternatives'
        $assessment = Assessment::with(['training', 'questions.alternatives'])
            ->where('assessment_id', $assessment_id)
            ->firstOrFail();

        $enrollment = Enrollment::where('student_id', $studentId)
            ->where('training_id', $assessment->training_id)
            ->firstOrFail();

        if ($assessment->training->isClosed()) {
            abort(403, 'El curso ya se cerró y no se pueden responder evaluaciones.');
        }

        $this->validateAssessmentAvailability($assessment);

        $pendingAttempt = AssessmentAttempt::where('enrollment_id', $enrollment->enrollment_id)
            ->where('assessment_id', $assessment_id)
            ->whereColumn('created_at', 'updated_at')
            ->latest('attempt_id')
            ->first();

        $attempt = null;
        $timerStarted = false;

        if ($pendingAttempt) {
            $attempt = $pendingAttempt;
            $timerStarted = true;
        } elseif ($request->query('start') === '1') {
            try {
                $this->ensureAttemptAllowed($assessment, $enrollment);
            } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
                return redirect()->route('student.courses.show', $assessment->training_id)
                    ->with('error', $e->getMessage());
            }

            $attemptNumber = AssessmentAttempt::where('enrollment_id', $enrollment->enrollment_id)
                ->where('assessment_id', $assessment_id)
                ->count() + 1;

            $attempt = AssessmentAttempt::create([
                'enrollment_id' => $enrollment->enrollment_id,
                'assessment_id' => $assessment_id,
                'number' => $attemptNumber,
                'date' => Carbon::now()->toDateString(),
                'score' => 0,
            ]);

            $timerStarted = true;
        }

        // Calcular y pasar datos para que el cliente calcule el tiempo restante de forma consistente
        $totalSeconds = max(0, ($assessment->time_limit ?: 60) * 60);
        $attemptCreatedTs = $attempt ? $attempt->created_at->timestamp : null;
        $serverNowTs = Carbon::now()->timestamp;
        $startUrl = route('student.assessment.take', ['id' => $assessment_id]) . '?start=1';

        return view('student.courses.take', compact('assessment', 'totalSeconds', 'attemptCreatedTs', 'serverNowTs', 'enrollment', 'attempt', 'timerStarted', 'startUrl'));
    }

    public function submitExam(Request $request, $assessment_id)
    {
        $studentId = auth()->id();

        // Actualizado: Cambiado 'questions.options' a 'questions.alternatives'
        $assessment = Assessment::with(['training', 'questions.alternatives'])
            ->where('assessment_id', $assessment_id)
            ->firstOrFail();

        $enrollment = Enrollment::where('student_id', $studentId)
            ->where('training_id', $assessment->training_id)
            ->firstOrFail();

        $this->validateAssessmentAvailability($assessment);

        // Permitir que 'answers' sea nulo (enviar sin responder) y validar alternativas si las hay
        $validated = $request->validate([
            'attempt_id' => 'required|integer|exists:assessment_attempts,attempt_id',
            'answers' => 'nullable|array',
            'answers.*' => 'nullable|integer|exists:alternatives,option_id',
        ]);

        $attempt = AssessmentAttempt::where('attempt_id', $validated['attempt_id'])
            ->where('enrollment_id', $enrollment->enrollment_id)
            ->where('assessment_id', $assessment_id)
            ->firstOrFail();

        if ($attempt->created_at->ne($attempt->updated_at)) {
            abort(403, 'Este intento ya fue enviado.');
        }

        try {
            $this->ensureAttemptAllowed($assessment, $enrollment, $attempt);
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $timeLimit = $assessment->time_limit;
        $elapsedSeconds = Carbon::now()->diffInSeconds($attempt->created_at);
        $maxSeconds = ($timeLimit * 60) + 120;

        if ($elapsedSeconds > $maxSeconds) {
            $attempt->score = 0;
            $attempt->save();
            $attempt->load('assessment');

            return view('student.assessments.result', compact('attempt'));
        }

        $totalScore = 0;
        $responses = $request->input('answers', []);

        foreach ($assessment->questions as $question) {
            $selectedOptionId = $responses[$question->question_id] ?? null;

            if ($selectedOptionId) {
                // Actualizado: Cambiado $question->options a $question->alternatives
                $selectedOption = $question->alternatives->firstWhere('option_id', $selectedOptionId);

                if ($selectedOption && $selectedOption->is_correct) {
                    $totalScore += $question->score;
                }
            }
        }

        $attempt->score = $totalScore;
        $attempt->save();
        $attempt->load('assessment');

        return view('student.assessments.result', compact('attempt'));
    }

    public function submitTask(Request $request, $task_id)
    {
        $studentId = auth()->id();

        $task = Task::with('training')
            ->where('task_id', $task_id)
            ->firstOrFail();

        $enrollment = Enrollment::where('student_id', $studentId)
            ->where('training_id', $task->training_id)
            ->firstOrFail();

        if ($task->training->isClosed()) {
            abort(403, 'El curso ya se cerró y no se aceptan nuevas entregas.');
        }

        $validated = $request->validate([
            'submission_text' => 'nullable|string',
            'attachment' => 'nullable|file|max:5120|mimes:pdf,doc,docx,txt,ppt,pptx,jpg,jpeg,png,zip',
        ]);

        $submission = TaskSubmission::firstOrNew([
            'task_id' => $task_id,
            'student_id' => $studentId,
        ]);

        if ($request->hasFile('attachment')) {
            try {
                if ($submission->file_path) {
                    Storage::disk('public')->delete($submission->file_path);
                }
            } catch (\Exception $e) {
                // ignore deletion errors
            }

            $submission->file_path = $request->file('attachment')->store('task-submissions', 'public');
        }

        $submission->submission_text = $validated['submission_text'] ?? null;
        $submission->submitted_at = now();
        $submission->grade = null;
        $submission->teacher_feedback = null;
        $submission->save();

        return redirect()->route('student.courses.show', ['id' => $task->training_id, 'tab' => 'contenido'])
            ->with('success', 'Tu tarea ha sido entregada correctamente.');
    }

    public function calendar(Request $request)
    {
        $studentId = auth()->id();
        $user = auth()->user();
        $person = $user->person;
        $fullName = trim(($person->last_names ?? '') . ' ' . ($person->first_names ?? '')) ?: ($user->username ?? 'Estudiante');

        $monthKey = $request->query('month', Carbon::now()->format('Y-m'));
        $selectedMonth = Carbon::createFromFormat('Y-m', $monthKey)->startOfMonth();

        $startOfMonth = $selectedMonth->copy()->startOfMonth();
        $endOfMonth = $selectedMonth->copy()->endOfMonth();
        $startCalendar = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);
        $endCalendar = $endOfMonth->copy()->endOfWeek(Carbon::SUNDAY);

        $days = [];
        for ($date = $startCalendar->copy(); $date->lte($endCalendar); $date->addDay()) {
            $days[] = $date->copy();
        }

        $enrollments = Enrollment::with(['training.course', 'training.assessments', 'training.tasks'])
            ->where('student_id', $studentId)
            ->get();

        $events = [];
        foreach ($enrollments as $enrollment) {
            $training = $enrollment->training;
            if (! $training) {
                continue;
            }

            foreach ($training->assessments as $assessment) {
                $dateKey = $assessment->end_date->format('Y-m-d');
                $events[$dateKey][] = [
                    'type' => 'assessment',
                    'title' => $assessment->title,
                    'training' => $training->course->title,
                    'range' => $assessment->start_date->format('d/m/Y') . ' → ' . $assessment->end_date->format('d/m/Y'),
                    'status' => $assessment->end_date->isToday() ? 'Vence hoy' : 'En curso',
                    'url' => route('student.courses.show', $training->training_id) . '?tab=contenido',
                ];
            }

            foreach ($training->tasks as $task) {
                if (! $task->due_date) {
                    continue;
                }

                $dateKey = $task->due_date->toDateString();
                $events[$dateKey][] = [
                    'type' => 'task',
                    'title' => $task->title,
                    'training' => $training->course->title,
                    'range' => 'Entrega: ' . $task->due_date->format('d/m/Y H:i'),
                    'status' => $task->due_date->isToday() ? 'Vence hoy' : ($task->due_date->isPast() ? 'Atrasada' : 'Pendiente'),
                    'url' => route('student.courses.show', ['id' => $training->training_id]) . '?tab=tareas#task-' . $task->task_id,
                ];
            }
        }

        $calendar = array_chunk($days, 7);
        $today = Carbon::today();

        return view('teacher.calendar', compact('fullName', 'selectedMonth', 'calendar', 'events', 'today'));
    }

    private function validateAssessmentAvailability(Assessment $assessment)
    {
        if (! $assessment->active) {
            abort(403, 'Esta evaluación no está disponible.');
        }

        $today = Carbon::today();

        // Asegurarse de comparar objetos Carbon para evitar comparaciones tipo cadena
        $start = $assessment->start_date instanceof Carbon ? $assessment->start_date->copy()->startOfDay() : Carbon::createFromFormat('Y-m-d', $assessment->start_date)->startOfDay();
        $end = $assessment->end_date instanceof Carbon ? $assessment->end_date->copy()->endOfDay() : Carbon::createFromFormat('Y-m-d', $assessment->end_date)->endOfDay();

        if ($today->lt($start) || $today->gt($end)) {
            abort(403, 'Esta evaluación está fuera de las fechas permitidas.');
        }
    }

    private function ensureAttemptAllowed(Assessment $assessment, Enrollment $enrollment, AssessmentAttempt $currentAttempt = null)
    {
        $attemptQuery = AssessmentAttempt::where('enrollment_id', $enrollment->enrollment_id)
            ->where('assessment_id', $assessment->assessment_id);

        if ($currentAttempt) {
            $attemptQuery->where('attempt_id', '!=', $currentAttempt->attempt_id);
        }

        $previousAttempts = $attemptQuery->count();

        if ($previousAttempts >= $assessment->allowed_attempts) {
            abort(403, 'Ha alcanzado el número máximo de intentos permitidos.');
        }
    }
}