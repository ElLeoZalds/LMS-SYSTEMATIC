<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    public const STATUS_DRAFT = 0;
    public const STATUS_ACTIVE = 1;

    protected $primaryKey = 'training_id';

    protected $fillable = [
        'course_id',
        'teacher_id',
        'administrator_id',
        'code',
        'modality',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => 'integer',
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

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'training_id', 'training_id');
    }

    public function attendances()
    {
        return $this->hasManyThrough(
            Attendance::class,
            Schedule::class,
            'training_id',
            'schedule_id',
            'training_id',
            'schedule_id'
        );
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'training_id', 'training_id');
    }

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

    public function normalizedStatus(): int
    {
        return is_numeric($this->status) ? (int) $this->status : self::STATUS_DRAFT;
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function isDraft(): bool
    {
        return $this->normalizedStatus() === self::STATUS_DRAFT;
    }

    public function isActive(): bool
    {
        if ($this->normalizedStatus() !== self::STATUS_ACTIVE) {
            return false;
        }

        $today = Carbon::today();

        if ($this->start_date && $today->lt(Carbon::parse($this->start_date)->startOfDay())) {
            return false;
        }

        if ($this->end_date && $today->gt(Carbon::parse($this->end_date)->endOfDay())) {
            return false;
        }

        return true;
    }

    public function isClosed(): bool
    {
        if ($this->normalizedStatus() === self::STATUS_DRAFT) {
            return true;
        }

        if ($this->end_date && Carbon::now()->gt(Carbon::parse($this->end_date)->endOfDay())) {
            return true;
        }

        return false;
    }

    public function canModifyActivities(): bool
    {
        return $this->isActive();
    }
}
