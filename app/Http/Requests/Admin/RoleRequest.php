<?php

namespace App\Http\Requests\Admin;

class RoleRequest extends Base
{
    public function storeRules(): array
    {
        return [
            'name' => 'required',
            'permissions' => 'required|array',
        ];
    }
}
