<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'order',
        'is_active',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    public function contents()
    {
        return $this->hasMany(Content::class, 'module_id', 'id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'module_id', 'id');
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'module_id', 'id');
    }

    public function enrollments()
    {
        return $this->belongsToMany(Enrollment::class, 'enrollment_module', 'module_id', 'enrollment_id');
    }
}
