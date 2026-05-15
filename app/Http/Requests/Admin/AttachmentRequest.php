<?php

namespace App\Http\Requests\Admin;

class AttachmentRequest extends Base
{
    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:jpeg,jpg,png,gif',
        ];
    }
}
