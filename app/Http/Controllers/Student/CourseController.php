<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentAttempt;
use App\Models\Enrollment;
use App\Models\Training;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CourseController extends Controller
{
    public function show($id)
    {
        $studentId = auth()->id();

        $isEnrolled = Enrollment::where('student_id', $studentId)
            ->where('training_id', $id)
            ->exists();

        if (! $isEnrolled) {
            abort(403, 'No estás inscrito en esta capacitación.');
        }

        $training = Training::with(['course', 'teacher.person', 'assessments'])
            ->where('training_id', $id)
            ->firstOrFail();

        $enrollment = Enrollment::where('student_id', $studentId)
            ->where('training_id', $id)
            ->firstOrFail();

        $attempts = AssessmentAttempt::where('enrollment_id', $enrollment->enrollment_id)
            ->with('assessment')
            ->orderByDesc('created_at')
            ->get();

        return view('student.courses.show', compact('training', 'attempts'));
    }

    public function takeExam($assessment_id)
    {
        $studentId = auth()->id();

        $assessment = Assessment::with('questions.options')
            ->where('assessment_id', $assessment_id)
            ->firstOrFail();

        $enrollment = Enrollment::where('student_id', $studentId)
            ->where('training_id', $assessment->training_id)
            ->firstOrFail();

        $this->validateAssessmentAvailability($assessment);

        $pendingAttempt = AssessmentAttempt::where('enrollment_id', $enrollment->enrollment_id)
            ->where('assessment_id', $assessment_id)
            ->whereColumn('created_at', 'updated_at')
            ->latest('attempt_id')
            ->first();

        if ($pendingAttempt) {
            $attempt = $pendingAttempt;
        } else {
            $this->ensureAttemptAllowed($assessment, $enrollment);

            $attemptNumber = AssessmentAttempt::where('enrollment_id', $enrollment->enrollment_id)
                ->where('assessment_id', $assessment_id)
                ->count() + 1;

            $attempt = AssessmentAttempt::create([
                'enrollment_id' => $enrollment->enrollment_id,
                'assessment_id' => $assessment_id,
                'number' => $attemptNumber,
                'date' => Carbon::now()->toDateString(),
                'score' => 0,
            ]);
        }

        $timeLimit = 60;

        return view('student.courses.take', compact('assessment', 'timeLimit', 'enrollment', 'attempt'));
    }

    public function submitExam(Request $request, $assessment_id)
    {
        $studentId = auth()->id();

        $assessment = Assessment::with('questions.options')
            ->where('assessment_id', $assessment_id)
            ->firstOrFail();

        $enrollment = Enrollment::where('student_id', $studentId)
            ->where('training_id', $assessment->training_id)
            ->firstOrFail();

        $this->validateAssessmentAvailability($assessment);

        $validated = $request->validate([
            'attempt_id' => 'required|integer|exists:assessment_attempts,attempt_id',
            'answers' => 'required|array',
            'answers.*' => 'nullable|integer|exists:alternatives,option_id',
        ]);

        $attempt = AssessmentAttempt::where('attempt_id', $validated['attempt_id'])
            ->where('enrollment_id', $enrollment->enrollment_id)
            ->where('assessment_id', $assessment_id)
            ->firstOrFail();

        if ($attempt->created_at->ne($attempt->updated_at)) {
            abort(403, 'Este intento ya fue enviado.');
        }

        $this->ensureAttemptAllowed($assessment, $enrollment, $attempt);

        $timeLimit = 60;
        $elapsedSeconds = Carbon::now()->diffInSeconds($attempt->created_at);
        $maxSeconds = ($timeLimit * 60) + 120;

        if ($elapsedSeconds > $maxSeconds) {
            $attempt->score = 0;
            $attempt->save();
            $attempt->load('assessment');

            return view('student.assessments.result', compact('attempt'));
        }

        $totalScore = 0;
        $responses = $validated['answers'] ?? [];

        foreach ($assessment->questions as $question) {
            $selectedOptionId = $responses[$question->question_id] ?? null;

            if ($selectedOptionId) {
                $selectedOption = $question->options()
                    ->where('option_id', $selectedOptionId)
                    ->first();

                if ($selectedOption && $selectedOption->is_correct) {
                    $totalScore += $question->score;
                }
            }
        }

        $attempt->score = $totalScore;
        $attempt->save();
        $attempt->load('assessment');

        return view('student.assessments.result', compact('attempt'));

    }

    private function validateAssessmentAvailability(Assessment $assessment)
    {
        if (! $assessment->active) {
            abort(403, 'Esta evaluación no está disponible.');
        }

        $today = Carbon::now()->toDateString();
        if ($today < $assessment->start_date || $today > $assessment->end_date) {
            abort(403, 'Esta evaluación está fuera de las fechas permitidas.');
        }
    }

    private function ensureAttemptAllowed(Assessment $assessment, Enrollment $enrollment, AssessmentAttempt $currentAttempt = null)
    {
        $attemptQuery = AssessmentAttempt::where('enrollment_id', $enrollment->enrollment_id)
            ->where('assessment_id', $assessment->assessment_id);

        if ($currentAttempt) {
            $attemptQuery->where('attempt_id', '!=', $currentAttempt->attempt_id);
        }

        $previousAttempts = $attemptQuery->count();

        if ($previousAttempts >= $assessment->allowed_attempts) {
            abort(403, 'Ha alcanzado el número máximo de intentos permitidos.');
        }
    }
}
