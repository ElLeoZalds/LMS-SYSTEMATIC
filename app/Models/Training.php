<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\Module;

class Training extends Model
{
    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_ARCHIVED = 2;
    public const STATUS_FINISHED = 3;
    public const STATUS_DRAFT = 4;

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
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => 'integer',
        'is_active' => 'boolean',
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

    public function modules()
    {
        return $this->course?->modules() ?? collect();
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

    public function hasActiveStudents(): bool
    {
        return $this->enrollments()
            ->where('status', 'A')
            ->exists();
    }

    public function hasAcademicHistory(): bool
    {
        $hasAssessmentAttempts = $this->assessments()
            ->whereHas('attempts', fn ($q) => $q->whereNotNull('submitted_at'))
            ->exists();

        $hasTaskGrades = $this->tasks()
            ->whereHas('submissions', fn ($q) => $q->whereNotNull('grade'))
            ->exists();

        return $hasAssessmentAttempts || $hasTaskGrades;
    }

    public function isCurrentlyActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE
            && $this->end_date
            && Carbon::parse($this->end_date)->isFuture();
    }

    public function canBeDeactivated(): bool
    {
        // No puede desactivarse si tiene estudiantes activos
        if ($this->hasActiveStudents()) {
            return false;
        }

        // Si tiene historial académico pero no estudiantes activos, puede archivarse
        // pero no desactivarse completamente
        return ! $this->hasAcademicHistory();
    }

    public function getDeactivationBlockReason(): ?string
    {
        $activeStudents = $this->enrollments()
            ->where('status', 'A')
            ->count();

        if ($activeStudents > 0) {
            return "Tiene {$activeStudents} estudiantes activos en esta capacitación.";
        }

        if ($this->hasAcademicHistory()) {
            return 'Tiene historial académico (calificaciones, entregas) registrado.';
        }

        return null;
    }

    public function isFinished(): bool
    {
        return (bool) ($this->end_date && Carbon::parse($this->end_date)->endOfDay()->isPast());
    }

    public function isActive(): bool
    {
        if (array_key_exists('is_active', $this->attributes)) {
            return (bool) $this->attributes['is_active'];
        }

        if ($this->normalizedStatus() !== self::STATUS_ACTIVE) {
            return false;
        }

        if ($this->isFinished()) {
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
