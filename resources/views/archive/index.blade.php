@extends('layouts.app')

@section('title', 'Archive Management | Euro Taxi System')
@section('page-heading', 'Archive Management')
@section('page-subheading', 'View and restore archived records from various modules')

@section('content')
<div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <!-- Archive Tabs -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="border-b border-gray-100">
            <nav class="flex -mb-px px-6 space-x-8" aria-label="Tabs" id="archive-tabs">
                <button onclick="switchTab('units')" class="tab-btn active border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all" data-tab="units">
                    Units ({{ count($archivedUnits) }})
                </button>
                <button onclick="switchTab('drivers')" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all" data-tab="drivers">
                    Drivers ({{ count($archivedDrivers) }})
                </button>
                <button onclick="switchTab('expenses')" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all" data-tab="expenses">
                    Expenses ({{ count($archivedExpenses) }})
                </button>
                <button onclick="switchTab('maintenance')" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all" data-tab="maintenance">
                    Maintenance ({{ count($archivedMaintenance) }})
                </button>
                <button onclick="switchTab('boundaries')" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all" data-tab="boundaries">
                    Boundaries ({{ count($archivedBoundaries) }})
                </button>
                <button onclick="switchTab('staff')" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all" data-tab="staff">
                    Staff ({{ count($archivedStaff) }})
                </button>
                <button onclick="switchTab('incidents')" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all" data-tab="incidents">
                    Incidents ({{ count($archivedIncidents) }})
                </button>
                <button onclick="switchTab('pricing_rules')" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all" data-tab="pricing_rules">
                    Pricing Rules ({{ count($archivedPricingRules) }})
                </button>
                <button onclick="switchTab('suppliers')" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all" data-tab="suppliers">
                    Suppliers ({{ count($archivedSuppliers) }})
                </button>

            </nav>
        </div>

        <div class="p-6">
            <!-- Units Tab -->
            <div id="tab-units" class="tab-content">
                @include('archive.partials._units_table', ['items' => $archivedUnits])
            </div>

            <!-- Drivers Tab -->
            <div id="tab-drivers" class="tab-content hidden">
                @include('archive.partials._drivers_table', ['items' => $archivedDrivers])
            </div>

            <!-- Expenses Tab -->
            <div id="tab-expenses" class="tab-content hidden">
                @include('archive.partials._expenses_table', ['items' => $archivedExpenses])
            </div>

            <!-- Maintenance Tab -->
            <div id="tab-maintenance" class="tab-content hidden">
                @include('archive.partials._maintenance_table', ['items' => $archivedMaintenance])
            </div>

            <!-- Boundaries Tab -->
            <div id="tab-boundaries" class="tab-content hidden">
                @include('archive.partials._boundaries_table', ['items' => $archivedBoundaries])
            </div>

            <!-- Staff Tab -->
            <div id="tab-staff" class="tab-content hidden">
                @include('archive.partials._staff_table', ['items' => $archivedStaff])
            </div>

            <!-- Incidents Tab -->
            <div id="tab-incidents" class="tab-content hidden">
                @include('archive.partials._incidents_table', ['items' => $archivedIncidents])
            </div>

            <!-- Pricing Rules Tab -->
            <div id="tab-pricing_rules" class="tab-content hidden">
                @include('archive.partials._pricing_rules_table', ['items' => $archivedPricingRules])
            </div>

            <!-- Suppliers Tab -->
            <div id="tab-suppliers" class="tab-content hidden">
                @include('archive.partials._suppliers_table', ['items' => $archivedSuppliers])
            </div>


        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- SHARED: Delete Permanently Modal — used by ALL archive partials --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
<div id="permanentDeleteModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-[200] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden animate-fade-in">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-red-600 to-rose-600 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <i data-lucide="shield-alert" class="w-5 h-5 text-white"></i>
                </div>
                <div>
                    <h3 class="text-base font-black text-white">Permanent Deletion</h3>
                    <p class="text-xs text-red-200 font-medium">This action cannot be undone</p>
                </div>
            </div>
        </div>

        <form id="permanentDeleteForm" method="POST" class="p-6 space-y-5">
            @csrf
            @method('DELETE')

            {{-- Warning Box --}}
            <div class="bg-red-50 border border-red-100 rounded-xl p-4 flex gap-3">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5"></i>
                <div>
                    <p class="text-xs font-black text-red-800 uppercase tracking-wide mb-1">Warning</p>
                    <p class="text-xs text-red-700 leading-relaxed">
                        You are about to permanently wipe
                        <span id="deleteItemName" class="font-black text-red-900"></span>
                        from the database. All related data will be lost forever.
                    </p>
                </div>
            </div>

            {{-- Password Field --}}
            <div class="space-y-2">
                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">Archive Deletion Password</label>
                <div class="relative">
                    <i data-lucide="lock" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                    <input type="password" name="archive_password" id="archivePasswordInput" required
                        class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none text-sm font-bold tracking-widest text-center"
                        placeholder="Enter password to confirm">
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-3 pt-1">
                <button type="button" onclick="closePermanentDeleteModal()"
                    class="flex-1 px-4 py-3 text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all">
                    Cancel
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-3 text-sm font-black text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-lg shadow-red-200 transition-all flex items-center justify-center gap-2">
                    <i data-lucide="trash-2" class="w-4 h-4"></i> Wipe Permanently
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // ── Tab Switcher ──────────────────────────────────────────────────
    function switchTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        document.getElementById('tab-' + tabId).classList.remove('hidden');
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('border-blue-500', 'text-blue-600');
            btn.classList.add('border-transparent', 'text-gray-500');
        });
        const activeBtn = document.querySelector(`[data-tab="${tabId}"]`);
        if (activeBtn) {
            activeBtn.classList.remove('border-transparent', 'text-gray-500');
            activeBtn.classList.add('border-blue-500', 'text-blue-600');
        }
    }

    // Handle initial tab from URL query parameter
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const initialTab = urlParams.get('tab');
        if (initialTab) switchTab(initialTab);
        if (window.lucide) window.lucide.createIcons();
    });

    // ── Shared Delete Permanently Modal ───────────────────────────────
    function confirmPermanentDelete(type, id, name) {
        const modal = document.getElementById('permanentDeleteModal');
        const form = document.getElementById('permanentDeleteForm');
        const nameSpan = document.getElementById('deleteItemName');
        const pwdInput = document.getElementById('archivePasswordInput');

        nameSpan.textContent = name;
        form.action = `/archive/force-delete/${type}/${id}`;
        pwdInput.value = '';
        modal.classList.remove('hidden');
        if (window.lucide) window.lucide.createIcons();
        setTimeout(() => pwdInput.focus(), 100);
    }

    function closePermanentDeleteModal() {
        document.getElementById('permanentDeleteModal').classList.add('hidden');
        document.getElementById('archivePasswordInput').value = '';
    }

    // Close modal on backdrop click
    document.getElementById('permanentDeleteModal').addEventListener('click', function(e) {
        if (e.target === this) closePermanentDeleteModal();
    });
</script>
@endsection
