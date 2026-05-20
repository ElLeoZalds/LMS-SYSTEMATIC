<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Training;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function store($trainingId)
    {
        $training = Training::findOrFail($trainingId);
        $user = Auth::user();

        // Verificar que el usuario logueado sea un estudiante
        if (!$user->roles->contains('name', 'Student')) {
            return back()->with('error', 'Solo los estudiantes pueden inscribirse en cursos.');
        }

        // ✓ Optimizado: Usamos exists() en lugar de first() para evitar cargar el modelo en memoria
        $alreadyEnrolled = Enrollment::where('student_id', $user->user_id)
            ->where('training_id', $trainingId)
            ->exists();

        if ($alreadyEnrolled) {
            return back()->with('error', 'Ya estás inscrito en este curso.');
        }

        // Crear la inscripción pública (autoinscripción)
        Enrollment::create([
            'training_id' => $trainingId,
            'student_id' => $user->user_id,
            'administrator_id' => null, // ← Corregido: Es nulo porque se inscribió el alumno solo, no un admin
            'enrollment_date' => now()->toDateString(),
            'status' => 'A'
        ]);

        return back()->with('success', 'Inscripción exitosa.');
    }
}