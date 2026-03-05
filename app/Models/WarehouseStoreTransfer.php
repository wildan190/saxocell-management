<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseStoreTransfer extends Model
{
    protected $fillable = [
        'transfer_number',
        'from_warehouse_id',
        'to_store_id',
        'transfer_date',
        'notes',
        'status',
        'shipping_number',
        'shipping_cost',
    ];

    protected $casts = [
        'transfer_date' => 'datetime',
    ];

    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    public function toStore()
    {
        return $this->belongsTo(Store::class, 'to_store_id');
    }

    public function items()
    {
        return $this->hasMany(WarehouseStoreTransferItem::class);
    }
}
