<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone',
    ];

    public function financeAccounts()
    {
        return $this->hasMany(FinanceAccount::class);
    }

    public function storeProducts()
    {
        return $this->hasMany(StoreProduct::class);
    }

    public function warehouseStoreTransfers()
    {
        return $this->hasMany(WarehouseStoreTransfer::class, 'to_store_id');
    }

    public function goodsRequests()
    {
        return $this->hasMany(StoreGoodsRequest::class);
    }
}
