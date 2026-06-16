<?php

namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

class ClientVoucher extends Model
{
    protected $fillable = [

        'branch_id',
        'client_id',
        'currency_id',
        'created_by',

        'type',

        'amount',

        'notes'
    ];



    protected $appends = [

        'allocated_amount',

        'remaining_amount',

        'is_closed'

    ];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class,'created_by');
    }

    public function allocations()
    {
        return $this->hasMany(VoucherAllocation::class,'voucher_id');
    }

    public function getAllocatedAmountAttribute()
    {
        $normal = $this->allocations()
            ->sum('amount');

        $exchange = $this->exchangeAllocations()
            ->sum('source_amount');

        return $normal + $exchange;
    }


    public function getRemainingAmountAttribute()
    {
        return max(
            0,
            $this->amount - $this->allocated_amount
        );
    }

    public function exchangeAllocations()
    {
        return $this->hasMany(
            CurrencyExchangeAllocation::class,
            'voucher_id'
        );
    }




    public function getIsClosedAttribute()
    {
        return $this->remaining_amount <= 0;
    }


}
