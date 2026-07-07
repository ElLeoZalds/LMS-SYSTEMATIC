<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Assessment;
use App\Models\AssessmentAttempt;
use App\Models\Attendance;
use App\Models\Content;
use App\Models\Enrollment;
use App\Models\Module;
use App\Models\Schedule;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\Training;
use App\Notifications\NewAnnouncementNotification;
use App\Support\ModuleSelectorHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
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

        $activeTrainings = Training::with(['course', 'schedules', 'enrollments'])
            ->where('teacher_id', $user->user_id)
            ->where('status', Training::STATUS_ACTIVE)
            ->orderByDesc('start_date')
            ->get();

        $pendingTasks = Task::with(['training.course', 'submissions'])
            ->whereHas('training', fn ($q) => $q->where('teacher_id', $user->user_id))
            ->whereNotNull('due_date')
            ->where('due_date', '<=', now()->addDays(7)->endOfDay())
            ->get()
            ->filter(fn ($task) => $task->submissions->contains(fn ($submission) => is_null($submission->grade)));

        $upcomingAssessments = Assessment::with(['training.course'])
            ->whereHas('training', fn ($q) => $q->where('teacher_id', $user->user_id))
            ->where('active', true)
            ->where(function ($query) {
                $query->whereBetween('start_date', [now()->startOfDay(), now()->addDays(7)->endOfDay()])
                    ->orWhereBetween('end_date', [now()->startOfDay(), now()->addDays(7)->endOfDay()]);
            })
            ->orderBy('start_date')
            ->get();

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
            'activeTrainings',
            'pendingTasks',
            'upcomingAssessments',
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

    public function storeContent(Request $request)
    {
        $validated = $request->validate([
            'training_id' => 'required|exists:trainings,training_id',
            'module_id' => 'required|exists:modules,id',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
            'attachment' => 'nullable|file|max:20480',
        ]);

        $training = Training::where('training_id', $validated['training_id'])->firstOrFail();

        if ($training->isFinished()) {
            return back()->with('error', 'No se pueden realizar cambios en una capacitación finalizada.');
        }

        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('course-contents', 'public');
        }

        Content::create([
            'training_id' => $validated['training_id'],
            'module_id' => $validated['module_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'type' => $request->input('type', 'Lectura'),
            'order_index' => Content::where('module_id', $validated['module_id'])->count() + 1,
            'file_path' => $path,
        ]);

        return back()->with('success', 'Contenido creado correctamente.');
    }

    public function updateContent(Request $request, $contentId)
    {
        $content = Content::findOrFail($contentId);

        $validated = $request->validate([
            'training_id' => 'required|exists:trainings,training_id',
            'module_id' => 'required|exists:modules,id',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
            'attachment' => 'nullable|file|max:20480',
        ]);

        $training = Training::where('training_id', $validated['training_id'])->firstOrFail();

        if ($training->isFinished()) {
            return back()->with('error', 'No se pueden realizar cambios en una capacitación finalizada.');
        }

        $path = $content->file_path;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('course-contents', 'public');
        }

        $content->update([
            'training_id' => $validated['training_id'],
            'module_id' => $validated['module_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'type' => $request->input('type', $content->type ?? 'Lectura'),
            'file_path' => $path,
        ]);

        return back()->with('success', 'Contenido actualizado correctamente.');
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

        $training = Training::with([
            'course.specialty',
            'schedules',
            'enrollments.student.person',
            'announcements' => fn ($query) => $query->latest('created_at')->take(3),
            'tasks.submissions',
            'assessments',
            'contents',
        ])
            ->where('training_id', $id)
            ->when(! $this->isAdministrator(), fn ($query) => $query->where('teacher_id', $user->user_id))
            ->firstOrFail();

        $totalStudents = $training->enrollments->count();
        $totalAssessments = $training->assessments()->count();
        $totalAttendanceRecords = Attendance::whereHas('schedule', fn ($q) => $q->where('training_id', $id))->count();

        $upcomingSchedules = $training->schedules()
            ->whereDate('date', '>=', Carbon::today())
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $pendingReviewsCount = 0;
        foreach ($training->tasks as $task) {
            $pendingReviewsCount += $task->submissions->whereNull('grade')->count();
        }
        $activeAssessmentsCount = $training->assessments->filter(fn ($assessment) => $assessment->active
            && (! $assessment->start_date || $assessment->start_date->lte(Carbon::today()))
            && (! $assessment->end_date || $assessment->end_date->gte(Carbon::today())))
            ->count();

        $attendanceSummary = [
            'present' => 0,
            'absent' => 0,
            'late' => 0,
            'justified' => 0,
        ];

        foreach (Attendance::whereHas('schedule', fn ($q) => $q->where('training_id', $id))->get() as $attendance) {
            $status = strtolower((string) ($attendance->attendance_status ?? ''));

            if ($status === 'present' || $status === 'p') {
                $attendanceSummary['present']++;
            } elseif ($status === 'absent' || $status === 'a') {
                $attendanceSummary['absent']++;
            } elseif ($status === 'late' || $status === 't') {
                $attendanceSummary['late']++;
            } elseif ($status === 'justified' || $status === 'j') {
                $attendanceSummary['justified']++;
            }
        }

        $latestAnnouncements = $training->announcements;

        $studentStatusFilter = request('status', 'all');
        $studentSearchFilter = trim((string) request('search', ''));
        $activeStudentFiltersCount = 0;

        if ($studentStatusFilter !== 'all') {
            $activeStudentFiltersCount++;
        }

        if ($studentSearchFilter !== '') {
            $activeStudentFiltersCount++;
        }

        $students = $training->enrollments->filter(function ($enrollment) use ($studentStatusFilter, $studentSearchFilter) {
            $isActive = strtoupper((string) $enrollment->status) === Enrollment::STATUS_ACTIVE;
            $person = $enrollment->student?->person;
            $fullName = trim(($person->first_names ?? '').' '.($person->last_names ?? ''));
            $document = (string) ($person->document_number ?? '');
            $search = mb_strtolower($studentSearchFilter);

            if ($studentStatusFilter === 'active' && ! $isActive) {
                return false;
            }

            if ($studentStatusFilter === 'inactive' && $isActive) {
                return false;
            }

            if ($search !== ''
                && ! str_contains(mb_strtolower($fullName), $search)
                && ! str_contains(mb_strtolower($document), $search)) {
                return false;
            }

            return true;
        })->values();

        $moduleId = (int) request('module_id', 0);
        $modules = Module::where('course_id', $training->course_id)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        ['modules' => $modules] = ModuleSelectorHelper::annotate($modules, $training);

        foreach ($modules as $module) {
            $module->module_id = $module->id;
            $module->name = $module->title;
            $module->contents = $module->contents()->orderBy('order_index')->get();
            $module->assessments = $module->assessments()->with(['questions', 'attempts'])->orderBy('start_date')->orderBy('end_date')->get();
            $module->tasks = $module->tasks()->with(['submissions'])->orderBy('due_date')->get();
            $module->contents_count = $module->contents->count();
            $module->assessments_count = $module->assessments->count();
            $module->tasks_count = $module->tasks->count();
        }

        $selectedModule = $modules->firstWhere('id', $moduleId) ?? $modules->first();
        $moduleId = $selectedModule?->id;

        $gradebook = $this->buildGradebookData($training, $selectedModule);

        return view('teacher.courses.show', compact(
            'training',
            'totalStudents',
            'totalAssessments',
            'totalAttendanceRecords',
            'upcomingSchedules',
            'pendingReviewsCount',
            'activeAssessmentsCount',
            'attendanceSummary',
            'latestAnnouncements',
            'students',
            'studentStatusFilter',
            'studentSearchFilter',
            'activeStudentFiltersCount',
            'modules',
            'selectedModule',
            'moduleId',
            'gradebook'
        ));
    }

    public function exportGradebook($trainingId, $moduleId = null)
    {
        $user = auth()->user();

        $training = Training::with(['course', 'enrollments.student.person'])
            ->where('training_id', $trainingId)
            ->when(! $this->isAdministrator(), fn ($query) => $query->where('teacher_id', $user->user_id))
            ->firstOrFail();

        $modules = Module::where('course_id', $training->course_id)
            ->where('is_active', true)
            ->orderBy('order')
            ->with(['tasks', 'assessments'])
            ->get();

        $selectedModule = $modules->firstWhere('id', (int) $moduleId) ?? $modules->first();
        $gradebook = $this->buildGradebookData($training, $selectedModule);

        $pdf = Pdf::loadView('teacher.courses.gradebook-pdf', [
            'training' => $training,
            'selectedModule' => $selectedModule,
            'gradebook' => $gradebook,
        ]);

        return $pdf->download('gradebook-'.$training->training_id.'.pdf');
    }

    private function buildGradebookData(Training $training, ?Module $selectedModule = null): array
    {
        $tasks = $selectedModule?->tasks ?? collect();
        $assessments = $selectedModule?->assessments ?? collect();

        $taskIds = $tasks->pluck('task_id')->filter()->all();
        $assessmentIds = $assessments->pluck('assessment_id')->filter()->all();
        $enrollmentIds = $training->enrollments->pluck('enrollment_id')->filter()->all();
        $studentIds = $training->enrollments->pluck('student_id')->filter()->all();

        $submissionMap = [];
        if (! empty($taskIds) && ! empty($studentIds)) {
            $submissions = TaskSubmission::whereIn('task_id', $taskIds)
                ->whereIn('student_id', $studentIds)
                ->get();

            foreach ($submissions as $submission) {
                $key = $submission->task_id.'-'.$submission->student_id;
                $current = $submissionMap[$key] ?? null;

                if (! $current) {
                    $submissionMap[$key] = $submission;
                    continue;
                }

                if ($submission->submitted_at && $current->submitted_at && $submission->submitted_at->gt($current->submitted_at)) {
                    $submissionMap[$key] = $submission;
                }
            }
        }

        $attemptMap = [];
        if (! empty($assessmentIds) && ! empty($enrollmentIds)) {
            $attempts = AssessmentAttempt::whereIn('assessment_id', $assessmentIds)
                ->whereIn('enrollment_id', $enrollmentIds)
                ->submitted()
                ->get();

            foreach ($attempts as $attempt) {
                $key = $attempt->assessment_id.'-'.$attempt->enrollment_id;
                $current = $attemptMap[$key] ?? null;

                if (! $current) {
                    $attemptMap[$key] = $attempt;
                    continue;
                }

                $currentSubmitted = $current->submitted_at ?? $current->created_at;
                $attemptSubmitted = $attempt->submitted_at ?? $attempt->created_at;

                if ($attemptSubmitted && $currentSubmitted && $attemptSubmitted->gt($currentSubmitted)) {
                    $attemptMap[$key] = $attempt;
                }
            }
        }

        $rows = [];
        foreach ($training->enrollments as $enrollment) {
            $student = $enrollment->student;
            $totalNotes = 0;
            $notesCount = 0;
            $cells = [];

            foreach ($tasks as $task) {
                $submission = $submissionMap[$task->task_id.'-'.$student->user_id] ?? null;
                $grade = $submission?->grade;

                if (! is_null($grade)) {
                    $totalNotes += (float) $grade;
                    $notesCount++;
                }

                $cells[] = [
                    'type' => 'task',
                    'label' => $task->title,
                    'value' => $grade,
                    'submission' => $submission,
                ];
            }

            foreach ($assessments as $assessment) {
                $attempt = $attemptMap[$assessment->assessment_id.'-'.$enrollment->enrollment_id] ?? null;
                $score = $attempt?->score;

                if (! is_null($score)) {
                    $totalNotes += (float) $score;
                    $notesCount++;
                }

                $cells[] = [
                    'type' => 'assessment',
                    'label' => $assessment->title,
                    'value' => $score,
                    'attempt' => $attempt,
                ];
            }

            $average = $notesCount > 0 ? round($totalNotes / $notesCount, 1) : null;

            $rows[] = [
                'enrollment' => $enrollment,
                'student' => $student,
                'cells' => $cells,
                'average' => $average,
            ];
        }

        return [
            'tasks' => $tasks,
            'assessments' => $assessments,
            'rows' => $rows,
        ];
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

        if ($training->isFinished()) {
            return back()->with('error', 'No se pueden realizar cambios en una capacitación finalizada.');
        }

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

        $announcement = Announcement::create([
            'training_id' => $training->training_id,
            'teacher_id' => $training->teacher_id,
            'content' => $validated['content'],
            'link' => $validated['link'] ?? null,
            'attachments' => $attachments ?: null,
        ]);

        $students = Enrollment::where('training_id', $training->training_id)
            ->with('student')
            ->get()
            ->pluck('student')
            ->filter()
            ->values();

        if ($students->isNotEmpty()) {
            Notification::send($students, new NewAnnouncementNotification($announcement, $training, $user));
        }

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
                $average = method_exists($a, 'averageSubmittedScore')
                    ? $a->averageSubmittedScore()
                    : 0.0;

                return [
                    'assessment_id' => $a->assessment_id,
                    'title' => $a->title,
                    'attempts' => $a->attempts_count ?? 0,
                    'average' => $average,
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
            'module_id' => 'required|exists:modules,id',
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
