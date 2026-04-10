<?php

namespace App\Tmp;

use App\Models\Unit;
use App\Models\BoundaryRule;
use Illuminate\Support\Facades\DB;

class RateApplier {
    public static function run() {
        echo "Applying Boundary Rates to Units...\n";
        
        $units = Unit::all();
        $rules = BoundaryRule::orderBy('start_year', 'asc')->get();
        
        $count = 0;
        foreach ($units as $unit) {
            $year = (int) $unit->year;
            
            $match = $rules->first(function ($rule) use ($year) {
                return $year >= $rule->start_year && $year <= $rule->end_year;
            });
            
            if ($match) {
                $unit->boundary_rate = $match->regular_rate;
                $unit->save();
                $count++;
                echo "Updated Unit: {$unit->plate_number} (Year: {$year}) -> Rate: {$match->regular_rate}\n";
            } else {
                echo "No rule found for Unit: {$unit->plate_number} (Year: {$year})\n";
            }
        }
        
        echo "Finished. Updated {$count} units.\n";
    }
}

RateApplier::run();
