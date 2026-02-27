<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisaType extends Model
{
    //
    protected $fillable = [
        'name',
        'category',
        'requires_package',
        'default_duration_days',
        'status'
    ];

    public function visas()
    {
        return $this->hasMany(Visa::class);
    }
}
