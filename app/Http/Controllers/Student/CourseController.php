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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function show($id)
    {
        $studentId = auth()->id();

        $training = Training::with([
            'course.specialty',
            'teacher.person',
            'assessments',
            'tasks',
            'contents.training',
            'announcements',
            'enrollments.student.person',
        ])
            ->where('training_id', $id)
            ->firstOrFail();

        $course = $training->course;
        $isEnrolled = Enrollment::where('student_id', $studentId)
            ->where('training_id', $id)
            ->exists();

        if ($isEnrolled) {
            $enrollment = Enrollment::where('student_id', $studentId)
                ->where('training_id', $id)
                ->first();

            $attendances = Attendance::where('enrollment_id', $enrollment->enrollment_id)
                ->with('schedule')
                ->get();
            $attempts = AssessmentAttempt::where('enrollment_id', $enrollment->enrollment_id)
                ->with('assessment')
                ->orderByDesc('created_at')
                ->get();
            $taskIds = $training->tasks->pluck('task_id')->all();
            $submissions = TaskSubmission::where('student_id', $studentId)
                ->whereIn('task_id', $taskIds)
                ->orderByDesc('submitted_at')
                ->get()
                ->unique('task_id')
                ->keyBy('task_id');
            $averageGrade = $enrollment->calculateAverage();

            return view('student.courses.show', compact(
                'training',
                'course',
                'isEnrolled',
                'attendances',
                'attempts',
                'submissions',
                'averageGrade'
            ));
        }

        return view('student.courses.detail', compact('training', 'course', 'isEnrolled'));
    }

    public function takeExam(Request $request, $assessment_id)
    {
        $studentId = auth()->id();

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
            ->whereNull('submitted_at')
            ->latest('attempt_id')
            ->first();

        if (! $pendingAttempt) {
            $attemptsUsed = AssessmentAttempt::where('enrollment_id', $enrollment->enrollment_id)
                ->where('assessment_id', $assessment_id)
                ->count();

            if ($attemptsUsed >= $assessment->allowed_attempts) {
                return redirect()->route('student.courses.show', $assessment->training_id)
                    ->with('error', 'Ha alcanzado el número máximo de intentos permitidos para esta evaluación.');
            }
        }

        $attempt = null;
        $timerStarted = false;

        if ($pendingAttempt) {
            $attempt = $pendingAttempt;
            $timerStarted = true;
        } elseif ($request->query('start') === '1') {
            try {
                $this->ensureAttemptAllowed($assessment, $enrollment);
            } catch (HttpException $e) {
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

        if ($attempt) {
            $assessment = $this->shuffleQuestionsForAttempt($assessment, $attempt->attempt_id);
        }

        $totalSeconds = max(0, ($assessment->time_limit ?: 60) * 60);
        $attemptCreatedTs = $attempt ? $attempt->created_at->timestamp : null;
        $serverNowTs = Carbon::now()->timestamp;
        $startUrl = route('student.assessment.take', ['id' => $assessment_id]).'?start=1';

        return view('student.courses.take', compact('assessment', 'totalSeconds', 'attemptCreatedTs', 'serverNowTs', 'enrollment', 'attempt', 'timerStarted', 'startUrl'));
    }

    private function shuffleQuestionsForAttempt(Assessment $assessment, int $attemptId): Assessment
    {
        $sessionKey = "assessment_question_order.{$attemptId}";
        $questionIds = session($sessionKey);

        if (! is_array($questionIds) || empty($questionIds)) {
            $questionIds = $assessment->questions->pluck('question_id')->shuffle()->values()->all();
            session([$sessionKey => $questionIds]);
        }

        $orderedQuestions = collect($questionIds)
            ->map(fn ($questionId) => $assessment->questions->firstWhere('question_id', $questionId))
            ->filter()
            ->values();

        $remainingQuestions = $assessment->questions
            ->reject(fn ($question) => in_array($question->question_id, $questionIds))
            ->values();

        $assessment->setRelation('questions', $orderedQuestions->concat($remainingQuestions)->values());

        return $assessment;
    }

    public function submitExam(Request $request, $assessment_id)
    {
        $studentId = auth()->id();

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

        $validated = $request->validate([
            'attempt_id' => 'required|integer|exists:assessment_attempts,attempt_id',
            'answers' => 'nullable|array',
            'answers.*' => 'nullable|integer|exists:alternatives,option_id',
        ]);

        $attempt = AssessmentAttempt::where('attempt_id', $validated['attempt_id'])
            ->where('enrollment_id', $enrollment->enrollment_id)
            ->where('assessment_id', $assessment_id)
            ->whereNull('submitted_at')
            ->firstOrFail();

        $timeLimit = $assessment->time_limit;
        $elapsedSeconds = Carbon::now()->diffInSeconds($attempt->created_at);
        $maxSeconds = ($timeLimit * 60) + 120;

        if ($elapsedSeconds > $maxSeconds) {
            $attempt->score = 0;
            $attempt->submitted_at = Carbon::now();
            $attempt->timestamps = false;
            $attempt->save();
            $attempt->load('assessment');

            return view('student.assessments.result', compact('attempt'));
        }

        $totalScore = 0;
        $responses = $request->input('answers', []);

        foreach ($assessment->questions as $question) {
            $selectedOptionId = $responses[$question->question_id] ?? null;

            if ($selectedOptionId) {
                $selectedOption = $question->alternatives->firstWhere('option_id', $selectedOptionId);

                if ($selectedOption && $selectedOption->is_correct) {
                    $totalScore += $question->score;
                }
            }
        }

        $attempt->score = $totalScore;
        $attempt->submitted_at = Carbon::now();
        $attempt->timestamps = false;
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
            } catch (Throwable) {
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
        $fullName = trim(($person->last_names ?? '').' '.($person->first_names ?? '')) ?: ($user->username ?? 'Estudiante');

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

        $hasEnrollments = $enrollments->isNotEmpty();
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
                    'range' => $assessment->start_date->format('d/m/Y').' → '.$assessment->end_date->format('d/m/Y'),
                    'status' => $assessment->end_date->isToday() ? 'Vence hoy' : 'En curso',
                    'url' => route('student.courses.show', $training->training_id).'?tab=contenido',
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
                    'range' => 'Entrega: '.$task->due_date->format('d/m/Y H:i'),
                    'status' => $task->due_date->isToday() ? 'Vence hoy' : ($task->due_date->isPast() ? 'Atrasada' : 'Pendiente'),
                    'url' => route('student.courses.show', ['id' => $training->training_id]).'?tab=tareas#task-'.$task->task_id,
                ];
            }
        }

        $calendar = array_chunk($days, 7);
        $today = Carbon::today();

        return view('teacher.calendar', compact('fullName', 'selectedMonth', 'calendar', 'events', 'today', 'hasEnrollments'));
    }

    private function validateAssessmentAvailability(Assessment $assessment)
    {
        if (! $assessment->isAvailableOnDate()) {
            abort(403, 'Esta evaluación no está disponible.');
        }
    }

    private function ensureAttemptAllowed(Assessment $assessment, Enrollment $enrollment, ?AssessmentAttempt $currentAttempt = null)
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

    public function previewCertificate($trainingId)
    {
        $studentId = auth()->id();

        $enrollment = Enrollment::with([
            'student.person',
            'training.course.specialty'
        ])
        ->where('student_id', $studentId)
        ->where('training_id', $trainingId)
        ->firstOrFail();

        $averageGrade = $enrollment->calculateAverage();

        if (! $enrollment->canReceiveCertificate()) {
            return redirect()->back()->with('error', 'No cumples con la nota mínima (13) para obtener el certificado. Tu nota actual es: ' . $averageGrade);
        }

        $certificateCode = 'SYS-' . str_pad($enrollment->enrollment_id, 6, '0', STR_PAD_LEFT);
        $verificationUrl = route('certificate.verify', ['code' => $certificateCode]);

        $qrCode = QrCode::format('svg')
            ->size(110)
            ->margin(1)
            ->generate($verificationUrl);

        $bgAnverso  = public_path('images/certificado-bg.jpg');
        $bgReverso  = public_path('images/certificado-reverso-bg.jpg');

        return view('student.certificates.preview', compact(
            'enrollment',
            'averageGrade',
            'certificateCode',
            'qrCode',
            'bgAnverso',
            'bgReverso',
            'trainingId'
        ));
    }

    public function downloadCertificate($trainingId)
    {
        $studentId = auth()->id();

        $enrollment = Enrollment::with([
            'student.person',
            'training.course.specialty'
        ])
        ->where('student_id', $studentId)
        ->where('training_id', $trainingId)
        ->firstOrFail();

        $averageGrade = $enrollment->calculateAverage();

        if (! $enrollment->canReceiveCertificate()) {
            return redirect()->back()->with('error', 'El curso no ha sido aprobado con la nota mínima requerida (13) para obtener el certificado. Tu nota promedio es: ' . $averageGrade);
        }

        // Generate certificate code: e.g. SYS-000042
        $certificateCode = 'SYS-' . str_pad($enrollment->enrollment_id, 6, '0', STR_PAD_LEFT);

        // Verification URL
        $verificationUrl = route('certificate.verify', ['code' => $certificateCode]);

        // Generate QR code as SVG
        $qrCode = QrCode::format('svg')
            ->size(110)
            ->margin(1)
            ->generate($verificationUrl);

        // Image backgrounds
        $bgAnverso = public_path('images/certificado-bg.jpg');
        $bgReverso = public_path('images/certificado-reverso-bg.jpg');

        $pdf = Pdf::loadView('student.certificates.template', compact(
            'enrollment',
            'averageGrade',
            'certificateCode',
            'qrCode',
            'bgAnverso',
            'bgReverso'
        ));

        // Configure PDF
        $pdf->setPaper('a4', 'landscape');
        $pdf->setWarnings(false);

        return $pdf->download('Certificado-' . Str::slug($enrollment->training->course->title) . '.pdf');
    }

    public function verifyCertificate($code)
    {
        try {
            // Parse code: e.g. SYS-000042
            $cleanCode = str_replace('SYS-', '', $code);
            $enrollmentId = (int) $cleanCode;

            $enrollment = Enrollment::with([
                'student.person',
                'training.course.specialty'
            ])->findOrFail($enrollmentId);

            $averageGrade = $enrollment->calculateAverage();

            if (! $enrollment->canReceiveCertificate()) {
                return view('student.certificates.verify_error', [
                    'message' => 'Este certificado no es válido porque el alumno no alcanzó la nota aprobatoria.'
                ]);
            }

            return view('student.certificates.verify', compact('enrollment', 'averageGrade', 'code'));
        } catch (Throwable $e) {
            return view('student.certificates.verify_error', [
                'message' => 'El código de certificado proporcionado no es válido o no existe.'
            ]);
        }
    }
}
