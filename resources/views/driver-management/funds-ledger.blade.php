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
            <button type="button" onclick="openLedgerDisburseModal()" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all flex items-center gap-1.5 shadow-md shadow-emerald-500/20 cursor-pointer">
                <i data-lucide="plus-circle" class="w-4 h-4"></i> New Deduction / Payout
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

        <!-- Card 3: Accident & Bangga Deductions -->
        <div class="relative overflow-hidden rounded-2xl border border-rose-200 bg-rose-50/30 p-5 shadow-xs">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-rose-700 block mb-1">Bangga & Damages</span>
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
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="border-b border-slate-200 bg-slate-50/50 px-6 pt-3 flex items-center justify-between flex-wrap gap-3">
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
            <!-- Filter Bar Form -->
            <form method="GET" action="{{ route('driver-management.funds-ledger') }}" class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-12 gap-3 bg-slate-50 p-4 rounded-xl border border-slate-200/80">
                <!-- Search -->
                <div class="lg:col-span-4">
                    <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1">Search Keywords</label>
                    <div class="relative">
                        <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="search" name="search" id="ledgerSearchInput" value="{{ request('search') }}" 
                               placeholder="Driver name, plate, or note..." 
                               autocomplete="new-password" spellcheck="false" autocorrect="off" autocapitalize="off" data-lpignore="true" data-form-type="other"
                               readonly onfocus="this.removeAttribute('readonly');"
                               class="w-full pl-9 pr-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                </div>

                <!-- Driver Filter -->
                <div class="lg:col-span-3">
                    <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1">Filter by Driver</label>
                    <select name="driver_id" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">All Drivers</option>
                        @foreach($drivers as $d)
                            <option value="{{ $d->id }}" {{ request('driver_id') == $d->id ? 'selected' : '' }}>
                                {{ $d->full_name }} ({{ $d->assigned_plate ?: 'Unassigned' }}) — ₱{{ number_format($d->current_balance, 2) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Transaction Type Filter -->
                <div class="lg:col-span-2">
                    <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1">Transaction Type</label>
                    <select name="type" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="all" {{ request('type') == 'all' || !request('type') ? 'selected' : '' }}>All Types</option>
                        <option value="deposit" {{ request('type') == 'deposit' ? 'selected' : '' }}>Shift Pondo Deposit</option>
                        <option value="damage_deduction" {{ request('type') == 'damage_deduction' ? 'selected' : '' }}>Bangga / Damage Deduction</option>
                        <option value="maintenance_share" {{ request('type') == 'maintenance_share' ? 'selected' : '' }}>Maintenance Share</option>
                        <option value="company_liability" {{ request('type') == 'company_liability' ? 'selected' : '' }}>Debt Settlement</option>
                        <option value="withdrawal" {{ request('type') == 'withdrawal' ? 'selected' : '' }}>Driver Cashout</option>
                    </select>
                </div>

                <!-- Date Range (From & To with Custom Compact Calendar matching Image 2) -->
                <div class="lg:col-span-3 relative" id="datePickerContainer">
                    <div class="flex items-end gap-1.5">
                        <div class="flex-1 min-w-0">
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1 truncate">Date From</label>
                            <button type="button" onclick="openCalendarPicker('from', event)" id="btnDateFrom" class="w-full px-2 py-2 bg-white border border-slate-200 hover:border-amber-400 focus:border-amber-500 rounded-xl text-xs font-bold text-slate-700 flex items-center justify-between transition-all shadow-2xs text-left group">
                                <span id="display_date_from" class="truncate {{ request('date_from') ? 'text-slate-900' : 'text-slate-400 font-normal' }}">
                                    {{ request('date_from') ? \Carbon\Carbon::parse(request('date_from'))->format('M d, Y') : 'Start Date' }}
                                </span>
                                <i data-lucide="calendar" class="w-3.5 h-3.5 text-amber-500 shrink-0 ml-1 group-hover:scale-110 transition-transform"></i>
                            </button>
                        </div>
                        <div class="flex-1 min-w-0">
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1 truncate">Date To</label>
                            <button type="button" onclick="openCalendarPicker('to', event)" id="btnDateTo" class="w-full px-2 py-2 bg-white border border-slate-200 hover:border-amber-400 focus:border-amber-500 rounded-xl text-xs font-bold text-slate-700 flex items-center justify-between transition-all shadow-2xs text-left group">
                                <span id="display_date_to" class="truncate {{ request('date_to') ? 'text-slate-900' : 'text-slate-400 font-normal' }}">
                                    {{ request('date_to') ? \Carbon\Carbon::parse(request('date_to'))->format('M d, Y') : 'End Date' }}
                                </span>
                                <i data-lucide="calendar" class="w-3.5 h-3.5 text-amber-500 shrink-0 ml-1 group-hover:scale-110 transition-transform"></i>
                            </button>
                        </div>

                        <!-- Real hidden inputs for the GET request form -->
                        <input type="hidden" name="date_from" id="filter_date_from" value="{{ request('date_from') }}">
                        <input type="hidden" name="date_to" id="filter_date_to" value="{{ request('date_to') }}">

                        <!-- Filter Submit Button -->
                        <button type="submit" class="px-3.5 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition-all flex items-center justify-center shrink-0 shadow-2xs" title="Apply Filter">
                            <i data-lucide="filter" class="w-4 h-4"></i>
                        </button>

                        <!-- Reset Filter Button -->
                        @if(request()->anyFilled(['search', 'driver_id', 'type', 'date_from', 'date_to']))
                            <a href="{{ route('driver-management.funds-ledger') }}" class="px-3 py-2 bg-slate-200 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-300 transition-all flex items-center justify-center shrink-0 shadow-2xs" title="Reset Filters">
                                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                            </a>
                        @endif
                    </div>

                    <!-- Custom Compact Calendar Popup (Styled exactly as requested image) -->
                    <div id="customCalendarDropdown" class="hidden absolute right-0 top-full mt-2 w-[285px] bg-white rounded-2xl shadow-2xl border border-slate-200/90 p-3.5 z-50 select-none">
                        <!-- Calendar Header: Navigation & Month/Year -->
                        <div class="flex items-center justify-between mb-3 px-1">
                            <button type="button" onclick="calendarNavMonth(-1, event)" class="w-7 h-7 flex items-center justify-center text-slate-400 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-colors cursor-pointer" title="Previous Month">
                                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                            </button>
                            <span class="font-bold text-slate-800 text-sm tracking-tight text-center" id="calMonthYearTitle"></span>
                            <button type="button" onclick="calendarNavMonth(1, event)" class="w-7 h-7 flex items-center justify-center text-slate-400 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-colors cursor-pointer" title="Next Month">
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
                        <div id="calDaysGrid" class="grid grid-cols-7 gap-y-1 text-center text-xs">
                            <!-- Populated dynamically -->
                        </div>

                        <!-- Quick Presets & Apply Footer -->
                        <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                            <div class="flex items-center gap-1">
                                <button type="button" onclick="setCalPreset('today', event)" class="px-2 py-1 text-slate-600 hover:text-amber-600 font-bold rounded-md hover:bg-amber-50 transition-colors cursor-pointer">Today</button>
                                <button type="button" onclick="setCalPreset('this_month', event)" class="px-2 py-1 text-slate-600 hover:text-amber-600 font-bold rounded-md hover:bg-amber-50 transition-colors cursor-pointer">Month</button>
                                <button type="button" onclick="resetCalSelection(event)" class="px-2 py-1 text-rose-500 hover:text-rose-700 font-bold rounded-md hover:bg-rose-50 transition-colors cursor-pointer">Clear</button>
                            </div>
                            <button type="button" onclick="applyCalSelection(event)" class="px-3 py-1 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-lg shadow-2xs transition-colors flex items-center gap-1 cursor-pointer">
                                Apply
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Table of Transactions -->
            <div class="overflow-x-auto rounded-xl border border-slate-200/80">
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
                                        <i data-lucide="arrow-down-left" class="w-3 h-3 text-emerald-600"></i> Boundary Pondo
                                    </span>
                                @elseif($t->type === 'damage_deduction')
                                    <span class="px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded-lg text-[10px] font-black uppercase tracking-wider inline-flex items-center gap-1">
                                        <i data-lucide="shield-alert" class="w-3 h-3 text-rose-600"></i> Bangga / Damage
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
                            <td colspan="7" class="p-12 text-center text-slate-400 font-bold uppercase tracking-wider text-xs">
                                <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                                No fund transactions found matching your criteria.
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
                            <th class="p-4 text-right">Damages / Bangga</th>
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
                                        <span class="font-black text-slate-900 block">{{ $d->full_name }}</span>
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
                                    <button type="button" onclick="openLedgerDisburseModal({{ $d->id }}, '{{ addslashes($d->full_name) }}', {{ $d->current_balance }})" class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[11px] font-black uppercase tracking-wider transition-all flex items-center gap-1 shadow-2xs cursor-pointer" {{ $d->current_balance <= 0 ? 'disabled style=opacity:0.5;cursor:not-allowed;' : '' }}>
                                        <i data-lucide="minus-circle" class="w-3.5 h-3.5"></i> Deduct / Payout
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
            <!-- Driver Selection -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Select Driver <span class="text-red-500">*</span></label>
                <select id="disburseDriverSelect" required onchange="onDriverSelectChanged()" class="w-full px-3 py-2.5 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">-- Choose Driver --</option>
                    @foreach($drivers as $d)
                        <option value="{{ $d->id }}" data-balance="{{ $d->current_balance }}" data-name="{{ $d->full_name }}">
                            {{ $d->full_name }} ({{ $d->assigned_plate ?: 'Unassigned' }}) — Available: ₱{{ number_format($d->current_balance, 2) }}
                        </option>
                    @endforeach
                </select>
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
                    <option value="damage_deduction">Accident / Collision Damage Deduction (Bawas Bangga / Sira sa Taxi)</option>
                    <option value="maintenance_share">Vehicle Maintenance Co-Payment (Hatian sa Pagawa after 6 mos)</option>
                    <option value="company_liability">Company Liability / Debt Settlement (Kaltas sa Utang/Shortage)</option>
                    <option value="withdrawal">Driver Personal Savings Withdrawal (Cashout / Ipon)</option>
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
                    <input type="date" id="disburseDateInput" required value="{{ date('Y-m-d') }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>

            <!-- Description / Reason -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Purpose / Accident & Repair Details <span class="text-red-500">*</span></label>
                <textarea id="disburseDescriptionInput" required rows="2" maxlength="250" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 font-medium" placeholder="E.g., Offset for rear bumper bangga repair / Cashout request..."></textarea>
            </div>

            <!-- Buttons -->
            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="closeLedgerDisburseModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all cursor-pointer">Cancel</button>
                <button type="submit" id="btnSubmitLedgerDisburse" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black uppercase tracking-wider shadow-md shadow-emerald-500/20 transition-all flex items-center gap-1.5 cursor-pointer">
                    <i data-lucide="check-circle" class="w-4 h-4"></i> Confirm Transaction
                </button>
            </div>
        </form>
    </div>
</div>

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

    function openLedgerDisburseModal(driverId = null, driverName = null, balance = null) {
        const select = document.getElementById('disburseDriverSelect');
        if (driverId) {
            select.value = driverId;
            currentSelectedDriverBal = balance !== null ? parseFloat(balance) : 0;
            document.getElementById('disburseAvailableDisplay').textContent = '₱' + currentSelectedDriverBal.toLocaleString('en-PH', {minimumFractionDigits: 2});
            document.getElementById('disburseAmountInput').max = currentSelectedDriverBal;
        } else {
            select.value = '';
            currentSelectedDriverBal = 0;
            document.getElementById('disburseAvailableDisplay').textContent = '₱0.00';
            document.getElementById('disburseAmountInput').max = 0;
        }
        document.getElementById('disburseAmountInput').value = '';
        document.getElementById('disburseDescriptionInput').value = '';
        document.getElementById('ledgerDisburseModal').classList.remove('hidden');
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function onDriverSelectChanged() {
        const select = document.getElementById('disburseDriverSelect');
        const opt = select.options[select.selectedIndex];
        if (opt && opt.value) {
            currentSelectedDriverBal = parseFloat(opt.getAttribute('data-balance') || 0);
            document.getElementById('disburseAvailableDisplay').textContent = '₱' + currentSelectedDriverBal.toLocaleString('en-PH', {minimumFractionDigits: 2});
            document.getElementById('disburseAmountInput').max = currentSelectedDriverBal;
        } else {
            currentSelectedDriverBal = 0;
            document.getElementById('disburseAvailableDisplay').textContent = '₱0.00';
            document.getElementById('disburseAmountInput').max = 0;
        }
    }

    function closeLedgerDisburseModal() {
        document.getElementById('ledgerDisburseModal').classList.add('hidden');
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

    // --- Custom Compact Calendar Range Picker (Styled exactly as Image 2) ---
    const calMonths = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    const calMonthsShort = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

    let calSelectedFrom = "{{ request('date_from') }}" || null;
    let calSelectedTo = "{{ request('date_to') }}" || null;
    let calHoveredDate = null;

    // Initialize calendar view to selected start date or today
    let initCalDate = calSelectedFrom ? new Date(calSelectedFrom + 'T00:00:00') : new Date();
    let calViewYear = initCalDate.getFullYear();
    let calViewMonth = initCalDate.getMonth(); // 0 - 11

    function formatCalDisplay(yyyy_mm_dd) {
        if (!yyyy_mm_dd) return '';
        const parts = yyyy_mm_dd.split('-');
        if (parts.length !== 3) return yyyy_mm_dd;
        const m = parseInt(parts[1], 10) - 1;
        const d = String(parseInt(parts[2], 10)).padStart(2, '0');
        return (calMonthsShort[m] || '') + ' ' + d + ', ' + parts[0];
    }

    function openCalendarPicker(target, event) {
        if (event) event.stopPropagation();
        const dropdown = document.getElementById('customCalendarDropdown');
        if (!dropdown) return;

        if (dropdown.classList.contains('hidden')) {
            dropdown.classList.remove('hidden');
            if (calSelectedFrom) {
                const d = new Date(calSelectedFrom + 'T00:00:00');
                calViewYear = d.getFullYear();
                calViewMonth = d.getMonth();
            }
            renderCustomCalendar();
            if (typeof lucide !== 'undefined') lucide.createIcons();
        } else {
            dropdown.classList.add('hidden');
        }
    }

    function calendarNavMonth(delta, event) {
        if (event) event.stopPropagation();
        calViewMonth += delta;
        if (calViewMonth < 0) {
            calViewMonth = 11;
            calViewYear--;
        } else if (calViewMonth > 11) {
            calViewMonth = 0;
            calViewYear++;
        }
        renderCustomCalendar();
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function renderCustomCalendar() {
        const titleEl = document.getElementById('calMonthYearTitle');
        const gridEl = document.getElementById('calDaysGrid');
        if (!titleEl || !gridEl) return;

        titleEl.textContent = `${calMonths[calViewMonth]} ${calViewYear}`;

        // Monday-based start index (0 = Mon, 6 = Sun)
        const firstDayOfMonth = new Date(calViewYear, calViewMonth, 1);
        const startDayIndex = (firstDayOfMonth.getDay() + 6) % 7;
        const totalDaysInMonth = new Date(calViewYear, calViewMonth + 1, 0).getDate();
        const prevMonthDays = new Date(calViewYear, calViewMonth, 0).getDate();

        const today = new Date();
        const todayStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;

        let html = '';

        // Previous month filler days (dimmed text-slate-300)
        for (let i = startDayIndex - 1; i >= 0; i--) {
            const dNum = prevMonthDays - i;
            html += `<div class="h-8 flex items-center justify-center text-slate-300 font-medium text-xs cursor-default">${dNum}</div>`;
        }

        // Active range calculation
        const activeFrom = calSelectedFrom;
        const activeTo = calSelectedTo || (calSelectedFrom && calHoveredDate && calHoveredDate >= calSelectedFrom ? calHoveredDate : null);

        // Current month days
        for (let d = 1; d <= totalDaysInMonth; d++) {
            const dateStr = `${calViewYear}-${String(calViewMonth + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;

            const isStart = activeFrom === dateStr;
            const isEnd = activeTo === dateStr;
            const isBetween = activeFrom && activeTo && dateStr > activeFrom && dateStr < activeTo;
            const isToday = dateStr === todayStr;

            let cellBg = '';
            let btnClasses = 'w-7 h-7 mx-auto rounded-xl flex items-center justify-center text-xs font-bold transition-all relative z-10 ';

            if (isStart && isEnd) {
                btnClasses += 'bg-amber-500 text-white shadow-xs';
            } else if (isStart) {
                btnClasses += 'bg-amber-500 text-white shadow-xs';
                if (activeTo) {
                    cellBg = 'bg-gradient-to-r from-transparent 50% to-[#fef3c7] 50%';
                }
            } else if (isEnd) {
                btnClasses += 'bg-amber-500 text-white shadow-xs';
                if (activeFrom) {
                    cellBg = 'bg-gradient-to-l from-transparent 50% to-[#fef3c7] 50%';
                }
            } else if (isBetween) {
                cellBg = 'bg-[#fef3c7]';
                btnClasses += 'text-[#92400e] font-bold';
            } else if (isToday) {
                btnClasses += 'border-2 border-amber-500 text-amber-600 hover:bg-amber-50 cursor-pointer';
            } else {
                btnClasses += 'text-slate-700 hover:bg-slate-100 cursor-pointer';
            }

            html += `
                <div class="h-8 flex items-center justify-center relative ${cellBg}" 
                     onmouseenter="onCalDateHover('${dateStr}')" 
                     onclick="onCalDateClick('${dateStr}', event)">
                    <div class="${btnClasses}">
                        ${d}
                    </div>
                </div>
            `;
        }

        // Next month trailing days to complete row grid
        const totalRendered = startDayIndex + totalDaysInMonth;
        const trailingDays = (7 - (totalRendered % 7)) % 7;
        for (let nextD = 1; nextD <= trailingDays; nextD++) {
            html += `<div class="h-8 flex items-center justify-center text-slate-300 font-medium text-xs cursor-default">${nextD}</div>`;
        }

        gridEl.innerHTML = html;
    }

    function onCalDateClick(dateStr, event) {
        if (event) event.stopPropagation();

        if (!calSelectedFrom || (calSelectedFrom && calSelectedTo)) {
            // First click: sets Start Date
            calSelectedFrom = dateStr;
            calSelectedTo = null;
            calHoveredDate = null;
        } else if (calSelectedFrom && !calSelectedTo) {
            // Second click: sets End Date
            if (dateStr < calSelectedFrom) {
                calSelectedTo = calSelectedFrom;
                calSelectedFrom = dateStr;
            } else {
                calSelectedTo = dateStr;
            }
            calHoveredDate = null;
        }

        updateInputsAndDisplay();
        renderCustomCalendar();
    }

    function onCalDateHover(dateStr) {
        if (calSelectedFrom && !calSelectedTo) {
            if (calHoveredDate !== dateStr) {
                calHoveredDate = dateStr;
                renderCustomCalendar();
            }
        }
    }

    function updateInputsAndDisplay() {
        const fromInput = document.getElementById('filter_date_from');
        const toInput = document.getElementById('filter_date_to');
        const displayFrom = document.getElementById('display_date_from');
        const displayTo = document.getElementById('display_date_to');

        if (fromInput) fromInput.value = calSelectedFrom || '';
        if (toInput) toInput.value = calSelectedTo || '';

        if (displayFrom) {
            if (calSelectedFrom) {
                displayFrom.textContent = formatCalDisplay(calSelectedFrom);
                displayFrom.className = 'truncate text-slate-900 font-bold';
            } else {
                displayFrom.textContent = 'Start Date';
                displayFrom.className = 'truncate text-slate-400 font-normal';
            }
        }

        if (displayTo) {
            if (calSelectedTo) {
                displayTo.textContent = formatCalDisplay(calSelectedTo);
                displayTo.className = 'truncate text-slate-900 font-bold';
            } else {
                displayTo.textContent = 'End Date';
                displayTo.className = 'truncate text-slate-400 font-normal';
            }
        }
    }

    function setCalPreset(preset, event) {
        if (event) event.stopPropagation();
        const now = new Date();
        const nowStr = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')}`;

        if (preset === 'today') {
            calSelectedFrom = nowStr;
            calSelectedTo = nowStr;
            calViewYear = now.getFullYear();
            calViewMonth = now.getMonth();
        } else if (preset === 'this_month') {
            calSelectedFrom = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-01`;
            calSelectedTo = nowStr;
            calViewYear = now.getFullYear();
            calViewMonth = now.getMonth();
        }

        updateInputsAndDisplay();
        renderCustomCalendar();
    }

    function resetCalSelection(event) {
        if (event) event.stopPropagation();
        calSelectedFrom = null;
        calSelectedTo = null;
        calHoveredDate = null;
        updateInputsAndDisplay();
        renderCustomCalendar();
    }

    function applyCalSelection(event) {
        if (event) event.stopPropagation();
        const dropdown = document.getElementById('customCalendarDropdown');
        if (dropdown) dropdown.classList.add('hidden');
        
        // Auto-submit the filter form to apply range immediately
        const form = dropdown ? dropdown.closest('form') : null;
        if (form) {
            form.submit();
        }
    }

    // Close calendar on outside click
    document.addEventListener('click', function(e) {
        const container = document.getElementById('datePickerContainer');
        const dropdown = document.getElementById('customCalendarDropdown');
        if (container && dropdown && !container.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>
@endsection
