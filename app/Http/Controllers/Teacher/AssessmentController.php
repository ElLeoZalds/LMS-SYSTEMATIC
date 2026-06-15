<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Alternative;
use App\Models\Assessment;
use App\Models\Question;
use App\Models\Training;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class AssessmentController extends Controller
{
    private const MAX_TOTAL_SCORE = 20;

    private function isAdministrator(): bool
    {
        return auth()->user()?->roles->contains('name', 'Administrator') ?? false;
    }

    public function index()
    {
        $user = auth()->user();
        $trainings = Training::with('course')
            ->when(! $this->isAdministrator(), fn ($query) => $query->where('teacher_id', $user->user_id))
            ->get();

        return view('teacher.assessments.index', compact('trainings'));
    }

    public function show($training_id)
    {
        $user = auth()->user();

        $training = Training::with([
            'course',
            'assessments.questions.alternatives',
            'assessments.attempts.user',
        ])
            ->where('training_id', $training_id)
            ->when(! $this->isAdministrator(), fn ($query) => $query->where('teacher_id', $user->user_id))
            ->firstOrFail();

        return view('teacher.assessments.manage', compact('training'));
    }

    public function showAssessment($assessment_id)
    {
        $user = auth()->user();

        $assessment = Assessment::with(['questions.alternatives', 'training.course'])
            ->where('assessment_id', $assessment_id)
            ->firstOrFail();

        if (! $this->isAdministrator() && $assessment->training->teacher_id !== $user->user_id) {
            abort(403, 'No autorizado.');
        }

        return view('teacher.assessments.show_assessment', compact('assessment'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'training_id' => 'required|exists:trainings,training_id',
            'title' => 'required|string|max:150',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'allowed_attempts' => 'required|integer|min:1|max:3',
            'time_limit' => 'nullable|integer|min:20|max:60',
            'active' => 'sometimes|boolean',
            'description' => 'nullable|string',
        ]);

        $user = auth()->user();
        $training = Training::where('training_id', $request->training_id)
            ->when(! $this->isAdministrator(), fn ($query) => $query->where('teacher_id', $user->user_id))
            ->firstOrFail();

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        if ($training->start_date) {
            $courseStart = Carbon::parse($training->start_date)->startOfDay();
            if ($startDate->lt($courseStart)) {
                return redirect()->back()->withInput()->withErrors(['start_date' => 'La fecha de inicio no puede ser anterior al inicio del curso.']);
            }
        }

        if ($training->end_date) {
            $courseEnd = Carbon::parse($training->end_date)->endOfDay();
            if ($startDate->gt($courseEnd)) {
                return redirect()->back()->withInput()->withErrors(['start_date' => 'La fecha de inicio no puede ser posterior a la fecha de cierre del curso.']);
            }

            if ($endDate->gt($courseEnd)) {
                return redirect()->back()->withInput()->withErrors(['end_date' => 'La fecha de fin no puede ser posterior a la fecha de cierre del curso.']);
            }
        }

        Assessment::create([
            'training_id' => $training->training_id,
            'title' => $request->title,
            'description' => $request->description ?? null,
            'start_date' => $request->start_date,
            'end_date' => Carbon::parse($request->end_date)->endOfDay()->toDateString(),
            'allowed_attempts' => $request->allowed_attempts,
            'time_limit' => $request->time_limit ?? 60,
            'active' => $request->has('active'),
        ]);

        return redirect()->route('teacher.courses.show', ['id' => $training->training_id, 'tab' => 'contenido'])
            ->with('success', 'Evaluación creada correctamente.');
    }

    public function addQuestion(Request $request, $assessment_id)
    {
        $request->validate([
            'question_text' => 'required|string',
            'alternatives' => 'required|array|min:2|max:5',
            'alternatives.*.text' => 'required|string',
            'correct_alternative' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $user = auth()->user();
        $assessment = Assessment::with('training')->findOrFail($assessment_id);

        if (! $this->isAdministrator() && $assessment->training->teacher_id !== $user->user_id) {
            abort(403, 'No autorizado.');
        }

        DB::transaction(function () use ($request, $assessment) {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('question-images', 'public');
            }

            $question = Question::create([
                'assessment_id' => $assessment->assessment_id,
                'question_text' => $request->question_text,
                'score' => 0,
                'order_index' => $assessment->questions()->count() + 1,
                'image_path' => $imagePath,
            ]);

            foreach ($request->alternatives as $index => $alternativeData) {
                Alternative::create([
                    'question_id' => $question->question_id,
                    'option_text' => $alternativeData['text'],
                    'is_correct' => $request->correct_alternative == $index,
                ]);
            }
        });

        return redirect()->route('teacher.assessments.show', ['assessment_id' => $assessment->assessment_id])
            ->with('success', 'Pregunta agregada.');
    }

    public function updateQuestion(Request $request, $question_id)
    {
        $request->validate([
            'question_text' => 'required|string',
            'alternatives' => 'required|array|min:2|max:5',
            'alternatives.*.text' => 'required|string',
            'correct_alternative' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $user = auth()->user();
        $question = Question::with('assessment.training')->findOrFail($question_id);

        if (! $this->isAdministrator() && $question->assessment->training->teacher_id !== $user->user_id) {
            abort(403, 'No autorizado.');
        }

        DB::transaction(function () use ($request, $question) {
            if ($request->hasFile('image')) {
                try {
                    if ($question->image_path) {
                        Storage::disk('public')->delete($question->image_path);
                    }
                } catch (Throwable) {
                }
                $imagePath = $request->file('image')->store('question-images', 'public');
                $question->image_path = $imagePath;
            }

            $question->update(['question_text' => $request->question_text, 'score' => $question->score, 'image_path' => $question->image_path ?? null]);
            $question->alternatives()->delete();

            foreach ($request->alternatives as $index => $alternativeData) {
                Alternative::create([
                    'question_id' => $question->question_id,
                    'option_text' => $alternativeData['text'],
                    'is_correct' => $request->correct_alternative == $index,
                ]);
            }
        });

        return redirect()->route('teacher.assessments.show', ['assessment_id' => $question->assessment->assessment_id])
            ->with('success', 'Pregunta actualizada.');
    }

    public function destroyQuestion($question_id)
    {
        $user = auth()->user();
        $question = Question::with('assessment.training')->findOrFail($question_id);

        if (! $this->isAdministrator() && $question->assessment->training->teacher_id !== $user->user_id) {
            abort(403, 'No autorizado.');
        }

        if (! $this->isAdministrator() && $question->assessment->attempts()->exists()) {
            return redirect()->back()->with('error', 'La evaluación ya tiene intentos.');
        }

        $trainingId = $question->assessment->training_id;
        $assessmentId = $question->assessment->assessment_id;
        DB::transaction(function () use ($question) {
            $question->alternatives()->delete();
            try {
                if ($question->image_path) {
                    \Storage::disk('public')->delete($question->image_path);
                }
            } catch (\Exception $e) {
            }
            $question->delete();
        });

        return redirect()->route('teacher.assessments.show', ['assessment_id' => $assessmentId]);
    }

    public function updateQuestionScore(Request $request, $question_id)
    {
        $request->validate([
            'score' => 'required|integer|min:0|max:20',
        ]);

        $user = auth()->user();
        $question = Question::with('assessment.training')->findOrFail($question_id);

        if (! $this->isAdministrator() && $question->assessment->training->teacher_id !== $user->user_id) {
            abort(403, 'No autorizado.');
        }

        $otherQuestionsScore = (int) Question::where('assessment_id', $question->assessment_id)
            ->where('question_id', '!=', $question->question_id)
            ->sum('score');

        if (($otherQuestionsScore + (int) $request->score) > self::MAX_TOTAL_SCORE) {
            return redirect()->back()->with('error', 'El puntaje total de la evaluación no puede superar los 20 puntos.');
        }

        $question->update(['score' => (int) $request->score]);

        return redirect()->back()->with('success', 'Puntaje actualizado correctamente.');
    }

    public function update(Request $request, $assessment_id)
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'allowed_attempts' => 'required|integer|min:1|max:3',
            'time_limit' => 'nullable|integer|min:20|max:60',
            'active' => 'sometimes|boolean',
        ]);

        $user = auth()->user();
        $assessment = Assessment::with('training')->where('assessment_id', $assessment_id)->firstOrFail();

        if (! $this->isAdministrator() && $assessment->training->teacher_id !== $user->user_id) {
            abort(403, 'No autorizado.');
        }

        if ($assessment->training->end_date) {
            $courseEnd = Carbon::parse($assessment->training->end_date)->endOfDay();
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date)->endOfDay();

            if ($startDate->gt($courseEnd)) {
                return redirect()->back()->withInput()->withErrors(['start_date' => 'La fecha de inicio no puede ser posterior a la fecha de cierre del curso.']);
            }

            if ($endDate->gt($courseEnd)) {
                return redirect()->back()->withInput()->withErrors(['end_date' => 'La fecha de fin no puede ser posterior a la fecha de cierre del curso.']);
            }
        }

        if ($assessment->attempts()->exists()) {
            return redirect()->back()->withErrors(['assessment' => 'No se puede modificar.']);
        }

        $assessment->update([
            'title' => $request->title,
            'start_date' => $request->start_date,
            'end_date' => Carbon::parse($request->end_date)->endOfDay()->toDateString(),
            'allowed_attempts' => $request->allowed_attempts,
            'time_limit' => $request->time_limit,
            'active' => $request->has('active'),
        ]);

        return redirect()->route('teacher.assessments.manage', ['training_id' => $assessment->training_id]);
    }

    public function destroy($assessment_id)
    {
        $user = auth()->user();
        $assessment = Assessment::with('training')->where('assessment_id', $assessment_id)->firstOrFail();

        if (! $this->isAdministrator() && $assessment->training->teacher_id !== $user->user_id) {
            abort(403, 'No autorizado.');
        }

        $trainingId = $assessment->training_id;
        if (! $this->isAdministrator() && $assessment->attempts()->exists()) {
            return redirect()->route('teacher.courses.show', ['id' => $trainingId, 'tab' => 'contenido'])
                ->with('error', 'No se puede eliminar la evaluación porque ya tiene intentos registrados.');
        }

        $assessment->delete();

        return redirect()->route('teacher.courses.show', ['id' => $trainingId, 'tab' => 'contenido'])
            ->with('success', 'Evaluación eliminada correctamente.');
    }
}
