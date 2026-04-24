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

    public function store(Request $request)
    {
        $request->validate([
            'especialidad' => 'required|max:100|unique:especialidades,especialidad'
        ]);

        Especialidad::create($request->all());

        return redirect()->route('especialidades.index')
            ->with('success', 'Creado correctamente');
    }

    public function edit($id)
    {
        $especialidad = Especialidad::findOrFail($id);
        $especialidades = Especialidad::all();

        return view('modulos.specialtyActions', compact('especialidad', 'especialidades'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'especialidad' => 'required|max:100|unique:especialidades,especialidad,' . $id . ',idespecialidad'
        ]);

        $esp = Especialidad::findOrFail($id);
        $esp->update($request->all());

        return redirect()->route('especialidades.index')
            ->with('success', 'Actualizado correctamente');
    }

    public function destroy($id)
    {
        Especialidad::findOrFail($id)->delete();

        return redirect()->route('especialidades.index')
            ->with('success', 'Eliminado correctamente');
    }
}