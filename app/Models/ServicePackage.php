<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePackage extends Model
{
    //
    protected $fillable = [
        'branch_id',
        'name',
        'description',
        'base_price',
        'estimated_cost',
        'duration_days',
        'available_from',
        'available_until',
        'status'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function visas()
    {
        return $this->hasMany(Visa::class, 'package_id');
    }
}
