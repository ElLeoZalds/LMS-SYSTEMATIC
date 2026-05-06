<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Specialty;

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
            'specialty' => 'required|string|max:100|unique:specialties,specialty,' . $id . ',specialty_id',
        ]);

        $specialty->update([
            'specialty' => $request->specialty,
        ]);

        return redirect()->route('admin.specialties.index')
            ->with('success', 'Especialidad actualizada correctamente');
    }

    public function destroy($id)
    {
        $specialty = Specialty::findOrFail($id);
        $specialty->delete();

        return redirect()->route('admin.specialties.index')
            ->with('success', 'Especialidad eliminada correctamente');
    }
}