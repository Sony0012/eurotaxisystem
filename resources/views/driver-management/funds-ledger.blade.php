@extends('layouts.app')

@section('title', 'Driver Savings & Pondo Ledger — Euro Taxi Fleet')
@section('page-heading', 'Driver Savings & Pondo Ledger')
@section('page-subheading', 'Comprehensive audit trail of driver emergency funds, accident damage deductions, maintenance co-payments, and savings withdrawals.')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="space-y-6">
    <!-- Top Action Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-3.5">
            <img src="{{ asset('image/kpi/fleet_index_3d.svg') }}" alt="Driver Savings & Pondo Ledger" class="w-12 h-12 sm:w-14 sm:h-14 object-contain pointer-events-none shrink-0 filter drop-shadow-md">
            <div>
                <h1 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">Driver Savings & Pondo Ledger</h1>
                <p class="text-xs text-slate-500 font-medium">Tracking {{ $stats['funded_drivers'] ?? 0 }} drivers with active funds & reserves</p>
            </div>
        </div>
        <div class="flex items-center gap-2.5 flex-wrap">
            <a href="{{ route('driver-management.funds-ledger.print', request()->all()) }}" target="_blank" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow-2xs">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg> Print Statement
            </a>
            <button type="button" onclick="openLedgerDepositModal()" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all flex items-center gap-1.5 shadow-md shadow-emerald-500/20 cursor-pointer">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="16"></line>
                    <line x1="8" y1="12" x2="16" y2="12"></line>
                </svg> Add Deposit
            </button>
            <button type="button" onclick="openLedgerDisburseModal()" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all flex items-center gap-1.5 shadow-md shadow-rose-500/20 cursor-pointer">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="8" y1="12" x2="16" y2="12"></line>
                </svg> Deduct / Payout
            </button>
        </div>
    </div>

    <!-- KPI Summary Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Card 1: Available Vault Balance -->
        <div class="relative overflow-hidden rounded-2xl shadow-xs border border-emerald-200/80 bg-gradient-to-br from-white via-emerald-50/50 to-emerald-100/40 p-4 sm:p-5 flex items-center justify-between min-w-0">
            <div class="absolute left-0 inset-y-0 h-8 w-1 rounded-r-full bg-emerald-500 my-auto"></div>
            <div class="flex-1 min-w-0 pl-1.5 relative z-10">
                <p class="text-emerald-700 text-[9px] sm:text-[10px] font-black uppercase tracking-widest mb-1 truncate">Total Available Pondo</p>
                <h3 class="text-slate-900 text-xl sm:text-2xl font-black tracking-tight leading-none mb-1 truncate tabular-nums">₱{{ number_format($stats['total_available'], 2) }}</h3>
                <p class="text-[9px] text-emerald-600 font-bold uppercase tracking-tight">In Driver Savings Vault</p>
            </div>
            <img src="{{ asset('image/kpi/reward_cash_3d.svg') }}" alt="Available Pondo 3D" class="w-12 h-12 sm:w-14 sm:h-14 object-contain pointer-events-none flex-shrink-0 filter drop-shadow-md">
        </div>

        <!-- Card 2: Lifetime Shift Deposits -->
        <div class="relative overflow-hidden rounded-2xl shadow-xs border border-blue-200/80 bg-gradient-to-br from-white via-blue-50/40 to-blue-100/30 p-4 sm:p-5 flex items-center justify-between min-w-0">
            <div class="absolute left-0 inset-y-0 h-8 w-1 rounded-r-full bg-blue-500 my-auto"></div>
            <div class="flex-1 min-w-0 pl-1.5 relative z-10">
                <p class="text-blue-600 text-[9px] sm:text-[10px] font-black uppercase tracking-widest mb-1 truncate">Lifetime Deposited</p>
                <h3 class="text-slate-900 text-xl sm:text-2xl font-black tracking-tight leading-none mb-1 truncate tabular-nums">₱{{ number_format($stats['total_deposited'], 2) }}</h3>
                <p class="text-[9px] text-blue-500 font-bold uppercase tracking-tight">From shift remittances</p>
            </div>
            <img src="{{ asset('image/kpi/revenue_3d.svg') }}" alt="Lifetime Deposited 3D" class="w-12 h-12 sm:w-14 sm:h-14 object-contain pointer-events-none flex-shrink-0 filter drop-shadow-md">
        </div>

        <!-- Card 3: Accident & Damage Deductions -->
        <div class="relative overflow-hidden rounded-2xl shadow-xs border border-rose-200/80 bg-gradient-to-br from-white via-rose-50/50 to-rose-100/40 p-4 sm:p-5 flex items-center justify-between min-w-0">
            <div class="absolute left-0 inset-y-0 h-8 w-1 rounded-r-full bg-rose-500 my-auto"></div>
            <div class="flex-1 min-w-0 pl-1.5 relative z-10">
                <p class="text-rose-600 text-[9px] sm:text-[10px] font-black uppercase tracking-widest mb-1 truncate">Accident & Damages</p>
                <h3 class="text-slate-900 text-xl sm:text-2xl font-black tracking-tight leading-none mb-1 truncate tabular-nums">₱{{ number_format($stats['total_damages'], 2) }}</h3>
                <p class="text-[9px] text-rose-500 font-bold uppercase tracking-tight">Deducted for unit repairs</p>
            </div>
            <img src="{{ asset('image/kpi/accident_3d.svg') }}" alt="Accident & Damages 3D" class="w-12 h-12 sm:w-14 sm:h-14 object-contain pointer-events-none flex-shrink-0 filter drop-shadow-md">
        </div>

        <!-- Card 4: Maintenance Co-Payments -->
        <div class="relative overflow-hidden rounded-2xl shadow-xs border border-amber-200/80 bg-gradient-to-br from-white via-amber-50/50 to-amber-100/40 p-4 sm:p-5 flex items-center justify-between min-w-0">
            <div class="absolute left-0 inset-y-0 h-8 w-1 rounded-r-full bg-amber-500 my-auto"></div>
            <div class="flex-1 min-w-0 pl-1.5 relative z-10">
                <p class="text-amber-700 text-[9px] sm:text-[10px] font-black uppercase tracking-widest mb-1 truncate">Maintenance Share</p>
                <h3 class="text-slate-900 text-xl sm:text-2xl font-black tracking-tight leading-none mb-1 truncate tabular-nums">₱{{ number_format($stats['total_maintenance'], 2) }}</h3>
                <p class="text-[9px] text-amber-600 font-bold uppercase tracking-tight">Contract maintenance split</p>
            </div>
            <img src="{{ asset('image/kpi/maintenance_3d.svg') }}" alt="Maintenance Share 3D" class="w-12 h-12 sm:w-14 sm:h-14 object-contain pointer-events-none flex-shrink-0 filter drop-shadow-md">
        </div>

        <!-- Card 5: Cashout Withdrawals -->
        <div class="relative overflow-hidden rounded-2xl shadow-xs border border-purple-200/80 bg-gradient-to-br from-white via-purple-50/50 to-purple-100/40 p-4 sm:p-5 flex items-center justify-between min-w-0">
            <div class="absolute left-0 inset-y-0 h-8 w-1 rounded-r-full bg-purple-500 my-auto"></div>
            <div class="flex-1 min-w-0 pl-1.5 relative z-10">
                <p class="text-purple-600 text-[9px] sm:text-[10px] font-black uppercase tracking-widest mb-1 truncate">Driver Cashouts</p>
                <h3 class="text-slate-900 text-xl sm:text-2xl font-black tracking-tight leading-none mb-1 truncate tabular-nums">₱{{ number_format($stats['total_cashouts'], 2) }}</h3>
                <p class="text-[9px] text-purple-500 font-bold uppercase tracking-tight">Personal savings payouts</p>
            </div>
            <img src="{{ asset('image/kpi/payout_3d.svg') }}" alt="Driver Cashouts 3D" class="w-12 h-12 sm:w-14 sm:h-14 object-contain pointer-events-none flex-shrink-0 filter drop-shadow-md">
        </div>
    </div>

    <!-- Main Navigation Tabs -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="border-b border-slate-200 bg-slate-50/50 px-6 pt-3 flex items-center justify-between flex-wrap gap-3 rounded-t-2xl">
            <div class="flex space-x-2">
                <button type="button" onclick="switchLedgerView('transactions')" id="tabBtnTransactions" class="px-4 py-3 border-b-2 font-black text-xs uppercase tracking-wider transition-all flex items-center gap-2 border-emerald-600 text-emerald-700">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="10" y1="6" x2="21" y2="6"></line>
                        <line x1="10" y1="12" x2="21" y2="12"></line>
                        <line x1="10" y1="18" x2="21" y2="18"></line>
                        <polyline points="3 6 4 7 4 5"></polyline>
                        <path d="M4 11h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H4a1 1 0 0 0-1 1v1h3"></path>
                    </svg> All Ledger Transactions
                    <span class="ml-1 px-2 py-0.5 rounded-full text-[10px] font-mono bg-emerald-100 text-emerald-800">{{ $transactions->total() }}</span>
                </button>
                <button type="button" onclick="switchLedgerView('directory')" id="tabBtnDirectory" class="px-4 py-3 border-b-2 font-black text-xs uppercase tracking-wider transition-all flex items-center gap-2 border-transparent text-slate-500 hover:text-slate-800">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg> Driver Balances Directory
                    <span class="ml-1 px-2 py-0.5 rounded-full text-[10px] font-mono bg-slate-200 text-slate-700">{{ count($drivers) }}</span>
                </button>
            </div>
        </div>

        <!-- VIEW 1: All Transactions Ledger -->
        <div id="viewTransactions" class="p-6 space-y-5">
            @php
                $selectedDriver = null;
                if (request('driver_id')) {
                    $selectedDriver = $drivers->firstWhere('id', request('driver_id'));
                }
                $currentSearchValue = request('search') ?: ($selectedDriver ? $selectedDriver->full_name : '');
            @endphp

            <!-- Filter Bar Form -->
            <form id="fundsLedgerFilterForm" method="GET" action="{{ route('driver-management.funds-ledger') }}" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-12 gap-3 bg-slate-50 p-4 rounded-xl border border-slate-200/80">
                <!-- Search & Driver Auto-suggestions (Unified Search) -->
                <div class="lg:col-span-6">
                    <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1 flex items-center justify-between">
                        <span>Search Driver or Keywords</span>
                        @if($currentSearchValue || request('driver_id'))
                            <span class="text-emerald-600 font-bold text-[10px] flex items-center gap-1">
                                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                </svg> filtered
                            </span>
                        @endif
                    </label>
                    <div class="relative" id="ledgerSearchContainer">
                        <div class="relative">
                            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <input type="text" name="search" id="ledgerSearchInput" value="{{ $currentSearchValue }}" 
                                   placeholder="Type driver name, taxi plate, or notes to search..." 
                                   autocomplete="new-password" spellcheck="false" autocorrect="off" autocapitalize="off" data-lpignore="true" data-form-type="other"
                                   class="w-full pl-9 pr-16 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 shadow-2xs">
                            
                            <div class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center gap-0.5">
                                @if($currentSearchValue || request('driver_id'))
                                    <button type="button" onclick="clearLedgerSearch(event)" class="text-slate-400 hover:text-rose-500 p-1 cursor-pointer transition-colors" title="Clear Search & Show All Drivers">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                    </button>
                                @endif
                                <button type="button" onclick="toggleLedgerDriverDropdown(event)" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer" title="Browse Drivers">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="driver_id" id="ledgerFilterDriverId" value="{{ request('driver_id') }}">

                        <!-- Driver Suggestions Dropdown (Same as Add Deposit) -->
                        <div id="ledgerDriverSearchDropdown" class="hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-slate-200 rounded-2xl shadow-2xl max-h-60 overflow-y-auto z-50 divide-y divide-slate-100">
                            <div class="px-3.5 py-2 bg-slate-50 border-b border-slate-100 flex items-center justify-between text-[10px] font-black uppercase tracking-wider text-slate-400 sticky top-0 bg-white/95 backdrop-blur-xs z-10">
                                <span>Driver Suggestions</span>
                                <span id="ledgerDriverMatchCount" class="font-mono">{{ count($drivers) }} drivers</span>
                            </div>
                            <div id="ledgerDriverSuggestionsList" class="divide-y divide-slate-100">
                                @foreach($drivers as $d)
                                    <div class="ledger-filter-driver-item px-3.5 py-2.5 hover:bg-emerald-50/70 cursor-pointer transition-colors flex items-center justify-between gap-2"
                                         data-id="{{ $d->id }}"
                                         data-name="{{ $d->full_name }}"
                                         data-plate="{{ $d->assigned_plate ?: 'Unassigned' }}"
                                         data-license="{{ $d->license_number ?: '' }}"
                                         data-balance="{{ $d->current_balance }}"
                                         onmousedown="selectLedgerFilterDriver(this)"
                                         onclick="selectLedgerFilterDriver(this)">
                                        <div class="min-w-0 flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-700 font-black text-[10px] flex items-center justify-center shrink-0 border border-slate-200 uppercase">
                                                @if(!empty($d->profile_photo))
                                                    <img src="{{ asset('storage/' . $d->profile_photo) }}" alt="" class="w-full h-full object-cover rounded-full">
                                                @else
                                                    {{ substr($d->first_name, 0, 1) }}{{ substr($d->last_name, 0, 1) }}
                                                @endif
                                            </div>
                                            <div class="truncate">
                                                <div class="font-black text-xs text-slate-900 truncate">{{ $d->full_name }}</div>
                                                <div class="text-[10px] font-semibold text-slate-400 flex items-center gap-1.5">
                                                    <span class="font-mono font-bold text-slate-600 bg-slate-100 px-1.5 py-0.5 rounded">{{ $d->assigned_plate ?: 'Unassigned' }}</span>
                                                    @if($d->license_number)
                                                        <span class="font-mono text-[9px]">Lic: {{ $d->license_number }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <span class="text-[9px] font-black uppercase tracking-wider text-slate-400 block">Pondo</span>
                                            <span class="text-xs font-mono font-black text-emerald-700">₱{{ number_format($d->current_balance, 2) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div id="ledgerDriverNoMatch" class="hidden p-4 text-center text-xs text-slate-400 font-semibold">
                                No matching drivers found. Press <kbd class="px-1.5 py-0.5 bg-slate-100 border border-slate-200 rounded text-[10px] font-mono font-bold text-slate-700">Enter</kbd> to search general keywords.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transaction Type Filter -->
                <div class="lg:col-span-3">
                    <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1">Transaction Type</label>
                    <select name="type" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="all" {{ request('type') == 'all' || !request('type') ? 'selected' : '' }}>All Types</option>
                        <option value="deposit" {{ request('type') == 'deposit' ? 'selected' : '' }}>Pondo Deposits (All)</option>
                        <option value="damage_deduction" {{ request('type') == 'damage_deduction' ? 'selected' : '' }}>Accident / Damage Deduction</option>
                        <option value="maintenance_share" {{ request('type') == 'maintenance_share' ? 'selected' : '' }}>Maintenance Share</option>
                        <option value="company_liability" {{ request('type') == 'company_liability' ? 'selected' : '' }}>Debt Settlement</option>
                        <option value="withdrawal" {{ request('type') == 'withdrawal' ? 'selected' : '' }}>Driver Cashout</option>
                    </select>
                </div>

                <!-- Single Date / Range Filter with Custom Compact Calendar -->
                <div class="lg:col-span-3 relative" id="datePickerContainer">
                    <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1">Date / Date Range</label>
                    <div class="flex items-center gap-1.5">
                        <div class="flex-1 min-w-0">
                            <button type="button" onclick="openCalendarPicker(event)" id="btnSingleDate" class="w-full px-3 py-2 bg-white border border-slate-200 hover:border-amber-400 focus:border-amber-500 rounded-xl text-xs font-bold text-slate-700 flex items-center justify-between transition-all shadow-2xs text-left group">
                                <div class="flex items-center gap-2 truncate">
                                    <svg class="w-4 h-4 text-amber-500 shrink-0 group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                    <span id="display_selected_date" class="truncate {{ (request('date') || request('date_from')) ? 'text-slate-900 font-bold' : 'text-slate-400 font-normal' }}">
                                        @if(request('date'))
                                            {{ \Carbon\Carbon::parse(request('date'))->format('M d, Y') }}
                                        @elseif(request('date_from') && request('date_to'))
                                            {{ \Carbon\Carbon::parse(request('date_from'))->format('M d, Y') }} &ndash; {{ \Carbon\Carbon::parse(request('date_to'))->format('M d, Y') }}
                                        @elseif(request('date_from'))
                                            From {{ \Carbon\Carbon::parse(request('date_from'))->format('M d, Y') }}
                                        @else
                                            Select Date or Range
                                        @endif
                                    </span>
                                </div>
                                <div class="flex items-center gap-1 shrink-0">
                                    @if(request('date') || request('date_from') || request('date_to'))
                                        <span onclick="clearSelectedDate(event, true)" class="text-slate-400 hover:text-rose-500 p-0.5 rounded-full hover:bg-slate-100 transition-colors cursor-pointer" title="Clear Date">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                                <line x1="6" y1="6" x2="18" y2="18"></line>
                                            </svg>
                                        </span>
                                    @else
                                        <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="6 9 12 15 18 9"></polyline>
                                        </svg>
                                    @endif
                                </div>
                            </button>
                            <input type="hidden" name="date" id="filter_date" value="{{ request('date') }}">
                            <input type="hidden" name="date_from" id="filter_date_from" value="{{ request('date_from') }}">
                            <input type="hidden" name="date_to" id="filter_date_to" value="{{ request('date_to') }}">
                        </div>

                        <!-- Filter Submit Button -->
                        <button type="submit" class="px-3.5 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition-all flex items-center justify-center shrink-0 shadow-2xs cursor-pointer" title="Apply Filter">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                            </svg>
                        </button>

                        <!-- Reset Filter Button -->
                        @if(request()->anyFilled(['search', 'driver_id', 'type', 'date', 'date_from', 'date_to']))
                            <a href="{{ route('driver-management.funds-ledger') }}" class="px-3 py-2 bg-slate-200 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-300 transition-all flex items-center justify-center shrink-0 shadow-2xs cursor-pointer" title="Reset Filters">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="1 4 1 10 7 10"></polyline>
                                    <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                                </svg>
                            </a>
                        @endif
                    </div>

                    <!-- Custom Compact Calendar Popup (Styled exactly as requested image) -->
                    <div id="customCalendarDropdown" class="hidden absolute right-0 top-full mt-2 w-[295px] bg-white rounded-2xl shadow-2xl border border-slate-200/90 p-3.5 z-[100] select-none">
                        <!-- Calendar Header: Navigation & Month/Year -->
                        <div class="flex items-center justify-between mb-3 px-1">
                            <button type="button" onclick="calendarNavMonth(-1, event)" class="w-7 h-7 flex items-center justify-center text-slate-400 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-colors cursor-pointer" title="Previous Month">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="15 18 9 12 15 6"></polyline>
                                </svg>
                            </button>
                            <span class="font-bold text-slate-800 text-sm tracking-tight text-center" id="calMonthYearTitle"></span>
                            <button type="button" id="calNextMonthBtn" onclick="calendarNavMonth(1, event)" class="w-7 h-7 flex items-center justify-center text-slate-400 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-colors cursor-pointer" title="Next Month">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </button>
                        </div>

                        <!-- Days of Week Header (MON - SUN as in Image) -->
                        <div class="grid grid-cols-7 mb-2 text-center">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider py-0.5">MON</span>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider py-0.5">TUE</span>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider py-0.5">WED</span>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider py-0.5">THU</span>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider py-0.5">FRI</span>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider py-0.5">SAT</span>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider py-0.5">SUN</span>
                        </div>

                        <!-- Days Grid -->
                        <div id="calDaysGrid" class="grid grid-cols-7 gap-y-1 text-center text-xs" onmouseleave="if (isSelecting) updateCalendarStyles(null);">
                            <!-- Populated dynamically -->
                        </div>

                        <!-- Quick Action Footer -->
                        <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                            <div class="flex items-center gap-1">
                                <button type="button" onclick="setCalPreset('today', event)" class="px-2 py-1 text-slate-600 hover:text-amber-600 font-bold rounded-md hover:bg-amber-50 transition-colors cursor-pointer">Today</button>
                                <button type="button" onclick="setCalPreset('month', event)" class="px-2 py-1 text-slate-600 hover:text-amber-600 font-bold rounded-md hover:bg-amber-50 transition-colors cursor-pointer">Month</button>
                                <button type="button" onclick="clearSelectedDate(event, false)" class="px-2 py-1 text-rose-500 hover:text-rose-700 font-bold rounded-md hover:bg-rose-50 transition-colors cursor-pointer">Clear</button>
                            </div>
                            <div class="flex items-center gap-1">
                                <button type="button" onclick="closeCalendarPicker(event)" class="px-2 py-1 text-slate-400 hover:text-slate-600 font-bold rounded-md transition-colors cursor-pointer">
                                    Close
                                </button>
                                <button type="button" id="btnApplyCalendar" onclick="applyDateSelection(event)" class="px-3 py-1 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-lg shadow-xs transition-colors cursor-pointer">
                                    Apply
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Active Filter Banner (Search, Driver, Type, Date) -->
            @if(request()->anyFilled(['search', 'driver_id', 'type', 'date', 'date_from', 'date_to']))
                <div class="flex items-center justify-between bg-amber-50/90 border border-amber-200/90 px-4 py-2.5 rounded-xl text-xs shadow-2xs flex-wrap gap-2">
                    <div class="flex items-center gap-2 text-amber-900 font-bold flex-wrap">
                        <span class="inline-flex items-center gap-1 bg-amber-500 text-white text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md shadow-2xs">
                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                            </svg> Active Filter
                        </span>
                        
                        @if($currentSearchValue || request('driver_id'))
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-white border border-amber-300 rounded-md text-slate-800 font-bold text-xs">
                                <svg class="w-3 h-3 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                Driver / Search: <strong class="text-emerald-700 font-black">{{ $currentSearchValue }}</strong>
                            </span>
                        @endif

                        @if(request('type') && request('type') !== 'all')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-white border border-amber-300 rounded-md text-slate-800 font-bold text-xs">
                                Type: <strong class="text-slate-900 font-black">{{ ucwords(str_replace('_', ' ', request('type'))) }}</strong>
                            </span>
                        @endif

                        @if(request('date') || request('date_from') || request('date_to'))
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-white border border-amber-300 rounded-md text-slate-800 font-bold text-xs">
                                <svg class="w-3 h-3 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                Date:
                                <strong class="text-amber-950 font-black">
                                    @if(request('date'))
                                        {{ rescue(fn() => \Carbon\Carbon::parse(request('date'))->format('M d, Y'), request('date')) }}
                                    @elseif(request('date_from') && request('date_to'))
                                        {{ rescue(fn() => \Carbon\Carbon::parse(request('date_from'))->format('M d, Y'), request('date_from')) }} &ndash; {{ rescue(fn() => \Carbon\Carbon::parse(request('date_to'))->format('M d, Y'), request('date_to')) }}
                                    @elseif(request('date_from'))
                                        From {{ rescue(fn() => \Carbon\Carbon::parse(request('date_from'))->format('M d, Y'), request('date_from')) }}
                                    @endif
                                </strong>
                            </span>
                        @endif

                        @if($transactions->total() === 0)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-rose-100 border border-rose-200 text-rose-700 font-black text-[11px]">
                                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg> No Records Found (0 records)
                            </span>
                        @else
                            <span class="text-slate-500 font-normal">({{ $transactions->total() }} record{{ $transactions->total() === 1 ? '' : 's' }} found)</span>
                        @endif
                    </div>
                    <a href="{{ route('driver-management.funds-ledger') }}" class="text-amber-800 hover:text-amber-950 font-bold flex items-center gap-1 hover:underline text-xs shrink-0 ml-2">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="1 4 1 10 7 10"></polyline>
                            <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                        </svg> Clear All Filters
                    </a>
                </div>
            @endif

            <!-- Table of Transactions -->
            <div id="fundsTransactionsTableWrap" class="overflow-x-auto rounded-xl border border-slate-200/80 transition-opacity duration-200">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50/80 text-slate-500 font-black uppercase tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="p-4 whitespace-nowrap">Date</th>
                            <th class="p-4 whitespace-nowrap">Driver & Unit</th>
                            <th class="p-4 whitespace-nowrap">Transaction Type</th>
                            <th class="p-4">Purpose / Reference Notes</th>
                            <th class="p-4 whitespace-nowrap text-right">Amount</th>
                            <th class="p-4 whitespace-nowrap text-right">Balance After</th>
                            <th class="p-4 whitespace-nowrap">Encoded By</th>
                            <th class="p-4 whitespace-nowrap text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($transactions as $t)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="p-4 font-bold text-slate-600 whitespace-nowrap">
                                <div>{{ \Carbon\Carbon::parse($t->date)->format('M d, Y') }}</div>
                                <span class="text-[10px] text-slate-400 font-normal">{{ \Carbon\Carbon::parse($t->created_at)->format('h:i A') }}</span>
                            </td>
                            <td class="p-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-700 font-black text-xs uppercase overflow-hidden border border-slate-200 shrink-0">
                                        @if(!empty($t->profile_photo))
                                            <img src="{{ asset('storage/' . $t->profile_photo) }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            {{ substr($t->first_name, 0, 1) }}{{ substr($t->last_name, 0, 1) }}
                                        @endif
                                    </div>
                                    <div>
                                        <a href="javascript:void(0)" onclick="openDriverDetailsModal({{ $t->driver_id }})" class="font-black text-slate-900 hover:text-emerald-600 transition-colors block">
                                            {{ $t->driver_name }}
                                        </a>
                                        <div class="flex items-center gap-1.5 text-[10px] font-mono text-slate-400">
                                            <span>Lic: {{ $t->license_number ?: 'N/A' }}</span>
                                            @if($t->shift_plate)
                                                <span>•</span>
                                                <span class="text-slate-600 font-bold">{{ $t->shift_plate }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 whitespace-nowrap">
                                @if($t->type === 'deposit')
                                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-[10px] font-black uppercase tracking-wider inline-flex items-center gap-1">
                                        <svg class="w-3 h-3 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="17" y1="7" x2="7" y2="17"></line>
                                            <polyline points="17 17 7 17 7 7"></polyline>
                                        </svg> {{ $t->boundary_id ? 'Shift Deposit' : 'Manual Deposit' }}
                                    </span>
                                @elseif($t->type === 'damage_deduction')
                                    <span class="px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded-lg text-[10px] font-black uppercase tracking-wider inline-flex items-center gap-1">
                                        <svg class="w-3 h-3 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                            <line x1="12" y1="8" x2="12" y2="12"></line>
                                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                        </svg> Accident / Damage
                                    </span>
                                @elseif($t->type === 'maintenance_share')
                                    <span class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg text-[10px] font-black uppercase tracking-wider inline-flex items-center gap-1">
                                        <svg class="w-3 h-3 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                                        </svg> Maintenance Share
                                    </span>
                                @elseif($t->type === 'company_liability')
                                    <span class="px-2.5 py-1 bg-purple-50 text-purple-700 border border-purple-200 rounded-lg text-[10px] font-black uppercase tracking-wider inline-flex items-center gap-1">
                                        <svg class="w-3 h-3 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1-2-1Z"></path>
                                            <line x1="16" y1="8" x2="8" y2="8"></line>
                                            <line x1="16" y1="12" x2="8" y2="12"></line>
                                            <line x1="13" y1="16" x2="8" y2="16"></line>
                                        </svg> Debt / Liability
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-[10px] font-black uppercase tracking-wider inline-flex items-center gap-1">
                                        <svg class="w-3 h-3 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="7" y1="17" x2="17" y2="7"></line>
                                            <polyline points="7 7 17 7 17 17"></polyline>
                                        </svg> Driver Cashout
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-xs font-medium text-slate-700 max-w-sm">
                                <div class="line-clamp-2" title="{{ $t->description }}">
                                    {{ $t->description ?: 'Shift fund transaction' }}
                                </div>
                                @if($t->boundary_id)
                                    <span class="text-[10px] font-mono text-slate-400 block mt-0.5">Shift Boundary Ref #{{ $t->boundary_id }}</span>
                                @endif
                            </td>
                            <td class="p-4 whitespace-nowrap text-right font-black text-xs">
                                @if($t->type === 'deposit')
                                    <span class="text-emerald-600">+₱{{ number_format($t->amount, 2) }}</span>
                                @else
                                    <span class="text-rose-600">-₱{{ number_format($t->amount, 2) }}</span>
                                @endif
                            </td>
                            <td class="p-4 whitespace-nowrap text-right font-mono font-black text-xs text-slate-900">
                                ₱{{ number_format($t->balance_after, 2) }}
                            </td>
                            <td class="p-4 whitespace-nowrap text-[11px] font-bold text-slate-400">
                                {{ $t->creator_name ?: 'System' }}
                            </td>
                            <td class="p-4 whitespace-nowrap text-center">
                                <button type="button" 
                                        onclick="openLedgerEditModal({{ json_encode([
                                            'id'             => $t->id,
                                            'driver_id'      => $t->driver_id,
                                            'driver_name'    => $t->driver_name,
                                            'license_number' => $t->license_number,
                                            'shift_plate'    => $t->shift_plate,
                                            'boundary_id'    => $t->boundary_id,
                                            'type'           => $t->type,
                                            'amount'         => (float)$t->amount,
                                            'date'           => $t->date,
                                            'description'    => $t->description,
                                        ]) }})" 
                                        class="px-2.5 py-1.5 bg-slate-100 hover:bg-amber-500 text-slate-700 hover:text-white rounded-lg text-xs font-bold transition-all inline-flex items-center gap-1.5 shadow-2xs hover:shadow-xs cursor-pointer group" 
                                        title="Edit Transaction">
                                    <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                    <span>Edit</span>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="p-12 text-center text-slate-500">
                                <img src="{{ asset('image/kpi/history_3d.svg') }}" alt="No Transactions" class="w-14 h-14 sm:w-16 sm:h-16 object-contain pointer-events-none mx-auto mb-3 filter drop-shadow-md">
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 border border-rose-200 text-rose-700 text-xs font-black uppercase tracking-wider mb-2">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                    </svg> No Records Found
                                </div>
                                <h4 class="font-black text-slate-800 text-base mb-1.5">{{ (request('date') || request('date_from')) ? 'No Transactions on Selected Date' : 'No Fund Transactions Found' }}</h4>
                                <p class="text-xs text-slate-500 mb-5 max-w-md mx-auto font-medium leading-relaxed">
                                    @if(request('date'))
                                        No fund transactions recorded on <strong class="text-slate-800 font-bold underline decoration-amber-400">{{ rescue(fn() => \Carbon\Carbon::parse(request('date'))->format('M d, Y'), request('date')) }}</strong>.
                                    @elseif(request('date_from') && request('date_to'))
                                        No fund transactions recorded between <strong class="text-slate-800 font-bold underline decoration-amber-400">{{ rescue(fn() => \Carbon\Carbon::parse(request('date_from'))->format('M d, Y'), request('date_from')) }}</strong> and <strong class="text-slate-800 font-bold underline decoration-amber-400">{{ rescue(fn() => \Carbon\Carbon::parse(request('date_to'))->format('M d, Y'), request('date_to')) }}</strong>.
                                    @elseif(request('date_from'))
                                        No fund transactions recorded starting from <strong class="text-slate-800 font-bold underline decoration-amber-400">{{ rescue(fn() => \Carbon\Carbon::parse(request('date_from'))->format('M d, Y'), request('date_from')) }}</strong>.
                                    @elseif($currentSearchValue || request('driver_id'))
                                        No fund transactions found matching <strong class="text-slate-800 font-bold underline decoration-emerald-400">{{ $currentSearchValue }}</strong>.
                                    @else
                                        No fund transactions match your filter criteria.
                                    @endif
                                </p>
                                @if(request()->anyFilled(['search', 'driver_id', 'type', 'date', 'date_from', 'date_to']))
                                    <a href="{{ route('driver-management.funds-ledger') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-all shadow-md cursor-pointer">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="1 4 1 10 7 10"></polyline>
                                            <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                                        </svg> Clear Filter & View All Records
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        </div>

        <!-- VIEW 2: Driver Balances Directory -->
        <div id="viewDirectory" class="p-6 hidden space-y-4">
            <div class="flex items-center justify-between flex-wrap gap-2">
                <p class="text-xs text-slate-500 font-medium">Overview of individual driver reserves, lifetime deposits, damage deductions, and available balances.</p>
                <div class="relative w-72">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="search" id="directorySearchInput" name="driver_directory_query" onkeyup="filterDirectoryTable()" 
                           placeholder="Search in directory..." 
                           autocomplete="new-password" spellcheck="false" autocorrect="off" autocapitalize="off" data-lpignore="true" data-form-type="other"
                           readonly onfocus="this.removeAttribute('readonly');"
                           class="w-full pl-9 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <div class="overflow-x-auto rounded-xl border border-slate-200/80">
                <table id="directoryTable" class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50/80 text-slate-500 font-black uppercase tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="p-4">Driver Name</th>
                            <th class="p-4">Assigned Taxi</th>
                            <th class="p-4 text-right">Lifetime Deposited</th>
                            <th class="p-4 text-right">Damages / Repairs</th>
                            <th class="p-4 text-right">Maintenance Share</th>
                            <th class="p-4 text-right">Cashouts & Clearances</th>
                            <th class="p-4 text-right">Available Pondo</th>
                            <th class="p-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($drivers as $d)
                        <tr class="hover:bg-slate-50/60 transition-colors directory-row" data-name="{{ strtolower($d->full_name) }}" data-plate="{{ strtolower($d->assigned_plate) }}">
                            <td class="p-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-700 font-black text-xs uppercase overflow-hidden border border-slate-200 shrink-0">
                                        @if(!empty($d->profile_photo))
                                            <img src="{{ asset('storage/' . $d->profile_photo) }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            {{ substr($d->first_name, 0, 1) }}{{ substr($d->last_name, 0, 1) }}
                                        @endif
                                    </div>
                                    <div>
                                        <a href="javascript:void(0)" onclick="openDriverDetailsModal({{ $d->id }})" class="font-black text-slate-900 hover:text-emerald-600 transition-colors block">{{ $d->full_name }}</a>
                                        <span class="text-[10px] text-slate-400 font-mono">Lic: {{ $d->license_number ?: 'N/A' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 whitespace-nowrap">
                                @if($d->assigned_plate)
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-700 font-mono font-bold rounded-lg text-xs border border-slate-200">
                                        {{ $d->assigned_plate }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-xs italic">Unassigned</span>
                                @endif
                            </td>
                            <td class="p-4 whitespace-nowrap text-right font-black text-slate-800">
                                ₱{{ number_format($d->total_deposit, 2) }}
                            </td>
                            <td class="p-4 whitespace-nowrap text-right font-bold {{ $d->damage_deductions > 0 ? 'text-rose-600 font-black' : 'text-slate-400' }}">
                                ₱{{ number_format($d->damage_deductions, 2) }}
                            </td>
                            <td class="p-4 whitespace-nowrap text-right font-bold {{ $d->maintenance_deductions > 0 ? 'text-amber-600 font-black' : 'text-slate-400' }}">
                                ₱{{ number_format($d->maintenance_deductions, 2) }}
                            </td>
                            <td class="p-4 whitespace-nowrap text-right font-bold text-slate-600">
                                ₱{{ number_format($d->cash_withdrawals + $d->liability_deductions, 2) }}
                            </td>
                            <td class="p-4 whitespace-nowrap text-right">
                                <span class="px-3 py-1.5 rounded-xl font-black text-xs font-mono {{ $d->current_balance > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-50 text-slate-400 border border-slate-200' }}">
                                    ₱{{ number_format($d->current_balance, 2) }}
                                </span>
                            </td>
                            <td class="p-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button type="button" onclick="openLedgerDepositModal({{ $d->id }}, '{{ addslashes($d->full_name) }}', {{ $d->current_balance }})" class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[11px] font-black uppercase tracking-wider transition-all flex items-center gap-1 shadow-2xs cursor-pointer" title="Manual Pondo Deposit">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg> Deposit
                                    </button>
                                    <button type="button" onclick="openLedgerDisburseModal({{ $d->id }}, '{{ addslashes($d->full_name) }}', {{ $d->current_balance }})" class="px-2.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-[11px] font-black uppercase tracking-wider transition-all flex items-center gap-1 shadow-2xs cursor-pointer" {{ $d->current_balance <= 0 ? 'disabled style=opacity:0.5;cursor:not-allowed;' : '' }}>
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg> Deduct / Payout
                                    </button>
                                    <a href="{{ route('driver-management.funds-ledger', ['driver_id' => $d->id]) }}" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[11px] font-bold uppercase tracking-wider transition-all flex items-center gap-1 cursor-pointer" title="View driver ledger">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10 9 9 9 8 9"></polyline>
                                        </svg> Statement
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Disburse / Deduct Modal -->
<div id="ledgerDisburseModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200">
        <div class="bg-slate-900 p-5 text-white flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="{{ asset('image/kpi/payout_3d.svg') }}" alt="Disburse / Deduct" class="w-9 h-9 object-contain pointer-events-none filter drop-shadow-sm shrink-0">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider">Disburse / Deduct Fund</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Driver Savings & Maintenance Reserve</p>
                </div>
            </div>
            <button type="button" onclick="closeLedgerDisburseModal()" class="text-slate-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <form id="ledgerDisburseForm" onsubmit="submitLedgerDisbursement(event)" class="p-6 space-y-4">
            <!-- Driver Selection (Searchable with instant suggestions) -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Select Driver <span class="text-red-500">*</span></label>
                <div class="relative" id="disburseDriverSearchContainer">
                    <div class="relative">
                        <input type="text" id="disburseDriverSearchInput" required
                               autocomplete="new-password" spellcheck="false" autocorrect="off" autocapitalize="off" data-lpignore="true" data-form-type="other"
                               class="w-full pl-9 pr-9 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-2xs"
                               placeholder="Type driver name or plate to search...">
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <button type="button" onclick="toggleDisburseDriverDropdown(event)" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                    </div>
                    <input type="hidden" id="disburseDriverSelect" name="driver_id" required value="">

                    <!-- Driver Suggestions Dropdown -->
                    <div id="disburseDriverDropdown" class="hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-slate-200 rounded-2xl shadow-2xl max-h-56 overflow-y-auto z-50 divide-y divide-slate-100">
                        <div class="px-3.5 py-2 bg-slate-50 border-b border-slate-100 flex items-center justify-between text-[10px] font-black uppercase tracking-wider text-slate-400 sticky top-0 bg-white/95 backdrop-blur-xs z-10">
                            <span>Available Drivers</span>
                            <span id="disburseDriverMatchCount" class="font-mono">{{ count($drivers) }} drivers</span>
                        </div>
                        <div id="disburseDriverList" class="divide-y divide-slate-100">
                            @foreach($drivers as $d)
                                <div class="disburse-driver-item px-3.5 py-2.5 hover:bg-rose-50/70 cursor-pointer transition-colors flex items-center justify-between gap-2"
                                     data-id="{{ $d->id }}"
                                     data-name="{{ $d->full_name }}"
                                     data-plate="{{ $d->assigned_plate ?: 'Unassigned' }}"
                                     data-license="{{ $d->license_number ?: '' }}"
                                     data-balance="{{ $d->current_balance }}"
                                     onmousedown="selectDisburseDriver(this)"
                                     onclick="selectDisburseDriver(this)">
                                    <div class="min-w-0 flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-700 font-black text-[10px] flex items-center justify-center shrink-0 border border-slate-200 uppercase">
                                            @if(!empty($d->profile_photo))
                                                <img src="{{ asset('storage/' . $d->profile_photo) }}" alt="" class="w-full h-full object-cover rounded-full">
                                            @else
                                                {{ substr($d->first_name, 0, 1) }}{{ substr($d->last_name, 0, 1) }}
                                            @endif
                                        </div>
                                        <div class="truncate">
                                            <div class="font-black text-xs text-slate-900 truncate">{{ $d->full_name }}</div>
                                            <div class="text-[10px] font-semibold text-slate-400 flex items-center gap-1.5">
                                                <span class="font-mono font-bold text-slate-600 bg-slate-100 px-1.5 py-0.5 rounded">{{ $d->assigned_plate ?: 'Unassigned' }}</span>
                                                @if($d->license_number)
                                                    <span class="font-mono text-[9px]">Lic: {{ $d->license_number }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <span class="text-[9px] font-black uppercase tracking-wider text-slate-400 block">Available</span>
                                        <span class="text-xs font-mono font-black {{ $d->current_balance > 0 ? 'text-emerald-700' : 'text-slate-400' }}">₱{{ number_format($d->current_balance, 2) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div id="disburseDriverNoMatch" class="hidden p-4 text-center text-xs text-slate-400 font-semibold">
                            No drivers found matching your search.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Balance Display -->
            <div class="p-3.5 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-emerald-800">Current Available Fund</span>
                    <p id="disburseAvailableDisplay" class="text-lg font-black text-emerald-700">₱0.00</p>
                </div>
                <span class="text-[9px] font-black bg-emerald-200 text-emerald-900 px-2 py-0.5 rounded-full uppercase tracking-wider">Withdrawable</span>
            </div>

            <!-- Disbursement / Deduction Type -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Disbursement / Deduction Type <span class="text-red-500">*</span></label>
                <select id="disburseTypeSelect" required class="w-full px-3 py-2.5 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="damage_deduction">Accident / Collision Damage Deduction (Vehicle Repair)</option>
                    <option value="maintenance_share">Vehicle Maintenance Co-Payment (Maintenance Share)</option>
                    <option value="company_liability">Company Liability / Debt Settlement (Debt Deduction)</option>
                    <option value="withdrawal">Driver Personal Savings Withdrawal (Cashout / Payout)</option>
                </select>
            </div>

            <!-- Amount & Date -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Amount (₱) <span class="text-red-500">*</span></label>
                    <input type="number" id="disburseAmountInput" required min="1" step="0.01" class="w-full px-3 py-2.5 border border-slate-300 rounded-xl text-sm font-black text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="0.00">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Transaction Date <span class="text-red-500">*</span></label>
                    <input type="date" id="disburseDateInput" required max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>

            <!-- Description / Reason -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Purpose / Repair Details <span class="text-red-500">*</span></label>
                <textarea id="disburseDescriptionInput" required rows="2" maxlength="250" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 font-medium" placeholder="E.g., Offset for rear bumper repair / Cashout request..."></textarea>
            </div>

            <!-- Buttons -->
            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="closeLedgerDisburseModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all cursor-pointer">Cancel</button>
                <button type="submit" id="btnSubmitLedgerDisburse" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-black uppercase tracking-wider shadow-md shadow-rose-500/20 transition-all flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg> Confirm Deduction / Payout
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Manual Pondo Deposit Modal -->
<div id="ledgerDepositModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200">
        <div class="bg-slate-900 p-5 text-white flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="{{ asset('image/kpi/reward_cash_3d.svg') }}" alt="Manual Pondo Deposit" class="w-9 h-9 object-contain pointer-events-none filter drop-shadow-sm shrink-0">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider">Manual Pondo Deposit</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Driver Savings & Emergency Reserve</p>
                </div>
            </div>
            <button type="button" onclick="closeLedgerDepositModal()" class="text-slate-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <form id="ledgerDepositForm" onsubmit="submitLedgerDeposit(event)" class="p-6 space-y-4">
            <!-- Driver Selection (Searchable with instant suggestions) -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Select Driver <span class="text-red-500">*</span></label>
                <div class="relative" id="depositDriverSearchContainer">
                    <div class="relative">
                        <input type="text" id="depositDriverSearchInput" required
                               autocomplete="new-password" spellcheck="false" autocorrect="off" autocapitalize="off" data-lpignore="true" data-form-type="other"
                               class="w-full pl-9 pr-9 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 shadow-2xs"
                               placeholder="Type driver name or plate to search...">
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <button type="button" onclick="toggleDepositDriverDropdown(event)" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                    </div>
                    <input type="hidden" id="depositDriverSelect" name="driver_id" required value="">

                    <!-- Driver Suggestions Dropdown -->
                    <div id="depositDriverDropdown" class="hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-slate-200 rounded-2xl shadow-2xl max-h-56 overflow-y-auto z-50 divide-y divide-slate-100">
                        <div class="px-3.5 py-2 bg-slate-50 border-b border-slate-100 flex items-center justify-between text-[10px] font-black uppercase tracking-wider text-slate-400 sticky top-0 bg-white/95 backdrop-blur-xs z-10">
                            <span>Available Drivers</span>
                            <span id="depositDriverMatchCount" class="font-mono">{{ count($drivers) }} drivers</span>
                        </div>
                        <div id="depositDriverList" class="divide-y divide-slate-100">
                            @foreach($drivers as $d)
                                <div class="deposit-driver-item px-3.5 py-2.5 hover:bg-emerald-50/70 cursor-pointer transition-colors flex items-center justify-between gap-2"
                                     data-id="{{ $d->id }}"
                                     data-name="{{ $d->full_name }}"
                                     data-plate="{{ $d->assigned_plate ?: 'Unassigned' }}"
                                     data-license="{{ $d->license_number ?: '' }}"
                                     data-balance="{{ $d->current_balance }}"
                                     onmousedown="selectDepositDriver(this)"
                                     onclick="selectDepositDriver(this)">
                                    <div class="min-w-0 flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-700 font-black text-[10px] flex items-center justify-center shrink-0 border border-slate-200 uppercase">
                                            @if(!empty($d->profile_photo))
                                                <img src="{{ asset('storage/' . $d->profile_photo) }}" alt="" class="w-full h-full object-cover rounded-full">
                                            @else
                                                {{ substr($d->first_name, 0, 1) }}{{ substr($d->last_name, 0, 1) }}
                                            @endif
                                        </div>
                                        <div class="truncate">
                                            <div class="font-black text-xs text-slate-900 truncate">{{ $d->full_name }}</div>
                                            <div class="text-[10px] font-semibold text-slate-400 flex items-center gap-1.5">
                                                <span class="font-mono font-bold text-slate-600 bg-slate-100 px-1.5 py-0.5 rounded">{{ $d->assigned_plate ?: 'Unassigned' }}</span>
                                                @if($d->license_number)
                                                    <span class="font-mono text-[9px]">Lic: {{ $d->license_number }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <span class="text-[9px] font-black uppercase tracking-wider text-slate-400 block">Pondo</span>
                                        <span class="text-xs font-mono font-black text-emerald-700">₱{{ number_format($d->current_balance, 2) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div id="depositDriverNoMatch" class="hidden p-4 text-center text-xs text-slate-400 font-semibold">
                            No drivers found matching your search.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Balance & Projected Balance Display -->
            <div class="p-3.5 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-emerald-800">Current Pondo Balance</span>
                    <p id="depositCurrentBalDisplay" class="text-lg font-black text-emerald-700">₱0.00</p>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-500">Projected Balance</span>
                    <p id="depositProjectedBalDisplay" class="text-sm font-black text-slate-700">₱0.00</p>
                </div>
            </div>

            <!-- Amount & Date -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Deposit Amount (₱) <span class="text-red-500">*</span></label>
                    <input type="number" id="depositAmountInput" required min="1" step="0.01" oninput="updateDepositProjectedBal()" class="w-full px-3 py-2.5 border border-slate-300 rounded-xl text-sm font-black text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="0.00">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Deposit Date <span class="text-red-500">*</span></label>
                    <input type="date" id="depositDateInput" required max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>

            <!-- Description / Reason -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Purpose / Payment Reference <span class="text-red-500">*</span></label>
                <textarea id="depositDescriptionInput" required rows="2" maxlength="250" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 font-medium" placeholder="E.g., Direct manual cash pondo payment / GCash reference #..."></textarea>
            </div>

            <!-- Buttons -->
            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="closeLedgerDepositModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all cursor-pointer">Cancel</button>
                <button type="submit" id="btnSubmitLedgerDeposit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black uppercase tracking-wider shadow-md shadow-emerald-500/20 transition-all flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg> Record Deposit
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Fund Transaction Modal -->
<div id="ledgerEditModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200">
        <!-- Modal Header -->
        <div class="bg-slate-900 p-5 text-white flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-500/20 border border-amber-500/30 flex items-center justify-center text-amber-400 shrink-0">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider">Edit Fund Transaction</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Modify Record Details & Amounts</p>
                </div>
            </div>
            <button type="button" onclick="closeLedgerEditModal()" class="text-slate-400 hover:text-white transition-colors p-1 rounded-lg hover:bg-slate-800 cursor-pointer">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <form id="ledgerEditForm" onsubmit="submitLedgerEdit(event)" class="p-6 space-y-4">
            <input type="hidden" id="editTransactionId" value="">

            <!-- Driver Info Card (Readonly Reference) -->
            <div class="p-3.5 bg-slate-50 border border-slate-200/90 rounded-xl flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-700 font-black text-xs flex items-center justify-center shrink-0 uppercase" id="editDriverInitials">
                        DR
                    </div>
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">Driver</span>
                        <h4 class="text-xs font-black text-slate-900" id="editDriverNameDisplay">—</h4>
                        <div class="flex items-center gap-1.5 text-[10px] font-mono text-slate-500">
                            <span id="editDriverLicenseDisplay">Lic: —</span>
                            <span id="editDriverPlateDot">•</span>
                            <span class="font-bold text-slate-700" id="editDriverPlateDisplay">—</span>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">Tx Ref ID</span>
                    <span class="text-xs font-mono font-black text-slate-800" id="editTxIdDisplay">#—</span>
                </div>
            </div>

            <!-- Boundary Link Notice Banner (if linked) -->
            <div id="editBoundaryBanner" class="hidden p-3 bg-blue-50 border border-blue-200 rounded-xl flex items-start gap-2.5">
                <svg class="w-4 h-4 text-blue-600 mt-0.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                <div class="text-[11px] text-blue-900 leading-snug">
                    <strong class="font-bold">Shift Boundary Linked:</strong>
                    <span id="editBoundaryBannerText">This transaction is tied to a shift boundary remittance. Updating the amount will synchronize the boundary's driver fund record.</span>
                </div>
            </div>

            <!-- Transaction Type -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Transaction Type <span class="text-red-500">*</span></label>
                <select id="editTypeSelect" required class="w-full px-3 py-2.5 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    <option value="deposit">Pondo Deposit (Savings Remittance)</option>
                    <option value="damage_deduction">Accident / Damage Deduction</option>
                    <option value="maintenance_share">Maintenance Share (Co-payment)</option>
                    <option value="company_liability">Debt / Liability Settlement</option>
                    <option value="withdrawal">Driver Cashout / Savings Payout</option>
                </select>
                <p id="editTypeNotice" class="hidden text-[10px] text-slate-400 font-medium mt-1 italic">Type is locked to Deposit because this was generated by a shift boundary.</p>
            </div>

            <!-- Amount & Date -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Amount (₱) <span class="text-red-500">*</span></label>
                    <input type="number" id="editAmountInput" required min="0.01" step="0.01" class="w-full px-3 py-2.5 border border-slate-300 rounded-xl text-sm font-black text-slate-900 focus:ring-2 focus:ring-amber-500 focus:border-amber-500" placeholder="0.00">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Transaction Date <span class="text-red-500">*</span></label>
                    <input type="date" id="editDateInput" required max="{{ date('Y-m-d') }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                </div>
            </div>

            <!-- Description / Notes -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Purpose / Reference Notes <span class="text-red-500">*</span></label>
                <textarea id="editDescriptionInput" required rows="2" maxlength="255" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 font-medium" placeholder="Reference notes or remarks..."></textarea>
            </div>

            <!-- Buttons -->
            <div class="pt-2 flex items-center justify-between gap-2 border-t border-slate-100">
                <button type="button" onclick="confirmDeleteFundTransaction()" class="px-3.5 py-2 text-rose-600 hover:text-white hover:bg-rose-600 border border-rose-200 hover:border-transparent rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer" title="Delete this entry">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    <span>Delete</span>
                </button>

                <div class="flex items-center gap-2">
                    <button type="button" onclick="closeLedgerEditModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all cursor-pointer">Cancel</button>
                    <button type="submit" id="btnSubmitLedgerEdit" class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-black uppercase tracking-wider shadow-md shadow-amber-500/20 transition-all flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg> Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@include('driver-management.partials._driver_details_modal')

<script>
    // --- Edit Fund Transaction Handlers ---
    let currentEditingTx = null;

    function openLedgerEditModal(tx) {
        currentEditingTx = tx;
        document.getElementById('editTransactionId').value = tx.id;
        document.getElementById('editTxIdDisplay').textContent = '#' + tx.id;

        // Initials
        let initials = 'DR';
        if (tx.driver_name) {
            const parts = tx.driver_name.trim().split(/\s+/);
            if (parts.length >= 2) {
                initials = (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
            } else if (parts.length === 1 && parts[0].length > 0) {
                initials = parts[0].substring(0, 2).toUpperCase();
            }
        }
        document.getElementById('editDriverInitials').textContent = initials;
        document.getElementById('editDriverNameDisplay').textContent = tx.driver_name || '—';
        document.getElementById('editDriverLicenseDisplay').textContent = tx.license_number ? 'Lic: ' + tx.license_number : 'Lic: N/A';
        document.getElementById('editDriverPlateDisplay').textContent = tx.shift_plate || 'Unassigned';

        // Boundary linked state
        const banner = document.getElementById('editBoundaryBanner');
        const bannerText = document.getElementById('editBoundaryBannerText');
        const typeSelect = document.getElementById('editTypeSelect');
        const typeNotice = document.getElementById('editTypeNotice');

        if (tx.boundary_id) {
            banner.classList.remove('hidden');
            if (bannerText) {
                bannerText.textContent = `This transaction is tied to shift boundary #${tx.boundary_id}. Updating the amount will synchronize the boundary's driver fund record.`;
            }
            typeSelect.value = 'deposit';
            typeSelect.disabled = true;
            typeNotice.classList.remove('hidden');
        } else {
            banner.classList.add('hidden');
            typeSelect.disabled = false;
            typeSelect.value = tx.type || 'deposit';
            typeNotice.classList.add('hidden');
        }

        document.getElementById('editAmountInput').value = parseFloat(tx.amount || 0).toFixed(2);
        document.getElementById('editDateInput').value = tx.date ? tx.date.split('T')[0] : '';
        document.getElementById('editDescriptionInput').value = tx.description || '';

        document.getElementById('ledgerEditModal').classList.remove('hidden');
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function closeLedgerEditModal() {
        document.getElementById('ledgerEditModal').classList.add('hidden');
        currentEditingTx = null;
    }

    function submitLedgerEdit(e) {
        e.preventDefault();
        if (!currentEditingTx) return;

        const id = document.getElementById('editTransactionId').value;
        const type = currentEditingTx.boundary_id ? 'deposit' : document.getElementById('editTypeSelect').value;
        const amount = parseFloat(document.getElementById('editAmountInput').value || 0);
        const date = document.getElementById('editDateInput').value;
        const description = document.getElementById('editDescriptionInput').value.trim();

        if (isNaN(amount) || amount <= 0) {
            Swal.fire({ icon: 'warning', title: 'Invalid Amount', text: 'Please enter a valid amount greater than ₱0.00.' });
            return;
        }

        const todayStr = (typeof getLocalTodayStr === 'function') ? getLocalTodayStr() : new Date().toISOString().split('T')[0];
        if (date > todayStr) {
            Swal.fire({ icon: 'warning', title: 'Invalid Date', text: 'Transaction date cannot be in the future.' });
            return;
        }

        const btn = document.getElementById('btnSubmitLedgerEdit');
        btn.disabled = true;
        btn.innerHTML = '<svg class="w-4 h-4 animate-spin inline mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-6.219-8.56"></path></svg> Saving...';

        fetch(`/driver-management/funds-ledger/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ amount, date, type, description })
        })
        .then(r => r.json())
        .then(res => {
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-4 h-4 inline mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> Save Changes';

            if (res.success) {
                closeLedgerEditModal();
                Swal.fire({
                    icon: 'success',
                    title: 'Transaction Updated!',
                    text: res.message || 'Ledger transaction updated successfully.',
                    timer: 1800,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    text: res.message || 'Failed to update transaction.'
                });
            }
        })
        .catch(err => {
            console.error(err);
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-4 h-4 inline mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> Save Changes';
            Swal.fire({ icon: 'error', title: 'Network Error', text: 'Something went wrong while connecting to the server.' });
        });
    }

    function confirmDeleteFundTransaction() {
        if (!currentEditingTx) return;
        const id = currentEditingTx.id;
        const isBoundaryLinked = !!currentEditingTx.boundary_id;

        let warningHtml = 'Are you sure you want to delete this ledger transaction? This will automatically recalculate all subsequent running balances for this driver.';
        if (isBoundaryLinked) {
            warningHtml += '<br><br><strong class="text-rose-600">Note:</strong> This entry is linked to shift boundary remittance #' + currentEditingTx.boundary_id + '. Deleting it will also reset the boundary\'s driver fund to ₱0.00.';
        }

        Swal.fire({
            title: 'Delete Transaction #' + id + '?',
            html: warningHtml,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Delete Entry',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait while removing record and recalculating balances.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/driver-management/funds-ledger/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        closeLedgerEditModal();
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: res.message || 'Transaction deleted successfully.',
                            timer: 1800,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Delete Failed',
                            text: res.message || 'Failed to delete transaction.'
                        });
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire({ icon: 'error', title: 'Network Error', text: 'Something went wrong while connecting to the server.' });
                });
            }
        });
    }

    let currentSelectedDriverBal = 0;

    function switchLedgerView(view) {
        const tabBtnTransactions = document.getElementById('tabBtnTransactions');
        const tabBtnDirectory = document.getElementById('tabBtnDirectory');
        const viewTransactions = document.getElementById('viewTransactions');
        const viewDirectory = document.getElementById('viewDirectory');

        if (view === 'transactions') {
            tabBtnTransactions.className = 'px-4 py-3 border-b-2 font-black text-xs uppercase tracking-wider transition-all flex items-center gap-2 border-emerald-600 text-emerald-700';
            tabBtnDirectory.className = 'px-4 py-3 border-b-2 font-black text-xs uppercase tracking-wider transition-all flex items-center gap-2 border-transparent text-slate-500 hover:text-slate-800';
            viewTransactions.classList.remove('hidden');
            viewDirectory.classList.add('hidden');
        } else {
            tabBtnDirectory.className = 'px-4 py-3 border-b-2 font-black text-xs uppercase tracking-wider transition-all flex items-center gap-2 border-emerald-600 text-emerald-700';
            tabBtnTransactions.className = 'px-4 py-3 border-b-2 font-black text-xs uppercase tracking-wider transition-all flex items-center gap-2 border-transparent text-slate-500 hover:text-slate-800';
            viewDirectory.classList.remove('hidden');
            viewTransactions.classList.add('hidden');
            setTimeout(sanitizeSearchAutofill, 20);
        }
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function filterDirectoryTable() {
        const inputEl = document.getElementById('directorySearchInput');
        if (!inputEl) return;
        let rawVal = inputEl.value || '';
        // If browser autofilled an email, clear it immediately
        if (rawVal.includes('@')) {
            inputEl.value = '';
            rawVal = '';
        }
        const query = rawVal.toLowerCase().trim();
        const rows = document.querySelectorAll('.directory-row');
        rows.forEach(r => {
            const name = r.getAttribute('data-name') || '';
            const plate = r.getAttribute('data-plate') || '';
            if (!query || name.includes(query) || plate.includes(query)) {
                r.style.display = '';
            } else {
                r.style.display = 'none';
            }
        });
    }

    function sanitizeSearchAutofill() {
        const dirInput = document.getElementById('directorySearchInput');
        if (dirInput && dirInput.value && dirInput.value.includes('@')) {
            dirInput.value = '';
            filterDirectoryTable();
        }
        const ledgerSearch = document.getElementById('ledgerSearchInput');
        if (ledgerSearch && ledgerSearch.value && ledgerSearch.value.includes('@')) {
            @if(!request()->has('search') || request('search') === '')
                ledgerSearch.value = '';
            @endif
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        sanitizeSearchAutofill();
        setTimeout(sanitizeSearchAutofill, 50);
        setTimeout(sanitizeSearchAutofill, 200);
        setTimeout(sanitizeSearchAutofill, 600);
        setTimeout(sanitizeSearchAutofill, 1200);
    });

    // --- Ledger Filter Driver Search Handlers ---
    function toggleLedgerDriverDropdown(e) {
        if (e) e.stopPropagation();
        const dd = document.getElementById('ledgerDriverSearchDropdown');
        const input = document.getElementById('ledgerSearchInput');
        if (dd && dd.classList.contains('hidden')) {
            filterLedgerDrivers(input ? input.value : '');
            dd.classList.remove('hidden');
            if (input) input.focus();
        } else if (dd) {
            dd.classList.add('hidden');
        }
    }

    function filterLedgerDrivers(term) {
        const cleanTerm = (term || '').toLowerCase().trim();
        const items = document.querySelectorAll('.ledger-filter-driver-item');
        let matches = 0;
        items.forEach(item => {
            const name = (item.getAttribute('data-name') || '').toLowerCase();
            const plate = (item.getAttribute('data-plate') || '').toLowerCase();
            const license = (item.getAttribute('data-license') || '').toLowerCase();
            if (!cleanTerm || name.includes(cleanTerm) || plate.includes(cleanTerm) || license.includes(cleanTerm)) {
                item.style.display = 'flex';
                matches++;
            } else {
                item.style.display = 'none';
            }
        });
        const countEl = document.getElementById('ledgerDriverMatchCount');
        if (countEl) countEl.textContent = `${matches} driver${matches === 1 ? '' : 's'}`;
        const noMatchEl = document.getElementById('ledgerDriverNoMatch');
        if (noMatchEl) noMatchEl.classList.toggle('hidden', matches > 0);
    }

    function selectLedgerFilterDriver(el) {
        const id = el.getAttribute('data-id');
        const name = el.getAttribute('data-name');

        const searchInput = document.getElementById('ledgerSearchInput');
        const driverIdInput = document.getElementById('ledgerFilterDriverId');
        const dropdown = document.getElementById('ledgerDriverSearchDropdown');

        if (searchInput) searchInput.value = name;
        if (driverIdInput) driverIdInput.value = id;
        if (dropdown) dropdown.classList.add('hidden');

        const form = document.getElementById('fundsLedgerFilterForm');
        if (form) {
            const tableWrap = document.getElementById('fundsTransactionsTableWrap');
            if (tableWrap) {
                tableWrap.style.opacity = '0.35';
                tableWrap.style.pointerEvents = 'none';
            }
            if (typeof form.requestSubmit === 'function') {
                form.requestSubmit();
            } else {
                form.submit();
            }
        }
    }

    function clearLedgerSearch(e) {
        if (e) {
            e.stopPropagation();
            e.preventDefault();
        }
        const searchInput = document.getElementById('ledgerSearchInput');
        const driverIdInput = document.getElementById('ledgerFilterDriverId');
        if (searchInput) searchInput.value = '';
        if (driverIdInput) driverIdInput.value = '';

        const form = document.getElementById('fundsLedgerFilterForm');
        if (form) {
            const tableWrap = document.getElementById('fundsTransactionsTableWrap');
            if (tableWrap) {
                tableWrap.style.opacity = '0.35';
                tableWrap.style.pointerEvents = 'none';
            }
            if (typeof form.requestSubmit === 'function') {
                form.requestSubmit();
            } else {
                form.submit();
            }
        }
    }

    function openLedgerDisburseModal(driverId = null, driverName = null, balance = null) {
        const hiddenInput = document.getElementById('disburseDriverSelect');
        const searchInput = document.getElementById('disburseDriverSearchInput');
        if (driverId) {
            hiddenInput.value = driverId;
            searchInput.value = driverName || '';
            currentSelectedDriverBal = balance !== null ? parseFloat(balance) : 0;
            document.getElementById('disburseAvailableDisplay').textContent = '₱' + currentSelectedDriverBal.toLocaleString('en-PH', {minimumFractionDigits: 2});
            document.getElementById('disburseAmountInput').max = currentSelectedDriverBal;
        } else {
            hiddenInput.value = '';
            searchInput.value = '';
            currentSelectedDriverBal = 0;
            document.getElementById('disburseAvailableDisplay').textContent = '₱0.00';
            document.getElementById('disburseAmountInput').max = 0;
            filterDisburseDrivers('');
        }
        document.getElementById('disburseDriverDropdown')?.classList.add('hidden');
        document.getElementById('disburseAmountInput').value = '';
        document.getElementById('disburseDescriptionInput').value = '';
        document.getElementById('ledgerDisburseModal').classList.remove('hidden');
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function toggleDisburseDriverDropdown(e) {
        if (e) e.stopPropagation();
        const dd = document.getElementById('disburseDriverDropdown');
        const input = document.getElementById('disburseDriverSearchInput');
        if (dd.classList.contains('hidden')) {
            filterDisburseDrivers(input.value);
            dd.classList.remove('hidden');
            input.focus();
        } else {
            dd.classList.add('hidden');
        }
    }

    function filterDisburseDrivers(term) {
        const cleanTerm = (term || '').toLowerCase().trim();
        const items = document.querySelectorAll('.disburse-driver-item');
        let matches = 0;
        items.forEach(item => {
            const name = (item.getAttribute('data-name') || '').toLowerCase();
            const plate = (item.getAttribute('data-plate') || '').toLowerCase();
            const license = (item.getAttribute('data-license') || '').toLowerCase();
            if (!cleanTerm || name.includes(cleanTerm) || plate.includes(cleanTerm) || license.includes(cleanTerm)) {
                item.style.display = 'flex';
                matches++;
            } else {
                item.style.display = 'none';
            }
        });
        const countEl = document.getElementById('disburseDriverMatchCount');
        if (countEl) countEl.textContent = `${matches} driver${matches === 1 ? '' : 's'}`;
        const noMatchEl = document.getElementById('disburseDriverNoMatch');
        if (noMatchEl) noMatchEl.classList.toggle('hidden', matches > 0);
    }

    function selectDisburseDriver(el) {
        const id = el.getAttribute('data-id');
        const name = el.getAttribute('data-name');
        const balance = parseFloat(el.getAttribute('data-balance') || 0);

        document.getElementById('disburseDriverSelect').value = id;
        document.getElementById('disburseDriverSearchInput').value = name;
        currentSelectedDriverBal = balance;
        document.getElementById('disburseAvailableDisplay').textContent = '₱' + balance.toLocaleString('en-PH', {minimumFractionDigits: 2});
        document.getElementById('disburseAmountInput').max = balance;
        document.getElementById('disburseDriverDropdown').classList.add('hidden');
    }

    function closeLedgerDisburseModal() {
        document.getElementById('ledgerDisburseModal').classList.add('hidden');
        document.getElementById('disburseDriverDropdown')?.classList.add('hidden');
    }

    function submitLedgerDisbursement(e) {
        e.preventDefault();
        const driverId = document.getElementById('disburseDriverSelect').value;
        const amount = parseFloat(document.getElementById('disburseAmountInput').value || 0);
        const type = document.getElementById('disburseTypeSelect').value;
        const date = document.getElementById('disburseDateInput').value;
        const description = document.getElementById('disburseDescriptionInput').value;

        if (!driverId) {
            Swal.fire({ icon: 'warning', title: 'Driver Required', text: 'Please select a driver from the dropdown.' });
            return;
        }

        const todayStr = new Date().toISOString().split('T')[0];
        if (date > todayStr) {
            Swal.fire({ icon: 'warning', title: 'Invalid Date', text: 'Transaction date cannot be in the advance or future.' });
            return;
        }

        if (amount <= 0) {
            Swal.fire({ icon: 'warning', title: 'Invalid Amount', text: 'Please enter a valid disbursement amount greater than ₱0.00.' });
            return;
        }

        if (amount > currentSelectedDriverBal) {
            Swal.fire({ icon: 'error', title: 'Insufficient Funds', text: `Amount (₱${amount.toLocaleString('en-PH', {minimumFractionDigits: 2})}) exceeds current available balance of ₱${currentSelectedDriverBal.toLocaleString('en-PH', {minimumFractionDigits: 2})}.` });
            return;
        }

        const btn = document.getElementById('btnSubmitLedgerDisburse');
        btn.disabled = true;
        btn.innerHTML = '<svg class="w-4 h-4 animate-spin inline mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-6.219-8.56"></path></svg> Processing...';

        fetch(`/driver-management/${driverId}/withdraw-fund`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ amount, type, date, description })
        })
        .then(r => r.json())
        .then(res => {
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-4 h-4 inline mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> Confirm Deduction / Payout';

            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Deduction / Payout Recorded!',
                    text: res.message || 'Transaction successfully saved into driver fund ledger.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Transaction Failed',
                    text: res.message || 'Unable to record disbursement.'
                });
            }
        })
        .catch(err => {
            console.error(err);
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-4 h-4 inline mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> Confirm Deduction / Payout';
            Swal.fire({ icon: 'error', title: 'Network Error', text: 'Something went wrong while connecting to the server.' });
        });
    }

    // --- Manual Deposit Modal JS Handlers ---
    let currentDepositDriverBal = 0;

    function openLedgerDepositModal(driverId = null, driverName = null, balance = null) {
        const hiddenInput = document.getElementById('depositDriverSelect');
        const searchInput = document.getElementById('depositDriverSearchInput');
        if (driverId) {
            hiddenInput.value = driverId;
            searchInput.value = driverName || '';
            currentDepositDriverBal = balance !== null ? parseFloat(balance) : 0;
            document.getElementById('depositCurrentBalDisplay').textContent = '₱' + currentDepositDriverBal.toLocaleString('en-PH', {minimumFractionDigits: 2});
        } else {
            hiddenInput.value = '';
            searchInput.value = '';
            currentDepositDriverBal = 0;
            document.getElementById('depositCurrentBalDisplay').textContent = '₱0.00';
            filterDepositDrivers('');
        }
        document.getElementById('depositDriverDropdown')?.classList.add('hidden');
        document.getElementById('depositAmountInput').value = '';
        document.getElementById('depositDescriptionInput').value = '';
        document.getElementById('depositProjectedBalDisplay').textContent = '₱' + currentDepositDriverBal.toLocaleString('en-PH', {minimumFractionDigits: 2});
        document.getElementById('ledgerDepositModal').classList.remove('hidden');
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function toggleDepositDriverDropdown(e) {
        if (e) e.stopPropagation();
        const dd = document.getElementById('depositDriverDropdown');
        const input = document.getElementById('depositDriverSearchInput');
        if (dd.classList.contains('hidden')) {
            filterDepositDrivers(input.value);
            dd.classList.remove('hidden');
            input.focus();
        } else {
            dd.classList.add('hidden');
        }
    }

    function filterDepositDrivers(term) {
        const cleanTerm = (term || '').toLowerCase().trim();
        const items = document.querySelectorAll('.deposit-driver-item');
        let matches = 0;
        items.forEach(item => {
            const name = (item.getAttribute('data-name') || '').toLowerCase();
            const plate = (item.getAttribute('data-plate') || '').toLowerCase();
            const license = (item.getAttribute('data-license') || '').toLowerCase();
            if (!cleanTerm || name.includes(cleanTerm) || plate.includes(cleanTerm) || license.includes(cleanTerm)) {
                item.style.display = 'flex';
                matches++;
            } else {
                item.style.display = 'none';
            }
        });
        const countEl = document.getElementById('depositDriverMatchCount');
        if (countEl) countEl.textContent = `${matches} driver${matches === 1 ? '' : 's'}`;
        const noMatchEl = document.getElementById('depositDriverNoMatch');
        if (noMatchEl) noMatchEl.classList.toggle('hidden', matches > 0);
    }

    function selectDepositDriver(el) {
        const id = el.getAttribute('data-id');
        const name = el.getAttribute('data-name');
        const balance = parseFloat(el.getAttribute('data-balance') || 0);

        document.getElementById('depositDriverSelect').value = id;
        document.getElementById('depositDriverSearchInput').value = name;
        currentDepositDriverBal = balance;
        document.getElementById('depositCurrentBalDisplay').textContent = '₱' + balance.toLocaleString('en-PH', {minimumFractionDigits: 2});
        updateDepositProjectedBal();
        document.getElementById('depositDriverDropdown').classList.add('hidden');
    }

    function updateDepositProjectedBal() {
        const addAmt = parseFloat(document.getElementById('depositAmountInput').value || 0);
        const projected = currentDepositDriverBal + (addAmt > 0 ? addAmt : 0);
        document.getElementById('depositProjectedBalDisplay').textContent = '₱' + projected.toLocaleString('en-PH', {minimumFractionDigits: 2});
    }

    function closeLedgerDepositModal() {
        document.getElementById('ledgerDepositModal').classList.add('hidden');
        document.getElementById('depositDriverDropdown')?.classList.add('hidden');
    }

    function submitLedgerDeposit(e) {
        e.preventDefault();
        const driverId = document.getElementById('depositDriverSelect').value;
        const amount = parseFloat(document.getElementById('depositAmountInput').value || 0);
        const date = document.getElementById('depositDateInput').value;
        const description = document.getElementById('depositDescriptionInput').value;

        if (!driverId) {
            Swal.fire({ icon: 'warning', title: 'Driver Required', text: 'Please select a driver from the dropdown.' });
            return;
        }

        const todayStr = getLocalTodayStr();
        if (date > todayStr) {
            Swal.fire({ icon: 'warning', title: 'Invalid Date', text: 'Deposit date cannot be in the future.' });
            return;
        }

        if (amount <= 0) {
            Swal.fire({ icon: 'warning', title: 'Invalid Amount', text: 'Please enter a valid deposit amount greater than ₱0.00.' });
            return;
        }

        const btn = document.getElementById('btnSubmitLedgerDeposit');
        btn.disabled = true;
        btn.innerHTML = '<svg class="w-4 h-4 animate-spin inline mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-6.219-8.56"></path></svg> Processing...';

        fetch(`/driver-management/${driverId}/deposit-fund`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ amount, date, description })
        })
        .then(r => r.json())
        .then(res => {
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-4 h-4 inline mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> Record Deposit';

            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Deposit Recorded!',
                    text: res.message || 'Deposit successfully recorded into driver pondo ledger.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Transaction Failed',
                    text: res.message || 'Unable to record deposit.'
                });
            }
        })
        .catch(err => {
            console.error(err);
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-4 h-4 inline mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> Record Deposit';
            Swal.fire({ icon: 'error', title: 'Network Error', text: 'Something went wrong while connecting to the server.' });
        });
    }

    function openDriverDetailsModal(driverId) {
        if (typeof openDriverDetails === 'function') {
            openDriverDetails(driverId);
        }
    }

    // --- Custom Compact Calendar (Supports Single Date OR Date Range) ---
    const calMonths = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    const calMonthsShort = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

    function getLocalTodayStr() {
        const now = new Date();
        const y = now.getFullYear();
        const m = String(now.getMonth() + 1).padStart(2, '0');
        const d = String(now.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    }

    let calStartDate = "{{ request('date') ?: request('date_from') }}" || null;
    let calEndDate = "{{ request('date') ? '' : request('date_to') }}" || null;
    let isSelecting = false;
    let calLastHoverDate = null;

    const todayStrInit = getLocalTodayStr();
    if (calStartDate && calStartDate > todayStrInit) calStartDate = todayStrInit;
    if (calEndDate && calEndDate > todayStrInit) calEndDate = todayStrInit;

    // Initialize calendar view to start date or today (never into future)
    let initCalDate = calStartDate ? new Date(calStartDate + 'T00:00:00') : new Date();
    if (initCalDate > new Date()) initCalDate = new Date();
    let calViewYear = initCalDate.getFullYear();
    let calViewMonth = initCalDate.getMonth(); // 0 - 11

    function formatCalDisplay(dateStr) {
        if (!dateStr || typeof dateStr !== 'string') return '';
        const parts = dateStr.split('-');
        if (parts.length !== 3) return dateStr;
        const m = parseInt(parts[1], 10) - 1;
        const d = String(parseInt(parts[2], 10)).padStart(2, '0');
        const monthName = calMonthsShort[m] || parts[1];
        return `${monthName} ${d}, ${parts[0]}`;
    }

    function openCalendarPicker(event) {
        if (event) event.stopPropagation();
        const dropdown = document.getElementById('customCalendarDropdown');
        if (!dropdown) return;

        if (dropdown.classList.contains('hidden')) {
            dropdown.classList.remove('hidden');
            const now = new Date();
            if (calStartDate) {
                const d = new Date(calStartDate + 'T00:00:00');
                if (d > now) {
                    calViewYear = now.getFullYear();
                    calViewMonth = now.getMonth();
                } else {
                    calViewYear = d.getFullYear();
                    calViewMonth = d.getMonth();
                }
            } else {
                calViewYear = now.getFullYear();
                calViewMonth = now.getMonth();
            }
            buildCalendarGrid();
            updateCalendarStyles();
        } else {
            dropdown.classList.add('hidden');
        }
    }

    function closeCalendarPicker(event) {
        if (event) event.stopPropagation();
        const dropdown = document.getElementById('customCalendarDropdown');
        if (dropdown) dropdown.classList.add('hidden');
    }

    function calendarNavMonth(delta, event) {
        if (event) event.stopPropagation();
        const today = new Date();
        if (delta > 0) {
            // Strictly forbid navigating to future months
            if (calViewYear > today.getFullYear() || (calViewYear === today.getFullYear() && calViewMonth >= today.getMonth())) {
                return;
            }
        }
        calViewMonth += delta;
        if (calViewMonth < 0) {
            calViewMonth = 11;
            calViewYear--;
        } else if (calViewMonth > 11) {
            calViewMonth = 0;
            calViewYear++;
        }
        buildCalendarGrid();
        updateCalendarStyles();
    }

    function buildCalendarGrid() {
        const titleEl = document.getElementById('calMonthYearTitle');
        const gridEl = document.getElementById('calDaysGrid');
        if (!titleEl || !gridEl) return;

        const today = new Date();
        const todayStr = getLocalTodayStr();

        // Check if calendar view is at or beyond current month & year
        const isCurrentOrFutureMonth = (calViewYear > today.getFullYear()) || 
            (calViewYear === today.getFullYear() && calViewMonth >= today.getMonth());
        
        // Prevent next month button if at current or future month
        const nextBtn = document.getElementById('calNextMonthBtn');
        if (nextBtn) {
            if (isCurrentOrFutureMonth) {
                nextBtn.disabled = true;
                nextBtn.classList.add('opacity-25', 'cursor-not-allowed', 'pointer-events-none');
            } else {
                nextBtn.disabled = false;
                nextBtn.classList.remove('opacity-25', 'cursor-not-allowed', 'pointer-events-none');
            }
        }

        titleEl.textContent = `${calMonths[calViewMonth]} ${calViewYear}`;

        // Monday-based start index (0 = Mon, 6 = Sun)
        const firstDayOfMonth = new Date(calViewYear, calViewMonth, 1);
        const startDayIndex = (firstDayOfMonth.getDay() + 6) % 7;
        const totalDaysInMonth = new Date(calViewYear, calViewMonth + 1, 0).getDate();
        const prevMonthDays = new Date(calViewYear, calViewMonth, 0).getDate();

        let html = '';

        // Previous month filler days
        for (let i = startDayIndex - 1; i >= 0; i--) {
            const dNum = prevMonthDays - i;
            html += `<div class="h-8 flex items-center justify-center text-slate-300 font-medium text-xs cursor-default select-none pointer-events-none">${dNum}</div>`;
        }

        // Current month days
        for (let d = 1; d <= totalDaysInMonth; d++) {
            const dateStr = `${calViewYear}-${String(calViewMonth + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
            const isFuture = dateStr > todayStr;

            if (isFuture) {
                // Future / Advance date is strictly disabled & cannot be selected
                html += `
                    <div class="h-8 flex items-center justify-center relative cursor-not-allowed select-none pointer-events-none">
                        <div class="w-7 h-7 mx-auto rounded-xl flex items-center justify-center text-xs font-normal text-slate-300 select-none opacity-40">
                            ${d}
                        </div>
                    </div>
                `;
                continue;
            }

            // Selectable day cell
            html += `
                <div class="cal-day-cell h-8 flex items-center justify-center relative cursor-pointer select-none" 
                     data-date="${dateStr}"
                     onclick="handleCellClick('${dateStr}', event)"
                     onmouseenter="handleCellMouseEnter('${dateStr}')">
                    <div class="cal-range-bg absolute inset-y-0 inset-x-0 hidden pointer-events-none"></div>
                    <div class="cal-day-btn w-7 h-7 mx-auto rounded-xl flex items-center justify-center text-xs font-bold relative z-10 transition-colors pointer-events-none select-none">
                        ${d}
                    </div>
                </div>
            `;
        }

        // Next month trailing days to complete row grid
        const totalRendered = startDayIndex + totalDaysInMonth;
        const trailingDays = (7 - (totalRendered % 7)) % 7;
        for (let nextD = 1; nextD <= trailingDays; nextD++) {
            html += `<div class="h-8 flex items-center justify-center text-slate-300 font-medium text-xs cursor-default select-none pointer-events-none">${nextD}</div>`;
        }

        gridEl.innerHTML = html;
    }

    function handleCellClick(dateStr, event) {
        if (event) {
            event.stopPropagation();
            event.preventDefault();
        }
        const todayStr = getLocalTodayStr();
        if (dateStr > todayStr) return; // Strict validation: Advance date disallowed

        if (!isSelecting) {
            // First click: sets Start Date and begins range selection
            calStartDate = dateStr;
            calEndDate = null;
            calLastHoverDate = null;
            isSelecting = true;
        } else {
            // Second click:
            if (dateStr === calStartDate) {
                // Clicked same date: confirm as single date
                calEndDate = null;
                calLastHoverDate = null;
                isSelecting = false;
            } else if (dateStr < calStartDate) {
                // Clicked earlier date: reorder
                calEndDate = calStartDate;
                calStartDate = dateStr;
                calLastHoverDate = null;
                isSelecting = false;
            } else {
                // Clicked later date: confirm range
                calEndDate = dateStr;
                calLastHoverDate = null;
                isSelecting = false;
            }
        }

        updateCalendarStyles();
        updateDisplayPreview();
    }

    function handleCellMouseEnter(dateStr) {
        if (!isSelecting) return;
        const todayStr = getLocalTodayStr();
        if (dateStr > todayStr) return;
        calLastHoverDate = dateStr;
        updateCalendarStyles(dateStr);
    }

    function updateCalendarStyles(hoverDate = null) {
        const todayStr = getLocalTodayStr();
        let start = calStartDate;
        let end = calEndDate;

        if (isSelecting && start && hoverDate && hoverDate <= todayStr) {
            if (hoverDate < start) {
                start = hoverDate;
                end = calStartDate;
            } else if (hoverDate > start) {
                end = hoverDate;
            }
        }

        const cells = document.querySelectorAll('#calDaysGrid .cal-day-cell');
        cells.forEach(cell => {
            const dateStr = cell.getAttribute('data-date');
            if (!dateStr) return;

            const isToday = dateStr === todayStr;
            const isStart = start && dateStr === start;
            const isEnd = end && dateStr === end;
            const isBetween = start && end && dateStr > start && dateStr < end;

            const bg = cell.querySelector('.cal-range-bg');
            const btn = cell.querySelector('.cal-day-btn');
            if (!btn) return;

            // Range background styling
            if (bg) {
                if (isStart && end && start !== end) {
                    bg.className = 'cal-range-bg absolute inset-y-0 inset-x-0 pointer-events-none bg-gradient-to-r from-transparent 50% to-[#fef3c7] 50%';
                } else if (isEnd && start && start !== end) {
                    bg.className = 'cal-range-bg absolute inset-y-0 inset-x-0 pointer-events-none bg-gradient-to-l from-transparent 50% to-[#fef3c7] 50%';
                } else if (isBetween) {
                    bg.className = 'cal-range-bg absolute inset-y-0 inset-x-0 pointer-events-none bg-[#fef3c7]';
                } else {
                    bg.className = 'cal-range-bg absolute inset-y-0 inset-x-0 pointer-events-none hidden';
                }
            }

            // Button styling
            let btnClasses = 'cal-day-btn w-7 h-7 mx-auto rounded-xl flex items-center justify-center text-xs font-bold relative z-10 transition-colors pointer-events-none select-none ';

            if (isStart && isEnd) {
                btnClasses += 'bg-amber-500 text-white shadow-xs';
            } else if (isStart || isEnd) {
                btnClasses += 'bg-amber-500 text-white shadow-xs';
            } else if (isBetween) {
                btnClasses += 'text-[#92400e] font-bold';
            } else if (isToday) {
                btnClasses += 'border-2 border-amber-500 text-amber-600 hover:bg-amber-50';
            } else {
                btnClasses += 'text-slate-700 hover:bg-amber-50 hover:text-amber-700';
            }

            btn.className = btnClasses;
        });
    }

    function updateDisplayPreview() {
        const display = document.getElementById('display_selected_date');
        if (!display) return;
        if (calStartDate && calEndDate && calStartDate !== calEndDate) {
            display.textContent = `${formatCalDisplay(calStartDate)} — ${formatCalDisplay(calEndDate)}`;
            display.className = 'truncate text-slate-900 font-bold';
        } else if (calStartDate) {
            display.textContent = formatCalDisplay(calStartDate);
            display.className = 'truncate text-slate-900 font-bold';
        } else {
            display.textContent = 'Select Date or Range';
            display.className = 'truncate text-slate-400 font-normal';
        }
    }

    function applyDateSelection(event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }

        const todayStr = getLocalTodayStr();

        // If user hovered over an end date and clicked Apply without a 2nd cell click:
        if (isSelecting && calStartDate) {
            if (calLastHoverDate && calLastHoverDate !== calStartDate && calLastHoverDate <= todayStr) {
                if (calLastHoverDate < calStartDate) {
                    calEndDate = calStartDate;
                    calStartDate = calLastHoverDate;
                } else {
                    calEndDate = calLastHoverDate;
                }
            } else {
                calEndDate = null;
            }
            isSelecting = false;
        }

        if (calStartDate && calStartDate > todayStr) calStartDate = todayStr;
        if (calEndDate && calEndDate > todayStr) calEndDate = todayStr;
        if (calStartDate && calEndDate && calStartDate > calEndDate) {
            const tmp = calStartDate;
            calStartDate = calEndDate;
            calEndDate = tmp;
        }

        const inputDate = document.getElementById('filter_date');
        const inputFrom = document.getElementById('filter_date_from');
        const inputTo = document.getElementById('filter_date_to');

        let previewLabel = 'All Dates';

        if (calStartDate && calEndDate && calStartDate !== calEndDate) {
            // Date Range
            if (inputDate) inputDate.value = '';
            if (inputFrom) inputFrom.value = calStartDate;
            if (inputTo) inputTo.value = calEndDate;
            previewLabel = `${formatCalDisplay(calStartDate)} — ${formatCalDisplay(calEndDate)}`;
        } else if (calStartDate) {
            // Single Date (None Date Range)
            if (inputDate) inputDate.value = calStartDate;
            if (inputFrom) inputFrom.value = '';
            if (inputTo) inputTo.value = '';
            previewLabel = formatCalDisplay(calStartDate);
        } else {
            // Cleared
            if (inputDate) inputDate.value = '';
            if (inputFrom) inputFrom.value = '';
            if (inputTo) inputTo.value = '';
            previewLabel = 'All Dates';
        }

        // 1. Immediate visual feedback on Apply Button: Spinner + "Applying..." + Disabled
        const btnApply = document.getElementById('btnApplyCalendar');
        if (btnApply) {
            btnApply.disabled = true;
            btnApply.classList.add('opacity-80', 'cursor-wait');
            btnApply.innerHTML = `<span class="inline-flex items-center gap-1.5"><svg class="animate-spin h-3.5 w-3.5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Applying...</span>`;
        }

        // 2. Immediate visual feedback on Trigger Button Box
        const display = document.getElementById('display_selected_date');
        if (display) {
            display.innerHTML = `<span class="inline-flex items-center gap-1.5 text-amber-700 font-bold"><svg class="animate-spin h-3.5 w-3.5 text-amber-600 inline shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Filtering: ${previewLabel}</span>`;
            display.className = 'truncate text-amber-700 font-bold';
        }

        // 3. Smooth table opacity dimming to visually confirm filtering is in progress
        const tableWrap = document.getElementById('fundsTransactionsTableWrap');
        if (tableWrap) {
            tableWrap.style.opacity = '0.35';
            tableWrap.style.pointerEvents = 'none';
        }

        // 4. Close popup dropdown immediately
        const dropdown = document.getElementById('customCalendarDropdown');
        if (dropdown) dropdown.classList.add('hidden');

        // 5. Guaranteed Direct URL Navigation (Unblockable by form submit listeners or quirks)
        try {
            const targetUrl = new URL(window.location.origin + window.location.pathname);

            const searchInput = document.getElementById('ledgerSearchInput');
            if (searchInput && searchInput.value.trim()) {
                targetUrl.searchParams.set('search', searchInput.value.trim());
            }

            const driverIdInput = document.getElementById('ledgerFilterDriverId');
            if (driverIdInput && driverIdInput.value) {
                targetUrl.searchParams.set('driver_id', driverIdInput.value);
            } else {
                targetUrl.searchParams.delete('driver_id');
            }

            const typeSelect = document.querySelector('#fundsLedgerFilterForm select[name="type"]');
            if (typeSelect && typeSelect.value && typeSelect.value !== 'all') {
                targetUrl.searchParams.set('type', typeSelect.value);
            }

            if (calStartDate && calEndDate && calStartDate !== calEndDate) {
                targetUrl.searchParams.set('date_from', calStartDate);
                targetUrl.searchParams.set('date_to', calEndDate);
                targetUrl.searchParams.delete('date');
            } else if (calStartDate) {
                targetUrl.searchParams.set('date', calStartDate);
                targetUrl.searchParams.delete('date_from');
                targetUrl.searchParams.delete('date_to');
            } else {
                targetUrl.searchParams.delete('date');
                targetUrl.searchParams.delete('date_from');
                targetUrl.searchParams.delete('date_to');
            }

            targetUrl.searchParams.delete('page');

            window.location.href = targetUrl.toString();
        } catch (e) {
            // Fallback to standard form submit
            const form = document.getElementById('fundsLedgerFilterForm') || document.getElementById('datePickerContainer')?.closest('form');
            if (form) {
                if (typeof form.requestSubmit === 'function') {
                    form.requestSubmit();
                } else {
                    form.submit();
                }
            }
        }
    }

    function setCalPreset(preset, event) {
        if (event) {
            event.stopPropagation();
            event.preventDefault();
        }
        const todayStr = getLocalTodayStr();
        const now = new Date();
        isSelecting = false;
        calLastHoverDate = null;

        if (preset === 'today') {
            calStartDate = todayStr;
            calEndDate = null;
            calViewYear = now.getFullYear();
            calViewMonth = now.getMonth();
        } else if (preset === 'month') {
            calStartDate = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-01`;
            calEndDate = todayStr;
            calViewYear = now.getFullYear();
            calViewMonth = now.getMonth();
        }

        buildCalendarGrid();
        updateCalendarStyles();
        updateDisplayPreview();
    }

    function clearSelectedDate(event, autoSubmit = false) {
        if (event) {
            event.stopPropagation();
            event.preventDefault();
        }
        calStartDate = null;
        calEndDate = null;
        calLastHoverDate = null;
        isSelecting = false;

        const inputDate = document.getElementById('filter_date');
        const inputFrom = document.getElementById('filter_date_from');
        const inputTo = document.getElementById('filter_date_to');
        if (inputDate) inputDate.value = '';
        if (inputFrom) inputFrom.value = '';
        if (inputTo) inputTo.value = '';

        buildCalendarGrid();
        updateCalendarStyles();
        updateDisplayPreview();

        if (autoSubmit) {
            applyDateSelection(event);
        }
    }

    // Close calendar on outside click
    document.addEventListener('click', function(e) {
        const container = document.getElementById('datePickerContainer');
        const dropdown = document.getElementById('customCalendarDropdown');
        if (dropdown && !dropdown.classList.contains('hidden')) {
            if (container && container.contains(e.target)) return;
            if (isSelecting) {
                calStartDate = "{{ request('date') ?: request('date_from') }}" || null;
                calEndDate = "{{ request('date') ? '' : request('date_to') }}" || null;
                calLastHoverDate = null;
                isSelecting = false;
                updateCalendarStyles();
                updateDisplayPreview();
            }
            dropdown.classList.add('hidden');
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const form = document.getElementById('fundsLedgerFilterForm');
        if (form) {
            form.addEventListener('submit', function() {
                const tableWrap = document.getElementById('fundsTransactionsTableWrap');
                if (tableWrap) {
                    tableWrap.style.opacity = '0.35';
                    tableWrap.style.pointerEvents = 'none';
                }
            });
        }

        // Searchable Driver Suggestions for Deposit Modal
        const depInput = document.getElementById('depositDriverSearchInput');
        const depDd = document.getElementById('depositDriverDropdown');
        if (depInput && depDd) {
            depInput.addEventListener('focus', function() {
                filterDepositDrivers(this.value);
                depDd.classList.remove('hidden');
            });
            depInput.addEventListener('click', function() {
                filterDepositDrivers(this.value);
                depDd.classList.remove('hidden');
            });
            depInput.addEventListener('input', function() {
                document.getElementById('depositDriverSelect').value = '';
                if (!this.value.trim()) {
                    currentDepositDriverBal = 0;
                    document.getElementById('depositCurrentBalDisplay').textContent = '₱0.00';
                    updateDepositProjectedBal();
                }
                filterDepositDrivers(this.value);
                depDd.classList.remove('hidden');
            });
            depInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    depDd.classList.add('hidden');
                } else if (e.key === 'Enter') {
                    if (!depDd.classList.contains('hidden')) {
                        const firstMatch = Array.from(document.querySelectorAll('.deposit-driver-item')).find(item => item.style.display !== 'none');
                        if (firstMatch) {
                            e.preventDefault();
                            selectDepositDriver(firstMatch);
                        }
                    }
                }
            });
        }

        // Searchable Driver Suggestions for Disburse Modal
        const disbInput = document.getElementById('disburseDriverSearchInput');
        const disbDd = document.getElementById('disburseDriverDropdown');
        if (disbInput && disbDd) {
            disbInput.addEventListener('focus', function() {
                filterDisburseDrivers(this.value);
                disbDd.classList.remove('hidden');
            });
            disbInput.addEventListener('click', function() {
                filterDisburseDrivers(this.value);
                disbDd.classList.remove('hidden');
            });
            disbInput.addEventListener('input', function() {
                document.getElementById('disburseDriverSelect').value = '';
                if (!this.value.trim()) {
                    currentSelectedDriverBal = 0;
                    document.getElementById('disburseAvailableDisplay').textContent = '₱0.00';
                    document.getElementById('disburseAmountInput').max = 0;
                }
                filterDisburseDrivers(this.value);
                disbDd.classList.remove('hidden');
            });
            disbInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    disbDd.classList.add('hidden');
                } else if (e.key === 'Enter') {
                    if (!disbDd.classList.contains('hidden')) {
                        const firstMatch = Array.from(document.querySelectorAll('.disburse-driver-item')).find(item => item.style.display !== 'none');
                        if (firstMatch) {
                            e.preventDefault();
                            selectDisburseDriver(firstMatch);
                        }
                    }
                }
            });
        }

        // Searchable Driver Suggestions for Ledger Main Filter Bar
        const ledgerSearchInput = document.getElementById('ledgerSearchInput');
        const ledgerDriverDropdown = document.getElementById('ledgerDriverSearchDropdown');
        if (ledgerSearchInput && ledgerDriverDropdown) {
            ledgerSearchInput.addEventListener('focus', function() {
                filterLedgerDrivers(this.value);
                ledgerDriverDropdown.classList.remove('hidden');
            });
            ledgerSearchInput.addEventListener('click', function() {
                filterLedgerDrivers(this.value);
                ledgerDriverDropdown.classList.remove('hidden');
            });
            ledgerSearchInput.addEventListener('input', function() {
                const dIdInput = document.getElementById('ledgerFilterDriverId');
                if (dIdInput) dIdInput.value = '';
                filterLedgerDrivers(this.value);
                ledgerDriverDropdown.classList.remove('hidden');
            });
            ledgerSearchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    ledgerDriverDropdown.classList.add('hidden');
                } else if (e.key === 'Enter') {
                    if (!ledgerDriverDropdown.classList.contains('hidden')) {
                        const visibleItems = Array.from(document.querySelectorAll('.ledger-filter-driver-item')).filter(item => item.style.display !== 'none');
                        if (visibleItems.length === 1) {
                            e.preventDefault();
                            selectLedgerFilterDriver(visibleItems[0]);
                        }
                    }
                }
            });
        }

        // Close driver suggestions on outside click
        document.addEventListener('click', function(e) {
            const searchContainer = document.getElementById('ledgerSearchContainer');
            if (searchContainer && !searchContainer.contains(e.target)) {
                document.getElementById('ledgerDriverSearchDropdown')?.classList.add('hidden');
            }
            const depContainer = document.getElementById('depositDriverSearchContainer');
            if (depContainer && !depContainer.contains(e.target)) {
                document.getElementById('depositDriverDropdown')?.classList.add('hidden');
            }
            const disbContainer = document.getElementById('disburseDriverSearchContainer');
            if (disbContainer && !disbContainer.contains(e.target)) {
                document.getElementById('disburseDriverDropdown')?.classList.add('hidden');
            }

            const editModal = document.getElementById('ledgerEditModal');
            if (editModal && e.target === editModal) {
                closeLedgerEditModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const editModal = document.getElementById('ledgerEditModal');
                if (editModal && !editModal.classList.contains('hidden')) {
                    closeLedgerEditModal();
                }
            }
        });
    });
</script>
@endsection
