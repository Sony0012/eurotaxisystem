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
    protected $description = 'Clean and reset maintenance records, boundaries, expenses, unit deadlines, alerts, and all operational test data to fresh zero state.';

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
            'salaries' => 'Salaries / Payroll Records',
            'driver_behavior' => 'Driver Behavior & Incident Logs',
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

        // Units with maintenance or missing status or shift deadlines
        $maintenanceUnits = 0;
        $missingUnits = 0;
        $unitsWithDeadlines = 0;
        if (Schema::hasTable('units')) {
            $maintenanceUnits = DB::table('units')->where('status', 'maintenance')->count();
            $missingUnits = DB::table('units')->where('status', 'missing')->count();
            $unitsWithDeadlines = DB::table('units')->whereNotNull('shift_deadline_at')->count();
            $this->line("- Units in maintenance status: <comment>{$maintenanceUnits}</comment>");
            $this->line("- Units in missing status: <comment>{$missingUnits}</comment>");
            $this->line("- Units with active shift deadlines: <comment>{$unitsWithDeadlines}</comment>");
        }

        $systemAlertsCount = 0;
        if (Schema::hasTable('system_alerts')) {
            $systemAlertsCount = DB::table('system_alerts')->count();
            $this->line("- System Alerts (`system_alerts`): <comment>{$systemAlertsCount}</comment>");
        }

        if ($isDryRun) {
            $this->info("\n[DRY RUN] No changes were made.");
            return Command::SUCCESS;
        }

        if (!$this->option('force')) {
            if (!$this->confirm('Are you sure you want to completely clean and reset Maintenance, Boundaries, Expenses, and all operational test data to fresh state?', true)) {
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

            // Clean system alerts (especially missing unit alerts)
            if (Schema::hasTable('system_alerts')) {
                $delAlerts = DB::table('system_alerts')->count();
                DB::table('system_alerts')->truncate();
                $this->info("✓ Cleared all {$delAlerts} alert(s) from `system_alerts`");
            }

            // Reset any units stuck in 'maintenance' or 'missing' status back to 'active', and clear legacy shift deadlines
            if (Schema::hasTable('units')) {
                $resetUnits = DB::table('units')->whereIn('status', ['maintenance', 'missing'])->update([
                    'status' => 'active',
                    'updated_at' => now(),
                ]);
                
                $clearedDeadlines = DB::table('units')->update([
                    'shift_deadline_at' => null,
                    'last_swapping_at' => null,
                    'current_turn_driver_id' => null,
                    'updated_at' => now(),
                ]);

                $this->info("✓ Reset {$resetUnits} unit(s) from 'maintenance'/'missing' to 'active'");
                $this->info("✓ Cleared shift deadlines and turnover anchors on {$clearedDeadlines} unit(s) (waiting for 1st boundary)");
            }

            // Clear application and dashboard caches
            Cache::flush();
            $this->info("✓ Flushed application and dashboard caches");

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $this->info("\n🎉 SUCCESS: All operational records and charts have been completely reset back to zero (fresh state)!");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->error("Error occurred while resetting data: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}