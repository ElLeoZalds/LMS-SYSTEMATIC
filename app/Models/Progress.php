<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    protected $primaryKey = 'progress_id';

    public $timestamps = true;

    protected $fillable = [
        'enrollment_id',
        'content_id',
        'percentage',
        'activity_date',
        'status',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'percentage' => 'decimal:2',
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id', 'enrollment_id');
    }

    public function isCompleted(): bool
    {
        return (float) $this->percentage >= 100;
    }
}
