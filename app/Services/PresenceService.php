<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserPresenceConnection;
use App\Models\UserActivityInterval;
use App\Models\LoginAudit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class PresenceService
{
    /**
     * Ensure presence tables exist in database (self-healing schema).
     */
    public function ensureTablesExist(): void
    {
        try {
            if (!Schema::hasTable('user_presence_connections')) {
                Schema::create('user_presence_connections', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('user_id')->index();
                    $table->string('connection_id', 64)->unique();
                    $table->string('session_id', 128)->nullable();
                    $table->string('device_type', 32)->default('desktop');
                    $table->string('browser', 64)->nullable();
                    $table->string('platform', 64)->nullable();
                    $table->string('ip_address', 45)->nullable();
                    $table->text('user_agent')->nullable();
                    $table->dateTime('connected_at');
                    $table->dateTime('last_seen_at')->index();
                    $table->dateTime('last_activity_at')->nullable();
                    $table->dateTime('disconnected_at')->nullable();
                    $table->boolean('is_active')->default(true)->index();
                    $table->timestamps();

                    $table->index(['user_id', 'is_active']);
                });
            }

            if (!Schema::hasTable('user_activity_intervals')) {
                Schema::create('user_activity_intervals', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('user_id')->index();
                    $table->string('connection_id', 64)->nullable()->index();
                    $table->date('date')->index();
                    $table->dateTime('started_at')->index();
                    $table->dateTime('ended_at')->index();
                    $table->unsignedInteger('duration_seconds')->default(0);
                    $table->timestamps();

                    $table->index(['user_id', 'date']);
                });
            }
        } catch (\Throwable $e) {
            // Schema creation failed or lacks permissions - will use fallback
        }
    }

    /**
     * Record a server-validated heartbeat from an authenticated browser tab/device.
     */
    public function recordHeartbeat(User $user, string $connectionId, array $metadata = []): array
    {
        $this->ensureTablesExist();

        $now = now();
        $today = $now->toDateString();
        $offlineTimeout = (int) config('presence.offline_timeout', 30);
        $idleTimeout    = (int) config('presence.idle_timeout', 300);
        $hasInteraction = !empty($metadata['has_interaction']);

        try {
            // 1. Upsert connection record for this specific tab/device
            $connection = UserPresenceConnection::where('connection_id', $connectionId)->first();

            if ($connection) {
                $updateData = [
                    'user_id'         => $user->id,
                    'last_seen_at'    => $now,
                    'is_active'       => true,
                    'disconnected_at' => null,
                ];
                if ($hasInteraction || !$connection->last_activity_at) {
                    $updateData['last_activity_at'] = $now;
                }
                if (!empty($metadata['session_id'])) {
                    $updateData['session_id'] = $metadata['session_id'];
                }
                if (!empty($metadata['ip_address'])) {
                    $updateData['ip_address'] = $metadata['ip_address'];
                }
                $connection->update($updateData);
            } else {
                UserPresenceConnection::create([
                    'user_id'          => $user->id,
                    'connection_id'    => $connectionId,
                    'session_id'       => $metadata['session_id'] ?? session()->getId(),
                    'device_type'      => $metadata['device_type'] ?? 'desktop',
                    'browser'          => $metadata['browser'] ?? null,
                    'platform'         => $metadata['platform'] ?? null,
                    'ip_address'       => $metadata['ip_address'] ?? request()->ip(),
                    'user_agent'       => $metadata['user_agent'] ?? request()->userAgent(),
                    'connected_at'     => $now,
                    'last_seen_at'     => $now,
                    'last_activity_at' => $now,
                    'is_active'        => true,
                ]);
            }
        } catch (\Throwable $e) {
            // Log fallback
        }

        // 2. Synchronize user master record safely
        try {
            User::where('id', $user->id)->update([
                'last_seen_at' => $now,
                'is_online'    => true,
            ]);
        } catch (\Throwable $e) {}

        // 3. Manage daily presence activity interval
        try {
            $latestInterval = UserActivityInterval::where('user_id', $user->id)
                ->where('date', $today)
                ->orderByDesc('ended_at')
                ->first();

            $graceThreshold = $now->copy()->subSeconds($offlineTimeout + 15);
            if ($latestInterval && Carbon::parse($latestInterval->ended_at)->gte($graceThreshold)) {
                $startedAt = Carbon::parse($latestInterval->started_at);
                $latestInterval->update([
                    'ended_at'         => $now,
                    'duration_seconds' => max(0, $startedAt->diffInSeconds($now)),
                ]);
            } else {
                UserActivityInterval::create([
                    'user_id'          => $user->id,
                    'connection_id'    => $connectionId,
                    'date'             => $today,
                    'started_at'       => $now,
                    'ended_at'         => $now,
                    'duration_seconds' => 0,
                ]);
            }
        } catch (\Throwable $e) {}

        $status = $this->determineUserStatus($user);

        return [
            'success'       => true,
            'status'        => $status,
            'connection_id' => $connectionId,
            'server_time'   => $now->toIso8601String(),
        ];
    }

    /**
     * Record clean disconnection of a tab.
     */
    public function recordDisconnect(User $user, string $connectionId): void
    {
        $now = now();
        try {
            UserPresenceConnection::where('connection_id', $connectionId)->update([
                'is_active'       => false,
                'disconnected_at' => $now,
                'last_seen_at'    => $now,
            ]);

            $offlineTimeout = (int) config('presence.offline_timeout', 30);
            $hasActiveConn = UserPresenceConnection::where('user_id', $user->id)
                ->where('is_active', true)
                ->where('last_seen_at', '>=', $now->copy()->subSeconds($offlineTimeout))
                ->exists();

            if (!$hasActiveConn) {
                User::where('id', $user->id)->update(['is_online' => false]);
            }
        } catch (\Throwable $e) {}
    }

    /**
     * Determine user's real-time presence status: 'active', 'idle', or 'inactive'.
     */
    public function determineUserStatus(User|int $user): string
    {
        $userId = $user instanceof User ? $user->id : $user;
        $offlineTimeout = (int) config('presence.offline_timeout', 30);
        $idleTimeout    = (int) config('presence.idle_timeout', 300);
        $now            = now();

        try {
            if (Schema::hasTable('user_presence_connections')) {
                $activeConnections = UserPresenceConnection::where('user_id', $userId)
                    ->where('is_active', true)
                    ->where('last_seen_at', '>=', $now->copy()->subSeconds($offlineTimeout))
                    ->get();

                if ($activeConnections->isNotEmpty()) {
                    $hasRecentInteraction = $activeConnections->contains(function ($conn) use ($now, $idleTimeout) {
                        if (!$conn->last_activity_at) return false;
                        return Carbon::parse($conn->last_activity_at)->gte($now->copy()->subSeconds($idleTimeout));
                    });

                    return $hasRecentInteraction ? 'active' : 'idle';
                }
            }
        } catch (\Throwable $e) {}

        // Fallback to User table presence
        try {
            $u = ($user instanceof User) ? $user : User::find($userId);
            if ($u && $u->last_seen_at && Carbon::parse($u->last_seen_at)->gte($now->copy()->subSeconds($offlineTimeout))) {
                return 'active';
            }
        } catch (\Throwable $e) {}

        return 'inactive';
    }

    /**
     * Calculate exact accumulated active working time for a user on a given date.
     * Merges overlapping intervals from multiple tabs/devices to prevent double counting.
     */
    public function calculateTodayActiveTime(int $userId, ?string $date = null): array
    {
        $date = $date ?: now()->toDateString();
        $targetHours = (int) config('presence.default_target_hours', 4);

        try {
            if (Schema::hasTable('user_activity_intervals')) {
                $rawIntervals = UserActivityInterval::where('user_id', $userId)
                    ->where('date', $date)
                    ->orderBy('started_at')
                    ->get(['started_at', 'ended_at', 'duration_seconds']);

                if ($rawIntervals->isNotEmpty()) {
                    $merged = [];
                    $currentStart = null;
                    $currentEnd   = null;

                    $isUserOnline = $this->determineUserStatus($userId) !== 'inactive';

                    foreach ($rawIntervals as $idx => $interval) {
                        $start = Carbon::parse($interval->started_at);
                        $end   = Carbon::parse($interval->ended_at);

                        // If user is currently online and this is the latest interval, extend end to now()
                        if ($isUserOnline && $idx === (count($rawIntervals) - 1)) {
                            if ($end->lt(now())) {
                                $end = now();
                            }
                        }

                        if ($end->lt($start)) $end = $start->copy();

                        if ($currentStart === null) {
                            $currentStart = $start->copy();
                            $currentEnd   = $end->copy();
                        } else {
                            if ($start->lte($currentEnd->copy()->addSeconds(30))) {
                                if ($end->gt($currentEnd)) $currentEnd = $end->copy();
                            } else {
                                $merged[] = [
                                    'start'   => $currentStart->copy(),
                                    'end'     => $currentEnd->copy(),
                                    'seconds' => max(0, $currentStart->diffInSeconds($currentEnd)),
                                ];
                                $currentStart = $start->copy();
                                $currentEnd   = $end->copy();
                            }
                        }
                    }

                    if ($currentStart !== null) {
                        $merged[] = [
                            'start'   => $currentStart->copy(),
                            'end'     => $currentEnd->copy(),
                            'seconds' => max(0, $currentStart->diffInSeconds($currentEnd)),
                        ];
                    }

                    $totalSeconds = (int) collect($merged)->sum('seconds');
                    $totalMins    = max(1, (int) round($totalSeconds / 60));
                    $totalHours   = round($totalMins / 60, 2);
                    $percentage   = ($targetHours > 0) ? min(100, (int) round(($totalMins / ($targetHours * 60)) * 100)) : 0;

                    $h = intdiv($totalMins, 60);
                    $m = $totalMins % 60;
                    $formatted = ($h > 0 && $m > 0) ? "{$h}h {$m}m" : (($h > 0) ? "{$h}h" : "{$m}m");

                    return [
                        'total_seconds' => $totalSeconds,
                        'total_mins'    => $totalMins,
                        'total_hours'   => $totalHours,
                        'percentage'    => $percentage,
                        'formatted'     => $formatted,
                        'intervals'     => $merged,
                    ];
                }
            }
        } catch (\Throwable $e) {}

        return [
            'total_seconds' => 0,
            'total_mins'    => 0,
            'total_hours'   => 0.0,
            'percentage'    => 0,
            'formatted'     => '0h',
            'intervals'     => [],
        ];
    }

    /**
     * Cleanup stale connections.
     */
    public function cleanupStaleConnections(): int
    {
        $offlineTimeout = (int) config('presence.offline_timeout', 30);
        $cutoff = now()->subSeconds($offlineTimeout);
        $affected = 0;

        try {
            if (Schema::hasTable('user_presence_connections')) {
                $affected = UserPresenceConnection::where('is_active', true)
                    ->where('last_seen_at', '<', $cutoff)
                    ->update([
                        'is_active'       => false,
                        'disconnected_at' => DB::raw('last_seen_at'),
                    ]);

                $activeUserIds = UserPresenceConnection::where('is_active', true)
                    ->where('last_seen_at', '>=', $cutoff)
                    ->pluck('user_id')
                    ->unique();

                User::where('is_online', true)
                    ->whereNotIn('id', $activeUserIds)
                    ->update(['is_online' => false]);
            }
        } catch (\Throwable $e) {}

        return $affected;
    }
}
