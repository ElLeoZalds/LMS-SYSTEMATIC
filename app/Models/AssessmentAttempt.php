<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentAttempt extends Model
{
    protected $table = 'assessment_attempts';
    protected $primaryKey = 'attempt_id';

    protected $fillable = [
        'enrollment_id',
        'assessment_id',
        'number',
        'date',
        'score',
    ];

    protected $casts = [
        'date'  => 'date',
        'score' => 'decimal:2',
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id', 'enrollment_id');
    }

    // AÑADE ESTA RELACIÓN CORRECTA
    public function user()
    {
        // Usamos hasOneThrough para saltar desde AssessmentAttempt -> Enrollment -> User
        // 1. Destino: User
        // 2. Intermedio: Enrollment
        // 3. FK en Enrollment: enrollment_id
        // 4. FK en User: user_id
        // 5. Local en Attempt: enrollment_id
        // 6. Local en Enrollment: student_id (¡Aquí estaba el cambio!)
        return $this->hasOneThrough(
            User::class, 
            Enrollment::class, 
            'enrollment_id', // FK en enrollments
            'user_id',       // PK en users
            'enrollment_id', // FK en assessment_attempts
            'student_id'     // FK en enrollments que apunta a users
        );
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class, 'assessment_id', 'assessment_id');
    }
}