<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Unit;

class UnitController extends Controller
{
    /**
     * Display a listing of the units — Web-parity data.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $statusFilter = $request->input('status', '');
        $sort = $request->input('sort', 'alphabetical');

        $query = DB::table('units as u')
            ->whereNull('u.deleted_at')
            ->leftJoin('drivers as d1', 'u.driver_id', '=', 'd1.id')
            ->leftJoin('drivers as d2', 'u.secondary_driver_id', '=', 'd2.id')
            ->select(
                'u.id',
                'u.plate_number',
                'u.make',
                'u.model',
                'u.year',
                'u.status',
                'u.motor_no',
                'u.chassis_no',
                'u.gps_device_count',
                'u.imei',
                'u.current_gps_odo',
                'u.last_service_odo_gps',
                'u.boundary_rate',
                'u.purchase_cost',
                'u.unit_type',
                'u.driver_id',
                'u.secondary_driver_id',
                DB::raw("CONCAT(COALESCE(d1.first_name,''), ' ', COALESCE(d1.last_name,'')) as primary_driver_name"),
                DB::raw("CONCAT(COALESCE(d2.first_name,''), ' ', COALESCE(d2.last_name,'')) as secondary_driver_name")
            );

        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('u.plate_number', 'like', "%$search%")
                  ->orWhere('u.model', 'like', "%$search%")
                  ->orWhere('u.make', 'like', "%$search%")
                  ->orWhere(DB::raw("CONCAT(COALESCE(d1.first_name,''), ' ', COALESCE(d1.last_name,''))"), 'like', "%$search%")
                  ->orWhere(DB::raw("CONCAT(COALESCE(d2.first_name,''), ' ', COALESCE(d2.last_name,''))"), 'like', "%$search%");
            });
        }

        // Status filter
        if ($statusFilter) {
            $query->where('u.status', $statusFilter);
        }

        // Sort
        switch ($sort) {
            case 'newest':
                $query->orderByDesc('u.created_at');
                break;
            case 'oldest':
                $query->orderBy('u.created_at');
                break;
            default:
                $query->orderBy('u.plate_number');
        }

        $rawUnits = $query->get();

        $units = $rawUnits->map(function ($u) {
            // Derive rate label from unit_type (matching what the web shows)
            $rateLabel = match(strtolower($u->unit_type ?? '')) {
                'new'    => 'Sunday Discount',
                'old'    => 'Standard Rate',
                'rented' => 'Rental Rate',
                default  => 'Standard Rate',
            };

            // ROI calculation
            $boundaryCollected = (float) DB::table('boundaries')
                ->where('unit_id', $u->id)
                ->whereNull('deleted_at')
                ->whereIn('status', ['paid', 'excess', 'shortage'])
                ->sum('actual_boundary');

            $maintenanceCost = (float) DB::table('maintenance')
                ->where('unit_id', $u->id)
                ->whereNull('deleted_at')
                ->where('status', '!=', 'cancelled')
                ->sum('cost');

            $totalInvestment = (float)($u->purchase_cost ?? 0) + $maintenanceCost;
            $roiPercentage = $totalInvestment > 0 ? ($boundaryCollected / $totalInvestment) * 100 : 0;
            $isRoi = $boundaryCollected >= $totalInvestment && $totalInvestment > 0;

            $d1Name = trim($u->primary_driver_name ?? '');
            $d2Name = trim($u->secondary_driver_name ?? '');

            return [
                'id'                   => $u->id,
                'plate_number'         => $u->plate_number,
                'make'                 => $u->make ?? '',
                'model'                => $u->model ?? '',
                'year'                 => $u->year ?? '',
                'motor_no'             => $u->motor_no ?? '—',
                'chassis_no'           => $u->chassis_no ?? '—',
                'gps_device_count'     => (int)($u->gps_device_count ?? 0),
                'imei'                 => $u->imei,
                'current_gps_odo'      => (float)($u->current_gps_odo ?? 0),
                'last_service_odo_gps' => (float)($u->last_service_odo_gps ?? 0),
                'status'               => $u->status ?? 'active',
                'boundary_rate'        => (float)($u->boundary_rate ?? 0),
                'rate_label'           => $rateLabel,
                'driver_id'            => $u->driver_id,
                'secondary_driver_id'  => $u->secondary_driver_id,
                'primary_driver'       => $d1Name ?: null,
                'secondary_driver'     => $d2Name ?: null,
                'purchase_cost'        => (float)($u->purchase_cost ?? 0),
                'maintenance_cost'     => $maintenanceCost,
                'revenue'              => $boundaryCollected,
                'roi'                  => $isRoi,
                'roi_percentage'       => round($roiPercentage, 2),
            ];
        });

        // Compute stats matching the web's quick-stats bar
        $all = DB::table('units')->whereNull('deleted_at')->get(['status']);
        $stats = [
            'total'       => $all->count(),
            'on_road'     => $all->where('status', 'active')->count(),
            'garage'      => $all->where('status', 'at_risk')->count(),
            'workshop'    => $all->where('status', 'maintenance')->count(),
            'coding'      => $all->where('status', 'coding')->count(),
        ];

        return response()->json([
            'success' => true,
            'data'    => $units,
            'stats'   => $stats,
        ]);
    }

    /**
     * Store a new unit.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plate_number'   => 'required|string|unique:units,plate_number|max:20',
            'make'           => 'required|string|max:50',
            'model'          => 'required|string|max:50',
            'year'           => 'required|integer|min:1990|max:2100',
            'motor_no'       => 'required|string|max:191',
            'chassis_no'     => 'required|string|max:191',
            'boundary_rate'  => 'required|numeric|min:0',
            'purchase_cost'  => 'nullable|numeric|min:0',
        ]);

        $unit = DB::table('units')->insertGetId(array_merge($validated, [
            'status'     => 'active',
            'unit_type'  => $validated['boundary_rate'] > 1000 ? 'new' : 'old',
            'created_at' => now(),
            'updated_at' => now(),
        ]));

        return response()->json(['success' => true, 'message' => 'Unit added successfully.', 'id' => $unit], 201);
    }

    /**
     * Display the specified unit.
     */
    public function show($id)
    {
        $unit = Unit::with(['driver'])->find($id);

        if (!$unit) {
            return response()->json(['success' => false, 'message' => 'Unit not found.'], 404);
        }

        return response()->json(['success' => true, 'data' => $unit]);
    }
}
