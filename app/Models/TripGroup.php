<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripGroup extends Model
{
    //
    protected $fillable = [
        'branch_id',
        'name',
        'departure_date',
        'return_date',
        'total_seats',
        'status'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function visas()
    {
        return $this->hasMany(Visa::class);
    }

    public function tripGroupBuses()
{
    return $this->hasMany(TripGroupBus::class);
}
}
