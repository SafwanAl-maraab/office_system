<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestDocument extends Model
{
    protected $fillable = [
        'request_id',
        'file_path',
        'file_type',
        'document_type',
        'uploaded_by'
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'uploaded_by');
    }
}
