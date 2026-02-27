<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    //

    protected $fillable = [
        'branch_id',
        'name',
        'phone',
        'country',
        'city',
        'balance',
        'status',
    ];


    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function visas()
    {
        return $this->hasMany(Visa::class);
    }
}
