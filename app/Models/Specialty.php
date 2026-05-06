<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    protected $primaryKey = 'specialty_id';

    protected $fillable = [
        'specialty'
    ];

    public function courses()
    {
        return $this->hasMany(Course::class, 'specialty_id', 'specialty_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'teacher_specialties', 'specialty_id', 'user_id');
    }
}