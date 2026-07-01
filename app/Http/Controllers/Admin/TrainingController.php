<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Training;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function index()
    {
        $trainings = Training::with(['course', 'teacher.person', 'administrator.person'])
            ->where('status', 1)
            ->when(request('code'), fn ($query, $code) => $query->where('code', 'like', '%'.$code.'%'))
            ->orderBy('created_at', 'desc')
            ->get();

        $courses = Course::all();
        $teachers = User::whereHas('roles', fn ($q) => $q->where('name', 'Teacher'))->with('person')->get();
        $students = User::whereHas('roles', fn ($q) => $q->where('name', 'Student'))->with('person')->take(100)->get();

        return view('admin.trainings.index', compact('trainings', 'courses', 'teachers', 'students'));
    }

    public function create()
    {
        $courses = Course::all();

        return view('admin.trainings.create', compact('courses'));
    }

    public function store(Request $request)
    {
        Training::create($this->trainingData($request));

        if ($request->wantsJson() || $request->ajax() || $request->isJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Capacitación programada con éxito.',
            ]);
        }

        return redirect()->route('admin.trainings.index')
            ->with('success', 'Capacitación programada con éxito.');
    }

    public function edit($id)
    {
        $training = Training::findOrFail($id);
        $courses = Course::all();

        return view('admin.trainings.edit', compact('training', 'courses'));
    }

    public function update(Request $request, $id)
    {
        $training = Training::findOrFail($id);
        $training->update($this->trainingData($request, $id));

        if ($request->wantsJson() || $request->ajax() || $request->isJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Capacitación actualizada correctamente.',
            ]);
        }

        return redirect()
            ->route('admin.trainings.index')
            ->with('success', 'Capacitación actualizada correctamente.');
    }

    public function destroy($id)
    {
        $training = Training::findOrFail($id);
        $training->delete();

        return redirect()
            ->route('admin.trainings.index')
            ->with('success', 'Capacitación eliminada correctamente.');
    }

    public function enroll(Request $request, Training $training)
    {
        $data = $request->validate([
            'student_id' => 'required|exists:users,user_id',
        ]);

        if ($this->alreadyEnrolled($training->training_id, $data['student_id'])) {
            return response()->json(['success' => false, 'message' => 'El alumno ya está inscrito en este curso.']);
        }

        Enrollment::create([
            'training_id' => $training->training_id,
            'student_id' => $data['student_id'],
            'enrollment_date' => now(),
            'status' => 'A',
        ]);

        return response()->json(['success' => true, 'message' => 'Alumno inscrito exitosamente.']);
    }

    private function trainingData(Request $request, ?int $trainingId = null): array
    {
        $data = $request->validate([
            'course_id' => 'required|exists:courses,course_id',
            'teacher_id' => 'required|exists:users,user_id',
            'modality' => 'required|in:virtual,presential,hybrid',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $data['administrator_id'] = auth()->id();

        if (! $trainingId) {
            $data['status'] = 1;
            $data['code'] = $this->generateTrainingCode($data['course_id'], $data['start_date']);
        }

        return $data;
    }

    private function generateTrainingCode(int $courseId, string $startDate): string
    {
        $course = Course::findOrFail($courseId);
        $abbreviation = strtoupper((string) ($course->abbreviation ?? 'COURSE'));
        $year = Carbon::parse($startDate)->format('Y');

        $counter = 1;
        $candidate = sprintf('%s-%s-%03d', $abbreviation, $year, $counter);

        while (Training::where('code', $candidate)->exists()) {
            $counter++;
            $candidate = sprintf('%s-%s-%03d', $abbreviation, $year, $counter);
        }

        return $candidate;
    }

    private function alreadyEnrolled(int $trainingId, int $studentId): bool
    {
        return Enrollment::where('training_id', $trainingId)
            ->where('student_id', $studentId)
            ->exists();
    }
}
