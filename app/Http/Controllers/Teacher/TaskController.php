<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Support\ModuleSelectorHelper;
use App\Models\TaskSubmission;
use App\Models\Training;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;

class TaskController extends Controller
{
    private function isAdministrator(): bool
    {
        return auth()->user()?->roles->contains('name', 'Administrator') ?? false;
    }

    private function moduleSelectionData(Training $training): array
    {
        return ModuleSelectorHelper::loadForTraining($training);
    }

    public function create(Request $request, $training_id = null)
    {
        $trainingId = $request->input('training_id', $training_id);
        $user = auth()->user();

        $training = Training::with('course')
            ->where('training_id', $trainingId)
            ->when(! $this->isAdministrator(), fn ($query) => $query->where('teacher_id', $user->user_id))
            ->firstOrFail();

        ['modules' => $modules, 'defaultModuleId' => $defaultModuleId] = $this->moduleSelectionData($training);

        return view('teacher.tasks.create', compact('training', 'modules', 'defaultModuleId'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request, true);
        $training = $this->trainingForCurrentUser($data['training_id']);
        $dueDate = Carbon::parse($data['delivery_date'])->endOfDay();

        if ($error = $this->dateValidationError($training, $dueDate, true)) {
            return redirect()->back()->withInput()->withErrors(['delivery_date' => $error]);
        }

        $taskData = [
            'training_id' => $training->training_id,
            'module_id' => $data['module_id'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'due_date' => $dueDate,
            'file_path' => $this->storeAttachment($request),
        ];

        Task::create($taskData);

        return redirect()->route('teacher.courses.show', ['id' => $training->training_id, 'tab' => 'contenido'])
            ->with('success', 'Tarea asignada correctamente.');
    }

    public function submissions($task_id)
    {
        $task = $this->taskForCurrentUser($task_id);
        $submissions = $task->submissions()->with('student.person')->get();

        return view('teacher.tasks.submissions', compact('task', 'submissions'));
    }

    public function grade(Request $request, $submission_id)
    {
        $data = $request->validate([
            'grade' => 'required|numeric|min:0|max:20',
            'feedback' => 'nullable|string',
        ]);

        $submission = $this->submissionForCurrentUser($submission_id);
        $submission->update([
            'grade' => $data['grade'],
            'teacher_feedback' => $data['feedback'] ?? null,
            'graded_at' => now(),
        ]);

        return redirect()->route('teacher.tasks.submissions', ['task_id' => $submission->task_id])
            ->with('success', 'Entrega calificada correctamente.');
    }

    public function update(Request $request, $task_id)
    {
        $data = $this->validatedData($request);
        $task = $this->taskForCurrentUser($task_id);
        $dueDate = Carbon::parse($data['delivery_date'])->endOfDay();

        if ($error = $this->dateValidationError($task->training, $dueDate)) {
            return redirect()->back()->withInput()->withErrors(['delivery_date' => $error]);
        }

        if ($request->hasFile('attachment')) {
            $this->deleteAttachment($task->file_path);
            $task->file_path = $this->storeAttachment($request);
        }

        $task->module_id = $data['module_id'];
        $task->title = $data['title'];
        $task->description = $data['description'] ?? null;
        $task->due_date = $dueDate;
        $task->save();

        return redirect()->route('teacher.courses.show', ['id' => $task->training_id, 'tab' => 'contenido'])
            ->with('success', 'Tarea actualizada correctamente.');
    }

    public function destroy($task_id)
    {
        $task = $this->taskForCurrentUser($task_id);

        if (! $this->isAdministrator() && $task->submissions()->exists()) {
            return redirect()->route('teacher.courses.show', ['id' => $task->training_id, 'tab' => 'contenido'])
                ->with('error', 'No se puede eliminar la tarea porque ya tiene entregas registradas.');
        }

        $trainingId = $task->training_id;
        $this->deleteAttachment($task->file_path);
        $task->delete();

        return redirect()->route('teacher.courses.show', ['id' => $trainingId, 'tab' => 'contenido'])
            ->with('success', 'Tarea eliminada correctamente.');
    }

    private function validatedData(Request $request, bool $includeTraining = false): array
    {
        $rules = [
            'module_id' => 'required|exists:modules,id',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'delivery_date' => 'required|date|after_or_equal:today',
            'attachment' => 'nullable|file|max:5120|mimes:pdf,doc,docx,txt,ppt,pptx,jpg,jpeg,png,zip',
        ];

        if ($includeTraining) {
            $rules = ['training_id' => 'required|exists:trainings,training_id'] + $rules;
        }

        return $request->validate($rules);
    }

    private function trainingForCurrentUser(int $trainingId): Training
    {
        $user = auth()->user();

        return Training::where('training_id', $trainingId)
            ->when(! $this->isAdministrator(), fn ($query) => $query->where('teacher_id', $user->user_id))
            ->firstOrFail();
    }

    private function taskForCurrentUser(int $taskId): Task
    {
        $user = auth()->user();

        return Task::where('task_id', $taskId)
            ->when(! $this->isAdministrator(), fn ($query) => $query->whereHas('training', function ($query) use ($user) {
                $query->where('teacher_id', $user->user_id);
            }))
            ->firstOrFail();
    }

    private function submissionForCurrentUser(int $submissionId): TaskSubmission
    {
        $user = auth()->user();

        return TaskSubmission::where('submission_id', $submissionId)
            ->when(! $this->isAdministrator(), fn ($query) => $query->whereHas('task.training', function ($query) use ($user) {
                $query->where('teacher_id', $user->user_id);
            }))
            ->firstOrFail();
    }

    private function dateValidationError(Training $training, Carbon $dueDate, bool $validateStartDate = false): ?string
    {
        if ($validateStartDate && $training->start_date && $dueDate->lt(Carbon::parse($training->start_date)->startOfDay())) {
            return 'La fecha de entrega no puede ser anterior al inicio del curso.';
        }

        if ($training->end_date && $dueDate->gt(Carbon::parse($training->end_date)->endOfDay())) {
            return 'La fecha de entrega no puede ser posterior a la fecha de cierre del curso.';
        }

        return null;
    }

    private function storeAttachment(Request $request): ?string
    {
        if (! $request->hasFile('attachment')) {
            return null;
        }

        return $request->file('attachment')->store('task-files', 'public');
    }

    private function deleteAttachment(?string $filePath): void
    {
        if (! $filePath) {
            return;
        }

        try {
            Storage::disk('public')->delete($filePath);
        } catch (Throwable) {
        }
    }
}
