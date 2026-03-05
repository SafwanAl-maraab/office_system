<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class VisaStatusHistory extends Model
{
    protected $fillable = [
        'visa_id',
        'old_status',
        'new_status',
        'changed_by',
        'notes'
    ];

    public function visa()
    {
        return $this->belongsTo(Visa::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'changed_by');
    }
}
