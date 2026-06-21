<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Travel extends Model
{
    protected $table = 'travels';

    protected $fillable = [
        'branch_id',
        'travel_date',
        'driver_id',
        'capacity',
        'driver_cost',
        'currency_id',
        'from_location',
        'to_location',
        'notes',
        'status' // تأكد من إضافته لتتمكن من تحديث حالة الرحلة (active / completed)
    ];

    /*
    |--------------------------------------------------------------------------
    | العلاقات (Relationships)
    |--------------------------------------------------------------------------
    */

    // الرحلة تتبع فرع معين
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // الرحلة مرتبطة بطلب أو عدة طلبات (حجوزات / شحن)
    public function requests()
    {
        return $this->belongsToMany(Request::class, 'travel_requests')
            ->withPivot('seat_number')
            ->withTimestamps();
    }

    // الرحلة يقودها سائق واحد
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    // المضافة حديثاً: أجرة السائق مربوطة بعملة محددة
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
