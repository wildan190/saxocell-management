<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['sku', 'name', 'unit', 'description', 'purchase_price', 'selling_price'];

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function receiptItems()
    {
        return $this->hasMany(GoodsReceiptItem::class);
    }

    public function priceHistories()
    {
        return $this->hasMany(ProductPriceHistory::class)->latest();
    }
}
