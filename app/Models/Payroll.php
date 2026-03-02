<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'month',
        'year',
        'period_start',
        'period_end',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(PayrollItem::class);
    }
}
