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
        'created_at',
        'updated_at'
    ];

    public const STATUS_NOT_FOUND = 0;
    public const STATUS_VERIFICATION_MYID = 5;
    public const STATUS_NEED_ADD_CARD = 1;
    public const STATUS_ADD_TRUSTEE = 12;
    public const STATUS_AWAITING_MODERATION = 2;
    public const STATUS_VERIFIED = 4;
    public const STATUS_VERIFICATION_DENIED = 8;

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
