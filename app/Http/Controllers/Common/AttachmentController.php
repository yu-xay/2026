<?php

namespace App\Http\Controllers\Common;

use App\Http\Requests\Admin\AttachmentRequest;
use App\Models\Attachment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Resource;

class AttachmentController extends R
{
    #[Post('upload')]
    public function __invoke(AttachmentRequest $request): JsonResponse
    {
        $file = $request->file('file');
        if ($file && $file->isValid()) {
            if(tenant()){
                $tenantPath = 'tenancy/assets/' . tenant('id') . '/attachments';
            } else {
                $tenantPath = 'attachments';
            }

            $path = $file->store($tenantPath, 'public');
            $attachmentModel = Attachment::create([
                'user_id' => Auth::id(),
                'mime_type' => $file->getClientMimeType(),
                'url' => asset(Storage::url($path)),
            ]);
            return $this->success([
                'id' => $attachmentModel->id,
                'url' => $attachmentModel->url,
            ]);
        }
        abort(400, '上传失败');
    }
}
