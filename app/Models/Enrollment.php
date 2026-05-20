<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $primaryKey = 'enrollment_id';

    protected $fillable = [
        'training_id',
        'student_id',
        'administrator_id',
        'enrollment_date',
        'scholarship_percentage',
        'status'
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'scholarship_percentage' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'user_id');
    }

    public function training()
    {
        return $this->belongsTo(Training::class, 'training_id', 'training_id');
    }

    public function administrator()
    {
        return $this->belongsTo(User::class, 'administrator_id', 'user_id');
    }

    public function progress()
    {
        return $this->hasMany(Progress::class, 'enrollment_id', 'enrollment_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'enrollment_id', 'enrollment_id');
    }
}