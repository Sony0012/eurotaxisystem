@extends('layouts.app')

@section('title', 'EuroTaxi | System History & Audit Logs')
@section('page-heading', 'System History Logs')
@section('page-subheading', 'Detailed audit trail of all administrative and system actions.')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 pb-12">

    {{-- ── 1. PAGE HEADER & STATS SUMMARY ────────────────────────────────── --}}
    <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-r from-white via-blue-50/30 to-indigo-50/20 backdrop-blur-md p-6 sm:p-7 shadow-xs">
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <!-- Left Info -->
            <div class="flex items-center gap-5">
                <div class="w-18 h-18 sm:w-20 sm:h-20 shrink-0">
                    <img src="{{ asset('image/kpi/history_3d.svg') }}" alt="System History Logs" class="w-full h-full object-contain filter drop-shadow-md hover:scale-105 transition-transform">
                </div>
                <div>
                    <!-- Breadcrumb -->
                    <div class="flex items-center gap-2 text-[11px] font-black text-slate-400 uppercase tracking-widest mb-1.5">
                        <span>System</span>
                        <span>/</span>
                        <span class="text-blue-600 font-black">History Logs</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">System History Logs</h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5 max-w-xl font-medium">Detailed audit trail of all administrative, operational, and automated system actions.</p>
                </div>
            </div>

            <!-- Right Stats Grid (21st.dev Stats Dashboard Pattern) -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 self-start lg:self-center shrink-0">
                <!-- Total Events -->
                <div class="bg-white/90 border border-slate-200/90 rounded-2xl p-3.5 shadow-2xs min-w-[120px]">
                    <div class="flex items-center gap-2 text-slate-400 mb-1">
                        <i data-lucide="activity" class="w-3.5 h-3.5 text-blue-500"></i>
                        <span class="text-[10px] font-black uppercase tracking-wider">Total Events</span>
                    </div>
                    <div class="text-lg font-black text-slate-900 leading-none">
                        {{ number_format($totalEvents ?? $logs->total()) }}
                    </div>
                </div>

                <!-- Today's Events -->
                <div class="bg-white/90 border border-slate-200/90 rounded-2xl p-3.5 shadow-2xs min-w-[120px]">
                    <div class="flex items-center gap-2 text-slate-400 mb-1">
                        <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-500"></i>
                        <span class="text-[10px] font-black uppercase tracking-wider">Today's Events</span>
                    </div>
                    <div class="text-lg font-black text-slate-900 leading-none">
                        {{ number_format($todayEvents ?? 0) }}
                    </div>
                </div>

                <!-- Admin Actions -->
                <div class="bg-white/90 border border-slate-200/90 rounded-2xl p-3.5 shadow-2xs col-span-2 sm:col-span-1 min-w-[120px]">
                    <div class="flex items-center gap-2 text-slate-400 mb-1">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-500"></i>
                        <span class="text-[10px] font-black uppercase tracking-wider">Admin Actions</span>
                    </div>
                    <div class="text-lg font-black text-slate-900 leading-none">
                        {{ number_format($adminActions ?? 0) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── 2. 21ST.DEV ENTERPRISE FILTER TOOLBAR ─────────────────────────── --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-5 sm:p-6 space-y-4">
        <form action="{{ route('activity-logs.index') }}" method="GET" id="historyFilterForm" class="space-y-4">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3.5">
                
                <!-- Search Input (5 cols) -->
                <div class="lg:col-span-5">
                    <label class="block text-[10px] uppercase font-black text-slate-400 tracking-wider mb-1 px-1">Search Keywords</label>
                    <div class="relative">
                        <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"></i>
                        <input type="text" name="search" id="logSearchInput" value="{{ request('search') }}" 
                            class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none text-xs font-bold text-slate-800 placeholder:text-slate-400" 
                            placeholder="Search names, emails, actions, notes..."
                            autocomplete="off" spellcheck="false">
                        <button type="button" id="clearSearchBtn" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 hidden" title="Clear Search">
                            <i data-lucide="x" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>
                </div>

                <!-- Category Filter (3 cols) -->
                <div class="lg:col-span-3">
                    <label class="block text-[10px] uppercase font-black text-slate-400 tracking-wider mb-1 px-1">Category</label>
                    <div class="relative">
                        <select name="type" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none text-xs font-bold text-slate-800 cursor-pointer appearance-none">
                            <option value="">All Activities</option>
                            <option value="auth" {{ request('type') === 'auth' ? 'selected' : '' }}>Login / Security</option>
                            <option value="admin" {{ request('type') === 'admin' ? 'selected' : '' }}>Admin Actions</option>
                            <option value="system" {{ request('type') === 'system' ? 'selected' : '' }}>System Logic</option>
                        </select>
                        <i data-lucide="chevron-down" class="absolute right-3.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none"></i>
                    </div>
                </div>

                <!-- From Date (2 cols) -->
                <div class="lg:col-span-2">
                    <label class="block text-[10px] uppercase font-black text-slate-400 tracking-wider mb-1 px-1">From Date</label>
                    <div class="relative">
                        <input type="date" name="date_from" value="{{ request('date_from') }}" 
                            class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none text-xs font-bold text-slate-800">
                    </div>
                </div>

                <!-- To Date + Action Buttons (2 cols) -->
                <div class="lg:col-span-2">
                    <label class="block text-[10px] uppercase font-black text-slate-400 tracking-wider mb-1 px-1">To Date</label>
                    <div class="flex items-center gap-2">
                        <input type="date" name="date_to" value="{{ request('date_to') }}" 
                            class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none text-xs font-bold text-slate-800">
                        
                        <!-- Apply Filter Button -->
                        <button type="submit" 
                            class="p-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl shadow-xs transition-all active:scale-95 shrink-0" 
                            title="Apply Filters">
                            <i data-lucide="filter" class="w-4 h-4"></i>
                        </button>
                        
                        <!-- Reset Button -->
                        <a href="{{ route('activity-logs.index') }}" 
                            class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-900 rounded-xl transition-all active:scale-95 shrink-0" 
                            title="Reset all filters">
                            <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>

            </div>

            <!-- Active Filter Badges / Chips -->
            @if(request('search') || request('type') || request('date_from') || request('date_to'))
            <div class="pt-2 border-t border-slate-100 flex flex-wrap items-center gap-2">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-1">Active Filters:</span>
                
                @if(request('search'))
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-xs font-bold">
                    Search: "{{ request('search') }}"
                    <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="hover:text-blue-900"><i data-lucide="x" class="w-3 h-3"></i></a>
                </span>
                @endif

                @if(request('type'))
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-purple-50 text-purple-700 border border-purple-200 rounded-lg text-xs font-bold">
                    Category: {{ ucfirst(request('type')) }}
                    <a href="{{ request()->fullUrlWithQuery(['type' => null]) }}" class="hover:text-purple-900"><i data-lucide="x" class="w-3 h-3"></i></a>
                </span>
                @endif

                @if(request('date_from'))
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-bold">
                    From: {{ request('date_from') }}
                    <a href="{{ request()->fullUrlWithQuery(['date_from' => null]) }}" class="hover:text-emerald-900"><i data-lucide="x" class="w-3 h-3"></i></a>
                </span>
                @endif

                @if(request('date_to'))
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-bold">
                    To: {{ request('date_to') }}
                    <a href="{{ request()->fullUrlWithQuery(['date_to' => null]) }}" class="hover:text-emerald-900"><i data-lucide="x" class="w-3 h-3"></i></a>
                </span>
                @endif

                <a href="{{ route('activity-logs.index') }}" class="text-xs font-bold text-slate-500 hover:text-rose-600 underline ml-2">Clear all</a>
            </div>
            @endif

        </form>
    </div>

    {{-- ── 3. MAIN AUDIT LOG DATA TABLE ─────────────────────────────────── --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        
        <!-- Table Header Bar -->
        <div class="px-6 sm:px-8 py-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-blue-50 text-blue-600 rounded-xl">
                    <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-900">Activity History</h3>
                    <p class="text-xs text-slate-500">Detailed record of user operations, system modifications, and access traces.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-black text-slate-700 bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200">
                    {{ $logs->total() }} Records
                </span>
                <span class="text-xs font-bold text-slate-400">
                    Page {{ $logs->currentPage() }} of {{ $logs->lastPage() }}
                </span>
            </div>
        </div>

        <!-- Table Responsive Container -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/70">
                    <tr>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest w-44">Timestamp</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest w-64">User & Role</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest w-56">Action</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Notes & Details</th>
                        <th class="px-6 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest w-48">Source & Device</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white" id="logTableBody">
                    @forelse($logs as $log)
                    <tr class="log-row hover:bg-slate-50/80 transition-colors" data-search-terms="{{ strtolower(($log->user_name ?? '') . ' ' . ($log->user_email ?? '') . ' ' . ($log->action ?? '') . ' ' . ($log->notes ?? '')) }}">
                        
                        <!-- 1. Timestamp Column -->
                        <td class="px-6 py-4 w-44">
                            <div class="flex flex-col space-y-1">
                                <span class="text-xs font-black text-slate-900 leading-none">
                                    {{ $log->created_at->format('M d, Y') }}
                                </span>
                                <span class="text-[11px] font-mono font-bold text-slate-500">
                                    {{ $log->created_at->format('h:i:s A') }}
                                </span>
                                <span class="inline-flex items-center gap-1 text-[9px] font-bold text-slate-400 uppercase tracking-tight">
                                    <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                    {{ $log->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </td>

                        <!-- 2. User & Role Column -->
                        <td class="px-6 py-4 w-64">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 text-white font-black text-xs flex items-center justify-center shadow-2xs shrink-0">
                                    {{ strtoupper(substr($log->user_name ?? 'S', 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-xs font-black text-slate-900 truncate leading-tight">
                                        {{ $log->user_name ?? 'System Automated' }}
                                    </div>
                                    <div class="text-[11px] text-slate-400 truncate mt-0.5">
                                        {{ $log->user_email ?? 'system@eurotaxi.internal' }}
                                    </div>
                                    <div class="mt-1">
                                        <span class="inline-block px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200">
                                            {{ strtoupper($log->user_role ?? 'System') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- 3. Action Badge Column -->
                        <td class="px-6 py-4 w-56">
                            @php
                                $action = strtolower($log->action ?? '');
                                $badgeClass = 'bg-slate-50 text-slate-700 border-slate-200';
                                $iconName = 'activity';
                                
                                // Creation / Addition / Restore
                                if (str_contains($action, 'create') || str_contains($action, 'add') || str_contains($action, 'recorded') || str_contains($action, 'register') || str_contains($action, 'restore') || str_contains($action, 'approve')) { 
                                    $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200'; 
                                    $iconName = 'plus-circle'; 
                                }
                                // Updates / Modifications / Payments
                                elseif (str_contains($action, 'edit') || str_contains($action, 'update') || str_contains($action, 'change') || str_contains($action, 'toggle') || str_contains($action, 'payment') || str_contains($action, 'salary') || str_contains($action, 'expense')) { 
                                    $badgeClass = 'bg-blue-50 text-blue-700 border-blue-200'; 
                                    $iconName = 'pencil'; 
                                }
                                // Deletions / Bans / Suspensions
                                elseif (str_contains($action, 'delete') || str_contains($action, 'reject') || str_contains($action, 'archive') || str_contains($action, 'dismissed') || str_contains($action, 'banned') || str_contains($action, 'suspend')) { 
                                    $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200'; 
                                    $iconName = 'trash-2'; 
                                }
                                // Maintenance / Reset
                                elseif (str_contains($action, 'reset') || str_contains($action, 'maintenance')) {
                                    $badgeClass = 'bg-purple-50 text-purple-700 border-purple-200';
                                    $iconName = 'wrench';
                                }
                                // Auth / Security
                                elseif (str_contains($action, 'login') || str_contains($action, 'logout') || str_contains($action, 'password') || str_contains($action, 'security')) {
                                    $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
                                    $iconName = 'shield-check';
                                }

                                // Specific Icon overrides
                                if (str_contains($action, 'driver')) $iconName = 'user-check';
                                elseif (str_contains($action, 'unit')) $iconName = 'car';
                                elseif (str_contains($action, 'incident')) $iconName = 'alert-triangle';
                            @endphp

                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[11px] font-bold border shadow-2xs {{ $badgeClass }}">
                                <i data-lucide="{{ $iconName }}" class="w-3.5 h-3.5 shrink-0"></i>
                                <span>{{ ucwords(str_replace('_', ' ', $log->action)) }}</span>
                            </span>
                        </td>

                        <!-- 4. Notes & Details Column -->
                        <td class="px-6 py-4">
                            @if($log->notes)
                                <div class="p-3 bg-slate-50/80 rounded-2xl border border-slate-200/70 text-xs font-semibold text-slate-700 leading-relaxed max-w-xl break-words">
                                    {!! nl2br(e($log->notes)) !!}
                                </div>
                            @else
                                <span class="text-xs text-slate-400 italic">No additional details recorded.</span>
                            @endif
                        </td>

                        <!-- 5. Source & Device Column -->
                        <td class="px-6 py-4 text-right w-48">
                            <div class="flex flex-col items-end space-y-1">
                                <!-- IP Address with copy button -->
                                <div class="inline-flex items-center gap-1.5 bg-slate-50 border border-slate-200 px-2.5 py-1 rounded-xl text-[10px] font-mono font-bold text-slate-700">
                                    <i data-lucide="network" class="w-3 h-3 text-slate-400"></i>
                                    <span>{{ $log->ip_address ?: '127.0.0.1' }}</span>
                                    <button type="button" onclick="copyToClipboard('{{ $log->ip_address }}', this)" class="text-slate-400 hover:text-blue-600 transition-colors p-0.5" title="Copy IP">
                                        <i data-lucide="copy" class="w-3 h-3"></i>
                                    </button>
                                </div>
                                <!-- User Agent device -->
                                <div class="text-[9px] text-slate-400 font-medium max-w-[140px] truncate" title="{{ $log->user_agent }}">
                                    <i data-lucide="monitor" class="w-2.5 h-2.5 inline mr-0.5 opacity-60"></i>
                                    {{ $log->user_agent ?: 'System Service' }}
                                </div>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center max-w-sm mx-auto space-y-3">
                                <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-3xl flex items-center justify-center">
                                    <i data-lucide="clipboard-list" class="w-8 h-8 opacity-40"></i>
                                </div>
                                <h4 class="text-sm font-bold text-slate-700">No activity found</h4>
                                <p class="text-xs text-slate-400 leading-relaxed">No system events match your current search keywords or date filters.</p>
                                <a href="{{ route('activity-logs.index') }}" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition-all">
                                    Reset Filters
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                    <!-- Client-side instant live search empty state (hidden by default) -->
                    <tr id="liveSearchEmptyState" class="hidden">
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center max-w-sm mx-auto space-y-3">
                                <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-3xl flex items-center justify-center">
                                    <i data-lucide="search-x" class="w-8 h-8 opacity-40"></i>
                                </div>
                                <h4 class="text-sm font-bold text-slate-700">No matching logs found</h4>
                                <p class="text-xs text-slate-400 leading-relaxed">Try typing a different name, email, action, or note keyword.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @if($logs->hasPages())
        <div class="p-4 sm:p-6 border-t border-slate-100 bg-slate-50/50">
            {{ $logs->links() }}
        </div>
        @endif
    </div>

</div>

<!-- Copy Toast Notification -->
<div id="copyToast" class="fixed bottom-6 right-6 z-50 transform transition-all duration-300 translate-y-20 opacity-0 pointer-events-none bg-slate-900 text-white text-xs font-bold px-4 py-2.5 rounded-2xl shadow-xl flex items-center gap-2">
    <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400"></i>
    <span>IP Address copied to clipboard!</span>
</div>

@push('scripts')
<script>
    // Copy to clipboard helper
    function copyToClipboard(text, btn) {
        if (!text) return;
        navigator.clipboard.writeText(text).then(() => {
            const toast = document.getElementById('copyToast');
            if (toast) {
                toast.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');
                toast.classList.add('translate-y-0', 'opacity-100');
                setTimeout(() => {
                    toast.classList.remove('translate-y-0', 'opacity-100');
                    toast.classList.add('translate-y-20', 'opacity-0', 'pointer-events-none');
                }, 2000);
            }
        }).catch(err => {
            console.error('Failed to copy', err);
        });
    }

    // Client-side Instant Filter helper
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('logSearchInput');
        const clearBtn = document.getElementById('clearSearchBtn');
        const rows = document.querySelectorAll('.log-row');
        const emptyState = document.getElementById('liveSearchEmptyState');

        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase().trim();
                let visibleCount = 0;

                if (searchTerm.length > 0) {
                    clearBtn.classList.remove('hidden');
                } else {
                    clearBtn.classList.add('hidden');
                }

                rows.forEach(row => {
                    const terms = row.getAttribute('data-search-terms') || '';
                    if (terms.includes(searchTerm)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (visibleCount === 0 && rows.length > 0) {
                    emptyState.classList.remove('hidden');
                } else {
                    emptyState.classList.add('hidden');
                }
            });

            if (clearBtn) {
                clearBtn.addEventListener('click', () => {
                    searchInput.value = '';
                    searchInput.dispatchEvent(new Event('input'));
                    searchInput.focus();
                });
            }
        }
    });
</script>
@endpush
@endsection
