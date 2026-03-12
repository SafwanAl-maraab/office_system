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

/*
|--------------------------------------------------------------------------
| Relations
|--------------------------------------------------------------------------
*/

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
|--------------------------------------------------------------------------
| الرصيد الإجمالي (قد يخلط العملات)
|--------------------------------------------------------------------------
*/

public function getBalanceAttribute()
{
return $this->transactions()->sum('amount');
}

/*
|--------------------------------------------------------------------------
| الرصيد حسب العملة
|--------------------------------------------------------------------------
*/

public function balances()
{
return $this->transactions()
->selectRaw('currency_id, SUM(amount) as total')
->groupBy('currency_id')
->with('currency');
}

/*
|--------------------------------------------------------------------------
| جلب العملات التي للوكيل معاملات بها
|--------------------------------------------------------------------------
*/

public function currencies()
{
return $this->transactions()
->select('currency_id')
->distinct()
->with('currency');
}

}