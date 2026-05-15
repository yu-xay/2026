<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsSpecValue extends Model
{
    //
    protected $fillable = [
        'spec_key_id',
        'value',
        'image',
        'id',
    ];
}
