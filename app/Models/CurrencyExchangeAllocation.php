<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyExchangeAllocation extends Model
{
    protected $fillable = [

        'voucher_id',
        'invoice_id',
        'payment_id',

        'source_currency_id',
        'target_currency_id',

        'exchange_rate',

        'source_amount',
        'target_amount',

        'notes',

        'created_by'
    ];

    public function voucher()
    {
        return $this->belongsTo(
            ClientVoucher::class
        );
    }

    public function invoice()
    {
        return $this->belongsTo(
            Invoice::class
        );
    }

    public function payment()
    {
        return $this->belongsTo(
            Payment::class
        );
    }

    public function sourceCurrency()
    {
        return $this->belongsTo(
            Currency::class,
            'source_currency_id'
        );
    }

    public function targetCurrency()
    {
        return $this->belongsTo(
            Currency::class,
            'target_currency_id'
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
