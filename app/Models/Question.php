<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $primaryKey = 'question_id';

    protected $fillable = [
        'assessment_id',
        'question_text',
        'score',
        'order_index',
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class, 'assessment_id', 'assessment_id');
    }

    public function alternatives()
    {
        // Apuntamos al nuevo modelo y mantenemos el orden físico de la migración
        return $this->hasMany(Alternative::class, 'question_id', 'question_id')
            ->orderBy('option_id');
    }
}