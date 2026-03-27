<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DriverBehaviorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $type_filter = $request->input('type', '');
        $severity_filter = $request->input('severity', '');
        $page = max(1, (int) $request->input('page', 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        // Get incidents with pagination
        $query = DB::table('driver_behavior as db')
            ->leftJoin('units as u', 'db.unit_id', '=', 'u.id')
            ->leftJoin('drivers as d', 'db.driver_id', '=', 'd.id')
            ->leftJoin('users as usr', 'd.user_id', '=', 'usr.id')
            ->select('db.*', 'u.unit_number', 'u.plate_number', 'usr.full_name as driver_name')
            ->orderByDesc('db.timestamp');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('usr.full_name', 'like', DB::raw("CONCAT('%', ?, '%') COLLATE utf8mb4_unicode_ci"), [$search])
                  ->orWhere('u.unit_number', 'like', DB::raw("CONCAT('%', ?, '%') COLLATE utf8mb4_unicode_ci"), [$search])
                  ->orWhere('db.incident_type', 'like', DB::raw("CONCAT('%', ?, '%') COLLATE utf8mb4_unicode_ci"), [$search])
                  ->orWhere('db.description', 'like', DB::raw("CONCAT('%', ?, '%') COLLATE utf8mb4_unicode_ci"), [$search]);
            });
        }

        if (!empty($type_filter)) {
            $query->where('db.incident_type', $type_filter);
        }

        if (!empty($severity_filter)) {
            $query->where('db.severity', $severity_filter);
        }

        $total = $query->count();
        $incidents = $query->offset($offset)->limit($limit)->get();

        $pagination = [
            'page' => $page,
            'total_pages' => ceil($total / $limit),
            'total_items' => $total,
            'has_prev' => $page > 1,
            'has_next' => $page < ceil($total / $limit),
            'prev_page' => $page - 1,
            'next_page' => $page + 1,
        ];

        // Get drivers for dropdown (active users with driver role)
        $drivers = DB::table('users as u')
            ->join('drivers as d', 'u.id', '=', 'd.user_id')
            ->where('u.is_active', 1)
            ->where('u.role', 'driver')
            ->select('d.id', 'u.full_name')
            ->orderBy('u.full_name')
            ->get();

        // Get units for dropdown
        $units = DB::table('units')
            ->where('status', 'active')
            ->select('id', 'unit_number', 'plate_number')
            ->orderBy('unit_number')
            ->get();

        return view('driver-behavior.index', compact(
            'incidents', 'search', 'type_filter', 'severity_filter', 
            'pagination', 'drivers', 'units'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'unit_id' => 'required|integer',
            'driver_id' => 'required|integer',
            'incident_type' => 'required|string',
            'severity' => 'required|string',
            'description' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'video_url' => 'nullable|string',
        ]);

        if ($data['unit_id'] > 0 && $data['driver_id'] > 0 && !empty($data['incident_type']) && !empty($data['severity']) && !empty($data['description'])) {
            DB::table('driver_behavior')->insert([
                'unit_id' => $data['unit_id'],
                'driver_id' => $data['driver_id'],
                'incident_type' => $data['incident_type'],
                'severity' => $data['severity'],
                'description' => $data['description'],
                'latitude' => $data['latitude'] ?? 0,
                'longitude' => $data['longitude'] ?? 0,
                'video_url' => $data['video_url'] ?? '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('driver-behavior.index')->with('success', 'Incident recorded successfully');
        } else {
            return redirect()->route('driver-behavior.index')->with('error', 'Please fill in all required fields');
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'unit_id' => 'required|integer',
            'driver_id' => 'required|integer',
            'incident_type' => 'required|string',
            'severity' => 'required|string',
            'description' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'video_url' => 'nullable|string',
        ]);

        DB::table('driver_behavior')->where('id', $id)->update([
            'unit_id' => $data['unit_id'],
            'driver_id' => $data['driver_id'],
            'incident_type' => $data['incident_type'],
            'severity' => $data['severity'],
            'description' => $data['description'],
            'latitude' => $data['latitude'] ?? 0,
            'longitude' => $data['longitude'] ?? 0,
            'video_url' => $data['video_url'] ?? '',
            'updated_at' => now(),
        ]);

        return redirect()->route('driver-behavior.index')->with('success', 'Incident updated successfully');
    }

    public function destroy($id)
    {
        DB::table('driver_behavior')->where('id', $id)->delete();
        return redirect()->route('driver-behavior.index')->with('success', 'Incident deleted successfully');
    }

    public function getStatistics(Request $request)
    {
        $date_from = $request->input('date_from', now()->subDays(30)->toDateString());
        $date_to = $request->input('date_to', now()->toDateString());

        $stats = [
            'total_incidents' => DB::table('driver_behavior')
                ->whereDate('created_at', '>=', $date_from)
                ->whereDate('created_at', '<=', $date_to)
                ->count(),
            'by_severity' => DB::table('driver_behavior')
                ->whereDate('created_at', '>=', $date_from)
                ->whereDate('created_at', '<=', $date_to)
                ->selectRaw('severity, COUNT(*) as count')
                ->groupBy('severity')
                ->get(),
            'by_type' => DB::table('driver_behavior')
                ->whereDate('created_at', '>=', $date_from)
                ->whereDate('created_at', '<=', $date_to)
                ->selectRaw('incident_type, COUNT(*) as count')
                ->groupBy('incident_type')
                ->get(),
        ];

        return response()->json($stats);
    }
}
