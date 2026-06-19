<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashboxTransaction extends Model
{
    protected $fillable = [

        'branch_id',

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

    public function branch()
    {
        return $this->belongsTo(
            Branch::class
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
        return match($this->type){

            'opening_balance'
            => 'رصيد افتتاحي',

            'deposit'
            => 'إيداع',

            'withdraw'
            => 'سحب',

            'exchange_out'
            => 'مصارفة خروج',

            'exchange_in'
            => 'مصارفة دخول',

            'expense'
            => 'مصروف',

            'income'
            => 'إيراد',

            'refund'
            => 'استرجاع',

            'adjustment'
            => 'تعديل',

            'client_voucher'
            => 'سندات',

            default
            => $this->type
        };
    }
}
