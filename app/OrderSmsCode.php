<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderSmsCode extends Model
{
    protected $table = 'order_sms_code';
    protected $fillable = [
        'phone',
        'verification_code',
        'created_at',
        'updated_at'
    ];
}
