<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Driver extends Model
{
    use HasFactory;

    protected $table = 'drivers';

    // لا يوجد updated_at
    public $timestamps = false;

    protected $fillable = [
        'branch_id',
        'name',
        'phone',
        'license_number',
        'status',
        'created_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // السائق يتبع فرع
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }


}
