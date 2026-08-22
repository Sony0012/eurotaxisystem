<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivityInterval extends Model
{
    protected $table = 'user_activity_intervals';

    protected $fillable = [
        'user_id',
        'connection_id',
        'date',
        'started_at',
        'ended_at',
        'duration_seconds',
    ];

    protected $casts = [
        'date'             => 'date',
        'started_at'       => 'datetime',
        'ended_at'         => 'datetime',
        'duration_seconds' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
