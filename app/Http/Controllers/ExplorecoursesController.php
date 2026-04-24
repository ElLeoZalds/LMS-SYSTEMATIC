<?php

namespace App\Http\Controllers;


use App\Models\Course;
use App\Models\Especialidad;

use Illuminate\Http\Request;

class ExplorecoursesController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return view('dashboard.courses.index', compact('courses'));
    }

    public function create()
    {
        $especialidades = Especialidad::all();
        return view('dashboard.courses.create', compact('especialidades'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'idespecialidad' => 'required|exists:especialidades,idespecialidad',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'cantidadhoras' => 'required|integer',
            'precioreferencial' => 'required|numeric',
            'pathbanner' => 'nullable|string',
        ]);
        Course::create($validated);
        return redirect()->route('cursos.index')->with('success', 'Curso creado correctamente');
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        $especialidades = Especialidad::all();
        return view('dashboard.courses.edit', compact('course', 'especialidades'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'idespecialidad' => 'required|exists:especialidades,idespecialidad',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'cantidadhoras' => 'required|integer',
            'precioreferencial' => 'required|numeric',
            'pathbanner' => 'nullable|string',
        ]);
        $course = Course::findOrFail($id);
        $course->update($validated);
        return redirect()->route('cursos.index')->with('success', 'Curso actualizado correctamente');
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
        return redirect()->route('cursos.index')->with('success', 'Curso eliminado correctamente');
    }
}
