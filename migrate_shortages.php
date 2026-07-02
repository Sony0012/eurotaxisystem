<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Boundary;
use App\Models\DriverBehavior;

$boundaries = Boundary::where('shortage', '>', 0)->get();
$count = 0;

foreach ($boundaries as $boundary) {
    // Check if it already exists by boundary_id
    $existing = DriverBehavior::where('boundary_id', $boundary->id)->first();
    
    // Check if it exists by unit_id and date and type (fallback for old records)
    if (!$existing) {
        $existing = DriverBehavior::where('unit_id', $boundary->unit_id)
            ->where('incident_date', $boundary->date)
            ->where('incident_type', 'Short Boundary')
            ->first();
    }

    if ($existing) {
        // Update it with boundary_id if it doesn't have it
        if (!$existing->boundary_id) {
            $existing->update(['boundary_id' => $boundary->id]);
        }
        
        // Only update total_charge_to_driver if it's different. We won't touch remaining_balance if it was paid
        if ($existing->total_charge_to_driver != $boundary->shortage) {
            // Recalculate remaining
            $remaining = max(0, $boundary->shortage - $existing->total_paid);
            $existing->update([
                'total_charge_to_driver' => $boundary->shortage,
                'remaining_balance' => $remaining,
                'charge_status' => $remaining > 0 ? 'pending' : 'paid'
            ]);
        }
    } else {
        // Create new
        DriverBehavior::create([
            'boundary_id'             => $boundary->id,
            'unit_id'                 => $boundary->unit_id,
            'driver_id'               => $boundary->driver_id,
            'incident_type'           => 'Short Boundary',
            'severity'                => 'medium',
            'description'             => 'Auto-logged: Boundary Shortage for ' . \Carbon\Carbon::parse($boundary->date)->format('M d, Y'),
            'incident_date'           => $boundary->date,
            'timestamp'               => \Carbon\Carbon::parse($boundary->created_at)->timestamp ?? time(),
            'total_charge_to_driver'  => $boundary->shortage,
            'total_paid'              => 0,
            'remaining_balance'       => $boundary->shortage,
            'charge_status'           => 'pending',
            'created_at'              => $boundary->created_at,
            'updated_at'              => $boundary->updated_at,
        ]);
        $count++;
    }
}

echo "Migrated $count new shortages to driver_behavior.\n";
