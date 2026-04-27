@extends('layouts.app')

@section('page-heading', 'Owner Control Center')
@section('page-subheading', 'System administration, user management & security audit — Super Admin only')

@push('styles')
<style>
    /* ── Premium dark glass palette ── */
    :root {
        --sa-bg:       #0f1117;
        --sa-surface:  #1a1d27;
        --sa-card:     #20243a;
        --sa-border:   rgba(255,255,255,.07);
        --sa-gold:     #f59e0b;
        --sa-gold-dim: #92400e;
        --sa-teal:     #14b8a6;
        --sa-purple:   #8b5cf6;
        --sa-red:      #ef4444;
        --sa-green:    #22c55e;
        --sa-text:     #e2e8f0;
        --sa-muted:    #64748b;
    }

    .sa-shell {
        background: var(--sa-bg);
        min-height: calc(100vh - 60px);
        color: var(--sa-text);
        font-family: 'Inter', sans-serif;
    }

    /* ── Tabs ── */
    .sa-tab-bar { border-bottom: 1px solid var(--sa-border); }
    .sa-tab {
        padding: .6rem 1.25rem;
        font-size: .78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: var(--sa-muted);
        cursor: pointer;
        border-bottom: 2px solid transparent;
        transition: all .2s;
        white-space: nowrap;
    }
    .sa-tab.active, .sa-tab:hover { color: var(--sa-gold); border-color: var(--sa-gold); }

    /* ── Stat cards ── */
    .sa-stat {
        background: linear-gradient(135deg, var(--sa-card) 0%, rgba(32,36,58,.6) 100%);
        border: 1px solid var(--sa-border);
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        position: relative;
        overflow: hidden;
        transition: transform .2s, box-shadow .2s;
    }
    .sa-stat:hover { transform: translateY(-2px); box-shadow: 0 10px 40px rgba(0,0,0,.4); }
    .sa-stat::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 120px; height: 120px;
        border-radius: 50%;
        opacity: .06;
        background: currentColor;
    }

    /* ── Tables ── */
    .sa-table { width: 100%; border-collapse: collapse; }
    .sa-table th {
        background: rgba(255,255,255,.03);
        color: var(--sa-muted);
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .07em;
        padding: .75rem 1rem;
        text-align: left;
        border-bottom: 1px solid var(--sa-border);
    }
    .sa-table td {
        padding: .85rem 1rem;
        font-size: .825rem;
        border-bottom: 1px solid rgba(255,255,255,.04);
        vertical-align: middle;
    }
    .sa-table tbody tr:hover { background: rgba(255,255,255,.025); }

    /* ── Badges ── */
    .badge-pending  { background:#78350f33; color:#fbbf24; border:1px solid #92400e; }
    .badge-approved { background:#14532d33; color:#4ade80; border:1px solid #166534; }
    .badge-rejected { background:#7f1d1d33; color:#f87171; border:1px solid #991b1b; }
    .badge-login    { background:#1e3a5f33; color:#60a5fa; border:1px solid #1e40af; }
    .badge-logout   { background:#1f2937; color:#9ca3af; border:1px solid #374151; }
    .badge-failed   { background:#7f1d1d33; color:#f87171; border:1px solid #991b1b; }
    .badge-role-super_admin { background:#4c1d9533; color:#c4b5fd; border:1px solid #7c3aed; }
    .badge-role-manager     { background:#1e3a5f33; color:#60a5fa; border:1px solid #1e40af; }
    .badge-role-dispatcher  { background:#134e4a33; color:#2dd4bf; border:1px solid #0f766e; }
    .badge-role-secretary   { background:#1e1b4b33; color:#a5b4fc; border:1px solid #4338ca; }
    .badge-role-staff       { background:#1f293733; color:#94a3b8; border:1px solid #334155; }

    .badge {
        display: inline-flex; align-items: center; gap: .3rem;
        padding: .2rem .65rem;
        border-radius: 999px;
        font-size: .67rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
    }

    /* ── Buttons ── */
    .btn-approve { background:#166534; color:#4ade80; border:1px solid #15803d; border-radius:.5rem; padding:.3rem .9rem; font-size:.72rem; font-weight:700; cursor:pointer; transition:all .2s; }
    .btn-approve:hover { background:#15803d; }
    .btn-reject  { background:#7f1d1d; color:#f87171; border:1px solid #991b1b; border-radius:.5rem; padding:.3rem .9rem; font-size:.72rem; font-weight:700; cursor:pointer; transition:all .2s; }
    .btn-reject:hover  { background:#991b1b; }
    .btn-ghost   { background:rgba(255,255,255,.05); color:var(--sa-muted); border:1px solid var(--sa-border); border-radius:.5rem; padding:.3rem .9rem; font-size:.72rem; font-weight:700; cursor:pointer; transition:all .2s; }
    .btn-ghost:hover   { background:rgba(255,255,255,.1); color:var(--sa-text); }
    .btn-gold   { background:var(--sa-gold); color:#1c1917; border:0; border-radius:.5rem; padding:.35rem 1rem; font-size:.72rem; font-weight:800; cursor:pointer; transition:all .2s; }
    .btn-gold:hover   { background:#fbbf24; }
    .btn-danger { background:#7f1d1d; color:#f87171; border:1px solid #991b1b; border-radius:.5rem; padding:.3rem .9rem; font-size:.72rem; font-weight:700; cursor:pointer; transition:all .2s; }

    /* ── Search & inputs ── */
    .sa-input {
        background: rgba(255,255,255,.06);
        border: 1px solid var(--sa-border);
        color: var(--sa-text);
        border-radius: .6rem;
        padding: .5rem 1rem;
        font-size: .82rem;
        outline: none;
        transition: border-color .2s;
        width: 100%;
    }
    .sa-input:focus { border-color: var(--sa-gold); }

    /* ── Page access toggle chips ── */
    .page-chip {
        cursor: pointer;
        padding: .3rem .75rem;
        border-radius: .45rem;
        font-size: .68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        border: 1px solid var(--sa-border);
        background: rgba(255,255,255,.04);
        color: var(--sa-muted);
        transition: all .2s;
        user-select: none;
    }
    .page-chip.active { background: #78350f44; color: var(--sa-gold); border-color: #92400e; }
    .page-chip:hover  { border-color: var(--sa-gold); color: var(--sa-gold); }

    /* ── Audit timeline dot ── */
    .audit-dot-login  { background: #3b82f6; }
    .audit-dot-logout { background: #6b7280; }
    .audit-dot-failed_login { background: #ef4444; }
    .audit-dot-approved { background: #22c55e; }
    .audit-dot-rejected { background: #a855f7; }

    /* ── Toast notification ── */
    #sa-toast {
        position: fixed; bottom: 1.5rem; left: 50%; transform: translateX(-50%) translateY(4rem);
        background: #1e293b; border: 1px solid var(--sa-gold); color: var(--sa-text);
        padding: .75rem 1.5rem; border-radius: .75rem; font-size: .83rem; font-weight: 600;
        box-shadow: 0 8px 32px rgba(0,0,0,.5);
        z-index: 9999; transition: transform .3s cubic-bezier(.34,1.56,.64,1);
        max-width: 90vw; text-align: center;
    }
    #sa-toast.show { transform: translateX(-50%) translateY(0); }
    #sa-toast.error { border-color: #ef4444; }

    /* ── Modal ── */
    .sa-modal-backdrop {
        position: fixed; inset: 0; background: rgba(0,0,0,.75); backdrop-filter: blur(4px);
        z-index: 9990; display: none; align-items: center; justify-content: center;
    }
    .sa-modal-backdrop.open { display: flex; }
    .sa-modal {
        background: var(--sa-card); border: 1px solid var(--sa-border); border-radius: 1.25rem;
        padding: 2rem; width: 100%; max-width: 520px; max-height: 90vh; overflow-y: auto;
        box-shadow: 0 24px 80px rgba(0,0,0,.7);
        animation: modal-in .25s ease;
    }
    @keyframes modal-in { from { opacity:0; transform:scale(.94) translateY(1rem); } to { opacity:1; transform:none; } }

    /* ── Scrollbar ── */
    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #374151; border-radius: 99px; }
</style>
@endpush

@section('content')
@php
    use App\Http\Controllers\SuperAdminController;
    $pages = SuperAdminController::$pageDefinitions;
    
    // Grouping manually to ensure we keep the associative keys (route patterns)
    $groups = [];
    foreach ($pages as $pattern => $def) {
        $g = $def['group'] ?? 'Other';
        $groups[$g][$pattern] = $def;
    }
@endphp

<div class="sa-shell p-0">

    {{-- ══ Header Banner ══ --}}
    <div style="background: linear-gradient(135deg, #0f1117 0%, #1a1127 50%, #0f1117 100%); border-bottom: 1px solid rgba(245,158,11,.15);" class="px-6 pt-5 pb-0">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-4">
                <div style="background:linear-gradient(135deg,#f59e0b,#d97706); width:52px; height:52px; border-radius:1rem;" class="flex items-center justify-center shadow-lg flex-shrink-0">
                    <i data-lucide="crown" style="width:26px;height:26px;color:#1c1917;"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2 mb-0.5">
                        <h1 style="color:#f59e0b; font-size:1.35rem; font-weight:900; letter-spacing:-.02em;">Owner Control Center</h1>
                        <span class="badge badge-role-super_admin">Super Admin</span>
                    </div>
                    <p style="color:#64748b; font-size:.8rem;">Welcome back, <strong style="color:#e2e8f0;">{{ auth()->user()->full_name }}</strong> · Full system access</p>
                </div>
            </div>
            <div class="hidden lg:flex items-center gap-3 text-right">
                <div>
                    <p style="color:#f59e0b; font-size:1.4rem; font-weight:900;">{{ $pendingUsers }}</p>
                    <p style="color:#64748b; font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Pending</p>
                </div>
                <div style="width:1px; height:36px; background:rgba(255,255,255,.08);"></div>
                <div>
                    <p style="color:#4ade80; font-size:1.4rem; font-weight:900;">{{ $activeUsers }}</p>
                    <p style="color:#64748b; font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Active</p>
                </div>
                <div style="width:1px; height:36px; background:rgba(255,255,255,.08);"></div>
                <div>
                    <p style="color:#e2e8f0; font-size:1.4rem; font-weight:900;">{{ $totalUsers }}</p>
                    <p style="color:#64748b; font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Total Users</p>
                </div>
            </div>
        </div>

        {{-- Tab Bar --}}
        <div class="sa-tab-bar flex gap-1 overflow-x-auto">
            <button class="sa-tab {{ $tab === 'overview' ? 'active' : '' }}" onclick="switchTab('overview')">
                <i data-lucide="layout-dashboard" class="inline w-3.5 h-3.5 mr-1 -mt-0.5"></i>Overview
            </button>
            <button class="sa-tab {{ $tab === 'pending' ? 'active' : '' }}" onclick="switchTab('pending')">
                <i data-lucide="clock" class="inline w-3.5 h-3.5 mr-1 -mt-0.5"></i>Pending Approvals
                @if($pendingUsers > 0)
                    <span style="background:#f59e0b; color:#1c1917; font-size:.6rem; padding:.05rem .4rem; border-radius:999px; margin-left:.3rem;">{{ $pendingUsers }}</span>
                @endif
            </button>
            <button class="sa-tab {{ $tab === 'users' ? 'active' : '' }}" onclick="switchTab('users')">
                <i data-lucide="users" class="inline w-3.5 h-3.5 mr-1 -mt-0.5"></i>All Users
            </button>
            <button class="sa-tab {{ $tab === 'access' ? 'active' : '' }}" onclick="switchTab('access')">
                <i data-lucide="shield-check" class="inline w-3.5 h-3.5 mr-1 -mt-0.5"></i>Page Access
            </button>
            <button class="sa-tab {{ $tab === 'audit' ? 'active' : '' }}" onclick="switchTab('audit')">
                <i data-lucide="activity" class="inline w-3.5 h-3.5 mr-1 -mt-0.5"></i>Login History
            </button>
        </div>
    </div>

    {{-- ══ Tab Content ══ --}}
    <div class="p-6">

        {{-- ─── OVERVIEW TAB ─── --}}
        <div id="tab-overview" class="sa-tab-content {{ $tab === 'overview' ? '' : 'hidden' }}">
            {{-- Stat Row --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="sa-stat" style="color:#f59e0b;">
                    <div class="flex items-center justify-between mb-3">
                        <span style="font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:#92400e;">Total Staff</span>
                        <div style="background:rgba(245,158,11,.12); padding:.45rem; border-radius:.6rem;">
                            <i data-lucide="users" style="width:16px;height:16px;color:#f59e0b;"></i>
                        </div>
                    </div>
                    <p style="font-size:2.2rem; font-weight:900; line-height:1; color:#f59e0b;">{{ $totalUsers }}</p>
                    <p style="font-size:.7rem; color:#64748b; margin-top:.4rem;">Registered accounts</p>
                </div>
                <div class="sa-stat" style="color:#fbbf24;">
                    <div class="flex items-center justify-between mb-3">
                        <span style="font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:#92400e;">Pending</span>
                        <div style="background:rgba(251,191,36,.12); padding:.45rem; border-radius:.6rem;">
                            <i data-lucide="clock" style="width:16px;height:16px;color:#fbbf24;"></i>
                        </div>
                    </div>
                    <p style="font-size:2.2rem; font-weight:900; line-height:1; color:#fbbf24;">{{ $pendingUsers }}</p>
                    <p style="font-size:.7rem; color:#64748b; margin-top:.4rem;">Awaiting your approval</p>
                </div>
                <div class="sa-stat" style="color:#22c55e;">
                    <div class="flex items-center justify-between mb-3">
                        <span style="font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:#166534;">Active</span>
                        <div style="background:rgba(34,197,94,.12); padding:.45rem; border-radius:.6rem;">
                            <i data-lucide="check-circle" style="width:16px;height:16px;color:#22c55e;"></i>
                        </div>
                    </div>
                    <p style="font-size:2.2rem; font-weight:900; line-height:1; color:#22c55e;">{{ $activeUsers }}</p>
                    <p style="font-size:.7rem; color:#64748b; margin-top:.4rem;">Approved & active</p>
                </div>
                <div class="sa-stat" style="color:#f87171;">
                    <div class="flex items-center justify-between mb-3">
                        <span style="font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:#991b1b;">Rejected</span>
                        <div style="background:rgba(239,68,68,.12); padding:.45rem; border-radius:.6rem;">
                            <i data-lucide="x-circle" style="width:16px;height:16px;color:#f87171;"></i>
                        </div>
                    </div>
                    <p style="font-size:2.2rem; font-weight:900; line-height:1; color:#f87171;">{{ $rejectedUsers }}</p>
                    <p style="font-size:.7rem; color:#64748b; margin-top:.4rem;">Denied access</p>
                </div>
            </div>

            {{-- Recent Activity --}}
            <div style="background:var(--sa-card); border:1px solid var(--sa-border); border-radius:1rem; overflow:hidden;">
                <div style="padding:1rem 1.25rem; border-bottom:1px solid var(--sa-border); display:flex; align-items:center; gap:.6rem;">
                    <i data-lucide="activity" style="width:15px;height:15px;color:#f59e0b;"></i>
                    <span style="font-size:.75rem; font-weight:800; text-transform:uppercase; letter-spacing:.07em; color:#94a3b8;">Recent Login Activity</span>
                </div>
                <div style="overflow-x:auto;">
                    <table class="sa-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Action</th>
                                <th>IP Address</th>
                                <th>When</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAudit as $audit)
                            <tr>
                                <td>
                                    <div style="font-weight:700; color:#e2e8f0;">{{ $audit->user_name ?? 'Unknown' }}</div>
                                    <div style="font-size:.72rem; color:#64748b;">{{ $audit->user_email ?? '' }}</div>
                                </td>
                                <td>
                                    @php
                                        $aMap = ['login' => ['badge-login','Login'], 'logout' => ['badge-logout','Logout'], 'failed_login' => ['badge-failed','Failed Login'], 'approved' => ['badge-approved','Approved'], 'rejected' => ['badge-rejected','Rejected']];
                                        [$cls,$lbl] = $aMap[$audit->action] ?? ['badge-logout', $audit->action];
                                    @endphp
                                    <span class="badge {{ $cls }}">{{ $lbl }}</span>
                                </td>
                                <td style="color:#64748b; font-family:monospace; font-size:.78rem;">{{ $audit->ip_address ?? '—' }}</td>
                                <td style="color:#64748b; font-size:.78rem;" title="{{ $audit->created_at }}">{{ \Carbon\Carbon::parse($audit->created_at)->diffForHumans() }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" style="text-align:center; color:#64748b; padding:2rem;">No activity recorded yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ─── PENDING APPROVALS TAB ─── --}}
        <div id="tab-pending" class="sa-tab-content {{ $tab === 'pending' ? '' : 'hidden' }}">
            @php $pending = $allUsers->where('approval_status', 'pending'); @endphp
            @if($pending->isEmpty())
                <div style="text-align:center; padding:4rem 2rem; color:#64748b;">
                    <i data-lucide="check-circle" style="width:48px;height:48px;color:#22c55e;margin:0 auto 1rem;display:block;"></i>
                    <p style="font-weight:700; font-size:1rem; color:#94a3b8;">All Clear!</p>
                    <p style="font-size:.85rem; margin-top:.5rem;">No accounts are pending approval at this time.</p>
                </div>
            @else
                <div style="background:rgba(245,158,11,.08); border:1px solid rgba(245,158,11,.25); border-radius:.75rem; padding:.85rem 1.25rem; margin-bottom:1.25rem; display:flex; align-items:center; gap:.75rem;">
                    <i data-lucide="alert-circle" style="width:16px;height:16px;color:#f59e0b; flex-shrink:0;"></i>
                    <p style="font-size:.8rem; color:#fbbf24; font-weight:600;">{{ $pending->count() }} account(s) are awaiting your approval. These users cannot log in until you approve them.</p>
                </div>
                <div style="background:var(--sa-card); border:1px solid var(--sa-border); border-radius:1rem; overflow:hidden;">
                    <table class="sa-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Registered</th>
                                <th style="text-align:right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pending as $u)
                            <tr id="pending-row-{{ $u->id }}">
                                <td>
                                    <div class="flex items-center gap-2.5">
                                        <div style="width:34px;height:34px;background:linear-gradient(135deg,#f59e0b,#d97706);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:900;font-size:.8rem;color:#1c1917;flex-shrink:0;">
                                            {{ strtoupper(substr($u->full_name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div style="font-weight:700;color:#e2e8f0;">{{ $u->full_name }}</div>
                                            <div style="font-size:.7rem;color:#64748b;">{{ $u->phone_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="color:#94a3b8; font-size:.8rem;">{{ $u->email }}</td>
                                <td><span class="badge badge-role-{{ $u->role }}">{{ ucfirst($u->role) }}</span></td>
                                <td style="color:#64748b; font-size:.78rem;">{{ \Carbon\Carbon::parse($u->created_at)->format('M d, Y h:i A') }}</td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <button class="btn-approve" onclick="approveUser({{ $u->id }}, '{{ $u->full_name }}')">
                                            <i data-lucide="check" class="inline w-3 h-3 mr-0.5"></i>Approve
                                        </button>
                                        <button class="btn-reject" onclick="rejectUser({{ $u->id }}, '{{ $u->full_name }}')">
                                            <i data-lucide="x" class="inline w-3 h-3 mr-0.5"></i>Reject
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- ─── ALL USERS TAB ─── --}}
        <div id="tab-users" class="sa-tab-content {{ $tab === 'users' ? '' : 'hidden' }}">
            <div class="flex items-center gap-3 mb-4 flex-wrap">
                <input type="text" id="userSearch" class="sa-input" style="max-width:280px;" placeholder="Search name, email, role…" oninput="filterUserTable(this.value)">
                <select id="statusFilter" class="sa-input" style="max-width:180px;" onchange="filterUserTable()">
                    <option value="">All Statuses</option>
                    <option value="approved">Approved</option>
                    <option value="pending">Pending</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div style="background:var(--sa-card); border:1px solid var(--sa-border); border-radius:1rem; overflow:hidden;">
                <table class="sa-table" id="userTable">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Active</th>
                            <th>Last Login</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allUsers as $u)
                        <tr class="user-row" data-name="{{ strtolower($u->full_name) }}" data-email="{{ strtolower($u->email) }}" data-role="{{ strtolower($u->role) }}" data-status="{{ $u->approval_status }}">
                            <td>
                                <div class="flex items-center gap-2.5">
                                    @if($u->profile_image)
                                        <img src="{{ asset('storage/' . $u->profile_image) }}" style="width:34px;height:34px;border-radius:50%;object-fit:cover;border:2px solid rgba(245,158,11,.3);" alt="">
                                    @else
                                        <div style="width:34px;height:34px;background:linear-gradient(135deg,#334155,#1e293b);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:900;font-size:.8rem;color:#94a3b8;flex-shrink:0;">
                                            {{ strtoupper(substr($u->full_name ?? 'U', 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div style="font-weight:700;color:#e2e8f0;">{{ $u->full_name }}</div>
                                        <div style="font-size:.7rem;color:#64748b;">{{ $u->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-role-{{ $u->role }}">{{ ucfirst(str_replace('_', ' ', $u->role)) }}</span></td>
                            <td>
                                @if($u->trashed())
                                    <span class="badge badge-rejected">Archived</span>
                                @elseif($u->approval_status === 'approved')
                                    <span class="badge badge-approved">Approved</span>
                                @elseif($u->approval_status === 'pending')
                                    <span class="badge badge-pending">Pending</span>
                                @else
                                    <span class="badge badge-rejected">Rejected</span>
                                @endif
                            </td>
                            <td>
                                <button onclick="toggleActive({{ $u->id }}, this)" style="background:{{ $u->is_active ? 'rgba(34,197,94,.15)' : 'rgba(239,68,68,.1)' }};border:1px solid {{ $u->is_active ? '#166534' : '#991b1b' }};color:{{ $u->is_active ? '#4ade80' : '#f87171' }};border-radius:999px;padding:.2rem .75rem;font-size:.68rem;font-weight:800;cursor:pointer;" data-id="{{ $u->id }}" data-active="{{ $u->is_active ? 1 : 0 }}">
                                    {{ $u->is_active ? '● Active' : '○ Inactive' }}
                                </button>
                            </td>
                            <td style="color:#64748b; font-size:.78rem;">
                                {{ $u->last_login ? \Carbon\Carbon::parse($u->last_login)->format('M d, Y h:i A') : 'Never' }}
                            </td>
                            <td>
                                <div class="flex justify-end gap-1.5">
                                    @if($u->trashed())
                                        <button class="btn-approve" onclick="restoreUser({{ $u->id }}, '{{ $u->full_name }}')">Restore</button>
                                    @else
                                        <button class="btn-ghost" onclick="openRoleModal({{ $u->id }}, '{{ addslashes($u->full_name) }}', '{{ $u->role }}')">
                                            <i data-lucide="user-plus" class="inline w-3 h-3 mr-0.5"></i>Role
                                        </button>
                                        <button class="btn-ghost" onclick="openPasswordModal({{ $u->id }}, '{{ addslashes($u->full_name) }}')">
                                            <i data-lucide="key" class="inline w-3 h-3 mr-0.5"></i>PW
                                        </button>
                                        @if($u->approval_status === 'pending')
                                            <button class="btn-approve" onclick="approveUser({{ $u->id }}, '{{ $u->full_name }}')">Approve</button>
                                        @endif
                                        <button class="btn-danger" onclick="deleteUser({{ $u->id }}, '{{ $u->full_name }}')">
                                            <i data-lucide="trash-2" class="inline w-3 h-3"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ─── PAGE ACCESS TAB ─── --}}
        <div id="tab-access" class="sa-tab-content {{ $tab === 'access' ? '' : 'hidden' }}">
            <div style="background:rgba(20,184,166,.08); border:1px solid rgba(20,184,166,.2); border-radius:.75rem; padding:.85rem 1.25rem; margin-bottom:1.5rem; display:flex; align-items:center; gap:.75rem;">
                <i data-lucide="info" style="width:15px;height:15px;color:#14b8a6;flex-shrink:0;"></i>
                <p style="font-size:.78rem; color:#5eead4; font-weight:600;">Click a user below, then toggle which pages they can access. Leave all unchecked to grant full access.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                {{-- User Picker --}}
                <div style="background:var(--sa-card); border:1px solid var(--sa-border); border-radius:1rem; overflow:hidden;">
                    <div style="padding:.85rem 1rem; border-bottom:1px solid var(--sa-border); font-size:.72rem; font-weight:800; text-transform:uppercase; letter-spacing:.07em; color:#64748b;">
                        Select User
                    </div>
                    <div style="max-height:460px; overflow-y:auto;">
                        @foreach($allUsers->where('approval_status', 'approved') as $u)
                        <div class="access-user-item" data-id="{{ $u->id }}" data-allowed="{{ json_encode($u->allowed_pages ?? null) }}"
                             onclick="selectAccessUser(this)"
                             style="padding:.85rem 1rem; cursor:pointer; border-bottom:1px solid rgba(255,255,255,.04); display:flex; align-items:center; gap:.75rem; transition:background .15s;">
                            <div style="width:32px;height:32px;background:linear-gradient(135deg,#334155,#1e293b);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:900;font-size:.75rem;color:#94a3b8;flex-shrink:0;">
                                {{ strtoupper(substr($u->full_name ?? 'U', 0, 1)) }}
                            </div>
                            <div style="flex:1; min-width:0;">
                                <div style="font-weight:700;color:#e2e8f0;font-size:.82rem; truncate;">{{ $u->full_name }}</div>
                                <div style="font-size:.68rem;color:#64748b;">{{ ucfirst($u->role) }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Page Picker --}}
                <div class="lg:col-span-2" style="background:var(--sa-card); border:1px solid var(--sa-border); border-radius:1rem; overflow:hidden;">
                    <div style="padding:.85rem 1rem; border-bottom:1px solid var(--sa-border); display:flex; align-items:center; justify-between;">
                        <div>
                            <span style="font-size:.72rem; font-weight:800; text-transform:uppercase; letter-spacing:.07em; color:#64748b;">Page Permissions</span>
                            <span id="access-user-name" style="font-size:.8rem; color:#f59e0b; font-weight:700; margin-left:.6rem;"></span>
                        </div>
                        <div class="flex gap-2 ml-auto">
                            <button class="btn-ghost" onclick="selectAllPages()" style="font-size:.68rem;">Select All</button>
                            <button class="btn-ghost" onclick="clearAllPages()" style="font-size:.68rem;">Clear All</button>
                            <button class="btn-gold" onclick="savePageAccess()" id="save-access-btn" disabled style="opacity:.4; font-size:.72rem;">
                                <i data-lucide="save" class="inline w-3 h-3 mr-1"></i>Save Access
                            </button>
                        </div>
                    </div>
                    <div id="page-picker-body" style="padding:1.25rem;">
                        <div style="text-align:center; color:#64748b; padding:2rem;" id="access-placeholder">
                            <i data-lucide="mouse-pointer-click" style="width:36px;height:36px;margin:0 auto .75rem;display:block;opacity:.4;"></i>
                            <p style="font-size:.82rem;">Select a user on the left to manage their page access.</p>
                        </div>
                        <div id="page-chips-container" class="hidden">
                            @foreach($groups as $groupName => $groupPages)
                            <div class="mb-4">
                                <p style="font-size:.68rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:#4b5563; margin-bottom:.6rem;">{{ $groupName }}</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($groupPages as $routePattern => $pageDef)
                                    <button class="page-chip" data-route="{{ $routePattern }}" onclick="togglePageChip(this)">
                                        <i data-lucide="{{ $pageDef['icon'] }}" class="inline" style="width:10px;height:10px;margin-right:.2rem;"></i>
                                        {{ $pageDef['label'] }}
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                            <div style="margin-top:1rem; padding-top:1rem; border-top:1px solid var(--sa-border); font-size:.72rem; color:#64748b;">
                                <i data-lucide="info" class="inline w-3 h-3 mr-1"></i>
                                <strong style="color:#94a3b8;">No chips selected</strong> = Full access (no restriction). Select specific pages to restrict this user.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── AUDIT LOG TAB ─── --}}
        <div id="tab-audit" class="sa-tab-content {{ $tab === 'audit' ? '' : 'hidden' }}">
            {{-- Filters --}}
            <div class="flex flex-wrap gap-3 mb-5">
                <input type="text" id="auditSearch" class="sa-input" style="max-width:240px;" placeholder="Name, email, IP…">
                <select id="auditActionFilter" class="sa-input" style="max-width:160px;">
                    <option value="">All Actions</option>
                    <option value="login">Login</option>
                    <option value="logout">Logout</option>
                    <option value="failed_login">Failed Login</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
                <select id="auditRoleFilter" class="sa-input" style="max-width:160px;">
                    <option value="">All Roles</option>
                    <option value="dispatcher">Dispatcher</option>
                    <option value="manager">Manager</option>
                    <option value="secretary">Secretary</option>
                    <option value="staff">Staff</option>
                    <option value="super_admin">Super Admin</option>
                </select>
                <button class="btn-gold" onclick="loadAuditLog(1)">
                    <i data-lucide="search" class="inline w-3 h-3 mr-1"></i>Search
                </button>
            </div>

            <div style="background:var(--sa-card); border:1px solid var(--sa-border); border-radius:1rem; overflow:hidden;">
                <div id="audit-table-container">
                    <table class="sa-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Role</th>
                                <th>Action</th>
                                <th>IP Address</th>
                                <th>Browser / Device</th>
                                <th>Notes</th>
                                <th>Date & Time</th>
                            </tr>
                        </thead>
                        <tbody id="audit-tbody">
                            @foreach($auditLog as $a)
                            <tr>
                                <td>
                                    <div style="font-weight:700;color:#e2e8f0;font-size:.82rem;">{{ $a->user_name ?? 'Unknown' }}</div>
                                    <div style="font-size:.7rem;color:#64748b;">{{ $a->user_email ?? '—' }}</div>
                                </td>
                                <td>
                                    @if($a->user_role)
                                        <span class="badge badge-role-{{ $a->user_role }}">{{ ucfirst(str_replace('_',' ',$a->user_role)) }}</span>
                                    @else
                                        <span style="color:#64748b;">—</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $aMap2 = ['login'=>['badge-login','● Login'],'logout'=>['badge-logout','○ Logout'],'failed_login'=>['badge-failed','✕ Failed'],'approved'=>['badge-approved','✔ Approved'],'rejected'=>['badge-rejected','✕ Rejected']];
                                        [$cls2,$lbl2] = $aMap2[$a->action] ?? ['badge-logout',$a->action];
                                    @endphp
                                    <span class="badge {{ $cls2 }}">{{ $lbl2 }}</span>
                                </td>
                                <td style="color:#94a3b8;font-family:monospace;font-size:.76rem;">{{ $a->ip_address ?? '—' }}</td>
                                <td style="color:#64748b;font-size:.72rem;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $a->user_agent }}">
                                    {{ Str::limit($a->user_agent ?? '—', 45) }}
                                </td>
                                <td style="color:#64748b;font-size:.75rem;">{{ $a->notes ?? '—' }}</td>
                                <td style="color:#64748b;font-size:.75rem;white-space:nowrap;">{{ \Carbon\Carbon::parse($a->created_at)->format('M d, Y h:i A') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- Pagination --}}
                <div class="flex items-center justify-between px-4 py-3 border-t border-sa-border" style="border-color:var(--sa-border);">
                    <span id="audit-info" style="font-size:.75rem; color:#64748b;">Showing {{ $auditLog->firstItem() ?? 0 }} – {{ $auditLog->lastItem() ?? 0 }} of {{ $auditLog->total() }}</span>
                    <div class="flex gap-2" id="audit-pagination">
                        @if($auditLog->onFirstPage())
                            <button class="btn-ghost" disabled style="opacity:.4; cursor:not-allowed;">← Prev</button>
                        @else
                            <button class="btn-ghost" onclick="loadAuditLog({{ $auditLog->currentPage() - 1 }})">← Prev</button>
                        @endif
                        @if($auditLog->hasMorePages())
                            <button class="btn-ghost" onclick="loadAuditLog({{ $auditLog->currentPage() + 1 }})">Next →</button>
                        @else
                            <button class="btn-ghost" disabled style="opacity:.4; cursor:not-allowed;">Next →</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- /p-6 --}}
</div>

{{-- Toast --}}
<div id="sa-toast"></div>

{{-- Password Reset Modal --}}
<div class="sa-modal-backdrop" id="pwModal">
    <div class="sa-modal">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 style="color:#f59e0b; font-weight:900; font-size:1.05rem;">Reset Password</h3>
                <p id="pw-modal-name" style="color:#64748b; font-size:.8rem; margin-top:.2rem;"></p>
            </div>
            <button onclick="closePwModal()" style="color:#64748b; cursor:pointer; padding:.4rem;"><i data-lucide="x" style="width:18px;height:18px;"></i></button>
        </div>
        <input type="hidden" id="pw-user-id">
        <div class="mb-4">
            <label style="font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#64748b; display:block; margin-bottom:.5rem;">New Password</label>
            <input type="password" id="pw-new" class="sa-input" placeholder="Minimum 6 characters">
        </div>
        <div class="mb-5">
            <label style="font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#64748b; display:block; margin-bottom:.5rem;">Confirm Password</label>
            <input type="password" id="pw-confirm" class="sa-input" placeholder="Repeat new password">
        </div>
        <div class="flex gap-3 justify-end">
            <button class="btn-ghost" onclick="closePwModal()">Cancel</button>
            <button class="btn-gold" onclick="submitPasswordReset()">
                <i data-lucide="key" class="inline w-3 h-3 mr-1"></i>Reset Password
            </button>
        </div>
    </div>
</div>

{{-- Role Update Modal --}}
<div class="sa-modal-backdrop" id="roleModal">
    <div class="sa-modal">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 style="color:#14b8a6; font-weight:900; font-size:1.05rem;">Update User Role</h3>
                <p id="role-modal-name" style="color:#64748b; font-size:.8rem; margin-top:.2rem;"></p>
            </div>
            <button onclick="closeRoleModal()" style="color:#64748b; cursor:pointer; padding:.4rem;"><i data-lucide="x" style="width:18px;height:18px;"></i></button>
        </div>
        <input type="hidden" id="role-user-id">
        <div class="mb-6">
            <label style="font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#64748b; display:block; margin-bottom:.5rem;">Assign New Role</label>
            <select id="role-select" class="sa-input">
                <option value="staff">Staff</option>
                <option value="dispatcher">Dispatcher</option>
                <option value="secretary">Secretary</option>
                <option value="manager">Manager</option>
            </select>
            <p style="font-size:.68rem; color:#64748b; margin-top:.75rem;">Changing a role will grant the user the default permissions for that role unless specific page access is set below.</p>
        </div>
        <div class="flex gap-3 justify-end">
            <button class="btn-ghost" onclick="closeRoleModal()">Cancel</button>
            <button class="btn-gold" onclick="submitRoleUpdate()" style="background:#14b8a6; color:white;">
                <i data-lucide="shield" class="inline w-3 h-3 mr-1"></i>Update Role
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// ─── Tab Switching ─────────────────────────────────────────────────────────────
function switchTab(tab) {
    document.querySelectorAll('.sa-tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.sa-tab').forEach(el => el.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.remove('hidden');
    event.currentTarget.classList.add('active');
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

// ─── Toast ────────────────────────────────────────────────────────────────────
function toast(msg, isError = false) {
    const el = document.getElementById('sa-toast');
    el.textContent = msg;
    el.className = 'show' + (isError ? ' error' : '');
    setTimeout(() => el.className = '', 3500);
}

// ─── Approve / Reject ─────────────────────────────────────────────────────────
async function approveUser(id, name) {
    if (!confirm('Approve account for ' + name + '? They will be able to log in immediately.')) return;
    const res = await fetch(`/super-admin/approve/${id}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' } });
    const data = await res.json();
    if (data.success) {
        toast('✔ ' + data.message);
        const row = document.getElementById('pending-row-' + id);
        if (row) row.remove();
        // Update pending badge
        updatePendingBadge();
    } else {
        toast(data.message || 'Error.', true);
    }
}

async function rejectUser(id, name) {
    if (!confirm('Reject account for ' + name + '?')) return;
    const res = await fetch(`/super-admin/reject/${id}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' } });
    const data = await res.json();
    if (data.success) {
        toast('Rejected: ' + name);
        const row = document.getElementById('pending-row-' + id);
        if (row) row.remove();
        updatePendingBadge();
    } else {
        toast(data.message || 'Error.', true);
    }
}

function updatePendingBadge() {
    const rows = document.querySelectorAll('#tab-pending tbody tr');
    const count = rows.length;
    const badge = document.querySelector('.sa-tab:nth-child(2) span');
    if (badge) badge.textContent = count > 0 ? count : '';
}

// ─── Toggle Active ─────────────────────────────────────────────────────────────
async function toggleActive(id, btn) {
    const res = await fetch(`/super-admin/toggle-active/${id}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' } });
    const data = await res.json();
    if (data.success) {
        toast(data.message);
        btn.textContent  = data.is_active ? '● Active' : '○ Inactive';
        btn.style.color  = data.is_active ? '#4ade80' : '#f87171';
        btn.style.background = data.is_active ? 'rgba(34,197,94,.15)' : 'rgba(239,68,68,.1)';
        btn.style.borderColor = data.is_active ? '#166534' : '#991b1b';
        btn.setAttribute('data-active', data.is_active ? 1 : 0);
    } else {
        toast(data.message || 'Error.', true);
    }
}

// ─── Delete / Restore ─────────────────────────────────────────────────────────
async function deleteUser(id, name) {
    if (!confirm(`Archive ${name}? They will be soft-deleted and cannot log in.`)) return;
    const res = await fetch(`/super-admin/users/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' } });
    const data = await res.json();
    if (data.success) { toast('Archived: ' + name); location.reload(); }
    else toast(data.message || 'Error.', true);
}
async function restoreUser(id, name) {
    const res = await fetch(`/super-admin/users/${id}/restore`, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' } });
    const data = await res.json();
    if (data.success) { toast('Restored: ' + name); location.reload(); }
    else toast(data.message || 'Error.', true);
}

// ─── User Search ──────────────────────────────────────────────────────────────
function filterUserTable(val) {
    val = (val || document.getElementById('userSearch').value || '').toLowerCase();
    const statusVal = document.getElementById('statusFilter').value.toLowerCase();
    document.querySelectorAll('#userTable .user-row').forEach(row => {
        const matchText = row.dataset.name.includes(val) || row.dataset.email.includes(val) || row.dataset.role.includes(val);
        const matchStatus = !statusVal || row.dataset.status === statusVal;
        row.style.display = (matchText && matchStatus) ? '' : 'none';
    });
}

// ─── Page Access ──────────────────────────────────────────────────────────────
let currentAccessUserId = null;

function selectAccessUser(el) {
    document.querySelectorAll('.access-user-item').forEach(i => {
        i.style.background = '';
        i.style.borderLeft = '';
    });
    el.style.background = 'rgba(245,158,11,.08)';
    el.style.borderLeft = '3px solid #f59e0b';

    currentAccessUserId = el.dataset.id;
    document.getElementById('access-user-name').textContent = '— ' + el.querySelector('[style*="font-weight:700"]').textContent.trim();
    document.getElementById('save-access-btn').disabled = false;
    document.getElementById('save-access-btn').style.opacity = '1';
    document.getElementById('access-placeholder').classList.add('hidden');
    document.getElementById('page-chips-container').classList.remove('hidden');

    // Load current allowed pages
    let allowed = null;
    try { allowed = JSON.parse(el.dataset.allowed); } catch(e) {}

    document.querySelectorAll('.page-chip').forEach(chip => {
        const route = chip.dataset.route;
        // If allowed is null → full access (no chips active)
        // If allowed is an array, activate matching chips
        const isActive = allowed && Array.isArray(allowed) && allowed.includes(route);
        chip.classList.toggle('active', isActive);
    });
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function togglePageChip(chip) {
    chip.classList.toggle('active');
}

function selectAllPages() {
    document.querySelectorAll('.page-chip').forEach(c => c.classList.add('active'));
}
function clearAllPages() {
    document.querySelectorAll('.page-chip').forEach(c => c.classList.remove('active'));
}

async function savePageAccess() {
    if (!currentAccessUserId) return;
    const activeChips = [...document.querySelectorAll('.page-chip.active')].map(c => c.dataset.route);
    const pages = activeChips.length > 0 ? activeChips : null;

    const res = await fetch(`/super-admin/page-access/${currentAccessUserId}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ pages })
    });
    const data = await res.json();
    if (data.success) {
        toast('✔ ' + data.message);
        // Update the data attribute on the user item
        const item = document.querySelector(`.access-user-item[data-id="${currentAccessUserId}"]`);
        if (item) item.dataset.allowed = JSON.stringify(pages);
    } else {
        toast(data.message || 'Error saving.', true);
    }
}

// ─── Audit Log Pagination ─────────────────────────────────────────────────────
async function loadAuditLog(page = 1) {
    const search  = document.getElementById('auditSearch').value;
    const action  = document.getElementById('auditActionFilter').value;
    const role    = document.getElementById('auditRoleFilter').value;
    const params  = new URLSearchParams({ page, search, action, role, per_page: 25 });

    const res  = await fetch(`/super-admin/login-history?${params}`, { headers: { 'Accept': 'application/json' } });
    const data = await res.json();

    const actionMap = {
        login: ['badge-login','● Login'], logout: ['badge-logout','○ Logout'],
        failed_login: ['badge-failed','✕ Failed'], approved: ['badge-approved','✔ Approved'], rejected: ['badge-rejected','✕ Rejected']
    };
    const roleClass = r => `badge-role-${r || 'staff'}`;

    document.getElementById('audit-tbody').innerHTML = data.data.length === 0
        ? `<tr><td colspan="7" style="text-align:center;color:#64748b;padding:2rem;">No records found.</td></tr>`
        : data.data.map(a => {
            const [cls, lbl] = actionMap[a.action] || ['badge-logout', a.action];
            return `<tr>
                <td><div style="font-weight:700;color:#e2e8f0;font-size:.82rem;">${a.user_name ?? '—'}</div><div style="font-size:.7rem;color:#64748b;">${a.user_email ?? ''}</div></td>
                <td>${a.user_role ? `<span class="badge ${roleClass(a.user_role)}">${a.user_role.replace('_',' ')}</span>` : '—'}</td>
                <td><span class="badge ${cls}">${lbl}</span></td>
                <td style="color:#94a3b8;font-family:monospace;font-size:.76rem;">${a.ip_address ?? '—'}</td>
                <td style="color:#64748b;font-size:.72rem;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="${a.user_agent ?? ''}">${(a.user_agent ?? '—').substring(0,50)}</td>
                <td style="color:#64748b;font-size:.75rem;">${a.notes ?? '—'}</td>
                <td style="color:#64748b;font-size:.75rem;white-space:nowrap;">${new Date(a.created_at).toLocaleDateString('en-US', {month:'short',day:'numeric',year:'numeric',hour:'2-digit',minute:'2-digit'})}</td>
            </tr>`;
        }).join('');

    document.getElementById('audit-info').textContent = `Showing ${data.from ?? 0} – ${data.to ?? 0} of ${data.total}`;

    const pag = document.getElementById('audit-pagination');
    pag.innerHTML = `
        <button class="btn-ghost" onclick="loadAuditLog(${data.current_page - 1})" ${data.current_page <= 1 ? 'disabled style="opacity:.4;cursor:not-allowed;"' : ''}>← Prev</button>
        <button class="btn-ghost" onclick="loadAuditLog(${data.current_page + 1})" ${!data.next_page_url ? 'disabled style="opacity:.4;cursor:not-allowed;"' : ''}>Next →</button>`;

    if (typeof lucide !== 'undefined') lucide.createIcons();
}

// ─── Password Reset Modal ─────────────────────────────────────────────────────
function openPasswordModal(id, name) {
    document.getElementById('pw-user-id').value = id;
    document.getElementById('pw-modal-name').textContent = 'Resetting password for: ' + name;
    document.getElementById('pw-new').value = '';
    document.getElementById('pw-confirm').value = '';
    document.getElementById('pwModal').classList.add('open');
    if (typeof lucide !== 'undefined') lucide.createIcons();
}
function closePwModal() {
    document.getElementById('pwModal').classList.remove('open');
}
async function submitPasswordReset() {
    const id  = document.getElementById('pw-user-id').value;
    const pw  = document.getElementById('pw-new').value;
    const cpw = document.getElementById('pw-confirm').value;
    if (!pw || pw.length < 6) { toast('Password must be at least 6 characters.', true); return; }
    if (pw !== cpw) { toast('Passwords do not match.', true); return; }

    const res = await fetch(`/super-admin/users/${id}/reset-password`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ password: pw })
    });
    const data = await res.json();
    if (data.success) { toast('✔ ' + data.message); closePwModal(); }
    else toast(data.message || 'Error.', true);
}

// ─── Role Update Modal ──────────────────────────────────────────────────────
function openRoleModal(id, name, currentRole) {
    document.getElementById('role-user-id').value = id;
    document.getElementById('role-modal-name').textContent = 'Promote or change role for: ' + name;
    document.getElementById('role-select').value = currentRole;
    document.getElementById('roleModal').classList.add('open');
    if (typeof lucide !== 'undefined') lucide.createIcons();
}
function closeRoleModal() {
    document.getElementById('roleModal').classList.remove('open');
}
async function submitRoleUpdate() {
    const id   = document.getElementById('role-user-id').value;
    const role = document.getElementById('role-select').value;

    const res = await fetch(`/super-admin/users/${id}/update-role`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ role })
    });
    const data = await res.json();
    if (data.success) {
        toast('✔ ' + data.message);
        closeRoleModal();
        location.reload(); // Reload to see badge update
    } else {
        toast(data.message || 'Error.', true);
    }
}

// Close modal on backdrop click
document.getElementById('pwModal').addEventListener('click', function(e) {
    if (e.target === this) closePwModal();
});
document.getElementById('roleModal').addEventListener('click', function(e) {
    if (e.target === this) closeRoleModal();
});

// Init icons on load
document.addEventListener('DOMContentLoaded', () => {
    if (typeof lucide !== 'undefined') lucide.createIcons();
    // Auto-refresh audit log every 30s if on audit tab
    setInterval(() => {
        if (!document.getElementById('tab-audit').classList.contains('hidden')) {
            loadAuditLog();
        }
    }, 30000);
});
</script>
@endpush
