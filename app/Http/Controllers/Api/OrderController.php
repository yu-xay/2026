<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Exception;

class OrderController extends R
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * 创建订单
     */
    #[Post('order-create')]
    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.sku_id' => 'required|integer',
            'items.*.num' => 'required|integer|min:1',
        ]);

        try {
            $order = $this->orderService->createOrder(
                $request->user()->id,
                $request->input('items'),
                $request->input('remark', '')
            );

            return $this->success([
                'order_no' => $order->order_no,
                'total_amount' => $order->total_amount,
            ], null, ['message' => '订单创建成功']);
        } catch (Exception $e) {
            return $this->error(['message' => $e->getMessage()]);
        }
    }

    /**
     * 订单列表
     */
    #[Get('order-list')]
    public function list(Request $request): JsonResponse
    {
        $orders = Order::with('items')
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 10));

        return $this->success($this->toArray($orders));
    }

    /**
     * 订单详情
     */
    #[Get('order-detail/{order}')]
    public function detail(Request $request, Order $order): JsonResponse
    {
        if ($order->user_id !== $request->user()->id) {
            return $this->error(['message' => '无权查看该订单']);
        }

        return $this->success($order->load('items'));
    }

    /**
     * 取消订单
     */
    #[Post('order-cancel/{order}')]
    public function cancel(Request $request, Order $order): JsonResponse
    {
        if ($order->user_id !== $request->user()->id) {
            return $this->error(['message' => '无权操作该订单']);
        }

        if ($this->orderService->cancelOrder($order->id)) {
            return $this->success([], null, ['message' => '订单已取消']);
        }

        return $this->error(['message' => '订单取消失败，可能已支付或已发货']);
    }
}
