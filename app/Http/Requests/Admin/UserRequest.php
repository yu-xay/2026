<?php

namespace App\Http\Requests\Admin;

class UserRequest extends Base
{
    protected function updateRules(): array
    {
        return array_merge($this->storeRules(), [
            'password' => 'sometimes|nullable|string|max:30',
        ]);
    }

    protected function storeRules(): array
    {
        return [
            'name' => 'required|string|max:30',
            'email' => 'required|string|max:30',
            'attachment_id' => 'required|integer|exists:attachments,id',
            'password' => 'required|string|max:30',
            'role_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
