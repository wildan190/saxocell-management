<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreToStoreTransfer extends Model
{
    protected $fillable = [
        'from_store_id',
        'to_store_id',
        'transfer_number',
        'status',
        'notes',
        'shipped_at',
        'received_at',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'received_at' => 'datetime',
    ];

    public function fromStore()
    {
        return $this->belongsTo(Store::class, 'from_store_id');
    }

    public function toStore()
    {
        return $this->belongsTo(Store::class, 'to_store_id');
    }

    public function items()
    {
        return $this->hasMany(StoreToStoreTransferItem::class, 'transfer_id');
    }

    public function logs()
    {
        return $this->morphMany(ActivityLog::class, 'loggable');
    }
}
