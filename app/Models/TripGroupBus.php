<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TripGroupBus extends Model
{
    use HasFactory;

    protected $table = 'trip_group_buses';

    public $timestamps = false;

    protected $fillable = [
        'trip_group_id',
        'bus_id',
        'driver_id',
        'notes',
        'created_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function tripGroup()
    {
        return $this->belongsTo(TripGroup::class);
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function visas()
    {
        return $this->hasMany(Visa::class);
    }


}
