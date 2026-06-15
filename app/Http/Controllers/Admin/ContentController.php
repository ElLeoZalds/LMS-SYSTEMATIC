<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Training;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function index()
    {
        $contents = Content::with(['training.course'])
            ->orderBy('training_id', 'asc')
            ->orderBy('order_index', 'asc')
            ->get();

        $trainings = $this->activeTrainings();

        return view('admin.contents.index', compact('contents', 'trainings'));
    }

    public function create()
    {
        $trainings = $this->activeTrainings();

        return view('admin.contents.create', compact('trainings'));
    }

    public function store(Request $request)
    {
        Content::create($this->validatedData($request));

        return redirect()->route('admin.contents.index')
            ->with('success', 'Contenido creado correctamente.');
    }

    public function edit($id)
    {
        $content = Content::findOrFail($id);
        $trainings = $this->activeTrainings();

        return view('admin.contents.edit', compact('content', 'trainings'));
    }

    public function update(Request $request, $id)
    {
        $content = Content::findOrFail($id);
        $content->update($this->validatedData($request));

        return redirect()->route('admin.contents.index')
            ->with('success', 'Contenido actualizado correctamente.');
    }

    public function destroy($id)
    {
        $content = Content::findOrFail($id);
        $content->delete();

        return redirect()->route('admin.contents.index')
            ->with('success', 'Contenido eliminado correctamente.');
    }

    private function activeTrainings()
    {
        return Training::where('status', 'A')->with('course')->get();
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'training_id' => 'required|exists:trainings,training_id',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'type' => 'required|string|max:50',
            'order_index' => 'required|integer|min:1',
        ]);
    }
}
