<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Request extends Model
{
    protected $fillable = [
        'branch_id',
        'client_id',
        'request_type_id',
        'request_number',
        'request_date',
        'status',

        'received_by',
        'notes'
    ];

    // العلاقات

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'received_by');
    }

    public function statuses()
    {
        return $this->hasMany(RequestStatusHistory::class);
    }

    public function travels()
    {
        return $this->belongsToMany(Travel::class, 'travel_requests')
            ->withPivot('seat_number')
            ->withTimestamps();
    }

    public function documents()
    {
        return $this->hasMany(RequestDocument::class);
    }


    public function requestType()
    {
        return $this->belongsTo(RequestType::class);
    }


    public function invoice()
    {
        return $this->hasOne(\App\Models\Invoice::class, 'reference_id')
            ->where('reference_type', 'request');
    }

    public function statusHistories()
    {
        return $this->hasMany(\App\Models\RequestStatusHistory::class);
    }


}
