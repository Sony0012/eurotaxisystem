<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Unit;

class UnitController extends Controller
{
    /**
     * Display a listing of the units with stats and ROI.
     */
    public function index()
    {
        $units = Unit::with(['driver'])->whereNull('deleted_at')->get()->map(function($unit) {
            // Calculate ROI
            $boundaryCollected = DB::table('boundaries')
                ->where('unit_id', $unit->id)
                ->whereNull('deleted_at')
                ->whereIn('status', ['paid', 'excess', 'shortage'])
                ->sum('actual_boundary') ?? 0;

            $maintenanceCost = DB::table('maintenance')
                ->where('unit_id', $unit->id)
                ->whereNull('deleted_at')
                ->where('status', '!=', 'cancelled')
                ->sum('cost') ?? 0;

            $totalInvestment = ($unit->purchase_cost ?? 0) + $maintenanceCost;
            $roiPercentage = $totalInvestment > 0 ? ($boundaryCollected / $totalInvestment) * 100 : 0;
            $isRoi = $boundaryCollected >= $totalInvestment && $totalInvestment > 0;

            return [
                'id' => $unit->unit_number ?? "TXN-" . $unit->id,
                'db_id' => $unit->id,
                'plate_number' => $unit->plate_number,
                'model' => $unit->model,
                'year' => $unit->year,
                'status' => $unit->status,
                'type' => $unit->boundary_rate > 1000 ? 'New Unit' : 'Old Unit',
                'assigned_driver' => $unit->driver ? $unit->driver->full_name : 'No Driver',
                'boundary_type' => $unit->boundary_rate > 1000 ? 'New' : 'Old',
                'purchase_cost' => (float)$unit->purchase_cost,
                'maintenance_cost' => (float)$maintenanceCost,
                'boundary_collected' => (float)$boundaryCollected,
                'revenue' => (float)$boundaryCollected,
                'roi' => $isRoi,
                'roi_percentage' => round($roiPercentage, 2),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $units,
        ]);
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
