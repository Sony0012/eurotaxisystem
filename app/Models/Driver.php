<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $table = 'drivers';

    protected $fillable = [
        'user_id',
        'license_number',
        'contact_number',
        'license_expiry',
        'hire_date',
        'daily_boundary_target',
        'address',
        'emergency_contact',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
