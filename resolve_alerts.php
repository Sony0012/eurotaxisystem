<?php
DB::table('system_alerts')->where('is_resolved', false)->where('type', '!=', 'low_stock')->update(['is_resolved' => true, 'updated_at' => now()]);
echo "\n--- RESOLVE START ---\n";
echo "Total Unresolved Remaining (should be 0 or just low_stock): " . DB::table('system_alerts')->where('is_resolved', false)->count() . "\n";
echo "--- RESOLVE END ---\n";
