<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskSubmission extends Model
{
    // Clave primaria personalizada
    protected $primaryKey = 'submission_id';

    protected $fillable = [
        'task_id',
        'student_id',
        'submission_text',
        'file_path',
        'submitted_at',
        'grade',
        'teacher_feedback',
        'graded_at'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'graded_at'    => 'datetime',
        'grade'        => 'decimal:2',
    ];

    /**
     * Relación: Una entrega pertenece a una tarea específica.
     */
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'task_id');
    }

    /**
     * Relación: Una entrega pertenece a un estudiante (User).
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'user_id');
    }
}