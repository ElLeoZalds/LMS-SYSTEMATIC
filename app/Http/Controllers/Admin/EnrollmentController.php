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

        // 1. Consultar en una sola query cuáles de los estudiantes enviados ya están inscritos
        $existingStudentIds = Enrollment::where('training_id', $trainingId)
            ->whereIn('student_id', $studentIds)
            ->pluck('student_id')
            ->toArray();

        // 2. Filtrar para quedarnos solo con los estudiantes nuevos
        $newStudentIds = array_diff($studentIds, $existingStudentIds);
        $createdCount = count($newStudentIds);

        // 3. Si hay estudiantes nuevos, preparar el bloque e insertar masivamente
        if ($createdCount > 0) {
            $insertData = [];
            $adminId = auth()->id();
            $date = now()->toDateString();
            $now = now();

            foreach ($newStudentIds as $studentId) {
                $insertData[] = [
                    'training_id' => $trainingId,
                    'student_id' => $studentId,
                    'administrator_id' => $adminId,
                    'enrollment_date' => $date,
                    'status' => 'A',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // Inserción masiva en una sola sentencia SQL
            Enrollment::insert($insertData);
        }

        $message = $createdCount > 0
            ? "{$createdCount} alumno(s) inscritos correctamente."
            : 'Ningún alumno nuevo fue inscrito porque ya estaban registrados en esta capacitación.';

        return redirect()->route('admin.trainings.index')
            ->with('success', $message);
    }
}
