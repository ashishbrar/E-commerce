<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StripeCredential extends Model
{
    protected $fillable = [
        'stripe_key',
        'stripe_secret',
        'mode'
    ];
}
