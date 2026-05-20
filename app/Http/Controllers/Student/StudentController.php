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
            'training.teacher.person'
        ])
            ->where('student_id', $studentId)
            ->get();

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
            'progress' 
        ])
            ->where('student_id', $studentId)
            ->get()
            ->map(function ($enrollment) {
                // Si la relación progress contiene datos, extrae el porcentaje del primer registro
                if ($enrollment->progress && $enrollment->progress->isNotEmpty()) {
                    $enrollment->progress_percentage = $enrollment->progress->first()->percentage;
                } else {
                    // Si la colección de progreso está vacía, calculamos por defecto según el estado de la matrícula
                    $enrollment->progress_percentage = $enrollment->status === 'C' ? 100 : 0;
                }
                
                return $enrollment;
            });

        return view('student.courses.index', compact('courses'));
    }
}