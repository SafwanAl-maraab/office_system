<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashboxExchange extends Model
{
    protected $fillable = [

        'branch_id',

        'from_currency_id',

        'to_currency_id',

        'from_amount',

        'rate',

        'to_amount',

        'notes',

        'created_by',

        'is_reversed',

        'reversed_at',

        'reversed_by'
    ];

    protected $casts = [

        'from_amount' => 'decimal:2',

        'to_amount' => 'decimal:2',

        'rate' => 'decimal:6',

        'is_reversed' => 'boolean',

        'reversed_at' => 'datetime'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function branch()
    {
        return $this->belongsTo(
            Branch::class
        );
    }

    public function fromCurrency()
    {
        return $this->belongsTo(
            Currency::class,
            'from_currency_id'
        );
    }

    public function toCurrency()
    {
        return $this->belongsTo(
            Currency::class,
            'to_currency_id'
        );
    }

    public function creator()
    {
        return $this->belongsTo(
            Employee::class,
            'created_by'
        );
    }

    public function reverser()
    {
        return $this->belongsTo(
            Employee::class,
            'reversed_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function getStatusLabelAttribute()
    {
        return $this->is_reversed
            ? 'معكوسة'
            : 'نشطة';
    }
}
