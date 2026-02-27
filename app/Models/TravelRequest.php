<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelRequest extends Model
{
    protected $fillable = [
        'travel_id',
        'request_id',
        'seat_number'
    ];

    // العلاقات

    public function travel()
    {
        return $this->belongsTo(Travel::class);
    }

    public function request()
    {
        return $this->belongsTo(Request::class);
    }
}
