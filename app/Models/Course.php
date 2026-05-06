<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $primaryKey = 'course_id';

    protected $fillable = [
        'specialty_id',
        'banner_path',
        'title',
        'description',
        'hours_count',
        'reference_price'
    ];

    public function trainings()
    {
        return $this->hasMany(Training::class, 'course_id', 'course_id');
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class, 'specialty_id', 'specialty_id');
    }
}