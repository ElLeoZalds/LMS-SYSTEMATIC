<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TaskSubmission;
use App\Models\AssessmentAttempt;
use App\Models\Module;

class Enrollment extends Model
{
    public const STATUS_PENDING = 'P';
    public const STATUS_ACTIVE = 'A';
    public const STATUS_COMPLETED = 'C';

    protected $primaryKey = 'enrollment_id';

    protected $fillable = [
        'training_id',
        'student_id',
        'administrator_id',
        'enrollment_date',
        'scholarship_percentage',
        'status',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'scholarship_percentage' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'user_id');
    }

    public function training()
    {
        return $this->belongsTo(Training::class, 'training_id', 'training_id');
    }

    public function administrator()
    {
        return $this->belongsTo(User::class, 'administrator_id', 'user_id');
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'enrollment_module', 'enrollment_id', 'module_id');
    }

    public function progress()
    {
        return $this->hasMany(Progress::class, 'enrollment_id', 'enrollment_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'enrollment_id', 'enrollment_id');
    }

    public function completedContentsCount()
    {
        if (! $this->training) {
            return 0;
        }

        $contentIds = $this->training->contents->pluck('content_id')->all();

        if (empty($contentIds)) {
            return 0;
        }

        return $this->progress
            ->filter(fn ($progress) => in_array($progress->content_id, $contentIds, true))
            ->filter(fn ($progress) => (float) $progress->percentage >= 100)
            ->unique('content_id')
            ->count();
    }

    public function completedTasksCount()
    {
        if (! $this->training) {
            return 0;
        }

        $taskIds = $this->training->tasks->pluck('task_id')->all();

        if (empty($taskIds)) {
            return 0;
        }

        return TaskSubmission::where('student_id', $this->student_id)
            ->whereIn('task_id', $taskIds)
            ->submitted()
            ->distinct('task_id')
            ->count('task_id');
    }

    public function completedAssessmentsCount()
    {
        return AssessmentAttempt::where('enrollment_id', $this->enrollment_id)
            ->submitted()
            ->distinct('assessment_id')
            ->count('assessment_id');
    }

    public function completedActivitiesCount()
    {
        return $this->completedContentsCount()
            + $this->completedTasksCount()
            + $this->completedAssessmentsCount();
    }

    public function totalActivitiesCount()
    {
        if (! $this->training) {
            return 0;
        }

        return $this->training->contents->count()
            + $this->training->tasks->count()
            + $this->training->assessments->count();
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->isCompleted() || $this->training?->isClosed()) {
            return 100;
        }

        $total = $this->totalActivitiesCount();
        if ($total === 0) {
            return 0;
        }

        return (int) round(($this->completedActivitiesCount() / $total) * 100);
    }

    public function isCompleted()
    {
        return strtoupper(trim((string) $this->status)) === self::STATUS_COMPLETED;
    }

    public function isInProgress()
    {
        return strtoupper(trim((string) $this->status)) === self::STATUS_ACTIVE;
    }

    public function isNotStarted()
    {
        return strtoupper(trim((string) $this->status)) === self::STATUS_PENDING;
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function canReceiveCertificate(float $minimumAverage = 13.0): bool
    {
        return $this->calculateAverage() >= $minimumAverage;
    }

    public function calculateAverage()
    {
        $training = $this->training;
        if (!$training) {
            return 0;
        }

        $totalNotes = 0;
        $notesCount = 0;

        // Tasks grades
        if ($training->tasks && $training->tasks->count() > 0) {
            $taskIds = $training->tasks->pluck('task_id')->toArray();
            $submissions = TaskSubmission::whereIn('task_id', $taskIds)
                ->where('student_id', $this->student_id)
                ->submitted()
                ->get();
            
            foreach ($submissions as $submission) {
                if (!is_null($submission->grade)) {
                    $totalNotes += $submission->grade;
                    $notesCount++;
                }
            }
        }

        // Assessments attempts grades
        if ($training->assessments && $training->assessments->count() > 0) {
            foreach ($training->assessments as $assessment) {
                $maxAttemptScore = AssessmentAttempt::where('enrollment_id', $this->enrollment_id)
                    ->where('assessment_id', $assessment->assessment_id)
                    ->submitted()
                    ->max('score');

                if (!is_null($maxAttemptScore)) {
                    $totalNotes += $maxAttemptScore;
                    $notesCount++;
                }
            }
        }

        return $notesCount > 0 ? round($totalNotes / $notesCount, 1) : 0;
    }
}
