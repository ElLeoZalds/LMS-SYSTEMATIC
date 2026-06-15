<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $primaryKey = 'task_id';

    protected $fillable = [
        'training_id',
        'title',
        'description',
        'due_date',
        'file_path',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function training()
    {
        return $this->belongsTo(Training::class, 'training_id', 'training_id');
    }

    public function submissions()
    {
        return $this->hasMany(TaskSubmission::class, 'task_id', 'task_id');
    }
}
