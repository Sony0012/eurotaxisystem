<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserPresenceConnection;
use App\Models\UserActivityInterval;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PresenceService
{
    /**
     * Record a server-validated heartbeat from an authenticated browser tab/device.
     */
    public function recordHeartbeat(User $user, string $connectionId, array $metadata = []): array
    {
        $now = now();
        $today = $now->toDateString();
        $offlineTimeout = (int) config('presence.offline_timeout', 30);
        $idleTimeout    = (int) config('presence.idle_timeout', 300);
        $hasInteraction = !empty($metadata['has_interaction']);

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
            $connection = UserPresenceConnection::create([
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

        // 2. Synchronize user master record
        User::where('id', $user->id)->update([
            'last_seen_at' => $now,
            'is_online'    => true,
        ]);

        // 3. Manage daily presence activity interval
        // Find latest interval for this user on today's calendar date
        $latestInterval = UserActivityInterval::where('user_id', $user->id)
            ->where('date', $today)
            ->orderByDesc('ended_at')
            ->first();

        // If an interval exists and ended recently (within offline timeout + 15s grace buffer)
        $graceThreshold = $now->copy()->subSeconds($offlineTimeout + 15);
        if ($latestInterval && Carbon::parse($latestInterval->ended_at)->gte($graceThreshold)) {
            $startedAt = Carbon::parse($latestInterval->started_at);
            // If cross-midnight handling is needed: date is already constrained to today
            $latestInterval->update([
                'ended_at'         => $now,
                'duration_seconds' => max(0, $startedAt->diffInSeconds($now)),
            ]);
        } else {
            // Start a new disjoint presence interval for today
            UserActivityInterval::create([
                'user_id'          => $user->id,
                'connection_id'    => $connectionId,
                'date'             => $today,
                'started_at'       => $now,
                'ended_at'         => $now,
                'duration_seconds' => 0,
            ]);
        }

        // 4. Return current status calculation
        $status = $this->determineUserStatus($user);

        return [
            'success'       => true,
            'status'        => $status,
            'connection_id' => $connectionId,
            'server_time'   => $now->toIso8601String(),
        ];
    }

    /**
     * Record clean disconnection of a tab (e.g. pagehide / unload).
     */
    public function recordDisconnect(User $user, string $connectionId): void
    {
        $now = now();
        UserPresenceConnection::where('connection_id', $connectionId)->update([
            'is_active'       => false,
            'disconnected_at' => $now,
            'last_seen_at'    => $now,
        ]);

        // Check if user has any remaining active connections
        $offlineTimeout = (int) config('presence.offline_timeout', 30);
        $hasActiveConn = UserPresenceConnection::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('last_seen_at', '>=', $now->copy()->subSeconds($offlineTimeout))
            ->exists();

        if (!$hasActiveConn) {
            User::where('id', $user->id)->update(['is_online' => false]);
        }
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

        $activeConnections = UserPresenceConnection::where('user_id', $userId)
            ->where('is_active', true)
            ->where('last_seen_at', '>=', $now->copy()->subSeconds($offlineTimeout))
            ->get();

        if ($activeConnections->isEmpty()) {
            return 'inactive';
        }

        // If any connected tab had user interaction within idleTimeout
        $hasRecentInteraction = $activeConnections->contains(function ($conn) use ($now, $idleTimeout) {
            if (!$conn->last_activity_at) return false;
            return Carbon::parse($conn->last_activity_at)->gte($now->copy()->subSeconds($idleTimeout));
        });

        return $hasRecentInteraction ? 'active' : 'idle';
    }

    /**
     * Calculate exact accumulated active working time for a user on a given date.
     * Merges overlapping intervals from multiple tabs/devices to prevent double counting.
     */
    public function calculateTodayActiveTime(int $userId, ?string $date = null): array
    {
        $date = $date ?: now()->toDateString();
        $targetHours = (int) config('presence.default_target_hours', 4);

        $rawIntervals = UserActivityInterval::where('user_id', $userId)
            ->where('date', $date)
            ->orderBy('started_at')
            ->get(['started_at', 'ended_at', 'duration_seconds']);

        if ($rawIntervals->isEmpty()) {
            return [
                'total_seconds' => 0,
                'total_mins'    => 0,
                'total_hours'   => 0.0,
                'percentage'    => 0,
                'formatted'     => '0h',
                'intervals'     => [],
            ];
        }

        // Merge overlapping or adjacent intervals
        $merged = [];
        $currentStart = null;
        $currentEnd   = null;

        foreach ($rawIntervals as $interval) {
            $start = Carbon::parse($interval->started_at);
            $end   = Carbon::parse($interval->ended_at);

            // Ensure minimum valid end time
            if ($end->lt($start)) {
                $end = $start->copy();
            }

            if ($currentStart === null) {
                $currentStart = $start->copy();
                $currentEnd   = $end->copy();
            } else {
                // If this interval overlaps or is adjacent (gap <= 30 seconds) with the current merged block
                if ($start->lte($currentEnd->copy()->addSeconds(30))) {
                    if ($end->gt($currentEnd)) {
                        $currentEnd = $end->copy();
                    }
                } else {
                    // Push completed merged interval
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
        $totalMins    = (int) round($totalSeconds / 60);
        $totalHours   = round($totalMins / 60, 2);
        $percentage   = ($targetHours > 0) ? min(100, (int) round(($totalMins / ($targetHours * 60)) * 100)) : 0;

        // Format string (e.g. "1h 30m" or "45m" or "2h")
        $h = intdiv($totalMins, 60);
        $m = $totalMins % 60;
        if ($h > 0 && $m > 0) {
            $formatted = "{$h}h {$m}m";
        } elseif ($h > 0) {
            $formatted = "{$h}h";
        } else {
            $formatted = "{$m}m";
        }

        return [
            'total_seconds' => $totalSeconds,
            'total_mins'    => $totalMins,
            'total_hours'   => $totalHours,
            'percentage'    => $percentage,
            'formatted'     => $formatted,
            'intervals'     => $merged,
        ];
    }

    /**
     * Cleanup stale / timed-out connections and auto-offline disconnected users.
     */
    public function cleanupStaleConnections(): int
    {
        $offlineTimeout = (int) config('presence.offline_timeout', 30);
        $cutoff = now()->subSeconds($offlineTimeout);

        // Mark inactive connections
        $affected = UserPresenceConnection::where('is_active', true)
            ->where('last_seen_at', '<', $cutoff)
            ->update([
                'is_active'       => false,
                'disconnected_at' => DB::raw('last_seen_at'),
            ]);

        // Find users with 0 active connections who are marked is_online = true
        $activeUserIds = UserPresenceConnection::where('is_active', true)
            ->where('last_seen_at', '>=', $cutoff)
            ->pluck('user_id')
            ->unique();

        User::where('is_online', true)
            ->whereNotIn('id', $activeUserIds)
            ->update(['is_online' => false]);

        return $affected;
    }
}
