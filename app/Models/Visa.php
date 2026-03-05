<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visa extends Model
{
    use HasFactory;

    protected $table = 'visas';

    protected $fillable = [
        'branch_id',
        'client_id',
        'visa_type_id',
        'package_id',
        'trip_group_id',
        'trip_group_bus_id',
        'agent_id',
'visa_number',
        'passport_number',
        'sponsor_name',
        'job_title',

        'original_price',
        'discount_percentage',
        'discount_amount',
        'sale_price',
        'cost_price',
        'agent_cost',

        'currency_id',
        'status',

        'issue_date',
        'expiry_date',

        'document_file',
        'image_file',

        'cancel_reason',
        'created_by'
    ];

    protected $casts = [
        'original_price' => 'float',
        'discount_percentage' => 'float',
        'discount_amount' => 'float',
        'sale_price' => 'float',
        'cost_price' => 'float',
        'agent_cost' => 'float',
        'issue_date' => 'date',
        'expiry_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function visaType()
    {
        return $this->belongsTo(VisaType::class);
    }

    public function package()
    {
        return $this->belongsTo(ServicePackage::class, 'package_id');
    }

    public function tripGroup()
    {
        return $this->belongsTo(TripGroup::class);
    }

    public function tripGroupBus()
    {
        return $this->belongsTo(TripGroupBus::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'reference_id')
            ->where('reference_type', 'visa');
    }

    public function statusHistories()
    {
        return $this->hasMany(VisaStatusHistory::class);
    }

    public function agentTransactions()
    {
        return $this->hasMany(AgentTransaction::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors (حسابات تلقائية)
    |--------------------------------------------------------------------------
    */

    // حساب الربح تلقائياً
    public function getProfitAttribute()
    {
        return ($this->sale_price ?? 0) - ($this->cost_price ?? 0);
    }

    // هل التأشيرة مدفوعة بالكامل؟
    public function getIsPaidAttribute()
    {
        return $this->invoice && $this->invoice->status === 'paid';
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function isIssued()
    {
        return $this->status === 'issued';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }
}