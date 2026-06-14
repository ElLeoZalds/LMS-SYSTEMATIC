<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Training;
use App\Models\Task;
use App\Models\TaskSubmission;
use Carbon\Carbon;

class TaskController extends Controller
{
    /**
     * Almacena una nueva tarea asignada desde el modal.
     */
    public function store(Request $request)
    {
        $request->validate([
            'training_id'   => 'required|exists:trainings,training_id',
            'title'         => 'required|string|max:150',
            'description'   => 'nullable|string',
            'delivery_date' => 'required|date|after_or_equal:today',
            'attachment'    => 'nullable|file|max:5120|mimes:pdf,doc,docx,txt,ppt,pptx,jpg,jpeg,png,zip',
        ]);

        $user = auth()->user();

        $training = Training::where('training_id', $request->training_id)
            ->where('teacher_id', $user->user_id)
            ->firstOrFail();

        $dueDate = Carbon::parse($request->delivery_date)->endOfDay();

        if ($training->start_date) {
            $courseStart = Carbon::parse($training->start_date)->startOfDay();
            if ($dueDate->lt($courseStart)) {
                return redirect()->back()->withInput()->withErrors(['delivery_date' => 'La fecha de entrega no puede ser anterior al inicio del curso.']);
            }
        }

        if ($training->end_date) {
            $courseEnd = Carbon::parse($training->end_date)->endOfDay();
            if ($dueDate->gt($courseEnd)) {
                return redirect()->back()->withInput()->withErrors(['delivery_date' => 'La fecha de entrega no puede ser posterior a la fecha de cierre del curso.']);
            }
        }

        $filePath = null;
        if ($request->hasFile('attachment')) {
            $filePath = $request->file('attachment')->store('task-files', 'public');
        }

        Task::create([
            'training_id' => $training->training_id,
            'title'       => $request->title,
            'description' => $request->description ?? null,
            'due_date'    => Carbon::parse($request->delivery_date)->endOfDay(),
            'file_path'   => $filePath,
        ]);

        return redirect()->route('teacher.courses.show', ['id' => $training->training_id, 'tab' => 'contenido'])
            ->with('success', 'Tarea asignada correctamente.');
    }

    /**
     * Muestra la lista de alumnos y sus entregas para una tarea específica.
     */
    public function submissions($task_id)
    {
        $user = auth()->user();

        // Buscamos la tarea asegurándonos de que pertenezca a un curso del profesor logueado
        $task = Task::where('task_id', $task_id)
            ->whereHas('training', function ($query) use ($user) {
                $query->where('teacher_id', $user->user_id);
            })->firstOrFail();

        // Cargamos las entregas junto con los datos del estudiante
        $submissions = $task->submissions()->with('student.person')->get();

        return view('teacher.tasks.submissions', compact('task', 'submissions'));
    }

    /**
     * Procesa y almacena la calificación y feedback de una entrega.
     */
    public function grade(Request $request, $submission_id)
    {
        $request->validate([
            'grade'    => 'required|numeric|min:0|max:20',
            'feedback' => 'nullable|string',
        ]);

        $user = auth()->user();

        // Buscamos la entrega asegurando que pertenezca a una tarea del profesor logueado
        $submission = TaskSubmission::where('submission_id', $submission_id)
            ->whereHas('task.training', function ($query) use ($user) {
                $query->where('teacher_id', $user->user_id);
            })->firstOrFail();

        // Actualizamos los campos de la calificación
        $submission->update([
            'grade'            => $request->grade,
            'teacher_feedback' => $request->feedback,
            'graded_at'        => now(),
        ]);

        return redirect()->route('teacher.tasks.submissions', ['task_id' => $submission->task_id])
            ->with('success', 'Entrega calificada correctamente.');
    }

    /**
     * Update an existing task (allow editing title, description, due date and attachment).
     */
    public function update(Request $request, $task_id)
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'delivery_date' => 'required|date|after_or_equal:today',
            'attachment' => 'nullable|file|max:5120|mimes:pdf,doc,docx,txt,ppt,pptx,jpg,jpeg,png,zip',
        ]);

        $user = auth()->user();

        $task = Task::where('task_id', $task_id)
            ->whereHas('training', function ($q) use ($user) {
                $q->where('teacher_id', $user->user_id);
            })->firstOrFail();

        if ($task->training->end_date) {
            $courseEnd = Carbon::parse($task->training->end_date)->endOfDay();
            $dueDate = Carbon::parse($request->delivery_date)->endOfDay();

            if ($dueDate->gt($courseEnd)) {
                return redirect()->back()->withInput()->withErrors(['delivery_date' => 'La fecha de entrega no puede ser posterior a la fecha de cierre del curso.']);
            }
        }

        // handle replacement of attachment
        if ($request->hasFile('attachment')) {
            try {
                if ($task->file_path) {
                    \Storage::disk('public')->delete($task->file_path);
                }
            } catch (\Exception $e) {}
            $path = $request->file('attachment')->store('task-files', 'public');
            $task->file_path = $path;
        }

        $task->title = $request->title;
        $task->description = $request->description ?? null;
        $task->due_date = Carbon::parse($request->delivery_date)->endOfDay();
        $task->save();

        return redirect()->route('teacher.courses.show', ['id' => $task->training_id, 'tab' => 'contenido'])
            ->with('success', 'Tarea actualizada correctamente.');
    }

    /**
     * Elimina una tarea y su archivo adjunto si existe.
     */
    public function destroy($task_id)
    {
        $user = auth()->user();

        $task = Task::where('task_id', $task_id)
            ->whereHas('training', function ($q) use ($user) {
                $q->where('teacher_id', $user->user_id);
            })->firstOrFail();

        if ($task->submissions()->exists()) {
            return redirect()->route('teacher.courses.show', ['id' => $task->training_id, 'tab' => 'contenido'])
                ->with('error', 'No se puede eliminar la tarea porque ya tiene entregas registradas.');
        }

        if ($task->file_path) {
            try {
                \Storage::disk('public')->delete($task->file_path);
            } catch (\Exception $e) {
                // ignore deletion error
            }
        }

        $trainingId = $task->training_id;
        $task->delete();

        return redirect()->route('teacher.courses.show', ['id' => $trainingId, 'tab' => 'contenido'])
            ->with('success', 'Tarea eliminada correctamente.');
    }
}
