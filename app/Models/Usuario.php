<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'idusuario';

    protected $fillable = [
        'idpersona',
        'username',
        'password',
        'estado',
        'fechacreacion',
        'fechamodificacion'
    ];

    public $timestamps = false;
}
