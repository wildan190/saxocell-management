<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreToStoreTransferItem extends Model
{
    protected $fillable = [
        'transfer_id',
        'product_id',
        'quantity',
    ];

    public function transfer()
    {
        return $this->belongsTo(StoreToStoreTransfer::class, 'transfer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
