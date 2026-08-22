@extends('layouts.app')
@section('title', 'Driver Performance - Euro System')
@section('page-heading', 'Driver Performance & Violations')
@section('page-subheading', 'Incidents • Incentives • Performance Summary — All in one place')

@section('content')
<style>
    .tab-btn { 
        padding: 0.625rem 1.25rem;
        font-size: 0.75rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        cursor: pointer;
    }
    .tab-btn.active { 
        background-color: #eab308; 
        color: white; 
        box-shadow: 0 10px 15px -3px rgba(234, 179, 8, 0.3);
        border: 1px solid #eab308;
    }
    .tab-btn:not(.active) { 
        background-color: white; 
        color: #6b7280; 
        border: 1px solid #f3f4f6; 
    }
    .tab-btn:not(.active):hover { 
        background-color: #fefce8; 
        color: #ca8a04; 
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 20px 25px -5px rgba(234, 179, 8, 0.1);
        border-color: #fde047;
    }
    .tab-btn:active { transform: scale(0.95); }
    .incident-tag { @apply px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest border; }
    .stat-card-premium { @apply transition-all duration-500 cursor-default; }
    .custom-scroll::-webkit-scrollbar { width: 4px; }
    .custom-scroll::-webkit-scrollbar-thumb { background: #eab308; border-radius: 99px; }
    
    .search-dropdown {
        display: none;
        position: absolute;
        z-index: 50;
        width: 100%;
        margin-top: 0.25rem;
        background-color: white;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        max-height: 10rem;
        overflow-y: auto;
        flex-direction: column;
    }
    .search-dropdown:not(.hidden) { display: flex; }
    .search-option { padding: 0.5rem 0.75rem; cursor: pointer; border-bottom: 1px solid #f3f4f6; }
    .search-option:last-child { border-bottom: none; }
    .cls-tab-btn.active {
        color: #111827;
        position: relative;
    }
    .cls-tab-btn.active::after {
        content: '';
        position: absolute;
        bottom: -17px;
        left: 0;
        right: 0;
        height: 3px;
        background: #eab308;
        border-radius: 99px;
    }
    
    #sa-toast {
        position: fixed; bottom: 1.5rem; left: 50%; transform: translateX(-50%) translateY(10rem); opacity: 0; visibility: hidden;
        background: #1e293b; border: 1px solid #eab308; color: #ffffff;
        padding: .85rem 1.75rem; border-radius: 999px; font-size: .85rem; font-weight: 600;
        box-shadow: 0 12px 40px rgba(0,0,0,.6);
        z-index: 9999; transition: transform .4s cubic-bezier(.34,1.56,.64,1);
        max-width: 90vw; display: flex; align-items: center; gap: .75rem;
     opacity: 0; visibility: hidden; }
    #sa-toast.show { transform: translateX(-50%) translateY(0);  opacity: 1; visibility: visible; }
    #sa-toast.error { border-color: #ef4444; }

    /* ── Modal ── */
    .sa-modal-backdrop {
        position: fixed; inset: 0; background: rgba(0,0,0,.75); backdrop-filter: blur(4px);
        z-index: 9990; display: none; align-items: center; justify-content: center;
    }
    .sa-modal-backdrop.open { display: flex; }
    .sa-modal {
        background: #fff; border: 1px solid #e2e8f0; border-radius: 2rem;
        padding: 2rem; width: 100%; max-width: 520px; max-height: 90vh; overflow-y: auto;
        box-shadow: 0 24px 80px rgba(0,0,0,.7);
        animation: modal-in .25s ease;
    }
    @keyframes modal-in { from { opacity:0; transform:scale(.94) translateY(1rem); } to { opacity:1; transform:none; } }

    .btn-danger { background:#7f1d1d; color:#f87171; border:1px solid #991b1b; border-radius:.5rem; padding:.3rem .9rem; font-size:.72rem; font-weight:700; cursor:pointer; transition:all .2s; }
    .btn-ghost   { background:transparent; color:#64748b; border:1px solid #e2e8f0; border-radius:.5rem; padding:.3rem .9rem; font-size:.72rem; font-weight:700; cursor:pointer; transition:all .2s; }
    .btn-ghost:hover   { background:rgba(0,0,0,0.04); color:#1e293b; }
    .sa-input {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #1e293b;
        border-radius: .6rem;
        padding: .5rem 1rem;
        font-size: .82rem;
        outline: none;
        transition: border-color .2s;
        width: 100%;
    }
    .sa-input:focus { border-color: #eab308; }

    @keyframes blob {
        0% { transform: translate(-100%, -100%); }
        25% { transform: translate(20%, -100%); }
        50% { transform: translate(20%, 20%); }
        75% { transform: translate(-100%, 20%); }
        100% { transform: translate(-100%, -100%); }
    }
    .animate-blob { animation: blob 5s linear infinite; }
    .animate-blob-slow { animation: blob 7s linear infinite; }
</style>

{{-- ════════ HEADER STATS (COMPACT) ════════ --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    {{-- 1. VIOLATIONS TODAY --}}
    <div class="cursor-default group relative overflow-hidden rounded-2xl shadow-lg border border-white/60 p-[3px] bg-slate-100/50 transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl hover:border-red-400/80">
        {{-- Animated Gradient Blob --}}
        <div class="absolute top-1/2 left-1/2 w-[220px] h-[220px] rounded-full opacity-100 filter blur-[14px] z-0 animate-blob bg-gradient-to-r from-red-600 via-rose-400 to-orange-500 pointer-events-none"></div>

        {{-- Glassy Card Content Container --}}
        <div class="relative w-full h-full bg-white/90 backdrop-blur-xl rounded-[13px] outline outline-1 outline-white/80 p-3.5 sm:p-5 flex items-center justify-between z-10 overflow-hidden">
            {{-- Hover Gradient Overlay --}}
            <div class="opacity-0 group-hover:opacity-100 transition-all duration-300 absolute inset-0 h-full w-full bg-gradient-to-t from-red-200/80 via-red-100/40 to-transparent pointer-events-none rounded-[13px] z-0"></div>

            {{-- Left Accent Pill --}}
            <div class="absolute left-0 inset-y-0 h-5 group-hover:h-9 w-1.5 rounded-tr-full rounded-br-full bg-slate-300 group-hover:bg-red-600 transition-all duration-300 origin-center my-auto z-10"></div>

            <div class="flex-1 min-w-0 relative z-10 pl-1.5">
                <p class="text-red-500 text-[9px] sm:text-[10px] font-bold uppercase tracking-widest mb-1 group-hover:translate-x-1.5 transition-transform duration-300 inline-block">Violations Today</p>
                <p class="text-slate-800 text-xl sm:text-3xl font-bold tracking-tight leading-none mb-1 group-hover:translate-x-1 transition-transform duration-300">{{ $stats['violations_today'] ?? 0 }}</p>
            </div>
        </div>
        {{-- 3D Graphic Element in Background --}}
        <img src="{{ asset('image/kpi/violation_3d.svg') }}" alt="Violations 3D" class="absolute -right-3 -bottom-3 w-24 h-24 sm:w-28 sm:h-28 object-contain pointer-events-none opacity-40 group-hover:opacity-85 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300" style="z-index: 12 !important;">
    </div>

    {{-- 2. TOTAL VIOLATORS --}}
    <div class="cursor-default group relative overflow-hidden rounded-2xl shadow-lg border border-white/60 p-[3px] bg-slate-100/50 transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl hover:border-teal-400/80">
        {{-- Animated Gradient Blob --}}
        <div class="absolute top-1/2 left-1/2 w-[220px] h-[220px] rounded-full opacity-100 filter blur-[14px] z-0 animate-blob-slow bg-gradient-to-r from-teal-500 via-emerald-400 to-cyan-500 pointer-events-none"></div>

        {{-- Glassy Card Content Container --}}
        <div class="relative w-full h-full bg-white/90 backdrop-blur-xl rounded-[13px] outline outline-1 outline-white/80 p-3.5 sm:p-5 flex items-center justify-between z-10 overflow-hidden">
            {{-- Hover Gradient Overlay --}}
            <div class="opacity-0 group-hover:opacity-100 transition-all duration-300 absolute inset-0 h-full w-full bg-gradient-to-t from-teal-200/80 via-teal-100/40 to-transparent pointer-events-none rounded-[13px] z-0"></div>

            {{-- Left Accent Pill --}}
            <div class="absolute left-0 inset-y-0 h-5 group-hover:h-9 w-1.5 rounded-tr-full rounded-br-full bg-slate-300 group-hover:bg-teal-600 transition-all duration-300 origin-center my-auto z-10"></div>

            <div class="flex-1 min-w-0 relative z-10 pl-1.5">
                <p class="text-teal-600 text-[9px] sm:text-[10px] font-bold uppercase tracking-widest mb-1 group-hover:translate-x-1.5 transition-transform duration-300 inline-block">Total Violators</p>
                <p class="text-slate-800 text-xl sm:text-3xl font-bold tracking-tight leading-none mb-1 group-hover:translate-x-1 transition-transform duration-300">{{ $stats['total_violators'] ?? 0 }}</p>
            </div>
        </div>
        {{-- 3D Graphic Element in Background --}}
        <img src="{{ asset('image/kpi/violators_3d.svg') }}" alt="Violators 3D" class="absolute -right-3 -bottom-3 w-24 h-24 sm:w-28 sm:h-28 object-contain pointer-events-none opacity-40 group-hover:opacity-85 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300" style="z-index: 12 !important;">
    </div>

    {{-- 3. TOTAL CHARGES --}}
    <div class="cursor-default group relative overflow-hidden rounded-2xl shadow-lg border border-white/60 p-[3px] bg-slate-100/50 transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl hover:border-purple-400/80">
        {{-- Animated Gradient Blob --}}
        <div class="absolute top-1/2 left-1/2 w-[220px] h-[220px] rounded-full opacity-100 filter blur-[14px] z-0 animate-blob bg-gradient-to-r from-purple-600 via-fuchsia-400 to-indigo-500 pointer-events-none"></div>

        {{-- Glassy Card Content Container --}}
        <div class="relative w-full h-full bg-white/90 backdrop-blur-xl rounded-[13px] outline outline-1 outline-white/80 p-3.5 sm:p-5 flex items-center justify-between z-10 overflow-hidden">
            {{-- Hover Gradient Overlay --}}
            <div class="opacity-0 group-hover:opacity-100 transition-all duration-300 absolute inset-0 h-full w-full bg-gradient-to-t from-purple-200/80 via-purple-100/40 to-transparent pointer-events-none rounded-[13px] z-0"></div>

            {{-- Left Accent Pill --}}
            <div class="absolute left-0 inset-y-0 h-5 group-hover:h-9 w-1.5 rounded-tr-full rounded-br-full bg-slate-300 group-hover:bg-purple-600 transition-all duration-300 origin-center my-auto z-10"></div>

            <div class="flex-1 min-w-0 relative z-10 pl-1.5">
                <p class="text-purple-600 text-[9px] sm:text-[10px] font-bold uppercase tracking-widest mb-1 group-hover:translate-x-1.5 transition-transform duration-300 inline-block">Total Charges</p>
                <p class="text-slate-800 text-xl sm:text-3xl font-bold tracking-tight leading-none mb-1 group-hover:translate-x-1 transition-transform duration-300">₱{{ number_format($stats['total_charges'] ?? 0, 0) }}</p>
            </div>
        </div>
        {{-- 3D Graphic Element in Background --}}
        <img src="{{ asset('image/kpi/charges_3d.svg') }}" alt="Charges 3D" class="absolute -right-3 -bottom-3 w-24 h-24 sm:w-28 sm:h-28 object-contain pointer-events-none opacity-40 group-hover:opacity-85 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300" style="z-index: 12 !important;">
    </div>

    {{-- 4. ELIGIBLE INCENTIVE --}}
    <div class="cursor-default group relative overflow-hidden rounded-2xl shadow-lg border border-white/60 p-[3px] bg-slate-100/50 transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl hover:border-yellow-400/80">
        {{-- Animated Gradient Blob --}}
        <div class="absolute top-1/2 left-1/2 w-[220px] h-[220px] rounded-full opacity-100 filter blur-[14px] z-0 animate-blob-slow bg-gradient-to-r from-amber-500 via-yellow-400 to-orange-400 pointer-events-none"></div>

        {{-- Glassy Card Content Container --}}
        <div class="relative w-full h-full bg-white/90 backdrop-blur-xl rounded-[13px] outline outline-1 outline-white/80 p-3.5 sm:p-5 flex items-center justify-between z-10 overflow-hidden">
            {{-- Hover Gradient Overlay --}}
            <div class="opacity-0 group-hover:opacity-100 transition-all duration-300 absolute inset-0 h-full w-full bg-gradient-to-t from-yellow-200/80 via-yellow-100/40 to-transparent pointer-events-none rounded-[13px] z-0"></div>

            {{-- Left Accent Pill --}}
            <div class="absolute left-0 inset-y-0 h-5 group-hover:h-9 w-1.5 rounded-tr-full rounded-br-full bg-slate-300 group-hover:bg-amber-500 transition-all duration-300 origin-center my-auto z-10"></div>

            <div class="flex-1 min-w-0 relative z-10 pl-1.5">
                <p class="text-amber-600 text-[9px] sm:text-[10px] font-bold uppercase tracking-widest mb-1 group-hover:translate-x-1.5 transition-transform duration-300 inline-block">Eligible Incentive</p>
                <p class="text-slate-800 text-xl sm:text-3xl font-bold tracking-tight leading-none mb-1 group-hover:translate-x-1 transition-transform duration-300">{{ count($incentive_summary['eligible'] ?? []) }}</p>
            </div>
        </div>
        {{-- 3D Graphic Element in Background --}}
        <img src="{{ asset('image/kpi/incentive_3d.svg') }}" alt="Incentive 3D" class="absolute -right-3 -bottom-3 w-24 h-24 sm:w-28 sm:h-28 object-contain pointer-events-none opacity-40 group-hover:opacity-85 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300" style="z-index: 12 !important;">
    </div>
</div>

    <div class="mb-4 flex flex-col sm:flex-row gap-3">
        <!-- Hidden input to trick browser autofill -->
        <input type="text" style="display:none" autocomplete="username">
        <input type="search" id="profileSearch" placeholder="Search driver name..." name="perf_search_query"
            class="w-full sm:flex-1 md:w-80 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-yellow-500 focus:outline-none shadow-sm"
            autocomplete="new-password" spellcheck="false" autocorrect="off" autocapitalize="off" readonly onfocus="this.removeAttribute('readonly');">
        
        <select id="profileStatusFilter" class="w-full sm:w-64 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-yellow-500 focus:outline-none shadow-sm">
            <option value="all">All Drivers</option>
            <option value="violators_today">Violators Today</option>
            <option value="total_violators">Total Violators</option>
            <option value="eligible">Eligible Incentives</option>
        </select>
    </div>

    <div id="profileGrid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach($driver_profiles as $profile)
        @php
            $inc = $profile['incentive'];
            $eligible = $inc['eligible'];
        @endphp
        <div class="profile-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all cursor-pointer" 
            data-name="{{ strtolower($profile['name']) }}" 
            data-eligible="{{ $eligible ? '1' : '0' }}"
            data-violators-today="{{ $profile['incidents_today'] > 0 ? '1' : '0' }}"
            data-total-violators="{{ $profile['incidents'] > 0 ? '1' : '0' }}"
            onclick="openDriverDetails({{ $profile['id'] }})">
            {{-- Card Header --}}
            <div class="p-5 border-b border-gray-50 flex items-center gap-3 {{ $eligible ? 'bg-gradient-to-r from-green-50 to-emerald-50' : 'bg-gray-50/50' }}">
                <div class="w-11 h-11 rounded-xl {{ $eligible ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center text-white font-black text-lg shadow-sm flex-shrink-0">
                    {{ strtoupper(substr($profile['name'], 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-black text-sm text-gray-800 truncate">{{ $profile['name'] }}</p>
                    <p class="text-[10px] font-bold text-blue-600 uppercase">{{ $profile['unit'] ?? 'No Unit Assigned' }}</p>
                </div>
                <div>
                    @if($eligible)
                        <span class="text-[9px] font-black px-2 py-1 bg-green-500 text-white rounded-xl shadow-sm">✓ ELIGIBLE</span>
                    @else
                        <span class="text-[9px] font-black px-2 py-1 bg-red-100 text-red-600 rounded-xl border border-red-200">✗ NOT YET</span>
                    @endif
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-3 divide-x divide-gray-50 border-b border-gray-50">
                <div class="p-3 text-center">
                    <p class="text-lg font-black text-gray-800">{{ $profile['incidents'] }}</p>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-wider">Incidents</p>
                </div>
                <div class="p-3 text-center">
                    <p class="text-lg font-black text-gray-800">{{ $profile['boundaries'] }}</p>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-wider">Shifts</p>
                </div>
                <div class="p-3 text-center">
                    <p class="text-lg font-black {{ $profile['charges'] > 0 ? 'text-red-600' : 'text-green-600' }}">₱{{ number_format($profile['charges'], 0) }}</p>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-wider">Charges</p>
                </div>
            </div>

            {{-- Incentive Progress --}}
            <div class="p-4">
                <div class="flex justify-between items-center mb-1.5">
                    <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">{{ $inc['driver_type'] }}</span>
                    <span class="text-[10px] font-bold text-gray-500">{{ $inc['valid_days'] }}/{{ $inc['required_days'] }} valid days</span>
                </div>
                <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-2 rounded-full transition-all {{ $eligible ? 'bg-green-500' : 'bg-yellow-400' }}"
                        style="width: {{ min(100, ($inc['valid_days'] / $inc['required_days']) * 100) }}%"></div>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-[10px] text-gray-400">{{ $inc['violations'] }} violation(s)</span>
                    <span class="text-[10px] font-bold text-gray-500">Next: {{ $inc['next_payout_date'] }}</span>
                </div>
                @if($profile['total_debt'] > 0)
                <div class="mt-2 flex items-center gap-1.5 text-[10px] font-bold text-red-600 bg-red-50 px-2 py-1 rounded-lg border border-red-100">
                    <i data-lucide="alert-circle" class="w-3 h-3 text-red-500"></i> Pending Debt: ₱{{ number_format($profile['total_debt'], 2) }}
                </div>
                @endif
                @if($profile['shortages'] > 0)
                <div class="mt-2 flex items-center gap-1.5 text-[10px] font-bold text-orange-600">
                    <i data-lucide="trending-down" class="w-3 h-3"></i> Total Shortage: ₱{{ number_format($profile['shortages'], 2) }}
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
@include('driver-management.partials._driver_details_modal')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('profileSearch');
    const statusSelect = document.getElementById('profileStatusFilter');
    const cards = document.querySelectorAll('.profile-card');

    if (searchInput) {
        searchInput.value = '';
    }

    window.filterProfiles = function() {
        const term = searchInput ? searchInput.value.toLowerCase() : '';
        const status = statusSelect ? statusSelect.value : 'all';

        cards.forEach(card => {
            const name = card.dataset.name || '';
            const isEligible = card.dataset.eligible === '1';
            const isViolatorToday = card.dataset.violatorsToday === '1';
            const isTotalViolator = card.dataset.totalViolators === '1';

            let matchesSearch = name.includes(term);
            let matchesStatus = true;

            if (status === 'violators_today') {
                matchesStatus = isViolatorToday;
            } else if (status === 'total_violators') {
                matchesStatus = isTotalViolator;
            } else if (status === 'eligible') {
                matchesStatus = isEligible;
            }

            if (matchesSearch && matchesStatus) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    };

    if (searchInput) searchInput.addEventListener('input', filterProfiles);
    if (statusSelect) statusSelect.addEventListener('change', filterProfiles);
});
</script>
@endsection
