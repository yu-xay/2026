<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class GoodsSku extends Model
{
    protected $fillable = [
        'id',
        'goods_id',
        'price',
        'stock',
        'spec_data',
    ];

    protected function specData(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => json_decode($value,true),
        );
    }
}
