<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    //
    protected $fillable = [
        'branch_id',
        'amount',
        'currency_id',
        'description',
        'created_by'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }
    public function currency()
    {
        return $this->belongsTo(\App\Models\Currency::class);
    }
}
