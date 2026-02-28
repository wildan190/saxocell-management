<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsReturn extends Model
{
    protected $fillable = [
        'goods_receipt_id',
        'return_number',
        'reason',
        'resolution',
        'adjusted_price',
        'proof_photos',
        'status'
    ];

    protected $casts = [
        'proof_photos' => 'array'
    ];

    public function goodsReceipt()
    {
        return $this->belongsTo(GoodsReceipt::class);
    }

    public function items()
    {
        return $this->hasMany(GoodsReturnItem::class);
    }
}
