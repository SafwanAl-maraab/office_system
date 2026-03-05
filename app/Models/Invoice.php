<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    //
    protected $fillable = [
        'branch_id',
        'client_id',
        'reference_type',
        'reference_id',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'currency_id',
        'status',
        'is_refund',
        'reversed_invoice_id'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function currency()
    {
        return $this->belongsTo(\App\Models\Currency::class);
    }

    public function refundInvoices()
    {
        return $this->hasMany(Invoice::class,'reversed_invoice_id');
    }
}
