<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'goods_id',
        'goods_sku_id',
        'goods_name',
        'goods_sku_name',
        'price',
        'num',
        'pic'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function goods(): BelongsTo
    {
        return $this->belongsTo(Goods::class);
    }

    public function sku(): BelongsTo
    {
        return $this->belongsTo(GoodsSku::class, 'goods_sku_id');
    }
}
