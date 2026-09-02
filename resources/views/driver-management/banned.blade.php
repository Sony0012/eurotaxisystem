@extends('layouts.app')

@section('title', 'Banned Drivers Roster - Euro System')
@section('page-heading', 'Banned Drivers Roster')
@section('page-subheading', 'Manage drivers under lock-out or administrative suspension')

@section('content')
<style>
    .banned-profile-card {
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .banned-profile-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(239, 68, 68, 0.08), 0 10px 10px -5px rgba(239, 68, 68, 0.04);
    }
    .fade-out-card {
        opacity: 0;
        transform: scale(0.9) translateY(20px);
        transition: all 0.5s ease;
    }
    /* Searchable select styling */
    .driver-search-item {
        transition: background 0.15s;
    }
    .driver-search-item:hover, .driver-search-item.active {
        background: #f1f5f9;
    }
    #driverDropdown {
        max-height: 220px;
        overflow-y: auto;
    }
    #driverDropdown::-webkit-scrollbar { width: 4px; }
    #driverDropdown::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 2px; }

    /* Modal animation */
    #addBanSuspendModal .modal-box,
    #autoBanSettingsModal .modal-box {
        transform: scale(0.95) translateY(10px);
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    #addBanSuspendModal.open .modal-box,
    #autoBanSettingsModal.open .modal-box {
        transform: scale(1) translateY(0);
    }
</style>

<div class="space-y-8">
    {{-- ── Premium Header ── --}}
    <div class="relative bg-slate-900 rounded-[2.5rem] p-8 overflow-hidden shadow-2xl border border-red-500/10">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-red-600/10 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute -left-20 -bottom-20 w-60 h-60 bg-yellow-500/5 rounded-full blur-[80px] pointer-events-none"></div>
        
        <div class="relative flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-amber-600 rounded-3xl flex items-center justify-center shadow-xl shadow-red-500/20">
                    <i data-lucide="shield-alert" class="w-8 h-8 text-white animate-pulse"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-white tracking-tight">Suspended Fleet Drivers</h3>
                    <p class="text-sm text-slate-400 mt-1 font-medium max-w-xl leading-relaxed">
                        Drivers blocked due to critical incidents or placed on administrative lock-out. Use the button to add a new suspension or ban.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 shrink-0 flex-wrap">
                <div class="flex flex-col text-left md:text-right bg-red-500/5 px-5 py-3.5 rounded-2xl border border-red-500/10">
                    <span class="text-[10px] font-black text-red-400 uppercase tracking-widest block mb-0.5">Total Lockouts</span>
                    <span id="banned-count-badge" class="text-3xl font-black text-red-500 tracking-tighter">{{ count($bannedDrivers) }}</span>
                </div>
                {{-- AUTO-BAN SETTINGS BUTTON --}}
                <button type="button" onclick="openAutoBanSettingsModal()"
                    class="flex items-center gap-2 px-5 py-4 bg-slate-800/90 hover:bg-slate-700 text-amber-300 hover:text-amber-200 font-black text-xs uppercase tracking-widest rounded-2xl border border-amber-500/30 shadow-xl shadow-black/20 transition-all active:scale-95 cursor-pointer">
                    <i data-lucide="sliders" class="w-4 h-4 text-amber-400"></i>
                    <span>Auto-Ban Settings</span>
                </button>
                {{-- ADD BAN/SUSPEND BUTTON --}}
                <button type="button" onclick="openAddBanModal()"
                    class="flex items-center gap-2.5 px-6 py-4 bg-gradient-to-br from-red-600 to-rose-700 hover:from-red-500 hover:to-rose-600 text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-xl shadow-red-500/30 transition-all active:scale-95 cursor-pointer">
                    <i data-lucide="shield-plus" class="w-5 h-5"></i>
                    <span class="hidden sm:inline">Add Ban / Suspend</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ── Auto-Ban Policy Awareness Banner ── --}}
    <div class="bg-gradient-to-r from-amber-500/10 via-orange-500/10 to-transparent border border-amber-500/20 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-xs">
        <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-amber-500/20 text-amber-600 flex items-center justify-center shrink-0 border border-amber-500/30">
                <i data-lucide="zap" class="w-5 h-5 text-amber-500"></i>
            </div>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs font-black text-slate-800 uppercase tracking-wide">Automated Lockout Policy</span>
                    <span id="policyStatusBadge" class="px-2 py-0.5 {{ ($autoBanSettings['auto_ban_enabled'] ?? '1') == '1' ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200' }} border rounded-md text-[10px] font-black uppercase tracking-wider">
                        {{ ($autoBanSettings['auto_ban_enabled'] ?? '1') == '1' ? 'ACTIVE & ENFORCING' : 'DISABLED' }}
                    </span>
                </div>
                <p id="policyBannerSummary" class="text-xs text-slate-500 font-medium mt-1">
                    Drivers holding an unreturned vehicle for <strong class="text-slate-900 font-bold" id="bannerOverdueDays">{{ $autoBanSettings['auto_ban_overdue_unit_days'] ?? 3 }}</strong> overdue days (daily missed boundary charges) are automatically <span class="uppercase font-black {{ ($autoBanSettings['auto_ban_action_type'] ?? 'banned') === 'banned' ? 'text-red-600' : 'text-amber-600' }}" id="bannerActionType">{{ $autoBanSettings['auto_ban_action_type'] ?? 'BANNED' }}</span> on shift deadline.
                </p>
            </div>
        </div>
        <button type="button" onclick="openAutoBanSettingsModal()"
            class="inline-flex items-center gap-1.5 text-xs font-black text-amber-700 hover:text-amber-800 bg-amber-100/80 hover:bg-amber-100 border border-amber-300/60 px-4 py-2.5 rounded-xl transition-all self-start sm:self-center shrink-0 cursor-pointer active:scale-95 shadow-2xs">
            <i data-lucide="sliders" class="w-3.5 h-3.5"></i> Configure Policy Days
        </button>
    </div>

    {{-- ── Controls Bar ── --}}
    <div class="flex flex-col lg:flex-row gap-4 justify-between items-center bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
        {{-- Search --}}
        <div class="relative w-full lg:max-w-xs">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                <i data-lucide="search" class="w-4 h-4"></i>
            </span>
            <input type="text" id="bannedSearchInput" placeholder="Search suspended drivers..."
                   autocomplete="new-password" spellcheck="false" autocorrect="off" autocapitalize="off"
                   readonly onfocus="this.removeAttribute('readonly');"
                   class="w-full pl-11 pr-4 py-3 text-sm border-2 border-gray-100 rounded-xl focus:border-red-500/30 focus:ring-4 focus:ring-red-500/5 transition-all outline-none bg-slate-50/50">
        </div>

        {{-- Filter Buttons --}}
        <div class="flex items-center gap-2 w-full lg:w-auto overflow-x-auto py-1 scrollbar-none">
            <button type="button" data-filter-status="all" onclick="setStatusFilter('all')"
                class="status-filter-btn px-4 py-2.5 bg-slate-900 text-white text-xs font-black rounded-xl transition-all shadow-md">
                All Lockouts
            </button>
            <button type="button" data-filter-status="banned" onclick="setStatusFilter('banned')"
                class="status-filter-btn px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all">
                Banned Only
            </button>
            <button type="button" data-filter-status="suspended" onclick="setStatusFilter('suspended')"
                class="status-filter-btn px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all">
                Suspended Only
            </button>
        </div>

        <div class="flex items-center gap-3 w-full lg:w-auto shrink-0 justify-end">
            <a href="{{ route('driver-management.index') }}"
               class="flex items-center justify-center gap-2 px-5 py-3 bg-slate-900 text-white text-xs font-black rounded-xl hover:bg-slate-800 transition-all active:scale-95 shadow-lg shadow-slate-200 w-full lg:w-auto">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Roster
            </a>
        </div>
    </div>

    {{-- ── Banned Drivers Grid ── --}}
    <div id="bannedDriversGrid" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($bannedDrivers as $driver)
            <div class="banned-profile-card bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden flex flex-col cursor-pointer hover:shadow-md hover:border-amber-200/80 transition-all"
                 id="driver-card-{{ $driver->id }}"
                 data-status="{{ $driver->driver_status }}"
                 data-search-terms="{{ strtolower($driver->full_name . ' ' . ($driver->license_number ?? '') . ' ' . ($driver->contact_number ?? '')) }}"
                 onclick="openChangeSuspensionModal({{ $driver->id }}, '{{ addslashes($driver->full_name) }}', '{{ $driver->driver_status }}')">
                
                {{-- Card Header --}}
                <div class="p-6 border-b border-gray-50 flex items-start gap-4 bg-slate-50/50">
                    <div class="relative w-14 h-14 rounded-2xl overflow-hidden shrink-0 border-2 {{ $driver->driver_status === 'suspended' ? 'border-amber-400' : 'border-red-400' }} shadow-sm bg-slate-100">
                        @if(!empty($driver->profile_photo))
                            <img src="{{ asset($driver->profile_photo) }}" alt="{{ $driver->full_name }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='{{ asset('image/avatars/driver.svg') }}';">
                        @else
                            <img src="{{ asset('image/avatars/driver.svg') }}" alt="{{ $driver->full_name }}" class="w-full h-full object-cover bg-amber-50">
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h4 class="text-base font-black text-slate-900 truncate">{{ $driver->full_name }}</h4>
                            @if($driver->driver_status === 'suspended')
                                <span class="px-2 py-0.5 bg-amber-100 text-amber-700 rounded-md text-[9px] font-black uppercase tracking-widest border border-amber-200">Suspended</span>
                            @else
                                <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded-md text-[9px] font-black uppercase tracking-widest border border-red-200">Banned</span>
                            @endif
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1 font-bold">REG KEY: DRV-{{ str_pad($driver->id, 4, '0', STR_PAD_LEFT) }}</p>
                        @if($driver->driver_status === 'suspended')
                            @if(($driver->days_left ?? 0) > 0)
                                <p class="text-[10px] text-amber-600 mt-1 font-black flex items-center gap-1">
                                    <i data-lucide="clock" class="w-3.5 h-3.5 animate-pulse"></i>
                                    SUSPENSION: {{ $driver->days_left }} DAYS REMAINING
                                </p>
                            @else
                                <p class="text-[10px] text-emerald-600 mt-1 font-black flex items-center gap-1">
                                    <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
                                    FINISHED - PENDING ACTIVATION
                                </p>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- Card Content --}}
                <div class="p-6 space-y-4 flex-1">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-0.5">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">License</span>
                            <span class="text-xs font-black text-slate-800 font-mono tracking-wider">{{ $driver->license_number ?? 'N/A' }}</span>
                        </div>
                        <div class="space-y-0.5">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Contact</span>
                            <span class="text-xs font-black text-slate-800">{{ $driver->contact_number ?? 'N/A' }}</span>
                        </div>
                        <div class="space-y-0.5">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Hire Date</span>
                            <span class="text-xs font-bold text-slate-600">{{ $driver->hire_date ? \Carbon\Carbon::parse($driver->hire_date)->format('M d, Y') : 'N/A' }}</span>
                        </div>
                        <div class="space-y-0.5">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Registrar</span>
                            <span class="text-xs font-bold text-slate-600 uppercase">{{ $driver->creator_name ?? 'System' }}</span>
                        </div>
                    </div>

                    @if(!empty($driver->suspension_reason))
                        <div class="pt-3 border-t border-gray-50 space-y-0.5">
                            <span class="text-[9px] font-black text-red-400 uppercase tracking-widest block">Reason / Description</span>
                            <p class="text-xs font-bold text-slate-700 leading-relaxed italic">"{{ $driver->suspension_reason }}"</p>
                        </div>
                    @elseif(!empty($driver->address))
                        <div class="pt-3 border-t border-gray-50 space-y-0.5">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Address</span>
                            <span class="text-xs font-bold text-slate-600 leading-relaxed block">{{ $driver->address }}</span>
                        </div>
                    @endif

                    {{-- Ban Incidents --}}
                    @if(count($driver->ban_incidents) > 0)
                        <div class="pt-4 border-t border-gray-50">
                            <h5 class="text-[10px] font-black text-red-500 uppercase tracking-widest mb-2 flex items-center gap-1.5">
                                <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> Ban Triggers ({{ count($driver->ban_incidents) }} incident(s))
                            </h5>
                            <div class="space-y-2 max-h-32 overflow-y-auto custom-scrollbar">
                                @foreach($driver->ban_incidents as $inc)
                                    <div class="p-2.5 bg-red-50/50 rounded-xl border border-red-100/50 flex flex-col gap-1">
                                        <div class="flex justify-between items-center gap-2">
                                            <span class="text-[10px] font-black text-red-800">{{ $inc->incident_type }}</span>
                                            <span class="text-[8px] font-black uppercase tracking-widest px-1.5 py-0.5 bg-red-200/50 text-red-700 rounded">{{ $inc->severity }}</span>
                                        </div>
                                        <p class="text-[10px] text-slate-600 leading-relaxed italic break-words">"{{ $inc->description }}"</p>
                                        <span class="text-[8px] text-slate-400 font-bold block text-right">{{ \Carbon\Carbon::parse($inc->incident_date)->format('M d, Y') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="pt-4 border-t border-gray-50 flex items-center gap-2 text-slate-400 text-xs font-bold italic py-2">
                            <i data-lucide="info" class="w-4 h-4"></i> No behavioral incidents logged.
                        </div>
                    @endif
                </div>

                {{-- Action Footer --}}
                <div class="p-5 border-t border-gray-50 bg-slate-50 flex justify-between items-center gap-3 relative z-50">
                    {{-- Dues & Lockout Details Button --}}
                    <button type="button"
                        class="modify-suspension-btn px-4 py-2.5 bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-black rounded-xl transition-all flex items-center gap-2 border border-amber-200 hover:border-amber-300 active:scale-95 cursor-pointer relative z-50 shadow-2xs"
                        onclick="event.stopPropagation(); openChangeSuspensionModal({{ $driver->id }}, '{{ addslashes($driver->full_name) }}', '{{ $driver->driver_status }}')"
                        data-id="{{ $driver->id }}"
                        data-name="{{ $driver->full_name }}"
                        data-status="{{ $driver->driver_status }}">
                        <i data-lucide="receipt" class="w-4 h-4 pointer-events-none text-amber-600"></i>
                        <span>Dues & Lockout Details</span>
                    </button>
                    <button type="button"
                            class="restore-driver-btn px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-black rounded-xl transition-all flex items-center gap-2 shadow-md hover:shadow-lg active:scale-95 cursor-pointer relative z-50"
                            onclick="event.stopPropagation(); performUnban({{ $driver->id }}, '{{ addslashes($driver->full_name) }}')"
                            data-id="{{ $driver->id }}"
                            data-name="{{ $driver->full_name }}">
                        <i data-lucide="shield-check" class="w-4 h-4 pointer-events-none text-emerald-400"></i> Restore Driver
                    </button>
                </div>
            </div>
        @empty
            <div id="empty-state-card" class="col-span-1 md:col-span-2 flex flex-col items-center justify-center py-24 bg-slate-50 rounded-[2.5rem] border border-dashed border-slate-200">
                <div class="w-20 h-20 bg-emerald-50 text-emerald-500 rounded-3xl flex items-center justify-center mb-6 shadow-xl shadow-emerald-100">
                    <i data-lucide="shield-check" class="w-10 h-10 animate-bounce"></i>
                </div>
                <h4 class="text-xl font-black text-slate-800 mb-2">No Suspended Drivers</h4>
                <p class="text-sm text-slate-500 max-w-xs text-center font-medium leading-relaxed">
                    All drivers are currently active and ready for dispatch.
                </p>
            </div>
        @endforelse
    </div>

    {{-- No Search Results --}}
    <div id="noSearchResultsCard" class="hidden flex-col items-center justify-center py-20 bg-slate-50 rounded-[2.5rem] border border-dashed border-slate-200">
        <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mb-4">
            <i data-lucide="search-code" class="w-8 h-8"></i>
        </div>
        <h4 class="text-base font-black text-slate-800 mb-1">No Matching Drivers Found</h4>
        <p class="text-xs text-slate-400">Try a different name, license, or contact number.</p>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════
     AUTO-BAN & LOCKOUT POLICY SETTINGS MODAL
════════════════════════════════════════════════════════ --}}
<div id="autoBanSettingsModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-[9999] flex items-center justify-center p-4">
    <div class="modal-box relative bg-white rounded-[2rem] shadow-2xl w-full max-w-xl overflow-hidden flex flex-col">
        
        {{-- Modal Header --}}
        <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-amber-950/40 p-6 shrink-0 border-b border-slate-700/50">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-500/20 border border-amber-500/30 rounded-2xl flex items-center justify-center">
                        <i data-lucide="sliders" class="w-6 h-6 text-amber-400"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-white uppercase tracking-wide">Auto-Ban & Lockout Policy</h3>
                        <p class="text-[11px] font-bold text-slate-400 mt-0.5 uppercase tracking-widest">Driver Missed Boundary & Overdue Rules</p>
                    </div>
                </div>
                <button type="button" onclick="closeAutoBanSettingsModal()" class="text-slate-400 hover:text-white bg-slate-700/50 hover:bg-slate-700 p-2 rounded-full transition-colors cursor-pointer">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
        </div>

        {{-- Form --}}
        <form id="autoBanSettingsForm" onsubmit="submitAutoBanSettings(event)" class="p-7 space-y-6 overflow-y-auto max-h-[80vh]">
            
            {{-- Automation Status Toggle --}}
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 flex items-center justify-between gap-4">
                <div>
                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-wide">Enable Automated Lockout System</h4>
                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">Automatically trigger suspension/ban when a driver hits the threshold.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer shrink-0">
                    <input type="checkbox" id="setting_auto_ban_enabled" class="sr-only peer" {{ ($autoBanSettings['auto_ban_enabled'] ?? '1') == '1' ? 'checked' : '' }} onchange="updatePolicySimulator()">
                    <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                </label>
            </div>

            {{-- 1. Overdue Unreturned Vehicle Limit (Days) --}}
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <label class="block text-[11px] font-black text-slate-700 uppercase tracking-widest">
                        Overdue Unreturned Vehicle Limit <span class="text-red-500">*</span>
                    </label>
                    <span class="text-[10px] font-bold text-amber-600 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-md">Auto Missed Boundary Tracking</span>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="adjustSettingDays('setting_overdue_days', -1)" class="w-11 h-11 bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-700 font-black rounded-xl text-lg flex items-center justify-center transition-all cursor-pointer select-none">
                        -
                    </button>
                    <div class="relative flex-1">
                        <input type="number" id="setting_overdue_days" min="1" max="30"
                            value="{{ $autoBanSettings['auto_ban_overdue_unit_days'] ?? ($autoBanSettings['auto_ban_missed_boundary_days'] ?? 3) }}"
                            oninput="updatePolicySimulator()"
                            class="w-full text-center font-black text-base py-3 border-2 border-slate-200 rounded-xl focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all outline-none bg-slate-50/50">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400 pointer-events-none">Days</span>
                    </div>
                    <button type="button" onclick="adjustSettingDays('setting_overdue_days', 1)" class="w-11 h-11 bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-700 font-black rounded-xl text-lg flex items-center justify-center transition-all cursor-pointer select-none">
                        +
                    </button>
                </div>
                <p class="text-[10px] text-slate-400 font-medium">Number of overdue days a driver holds the taxi past their shift deadline (daily missed boundary charges are auto-billed) before automatic lockout is executed.</p>
            </div>

            {{-- 3. Lockout Action Type --}}
            <div class="space-y-2.5">
                <label class="block text-[11px] font-black text-slate-700 uppercase tracking-widest">
                    Action to Execute on Threshold <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative flex items-center p-3.5 border-2 rounded-2xl cursor-pointer transition-all hover:bg-slate-50" id="label_action_banned">
                        <input type="radio" name="setting_action_type" value="banned" class="sr-only" {{ ($autoBanSettings['auto_ban_action_type'] ?? 'banned') === 'banned' ? 'checked' : '' }} onchange="onActionTypeChange('banned')">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                                <i data-lucide="shield-ban" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <div class="text-xs font-black text-slate-900">Permanent Ban</div>
                                <div class="text-[10px] text-slate-400 font-medium">Deactivate & blacklist</div>
                            </div>
                        </div>
                    </label>

                    <label class="relative flex items-center p-3.5 border-2 rounded-2xl cursor-pointer transition-all hover:bg-slate-50" id="label_action_suspended">
                        <input type="radio" name="setting_action_type" value="suspended" class="sr-only" {{ ($autoBanSettings['auto_ban_action_type'] ?? 'banned') === 'suspended' ? 'checked' : '' }} onchange="onActionTypeChange('suspended')">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                                <i data-lucide="clock" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <div class="text-xs font-black text-slate-900">Temporary Suspend</div>
                                <div class="text-[10px] text-slate-400 font-medium">Set duration lockout</div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- 4. Temporary Suspension Duration (conditional) --}}
            <div id="suspensionDurationContainer" class="{{ ($autoBanSettings['auto_ban_action_type'] ?? 'banned') === 'suspended' ? '' : 'hidden' }} space-y-2">
                <label class="block text-[11px] font-black text-slate-700 uppercase tracking-widest">
                    Default Suspension Duration <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="adjustSettingDays('setting_suspension_days', -1)" class="w-11 h-11 bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-700 font-black rounded-xl text-lg flex items-center justify-center transition-all cursor-pointer select-none">
                        -
                    </button>
                    <div class="relative flex-1">
                        <input type="number" id="setting_suspension_days" min="1" max="90"
                            value="{{ $autoBanSettings['auto_ban_default_suspension_days'] ?? 7 }}"
                            oninput="updatePolicySimulator()"
                            class="w-full text-center font-black text-base py-3 border-2 border-slate-200 rounded-xl focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all outline-none bg-slate-50/50">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400 pointer-events-none">Days</span>
                    </div>
                    <button type="button" onclick="adjustSettingDays('setting_suspension_days', 1)" class="w-11 h-11 bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-700 font-black rounded-xl text-lg flex items-center justify-center transition-all cursor-pointer select-none">
                        +
                    </button>
                </div>
            </div>

            {{-- 5. Live Interactive Rule Simulator & Awareness Forecaster --}}
            <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 rounded-2xl p-5 text-white shadow-xl border border-slate-700/60 space-y-2.5">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <i data-lucide="activity" class="w-4 h-4 text-amber-400 animate-pulse"></i>
                        <span class="text-[10px] font-black text-amber-400 uppercase tracking-widest">Policy Forecast & Awareness</span>
                    </div>
                    <span id="simulatorStatusBadge" class="px-2 py-0.5 bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 rounded-md text-[9px] font-black uppercase tracking-wider">Active</span>
                </div>
                <div id="simulatorExplanationText" class="text-xs text-slate-300 font-medium leading-relaxed bg-black/20 p-3.5 rounded-xl border border-white/5 space-y-1.5">
                    <!-- Injected dynamically by updatePolicySimulator() -->
                </div>
            </div>

            {{-- Modal Actions --}}
            <div class="flex justify-end items-center gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeAutoBanSettingsModal()"
                    class="px-5 py-3 text-slate-600 hover:text-slate-800 hover:bg-slate-100 font-bold text-xs uppercase tracking-widest rounded-xl transition-all cursor-pointer">
                    Cancel
                </button>
                <button type="submit" id="saveAutoBanSettingsBtn"
                    class="px-6 py-3.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 font-black text-xs uppercase tracking-widest rounded-xl shadow-lg shadow-amber-500/20 transition-all active:scale-95 cursor-pointer flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    <span>Save Policy Settings</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════
     ADD NEW BAN / SUSPENSION MODAL
════════════════════════════════════════════════════════ --}}
<div id="addBanSuspendModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-[9999] flex items-center justify-center p-4">
    <div class="modal-box relative bg-white rounded-[2rem] shadow-2xl w-full max-w-lg overflow-hidden flex flex-col">
        
        {{-- Modal Header --}}
        <div class="bg-gradient-to-r from-slate-900 to-slate-800 p-6 shrink-0">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-red-500/20 rounded-2xl flex items-center justify-center">
                        <i data-lucide="shield-ban" class="w-6 h-6 text-red-400"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-white uppercase tracking-wide">Add Suspension / Ban</h3>
                        <p class="text-[11px] font-bold text-slate-400 mt-0.5 uppercase tracking-widest">Administrative Lock-Out Action</p>
                    </div>
                </div>
                <button type="button" onclick="closeAddBanModal()" class="text-slate-400 hover:text-white bg-slate-700/50 hover:bg-slate-700 p-2 rounded-full transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
        </div>

        {{-- Form --}}
        <form id="addBanSuspendForm" onsubmit="submitAddBanSuspend(event)" class="p-7 space-y-6 overflow-y-auto">
            
            {{-- STEP 1: Select Driver --}}
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">
                    Select Driver <span class="text-red-500">*</span>
                </label>
                {{-- Searchable Driver Input --}}
                <div class="relative" id="driverSelectContainer">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="user-search" class="w-4 h-4 text-slate-400"></i>
                    </div>
                    <input type="text" id="driverSearchInput"
                        placeholder="Type driver name to search..."
                        autocomplete="off"
                        oninput="filterDriverList(this.value)"
                        onfocus="showDriverDropdown()"
                        class="w-full pl-11 pr-4 py-3.5 border-2 border-slate-100 rounded-xl focus:border-red-400/50 focus:ring-4 focus:ring-red-500/5 transition-all outline-none bg-slate-50/50 font-bold text-sm text-slate-700">
                    <input type="hidden" id="selectedDriverId" value="">
                    {{-- Dropdown --}}
                    <div id="driverDropdown" class="absolute z-50 w-full mt-1 bg-white rounded-xl border border-slate-100 shadow-2xl hidden">
                        @forelse($activeDrivers as $d)
                            <div class="driver-search-item px-4 py-3 cursor-pointer flex items-center gap-3"
                                 data-id="{{ $d->id }}"
                                 data-name="{{ $d->full_name }}"
                                 data-status="{{ $d->driver_status }}"
                                 onclick="selectDriver({{ $d->id }}, '{{ addslashes($d->full_name) }}', '{{ $d->driver_status }}')">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xs font-black shrink-0">
                                    {{ substr($d->first_name, 0, 1) }}{{ substr($d->last_name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-black text-slate-800 truncate">{{ $d->full_name }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ ucfirst($d->driver_status) }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="px-4 py-4 text-center text-xs text-slate-400 font-bold">No active drivers found</div>
                        @endforelse
                    </div>
                </div>
                <p class="text-[10px] text-slate-400 font-bold">Only active (non-banned/suspended) drivers are listed.</p>
            </div>

            {{-- STEP 2: Action Type --}}
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">
                    Action Type <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" id="btnSuspend" onclick="setActionType('suspend')"
                        class="action-type-btn relative flex flex-col items-center gap-2 px-4 py-4 border-2 border-amber-400 bg-amber-50 text-amber-700 rounded-2xl transition-all font-black text-xs uppercase tracking-widest shadow-sm">
                        <i data-lucide="clock" class="w-6 h-6"></i>
                        Temporary Suspension
                        <span class="text-[9px] font-bold normal-case tracking-normal text-amber-500">Limited days</span>
                        <div class="absolute top-2 right-2 w-4 h-4 bg-amber-400 rounded-full flex items-center justify-center" id="checkSuspend">
                            <i data-lucide="check" class="w-2.5 h-2.5 text-white"></i>
                        </div>
                    </button>
                    <button type="button" id="btnBan" onclick="setActionType('ban')"
                        class="action-type-btn relative flex flex-col items-center gap-2 px-4 py-4 border-2 border-slate-200 bg-slate-50 text-slate-500 rounded-2xl transition-all font-black text-xs uppercase tracking-widest">
                        <i data-lucide="ban" class="w-6 h-6"></i>
                        Permanent Ban
                        <span class="text-[9px] font-bold normal-case tracking-normal">Indefinite</span>
                        <div class="absolute top-2 right-2 w-4 h-4 bg-slate-200 rounded-full hidden items-center justify-center" id="checkBan">
                            <i data-lucide="check" class="w-2.5 h-2.5 text-white"></i>
                        </div>
                    </button>
                </div>
                <input type="hidden" id="addBanActionType" value="suspend">
            </div>

            {{-- STEP 3: Duration (suspend only) --}}
            <div id="durationSection" class="space-y-2">
                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">
                    Suspension Duration <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="calendar-clock" class="w-4 h-4 text-slate-400"></i>
                    </div>
                    <input type="number" id="addBanDuration" min="1" max="365" value="7"
                        class="w-full pl-11 pr-4 py-3.5 border-2 border-slate-100 rounded-xl focus:border-amber-400/50 focus:ring-4 focus:ring-amber-500/5 transition-all outline-none bg-slate-50/50 font-black text-sm text-slate-800"
                        placeholder="Enter number of days (e.g. 7)">
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                        <span class="text-xs font-black text-slate-400">DAYS</span>
                    </div>
                </div>
                {{-- Quick Day Presets --}}
                <div class="flex flex-wrap gap-2 mt-2">
                    @foreach([3, 7, 14, 30, 60, 90] as $d)
                        <button type="button" onclick="document.getElementById('addBanDuration').value = {{ $d }}"
                            class="px-3 py-1.5 bg-slate-100 hover:bg-amber-100 hover:text-amber-700 text-slate-600 text-[10px] font-black rounded-lg transition-all border border-slate-200 hover:border-amber-300">
                            {{ $d }}d
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- STEP 4: Description / Reason --}}
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">
                        Reason / Description <span class="text-red-500">*</span>
                    </label>
                    <span id="addReasonCount" class="text-[10px] font-bold text-slate-400">0 / 500</span>
                </div>
                <textarea id="addBanReason" rows="4"
                    placeholder="Provide a clear and detailed explanation for this administrative action. This will be recorded in the driver's history..."
                    required minlength="5" maxlength="500"
                    oninput="document.getElementById('addReasonCount').textContent = this.value.length + ' / 500'"
                    class="w-full px-4 py-3.5 border-2 border-slate-100 rounded-xl focus:border-red-400/50 focus:ring-4 focus:ring-red-500/5 transition-all outline-none bg-slate-50/50 font-medium text-sm text-slate-700 resize-none"></textarea>
            </div>

            {{-- Warning Note --}}
            <div class="bg-red-50 border border-red-100 rounded-2xl p-4 flex items-start gap-3">
                <i data-lucide="triangle-alert" class="w-5 h-5 text-red-500 shrink-0 mt-0.5 animate-pulse"></i>
                <div>
                    <p class="text-[10px] font-black text-red-700 uppercase tracking-widest">Important Notice</p>
                    <p class="text-[11px] text-red-600 font-semibold mt-1 leading-relaxed">
                        The driver will be automatically <strong>unassigned from their active vehicle unit</strong> and this action will be logged in the system activity records.
                    </p>
                </div>
            </div>

            {{-- Footer Buttons --}}
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeAddBanModal()"
                    class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-black rounded-xl transition-all">
                    Cancel
                </button>
                <button type="submit" id="addBanSubmitBtn"
                    class="px-8 py-3 bg-gradient-to-r from-red-600 to-rose-700 hover:from-red-500 hover:to-rose-600 text-white text-xs font-black rounded-xl shadow-lg shadow-red-200 transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="shield-alert" class="w-4 h-4"></i>
                    <span id="addBanSubmitLabel">Apply Suspension</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════
     LOCKOUT & DEBT BREAKDOWN MODAL (for banned/suspended drivers)
════════════════════════════════════════════════════════ --}}
<div id="changeSuspensionModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-[9999] flex items-center justify-center p-4">
    <div class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh]">
        
        {{-- Header --}}
        <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-amber-950/50 p-6 shrink-0 border-b border-slate-700/50">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-500/20 border border-amber-500/30 rounded-2xl flex items-center justify-center">
                        <i data-lucide="receipt" class="w-6 h-6 text-amber-400"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-lg font-black text-white" id="modalDriverName">Driver Name</h3>
                            <span id="modalDriverStatusBadge" class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider bg-red-500/20 text-red-300 border border-red-500/30">Banned</span>
                        </div>
                        <p id="changeSuspendSubtitle" class="text-[11px] font-bold text-slate-400 mt-0.5 uppercase tracking-widest">DRV-0000 • Lockout & Dues Breakdown</p>
                    </div>
                </div>
                <button type="button" onclick="closeChangeSuspensionModal()" class="text-slate-400 hover:text-white bg-slate-700/50 hover:bg-slate-700 p-2 rounded-full transition-colors cursor-pointer">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
        </div>

        <div class="p-6 sm:p-7 overflow-y-auto space-y-6">
            {{-- 1. Three Top Metric Cards (Total Dues, Overdue Days, Unit) --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                {{-- Total Unpaid Dues --}}
                <div class="bg-gradient-to-br from-red-50 to-rose-50/60 border border-red-200/80 rounded-2xl p-4 shadow-2xs">
                    <div class="flex items-center justify-between text-red-600 mb-1">
                        <span class="text-[10px] font-black uppercase tracking-wider">Total Dues (Babayaran)</span>
                        <i data-lucide="wallet" class="w-4 h-4"></i>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-red-700 tracking-tight" id="modalTotalDues">
                        ₱0.00
                    </div>
                    <span class="text-[10px] font-bold text-red-500 mt-0.5 block">Accumulated pending charges</span>
                </div>

                {{-- Overdue / Missed Days --}}
                <div class="bg-gradient-to-br from-amber-50 to-orange-50/60 border border-amber-200/80 rounded-2xl p-4 shadow-2xs">
                    <div class="flex items-center justify-between text-amber-700 mb-1">
                        <span class="text-[10px] font-black uppercase tracking-wider">Missed / Overdue Days</span>
                        <i data-lucide="calendar-alert" class="w-4 h-4"></i>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-amber-800 tracking-tight" id="modalOverdueDaysCount">
                        0 Days
                    </div>
                    <span class="text-[10px] font-bold text-amber-600 mt-0.5 block">Unreturned vehicle days</span>
                </div>

                {{-- Current Unit --}}
                <div class="bg-gradient-to-br from-slate-50 to-blue-50/50 border border-slate-200/80 rounded-2xl p-4 shadow-2xs">
                    <div class="flex items-center justify-between text-slate-600 mb-1">
                        <span class="text-[10px] font-black uppercase tracking-wider">Assigned Unit</span>
                        <i data-lucide="car" class="w-4 h-4 text-sky-600"></i>
                    </div>
                    <div class="text-base sm:text-lg font-black text-slate-800 truncate" id="modalUnitPlate">
                        None
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 mt-0.5 block" id="modalBoundaryRate">Rate: ₱0.00/day</span>
                </div>
            </div>

            {{-- 2. Itemized Daily Missed Boundary & Incident Breakdown Table --}}
            <div class="space-y-2.5">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-wide flex items-center gap-2">
                        <i data-lucide="list-ordered" class="w-4 h-4 text-amber-500"></i>
                        Itemized Daily Dues & Incident Breakdown
                    </h4>
                    <span class="text-[10px] font-bold text-slate-400" id="breakdownCount">0 Records</span>
                </div>

                <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-2xs">
                    <div class="max-h-56 overflow-y-auto custom-scrollbar">
                        <table class="w-full divide-y divide-slate-100 text-left">
                            <thead class="bg-slate-50 sticky top-0 z-10">
                                <tr>
                                    <th class="px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-wider">Date</th>
                                    <th class="px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-wider">Charge / Incident Type</th>
                                    <th class="px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-wider">Description</th>
                                    <th class="px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-wider text-right">Amount Due</th>
                                </tr>
                            </thead>
                            <tbody id="modalBreakdownTbody" class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-slate-400 font-bold">
                                        Loading dues breakdown...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- 3. Administrative Lockout Adjustment Form (Re-Suspend / Modify) --}}
            <form id="changeSuspensionForm" onsubmit="submitChangeSuspension(event)" class="bg-slate-50 border border-slate-200 rounded-2xl p-5 space-y-4">
                <input type="hidden" id="changeSuspendDriverId" value="">
                
                <div class="flex items-center justify-between">
                    <h5 class="text-xs font-black text-slate-800 uppercase tracking-wide flex items-center gap-2">
                        <i data-lucide="shield-alert" class="w-4 h-4 text-slate-600"></i>
                        Administrative Status Adjustment
                    </h5>
                    <span class="text-[10px] font-bold text-slate-400">Modify lockout policy for this driver</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">Action Type <span class="text-red-500">*</span></label>
                        <select id="changeSuspendActionType" onchange="toggleChangeDuration()"
                            class="w-full px-3.5 py-2.5 border-2 border-slate-200 rounded-xl focus:border-amber-400/50 focus:ring-4 focus:ring-amber-500/5 transition-all outline-none bg-white font-bold text-xs text-slate-700">
                            <option value="suspend">Temporary Suspension</option>
                            <option value="ban">Permanent Ban</option>
                        </select>
                    </div>

                    <div class="space-y-1.5" id="changeDurationSection">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">Duration (Days) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="number" id="changeSuspendDuration" min="1" max="365" value="7"
                                class="w-full px-3.5 py-2.5 border-2 border-slate-200 rounded-xl focus:border-amber-400/50 focus:ring-4 focus:ring-amber-500/5 transition-all outline-none bg-white font-black text-xs text-slate-800">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-[10px] font-black text-slate-400">DAYS</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Preset Duration Pills --}}
                <div class="flex flex-wrap items-center gap-1.5" id="changeDurationPresets">
                    <span class="text-[10px] font-bold text-slate-400">Quick set:</span>
                    @foreach([3, 7, 14, 30, 60, 90] as $d)
                        <button type="button" onclick="document.getElementById('changeSuspendDuration').value = {{ $d }}"
                            class="px-2.5 py-1 bg-white hover:bg-amber-100 hover:text-amber-800 text-slate-600 text-[10px] font-bold rounded-lg transition-all border border-slate-200">
                            {{ $d }}d
                        </button>
                    @endforeach
                </div>

                <div class="space-y-1.5">
                    <div class="flex justify-between items-center">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">Administrative Notes / Reason <span class="text-red-500">*</span></label>
                        <span id="changeReasonCount" class="text-[10px] font-bold text-slate-400">0 / 500</span>
                    </div>
                    <textarea id="changeSuspendReason" rows="2"
                        placeholder="State reason for modifying driver status or settlement agreement..."
                        required minlength="5" maxlength="500"
                        oninput="document.getElementById('changeReasonCount').textContent = this.value.length + ' / 500'"
                        class="w-full px-3.5 py-2.5 border-2 border-slate-200 rounded-xl focus:border-amber-400/50 focus:ring-4 focus:ring-amber-500/5 transition-all outline-none bg-white font-medium text-xs text-slate-700 resize-none"></textarea>
                </div>

                <div class="flex justify-between items-center pt-2 border-t border-slate-200/80">
                    <button type="button" id="modalRestoreDriverBtn"
                        onclick="performUnbanFromModal()"
                        class="px-4 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-black rounded-xl border border-emerald-300 transition-all active:scale-95 flex items-center gap-1.5 cursor-pointer">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Restore / Unban Driver
                    </button>

                    <div class="flex items-center gap-2">
                        <button type="button" onclick="closeChangeSuspensionModal()"
                            class="px-4 py-2.5 bg-white hover:bg-slate-100 text-slate-700 text-xs font-black rounded-xl border border-slate-200 transition-all cursor-pointer">
                            Close
                        </button>
                        <button type="submit" id="changeSuspendSubmitBtn"
                            class="px-5 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 text-xs font-black rounded-xl shadow-md transition-all active:scale-95 flex items-center gap-1.5 cursor-pointer">
                            <i data-lucide="save" class="w-3.5 h-3.5"></i> Update Status
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
/* ═══════════════════════════════════════════════════════
   SEARCH & FILTER
═══════════════════════════════════════════════════════ */
let currentFilter = 'all';

function filterDrivers() {
    const query = document.getElementById('bannedSearchInput')?.value.trim().toLowerCase() || '';
    const cards = document.querySelectorAll('.banned-profile-card');
    const noResults = document.getElementById('noSearchResultsCard');
    let visibleCount = 0;
    cards.forEach(card => {
        const terms = card.getAttribute('data-search-terms') || '';
        const status = card.getAttribute('data-status') || '';
        const matchesSearch = terms.includes(query);
        const matchesStatus = (currentFilter === 'all' || status === currentFilter);
        if (matchesSearch && matchesStatus) { card.classList.remove('hidden'); visibleCount++; }
        else { card.classList.add('hidden'); }
    });
    if (visibleCount === 0 && cards.length > 0) {
        noResults.classList.remove('hidden'); noResults.classList.add('flex');
    } else {
        noResults.classList.add('hidden'); noResults.classList.remove('flex');
    }
}

function setStatusFilter(status) {
    currentFilter = status;
    document.querySelectorAll('.status-filter-btn').forEach(btn => {
        const btnStatus = btn.getAttribute('data-filter-status');
        btn.className = btnStatus === status
            ? 'status-filter-btn px-4 py-2.5 bg-slate-900 text-white text-xs font-black rounded-xl transition-all shadow-md'
            : 'status-filter-btn px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all';
    });
    filterDrivers();
}
document.getElementById('bannedSearchInput')?.addEventListener('input', filterDrivers);

/* ═══════════════════════════════════════════════════════
   RESTORE (UNBAN)
═══════════════════════════════════════════════════════ */
function performUnban(driverId, driverName) {
    if (!confirm('Are you sure you want to RESTORE ' + driverName + '?\nThis will set their status back to Available.')) return;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    fetch(`/driver-management/${driverId}/unban`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            if (typeof showNotification === 'function') showNotification(data.message, 'success');
            else alert(data.message);
            const card = document.getElementById('driver-card-' + driverId);
            if (card) {
                card.classList.add('fade-out-card');
                setTimeout(() => {
                    card.remove();
                    const badge = document.getElementById('banned-count-badge');
                    if (badge) {
                        let curr = parseInt(badge.textContent) || 0;
                        badge.textContent = Math.max(0, curr - 1);
                    }
                }, 500);
            }
        } else { alert('Error: ' + data.message); }
    })
    .catch(err => { console.error(err); alert('Failed to restore driver. Please try again.'); });
}

/* ═══════════════════════════════════════════════════════
   ADD NEW BAN / SUSPEND MODAL
═══════════════════════════════════════════════════════ */
let currentActionType = 'suspend';

function openAddBanModal() {
    document.getElementById('driverSearchInput').value = '';
    document.getElementById('selectedDriverId').value = '';
    document.getElementById('addBanDuration').value = '7';
    document.getElementById('addBanReason').value = '';
    document.getElementById('addReasonCount').textContent = '0 / 500';
    setActionType('suspend');
    const modal = document.getElementById('addBanSuspendModal');
    modal.classList.remove('hidden');
    setTimeout(() => modal.classList.add('open'), 10);
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function closeAddBanModal() {
    const modal = document.getElementById('addBanSuspendModal');
    modal.classList.remove('open');
    setTimeout(() => modal.classList.add('hidden'), 250);
}

function setActionType(type) {
    currentActionType = type;
    document.getElementById('addBanActionType').value = type;
    const durationSection = document.getElementById('durationSection');
    const submitLabel = document.getElementById('addBanSubmitLabel');
    const btnSuspend = document.getElementById('btnSuspend');
    const btnBan = document.getElementById('btnBan');
    const checkSuspend = document.getElementById('checkSuspend');
    const checkBan = document.getElementById('checkBan');

    if (type === 'suspend') {
        durationSection.style.display = 'block';
        document.getElementById('addBanDuration').required = true;
        submitLabel.textContent = 'Apply Suspension';
        // Suspend active
        btnSuspend.className = 'action-type-btn relative flex flex-col items-center gap-2 px-4 py-4 border-2 border-amber-400 bg-amber-50 text-amber-700 rounded-2xl transition-all font-black text-xs uppercase tracking-widest shadow-sm';
        checkSuspend.className = 'absolute top-2 right-2 w-4 h-4 bg-amber-400 rounded-full flex items-center justify-center';
        // Ban inactive
        btnBan.className = 'action-type-btn relative flex flex-col items-center gap-2 px-4 py-4 border-2 border-slate-200 bg-slate-50 text-slate-400 rounded-2xl transition-all font-black text-xs uppercase tracking-widest';
        checkBan.className = 'absolute top-2 right-2 w-4 h-4 bg-slate-200 rounded-full hidden items-center justify-center';
    } else {
        durationSection.style.display = 'none';
        document.getElementById('addBanDuration').required = false;
        submitLabel.textContent = 'Apply Permanent Ban';
        // Ban active
        btnBan.className = 'action-type-btn relative flex flex-col items-center gap-2 px-4 py-4 border-2 border-red-500 bg-red-50 text-red-700 rounded-2xl transition-all font-black text-xs uppercase tracking-widest shadow-sm';
        checkBan.className = 'absolute top-2 right-2 w-4 h-4 bg-red-500 rounded-full flex items-center justify-center';
        // Suspend inactive
        btnSuspend.className = 'action-type-btn relative flex flex-col items-center gap-2 px-4 py-4 border-2 border-slate-200 bg-slate-50 text-slate-400 rounded-2xl transition-all font-black text-xs uppercase tracking-widest';
        checkSuspend.className = 'absolute top-2 right-2 w-4 h-4 bg-slate-200 rounded-full hidden items-center justify-center';
    }
}

/* Driver Search Dropdown */
function showDriverDropdown() {
    document.getElementById('driverDropdown').classList.remove('hidden');
    document.addEventListener('click', hideDriverDropdownOnOutside, true);
}
function hideDriverDropdownOnOutside(e) {
    const container = document.getElementById('driverSelectContainer');
    if (!container.contains(e.target)) {
        document.getElementById('driverDropdown').classList.add('hidden');
        document.removeEventListener('click', hideDriverDropdownOnOutside, true);
    }
}
function filterDriverList(query) {
    const items = document.querySelectorAll('.driver-search-item');
    const lq = query.toLowerCase();
    let visible = 0;
    items.forEach(item => {
        const name = (item.getAttribute('data-name') || '').toLowerCase();
        if (name.includes(lq)) { item.classList.remove('hidden'); visible++; }
        else { item.classList.add('hidden'); }
    });
    document.getElementById('driverDropdown').classList.remove('hidden');
}
function selectDriver(id, name, status) {
    document.getElementById('selectedDriverId').value = id;
    document.getElementById('driverSearchInput').value = name;
    document.getElementById('driverDropdown').classList.add('hidden');
}

function submitAddBanSuspend(event) {
    event.preventDefault();
    const driverId = document.getElementById('selectedDriverId').value;
    if (!driverId) { alert('Please select a driver first.'); return; }
    const actionType = document.getElementById('addBanActionType').value;
    const durationDays = document.getElementById('addBanDuration').value.trim();
    const reason = document.getElementById('addBanReason').value.trim();

    if (!reason || reason.length < 5) { alert('Please provide a reason (minimum 5 characters).'); return; }
    if (actionType === 'suspend') {
        const days = parseInt(durationDays, 10);
        if (isNaN(days) || days < 1 || days > 365) { alert('Suspension duration must be between 1 and 365 days.'); return; }
    }

    const confirmMsg = actionType === 'suspend'
        ? `Confirm SUSPEND for ${document.getElementById('driverSearchInput').value} for ${durationDays} day(s)?`
        : `Confirm PERMANENT BAN for ${document.getElementById('driverSearchInput').value}?`;
    if (!confirm(confirmMsg)) return;

    const btn = document.getElementById('addBanSubmitBtn');
    const origHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span> Processing...';

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    fetch(`/driver-management/${driverId}/suspend-or-ban`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: JSON.stringify({ action_type: actionType, duration_days: actionType === 'suspend' ? durationDays : null, reason: reason })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            closeAddBanModal();
            if (typeof showNotification === 'function') showNotification(data.message, 'success');
            setTimeout(() => window.location.reload(), 800);
        } else {
            alert('Error: ' + (data.message || 'Failed to apply lockout.'));
            btn.disabled = false;
            btn.innerHTML = origHtml;
        }
    })
    .catch(err => {
        console.error(err);
        alert('Failed to apply lockout. Please try again.');
        btn.disabled = false;
        btn.innerHTML = origHtml;
    });
}

/* ═══════════════════════════════════════════════════════
   LOCKOUT & DEBT BREAKDOWN MODAL LOGIC
═══════════════════════════════════════════════════════ */
async function openChangeSuspensionModal(driverId, driverName, currentStatus) {
    const modal = document.getElementById('changeSuspensionModal');
    if (!modal) return;

    document.getElementById('changeSuspendDriverId').value = driverId;
    document.getElementById('modalDriverName').textContent = driverName;
    document.getElementById('changeSuspendSubtitle').textContent = `DRV-${String(driverId).padStart(4, '0')} • Lockout & Dues Breakdown`;
    
    const statusBadge = document.getElementById('modalDriverStatusBadge');
    if (statusBadge) {
        if (currentStatus === 'suspended') {
            statusBadge.className = 'px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/30';
            statusBadge.textContent = 'Suspended';
        } else {
            statusBadge.className = 'px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider bg-red-500/20 text-red-300 border border-red-500/30';
            statusBadge.textContent = 'Banned';
        }
    }

    document.getElementById('changeSuspendActionType').value = currentStatus === 'suspended' ? 'suspend' : 'ban';
    document.getElementById('changeSuspendDuration').value = '7';
    document.getElementById('changeSuspendReason').value = '';
    document.getElementById('changeReasonCount').textContent = '0 / 500';
    toggleChangeDuration();

    // Reset breakdown display to loading
    document.getElementById('modalTotalDues').textContent = '₱0.00';
    document.getElementById('modalOverdueDaysCount').textContent = '0 Days';
    document.getElementById('modalUnitPlate').textContent = 'Loading...';
    document.getElementById('modalBoundaryRate').textContent = 'Rate: ₱0.00/day';
    document.getElementById('modalBreakdownTbody').innerHTML = `
        <tr>
            <td colspan="4" class="px-4 py-8 text-center text-slate-400 font-bold">
                <span class="inline-block w-4 h-4 border-2 border-amber-500 border-t-transparent rounded-full animate-spin mr-2"></span>
                Fetching driver dues & incident history...
            </td>
        </tr>
    `;

    modal.classList.remove('hidden');
    modal.style.display = 'flex';
    if (typeof lucide !== 'undefined') lucide.createIcons();

    // Fetch real-time breakdown data via AJAX
    try {
        const res = await fetch(`/driver-management/${driverId}/lockout-details`, {
            headers: { 'Accept': 'application/json' }
        });
        const data = await res.json();
        
        if (data.success) {
            const d = data.driver;
            const charges = data.unpaid_charges || [];
            const totalDues = data.total_unpaid_amount || 0;
            const missedDays = data.missed_boundary_days_count || 0;

            document.getElementById('modalTotalDues').textContent = '₱' + parseFloat(totalDues).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('modalOverdueDaysCount').textContent = `${missedDays} ${missedDays === 1 ? 'Day' : 'Days'}`;
            document.getElementById('modalUnitPlate').textContent = d.current_unit_plate || 'Unassigned / Returned';
            document.getElementById('modalBoundaryRate').textContent = d.unit_boundary_rate ? `Daily Rate: ₱${parseFloat(d.unit_boundary_rate).toLocaleString('en-US', {minimumFractionDigits: 2})}` : 'No Active Rate';
            document.getElementById('breakdownCount').textContent = `${charges.length} ${charges.length === 1 ? 'Record' : 'Records'}`;

            const tbody = document.getElementById('modalBreakdownTbody');
            if (charges.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-slate-400 font-bold">
                            <i data-lucide="check-circle" class="w-6 h-6 mx-auto mb-1 text-emerald-500 opacity-80"></i>
                            No pending boundary dues or unpaid charges recorded.
                        </td>
                    </tr>
                `;
            } else {
                tbody.innerHTML = charges.map(c => {
                    const formattedDate = c.incident_date ? new Date(c.incident_date).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' }) : 'N/A';
                    const amount = parseFloat(c.remaining_balance || c.total_charge_to_driver || 0);
                    const isMissed = (c.incident_type || '').toLowerCase().includes('missed');
                    
                    return `
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap text-[11px] font-black text-slate-800">
                                <span class="inline-flex items-center gap-1">
                                    <i data-lucide="calendar" class="w-3 h-3 text-slate-400"></i>
                                    ${formattedDate}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider ${isMissed ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-red-100 text-red-800 border border-red-200'}">
                                    ${c.incident_type || 'Charge'}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-[11px] text-slate-600">
                                <p class="line-clamp-2 italic font-medium">"${c.description || 'Missed boundary charge'}"</p>
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <span class="text-xs font-black text-red-600">
                                    ₱${amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                                </span>
                            </td>
                        </tr>
                    `;
                }).join('');
            }
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    } catch(err) {
        console.error('Failed to load driver lockout details:', err);
    }
}

function closeChangeSuspensionModal() {
    const modal = document.getElementById('changeSuspensionModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.style.display = 'none';
    }
}

function toggleChangeDuration() {
    const type = document.getElementById('changeSuspendActionType').value;
    const section = document.getElementById('changeDurationSection');
    const presets = document.getElementById('changeDurationPresets');
    const input = document.getElementById('changeSuspendDuration');
    if (type === 'suspend') { 
        if (section) section.style.display = 'block'; 
        if (presets) presets.style.display = 'flex';
        if (input) input.required = true; 
    } else { 
        if (section) section.style.display = 'none'; 
        if (presets) presets.style.display = 'none';
        if (input) input.required = false; 
    }
}

function performUnbanFromModal() {
    const driverId = document.getElementById('changeSuspendDriverId').value;
    const driverName = document.getElementById('modalDriverName').textContent;
    if (!driverId) return;
    closeChangeSuspensionModal();
    performUnban(driverId, driverName);
}

function submitChangeSuspension(event) {
    event.preventDefault();
    const driverId = document.getElementById('changeSuspendDriverId').value;
    const actionType = document.getElementById('changeSuspendActionType').value;
    const durationDays = document.getElementById('changeSuspendDuration').value.trim();
    const reason = document.getElementById('changeSuspendReason').value.trim();

    if (!reason || reason.length < 5) { alert('Please provide a reason (minimum 5 characters).'); return; }
    if (actionType === 'suspend') {
        const days = parseInt(durationDays, 10);
        if (isNaN(days) || days < 1 || days > 365) { alert('Duration must be between 1 and 365 days.'); return; }
    }

    const btn = document.getElementById('changeSuspendSubmitBtn');
    const origHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="inline-block w-4 h-4 border-2 border-slate-950 border-t-transparent rounded-full animate-spin"></span> Updating...';

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    fetch(`/driver-management/${driverId}/suspend-or-ban`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: JSON.stringify({ action_type: actionType, duration_days: actionType === 'suspend' ? durationDays : null, reason: reason })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            closeChangeSuspensionModal();
            alert(data.message || 'Driver status updated successfully.');
            setTimeout(() => window.location.reload(), 500);
        } else {
            alert('Error: ' + (data.message || 'Failed.'));
            btn.disabled = false;
            btn.innerHTML = origHtml;
        }
    })
    .catch(err => {
        console.error(err);
        alert('Failed. Please try again.');
        btn.disabled = false;
        btn.innerHTML = origHtml;
    });
}

/* ════════════════════════════════════════════════════════
   AUTO-BAN & LOCKOUT POLICY SETTINGS LOGIC
════════════════════════════════════════════════════════ */
function openAutoBanSettingsModal() {
    const modal = document.getElementById('autoBanSettingsModal');
    if (!modal) return;
    modal.classList.remove('hidden');
    setTimeout(() => modal.classList.add('open'), 10);
    updatePolicySimulator();
    if (window.lucide) lucide.createIcons();
}

function closeAutoBanSettingsModal() {
    const modal = document.getElementById('autoBanSettingsModal');
    if (!modal) return;
    modal.classList.remove('open');
    setTimeout(() => modal.classList.add('hidden'), 200);
}

function adjustSettingDays(inputId, amount) {
    const input = document.getElementById(inputId);
    if (!input) return;
    let val = parseInt(input.value, 10) || 1;
    val += amount;
    const min = parseInt(input.min, 10) || 1;
    const max = parseInt(input.max, 10) || 30;
    if (val < min) val = min;
    if (val > max) val = max;
    input.value = val;
    updatePolicySimulator();
}

function onActionTypeChange(action) {
    const durContainer = document.getElementById('suspensionDurationContainer');
    const labelBanned = document.getElementById('label_action_banned');
    const labelSuspended = document.getElementById('label_action_suspended');

    if (action === 'suspended') {
        if (durContainer) durContainer.classList.remove('hidden');
        if (labelSuspended) labelSuspended.className = 'relative flex items-center p-3.5 border-2 border-amber-500 bg-amber-50/50 rounded-2xl cursor-pointer transition-all';
        if (labelBanned) labelBanned.className = 'relative flex items-center p-3.5 border-2 border-slate-200 hover:bg-slate-50 rounded-2xl cursor-pointer transition-all';
    } else {
        if (durContainer) durContainer.classList.add('hidden');
        if (labelBanned) labelBanned.className = 'relative flex items-center p-3.5 border-2 border-red-500 bg-red-50/50 rounded-2xl cursor-pointer transition-all';
        if (labelSuspended) labelSuspended.className = 'relative flex items-center p-3.5 border-2 border-slate-200 hover:bg-slate-50 rounded-2xl cursor-pointer transition-all';
    }
    updatePolicySimulator();
}

function updatePolicySimulator() {
    const isEnabled = document.getElementById('setting_auto_ban_enabled')?.checked ?? true;
    const overdueDays = parseInt(document.getElementById('setting_overdue_days')?.value, 10) || 3;
    const actionRadios = document.getElementsByName('setting_action_type');
    let actionType = 'banned';
    for (const r of actionRadios) {
        if (r.checked) { actionType = r.value; break; }
    }
    const suspDays = parseInt(document.getElementById('setting_suspension_days')?.value, 10) || 7;

    const badge = document.getElementById('simulatorStatusBadge');
    if (badge) {
        if (isEnabled) {
            badge.className = 'px-2 py-0.5 bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 rounded-md text-[9px] font-black uppercase tracking-wider';
            badge.textContent = 'Enforcing Active';
        } else {
            badge.className = 'px-2 py-0.5 bg-rose-500/20 text-rose-300 border border-rose-500/40 rounded-md text-[9px] font-black uppercase tracking-wider';
            badge.textContent = 'Automation Disabled';
        }
    }

    const expText = document.getElementById('simulatorExplanationText');
    if (expText) {
        if (!isEnabled) {
            expText.innerHTML = `
                <div class="text-rose-300 font-bold flex items-center gap-1.5">
                    <span>⚠️</span> Auto-Lockout is currently <strong>TURNED OFF</strong>. No drivers will be automatically suspended or banned based on unreturned vehicles.
                </div>
            `;
        } else {
            const actionLabel = actionType === 'banned' 
                ? '<strong class="text-red-400 font-black tracking-wide">PERMANENTLY BANNED</strong> (Blacklisted & Mobile Account Revoked)' 
                : `<strong class="text-amber-400 font-black tracking-wide">SUSPENDED FOR ${suspDays} DAYS</strong> (Temporary Lockout)`;

            expText.innerHTML = `
                <div class="space-y-2">
                    <p>• <strong>Vehicle & Boundary Trigger:</strong> Driver holds the taxi for <strong class="text-amber-400 font-bold">${overdueDays} overdue days</strong> past shift deadline without returning.</p>
                    <p>• <strong>Daily Charge Accumulation:</strong> Daily missed boundary fees (₱ rate) are automatically billed for each overdue day.</p>
                    <p>• <strong>Automated Lockout Penalty:</strong> Upon reaching Day ${overdueDays}, the driver is automatically ${actionLabel} at 11:59 PM shift deadline.</p>
                </div>
            `;
        }
    }
}

async function submitAutoBanSettings(e) {
    e.preventDefault();
    const btn = document.getElementById('saveAutoBanSettingsBtn');
    const origHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="inline-block w-4 h-4 border-2 border-slate-900 border-t-transparent rounded-full animate-spin"></span> Saving...';

    const isEnabled = document.getElementById('setting_auto_ban_enabled').checked ? 1 : 0;
    const overdueDays = parseInt(document.getElementById('setting_overdue_days').value, 10) || 3;
    const suspDays = parseInt(document.getElementById('setting_suspension_days').value, 10) || 7;
    
    let actionType = 'banned';
    const actionRadios = document.getElementsByName('setting_action_type');
    for (const r of actionRadios) {
        if (r.checked) { actionType = r.value; break; }
    }

    const payload = {
        auto_ban_enabled: isEnabled,
        auto_ban_missed_boundary_days: overdueDays,
        auto_ban_overdue_unit_days: overdueDays,
        auto_ban_critical_incidents_threshold: 1,
        auto_ban_default_suspension_days: suspDays,
        auto_ban_action_type: actionType,
    };

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
        const res = await fetch('{{ route("driver-management.update-auto-ban-settings") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(payload)
        });

        const result = await res.json();
        if (res.ok && result.success) {
            // Update the awareness banner values instantly without reload
            const bannerOverdue = document.getElementById('bannerOverdueDays');
            const bannerAction = document.getElementById('bannerActionType');
            const policyBadge = document.getElementById('policyStatusBadge');

            if (bannerOverdue) bannerOverdue.textContent = overdueDays;
            if (bannerAction) {
                bannerAction.textContent = actionType.toUpperCase();
                bannerAction.className = `uppercase font-black ${actionType === 'banned' ? 'text-red-600' : 'text-amber-600'}`;
            }
            if (policyBadge) {
                if (isEnabled) {
                    policyBadge.className = 'px-2 py-0.5 bg-emerald-100 text-emerald-800 border border-emerald-200 rounded-md text-[10px] font-black uppercase tracking-wider';
                    policyBadge.textContent = 'ACTIVE & ENFORCING';
                } else {
                    policyBadge.className = 'px-2 py-0.5 bg-slate-100 text-slate-600 border border-slate-200 rounded-md text-[10px] font-black uppercase tracking-wider';
                    policyBadge.textContent = 'DISABLED';
                }
            }

            closeAutoBanSettingsModal();
            alert(result.message || 'Auto-ban policy updated successfully.');
        } else {
            alert(result.message || 'Failed to update auto-ban settings.');
        }
    } catch(err) {
        console.error('Save Auto-Ban Settings Error:', err);
        alert('A network error occurred while saving policy settings.');
    } finally {
        btn.disabled = false;
        btn.innerHTML = origHtml;
    }
}

/* Close modals on Escape */
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { 
        closeAddBanModal(); 
        closeChangeSuspensionModal(); 
        closeAutoBanSettingsModal();
    }
});

/* Event listeners for driver card buttons to stop propagation safely */
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.restore-driver-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            performUnban(this.getAttribute('data-id'), this.getAttribute('data-name'));
        });
    });

    document.querySelectorAll('.modify-suspension-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            openChangeSuspensionModal(
                this.getAttribute('data-id'),
                this.getAttribute('data-name'),
                this.getAttribute('data-status')
            );
        });
    });

    const urlParams = new URLSearchParams(window.location.search);
    const suspendDriverId = urlParams.get('suspend_driver_id');
    if (suspendDriverId) {
        const items = document.querySelectorAll('.driver-search-item');
        let selectedItem = null;
        items.forEach(item => {
            if (item.getAttribute('data-id') == suspendDriverId) {
                selectedItem = item;
            }
        });
        if (selectedItem) {
            const driverName = selectedItem.getAttribute('data-name');
            const driverStatus = selectedItem.getAttribute('data-status');
            selectDriver(suspendDriverId, driverName, driverStatus);
            openAddBanModal();
            const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
            window.history.replaceState({path:newUrl}, '', newUrl);
        }
    }
});
</script>

@include('driver-management.partials._driver_details_modal')
@endsection

