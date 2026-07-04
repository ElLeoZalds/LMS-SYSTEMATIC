<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Module;

class Content extends Model
{
    protected $primaryKey = 'content_id';

    protected $fillable = [
        'training_id',
        'description',
        'title',
        'type',
        'order_index',
    ];

    public function training()
    {
        return $this->belongsTo(Training::class, 'training_id', 'training_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }

    public function canBeAccessed(): bool
    {
        $training = $this->training;

        if (! $training) {
            return false;
        }

        return $training->isActive();
    }
}
