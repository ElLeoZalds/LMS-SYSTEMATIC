<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    // Forzamos el plural correcto en inglés para la tabla
    protected $table = 'people';
    protected $primaryKey = 'person_id';

    protected $fillable = [
        'first_names',
        'last_names',
        'document_type',
        'document_number',
        'phone',
        'address',
        'email',
        'gender',
        'birth_date'
    ];

    /**
     * Get the user account associated with the person.
     */
    public function user()
    {
        return $this->hasOne(User::class, 'person_id', 'person_id');
    }
}