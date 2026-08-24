@extends('layouts.app')

@section('title', 'Inventory Management - Euro System')
@section('page-heading', 'Inventory Management')
@section('page-subheading', 'Manage spare parts, stock levels, suppliers, and purchase history')

@section('content')
<div class="w-full space-y-6">
    <!-- ── 3D KPI SUMMARY CARDS ── -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
        <!-- Card 1: Total Parts -->
        <div class="group relative overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-br from-white via-blue-50/40 to-indigo-50/20 p-5 sm:p-6 shadow-xs hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between gap-4 relative z-10">
                <div class="min-w-0 flex-1">
                    <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-400 block mb-1">
                        Total Parts
                    </span>
                    <div class="text-2xl sm:text-3xl font-black text-slate-900 leading-none tracking-tight tabular-nums truncate">
                        {{ number_format($totalParts) }}
                    </div>
                    <div class="mt-2 flex items-center gap-1.5 text-[11px] font-bold text-blue-600">
                        <span class="inline-flex h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                        <span>Cataloged Items</span>
                    </div>
                </div>
                <div class="w-14 h-14 sm:w-16 sm:h-16 shrink-0 group-hover:scale-105 transition-transform">
                    <img src="{{ asset('image/kpi/inv_parts_3d.svg') }}" alt="Total Parts" class="w-full h-full object-contain filter drop-shadow-md">
                </div>
            </div>
        </div>

        <!-- Card 2: Total Stock Value -->
        <div class="group relative overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-br from-white via-emerald-50/40 to-teal-50/20 p-5 sm:p-6 shadow-xs hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between gap-4 relative z-10">
                <div class="min-w-0 flex-1">
                    <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-400 block mb-1">
                        Total Stock Value
                    </span>
                    <div class="text-2xl sm:text-3xl font-black text-emerald-600 leading-none tracking-tight tabular-nums truncate">
                        ₱{{ number_format($totalStockValue, 2) }}
                    </div>
                    <div class="mt-2 flex items-center gap-1.5 text-[11px] font-bold text-emerald-600">
                        <span class="inline-flex h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        <span>Current Inventory Worth</span>
                    </div>
                </div>
                <div class="w-14 h-14 sm:w-16 sm:h-16 shrink-0 group-hover:scale-105 transition-transform">
                    <img src="{{ asset('image/kpi/inv_value_3d.svg') }}" alt="Total Stock Value" class="w-full h-full object-contain filter drop-shadow-md">
                </div>
            </div>
        </div>

        <!-- Card 3: Out of Stock -->
        <div class="group relative overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-br from-white via-rose-50/40 to-red-50/20 p-5 sm:p-6 shadow-xs hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between gap-4 relative z-10">
                <div class="min-w-0 flex-1">
                    <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-400 block mb-1">
                        Out of Stock
                    </span>
                    <div class="text-2xl sm:text-3xl font-black {{ $outOfStock > 0 ? 'text-rose-600' : 'text-slate-900' }} leading-none tracking-tight tabular-nums truncate">
                        {{ number_format($outOfStock) }}
                    </div>
                    <div class="mt-2 flex items-center gap-1.5 text-[11px] font-bold {{ $outOfStock > 0 ? 'text-rose-600' : 'text-slate-400' }}">
                        <span class="relative flex h-2 w-2">
                            @if($outOfStock > 0)
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                            @endif
                            <span class="relative inline-flex rounded-full h-2 w-2 {{ $outOfStock > 0 ? 'bg-rose-500' : 'bg-slate-300' }}"></span>
                        </span>
                        <span>{{ $outOfStock > 0 ? 'Requires Restocking' : 'All In Stock' }}</span>
                    </div>
                </div>
                <div class="w-14 h-14 sm:w-16 sm:h-16 shrink-0 group-hover:scale-105 transition-transform">
                    <img src="{{ asset('image/kpi/inv_alert_3d.svg') }}" alt="Out of Stock" class="w-full h-full object-contain filter drop-shadow-md">
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs & Actions -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row items-center justify-between p-5 gap-4">
        <div class="flex gap-2 w-full md:w-auto overflow-x-auto custom-scrollbar pb-2 md:pb-0">
            <button onclick="switchTab('active')" id="tab-active" class="inventory-tab px-5 py-2.5 rounded-xl text-sm font-bold bg-gray-900 text-white transition-all shadow-sm whitespace-nowrap">
                Active Parts
            </button>
            <button onclick="switchTab('history')" id="tab-history" class="inventory-tab px-5 py-2.5 rounded-xl text-sm font-bold bg-white text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-all whitespace-nowrap">
                Purchase History
            </button>
            <button onclick="switchTab('archived')" id="tab-archived" class="inventory-tab px-5 py-2.5 rounded-xl text-sm font-bold bg-white text-red-600 hover:bg-red-50 hover:text-red-700 transition-all whitespace-nowrap">
                Archived
            </button>
        </div>
        
        <div class="flex gap-3 w-full md:w-auto">
            <button onclick="openSuppliersModal()" class="flex-1 md:flex-none px-5 py-2.5 bg-purple-50 text-purple-700 font-bold rounded-xl text-sm hover:bg-purple-100 transition-all flex items-center justify-center gap-2">
                <i data-lucide="book-user" class="w-4 h-4"></i> Suppliers
            </button>
            <button onclick="openPartMiniModal()" class="flex-1 md:flex-none px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl text-sm hover:bg-blue-700 transition-all shadow-md shadow-blue-200 flex items-center justify-center gap-2 hover:-translate-y-0.5">
                <i data-lucide="plus" class="w-4 h-4"></i> Add Part
            </button>
        </div>
    </div>

    <!-- Content Sections -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative min-h-[500px]">
        <!-- Search bar (Only for Active and Archived) -->
        <div id="searchContainer" class="p-5 border-b border-gray-100 bg-gray-50/50">
            <div class="relative max-w-lg">
                <!-- Fake hidden inputs to trap aggressive Chrome autofill -->
                <input type="text" style="display:none" aria-hidden="true">
                <input type="password" style="display:none" aria-hidden="true">
                
                <i data-lucide="search" class="absolute left-4 top-3.5 w-5 h-5 text-gray-400"></i>
                <input type="text" role="presentation" autocomplete="off" data-lpignore="true" data-form-type="other" id="partsSearchInput" oninput="filterTables()" placeholder="Search by part name or supplier..." 
                    class="w-full pl-12 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all shadow-sm font-medium">
            </div>
        </div>

        <!-- 1. Active Parts -->
        <div id="section-active" class="inventory-section">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-8 py-4 text-left text-[11px] font-black text-gray-400 uppercase tracking-widest">Part Name</th>
                            <th class="px-8 py-4 text-left text-[11px] font-black text-gray-400 uppercase tracking-widest">Supplier</th>
                            <th class="px-8 py-4 text-right text-[11px] font-black text-gray-400 uppercase tracking-widest">Price (₱)</th>
                            <th class="px-8 py-4 text-center text-[11px] font-black text-gray-400 uppercase tracking-widest">In Stock</th>
                            <th class="px-8 py-4 text-right text-[11px] font-black text-gray-400 uppercase tracking-widest">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="activePartsTable" class="divide-y divide-gray-50/80">
                        <tr><td colspan="5" class="text-center py-12 text-gray-400"><i data-lucide="loader-2" class="w-6 h-6 animate-spin mx-auto mb-3"></i>Loading inventory...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2. Purchase History -->
        <div id="section-history" class="inventory-section hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-8 py-4 text-left text-[11px] font-black text-gray-400 uppercase tracking-widest">Date</th>
                            <th class="px-8 py-4 text-left text-[11px] font-black text-gray-400 uppercase tracking-widest">Description</th>
                            <th class="px-8 py-4 text-right text-[11px] font-black text-gray-400 uppercase tracking-widest">Amount</th>
                        </tr>
                    </thead>
                    <tbody id="historyTable" class="divide-y divide-gray-50/80">
                        <tr><td colspan="3" class="text-center py-12 text-gray-400"><i data-lucide="loader-2" class="w-6 h-6 animate-spin mx-auto mb-3"></i>Loading history...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 3. Archived Parts -->
        <div id="section-archived" class="inventory-section hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-8 py-4 text-left text-[11px] font-black text-gray-400 uppercase tracking-widest">Part Name</th>
                            <th class="px-8 py-4 text-left text-[11px] font-black text-gray-400 uppercase tracking-widest">Supplier</th>
                            <th class="px-8 py-4 text-right text-[11px] font-black text-gray-400 uppercase tracking-widest">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="archivedTable" class="divide-y divide-gray-50/80">
                        <tr><td colspan="3" class="text-center py-12 text-gray-400"><i data-lucide="loader-2" class="w-6 h-6 animate-spin mx-auto mb-3"></i>Loading archived parts...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->

<!-- 1. Add/Edit Part Modal -->
<div id="partMiniModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 overflow-hidden">
        <div class="flex justify-between items-start mb-5">
            <div class="flex items-center gap-3">
                <div id="miniModalIcon" class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                    <i data-lucide="plus" class="w-5 h-5 text-blue-600"></i>
                </div>
                <div>
                    <h3 id="miniModalTitle" class="text-lg font-black text-gray-900 leading-tight">Add New Part</h3>
                    <p id="miniModalSubtitle" class="text-xs text-gray-500 font-medium">Create a new item in the spare parts catalog</p>
                </div>
            </div>
            <button onclick="closePartMiniModal()" class="text-gray-400 hover:text-gray-600 p-1">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <div class="space-y-4">
            <input type="hidden" id="newPartId">
            <input type="hidden" id="newPartCurrentStock" value="0">

            <!-- AI Live Auto-Identification Preview -->
            <div id="aiPartDetectorPreview" class="p-3.5 rounded-2xl border transition-all duration-300 bg-slate-50 border-slate-200/80 flex items-center gap-3.5 shadow-xs">
                <div id="aiDetectorIconBox" class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 border bg-white border-slate-200 shadow-xs transition-all">
                    <!-- SVG rendered dynamically -->
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[9px] font-black uppercase tracking-widest text-blue-600 flex items-center gap-1">
                            <i data-lucide="sparkles" class="w-3 h-3 text-blue-500"></i> AI Auto-Identified
                        </span>
                        <span id="aiConfidenceBadge" class="text-[9px] font-bold text-slate-400">Live Detector</span>
                    </div>
                    <div id="aiDetectorCategoryName" class="text-xs font-black text-slate-800 tracking-tight mt-0.5 truncate">
                        Auto Component
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Part Name <span class="text-red-500">*</span></label>
                <input type="text" id="newPartName" maxlength="35" oninput="updateAIMiniModalPreview(this.value)" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-gray-50 focus:bg-white transition-colors" placeholder="e.g. Shock Absorber (Front), Brake Pad, Air Filter...">
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Price (₱) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" id="newPartPrice" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-gray-50 focus:bg-white transition-colors" placeholder="0.00">
                </div>
                <div>
                    <label id="lblQtyMode" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Initial Qty</label>
                    <input type="number" id="newPartQty" min="0" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-gray-50 focus:bg-white transition-colors" placeholder="0">
                    <p id="qtyError" class="hidden text-xs text-red-500 mt-1 font-medium">Cannot reduce stock here. Only add.</p>
                </div>
            </div>
            
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Supplier (Optional)</label>
                <select id="newPartSupplier" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-gray-50 focus:bg-white transition-colors cursor-pointer">
                    <option value="">-- No Supplier --</option>
                    @foreach(\App\Models\Supplier::orderBy('name')->get() as $s)
                        <option value="{{ $s->name }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="pt-2 flex gap-3">
                <button onclick="closePartMiniModal()" class="flex-1 py-3 text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Cancel</button>
                <button onclick="saveNewPart()" class="flex-1 py-3 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md shadow-blue-200 transition-colors flex justify-center items-center gap-2">
                    <i data-lucide="check" class="w-4 h-4"></i> <span id="txtSavePart">Save Part</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Suppliers Modal -->
<div id="suppliersModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 max-h-[85vh] flex flex-col">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-xl font-black text-gray-900">Manage Suppliers</h3>
                <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mt-0.5">Directory of Parts Sources</p>
            </div>
            <button onclick="closeSuppliersModal()" class="p-2 hover:bg-gray-50 rounded-full transition text-gray-400">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <div class="flex flex-col gap-3 mb-6 p-4 bg-gray-50 rounded-xl border border-gray-100">
            <input type="hidden" id="supplierId">
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Supplier Name</label>
                <input type="text" id="supplierName" maxlength="35" placeholder="Enter supplier name..." 
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-white shadow-sm">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Contact Person</label>
                    <input type="text" id="supplierContact" maxlength="25" placeholder="Full name" 
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-white shadow-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Phone Number</label>
                    <input type="text" id="supplierPhone" maxlength="11" placeholder="09xxxxxxxxx" 
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-white shadow-sm">
                </div>
            </div>
            <div class="flex gap-2 mt-1">
                <button id="btnSaveSupplier" onclick="saveSupplier()" class="flex-1 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-bold transition flex items-center justify-center gap-2 shadow-sm">
                    <i data-lucide="save" class="w-4 h-4"></i> <span id="txtSaveSupplier">Save Supplier</span>
                </button>
                <button id="btnCancelSupplierEdit" onclick="resetSupplierForm()" class="hidden px-4 py-2 bg-gray-200 text-gray-600 rounded-lg hover:bg-gray-300 text-sm font-bold transition">
                    Cancel
                </button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto custom-scrollbar pr-2">
            <table class="min-w-full divide-y divide-gray-100">
                <tbody id="suppliersTableBody" class="divide-y divide-gray-50">
                    <tr><td class="text-center py-4 text-gray-400 text-sm">Loading suppliers...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let activeParts = [];
    let archivedParts = [];
    let purchaseHistory = [];
    let suppliersList = [];
    let currentTab = 'active';

    document.addEventListener('DOMContentLoaded', () => {
        loadActiveParts();
        loadSuppliers();
        if(typeof lucide !== 'undefined') lucide.createIcons();

        // Phone Number format
        const sPhone = document.getElementById('supplierPhone');
        if (sPhone) {
            sPhone.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').substring(0, 11);
            });
        }
    });

    function showToast(message, type = 'success') {
        const container = document.getElementById('appContentArea');
        if (!container) return;
        const toast = document.createElement('div');
        toast.className = `alert-slide mb-4 p-4 rounded-lg border flex items-center gap-3 shadow-md transform transition-all duration-300 ${
            type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800'
        }`;
        toast.innerHTML = `
            <i data-lucide="${type === 'success' ? 'check-circle' : 'alert-circle'}" class="w-5 h-5 flex-shrink-0"></i>
            <div class="flex-1 font-bold text-sm tracking-tight">${message}</div>
            <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        `;
        container.prepend(toast);
        lucide.createIcons();
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    function switchTab(tab) {
        currentTab = tab;
        document.querySelectorAll('.inventory-tab').forEach(el => {
            el.className = 'inventory-tab px-4 py-2 rounded-lg text-sm font-bold bg-white text-gray-600 hover:bg-gray-50 transition whitespace-nowrap';
        });
        document.querySelectorAll('.inventory-section').forEach(el => el.classList.add('hidden'));
        
        let activeClass = '';
        if (tab === 'active') activeClass = 'bg-gray-900 text-white';
        else if (tab === 'history') activeClass = 'bg-blue-600 text-white';
        else if (tab === 'archived') activeClass = 'bg-red-600 text-white';

        document.getElementById(`tab-${tab}`).className = `inventory-tab px-4 py-2 rounded-lg text-sm font-bold transition whitespace-nowrap ${activeClass}`;
        document.getElementById(`section-${tab}`).classList.remove('hidden');

        // Search container visibility
        document.getElementById('searchContainer').classList.toggle('hidden', tab === 'history');

        if (tab === 'history' && purchaseHistory.length === 0) loadHistory();
        if (tab === 'archived' && archivedParts.length === 0) loadArchivedParts();
    }

    function filterTables() {
        const query = document.getElementById('partsSearchInput').value.toLowerCase();
        
        if (currentTab === 'active') {
            const filtered = activeParts.filter(p => (p.name || '').toLowerCase().includes(query) || (p.supplier || '').toLowerCase().includes(query));
            renderActiveParts(filtered);
        } else if (currentTab === 'archived') {
            const filtered = archivedParts.filter(p => (p.name || '').toLowerCase().includes(query) || (p.supplier || '').toLowerCase().includes(query));
            renderArchivedParts(filtered);
        }
    }

    // --- Loading Data ---
    async function loadActiveParts() {
        try {
            const res = await fetch("{{ route('spare-parts.index') }}");
            const result = await res.json();
            if (result.success) {
                activeParts = result.data;
                renderActiveParts(activeParts);
            }
        } catch(e) { console.error(e); }
    }

    async function loadHistory() {
        try {
            const res = await fetch("{{ route('spare-parts.history') }}");
            const result = await res.json();
            if (result.success) {
                purchaseHistory = result.data;
                renderHistory(purchaseHistory);
            }
        } catch(e) { console.error(e); }
    }

    async function loadArchivedParts() {
        try {
            const res = await fetch("{{ route('spare-parts.archived') }}");
            const result = await res.json();
            if (result.success) {
                archivedParts = result.data;
                renderArchivedParts(archivedParts);
            }
        } catch(e) { console.error(e); }
    }

    async function loadSuppliers() {
        try {
            const res = await fetch("{{ route('suppliers.index') }}");
            const result = await res.json();
            if(result.success) {
                suppliersList = result.data;
                renderSuppliers(suppliersList);
            }
        } catch(e) { console.error(e); }
    }

    // ── AI Smart Auto Part Classifier & SVG Visual Generator ──
    function getPartAIMeta(name = '') {
        const raw = (name || '').toLowerCase().trim();

        // 1. Suspension & Steering (Shocks, struts, coil springs, bushings, tie rods, ball joints, control arms)
        if (/shock|absorber|strut|suspension|spring|coil|bushing|stabilizer|tie\s*rod|ball\s*joint|rack\s*end|control\s*arm|sway\s*bar|end\s*link|knuckle|crossmember|strut\s*mount/i.test(raw)) {
            return {
                category: 'Suspension & Steering',
                badgeBg: 'bg-gradient-to-br from-amber-50 to-orange-50/80',
                badgeBorder: 'border-amber-200/90',
                textClass: 'text-amber-600',
                dotClass: 'bg-amber-500',
                glowClass: 'group-hover:border-amber-400',
                svg: `<svg class="w-6 h-6 text-amber-500 shrink-0 drop-shadow-xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="3.5" r="1.8"></circle>
                        <line x1="12" y1="5.3" x2="12" y2="8"></line>
                        <rect x="9.5" y="8" width="5" height="3" rx="0.8" fill="currentColor" fill-opacity="0.25"></rect>
                        <path d="M10 11l4 1.8-4 1.8 4 1.8-4 1.8 4 1.8"></path>
                        <rect x="9" y="19" width="6" height="2" rx="0.5"></rect>
                        <circle cx="12" cy="22" r="1.5"></circle>
                      </svg>`
            };
        }

        // 2. Braking System (Pads, rotors, caliper, shoe, disk, disc, hose, handbrake, fluid)
        if (/brake|rotor|caliper|pad|shoe|disk|disc|hose|handbrake|drum\s*brake|abs\s*sensor/i.test(raw)) {
            return {
                category: 'Braking System',
                badgeBg: 'bg-gradient-to-br from-rose-50 to-red-50/80',
                badgeBorder: 'border-rose-200/90',
                textClass: 'text-rose-600',
                dotClass: 'bg-rose-500',
                glowClass: 'group-hover:border-rose-400',
                svg: `<svg class="w-6 h-6 text-rose-500 shrink-0 drop-shadow-xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9"></circle>
                        <circle cx="12" cy="12" r="3.5"></circle>
                        <circle cx="12" cy="6.5" r="1" fill="currentColor"></circle>
                        <circle cx="12" cy="17.5" r="1" fill="currentColor"></circle>
                        <circle cx="6.5" cy="12" r="1" fill="currentColor"></circle>
                        <circle cx="17.5" cy="12" r="1" fill="currentColor"></circle>
                        <path d="M3.5 8.5a9 9 0 0 1 7.5-5V9l-3.5 1.5z" fill="currentColor" fill-opacity="0.25"></path>
                      </svg>`
            };
        }

        // 3. Filtration System (Air filter, cabin filter, oil filter, fuel filter, AC filter)
        if (/filter|intake\s*filter|air\s*cleaner/i.test(raw)) {
            return {
                category: 'Filtration System',
                badgeBg: 'bg-gradient-to-br from-sky-50 to-blue-50/80',
                badgeBorder: 'border-sky-200/90',
                textClass: 'text-sky-600',
                dotClass: 'bg-sky-500',
                glowClass: 'group-hover:border-sky-400',
                svg: `<svg class="w-6 h-6 text-sky-500 shrink-0 drop-shadow-xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="4" y="4" width="16" height="16" rx="3"></rect>
                        <line x1="4" y1="8" x2="20" y2="8"></line>
                        <line x1="4" y1="12" x2="20" y2="12"></line>
                        <line x1="4" y1="16" x2="20" y2="16"></line>
                        <line x1="8" y1="4" x2="8" y2="20"></line>
                        <line x1="12" y1="4" x2="12" y2="20"></line>
                        <line x1="16" y1="4" x2="16" y2="20"></line>
                      </svg>`
            };
        }

        // 4. Fluids & Lubricants (Engine oil, ATF, CVT, gear oil, coolant, grease)
        if (/fluid|oil|atf|cvt|coolant|grease|lubricant|synthetic|mineral|petroleum|steering\s*fluid/i.test(raw)) {
            return {
                category: 'Fluids & Lubricants',
                badgeBg: 'bg-gradient-to-br from-emerald-50 to-teal-50/80',
                badgeBorder: 'border-emerald-200/90',
                textClass: 'text-emerald-600',
                dotClass: 'bg-emerald-500',
                glowClass: 'group-hover:border-emerald-400',
                svg: `<svg class="w-6 h-6 text-emerald-500 shrink-0 drop-shadow-xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M7 4h10v3l-2 3v10a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2V10L5 7V4h2z"></path>
                        <path d="M12 12a2 2 0 0 0-2 2c0 1.5 2 3.5 2 3.5s2-2 2-3.5a2 2 0 0 0-2-2z" fill="currentColor"></path>
                        <line x1="9" y1="2" x2="15" y2="2"></line>
                      </svg>`
            };
        }

        // 5. Electrical & Lighting (Battery, alternator, starter, bulb, headlight, fuse, relay, horn)
        if (/battery|alternator|starter|bulb|headlight|taillight|signal|fuse|relay|horn|sensor|wire|harness|switch|ecu/i.test(raw)) {
            return {
                category: 'Electrical & Lighting',
                badgeBg: 'bg-gradient-to-br from-violet-50 to-purple-50/80',
                badgeBorder: 'border-violet-200/90',
                textClass: 'text-violet-600',
                dotClass: 'bg-violet-500',
                glowClass: 'group-hover:border-violet-400',
                svg: `<svg class="w-6 h-6 text-violet-500 shrink-0 drop-shadow-xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="6" width="18" height="15" rx="3"></rect>
                        <line x1="6" y1="3" x2="8" y2="6"></line>
                        <line x1="16" y1="3" x2="18" y2="6"></line>
                        <line x1="7" y1="11" x2="11" y2="11"></line>
                        <line x1="9" y1="9" x2="9" y2="13"></line>
                        <line x1="14" y1="11" x2="17" y2="11"></line>
                      </svg>`
            };
        }

        // 6. Engine, Ignition & Belts (Spark plugs, ignition coil, belts, pistons, gaskets, valves)
        if (/spark|plug|ignition|piston|belt|timing|serpentine|gasket|valve|cylinder|camshaft|crankshaft|engine|motor|injector/i.test(raw)) {
            return {
                category: 'Engine & Ignition',
                badgeBg: 'bg-gradient-to-br from-orange-50 to-amber-50/80',
                badgeBorder: 'border-orange-200/90',
                textClass: 'text-orange-600',
                dotClass: 'bg-orange-500',
                glowClass: 'group-hover:border-orange-400',
                svg: `<svg class="w-6 h-6 text-orange-500 shrink-0 drop-shadow-xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10 2h4v3h-4z"></path>
                        <rect x="9" y="5" width="6" height="5" rx="1"></rect>
                        <path d="M11 10v4l-3 3 1 1 3-3v4h2v-4l3 3 1-1-3-3v-4h-4z" fill="currentColor" fill-opacity="0.25"></path>
                        <line x1="12" y1="18" x2="12" y2="22"></line>
                      </svg>`
            };
        }

        // 7. Cooling & Climate (Radiator, water pump, thermostat, fan, AC compressor, condenser)
        if (/radiator|cool|thermostat|water\s*pump|compressor|condenser|evaporator|blower|heater|fan/i.test(raw)) {
            return {
                category: 'Cooling & Climate',
                badgeBg: 'bg-gradient-to-br from-cyan-50 to-teal-50/80',
                badgeBorder: 'border-cyan-200/90',
                textClass: 'text-cyan-600',
                dotClass: 'bg-cyan-500',
                glowClass: 'group-hover:border-cyan-400',
                svg: `<svg class="w-6 h-6 text-cyan-500 shrink-0 drop-shadow-xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                        <line x1="7" y1="9" x2="7" y2="15"></line>
                        <line x1="10" y1="9" x2="10" y2="15"></line>
                        <line x1="14" y1="9" x2="14" y2="15"></line>
                        <line x1="17" y1="9" x2="17" y2="15"></line>
                        <path d="M12 2v3m0 14v3"></path>
                      </svg>`
            };
        }

        // 8. Tires, Wheels & Hubs
        if (/tire|tyre|wheel|rim|bearing|hub|lug\s*nut|valve\s*stem/i.test(raw)) {
            return {
                category: 'Tires & Wheels',
                badgeBg: 'bg-gradient-to-br from-slate-100 to-zinc-100',
                badgeBorder: 'border-slate-300',
                textClass: 'text-slate-700',
                dotClass: 'bg-slate-600',
                glowClass: 'group-hover:border-slate-400',
                svg: `<svg class="w-6 h-6 text-slate-700 shrink-0 drop-shadow-xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9.5"></circle>
                        <circle cx="12" cy="12" r="5"></circle>
                        <circle cx="12" cy="12" r="2" fill="currentColor"></circle>
                        <line x1="12" y1="2.5" x2="12" y2="4.5"></line>
                        <line x1="12" y1="19.5" x2="12" y2="21.5"></line>
                        <line x1="2.5" y1="12" x2="4.5" y2="12"></line>
                        <line x1="19.5" y1="12" x2="21.5" y2="12"></line>
                      </svg>`
            };
        }

        // 9. Drivetrain & Transmission
        if (/clutch|flywheel|axle|cv\s*joint|drive\s*shaft|differential|transmission|gearbox|gear/i.test(raw)) {
            return {
                category: 'Drivetrain & Transmission',
                badgeBg: 'bg-gradient-to-br from-indigo-50 to-blue-50/80',
                badgeBorder: 'border-indigo-200/90',
                textClass: 'text-indigo-600',
                dotClass: 'bg-indigo-500',
                glowClass: 'group-hover:border-indigo-400',
                svg: `<svg class="w-6 h-6 text-indigo-500 shrink-0 drop-shadow-xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="9" r="4.5"></circle>
                        <circle cx="16" cy="16" r="4.5"></circle>
                        <circle cx="9" cy="9" r="1.5" fill="currentColor"></circle>
                        <circle cx="16" cy="16" r="1.5" fill="currentColor"></circle>
                        <path d="M9 3v2m0 8v2M3 9h2m8 0h2m1 7v2m0 8v2m-6-3h2m8 0h2"></path>
                      </svg>`
            };
        }

        // 10. Body, Glass & Wipers
        if (/wiper|blade|mirror|door|bumper|windshield|glass|handle|fender|hood|trunk|latch|lock/i.test(raw)) {
            return {
                category: 'Body, Glass & Wipers',
                badgeBg: 'bg-gradient-to-br from-blue-50 to-indigo-50/80',
                badgeBorder: 'border-blue-200/90',
                textClass: 'text-blue-600',
                dotClass: 'bg-blue-500',
                glowClass: 'group-hover:border-blue-400',
                svg: `<svg class="w-6 h-6 text-blue-500 shrink-0 drop-shadow-xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19l14-14"></path>
                        <path d="M18 5l2 2-14 14-2-2z" fill="currentColor" fill-opacity="0.25"></path>
                        <circle cx="4" cy="20" r="1.8"></circle>
                        <path d="M5 9a14 14 0 0 1 14 0" stroke-dasharray="2 2"></path>
                      </svg>`
            };
        }

        // 11. Hardware & Fasteners
        if (/bolt|nut|screw|clip|clamp|bracket|washer|pin|fitting/i.test(raw)) {
            return {
                category: 'Hardware & Fasteners',
                badgeBg: 'bg-gradient-to-br from-slate-50 to-gray-100',
                badgeBorder: 'border-slate-200/90',
                textClass: 'text-slate-600',
                dotClass: 'bg-slate-500',
                glowClass: 'group-hover:border-slate-400',
                svg: `<svg class="w-6 h-6 text-slate-600 shrink-0 drop-shadow-xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 4h6v3l-1 1v12h-4V8L9 7V4z"></path>
                        <line x1="7" y1="4" x2="17" y2="4"></line>
                        <line x1="9" y1="11" x2="15" y2="11"></line>
                        <line x1="9" y1="14" x2="15" y2="14"></line>
                      </svg>`
            };
        }

        // 12. Default Precision Auto Part / Component
        return {
            category: 'Auto Component',
            badgeBg: 'bg-gradient-to-br from-slate-50 to-blue-50/60',
            badgeBorder: 'border-slate-200/90',
            textClass: 'text-slate-600',
            dotClass: 'bg-blue-500',
            glowClass: 'group-hover:border-blue-400',
            svg: `<svg class="w-6 h-6 text-blue-500 shrink-0 drop-shadow-xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                  </svg>`
        };
    }

    // ── Live AI Preview Update in Add/Edit Part Modal ──
    function updateAIMiniModalPreview(name) {
        const meta = getPartAIMeta(name);
        const iconBox = document.getElementById('aiDetectorIconBox');
        const catLabel = document.getElementById('aiDetectorCategoryName');
        const previewContainer = document.getElementById('aiPartDetectorPreview');
        const confidenceBadge = document.getElementById('aiConfidenceBadge');

        if (iconBox) {
            iconBox.className = `w-12 h-12 rounded-xl flex items-center justify-center shrink-0 border ${meta.badgeBorder} ${meta.badgeBg} shadow-xs transition-all duration-300 transform scale-100 hover:scale-105`;
            iconBox.innerHTML = meta.svg;
        }

        if (catLabel) {
            catLabel.innerText = meta.category;
            catLabel.className = `text-xs font-black ${meta.textClass} tracking-tight mt-0.5 truncate`;
        }

        if (previewContainer) {
            previewContainer.className = `p-3.5 rounded-2xl border transition-all duration-300 ${meta.badgeBg} ${meta.badgeBorder} flex items-center gap-3.5 shadow-xs`;
        }

        if (confidenceBadge) {
            confidenceBadge.innerText = name && name.trim().length > 1 ? '✨ AI Identified' : 'Type part name...';
            confidenceBadge.className = name && name.trim().length > 1 ? `text-[9px] font-black uppercase ${meta.textClass}` : 'text-[9px] font-bold text-slate-400';
        }
    }

    // --- Rendering Tables ---
    function renderActiveParts(data) {
        const tbody = document.getElementById('activePartsTable');
        if (data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center py-16 text-gray-500 text-sm font-medium">No parts found.</td></tr>`;
            return;
        }

        tbody.innerHTML = data.map(p => {
            const isOut = p.stock_quantity <= 0;
            const isLow = !isOut && p.stock_quantity <= 5;
            let badgeClass = 'bg-green-50 text-green-700';
            if (isOut) badgeClass = 'bg-red-50 text-red-600';
            else if (isLow) badgeClass = 'bg-yellow-50 text-yellow-600';

            const aiMeta = getPartAIMeta(p.name);

            return `
            <tr class="hover:bg-gray-50/80 transition-colors group">
                <td class="px-8 py-4">
                    <div class="flex items-center gap-3.5">
                        <div class="relative w-11 h-11 rounded-2xl flex items-center justify-center shrink-0 border ${aiMeta.badgeBorder} ${aiMeta.badgeBg} shadow-xs group-hover:scale-105 ${aiMeta.glowClass} transition-all">
                            ${aiMeta.svg}
                        </div>
                        <div class="min-w-0">
                            <div class="text-sm font-black text-gray-900 tracking-tight group-hover:text-blue-600 transition-colors truncate">${p.name}</div>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider ${aiMeta.textClass}">
                                    <span class="w-1.5 h-1.5 rounded-full ${aiMeta.dotClass}"></span>
                                    ${aiMeta.category}
                                </span>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-8 py-4">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider ${p.supplier ? 'bg-purple-50 text-purple-700' : 'bg-gray-100 text-gray-500'}">
                        ${p.supplier || 'Unspecified'}
                    </span>
                </td>
                <td class="px-8 py-4 text-right">
                    <div class="text-sm font-bold text-gray-900">₱${parseFloat(p.price).toFixed(2)}</div>
                </td>
                <td class="px-8 py-4 text-center">
                    <span class="inline-flex px-3 py-1.5 rounded-lg text-xs font-black ${badgeClass}">
                        ${p.stock_quantity}
                    </span>
                </td>
                <td class="px-8 py-4 text-right">
                    <div class="flex justify-end gap-2">
                        <button onclick="editPart(${p.id}, '${addslashes(p.name)}', ${p.price}, ${p.stock_quantity}, '${addslashes(p.supplier||'')}')" class="p-2 text-blue-600 hover:bg-blue-100 rounded-xl transition-all" title="Purchase / Edit Part">
                            <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                        </button>
                        <button onclick="archivePart(${p.id})" class="p-2 text-red-500 hover:bg-red-100 rounded-xl transition-all" title="Archive Part">
                            <i data-lucide="trash" class="w-4 h-4"></i>
                        </button>
                    </div>
                </td>
            </tr>
            `;
        }).join('');
        lucide.createIcons();
    }

    function renderHistory(data) {
        const tbody = document.getElementById('historyTable');
        if (data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="3" class="text-center py-16 text-gray-500 text-sm font-medium">No purchase records found.</td></tr>`;
            return;
        }

        tbody.innerHTML = data.map(ph => {
            const dateStr = new Date(ph.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            const aiMeta = getPartAIMeta(ph.description);

            return `
            <tr class="hover:bg-gray-50/80 transition-colors">
                <td class="px-8 py-5 whitespace-nowrap">
                    <div class="text-sm font-bold text-gray-700">${dateStr}</div>
                </td>
                <td class="px-8 py-5">
                    <div class="flex items-center gap-3.5">
                        <div class="relative w-10 h-10 rounded-xl flex items-center justify-center shrink-0 border ${aiMeta.badgeBorder} ${aiMeta.badgeBg} shadow-xs">
                            ${aiMeta.svg}
                        </div>
                        <div class="min-w-0">
                            <div class="text-sm font-black text-gray-800 tracking-tight">${ph.description}</div>
                            <div class="text-xs text-blue-500 font-bold uppercase mt-0.5 tracking-wider">${ph.category}</div>
                        </div>
                    </div>
                </td>
                <td class="px-8 py-5 text-right">
                    <div class="text-base font-black text-green-600">₱${parseFloat(ph.amount).toFixed(2)}</div>
                </td>
            </tr>
            `;
        }).join('');
    }

    function renderArchivedParts(data) {
        const tbody = document.getElementById('archivedTable');
        if (data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="3" class="text-center py-16 text-gray-500 text-sm font-medium">No archived parts found.</td></tr>`;
            return;
        }

        tbody.innerHTML = data.map(p => {
            const aiMeta = getPartAIMeta(p.name);
            return `
            <tr class="hover:bg-gray-50/80 transition-colors group">
                <td class="px-8 py-4">
                    <div class="flex items-center gap-3.5">
                        <div class="relative w-10 h-10 rounded-xl flex items-center justify-center shrink-0 border ${aiMeta.badgeBorder} ${aiMeta.badgeBg} shadow-xs">
                            ${aiMeta.svg}
                        </div>
                        <div class="min-w-0">
                            <div class="text-sm font-black text-gray-700 tracking-tight">${p.name}</div>
                            <div class="flex items-center gap-1 mt-0.5">
                                <span class="text-[10px] font-black uppercase tracking-wider ${aiMeta.textClass}">${aiMeta.category}</span>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-8 py-4 text-xs font-bold text-gray-400 uppercase">${p.supplier || 'Unspecified'}</td>
                <td class="px-8 py-4 text-right flex justify-end gap-2">
                    <button onclick="restorePart(${p.id})" title="Restore Item" class="p-2 text-green-600 hover:bg-green-100 rounded-xl transition-all">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    </button>
                    <button onclick="forceDeletePart(${p.id})" title="Delete Permanently" class="p-2 text-red-500 hover:bg-red-100 rounded-xl transition-all">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </td>
            </tr>
            `;
        }).join('');
        lucide.createIcons();
    }

    // --- Actions ---
    function openPartMiniModal(isEdit = false) {
        const modal = document.getElementById('partMiniModal');
        modal.classList.remove('hidden');
        document.getElementById('qtyError').classList.add('hidden');
        
        if (!isEdit) {
            document.getElementById('newPartId').value = '';
            document.getElementById('newPartName').value = '';
            document.getElementById('newPartPrice').value = '';
            document.getElementById('newPartQty').value = '';
            document.getElementById('newPartSupplier').value = '';
            
            document.getElementById('miniModalTitle').innerText = 'Add New Part';
            document.getElementById('miniModalSubtitle').innerText = 'Create a new item in the spare parts catalog';
            document.getElementById('lblQtyMode').innerText = 'Initial Qty';
            document.getElementById('txtSavePart').innerText = 'Save Part';
            document.getElementById('newPartName').readOnly = false;
            
            const iconContainer = document.getElementById('miniModalIcon');
            iconContainer.className = 'w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center';
            iconContainer.innerHTML = '<i data-lucide="plus" class="w-5 h-5 text-blue-600"></i>';
            updateAIMiniModalPreview('');
            lucide.createIcons();
        }
    }

    function closePartMiniModal() {
        document.getElementById('partMiniModal').classList.add('hidden');
    }

    function editPart(id, name, price, qty, supplier) {
        openPartMiniModal(true);
        document.getElementById('newPartId').value = id;
        document.getElementById('newPartCurrentStock').value = qty;
        document.getElementById('newPartName').value = name;
        document.getElementById('newPartPrice').value = price;
        document.getElementById('newPartQty').value = ''; 
        document.getElementById('newPartSupplier').value = supplier || '';

        document.getElementById('miniModalTitle').innerText = 'Purchase / Edit Part';
        document.getElementById('miniModalSubtitle').innerText = 'Add stock or update part details';
        document.getElementById('lblQtyMode').innerHTML = `Add Stock <span class="text-gray-400 font-normal ml-1">(Current: ${qty})</span>`;
        document.getElementById('txtSavePart').innerText = 'Save Changes';
        document.getElementById('newPartName').readOnly = true;

        const iconContainer = document.getElementById('miniModalIcon');
        iconContainer.className = 'w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center';
        iconContainer.innerHTML = '<i data-lucide="shopping-cart" class="w-5 h-5 text-blue-600"></i>';
        
        updateAIMiniModalPreview(name);
        lucide.createIcons();
    }

    async function saveNewPart() {
        const id = document.getElementById('newPartId').value;
        const name = document.getElementById('newPartName').value;
        const price = document.getElementById('newPartPrice').value;
        const qty_to_add = parseInt(document.getElementById('newPartQty').value) || 0;
        const supplier = document.getElementById('newPartSupplier').value;

        if(!name || !price) {
            showToast('Part Name and Price are required.', 'error');
            return;
        }

        if (id && qty_to_add < 0) {
            document.getElementById('qtyError').classList.remove('hidden');
            return;
        }

        try {
            const res = await fetch("{{ route('spare-parts.store') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ id, name, price, qty_to_add, supplier })
            });
            const result = await res.json();
            if(result.success) {
                showToast(result.message, 'success');
                closePartMiniModal();
                loadActiveParts();
                if(qty_to_add > 0) {
                    loadHistory();
                    setTimeout(() => window.location.reload(), 1500); // Reload to update metric cards
                }
            } else {
                showToast(result.message || 'Something went wrong.', 'error');
            }
        } catch(e) { console.error(e); }
    }

    async function archivePart(id) {
        if(!confirm('Are you sure you want to archive this part?')) return;
        try {
            const res = await fetch(`{{ url('spare-parts') }}/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            const result = await res.json();
            if(result.success) {
                showToast('Part archived successfully', 'success');
                loadActiveParts();
                loadArchivedParts();
                setTimeout(() => window.location.reload(), 1500);
            }
        } catch(e) { console.error(e); }
    }

    async function restorePart(id) {
        try {
            const res = await fetch(`{{ url('spare-parts/restore') }}/${id}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            const result = await res.json();
            if (result.success) {
                showToast(result.message, 'success');
                loadActiveParts();
                loadArchivedParts();
                setTimeout(() => window.location.reload(), 1500);
            }
        } catch(e) { console.error(e); }
    }

    async function forceDeletePart(id) {
        if (!confirm('🛑 WARNING: This will permanently delete the part record. Proceed?')) return;
        try {
            const res = await fetch(`{{ url('spare-parts/permanent') }}/${id}`, {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ archive_password: 'bypass' }) // If you use auth modal, adapt this
            });
            const result = await res.json();
            if (result.success) {
                showToast(result.message, 'success');
                loadArchivedParts();
            } else {
                showToast(result.message, 'error');
            }
        } catch(e) { console.error(e); }
    }

    // --- Suppliers Logic ---
    function openSuppliersModal() {
        document.getElementById('suppliersModal').classList.remove('hidden');
        resetSupplierForm();
    }
    function closeSuppliersModal() {
        document.getElementById('suppliersModal').classList.add('hidden');
    }
    function resetSupplierForm() {
        document.getElementById('supplierId').value = '';
        document.getElementById('supplierName').value = '';
        document.getElementById('supplierContact').value = '';
        document.getElementById('supplierPhone').value = '';
        document.getElementById('btnSaveSupplier').classList.remove('bg-yellow-600', 'hover:bg-yellow-700');
        document.getElementById('btnSaveSupplier').classList.add('bg-blue-600', 'hover:bg-blue-700');
        document.getElementById('txtSaveSupplier').innerText = 'Save Supplier';
        document.getElementById('btnCancelSupplierEdit').classList.add('hidden');
    }
    function renderSuppliers(data) {
        const tbody = document.getElementById('suppliersTableBody');
        if(data.length === 0) {
            tbody.innerHTML = `<tr><td class="text-center py-4 text-gray-400 text-sm">No suppliers added yet.</td></tr>`;
            return;
        }
        tbody.innerHTML = data.map(s => `
            <tr class="hover:bg-gray-50 transition group">
                <td class="py-3 pr-4">
                    <div class="text-sm font-black text-gray-800 tracking-tight">${s.name}</div>
                    ${(s.contact_person || s.phone_number) ? `<div class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">${s.contact_person || '—'} · ${s.phone_number || '—'}</div>` : ''}
                </td>
                <td class="py-3 text-right">
                    <div class="flex justify-end gap-2">
                        <button onclick="editSupplier(${s.id}, '${addslashes(s.name)}', '${addslashes(s.contact_person||'')}', '${s.phone_number||''}')" class="p-1.5 text-blue-400 hover:text-blue-600 hover:bg-blue-50 rounded transition">
                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                        </button>
                        <button onclick="deleteSupplier(${s.id})" class="p-1.5 text-red-300 hover:text-red-600 hover:bg-red-50 rounded transition">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
        lucide.createIcons();
        
        // Update new part dropdown
        const select = document.getElementById('newPartSupplier');
        if(select) {
            const currentVal = select.value;
            select.innerHTML = '<option value="">-- No Supplier --</option>' + data.map(s => `<option value="${s.name}">${s.name}</option>`).join('');
            select.value = currentVal;
        }
    }
    async function saveSupplier() {
        const id = document.getElementById('supplierId').value;
        const name = document.getElementById('supplierName').value;
        const contact = document.getElementById('supplierContact').value;
        const phone = document.getElementById('supplierPhone').value;
        if(!name) return showToast('Supplier name is required.', 'error');
        
        try {
            const res = await fetch("{{ route('suppliers.store') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ id, name, contact_person: contact, phone_number: phone })
            });
            const result = await res.json();
            if(result.success) {
                showToast(result.message, 'success');
                resetSupplierForm();
                loadSuppliers();
            } else {
                showToast(result.message, 'error');
            }
        } catch(e) { console.error(e); }
    }
    function editSupplier(id, name, contact, phone) {
        document.getElementById('supplierId').value = id;
        document.getElementById('supplierName').value = name;
        document.getElementById('supplierContact').value = contact;
        document.getElementById('supplierPhone').value = phone;
        document.getElementById('txtSaveSupplier').innerText = 'Update Supplier';
        document.getElementById('btnCancelSupplierEdit').classList.remove('hidden');
    }
    async function deleteSupplier(id) {
        if(!confirm('Are you sure you want to delete this supplier?')) return;
        try {
            const res = await fetch(`{{ url('suppliers') }}/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            const result = await res.json();
            if(result.success) {
                showToast(result.message, 'success');
                loadSuppliers();
            } else {
                showToast(result.message, 'error');
            }
        } catch(e) { console.error(e); }
    }

    function addslashes(str) {
        return (str + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
    }
</script>
@endpush
