<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Training;
use App\Models\User;

class EnrollmentController extends Controller
{
    public function create(Request $request)
    {
        $selectedTraining = null;
        $eligibleStudentIds = null;

        if ($request->filled('training_id')) {
            $selectedTraining = Training::with('course', 'teacher.person')
                ->where('training_id', $request->training_id)
                ->where('status', 1)
                ->firstOrFail();

            $eligibleStudentIds = Enrollment::whereHas('training', function ($query) use ($selectedTraining) {
                    $query->where('course_id', $selectedTraining->course_id);
                })
                ->pluck('student_id')
                ->unique()
                ->all();
        }

        $trainings = Training::with('course', 'teacher.person')
            ->where('status', 1)
            ->get();

        $studentsQuery = User::with('person')
            ->whereHas('roles', fn($q) => $q->where('name', 'Student'))
            ->orderBy('username');

        if ($selectedTraining && !empty($eligibleStudentIds)) {
            $studentsQuery->whereNotIn('user_id', $eligibleStudentIds);
        }

        $students = $studentsQuery->get();

        return view('admin.enrollments.create', compact('trainings', 'students', 'selectedTraining'));
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
        $training = Training::with('course')->findOrFail($trainingId);

        $alreadyEnrolledInCourse = Enrollment::whereIn('student_id', $studentIds)
            ->whereHas('training', function ($query) use ($training) {
                $query->where('course_id', $training->course_id);
            })
            ->pluck('student_id')
            ->toArray();

        // 1. Consultar en una sola query cuáles de los estudiantes enviados ya están inscritos
        $existingStudentIds = Enrollment::where('training_id', $trainingId)
            ->whereIn('student_id', $studentIds)
            ->pluck('student_id')
            ->toArray();

        // 2. Filtrar para quedarnos solo con los estudiantes nuevos y que no pertenezcan a otra capacitación del mismo curso
        $newStudentIds = array_diff($studentIds, $existingStudentIds, $alreadyEnrolledInCourse);
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
            : 'Ningún alumno nuevo fue inscrito porque ya estaban registrados en esta capacitación o en otra capacitación del mismo curso.';

        return redirect()->route('admin.trainings.index')
            ->with('success', $message);
    }
}
