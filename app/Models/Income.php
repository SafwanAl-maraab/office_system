<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = [

        'branch_id',

        'amount',

        'currency_id',

        'description',

        'created_by'
    ];

    protected $casts = [

        'amount' => 'decimal:2'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function branch()
    {
        return $this->belongsTo(
            Branch::class
        );
    }

    public function currency()
    {
        return $this->belongsTo(
            Currency::class
        );
    }

    public function employee()
    {
        return $this->belongsTo(
            Employee::class,
            'created_by'
        );
    }
}
