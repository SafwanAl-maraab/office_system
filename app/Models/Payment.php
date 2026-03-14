<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    protected $fillable = [
        'branch_id',
        'client_id',
        'invoice_id',
        'created_by',
        'amount',
        'currency_id',
        'payment_method'

    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class );
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class , 'created_by');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
    public function creator()
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }
}
