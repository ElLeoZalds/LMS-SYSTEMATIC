<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Training;
use App\Models\Attendance;
use App\Models\Assessment;
use App\Models\AssessmentAttempt;
use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $totalStudents = Enrollment::whereHas('training', fn($q) => $q->where('teacher_id', $user->user_id))->count();
        $totalActiveTrainings = $user->trainings->where('status', 'A')->count();
        $totalTasks = Assessment::whereHas('training', fn($q) => $q->where('teacher_id', $user->user_id))->count();

        $totalAttempts = AssessmentAttempt::whereHas('assessment.training', fn($q) => $q->where('teacher_id', $user->user_id))->count();
        $averageScore = AssessmentAttempt::whereHas('assessment.training', fn($q) => $q->where('teacher_id', $user->user_id))->avg('score');
        $averageScore = $averageScore !== null ? round($averageScore, 2) : 0;

        $recentActivities = Assessment::with('training.course')
            ->whereHas('training', fn($q) => $q->where('teacher_id', $user->user_id))
            ->latest('created_at')
            ->take(10)
            ->get();

        return view('teacher.dashboard', compact(
            'totalStudents',
            'totalActiveTrainings',
            'totalTasks',
            'totalAttempts',
            'averageScore',
            'recentActivities'
        ));
    }

    public function courses()
    {
        $user = auth()->user();

        $trainings = Training::with('course')
            ->where('teacher_id', $user->user_id)
            ->get();

        return view('teacher.courses.index', compact('trainings'));
    }

    public function show($id)
    {
        $user = auth()->user();

        // Cargamos el training con sus evaluaciones y las relaciones necesarias para las notas
        $training = Training::with([
            'course',
            'assessments.attempts', // Trae los intentos globales de las evaluaciones de este curso
            'enrollments.student.person',
        ])
            ->where('training_id', $id)
            ->where('teacher_id', $user->user_id)
            ->firstOrFail();

        $totalStudents = $training->enrollments->count();
        $totalAssessments = $training->assessments->count();
        $totalAttendanceRecords = Attendance::whereHas('schedule', fn($q) => $q->where('training_id', $id))->count();

        // Obtenemos los estudiantes directamente desde las inscripciones cargadas
        $students = $training->enrollments;

        return view('teacher.courses.show', compact(
            'training', 
            'totalStudents', 
            'totalAssessments', 
            'totalAttendanceRecords',
            'students' // Enviamos los estudiantes para mapear la matriz en la vista
        ));
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

    public function attendance($id)
    {
        $user = auth()->user();

        $training = Training::with([
            'course',
            'enrollments.student.person'
        ])
            ->where('training_id', $id)
            ->where('teacher_id', $user->user_id)
            ->firstOrFail();

        $students = $training->enrollments;

        return view('teacher.attendance', compact('training', 'students'));
    }


    public function createTask($training_id)
    {
        $user = auth()->user();

        $training = Training::with('course')
            ->where('training_id', $training_id)
            ->where('teacher_id', $user->user_id)
            ->firstOrFail();

        return view('teacher.tasks.create', compact('training'));
    }

    public function storeTask(Request $request)
    {
        $request->validate([
            'training_id' => 'required|exists:trainings,training_id',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $user = auth()->user();

        $training = Training::where('training_id', $request->training_id)
            ->where('teacher_id', $user->user_id)
            ->first();

        if (!$training) {
            abort(403, 'No autorizado: Este training no te pertenece.');
        }

        Assessment::create([
            'training_id' => $request->training_id,
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date ?: now()->toDateString(),
            'end_date' => $request->end_date,
            'allowed_attempts' => 1,
            'active' => true,
        ]);

        return redirect()->route('teacher.courses.show', $request->training_id)
            ->with('success', 'Tarea creada correctamente.');
    }
}