<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsCat extends Model
{
    use softDeletes;

    protected $fillable = [
        'name',
        'sort',
    ];

    public function goods(): HasMany
    {
        return $this->hasMany(Goods::class, 'category_id', 'id');
    }
}
