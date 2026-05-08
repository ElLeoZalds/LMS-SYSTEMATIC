<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index()
    {
        $studentId = Auth::user()->user_id;

        $enrollments = Enrollment::with([
            'training.course',
            'training.teacher'
        ])
            ->where('student_id', $studentId)
            ->get();

        $totalCourses = $enrollments->count();
        $completed = 0;
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

        // Obtener todos los cursos matriculados del estudiante
        $courses = \App\Models\Course::with(['trainings.enrollments', 'trainings.teacher'])
            ->whereHas('trainings.enrollments', function ($query) use ($studentId) {
                $query->where('student_id', $studentId);
            })
            ->get()
            ->map(function ($course) use ($studentId) {
                // Calcular progreso por curso
                $enrollments = Enrollment::where('student_id', $studentId)
                    ->whereHas('training', function ($query) use ($course) {
                    $query->where('course_id', $course->course_id);
                })
                    ->get();

                $totalEnrollments = $enrollments->count();
                $completedEnrollments = $enrollments->where('status', 'C')->count();
                $course->progress_percentage = $totalEnrollments > 0
                    ? round(($completedEnrollments / $totalEnrollments) * 100)
                    : 0;

                // Obtener el primer instructor del curso
                $course->teacher = $course->trainings->first()?->teacher;

                return $course;
            });

        return view('student.courses.index', compact('courses'));
    }
}