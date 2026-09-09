<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TrackChanges;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use TrackChanges, SoftDeletes;
    protected $table = 'drivers';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'nickname',
        'profile_photo',
        'license_number',
        'contact_number',
        'license_expiry',
        'license_photo',
        'nbi_clearance_photo',
        'pnp_clearance_photo',
        'hire_date',
        'daily_boundary_target',
        'address',
        'emergency_contact',
        'emergency_phone',
        'driver_type',
        'driver_status',
        'suspended_until',
        'suspension_reason',
        'designation',
        'notes',
        'created_by',
        'updated_by',
    ];

    public function getFullNameAttribute()
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? '')) ?: 'N/A';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function booted()
    {
        static::deleting(function ($driver) {
            Unit::where('driver_id', $driver->id)->update(['driver_id' => null, 'updated_at' => now()]);
            Unit::where('secondary_driver_id', $driver->id)->update(['secondary_driver_id' => null, 'updated_at' => now()]);
            Unit::where('current_turn_driver_id', $driver->id)->update(['current_turn_driver_id' => null, 'updated_at' => now()]);
        });

        static::saved(function ($driver) {
            if (in_array($driver->driver_status, ['banned', 'suspended'])) {
                Unit::where('driver_id', $driver->id)->update(['driver_id' => null, 'updated_at' => now()]);
                Unit::where('secondary_driver_id', $driver->id)->update(['secondary_driver_id' => null, 'updated_at' => now()]);
                Unit::where('current_turn_driver_id', $driver->id)->update(['current_turn_driver_id' => null, 'updated_at' => now()]);
            }
        });
    }
}
