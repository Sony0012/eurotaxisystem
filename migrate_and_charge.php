<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\DriverBehavior;

echo "Starting Migration and Retroactive Charging...\n";

// 1. Migrate Legacy Net Shortages
$drivers = DB::select("
    SELECT d.id, 
           (SELECT GREATEST(0, COALESCE(SUM(shortage),0) - COALESCE(SUM(excess),0)) FROM boundaries WHERE driver_id = d.id AND deleted_at IS NULL) as net_shortage
    FROM drivers d
    WHERE d.deleted_at IS NULL AND d.driver_status != 'banned'
");

$migratedCount = 0;
foreach ($drivers as $driver) {
    if ($driver->net_shortage > 0) {
        // Check if we already migrated it (to be safe from double-running)
        $exists = DB::table('driver_behavior')
            ->where('driver_id', $driver->id)
            ->where('incident_type', 'Short Boundary')
            ->where('description', 'Legacy Boundary Shortage Balance')
            ->exists();
            
        if (!$exists) {
            DB::table('driver_behavior')->insert([
                'unit_id' => null,
                'driver_id' => $driver->id,
                'incident_type' => 'Short Boundary',
                'severity' => 'medium',
                'description' => 'Legacy Boundary Shortage Balance',
                'incident_date' => now()->toDateString(),
                'timestamp' => now(),
                'total_charge_to_driver' => $driver->net_shortage,
                'remaining_balance' => $driver->net_shortage,
                'charge_status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $migratedCount++;
        }
    }
}
echo "Migrated $migratedCount legacy shortage balances.\n";

// 2. Retroactively Charge Missed Boundaries
$units = DB::table('units')
    ->whereNull('deleted_at')
    ->whereNotNull('shift_deadline_at')
    ->whereNotIn('status', ['retired', 'maintenance'])
    ->get();

$chargedCount = 0;
$now = Carbon::now();

foreach ($units as $unit) {
    $deadline = Carbon::parse($unit->shift_deadline_at);
    if ($deadline->isPast()) {
        $diffHours = $deadline->diffInHours($now);
        $diffDays = floor($diffHours / 24);
        
        if ($diffDays >= 1) {
            $targetRate = $unit->boundary_rate;
            $driverId = $unit->current_turn_driver_id ?: $unit->driver_id;
            
            if ($targetRate > 0 && $driverId) {
                // Charge for each missing day specifically
                for ($i = 1; $i <= $diffDays; $i++) {
                    $missedDate = $deadline->copy()->addDays($i)->toDateString();
                    
                    // Prevent duplicate charges for the same date
                    $exists = DB::table('driver_behavior')
                        ->where('driver_id', $driverId)
                        ->where('incident_type', 'Missed Boundary')
                        ->where('incident_date', $missedDate)
                        ->exists();
                        
                    if (!$exists) {
                        DB::table('driver_behavior')->insert([
                            'unit_id' => $unit->id,
                            'driver_id' => $driverId,
                            'incident_type' => 'Missed Boundary',
                            'severity' => 'high',
                            'description' => "Auto-logged [Missed Boundary]: Unit not returned on $missedDate.",
                            'incident_date' => $missedDate,
                            'timestamp' => Carbon::parse($missedDate),
                            'total_charge_to_driver' => $targetRate,
                            'remaining_balance' => $targetRate,
                            'charge_status' => 'pending',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        $chargedCount++;
                    }
                }
            }
        }
    }
}

echo "Retroactively charged $chargedCount missed boundary days.\n";
echo "Done.\n";
