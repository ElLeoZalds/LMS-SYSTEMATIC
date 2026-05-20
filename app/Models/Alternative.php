<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alternative extends Model
{
    // Laravel asociará automáticamente este modelo a la tabla 'alternatives'
    protected $primaryKey = 'option_id';

    protected $fillable = [
        'question_id',
        'option_text',
        'is_correct',
    ];

    /**
     * Get the question that owns the alternative.
     */
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'question_id');
    }
}