<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreGoodsRequest extends Model
{
    protected $fillable = [
        'request_number',
        'store_id',
        'warehouse_id',
        'status',
        'notes',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function items()
    {
        return $this->hasMany(StoreGoodsRequestItem::class);
    }
}
