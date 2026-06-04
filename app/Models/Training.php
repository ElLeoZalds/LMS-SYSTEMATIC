<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $primaryKey = 'training_id';

    protected $fillable = [
        'course_id',
        'teacher_id',
        'administrator_id',
        'modality',
        'price',
        'creation_date',
        'status'
    ];

    protected $casts = [
        'creation_date' => 'date',
        'price'         => 'decimal:2',
    ];

    protected $appends = [
        'start_date',
        'end_date',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'user_id');
    }

    public function administrator()
    {
        return $this->belongsTo(User::class, 'administrator_id', 'user_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'training_id', 'training_id');
    }

    /**
     * Get the schedules associated with the training.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'training_id', 'training_id');
    }

    public function getStartDateAttribute()
    {
        if ($this->relationLoaded('schedules')) {
            $schedule = $this->schedules->sortBy('date')->first();
        } else {
            $schedule = $this->schedules()->orderBy('date')->first();
        }

        return $schedule ? $schedule->date : null;
    }

    public function getEndDateAttribute()
    {
        if ($this->relationLoaded('schedules')) {
            $schedule = $this->schedules->sortByDesc('date')->first();
        } else {
            $schedule = $this->schedules()->orderByDesc('date')->first();
        }

        return $schedule ? $schedule->date : null;
    }

    public function attendances()
    {
        return $this->hasManyThrough(
            Attendance::class,
            Schedule::class,
            'training_id',  // Clave foránea en tabla schedules
            'schedule_id',  // Clave foránea en tabla attendances
            'training_id',  // Clave local en tabla trainings
            'schedule_id'   // Clave local en tabla schedules
        );
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'training_id', 'training_id');
    }

    // Agrega esta relación si no existe (o cambia el nombre si tu modelo se llama distinto)
    public function tasks()
    {
        return $this->hasMany(Task::class, 'training_id', 'training_id');
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'training_id', 'training_id');
    }
}