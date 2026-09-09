@extends('layouts.app')

@section('title', 'Archive Management | Euro Taxi System')
@section('page-heading', 'Archive Management')
@section('page-subheading', 'View and restore archived records from various modules')

@push('styles')
<style>
    /* ── 21st.dev Enterprise Archive Styles ── */
    .archive-table-container table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .archive-table-container table thead th {
        background: #f8fafc;
        padding: 0.9rem 1.5rem;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #64748b;
        border-bottom: 1px solid #e2e8f0;
    }

    .archive-table-container table tbody td {
        padding: 1.15rem 1.5rem;
        font-size: 0.875rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .archive-table-container table tbody tr {
        transition: all 0.15s ease;
    }

    .archive-table-container table tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Force hidden tab content to truly disappear */
    .tab-content.hidden {
        display: none !important;
        visibility: hidden !important;
        pointer-events: none !important;
        position: absolute !important;
        left: -9999px !important;
    }

    /* Tab pill active transitions */
    .archive-pill-tab {
        background-color: #f8fafc;
        color: #334155;
        border-color: #e2e8f0;
        transition: all 0.15s ease;
    }
    .archive-pill-tab:hover {
        background-color: #f1f5f9;
        color: #0f172a;
        border-color: #cbd5e1;
    }
    .archive-pill-tab.active,
    .archive-pill-tab.active:hover,
    .archive-pill-tab.active:focus,
    .archive-pill-tab.active:active {
        background-color: #0f172a !important;
        color: #ffffff !important;
        border-color: #0f172a !important;
        box-shadow: 0 4px 14px -2px rgba(15, 23, 42, 0.25) !important;
    }
    .archive-pill-tab.active span,
    .archive-pill-tab.active:hover span,
    .archive-pill-tab.active:focus span {
        color: #ffffff !important;
    }
    .archive-pill-tab.active .pill-counter,
    .archive-pill-tab.active:hover .pill-counter,
    .archive-pill-tab.active:focus .pill-counter {
        background-color: rgba(255, 255, 255, 0.2) !important;
        color: #ffffff !important;
    }
    .archive-pill-tab.active i,
    .archive-pill-tab.active:hover i,
    .archive-pill-tab.active:focus i,
    .archive-pill-tab.active svg,
    .archive-pill-tab.active:hover svg,
    .archive-pill-tab.active:focus svg {
        color: #ffffff !important;
        stroke: #ffffff !important;
    }
</style>
@endpush

@section('content')
@php
    $totalArchived = count($archivedUnits) + count($archivedDrivers) + count($archivedUserAccounts) + count($archivedExpenses) + count($archivedMaintenance) + count($archivedBoundaries) + count($archivedStaff) + count($archivedIncidents) + count($archivedAccidents) + count($archivedPricingRules) + count($archivedSuppliers) + count($archivedSpareParts) + count($archivedFranchiseCases) + count($archivedDriverTerms);
    $fleetCount = count($archivedUnits) + count($archivedDrivers) + count($archivedStaff);
    $opsFinancialCount = count($archivedExpenses) + count($archivedBoundaries) + count($archivedMaintenance) + count($archivedSpareParts);
    $securityCount = count($archivedUserAccounts) + count($archivedIncidents) + count($archivedAccidents) + count($archivedFranchiseCases);
@endphp

<div class="w-full space-y-6 pb-12">

    {{-- ── 1. PAGE HEADER & STATS SUMMARY ────────────────────────────────── --}}
    <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-r from-white via-sky-50/40 to-blue-50/30 backdrop-blur-md p-6 sm:p-8 shadow-xs w-full">
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <!-- Left Info with 3D Archive SVG -->
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 sm:w-24 sm:h-24 lg:w-28 lg:h-28 shrink-0">
                    <img src="{{ asset('image/kpi/archive_3d.svg') }}" alt="Archive Management" class="w-full h-full object-contain filter drop-shadow-lg hover:scale-105 transition-transform">
                </div>
                <div>
                    <!-- Breadcrumb -->
                    <div class="flex items-center gap-2 text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">
                        <span>System</span>
                        <span>/</span>
                        <span class="text-sky-600 font-black">Archive Management</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight">Archive Management</h1>
                    <p class="text-xs sm:text-sm lg:text-base text-slate-500 mt-1 max-w-2xl font-medium leading-relaxed">
                        Safely view, inspect, restore, or permanently purge archived records across all 14 fleet and system operational modules.
                    </p>
                </div>
            </div>

            <!-- Right Stats Grid (21st.dev Stats Dashboard Pattern) -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3.5 sm:gap-4 self-start lg:self-center shrink-0 w-full lg:w-auto">
                <!-- Total Archived -->
                <div class="bg-white/95 border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-xs min-w-[140px] flex-1">
                    <div class="flex items-center gap-2 text-slate-400 mb-1.5">
                        <i data-lucide="archive" class="w-4 h-4 text-sky-500"></i>
                        <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider">Total Archived</span>
                    </div>
                    <div class="text-xl sm:text-2xl lg:text-3xl font-black text-slate-900 leading-none tracking-tight" id="stat-total-archived">
                        {{ number_format($totalArchived) }}
                    </div>
                </div>

                <!-- Fleet & Drivers -->
                <div class="bg-white/95 border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-xs min-w-[140px] flex-1">
                    <div class="flex items-center gap-2 text-slate-400 mb-1.5">
                        <i data-lucide="car" class="w-4 h-4 text-amber-500"></i>
                        <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider">Fleet & Drivers</span>
                    </div>
                    <div class="text-xl sm:text-2xl lg:text-3xl font-black text-slate-900 leading-none tracking-tight" id="stat-fleet-drivers">
                        {{ number_format($fleetCount) }}
                    </div>
                </div>

                <!-- Financial & Ops -->
                <div class="bg-white/95 border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-xs col-span-2 sm:col-span-1 min-w-[140px] flex-1">
                    <div class="flex items-center gap-2 text-slate-400 mb-1.5">
                        <i data-lucide="wallet" class="w-4 h-4 text-emerald-500"></i>
                        <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider">Financial & Ops</span>
                    </div>
                    <div class="text-xl sm:text-2xl lg:text-3xl font-black text-slate-900 leading-none tracking-tight" id="stat-financial-ops">
                        {{ number_format($opsFinancialCount) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── 2. 21ST.DEV ROLE FILTER CHIPS / CATEGORY TAB BAR ──────────────── --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-4 sm:p-5 space-y-4 w-full">
        <div class="flex items-center justify-between px-2 pb-1">
            <div class="flex items-center gap-2">
                <i data-lucide="layers" class="w-4 h-4 text-slate-400"></i>
                <span class="text-xs font-black text-slate-500 uppercase tracking-widest">Select Archive Module</span>
            </div>
            <span class="text-xs font-bold text-slate-400">14 Categories Available</span>
        </div>

        <nav class="flex flex-wrap gap-2" aria-label="Archive Tabs" id="archive-tabs">
            
            <!-- 1. Units -->
            <button type="button" onclick="switchTab('units')" class="archive-pill-tab active inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl border text-xs font-bold transition-all active:scale-95 cursor-pointer" data-tab="units">
                <i data-lucide="car" class="w-3.5 h-3.5"></i>
                <span>Units</span>
                <span class="pill-counter px-2 py-0.5 rounded-full text-[10px] font-black bg-slate-200 text-slate-700">{{ count($archivedUnits) }}</span>
            </button>

            <!-- 2. Drivers -->
            <button type="button" onclick="switchTab('drivers')" class="archive-pill-tab inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl border text-xs font-bold transition-all active:scale-95 cursor-pointer" data-tab="drivers">
                <i data-lucide="users" class="w-3.5 h-3.5"></i>
                <span>Drivers</span>
                <span class="pill-counter px-2 py-0.5 rounded-full text-[10px] font-black bg-slate-200 text-slate-700">{{ count($archivedDrivers) }}</span>
            </button>

            <!-- 3. User Accounts -->
            <button type="button" onclick="switchTab('user_accounts')" class="archive-pill-tab inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl border text-xs font-bold transition-all active:scale-95 cursor-pointer" data-tab="user_accounts">
                <i data-lucide="shield" class="w-3.5 h-3.5"></i>
                <span>User Accounts</span>
                <span class="pill-counter px-2 py-0.5 rounded-full text-[10px] font-black bg-slate-200 text-slate-700">{{ count($archivedUserAccounts) }}</span>
            </button>

            <!-- 4. Expenses -->
            <button type="button" onclick="switchTab('expenses')" class="archive-pill-tab inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl border text-xs font-bold transition-all active:scale-95 cursor-pointer" data-tab="expenses">
                <i data-lucide="receipt" class="w-3.5 h-3.5"></i>
                <span>Expenses</span>
                <span class="pill-counter px-2 py-0.5 rounded-full text-[10px] font-black bg-slate-200 text-slate-700">{{ count($archivedExpenses) }}</span>
            </button>

            <!-- 5. Maintenance -->
            <button type="button" onclick="switchTab('maintenance')" class="archive-pill-tab inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl border text-xs font-bold transition-all active:scale-95 cursor-pointer" data-tab="maintenance">
                <i data-lucide="wrench" class="w-3.5 h-3.5"></i>
                <span>Maintenance</span>
                <span class="pill-counter px-2 py-0.5 rounded-full text-[10px] font-black bg-slate-200 text-slate-700">{{ count($archivedMaintenance) }}</span>
            </button>

            <!-- 6. Boundaries -->
            <button type="button" onclick="switchTab('boundaries')" class="archive-pill-tab inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl border text-xs font-bold transition-all active:scale-95 cursor-pointer" data-tab="boundaries">
                <i data-lucide="wallet" class="w-3.5 h-3.5"></i>
                <span>Boundaries</span>
                <span class="pill-counter px-2 py-0.5 rounded-full text-[10px] font-black bg-slate-200 text-slate-700">{{ count($archivedBoundaries) }}</span>
            </button>

            <!-- 7. Staff -->
            <button type="button" onclick="switchTab('staff')" class="archive-pill-tab inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl border text-xs font-bold transition-all active:scale-95 cursor-pointer" data-tab="staff">
                <i data-lucide="user-cog" class="w-3.5 h-3.5"></i>
                <span>Staff</span>
                <span class="pill-counter px-2 py-0.5 rounded-full text-[10px] font-black bg-slate-200 text-slate-700">{{ count($archivedStaff) }}</span>
            </button>

            <!-- 8. Incidents -->
            <button type="button" onclick="switchTab('incidents')" class="archive-pill-tab inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl border text-xs font-bold transition-all active:scale-95 cursor-pointer" data-tab="incidents">
                <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i>
                <span>Incidents</span>
                <span class="pill-counter px-2 py-0.5 rounded-full text-[10px] font-black bg-slate-200 text-slate-700">{{ count($archivedIncidents) }}</span>
            </button>

            <!-- 9. Accidents/SOS -->
            <button type="button" onclick="switchTab('accidents')" class="archive-pill-tab inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl border text-xs font-bold transition-all active:scale-95 cursor-pointer" data-tab="accidents">
                <i data-lucide="ambulance" class="w-3.5 h-3.5"></i>
                <span>Accidents/SOS</span>
                <span class="pill-counter px-2 py-0.5 rounded-full text-[10px] font-black bg-slate-200 text-slate-700">{{ count($archivedAccidents) }}</span>
            </button>

            <!-- 10. Pricing Rules -->
            <button type="button" onclick="switchTab('pricing_rules')" class="archive-pill-tab inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl border text-xs font-bold transition-all active:scale-95 cursor-pointer" data-tab="pricing_rules">
                <i data-lucide="scale" class="w-3.5 h-3.5"></i>
                <span>Pricing Rules</span>
                <span class="pill-counter px-2 py-0.5 rounded-full text-[10px] font-black bg-slate-200 text-slate-700">{{ count($archivedPricingRules) }}</span>
            </button>

            <!-- 11. Suppliers -->
            <button type="button" onclick="switchTab('suppliers')" class="archive-pill-tab inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl border text-xs font-bold transition-all active:scale-95 cursor-pointer" data-tab="suppliers">
                <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                <span>Suppliers</span>
                <span class="pill-counter px-2 py-0.5 rounded-full text-[10px] font-black bg-slate-200 text-slate-700">{{ count($archivedSuppliers) }}</span>
            </button>

            <!-- 12. Spare Parts -->
            <button type="button" onclick="switchTab('spare_parts')" class="archive-pill-tab inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl border text-xs font-bold transition-all active:scale-95 cursor-pointer" data-tab="spare_parts">
                <i data-lucide="package" class="w-3.5 h-3.5"></i>
                <span>Spare Parts</span>
                <span class="pill-counter px-2 py-0.5 rounded-full text-[10px] font-black bg-slate-200 text-slate-700">{{ count($archivedSpareParts) }}</span>
            </button>

            <!-- 13. Franchise Cases -->
            <button type="button" onclick="switchTab('franchise_cases')" class="archive-pill-tab inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl border text-xs font-bold transition-all active:scale-95 cursor-pointer" data-tab="franchise_cases">
                <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                <span>Franchise Cases</span>
                <span class="pill-counter px-2 py-0.5 rounded-full text-[10px] font-black bg-slate-200 text-slate-700">{{ count($archivedFranchiseCases) }}</span>
            </button>

            <!-- 14. Driver Terms -->
            <button type="button" onclick="switchTab('driver_terms')" class="archive-pill-tab inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl border text-xs font-bold transition-all active:scale-95 cursor-pointer" data-tab="driver_terms">
                <i data-lucide="scroll" class="w-3.5 h-3.5"></i>
                <span>Driver Terms</span>
                <span class="pill-counter px-2 py-0.5 rounded-full text-[10px] font-black bg-slate-200 text-slate-700">{{ count($archivedDriverTerms) }}</span>
            </button>

        </nav>
    </div>

    {{-- ── 3. 21ST.DEV CARD FRAME ARCHIVE TABLE ───────────────────────────── --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden w-full archive-table-container">
        
        <!-- Table Header Bar -->
        <div class="px-6 sm:px-8 py-5 sm:py-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="p-2.5 bg-sky-50 text-sky-600 rounded-xl">
                    <i data-lucide="archive-restore" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-lg sm:text-xl font-black text-slate-900" id="currentTabHeading">Archived Units</h3>
                    <p class="text-xs sm:text-sm text-slate-500">Restore items back to active status or permanently wipe them from database.</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <span class="text-xs sm:text-sm font-black text-sky-700 bg-sky-50 px-3.5 py-1.5 rounded-xl border border-sky-200" id="currentTabBadge">
                    {{ count($archivedUnits) }} Records
                </span>
            </div>
        </div>

        <!-- Tab Content Areas (All 14 Modules) -->
        <div class="p-0">
            <!-- 1. Units Tab -->
            <div id="tab-units" class="tab-content" data-title="Archived Units" data-count="{{ count($archivedUnits) }}">
                @include('archive.partials._units_table', ['items' => $archivedUnits])
            </div>

            <!-- 2. Drivers Tab -->
            <div id="tab-drivers" class="tab-content hidden" data-title="Archived Drivers" data-count="{{ count($archivedDrivers) }}">
                @include('archive.partials._drivers_table', ['items' => $archivedDrivers])
            </div>

            <!-- 3. User Accounts Tab -->
            <div id="tab-user_accounts" class="tab-content hidden" data-title="Archived User Accounts" data-count="{{ count($archivedUserAccounts) }}">
                @include('archive.partials._user_accounts_table', ['items' => $archivedUserAccounts])
            </div>

            <!-- 4. Expenses Tab -->
            <div id="tab-expenses" class="tab-content hidden" data-title="Archived Expenses" data-count="{{ count($archivedExpenses) }}">
                @include('archive.partials._expenses_table', ['items' => $archivedExpenses])
            </div>

            <!-- 5. Maintenance Tab -->
            <div id="tab-maintenance" class="tab-content hidden" data-title="Archived Maintenance Records" data-count="{{ count($archivedMaintenance) }}">
                @include('archive.partials._maintenance_table', ['items' => $archivedMaintenance])
            </div>

            <!-- 6. Boundaries Tab -->
            <div id="tab-boundaries" class="tab-content hidden" data-title="Archived Boundaries" data-count="{{ count($archivedBoundaries) }}">
                @include('archive.partials._boundaries_table', ['items' => $archivedBoundaries])
            </div>

            <!-- 7. Staff Tab -->
            <div id="tab-staff" class="tab-content hidden" data-title="Archived Staff" data-count="{{ count($archivedStaff) }}">
                @include('archive.partials._staff_table', ['items' => $archivedStaff])
            </div>

            <!-- 8. Incidents Tab -->
            <div id="tab-incidents" class="tab-content hidden" data-title="Archived Incidents & Behaviors" data-count="{{ count($archivedIncidents) }}">
                @include('archive.partials._incidents_table', ['items' => $archivedIncidents])
            </div>

            <!-- 9. Accidents Tab -->
            <div id="tab-accidents" class="tab-content hidden" data-title="Archived Accidents & SOS" data-count="{{ count($archivedAccidents) }}">
                @include('archive.partials._accidents_table', ['items' => $archivedAccidents])
            </div>

            <!-- 10. Pricing Rules Tab -->
            <div id="tab-pricing_rules" class="tab-content hidden" data-title="Archived Pricing Rules" data-count="{{ count($archivedPricingRules) }}">
                @include('archive.partials._pricing_rules_table', ['items' => $archivedPricingRules])
            </div>

            <!-- 11. Suppliers Tab -->
            <div id="tab-suppliers" class="tab-content hidden" data-title="Archived Suppliers" data-count="{{ count($archivedSuppliers) }}">
                @include('archive.partials._suppliers_table', ['items' => $archivedSuppliers])
            </div>

            <!-- 12. Spare Parts Tab -->
            <div id="tab-spare_parts" class="tab-content hidden" data-title="Archived Spare Parts" data-count="{{ count($archivedSpareParts) }}">
                @include('archive.partials._spare_parts_table', ['items' => $archivedSpareParts])
            </div>

            <!-- 13. Franchise Cases Tab -->
            <div id="tab-franchise_cases" class="tab-content hidden" data-title="Archived Franchise Cases" data-count="{{ count($archivedFranchiseCases) }}">
                @include('archive.partials._franchise_cases_table', ['items' => $archivedFranchiseCases])
            </div>

            <!-- 14. Driver Terms Tab -->
            <div id="tab-driver_terms" class="tab-content hidden" data-title="Archived Driver Terms" data-count="{{ count($archivedDriverTerms) }}">
                @include('archive.partials._driver_terms_table', ['items' => $archivedDriverTerms])
            </div>
        </div>

    </div>

</div>

<!-- Lightbox Modal -->
<div id="lightbox" class="fixed inset-0 z-[100] bg-black/90 backdrop-blur-xs items-center justify-center p-4 transition-opacity" style="display:none;" onclick="this.style.display='none'">
    <button class="absolute top-6 right-6 text-white hover:text-gray-300 transition-colors p-2" onclick="document.getElementById('lightbox').style.display='none'">
        <i data-lucide="x" class="w-8 h-8"></i>
    </button>
    <img id="lightbox-img" src="" alt="Zoomed Document" class="max-w-full max-h-full object-contain rounded-2xl shadow-2xl select-none" style="-webkit-user-drag: none;" oncontextmenu="return false;" draggable="false" onclick="event.stopPropagation()">
</div>

<script>
    // ── Global Archive Configuration for Empty States ─────────────────────
    const archiveEmptyStates = {
        units: { icon: 'car', title: 'No archived units found', desc: 'Archived taxi units will appear here for restoration or deletion.', cols: 3 },
        drivers: { icon: 'users', title: 'No archived drivers found', desc: 'Drivers moved to archive will appear here for restoration or deletion.', cols: 4 },
        user_accounts: { icon: 'shield', title: 'No archived user accounts found', desc: 'Deactivated or deleted accounts will appear here.', cols: 5 },
        expenses: { icon: 'receipt', title: 'No archived expenses found', desc: 'Expenses deleted from Office Expenses will appear here.', cols: 5 },
        maintenance: { icon: 'wrench', title: 'No archived maintenance records found', desc: 'Deleted maintenance items will appear here.', cols: 5 },
        boundaries: { icon: 'wallet', title: 'No archived boundary records found', desc: 'Archived boundaries will appear here.', cols: 5 },
        staff: { icon: 'user-cog', title: 'No archived staff records found', desc: 'Archived staff employees will appear here.', cols: 4 },
        incidents: { icon: 'alert-triangle', title: 'No archived incidents found', desc: 'Behavioral and operational incidents moved to archive will appear here.', cols: 5 },
        accidents: { icon: 'ambulance', title: 'No archived accidents found', desc: 'Accident and SOS emergency tickets moved to archive will appear here.', cols: 5 },
        pricing_rules: { icon: 'scale', title: 'No archived pricing rules found', desc: 'Pricing and boundary calculation rules in archive will appear here.', cols: 4 },
        suppliers: { icon: 'truck', title: 'No archived suppliers found', desc: 'Suppliers deleted from supplier management will appear here.', cols: 6 },
        spare_parts: { icon: 'package', title: 'No archived spare parts found', desc: 'Spare parts you archive from Inventory will appear here.', cols: 5 },
        franchise_cases: { icon: 'file-text', title: 'No archived franchise cases found', desc: 'LTFRB franchise case dockets moved to archive will appear here.', cols: 3 },
        driver_terms: { icon: 'scroll', title: 'No archived terms', desc: 'There are no deleted driver terms documents in storage.' }
    };

    // ── Modern Floating Toast Feedback ───────────────────────────────────
    function showArchiveToast(message, type = 'success') {
        let container = document.getElementById('archive-toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'archive-toast-container';
            container.className = 'fixed bottom-6 right-6 z-[9999] flex flex-col gap-2.5 pointer-events-none';
            document.body.appendChild(container);
        }

        const isSuccess = type === 'success';
        const toast = document.createElement('div');
        toast.className = `flex items-center gap-3 px-4 py-3 rounded-2xl shadow-2xl border pointer-events-auto transition-all transform duration-300 translate-y-4 opacity-0 text-xs sm:text-sm font-bold backdrop-blur-md ${
            isSuccess 
                ? 'bg-slate-900/95 text-white border-slate-700/80 shadow-slate-900/40' 
                : 'bg-rose-900/95 text-white border-rose-700/80 shadow-rose-900/40'
        }`;

        toast.innerHTML = `
            <div class="w-6 h-6 rounded-full flex items-center justify-center shrink-0 ${isSuccess ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/20 text-rose-300'}">
                <i data-lucide="${isSuccess ? 'check-circle-2' : 'alert-circle'}" class="w-4 h-4"></i>
            </div>
            <span class="flex-1 pr-2 leading-tight">${message}</span>
            <button type="button" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors" onclick="this.parentElement.remove()">
                <i data-lucide="x" class="w-3.5 h-3.5"></i>
            </button>
        `;

        container.appendChild(toast);
        if (window.lucide) lucide.createIcons();

        requestAnimationFrame(() => {
            toast.classList.remove('translate-y-4', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');
        });

        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-y-2');
            setTimeout(() => toast.remove(), 300);
        }, 3500);
    }

    // ── Dynamic Counter Decrement Engine ─────────────────────────────────
    function decrementArchiveCounters(tabId) {
        // 1. Update tab pill counter
        const tabBtn = document.querySelector(`[data-tab="${tabId}"]`);
        if (tabBtn) {
            const pill = tabBtn.querySelector('.pill-counter');
            if (pill) {
                const currentPillCount = Math.max(0, parseInt(pill.textContent.trim() || '0', 10) - 1);
                pill.textContent = currentPillCount;
            }
        }

        // 2. Update tab content container data-count & active badge
        const tabContent = document.getElementById('tab-' + tabId);
        if (tabContent) {
            const newTabCount = Math.max(0, parseInt(tabContent.getAttribute('data-count') || '0', 10) - 1);
            tabContent.setAttribute('data-count', newTabCount);
            
            // If this tab is currently the active visible tab, update card badge
            if (!tabContent.classList.contains('hidden')) {
                const badgeEl = document.getElementById('currentTabBadge');
                if (badgeEl) badgeEl.textContent = newTabCount + ' Records';
            }
        }

        // 3. Update top summary statistics
        const totalStatEl = document.getElementById('stat-total-archived');
        if (totalStatEl) {
            const total = Math.max(0, parseInt(totalStatEl.textContent.replace(/,/g, '').trim() || '0', 10) - 1);
            totalStatEl.textContent = total.toLocaleString();
        }

        const fleetTabs = ['units', 'drivers', 'staff'];
        const opsFinancialTabs = ['expenses', 'boundaries', 'maintenance', 'spare_parts'];

        if (fleetTabs.includes(tabId)) {
            const fleetStatEl = document.getElementById('stat-fleet-drivers');
            if (fleetStatEl) {
                const fleet = Math.max(0, parseInt(fleetStatEl.textContent.replace(/,/g, '').trim() || '0', 10) - 1);
                fleetStatEl.textContent = fleet.toLocaleString();
            }
        } else if (opsFinancialTabs.includes(tabId)) {
            const opsStatEl = document.getElementById('stat-financial-ops');
            if (opsStatEl) {
                const ops = Math.max(0, parseInt(opsStatEl.textContent.replace(/,/g, '').trim() || '0', 10) - 1);
                opsStatEl.textContent = ops.toLocaleString();
            }
        }
    }

    // ── Check and Render Empty State In-Place ─────────────────────────────
    function checkAndUpdateEmptyState(tabId) {
        const tabContent = document.getElementById('tab-' + tabId);
        if (!tabContent) return;

        if (tabId === 'driver_terms') {
            const grid = tabContent.querySelector('.grid');
            if (grid && grid.querySelectorAll(':scope > div').length === 0) {
                tabContent.innerHTML = `
                    <div class="px-6 sm:px-8 py-16 text-center">
                        <div class="flex flex-col items-center gap-3 text-slate-400">
                            <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center">
                                <i data-lucide="scroll" class="w-8 h-8 opacity-40"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-700">No archived terms</p>
                            <p class="text-xs text-slate-400">There are no deleted driver terms documents in storage.</p>
                        </div>
                    </div>
                `;
                if (window.lucide) lucide.createIcons();
            }
            return;
        }

        const tbody = tabContent.querySelector('tbody');
        if (tbody) {
            const rows = tbody.querySelectorAll('tr');
            if (rows.length === 0) {
                const config = archiveEmptyStates[tabId] || { icon: 'archive', title: 'No archived items found', desc: 'Archived records will appear here.', cols: 4 };
                tbody.innerHTML = `
                    <tr>
                        <td colspan="${config.cols}" class="px-6 sm:px-8 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-slate-400">
                                <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center">
                                    <i data-lucide="${config.icon}" class="w-8 h-8 opacity-40"></i>
                                </div>
                                <p class="text-sm font-bold text-slate-700">${config.title}</p>
                                <p class="text-xs text-slate-400">${config.desc}</p>
                            </div>
                        </td>
                    </tr>
                `;
                if (window.lucide) lucide.createIcons();
            }
        }
    }

    function getCurrentActiveTabId() {
        const activeContent = document.querySelector('.tab-content:not(.hidden)');
        if (activeContent) {
            return activeContent.id.replace('tab-', '');
        }
        const activeTabBtn = document.querySelector('.archive-pill-tab.active');
        if (activeTabBtn) {
            return activeTabBtn.getAttribute('data-tab');
        }
        return 'units';
    }

    // ── Global Archive In-Place Restore Action ─────────────────────────────
    async function archiveRestore(restoreUrl, btnEl) {
        if (!confirm('Are you sure you want to restore this archived record back to active?')) return;

        const btn = btnEl || (window.event ? (window.event.target.closest('button') || window.event.target) : null);
        const row = btn ? (btn.closest('tr') || btn.closest('.grid > div')) : null;
        const tabContainer = btn ? btn.closest('.tab-content') : null;
        const tabId = tabContainer ? tabContainer.id.replace('tab-', '') : getCurrentActiveTabId();

        let originalContent = '';
        if (btn) {
            originalContent = btn.innerHTML;
            btn.disabled = true;
            btn.style.opacity = '0.7';
            btn.innerHTML = '<i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin"></i> Restoring...';
            if (window.lucide) lucide.createIcons();
        }

        try {
            const response = await fetch(restoreUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            });

            const result = await response.json();

            if (response.ok && result.success !== false) {
                if (row) {
                    row.style.pointerEvents = 'none';
                    row.style.transition = 'all 0.35s cubic-bezier(0.4, 0, 0.2, 1)';
                    row.style.opacity = '0';
                    row.style.transform = 'scale(0.96) translateY(-4px)';
                    setTimeout(() => {
                        row.remove();
                        decrementArchiveCounters(tabId);
                        checkAndUpdateEmptyState(tabId);
                    }, 350);
                } else {
                    decrementArchiveCounters(tabId);
                    checkAndUpdateEmptyState(tabId);
                }
                showArchiveToast(result.message || 'Item restored successfully.', 'success');
            } else {
                if (btn) {
                    btn.disabled = false;
                    btn.style.opacity = '1';
                    btn.innerHTML = originalContent;
                    if (window.lucide) lucide.createIcons();
                }
                showArchiveToast(result.message || 'Error occurred. Please try again.', 'error');
            }
        } catch (err) {
            if (btn) {
                btn.disabled = false;
                btn.style.opacity = '1';
                btn.innerHTML = originalContent;
                if (window.lucide) lucide.createIcons();
            }
            showArchiveToast('A network error occurred. Please try again.', 'error');
        }
    }

    // ── Global Archive In-Place Force Delete Action ────────────────────────
    async function archiveForceDelete(deleteUrl, btnEl) {
        if (typeof window.promptArchiveDeletionPassword !== 'function') {
            alert('Security verification modal is not available. Please refresh the page.');
            return;
        }

        const password = await window.promptArchiveDeletionPassword();
        if (!password) return; // User cancelled

        const btn = btnEl || (window.event ? (window.event.target.closest('button') || window.event.target) : null);
        const row = btn ? (btn.closest('tr') || btn.closest('.grid > div')) : null;
        const tabContainer = btn ? btn.closest('.tab-content') : null;
        const tabId = tabContainer ? tabContainer.id.replace('tab-', '') : getCurrentActiveTabId();

        let originalContent = '';
        if (btn) {
            originalContent = btn.innerHTML;
            btn.disabled = true;
            btn.style.opacity = '0.7';
            btn.innerHTML = '<i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin"></i> Deleting...';
            if (window.lucide) lucide.createIcons();
        }

        try {
            const response = await fetch(deleteUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ archive_password: password }),
            });

            const result = await response.json();

            if (response.ok && result.success !== false) {
                if (row) {
                    row.style.pointerEvents = 'none';
                    row.style.transition = 'all 0.35s cubic-bezier(0.4, 0, 0.2, 1)';
                    row.style.opacity = '0';
                    row.style.transform = 'scale(0.96) translateY(-4px)';
                    setTimeout(() => {
                        row.remove();
                        decrementArchiveCounters(tabId);
                        checkAndUpdateEmptyState(tabId);
                    }, 350);
                } else {
                    decrementArchiveCounters(tabId);
                    checkAndUpdateEmptyState(tabId);
                }
                showArchiveToast(result.message || 'Item permanently deleted.', 'success');
            } else {
                if (btn) {
                    btn.disabled = false;
                    btn.style.opacity = '1';
                    btn.innerHTML = originalContent;
                    if (window.lucide) lucide.createIcons();
                }
                showArchiveToast(result.message || 'Invalid deletion password or error occurred.', 'error');
            }
        } catch (err) {
            if (btn) {
                btn.disabled = false;
                btn.style.opacity = '1';
                btn.innerHTML = originalContent;
                if (window.lucide) lucide.createIcons();
            }
            showArchiveToast('A network error occurred. Please try again.', 'error');
        }
    }

    function switchTab(tabId) {
        // Hide all tab content
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });

        // Show selected tab content
        const targetTab = document.getElementById('tab-' + tabId);
        if (targetTab) {
            targetTab.classList.remove('hidden');
            
            // Update Card Header info
            const title = targetTab.getAttribute('data-title');
            const count = targetTab.getAttribute('data-count');
            const headingEl = document.getElementById('currentTabHeading');
            const badgeEl = document.getElementById('currentTabBadge');
            
            if (headingEl && title) headingEl.textContent = title;
            if (badgeEl && count !== null) badgeEl.textContent = count + ' Records';
        }

        // Update tab pill button styles
        document.querySelectorAll('.archive-pill-tab').forEach(btn => {
            btn.classList.remove('active');
        });

        const activeBtn = document.querySelector(`[data-tab="${tabId}"]`);
        if (activeBtn) {
            activeBtn.classList.add('active');
        }

        if (tabId === 'spare_parts') {
            renderArchivedSparePartsIcons();
        }

        // Preserve tab in URL and sessionStorage without page reload
        try {
            const url = new URL(window.location.href);
            url.searchParams.set('tab', tabId);
            window.history.replaceState({ tab: tabId }, '', url.toString());
            sessionStorage.setItem('archive_active_tab', tabId);
        } catch (e) {}
    }

    // Lightbox Functionality
    function openLightbox(src) {
        document.getElementById('lightbox-img').src = src;
        document.getElementById('lightbox').style.display = 'flex';
    }

    // ── Procedural Vector Icons for Archived Spare Parts ──
    function renderArchivedSparePartsIcons() {
        if (typeof generateDynamicPartSVG !== 'function') return;

        document.querySelectorAll('.archived-part-icon-box').forEach(box => {
            const name = box.getAttribute('data-part-name');
            if (!name) return;
            const svgUri = generateDynamicPartSVG(name);
            const meta = getPartAIMeta(name);
            
            box.className = `archived-part-icon-box relative w-12 h-12 rounded-2xl p-1 flex items-center justify-center shrink-0 border ${meta.badgeBorder} ${meta.badgeBg} bg-white shadow-xs cursor-pointer overflow-hidden group-hover:scale-105 transition-transform`;
            box.innerHTML = `<img src="${svgUri}" alt="${name}" class="w-full h-full object-contain filter drop-shadow-sm rounded-xl">`;
            box.onclick = () => openSparePartVectorModal(svgUri, name);
        });

        document.querySelectorAll('.archived-part-meta').forEach(metaEl => {
            const name = metaEl.getAttribute('data-part-name');
            if (!name) return;
            const meta = getPartAIMeta(name);
            metaEl.innerHTML = `
                <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider ${meta.textClass}">
                    <span class="w-1.5 h-1.5 rounded-full ${meta.dotClass}"></span>
                    ${meta.category}
                </span>
            `;
        });
    }

    // Handle initial tab from URL query parameter or sessionStorage
    function initArchive() {
        const urlParams = new URLSearchParams(window.location.search);
        const tabFromUrl = urlParams.get('tab');
        const tabFromStorage = sessionStorage.getItem('archive_active_tab');
        const initialTab = tabFromUrl || tabFromStorage || 'units';

        if (document.getElementById('tab-' + initialTab)) {
            switchTab(initialTab);
        } else {
            switchTab('units');
        }

        renderArchivedSparePartsIcons();
        if (window.lucide) lucide.createIcons();
    }

    window.addEventListener('popstate', (e) => {
        if (e.state && e.state.tab) {
            switchTab(e.state.tab);
        } else {
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab') || 'units';
            switchTab(tab);
        }
    });

    document.addEventListener('DOMContentLoaded', initArchive);
    document.addEventListener('page:loaded', initArchive);
    initArchive();
</script>
<script src="{{ asset('js/spare-parts-vector-engine.js') }}"></script>
@endsection
