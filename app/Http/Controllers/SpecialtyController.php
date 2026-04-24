<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    public function index()
    {
        $especialidades = Especialidad::all();
        return view('modulos.specialtyActions', compact('especialidades'));
    }

    public function create()
    {
        return view('modulos.specialtyActions');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'especialidad' => 'required|string|max:100|unique:especialidades,especialidad',
        ]);

        Especialidad::create($validated);
        return redirect()->route('especialidades.index')->with('success', 'Especialidad creada correctamente');
    }

    public function edit($id)
    {
        $especialidad = Especialidad::findOrFail($id);
        $especialidades = Especialidad::all();
        return view('modulos.specialtyActions', compact('especialidad', 'especialidades'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'especialidad' => 'required|string|max:100|unique:especialidades,especialidad,' . $id . ',idespecialidad',
        ]);

        $especialidad = Especialidad::findOrFail($id);
        $especialidad->update($validated);
        return redirect()->route('especialidades.index')->with('success', 'Especialidad actualizada correctamente');
    }

    public function destroy($id)
    {
        $especialidad = Especialidad::findOrFail($id);
        $especialidad->delete();
        return redirect()->route('especialidades.index')->with('success', 'Especialidad eliminada correctamente');
    }
}