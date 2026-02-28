<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'store_id',
        'customer_name',
        'customer_contact',
        'customer_address',
        'proof_of_transfer_path',
        'resi_number',
        'status',
        'total_amount',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
