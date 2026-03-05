<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReturnPayment extends Model
{
    protected $fillable = [
        'product_return_id',
        'amount',
        'finance_account_id',
        'payment_date',
        'notes',
    ];

    protected $casts = ['payment_date' => 'date'];

    public function productReturn()
    {
        return $this->belongsTo(ProductReturn::class);
    }

    public function financeAccount()
    {
        return $this->belongsTo(FinanceAccount::class);
    }
}
