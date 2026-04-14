@extends('layouts.app')
@section('title', 'Driver Performance - Euro System')
@section('page-heading', 'Driver Performance & Violations')
@section('page-subheading', 'Incidents • Incentives • Driver Profiles — All in one place')

@section('content')
<style>
    .tab-btn { @apply px-5 py-2.5 text-xs font-black uppercase tracking-widest rounded-xl transition-all; }
    .tab-btn.active { @apply bg-yellow-500 text-white shadow-md; }
    .tab-btn:not(.active) { @apply bg-white text-gray-500 border border-gray-200 hover:bg-gray-50; }
    .incident-tag { @apply px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest border; }
    .stat-card { @apply bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4; }
    .custom-scroll::-webkit-scrollbar { width: 4px; }
    .custom-scroll::-webkit-scrollbar-thumb { background: #eab308; border-radius: 99px; }
</style>

{{-- ════════ HEADER STATS ════════ --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="stat-card col-span-1">
        <div class="p-3 bg-red-100 rounded-xl"><i data-lucide="activity" class="w-5 h-5 text-red-600"></i></div>
        <div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Incidents</p>
            <p class="text-2xl font-black text-gray-800">{{ $stats['incidents_period'] ?? 0 }}</p>
        </div>
    </div>
    <div class="stat-card col-span-1">
        <div class="p-3 bg-orange-100 rounded-xl"><i data-lucide="users" class="w-5 h-5 text-orange-600"></i></div>
        <div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Violators</p>
            <p class="text-2xl font-black text-gray-800">{{ $stats['total_violators'] ?? 0 }}</p>
        </div>
    </div>
    <div class="stat-card col-span-1">
        <div class="p-3 bg-purple-100 rounded-xl"><i data-lucide="banknote" class="w-5 h-5 text-purple-600"></i></div>
        <div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Charges</p>
            <p class="text-2xl font-black text-gray-800">₱{{ number_format($stats['total_charges'] ?? 0, 0) }}</p>
        </div>
    </div>
    <div class="stat-card col-span-1 border-yellow-200 bg-yellow-50/30">
        <div class="p-3 bg-yellow-100 rounded-xl"><i data-lucide="trophy" class="w-5 h-5 text-yellow-600"></i></div>
        <div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Eligible Incentive</p>
            <p class="text-2xl font-black text-yellow-600">{{ count($incentive_summary['eligible'] ?? []) }}</p>
        </div>
    </div>
</div>

{{-- ════════ TAB NAVIGATION ════════ --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-3 mb-5 flex flex-wrap gap-2">
    <button onclick="switchTab('incidents')" id="tab-btn-incidents"
        class="tab-btn {{ ($tab ?? 'incidents') === 'incidents' ? 'active' : '' }}">
        <i data-lucide="list" class="w-3.5 h-3.5 inline mr-1"></i> Incident Log
    </button>
    <button onclick="switchTab('incentives')" id="tab-btn-incentives"
        class="tab-btn {{ ($tab ?? '') === 'incentives' ? 'active' : '' }}">
        <i data-lucide="trophy" class="w-3.5 h-3.5 inline mr-1"></i>
        Incentive Dashboard
        @if(count($incentive_summary['eligible'] ?? []) > 0)
            <span class="ml-1 px-1.5 py-0.5 bg-green-500 text-white text-[9px] rounded-full">{{ count($incentive_summary['eligible']) }}</span>
        @endif
    </button>
    <button onclick="switchTab('profiles')" id="tab-btn-profiles"
        class="tab-btn {{ ($tab ?? '') === 'profiles' ? 'active' : '' }}">
        <i data-lucide="user-circle" class="w-3.5 h-3.5 inline mr-1"></i> Driver Profiles
    </button>
    <div class="flex-1"></div>
    <button onclick="openIncidentModal()" class="px-5 py-2.5 bg-red-600 text-white font-black text-xs uppercase tracking-widest rounded-xl hover:bg-red-700 transition-all flex items-center gap-2 shadow-sm">
        <i data-lucide="plus" class="w-4 h-4"></i> Record Incident
    </button>
</div>

@if(session('success'))
<div class="mb-4 px-5 py-3 bg-green-50 border border-green-200 text-green-700 rounded-2xl text-sm font-semibold flex items-center gap-2">
    <i data-lucide="check-circle" class="w-4 h-4"></i> {{ session('success') }}
</div>
@endif

{{-- ════════════════════════════════════════
     TAB 1: INCIDENT LOG
════════════════════════════════════════ --}}
<div id="tab-incidents" class="{{ ($tab ?? 'incidents') === 'incidents' ? '' : 'hidden' }}">

    {{-- Filters --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4">
        <form method="GET" action="{{ route('driver-behavior.index') }}" class="flex flex-wrap gap-3 items-end">
            <input type="hidden" name="tab" value="incidents">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Search</label>
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3 top-2.5 w-3.5 h-3.5 text-gray-400"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Driver, unit, description..."
                        class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                </div>
            </div>
            <div class="w-40">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Type</label>
                <select name="type" class="w-full px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                    <option value="">All Types</option>
                    @foreach(App\Http\Controllers\DriverBehaviorController::$incidentTypes as $type => $meta)
                        <option value="{{ $type }}" {{ $type_filter === $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-36">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Severity</label>
                <select name="severity" class="w-full px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                    <option value="">All</option>
                    <option value="critical" {{ $severity_filter === 'critical' ? 'selected' : '' }}>Critical</option>
                    <option value="high" {{ $severity_filter === 'high' ? 'selected' : '' }}>High</option>
                    <option value="medium" {{ $severity_filter === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="low" {{ $severity_filter === 'low' ? 'selected' : '' }}>Low</option>
                </select>
            </div>
            <div class="w-36">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">From</label>
                <input type="date" name="date_from" value="{{ $date_from }}"
                    class="w-full px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium focus:ring-2 focus:ring-yellow-500 focus:outline-none">
            </div>
            <div class="w-36">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">To</label>
                <input type="date" name="date_to" value="{{ $date_to }}"
                    class="w-full px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium focus:ring-2 focus:ring-yellow-500 focus:outline-none">
            </div>
            <button type="submit" class="px-5 py-2 bg-yellow-500 text-white font-black text-xs uppercase tracking-widest rounded-xl hover:bg-yellow-600 transition-all">
                Filter
            </button>
            <a href="{{ route('driver-behavior.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 font-black text-xs uppercase tracking-widest rounded-xl hover:bg-gray-200 transition-all">Clear</a>
        </form>
    </div>

    {{-- Incident Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-50">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Date / Time</th>
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Driver</th>
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Unit</th>
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Incident Type</th>
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Severity</th>
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Description</th>
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Charge</th>
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Incentive</th>
                        <th class="px-5 py-3.5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($incidents as $inc)
                    @php
                        $sevColors = [
                            'critical' => 'bg-red-100 text-red-700 border-red-200',
                            'high'     => 'bg-orange-100 text-orange-700 border-orange-200',
                            'medium'   => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                            'low'      => 'bg-blue-100 text-blue-700 border-blue-200',
                        ];
                        $typeColors = [
                            'Coding Violation'    => 'bg-red-100 text-red-700 border-red-200',
                            'Late Boundary'       => 'bg-orange-100 text-orange-700 border-orange-200',
                            'Short Boundary'      => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                            'Vehicle Damage'      => 'bg-purple-100 text-purple-700 border-purple-200',
                            'Accident'            => 'bg-red-100 text-red-700 border-red-200',
                            'Traffic Violation'   => 'bg-orange-100 text-orange-700 border-orange-200',
                            'Absent / No Show'    => 'bg-gray-100 text-gray-600 border-gray-200',
                            'Passenger Complaint' => 'bg-blue-100 text-blue-700 border-blue-200',
                        ];
                        $tc  = $typeColors[$inc->incident_type] ?? 'bg-gray-100 text-gray-600 border-gray-200';
                        $sc  = $sevColors[$inc->severity] ?? 'bg-gray-100 text-gray-600 border-gray-200';
                        $isAccident = in_array($inc->incident_type, ['Accident','Vehicle Damage']);
                    @endphp
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <div class="text-xs font-bold text-gray-800">{{ \Carbon\Carbon::parse($inc->timestamp)->timezone('Asia/Manila')->format('M d, Y') }}</div>
                            <div class="text-[10px] text-gray-400 font-medium">{{ \Carbon\Carbon::parse($inc->timestamp)->timezone('Asia/Manila')->format('h:i A') }}</div>
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <div class="text-xs font-bold text-gray-800">{{ $inc->driver_name ?? '—' }}</div>
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="text-xs font-black text-blue-600 uppercase">{{ $inc->plate_number ?? '—' }}</span>
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="incident-tag {{ $tc }}">{{ $inc->incident_type }}</span>
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="incident-tag {{ $sc }}">{{ ucfirst($inc->severity) }}</span>
                        </td>
                        <td class="px-5 py-3.5 max-w-[220px]">
                            <p class="text-xs text-gray-600 line-clamp-2">{{ $inc->description }}</p>
                            @if($isAccident && $inc->third_party_name)
                                <p class="text-[10px] text-purple-600 font-bold mt-0.5">3rd Party: {{ $inc->third_party_name }} {{ $inc->third_party_vehicle ? "({$inc->third_party_vehicle})" : '' }}</p>
                            @endif
                            @if($inc->is_driver_fault)
                                <span class="text-[9px] font-black text-red-600 bg-red-50 px-1.5 py-0.5 rounded-full border border-red-100">DRIVER FAULT</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @if($inc->total_charge_to_driver > 0)
                                <div class="text-xs font-black text-red-600">₱{{ number_format($inc->total_charge_to_driver, 2) }}</div>
                                <span class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded-full
                                    {{ $inc->charge_status === 'paid' ? 'bg-green-100 text-green-700' : ( $inc->charge_status === 'waived' ? 'bg-gray-100 text-gray-500' : 'bg-orange-100 text-orange-700') }}">
                                    {{ ucfirst($inc->charge_status ?? 'pending') }}</span>
                            @else
                                <span class="text-gray-300 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @if(in_array($inc->severity, ['high','critical']) || $inc->is_driver_fault)
                                <span class="text-[9px] font-black text-red-600 bg-red-50 px-1.5 py-0.5 rounded-full border border-red-100">VOID</span>
                            @else
                                <span class="text-[9px] font-black text-green-600 bg-green-50 px-1.5 py-0.5 rounded-full border border-green-100">OK</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap text-right">
                            <form method="POST" action="{{ route('driver-behavior.destroy', $inc->id) }}" class="inline" onsubmit="return confirm('Delete this incident record?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg text-red-400 hover:bg-red-50 hover:text-red-600 transition-colors">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="px-5 py-16 text-center">
                        <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-3 border border-green-100">
                            <i data-lucide="shield-check" class="w-8 h-8 text-green-500"></i>
                        </div>
                        <p class="text-sm font-black text-gray-400 uppercase tracking-widest">No incidents found</p>
                        <p class="text-xs text-gray-300 mt-1">All drivers are performing well</p>
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- Pagination --}}
        @if($pagination['total_pages'] > 1)
        <div class="px-5 py-3 border-t border-gray-50 flex items-center justify-between">
            <p class="text-xs text-gray-400 font-medium">{{ $pagination['total_items'] }} total incidents</p>
            <div class="flex gap-1">
                @if($pagination['has_prev'])
                    <a href="{{ request()->fullUrlWithQuery(['page' => $pagination['prev_page']]) }}" class="px-3 py-1.5 text-xs font-black bg-gray-100 rounded-lg hover:bg-yellow-100">‹ Prev</a>
                @endif
                <span class="px-3 py-1.5 text-xs font-black bg-yellow-500 text-white rounded-lg">{{ $pagination['page'] }}</span>
                @if($pagination['has_next'])
                    <a href="{{ request()->fullUrlWithQuery(['page' => $pagination['next_page']]) }}" class="px-3 py-1.5 text-xs font-black bg-gray-100 rounded-lg hover:bg-yellow-100">Next ›</a>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

{{-- ════════════════════════════════════════
     TAB 2: INCENTIVE DASHBOARD
════════════════════════════════════════ --}}
<div id="tab-incentives" class="{{ ($tab ?? '') === 'incentives' ? '' : 'hidden' }}">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-5 text-white shadow-lg">
            <i data-lucide="trophy" class="w-6 h-6 mb-2 opacity-80"></i>
            <p class="text-3xl font-black">{{ count($incentive_summary['eligible'] ?? []) }}</p>
            <p class="text-xs font-black uppercase tracking-widest opacity-80 mt-1">Eligible for Incentive</p>
        </div>
        <div class="bg-gradient-to-br from-red-500 to-rose-600 rounded-2xl p-5 text-white shadow-lg">
            <i data-lucide="x-circle" class="w-6 h-6 mb-2 opacity-80"></i>
            <p class="text-3xl font-black">{{ count($incentive_summary['ineligible'] ?? []) }}</p>
            <p class="text-xs font-black uppercase tracking-widest opacity-80 mt-1">Disqualified</p>
        </div>
        <div class="bg-gradient-to-br from-yellow-500 to-amber-600 rounded-2xl p-5 text-white shadow-lg">
            <i data-lucide="calendar-check" class="w-6 h-6 mb-2 opacity-80"></i>
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
            <p class="text-xl font-black">{{ $targetDate->format('M d, Y') }}</p>
            <p class="text-xs font-black uppercase tracking-widest opacity-80 mt-1">Next Payout Sunday</p>
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
                <tr class="hover:bg-green-50/30 transition-colors">
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
                @php $reason = $d['violations'] > 0 ? 'Has Violations' : 'Insufficient Days'; @endphp
                <tr class="hover:bg-red-50/20 transition-colors">
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
            </tbody>
        </table>
    </div>
</div>

{{-- ════════════════════════════════════════
     TAB 3: DRIVER PROFILES
════════════════════════════════════════ --}}
<div id="tab-profiles" class="{{ ($tab ?? '') === 'profiles' ? '' : 'hidden' }}">
    <div class="mb-4">
        <input type="text" id="profileSearch" placeholder="Search driver name..."
            class="w-full md:w-80 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-yellow-500 focus:outline-none shadow-sm"
            onkeyup="filterProfiles(this.value)">
    </div>

    <div id="profileGrid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach($driver_profiles as $profile)
        @php
            $inc = $profile['incentive'];
            $eligible = $inc['eligible'];
        @endphp
        <div class="profile-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all" data-name="{{ strtolower($profile['name']) }}">
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
                @if($profile['shortages'] > 0)
                <div class="mt-2 flex items-center gap-1.5 text-[10px] font-bold text-orange-600">
                    <i data-lucide="trending-down" class="w-3 h-3"></i> Total Shortage: ₱{{ number_format($profile['shortages'], 2) }}
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- ════════════════════════════════════════
     RECORD INCIDENT MODAL (PREMIUM)
════════════════════════════════════════ --}}
<div id="incidentModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden overflow-y-auto z-[100] flex items-start justify-center py-6 px-4">
    <div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
        {{-- Modal Header --}}
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-900 to-gray-800 text-white flex items-center justify-between">
            <div>
                <h3 class="font-black text-base">Record Driver Incident</h3>
                <p class="text-xs text-gray-400 mt-0.5">All fields marked * are required</p>
            </div>
            <button onclick="closeIncidentModal()" class="p-2 rounded-xl bg-white/10 hover:bg-white/20 transition-all">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('driver-behavior.store') }}" id="incidentForm" class="max-h-[80vh] overflow-y-auto custom-scroll">
            @csrf
            <div class="p-6 space-y-5">

                {{-- Section: Basic Info --}}
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Basic Information</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Unit *</label>
                            <select name="unit_id" required class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                                <option value="">Select Unit</option>
                                @foreach($units as $u)
                                    <option value="{{ $u->id }}">{{ $u->plate_number }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Driver *</label>
                            <select name="driver_id" required class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                                <option value="">Select Driver</option>
                                @foreach($drivers as $d)
                                    <option value="{{ $d->id }}">{{ $d->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Incident Type *</label>
                            <select name="incident_type" required id="incidentTypeSelect"
                                onchange="handleTypeChange(this.value)"
                                class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                                <option value="">Select Type</option>
                                @foreach(App\Http\Controllers\DriverBehaviorController::$incidentTypes as $type => $meta)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Severity *</label>
                            <select name="severity" required class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                                <option value="">Select</option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Date of Incident *</label>
                            <input type="date" name="incident_date" value="{{ date('Y-m-d') }}" required
                                class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                        </div>
                    </div>
                </div>

                {{-- Section: Narrative --}}
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Incident Narrative</p>
                    <textarea name="description" required rows="4" placeholder="Describe what happened. Who was involved, where, when, what were the circumstances..."
                        class="w-full px-3 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-yellow-500 focus:outline-none resize-none"></textarea>
                </div>

                {{-- Section: Accident Details (Conditional) --}}
                {{-- Section: Comprehensive Accident Details (Conditional) --}}
                <div id="accidentSection" class="hidden p-5 bg-gradient-to-b from-gray-50 to-white rounded-2xl border border-gray-200 shadow-sm mt-5">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-gray-100 pb-4 mb-5">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-red-100 rounded-xl"><i data-lucide="car-crash" class="w-5 h-5 text-red-600"></i></div>
                            <div>
                                <h4 class="font-black text-sm text-gray-800 uppercase tracking-widest">Comprehensive Accident Report</h4>
                                <p class="text-[10px] text-gray-500 font-medium">Track third parties, damages, and automated debt collection.</p>
                            </div>
                        </div>
                        <label class="flex items-center gap-3 cursor-pointer mt-3 md:mt-0 p-3 bg-red-50 rounded-xl border border-red-200 shadow-sm transition-all hover:bg-red-100">
                            <input type="checkbox" name="is_driver_fault" id="faultCheck" value="1" onchange="toggleLiabilityInfo(this.checked)" class="w-5 h-5 accent-red-600 rounded">
                            <div>
                                <p class="text-xs font-black text-red-700 uppercase">Driver is At Fault</p>
                                <p class="text-[9px] text-red-600 font-bold uppercase tracking-widest">Create Auto Debt Record</p>
                            </div>
                        </label>
                    </div>

                    {{-- Parties Involved --}}
                    <div class="mb-6">
                        <div class="flex justify-between items-end mb-3">
                            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest"><span class="text-blue-500 mr-1">■</span> Involved Parties / Vehicles</p>
                            <button type="button" onclick="addPartyRow()" class="text-[10px] font-black uppercase tracking-widest text-blue-600 hover:text-blue-700 flex items-center gap-1.5"><i data-lucide="plus-circle" class="w-3.5 h-3.5"></i> Add Party</button>
                        </div>
                        <div id="partiesContainer" class="space-y-3">
                            <!-- JS will inject party rows here -->
                        </div>
                    </div>

                    {{-- Damages Checklist --}}
                    <div class="mb-4">
                        <div class="flex justify-between items-end mb-3">
                            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest"><span class="text-purple-500 mr-1">■</span> Damages & Repair Checklist</p>
                            <button type="button" onclick="addDamageRow()" class="text-[10px] font-black uppercase tracking-widest text-purple-600 hover:text-purple-700 flex items-center gap-1.5"><i data-lucide="plus-circle" class="w-3.5 h-3.5"></i> Add Damage Item</button>
                        </div>
                        <div id="damagesContainer" class="space-y-3">
                            <!-- JS will inject damage rows here -->
                        </div>
                    </div>

                    {{-- Liability Total --}}
                    <div id="liabilitySection" class="hidden p-4 bg-gradient-to-r from-red-50 to-white rounded-xl border-l-4 border-l-red-500 border-y border-y-gray-100 border-r border-r-gray-100 mt-5 items-center justify-between shadow-sm">
                        <div>
                            <p class="text-[10px] font-black text-red-600 uppercase tracking-widest flex items-center gap-1.5"><i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i> Driver Liability Triggered</p>
                            <p class="text-xs text-gray-600 font-medium mt-0.5">This total will be automatically converted strictly into manageable boundary installments.</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Grand Charge</p>
                            <p class="text-2xl font-black text-red-700 font-mono tracking-tight" id="grandTotalDisplay">₱0.00</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex gap-3">
                <button type="submit" class="flex-1 py-2.5 bg-gray-900 text-white font-black text-xs uppercase tracking-widest rounded-xl hover:bg-gray-800 transition-all shadow-sm">
                    Save Incident Record
                </button>
                <button type="button" onclick="closeIncidentModal()" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-600 font-black text-xs uppercase tracking-widest rounded-xl hover:bg-gray-50 transition-all">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ─── Tab Switching ───────────────────────────────────
function switchTab(name) {
    ['incidents','incentives','profiles'].forEach(t => {
        document.getElementById('tab-' + t).classList.add('hidden');
        document.getElementById('tab-btn-' + t).classList.remove('active');
    });
    document.getElementById('tab-' + name).classList.remove('hidden');
    document.getElementById('tab-btn-' + name).classList.add('active');
    history.replaceState(null, '', '?tab=' + name);
}

// ─── Incident Modal ───────────────────────────────────
function openIncidentModal() {
    document.getElementById('incidentModal').classList.remove('hidden');
    if(window.lucide) lucide.createIcons();
}
function closeIncidentModal() {
    document.getElementById('incidentModal').classList.add('hidden');
}
// Close on backdrop click
document.getElementById('incidentModal').addEventListener('click', function(e) {
    if (e.target === this) closeIncidentModal();
});

// ─── Dynamic Incident Fields ────────────────────────
const spareParts = {!! json_encode($spare_parts) !!};
let partyCount = 0;
let damageCount = 0;

function handleTypeChange(type) {
    const accidentTypes = ['Accident', 'Vehicle Damage'];
    const section = document.getElementById('accidentSection');
    if (accidentTypes.includes(type)) {
        section.classList.remove('hidden');
        if(partyCount === 0) addPartyRow();
        if(damageCount === 0) addDamageRow();
        if(window.lucide) lucide.createIcons();
    } else {
        section.classList.add('hidden');
    }
}

function addPartyRow() {
    const container = document.getElementById('partiesContainer');
    const row = document.createElement('div');
    row.className = 'grid grid-cols-1 md:grid-cols-4 gap-3 p-3 bg-white border border-gray-100 rounded-xl items-end relative group';
    row.innerHTML = `
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Name / Entity</label>
            <input type="text" name="involved_parties[${partyCount}][name]" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs" placeholder="e.g. Juan Cruz">
        </div>
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Vehicle</label>
            <input type="text" name="involved_parties[${partyCount}][vehicle_type]" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs" placeholder="Motorcycle, SUV...">
        </div>
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Plate Number</label>
            <input type="text" name="involved_parties[${partyCount}][plate_number]" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs" placeholder="ABC 123">
        </div>
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Contact (Opt)</label>
            <input type="text" name="involved_parties[${partyCount}][contact_info]" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs" placeholder="09...">
        </div>
        <button type="button" onclick="this.remove()" class="absolute -top-2 -right-2 bg-white text-gray-400 hover:text-red-500 rounded-full border border-gray-100 shadow-sm p-1 opacity-0 group-hover:opacity-100 transition-opacity">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    `;
    container.appendChild(row);
    partyCount++;
}

function addDamageRow() {
    const container = document.getElementById('damagesContainer');
    const row = document.createElement('div');
    row.className = 'grid grid-cols-1 md:grid-cols-6 gap-3 p-3 bg-white border border-gray-100 rounded-xl items-end relative group damage-row';
    
    let options = '<option value="">Custom Part / Labor</option>';
    spareParts.forEach(p => {
        options += `<option value="${p.id}" data-price="${p.price}">${p.name} (₱${parseFloat(p.price).toLocaleString()})</option>`;
    });

    row.innerHTML = `
        <div class="col-span-2">
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Part / Service Selection</label>
            <select name="damages[${damageCount}][spare_part_id]" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs spart-select" onchange="syncPartData(this, ${damageCount})">
                ${options}
            </select>
        </div>
        <div class="col-span-2">
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Specific Damage Description</label>
            <input type="text" name="damages[${damageCount}][part_name]" id="dmg_name_${damageCount}" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs" placeholder="Description..." required>
        </div>
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Unit Price</label>
            <input type="number" step="0.01" min="0" name="damages[${damageCount}][unit_price]" id="dmg_price_${damageCount}" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs dmg-price" value="0" oninput="calculateGrandTotal()">
        </div>
        <div class="relative flex gap-2 w-full">
            <div class="flex-1">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Qty</label>
                <input type="number" min="1" name="damages[${damageCount}][qty]" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs dmg-qty" value="1" oninput="calculateGrandTotal()">
            </div>
            <button type="button" onclick="this.parentElement.parentElement.remove(); calculateGrandTotal();" class="absolute -top-7 -right-3 bg-white text-gray-400 hover:text-red-500 rounded-full border border-gray-100 shadow-sm p-1 opacity-0 group-[.damage-row:hover]:opacity-100 transition-opacity">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    `;
    container.appendChild(row);
    damageCount++;
}

function syncPartData(selectElement, index) {
    const selected = selectElement.options[selectElement.selectedIndex];
    const nameInput = document.getElementById('dmg_name_' + index);
    const priceInput = document.getElementById('dmg_price_' + index);
    
    if (selectElement.value) {
        nameInput.value = selected.text.split(' (₱')[0];
        nameInput.readOnly = true;
        nameInput.classList.add('bg-gray-100');
        priceInput.value = selected.dataset.price;
    } else {
        nameInput.value = '';
        nameInput.readOnly = false;
        nameInput.classList.remove('bg-gray-100');
    }
    calculateGrandTotal();
}

function calculateGrandTotal() {
    let total = 0;
    document.querySelectorAll('.damage-row').forEach(row => {
        const p = parseFloat(row.querySelector('.dmg-price').value) || 0;
        const q = parseInt(row.querySelector('.dmg-qty').value) || 0;
        total += (p * q);
    });
    document.getElementById('grandTotalDisplay').textContent = '₱' + total.toLocaleString('en-PH', {minimumFractionDigits: 2});
}

function toggleLiabilityInfo(isFault) {
    const liabSec = document.getElementById('liabilitySection');
    if(isFault) {
        liabSec.classList.remove('hidden');
        liabSec.classList.add('flex');
    } else {
        liabSec.classList.add('hidden');
        liabSec.classList.remove('flex');
    }
}

// ─── Profile Search ───────────────────────────────────
function filterProfiles(query) {
    const q = query.toLowerCase().trim();
    document.querySelectorAll('.profile-card').forEach(card => {
        const name = card.dataset.name || '';
        card.style.display = (!q || name.includes(q)) ? '' : 'none';
    });
}

// ─── Keyboard escape ────────────────────────────────
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeIncidentModal();
});
</script>
@endpush