<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreProduct extends Model
{
    protected $fillable = [
        'store_id',
        'product_id',
        'stock',
        'description_1',
        'description_2',
        'description_3',
        'price',
        'is_active',
        'image_path',
        'min_price',
        'max_price',
        'internal_description',
        'label',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
