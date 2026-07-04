<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Assessment;
use App\Models\AssessmentAttempt;
use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\Module;
use App\Models\Schedule;
use App\Models\Task;
use App\Models\Training;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    private function isAdministrator(): bool
    {
        return auth()->user()?->roles->contains('name', 'Administrator') ?? false;
    }

    public function dashboard()
    {
        $user = auth()->user();

        $totalStudents = Enrollment::whereHas('training', fn ($q) => $q->where('teacher_id', $user->user_id))->count();
        $totalActiveTrainings = Training::where('teacher_id', $user->user_id)
            ->where('status', Training::STATUS_ACTIVE)
            ->count();
        $totalTasks = Task::whereHas('training', fn ($q) => $q->where('teacher_id', $user->user_id))->count();

        $totalAttempts = AssessmentAttempt::whereHas('assessment.training', fn ($q) => $q->where('teacher_id', $user->user_id))->count();
        $averageScore = AssessmentAttempt::whereHas('assessment.training', fn ($q) => $q->where('teacher_id', $user->user_id))
            ->whereNotNull('submitted_at')
            ->avg('score');
        $averageScore = $averageScore !== null ? round($averageScore, 2) : 0;

        $recentActivities = Assessment::with('training.course')
            ->whereHas('training', fn ($q) => $q->where('teacher_id', $user->user_id))
            ->latest('created_at')
            ->take(10)
            ->get();

        // Traemos los trainings del docente para poblar los modals de filtrado
        $trainings = $user->trainings()->with('course')->get();

        return view('teacher.dashboard', compact(
            'totalStudents',
            'totalActiveTrainings',
            'totalTasks',
            'totalAttempts',
            'averageScore',
            'recentActivities',
            'trainings'
        ));
    }

    public function courses(Request $request)
    {
        $user = auth()->user();

        $query = Training::with('course')
            ->when(! $this->isAdministrator(), fn ($query) => $query->where('teacher_id', $user->user_id));

        // Soportar filtrado por estado: ?status=A para activos
        if ($request->query('status')) {
            $status = strtoupper($request->query('status'));
            $status = match ($status) {
                'A', 'ACTIVE' => 1,
                'C', 'CLOSED', '0' => 0,
                default => $status,
            };

            $query->where('status', $status);
        }

        $trainings = $query->get();

        return view('teacher.courses.index', compact('trainings'));
    }

    public function uploadBanner(Request $request, $training_id)
    {
        $request->validate([
            'banner' => 'required|image|mimes:jpg,png|max:2048',
        ]);

        $user = auth()->user();

        $training = Training::with('course')
            ->where('training_id', $training_id)
            ->when(! $this->isAdministrator(), fn ($query) => $query->where('teacher_id', $user->user_id))
            ->firstOrFail();

        $course = $training->course;

        if (! $course) {
            return back()->with('error', 'No se encontró el curso asociado.');
        }

        if ($course->banner_path) {
            Storage::disk('public')->delete($course->banner_path);
        }

        $path = $request->file('banner')->store('course-banners', 'public');
        $course->update(['banner_path' => $path]);

        return back()->with('success', 'Banner subido correctamente.');
    }

    public function calendar(Request $request)
    {
        $user = auth()->user();
        $person = $user->person;
        $fullName = trim(($person->last_names ?? '').' '.($person->first_names ?? '')) ?: ($user->username ?? 'Docente');

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

        $trainings = Training::with(['course', 'assessments', 'tasks'])
            ->when(! $this->isAdministrator(), fn ($query) => $query->where('teacher_id', $user->user_id))
            ->get();

        $events = [];
        foreach ($trainings as $training) {
            foreach ($training->assessments as $assessment) {
                $dateKey = $assessment->end_date->format('Y-m-d');
                $events[$dateKey][] = [
                    'type' => 'assessment',
                    'title' => $assessment->title,
                    'training' => $training->course->title,
                    'range' => $assessment->start_date->format('d/m/Y').' → '.$assessment->end_date->format('d/m/Y'),
                    'status' => $assessment->end_date->isToday() ? 'Vence hoy' : 'En curso',
                    'url' => route('teacher.assessments.show', $assessment->assessment_id),
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
                    'url' => route('teacher.tasks.submissions', $task->task_id),
                ];
            }
        }

        $calendar = array_chunk($days, 7);
        $today = Carbon::today();

        return view('teacher.calendar', compact(
            'fullName',
            'selectedMonth',
            'calendar',
            'events',
            'today'
        ));
    }

    public function show($id)
    {
        $user = auth()->user();

        // Cargamos el training con sus evaluaciones y las relaciones necesarias para las notas
        $training = Training::with([
            'course',
            'schedules',
            'assessments.attempts.enrollment', // Trae los intentos globales de las evaluaciones de este curso y su enrollment
            'tasks',
            'enrollments.student.person',
            'announcements',
        ])
            ->where('training_id', $id)
            ->when(! $this->isAdministrator(), fn ($query) => $query->where('teacher_id', $user->user_id))
            ->firstOrFail();

        $totalStudents = $training->enrollments->count();
        $totalAssessments = $training->assessments->count();
        $totalAttendanceRecords = Attendance::whereHas('schedule', fn ($q) => $q->where('training_id', $id))->count();
        $modules = Module::where('course_id', $training->course_id)->where('is_active', true)->orderBy('order')->get();

        // Obtenemos los estudiantes directamente desde las inscripciones cargadas
        $students = $training->enrollments;

        return view('teacher.courses.show', compact(
            'training',
            'totalStudents',
            'totalAssessments',
            'totalAttendanceRecords',
            'students',
            'modules'
        ));
    }

    public function report($id)
    {
        $user = auth()->user();

        $training = Training::with([
            'course',
            'teacher.person',
            'enrollments.student.person',
            'assessments.attempts.enrollment',
            'tasks.submissions',
        ])
            ->where('training_id', $id)
            ->when(! $this->isAdministrator(), fn ($query) => $query->where('teacher_id', $user->user_id))
            ->firstOrFail();

        $totalStudents = $training->enrollments->count();
        $totalAssessments = $training->assessments->count();
        $totalAttendanceRecords = Attendance::whereHas('schedule', fn ($q) => $q->where('training_id', $id))->count();
        $students = $training->enrollments;

        return view('teacher.courses.report', compact(
            'training',
            'students',
            'totalStudents',
            'totalAssessments',
            'totalAttendanceRecords'
        ));
    }

    public function reportAttendance($id)
    {
        $user = auth()->user();

        $training = Training::with([
            'course',
            'teacher.person',
            'enrollments.student.person',
        ])
            ->where('training_id', $id)
            ->when(! $this->isAdministrator(), fn ($query) => $query->where('teacher_id', $user->user_id))
            ->firstOrFail();

        $schedules = Schedule::where('training_id', $training->training_id)
            ->orderBy('date')
            ->with(['attendances.enrollment.student.person'])
            ->get();

        $attendanceMap = [];
        foreach ($schedules as $schedule) {
            foreach ($schedule->attendances as $attendance) {
                $attendanceMap[$attendance->enrollment_id][$schedule->schedule_id] = $attendance;
            }
        }

        $attendanceSummary = [];
        $totalSchedules = $schedules->count();

        foreach ($training->enrollments as $enrollment) {
            $counts = [
                'present' => 0,
                'absent' => 0,
                'justified' => 0,
                'late' => 0,
            ];

            foreach ($schedules as $schedule) {
                $attendance = $attendanceMap[$enrollment->enrollment_id][$schedule->schedule_id] ?? null;
                $status = $attendance ? ($attendance->attendance_status ?? $attendance->attendance) : null;
                $status = is_string($status) ? strtolower($status) : null;

                $normalizedStatus = match ($status) {
                    'p', 'present' => 'present',
                    'a', 'absent' => 'absent',
                    'j', 'justified' => 'justified',
                    't', 'late' => 'late',
                    default => null,
                };

                if (isset($counts[$normalizedStatus])) {
                    $counts[$normalizedStatus]++;
                }
            }

            $attendanceSummary[$enrollment->enrollment_id] = [
                'counts' => $counts,
                'percentages' => collect($counts)
                    ->map(function ($count) use ($totalSchedules) {
                        if ($totalSchedules === 0) {
                            return 0;
                        }

                        return round(($count / $totalSchedules) * 100, 1);
                    })
                    ->all(),
            ];
        }

        $totalAttendanceRecords = $training->attendances()->count();

        return view('teacher.courses.report-attendance', compact(
            'training',
            'schedules',
            'attendanceMap',
            'attendanceSummary',
            'totalSchedules',
            'totalAttendanceRecords'
        ));
    }

    public function storeAnnouncement(Request $request, $id)
    {
        $user = auth()->user();

        $training = Training::where('training_id', $id)
            ->when(! $this->isAdministrator(), fn ($query) => $query->where('teacher_id', $user->user_id))
            ->firstOrFail();

        $validated = $request->validate([
            'content' => 'required|string|max:3000',
            'link' => 'nullable|url|max:255',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt,ppt,pptx,zip',
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if (! $file->isValid()) {
                    continue;
                }
                $path = $file->store('course-announcements/'.$training->training_id, 'public');
                $attachments[] = [
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                    'mime' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ];
            }
        }

        Announcement::create([
            'training_id' => $training->training_id,
            'teacher_id' => $training->teacher_id,
            'content' => $validated['content'],
            'link' => $validated['link'] ?? null,
            'attachments' => $attachments ?: null,
        ]);

        return redirect()->route('teacher.courses.show', ['id' => $training->training_id, 'tab' => 'anuncios'])
            ->with('success', 'Anuncio publicado correctamente.');
    }

    public function students($id)
    {
        $user = auth()->user();

        $training = Training::with('course')
            ->where('training_id', $id)
            ->when(! $this->isAdministrator(), fn ($query) => $query->where('teacher_id', $user->user_id))
            ->firstOrFail();

        $students = Enrollment::with([
            'student.person',
            'progress',
            'training.contents',
            'training.tasks',
            'training.assessments',
        ])
            ->where('training_id', $id)
            ->get();

        return view('teacher.courses.students', compact('training', 'students'));
    }

    public function attendance($id)
    {
        $user = auth()->user();

        $training = Training::with([
            'course',
            'enrollments.student.person',
        ])
            ->where('training_id', $id)
            ->when(! $this->isAdministrator(), fn ($query) => $query->where('teacher_id', $user->user_id))
            ->firstOrFail();

        $students = $training->enrollments;

        return view('teacher.attendance', compact('training', 'students'));
    }

    /**
     * Devuelve via JSON los estudiantes inscritos en un training (para modal AJAX).
     */
    public function ajaxStudents($id)
    {
        $user = auth()->user();

        $training = Training::with(['enrollments.student.person'])
            ->where('training_id', $id)
            ->when(! $this->isAdministrator(), fn ($query) => $query->where('teacher_id', $user->user_id))
            ->firstOrFail();

        $students = $training->enrollments->map(function ($enrollment) {
            $student = $enrollment->student;
            $person = $student->person ?? null;

            // Los campos en la tabla people son first_names y last_names
            $first = $person->first_names ?? '';
            $last = $person->last_names ?? '';
            $name = trim($first.' '.$last);

            return [
                'enrollment_id' => $enrollment->enrollment_id ?? null,
                'student_id' => $student->user_id ?? null,
                'name' => $name !== '' ? $name : ($student->username ?? 'N/A'),
                'email' => $person->email ?? null,
            ];
        });

        return response()->json(['data' => $students]);
    }

    /**
     * Devuelve via JSON los promedios de las evaluaciones del training (para modal AJAX).
     */
    public function ajaxAverages($id)
    {
        $user = auth()->user();

        $training = Training::where('training_id', $id)
            ->when(! $this->isAdministrator(), fn ($query) => $query->where('teacher_id', $user->user_id))
            ->firstOrFail();

        $assessments = Assessment::where('training_id', $id)
            ->withCount(['attempts as attempts_count'])
            ->with('attempts')
            ->get()
            ->map(function ($a) {
                return [
                    'assessment_id' => $a->assessment_id,
                    'title' => $a->title,
                    'attempts' => $a->attempts_count ?? 0,
                    'average' => $a->averageSubmittedScore(),
                    'active' => (bool) $a->active,
                ];
            });

        return response()->json(['data' => $assessments]);
    }

    public function createTask($training_id)
    {
        $user = auth()->user();

        $training = Training::with('course')
            ->where('training_id', $training_id)
            ->when(! $this->isAdministrator(), fn ($query) => $query->where('teacher_id', $user->user_id))
            ->firstOrFail();

        $modules = Module::where('course_id', $training->course_id)->where('is_active', true)->orderBy('order')->get();

        return view('teacher.tasks.create', compact('training', 'modules'));
    }

    public function storeTask(Request $request)
    {
        $request->validate([
            'training_id' => 'required|exists:trainings,training_id',
            'module_id' => 'required|exists:modules,module_id',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'delivery_date' => 'required|date|after_or_equal:today',
        ]);

        $user = auth()->user();

        $training = Training::where('training_id', $request->training_id)
            ->when(! $this->isAdministrator(), fn ($query) => $query->where('teacher_id', $user->user_id))
            ->first();

        if (! $training) {
            abort(403, 'No autorizado: Este training no te pertenece.');
        }

        Task::create([
            'training_id' => $request->training_id,
            'module_id' => $request->module_id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => Carbon::parse($request->delivery_date)->endOfDay(),
        ]);

        return redirect()->route('teacher.courses.show', $request->training_id)
            ->with('success', 'Tarea creada correctamente.');
    }
}
