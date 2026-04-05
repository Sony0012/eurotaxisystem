<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LiveTrackingController extends Controller
{
    // ─── Main Page ─────────────────────────────────────────

    // ─── Main Page ─────────────────────────────────────────
    public function index()
    {
        try {
            // Get all units with their latest GPS data
            $tracked_units = DB::table('units as u')
                ->leftJoin('drivers as d', 'u.driver_id', '=', 'd.id')
                ->leftJoin('users as usr', 'd.user_id', '=', 'usr.id')
                ->leftJoin('gps_tracking as g', 'u.id', '=', 'g.unit_id')
                ->select(
                    'u.id', 'u.unit_number', 'u.plate_number', 'u.make', 'u.model', 'u.status', 'u.gps_link',
                    'usr.full_name as current_driver', 'usr.phone as driver_phone',
                    'g.latitude', 'g.longitude', 'g.speed', 'g.heading', 'g.ignition_status', 'g.timestamp as last_update'
                )
                ->orderBy('u.plate_number')
                ->get();

            // Determine GPS status for each unit
            foreach ($tracked_units as $unit) {
                $status = 'offline';
                $lastUpdate = $unit->last_update ? new \DateTime($unit->last_update) : null;
                
                if ($lastUpdate) {
                    $now = new \DateTime();
                    $diff = $now->getTimestamp() - $lastUpdate->getTimestamp();
                    
                    if ($diff < 300) { // Less than 5 minutes
                        if ($unit->ignition_status) {
                            $status = $unit->speed > 0 ? 'active' : 'idle';
                        } else {
                            $status = 'idle';
                        }
                    } else {
                        $status = 'offline';
                    }
                } elseif (!empty($unit->gps_link)) {
                    // Fallback to if link exists
                    $status = 'active';
                }
                
                $unit->gps_status = $status;
            }

            // Simulated stats logic
            $stats = [
                'total'     => $tracked_units->count(),
                'active'    => $tracked_units->where('gps_status', 'active')->count(),
                'idle'      => $tracked_units->where('gps_status', 'idle')->count(),
                'offline'   => $tracked_units->where('gps_status', 'offline')->count(),
                'avg_speed' => $tracked_units->avg('speed') ?? 0
            ];

            // Get system alerts
            $alerts = DB::table('system_alerts')
                ->where('is_resolved', false)
                ->orderByDesc('created_at')
                ->limit(10)
                ->get();

            // Get maintenance alerts
            $maintenanceAlerts = DB::table('maintenance')
                ->where('status', 'pending')
                ->where('date_started', '<=', now())
                ->orderBy('date_started')
                ->limit(5)
                ->get();

            return view('live-tracking.index', compact('tracked_units', 'alerts', 'maintenanceAlerts', 'stats'));

        } catch (\Exception $e) {
            \Log::error('Live Tracking Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading tracking data.');
        }
    }

    // ─── AJAX: All Units (for auto-refresh) ────────────────
    public function getUnitsLive()
    {
        try {
            $units = DB::table('units as u')
                ->leftJoin('drivers as d', 'u.driver_id', '=', 'd.id')
                ->leftJoin('users as usr', 'd.user_id', '=', 'usr.id')
                ->leftJoin('gps_tracking as g', 'u.id', '=', 'g.unit_id')
                ->select(
                    'u.id', 'u.unit_number', 'u.plate_number', 'u.gps_link', 'u.status',
                    'usr.full_name as driver_name', 'usr.phone as driver_phone',
                    'g.latitude', 'g.longitude', 'g.speed', 'g.heading', 'g.ignition_status', 'g.timestamp as last_update'
                )
                ->orderBy('u.plate_number')
                ->get();

            $result = $units->map(function ($unit) {
                // Determine Status
                $status = 'offline';
                $lastUpdate = $unit->last_update ? new \DateTime($unit->last_update) : null;
                
                if ($lastUpdate) {
                    $now = new \DateTime();
                    $diff = $now->getTimestamp() - $lastUpdate->getTimestamp();
                    if ($diff < 300) {
                        $status = ($unit->ignition_status && $unit->speed > 0) ? 'active' : 'idle';
                    }
                } elseif (!empty($unit->gps_link)) {
                    $status = 'active';
                }

                return [
                    'unit_id'         => $unit->id,
                    'unit_number'     => $unit->unit_number,
                    'plate_number'    => $unit->plate_number,
                    'driver_name'     => $unit->driver_name ?? 'None',
                    'gps_status'      => $status,
                    'speed'           => $unit->speed ?? 0,
                    'ignition_status' => (bool)$unit->ignition_status,
                    'last_update'     => $unit->last_update,
                    'gps_link'        => $unit->gps_link
                ];
            });

            return response()->json([
                'success' => true,
                'units' => $result,
                'stats' => [
                    'total'   => $result->count(),
                    'active'  => $result->where('gps_status', 'active')->count(),
                    'idle'    => $result->where('gps_status', 'idle')->count(),
                    'offline' => $result->where('gps_status', 'offline')->count()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ─── AJAX: Single Unit ─────────────────────────────────
    public function getUnitLocation($id)
    {
        try {
            $unit = DB::table('units as u')
                ->leftJoin('gps_tracking as g', 'u.id', '=', 'g.unit_id')
                ->select('u.*', 'g.latitude', 'g.longitude', 'g.speed', 'g.heading', 'g.ignition_status', 'g.timestamp as last_update')
                ->where('u.id', $id)
                ->first();

            if (!$unit) {
                return response()->json(['success' => false, 'error' => 'Unit not found']);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'unit_id'         => $unit->id,
                    'gps_link'        => $unit->gps_link,
                    'latitude'        => $unit->latitude,
                    'longitude'       => $unit->longitude,
                    'speed'           => $unit->speed ?? 0,
                    'ignition_status' => (bool)$unit->ignition_status,
                    'status'          => (!empty($unit->last_update) && (time() - strtotime($unit->last_update)) < 300) ? 'active' : 'offline'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
