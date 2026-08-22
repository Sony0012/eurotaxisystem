<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPresenceConnection extends Model
{
    protected $table = 'user_presence_connections';

    protected $fillable = [
        'user_id',
        'connection_id',
        'session_id',
        'device_type',
        'browser',
        'platform',
        'ip_address',
        'user_agent',
        'connected_at',
        'last_seen_at',
        'last_activity_at',
        'disconnected_at',
        'is_active',
    ];

    protected $casts = [
        'connected_at'     => 'datetime',
        'last_seen_at'      => 'datetime',
        'last_activity_at'  => 'datetime',
        'disconnected_at'  => 'datetime',
        'is_active'        => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
