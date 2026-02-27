<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestStatusHistory extends Model
{
    protected $fillable = [
        'request_id',
        'old_status',
        'new_status',
        'changed_by',
        'notes'
    ];

    // العلاقات

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'changed_by');
    }
}
