<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsReceipt extends Model
{
    protected $fillable = ['warehouse_id', 'store_id', 'admin_id', 'receipt_number', 'received_date', 'sender_name', 'admin_fee', 'received_by', 'notes'];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function items()
    {
        return $this->hasMany(GoodsReceiptItem::class);
    }

    public function returns()
    {
        return $this->hasMany(GoodsReturn::class);
    }
}
