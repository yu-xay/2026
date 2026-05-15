<?php

namespace App\Http\Requests\Admin;

class GoodsCatRequest extends Base
{
    public function storeRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'sort' => 'required|integer|min:0',
        ];
    }
}
