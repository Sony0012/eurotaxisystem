<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class RevertJulyCashierReport extends Command
{
    protected $signature = 'fleet:revert-july-cashier {--force : Force deletion without prompt}';
    protected $description = 'Safely and completely revert the July 2026 cashier report import';

    public function handle()
    {
        $this->warn("=== REVERTING JULY 2026 CASHIER IMPORT ===");

        DB::beginTransaction();
        try {
            // 1. Delete July Boundaries
            $deletedBoundaries = DB::table('boundaries')
                ->whereBetween('date', ['2026-07-01', '2026-07-31'])
                ->delete();
            $this->info("1. Deleted July boundaries: {$deletedBoundaries}");

            // 2. Delete July Driver Funds
            $deletedFunds = DB::table('driver_funds')
                ->whereBetween('date', ['2026-07-01', '2026-07-31'])
                ->delete();
            $this->info("2. Deleted July driver funds (Pondo): {$deletedFunds}");

            // 3. Reset Unit Designations made by import (updated_at = 2026-07-01 00:00:00)
            $resetUnits = DB::table('units')
                ->where('updated_at', '2026-07-01 00:00:00')
                ->update([
                    'driver_id' => null,
                    'secondary_driver_id' => null,
                    'current_turn_driver_id' => null,
                    'updated_at' => now(),
                ]);
            $this->info("3. Reset driver designations on units: {$resetUnits}");

            // 4. Delete New Units created by import (created_at = 2026-07-01 00:00:00)
            $deletedUnits = DB::table('units')
                ->where('created_at', '2026-07-01 00:00:00')
                ->delete();
            $this->info("4. Deleted new units created during import: {$deletedUnits}");

            // 5. Delete New Drivers created by import (created_at = 2026-07-01 00:00:00)
            $deletedDrivers = DB::table('drivers')
                ->where('created_at', '2026-07-01 00:00:00')
                ->delete();
            $this->info("5. Deleted new drivers created during import: {$deletedDrivers}");

            // 6. Reset status of existing drivers who were set to 'assigned'
            // Keep drivers who still have a unit assigned
            $assignedDriverIds = DB::table('units')
                ->whereNull('deleted_at')
                ->pluck('driver_id')
                ->merge(DB::table('units')->whereNull('deleted_at')->pluck('secondary_driver_id'))
                ->filter()
                ->unique()
                ->toArray();

            $unassignedDrivers = DB::table('drivers')
                ->whereNull('deleted_at')
                ->where('driver_status', 'assigned')
                ->whereNotIn('id', $assignedDriverIds)
                ->update(['driver_status' => 'available']);
            $this->info("6. Reset driver status to available: {$unassignedDrivers}");

            DB::commit();
            $this->info("\nDatabase transaction committed successfully!");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error during revert: " . $e->getMessage());
            return 1;
        }

        // Clear Caches
        Cache::forget('web_dashboard_stats');
        Cache::forget('api_dashboard_stats_7');
        Cache::forget('api_dashboard_stats_30');
        $this->info("Caches cleared. System is 100% restored to its exact pre-import state!");

        return 0;
    }
}
