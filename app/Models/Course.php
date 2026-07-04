<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Module;

class Course extends Model
{
    public const STATUS_ACTIVE = 'A';
    public const STATUS_INACTIVE = 'I';

    protected $primaryKey = 'course_id';

    protected $fillable = [
        'specialty_id',
        'banner_path',
        'title',
        'abbreviation',
        'description',
        'hours_count',
        'reference_price',
        'status',
        'is_active',
    ];

    public function trainings()
    {
        return $this->hasMany(Training::class, 'course_id', 'course_id');
    }

    public function modules()
    {
        return $this->hasMany(Module::class, 'course_id', 'course_id');
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class, 'specialty_id', 'specialty_id');
    }

    public function isActive(): bool
    {
        if (array_key_exists('is_active', $this->attributes)) {
            return (bool) $this->attributes['is_active'];
        }

        return (string) ($this->status ?? self::STATUS_ACTIVE) === self::STATUS_ACTIVE;
    }
}
