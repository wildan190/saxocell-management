<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductActivity extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'store_id',
        'finance_transaction_id',
        'activity_type',
        'description',
        'cost',
        'old_purchase_price',
        'new_purchase_price',
        'old_selling_price',
        'new_selling_price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function financeTransaction()
    {
        return $this->belongsTo(FinanceTransaction::class);
    }

    public function getActivityTypeLabelAttribute(): string
    {
        return match ($this->activity_type) {
            'repair' => 'Perbaikan (Repair)',
            'improvement' => 'Peningkatan (Improvement)',
            'price_adjustment' => 'Penyesuaian Harga',
            'status_change' => 'Perubahan Status',
            default => ucfirst($this->activity_type),
        };
    }
}
