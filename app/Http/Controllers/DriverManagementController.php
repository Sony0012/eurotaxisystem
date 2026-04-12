<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Traits\CalculatesBoundary;

class DriverManagementController extends Controller
{
    use CalculatesBoundary;

    public function index(Request $request)
    {
        $search        = $request->input('search', '');
        $status_filter = $request->input('status', '');
        $sort          = $request->input('sort', 'alphabetical');
        $page          = max(1, (int) $request->input('page', 1));
        $limit         = 10;
        $offset        = ($page - 1) * $limit;

        // Build base query — no users JOIN needed, names are in drivers table
        $query = DB::table('drivers as d')
            ->whereNull('d.deleted_at')
            ->leftJoin('users as creator', 'd.created_by', '=', 'creator.id')
            ->leftJoin('users as editor', 'd.updated_by', '=', 'editor.id')
            ->select(
                'd.id', 'd.user_id', 'd.first_name', 'd.last_name', 'd.nickname',
                'd.license_number', 'd.license_expiry',
                'd.contact_number', 'd.hire_date', 'd.daily_boundary_target',
                'd.driver_type', 'd.driver_status',
                'd.emergency_contact', 'd.emergency_phone',
                DB::raw("CONCAT(COALESCE(d.first_name,''), ' ', COALESCE(d.last_name,'')) as full_name"),
                'creator.full_name as creator_name',
                'editor.full_name as editor_name',
                // Unit assignment — units.driver_id links to drivers.id
                DB::raw("(SELECT plate_number FROM units WHERE (driver_id = d.id OR secondary_driver_id = d.id) AND deleted_at IS NULL LIMIT 1) as assigned_unit"),
                DB::raw("(SELECT plate_number FROM units WHERE (driver_id = d.id OR secondary_driver_id = d.id) AND deleted_at IS NULL LIMIT 1) as assigned_plate"),
                DB::raw("(SELECT boundary_rate FROM units WHERE (driver_id = d.id OR secondary_driver_id = d.id) AND deleted_at IS NULL LIMIT 1) as assigned_boundary_rate"),
                DB::raw("(SELECT coding_day FROM units WHERE (driver_id = d.id OR secondary_driver_id = d.id) AND deleted_at IS NULL LIMIT 1) as assigned_coding_day"),
                DB::raw("(SELECT year FROM units WHERE (driver_id = d.id OR secondary_driver_id = d.id) AND deleted_at IS NULL LIMIT 1) as assigned_unit_year"),
                DB::raw("(SELECT COALESCE(SUM(actual_boundary * 0.05), 0) FROM boundaries WHERE driver_id = d.id AND status IN ('paid', 'excess') AND MONTH(date) = MONTH(CURRENT_DATE()) AND YEAR(date) = YEAR(CURRENT_DATE()) AND deleted_at IS NULL) as monthly_incentive"),
                DB::raw("(SELECT CASE
                    WHEN COUNT(*) >= 25 THEN 'Excellent'
                    WHEN COUNT(*) >= 15 THEN 'Good'
                    WHEN COUNT(*) >= 5  THEN 'Average'
                    ELSE 'Growing'
                END FROM boundaries WHERE driver_id = d.id AND status IN ('paid', 'excess', 'shortage') AND date >= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY) AND deleted_at IS NULL) as performance_rating"),
                // is_active derived from driver_status
                DB::raw("CASE WHEN d.driver_status IN ('available','assigned') THEN 1 ELSE 0 END as is_active"),
                // Net unpaid shortage: sum of all shortages minus sum of all excess
                DB::raw("(SELECT GREATEST(0, COALESCE(SUM(shortage),0) - COALESCE(SUM(excess),0)) FROM boundaries WHERE driver_id = d.id AND deleted_at IS NULL) as net_shortage")
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('d.first_name',    'like', "%{$search}%")
                  ->orWhere('d.last_name',   'like', "%{$search}%")
                  ->orWhere('d.license_number', 'like', "%{$search}%")
                  ->orWhere('d.contact_number', 'like', "%{$search}%");
            });
        }

        if ($status_filter) {
            if ($status_filter === 'active') {
                $query->whereIn('d.driver_status', ['available', 'assigned']);
            } elseif ($status_filter === 'inactive') {
                $query->whereNotIn('d.driver_status', ['available', 'assigned']);
            } elseif ($status_filter === 'no_unit') {
                $query->whereNotExists(function($q) {
                    $q->select(DB::raw(1))
                      ->from('units')
                      ->whereNull('deleted_at')
                      ->where(function($q2) {
                          $q2->whereColumn('units.driver_id', 'd.id')
                             ->orWhereColumn('units.secondary_driver_id', 'd.id');
                      });
                });
            }
        }

        switch ($sort) {
            case 'newest':
                $query->orderBy('d.created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('d.created_at', 'asc');
                break;
            case 'status':
                $query->orderBy('d.driver_status', 'asc')->orderBy('d.last_name', 'asc');
                break;
            case 'alphabetical':
            default:
                $query->orderBy('d.last_name', 'asc')->orderBy('d.first_name', 'asc');
                break;
        }

        $total       = $query->count();
        $drivers     = $query->offset($offset)->limit($limit)->get();
        $total_pages = max(1, ceil($total / $limit));

        $rules = DB::table('boundary_rules')->get();

        foreach ($drivers as $driver) {
            if (!empty($driver->assigned_plate) || !empty($driver->assigned_unit)) {
                // Smart Pricing Calculation
                $pricing = $this->getCurrentPricing([
                    'year' => $driver->assigned_unit_year,
                    'boundary_rate' => $driver->assigned_boundary_rate,
                    'plate_number' => $driver->assigned_plate,
                    'coding_day' => $driver->assigned_coding_day,
                    'daily_boundary_target' => $driver->daily_boundary_target
                ], $rules);

                $driver->current_target = $pricing['rate'];
                $driver->target_label = $pricing['label'];
                $driver->target_type = $pricing['type'];
            } else {
                $driver->current_target = 0;
                $driver->target_label = null;
                $driver->target_type = null;
            }
        }

        // Stats
        $stats = [
            'total'     => DB::table('drivers')->whereNull('deleted_at')->count(),
            'available' => DB::table('drivers')->whereNull('deleted_at')->where('driver_status', 'available')->count(),
            'assigned'  => DB::table('drivers')->whereNull('deleted_at')->where('driver_status', 'assigned')->count(),
            'on_leave'  => DB::table('drivers')->whereNull('deleted_at')->where('driver_status', 'on_leave')->count(),
        ];

        // Expiring licenses within 30 days
        $expiring_licenses = DB::table('drivers as d')
            ->whereNull('d.deleted_at')
            ->whereRaw('d.license_expiry BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)')
            ->select(
                DB::raw("CONCAT(COALESCE(d.first_name,''), ' ', COALESCE(d.last_name,'')) as full_name"),
                'd.license_number', 'd.license_expiry'
            )
            ->get();

        $pagination = [
            'page'        => $page,
            'total_pages' => $total_pages,
            'total_items' => $total,
            'has_prev'    => $page > 1,
            'has_next'    => $page < $total_pages,
            'prev_page'   => $page - 1,
            'next_page'   => $page + 1,
        ];

        if ($request->ajax()) {
            return view('driver-management.partials._drivers_table', compact(
                'drivers', 'pagination', 'search', 'status_filter', 'sort'
            ))->render();
        }

        $boundary_rules = \App\Models\BoundaryRule::all();

        return view('driver-management.index', compact(
            'drivers', 'search', 'pagination', 'stats', 'expiring_licenses', 'status_filter', 'sort', 'boundary_rules'
        ));
    }

    public function show($id)
    {
        $driver = DB::table('drivers as d')
            ->whereNull('d.deleted_at')
            ->where('d.id', $id)
            ->select(
                'd.*',
                DB::raw("CONCAT(COALESCE(d.first_name,''), ' ', COALESCE(d.last_name,'')) as full_name"),
                DB::raw("(SELECT plate_number FROM units WHERE (driver_id = d.id OR secondary_driver_id = d.id) AND deleted_at IS NULL LIMIT 1) as assigned_unit"),
                DB::raw("(SELECT plate_number FROM units WHERE (driver_id = d.id OR secondary_driver_id = d.id) AND deleted_at IS NULL LIMIT 1) as assigned_plate"),
                DB::raw("(SELECT boundary_rate FROM units WHERE (driver_id = d.id OR secondary_driver_id = d.id) AND deleted_at IS NULL LIMIT 1) as assigned_boundary_rate"),
                DB::raw("(SELECT coding_day FROM units WHERE (driver_id = d.id OR secondary_driver_id = d.id) AND deleted_at IS NULL LIMIT 1) as assigned_coding_day"),
                DB::raw("(SELECT year FROM units WHERE (driver_id = d.id OR secondary_driver_id = d.id) AND deleted_at IS NULL LIMIT 1) as assigned_unit_year"),
                DB::raw("(SELECT COALESCE(SUM(actual_boundary * 0.05), 0) FROM boundaries WHERE driver_id = d.id AND status IN ('paid', 'excess') AND MONTH(date) = MONTH(CURRENT_DATE()) AND YEAR(date) = YEAR(CURRENT_DATE()) AND deleted_at IS NULL) as monthly_incentive"),
                DB::raw("(SELECT CASE
                    WHEN COUNT(*) >= 25 THEN 'Excellent'
                    WHEN COUNT(*) >= 15 THEN 'Good'
                    WHEN COUNT(*) >= 5  THEN 'Average'
                    ELSE 'Growing'
                END FROM boundaries WHERE driver_id = d.id AND status IN ('paid', 'excess', 'shortage') AND date >= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY) AND deleted_at IS NULL) as performance_rating")
            )
            ->first();

        if (!empty($driver->assigned_plate) || !empty($driver->assigned_unit)) {
            $driver->current_pricing = $this->getCurrentPricing([
                'year' => $driver->assigned_unit_year,
                'boundary_rate' => $driver->assigned_boundary_rate,
                'plate_number' => $driver->assigned_plate,
                'coding_day' => $driver->assigned_coding_day,
                'daily_boundary_target' => $driver->daily_boundary_target
            ]);
        } else {
            $driver->current_pricing = null;
        }

        // Recent performance logs for the modal tab
        $driver->recent_performance = DB::table('boundaries as b')
            ->where('b.driver_id', $id)
            ->whereNull('b.deleted_at')
            ->leftJoin('units as u', 'b.unit_id', '=', 'u.id')
            ->select('b.date', 'b.actual_boundary', 'b.boundary_amount', 'b.status', 'b.shortage', 'u.plate_number')
            ->orderByDesc('b.date')
            ->limit(10)
            ->get();

        return response()->json($driver);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'            => 'required|string|max:100',
            'last_name'             => 'required|string|max:100',
            'contact_number'        => 'required|string|max:20',
            'address'               => 'required|string',
            'license_number'        => 'required|string|max:50|unique:drivers,license_number',
            'license_expiry'        => 'required|date',
            'emergency_contact'     => 'required|string|max:100',
            'emergency_phone'       => 'required|string|max:20',
            'hire_date'             => 'required|date',
            'daily_boundary_target' => 'nullable|numeric|min:0',
        ]);

        Driver::create([
            'first_name'            => $request->first_name,
            'last_name'             => $request->last_name,
            'nickname'              => $request->nickname,
            'license_number'        => $request->license_number,
            'license_expiry'        => $request->license_expiry,
            'contact_number'        => $request->contact_number,
            'address'               => $request->address,
            'emergency_contact'     => $request->emergency_contact,
            'emergency_phone'       => $request->emergency_phone,
            'hire_date'             => $request->hire_date,
            'daily_boundary_target' => $request->daily_boundary_target ?? 0,
            'driver_type'           => $request->driver_type ?? 'regular',
            'driver_status'         => 'available',
        ]);

        return redirect()->route('driver-management.index')
            ->with('success', "Driver {$request->first_name} {$request->last_name} added successfully!");
    }

    public function update(Request $request, $id)
    {
        $driver_instance = Driver::findOrFail($id);

        $request->validate([
            'first_name'            => 'required|string|max:100',
            'last_name'             => 'required|string|max:100',
            'contact_number'        => 'required|string|max:20',
            'address'               => 'required|string',
            'license_number'        => 'required|string|max:50',
            'license_expiry'        => 'required|date',
            'emergency_contact'     => 'required|string|max:100',
            'emergency_phone'       => 'required|string|max:20',
            'hire_date'             => 'required|date',
            'daily_boundary_target' => 'nullable|numeric|min:0',
            'driver_type'           => 'nullable|in:regular,senior,trainee',
            'driver_status'         => 'nullable|in:available,assigned,on_leave,suspended',
        ]);

        $driver_instance->update([
            'first_name'            => $request->first_name,
            'last_name'             => $request->last_name,
            'nickname'              => $request->nickname,
            'license_number'        => $request->license_number,
            'license_expiry'        => $request->license_expiry,
            'contact_number'        => $request->contact_number,
            'address'               => $request->address,
            'emergency_contact'     => $request->emergency_contact,
            'emergency_phone'       => $request->emergency_phone,
            'hire_date'             => $request->hire_date,
            'daily_boundary_target' => $request->daily_boundary_target ?? 0,
            'driver_type'           => $request->driver_type ?? 'regular',
            'driver_status'         => $request->driver_status ?? 'available',
        ]);

        return redirect()->route('driver-management.index')->with('success', 'Driver updated successfully');
    }

    public function destroy($id)
    {
        $driver = Driver::find($id);
        if ($driver) {
            // Unassign from units before soft-deleting
            DB::table('units')->where('driver_id', $driver->id)->update(['driver_id' => null]);
            DB::table('units')->where('secondary_driver_id', $driver->id)->update(['secondary_driver_id' => null]);
            $driver->delete();
            return redirect()->route('driver-management.index')->with('success', 'Driver archived successfully');
        }
        return redirect()->route('driver-management.index')->with('error', 'Driver not found.');
    }

    public function uploadDocuments(Request $request, $id)
    {
        $request->validate([
            'license_scan'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'nbi_clearance'       => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'medical_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $driver = DB::table('drivers')->where('id', $id)->first();
        if (!$driver) {
            return back()->with('error', 'Driver not found.');
        }

        foreach (['license_scan', 'nbi_clearance', 'medical_certificate'] as $field) {
            if ($request->hasFile($field)) {
                $file     = $request->file($field);
                $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/drivers'), $filename);
            }
        }

        return back()->with('success', 'Documents uploaded successfully!');
    }
}
