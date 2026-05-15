<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\ApiResource;
use Spatie\RouteAttributes\Attributes\Middleware;

#[ApiResource(resource: 'order')]
#[Middleware(['permission:order'])]
class OrderController extends R
{
    public function index(Request $request)
    {
        $order = Order::page();
        $response = $this->toArray($order);

        $result = array_map(function ($item) {
            $item['address_snapshot'] = json_decode($item['address_snapshot'], true);
            return $item;
        }, $response['data']);
        return $this->success($result, $response['paginate']);
    }

    public function show(Request $request, Order $order): JsonResponse
    {
        $order['address_snapshot'] = json_decode($order['address_snapshot'], true);
        //规格
        return $this->success($order);
    }
}
