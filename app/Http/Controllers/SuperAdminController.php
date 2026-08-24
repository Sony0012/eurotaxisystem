<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\LoginAudit;
use App\Models\SystemSetting;
use App\Models\IncidentClassification;
use App\Models\Role;
use App\Models\Driver;
use App\Models\StaffFeedback;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SuperAdminController extends Controller
{
    // ─── Centralized page definitions (Strictly matches the 18 Sidebar Menu Items) ───
    public static array $pageDefinitions = [
        // ─── Core Management ───────────────────
        'dashboard' => ['icon' => 'layout-dashboard', 'label' => 'Dashboard', 'group' => '1. Core Management'],
        'units.*' => ['icon' => 'car', 'label' => 'Unit Management', 'group' => '1. Core Management'],
        'driver-management.*' => ['icon' => 'users', 'label' => 'Driver Management', 'group' => '1. Core Management'],
        'activity-logs.*' => ['icon' => 'history', 'label' => 'History Logs', 'group' => '1. Core Management'],

        // ─── Operations ────────────────────────
        'live-tracking.*' => ['icon' => 'map-pin', 'label' => 'Live Tracking', 'group' => '2. Operations'],
        'decision-management.*' => ['icon' => 'file-text', 'label' => 'Franchise', 'group' => '2. Operations'],
        'boundaries.*' => ['icon' => 'wallet', 'label' => 'Boundaries', 'group' => '2. Operations'],
        'maintenance.*' => ['icon' => 'wrench', 'label' => 'Maintenance', 'group' => '2. Operations'],
        'coding.*' => ['icon' => 'calendar', 'label' => 'Coding Management', 'group' => '2. Operations'],
        'driver-behavior.*' => ['icon' => 'alert-triangle', 'label' => 'Driver Behavior', 'group' => '2. Operations'],

        // ─── Financial ─────────────────────────
        'office-expenses.*' => ['icon' => 'philippine-peso', 'label' => 'Office Expenses', 'group' => '3. Financial'],
        'salary.*' => ['icon' => 'calculator', 'label' => 'Salary Management', 'group' => '3. Financial'],

        // ─── Reports ───────────────────────────
        'analytics.*' => ['icon' => 'bar-chart', 'label' => 'Analytics', 'group' => '4. Reports'],
        'unit-profitability.*' => ['icon' => 'trending-up', 'label' => 'Unit Profitability', 'group' => '4. Reports'],

        // ─── Staff, Support & Admin ────────────
        'staff.*' => ['icon' => 'user-cog', 'label' => 'General Staff Records', 'group' => '5. Staff & Support'],
        'support.*' => ['icon' => 'message-square', 'label' => 'Support Center', 'group' => '5. Staff & Support'],
        'announcements.*' => ['icon' => 'megaphone', 'label' => 'Announcements', 'group' => '5. Staff & Support'],
        'archive.*' => ['icon' => 'archive', 'label' => 'Archive', 'group' => '5. Staff & Support'],
    ];

    // ─── Dashboard ────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $tab = $request->get('tab', 'overview');

        // Stats
        $totalUsers = User::whereNotIn('role', ['super_admin', 'driver'])->count();
        $activeUsers = User::whereNotIn('role', ['super_admin', 'driver'])
            ->where('is_active', true)
            ->where('approval_status', 'approved')
            ->whereDate('last_login', today())
            ->count();
        $rejectedUsers = LoginAudit::whereIn('action', ['failed_login', 'rejected'])
            ->whereDate('created_at', today())
            ->count();

        // Recent login audit (for overview) - Filter for only login-related activity
        $recentAudit = LoginAudit::whereIn('action', ['login', 'failed_login', 'logout'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Users
        $allUsers = User::whereNotIn('role', ['super_admin', 'driver'])
            ->withTrashed()
            ->orderByRaw("FIELD(approval_status, 'pending', 'approved', 'rejected')")
            ->orderByDesc('created_at')
            ->get();

        // Paginated audit log - Filter for login-related activity for this tab
        $auditLog = LoginAudit::whereIn('action', ['login', 'logout'])
            ->orderByDesc('created_at')
            ->paginate(25);
        // Classifications
        $classifications = IncidentClassification::orderBy('name')->get();
        $archivedClassifications = IncidentClassification::onlyTrashed()->orderBy('name')->get();

        // Roles
        $roles = Role::orderBy('label')->get();
        $archivedRoles = Role::onlyTrashed()->orderBy('label')->get();

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

    public function indexJson(Request $request)
    {
        if (Auth::user()->role !== 'super_admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $totalUsers = User::whereNotIn('role', ['super_admin', 'driver'])->count();
        $activeUsers = User::whereNotIn('role', ['super_admin', 'driver'])
            ->where('is_active', true)
            ->where('approval_status', 'approved')
            ->whereDate('last_login', today())
            ->count();
        $rejectedUsers = LoginAudit::whereIn('action', ['failed_login', 'rejected'])
            ->whereDate('created_at', today())
            ->count();

        $recentAudit = LoginAudit::whereIn('action', ['login', 'failed_login', 'logout'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $allUsers = User::whereNotIn('role', ['super_admin', 'driver'])
            ->orderByRaw("FIELD(approval_status, 'pending', 'approved', 'rejected')")
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'stats' => [
                'total_users' => $totalUsers,
                'active_users' => $activeUsers,
                'rejected_users' => $rejectedUsers,
            ],
            'recentAudit' => $recentAudit,
            'allUsers' => $allUsers,
            'archivedUsers' => User::onlyTrashed()->whereNotIn('role', ['super_admin', 'driver'])->orderBy('deleted_at', 'desc')->get(),
            'roles' => Role::orderBy('label')->get(),
            'archivedRoles' => Role::onlyTrashed()->orderBy('label')->get()
        ]);
    }

    // ─── Approve User ─────────────────────────────────────────────────────────

    public function approveUser(Request $request, $id)
    {
        $user = User::where('id', $id)->firstOrFail();

        $user->update([
            'approval_status' => 'approved',
            'is_active' => true,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
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
        $user = User::where('id', $id)->firstOrFail();

        $user->update([
            'approval_status' => 'rejected',
            'is_active' => false,
        ]);

        LoginAudit::log('rejected', $user, 'Account rejected by ' . Auth::user()->full_name);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => $user->full_name . '\'s account has been rejected.']);
        }

        return back()->with('success', $user->full_name . '\'s account has been rejected.');
    }

    // ─── Toggle Active Status ─────────────────────────────────────────────────

    public function toggleDisable(Request $request, $id)
    {
        $user = User::where('id', $id)->firstOrFail();

        if ($user->role === 'super_admin') {
            return response()->json(['success' => false, 'message' => 'Cannot disable the Super Admin account.'], 403);
        }

        $is_disabled = $request->input('is_disabled');
        $reason = $request->input('reason');

        $user->update([
            'is_disabled' => $is_disabled,
            'disable_reason' => $is_disabled ? $reason : null,
        ]);

        $action = $is_disabled ? 'account_disabled' : 'account_enabled';
        LoginAudit::log($is_disabled ? 'rejected' : 'approved', $user, 'Account ' . ($is_disabled ? 'disabled' : 'enabled') . ' by ' . Auth::user()->full_name . ($reason ? ' Reason: ' . $reason : ''));

        return response()->json([
            'success' => true,
            'is_disabled' => $user->is_disabled,
            'message' => 'Account ' . ($user->is_disabled ? 'disabled' : 'enabled') . ' successfully.'
        ]);
    }

    // ─── Update Page Access ───────────────────────────────────────────────────

    public function updatePageAccess(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User account not found.'], 404);
        }

        if ($user->role === 'super_admin') {
            return response()->json(['success' => false, 'message' => 'Cannot restrict Super Admin pages.'], 403);
        }

        $pages = $request->input('pages', []);

        // Array of allowed routes (e.g. ['units.*', 'dashboard', ...])
        $user->allowed_pages = is_array($pages) ? array_values($pages) : [];
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Page access updated for ' . $user->full_name . '.',
        ]);
    }

    // ─── Login History (Paginated JSON) ───────────────────────────────────────

    public function loginHistory(Request $request)
    {
        $query = LoginAudit::whereIn('action', ['login', 'logout'])
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

    public function archiveUser(Request $request, $id)
    {
        $user = User::where('id', $id)->firstOrFail();

        if ($user->role === 'super_admin') {
            return response()->json(['success' => false, 'message' => 'Cannot archive the Super Admin account.'], 403);
        }

        $user->delete(); // Soft delete
        LoginAudit::log('rejected', $user, 'Account archived by ' . Auth::user()->full_name);

        return response()->json(['success' => true, 'message' => $user->full_name . ' has been moved to archives.']);
    }

    public function restoreUser(Request $request, $id)
    {
        $user = User::withTrashed()->where('id', $id)->firstOrFail();
        $user->restore();

        LoginAudit::log('approved', $user, 'Account restored by ' . Auth::user()->full_name);

        return response()->json(['success' => true, 'message' => $user->full_name . ' has been restored.']);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::withTrashed()->where('id', $id)->firstOrFail();

        if ($user->role === 'super_admin' && Auth::user()->id != $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $data = $request->validate([
            'first_name' => ['required', 'string', 'min:3', 'max:50', 'regex:/^[A-Za-z]+(?:\s[A-Za-z]+)*$/'],
            'middle_name' => ['nullable', 'string', 'min:3', 'max:50', 'regex:/^[A-Za-z]+(?:\s[A-Za-z]+)*$/'],
            'last_name' => ['required', 'string', 'min:3', 'max:50', 'regex:/^[A-Za-z]+(?:\s[A-Za-z]+)*$/'],
            'suffix' => ['nullable', 'string', 'max:10'],
            'email' => ['required', 'email', 'unique:users,email,' . $id],
            'role' => ['required', 'string'],
            'phone_number' => ['nullable', 'string', 'regex:/^09\d{9}$/'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $fullNameParts = array_filter([
            $data['first_name'],
            $data['middle_name'] ?? null,
            $data['last_name']
        ]);
        $fullName = implode(' ', $fullNameParts);
        if (!empty($data['suffix'])) {
            $fullName .= ' ' . $data['suffix'];
        }
        $data['full_name'] = $fullName;
        $data['name'] = $fullName;

        $user->update($data);

        LoginAudit::log('approved', $user, 'Account details updated by ' . Auth::user()->full_name);

        return response()->json(['success' => true, 'message' => 'User account updated successfully.']);
    }

    // ─── Get User Details & History ───────────────────────────────────────────

    public function getUserDetails(Request $request, $id)
    {
        $user = User::withTrashed()->where('id', $id)->firstOrFail();
        $history = LoginAudit::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        // Append profile image url for easier frontend handling
        $profileUrl = null;
        if ($user->profile_image) {
            $isIcon = str_starts_with($user->profile_image, 'image/') || str_contains($user->profile_image, 'resources/assets/');
            if ($isIcon) {
                $profileUrl = asset(str_replace('resources/', '', $user->profile_image));
            } else {
                $profileUrl = asset('storage/' . $user->profile_image);
            }
        }

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'role' => $user->role,
                'status' => $user->approval_status,
                'is_active' => $user->is_active,
                'is_disabled' => $user->is_disabled,
                'trashed' => $user->trashed(),
                'must_change_password' => $user->must_change_password,
                'last_login' => $user->last_login,
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

        $user = User::where('id', $id)->firstOrFail();

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

        $user = User::where('id', $id)->firstOrFail();

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
        $validRoles = Role::pluck('name')->toArray();
        $roleIn = implode(',', $validRoles);

        $request->validate([
            'first_name' => ['required', 'string', 'min:3', 'max:50', 'regex:/^[A-Za-z]+(?:\s[A-Za-z]+)*$/'],
            'middle_name' => ['nullable', 'string', 'min:3', 'max:50', 'regex:/^[A-Za-z]+(?:\s[A-Za-z]+)*$/'],
            'last_name' => ['required', 'string', 'min:3', 'max:50', 'regex:/^[A-Za-z]+(?:\s[A-Za-z]+)*$/'],
            'suffix' => ['nullable', 'string', 'max:10'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', 'in:' . $roleIn],
            'phone_number' => ['nullable', 'string', 'regex:/^09\d{9}$/'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        // Auto-generate a secure temp password
        $tempPassword = strtoupper(substr(str_shuffle('abcdefghjkmnpqrstuvwxyz'), 0, 3))
            . rand(100, 999)
            . str_shuffle('!@#$%')[0];

        $fullNameParts = array_filter([
            $request->first_name,
            $request->middle_name,
            $request->last_name
        ]);
        $fullName = implode(' ', $fullNameParts);
        if ($request->suffix) {
            $fullName .= ' ' . $request->suffix;
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'suffix' => $request->suffix,
            'full_name' => $fullName,
            'name' => $fullName,
            'username' => strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $request->first_name . $request->last_name)) . rand(100, 999),
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'role' => $request->role,
            'password' => Hash::make($tempPassword),
            'password_hash' => Hash::make($tempPassword),
            'must_change_password' => true,
            'temp_password' => $tempPassword,
            'is_active' => true,
            'is_verified' => true,
            'approval_status' => 'approved',
        ]);

        // Send welcome email with temp password (using send_custom_email for hPanel fallback support)
        try {
            require_once app_path('Helpers/MailerHelper.php');
            $emailBody = view('emails.staff_welcome', ['user' => $user, 'tempPassword' => $tempPassword])->render();
            $sent = send_custom_email(
                $user->email,
                'Welcome to Eurotaxi Fleet Management System - Your Account Credentials',
                $emailBody
            );
            if (!$sent) {
                Log::error('StaffWelcomeMail failed: send_custom_email returned false for ' . $user->email);
            }
        } catch (\Throwable $e) {
            Log::error('StaffWelcomeMail failed: ' . $e->getMessage());
        }

        LoginAudit::log('created', $user, 'Staff account created by ' . Auth::user()->full_name . ' with role: ' . $user->role);

        return response()->json([
            'success' => true,
            'message' => 'Staff account created! Credentials sent to ' . $user->email,
            'temp_password' => $tempPassword,
        ]);
    }

    // ─── Incident Classification Management ───────────────────────────────────
    public function storeClassification(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:incident_classifications,name',
            'default_severity' => 'required|in:low,medium,high,critical',
            'color' => 'required|string',
            'icon' => 'required|string',
            'behavior_mode' => 'nullable|in:narrative,complaint,traffic,damage,security',
            'sub_options' => 'nullable|array',
            'sub_options.*' => 'string|max:100',
            'auto_ban_trigger' => 'nullable|boolean',
            'ban_trigger_value' => 'nullable|string|max:100',
            'show_not_at_fault' => 'nullable|boolean',
        ]);

        $data['behavior_mode'] = $data['behavior_mode'] ?? 'narrative';
        $data['sub_options'] = $data['sub_options'] ?? null;
        $data['auto_ban_trigger'] = (bool) ($data['auto_ban_trigger'] ?? false);
        $data['show_not_at_fault'] = (bool) ($data['show_not_at_fault'] ?? false);

        $item = IncidentClassification::create($data);

        return response()->json(['success' => true, 'data' => $item, 'message' => 'New incident classification added!']);
    }

    public function getClassificationDetails($id)
    {
        $item = IncidentClassification::withTrashed()->where('id', $id)->firstOrFail();
        return response()->json(['success' => true, 'data' => $item]);
    }

    public function updateClassification(Request $request, $id)
    {
        Log::info("Updating Classification ID: {$id}", $request->all());
        $item = IncidentClassification::where('id', $id)->firstOrFail();

        $data = $request->validate([
            'name' => 'required|string|unique:incident_classifications,name,' . $id,
            'default_severity' => 'required|in:low,medium,high,critical',
            'color' => 'required|string',
            'icon' => 'required|string',
            'behavior_mode' => 'nullable|in:narrative,complaint,traffic,damage,security',
            'sub_options' => 'nullable|array',
            'sub_options.*' => 'string|max:100',
            'auto_ban_trigger' => 'nullable|boolean',
            'ban_trigger_value' => 'nullable|string|max:100',
            'show_not_at_fault' => 'nullable|boolean',
        ]);

        $data['sub_options'] = $data['sub_options'] ?? null;
        $data['auto_ban_trigger'] = (bool) ($data['auto_ban_trigger'] ?? false);
        $data['show_not_at_fault'] = (bool) ($data['show_not_at_fault'] ?? false);
        $data['behavior_mode'] = $data['behavior_mode'] ?? 'narrative';

        $item->update($data);

        return response()->json(['success' => true, 'data' => $item, 'message' => 'Classification updated successfully.']);
    }

    public function archiveClassification($id, Request $request)
    {
        try {
            $item = IncidentClassification::where('id', $id)->firstOrFail();
            $item->delete();
            return response()->json(['success' => true, 'message' => 'Classification moved to Archive.']);
        } catch (\Exception $e) {
            Log::error("Archive Classification Error: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function restoreClassification($id)
    {
        $item = IncidentClassification::withTrashed()->where('id', $id)->firstOrFail();
        $item->restore();

        return response()->json(['success' => true, 'message' => 'Classification restored.']);
    }



    // ─── Role Management ───────────────────────────────────────────────────────
    public function storeRole(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'label' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $role = Role::create($data);

        return response()->json(['success' => true, 'data' => $role, 'message' => 'New system role added!']);
    }

    public function updateRoleDetail(Request $request, $id)
    {
        $role = Role::where('id', $id)->firstOrFail();

        $data = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $id,
            'label' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $role->update($data);

        return response()->json(['success' => true, 'data' => $role, 'message' => 'Role updated successfully.']);
    }

    public function archiveRole($id)
    {
        $role = Role::where('id', $id)->firstOrFail();
        $role->delete();

        return response()->json(['success' => true, 'message' => 'Role moved to archive.']);
    }

    public function restoreRole($id)
    {
        $role = Role::withTrashed()->where('id', $id)->firstOrFail();
        $role->restore();

        return response()->json(['success' => true, 'message' => 'Role restored.']);
    }

    public function deleteRole($id, Request $request)
    {
        try {
            $this->verifyArchivePassword($request);
            $role = Role::withTrashed()->where('id', $id)->firstOrFail();
            $role->forceDelete();
            return response()->json(['success' => true, 'message' => 'Role permanently deleted.']);
        } catch (\Exception $e) {
            $code = ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) ? $e->getStatusCode() : 403;
            return response()->json(['success' => false, 'message' => $e->getMessage()], $code);
        }
    }

    public function deleteUser($id, Request $request)
    {
        $this->verifyArchivePassword($request);

        $user = User::withTrashed()->where('id', $id)->firstOrFail();

        if ($user->role === 'super_admin') {
            return response()->json(['success' => false, 'message' => 'Cannot delete the Super Admin.'], 403);
        }

        // Unlink from Driver record if exists to prevent CASCADE delete from DB
        Driver::where('user_id', $user->id)->update(['user_id' => null]);

        $user->forceDelete();
        return response()->json(['success' => true, 'message' => 'User permanently deleted (Driver record preserved).']);
    }

    public function updateArchivePassword(Request $request)
    {
        $request->validate([
            'archive_password' => 'required|string|min:6|confirmed',
        ]);

        $hashed = Hash::make($request->archive_password);

        SystemSetting::updateOrCreate(
            ['key' => 'archive_deletion_password'],
            ['value' => $hashed, 'group' => 'security']
        );

        return response()->json(['success' => true, 'message' => 'Archive deletion password updated successfully.']);
    }

    public function deleteClassification($id, Request $request)
    {
        try {
            $this->verifyArchivePassword($request);

            $item = IncidentClassification::withTrashed()->where('id', $id)->firstOrFail();
            $item->forceDelete();

            return response()->json(['success' => true, 'message' => 'Classification permanently deleted.']);
        } catch (\Exception $e) {
            $code = ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) ? $e->getStatusCode() : 500;
            return response()->json(['success' => false, 'message' => $e->getMessage()], $code);
        }
    }

    private function verifyArchivePassword(Request $request)
    {
        $password = $request->input('archive_password');

        if (!SystemSetting::verifyPassword($password)) {
            $msg = !SystemSetting::get('archive_deletion_password')
                ? 'Archive deletion password is not set. Please set it in the System Security tab.'
                : 'Invalid archive deletion password.';
            throw new \Exception($msg);
        }
    }

    // ─── Client Activity Monitoring ───────────────────────────────────────────

    public function activityMonitoring(Request $request)
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['super_admin', 'owner', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $date   = $request->get('date', now()->toDateString());
        $target = (int) $request->get('target', 4); // hours/day

        // 1. Fetch Owner and all Staff User accounts (excluding drivers)
        $users = User::where('role', '!=', 'driver')
            ->whereNull('deleted_at')
            ->orderByRaw("FIELD(role, 'super_admin', 'owner', 'manager', 'dispatcher', 'secretary', 'cashier', 'accountant', 'staff')")
            ->orderBy('full_name')
            ->get();

        $userIds = $users->pluck('id')->toArray();

        // 2. Fetch audit logs for the selected date
        $allAuditsToday = LoginAudit::whereDate('created_at', $date)
            ->orderBy('created_at')
            ->get();

        // 3. Fetch 60-day heatmap raw data
        $startDate = Carbon::parse($date)->subDays(59)->toDateString();
        $heatmapRaw = LoginAudit::whereBetween('created_at', [$startDate . ' 00:00:00', $date . ' 23:59:59'])
            ->selectRaw('user_id, DATE(created_at) as day, COUNT(*) as total_acts, SUM(CASE WHEN action = "login" THEN 1 ELSE 0 END) as logins, MIN(created_at) as first_act, MAX(created_at) as last_act')
            ->groupBy('user_id', 'day')
            ->get();

        $intervalMap = [];
        if (Schema::hasTable('user_activity_intervals')) {
            try {
                $intervalsRaw = \App\Models\UserActivityInterval::whereBetween('date', [$startDate, $date])
                    ->selectRaw('user_id, date, SUM(duration_seconds) as total_seconds')
                    ->groupBy('user_id', 'date')
                    ->get();
                foreach ($intervalsRaw as $ir) {
                    $intervalMap[$ir->user_id][$ir->date] = (int) round($ir->total_seconds / 60);
                }
            } catch (\Throwable $e) {}
        }

        $heatmapMap = [];
        foreach ($heatmapRaw as $row) {
            if ($row->user_id) {
                $heatmapMap[$row->user_id][$row->day] = [
                    'acts'      => (int) $row->total_acts,
                    'logins'    => (int) $row->logins,
                    'first_act' => $row->first_act,
                    'last_act'  => $row->last_act,
                ];
            }
        }

        // Helper to extract system module from action name
        $getModule = function(string $action): string {
            $act = strtolower($action);
            if (str_contains($act, 'boundary') || str_contains($act, 'remittance')) return 'Boundaries';
            if (str_contains($act, 'expense')) return 'Office Expenses';
            if (str_contains($act, 'maintenance') || str_contains($act, 'spare part')) return 'Maintenance';
            if (str_contains($act, 'driver') || str_contains($act, 'incentive')) return 'Driver Management';
            if (str_contains($act, 'incident') || str_contains($act, 'behavior')) return 'Driver Behavior';
            if (str_contains($act, 'franchise') || str_contains($act, 'case')) return 'Franchise';
            if (str_contains($act, 'unit') || str_contains($act, 'coding')) return 'Unit Management';
            if (str_contains($act, 'salary')) return 'Salary Management';
            if (str_contains($act, 'staff') || str_contains($act, 'role') || str_contains($act, 'access')) return 'Staff & Access';
            if (str_contains($act, 'login') || str_contains($act, 'logout')) return 'Authentication';
            return 'General Operations';
        };

        $userData = [];

        $presenceService = app(\App\Services\PresenceService::class);

        // Process User accounts
        foreach ($users as $user) {
            $userName = $user->full_name ?: ($user->name ?: 'User #' . $user->id);

            $userAudits = $allAuditsToday->where('user_id', $user->id)->values();

            // 1. Strict Server-Validated Presence Status ('active' 🟢, 'idle' 🟡, 'inactive' 🔴)
            $status = $presenceService->determineUserStatus($user);
            $isOnline = in_array($status, ['active', 'idle']);

            // 2. Accurate Accumulated Active Time for Today (Intervals + Live Continuous Elapsed Session)
            $activeTimeData = $presenceService->calculateTodayActiveTime($user->id, $date);
            $totalMins  = $activeTimeData['total_mins'];

            // Logins & Audits
            $loginEntries = $userAudits->where('action', 'login');
            $firstLogin   = $loginEntries->first();
            $lastEntry    = $userAudits->last();

            // Determine First Login & First Active
            $firstTimeObj = null;
            $firstLabel = 'First login';
            if ($firstLogin) {
                $firstTimeObj = Carbon::parse($firstLogin->created_at);
                $firstLabel = 'First login';
            } elseif ($user->last_login && Carbon::parse($user->last_login)->toDateString() === $date) {
                $firstTimeObj = Carbon::parse($user->last_login);
                $firstLabel = 'First login';
            } elseif (!empty($activeTimeData['intervals'])) {
                $firstTimeObj = Carbon::parse($activeTimeData['intervals'][0]['start']);
                $firstLabel = 'First active';
            } elseif ($userAudits->isNotEmpty()) {
                $firstTimeObj = Carbon::parse($userAudits->first()->created_at);
                $firstLabel = 'First active';
            } elseif ($user->last_seen_at && Carbon::parse($user->last_seen_at)->toDateString() === $date) {
                $firstTimeObj = Carbon::parse($user->last_seen_at);
                $firstLabel = 'First active';
            }

            // Determine Last Active / Seen Time Today
            $lastTimeObj = null;
            if ($user->last_seen_at && Carbon::parse($user->last_seen_at)->toDateString() === $date) {
                $lastTimeObj = Carbon::parse($user->last_seen_at);
            } elseif ($lastEntry) {
                $lastTimeObj = Carbon::parse($lastEntry->created_at);
            } elseif (!empty($activeTimeData['intervals'])) {
                $lastTimeObj = Carbon::parse(end($activeTimeData['intervals'])['end']);
            }

            $todayMidnight = Carbon::parse($date . ' 00:00:00');
            $todayEnd = Carbon::parse($date . ' 23:59:59');

            // Calculate live continuous elapsed active time if online, OR preserve accumulated session duration if offline
            if ($isOnline) {
                if ($firstTimeObj) {
                    $effectiveStart = $firstTimeObj->lt($todayMidnight) ? $todayMidnight : $firstTimeObj;
                    $effectiveNow = now()->gt($todayEnd) ? $todayEnd : now();
                    $elapsedSinceStart = max(1, (int) round($effectiveStart->diffInMinutes($effectiveNow)));
                    $totalMins = max($totalMins, $elapsedSinceStart);
                } else {
                    $totalMins = max(1, $totalMins);
                }
            } else {
                // If user was active/logged in today but is now offline, preserve the accumulated session time
                if ($firstTimeObj && $firstTimeObj->toDateString() === $date) {
                    if ($lastTimeObj && $lastTimeObj->gt($firstTimeObj)) {
                        $effectiveStart = $firstTimeObj->lt($todayMidnight) ? $todayMidnight : $firstTimeObj;
                        $effectiveEnd = $lastTimeObj->gt($todayEnd) ? $todayEnd : $lastTimeObj;
                        $sessionDuration = max(1, (int) round($effectiveStart->diffInMinutes($effectiveEnd)));
                        $totalMins = max($totalMins, $sessionDuration);
                    } else {
                        $totalMins = max($totalMins, 1);
                    }
                }
            }

            $totalHours = round($totalMins / 60, 2);
            $pct        = ($target > 0) ? (int) round(($totalMins / ($target * 60)) * 100) : 0;

            // 3. Meaningful Operational Actions (Excluding automated heartbeats & background checks)
            $meaningfulActs = $userAudits->whereNotIn('action', ['login', 'logout', 'failed_login', 'session_start', 'active_presence']);

            // Distinct operational modules accessed today
            $modules = [];
            foreach ($userAudits as $aud) {
                if (in_array($aud->action, ['login', 'logout', 'failed_login', 'session_start', 'active_presence'])) {
                    continue;
                }
                $mod = $getModule($aud->action);
                if (!in_array($mod, $modules) && $mod !== 'Authentication') {
                    $modules[] = $mod;
                }
            }
            if (empty($modules) && ($loginEntries->count() > 0 || $isOnline || $totalMins > 0)) {
                $modules[] = 'Dashboard Overview';
            }

            // 4. Formatted Strings
            $firstLoginStr = $firstTimeObj ? "{$firstLabel}: " . $firstTimeObj->format('h:i A') : null;

            $lastActiveStr = null;
            if ($isOnline) {
                $lastActiveStr = 'Online now (' . now()->format('h:i A') . ')';
            } elseif ($user->last_seen_at) {
                $lastActiveStr = Carbon::parse($user->last_seen_at)->format('M d, Y h:i A');
            } elseif ($lastEntry) {
                $lastActiveStr = Carbon::parse($lastEntry->created_at)->format('M d, Y h:i A');
            } elseif ($user->last_login) {
                $lastActiveStr = Carbon::parse($user->last_login)->format('M d, Y h:i A');
            }

            // Explicit Last Offline String
            $lastOfflineTime = $user->last_seen_at ? Carbon::parse($user->last_seen_at) : ($lastEntry ? Carbon::parse($lastEntry->created_at) : ($user->last_login ? Carbon::parse($user->last_login) : null));
            $lastOfflineStr = null;
            if ($lastOfflineTime) {
                if ($lastOfflineTime->toDateString() === $date) {
                    $lastOfflineStr = 'Last offline: ' . $lastOfflineTime->format('h:i A');
                } else {
                    $lastOfflineStr = 'Last offline: ' . $lastOfflineTime->format('M d, Y h:i A');
                }
            } else {
                $lastOfflineStr = 'Never logged in';
            }

            // Format sessions list for drill-down modal
            $sessions = [];
            foreach ($activeTimeData['intervals'] as $intv) {
                $sessions[] = [
                    'start'   => Carbon::parse($intv['start'])->format('h:i A'),
                    'end'     => Carbon::parse($intv['end'])->format('h:i A'),
                    'mins'    => max(1, (int) round($intv['seconds'] / 60)),
                    'actions' => 1,
                ];
            }

            // 60-day timeline heatmap (scrollable history)
            $heatmap = [];
            for ($i = 59; $i >= 0; $i--) {
                $dayStr = Carbon::parse($date)->subDays($i)->toDateString();
                $dayData = $heatmapMap[$user->id][$dayStr] ?? ['acts' => 0, 'logins' => 0, 'first_act' => null, 'last_act' => null];
                $actsCount = $dayData['acts'];
                $loginsCount = $dayData['logins'];

                if ($i === 0) {
                    $dayMins = $totalMins;
                    if (($isOnline || $totalMins > 0) && $actsCount === 0) {
                        $actsCount = max(1, $userAudits->count());
                        $loginsCount = max(1, $loginsCount);
                    }
                } else {
                    if (isset($intervalMap[$user->id][$dayStr]) && $intervalMap[$user->id][$dayStr] > 0) {
                        $dayMins = $intervalMap[$user->id][$dayStr];
                    } elseif (!empty($dayData['first_act']) && !empty($dayData['last_act'])) {
                        $fAct = Carbon::parse($dayData['first_act']);
                        $lAct = Carbon::parse($dayData['last_act']);
                        $dayMins = max(1, (int) round($fAct->diffInMinutes($lAct)));
                    } else {
                        $dayMins = $actsCount > 0 ? max(1, $actsCount * 3) : 0;
                    }
                }

                // Format hours/minutes for rich 21st.dev hover tooltip
                if ($dayMins <= 0) {
                    $hoursFormatted = '0h (Inactive)';
                } else {
                    $h = intdiv($dayMins, 60);
                    $m = $dayMins % 60;
                    $hoursFormatted = ($h > 0 && $m > 0) ? "{$h}h {$m}m" : (($h > 0) ? "{$h}h" : "{$m}m");
                }

                $heatmap[] = [
                    'date'            => $dayStr,
                    'date_formatted'  => Carbon::parse($dayStr)->format('l, M d, Y'),
                    'activities'      => $actsCount,
                    'logins'          => $loginsCount,
                    'mins'            => $dayMins,
                    'hours_formatted' => $hoursFormatted,
                ];
            }

            $userData[] = [
                'id'            => $user->id,
                'is_staff_row'  => false,
                'name'          => $userName,
                'email'         => $user->email,
                'role'          => $user->role,
                'role_label'    => $user->role === 'super_admin' ? 'Owner / Super Admin' : ucfirst(str_replace('_', ' ', $user->role)),
                'last_login'    => $user->last_login ? Carbon::parse($user->last_login)->format('M d, Y h:i A') : ($user->last_seen_at ? Carbon::parse($user->last_seen_at)->format('M d, Y h:i A') : null),
                'todayH'        => $totalHours,
                'todayMins'     => $totalMins,
                'pct'           => $pct,
                'sessions'      => count($sessions),
                'sessionList'   => $sessions,
                'activities'    => $meaningfulActs->count() > 0 ? $meaningfulActs->count() : ($totalMins > 0 ? 1 : 0),
                'meaningfulActs'=> $meaningfulActs->count(),
                'modules'       => $modules,
                'status'        => $status,
                'firstLogin'    => $firstLoginStr,
                'lastActive'    => $lastActiveStr,
                'lastOffline'   => $lastOfflineStr,
                'heatmap'       => $heatmap,
                'isOnline'      => $isOnline,
            ];
        }

        // Summary Aggregates
        $totalUsersCount = count($userData);
        $activeCount     = collect($userData)->whereIn('status', ['active', 'idle'])->count();
        $lowCount        = collect($userData)->where('status', 'low')->count();
        $noneCount       = collect($userData)->whereIn('status', ['inactive', 'none'])->count();
        $totalHoursSum   = collect($userData)->sum('todayH');
        $activeUsersCol  = collect($userData)->where('todayH', '>', 0);
        $avgHours        = $activeUsersCol->count() > 0 ? round($totalHoursSum / $activeUsersCol->count(), 1) : 0;
        $adoptionRate    = $totalUsersCount > 0 ? round(($activeCount / $totalUsersCount) * 100) : 0;
        $totalActsSum    = collect($userData)->sum('activities');
        $totalClients    = collect($userData)->pluck('role')->unique()->count();

        // Server-Side Attention Alerts (Offline / Inactive Staff)
        $alerts = [];
        foreach ($userData as $u) {
            if (!$u['isOnline'] && $u['todayMins'] === 0) {
                $lastSeen = $u['last_login'] ?: 'Never logged in';
                $alerts[] = [
                    'user_id'  => $u['id'],
                    'name'     => $u['name'],
                    'role'     => $u['role'],
                    'isOnline' => false,
                    'msg'      => "No active presence recorded today. Last seen: {$lastSeen}.",
                    'icon'     => 'alert-octagon',
                    'color'    => '#ef4444',
                    'bg'       => '#fff1f2',
                ];
            } elseif (!$u['isOnline'] && $u['todayMins'] > 0 && $u['todayMins'] < ($target * 60 * 0.6)) {
                $h = intdiv($u['todayMins'], 60);
                $m = $u['todayMins'] % 60;
                $fmt = ($h > 0 && $m > 0) ? "{$h}h {$m}m" : (($h > 0) ? "{$h}h" : "{$m}m");
                $alerts[] = [
                    'user_id'  => $u['id'],
                    'name'     => $u['name'],
                    'role'     => $u['role'],
                    'isOnline' => false,
                    'msg'      => "Low activity — {$fmt} of {$target}h target. {$u['activities']} interaction(s) recorded.",
                    'icon'     => 'alert-triangle',
                    'color'    => '#f59e0b',
                    'bg'       => '#fffbeb',
                ];
            }
        }

        return response()->json([
            'success' => true,
            'date'    => $date,
            'target'  => $target,
            'users'   => $userData,
            'alerts'  => $alerts,
            'summary' => [
                'total'       => $totalUsersCount,
                'active'      => $activeCount,
                'low'         => $lowCount,
                'none'        => $noneCount,
                'avgH'        => $avgHours,
                'adoption'    => $adoptionRate,
                'total_acts'  => $totalActsSum,
                'roles_count' => $totalClients,
            ],
        ]);
    }

    public function userActivityDetail(Request $request, $id)
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['super_admin', 'owner', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $date = $request->get('date', now()->toDateString());

        // Check if staff row
        if (str_starts_with((string)$id, 'staff_')) {
            $staffId = (int) str_replace('staff_', '', $id);
            $staff = \App\Models\Staff::withTrashed()->findOrFail($staffId);

            $audits = LoginAudit::where(function($q) use ($staff) {
                $q->where('user_name', 'like', '%' . $staff->name . '%')
                  ->orWhere('notes', 'like', '%' . $staff->name . '%');
            })->orderByDesc('created_at')->limit(50)->get();

            return response()->json([
                'success' => true,
                'user'    => [
                    'id'              => 'staff_' . $staff->id,
                    'name'            => $staff->name,
                    'email'           => $staff->phone ? 'Phone: ' . $staff->phone : 'General Staff',
                    'role'            => $staff->role ?: 'staff',
                    'role_label'      => ucfirst(str_replace('_', ' ', $staff->role ?: 'staff')),
                    'last_login'      => null,
                    'created_at'      => $staff->created_at ? Carbon::parse($staff->created_at)->format('M d, Y') : null,
                    'approval_status' => $staff->status ?: 'Active',
                    'is_disabled'     => false,
                ],
                'todayAudit' => $audits->where('created_at', '>=', $date . ' 00:00:00')->values(),
                'history'    => $audits,
            ]);
        }

        $user = User::withTrashed()->where('id', $id)->firstOrFail();

        // All audit for the selected day
        $todayAudits = LoginAudit::where('user_id', $id)
            ->whereDate('created_at', $date)
            ->orderBy('created_at')
            ->get();

        // Recent 50 audit entries for this user
        $history = LoginAudit::where('user_id', $id)
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        $presenceService = app(\App\Services\PresenceService::class);
        $status = $presenceService->determineUserStatus($user);
        $isOnline = in_array($status, ['active', 'idle']);
        $activeTimeData = $presenceService->calculateTodayActiveTime($user->id, $date);

        $todayMins = $activeTimeData['total_mins'];
        $firstLoginEntry = $todayAudits->where('action', 'login')->first();
        $firstTimeObj = $firstLoginEntry ? Carbon::parse($firstLoginEntry->created_at) : ($user->last_login && Carbon::parse($user->last_login)->toDateString() === $date ? Carbon::parse($user->last_login) : ($todayAudits->isNotEmpty() ? Carbon::parse($todayAudits->first()->created_at) : null));
        $lastTimeObj = $user->last_seen_at && Carbon::parse($user->last_seen_at)->toDateString() === $date ? Carbon::parse($user->last_seen_at) : ($todayAudits->isNotEmpty() ? Carbon::parse($todayAudits->last()->created_at) : null);

        if ($todayMins === 0 && $firstTimeObj) {
            $endPoint = $isOnline ? now() : ($lastTimeObj ?: $firstTimeObj);
            $todayMins = max(1, (int) round($firstTimeObj->diffInMinutes($endPoint)));
        }

        $aiSummary = \App\Services\ActivityAuditService::generateExecutiveSummary(
            $user,
            $date,
            $todayAudits,
            [
                'hours'     => round($todayMins / 60, 2),
                'mins'      => $todayMins,
                'is_online' => $isOnline,
                'status'    => $status,
            ]
        );

        return response()->json([
            'success'    => true,
            'user'       => [
                'id'              => $user->id,
                'name'            => $user->full_name ?? $user->name,
                'email'           => $user->email,
                'role'            => $user->role,
                'role_label'      => $user->role === 'super_admin' ? 'Owner / Super Admin' : ucfirst(str_replace('_', ' ', $user->role)),
                'last_login'      => $user->last_login ? Carbon::parse($user->last_login)->format('M d, Y h:i A') : null,
                'created_at'      => $user->created_at ? Carbon::parse($user->created_at)->format('M d, Y') : null,
                'approval_status' => $user->approval_status,
                'is_disabled'     => (bool) $user->is_disabled,
            ],
            'ai_summary' => $aiSummary,
            'todayAudit' => $todayAudits,
            'history'    => $history,
        ]);
    }

    /**
     * Reset/Purge activity history and intervals for a specific staff/user.
     */
    public function resetUserActivity(Request $request, $id)
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['super_admin', 'owner', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            if (str_starts_with((string)$id, 'staff_')) {
                $staffId = (int) str_replace('staff_', '', $id);
                $staff = \App\Models\Staff::withTrashed()->findOrFail($staffId);
                LoginAudit::where(function($q) use ($staff) {
                    $q->where('user_name', 'like', '%' . $staff->name . '%')
                      ->orWhere('notes', 'like', '%' . $staff->name . '%');
                })->delete();
            } else {
                $user = User::withTrashed()->findOrFail($id);
                
                // Delete all login audit entries for this user
                LoginAudit::where('user_id', $user->id)->delete();

                // Delete all activity intervals for this user if table exists
                if (Schema::hasTable('user_activity_intervals')) {
                    \App\Models\UserActivityInterval::where('user_id', $user->id)->delete();
                }

                // Delete presence connections if table exists
                if (Schema::hasTable('user_presence_connections')) {
                    \App\Models\UserPresenceConnection::where('user_id', $user->id)->delete();
                }

                // Reset today presence status on User record
                $user->update([
                    'is_online' => false,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Staff activity history has been permanently reset.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset activity: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store staff feedback submitted from sidebar widget.
     */
    public function submitStaffFeedback(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        $request->validate([
            'rating'   => 'required|string|in:very-sad,sad,neutral,happy',
            'feedback' => 'required|string|max:5000',
        ]);

        $labels = [
            'very-sad' => 'Terrible',
            'sad'      => 'Bad',
            'neutral'  => 'Okay',
            'happy'    => 'Amazing',
        ];

        try {
            $user = Auth::user();
            $imagePaths = [];

            // 1. Process base64 or file images from request
            if ($request->has('images') && is_array($request->images)) {
                $uploadDir = public_path('uploads/staff-feedbacks');
                if (!file_exists($uploadDir)) {
                    @mkdir($uploadDir, 0755, true);
                }

                foreach ($request->images as $idx => $imgData) {
                    if (empty($imgData)) continue;

                    // If it's a base64 string: data:image/png;base64,xxxx
                    if (is_string($imgData) && preg_match('/^data:image\/(\w+);base64,/', $imgData, $type)) {
                        $imgContent = substr($imgData, strpos($imgData, ',') + 1);
                        $ext = strtolower($type[1]);
                        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                            $ext = 'png';
                        }
                        $decoded = base64_decode($imgContent);
                        if ($decoded !== false) {
                            $fileName = 'feedback_' . $user->id . '_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                            file_put_contents($uploadDir . '/' . $fileName, $decoded);
                            $imagePaths[] = '/uploads/staff-feedbacks/' . $fileName;
                        }
                    } elseif (is_string($imgData) && (str_starts_with($imgData, 'http') || str_starts_with($imgData, '/uploads/'))) {
                        $imagePaths[] = $imgData;
                    }
                }
            }

            // Also check for standard multipart file uploads
            if ($request->hasFile('files')) {
                $uploadDir = public_path('uploads/staff-feedbacks');
                if (!file_exists($uploadDir)) {
                    @mkdir($uploadDir, 0755, true);
                }
                foreach ($request->file('files') as $file) {
                    if ($file->isValid()) {
                        $fileName = 'feedback_' . $user->id . '_' . time() . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
                        $file->move($uploadDir, $fileName);
                        $imagePaths[] = '/uploads/staff-feedbacks/' . $fileName;
                    }
                }
            }

            $feedback = StaffFeedback::create([
                'user_id'      => $user->id,
                'user_name'    => $user->full_name ?? ($user->first_name ? trim($user->first_name . ' ' . $user->last_name) : $user->name),
                'user_email'   => $user->email,
                'user_role'    => $user->role,
                'rating'       => $request->rating,
                'rating_label' => $labels[$request->rating] ?? ucfirst($request->rating),
                'feedback'     => trim($request->feedback),
                'images'       => !empty($imagePaths) ? $imagePaths : null,
                'page_url'     => $request->page_url ?: url()->previous(),
                'status'       => 'new',
                'ip_address'   => $request->ip(),
            ]);

            return response()->json([
                'success'  => true,
                'message'  => 'Thank you! Your feedback with screenshot attachments has been received.',
                'feedback' => $feedback,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit feedback: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get list of all staff feedback entries.
     */
    public function getStaffFeedbacks(Request $request)
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['super_admin', 'owner', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $query = StaffFeedback::query()->orderByDesc('created_at');

            if ($request->filled('rating') && $request->rating !== 'all') {
                $query->where('rating', $request->rating);
            }

            if ($request->filled('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            $feedbacks = $query->limit(200)->get()->map(function($f) {
                return [
                    'id'           => $f->id,
                    'user_id'      => $f->user_id,
                    'user_name'    => $f->user_name ?: 'Staff Member',
                    'user_email'   => $f->user_email,
                    'user_role'    => $f->user_role ?: 'staff',
                    'rating'       => $f->rating,
                    'rating_label' => $f->rating_label ?: ucfirst($f->rating),
                    'feedback'     => $f->feedback,
                    'images'       => is_array($f->images) ? $f->images : (is_string($f->images) ? json_decode($f->images, true) : []),
                    'page_url'     => $f->page_url,
                    'status'       => $f->status,
                    'created_at'   => $f->created_at ? $f->created_at->format('M d, Y h:i A') : null,
                    'time_ago'     => $f->created_at ? $f->created_at->diffForHumans() : '',
                ];
            });

            return response()->json([
                'success'   => true,
                'feedbacks' => $feedbacks,
                'counts'    => [
                    'total'    => StaffFeedback::count(),
                    'amazing'  => StaffFeedback::where('rating', 'happy')->count(),
                    'okay'     => StaffFeedback::where('rating', 'neutral')->count(),
                    'bad'      => StaffFeedback::where('rating', 'sad')->count(),
                    'terrible' => StaffFeedback::where('rating', 'very-sad')->count(),
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'feedbacks' => [], 'counts' => ['total' => 0, 'amazing' => 0, 'okay' => 0, 'bad' => 0, 'terrible' => 0]], 500);
        }
    }

    /**
     * Update feedback status (reviewed, resolved, new).
     */
    public function updateFeedbackStatus(Request $request, $id)
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['super_admin', 'owner', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|string|in:new,reviewed,resolved',
        ]);

        $fb = StaffFeedback::findOrFail($id);
        $fb->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Feedback status updated to ' . ucfirst($request->status) . '.',
        ]);
    }

    /**
     * Delete a feedback record.
     */
    public function deleteFeedback(Request $request, $id)
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['super_admin', 'owner', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $fb = StaffFeedback::findOrFail($id);
        $fb->delete();

        return response()->json([
            'success' => true,
            'message' => 'Feedback record has been deleted.',
        ]);
    }
}

