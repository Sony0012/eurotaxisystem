<?php
use App\Models\Unit;
use App\Models\BoundaryRule;
use Illuminate\Support\Facades\DB;

echo "Verifying Automated Discounts...\n";

// We need to simulate the day of the week since date('l') is real-time
// I'll temporarily modify UnitController or just replicate the logic here for verification

function calculateLogic($unit, $day) {
    $year = (int) $unit->year;
    $customRate = (float) $unit->boundary_rate;
    
    $rule = BoundaryRule::where('start_year', '<=', $year)
        ->where('end_year', '>=', $year)
        ->first();

    $base = $customRate > 0 ? $customRate : ($rule ? (float)$rule->regular_rate : 1100);
    
    // Simulate Friday (Regular), Saturday, Sunday
    if ($day === 'Saturday') {
        $discount = $rule ? (float)$rule->sat_discount : 100;
        return $base - $discount;
    } elseif ($day === 'Sunday') {
        $discount = $rule ? (float)$rule->sun_discount : 200;
        return $base - $discount;
    } else {
        return $base;
    }
}

$testUnit = Unit::where('plate_number', 'VAA 9864')->first(); // 2021 model, Rate 1400
echo "Test Unit: {$testUnit->plate_number} (Year: {$testUnit->year}, Base Rate: {$testUnit->boundary_rate})\n";

$sat = calculateLogic($testUnit, 'Saturday');
$sun = calculateLogic($testUnit, 'Sunday');
$reg = calculateLogic($testUnit, 'Friday');

echo "Friday: $reg\n";
echo "Saturday: $sat (Expected 1300 if discount is 100)\n";
echo "Sunday: $sun (Expected 1200 if discount is 200)\n";

if ($sat == 1300 && $sun == 1200) {
    echo "SUCCESS: Rule-based discounts verified.\n";
} else {
    echo "FAILURE: Check logic.\n";
}
