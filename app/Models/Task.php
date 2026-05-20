<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // Indicar la clave primaria personalizada si no usas 'id' a secas
    protected $primaryKey = 'task_id';

    protected $fillable = [
        'training_id',
        'title',
        'description',
        'due_date'
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    /**
     * Relación inversa: Una tarea pertenece a un curso dictado (Training).
     */
    public function training()
    {
        return $this->belongsTo(Training::class, 'training_id', 'training_id');
    }

    /**
     * Relación directa: Una tarea tiene muchas entregas hechas por los alumnos.
     */
    public function submissions()
    {
        return $this->hasMany(TaskSubmission::class, 'task_id', 'task_id');
    }
}