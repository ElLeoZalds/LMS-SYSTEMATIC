<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'cursos';
    protected $primaryKey = 'idcurso';
    public $timestamps = false;

    protected $fillable = [
        'idespecialidad',
        'pathbanner',
        'titulo',
        'descripcion',
        'cantidadhoras',
        'precioreferencial'
    ];

    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class, 'idespecialidad', 'idespecialidad');
    }
}
