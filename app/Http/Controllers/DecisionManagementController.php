<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DecisionManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $page = max(1, (int) $request->input('page', 1));
        $limit = 15;
        $offset = ($page - 1) * $limit;

        // Real columns: id, applicant_name, case_no, type_of_application, denomination, date_filed, expiry_date
        $query = DB::table('franchise_cases');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('applicant_name', 'like', DB::raw("CONCAT('%', ?, '%') COLLATE utf8mb4_unicode_ci"), [$search])
                  ->orWhere('case_no', 'like', DB::raw("CONCAT('%', ?, '%') COLLATE utf8mb4_unicode_ci"), [$search])
                  ->orWhere('type_of_application', 'like', DB::raw("CONCAT('%', ?, '%') COLLATE utf8mb4_unicode_ci"), [$search])
                  ->orWhere('denomination', 'like', DB::raw("CONCAT('%', ?, '%') COLLATE utf8mb4_unicode_ci"), [$search]);
            });
        }

        $total = $query->count();
        $cases = $query->orderByDesc('created_at')->offset($offset)->limit($limit)->get();

        // Get statistics
        $stats = [
            'total_cases' => DB::table('franchise_cases')->count(),
            'expiring_soon' => DB::table('franchise_cases')
                ->whereNotNull('expiry_date')
                ->whereRaw('expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)')
                ->count(),
            'expired' => DB::table('franchise_cases')
                ->whereNotNull('expiry_date')
                ->whereRaw('expiry_date < CURDATE()')
                ->count(),
            'pending' => DB::table('franchise_cases')->where('status', 'pending')->count(),
            'approved' => DB::table('franchise_cases')->where('status', 'approved')->count(),
            'rejected' => DB::table('franchise_cases')->where('status', 'rejected')->count(),
        ];

        $pagination = [
            'page' => $page,
            'total_pages' => ceil($total / $limit),
            'total_items' => $total,
            'has_prev' => $page > 1,
            'has_next' => $page < ceil($total / $limit),
            'prev_page' => $page - 1,
            'next_page' => $page + 1,
        ];

        return view('decision-management.index', compact('cases', 'search', 'pagination', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'applicant_name' => 'required|string|max:255',
            'case_no' => 'required|string|max:100|unique:franchise_cases,case_no',
            'type_of_application' => 'required|string|max:255',
            'denomination' => 'required|string|max:255',
            'date_filed' => 'required|date',
            'expiry_date' => 'nullable|date',
            'status' => 'nullable|in:pending,approved,rejected',
            'remarks' => 'nullable|string',
        ]);

        DB::table('franchise_cases')->insert([
            'applicant_name' => $request->applicant_name,
            'case_no' => $request->case_no,
            'type_of_application' => $request->type_of_application,
            'denomination' => $request->denomination,
            'date_filed' => $request->date_filed,
            'expiry_date' => $request->expiry_date ?: null,
            'status' => $request->status ?? 'pending',
            'remarks' => $request->remarks,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('decision-management.index')->with('success', 'Case added successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'applicant_name' => 'required|string|max:255',
            'case_no' => 'required|string|max:100',
            'type_of_application' => 'required|string|max:255',
            'denomination' => 'required|string|max:255',
            'date_filed' => 'required|date',
            'expiry_date' => 'nullable|date',
            'status' => 'nullable|in:pending,approved,rejected',
            'remarks' => 'nullable|string',
        ]);

        DB::table('franchise_cases')->where('id', $id)->update([
            'applicant_name' => $request->applicant_name,
            'case_no' => $request->case_no,
            'type_of_application' => $request->type_of_application,
            'denomination' => $request->denomination,
            'date_filed' => $request->date_filed,
            'expiry_date' => $request->expiry_date ?: null,
            'status' => $request->status ?? 'pending',
            'remarks' => $request->remarks,
            'updated_at' => now(),
        ]);

        return redirect()->route('decision-management.index')->with('success', 'Case updated successfully');
    }

    public function destroy($id)
    {
        DB::table('franchise_cases')->where('id', $id)->delete();
        return redirect()->route('decision-management.index')->with('success', 'Case deleted successfully');
    }

    public function approve($id)
    {
        DB::table('franchise_cases')->where('id', $id)->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('decision-management.index')->with('success', 'Case approved successfully');
    }

    public function reject($id)
    {
        DB::table('franchise_cases')->where('id', $id)->update([
            'status' => 'rejected',
            'rejected_by' => auth()->id(),
            'rejected_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('decision-management.index')->with('success', 'Case rejected successfully');
    }
}
