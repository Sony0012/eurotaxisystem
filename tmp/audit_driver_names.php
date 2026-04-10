<?php
use App\Models\Driver;
$drivers = Driver::limit(10)->get();
foreach ($drivers as $d) {
    echo $d->id . ": '" . $d->first_name . "' '" . $d->last_name . "'\n";
}
echo "Total Drivers: " . Driver::count() . "\n";
echo "Drivers with non-empty first_name: " . Driver::whereNotNull('first_name')->where('first_name', '!=', '')->count() . "\n";
