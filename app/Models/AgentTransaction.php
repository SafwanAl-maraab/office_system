<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentTransaction extends Model
{

protected $fillable = [
'agent_id',
'branch_id',
'visa_id',
'agent_payment_id',
'type',
'amount',
'currency_id'
];

/*
|--------------------------------------------------------------------------
| Relations
|--------------------------------------------------------------------------
*/

public function agent()
{
return $this->belongsTo(Agent::class);
}

public function branch()
{
return $this->belongsTo(Branch::class);
}

public function visa()
{
return $this->belongsTo(Visa::class);
}

public function payment()
{
return $this->belongsTo(AgentPayment::class,'agent_payment_id');
}

public function currency()
{
return $this->belongsTo(Currency::class);
}

/*
|--------------------------------------------------------------------------
| أنواع العمليات
|--------------------------------------------------------------------------
*/

public const TYPE_VISA_COST = 'visa_cost';
public const TYPE_PAYMENT   = 'payment';
public const TYPE_ADJUSTMENT = 'adjustment';
public const TYPE_EXCHANGE = 'exchange';

/*
|--------------------------------------------------------------------------
| هل العملية دين؟
|--------------------------------------------------------------------------
*/

public function isDebit()
{
return $this->amount > 0;
}

/*
|--------------------------------------------------------------------------
| هل العملية دفع؟
|--------------------------------------------------------------------------
*/

public function isCredit()
{
return $this->amount < 0;
}

}