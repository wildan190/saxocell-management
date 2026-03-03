<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradeIn extends Model
{
    protected $fillable = [
        'store_id',
        'trade_in_number',
        'customer_name',
        'customer_contact',
        'device_name',
        'brand',
        'model',
        'imei',
        'condition',
        'condition_notes',
        'desired_product',
        'desired_product_notes',
        'assessed_value',
        'purchase_price',
        'status',
        'rejection_reason',
        'handled_by',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function getConditionLabelAttribute(): string
    {
        return match ($this->condition) {
            'excellent' => 'Sangat Baik',
            'good' => 'Baik',
            'fair' => 'Sedang',
            'poor' => 'Rusak',
            default => ucfirst($this->condition),
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => ucfirst($this->status),
        };
    }
}
