<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsReturnItem extends Model
{
    protected $fillable = [
        'goods_return_id',
        'product_id',
        'quantity'
    ];

    public function goodsReturn()
    {
        return $this->belongsTo(GoodsReturn::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
