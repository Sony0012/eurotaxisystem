<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BoundaryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $date_from = $request->input('date_from', date('Y-m-01'));
        $date_to = $request->input('date_to', date('Y-m-d'));
        $page = max(1, (int) $request->input('page', 1));
        $limit = 15;
        $offset = ($page - 1) * $limit;

        $query = DB::table('boundaries as b')
            ->leftJoin('units as u', 'b.unit_id', '=', 'u.id')
            ->leftJoin('users as usr', 'b.driver_id', '=', 'usr.id')
            ->select('b.*', 'u.unit_number', 'u.plate_number', 'usr.full_name as driver_name')
            ->whereBetween('b.date', [$date_from, $date_to]);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('u.unit_number', 'like', DB::raw("CONCAT('%', ?, '%') COLLATE utf8mb4_unicode_ci"), [$search])
                    ->orWhere('u.plate_number', 'like', DB::raw("CONCAT('%', ?, '%') COLLATE utf8mb4_unicode_ci"), [$search])
                    ->orWhere('usr.full_name', 'like', DB::raw("CONCAT('%', ?, '%') COLLATE utf8mb4_unicode_ci"), [$search]);
            });
        }

        $total = $query->count();
        $boundaries = $query->orderByDesc('b.date')->offset($offset)->limit($limit)->get();

        $totals = DB::table('boundaries')
            ->whereBetween('date', [$date_from, $date_to])
            ->selectRaw('SUM(boundary_amount) as total_amount, COUNT(*) as total_records, SUM(CASE WHEN status="paid" THEN boundary_amount ELSE 0 END) as paid_total')
            ->first();

        $units = DB::table('units')->where('status', 'active')->orderBy('unit_number')->get();
        $drivers = DB::table('users')->where('is_active', true)->get();

        $pagination = [
            'page' => $page,
            'total_pages' => ceil($total / $limit),
            'total_items' => $total,
            'has_prev' => $page > 1,
            'has_next' => $page < ceil($total / $limit),
            'prev_page' => $page - 1,
            'next_page' => $page + 1,
        ];

        return view('boundaries.index', compact('boundaries', 'pagination', 'search', 'date_from', 'date_to', 'totals', 'units', 'drivers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|integer',
            'driver_id' => 'required|integer',
            'date' => 'required|date',
            'boundary_amount' => 'required|numeric',
            'actual_boundary' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        $unit_id = $request->unit_id;
        $driver_id = $request->driver_id;
        $date = $request->date;
        $boundary_amount = $request->boundary_amount;
        $actual_boundary = $request->actual_boundary ?? $boundary_amount;
        $notes = $request->notes ?? '';

        // Check for duplicate entry
        $existing = DB::table('boundaries')
            ->where('unit_id', $unit_id)
            ->where('date', $date)
            ->first();

        if ($existing) {
            return back()->with('error', 'Boundary record already exists for this unit and date');
        }

        // Calculate shortage and excess
        $shortage = max(0, $boundary_amount - $actual_boundary);
        $excess = max(0, $actual_boundary - $boundary_amount);
        $status = $shortage > 0 ? 'shortage' : ($excess > 0 ? 'excess' : 'paid');

        DB::table('boundaries')->insert([
            'unit_id' => $unit_id,
            'driver_id' => $driver_id,
            'date' => $date,
            'boundary_amount' => $boundary_amount,
            'actual_boundary' => $actual_boundary,
            'shortage' => $shortage,
            'excess' => $excess,
            'status' => $status,
            'notes' => $notes,
            'recorded_by' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('boundaries.index')->with('success', 'Boundary record added successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'unit_id' => 'required|integer',
            'driver_id' => 'required|integer',
            'date' => 'required|date',
            'boundary_amount' => 'required|numeric',
            'actual_boundary' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        $boundary_amount = $request->boundary_amount;
        $actual_boundary = $request->actual_boundary ?? $boundary_amount;
        
        // Calculate shortage and excess
        $shortage = max(0, $boundary_amount - $actual_boundary);
        $excess = max(0, $actual_boundary - $boundary_amount);
        $status = $shortage > 0 ? 'shortage' : ($excess > 0 ? 'excess' : 'paid');

        DB::table('boundaries')->where('id', $id)->update([
            'unit_id' => $request->unit_id,
            'driver_id' => $request->driver_id,
            'date' => $request->date,
            'boundary_amount' => $boundary_amount,
            'actual_boundary' => $actual_boundary,
            'shortage' => $shortage,
            'excess' => $excess,
            'status' => $status,
            'notes' => $request->notes,
            'updated_at' => now(),
        ]);

        return redirect()->route('boundaries.index')->with('success', 'Boundary updated successfully!');
    }

    public function destroy($id)
    {
        DB::table('boundaries')->where('id', $id)->delete();
        return redirect()->route('boundaries.index')->with('success', 'Boundary record deleted!');
    }
}
