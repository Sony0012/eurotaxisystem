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

</style>

{{-- ════════════════════════════════════════
     TAB 2: INCENTIVE DASHBOARD
     ════════════════════════════════════════ --}}
<div id="tab-incentives">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
        {{-- 1. ELIGIBLE FOR INCENTIVE --}}
        <div class="relative overflow-hidden rounded-2xl shadow-sm border border-green-200 bg-gradient-to-br from-green-50 to-emerald-50/70 p-4 sm:p-5 flex items-center justify-between">
            <div class="flex-1 min-w-0 relative z-10">
                <p class="text-green-600 text-[9px] sm:text-[10px] font-bold uppercase tracking-widest mb-1">Eligible for Incentive</p>
                <p class="text-slate-800 text-xl sm:text-3xl font-bold tracking-tight leading-none mb-1">{{ count($incentive_summary['eligible'] ?? []) }}</p>
            </div>
            <img src="{{ asset('image/kpi/incentive_3d.svg') }}" alt="Incentive 3D" class="w-16 h-16 sm:w-20 sm:h-20 object-contain pointer-events-none flex-shrink-0">
        </div>

        {{-- 2. DISQUALIFIED --}}
        <div class="relative overflow-hidden rounded-2xl shadow-sm border border-red-200 bg-gradient-to-br from-red-50 to-rose-50/70 p-4 sm:p-5 flex items-center justify-between">
            <div class="flex-1 min-w-0 relative z-10">
                <p class="text-red-500 text-[9px] sm:text-[10px] font-bold uppercase tracking-widest mb-1">Disqualified</p>
                <p class="text-slate-800 text-xl sm:text-3xl font-bold tracking-tight leading-none mb-1">{{ count($incentive_summary['ineligible'] ?? []) }}</p>
            </div>
            <img src="{{ asset('image/kpi/disqualified_3d.svg') }}" alt="Disqualified 3D" class="w-16 h-16 sm:w-20 sm:h-20 object-contain pointer-events-none flex-shrink-0">
        </div>

        {{-- 3. NEXT PAYOUT SUNDAY --}}
        <div class="relative overflow-hidden rounded-2xl shadow-sm border border-yellow-200 bg-gradient-to-br from-yellow-50 to-amber-50/70 p-4 sm:p-5 flex items-center justify-between">
            <div class="flex-1 min-w-0 relative z-10">
                <p class="text-amber-600 text-[9px] sm:text-[10px] font-bold uppercase tracking-widest mb-1">Next Payout Sunday</p>
                @php
                    $now = now()->timezone('Asia/Manila');
                    $firstSundayThisMonth = $now->copy()->startOfMonth();
                    while($firstSundayThisMonth->dayOfWeek !== \Carbon\Carbon::SUNDAY) { $firstSundayThisMonth->addDay(); }
                    
                    if ($now->gt($firstSundayThisMonth->endOfDay())) {
                        // Already passed this month's, target next month
                        $targetDate = $now->copy()->addMonth()->startOfMonth();
                    } else {
                        $targetDate = $now->copy()->startOfMonth();
                    }

                    while($targetDate->dayOfWeek !== \Carbon\Carbon::SUNDAY) { $targetDate->addDay(); }
                @endphp
                <p class="text-slate-800 text-lg sm:text-2xl font-bold tracking-tight leading-none mb-1">{{ $targetDate->format('M d, Y') }}</p>
            </div>
            <img src="{{ asset('image/kpi/payout_3d.svg') }}" alt="Payout 3D" class="w-16 h-16 sm:w-20 sm:h-20 object-contain pointer-events-none flex-shrink-0">
        </div>
    </div>

    {{-- Search and Filter --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-5 p-4 bg-white rounded-2xl shadow-sm border border-gray-100 items-end">
        <div class="flex-1 w-full">
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                </div>
                <!-- Hidden input to trick browser autofill -->
                <input type="text" style="display:none" autocomplete="username">
                <input type="text" id="incentiveSearch" name="incentive_search_query" autocomplete="new-password" placeholder="Search driver or plate number..." 
                    class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium focus:ring-2 focus:ring-yellow-500 focus:outline-none transition-all">
            </div>
        </div>
        <div class="w-full sm:w-64">
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Status</label>
            <select id="incentiveStatus" class="w-full px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                <option value="all">All Drivers</option>
                <option value="eligible">Eligible Only</option>
                <option value="disqualified">Disqualified Only</option>
                <option value="has_violations">Has Violations</option>
                <option value="insufficient_days">Insufficient Days</option>
            </select>
        </div>
    </div>

    {{-- Eligible Drivers --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-5">
        <div class="px-5 py-4 border-b bg-green-50/50 flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4 text-green-600"></i>
            <h3 class="font-black text-sm text-gray-800 uppercase tracking-widest">Eligible Drivers ({{ count($incentive_summary['eligible'] ?? []) }})</h3>
        </div>
        <table class="min-w-full divide-y divide-gray-50">
            <thead class="bg-gray-50/80">
                <tr>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Driver</th>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Unit</th>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Type</th>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Valid Days</th>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Violations</th>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Next Payout</th>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($incentive_summary['eligible'] as $d)
                <tr class="hover:bg-green-50/30 transition-colors incentive-row" data-search="{{ strtolower($d['name'] . ' ' . ($d['unit'] ?? '')) }}" data-status="eligible">
                    <td class="px-5 py-3.5"><span class="text-xs font-black text-gray-800">{{ $d['name'] }}</span></td>
                    <td class="px-5 py-3.5"><span class="text-xs font-black text-blue-600 uppercase">{{ $d['unit'] ?? '—' }}</span></td>
                    <td class="px-5 py-3.5">
                        <span class="text-[9px] font-black px-2 py-0.5 rounded-full {{ $d['driver_type'] === 'Dual Driver' ? 'bg-purple-100 text-purple-700 border border-purple-200' : 'bg-blue-100 text-blue-700 border border-blue-200' }}">{{ $d['driver_type'] }}</span>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-2">
                            <div class="w-20 h-1.5 bg-gray-100 rounded-full"><div class="h-1.5 bg-green-500 rounded-full" style="width:{{ min(100, ($d['valid_days']/20)*100) }}%"></div></div>
                            <span class="text-xs font-black text-green-600">{{ $d['valid_days'] }}/20</span>
                        </div>
                    </td>
                    <td class="px-5 py-3.5"><span class="text-xs font-black {{ $d['violations'] > 0 ? 'text-red-600' : 'text-green-600' }}">{{ $d['violations'] }}</span></td>
                    <td class="px-5 py-3.5"><span class="text-xs font-medium text-gray-600">{{ $d['next_payout'] }}</span></td>
                    <td class="px-5 py-3.5">
                        <form method="POST" action="{{ route('driver-behavior.release-incentive') }}" onsubmit="return confirm('Release incentive for {{ addslashes($d['name']) }}? This will reset their counter.')">
                            @csrf
                            <input type="hidden" name="driver_id" value="{{ $d['driver_id'] }}">
                            <button type="submit" class="px-3 py-1.5 bg-green-600 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-green-700 transition-all">
                                Release ✓
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-8 text-center text-xs text-gray-400 font-medium italic">No drivers eligible yet this period.</td></tr>
                @endforelse
                <tr id="eligibleNoResults" style="display: none;"><td colspan="7" class="px-5 py-8 text-center text-xs text-gray-400 font-medium italic">No matching eligible drivers found.</td></tr>
            </tbody>
        </table>
    </div>

    {{-- Ineligible Drivers --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b bg-red-50/50 flex items-center gap-2">
            <i data-lucide="x-circle" class="w-4 h-4 text-red-500"></i>
            <h3 class="font-black text-sm text-gray-800 uppercase tracking-widest">Disqualified / Pending ({{ count($incentive_summary['ineligible'] ?? []) }})</h3>
        </div>
        <table class="min-w-full divide-y divide-gray-50">
            <thead class="bg-gray-50/80">
                <tr>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Driver</th>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Unit</th>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Type</th>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Valid Days</th>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Violations</th>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($incentive_summary['ineligible'] as $d)
                @php 
                    $reason = $d['violations'] > 0 ? 'Has Violations' : 'Insufficient Days'; 
                    $statusKey = $d['violations'] > 0 ? 'has_violations' : 'insufficient_days';
                @endphp
                <tr class="hover:bg-red-50/20 transition-colors incentive-row" data-search="{{ strtolower($d['name'] . ' ' . ($d['unit'] ?? '')) }}" data-status="{{ $statusKey }}">
                    <td class="px-5 py-3.5"><span class="text-xs font-bold text-gray-700">{{ $d['name'] }}</span></td>
                    <td class="px-5 py-3.5"><span class="text-xs font-black text-blue-600 uppercase">{{ $d['unit'] ?? '—' }}</span></td>
                    <td class="px-5 py-3.5">
                        <span class="text-[9px] font-black px-2 py-0.5 rounded-full {{ $d['driver_type'] === 'Dual Driver' ? 'bg-purple-100 text-purple-700 border border-purple-200' : 'bg-blue-100 text-blue-700 border border-blue-200' }}">{{ $d['driver_type'] }}</span>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-2">
                            <div class="w-20 h-1.5 bg-gray-100 rounded-full"><div class="h-1.5 bg-red-400 rounded-full" style="width:{{ min(100, ($d['valid_days']/20)*100) }}%"></div></div>
                            <span class="text-xs font-black text-red-500">{{ $d['valid_days'] }}/20</span>
                        </div>
                    </td>
                    <td class="px-5 py-3.5"><span class="text-xs font-black {{ $d['violations'] > 0 ? 'text-red-600' : 'text-gray-400' }}">{{ $d['violations'] }}</span></td>
                    <td class="px-5 py-3.5">
                        <span class="text-[9px] font-black px-2 py-0.5 rounded-full bg-red-100 text-red-700 border border-red-200">{{ $reason }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-8 text-center text-xs text-gray-400 font-medium italic">All drivers are eligible! 🎉</td></tr>
                @endforelse
                <tr id="ineligibleNoResults" style="display: none;"><td colspan="6" class="px-5 py-8 text-center text-xs text-gray-400 font-medium italic">No matching disqualified drivers found.</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('incentiveSearch');
    const statusSelect = document.getElementById('incentiveStatus');
    const rows = document.querySelectorAll('.incentive-row');

    // Force clear the input on load to defeat aggressive browser autofills
    if (searchInput) {
        searchInput.value = '';
    }

    function filterRows() {
        const query = searchInput ? searchInput.value.toLowerCase() : '';
        const status = statusSelect ? statusSelect.value : 'all';
        let visibleEligible = 0;
        let visibleIneligible = 0;

        rows.forEach(row => {
            const searchData = row.getAttribute('data-search') || '';
            const rowStatus = row.getAttribute('data-status');
            
            let matchesSearch = searchData.includes(query);
            let matchesStatus = true;

            if (status !== 'all') {
                if (status === 'disqualified') {
                    matchesStatus = ['has_violations', 'insufficient_days'].includes(rowStatus);
                } else {
                    matchesStatus = rowStatus === status;
                }
            }

            if (matchesSearch && matchesStatus) {
                row.style.display = '';
                if (rowStatus === 'eligible') {
                    visibleEligible++;
                } else {
                    visibleIneligible++;
                }
            } else {
                row.style.display = 'none';
            }
        });

        const eligibleNoResults = document.getElementById('eligibleNoResults');
        if (eligibleNoResults) {
            const hasEligibleRows = document.querySelectorAll('.incentive-row[data-status="eligible"]').length > 0;
            eligibleNoResults.style.display = (visibleEligible === 0 && hasEligibleRows) ? '' : 'none';
        }

        const ineligibleNoResults = document.getElementById('ineligibleNoResults');
        if (ineligibleNoResults) {
            const hasIneligibleRows = document.querySelectorAll('.incentive-row:not([data-status="eligible"])').length > 0;
            ineligibleNoResults.style.display = (visibleIneligible === 0 && hasIneligibleRows) ? '' : 'none';
        }
    }

    if(searchInput) searchInput.addEventListener('input', filterRows);
    if(statusSelect) statusSelect.addEventListener('change', filterRows);
});
</script>
@endsection
