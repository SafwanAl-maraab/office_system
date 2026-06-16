<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    protected $fillable = [

        'branch_id',

        'from_currency_id',
        'to_currency_id',
'rate_date',
        'rate',

        'is_default',

        'created_by'
    ];
    public function getDisplayRateAttribute()
    {
        return $this->rate . ' '
            . $this->fromCurrency->code
            . ' = 1 '
            . $this->toCurrency->code;
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

    public function employee()
    {
        return $this->belongsTo(
            Employee::class,
            'created_by'
        );
    }
}
