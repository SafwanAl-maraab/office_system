<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{

    protected $fillable = [

        'branch_id',
        'client_id',
        'trip_id',
        'seat_number',
        'purchase_price',
        'sale_price',
        'discount_percent',
        'discount_amount',
        'total_before_discount',
        'final_price',
        'currency_id',
        'status',
        'created_by'

    ];



    public function trip()
    {
        return $this->belongsTo(Trip::class ,'trip_id');
    }



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

    public function invoice()
    {
        return $this->hasOne(Invoice::class,'reference_id')
            ->where('reference_type','booking');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function transactions()
    {
        return $this->belongsTo(AgentTransaction::class ,'booking_id');
    }


}
