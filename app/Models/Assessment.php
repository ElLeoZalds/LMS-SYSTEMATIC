<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Module;

class Assessment extends Model
{
    protected $primaryKey = 'assessment_id';

    protected $fillable = [
        'training_id',
        'module_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'allowed_attempts',
        'active',
        'time_limit',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'active' => 'boolean',
    ];

    public function training()
    {
        return $this->belongsTo(Training::class, 'training_id', 'training_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'assessment_id', 'assessment_id')
            ->orderBy('order_index');
    }

    public function attempts()
    {
        return $this->hasMany(AssessmentAttempt::class, 'assessment_id', 'assessment_id');
    }

    public function averageSubmittedScore(): float
    {
        $submittedAttempts = $this->attempts->filter(fn ($attempt) => $attempt->isSubmitted());

        if ($submittedAttempts->isEmpty()) {
            return 0.0;
        }

        return round($submittedAttempts->avg('score'), 1);
    }

    public function isAvailableOnDate(?\Carbon\Carbon $date = null): bool
    {
        $today = ($date ?? \Carbon\Carbon::today())->startOfDay();

        if (! $this->active) {
            return false;
        }

        if ($this->start_date) {
            $startDate = $this->start_date instanceof \Carbon\Carbon ? $this->start_date->startOfDay() : \Carbon\Carbon::parse($this->start_date)->startOfDay();
            if ($today->lt($startDate)) {
                return false;
            }
        }

        if ($this->end_date) {
            $endDate = $this->end_date instanceof \Carbon\Carbon ? $this->end_date->endOfDay() : \Carbon\Carbon::parse($this->end_date)->endOfDay();
            if ($today->gt($endDate)) {
                return false;
            }
        }

        return true;
    }
}
