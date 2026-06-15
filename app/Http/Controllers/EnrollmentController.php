<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Training;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function store($trainingId)
    {
        Training::findOrFail($trainingId);

        $user = Auth::user();

        if (! $this->isStudent($user)) {
            return back()->with('error', 'Solo los estudiantes pueden inscribirse en cursos.');
        }

        if ($this->isAlreadyEnrolled($user->user_id, $trainingId)) {
            return back()->with('error', 'Ya estás inscrito en este curso.');
        }

        $this->createEnrollment($user->user_id, $trainingId);

        return back()->with('success', 'Inscripción exitosa.');
    }

    private function isStudent($user): bool
    {
        return $user->roles->contains('name', 'Student');
    }

    private function isAlreadyEnrolled(int $studentId, int $trainingId): bool
    {
        return Enrollment::where('student_id', $studentId)
            ->where('training_id', $trainingId)
            ->exists();
    }

    private function createEnrollment(int $studentId, int $trainingId): void
    {
        Enrollment::create([
            'training_id' => $trainingId,
            'student_id' => $studentId,
            'administrator_id' => null,
            'enrollment_date' => now()->toDateString(),
            'status' => 'A',
        ]);
    }
}
