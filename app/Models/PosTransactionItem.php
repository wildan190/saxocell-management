<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosTransactionItem extends Model
{
    protected $fillable = [
        'pos_transaction_id',
        'store_product_id',
        'product_name',
        'product_sku',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    public function posTransaction()
    {
        return $this->belongsTo(PosTransaction::class);
    }

    public function storeProduct()
    {
        return $this->belongsTo(StoreProduct::class);
    }
}
