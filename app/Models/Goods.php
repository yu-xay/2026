<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Goods extends Model
{
    use softDeletes;

    public const table = 'goods';
    protected $fillable = ['title', 'sub_title', 'sort', 'category_id', 'description', 'status', 'data'];

    public function pic()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function cat()
    {
        return $this->hasOne(GoodsCat::class, 'id', 'category_id');
    }

    public function attr()
    {
        return $this->hasMany(GoodsSpecKey::class, 'goods_id', 'id');
    }
    public function sku(){
        return $this->hasMany(GoodsSku::class, 'goods_id', 'id');
    }
}
