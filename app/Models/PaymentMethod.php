<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $primaryKey = 'method_id';

    protected $fillable = [
        'payment_method'
    ];

    public function payments()
    {
        // Primer parámetro: Llave foránea en la tabla payments ('payment_method_id')
        // Segundo parámetro: Llave primaria en la tabla actual ('method_id')
        return $this->hasMany(Payment::class, 'payment_method_id', 'method_id');
    }
}