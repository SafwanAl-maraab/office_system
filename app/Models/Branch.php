<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    //
    protected $fillable = [
        'name',
        'location',
        'phone',
        'opening_balance',
        'status'
    ];


    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function packages()
    {
        return $this->hasMany(ServicePackage::class);
    }

    public function tripGroups()
    {
        return $this->hasMany(TripGroup::class);
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

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function cashbox()
    {
        return $this->hasOne(BranchCashbox::class);
    }

    public function agents()
    {
        return $this->hasMany(Agent::class);
    }

    // app/Models/Branch.php

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
}
