<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Module;
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
        $modules = Module::where('is_active', true)->orderBy('order')->get();

        return view('admin.contents.create', compact('trainings', 'modules'));
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
        $modules = Module::where('is_active', true)->orderBy('order')->get();
        $selectedTraining = $content->training;
        $courseId = $selectedTraining?->course_id;

        if ($courseId) {
            $modules = Module::where('course_id', $courseId)->where('is_active', true)->orderBy('order')->get();
        }

        return view('admin.contents.edit', compact('content', 'trainings', 'modules'));
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
        return Training::where('status', Training::STATUS_ACTIVE)->with('course')->get();
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'training_id' => 'required|exists:trainings,training_id',
            'module_id' => 'required|exists:modules,module_id',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'type' => 'required|string|max:50',
            'order_index' => 'required|integer|min:1',
        ]);
    }
}
