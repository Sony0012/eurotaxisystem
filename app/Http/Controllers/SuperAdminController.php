<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\LoginAudit;

class SuperAdminController extends Controller
{
    // ─── Centralized page definitions (route => label) ────────────────────────
    public static array $pageDefinitions = [
        'dashboard'                  => ['icon' => 'layout-dashboard', 'label' => 'Dashboard',          'group' => 'Core'],
        'units.*'                    => ['icon' => 'car',              'label' => 'Unit Management',     'group' => 'Core'],
        'driver-management.*'        => ['icon' => 'users',            'label' => 'Driver Management',   'group' => 'Core'],
        'boundaries.*'               => ['icon' => 'dollar-sign',      'label' => 'Boundaries',          'group' => 'Finance'],
        'maintenance.*'              => ['icon' => 'wrench',           'label' => 'Maintenance',          'group' => 'Operations'],
        'coding.*'                   => ['icon' => 'calendar',         'label' => 'Coding Management',   'group' => 'Operations'],
        'driver-behavior.*'          => ['icon' => 'alert-triangle',   'label' => 'Driver Behavior',     'group' => 'Operations'],
        'office-expenses.*'          => ['icon' => 'receipt',          'label' => 'Office Expenses',     'group' => 'Finance'],
        'salary.*'                   => ['icon' => 'calculator',       'label' => 'Salary Management',   'group' => 'Finance'],
        'activity-logs.*'            => ['icon' => 'history',          'label' => 'History Logs',        'group' => 'Core'],
        'salaries.*'                 => ['icon' => 'calculator',       'label' => 'Salaries',            'group' => 'Finance'],
        'analytics.*'                => ['icon' => 'bar-chart',        'label' => 'Analytics',           'group' => 'Reports'],
        'unit-profitability.*'       => ['icon' => 'trending-up',      'label' => 'Unit Profitability',  'group' => 'Reports'],
        'live-tracking.*'            => ['icon' => 'map-pin',          'label' => 'Live Tracking',       'group' => 'Operations'],
        'decision-management.*'      => ['icon' => 'file-text',        'label' => 'Franchise',           'group' => 'Admin'],
        'staff.*'                    => ['icon' => 'user-cog',         'label' => 'Staff Records',       'group' => 'Admin'],
        'archive.*'                  => ['icon' => 'archive',          'label' => 'Archive',             'group' => 'Admin'],
        'boundary-rules.*'           => ['icon' => 'settings',         'label' => 'Boundary Rules',      'group' => 'Settings'],
        'spare-parts.*'              => ['icon' => 'package',          'label' => 'Spare Parts',         'group' => 'Settings'],
        'suppliers.*'                => ['icon' => 'truck',            'label' => 'Suppliers',           'group' => 'Settings'],
    ];

    // ─── Dashboard ────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $tab = $request->get('tab', 'overview');

        // Stats
        $totalUsers    = User::whereNotIn('role', ['super_admin'])->count();
        $pendingUsers  = User::whereNotIn('role', ['super_admin'])->where('approval_status', 'pending')->count();
        $activeUsers   = User::whereNotIn('role', ['super_admin'])->where('is_active', true)->where('approval_status', 'approved')->count();
        $rejectedUsers = User::whereNotIn('role', ['super_admin'])->where('approval_status', 'rejected')->count();

        // Recent login audit (for overview)
        $recentAudit = LoginAudit::orderByDesc('created_at')->limit(10)->get();

        // Users
        $allUsers = User::whereNotIn('role', ['super_admin'])
            ->withTrashed()
            ->orderByRaw("FIELD(approval_status, 'pending', 'approved', 'rejected')")
            ->orderByDesc('created_at')
            ->get();

        // Paginated audit log
        $auditLog = LoginAudit::orderByDesc('created_at')->paginate(25);

        return view('super-admin.index', compact(
            'tab',
            'totalUsers',
            'pendingUsers',
            'activeUsers',
            'rejectedUsers',
            'recentAudit',
            'allUsers',
            'auditLog'
        ));
    }

    // ─── Approve User ─────────────────────────────────────────────────────────

    public function approveUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'approval_status' => 'approved',
            'is_active'       => true,
            'approved_by'     => Auth::id(),
            'approved_at'     => now(),
        ]);

        LoginAudit::log('approved', $user, 'Account approved by ' . Auth::user()->full_name);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => $user->full_name . '\'s account has been approved.']);
        }

        return back()->with('success', $user->full_name . '\'s account has been approved and is now active.');
    }

    // ─── Reject User ──────────────────────────────────────────────────────────

    public function rejectUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'approval_status' => 'rejected',
            'is_active'       => false,
        ]);

        LoginAudit::log('rejected', $user, 'Account rejected by ' . Auth::user()->full_name);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => $user->full_name . '\'s account has been rejected.']);
        }

        return back()->with('success', $user->full_name . '\'s account has been rejected.');
    }

    // ─── Toggle Active Status ─────────────────────────────────────────────────

    public function toggleActive(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'super_admin') {
            return response()->json(['success' => false, 'message' => 'Cannot modify the Super Admin account.'], 403);
        }

        $newActive = !$user->is_active;
        $user->update(['is_active' => $newActive]);

        $action = $newActive ? 'Account re-activated' : 'Account deactivated';
        LoginAudit::log($newActive ? 'approved' : 'rejected', $user, $action . ' by ' . Auth::user()->full_name);

        return response()->json([
            'success'   => true,
            'is_active' => $newActive,
            'message'   => $user->full_name . ' has been ' . ($newActive ? 'activated.' : 'deactivated.'),
        ]);
    }

    // ─── Update Page Access ───────────────────────────────────────────────────

    public function updatePageAccess(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'super_admin') {
            return response()->json(['success' => false, 'message' => 'Cannot restrict Super Admin pages.'], 403);
        }

        $pages = $request->input('pages', null);

        // null = no restriction, [] = all blocked, [...] = specific pages allowed
        $user->update(['allowed_pages' => $pages]);

        return response()->json([
            'success' => true,
            'message' => 'Page access updated for ' . $user->full_name . '.',
        ]);
    }

    // ─── Login History (Paginated JSON) ───────────────────────────────────────

    public function loginHistory(Request $request)
    {
        $query = LoginAudit::orderByDesc('created_at');

        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->where(function ($q) use ($s) {
                $q->where('user_name', 'like', "%$s%")
                  ->orWhere('user_email', 'like', "%$s%")
                  ->orWhere('ip_address', 'like', "%$s%");
            });
        }

        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        if ($request->filled('role')) {
            $query->where('user_role', $request->input('role'));
        }

        $perPage = min((int) $request->input('per_page', 25), 100);
        $results = $query->paginate($perPage);

        return response()->json($results);
    }

    // ─── Delete / Restore User ────────────────────────────────────────────────

    public function deleteUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'super_admin') {
            return response()->json(['success' => false, 'message' => 'Cannot delete the Super Admin account.'], 403);
        }

        $user->delete(); // Soft delete
        return response()->json(['success' => true, 'message' => $user->full_name . ' has been archived.']);
    }

    public function restoreUser(Request $request, $id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        $user->update(['approval_status' => 'approved', 'is_active' => true]);

        return response()->json(['success' => true, 'message' => $user->full_name . ' has been restored.']);
    }

    // ─── Reset Password (Super Admin override) ────────────────────────────────

    public function resetPassword(Request $request, $id)
    {
        $request->validate(['password' => 'required|string|min:6']);

        $user = User::findOrFail($id);

        if ($user->role === 'super_admin' && Auth::user()->role !== 'super_admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $hashed = Hash::make($request->input('password'));
        $user->update(['password' => $hashed, 'password_hash' => $hashed]);

        LoginAudit::log('approved', $user, 'Password reset by ' . Auth::user()->full_name);

        return response()->json(['success' => true, 'message' => 'Password has been reset for ' . $user->full_name . '.']);
    }
    // ─── Update User Role ───────────────────────────────────────────────────
    public function updateRole(Request $request, $id)
    {
        $request->validate(['role' => 'required|string|in:manager,dispatcher,secretary,staff']);

        $user = User::findOrFail($id);

        if ($user->role === 'super_admin') {
            return response()->json(['success' => false, 'message' => 'Cannot change the Super Admin role.'], 403);
        }

        $oldRole = $user->role;
        $user->update(['role' => $request->input('role')]);

        LoginAudit::log('approved', $user, 'Role changed from ' . $oldRole . ' to ' . $user->role . ' by ' . Auth::user()->full_name);

        return response()->json(['success' => true, 'message' => 'Role updated for ' . $user->full_name . '.']);
    }
}
