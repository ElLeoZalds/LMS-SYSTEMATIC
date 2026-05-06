<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Specialty;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::orderBy('created_at', 'desc')->get();
        $specialties = Specialty::all();

        return view('admin.courses.index', compact('courses', 'specialties'));
    }

    public function create()
    {
        $specialties = Specialty::all();
        return view('admin.courses.create', compact('specialties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'specialty_id' => 'required|exists:specialties,specialty_id',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'hours_count' => 'nullable|integer|min:0',
            'reference_price' => 'nullable|numeric|min:0',
            'banner_path' => 'nullable|string'
        ]);

        Course::create([
            'specialty_id' => $request->specialty_id,
            'title' => $request->title,
            'description' => $request->description,
            'hours_count' => $request->hours_count,
            'reference_price' => $request->reference_price,
            'banner_path' => $request->banner_path
        ]);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso creado correctamente');
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        $specialties = Specialty::all();
        return view('admin.courses.edit', compact('course', 'specialties'));
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $course->update([
            'title' => $request->title,
            'description' => $request->description,
            'reference_price' => $request->reference_price
        ]);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso actualizado');
    }

    public function destroy($id)
    {
        Course::destroy($id);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso eliminado');
    }
}