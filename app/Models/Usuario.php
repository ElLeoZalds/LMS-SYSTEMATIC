<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Role;

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

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'usuariosroles', 'idusuario', 'idrol', 'idusuario', 'idrol');
    }
}
