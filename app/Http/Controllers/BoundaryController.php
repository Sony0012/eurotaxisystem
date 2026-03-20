<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\Boundary;
use Carbon\Carbon;

class BoundaryController extends Controller
{
    /**
     * Display a listing of boundary records.
     */
    public function index(Request $request)
    {
        $search    = $request->get('search', '');
        $date_from = $request->get('date_from', Carbon::now()->startOfMonth()->toDateString());
        $date_to   = $request->get('date_to',   Carbon::now()->toDateString());
        $page      = max(1, (int) $request->get('page', 1));
        $perPage   = 20;

        // Build query joining units and drivers tables
        $query = DB::table('boundaries as b')
            ->leftJoin('units as u',   'b.unit_id',   '=', 'u.id')
            ->leftJoin('drivers as d', 'b.driver_id', '=', 'd.id')
            ->leftJoin('users as usr', 'd.user_id',   '=', 'usr.id')
            ->select(
                'b.id',
                'b.boundary_amount',
                'b.date',
                'b.status',
                'b.notes',
                'u.unit_number',
                'u.plate_number',
                DB::raw("COALESCE(usr.name, 'N/A') as driver_name")
            )
            ->whereBetween('b.date', [$date_from, $date_to]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('u.unit_number',  'like', "%{$search}%")
                  ->orWhere('u.plate_number','like', "%{$search}%")
                  ->orWhere('usr.name',      'like', "%{$search}%");
            });
        }

        $total = $query->count();

        $boundaries = $query
            ->orderByDesc('b.date')
            ->orderByDesc('b.id')
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();

        // Totals for the summary cards
        $totalsQuery = DB::table('boundaries as b')
            ->whereBetween('b.date', [$date_from, $date_to]);

        if ($search) {
            $totalsQuery->leftJoin('units as u',   'b.unit_id',   '=', 'u.id')
                        ->leftJoin('drivers as d', 'b.driver_id', '=', 'd.id')
                        ->leftJoin('users as usr', 'd.user_id',   '=', 'usr.id')
                        ->where(function ($q) use ($search) {
                            $q->where('u.unit_number',  'like', "%{$search}%")
                              ->orWhere('u.plate_number','like', "%{$search}%")
                              ->orWhere('usr.name',      'like', "%{$search}%");
                        });
        }

        $totals = (object) [
            'total_amount'  => $totalsQuery->sum('b.boundary_amount'),
            'total_records' => $total,
            'paid_total'    => (clone $totalsQuery)->where('b.status', 'paid')->sum('b.boundary_amount'),
        ];

        // Units list for the "Record Boundary" modal
        $units = DB::table('units')
            ->select('id', 'unit_number', 'plate_number')
            ->orderBy('unit_number')
            ->get();

        // Drivers list (active)
        $drivers = DB::table('drivers as d')
            ->join('users as u', 'd.user_id', '=', 'u.id')
            ->select('d.id', 'u.name as driver_name')
            ->where('u.is_active', true)
            ->orderBy('u.name')
            ->get();

        $totalPages = max(1, (int) ceil($total / $perPage));

        $pagination = [
            'page'        => $page,
            'per_page'    => $perPage,
            'total_items' => $total,
            'total_pages' => $totalPages,
            'has_prev'    => $page > 1,
            'prev_page'   => $page - 1,
            'has_next'    => $page < $totalPages,
            'next_page'   => $page + 1,
        ];

        return view('boundaries.index', compact(
            'boundaries', 'pagination', 'search',
            'date_from', 'date_to', 'totals', 'units', 'drivers'
        ));
    }

    /**
     * Store a newly created boundary record.
     */
    public function store(Request $request)
    {
        $request->validate([
            'unit_id'         => 'required|exists:units,id',
            'driver_id'       => 'nullable|exists:drivers,id',
            'boundary_amount' => 'required|numeric|min:0',
            'date'            => 'required|date',
            'status'          => 'required|in:paid,late,short,excess',
            'notes'           => 'nullable|string|max:500',
        ]);

        $driverId = $request->driver_id;
        if (empty($driverId)) {
            $driverId = DB::table('units')->where('id', $request->unit_id)->value('driver_id');
        }

        if (empty($driverId)) {
            return back()
                ->withErrors(['driver_id' => 'Selected unit has no assigned driver. Please select a driver.'])
                ->withInput();
        }

        $payload = [
            'unit_id'         => $request->unit_id,
            'driver_id'       => $driverId,
            'boundary_amount' => $request->boundary_amount,
            'date'            => $request->date,
            'status'          => $request->status,
            'notes'           => $request->notes,
        ];

        if (Schema::hasColumn('boundaries', 'recorded_by')) {
            $payload['recorded_by'] = Auth::id();
        }

        Boundary::create($payload);

        return redirect()->route('boundaries.index')
            ->with('success', 'Boundary record saved successfully.');
    }

    /**
     * Show the form for editing the specified boundary.
     */
    public function edit($id)
    {
        $boundary = Boundary::findOrFail($id);
        $units    = DB::table('units')->select('id', 'unit_number', 'plate_number')->orderBy('unit_number')->get();
        $drivers  = DB::table('drivers as d')
            ->join('users as u', 'd.user_id', '=', 'u.id')
            ->select('d.id', 'u.name as driver_name')
            ->orderBy('u.name')
            ->get();

        return view('boundaries.edit', compact('boundary', 'units', 'drivers'));
    }

    /**
     * Update the specified boundary record.
     */
    public function update(Request $request, $id)
    {
        $boundary = Boundary::findOrFail($id);

        $request->validate([
            'unit_id'         => 'required|exists:units,id',
            'driver_id'       => 'nullable|exists:drivers,id',
            'boundary_amount' => 'required|numeric|min:0',
            'date'            => 'required|date',
            'status'          => 'required|in:paid,late,short,excess',
            'notes'           => 'nullable|string|max:500',
        ]);

        $driverId = $request->driver_id;
        if (empty($driverId)) {
            $driverId = DB::table('units')->where('id', $request->unit_id)->value('driver_id');
        }

        if (empty($driverId)) {
            return back()
                ->withErrors(['driver_id' => 'Selected unit has no assigned driver. Please select a driver.'])
                ->withInput();
        }

        $boundary->update([
            'unit_id'         => $request->unit_id,
            'driver_id'       => $driverId,
            'boundary_amount' => $request->boundary_amount,
            'date'            => $request->date,
            'status'          => $request->status,
            'notes'           => $request->notes,
        ]);

        return redirect()->route('boundaries.index')
            ->with('success', 'Boundary record updated successfully.');
    }

    /**
     * Remove the specified boundary record.
     */
    public function destroy($id)
    {
        $boundary = Boundary::findOrFail($id);
        $boundary->delete();

        return redirect()->route('boundaries.index')
            ->with('success', 'Boundary record deleted.');
    }

    /**
     * Display the specified boundary (show page – not used in current UI but required by resource route).
     */
    public function show($id)
    {
        return redirect()->route('boundaries.index');
    }

    /**
     * Show the form for creating a new boundary (not used – modal based UI).
     */
    public function create()
    {
        return redirect()->route('boundaries.index');
    }
}
