<?php
use App\Models\User;
use App\Models\Driver;
use Illuminate\Support\Facades\DB;

$drivers = Driver::all();
$updated = 0;
$skipped = 0;

foreach ($drivers as $d) {
    if ($d->user_id) {
        $user = User::withTrashed()->find($d->user_id);
        if ($user) {
            $d->first_name = $user->first_name;
            $d->last_name = $user->last_name;
            // Also copy other useful data if needed (e.g. nickname)
            $d->save();
            $updated++;
        } else {
            $skipped++;
        }
    } else {
        $skipped++;
    }
}

echo "Migration Result:\n";
echo "Updated: $updated drivers\n";
echo "Skipped: $skipped drivers\n";

// Check a few records
foreach (Driver::limit(5)->get() as $d) {
    echo "ID " . $d->id . ": " . $d->first_name . " " . $d->last_name . "\n";
}
