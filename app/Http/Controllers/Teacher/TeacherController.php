<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Training;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $trainings = Training::with(['course', 'enrollments'])
            ->withCount('enrollments')
            ->where('teacher_id', $user->user_id)
            ->get();

        $totalCourses = $trainings->count();
        $totalStudents = $trainings->sum('enrollments_count');

        return view('teacher.dashboard', compact(
            'trainings',
            'totalCourses',
            'totalStudents'
        ));
    }

    public function courses()
    {
        $user = auth()->user();

        $courses = Training::with('course')
            ->where('teacher_id', $user->user_id)
            ->get()
            ->pluck('course')
            ->unique('course_id')
            ->values();

        return view('teacher.courses.index', compact('courses'));
    }

    public function students($id)
    {
        $user = auth()->user();

        $training = Training::with([
            'course',
            'enrollments.student.person',
            'enrollments.progress'
        ])
            ->where('training_id', $id)
            ->where('teacher_id', $user->user_id)
            ->firstOrFail();

        $students = $training->enrollments;

        return view('teacher.courses.students', compact('training', 'students'));
    }
}