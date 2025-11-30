<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'stripe_id',
        'amount',
        'amount_captured',
        'platform_fee',
        'currency',
        'status',
        'description',
    ];
}
