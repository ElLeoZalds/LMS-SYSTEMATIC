<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    protected $table = 'especialidades';
    protected $primaryKey = 'idespecialidad';

    protected $fillable = ['especialidad'];

    public $timestamps = true;
}
