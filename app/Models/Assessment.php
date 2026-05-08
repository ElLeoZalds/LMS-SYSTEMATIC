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
        'active'
    ];

    public function training()
    {
        return $this->belongsTo(Training::class, 'training_id', 'training_id');
    }
}