<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Unit;
use App\Models\Boundary;
use App\Models\Maintenance;
use App\Models\Expense;
use App\Models\User;
use App\Models\SystemAlert;
use App\Models\FranchiseCase;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get dashboard statistics
        $stats = [];
        $alerts = [];

        // Total units (matches Unit Management default list)
        $stats['active_units'] = Unit::count();

        // Units with ROI achieved (calculated from real boundary data)
        $stats['roi_units'] = DB::table('units as u')
            ->where('u.purchase_cost', '>', 0)
            ->whereExists(function($query) {
                $query->select(DB::raw(1))
                    ->from('boundaries as b')
                    ->whereRaw('b.unit_id = u.id')
                    ->whereIn('b.status', ['paid', 'excess'])
                    ->groupBy('b.unit_id')
                    ->havingRaw('SUM(b.boundary_amount) >= u.purchase_cost');
            })
            ->count();

        // Units under coding
        $stats['coding_units'] = DB::table('units')->whereRaw('LOWER(status) = ?', ['coding'])->count();

        // Units under maintenance
        $stats['maintenance_units'] = DB::table('units')->whereRaw('LOWER(status) = ?', ['maintenance'])->count();

        // Today's boundary collected
        $stats['today_boundary'] = DB::table('boundaries')
            ->whereDate('date', now()->toDateString())
            ->sum('boundary_amount') ?? 0;

        // Today's expenses
        $stats['today_expenses'] = DB::table('expenses')
            ->whereDate('date', now()->toDateString())
            ->sum('amount') ?? 0;

        // Net income today
        $stats['net_income'] = $stats['today_boundary'] - $stats['today_expenses'];

        // Active drivers — drivers table uses driver_status column
        $stats['active_drivers'] = DB::table('drivers as d')
            ->join('users as u', 'd.user_id', '=', 'u.id')
            ->where('u.is_active', true)
            ->count();

        // Average boundary rate for active units
        $stats['avg_boundary'] = DB::table('units')
            ->where('status', 'active')
            ->avg('boundary_rate') ?? 0;

        // Maintenance cost this month — real column: date_started (not maintenance_date)
        $stats['monthly_maintenance'] = DB::table('maintenance')
            ->whereMonth('date_started', now()->month)
            ->whereYear('date_started', now()->year)
            ->where('status', 'completed')
            ->sum('cost') ?? 0;

        // System alerts (unresolved)
        $alerts = DB::table('system_alerts')
            ->where('is_resolved', false)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Revenue trend (dynamic based on period)
        $period = $request->get('period', 30); // Default to 30 days
        $revenue_trend = collect(range($period - 1, 0))->map(function ($daysAgo) use ($period) {
            $date = now()->subDays($daysAgo)->toDateString();
            $boundary = DB::table('boundaries')->whereDate('date', $date)->sum('boundary_amount') ?? 0;
            
            // Format label based on period
            if ($period <= 7) {
                $label = now()->subDays($daysAgo)->format('M j');
            } elseif ($period <= 30) {
                $label = now()->subDays($daysAgo)->format('M j');
            } elseif ($period <= 90) {
                $label = now()->subDays($daysAgo)->format('M j');
            } else {
                $label = now()->subDays($daysAgo)->format('M Y');
            }
            
            return [
                'date' => $label,
                'revenue' => (float) $boundary,
            ];
        })->values()->toArray();

        // Weekly financial trend (last 7 days real data)
        $weekly_data = collect(range(6, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo)->toDateString();
            $boundary = DB::table('boundaries')->whereDate('date', $date)->sum('boundary_amount') ?? 0;
            $expenses = DB::table('expenses')->whereDate('date', $date)->sum('amount') ?? 0;
            return [
                'day'      => now()->subDays($daysAgo)->format('D'),
                'boundary' => (float) $boundary,
                'expenses' => (float) $expenses,
                'net'      => (float) ($boundary - $expenses),
            ];
        })->values()->toArray();

        $unit_status_data = [
            ['status' => 'Active',            'count' => DB::table('units')->whereRaw('LOWER(status) = ?', ['active'])->count()],
            ['status' => 'Under Maintenance', 'count' => DB::table('units')->whereRaw('LOWER(status) = ?', ['maintenance'])->count()],
            ['status' => 'Coding',            'count' => DB::table('units')->whereRaw('LOWER(status) = ?', ['coding'])->count()],
            ['status' => 'Retired',           'count' => DB::table('units')->whereRaw('LOWER(status) = ?', ['retired'])->count()],
        ];

        // Unit performance (top performing units)
        $unit_performance = DB::table('units as u')
            ->leftJoin('boundaries as b', 'u.id', '=', 'b.unit_id')
            ->select('u.unit_number', DB::raw('COALESCE(SUM(b.boundary_amount), 0) as total_boundary'), 'u.boundary_rate')
            ->where('u.status', 'active')
            ->groupBy('u.id', 'u.unit_number', 'u.boundary_rate')
            ->orderByDesc('total_boundary')
            ->limit(10)
            ->get()
            ->map(function($unit) {
                return [
                    'unit' => $unit->unit_number,
                    'performance' => (float) $unit->total_boundary,
                    'target' => (float) $unit->boundary_rate * 30, // Monthly target
                ];
            });

        // Expense breakdown by category
        $expense_breakdown = DB::table('expenses')
            ->select('category', DB::raw('SUM(amount) as total'))
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->groupBy('category')
            ->orderByDesc('total')
            ->get()
            ->map(function($expense) {
                return [
                    'category' => $expense->category,
                    'amount' => (float) $expense->total,
                ];
            });

        return view('dashboard', compact('stats', 'alerts', 'weekly_data', 'unit_status_data', 'revenue_trend', 'unit_performance', 'expense_breakdown'));
    }

    public function getRealTimeData()
    {
        // Get fresh dashboard statistics
        $stats = [];
        $alerts = [];

        // Total units
        $stats['active_units'] = Unit::count();

        // Units with ROI achieved
        $stats['roi_units'] = DB::table('units as u')
            ->where('u.purchase_cost', '>', 0)
            ->whereExists(function($query) {
                $query->select(DB::raw(1))
                    ->from('boundaries as b')
                    ->whereRaw('b.unit_id = u.id')
                    ->whereIn('b.status', ['paid', 'excess'])
                    ->groupBy('b.unit_id')
                    ->havingRaw('SUM(b.boundary_amount) >= u.purchase_cost');
            })
            ->count();

        // Units under coding
        $stats['coding_units'] = DB::table('units')->whereRaw('LOWER(status) = ?', ['coding'])->count();

        // Units under maintenance
        $stats['maintenance_units'] = DB::table('units')->whereRaw('LOWER(status) = ?', ['maintenance'])->count();

        // Today's boundary collected
        $stats['today_boundary'] = DB::table('boundaries')
            ->whereDate('date', now()->toDateString())
            ->sum('boundary_amount') ?? 0;

        // Today's expenses
        $stats['today_expenses'] = DB::table('expenses')
            ->whereDate('date', now()->toDateString())
            ->sum('amount') ?? 0;

        // Net income today
        $stats['net_income'] = $stats['today_boundary'] - $stats['today_expenses'];

        // Active drivers
        $stats['active_drivers'] = DB::table('drivers as d')
            ->join('users as u', 'd.user_id', '=', 'u.id')
            ->where('u.is_active', true)
            ->count();

        // Average boundary rate
        $stats['avg_boundary'] = DB::table('units')
            ->where('status', 'active')
            ->avg('boundary_rate') ?? 0;

        // Maintenance cost this month
        $stats['monthly_maintenance'] = DB::table('maintenance')
            ->whereMonth('date_started', now()->month)
            ->whereYear('date_started', now()->year)
            ->where('status', 'completed')
            ->sum('cost') ?? 0;

        // System alerts
        $alerts = DB::table('system_alerts')
            ->where('is_resolved', false)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(function($alert) {
                return [
                    'message' => $alert->message,
                    'severity' => $alert->severity,
                    'alert_type' => $alert->alert_type
                ];
            });

        // Weekly financial trend
        $weekly_data = collect(range(6, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo)->toDateString();
            $boundary = DB::table('boundaries')->whereDate('date', $date)->sum('boundary_amount') ?? 0;
            $expenses = DB::table('expenses')->whereDate('date', $date)->sum('amount') ?? 0;
            return [
                'day'      => now()->subDays($daysAgo)->format('D'),
                'boundary' => (float) $boundary,
                'expenses' => (float) $expenses,
                'net'      => (float) ($boundary - $expenses),
            ];
        })->values()->toArray();

        $unit_status_data = [
            ['status' => 'Active',            'count' => DB::table('units')->whereRaw('LOWER(status) = ?', ['active'])->count()],
            ['status' => 'Under Maintenance', 'count' => DB::table('units')->whereRaw('LOWER(status) = ?', ['maintenance'])->count()],
            ['status' => 'Coding',            'count' => DB::table('units')->whereRaw('LOWER(status) = ?', ['coding'])->count()],
            ['status' => 'Retired',           'count' => DB::table('units')->whereRaw('LOWER(status) = ?', ['retired'])->count()],
        ];

        // Revenue trend (last 30 days)
        $revenue_trend = collect(range(29, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo)->toDateString();
            $boundary = DB::table('boundaries')->whereDate('date', $date)->sum('boundary_amount') ?? 0;
            return [
                'date' => now()->subDays($daysAgo)->format('M j'),
                'revenue' => (float) $boundary,
            ];
        })->values()->toArray();

        // Unit performance (top performing units)
        $unit_performance = DB::table('units as u')
            ->leftJoin('boundaries as b', 'u.id', '=', 'b.unit_id')
            ->select('u.unit_number', DB::raw('COALESCE(SUM(b.boundary_amount), 0) as total_boundary'), 'u.boundary_rate')
            ->where('u.status', 'active')
            ->groupBy('u.id', 'u.unit_number', 'u.boundary_rate')
            ->orderByDesc('total_boundary')
            ->limit(10)
            ->get()
            ->map(function($unit) {
                return [
                    'unit' => $unit->unit_number,
                    'performance' => (float) $unit->total_boundary,
                    'target' => (float) $unit->boundary_rate * 30, // Monthly target
                ];
            });

        // Expense breakdown by category
        $expense_breakdown = DB::table('expenses')
            ->select('category', DB::raw('SUM(amount) as total'))
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->groupBy('category')
            ->orderByDesc('total')
            ->get()
            ->map(function($expense) {
                return [
                    'category' => $expense->category,
                    'amount' => (float) $expense->total,
                ];
            });

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'alerts' => $alerts,
            'charts' => [
                'weekly_data' => $weekly_data,
                'unit_status_data' => $unit_status_data,
                'revenue_trend' => $revenue_trend,
                'unit_performance' => $unit_performance,
                'expense_breakdown' => $expense_breakdown
            ]
        ]);
    }

    public function getRevenueTrend(Request $request)
    {
        $period = $request->get('period', 30);
        
        $revenue_trend = collect(range($period - 1, 0))->map(function ($daysAgo) use ($period) {
            $date = now()->subDays($daysAgo)->toDateString();
            $boundary = DB::table('boundaries')->whereDate('date', $date)->sum('boundary_amount') ?? 0;
            
            // Format label based on period
            if ($period <= 7) {
                $label = now()->subDays($daysAgo)->format('M j');
            } elseif ($period <= 30) {
                $label = now()->subDays($daysAgo)->format('M j');
            } elseif ($period <= 90) {
                $label = now()->subDays($daysAgo)->format('M j');
            } else {
                $label = now()->subDays($daysAgo)->format('M Y');
            }
            
            return [
                'date' => $label,
                'revenue' => (float) $boundary,
            ];
        })->values()->toArray();

        return response()->json([
            'success' => true,
            'data' => $revenue_trend
        ]);
    }

    public function getUnitsOverview()
    {
        try {
            // Get all units with complete real information
            $units = DB::table('units')
                ->select('id', 'unit_number', 'status', 'boundary_rate', 'purchase_cost', 'plate_number', 'driver_id')
                ->orderBy('unit_number')
                ->get()
                ->map(function($unit) {
                    // Get total boundary for this unit from real data
                    $totalBoundary = DB::table('boundaries')
                        ->where('unit_id', $unit->id)
                        ->sum('boundary_amount') ?? 0;
                    
                    // Get today's boundary for real-time data
                    $todayBoundary = DB::table('boundaries')
                        ->where('unit_id', $unit->id)
                        ->whereDate('date', now()->toDateString())
                        ->sum('boundary_amount') ?? 0;
                    
                    // Get driver information
                    $driverName = 'N/A';
                    if ($unit->driver_id) {
                        $driver = DB::table('drivers as d')
                            ->join('users as u', 'd.user_id', '=', 'u.id')
                            ->where('d.id', $unit->driver_id)
                            ->first();
                        $driverName = $driver ? $driver->name : 'N/A';
                    }
                    
                    // Calculate ROI percentage based on real data
                    $roiPercentage = 0;
                    if ($unit->purchase_cost > 0 && $totalBoundary > 0) {
                        $roiPercentage = min(100, round(($totalBoundary / $unit->purchase_cost) * 100, 2));
                    }
                    
                    // Calculate days to ROI based on real performance
                    $daysToROI = 0;
                    if ($unit->purchase_cost > 0 && $totalBoundary > 0 && $roiPercentage < 100) {
                        // Method 1: Calculate based on recent 30-day average
                        $recent30DaysBoundary = DB::table('boundaries')
                            ->where('unit_id', $unit->id)
                            ->where('boundary_amount', '>', 0)
                            ->whereDate('date', '>=', now()->subDays(30)->toDateString())
                            ->sum('boundary_amount') ?? 0;
                        
                        // Method 2: Calculate based on last 10 days average
                        $last10DaysBoundary = DB::table('boundaries')
                            ->where('unit_id', $unit->id)
                            ->where('boundary_amount', '>', 0)
                            ->whereDate('date', '>=', now()->subDays(10)->toDateString())
                            ->sum('boundary_amount') ?? 0;
                        
                        // Method 3: Calculate based on last 7 days average
                        $last7DaysBoundary = DB::table('boundaries')
                            ->where('unit_id', $unit->id)
                            ->where('boundary_amount', '>', 0)
                            ->whereDate('date', '>=', now()->subDays(7)->toDateString())
                            ->sum('boundary_amount') ?? 0;
                        
                        // Choose the most reliable method
                        $dailyAverage = 0;
                        
                        if ($last7DaysBoundary > 0) {
                            // Use last 7 days if available (most recent)
                            $dailyAverage = $last7DaysBoundary / 7;
                        } elseif ($last10DaysBoundary > 0) {
                            // Use last 10 days
                            $dailyAverage = $last10DaysBoundary / 10;
                        } elseif ($recent30DaysBoundary > 0) {
                            // Use last 30 days
                            $dailyAverage = $recent30DaysBoundary / 30;
                        } else {
                            // Fallback to overall average
                            $activeDays = DB::table('boundaries')
                                ->where('unit_id', $unit->id)
                                ->where('boundary_amount', '>', 0)
                                ->count();
                            if ($activeDays > 0) {
                                $dailyAverage = $totalBoundary / $activeDays;
                            }
                        }
                        
                        // Calculate days to ROI with accuracy improvements
                        if ($dailyAverage > 0) {
                            $remainingAmount = $unit->purchase_cost - $totalBoundary;
                            $daysToROI = ceil($remainingAmount / $dailyAverage);
                            
                            // Cap at maximum 365 days (1 year) for realistic estimation
                            $daysToROI = min($daysToROI, 365);
                            
                            // If ROI is very close (within 5%), show as "Almost there"
                            if ($daysToROI <= 5) {
                                $daysToROI = 0; // Will be handled as "Almost there"
                            }
                        } else {
                            $daysToROI = 999; // No recent activity indicator
                        }
                    }
                    
                    return [
                        'id' => $unit->id,
                        'unit_number' => $unit->unit_number,
                        'plate_number' => $unit->plate_number,
                        'status' => strtolower($unit->status),
                        'boundary_rate' => (float) $unit->boundary_rate,
                        'total_boundary' => (float) $totalBoundary,
                        'today_boundary' => (float) $todayBoundary,
                        'purchase_cost' => (float) $unit->purchase_cost,
                        'driver_name' => $driverName,
                        'driver_id' => $unit->driver_id,
                        'roi_percentage' => $roiPercentage,
                        'roi_achieved' => $roiPercentage >= 100,
                        'days_to_roi' => $daysToROI,
                        'last_activity' => $this->getLastActivity($unit->id),
                        'performance_rating' => $this->getPerformanceRating($roiPercentage)
                    ];
                });

            // Calculate real stats from actual data
            $stats = [
                'total_units' => $units->count(),
                'active_units' => $units->where('status', 'active')->count(),
                'roi_units' => $units->where('roi_achieved', true)->count(),
                'coding_units' => $units->where('status', 'coding')->count(),
                'maintenance_units' => $units->where('status', 'maintenance')->count(),
                'retired_units' => $units->where('status', 'retired')->count(),
                'avg_roi' => $units->avg('roi_percentage') ?: 0,
                'total_investment' => $units->sum('purchase_cost'),
                'total_collected' => $units->sum('total_boundary'),
                'today_collected' => $units->sum('today_boundary')
            ];

            return response()->json([
                'success' => true,
                'units' => $units,
                'stats' => $stats,
                'data_source' => 'real_database',
                'last_updated' => now()->toDateTimeString()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error loading units overview: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading units data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get last activity for a unit
     */
    private function getLastActivity($unitId)
    {
        $lastBoundary = DB::table('boundaries')
            ->where('unit_id', $unitId)
            ->orderBy('date', 'desc')
            ->first();
            
        return $lastBoundary ? $lastBoundary->date : null;
    }

    /**
     * Get performance rating based on ROI
     */
    private function getPerformanceRating($roiPercentage)
    {
        if ($roiPercentage >= 100) return 'excellent';
        if ($roiPercentage >= 75) return 'good';
        if ($roiPercentage >= 50) return 'average';
        return 'growing';
    }
}
