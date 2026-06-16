<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherAllocation extends Model
{
    protected $fillable = [

        'voucher_id',
        'invoice_id',
        'payment_id',

        'amount',

        'notes',

        'created_by'
    ];

    public function voucher()
    {
        return $this->belongsTo(ClientVoucher::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class,'created_by');
    }

}
