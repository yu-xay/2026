<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attachment extends Model
{
    protected $fillable = ['url',
        'user_id',
        'mime_type',
        'attachable_type',
        'attachable_id'
    ];

    public static function saveOne(int $attachment_id, Model $model): void
    {
        $morphClass = $model->getMorphClass();
        $modelId = $model->id;
        Attachment::where('attachable_type', $morphClass)
            ->where('attachable_id', $modelId)
            ->whereNot('id', $attachment_id)
            ->forceDelete();

        Attachment::find($attachment_id)->update([
            'attachable_type' => $model->getMorphClass(),
            'attachable_id' => $model->id
        ]);
    }

    public static function saveMany(array $attachment_ids, Model $model): void
    {
        $morphClass = $model->getMorphClass();
        $modelId = $model->id;

        Attachment::where('attachable_type', $morphClass)
            ->where('attachable_id', $modelId)
            ->whereNotIn('id', $attachment_ids)
            ->forceDelete();

        if (!empty($attachment_ids)) {
            Attachment::whereIn('id', $attachment_ids)->update([
                'attachable_type' => $morphClass,
                'attachable_id' => $modelId,
                'updated_at' => now(), // 手动更新时间戳
            ]);
        }
    }

    use SoftDeletes;

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}
