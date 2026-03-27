<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $date_from = $request->input('date_from', date('Y-m-01'));
        $date_to = $request->input('date_to', date('Y-m-d'));

        // Get monthly revenue data (last 6 months)
        $monthlyRevenueData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = date('M', strtotime("-$i months"));
            $startDate = date('Y-m-01', strtotime("-$i months"));
            $endDate = date('Y-m-t', strtotime("-$i months"));
            
            // Get boundary collections
            $boundary = DB::table('boundaries')
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('boundary_amount') ?? 0;
            
            // Get expenses
            $expenses = DB::table('expenses')
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('amount') ?? 0;
            
            $net = $boundary - $expenses;
            
            $monthlyRevenueData[] = [
                'month' => $month,
                'boundary' => $boundary,
                'expenses' => $expenses,
                'net' => $net
            ];
        }

        // Get unit idle analysis
        $unitIdleAnalysis = DB::table('units as u')
            ->leftJoin('maintenance as m', 'u.id', '=', 'm.unit_id')
            ->selectRaw('
                u.unit_number,
                COUNT(m.id) as breakdown_count,
                SUM(CASE WHEN m.status = "completed" THEN DATEDIFF(m.date_completed, m.date_started) ELSE 0 END) as total_maintenance_days
            ')
            ->where('m.date_started', '>=', DB::raw('DATE_SUB(CURDATE(), INTERVAL 30 DAY)'))
            ->groupBy('u.id', 'u.unit_number')
            ->get();

        // Get driver performance
        $driverPerformance = DB::table('boundaries as b')
            ->join('users as u', 'b.driver_id', '=', 'u.id')
            ->selectRaw('
                u.full_name,
                COUNT(b.id) as days_worked,
                SUM(b.boundary_amount) as total_collected,
                AVG(b.boundary_amount) as avg_daily,
                SUM(b.excess) - SUM(b.shortage) as net_excess
            ')
            ->whereBetween('b.date', [$date_from, $date_to])
            ->groupBy('u.id', 'u.full_name')
            ->orderByDesc('avg_daily')
            ->limit(10)
            ->get();

        // Get expense trends
        $expenseTrends = DB::table('expenses')
            ->selectRaw('
                DATE_FORMAT(date, "%Y-%m") as month,
                SUM(amount) as total,
                COUNT(*) as count
            ')
            ->whereBetween('date', [$date_from, $date_to])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Get maintenance costs by type
        $maintenanceCosts = DB::table('maintenance')
            ->selectRaw('
                maintenance_type,
                SUM(cost) as total_cost,
                COUNT(*) as count,
                AVG(cost) as avg_cost
            ')
            ->whereBetween('date_started', [$date_from, $date_to])
            ->groupBy('maintenance_type')
            ->orderByDesc('total_cost')
            ->get();

        // Calculate total boundary and expenses
        $total_boundary = DB::table('boundaries')->count();
        $total_expenses = DB::table('expenses')
            ->whereBetween('date', [$date_from, $date_to])
            ->sum('amount') ?? 0;
        
        // Calculate net income and active drivers
        $net_income = 0; // Placeholder - would need revenue data to calculate
        $active_drivers = DB::table('users')
            ->where('role', 'driver')
            ->count();
        
        // Get top performing units
        $top_units = DB::table('units')
            ->leftJoin('users as d', 'units.driver_id', '=', 'd.id')
            ->select('units.*', 'd.full_name as driver_name')
            ->selectRaw('0 as total_collected, 0 as days_operated') // Placeholder values
            ->orderBy('units.unit_number')
            ->limit(5)
            ->get();
        
        // Get daily trend data
        $daily_trend = DB::table('expenses')
            ->selectRaw('DATE(date) as date, SUM(amount) as total')
            ->whereBetween('date', [$date_from, $date_to])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Get expense by category
        $expense_by_category = DB::table('expenses')
            ->selectRaw('category, SUM(amount) as total, COUNT(*) as count')
            ->whereBetween('date', [$date_from, $date_to])
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        return view('analytics.index', compact(
            'monthlyRevenueData',
            'unitIdleAnalysis',
            'driverPerformance',
            'expenseTrends',
            'maintenanceCosts',
            'total_boundary',
            'total_expenses',
            'net_income',
            'active_drivers',
            'top_units',
            'daily_trend',
            'expense_by_category',
            'date_from',
            'date_to'
        ));
    }
}
