<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Heartbeat & Presence Tracking Configuration
    |--------------------------------------------------------------------------
    */

    // Number of seconds between frontend heartbeat pings
    'heartbeat_interval' => (int) env('PRESENCE_HEARTBEAT_INTERVAL', 10),

    // Number of seconds without a heartbeat after which a connection is deemed inactive/offline
    'offline_timeout' => (int) env('PRESENCE_OFFLINE_TIMEOUT', 30),

    // Number of seconds without meaningful user interaction before status flips from ACTIVE to IDLE
    'idle_timeout' => (int) env('PRESENCE_IDLE_TIMEOUT', 300),

    // Whether idle connected time counts toward the daily active working target (default: true)
    'count_idle_as_active' => (bool) env('PRESENCE_COUNT_IDLE_AS_ACTIVE', true),

    // Default daily target in hours
    'default_target_hours' => (int) env('PRESENCE_DEFAULT_TARGET_HOURS', 4),
];
