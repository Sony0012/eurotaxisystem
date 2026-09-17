@extends('layouts.app')

@section('title', 'Driver Savings & Pondo Ledger — Euro Taxi Fleet')
@section('page-heading', 'Driver Savings & Pondo Ledger')
@section('page-subheading', 'Comprehensive audit trail of driver emergency funds, accident damage deductions, maintenance co-payments, and savings withdrawals.')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="space-y-6">
    <!-- Top Action Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white shadow-md shadow-emerald-500/20">
                <i data-lucide="piggy-bank" class="w-6 h-6"></i>
            </div>
            <div>
                <h1 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">Driver Savings & Pondo Ledger</h1>
                <p class="text-xs text-slate-500 font-medium">Tracking {{ $stats['funded_drivers'] ?? 0 }} drivers with active funds & reserves</p>
            </div>
        </div>
        <div class="flex items-center gap-2.5 flex-wrap">
            <a href="{{ route('driver-management.funds-ledger.print', request()->all()) }}" target="_blank" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow-2xs">
                <i data-lucide="printer" class="w-4 h-4"></i> Print Statement
            </a>
            <button type="button" onclick="openLedgerDepositModal()" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all flex items-center gap-1.5 shadow-md shadow-emerald-500/20 cursor-pointer">
                <i data-lucide="plus-circle" class="w-4 h-4"></i> Add Deposit
            </button>
            <button type="button" onclick="openLedgerDisburseModal()" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all flex items-center gap-1.5 shadow-md shadow-rose-500/20 cursor-pointer">
                <i data-lucide="minus-circle" class="w-4 h-4"></i> Deduct / Payout
            </button>
        </div>
    </div>

    <!-- KPI Summary Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Card 1: Available Vault Balance -->
        <div class="relative overflow-hidden rounded-2xl border border-emerald-200/90 bg-gradient-to-br from-emerald-500/10 via-teal-50/40 to-white p-5 shadow-xs">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-emerald-800 block mb-1">Total Available Pondo</span>
                    <div class="text-2xl font-black text-emerald-700 leading-none">
                        ₱{{ number_format($stats['total_available'], 2) }}
                    </div>
                    <span class="text-[10px] font-bold text-emerald-600 mt-2 block">In Driver Savings Vault</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center shadow-sm">
                    <i data-lucide="wallet" class="w-5 h-5"></i>
                </div>
            </div>
        </div>

        <!-- Card 2: Lifetime Shift Deposits -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-xs">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block mb-1">Lifetime Deposited</span>
                    <div class="text-xl font-black text-slate-900 leading-none">
                        ₱{{ number_format($stats['total_deposited'], 2) }}
                    </div>
                    <span class="text-[10px] font-bold text-slate-500 mt-2 block">From shift remittances</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100">
                    <i data-lucide="arrow-down-left" class="w-5 h-5"></i>
                </div>
            </div>
        </div>

        <!-- Card 3: Accident & Damage Deductions -->
        <div class="relative overflow-hidden rounded-2xl border border-rose-200 bg-rose-50/30 p-5 shadow-xs">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-rose-700 block mb-1">Accident & Damages</span>
                    <div class="text-xl font-black text-rose-600 leading-none">
                        ₱{{ number_format($stats['total_damages'], 2) }}
                    </div>
                    <span class="text-[10px] font-bold text-rose-500 mt-2 block">Deducted for unit repairs</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-rose-500 text-white flex items-center justify-center shadow-sm">
                    <i data-lucide="shield-alert" class="w-5 h-5"></i>
                </div>
            </div>
        </div>

        <!-- Card 4: Maintenance Co-Payments -->
        <div class="relative overflow-hidden rounded-2xl border border-amber-200 bg-amber-50/30 p-5 shadow-xs">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-amber-800 block mb-1">Maintenance Share</span>
                    <div class="text-xl font-black text-amber-700 leading-none">
                        ₱{{ number_format($stats['total_maintenance'], 2) }}
                    </div>
                    <span class="text-[10px] font-bold text-amber-600 mt-2 block">Contract maintenance split</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shadow-sm">
                    <i data-lucide="wrench" class="w-5 h-5"></i>
                </div>
            </div>
        </div>

        <!-- Card 5: Cashout Withdrawals -->
        <div class="relative overflow-hidden rounded-2xl border border-indigo-200 bg-indigo-50/20 p-5 shadow-xs">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-indigo-700 block mb-1">Driver Cashouts</span>
                    <div class="text-xl font-black text-indigo-600 leading-none">
                        ₱{{ number_format($stats['total_cashouts'], 2) }}
                    </div>
                    <span class="text-[10px] font-bold text-indigo-500 mt-2 block">Personal savings payouts</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-indigo-500 text-white flex items-center justify-center shadow-sm">
                    <i data-lucide="arrow-up-right" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation Tabs -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="border-b border-slate-200 bg-slate-50/50 px-6 pt-3 flex items-center justify-between flex-wrap gap-3 rounded-t-2xl">
            <div class="flex space-x-2">
                <button type="button" onclick="switchLedgerView('transactions')" id="tabBtnTransactions" class="px-4 py-3 border-b-2 font-black text-xs uppercase tracking-wider transition-all flex items-center gap-2 border-emerald-600 text-emerald-700">
                    <i data-lucide="list-ordered" class="w-4 h-4"></i> All Ledger Transactions
                    <span class="ml-1 px-2 py-0.5 rounded-full text-[10px] font-mono bg-emerald-100 text-emerald-800">{{ $transactions->total() }}</span>
                </button>
                <button type="button" onclick="switchLedgerView('directory')" id="tabBtnDirectory" class="px-4 py-3 border-b-2 font-black text-xs uppercase tracking-wider transition-all flex items-center gap-2 border-transparent text-slate-500 hover:text-slate-800">
                    <i data-lucide="users" class="w-4 h-4"></i> Driver Balances Directory
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
                                <i data-lucide="filter" class="w-3 h-3"></i> filtered
                            </span>
                        @endif
                    </label>
                    <div class="relative" id="ledgerSearchContainer">
                        <div class="relative">
                            <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                            <input type="text" name="search" id="ledgerSearchInput" value="{{ $currentSearchValue }}" 
                                   placeholder="Type driver name, taxi plate, or notes to search..." 
                                   autocomplete="new-password" spellcheck="false" autocorrect="off" autocapitalize="off" data-lpignore="true" data-form-type="other"
                                   class="w-full pl-9 pr-16 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 shadow-2xs">
                            
                            <div class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center gap-0.5">
                                @if($currentSearchValue || request('driver_id'))
                                    <button type="button" onclick="clearLedgerSearch(event)" class="text-slate-400 hover:text-rose-500 p-1 cursor-pointer transition-colors" title="Clear Search & Show All Drivers">
                                        <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                    </button>
                                @endif
                                <button type="button" onclick="toggleLedgerDriverDropdown(event)" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer" title="Browse Drivers">
                                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
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
                                    <i data-lucide="calendar" class="w-4 h-4 text-amber-500 shrink-0 group-hover:scale-110 transition-transform"></i>
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
                                            <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                        </span>
                                    @else
                                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-400"></i>
                                    @endif
                                </div>
                            </button>
                            <input type="hidden" name="date" id="filter_date" value="{{ request('date') }}">
                            <input type="hidden" name="date_from" id="filter_date_from" value="{{ request('date_from') }}">
                            <input type="hidden" name="date_to" id="filter_date_to" value="{{ request('date_to') }}">
                        </div>

                        <!-- Filter Submit Button -->
                        <button type="submit" class="px-3.5 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition-all flex items-center justify-center shrink-0 shadow-2xs cursor-pointer" title="Apply Filter">
                            <i data-lucide="filter" class="w-4 h-4"></i>
                        </button>

                        <!-- Reset Filter Button -->
                        @if(request()->anyFilled(['search', 'driver_id', 'type', 'date', 'date_from', 'date_to']))
                            <a href="{{ route('driver-management.funds-ledger') }}" class="px-3 py-2 bg-slate-200 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-300 transition-all flex items-center justify-center shrink-0 shadow-2xs cursor-pointer" title="Reset Filters">
                                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                            </a>
                        @endif
                    </div>

                    <!-- Custom Compact Calendar Popup (Styled exactly as requested image) -->
                    <div id="customCalendarDropdown" class="hidden absolute right-0 top-full mt-2 w-[295px] bg-white rounded-2xl shadow-2xl border border-slate-200/90 p-3.5 z-[100] select-none">
                        <!-- Calendar Header: Navigation & Month/Year -->
                        <div class="flex items-center justify-between mb-3 px-1">
                            <button type="button" onclick="calendarNavMonth(-1, event)" class="w-7 h-7 flex items-center justify-center text-slate-400 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-colors cursor-pointer" title="Previous Month">
                                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                            </button>
                            <span class="font-bold text-slate-800 text-sm tracking-tight text-center" id="calMonthYearTitle"></span>
                            <button type="button" id="calNextMonthBtn" onclick="calendarNavMonth(1, event)" class="w-7 h-7 flex items-center justify-center text-slate-400 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-colors cursor-pointer" title="Next Month">
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
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
                            <i data-lucide="filter" class="w-3 h-3"></i> Active Filter
                        </span>
                        
                        @if($currentSearchValue || request('driver_id'))
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-white border border-amber-300 rounded-md text-slate-800 font-bold text-xs">
                                <i data-lucide="user" class="w-3 h-3 text-emerald-600"></i>
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
                                <i data-lucide="calendar" class="w-3 h-3 text-amber-600"></i>
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
                                <i data-lucide="alert-circle" class="w-3 h-3"></i> No Records Found (0 records)
                            </span>
                        @else
                            <span class="text-slate-500 font-normal">({{ $transactions->total() }} record{{ $transactions->total() === 1 ? '' : 's' }} found)</span>
                        @endif
                    </div>
                    <a href="{{ route('driver-management.funds-ledger') }}" class="text-amber-800 hover:text-amber-950 font-bold flex items-center gap-1 hover:underline text-xs shrink-0 ml-2">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Clear All Filters
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
                                        <i data-lucide="arrow-down-left" class="w-3 h-3 text-emerald-600"></i> {{ $t->boundary_id ? 'Shift Deposit' : 'Manual Deposit' }}
                                    </span>
                                @elseif($t->type === 'damage_deduction')
                                    <span class="px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded-lg text-[10px] font-black uppercase tracking-wider inline-flex items-center gap-1">
                                        <i data-lucide="shield-alert" class="w-3 h-3 text-rose-600"></i> Accident / Damage
                                    </span>
                                @elseif($t->type === 'maintenance_share')
                                    <span class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg text-[10px] font-black uppercase tracking-wider inline-flex items-center gap-1">
                                        <i data-lucide="wrench" class="w-3 h-3 text-amber-600"></i> Maintenance Share
                                    </span>
                                @elseif($t->type === 'company_liability')
                                    <span class="px-2.5 py-1 bg-purple-50 text-purple-700 border border-purple-200 rounded-lg text-[10px] font-black uppercase tracking-wider inline-flex items-center gap-1">
                                        <i data-lucide="receipt" class="w-3 h-3 text-purple-600"></i> Debt / Liability
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-[10px] font-black uppercase tracking-wider inline-flex items-center gap-1">
                                        <i data-lucide="arrow-up-right" class="w-3 h-3 text-blue-600"></i> Driver Cashout
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
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center text-slate-500">
                                <div class="w-14 h-14 rounded-2xl bg-amber-50 border border-amber-200 text-amber-500 flex items-center justify-center mx-auto mb-3 shadow-xs">
                                    <i data-lucide="calendar-x" class="w-7 h-7"></i>
                                </div>
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 border border-rose-200 text-rose-700 text-xs font-black uppercase tracking-wider mb-2">
                                    <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> No Records Found
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
                                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Clear Filter & View All Records
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
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
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
                                        <i data-lucide="plus" class="w-3.5 h-3.5"></i> Deposit
                                    </button>
                                    <button type="button" onclick="openLedgerDisburseModal({{ $d->id }}, '{{ addslashes($d->full_name) }}', {{ $d->current_balance }})" class="px-2.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-[11px] font-black uppercase tracking-wider transition-all flex items-center gap-1 shadow-2xs cursor-pointer" {{ $d->current_balance <= 0 ? 'disabled style=opacity:0.5;cursor:not-allowed;' : '' }}>
                                        <i data-lucide="minus" class="w-3.5 h-3.5"></i> Deduct / Payout
                                    </button>
                                    <a href="{{ route('driver-management.funds-ledger', ['driver_id' => $d->id]) }}" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[11px] font-bold uppercase tracking-wider transition-all flex items-center gap-1 cursor-pointer" title="View driver ledger">
                                        <i data-lucide="file-text" class="w-3.5 h-3.5"></i> Statement
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
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-emerald-500/20 border border-emerald-400/30 flex items-center justify-center text-emerald-400">
                    <i data-lucide="piggy-bank" class="w-4 h-4"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider">Disburse / Deduct Fund</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Driver Savings & Maintenance Reserve</p>
                </div>
            </div>
            <button type="button" onclick="closeLedgerDisburseModal()" class="text-slate-400 hover:text-white transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
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
                        <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                        <button type="button" onclick="toggleDisburseDriverDropdown(event)" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
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
                    <i data-lucide="check-circle" class="w-4 h-4"></i> Confirm Deduction / Payout
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Manual Pondo Deposit Modal -->
<div id="ledgerDepositModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200">
        <div class="bg-slate-900 p-5 text-white flex justify-between items-center">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-emerald-500/20 border border-emerald-400/30 flex items-center justify-center text-emerald-400">
                    <i data-lucide="arrow-down-left" class="w-4 h-4"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider">Manual Pondo Deposit</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Driver Savings & Emergency Reserve</p>
                </div>
            </div>
            <button type="button" onclick="closeLedgerDepositModal()" class="text-slate-400 hover:text-white transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
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
                        <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                        <button type="button" onclick="toggleDepositDriverDropdown(event)" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
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
                    <i data-lucide="check-circle" class="w-4 h-4"></i> Record Deposit
                </button>
            </div>
        </form>
    </div>
</div>

@include('driver-management.partials._driver_details_modal')

<script>
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
        btn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Processing...';
        if (typeof lucide !== 'undefined') lucide.createIcons();

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
            btn.innerHTML = '<i data-lucide="check-circle" class="w-4 h-4"></i> Confirm Transaction';
            if (typeof lucide !== 'undefined') lucide.createIcons();

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
            btn.innerHTML = '<i data-lucide="check-circle" class="w-4 h-4"></i> Confirm Transaction';
            if (typeof lucide !== 'undefined') lucide.createIcons();
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
        btn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Processing...';
        if (typeof lucide !== 'undefined') lucide.createIcons();

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
            btn.innerHTML = '<i data-lucide="check-circle" class="w-4 h-4"></i> Record Deposit';
            if (typeof lucide !== 'undefined') lucide.createIcons();

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
            btn.innerHTML = '<i data-lucide="check-circle" class="w-4 h-4"></i> Record Deposit';
            if (typeof lucide !== 'undefined') lucide.createIcons();
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
        });
    });
</script>
@endsection
