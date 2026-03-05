<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Travel extends Model
{

    protected $table = 'travels';

    protected $fillable = [
        'branch_id',
        'travel_date',
        'driver_id',
        'capacity',
        'from_location',
        'to_location',
        'notes'
    ];

    // العلاقات

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function requests()
    {
        return $this->belongsToMany(Request::class, 'travel_requests')
            ->withPivot('seat_number')
            ->withTimestamps();
    }
    public function driver()
    {
        return $this->belongsTo(\App\Models\Driver::class);
    }

}
