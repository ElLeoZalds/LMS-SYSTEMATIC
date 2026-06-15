<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $primaryKey = 'method_id';

    protected $fillable = [
        'payment_method',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'payment_method_id', 'method_id');
    }
}
