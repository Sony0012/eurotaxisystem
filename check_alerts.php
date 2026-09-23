<?php
echo "\n--- COUNT START ---\n";
echo "Total Unresolved: " . DB::table('system_alerts')->where('is_resolved', false)->count() . "\n";
echo "--- COUNT END ---\n";
