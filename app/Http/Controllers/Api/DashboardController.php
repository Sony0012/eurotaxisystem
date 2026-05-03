<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today         = now()->timezone('Asia/Manila')->toDateString();
        $startOfMonth  = now()->timezone('Asia/Manila')->startOfMonth()->toDateString();
        $todayDay      = now()->timezone('Asia/Manila')->format('l');

        // ── STATS ──────────────────────────────────────────────────────────────

        $stats['active_units'] = DB::table('units')->whereNull('deleted_at')->count();

        $stats['roi_units'] = DB::table('units as u')
            ->whereNull('u.deleted_at')
            ->whereExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('boundaries as b')
                  ->whereNull('b.deleted_at')
                  ->whereRaw('b.unit_id = u.id')
                  ->whereIn('b.status', ['paid', 'excess', 'shortage'])
                  ->groupBy('b.unit_id')
                  ->havingRaw('SUM(b.actual_boundary) >= u.purchase_cost');
            })->count();
        $stats['roi_achieved'] = $stats['roi_units'];

        // Boundary stats (Matching Web)
        $yesterday = now()->timezone('Asia/Manila')->subDay()->toDateString();
        $year      = now()->timezone('Asia/Manila')->year;
        $month     = now()->timezone('Asia/Manila')->month;

        $stats['today_boundary'] = (float)(DB::table('boundaries')->whereNull('deleted_at')->whereDate('date', $today)->sum('actual_boundary') ?? 0);
        $stats['yesterday_boundary'] = (float)(DB::table('boundaries')->whereNull('deleted_at')->whereDate('date', $yesterday)->sum('actual_boundary') ?? 0);
        $stats['month_boundary'] = (float)(DB::table('boundaries')->whereNull('deleted_at')->whereMonth('date', $month)->whereYear('date', $year)->sum('actual_boundary') ?? 0);
        $stats['year_boundary'] = (float)(DB::table('boundaries')->whereNull('deleted_at')->whereYear('date', $year)->sum('actual_boundary') ?? 0);

        // Detailed boundaries for today (for modal)
        $boundaryList = DB::table('boundaries as b')
            ->join('units as u', 'b.unit_id', '=', 'u.id')
            ->leftJoin('drivers as d', 'b.driver_id', '=', 'd.id')
            ->select(
                'b.id', 'b.actual_boundary', 'b.status', 'b.date',
                'u.plate_number',
                DB::raw("CONCAT(COALESCE(d.first_name,''), ' ', COALESCE(d.last_name,'')) as driver_name")
            )
            ->whereNull('b.deleted_at')
            ->whereDate('b.date', $today)
            ->orderByDesc('b.created_at')
            ->get();

        // Expenses
        $genEx  = (float)(DB::table('expenses')->whereNull('deleted_at')->whereDate('date', $today)->sum('amount') ?? 0);
        $salEx  = (float)(DB::table('salaries')->whereDate('pay_date', $today)->sum('total_salary') ?? 0);
        $mntEx  = (float)(DB::table('maintenance')->whereNull('deleted_at')->whereDate('date_started', $today)->where('status', '!=', 'cancelled')->sum('cost') ?? 0);
        $stats['total_expenses_today'] = $genEx + $salEx + $mntEx;
        $stats['today_expenses']       = $stats['total_expenses_today'];
        $stats['expense_general']      = $genEx;
        $stats['expense_salary']       = $salEx;
        $stats['expense_maintenance']  = $mntEx;

        // Month expenses (Matching Web: whereMonth and whereYear)
        $mGenEx = (float)(DB::table('expenses')->whereNull('deleted_at')->whereMonth('date', $month)->whereYear('date', $year)->sum('amount') ?? 0);
        $mSalEx = (float)(DB::table('salaries')->whereMonth('pay_date', $month)->whereYear('pay_date', $year)->sum('total_salary') ?? 0);
        $mMntEx = (float)(DB::table('maintenance')->whereNull('deleted_at')->whereMonth('date_started', $month)->whereYear('date_started', $year)->where('status', '!=', 'cancelled')->sum('cost') ?? 0);
        $stats['total_expenses_month'] = $mGenEx + $mSalEx + $mMntEx;

        // 3. Expense Breakdown (Detailed Categories from Web)
        $genExpenses = DB::table('expenses')
            ->whereNull('deleted_at')
            ->select('category', DB::raw('SUM(amount) as total'))
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->groupBy('category')
            ->get();

        $expenseBreakdown = $genExpenses->map(fn($e) => [
            'name' => $e->category ?: 'General',
            'value' => (float)$e->total
        ])->toArray();

        // Add Salaries & Maintenance
        $expenseBreakdown[] = ['name' => 'Salaries', 'value' => (float)$mSalEx];
        $expenseBreakdown[] = ['name' => 'Maintenance', 'value' => (float)$mMntEx];

        // Filter out zero values to keep chart clean
        $expenseBreakdown = array_values(array_filter($expenseBreakdown, fn($e) => $e['value'] > 0));

        // Net income
        $stats['net_income']       = $stats['today_boundary'] - $stats['total_expenses_today'];
        $stats['net_income_month'] = $stats['month_boundary'] - $stats['total_expenses_month'];

        // Maintenance units
        $stats['maintenance_units'] = DB::table('maintenance')
            ->join('units', 'maintenance.unit_id', '=', 'units.id')
            ->whereNull('maintenance.deleted_at')->whereNull('units.deleted_at')
            ->whereNotIn(DB::raw('LOWER(maintenance.status)'), ['complete', 'completed', 'cancelled'])
            ->count();

        // Active drivers
        $stats['active_drivers'] = DB::table('drivers')->whereNull('deleted_at')->count();

        // Coding units today
        $allUnits = DB::table('units')->whereNull('deleted_at')->get(['plate_number']);
        $codingDays = ['1'=>'Monday','2'=>'Monday','3'=>'Tuesday','4'=>'Tuesday','5'=>'Wednesday','6'=>'Wednesday','7'=>'Thursday','8'=>'Thursday','9'=>'Friday','0'=>'Friday'];
        $stats['coding_units'] = $allUnits->filter(fn($u) => ($codingDays[substr($u->plate_number, -1)] ?? '') === $todayDay)->count();

        // ── CHART DATA ─────────────────────────────────────────────────────────

        // 1. Revenue Trend (dynamic period)
        $days = (int)($request->input('days', 7));
        if (!in_array($days, [7, 30, 90, 365])) $days = 7;

        $revenueTrend = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $d    = now()->timezone('Asia/Manila')->subDays($i)->toDateString();
            $rev  = (float)(DB::table('boundaries')->whereNull('deleted_at')->whereDate('date', $d)->sum('actual_boundary') ?? 0);
            $gx   = (float)(DB::table('expenses')->whereNull('deleted_at')->whereDate('date', $d)->sum('amount') ?? 0);

            $label = ($days <= 30)
                ? now()->timezone('Asia/Manila')->subDays($i)->format('M d')
                : now()->timezone('Asia/Manila')->subDays($i)->format('M d y');

            // Matching Web: Trend only shows boundaries vs general expenses
            $revenueTrend[] = ['date' => $label, 'revenue' => $rev, 'expenses' => $gx, 'netIncome' => $rev - $gx];
        }

        // 4. UNIT PERFORMANCE (Copying Web Logic Exactly)
        $unitPerformance = DB::table('units as u')
            ->whereNull('u.deleted_at')
            ->leftJoin('boundaries as b', function($join) {
                $join->on('u.id', '=', 'b.unit_id')->whereNull('b.deleted_at');
            })
            ->select('u.plate_number', DB::raw('COALESCE(SUM(b.actual_boundary), 0) as total_boundary'), 'u.boundary_rate')
            ->where('u.status', 'active')
            ->groupBy('u.id', 'u.plate_number', 'u.boundary_rate')
            ->orderByDesc('total_boundary')
            ->limit(10)
            ->get()
            ->map(fn($u) => [
                'plate' => $u->plate_number,
                'actual' => (float)$u->total_boundary,
                'target' => (float)$u->boundary_rate * 30
            ])->toArray();

        // 5. TOP DRIVERS (Copying Web Logic Exactly)
        $topDriversData = DB::table('drivers as d')
            ->whereNull('d.deleted_at')
            ->leftJoin('boundaries as b', function($join) {
                $join->on('d.id', '=', 'b.driver_id')->whereNull('b.deleted_at');
            })
            ->select(
                DB::raw("CONCAT(COALESCE(d.first_name,''), ' ', COALESCE(d.last_name,'')) as name"),
                DB::raw('COUNT(CASE WHEN b.status IN ("paid", "excess", "shortage") THEN 1 END) as good_days'),
                DB::raw('SUM(b.actual_boundary) as total')
            )
            ->whereIn('d.driver_status', ['available', 'assigned'])
            ->groupBy('d.id', 'd.first_name', 'd.last_name')
            ->orderByDesc('good_days')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $topDrivers = $topDriversData->map(fn($d) => [
            'name' => $d->name,
            'total' => (float)$d->total,
            'score' => (int)$d->good_days
        ])->toArray();


        // 4. Weekly Financial Overview (Matching Web Exactly: Boundaries vs Expenses)
        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $d   = now()->timezone('Asia/Manila')->subDays($i)->toDateString();
            $rev = (float)(DB::table('boundaries')->whereNull('deleted_at')->whereDate('date', $d)->sum('actual_boundary') ?? 0);
            $gx  = (float)(DB::table('expenses')->whereNull('deleted_at')->whereDate('date', $d)->sum('amount') ?? 0);
            $weeklyData[] = [
                'day' => now()->timezone('Asia/Manila')->subDays($i)->format('D'),
                'boundary' => $rev,
                'expenses' => $gx,
                'net' => $rev - $gx
            ];
        }

        // 5. Unit Status Distribution (Matching Web Legend Exactly)
        $unitStatusDist = [
            ['name' => 'Active',             'value' => (int)DB::table('units')->whereNull('deleted_at')->where('status', 'active')->count()],
            ['name' => 'Under Maintenance',  'value' => (int)DB::table('units')->whereNull('deleted_at')->where('status', 'maintenance')->count()],
            ['name' => 'Coding',             'value' => (int)DB::table('units')->whereNull('deleted_at')->where('status', 'coding')->count()],
            ['name' => 'Missing / Stolen',    'value' => (int)DB::table('units')->whereNull('deleted_at')->where('status', 'missing')->count()],
            ['name' => 'Retired',            'value' => (int)DB::table('units')->whereNull('deleted_at')->where('status', 'retired')->count()],
        ];

        // ── MODAL DATA ─────────────────────────────────────────────────────────

        // Maintenance details
        $maintenanceList = DB::table('maintenance')
            ->join('units', 'maintenance.unit_id', '=', 'units.id')
            ->leftJoin('drivers', 'units.driver_id', '=', 'drivers.id')
            ->select(
                'maintenance.id', 'maintenance.maintenance_type as type', 'maintenance.status',
                'maintenance.cost', 'maintenance.description',
                'maintenance.date_started', 'maintenance.date_completed',
                'units.plate_number',
                DB::raw("CONCAT(COALESCE(drivers.first_name,''), ' ', COALESCE(drivers.last_name,'')) as driver_name")
            )
            ->whereNull('maintenance.deleted_at')->whereNull('units.deleted_at')
            ->orderByDesc('maintenance.date_started')->limit(50)->get();

        // Drivers list
        $driversList = DB::table('drivers')
            ->leftJoin('units', 'drivers.id', '=', 'units.driver_id')
            ->select(
                'drivers.id', 'drivers.first_name', 'drivers.last_name',
                'drivers.contact_number', 'drivers.license_number', 'drivers.driver_status',
                'units.plate_number'
            )
            ->whereNull('drivers.deleted_at')
            ->orderBy('drivers.first_name')->limit(100)->get();

        // Coding units
        $codingList = $allUnits->filter(fn($u) => ($codingDays[substr($u->plate_number, -1)] ?? '') === $todayDay)
            ->values()->toArray();

        // Units list for Overview Modal (Matching Web)
        $unitsList = DB::table('units as u')
            ->whereNull('u.deleted_at')
            ->leftJoin('boundaries as b', function($join) {
                $join->on('u.id', '=', 'b.unit_id')->whereNull('b.deleted_at');
            })
            ->leftJoin('drivers as d', 'u.driver_id', '=', 'd.id')
            ->select(
                'u.id', 'u.plate_number', 'u.status', 'u.purchase_cost',
                DB::raw('COALESCE(SUM(b.actual_boundary), 0) as total_collection'),
                'd.id as driver_id'
            )
            ->groupBy('u.id', 'u.plate_number', 'u.status', 'u.purchase_cost', 'd.id')
            ->get()
            ->map(function($u) {
                $roi = ($u->purchase_cost > 0) ? ($u->total_collection / $u->purchase_cost) * 100 : 0;
                
                // Determine display status like web
                $displayStatus = $u->status;
                if ($u->status === 'active' && !$u->driver_id) {
                    $displayStatus = 'vacant';
                }

                return [
                    'id' => $u->id,
                    'plate' => $u->plate_number,
                    'status' => strtolower($displayStatus),
                    'total_collection' => (float)$u->total_collection,
                    'roi' => round($roi, 1),
                ];
            });

        // Executive Insights (Harmonizing with Web hardcoded/static values for parity)
        $topPerformerUnit = !empty($unitPerformance) ? $unitPerformance[0]['plate'] : 'N/A';
        $topPerformerDriver = !empty($topDrivers) ? $topDrivers[0]['name'] : 'N/A';

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'chartData' => [
                'revenueTrend' => $revenueTrend,
                'expenseBreakdown' => $expenseBreakdown,
                'unitStatusDist' => $unitStatusDist,
                'unitPerformance' => $unitPerformance,
                'weeklyData' => $weeklyData,
                'topDrivers' => $topDrivers
            ],
            'insights' => [
                'fleetHealth' => 82, // Hardcoded to match web precisely
                'healthMessage' => 'Most units are meeting over 80% of their monthly boundary targets.',
                'topPerformerUnit' => $topPerformerUnit,
                'topPerformerDriver' => $topPerformerDriver
            ],
            'modalData'  => [
                'maintenanceList' => $maintenanceList,
                'driversList'     => $driversList,
                'codingList'      => $codingList,
                'unitsList'       => $unitsList,
                'boundaryList'    => $boundaryList,
            ]
        ]);
    }
}
