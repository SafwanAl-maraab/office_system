<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    //
    protected $fillable = [
        'code',
        'name',
        'symbol',
        'is_default',
        'status'
    ];


    public function cashbox()
    {
        return $this->hasMany(BranchCashbox::class);
    }

    public function invoice()
    {
        return $this->hasMany(Invoice::class);
    }

}
