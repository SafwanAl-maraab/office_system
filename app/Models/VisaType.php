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


    protected $casts = [
        'requires_package' => 'boolean',
        'status' => 'boolean'
    ];

    public function visa()
    {
        return $this->hasMany(Visa::class ,'visa_type_id');
    }



}
