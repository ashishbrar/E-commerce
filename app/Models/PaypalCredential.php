<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaypalCredential extends Model
{
     protected $fillable = [
        'paypal_method',
        'client_id',
        'client_secret',
    ];
}
