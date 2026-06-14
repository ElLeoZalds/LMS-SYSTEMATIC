<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $primaryKey = 'training_id';

    protected $fillable = [
        'course_id',
        'teacher_id',
        'administrator_id',
        'nrc',
        'modality',
        'start_date',
        'end_date',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'status'     => 'integer',
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

    public function contents()
    {
        return $this->hasMany(Content::class, 'training_id', 'training_id');
    }

    public function normalizedStatus(): string
    {
        return trim((string) $this->status);
    }

    public function isDraft(): bool
    {
        return in_array($this->normalizedStatus(), ['0', 'DRAFT', 'D'], true);
    }

    public function isActive(): bool
    {
        $startDate = $this->start_date ? Carbon::parse($this->start_date)->startOfDay() : null;
        $endDate = $this->end_date ? Carbon::parse($this->end_date)->endOfDay() : null;
        $today = Carbon::today();

        if ($startDate && $today->lt($startDate)) {
            return false;
        }

        if ($endDate && $today->gt($endDate)) {
            return false;
        }

        if (in_array($this->normalizedStatus(), ['0', 'C', 'CLOSED'], true)) {
            return false;
        }

        if ($startDate || $endDate) {
            return ! $this->isClosed();
        }

        return in_array($this->normalizedStatus(), ['1', 'ACTIVE', 'A'], true);
    }

    public function isClosed(): bool
    {
        $endDate = $this->end_date ? Carbon::parse($this->end_date)->endOfDay() : null;

        if ($endDate && Carbon::now()->gt($endDate)) {
            return true;
        }

        return in_array($this->normalizedStatus(), ['0', 'CLOSED', 'C'], true);
    }

    public function canModifyActivities(): bool
    {
        return $this->isActive() && ! $this->isClosed();
    }
}
