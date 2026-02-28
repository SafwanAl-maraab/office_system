<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestType extends Model
{
protected $fillable = [
'branch_id',
'name',
'service_category',
'price',
'currency_id',
'status',
];

/* ================= Relationships ================= */

public function branch()
{
return $this->belongsTo(Branch::class);
}

public function currency()
{
return $this->belongsTo(Currency::class);
}

public function requests()
{
return $this->hasMany(Request::class);
}
}
