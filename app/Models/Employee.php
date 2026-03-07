<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //
    protected $fillable = [
        'branch_id',
        'role_id',
        'full_name',
        'phone',
        'salary',
        'commission_percentage',
        'status'
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(\App\Models\Branch::class);
    }

    public function role()
    {
        return $this->belongsTo(\App\Models\Role::class);
    }

    public function visas()
    {
        return $this->hasMany(\App\Models\Visa::class);
    }

    public function requests()
    {
        return $this->hasMany(\App\Models\Request::class);
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class);
    }

    public function expenses()
    {
        return $this->hasMany(\App\Models\Expense::class);
    }
    public function visaStatusChanges()
{
    return $this->hasMany(VisaStatusHistory::class, 'changed_by');
}


// app/Models/Employee.php

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
