<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    //
    protected $fillable = [
        'branch_id',
        'full_name',
        'phone',
        'passport_number',
        'national_id',
        'address',
        'notes',
        'status'
    ];
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function visas()
    {
        return $this->hasMany(Visa::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
