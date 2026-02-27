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
}
