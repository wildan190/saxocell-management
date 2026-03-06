<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'sku',
        'imei',
        'name',
        'category',
        'unit',
        'description',
        'purchase_price',
        'selling_price',
        'status',
        'quality_label',
        'quality_description',
        'store_label',
    ];

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'available' => 'Tersedia',
            'service' => 'Dalam Perbaikan (Service)',
            'sold' => 'Terjual',
            default => ucfirst($this->status),
        };
    }

    public function getQualityLabelConfigAttribute(): array
    {
        return match ($this->quality_label) {
            'yellow' => ['color' => 'yellow', 'label' => 'Kurang Sesuai', 'class' => 'bg-yellow-50 text-yellow-700 border-yellow-200'],
            'red' => ['color' => 'red', 'label' => 'Tidak Sesuai', 'class' => 'bg-rose-50 text-rose-700 border-rose-200'],
            default => ['color' => 'none', 'label' => 'Sesuai', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
        };
    }

    public function getStoreLabelConfigAttribute(): array
    {
        return match ($this->store_label) {
            'grey' => ['label' => 'Disembunyikan dari Marketplace', 'class' => 'bg-slate-100 text-slate-500 border-slate-200'],
            'red' => ['label' => 'Tidak Sesuai (Toko)', 'class' => 'bg-rose-50 text-rose-700 border-rose-200'],
            default => ['label' => '-', 'class' => ''],
        };
    }

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

    public function activities()
    {
        return $this->hasMany(ProductActivity::class)->latest();
    }

    public function storeProducts()
    {
        return $this->hasMany(StoreProduct::class);
    }

    public function productReturns()
    {
        return $this->hasMany(ProductReturn::class)->latest();
    }
}
