<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsSpecKey extends Model
{
    //
    protected $fillable = [
        'goods_id',
        'name',
        'id',
    ];
    public function children(){
        return $this->hasMany('App\Models\GoodsSpecValue', 'spec_key_id', 'id');
    }
}
