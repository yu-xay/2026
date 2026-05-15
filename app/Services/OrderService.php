<?php

namespace App\Services;

use App\Models\GoodsSku;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class OrderService
{
    /**
     * 创建订单
     * @param int $userId
     * @param array $items [['sku_id' => 1, 'num' => 2], ...]
     * @param string $remark
     * @return Order
     * @throws Exception
     */
    public function createOrder(int $userId, array $items, string $remark = ''): Order
    {
        return DB::transaction(function () use ($userId, $items, $remark) {
            $totalAmount = 0;
            $orderItems = [];

            foreach ($items as $item) {
                $sku = GoodsSku::with('goods')->lockForUpdate()->find($item['sku_id']);
                if (!$sku) {
                    throw new Exception("商品规格不存在");
                }
                if ($sku->stock < $item['num']) {
                    throw new Exception("商品 [{$sku->goods->name}] 库存不足");
                }

                // 扣减库存
                $sku->decrement('stock', $item['num']);

                $totalAmount += $sku->price * $item['num'];
                $orderItems[] = [
                    'goods_id' => $sku->goods_id,
                    'goods_sku_id' => $sku->id,
                    'goods_name' => $sku->goods->name,
                    'goods_sku_name' => $sku->name,
                    'price' => $sku->price,
                    'num' => $item['num'],
                    'pic' => $sku->pic ?: $sku->goods->pic()->first()?->url,
                ];
            }

            // 创建订单主表
            $order = Order::create([
                'order_no' => date('YmdHis') . Str::random(6),
                'user_id' => $userId,
                'total_amount' => $totalAmount,
                'status' => 0, // 待支付
                'remark' => $remark,
            ]);

            // 创建订单详情
            foreach ($orderItems as $orderItem) {
                $orderItem['order_id'] = $order->id;
                OrderItem::create($orderItem);
            }

            return $order;
        });
    }

    /**
     * 支付成功回调处理
     * @param string $orderNo
     * @return bool
     */
    public function paySuccess(string $orderNo): bool
    {
        $order = Order::where('order_no', $orderNo)->first();
        if (!$order || $order->status != 0) {
            return false;
        }

        return $order->update([
            'status' => 1, // 已支付
            'pay_time' => now(),
        ]);
    }

    /**
     * 取消订单（释放库存）
     * @param int $orderId
     * @return bool
     */
    public function cancelOrder(int $orderId): bool
    {
        return DB::transaction(function () use ($orderId) {
            $order = Order::with('items')->lockForUpdate()->find($orderId);
            if (!$order || $order->status != 0) {
                return false;
            }

            // 释放库存
            foreach ($order->items as $item) {
                GoodsSku::where('id', $item->goods_sku_id)->increment('stock', $item->num);
            }

            return $order->update(['status' => 4]); // 已取消
        });
    }
}
