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

    public function agentPayment()
    {
        return $this->belongsTo(AgentPayment::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}