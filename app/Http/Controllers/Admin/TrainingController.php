<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Training;
use App\Models\Course;
use App\Models\User;

class TrainingController extends Controller
{
    /**
     * Display a listing of trainings.
     */
    public function index()
    {
        $trainings = Training::with('course')
            ->where('status', 'A')
            ->orderBy('created_at', 'desc')
            ->get();

        $courses = Course::all();
        $teachers = User::whereHas('roles', fn($q) => $q->where('name', 'Teacher'))->get();

        return view('admin.trainings.index', compact('trainings', 'courses', 'teachers'));
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
        ]);

        Training::create([
            'course_id' => $request->course_id,
            'teacher_id' => $request->teacher_id,
            'administrator_id' => auth()->id(),
            'modality' => $request->modality,
            'price' => $request->price,
            'creation_date' => now()->toDateString(),
            'status' => 'A',
        ]);

        return redirect()
            ->route('admin.trainings.index')
            ->with('success', 'Training creado correctamente');
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
}