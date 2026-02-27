<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Travel extends Model
{
    protected $fillable = [
        'branch_id',
        'travel_date',
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
}
