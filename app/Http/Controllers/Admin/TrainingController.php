<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Training;
use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;

class TrainingController extends Controller
{
    /**
     * Display a listing of trainings.
     */
    public function index()
    {
        $trainings = Training::with(['course', 'teacher.person', 'administrator.person'])
            ->where('status', 'A')
            ->orderBy('created_at', 'desc')
            ->get();

        $courses = Course::all();
        $teachers = User::whereHas('roles', fn($q) => $q->where('name', 'Teacher'))->with('person')->get();
        // NOTA: Si usas la inscripción masiva en un modal searchable, quita la carga masiva de $students de aquí
        // y manéjala mediante una petición AJAX/API paginada para proteger la memoria RAM.
        // Cargamos solo un subconjunto por defecto para evitar OOM en entornos con muchos estudiantes.
        $students = User::whereHas('roles', fn($q) => $q->where('name', 'Student'))->with('person')->take(100)->get();

        return view('admin.trainings.index', compact('trainings', 'courses', 'teachers', 'students'));
    }

    /**
     * Show the form for creating a new training.
     */
    public function create()
    {
        $courses = Course::all();

        return view('admin.trainings.create', compact('courses'));
    }

    /**
     * Store a newly created training in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,course_id',
            'teacher_id' => 'required|exists:users,user_id',
            'modality' => 'required|in:virtual,presential,hybrid',
            'price' => 'required|numeric|min:0.01',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'schedule' => 'required|string|max:100',
        ]);

        Training::create([
            'course_id' => $request->course_id,
            'teacher_id' => $request->teacher_id,
            'administrator_id' => auth()->id(),
            'modality' => $request->modality,
            'price' => $request->price,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'schedule' => $request->schedule,
            'creation_date' => now()->toDateString(),
            'status' => 'A',
        ]);

        return redirect()->route('admin.trainings.index')
            ->with('success', 'Capacitación programada con éxito.');
    }

    /**
     * Show the form for editing the specified training.
     */
    public function edit($id)
    {
        $training = Training::findOrFail($id);
        $courses = Course::all();

        return view('admin.trainings.edit', compact('training', 'courses'));
    }

    /**
     * Update the specified training in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,course_id',
            'teacher_id' => 'required|exists:users,user_id',
            'modality' => 'required|in:virtual,presential,hybrid',
            'price' => 'required|numeric|min:0.01',
        ]);

        $training = Training::findOrFail($id);

        $training->update([
            'course_id' => $request->course_id,
            'teacher_id' => $request->teacher_id,
            'modality' => $request->modality,
            'price' => $request->price,
        ]);

        return redirect()
            ->route('admin.trainings.index')
            ->with('success', 'Training actualizado correctamente');
    }

    /**
     * Remove the specified training from storage.
     */
    public function destroy($id)
    {
        $training = Training::findOrFail($id);
        $training->delete();

        return redirect()
            ->route('admin.trainings.index')
            ->with('success', 'Training eliminado correctamente');
    }

    /**
     * Enroll a student in a training.
     */
    public function enroll(Request $request, Training $training)
    {
        $request->validate([
            'student_id' => 'required|exists:users,user_id',
        ]);

        // Check if already enrolled
        $alreadyEnrolled = Enrollment::where('training_id', $training->training_id)
                            ->where('student_id', $request->student_id)
                            ->exists();

        if ($alreadyEnrolled) {
            return response()->json(['success' => false, 'message' => 'El alumno ya está inscrito en este curso.']);
        }

        Enrollment::create([
            'training_id' => $training->training_id,
            'student_id' => $request->student_id,
            'enrollment_date' => now(),
            'status' => 'A',
        ]);

        return response()->json(['success' => true, 'message' => 'Alumno inscrito exitosamente.']);
    }
}