<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAccountStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->is_disabled) {
                $reason = $user->disable_reason ?? 'Your account has been temporarily disabled by the Owner/Super Admin.';

                // For API requests, just return JSON without touching session
                if ($request->expectsJson() || $request->is('api/*')) {
                    Auth::logout();
                    return response()->json(['success' => false, 'message' => $reason], 403);
                }

                // For web requests, handle session
                Auth::logout();
                try {
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                } catch (\Exception $e) {
                    // Session not available, skip
                }

                return redirect()->route('login')->withErrors(['email' => $reason]);
            }

            // Update user presence & active timestamp (throttled to avoid redundant DB writes)
            $needsPresenceUpdate = !$user->last_seen_at || \Carbon\Carbon::parse($user->last_seen_at)->diffInSeconds(now()) >= 15 || !$user->is_online;
            if ($needsPresenceUpdate) {
                \App\Models\User::where('id', $user->id)->update([
                    'last_seen_at' => now(),
                    'is_online'    => true,
                ]);

                // Record session start & continuous active checkpoints in LoginAudit
                $today = now()->toDateString();
                $lastAudit = \App\Models\LoginAudit::where('user_id', $user->id)
                    ->whereDate('created_at', $today)
                    ->orderByDesc('created_at')
                    ->first();

                // If first time active today, or inactive gap >= 30 minutes, mark session_start
                if (!$lastAudit || \Carbon\Carbon::parse($lastAudit->created_at)->diffInMinutes(now()) >= 30) {
                    \App\Models\LoginAudit::log('session_start', $user, 'Session active / opened dashboard');
                } elseif (\Carbon\Carbon::parse($lastAudit->created_at)->diffInMinutes(now()) >= 5) {
                    // Checkpoint every 5 minutes during continuous active usage
                    \App\Models\LoginAudit::log('active_presence', $user, 'Active usage continuous checkpoint');
                }
            }
        }

        return $next($request);
    }
}
