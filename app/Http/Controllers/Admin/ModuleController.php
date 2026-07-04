<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ModuleRequest;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index(Request $request)
    {
        $query = Module::query()->with('course')->orderBy('order')->orderBy('title');

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->input('course_id'));
        }

        $modules = $query->get();
        $courses = Course::orderBy('title')->get();

        return view('admin.modules.index', compact('modules', 'courses'));
    }

    public function create()
    {
        $courses = Course::orderBy('title')->get();

        return view('admin.modules.create', compact('courses'));
    }

    public function store(ModuleRequest $request)
    {
        Module::create($request->validated());

        return redirect()->route('admin.modules.index')
            ->with('success', 'Módulo creado correctamente.');
    }

    public function edit(Module $module)
    {
        $courses = Course::orderBy('title')->get();

        return view('admin.modules.edit', compact('module', 'courses'));
    }

    public function update(ModuleRequest $request, Module $module)
    {
        $module->update($request->validated());

        return redirect()->route('admin.modules.index')
            ->with('success', 'Módulo actualizado correctamente.');
    }

    public function toggleActive(Module $module)
    {
        if (! $module->is_active) {
            return back()->with('success', 'El módulo ya estaba inactivo.');
        }

        $hasSubmissions = $module->tasks()->whereHas('submissions')->exists();
        $hasAttempts = $module->assessments()->whereHas('attempts')->exists();

        if ($hasSubmissions || $hasAttempts) {
            return back()->with('error', 'No se puede desactivar este módulo porque ya contiene calificaciones o entregas de estudiantes registradas.');
        }

        $module->update([
            'is_active' => false,
        ]);

        return back()->with('success', 'Módulo desactivado correctamente.');
    }
}
