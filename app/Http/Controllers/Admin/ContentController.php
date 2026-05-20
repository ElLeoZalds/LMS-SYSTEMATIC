<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Training;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    /**
     * Display a listing of contents.
     */
    public function index()
    {
        $contents = Content::with(['training.course'])
            ->orderBy('training_id', 'asc')
            ->orderBy('order_index', 'asc')
            ->get();

        $trainings = Training::where('status', 'A')->get();

        return view('admin.contents.index', compact('contents', 'trainings'));
    }

    /**
     * Show the form for creating a new content.
     */
    public function create()
    {
        $trainings = Training::where('status', 'A')->with('course')->get();

        return view('admin.contents.create', compact('trainings'));
    }

    /**
     * Store a newly created content in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'training_id' => 'required|exists:trainings,training_id',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'type' => 'required|string|max:50',
            'order_index' => 'required|integer|min:1',
        ]);

        Content::create([
            'training_id' => $request->training_id,
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'order_index' => $request->order_index,
        ]);

        return redirect()->route('admin.contents.index')
            ->with('success', 'Contenido creado correctamente.');
    }

    /**
     * Show the form for editing the specified content.
     */
    public function edit($id)
    {
        $content = Content::findOrFail($id);
        $trainings = Training::where('status', 'A')->with('course')->get();

        return view('admin.contents.edit', compact('content', 'trainings'));
    }

    /**
     * Update the specified content in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'training_id' => 'required|exists:trainings,training_id',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'type' => 'required|string|max:50',
            'order_index' => 'required|integer|min:1',
        ]);

        $content = Content::findOrFail($id);

        $content->update([
            'training_id' => $request->training_id,
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'order_index' => $request->order_index,
        ]);

        return redirect()->route('admin.contents.index')
            ->with('success', 'Contenido actualizado correctamente.');
    }

    /**
     * Remove the specified content from storage.
     */
    public function destroy($id)
    {
        $content = Content::findOrFail($id);
        $content->delete();

        return redirect()->route('admin.contents.index')
            ->with('success', 'Contenido eliminado correctamente.');
    }
}
