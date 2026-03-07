<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{

    protected $fillable = [

        'branch_id',
        'bus_id',
        'from_city',
        'to_city',
        'trip_date',
        'trip_time',
        'purchase_price',
        'sale_price',
        'currency_id',
        'notes',
        'status',
        'created_by'

    ];



    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }



    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }



    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }



    public function employee()
    {
        return $this->belongsTo(Employee::class,'created_by');
    }



    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

}
