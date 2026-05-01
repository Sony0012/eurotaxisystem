<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Unit;

class DashboardController extends Controller
{
    /**
     * Get summary statistics for the mobile dashboard.
     */
    public function index()
    {
        $today = now()->timezone('Asia/Manila')->toDateString();
        $stats = [];
        
        // Total units (All non-deleted)
        $stats['active_units'] = DB::table('units')->whereNull('deleted_at')->count();

        // ROI Units
        $stats['roi_units'] = DB::table('units as u')
            ->whereNull('u.deleted_at')
            ->whereExists(function($query) {
                $query->select(DB::raw(1))
                    ->from('boundaries as b')
                    ->whereNull('b.deleted_at')
                    ->whereRaw('b.unit_id = u.id')
                    ->whereIn('b.status', ['paid', 'excess', 'shortage'])
                    ->groupBy('b.unit_id')
                    ->havingRaw('SUM(b.actual_boundary) >= u.purchase_cost');
            })
            ->count();
        $stats['roi_achieved'] = $stats['roi_units'];

        // Coding Units Today
        $todayDay = now()->timezone('Asia/Manila')->format('l');
        $allUnits = DB::table('units')->whereNull('deleted_at')->get();
        $stats['coding_units'] = $allUnits->filter(function($unit) {
            $todayDay = now()->timezone('Asia/Manila')->format('l');
            $plate = $unit->plate_number;
            $lastDigit = substr($plate, -1);
            $codingDays = ['1' => 'Monday', '2' => 'Monday', '3' => 'Tuesday', '4' => 'Tuesday', '5' => 'Wednesday', '6' => 'Wednesday', '7' => 'Thursday', '8' => 'Thursday', '9' => 'Friday', '0' => 'Friday'];
            return ($codingDays[$lastDigit] ?? '') === $todayDay;
        })->count();

        // Maintenance Units
        $stats['maintenance_units'] = DB::table('maintenance')
            ->join('units', 'maintenance.unit_id', '=', 'units.id')
            ->whereNull('maintenance.deleted_at')
            ->whereNull('units.deleted_at')
            ->whereNotIn(DB::raw('LOWER(maintenance.status)'), ['complete', 'completed', 'cancelled'])
            ->count();

        // Financials
        $stats['today_boundary'] = DB::table('boundaries')
            ->whereNull('deleted_at')
            ->whereDate('date', $today)
            ->sum('actual_boundary') ?? 0;

        $genEx = DB::table('expenses')->whereNull('deleted_at')->whereDate('date', $today)->sum('amount') ?? 0;
        $salEx = DB::table('salaries')->whereDate('pay_date', $today)->sum('total_salary') ?? 0;
        $mntEx = DB::table('maintenance')->whereNull('deleted_at')->whereDate('date_started', $today)->where('status', '!=', 'cancelled')->sum('cost') ?? 0;
        
        $stats['total_expenses_today'] = $genEx + $salEx + $mntEx;
        $stats['today_expenses'] = $stats['total_expenses_today'];
        $stats['net_income'] = $stats['today_boundary'] - $stats['total_expenses_today'];

        // Active drivers
        $stats['active_drivers'] = DB::table('drivers')->whereNull('deleted_at')->count();

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'data' => $stats,
        ]);
    }
}
