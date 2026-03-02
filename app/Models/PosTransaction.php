<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosTransaction extends Model
{
    protected $fillable = [
        'store_id',
        'transaction_number',
        'cashier_name',
        'customer_name',
        'payment_method',
        'subtotal',
        'discount',
        'total_amount',
        'amount_paid',
        'change_amount',
        'notes',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function items()
    {
        return $this->hasMany(PosTransactionItem::class);
    }
}
