<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $primaryKey = 'schedule_id';

    protected $fillable = [
        'training_id',
        'date',
        'start_time',
        'end_time'
    ];

    public function training()
    {
        return $this->belongsTo(Training::class, 'training_id', 'training_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'schedule_id', 'schedule_id');
    }
}