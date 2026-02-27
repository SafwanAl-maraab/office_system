<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    //

    protected $fillable = [
        'branch_id',
        'office_name',
        'logo',
        'primary_phone',
        'secondary_phone',
        'email',
        'address',
        'short_description',
        'facebook',
        'whatsapp',
        'website',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
