<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $primaryKey = 'attendance_id';

    protected $fillable = [
        'schedule_id',
        'enrollment_id',
        'attendance'
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'schedule_id');
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id', 'enrollment_id');
    }

    public function student()
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

    public function scopeForDate($query, $date)
    {
        return $query->where('date', $date);
    }
}