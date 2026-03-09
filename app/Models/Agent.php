<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{

protected $fillable = [
'branch_id',
'name',
'phone',
'country',
'city',
'status',
];

public function branch()
{
return $this->belongsTo(Branch::class);
}

public function visas()
{
return $this->hasMany(Visa::class);
}

public function payments()
{
return $this->hasMany(AgentPayment::class);
}

public function transactions()
{
return $this->hasMany(AgentTransaction::class);
}

/*
|--------------------------------
| حساب الرصيد تلقائياً
|--------------------------------
*/

public function getBalanceAttribute()
{
return $this->transactions()->sum('amount');
}

}