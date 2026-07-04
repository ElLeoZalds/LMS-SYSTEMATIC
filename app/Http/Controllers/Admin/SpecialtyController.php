<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    public function index()
    {
        $specialties = Specialty::orderBy('created_at', 'desc')->get();

        return view('admin.specialties.index', compact('specialties'));
    }

    public function create()
    {
        return view('admin.specialties.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'specialty' => 'required|string|max:100|unique:specialties,specialty',
        ]);

        Specialty::create([
            'specialty' => $request->specialty,
            'is_active' => true,
        ]);

        return redirect()->route('admin.specialties.index')
            ->with('success', 'Especialidad creada correctamente');
    }

    public function edit($id)
    {
        $specialty = Specialty::findOrFail($id);

        return view('admin.specialties.edit', compact('specialty'));
    }

    public function update(Request $request, $id)
    {
        $specialty = Specialty::findOrFail($id);

        $request->validate([
            'specialty' => 'required|string|max:100|unique:specialties,specialty,'.$id.',specialty_id',
        ]);

        $specialty->update([
            'specialty' => $request->specialty,
        ]);

        return redirect()->route('admin.specialties.index')
            ->with('success', 'Especialidad actualizada correctamente');
    }

    public function destroy($id)
    {
        return redirect()->route('admin.specialties.index')
            ->with('error', 'La eliminación no está permitida. Use la opción de desactivar para ocultar esta especialidad.');
    }

    public function toggleActive($id)
    {
        $specialty = Specialty::findOrFail($id);
        $isActive = $specialty->isActive();

        $specialty->update(['is_active' => ! $isActive]);

        if (! $isActive) {
            $specialty->courses()->update(['is_active' => false]);

            return redirect()->route('admin.specialties.index')
                ->with('success', 'Especialidad activada. Los cursos asociados se han desactivado automáticamente.');
        }

        $courseCount = $specialty->courses()->count();

        return redirect()->route('admin.specialties.index')
            ->with('success', "Especialidad desactivada. {$courseCount} cursos asociados también han sido desactivados.");
    }
}
