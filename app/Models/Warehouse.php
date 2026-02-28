<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = [
        'name',
        'location',
        'capacity',
        'description',
    ];

    public function financeAccounts()
    {
        return $this->hasMany(FinanceAccount::class);
    }

    public function goodsReceipts()
    {
        return $this->hasMany(GoodsReceipt::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function fromTransfers()
    {
        return $this->hasMany(StockTransfer::class, 'from_warehouse_id');
    }

    public function toTransfers()
    {
        return $this->hasMany(StockTransfer::class, 'to_warehouse_id');
    }

    public function warehouseStoreTransfers()
    {
        return $this->hasMany(WarehouseStoreTransfer::class, 'from_warehouse_id');
    }

    public function incomingRequests()
    {
        return $this->hasMany(StoreGoodsRequest::class);
    }
}
