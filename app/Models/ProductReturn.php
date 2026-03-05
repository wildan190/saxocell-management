<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReturn extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'resi',
        'shipping_cost',
        'shipped_at',
        'arrived_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'arrived_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(ProductReturnPayment::class)->latest();
    }

    public function getTotalReturnedAttribute(): float
    {
        return (float) $this->payments()->sum('amount');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Pengiriman',
            'shipped' => 'Dalam Pengiriman',
            'arrived' => 'Sudah Sampai, Menunggu Refund',
            'cleared' => 'Selesai',
            default => ucfirst($this->status),
        };
    }
}
