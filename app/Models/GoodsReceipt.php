<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsReceipt extends Model
{
    protected $fillable = ['warehouse_id', 'receipt_number', 'received_date', 'sender_name', 'received_by', 'notes'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
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
