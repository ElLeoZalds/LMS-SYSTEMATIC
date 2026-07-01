<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Specialty;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index()
    {
        $student = Auth::user();

        $enrollmentsQuery = $student->enrollments();
        $hasEnrollments = $enrollmentsQuery->exists();

        $enrollments = $hasEnrollments
            ? $enrollmentsQuery->with([
                'training.course',
                'training.teacher.person',
                'training.schedules',
                'training.contents',
                'training.tasks',
                'training.assessments',
                'progress',
            ])->get()->map(function ($enrollment) {
                $schedule = $enrollment->training?->schedules?->sortBy('date')->first();
                if ($schedule && $schedule->start_time && $schedule->end_time) {
                    $enrollment->schedule_label = Carbon::parse($schedule->start_time)->format('H:i').' - '.Carbon::parse($schedule->end_time)->format('H:i');
                } else {
                    $enrollment->schedule_label = 'Horario no disponible';
                }

                return $enrollment;
            })
            : collect();
        $totalCourses = $enrollments->count();
        $completed = $enrollments->where('status', 'C')->count();
        $inProgress = $enrollments->where('status', 'A')->count();

        $studentName = trim(implode(' ', array_filter([
            optional(Auth::user()->person)->first_names,
            optional(Auth::user()->person)->last_names,
        ])));

        $featuredCourses = collect();
        $specialties = collect();

        if (! $hasEnrollments) {
            $featuredCourses = Course::with(['specialty'])
                ->whereHas('trainings', function ($query) {
                    $query->where('status', 1);
                })
                ->latest('created_at')
                ->take(3)
                ->get();

            $specialties = Specialty::query()
                ->whereHas('courses', function ($query) {
                    $query->whereHas('trainings', function ($trainingQuery) {
                        $trainingQuery->where('status', 1);
                    });
                })
                ->orderBy('specialty')
                ->get();
        }

        return view('student.dashboard', compact(
            'enrollments',
            'totalCourses',
            'completed',
            'inProgress',
            'hasEnrollments',
            'featuredCourses',
            'specialties',
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
}
