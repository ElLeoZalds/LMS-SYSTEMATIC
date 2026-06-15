<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
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
        'birth_date',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'person_id', 'person_id');
    }
}
