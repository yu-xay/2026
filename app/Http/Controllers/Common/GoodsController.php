<?php

namespace App\Http\Controllers\Common;

use App\Http\Requests\Admin\GoodsRequest;
use App\Models\Attachment;
use App\Models\Goods;
use App\Models\GoodsSku;
use App\Models\GoodsSpecKey;
use App\Models\GoodsSpecValue;
use App\Services\Admin\GoodsService;
use Illuminate\Support\Arr;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Spatie\RouteAttributes\Attributes\ApiResource;
use Spatie\RouteAttributes\Attributes\Middleware;

#[ApiResource(resource: 'goods', parameters: ['goods' => 'goods'])]
#[Middleware(['permission:goods'])]
class GoodsController extends R
{
    public function index(GoodsRequest $request): JsonResponse
    {
        $goodsModel = Goods::keyword('name')->with('cat')->page();
        $response = $this->toArray($goodsModel);

        return $this->success($response['data'], $response['paginate']);
    }

    /**
     * @throws \Throwable
     */
    public function store(GoodsRequest $request): JsonResponse
    {
        $form = $request->validated();

        $validated = $request->safe()->except(['attachment_id', 'skus', 'spec_groups']);
        DB::transaction(function () use ($form, $validated) {
            $goods = Goods::create($validated);

            $goodsService = new GoodsService();
            $goodsService->spec_groups = $form['spec_groups'];
            $goodsService->skus = $form['skus'];
            $goodsService->setSkus($goods);
            //Attachment::saveMany($form['attachment_id'], $goods);
        });

        return $this->success([
            'message' => self::MESSAGE['STORE']
        ]);
    }

    public function show(GoodsRequest $request, Goods $goods): JsonResponse
    {
        $result = $goods->toArray();
        //图片
        $result['pic'] = $goods->pic;

        $result['attachment_id'] = $goods->pic->pluck('id')->toArray();

        $result['spec_groups'] = GoodsSpecKey::with('children')->where('goods_id', $goods->id)->get();
        $maps = [];
        foreach ($result['spec_groups'] as $k1 => $group) {
            $result['spec_groups'][$k1]['_index'] = $group['id'];
            foreach ($group['children'] as $k2 => $value) {
                $result['spec_groups'][$k1]['children'][$k2]['_index'] = $value['id'];
            }
        }

        $result['skus'] = GoodsSku::where('goods_id', $goods->id)->get();
        foreach ($result['skus'] as $i => $v) {

            $result['skus'][$i]['_index'] = array_values($v['spec_data']);
        }
        //规格
        return $this->success($result);
    }

    /**
     * @throws \Throwable
     */
    public function update(GoodsRequest $request, Goods $goods): JsonResponse
    {
        $form = $request->validated();
        $validated = $request->safe()->except(['attachment_id', 'skus', 'spec_groups']);

        DB::transaction(function () use ($form, $goods, $validated) {
            $goods->update($validated);
            $goodsService = new GoodsService();
            $goodsService->spec_groups = $form['spec_groups'];
            $goodsService->skus = $form['skus'];
            $goodsService->setSkus($goods);

            Attachment::saveMany($form['attachment_id'], $goods);
        });
        return $this->success([
            'message' => self::MESSAGE['UPDATE']
        ]);
    }


    public function destroy(GoodsRequest $request, Goods $goods): JsonResponse
    {
        $goods->delete();

        return $this->success([
            'message' => self::MESSAGE['DESTROY']
        ]);
    }
}
