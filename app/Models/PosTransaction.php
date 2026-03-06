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
        'payment_splits',
        'subtotal',
        'discount',
        'total_amount',
        'amount_paid',
        'change_amount',
        'is_trade_in',
        'trade_in_device_name',
        'trade_in_imei',
        'trade_in_customer_price',
        'trade_in_product_id',
        'notes',
    ];

    public function tradeInProduct()
    {
        return $this->belongsTo(Product::class, 'trade_in_product_id');
    }

    protected $casts = [
        'payment_splits' => 'array',
    ];

    public function getPaymentMethodLabelAttribute(): string
    {
        $labels = ['cash' => 'Tunai', 'transfer' => 'Transfer', 'qris' => 'QRIS', 'split' => 'Split Payment'];
        return $labels[$this->payment_method] ?? ucfirst($this->payment_method);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function items()
    {
        return $this->hasMany(PosTransactionItem::class);
    }
}
