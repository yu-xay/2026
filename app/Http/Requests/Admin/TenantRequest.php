<?php

namespace App\Http\Requests\Admin;

class TenantRequest extends Base
{
    public function storeRules(): array
    {
        return [
            'id' => 'required|string|max:20|alpha_num|unique:tenants,id',
            'name' => 'required|string|max:255',
        ];
    }

    public function updateRules(): array
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }
}
