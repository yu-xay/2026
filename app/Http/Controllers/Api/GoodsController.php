<?php

namespace App\Http\Controllers\Api;

use App\Models\Goods;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;

class GoodsController extends R
{
    #[Get('goods-list')]
    public function a(): JsonResponse
    {
        $goodsList = Goods::all();

        return $this->success([
            'list' => $goodsList,
        ]);
    }

    #[Get('goods-detail/{goods}')]
    public function b(Request $request, Goods $goods): JsonResponse
    {
        $detail = $goods->toArray();
        $detail['cat_name'] = $goods->cat()->first()?->name;
        $detail['pic'] = $goods->pic()->first();
        return $this->success([
            'detail' => $detail,
            'spec' => $goods->attr()->with('children')->get(),
            'sku' => $goods->sku()->get(),
        ]);
    }


}