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

        // Today boundary
        $stats['today_boundary'] = (float)(DB::table('boundaries')
            ->whereNull('deleted_at')->whereDate('date', $today)->sum('actual_boundary') ?? 0);

        // Month boundary
        $stats['month_boundary'] = (float)(DB::table('boundaries')
            ->whereNull('deleted_at')->whereDate('date', '>=', $startOfMonth)->sum('actual_boundary') ?? 0);

        // Expenses
        $genEx  = (float)(DB::table('expenses')->whereNull('deleted_at')->whereDate('date', $today)->sum('amount') ?? 0);
        $salEx  = (float)(DB::table('salaries')->whereDate('pay_date', $today)->sum('total_salary') ?? 0);
        $mntEx  = (float)(DB::table('maintenance')->whereNull('deleted_at')->whereDate('date_started', $today)->where('status', '!=', 'cancelled')->sum('cost') ?? 0);
        $stats['total_expenses_today'] = $genEx + $salEx + $mntEx;
        $stats['today_expenses']       = $stats['total_expenses_today'];
        $stats['expense_general']      = $genEx;
        $stats['expense_salary']       = $salEx;
        $stats['expense_maintenance']  = $mntEx;

        // Month expenses
        $mGenEx = (float)(DB::table('expenses')->whereNull('deleted_at')->whereDate('date', '>=', $startOfMonth)->sum('amount') ?? 0);
        $mSalEx = (float)(DB::table('salaries')->whereDate('pay_date', '>=', $startOfMonth)->sum('total_salary') ?? 0);
        $mMntEx = (float)(DB::table('maintenance')->whereNull('deleted_at')->whereDate('date_started', '>=', $startOfMonth)->where('status', '!=', 'cancelled')->sum('cost') ?? 0);
        $stats['total_expenses_month'] = $mGenEx + $mSalEx + $mMntEx;

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
            $sx   = (float)(DB::table('salaries')->whereDate('pay_date', $d)->sum('total_salary') ?? 0);
            $mx   = (float)(DB::table('maintenance')->whereNull('deleted_at')->whereDate('date_started', $d)->where('status', '!=', 'cancelled')->sum('cost') ?? 0);
            $exp  = $gx + $sx + $mx;

            $label = ($days <= 30)
                ? now()->timezone('Asia/Manila')->subDays($i)->format('M d')
                : now()->timezone('Asia/Manila')->subDays($i)->format('M d y');

            $revenueTrend[] = ['date' => $label, 'revenue' => $rev, 'expenses' => $exp, 'netIncome' => $rev - $exp];
        }

        // 2. Unit Performance (Top 10 this month)
        $topUnits = DB::table('boundaries')
            ->join('units', 'boundaries.unit_id', '=', 'units.id')
            ->select('units.plate_number', DB::raw('SUM(boundaries.actual_boundary) as total_collected'))
            ->whereNull('boundaries.deleted_at')
            ->whereDate('boundaries.date', '>=', $startOfMonth)
            ->whereIn('boundaries.status', ['paid', 'excess', 'shortage'])
            ->groupBy('units.id', 'units.plate_number')
            ->orderByDesc('total_collected')->limit(10)->get();

        $unitPerformance = $topUnits->map(fn($u) => [
            'plate' => $u->plate_number,
            'actual' => (float)$u->total_collected,
            'target' => 30000
        ])->toArray();

        // 3. Expense Breakdown
        $expenseBreakdown = [
            ['name' => 'General', 'value' => $genEx],
            ['name' => 'Salaries', 'value' => $salEx],
            ['name' => 'Maintenance', 'value' => $mntEx],
        ];

        // 4. Weekly Financial Overview (last 7 days grouped)
        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $d   = now()->timezone('Asia/Manila')->subDays($i)->toDateString();
            $rev = (float)(DB::table('boundaries')->whereNull('deleted_at')->whereDate('date', $d)->sum('actual_boundary') ?? 0);
            $gx  = (float)(DB::table('expenses')->whereNull('deleted_at')->whereDate('date', $d)->sum('amount') ?? 0);
            $sx  = (float)(DB::table('salaries')->whereDate('pay_date', $d)->sum('total_salary') ?? 0);
            $mx  = (float)(DB::table('maintenance')->whereNull('deleted_at')->whereDate('date_started', $d)->where('status', '!=', 'cancelled')->sum('cost') ?? 0);
            $weeklyData[] = [
                'day' => now()->timezone('Asia/Manila')->subDays($i)->format('D'),
                'boundary' => $rev,
                'expenses' => $gx + $sx + $mx
            ];
        }

        // 5. Unit Status Distribution
        $totalUnits      = $stats['active_units'];
        $maintenanceCount = $stats['maintenance_units'];
        $codingCount     = $stats['coding_units'];
        $operationalCount = max(0, $totalUnits - $maintenanceCount - $codingCount);
        $unitStatusDist  = [
            ['name' => 'Operational', 'value' => $operationalCount],
            ['name' => 'Maintenance', 'value' => $maintenanceCount],
            ['name' => 'Coding Today', 'value' => $codingCount],
        ];

        // 6. Top Drivers (by boundary collected this month)
        $topDrivers = DB::table('boundaries')
            ->leftJoin('drivers', 'boundaries.driver_id', '=', 'drivers.id')
            ->select('drivers.first_name', 'drivers.last_name', DB::raw('SUM(boundaries.actual_boundary) as total'))
            ->whereNull('boundaries.deleted_at')
            ->whereNotNull('boundaries.driver_id')
            ->whereDate('boundaries.date', '>=', $startOfMonth)
            ->whereIn('boundaries.status', ['paid', 'excess', 'shortage'])
            ->groupBy('drivers.id', 'drivers.first_name', 'drivers.last_name')
            ->orderByDesc('total')->limit(10)->get();

        $topDriversData = $topDrivers->map(fn($d) => [
            'name' => trim(($d->first_name ?? '') . ' ' . ($d->last_name ?? '')),
            'total' => (float)$d->total
        ])->filter(fn($d) => $d['name'] !== '')->values()->toArray();

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

        return response()->json([
            'success'    => true,
            'stats'      => $stats,
            'chartData'  => [
                'revenueTrend'    => $revenueTrend,
                'unitPerformance' => $unitPerformance,
                'expenseBreakdown'=> $expenseBreakdown,
                'weeklyData'      => $weeklyData,
                'unitStatusDist'  => $unitStatusDist,
                'topDrivers'      => $topDriversData,
            ],
            'modalData'  => [
                'maintenanceList' => $maintenanceList,
                'driversList'     => $driversList,
                'codingList'      => $codingList,
            ]
        ]);
    }
}
