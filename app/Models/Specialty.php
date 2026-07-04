<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    public const STATUS_ACTIVE = 'A';
    public const STATUS_INACTIVE = 'I';

    protected $primaryKey = 'specialty_id';

    protected $fillable = [
        'specialty',
        'status',
        'is_active',
    ];

    public function courses()
    {
        return $this->hasMany(Course::class, 'specialty_id', 'specialty_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'teacher_specialties', 'specialty_id', 'user_id');
    }

    public function isActive(): bool
    {
        if (array_key_exists('is_active', $this->attributes)) {
            return (bool) $this->attributes['is_active'];
        }

        return (string) ($this->status ?? self::STATUS_ACTIVE) === self::STATUS_ACTIVE;
    }
}
