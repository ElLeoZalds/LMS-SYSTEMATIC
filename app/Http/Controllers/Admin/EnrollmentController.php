<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Training;
use App\Models\User;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function create(Request $request)
    {
        $selectedTraining = $this->selectedTraining($request);
        $trainings = $this->activeTrainings();
        $students = $this->availableStudents($selectedTraining);

        return view('admin.enrollments.create', compact('trainings', 'students', 'selectedTraining'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'required|exists:users,user_id',
            'training_id' => 'required|exists:trainings,training_id',
        ]);

        $trainingId = (int) $request->training_id;
        $studentIds = array_unique($request->student_ids);
        $training = Training::with('course')->findOrFail($trainingId);

        $validatedStudentIds = [];
        foreach ($studentIds as $studentId) {
            $validation = Enrollment::validateEnrollment((int) $studentId, $trainingId);

            if (! $validation['success']) {
                return back()->with('error', $validation['message']);
            }

            $validatedStudentIds[] = (int) $studentId;
        }

        $newStudentIds = $this->newStudentIds($validatedStudentIds, $training, $trainingId);

        $createdCount = count($newStudentIds);

        if ($createdCount > 0) {
            Enrollment::insert($this->enrollmentRows($newStudentIds, $trainingId));
        }

        return redirect()->route('admin.trainings.index')
            ->with('success', $this->storeMessage($createdCount));
    }

    private function selectedTraining(Request $request): ?Training
    {
        if (! $request->filled('training_id')) {
            return null;
        }

        return Training::with('course', 'teacher.person')
            ->where('training_id', $request->training_id)
            ->where('status', Training::STATUS_ACTIVE)
            ->firstOrFail();
    }

    private function activeTrainings()
    {
        return Training::with('course', 'teacher.person')
            ->where('status', Training::STATUS_ACTIVE)
            ->get();
    }

    private function availableStudents(?Training $selectedTraining)
    {
        $studentsQuery = User::with('person')
            ->whereHas('roles', fn ($query) => $query->where('name', 'Student'))
            ->orderBy('username');

        if ($selectedTraining) {
            $studentsQuery->whereNotIn('user_id', $this->studentIdsEnrolledInCourse($selectedTraining));
        }

        return $studentsQuery->get();
    }

    private function studentIdsEnrolledInCourse(Training $training): array
    {
        return Enrollment::whereHas('training', fn ($query) => $query->where('course_id', $training->course_id))
            ->pluck('student_id')
            ->unique()
            ->all();
    }

    private function newStudentIds(array $studentIds, Training $training, int $trainingId): array
    {
        return array_diff(
            $studentIds,
            $this->studentIdsEnrolledInTraining($studentIds, $trainingId),
            $this->studentIdsEnrolledInCourse($training)
        );
    }

    private function studentIdsEnrolledInTraining(array $studentIds, int $trainingId): array
    {
        return Enrollment::where('training_id', $trainingId)
            ->whereIn('student_id', $studentIds)
            ->pluck('student_id')
            ->toArray();
    }

    private function enrollmentRows(array $studentIds, int $trainingId): array
    {
        $now = now();

        return collect($studentIds)
            ->map(fn ($studentId) => [
                'training_id' => $trainingId,
                'student_id' => $studentId,
                'administrator_id' => auth()->id(),
                'enrollment_date' => $now->toDateString(),
                'status' => Enrollment::STATUS_ACTIVE,
                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->values()
            ->all();
    }

    private function storeMessage(int $createdCount): string
    {
        if ($createdCount > 0) {
            return "{$createdCount} alumno(s) inscritos correctamente.";
        }

        return 'Ningún alumno nuevo fue inscrito porque ya estaban registrados en esta capacitación o en otra capacitación del mismo curso.';
    }
}
