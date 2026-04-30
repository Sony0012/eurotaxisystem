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
        $activeUsers   = User::whereNotIn('role', ['super_admin'])->where('is_active', true)->where('approval_status', 'approved')->count();
        $rejectedUsers = User::whereNotIn('role', ['super_admin'])->where('approval_status', 'rejected')->count();

        // Recent login audit (for overview) - Filter for only login-related activity
        $recentAudit = LoginAudit::whereIn('action', ['login', 'failed_login', 'logout'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Users
        $allUsers = User::whereNotIn('role', ['super_admin'])
            ->withTrashed()
            ->orderByRaw("FIELD(approval_status, 'pending', 'approved', 'rejected')")
            ->orderByDesc('created_at')
            ->get();

        // Paginated audit log - Filter for login-related activity for this tab
        $auditLog = LoginAudit::whereIn('action', ['login', 'failed_login', 'logout', 'approved', 'rejected', 'password_changed', 'created'])
            ->orderByDesc('created_at')
            ->paginate(25);
        // Classifications
        $classifications = \App\Models\IncidentClassification::orderBy('name')->get();
        $archivedClassifications = \App\Models\IncidentClassification::onlyTrashed()->orderBy('name')->get();

        // Roles
        $roles = \App\Models\Role::orderBy('label')->get();
        $archivedRoles = \App\Models\Role::onlyTrashed()->orderBy('label')->get();

        return view('super-admin.index', compact(
            'tab',
            'totalUsers',
            'activeUsers',
            'rejectedUsers',
            'recentAudit',
            'allUsers',
            'auditLog',
            'classifications',
            'archivedClassifications',
            'roles',
            'archivedRoles'
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
        $query = LoginAudit::whereIn('action', ['login', 'failed_login', 'logout', 'approved', 'rejected', 'password_changed', 'created'])
            ->orderByDesc('created_at');

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

    // ─── Get User Details & History ───────────────────────────────────────────

    public function getUserDetails(Request $request, $id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $history = LoginAudit::where('user_id', $user->id)
                             ->orderByDesc('created_at')
                             ->limit(50)
                             ->get();
                             
        // Append profile image url for easier frontend handling
        $profileUrl = $user->profile_image ? asset('storage/' . $user->profile_image) : null;
                             
        return response()->json([
            'success' => true,
            'user'    => [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'role' => $user->role,
                'status' => $user->approval_status,
                'is_active' => $user->is_active,
                'trashed' => $user->trashed(),
                'created_at' => $user->created_at->format('M d, Y h:i A'),
                'profile_url' => $profileUrl,
                'initials' => strtoupper(substr($user->full_name ?? 'U', 0, 1))
            ],
            'history' => $history
        ]);
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

    // ─── CREATE STAFF ACCOUNT (Super Admin only) ──────────────────────────────
    public function storeStaff(Request $request)
    {
        $validRoles = \App\Models\Role::pluck('name')->toArray();
        $roleIn = implode(',', $validRoles);

        $request->validate([
            'first_name'   => 'required|string|max:50',
            'last_name'    => 'required|string|max:50',
            'email'        => 'required|email|unique:users,email',
            'role'         => 'required|in:' . $roleIn,
            'phone_number' => 'nullable|string|max:20',
            'address'      => 'nullable|string|max:255',
        ]);

        // Auto-generate a secure temp password
        $tempPassword = strtoupper(substr(str_shuffle('abcdefghjkmnpqrstuvwxyz'), 0, 3))
                      . rand(100, 999)
                      . str_shuffle('!@#$%')[0];

        $user = User::create([
            'first_name'           => $request->first_name,
            'last_name'            => $request->last_name,
            'full_name'            => $request->first_name . ' ' . $request->last_name,
            'name'                 => $request->first_name . ' ' . $request->last_name,
            'username'             => strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $request->first_name . $request->last_name)) . rand(100, 999),
            'email'                => $request->email,
            'phone_number'         => $request->phone_number,
            'address'              => $request->address,
            'role'                 => $request->role,
            'password'             => \Illuminate\Support\Facades\Hash::make($tempPassword),
            'password_hash'        => \Illuminate\Support\Facades\Hash::make($tempPassword),
            'must_change_password' => true,
            'temp_password'        => $tempPassword,
            'is_active'            => true,
            'is_verified'          => true,
            'approval_status'      => 'approved',
        ]);

        // Send welcome email with temp password
        try {
            $mailResult = \Mail::to($user->email)->send(new \App\Mail\StaffWelcomeMail($user, $tempPassword));
        } catch (\Throwable $e) {
            \Log::error('StaffWelcomeMail failed: ' . $e->getMessage());
        }

        LoginAudit::log('created', $user, 'Staff account created by ' . Auth::user()->full_name . ' with role: ' . $user->role);

        return response()->json([
            'success'       => true,
            'message'       => 'Staff account created! Credentials sent to ' . $user->email,
            'temp_password' => $tempPassword,
        ]);
    }

    // ─── Incident Classification Management ───────────────────────────────────
    public function storeClassification(Request $request)
    {
        $data = $request->validate([
            'name'              => 'required|string|unique:incident_classifications,name',
            'default_severity'  => 'required|in:low,medium,high,critical',
            'color'             => 'required|string',
            'icon'              => 'required|string',
            'behavior_mode'     => 'nullable|in:narrative,complaint,traffic,damage',
            'sub_options'       => 'nullable|array',
            'sub_options.*'     => 'string|max:100',
            'auto_ban_trigger'  => 'nullable|boolean',
            'ban_trigger_value' => 'nullable|string|max:100',
        ]);

        $data['behavior_mode']    = $data['behavior_mode'] ?? 'narrative';
        $data['sub_options']      = $data['sub_options'] ?? null;
        $data['auto_ban_trigger'] = (bool)($data['auto_ban_trigger'] ?? false);

        $item = \App\Models\IncidentClassification::create($data);

        return response()->json(['success' => true, 'data' => $item, 'message' => 'New incident classification added!']);
    }

    public function updateClassification(Request $request, $id)
    {
        $item = \App\Models\IncidentClassification::findOrFail($id);
        
        $data = $request->validate([
            'name'              => 'required|string|unique:incident_classifications,name,' . $id,
            'default_severity'  => 'required|in:low,medium,high,critical',
            'color'             => 'required|string',
            'icon'              => 'required|string',
            'behavior_mode'     => 'nullable|in:narrative,complaint,traffic,damage',
            'sub_options'       => 'nullable|array',
            'sub_options.*'     => 'string|max:100',
            'auto_ban_trigger'  => 'nullable|boolean',
            'ban_trigger_value' => 'nullable|string|max:100',
        ]);

        $data['sub_options']      = $data['sub_options'] ?? null;
        $data['auto_ban_trigger'] = (bool)($data['auto_ban_trigger'] ?? false);
        $data['behavior_mode']    = $data['behavior_mode'] ?? 'narrative';

        $item->update($data);

        return response()->json(['success' => true, 'data' => $item, 'message' => 'Classification updated successfully.']);
    }

    public function archiveClassification($id)
    {
        $item = \App\Models\IncidentClassification::findOrFail($id);
        $item->delete();

        return response()->json(['success' => true, 'message' => 'Classification moved to archive.']);
    }

    public function restoreClassification($id)
    {
        $item = \App\Models\IncidentClassification::withTrashed()->findOrFail($id);
        $item->restore();

        return response()->json(['success' => true, 'message' => 'Classification restored.']);
    }

    public function deleteClassification($id)
    {
        $item = \App\Models\IncidentClassification::withTrashed()->findOrFail($id);
        $item->forceDelete();

        return response()->json(['success' => true, 'message' => 'Classification permanently deleted.']);
    }

    // ─── Role Management ───────────────────────────────────────────────────────
    public function storeRole(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|unique:roles,name',
            'label'       => 'required|string',
            'description' => 'nullable|string',
        ]);

        $role = \App\Models\Role::create($data);

        return response()->json(['success' => true, 'data' => $role, 'message' => 'New system role added!']);
    }

    public function updateRoleDetail(Request $request, $id)
    {
        $role = \App\Models\Role::findOrFail($id);
        
        $data = $request->validate([
            'name'        => 'required|string|unique:roles,name,' . $id,
            'label'       => 'required|string',
            'description' => 'nullable|string',
        ]);

        $role->update($data);

        return response()->json(['success' => true, 'data' => $role, 'message' => 'Role updated successfully.']);
    }

    public function archiveRole($id)
    {
        $role = \App\Models\Role::findOrFail($id);
        $role->delete();

        return response()->json(['success' => true, 'message' => 'Role moved to archive.']);
    }

    public function restoreRole($id)
    {
        $role = \App\Models\Role::withTrashed()->findOrFail($id);
        $role->restore();

        return response()->json(['success' => true, 'message' => 'Role restored.']);
    }

    public function deleteRole($id)
    {
        $role = \App\Models\Role::withTrashed()->findOrFail($id);
        $role->forceDelete();

        return response()->json(['success' => true, 'message' => 'Role permanently deleted.']);
    }
}
