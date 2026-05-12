<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Training;
use App\Models\User;

class EnrollmentController extends Controller
{
    public function create()
    {
        $trainings = Training::with('course', 'teacher.person')
            ->where('status', 'A')
            ->get();

        $students = User::with('person')
            ->whereHas('roles', fn($q) => $q->where('name', 'Student'))
            ->orderBy('username')
            ->get();

        return view('admin.enrollments.create', compact('trainings', 'students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'required|exists:users,user_id',
            'training_id' => 'required|exists:trainings,training_id',
        ]);

        $trainingId = $request->training_id;
        $studentIds = array_unique($request->student_ids);
        $createdCount = 0;

        foreach ($studentIds as $studentId) {
            $exists = Enrollment::where('student_id', $studentId)
                ->where('training_id', $trainingId)
                ->exists();

            if ($exists) {
                continue;
            }

            Enrollment::create([
                'training_id' => $trainingId,
                'student_id' => $studentId,
                'administrator_id' => auth()->id(),
                'enrollment_date' => now()->toDateString(),
                'status' => 'A'
            ]);

            $createdCount++;
        }

        $message = $createdCount > 0
            ? "{$createdCount} alumno(s) inscritos correctamente."
            : 'Ningún alumno nuevo fue inscrito porque ya estaban registrados en esta capacitación.';

        return redirect()->route('admin.trainings.index')
            ->with('success', $message);
    }
}
