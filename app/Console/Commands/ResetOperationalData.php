<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;

class ResetOperationalData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eurotaxi:reset-operational-data {--dry-run : Only show current counts without deleting} {--force : Force deletion without confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean and reset maintenance records, boundaries, expenses, and related operational data to fresh status.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');

        $this->info("=== EURO TAXI OPERATIONAL DATA RESET ===");

        $tablesToClean = [
            'maintenance' => 'Maintenance Records',
            'maintenance_parts' => 'Maintenance Parts Details',
            'rescue_requests' => 'Rescue Requests',
            'boundaries' => 'Boundary Collection Records',
            'expenses' => 'Expense Records',
        ];

        $counts = [];

        foreach ($tablesToClean as $table => $label) {
            if (Schema::hasTable($table)) {
                $count = DB::table($table)->count();
                $counts[$table] = $count;
                $this->line("- {$label} (`{$table}`): <comment>{$count}</comment> records");
            } else {
                $this->warn("- {$label} (`{$table}`): Table not found in database");
            }
        }

        // Check behavior records related to boundaries / maintenance
        $behaviorCount = 0;
        if (Schema::hasTable('driver_behavior')) {
            $behaviorCount = DB::table('driver_behavior')
                ->whereIn('incident_type', ['Short Boundary', 'Vehicle Damage', 'Late Remittance'])
                ->count();
            $this->line("- Boundary & Damage Behavior Logs (`driver_behavior`): <comment>{$behaviorCount}</comment> records");
        }

        // Units with maintenance status
        $maintenanceUnits = 0;
        if (Schema::hasTable('units')) {
            $maintenanceUnits = DB::table('units')->where('status', 'maintenance')->count();
            $this->line("- Units in maintenance status: <comment>{$maintenanceUnits}</comment> units");
        }

        if ($isDryRun) {
            $this->info("\n[DRY RUN] No changes were made.");
            return Command::SUCCESS;
        }

        if (!$this->option('force')) {
            if (!$this->confirm('Are you sure you want to completely clean and reset Maintenance, Boundaries, and Expenses to fresh state?', true)) {
                $this->warn("Operation cancelled.");
                return Command::FAILURE;
            }
        }

        $this->info("\nProceeding with cleaning operational records...");

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            foreach ($tablesToClean as $table => $label) {
                if (Schema::hasTable($table)) {
                    $deleted = DB::table($table)->count();
                    DB::table($table)->truncate();
                    $this->info("✓ Cleared all {$deleted} record(s) from `{$table}` ({$label})");
                }
            }

            // Also clean driver behavior entries that were generated purely for short boundaries, damages, or late remittance
            if (Schema::hasTable('driver_behavior')) {
                $deletedBehavior = DB::table('driver_behavior')
                    ->whereIn('incident_type', ['Short Boundary', 'Vehicle Damage', 'Late Remittance'])
                    ->delete();
                $this->info("✓ Cleared {$deletedBehavior} related operational record(s) from `driver_behavior` (Short Boundary / Vehicle Damage / Late Remittance)");
            }

            // Reset any units stuck in 'maintenance' status back to 'active'
            if (Schema::hasTable('units')) {
                $resetUnits = DB::table('units')->where('status', 'maintenance')->update([
                    'status' => 'active',
                    'updated_at' => now(),
                ]);
                $this->info("✓ Reset {$resetUnits} unit(s) from 'maintenance' to 'active'");
            }

            // Clear application and dashboard caches
            Cache::flush();
            $this->info("✓ Flushed application and dashboard caches");

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $this->info("\n🎉 SUCCESS: All maintenance records, boundary records, expenses, and related operational data have been completely cleaned and reset to fresh state!");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->error("Error occurred while resetting data: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}