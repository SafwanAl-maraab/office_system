<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientBalanceLog extends Model
{
    protected $fillable = [

        'client_id',

        'currency_id',

        'amount',

        'type',

        'reference_type',

        'reference_id',

        'notes',

        'created_by'
    ];

    protected $casts = [

        'amount' => 'decimal:2'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function client()
    {
        return $this->belongsTo(
            Client::class
        );
    }

    public function currency()
    {
        return $this->belongsTo(
            Currency::class
        );
    }

    public function employee()
    {
        return $this->belongsTo(
            Employee::class,
            'created_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function getTypeLabelAttribute()
    {
        return match ($this->type) {

            'receipt' =>
            'سند قبض',

            'payment' =>
            'سند صرف',

            'settlement' =>
            'تسوية فاتورة',

            'exchange_out' =>
            'تحويل عملة (خروج)',

            'exchange_in' =>
            'تحويل عملة (دخول)',

            'opening_balance' =>
            'رصيد افتتاحي',

            'refund' =>
            'استرجاع',

            default =>
            $this->type
        };
    }

    /*
    |--------------------------------------------------------------------------
    | هل العملية تزيد الرصيد
    |--------------------------------------------------------------------------
    */

    public function getIsPositiveAttribute()
    {
        return in_array(
            $this->type,
            [
                'receipt',
                'opening_balance',
                'refund',
                'exchange_in'
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | هل العملية تنقص الرصيد
    |--------------------------------------------------------------------------
    */

    public function getIsNegativeAttribute()
    {
        return in_array(
            $this->type,
            [
                'payment',
                'settlement',
                'exchange_out'
            ]
        );
    }
    public function getDebitAttribute()
    {
        return $this->amount < 0
            ? abs($this->amount)
            : 0;
    }

    public function getCreditAttribute()
    {
        return $this->amount > 0
            ? $this->amount
            : 0;
    }

}
