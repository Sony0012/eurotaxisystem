<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\Unit;
use App\Models\Boundary;
use App\Models\Maintenance;
use App\Models\Expense;
use App\Models\User;
use App\Models\SystemAlert;
use App\Models\FranchiseCase;
use App\Models\DriverBehavior;
use App\Traits\CalculatesDriverPerformance;
use App\Services\NotificationService;
use Carbon\Carbon;

class DashboardController extends Controller
{
    use CalculatesDriverPerformance;
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function completeTutorial(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user) {
            $user->tutorial_completed = true;
            $user->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 401);
    }

    public function index(Request $request)
    {
        // AUTO-TRIGGER: Daily Coding Alerts (runs once per day when first staff visits dashboard)
        $cacheKey = 'daily_coding_alerts_sent_' . now()->toDateString();
        if (!Cache::has($cacheKey)) {
            $this->notificationService->dispatchDailyCodingNotifications();
            Cache::put($cacheKey, true, now()->endOfDay());
        }

        // AUTO-TRIGGER: Daily Missed Boundary Charges
        $autoChargeKey = 'daily_missed_boundary_charged_' . now()->toDateString();
        if (!Cache::has($autoChargeKey)) {
            $this->dispatchDailyMissedBoundaryCharge();
            Cache::put($autoChargeKey, true, now()->endOfDay());
        }

        // Get dashboard statistics using centralized method
        $stats = $this->getDashboardStats();
        
        // System alerts (unresolved)
        $alerts = DB::table('system_alerts')
            ->where('is_resolved', false)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Revenue trend (dynamic based on period)
        $period = $request->get('period', 30);
        $revenue_trend = $this->getRevenueTrendData($period);

        // Weekly financial trend (last 7 days real data)
        $weekly_data = $this->getWeeklyFinancialData();

        // Unit performance (top performing units)
        $unit_performance = $this->getUnitPerformanceData();

        // Unit status distribution data
        $unit_status_data = $this->getUnitStatusDistributionData();
        $unit_status_distribution_data = $unit_status_data;

        // Expense breakdown
        $expense_breakdown = $this->getExpenseBreakdownData();

        // Top Drivers
        $top_drivers = $this->getTopDriversData();

        // Initial Maintenance Data (for instant 0ms modal rendering)
        $initial_maintenance = $this->fetchMaintenanceData('all');

        return view('dashboard', compact(
            'stats', 'alerts', 'revenue_trend', 'weekly_data', 
            'unit_status_data', 'unit_status_distribution_data', 
            'unit_performance', 'expense_breakdown', 'top_drivers',
            'initial_maintenance'
        ));
    }

    public function getRealTimeData()
    {
        try {
            // Get dashboard statistics (Skip monitorSystemStatus for AJAX to avoid load and flickering)
            $stats = $this->getDashboardStats(false);
            
            // System alerts
            $alerts = DB::table('system_alerts')
                ->where('is_resolved', false)
                ->orderByDesc('created_at')
                ->limit(10)
                ->get()
                ->map(function($alert) {
                    return [
                        'message' => $alert->message,
                        'severity' => 'medium',
                        'alert_type' => $alert->type ?? 'notice'
                    ];
                });

            // Weekly data
            $weekly_data = $this->getWeeklyFinancialData();

            // Charts data
            $unit_status_data = $this->getUnitStatusDistributionData();
            $revenue_trend = $this->getRevenueTrendData(30);
            $unit_performance = $this->getUnitPerformanceData();
            $expense_breakdown = $this->getExpenseBreakdownData();
            $top_drivers = $this->getTopDriversData();

            return response()->json([
                'success' => true,
                'stats' => $stats,
                'alerts' => $alerts,
                'charts' => [
                    'weekly_data' => $weekly_data,
                    'unit_status_data' => $unit_status_data,
                    'revenue_trend' => $revenue_trend,
                    'unit_performance' => $unit_performance,
                    'expense_breakdown' => $expense_breakdown,
                    'top_drivers' => $top_drivers
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Dashboard Realtime Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getRevenueTrend(Request $request)
    {
        $period = (int) $request->get('period', 30);
        $startDate = now()->subDays($period - 1)->toDateString();
        
        // Use a single query with GROUP BY for the entire period
        $revenueData = DB::table('boundaries')
            ->whereNull('deleted_at')
            ->whereDate('date', '>=', $startDate)
            ->select(DB::raw('DATE(date) as revenue_date'), DB::raw('SUM(actual_boundary) as total_revenue'))
            ->groupBy(DB::raw('DATE(date)'))
            ->orderBy('revenue_date', 'asc')
            ->get()
            ->keyBy('revenue_date');

        $revenue_trend = collect(range($period - 1, 0))->map(function ($daysAgo) use ($period, $revenueData) {
            $carbonDate = now()->subDays($daysAgo);
            $dateString = $carbonDate->toDateString();
            
            $boundary = isset($revenueData[$dateString]) ? (float)$revenueData[$dateString]->total_revenue : 0;
            
            // Format label based on period
            $label = $carbonDate->format('M j');
            if ($period > 30) {
                $label = $carbonDate->format('M Y');
            }
            
            return [
                'date' => $label,
                'revenue' => $boundary,
            ];
        })->values()->toArray();

        return response()->json([
            'success' => true,
            'data' => $revenue_trend,
            'period' => $period,
            'total_period_revenue' => array_sum(array_column($revenue_trend, 'revenue'))
        ]);
    }

    public function getUnitsOverview()
    {
        try {
            $todayDay = now()->format('l');
            $todayDate = now()->toDateString();
            $sub30Days = now()->subDays(30)->toDateString();
            $sub10Days = now()->subDays(10)->toDateString();
            $sub7Days = now()->subDays(7)->toDateString();

            $hasUDeleted = Schema::hasColumn('units', 'deleted_at');
            $hasBDeleted = Schema::hasColumn('boundaries', 'deleted_at');
            $hasMDeleted = Schema::hasColumn('maintenance', 'deleted_at');
            $hasCDeleted = Schema::hasColumn('coding_records', 'deleted_at');

            $bDelClause = $hasBDeleted ? " AND deleted_at IS NULL" : "";
            $mDelClause = $hasMDeleted ? " AND deleted_at IS NULL" : "";
            $cDelClause = $hasCDeleted ? " AND deleted_at IS NULL" : "";

            // 1. Get units with essential joined data and aggregate subqueries to avoid N+1
            $unitsQuery = DB::table('units as u')
                ->leftJoin('drivers as d1', 'u.driver_id', '=', 'd1.id')
                ->leftJoin('drivers as d2', 'u.secondary_driver_id', '=', 'd2.id');

            if ($hasUDeleted) {
                $unitsQuery->whereNull('u.deleted_at');
            }

            $units = $unitsQuery
                ->select([
                    'u.id', 'u.status', 'u.boundary_rate', 'u.purchase_cost', 'u.plate_number', 'u.driver_id', 'u.secondary_driver_id',
                    DB::raw("TRIM(CONCAT(COALESCE(d1.first_name, ''), ' ', COALESCE(d1.last_name, ''))) as driver1_name"),
                    DB::raw("TRIM(CONCAT(COALESCE(d2.first_name, ''), ' ', COALESCE(d2.last_name, ''))) as driver2_name"),
                    // Total Boundary
                    DB::raw("(SELECT SUM(actual_boundary) FROM boundaries WHERE unit_id = u.id{$bDelClause}) as total_boundary"),
                    // Today's Boundary
                    DB::raw("(SELECT SUM(actual_boundary) FROM boundaries WHERE unit_id = u.id{$bDelClause} AND DATE(date) = '$todayDate') as today_boundary"),
                    // Recent Boundary sums for ROI calculation
                    DB::raw("(SELECT SUM(actual_boundary) FROM boundaries WHERE unit_id = u.id{$bDelClause} AND DATE(date) >= '$sub30Days' AND boundary_amount > 0) as boundary_30d"),
                    DB::raw("(SELECT SUM(actual_boundary) FROM boundaries WHERE unit_id = u.id{$bDelClause} AND DATE(date) >= '$sub10Days' AND boundary_amount > 0) as boundary_10d"),
                    DB::raw("(SELECT SUM(actual_boundary) FROM boundaries WHERE unit_id = u.id{$bDelClause} AND DATE(date) >= '$sub7Days' AND boundary_amount > 0) as boundary_7d"),
                    // Active days count
                    DB::raw("(SELECT COUNT(*) FROM boundaries WHERE unit_id = u.id{$bDelClause} AND boundary_amount > 0) as active_days"),
                    // Maintenance Costs
                    DB::raw("(SELECT SUM(cost) FROM maintenance WHERE unit_id = u.id{$mDelClause} AND status != 'cancelled') as total_maintenance_cost"),
                    // Coding Costs
                    DB::raw("(SELECT SUM(cost) FROM coding_records WHERE unit_id = u.id{$cDelClause}) as total_coding_cost"),
                    // Last Activity Date
                    DB::raw("(SELECT MAX(date) FROM boundaries WHERE unit_id = u.id{$bDelClause}) as last_activity_date")
                ])
                ->orderBy('u.plate_number')
                ->get()
                ->map(function($unit) use ($todayDay) {
                    $displayStatus = strtolower($unit->status ?? 'active');
                    
                    // Auto-correct Vacant/Active status based on assigned drivers
                    if (($unit->driver_id || $unit->secondary_driver_id) && $displayStatus === 'vacant') {
                        $displayStatus = 'active';
                    } elseif (!$unit->driver_id && !$unit->secondary_driver_id && $displayStatus === 'active') {
                        $displayStatus = 'vacant';
                    }
                    
                    // Automation: Identify if it should be coding based on plate number
                    $plateCodingDay = $this->getCodingDay($unit->plate_number);
                    $shouldBeCodingToday = ($plateCodingDay === $todayDay);

                    if ($shouldBeCodingToday && $displayStatus !== 'missing') {
                        $displayStatus = 'coding';
                    } elseif ($displayStatus === 'coding' && !$shouldBeCodingToday) {
                        $displayStatus = 'active';
                    }
                    
                    $totalBoundary = (float)($unit->total_boundary ?? 0);
                    $totalCosts = (float)($unit->total_maintenance_cost ?? 0) + (float)($unit->total_coding_cost ?? 0);
                    $netRevenue = $totalBoundary - $totalCosts;
                    
                    $roiPercentage = 0;
                    if ($unit->purchase_cost > 0 && $netRevenue > 0) {
                        $roiPercentage = min(100, round(($netRevenue / $unit->purchase_cost) * 100, 2));
                    }
                    
                    // Driver name logic
                    $driverName = $unit->driver1_name ?: ($unit->driver2_name ?: 'No Driver');
                    if (!$unit->driver_id && !$unit->secondary_driver_id) $driverName = 'No Driver';
                    
                    // Days to ROI calculation logic (optimized)
                    $daysToROI = 0;
                    if ($unit->purchase_cost > 0 && $totalBoundary > 0 && $roiPercentage < 100) {
                        $dailyAverage = 0;
                        if ($unit->boundary_7d > 0) $dailyAverage = $unit->boundary_7d / 7;
                        elseif ($unit->boundary_10d > 0) $dailyAverage = $unit->boundary_10d / 10;
                        elseif ($unit->boundary_30d > 0) $dailyAverage = $unit->boundary_30d / 30;
                        elseif ($unit->active_days > 0) $dailyAverage = $totalBoundary / $unit->active_days;

                        if ($dailyAverage > 0) {
                            $remainingAmount = $unit->purchase_cost - $totalBoundary;
                            $daysToROI = ceil($remainingAmount / $dailyAverage);
                            $daysToROI = min($daysToROI, 365);
                            if ($daysToROI <= 5) $daysToROI = 0; // Almost there
                        } else {
                            $daysToROI = 999;
                        }
                    }
                    
                    return [
                        'id' => $unit->id,
                        'plate_number' => $unit->plate_number,
                        'status' => $displayStatus,
                        'boundary_rate' => (float) ($unit->boundary_rate ?? 0),
                        'total_boundary' => $totalBoundary,
                        'today_boundary' => (float)($unit->today_boundary ?? 0),
                        'purchase_cost' => (float) ($unit->purchase_cost ?? 0),
                        'driver_name' => $driverName,
                        'driver1_name' => $unit->driver1_name,
                        'driver2_name' => $unit->driver2_name,
                        'driver_id' => $unit->driver_id,
                        'secondary_driver_id' => $unit->secondary_driver_id,
                        'roi_percentage' => $roiPercentage,
                        'roi_achieved' => $roiPercentage >= 100,
                        'days_to_roi' => $daysToROI,
                        'last_activity' => $unit->last_activity_date,
                        'performance_rating' => $this->getPerformanceRating($roiPercentage)
                    ];
                });

            // Calculate real stats from actual data
            $stats = [
                'total_units' => $units->count(),
                'vacant_units' => $units->whereNull('driver_id')->whereNull('secondary_driver_id')->count(),
                'active_units' => $units->filter(function($u) { return !is_null($u['driver_id']) || !is_null($u['secondary_driver_id']); })->where('status', '!=', 'missing')->count(),
                'coding_units' => $units->where('status', 'coding')->count(),
                'missing_units' => $units->where('status', 'missing')->count(),
                'roi_units' => $units->where('roi_achieved', true)->count(),
                'avg_roi' => $units->avg('roi_percentage') ?: 0,
                'total_investment' => (float) $units->sum('purchase_cost'),
                'total_collected' => (float) $units->sum('total_boundary'),
                'today_collected' => (float) $units->sum('today_boundary')
            ];

            return response()->json([
                'success' => true,
                'units' => $units,
                'stats' => $stats,
                'data_source' => 'real_database',
                'last_updated' => now()->toDateTimeString()
            ]);
            
        } catch (\Throwable $e) {
            Log::error('Error loading units overview: ' . $e->getMessage());
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

    /**
     * Get daily boundary collections with detailed information
     */
    public function getDailyBoundaryCollections(Request $request)
    {
        try {
            // Get optional date from request, default to today
            $date = $request->get('date', now()->toDateString());

            // Get boundary collections for the specific date with complete information
            $bQuery = DB::table('boundaries as b')
                ->leftJoin('units as u', 'b.unit_id', '=', 'u.id')
                ->leftJoin('drivers as d', 'b.driver_id', '=', 'd.id')
                ->select([
                    'b.id',
                    'b.unit_id',
                    'b.actual_boundary',
                    'b.boundary_amount',
                    'b.date',
                    'b.created_at',
                    'u.plate_number',
                    'd.first_name',
                    'd.last_name',
                    'd.nickname',
                    'd.id as driver_id'
                ])
                ->whereDate('b.date', $date);

            if (Schema::hasColumn('boundaries', 'deleted_at')) {
                $bQuery->whereNull('b.deleted_at');
            }

            $collections = $bQuery
                ->orderBy('b.id', 'desc')
                ->get()
                ->map(function($collection) {
                    $driverName = trim(($collection->first_name ?? '') . ' ' . ($collection->last_name ?? ''));
                    if (empty($driverName)) $driverName = $collection->nickname ?? 'No Driver Assigned';
                    
                    return [
                        'id' => $collection->id,
                        'unit_id' => $collection->unit_id,
                        'plate_number' => $collection->plate_number,
                        'driver_name' => $driverName,
                        'driver_id' => $collection->driver_id,
                        'boundary_amount' => (float) ($collection->actual_boundary ?? 0),
                        'date' => $collection->date,
                        'time' => isset($collection->created_at) ? \Carbon\Carbon::parse($collection->created_at)->format('h:i A') : 'N/A', 
                        'location' => 'Main Office', 
                        'status' => 'verified' 
                    ];
                });

            // Calculate statistics
            $today = now()->toDateString();
            $yesterday = now()->subDay()->toDateString();
            $month = now()->month;
            $year = now()->year;

            $hasBDeleted = Schema::hasColumn('boundaries', 'deleted_at');

            $stats = [
                'total_today' => (int) (DB::table('boundaries')->when($hasBDeleted, fn($q)=>$q->whereNull('deleted_at'))->whereDate('date', $today)->count()),
                'amount_yesterday' => (float) (DB::table('boundaries')->when($hasBDeleted, fn($q)=>$q->whereNull('deleted_at'))->whereDate('date', $yesterday)->sum('actual_boundary') ?? 0),
                'amount_monthly' => (float) (DB::table('boundaries')->when($hasBDeleted, fn($q)=>$q->whereNull('deleted_at'))->whereMonth('date', $month)->whereYear('date', $year)->sum('actual_boundary') ?? 0),
                'total_yearly_amount' => (float) (DB::table('boundaries')->when($hasBDeleted, fn($q)=>$q->whereNull('deleted_at'))->whereYear('date', $year)->sum('actual_boundary') ?? 0),
                'filter_date' => $date
            ];

            return response()->json([
                'success' => true,
                'collections' => $collections,
                'stats' => $stats,
                'data_source' => 'real_database',
                'last_updated' => now()->toDateTimeString()
            ]);
            
        } catch (\Throwable $e) {
            Log::error('Error loading daily boundary collections: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading boundary collections: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get net income details with breakdown
     */
    public function getNetIncomeDetails()
    {
        try {
            $hasBDeleted = Schema::hasColumn('boundaries', 'deleted_at');
            $hasMDeleted = Schema::hasColumn('maintenance', 'deleted_at');
            $hasCDeleted = Schema::hasColumn('coding_records', 'deleted_at');
            $hasEDeleted = Schema::hasColumn('expenses', 'deleted_at');

            // Get income data from boundaries
            $incomeQuery = DB::table('boundaries as b')
                ->leftJoin('units as u', 'b.unit_id', '=', 'u.id')
                ->leftJoin('drivers as d', 'b.driver_id', '=', 'd.id')
                ->leftJoin('users as du', 'd.user_id', '=', 'du.id')
                ->select([
                    'b.id',
                    'b.unit_id',
                    'b.actual_boundary',
                    'b.boundary_amount',
                    'b.date',
                    'u.plate_number',
                    'du.name as driver_name',
                    'd.id as driver_id'
                ]);

            if ($hasBDeleted) {
                $incomeQuery->whereNull('b.deleted_at');
            }

            $incomeData = $incomeQuery
                ->orderBy('b.date', 'desc')
                ->orderBy('b.id', 'desc')
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'type' => 'income',
                        'description' => 'Boundary Collection - ' . ($item->plate_number ?? 'N/A'),
                        'category' => 'Boundary Income',
                        'amount' => (float) ($item->actual_boundary ?? 0),
                        'date' => $item->date,
                        'source' => $item->plate_number ?? 'N/A',
                        'reference' => 'Boundary #' . $item->id,
                        'plate_number' => $item->plate_number,
                        'driver_name' => $item->driver_name
                    ];
                });

            // Initialize expense data as empty collection
            $expenseData = collect();
            $expenseTable = null;

            if (Schema::hasTable('expenses')) {
                try {
                    $expenseTable = 'expenses';
                    $expQuery = DB::table('expenses as oe')
                        ->leftJoin('users as u', 'oe.created_by', '=', 'u.id')
                        ->select([
                            'oe.id',
                            'oe.category as expense_type',
                            'oe.amount',
                            'oe.description',
                            'oe.date',
                            'oe.created_by as user_id',
                            'u.name as user_name'
                        ]);

                    if ($hasEDeleted) {
                        $expQuery->whereNull('oe.deleted_at');
                    }

                    $expenseData = $expQuery
                        ->orderBy('oe.date', 'desc')
                        ->orderBy('oe.id', 'desc')
                        ->get()
                        ->map(function($item) {
                            return [
                                'id' => $item->id,
                                'type' => 'expense',
                                'description' => $item->description ?: $item->expense_type,
                                'category' => $item->expense_type,
                                'amount' => abs((float) ($item->amount ?? 0)),
                                'date' => $item->date,
                                'source' => $item->user_name ?: 'Office / System',
                                'reference' => 'Expense #' . $item->id,
                                'expense_type' => $item->expense_type,
                                'user_name' => $item->user_name ?: 'System Admin'
                            ];
                        });
                } catch (\Throwable $expenseError) {
                    Log::error('Error loading expense data: ' . $expenseError->getMessage());
                    $expenseData = collect();
                }
            }

            // Add Maintenance costs as expenses
            $maintenanceExpenses = collect();
            if (Schema::hasTable('maintenance')) {
                try {
                    $mQuery = DB::table('maintenance as m')
                        ->join('units as u', 'm.unit_id', '=', 'u.id')
                        ->where('m.status', '!=', 'cancelled');
                    
                    if ($hasMDeleted) {
                        $mQuery->whereNull('m.deleted_at');
                    }

                    $maintenanceExpenses = $mQuery
                        ->select('m.*', 'u.plate_number')
                        ->get()
                        ->map(function($item) {
                            return [
                                'id' => $item->id,
                                'type' => 'maintenance',
                                'description' => 'Unit ' . ($item->plate_number ?? 'N/A') . ' - ' . ($item->maintenance_type ?: 'Maintenance'),
                                'category' => 'Maintenance',
                                'amount' => abs((float) ($item->cost ?? 0)),
                                'date' => $item->date_started,
                                'source' => $item->mechanic_name ?: 'Workshop',
                                'reference' => 'MNT-#' . $item->id,
                                'expense_type' => $item->maintenance_type,
                                'user_name' => $item->mechanic_name
                            ];
                        });
                } catch (\Throwable $mErr) {
                    Log::error('Error loading maintenance expenses: ' . $mErr->getMessage());
                }
            }

            // Add Coding costs as expenses
            $codingExpenses = collect();
            if (Schema::hasTable('coding_records')) {
                try {
                    $cQuery = DB::table('coding_records as c')
                        ->join('units as u', 'c.unit_id', '=', 'u.id');

                    if ($hasCDeleted) {
                        $cQuery->whereNull('c.deleted_at');
                    }

                    $codingExpenses = $cQuery
                        ->select('c.*', 'u.plate_number')
                        ->get()
                        ->map(function($item) {
                            return [
                                'id' => $item->id,
                                'type' => 'coding',
                                'description' => 'Unit ' . ($item->plate_number ?? 'N/A') . ' - Coding Fee',
                                'category' => 'Coding',
                                'amount' => abs((float) ($item->cost ?? 0)),
                                'date' => $item->date,
                                'source' => 'System',
                                'reference' => 'COD-#' . $item->id,
                                'expense_type' => 'Coding Fee',
                                'user_name' => 'Automated'
                            ];
                        });
                } catch (\Throwable $cErr) {
                    Log::error('Error loading coding expenses: ' . $cErr->getMessage());
                }
            }

            // Add Salaries as expenses
            $salaryExpenses = collect();
            if (Schema::hasTable('salaries')) {
                try {
                    $salaryExpenses = DB::table('salaries as s')
                        ->leftJoin('users as u', function($join) {
                            $join->on('s.employee_id', '=', 'u.id')->where('s.source', '=', 'user');
                        })
                        ->leftJoin('staff as st', function($join) {
                            $join->on('s.employee_id', '=', 'st.id')->where('s.source', '=', 'staff');
                        })
                        ->select('s.*', DB::raw('COALESCE(u.name, st.name) as employee_name'))
                        ->get()
                        ->map(function($item) {
                            return [
                                'id' => $item->id,
                                'type' => 'salary',
                                'description' => 'Salary Payment - ' . ($item->employee_name ?? 'Staff'),
                                'category' => 'Payroll',
                                'amount' => abs((float) ($item->total_salary ?? 0)),
                                'date' => $item->pay_date ?? $item->created_at,
                                'source' => 'Finance',
                                'reference' => 'SAL-#' . $item->id,
                                'expense_type' => 'Salary',
                                'user_name' => 'System'
                            ];
                        });
                } catch (\Throwable $sErr) {
                    Log::error('Error loading salary expenses: ' . $sErr->getMessage());
                }
            }

            // Combine all financial data
            $allData = $incomeData->concat($expenseData)
                ->concat($maintenanceExpenses)
                ->concat($codingExpenses)
                ->concat($salaryExpenses)
                ->sortByDesc('date')
                ->values();

            // Calculate statistics
            $totalIncome = (float) $incomeData->sum('amount');
            $totalExpenses = (float) ($expenseData->sum('amount') + 
                            $maintenanceExpenses->sum('amount') + 
                            $codingExpenses->sum('amount') + 
                            $salaryExpenses->sum('amount'));
            $netIncome = $totalIncome - $totalExpenses;
            $profitMargin = $totalIncome > 0 ? (($netIncome / $totalIncome) * 100) : 0;

            $stats = [
                'total_income' => $totalIncome,
                'total_expenses' => $totalExpenses,
                'net_income' => $netIncome,
                'profit_margin' => $profitMargin,
                'income_count' => $incomeData->count(),
                'expense_count' => $expenseData->count(),
                'total_transactions' => $allData->count(),
                'expense_table_used' => $expenseTable,
                'debug_info' => [
                    'income_data_count' => $incomeData->count(),
                    'expense_data_count' => $expenseData->count(),
                    'expense_table_found' => $expenseTable ? 'yes' : 'no'
                ]
            ];

            return response()->json([
                'success' => true,
                'income_data' => $allData,
                'stats' => $stats,
                'data_source' => 'real_database',
                'last_updated' => now()->toDateTimeString()
            ]);
            
        } catch (\Throwable $e) {
            Log::error('Error loading net income details: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error loading income data: ' . $e->getMessage(),
                'error_type' => get_class($e),
                'debug_info' => [
                    'line' => $e->getLine(),
                    'file' => basename($e->getFile())
                ]
            ], 500);
        }
    }

    /**
     * Fetch maintenance records and stats for both view preloading and API response
     */
    public function fetchMaintenanceData($filter = 'all')
    {
        $unitsQuery = DB::table('maintenance as m')
            ->join('units as u', 'm.unit_id', '=', 'u.id');

        if ($filter === 'complete' || $filter === 'completed') {
            // Query historical completed maintenance records
            $unitsQuery->whereIn(DB::raw('LOWER(m.status)'), ['completed', 'complete']);
        } else {
            // Base logic: All active maintenance records (Not completed/cancelled)
            $unitsQuery->whereNotIn(DB::raw('LOWER(m.status)'), ['completed', 'complete', 'cancelled']);

            // Filter by type if specified
            if ($filter !== 'all') {
                if ($filter === 'preventive') {
                    $unitsQuery->where('m.maintenance_type', 'LIKE', '%preventive%');
                } elseif ($filter === 'corrective') {
                    $unitsQuery->where('m.maintenance_type', 'LIKE', '%corrective%');
                } elseif ($filter === 'emergency') {
                    $unitsQuery->where('m.maintenance_type', 'LIKE', '%emergency%');
                }
            }
        }

        if (Schema::hasColumn('maintenance', 'deleted_at')) {
            $unitsQuery->whereNull('m.deleted_at');
        }
        if (Schema::hasColumn('units', 'deleted_at')) {
            $unitsQuery->whereNull('u.deleted_at');
        }

        $unitsQuery->leftJoin('drivers as d', 'm.driver_id', '=', 'd.id');

        $select = [
            'u.id',
            'u.plate_number',
            'u.status',
            'u.purchase_cost',
            'u.boundary_rate',
            'u.created_at',
            DB::raw("CONCAT(COALESCE(d.first_name,''), ' ', COALESCE(d.last_name,'')) as driver_name"),
            'm.id as maintenance_id',
            'm.maintenance_type',
            'm.description',
            'm.date_started as start_date',
            'm.date_completed as end_date',
            'm.status as maintenance_status',
            'm.cost as maintenance_cost',
            'm.mechanic_name',
        ];

        $maintenanceUnits = $unitsQuery
            ->select($select)
            ->when($filter === 'complete' || $filter === 'completed', function ($q) {
                $q->orderBy('m.date_completed', 'desc');
            }, function ($q) {
                $q->orderBy('m.date_started', 'desc');
            })
            ->get()
            ->map(function($unit) {
                $startDate = data_get($unit, 'start_date');
                $endDate = data_get($unit, 'end_date');
                return [
                    'id' => $unit->id,
                    'plate_number' => $unit->plate_number,
                    'status' => $unit->status,
                    'driver_name' => $unit->driver_name,
                    'maintenance_type' => $unit->maintenance_type ?: 'Maintenance',
                    'description' => $unit->description ?: 'No description available',
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'estimated_completion' => $endDate,
                    'maintenance_status' => $unit->maintenance_status ?: 'Ongoing',
                    'maintenance_cost' => (float) ($unit->maintenance_cost ?? 0),
                    'maintenance_id' => $unit->maintenance_id,
                    'mechanic_name' => $unit->mechanic_name ?: 'Unknown',
                    'purchase_cost' => (float) ($unit->purchase_cost ?? 0),
                    'boundary_rate' => (float) ($unit->boundary_rate ?? 0)
                ];
            });

        // Calculate Global Overview Stats based on MAINTENANCE records, not unit status
        $statsQuery = DB::table('maintenance')
            ->join('units', 'maintenance.unit_id', '=', 'units.id')
            ->whereNotIn(DB::raw('LOWER(maintenance.status)'), ['completed', 'complete', 'cancelled']);

        if (Schema::hasColumn('maintenance', 'deleted_at')) {
            $statsQuery->whereNull('maintenance.deleted_at');
        }
        if (Schema::hasColumn('units', 'deleted_at')) {
            $statsQuery->whereNull('units.deleted_at');
        }

        $mStats = $statsQuery->select([
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN LOWER(maintenance.maintenance_type) LIKE "%preventive%" THEN 1 ELSE 0 END) as preventive'),
            DB::raw('SUM(CASE WHEN LOWER(maintenance.maintenance_type) LIKE "%corrective%" THEN 1 ELSE 0 END) as corrective'),
            DB::raw('SUM(CASE WHEN LOWER(maintenance.maintenance_type) LIKE "%emergency%" THEN 1 ELSE 0 END) as emergency'),
        ])->first();

        $completedQuery = DB::table('maintenance')
            ->join('units', 'maintenance.unit_id', '=', 'units.id')
            ->whereIn(DB::raw('LOWER(maintenance.status)'), ['completed', 'complete']);

        if (Schema::hasColumn('maintenance', 'deleted_at')) {
            $completedQuery->whereNull('maintenance.deleted_at');
        }
        if (Schema::hasColumn('units', 'deleted_at')) {
            $completedQuery->whereNull('units.deleted_at');
        }

        $completedCount = $completedQuery->count();

        $avgMaintenanceDays = 0;
        $unitsWithValidDates = $maintenanceUnits->filter(function($unit) {
            return !empty($unit['start_date']) && !empty($unit['end_date']) && strtotime($unit['start_date']) && strtotime($unit['end_date']);
        });
        if ($unitsWithValidDates->count() > 0) {
            $avgMaintenanceDays = $unitsWithValidDates->map(function($unit) {
                try {
                    return abs(Carbon::parse($unit['end_date'])->diffInDays(Carbon::parse($unit['start_date'])));
                } catch (\Throwable $t) {
                    return 0;
                }
            })->avg() ?? 0;
        }

        $stats = [
            'total_maintenance' => (int) ($mStats->total ?? 0),
            'preventive_maintenance' => (int) ($mStats->preventive ?? 0),
            'corrective_maintenance' => (int) ($mStats->corrective ?? 0),
            'emergency_maintenance' => (int) ($mStats->emergency ?? 0),
            'completed_total' => (int) ($completedCount ?? 0),
            'avg_maintenance_days' => round((float) $avgMaintenanceDays, 1),
            'total_maintenance_cost' => (float) $maintenanceUnits->sum('maintenance_cost')
        ];

        return [
            'units' => $maintenanceUnits,
            'stats' => $stats,
            'filter_applied' => $filter,
            'data_source' => 'real_database',
            'last_updated' => now()->toDateTimeString()
        ];
    }

    /**
     * Get units currently under maintenance or historical maintenance records via API.
     */
    public function getMaintenanceUnits(Request $request)
    {
        try {
            $filter = $request->query('filter', 'all');
            $data = $this->fetchMaintenanceData($filter);
            return response()->json(array_merge(['success' => true], $data));
        } catch (\Throwable $e) {
            Log::error('Error loading maintenance units: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading maintenance units: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get active drivers with detailed information
     */
    public function getActiveDrivers()
    {
        try {
            $select = [
                'd.id',
                'd.user_id',
                DB::raw("CONCAT(COALESCE(d.first_name,''), ' ', COALESCE(d.last_name,'')) as name"),
                DB::raw('NULL as email'),
                DB::raw('(SELECT COUNT(id) FROM units WHERE (driver_id = d.id OR secondary_driver_id = d.id)' . (Schema::hasColumn('units', 'deleted_at') ? ' AND deleted_at IS NULL' : '') . ') as assigned_units'),
                DB::raw('(SELECT COALESCE(SUM(actual_boundary), 0) FROM boundaries WHERE driver_id = d.id' . (Schema::hasColumn('boundaries', 'deleted_at') ? ' AND deleted_at IS NULL' : '') . ') as total_boundary'),
                DB::raw('(SELECT COALESCE(AVG(actual_boundary), 0) FROM boundaries WHERE driver_id = d.id' . (Schema::hasColumn('boundaries', 'deleted_at') ? ' AND deleted_at IS NULL' : '') . ') as avg_boundary'),
                DB::raw('(SELECT GROUP_CONCAT(DISTINCT plate_number) FROM units WHERE (driver_id = d.id OR secondary_driver_id = d.id)' . (Schema::hasColumn('units', 'deleted_at') ? ' AND deleted_at IS NULL' : '') . ') as plate_numbers'),
                'd.hire_date',
                'd.license_number',
                'd.contact_number as phone',
                'd.address'
            ];

            $query = DB::table('drivers as d')
                ->select($select)
                ->whereIn('d.driver_status', ['available', 'assigned', 'active']);

            if (Schema::hasColumn('drivers', 'deleted_at')) {
                $query->whereNull('d.deleted_at');
            }

            $activeDrivers = $query
                ->orderBy('d.first_name', 'asc')
                ->get()
                ->map(function($driver) {
                    $avgBoundary = (float) ($driver->avg_boundary ?? 0);
                    
                    // Base performance rating
                    $performanceRating = 'average';
                    if ($avgBoundary >= 2000) $performanceRating = 'excellent';
                    elseif ($avgBoundary >= 1500) $performanceRating = 'good';
                    elseif ($avgBoundary >= 1000) $performanceRating = 'average';
                    else $performanceRating = 'needs_improvement';

                    $isTopPerformer = ($performanceRating === 'excellent');
                    
                    return [
                        'id' => $driver->id,
                        'name' => $driver->name,
                        'email' => $driver->email,
                        'license_number' => $driver->license_number,
                        'phone' => $driver->phone,
                        'address' => $driver->address,
                        'hire_date' => $driver->hire_date,
                        'assigned_units' => (int) ($driver->assigned_units ?? 0),
                        'plate_numbers' => $driver->plate_numbers ?? null,
                        'total_boundary' => (float) ($driver->total_boundary ?? 0),
                        'avg_boundary' => $avgBoundary,
                        'performance_rating' => $performanceRating,
                        'is_top_performer' => $isTopPerformer
                    ];
                });

            // Calculate statistics
            $totalDrivers = $activeDrivers->count();
            $vacantDrivers = $activeDrivers->where('assigned_units', 0)->count();
            $activeWithUnits = $activeDrivers->where('assigned_units', '>', 0)->count();
            $topPerformersCount = $activeDrivers->where('is_top_performer', true)->count();

            $stats = [
                'total_drivers' => $totalDrivers,
                'vacant_drivers' => $vacantDrivers,
                'active_with_units' => $activeWithUnits,
                'top_performers' => $topPerformersCount,
                'total_boundary_collected' => (float) $activeDrivers->sum('total_boundary')
            ];

            return response()->json([
                'success' => true,
                'drivers' => $activeDrivers,
                'stats' => $stats,
                'data_source' => 'real_database',
                'last_updated' => now()->toDateTimeString()
            ]);
            
        } catch (\Throwable $e) {
            Log::error('Error loading active drivers: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading active drivers: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get coding units with detailed information
     */
    public function getCodingUnits()
    {
        try {
            $unitsQuery = DB::table('units as u');
            if (Schema::hasColumn('units', 'deleted_at')) {
                $unitsQuery->whereNull('u.deleted_at');
            }
            $today = now()->format('l');

            $unitsQuery->leftJoin('drivers as d1', 'u.driver_id', '=', 'd1.id');
            $unitsQuery->leftJoin('drivers as d2', 'u.secondary_driver_id', '=', 'd2.id');

            $latestC = DB::table('coding_records')
                ->select('unit_id', DB::raw('MAX(id) as latest_id'));
            
            if (Schema::hasColumn('coding_records', 'deleted_at')) {
                $latestC->whereNull('deleted_at');
            }
            $latestC->groupBy('unit_id');

            $unitsQuery->leftJoinSub($latestC, 'latest_c', function($join) {
                $join->on('u.id', '=', 'latest_c.unit_id');
            })->leftJoin('coding_records as c', 'latest_c.latest_id', '=', 'c.id');

            $select = [
                'u.id',
                'u.plate_number',
                'u.status',
                'u.purchase_cost',
                'u.boundary_rate',
                'u.created_at',
                DB::raw("TRIM(CONCAT(COALESCE(d1.first_name, ''), ' ', COALESCE(d1.last_name, ''))) as driver1_name"),
                DB::raw("TRIM(CONCAT(COALESCE(d2.first_name, ''), ' ', COALESCE(d2.last_name, ''))) as driver2_name"),
                'c.id as coding_id',
                DB::raw("'Coding' as coding_type"),
                'c.description',
                'c.date as start_date',
                'c.date as end_date',
                'c.status as coding_status',
                'c.cost as coding_cost',
            ];

            $allUnits = $unitsQuery->select($select)->get();
            
            $codingUnits = $allUnits->map(function($unit) {
                $startDate = data_get($unit, 'start_date');
                $endDate = data_get($unit, 'end_date');
                
                // Determine coding day based on plate ending (LTO rules)
                $codingDay = $this->getCodingDay($unit->plate_number);

                return [
                    'id' => $unit->id,
                    'plate_number' => $unit->plate_number,
                    'status' => $unit->status,
                    'driver1_name' => $unit->driver1_name,
                    'driver2_name' => $unit->driver2_name,
                    'coding_type' => $unit->coding_type ?: 'Coding',
                    'coding_day' => $codingDay,
                    'description' => $unit->description ?: 'No description available',
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'estimated_completion' => $endDate,
                    'coding_status' => $unit->coding_status ?: 'Ongoing',
                    'coding_cost' => (float) ($unit->coding_cost ?? 0),
                    'purchase_cost' => (float) ($unit->purchase_cost ?? 0),
                    'boundary_rate' => (float) ($unit->boundary_rate ?? 0)
                ];
            });

            // Calculate statistics
            $totalCoding = $codingUnits->count();
            $completedCoding = $codingUnits->where('coding_status', 'completed')->count();
            $pendingCoding = $codingUnits->where('coding_status', 'pending')->count();
            
            $avgCodingDays = 0;
            $codingWithValidDates = $codingUnits->filter(function($unit) {
                return !empty($unit['start_date']) && !empty($unit['end_date']) && strtotime($unit['start_date']) && strtotime($unit['end_date']);
            });
            if ($codingWithValidDates->count() > 0) {
                $avgCodingDays = $codingWithValidDates->map(function($unit) {
                    try {
                        return abs(Carbon::parse($unit['end_date'])->diffInDays(Carbon::parse($unit['start_date'])));
                    } catch (\Throwable $t) {
                        return 0;
                    }
                })->avg() ?? 0;
            }

            $stats = [
                'total_coding' => $totalCoding,
                'completed_coding' => $completedCoding,
                'pending_coding' => $pendingCoding,
                'avg_coding_days' => round((float) $avgCodingDays, 1),
                'total_coding_cost' => (float) $codingUnits->sum('coding_cost')
            ];

            return response()->json([
                'success' => true,
                'units' => $codingUnits,
                'stats' => $stats,
                'data_source' => 'real_database',
                'last_updated' => now()->toDateTimeString()
            ]);
            
        } catch (\Throwable $e) {
            Log::error('Error loading coding units: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading coding units: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getCodingDay($plateNumber)
    {
        if (empty($plateNumber)) return 'Unknown';
        $lastDigit = @substr(preg_replace('/[^0-9]/', '', $plateNumber), -1);
        if ($lastDigit === false || $lastDigit === '') return 'Unknown';
        
        if ($lastDigit == 1 || $lastDigit == 2) return 'Monday';
        if ($lastDigit == 3 || $lastDigit == 4) return 'Tuesday';
        if ($lastDigit == 5 || $lastDigit == 6) return 'Wednesday';
        if ($lastDigit == 7 || $lastDigit == 8) return 'Thursday';
        if ($lastDigit == 9 || $lastDigit == 0) return 'Friday';
        
        return 'Unknown';
    }
    /**
     * Centralized Dashboard Statistics
     */
    private function getDashboardStats($runMonitor = true)
    {
        // Run automated system monitoring only when requested and rate-limited (every 5 minutes)
        if ($runMonitor) {
            $lastMonitor = session('last_monitor_run');
            if (!$lastMonitor || now()->diffInMinutes(Carbon::parse($lastMonitor)) >= 5) {
                $this->monitorSystemStatus();
                session(['last_monitor_run' => now()->toDateTimeString()]);
            }
        }

        $today = now()->timezone('Asia/Manila')->toDateString();
        $todayDay = now()->timezone('Asia/Manila')->format('l');

        // Cache the entire web dashboard statistics for 60 seconds to prevent database resource/connection exhaustion on shared hosting.
        return Cache::remember('web_dashboard_stats', 60, function() use ($today, $todayDay) {
            $stats = [];
            $hasUDeleted = Schema::hasColumn('units', 'deleted_at');
            $hasBDeleted = Schema::hasColumn('boundaries', 'deleted_at');
            $hasMDeleted = Schema::hasColumn('maintenance', 'deleted_at');
            $hasDDeleted = Schema::hasColumn('drivers', 'deleted_at');
            $hasEDeleted = Schema::hasTable('expenses') && Schema::hasColumn('expenses', 'deleted_at');

            // 1. Total Units
            $uQ = DB::table('units');
            if ($hasUDeleted) $uQ->whereNull('deleted_at');
            $stats['active_units'] = $uQ->count();

            // 2. ROI Achieved
            $roiQ = DB::table('units as u')->where('u.purchase_cost', '>', 0);
            if ($hasUDeleted) $roiQ->whereNull('u.deleted_at');
            $stats['roi_units'] = $roiQ->whereExists(function($query) use ($hasBDeleted) {
                    $bSub = $query->select(DB::raw(1))
                        ->from('boundaries as b');
                    if ($hasBDeleted) $bSub->whereNull('b.deleted_at');
                    $bSub->whereRaw('b.unit_id = u.id')
                        ->whereIn('b.status', ['paid', 'excess', 'shortage'])
                        ->groupBy('b.unit_id')
                        ->havingRaw('SUM(b.actual_boundary) >= u.purchase_cost');
                })
                ->count();

            // 3. Coding Units Today
            $allUnitsQ = DB::table('units');
            if ($hasUDeleted) $allUnitsQ->whereNull('deleted_at');
            $allUnits = $allUnitsQ->get();
            $stats['coding_units'] = $allUnits->filter(function($unit) use ($todayDay) {
                $codingDay = $unit->coding_day ?: $this->getCodingDay($unit->plate_number);
                return $codingDay === $todayDay;
            })->count();

            // 4. Maintenance Units (Primary Source: Maintenance Table)
            if (Schema::hasTable('maintenance')) {
                $mStatsQ = DB::table('maintenance')
                    ->join('units', 'maintenance.unit_id', '=', 'units.id');
                if ($hasMDeleted) $mStatsQ->whereNull('maintenance.deleted_at');
                if ($hasUDeleted) $mStatsQ->whereNull('units.deleted_at');
                $stats['maintenance_units'] = $mStatsQ->whereNotIn(DB::raw('LOWER(maintenance.status)'), ['complete', 'completed', 'cancelled'])->count();
            } else {
                $stats['maintenance_units'] = 0;
            }

            // 5. Financials (Today)
            $bTodayQ = DB::table('boundaries')->whereDate('date', $today);
            if ($hasBDeleted) $bTodayQ->whereNull('deleted_at');
            $stats['today_boundary'] = (float) ($bTodayQ->sum('actual_boundary') ?? 0);

            $genExToday = 0;
            if (Schema::hasTable('expenses')) {
                $eQ = DB::table('expenses')->whereDate('date', $today);
                if ($hasEDeleted) $eQ->whereNull('deleted_at');
                $genExToday = (float) ($eQ->sum('amount') ?? 0);
            }

            $salExToday = 0;
            if (Schema::hasTable('salaries')) {
                $salExToday = (float) (DB::table('salaries')->whereDate('pay_date', $today)->sum('total_salary') ?? 0);
            }

            $mntExToday = 0;
            if (Schema::hasTable('maintenance')) {
                $mTodayQ = DB::table('maintenance')->whereDate('date_started', $today)->where('status', '!=', 'cancelled');
                if ($hasMDeleted) $mTodayQ->whereNull('deleted_at');
                $mntExToday = (float) ($mTodayQ->sum('cost') ?? 0);
            }
            
            $stats['total_expenses_today'] = abs($genExToday) + abs($salExToday) + abs($mntExToday);
            $stats['net_income'] = $stats['today_boundary'] - $stats['total_expenses_today'];

            // 6. Financials (This Month)
            $month = now()->timezone('Asia/Manila')->month;
            $year = now()->timezone('Asia/Manila')->year;

            $bMonthQ = DB::table('boundaries')->whereMonth('date', $month)->whereYear('date', $year);
            if ($hasBDeleted) $bMonthQ->whereNull('deleted_at');
            $stats['month_boundary'] = (float) ($bMonthQ->sum('actual_boundary') ?? 0);

            $genExMonth = 0;
            if (Schema::hasTable('expenses')) {
                $eMonthQ = DB::table('expenses')->whereMonth('date', $month)->whereYear('date', $year);
                if ($hasEDeleted) $eMonthQ->whereNull('deleted_at');
                $genExMonth = (float) ($eMonthQ->sum('amount') ?? 0);
            }

            $salExMonth = 0;
            if (Schema::hasTable('salaries')) {
                $salExMonth = (float) (DB::table('salaries')->whereMonth('pay_date', $month)->whereYear('pay_date', $year)->sum('total_salary') ?? 0);
            }

            $mntExMonth = 0;
            if (Schema::hasTable('maintenance')) {
                $mMonthQ = DB::table('maintenance')->whereMonth('date_started', $month)->whereYear('date_started', $year)->where('status', '!=', 'cancelled');
                if ($hasMDeleted) $mMonthQ->whereNull('deleted_at');
                $mntExMonth = (float) ($mMonthQ->sum('cost') ?? 0);
            }
            
            $stats['total_expenses_month'] = abs($genExMonth) + abs($salExMonth) + abs($mntExMonth);
            $stats['net_income_month'] = $stats['month_boundary'] - $stats['total_expenses_month'];

            $stats['roi_achieved'] = $stats['roi_units']; // Harmonize for JS

            // Daily Target (Active Units Rate)
            $targetQ = DB::table('units')->whereRaw('LOWER(status) = ?', ['active']);
            if ($hasUDeleted) $targetQ->whereNull('deleted_at');
            $stats['daily_target'] = (float) ($targetQ->sum('boundary_rate') ?? 0);
            if ($stats['daily_target'] <= 0) $stats['daily_target'] = 2500;

            // Active Drivers
            $driversQ = DB::table('drivers');
            if ($hasDDeleted) $driversQ->whereNull('deleted_at');
            $stats['active_drivers'] = $driversQ->count();

            // Missing/Stolen Units
            $missingQ = DB::table('units')->where('status', 'missing');
            if ($hasUDeleted) $missingQ->whereNull('deleted_at');
            $stats['missing_units'] = $missingQ->count();

            // Average Boundary
            $avgBQ = DB::table('units')->where('status', 'active');
            if ($hasUDeleted) $avgBQ->whereNull('deleted_at');
            $stats['avg_boundary'] = (float) ($avgBQ->avg('boundary_rate') ?? 0);

            return $stats;
        });
    }

    /**
     * Automated System Monitoring & Maintenance
     */
    private function monitorSystemStatus()
    {
        $today = now()->timezone('Asia/Manila')->toDateString();
        $todayDay = now()->timezone('Asia/Manila')->format('l');

        // 1. Coding Notice
        $allFleetForCoding = DB::table('units')->whereNull('deleted_at')->get();
        $codingUnitsCount = $allFleetForCoding->filter(function($unit) use ($todayDay) {
            $codingDay = $unit->coding_day ?: $this->getCodingDay($unit->plate_number);
            return $codingDay === $todayDay;
        })->count();

        if ($codingUnitsCount > 0) {
            $alertExists = DB::table('system_alerts')
                ->where('type', 'coding_notice')
                ->whereDate('created_at', now()->toDateString())
                ->exists();
            
            if (!$alertExists) {
                DB::table('system_alerts')->insert([
                    'type' => 'coding_notice',
                    'title' => "Today's Unit Coding",
                    'message' => "There are {$codingUnitsCount} units on coding today ({$todayDay}).",
                    'is_resolved' => false,
                    'created_at' => now()->startOfDay()->addHour(),
                    'updated_at' => now()->startOfDay()->addHour()
                ]);
            }
        }

        // 2. Auto-resolve missing unit alerts
        $activeMissingAlerts = DB::table('system_alerts')
            ->where('type', 'missing_unit')
            ->where('is_resolved', false)
            ->get();

        foreach ($activeMissingAlerts as $ama) {
            $plateStr = str_replace("🚨 Missing Unit: ", "", $ama->title);
            $u = DB::table('units')->where('plate_number', $plateStr)->whereNull('deleted_at')->first();
            
            if (!$u || strtolower($u->status) === 'maintenance' || !$u->shift_deadline_at || Carbon::parse($u->shift_deadline_at)->diffInHours(now(), false) < 24) {
                DB::table('system_alerts')->where('id', $ama->id)->update(['is_resolved' => true, 'updated_at' => now()]);
            }
        }

        // 3. Auto-generate Missing Unit Notifications
        $missingUnits = DB::table('units')
            ->leftJoin('drivers', 'units.current_turn_driver_id', '=', 'drivers.id')
            ->whereNull('units.deleted_at')
            ->whereRaw('LOWER(units.status) NOT IN (?, ?, ?)', ['maintenance', 'surveillance', 'retired'])
            ->whereNotNull('units.shift_deadline_at')
            ->where('units.shift_deadline_at', '<', now()->subHours(24))
            ->where(function($q) {
                $q->whereNotNull('units.driver_id')->orWhereNotNull('units.secondary_driver_id');
            })
            ->select('units.id', 'units.plate_number', 'drivers.first_name', 'drivers.last_name', 'units.shift_deadline_at')
            ->get();

        foreach ($missingUnits as $unit) {
            $diffHours = now()->diffInHours(Carbon::parse($unit->shift_deadline_at));
            $diffDays = floor($diffHours / 24);
            $driverName = $unit->first_name ? trim($unit->first_name . ' ' . $unit->last_name) : 'Unknown Driver';
            
            $alertTitle = "🚨 Missing Unit: {$unit->plate_number}";
            $existingAlert = DB::table('system_alerts')->where('type', 'missing_unit')->where('title', $alertTitle)->where(function($q) {
                $q->where('is_resolved', false)
                  ->orWhereDate('created_at', today());
            })->first();
            $msg = "Unit {$unit->plate_number} has not remitted a boundary for {$diffDays} day(s). The last driver on record is {$driverName}.";

            if (!$existingAlert) {
                DB::table('system_alerts')->insert([
                    'type' => 'missing_unit', 'title' => $alertTitle, 'message' => $msg, 'is_resolved' => false, 'created_at' => now(), 'updated_at' => now()
                ]);
            } else {
                DB::table('system_alerts')->where('id', $existingAlert->id)->update(['message' => $msg, 'updated_at' => now()]);
            }

            // 4. Auto-Flagdown (48 Hours)
            if ($diffHours >= 48) {
                $suspectId = DB::table('units')->where('id', $unit->id)->value('current_turn_driver_id');
                if ($suspectId) {
                    $deadline = Carbon::parse($unit->shift_deadline_at);
                    $existingViolation = DB::table('driver_behavior')
                        ->where('driver_id', $suspectId)->where('unit_id', $unit->id)
                        ->where('incident_type', 'missing_unit_overdue')->where('incident_date', $deadline->toDateString())
                        ->exists();

                    if (!$existingViolation) {
                        DB::table('driver_behavior')->insert([
                            'unit_id' => $unit->id, 'driver_id' => $suspectId, 'incident_type' => 'missing_unit_overdue', 'severity' => 'high',
                            'description' => "Auto-logged [Flagdown]: Unit {$unit->plate_number} is overdue for >48 hours.",
                            'latitude' => 0, 'longitude' => 0, 'video_url' => '', 'timestamp' => now(), 'incident_date' => $deadline->toDateString(), 'created_at' => now(),
                        ]);
                    }
                }
            }
        }
    }

    private function getWeeklyFinancialData()
    {
        return collect(range(6, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo)->toDateString();
            $boundary = DB::table('boundaries')->whereNull('deleted_at')->whereDate('date', $date)->sum('actual_boundary') ?? 0;
            $expenses = abs((float)(DB::table('expenses')->whereNull('deleted_at')->whereDate('date', $date)->sum('amount') ?? 0));
            return [
                'day'      => now()->subDays($daysAgo)->format('D'),
                'boundary' => (float) $boundary,
                'expenses' => (float) $expenses,
                'net'      => (float) ($boundary - $expenses),
            ];
        })->values()->toArray();
    }

    private function getRevenueTrendData($period)
    {
        return collect(range($period - 1, 0))->map(function ($daysAgo) {
            $label = now()->subDays($daysAgo)->format('M j');
            $date = now()->subDays($daysAgo)->toDateString();
            $boundary = DB::table('boundaries')->whereNull('deleted_at')->whereDate('date', $date)->sum('actual_boundary') ?? 0;
            return [
                'date' => $label,
                'revenue' => (float) $boundary,
            ];
        })->values()->toArray();
    }

    private function getUnitStatusDistributionData()
    {
        $allUnits = DB::table('units')->whereNull('deleted_at')->get();
        $todayDay = now()->timezone('Asia/Manila')->format('l');

        $codingCount = $allUnits->filter(function($unit) use ($todayDay) {
            $codingDay = $unit->coding_day ?: $this->getCodingDay($unit->plate_number);
            return $codingDay === $todayDay;
        })->count();

        $maintenanceCount = DB::table('maintenance')
            ->whereNull('deleted_at')
            ->whereNotIn(DB::raw('LOWER(status)'), ['complete', 'completed', 'cancelled'])
            ->count();

        $missingCount = $allUnits->filter(fn($u) => strtolower($u->status) === 'missing')->count();
        $retiredCount = $allUnits->filter(fn($u) => strtolower($u->status) === 'retired')->count();
        $totalCount = $allUnits->count();
        $activeCount = max(0, $totalCount - $codingCount - $maintenanceCount - $retiredCount - $missingCount);

        return [
            ['status' => 'Active',            'count' => $activeCount],
            ['status' => 'Under Maintenance', 'count' => $maintenanceCount],
            ['status' => 'Coding',            'count' => $codingCount],
            ['status' => 'Missing / Stolen',  'count' => $missingCount],
            ['status' => 'Retired',           'count' => $retiredCount],
        ];
    }

    private function getUnitPerformanceData()
    {
        $thirtyDaysAgo = now()->subDays(30)->toDateString();

        return DB::table('units as u')
            ->whereNull('u.deleted_at')
            ->leftJoin('boundaries as b', function($join) use ($thirtyDaysAgo) {
                $join->on('u.id', '=', 'b.unit_id')
                     ->whereNull('b.deleted_at')
                     ->where('b.date', '>=', $thirtyDaysAgo);
            })
            ->select('u.plate_number', DB::raw('COALESCE(SUM(b.actual_boundary), 0) as total_boundary'), 'u.boundary_rate')
            ->where('u.status', 'active')
            ->groupBy('u.id', 'u.plate_number', 'u.boundary_rate')
            ->orderByDesc('total_boundary')
            ->limit(10)
            ->get()
            ->map(function($unit) {
                return [
                    'unit' => $unit->plate_number,
                    'performance' => (float) $unit->total_boundary,
                    'target' => (float) $unit->boundary_rate * 30,
                ];
            });
    }

    private function getExpenseBreakdownData()
    {
        $month = now()->month;
        $year = now()->year;

        // 1. General Expenses from 'expenses' table
        $genExpenses = DB::table('expenses')
            ->whereNull('deleted_at')
            ->select('category', DB::raw('SUM(amount) as total'))
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->groupBy('category')
            ->get();

        // 2. Maintenance Costs
        $mntTotal = DB::table('maintenance')
            ->whereNull('deleted_at')
            ->where('status', '!=', 'cancelled')
            ->whereMonth('date_started', $month)
            ->whereYear('date_started', $year)
            ->sum('cost') ?? 0;

        // 3. Salaries / Payroll
        $salTotal = DB::table('salaries')
            ->whereMonth('pay_date', $month)
            ->whereYear('pay_date', $year)
            ->sum('total_salary') ?? 0;

        $breakdown = $genExpenses->map(fn($e) => ['category' => $e->category, 'amount' => (float) $e->total])->toArray();
        
        if ($mntTotal > 0) {
            $breakdown[] = ['category' => 'Maintenance', 'amount' => (float) $mntTotal];
        }
        if ($salTotal > 0) {
            $breakdown[] = ['category' => 'Payroll/Salaries', 'amount' => (float) $salTotal];
        }

        $data = collect($breakdown)->sortByDesc('amount')->values();

        if ($data->isEmpty() || $data->every(fn($d) => $d['amount'] == 0)) {
            return collect([]);
        }
        return $data;
    }

    private function getTopDriversData()
    {
        $data = DB::table('drivers as d')
            ->whereNull('d.deleted_at')
            ->leftJoin('boundaries as b', function($join) {
                $join->on('d.id', '=', 'b.driver_id')->whereNull('b.deleted_at');
            })
            ->leftJoin('driver_behavior as db', 'd.id', '=', 'db.driver_id')
            ->select(
                DB::raw("CONCAT(COALESCE(d.first_name,''), ' ', COALESCE(d.last_name,'')) as full_name"),
                DB::raw('COUNT(CASE WHEN b.status IN ("paid", "excess", "shortage") THEN 1 END) as good_days'),
                DB::raw('SUM(b.actual_boundary) as total_boundary'),
                DB::raw('COUNT(CASE WHEN ' . $this->getViolationQuerySnippet() . ' THEN 1 END) as violation_count')
            )
            ->whereIn('d.driver_status', ['available', 'assigned'])
            ->groupBy('d.id', 'd.first_name', 'd.last_name')
            ->having('violation_count', '=', 0)
            ->orderByDesc('good_days')
            ->orderByDesc('total_boundary')
            ->limit(5)
            ->get()
            ->map(fn($d) => ['name' => $d->full_name, 'score' => (int) $d->good_days, 'total' => (float) $d->total_boundary]);

        if ($data->isEmpty() || $data->every(fn($d) => $d['score'] == 0)) {
            return collect([]);
        }
        return $data;
    }

    private function dispatchDailyMissedBoundaryCharge()
    {
        try {
            $units = DB::table('units')
                ->whereNull('deleted_at')
                ->whereNotNull('shift_deadline_at')
                ->whereNotIn('status', ['retired', 'maintenance'])
                ->get();

            $now = Carbon::now();

            foreach ($units as $unit) {
                $deadline = Carbon::parse($unit->shift_deadline_at);
                if ($deadline->isPast()) {
                    $diffHours = $deadline->diffInHours($now);
                    $diffDays = floor($diffHours / 24);
                    
                    if ($diffDays >= 1) {
                        $targetRate = $unit->boundary_rate;
                        $driverId = $unit->current_turn_driver_id ?: $unit->driver_id;
                        
                        if ($targetRate > 0 && $driverId) {
                            for ($i = 1; $i <= $diffDays; $i++) {
                                $missedDate = $deadline->copy()->addDays($i)->toDateString();
                                
                                $exists = DB::table('driver_behavior')
                                    ->where('driver_id', $driverId)
                                    ->where('incident_type', 'Missed Boundary')
                                    ->where('incident_date', $missedDate)
                                    ->exists();
                                    
                                if (!$exists) {
                                    DB::table('driver_behavior')->insert([
                                        'unit_id' => $unit->id,
                                        'driver_id' => $driverId,
                                        'incident_type' => 'Missed Boundary',
                                        'severity' => 'high',
                                        'description' => "Auto-logged [Missed Boundary]: Unit not returned on $missedDate.",
                                        'incident_date' => $missedDate,
                                        'timestamp' => Carbon::parse($missedDate),
                                        'total_charge_to_driver' => $targetRate,
                                        'remaining_balance' => $targetRate,
                                        'charge_status' => 'pending',
                                        'created_at' => now(),
                                        'updated_at' => now(),
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Error in dispatchDailyMissedBoundaryCharge: ' . $e->getMessage());
        }
    }
}
