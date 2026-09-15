<?php

namespace App\Console\Commands;

use App\Services\PresenceService;
use Illuminate\Console\Command;

class CleanupPresenceConnections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'presence:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Close stale user presence connections and synchronize online statuses';

    /**
     * Execute the console command.
     */
    public function handle(PresenceService $presenceService): int
    {
        $affected = $presenceService->cleanupStaleConnections();
        $this->info("Successfully cleaned up {$affected} stale presence connection(s).");

        return Command::SUCCESS;
    }
}
