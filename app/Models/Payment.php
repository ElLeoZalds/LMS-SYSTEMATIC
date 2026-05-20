<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'enrollment_id',
        'payment_method_id',
        'date',
        'installment',
        'amount',
        'status'
    ];

    protected $casts = [
        'date'        => 'date',
        'installment' => 'decimal:2',
        'amount'      => 'decimal:2',
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id', 'enrollment_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'method_id');
    }
}