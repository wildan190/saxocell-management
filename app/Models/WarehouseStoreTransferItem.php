<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseStoreTransferItem extends Model
{
    protected $fillable = [
        'warehouse_store_transfer_id',
        'product_id',
        'quantity',
    ];

    public function transfer()
    {
        return $this->belongsTo(WarehouseStoreTransfer::class, 'warehouse_store_transfer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
