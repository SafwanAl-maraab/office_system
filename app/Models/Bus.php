<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bus extends Model
{
    use HasFactory;

    protected $table = 'buses';

    // لا يوجد updated_at
    public $timestamps = false;

    protected $fillable = [
        'branch_id',
        'plate_number',
        'agent_id',
        'model',
        'capacity',
        'status',
        'created_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // الباص يتبع فرع
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class ,'bus_id' );
    }





    public function currentTrip()
    {
        return $this->hasOne(Trip::class)
            ->whereDate('trip_date',today())
            ->latest();
    }



    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function drivers()
    {
        return $this->belongsToMany(
            Driver::class,
            'bus_drivers'
        )->withPivot('start_at','end_at','active');
    }
}
