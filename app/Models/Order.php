<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
//    protected $fillable = [
//        'order_no',
//        'user_id',
//        'total_amount',
//        'status', // 0:待支付, 1:已支付, 2:已发货, 3:已完成, 4:已取消
//        'pay_time',
//        'remark'
//    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
