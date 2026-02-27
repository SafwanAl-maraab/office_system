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

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // الدفع تابع لفرع
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // الدفع تابع لوكيل
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    // العملة
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
