<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'company',
        'email',
        'contact',
        'nik',
        'address',
        'description',
    ];

    public function transactions()
    {
        return $this->hasMany(FinanceTransaction::class);
    }
}
