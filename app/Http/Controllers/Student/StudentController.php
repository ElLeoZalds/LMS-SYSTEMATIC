<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentController extends Controller
{
    public function index()
    {
        $studentId = Auth::user()->user_id;

        $enrollments = Enrollment::with([
            'training.course',
            'training.teacher.person',
            'training.schedules',
            'progress'
        ])
            ->where('student_id', $studentId)
            ->get()
            ->map(function ($enrollment) {
                if ($enrollment->progress && $enrollment->progress->isNotEmpty()) {
                    $enrollment->progress_percentage = $enrollment->progress->first()->percentage;
                } else {
                    $enrollment->progress_percentage = $enrollment->status === 'C' ? 100 : 0;
                }

                $schedule = $enrollment->training?->schedules?->sortBy('date')->first();
                if ($schedule && $schedule->start_time && $schedule->end_time) {
                    $enrollment->schedule_label = Carbon::parse($schedule->start_time)->format('H:i') . ' - ' . Carbon::parse($schedule->end_time)->format('H:i');
                } else {
                    $enrollment->schedule_label = 'Horario no disponible';
                }

                return $enrollment;
            });

        $totalCourses = $enrollments->count();
        $completed = $enrollments->where('status', 'C')->count();
        $inProgress = $enrollments->where('status', 'A')->count();

        return view('student.dashboard', compact(
            'enrollments',
            'totalCourses',
            'completed',
            'inProgress'
        ));
    }

    public function courses()
    {
        $studentId = Auth::user()->user_id;

        $courses = Enrollment::with([
            'training.course',
            'training.teacher.person',
            'training.schedules',
            'progress'
        ])
            ->where('student_id', $studentId)
            ->get()
            ->map(function ($enrollment) {
                if ($enrollment->progress && $enrollment->progress->isNotEmpty()) {
                    $enrollment->progress_percentage = $enrollment->progress->first()->percentage;
                } else {
                    $enrollment->progress_percentage = $enrollment->status === 'C' ? 100 : 0;
                }

                $schedule = $enrollment->training?->schedules?->sortBy('date')->first();
                if ($schedule && $schedule->start_time && $schedule->end_time) {
                    $enrollment->schedule_label = Carbon::parse($schedule->start_time)->format('H:i') . ' - ' . Carbon::parse($schedule->end_time)->format('H:i');
                } else {
                    $enrollment->schedule_label = 'Horario no disponible';
                }

                return $enrollment;
            });

        return view('student.courses.index', compact('courses'));
    }
}