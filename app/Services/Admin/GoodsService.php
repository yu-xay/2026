<?php

namespace App\Services\Admin;

use App\Models\Goods;
use App\Models\GoodsSku;
use App\Models\GoodsSpecKey;
use App\Models\GoodsSpecValue;
use Illuminate\Support\Arr;

class GoodsService
{
    public array $spec_groups = [];
    public array $skus = [];

    public function setSkus(Goods $goods)
    {
        $specGroups = collect($this->spec_groups);
        $skus = collect($this->skus);

        $this->deleteOld($goods);

        //更新key
        $groupList = $specGroups->map(function ($item) use ($goods) {
            $result = Arr::only($item, ['id', 'name']);
            $result['goods_id'] = $goods->id;
            return $result;
        })->toArray();
        GoodsSpecKey::upsert(
            $groupList,
            ['id', 'goods_id'],
            ['name']
        );
        //获取更新之后的key
        $keyMap = GoodsSpecKey::where('goods_id', $goods->id)
            ->pluck('id', 'name')
            ->all();

        //更新value
        $valueData = [];
        $vIndex = [];
        foreach ($specGroups as $group) {
            foreach ($group['children'] as $specValue) {
                $vIndex[$specValue['_index']] = $specValue['name'];
                $valueData[] = [
                    'id' => $specValue['id'] ?? null,
                    'spec_key_id' => $keyMap[$group['name']],
                    'name' => $specValue['name'],
                    'image' => '',
                    'updated_at' => now(),
                ];
            }
        }
        GoodsSpecValue::upsert(
            $valueData,
            ['id'],
            ['spec_key_id', 'name', 'updated_at']
        );

        //获取更新之后的value
        $valueMap = GoodsSpecValue::whereIn('spec_key_id', $keyMap)
            ->select(['id', 'spec_key_id', 'name'])
            ->get()->toArray();
        $valueMap = collect($valueMap)->keyBy('name')->toArray();

        //更新sku
        $skus = $skus->map(function ($item) use ($goods, $vIndex, $keyMap, $valueMap) {
            //刷新spec_data
            $spec_data = [];
            foreach ($item['_index'] as $t2) {
                $v = $valueMap[$vIndex[$t2]];
                $spec_data[$v['spec_key_id']] = $v['id'];
            }

            $result = Arr::only($item, ['id', 'price', 'stock']);
            $result['goods_id'] = $goods->id;
            $result['spec_data'] = json_encode($spec_data);
            return $result;
        })->toArray();

        GoodsSku::upsert(
            $skus,
            ['id', 'goods_id'],
            ['goods_id', 'price', 'stock', 'spec_data']
        );

        return true;
    }

    private function deleteOld(Goods $goods): void
    {
        $specGroups = collect($this->spec_groups);
        $skus = collect($this->skus);

        //删除key
        $beforeSpecKeys = GoodsSpecKey::where('goods_id', $goods->id)->pluck('id')->toArray();
        $afterSpecKeys = $specGroups->pluck('id')->filter()->values()->all();
        $delIds = array_diff($beforeSpecKeys, $afterSpecKeys);
        if ($delIds) {
            GoodsSpecKey::whereIn('id', $delIds)->delete();
        }
        //删除value
        if ($delIds) {
            $afterSpecValues = $specGroups->flatMap(function ($group) {
                return data_get($group, 'children', []);
            })->pluck('id')->filter()->values()->all();
            GoodsSpecValue::whereIn('spec_key_id', $delIds)->whereNotIn('id', $afterSpecValues)->delete();
        }

        //删除SKU
        $safe_specKeyIds = $skus->pluck('id')->filter()->values()->all();
        GoodsSku::where('goods_id', $goods->id)->whereNotIn('id', $safe_specKeyIds)->delete();
    }
}