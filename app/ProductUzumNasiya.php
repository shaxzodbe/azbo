<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductUzumNasiya extends Model
{
    protected $table = 'product_uzum_nasiya';
    protected $fillable = [
        'id',
        'product_id',
        'user_id',
        'bearer_token',
        'ref_id',
        'product_price',
        'product_monthly_price',
        'uzum_nasiya_phone',
        'created_at',
        'updated_at'
    ];

    public function products()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function status($number): array
    {
        $value = [0 => 'Kutilmoqda', 1 => 'Qabul qilindi', 2 => 'Rad etildi'];
        $color = ['Kutilmoqda' => 'badge-info', 'Rad etildi' => 'badge-danger', 'Qabul qilindi' => 'badge-success'];
        return [
            'value' => $value[$number],
            'color' => $color[$value[$number]]
        ];
    }
}
