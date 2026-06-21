<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RolePermissionPeriod extends Model
{
    use HasFactory;

    protected $fillable = [

        'role_id',

        'permission',

        'start_at',

        'end_at',

        'granted_by',

        'revoked_by',

        'reason',

    ];

    protected $casts = [

        'start_at' => 'datetime',

        'end_at' => 'datetime',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function role()
    {
        return $this->belongsTo(
            \Spatie\Permission\Models\Role::class
        );
    }

    public function grantedBy()
    {
        return $this->belongsTo(
            User::class,
            'granted_by'
        );
    }

    public function revokedBy()
    {
        return $this->belongsTo(
            User::class,
            'revoked_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->whereNull('end_at');
    }

    public function scopeExpired($query)
    {
        return $query->whereNotNull('end_at');
    }
}
