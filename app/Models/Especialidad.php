<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    protected $table = 'especialidades';
    protected $primaryKey = 'idespecialidad';
    public $timestamps = true;
    protected $fillable = ['especialidad'];
}
