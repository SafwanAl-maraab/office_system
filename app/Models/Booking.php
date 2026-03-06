<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [

        'branch_id',
        'client_id',
        'trip_group_id',
        'trip_group_bus_id',
        'seat_number',
        'booking_number',
        'price',
        'currency_id',
        'status',
        'notes',
        'created_by'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function tripGroup()
    {
        return $this->belongsTo(TripGroup::class);
    }

    public function bus()
    {
        return $this->belongsTo(TripGroupBus::class,'trip_group_bus_id');
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
}