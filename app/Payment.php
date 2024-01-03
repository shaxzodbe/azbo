<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['txn_code','seller_id','amount','payment_details','payment_method','state','paycom_cancel_reason','	paycom_system_time_created','paycom_time_created','paycom_time_preform','paycom_time_canceled'];

}
