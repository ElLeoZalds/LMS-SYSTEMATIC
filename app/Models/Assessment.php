<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $primaryKey = 'assessment_id';

    protected $fillable = [
        'training_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'allowed_attempts',
        'active',
        'time_limit'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'active'     => 'boolean',
    ];

    public function training()
    {
        return $this->belongsTo(Training::class, 'training_id', 'training_id');
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
}