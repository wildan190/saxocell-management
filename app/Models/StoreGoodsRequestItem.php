<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreGoodsRequestItem extends Model
{
    protected $fillable = [
        'store_goods_request_id',
        'product_id',
        'quantity',
    ];

    public function request()
    {
        return $this->belongsTo(StoreGoodsRequest::class, 'store_goods_request_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
