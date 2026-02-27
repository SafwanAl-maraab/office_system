<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchCashbox extends Model
{
    //
    protected $fillable = [
        'branch_id',
        'balance',
        'currency_id',
        'branch_id'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
