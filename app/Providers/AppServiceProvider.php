<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Carbon::setLocale('en');
        date_default_timezone_set('Asia/Manila');

        // Fix for shared hosting MAX_JOIN_SIZE limitation
        // Allows complex queries with multiple JOINs to run without hitting row limits
        try {
            \Illuminate\Support\Facades\DB::statement('SET SQL_BIG_SELECTS=1');
        } catch (\Exception $e) {
            // Silent fail if DB not yet available (e.g. during migrations)
        }

        // Global Notifications Manager
        \Illuminate\Support\Facades\View::composer('layouts.app', function ($view) {
            if (!auth()->check()) {
                return;
            }

            try {
                $headerNotifications = [];
                $now = Carbon::now('Asia/Manila');
                $today = $now->toDateString();

                // ─── 1. AUTO-MAINTAIN SYSTEM ALERTS ──────────────────────────────────
                
                // A. Coding Notice Alert (Logic: once per day, only on weekdays)
                $todayDay = $now->format('l');
                $isWeekday = in_array($todayDay, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']);
                
                if ($isWeekday) {
                    $codingAlertExists = \Illuminate\Support\Facades\DB::table('system_alerts')
                        ->where('type', 'coding_notice')
                        ->whereDate('created_at', $today)
                        ->exists();

                    if (!$codingAlertExists) {
                        // Avoid duplicates from concurrent requests
                        try {
                            $allUnits = \Illuminate\Support\Facades\DB::table('units')->whereNull('deleted_at')->get();
                            $codingUnitsCount = $allUnits->filter(function($u) use ($todayDay) {
                                // Priority 1: Manual coding day in DB
                                if (!empty($u->coding_day) && $u->coding_day !== 'Unknown') return $u->coding_day === $todayDay;
                                // Priority 2: Automated check from plate
                                $plateSuffix = preg_replace('/[^0-9]/', '', $u->plate_number);
                                $lastDigit = substr($plateSuffix, -1);
                                $map = [1=>'Monday', 2=>'Monday', 3=>'Tuesday', 4=>'Tuesday', 5=>'Wednesday', 6=>'Wednesday', 7=>'Thursday', 8=>'Thursday', 9=>'Friday', 0=>'Friday'];
                                return ($map[$lastDigit] ?? 'Unknown') === $todayDay;
                            })->count();

                            if ($codingUnitsCount > 0) {
                                \Illuminate\Support\Facades\DB::table('system_alerts')->insert([
                                    'type' => 'coding_notice', 'title' => "Today's Unit Coding",
                                    'message' => "There are {$codingUnitsCount} units on coding today ({$todayDay}).",
                                    'is_resolved' => false, 'created_at' => $now, 'updated_at' => $now
                                ]);
                            }
                        } catch (\Exception $ex) { /* Handle race condition */ }
                    }
                }

                // B. Missing Unit Alerts (> 24h) & Auto-Resolve
                // Get all active alerts for missing units to check if they should be resolved
                $activeMissingAlerts = \Illuminate\Support\Facades\DB::table('system_alerts')
                    ->where('type', 'missing_unit')->where('is_resolved', false)->get();

                foreach ($activeMissingAlerts as $ama) {
                    $plateStr = str_replace("🚨 Missing Unit: ", "", $ama->title);
                    $u = \Illuminate\Support\Facades\DB::table('units')->where('plate_number', $plateStr)->whereNull('deleted_at')->first();
                    
                    // Resolve if: Unit not found, in maintenance, retired, or no longer 24h overdue
                    if (!$u || in_array(strtolower($u->status), ['maintenance', 'retired', 'surveillance']) 
                        || !$u->shift_deadline_at 
                        || Carbon::parse($u->shift_deadline_at)->diffInHours($now, false) < 24) {
                        \Illuminate\Support\Facades\DB::table('system_alerts')->where('id', $ama->id)->update(['is_resolved' => true, 'updated_at' => $now]);
                    }
                }

                // C. Generation of new Missing Alerts
                $missingUnits = \Illuminate\Support\Facades\DB::table('units')
                    ->leftJoin('drivers', 'units.current_turn_driver_id', '=', 'drivers.id')
                    ->whereNull('units.deleted_at')
                    ->whereRaw('LOWER(units.status) NOT IN (?, ?, ?)', ['maintenance', 'surveillance', 'retired'])
                    ->whereNotNull('units.shift_deadline_at')
                    ->where('units.shift_deadline_at', '<', $now->copy()->subHours(24))
                    ->where(function($q) {
                        $q->whereNotNull('units.driver_id')->orWhereNotNull('units.secondary_driver_id');
                    })
                    ->select('units.id', 'units.plate_number', 'drivers.first_name', 'drivers.last_name', 'units.shift_deadline_at')
                    ->get();

                foreach ($missingUnits as $mu) {
                    $deadline = Carbon::parse($mu->shift_deadline_at);
                    $diffHours = $now->diffInHours($deadline);
                    $diffDays = floor($diffHours / 24);
                    $driverName = $mu->first_name ? trim($mu->first_name . ' ' . $mu->last_name) : 'Unknown Driver';
                    $alertTitle = "🚨 Missing Unit: {$mu->plate_number}";
                    
                    $existingAlert = \Illuminate\Support\Facades\DB::table('system_alerts')
                        ->where('type', 'missing_unit')->where('title', $alertTitle)->where('is_resolved', false)->first();

                    $msg = "Unit {$mu->plate_number} has not remitted a boundary for {$diffDays}+ day(s). Last driver: {$driverName}.";

                    if (!$existingAlert) {
                        \Illuminate\Support\Facades\DB::table('system_alerts')->insert([
                            'type' => 'missing_unit', 'title' => $alertTitle, 'message' => $msg, 'is_resolved' => false,
                            'created_at' => $now, 'updated_at' => $now
                        ]);
                    } else {
                        \Illuminate\Support\Facades\DB::table('system_alerts')->where('id', $existingAlert->id)->update(['message' => $msg, 'updated_at' => $now]);
                    }

                    // --- AUTO-FLAGDOWN LOGIC (48 Hours) ---
                    if ($diffHours >= 48) {
                        $suspectId = \Illuminate\Support\Facades\DB::table('units')->where('id', $mu->id)->value('current_turn_driver_id');
                        if ($suspectId) {
                            $existingViolation = \Illuminate\Support\Facades\DB::table('driver_behavior')
                                ->where('driver_id', $suspectId)->where('unit_id', $mu->id)->where('incident_type', 'missing_unit_overdue')
                                ->where('incident_date', $deadline->toDateString())->exists();

                            if (!$existingViolation) {
                                \Illuminate\Support\Facades\DB::table('driver_behavior')->insert([
                                    'unit_id' => $mu->id, 'driver_id' => $suspectId, 'incident_type' => 'missing_unit_overdue',
                                    'severity' => 'high', 'description' => "Auto-logged [Flagdown]: Unit {$mu->plate_number} overdue >48h.",
                                    'latitude' => 0, 'longitude' => 0, 'video_url' => '', 'timestamp' => $now,
                                    'incident_date' => $deadline->toDateString(), 'created_at' => $now,
                                ]);
                            }
                        }
                    }
                }

                // ─── 2. GATHER NOTIFICATIONS ──────────────────────────────────────────
                $headerNotifications = app(\App\Services\NotificationService::class)->getGlobalNotifications();

                $view->with('globalNotifications', $headerNotifications);
                $view->with('globalNotificationCount', count($headerNotifications));

            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Global Notif Error: ' . $e->getMessage());
                $view->with('globalNotifications', []);
                $view->with('globalNotificationCount', 0);
            }
        });
    }
}
