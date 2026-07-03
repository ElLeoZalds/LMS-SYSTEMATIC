<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentAttempt extends Model
{
    protected $table = 'assessment_attempts';

    protected $primaryKey = 'attempt_id';

    protected $fillable = [
        'enrollment_id',
        'assessment_id',
        'number',
        'date',
        'score',
        'submitted_at',
    ];

    protected $casts = [
        'date' => 'date',
        'score' => 'decimal:2',
        'submitted_at' => 'datetime',
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id', 'enrollment_id');
    }

    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            Enrollment::class,
            'enrollment_id',
            'user_id',
            'enrollment_id',
            'student_id'
        );
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class, 'assessment_id', 'assessment_id');
    }

    public function scopePending($query)
    {
        return $query->whereNull('submitted_at');
    }

    public function scopeSubmitted($query)
    {
        return $query->whereNotNull('submitted_at');
    }

    public function isPending(): bool
    {
        return $this->submitted_at === null;
    }

    public function isSubmitted(): bool
    {
        return ! $this->isPending();
    }

    public function isPassed(): bool
    {
        return $this->isSubmitted() && (float) $this->score > 0;
    }
}
