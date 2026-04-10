<?php

namespace App\Tmp;

use App\Models\BoundaryRule;
use Illuminate\Support\Facades\DB;

class RuleSeeder {
    public static function run() {
        echo "Seeding Boundary Rules...\n";
        
        $rules = [
            [
                'name' => 'Legacy Models (2014 & Below)',
                'start_year' => 2000,
                'end_year' => 2014,
                'regular_rate' => 1100.00,
                'sat_discount' => 100.00,
                'sun_discount' => 200.00,
                'coding_rate' => 550.00,
                'coding_is_fixed' => false,
            ],
            [
                'name' => 'Standard Models (2015-2017)',
                'start_year' => 2015,
                'end_year' => 2017,
                'regular_rate' => 1200.00,
                'sat_discount' => 100.00,
                'sun_discount' => 200.00,
                'coding_rate' => 600.00,
                'coding_is_fixed' => false,
            ],
            [
                'name' => 'Modern Models (2018-2020)',
                'start_year' => 2018,
                'end_year' => 2020,
                'regular_rate' => 1300.00,
                'sat_discount' => 100.00,
                'sun_discount' => 200.00,
                'coding_rate' => 650.00,
                'coding_is_fixed' => false,
            ],
            [
                'name' => 'Premium Models (2021-2023)',
                'start_year' => 2021,
                'end_year' => 2025,
                'regular_rate' => 1400.00,
                'sat_discount' => 100.00,
                'sun_discount' => 200.00,
                'coding_rate' => 700.00,
                'coding_is_fixed' => false,
            ],
        ];

        foreach ($rules as $rule) {
            BoundaryRule::updateOrCreate(
                ['name' => $rule['name']],
                $rule
            );
            echo "Upserted Bracket: {$rule['name']}\n";
        }

        echo "Seeding Complete.\n";
    }
}

RuleSeeder::run();
