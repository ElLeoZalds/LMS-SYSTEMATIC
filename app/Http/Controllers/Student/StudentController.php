<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AssessmentAttempt;
use App\Models\Enrollment;
use App\Models\TaskSubmission;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function index()
    {
        $student = Auth::user();

        $enrollments = $student->enrollments()
            ->with([
                'training.course',
                'training.teacher.person',
                'training.schedules',
                'training.contents',
                'training.tasks',
                'training.assessments',
                'training.announcements',
                'progress',
            ])
            ->get()
            ->map(function ($enrollment) {
                $schedule = $enrollment->training?->schedules?->sortBy('date')->first();
                if ($schedule && $schedule->start_time && $schedule->end_time) {
                    $enrollment->schedule_label = Carbon::parse($schedule->start_time)->format('H:i').' - '.Carbon::parse($schedule->end_time)->format('H:i');
                } else {
                    $enrollment->schedule_label = 'Horario no disponible';
                }

                return $enrollment;
            });

        $activeTrainings = $enrollments->filter(fn ($enrollment) => ! $enrollment->isCompleted());
        $studentName = trim(implode(' ', array_filter([
            optional($student->person)->first_names,
            optional($student->person)->last_names,
        ])));

        $upcomingDeadlines = $this->buildUpcomingDeadlines($activeTrainings);
        $availableAssessments = $this->buildAvailableAssessments($activeTrainings);
        $recentAnnouncements = $this->buildRecentAnnouncements($activeTrainings);
        $notifications = $this->buildNotifications($activeTrainings, $upcomingDeadlines, $availableAssessments, $recentAnnouncements);
        $stats = $this->buildStats($activeTrainings);
        $overallProgress = (int) round($stats['overall_progress'] ?? 0);

        return view('student.dashboard', compact(
            'activeTrainings',
            'upcomingDeadlines',
            'availableAssessments',
            'recentAnnouncements',
            'notifications',
            'stats',
            'overallProgress',
            'studentName'
        ));
    }

    public function courses()
    {
        $studentId = Auth::user()->user_id;

        $courses = Enrollment::with([
            'training.course',
            'training.teacher.person',
            'training.schedules',
            'training.contents',
            'training.tasks',
            'training.assessments',
            'progress',
        ])
            ->where('student_id', $studentId)
            ->get()
            ->map(function ($enrollment) {
                $schedule = $enrollment->training?->schedules?->sortBy('date')->first();
                if ($schedule && $schedule->start_time && $schedule->end_time) {
                    $enrollment->schedule_label = Carbon::parse($schedule->start_time)->format('H:i').' - '.Carbon::parse($schedule->end_time)->format('H:i');
                } else {
                    $enrollment->schedule_label = 'Horario no disponible';
                }

                return $enrollment;
            });

        return view('student.courses.index', compact('courses'));
    }

    public function notifications()
    {
        $student = Auth::user();
        $enrollments = $student->enrollments()
            ->with([
                'training.course',
                'training.teacher.person',
                'training.tasks',
                'training.assessments',
                'training.announcements',
            ])
            ->get();

        $activeTrainings = $enrollments->filter(fn ($enrollment) => ! $enrollment->isCompleted());
        $upcomingDeadlines = $this->buildUpcomingDeadlines($activeTrainings);
        $availableAssessments = $this->buildAvailableAssessments($activeTrainings);
        $recentAnnouncements = $this->buildRecentAnnouncements($activeTrainings);
        $notifications = $this->buildNotifications($activeTrainings, $upcomingDeadlines, $availableAssessments, $recentAnnouncements);
        $readNotificationIds = $this->getReadNotificationIds();

        $typeFilter = request('type');
        if ($typeFilter) {
            $notifications = $notifications->filter(function ($notification) use ($typeFilter) {
                return Str::slug($notification['type']) === Str::slug($typeFilter);
            });
        }

        $notifications = $notifications->map(function ($notification) use ($readNotificationIds) {
            $notification['is_read'] = in_array((string) $notification['id'], $readNotificationIds, true);

            return $notification;
        });

        $page = (int) request('page', 1);
        $perPage = 10;
        $paginatedNotifications = new LengthAwarePaginator(
            $notifications->forPage($page, $perPage)->values(),
            $notifications->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('student.notifications', compact('paginatedNotifications', 'typeFilter'));
    }

    public function getUnreadNotifications(): JsonResponse
    {
        $notifications = Auth::user()
            ->unreadNotifications()
            ->latest('created_at')
            ->take(10)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => data_get($notification->data, 'announcement_title', data_get($notification->data, 'title', 'Nuevo anuncio')),
                    'course' => data_get($notification->data, 'course_title', data_get($notification->data, 'course', 'Curso')),
                    'created_at' => $notification->created_at?->toIso8601String(),
                    'relative_time' => $notification->created_at?->diffForHumans(),
                    'url' => data_get($notification->data, 'url', route('student.dashboard')),
                ];
            });

        return response()->json([
            'success' => true,
            'count' => $notifications->count(),
            'notifications' => $notifications,
        ]);
    }

    public function markAllNotificationsAsRead(): JsonResponse
    {
        Auth::user()->unreadNotifications->each->markAsRead();
        session()->forget('student.read_notification_ids');

        return response()->json([
            'success' => true,
            'count' => 0,
        ]);
    }

    public function markNotificationAsRead($notificationId): JsonResponse
    {
        $notification = Auth::user()->notifications()->find($notificationId);

        if ($notification) {
            $notification->markAsRead();
        }

        $readIds = $this->getReadNotificationIds();
        $readIds[] = (string) $notificationId;
        session()->put('student.read_notification_ids', array_values(array_unique($readIds)));

        return response()->json([
            'success' => true,
            'notification_id' => $notificationId,
        ]);
    }

    private function buildUpcomingDeadlines($activeTrainings)
    {
        $deadlines = collect();
        $now = Carbon::now();

        foreach ($activeTrainings as $enrollment) {
            $training = $enrollment->training;
            if (! $training) {
                continue;
            }

            foreach ($training->tasks as $task) {
                if (! $task->due_date) {
                    continue;
                }

                $dueDate = Carbon::parse($task->due_date);
                if ($dueDate->lt($now->copy()->subDays(7))) {
                    continue;
                }

                $deadlines->push([
                    'id' => 'task-'.$task->task_id,
                    'title' => $task->title,
                    'training_title' => $training->course?->title ?? 'Capacitación',
                    'deadline' => $dueDate->format('d/m/Y H:i'),
                    'sort_date' => $dueDate,
                    'type' => 'Tarea',
                    'icon' => 'bi-clock',
                    'state' => $dueDate->isPast() ? 'Vencida' : 'Pendiente',
                    'url' => route('student.courses.show', $training->training_id),
                ]);
            }

            foreach ($training->assessments as $assessment) {
                if (! $assessment->active || ! $assessment->end_date) {
                    continue;
                }

                $deadline = Carbon::parse($assessment->end_date);
                if ($deadline->lt($now->copy()->subDays(7))) {
                    continue;
                }

                $deadlines->push([
                    'id' => 'assessment-'.$assessment->assessment_id,
                    'title' => $assessment->title,
                    'training_title' => $training->course?->title ?? 'Capacitación',
                    'deadline' => $deadline->format('d/m/Y H:i'),
                    'sort_date' => $deadline,
                    'type' => 'Evaluación',
                    'icon' => 'bi-journal-check',
                    'state' => $deadline->isPast() ? 'Vencida' : 'Pendiente',
                    'url' => route('student.courses.show', $training->training_id),
                ]);
            }
        }

        return $deadlines->sortBy('sort_date')->take(5)->values();
    }

    private function buildAvailableAssessments($activeTrainings)
    {
        $items = collect();
        $now = Carbon::now();

        foreach ($activeTrainings as $enrollment) {
            $training = $enrollment->training;
            if (! $training) {
                continue;
            }

            foreach ($training->assessments as $assessment) {
                if (! $assessment->active) {
                    continue;
                }

                $startDate = $assessment->start_date ? Carbon::parse($assessment->start_date)->startOfDay() : null;
                $endDate = $assessment->end_date ? Carbon::parse($assessment->end_date)->endOfDay() : null;
                if (($startDate && $now->lt($startDate)) || ($endDate && $now->gt($endDate))) {
                    continue;
                }

                $items->push([
                    'id' => 'assessment-available-'.$assessment->assessment_id,
                    'title' => $assessment->title,
                    'training_title' => $training->course?->title ?? 'Capacitación',
                    'deadline' => $endDate ? $endDate->format('d/m/Y H:i') : 'Sin fecha',
                    'url' => route('student.courses.show', $training->training_id),
                ]);
            }
        }

        return $items->take(5)->values();
    }

    private function buildRecentAnnouncements($activeTrainings)
    {
        $announcements = collect();

        foreach ($activeTrainings as $enrollment) {
            $training = $enrollment->training;
            if (! $training) {
                continue;
            }

            foreach ($training->announcements as $announcement) {
                $announcements->push([
                    'id' => 'announcement-'.$announcement->announcement_id,
                    'title' => Str::limit(strip_tags($announcement->content), 50),
                    'training_title' => $training->course?->title ?? 'Capacitación',
                    'created_at' => $announcement->created_at ?? now(),
                    'excerpt' => Str::limit(strip_tags($announcement->content), 100),
                    'url' => route('student.courses.show', $training->training_id),
                ]);
            }
        }

        return $announcements->sortByDesc('created_at')->take(5)->values();
    }

    private function buildNotifications($activeTrainings, $upcomingDeadlines, $availableAssessments, $recentAnnouncements)
    {
        $notifications = collect();

        foreach ($recentAnnouncements as $announcement) {
            $notifications->push([
                'id' => 'announcement-'.$announcement['id'],
                'type' => 'Anuncios',
                'icon' => 'bi-megaphone',
                'title' => 'Nuevo anuncio: '.$announcement['title'],
                'message' => $announcement['training_title'],
                'created_at' => $announcement['created_at'],
                'url' => $announcement['url'],
                'badge' => 'Nuevo',
            ]);
        }

        foreach ($upcomingDeadlines as $deadline) {
            $notifications->push([
                'id' => 'deadline-'.$deadline['id'],
                'type' => 'Tareas',
                'icon' => 'bi-clock',
                'title' => $deadline['type'].': '.$deadline['title'],
                'message' => $deadline['training_title'].' · vence '.$deadline['deadline'],
                'created_at' => $deadline['sort_date'],
                'url' => $deadline['url'],
                'badge' => 'Nuevo',
            ]);
        }

        foreach ($availableAssessments as $assessment) {
            $notifications->push([
                'id' => 'assessment-'.$assessment['id'],
                'type' => 'Evaluaciones',
                'icon' => 'bi-journal-check',
                'title' => 'Evaluación disponible: '.$assessment['title'],
                'message' => $assessment['training_title'],
                'created_at' => now(),
                'url' => $assessment['url'],
                'badge' => 'Nuevo',
            ]);
        }

        $gradeNotifications = $this->buildGradeNotifications($activeTrainings);
        foreach ($gradeNotifications as $gradeNotification) {
            $notifications->push($gradeNotification);
        }

        return $notifications->sortByDesc('created_at')->take(10)->values();
    }

    private function buildGradeNotifications($activeTrainings)
    {
        $notifications = collect();

        foreach ($activeTrainings as $enrollment) {
            $training = $enrollment->training;
            if (! $training) {
                continue;
            }

            foreach ($training->tasks as $task) {
                $submission = TaskSubmission::where('task_id', $task->task_id)
                    ->where('student_id', Auth::id())
                    ->whereNotNull('grade')
                    ->latest('graded_at')
                    ->first();

                if ($submission) {
                    $notifications->push([
                        'id' => 'grade-task-'.$submission->submission_id,
                        'type' => 'Calificaciones',
                        'icon' => 'bi-star',
                        'title' => 'Calificación publicada: '.$task->title,
                        'message' => $training->course?->title ?? 'Capacitación',
                        'created_at' => $submission->graded_at ?? $submission->updated_at ?? now(),
                        'url' => route('student.courses.show', $training->training_id),
                        'badge' => 'Nuevo',
                    ]);
                }
            }

            foreach ($training->assessments as $assessment) {
                $attempt = AssessmentAttempt::where('assessment_id', $assessment->assessment_id)
                    ->where('enrollment_id', $enrollment->enrollment_id)
                    ->whereNotNull('submitted_at')
                    ->whereNotNull('score')
                    ->latest('submitted_at')
                    ->first();

                if ($attempt) {
                    $notifications->push([
                        'id' => 'grade-assessment-'.$attempt->attempt_id,
                        'type' => 'Calificaciones',
                        'icon' => 'bi-star',
                        'title' => 'Calificación publicada: '.$assessment->title,
                        'message' => $training->course?->title ?? 'Capacitación',
                        'created_at' => $attempt->submitted_at ?? now(),
                        'url' => route('student.courses.show', $training->training_id),
                        'badge' => 'Nuevo',
                    ]);
                }
            }
        }

        return $notifications;
    }

    private function buildStats($activeTrainings)
    {
        $completedTasks = 0;
        $approvedAssessments = 0;
        $averageGrade = 0;
        $progressTotal = 0;

        foreach ($activeTrainings as $enrollment) {
            $completedTasks += $enrollment->completedTasksCount();
            $approvedAssessments += collect($enrollment->training?->assessments ?? [])->filter(function ($assessment) use ($enrollment) {
                $attempt = AssessmentAttempt::where('assessment_id', $assessment->assessment_id)
                    ->where('enrollment_id', $enrollment->enrollment_id)
                    ->whereNotNull('submitted_at')
                    ->whereNotNull('score')
                    ->latest('submitted_at')
                    ->first();

                return $attempt && (float) $attempt->score >= 13;
            })->count();

            $progressTotal += (int) $enrollment->getProgressPercentageAttribute();
        }

        if ($activeTrainings->isNotEmpty()) {
            $averageGrade = round($activeTrainings->avg(function ($enrollment) {
                return (float) $enrollment->calculateAverage();
            }), 2);
        }

        $overallProgress = $activeTrainings->isEmpty() ? 0 : (int) round($progressTotal / $activeTrainings->count());

        return [
            'active_trainings' => $activeTrainings->count(),
            'completed_tasks' => $completedTasks,
            'approved_assessments' => $approvedAssessments,
            'average_grade' => $averageGrade,
            'overall_progress' => $overallProgress,
        ];
    }

    private function getReadNotificationIds(): array
    {
        return array_values(array_filter((array) session('student.read_notification_ids', [])));
    }

}
