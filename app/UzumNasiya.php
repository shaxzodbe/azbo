<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UzumNasiya extends Model
{
    protected $table = 'uzum_nasiya';
    protected $fillable = [
        'id',
        'buyer_id',
        'status',
        'available_periods',
        'product_id',
        'uzum_phone',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'available_periods' => 'array'
    ];

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
