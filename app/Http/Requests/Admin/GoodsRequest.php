<?php

namespace App\Http\Requests\Admin;

class GoodsRequest extends Base
{
    public function storeRules(): array
    {
        return [
            'title' => 'required|string|max:20',
            'sub_title' => 'nullable|string|max:100',
            'sort' => 'bail|integer|min:0',
            'category_id' => 'bail|required|integer|exists:goods_cats,id',
            'description' => 'bail|string|nullable',
            'status' => 'required|integer|between:0,1',
            'data' => 'nullable|array',
            'attachment_id' => 'array',
            'skus.*.price' => 'required|numeric|min:0',
            'skus.*.stock' => 'required|integer|min:0',
            'skus.*' => 'sometimes',
            'spec_groups.*.name' => 'required|string',
            'spec_groups.*' => 'sometimes',
            'spec_groups' => [
                'array',
                function ($attribute, $value, $fail) {
                    // 提取所有规格名并去重比较
                    $names = collect($value)->pluck('name')->filter()->toArray();
                    if (count($names) !== count(array_unique($names))) {
                        $fail('规格组名称不能重复。');
                    }
                    foreach ($value as $v1) {
                        $names = collect($v1['children'])->pluck('name')->filter()->toArray();
                        if (count($names) !== count(array_unique($names))) {
                            $fail('规格值名称不能重复。');
                        }
                    }
                },
            ],
        ];
    }

    public function attributes(): array
    {
        return array_merge(parent::attributes(), [
            'skus.*.price' => '规格价格',
            'skus.*.stock' => '规格库存',
            'spec_groups.*.name' => '规格组名称'
        ]);
    }
}