<?php

namespace App\Models\Custom;

use App\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OneClickBuy extends Model
{
    use SoftDeletes;

    protected $table = 'one_click_buy';

    protected $fillable = [
        "product_id",
        "phone",
        "name",
        "address",
        "addition",
        "to_home",
        "promo_kod",
        "payment_type",
        "agree_to_buy",
        'status',
        'user_id'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function status($number): array
    {
        $value = [0 => 'Kutilmoqda', 1 => 'Rad etildi', 2 => 'Qabul qilindi'];
        $color = ['Kutilmoqda' => 'badge-info', 'Rad etildi' => 'badge-danger', 'Qabul qilindi' => 'badge-success'];
        return [
            'value' => $value[$number],
            'color' => $color[$value[$number]]
        ];
    }


}
