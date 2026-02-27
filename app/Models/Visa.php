<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visa extends Model
{
    //
    protected $fillable = [
        'branch_id',
        'client_id',
        'visa_type_id',
        'package_id',
        'trip_group_id',
        'trip_group_bus_id',
        'agent_id',
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

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }

    public function tripGroupBus()
    {
        return $this->belongsTo(TripGroupBus::class);
    }
}
