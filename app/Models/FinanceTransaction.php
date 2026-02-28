<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceTransaction extends Model
{
    protected $fillable = [
        'finance_account_id',
        'category',
        'title',
        'amount',
        'type',
        'is_admin_fee',
        'transaction_date',
        'description',
        'notes',
        'supplier_id',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'is_admin_fee' => 'boolean',
    ];

    public function account()
    {
        return $this->belongsTo(FinanceAccount::class, 'finance_account_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
