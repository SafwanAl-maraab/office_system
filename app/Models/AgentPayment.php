<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentPayment extends Model
{
use HasFactory;

protected $fillable = [
'branch_id',
'agent_id',
'amount',
'currency_id',
'description',
];

public function branch()
{
return $this->belongsTo(Branch::class);
}

public function agent()
{
return $this->belongsTo(Agent::class);
}

public function currency()
{
return $this->belongsTo(Currency::class);
}

public function transactions()
{
return $this->hasMany(AgentTransaction::class);
}

}