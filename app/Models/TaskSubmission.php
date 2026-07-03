<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskSubmission extends Model
{
    protected $primaryKey = 'submission_id';

    protected $fillable = [
        'task_id',
        'student_id',
        'submission_text',
        'file_path',
        'submitted_at',
        'grade',
        'teacher_feedback',
        'graded_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
        'grade' => 'decimal:2',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'task_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'user_id');
    }

    public function scopeSubmitted($query)
    {
        return $query->whereNotNull('submitted_at');
    }

    public function isSubmitted(): bool
    {
        return ! is_null($this->submitted_at);
    }

    public function isGraded(): bool
    {
        return ! is_null($this->grade);
    }
}
