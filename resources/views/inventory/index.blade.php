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

<!-- 1. Mini Modal for Quick Add / Restock Part -->
<div id="partMiniModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-start mb-4">
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
            <input type="hidden" id="newPartImageUrl" value="">

            <!-- AI Live Real-Time Vector Generator Preview -->
            <div id="aiPartDetectorPreview" class="p-4 rounded-2xl border transition-all duration-300 bg-gradient-to-br from-slate-50 to-blue-50/50 border-blue-100 flex items-center gap-4 shadow-xs">
                <div id="aiDetectorIconBox" class="w-16 h-16 rounded-2xl flex items-center justify-center shrink-0 border bg-white border-slate-200 shadow-sm transition-all cursor-pointer p-1.5 overflow-hidden hover:scale-105" onclick="openImageModal(document.getElementById('newPartImageUrl').value)" title="Click to view large preview">
                    <!-- SVG rendered dynamically in real-time -->
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[10px] font-black uppercase tracking-wider text-blue-600 flex items-center gap-1.5">
                            <i data-lucide="sparkles" class="w-3.5 h-3.5 text-blue-500"></i> AI Real-Time Vector Engine
                        </span>
                        <span id="aiConfidenceBadge" class="text-[9px] font-bold text-slate-400">Live Generator</span>
                    </div>
                    <div id="aiDetectorCategoryName" class="text-sm font-black text-slate-800 tracking-tight mt-0.5 truncate">
                        Auto Component
                    </div>
                    <div class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-1.5 font-medium">
                        <span id="imgSourceLabel" class="text-blue-600 font-bold">Auto-generated procedural SVG based on part name</span>
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Part Name <span class="text-red-500">*</span></label>
                <input type="text" id="newPartName" maxlength="100" oninput="onPartNameInput(this.value)" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-gray-50 focus:bg-white transition-colors" placeholder="e.g. Shock Absorber (Front), Brake Pad, Window Glass, Side Mirror, Exhaust...">
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

<script>
    let activeParts = [];
    let archivedParts = [];
    let purchaseHistory = [];
    let suppliersList = [];
    let currentTab = 'active';

    function initInventoryPage() {
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
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initInventoryPage);
    } else {
        initInventoryPage();
    }

    function showToast(message, type = 'success') {
        const container = document.getElementById('appContentArea') || document.body;
        if (!container) return;
        const toast = document.createElement('div');
        
        let colorClasses = 'bg-green-50 border-green-200 text-green-800';
        let iconName = 'check-circle';
        if (type === 'error') {
            colorClasses = 'bg-red-50 border-red-200 text-red-800';
            iconName = 'alert-circle';
        } else if (type === 'info') {
            colorClasses = 'bg-blue-50 border-blue-200 text-blue-800';
            iconName = 'info';
        }

        toast.className = `alert-slide mb-3 p-3 rounded-xl border flex items-center gap-2.5 shadow-sm transform transition-all duration-300 ${colorClasses}`;
        toast.innerHTML = `
            <i data-lucide="${iconName}" class="w-4 h-4 flex-shrink-0"></i>
            <div class="flex-1 font-bold text-xs tracking-tight">${message}</div>
            <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-3.5 h-3.5"></i>
            </button>
        `;
        container.prepend(toast);
        if (typeof lucide !== 'undefined') lucide.createIcons();
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

        const tabBtn = document.getElementById(`tab-${tab}`);
        if (tabBtn) tabBtn.className = `inventory-tab px-4 py-2 rounded-lg text-sm font-bold transition whitespace-nowrap ${activeClass}`;
        const sec = document.getElementById(`section-${tab}`);
        if (sec) sec.classList.remove('hidden');

        // Search container visibility
        const searchBox = document.getElementById('searchContainer');
        if (searchBox) searchBox.classList.toggle('hidden', tab === 'history');

        if (tab === 'history' && purchaseHistory.length === 0) loadHistory();
        if (tab === 'archived' && archivedParts.length === 0) loadArchivedParts();
    }

    function filterTables() {
        const input = document.getElementById('partsSearchInput');
        const query = input ? input.value.toLowerCase() : '';
        
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
        const tbody = document.getElementById('activePartsTable');
        try {
            const res = await fetch("{{ route('spare-parts.index') }}");
            if (!res.ok) throw new Error('HTTP ' + res.status);
            const result = await res.json();
            if (result.success) {
                activeParts = result.data || [];
                renderActiveParts(activeParts);
            } else {
                throw new Error(result.message || 'Error loading inventory');
            }
        } catch(e) { 
            console.error('loadActiveParts error:', e);
            if (tbody) {
                tbody.innerHTML = `<tr><td colspan="5" class="text-center py-12 text-red-500 font-bold text-sm">Failed to load inventory. <button onclick="loadActiveParts()" class="ml-2 underline text-blue-600 font-bold cursor-pointer">Click to Retry</button></td></tr>`;
            }
        }
    }

    async function loadHistory() {
        const tbody = document.getElementById('historyTable');
        try {
            const res = await fetch("{{ route('spare-parts.history') }}");
            if (!res.ok) throw new Error('HTTP ' + res.status);
            const result = await res.json();
            if (result.success) {
                purchaseHistory = result.data || [];
                renderHistory(purchaseHistory);
            }
        } catch(e) { 
            console.error('loadHistory error:', e);
            if (tbody) {
                tbody.innerHTML = `<tr><td colspan="3" class="text-center py-12 text-red-500 font-bold text-sm">Failed to load purchase history. <button onclick="loadHistory()" class="ml-2 underline text-blue-600 font-bold cursor-pointer">Retry</button></td></tr>`;
            }
        }
    }

    async function loadArchivedParts() {
        const tbody = document.getElementById('archivedTable');
        try {
            const res = await fetch("{{ route('spare-parts.archived') }}");
            if (!res.ok) throw new Error('HTTP ' + res.status);
            const result = await res.json();
            if (result.success) {
                archivedParts = result.data || [];
                renderArchivedParts(archivedParts);
            }
        } catch(e) { 
            console.error('loadArchivedParts error:', e);
            if (tbody) {
                tbody.innerHTML = `<tr><td colspan="3" class="text-center py-12 text-red-500 font-bold text-sm">Failed to load archived parts. <button onclick="loadArchivedParts()" class="ml-2 underline text-blue-600 font-bold cursor-pointer">Retry</button></td></tr>`;
            }
        }
    }

    async function loadSuppliers() {
        const tbody = document.getElementById('suppliersTableBody');
        try {
            const res = await fetch("{{ route('suppliers.index') }}");
            if (!res.ok) throw new Error('HTTP ' + res.status);
            const result = await res.json();
            if(result.success) {
                suppliersList = result.data || [];
                renderSuppliers(suppliersList);
            }
        } catch(e) { 
            console.error('loadSuppliers error:', e);
            if (tbody) {
                tbody.innerHTML = `<tr><td class="text-center py-4 text-red-500 text-sm font-bold">Failed to load suppliers. <button onclick="loadSuppliers()" class="ml-2 underline text-blue-600 font-bold cursor-pointer">Retry</button></td></tr>`;
            }
        }
    }

    // ── AI Universal Real-Time Procedural Vector Engine (Expanded 45+ Automotive Component Blueprints) ──
    function generateDynamicPartSVG(partName) {
        const raw = (partName || 'Auto Part').toLowerCase().trim();
        const id = 'svg_' + Math.random().toString(36).substring(2, 9);

        // Dynamic Color Palette Extraction from Part Name
        let pColor = '#2563eb', sColor = '#3b82f6', aColor = '#60a5fa'; // Default Racing Blue
        if (/red|brembo|ferodo|sport|racing|sti|type\s*r/i.test(raw)) {
            pColor = '#dc2626'; sColor = '#ef4444'; aColor = '#f87171';
        } else if (/gold|yellow|motolite|ohlins|amaron/i.test(raw)) {
            pColor = '#d97706'; sColor = '#f59e0b'; aColor = '#fbbf24';
        } else if (/green|tein|monster|eco|hybrid/i.test(raw)) {
            pColor = '#16a34a'; sColor = '#22c55e'; aColor = '#4ade80';
        } else if (/black|dark|carbon|shadow|stealth/i.test(raw)) {
            pColor = '#1e293b'; sColor = '#334155'; aColor = '#64748b';
        } else if (/purple|hks/i.test(raw)) {
            pColor = '#9333ea'; sColor = '#a855f7'; aColor = '#c084fc';
        } else if (/cyan|sky|teal|cool|blue/i.test(raw)) {
            pColor = '#0284c7'; sColor = '#38bdf8'; aColor = '#bae6fd';
        }

        const isDrilled = /drill|slot|vent/i.test(raw);
        let content = '';

        // 1. CYLINDER HEAD / CYLINDER BLOCK / HEAD ASSEMBLY
        if (/cylinder\s*head|cylinder\s*block|head\s*assembly/i.test(raw)) {
            content = `
                <rect x="25" y="45" width="150" height="110" rx="8" fill="url(#${id}_metal)" stroke="#475569" stroke-width="2"/>
                <rect x="35" y="32" width="130" height="22" rx="4" fill="#334155"/>
                <circle cx="55" cy="43" r="7" fill="url(#${id}_metal)"/>
                <circle cx="145" cy="43" r="7" fill="url(#${id}_metal)"/>
                ${[48, 82, 116, 150].map(x => `
                    <rect x="${x-10}" y="60" width="20" height="42" rx="3" fill="#1e293b"/>
                    <circle cx="${x}" cy="72" r="6" fill="#cbd5e1"/>
                    <circle cx="${x}" cy="72" r="3" fill="#0f172a"/>
                    <rect x="${x-6}" y="84" width="12" height="14" rx="2" fill="${pColor}"/>
                    <circle cx="${x}" cy="130" r="12" fill="#0f172a"/>
                    <circle cx="${x-4}" cy="128" r="4" fill="url(#${id}_metal)"/>
                    <circle cx="${x+4}" cy="128" r="4" fill="url(#${id}_metal)"/>
                `).join('')}
                <rect x="30" y="145" width="140" height="10" rx="2" fill="#0f172a"/>
                ${[38, 72, 106, 140, 162].map(x => `<circle cx="${x}" cy="150" r="3" fill="#94a3b8"/>`).join('')}
            `;
        }
        // 2. ENGINE COVER / VALVE COVER / CAM COVER / ROCKER COVER / BEAUTY COVER
        else if (/engine\s*cover|valve\s*cover|cam\s*cover|rocker\s*cover|beauty\s*cover/i.test(raw)) {
            content = `
                <path d="M 30 50 Q 100 35 170 50 L 165 150 Q 100 160 35 150 Z" fill="${pColor}" stroke="#0f172a" stroke-width="2.5"/>
                <path d="M 38 58 Q 100 46 162 58 L 158 142 Q 100 152 42 142 Z" fill="#1e293b" opacity="0.85"/>
                ${[70, 82, 94, 106, 118, 130].map(y => `
                    <line x1="55" y1="${y}" x2="145" y2="${y}" stroke="url(#${id}_metal)" stroke-width="3" stroke-linecap="round"/>
                `).join('')}
                <circle cx="60" cy="75" r="14" fill="#0f172a" stroke="#eab308" stroke-width="2.5"/>
                <rect x="52" y="72" width="16" height="6" rx="2" fill="#eab308"/>
                <rect x="80" y="92" width="40" height="24" rx="4" fill="url(#${id}_metal)" stroke="#ffffff" stroke-width="1"/>
                <text x="100" y="108" font-size="9" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#0f172a">V6 DOHC</text>
            `;
        }
        // 3. HEAD GASKET / VALVE COVER GASKET / GASKET / SEAL / O-RING
        else if (/head\s*gasket|gasket|seal|o-ring|grommet/i.test(raw)) {
            content = `
                <rect x="25" y="55" width="150" height="90" rx="6" fill="#334155" stroke="#94a3b8" stroke-width="2"/>
                ${[48, 82, 116, 150].map(x => `
                    <circle cx="${x}" cy="100" r="15" fill="#0f172a" stroke="url(#${id}_metal)" stroke-width="3.5"/>
                    <circle cx="${x}" cy="100" r="13" fill="#020617"/>
                `).join('')}
                ${[35, 65, 99, 133, 165].map(x => `
                    <circle cx="${x}" cy="70" r="4" fill="#38bdf8"/>
                    <circle cx="${x}" cy="130" r="4" fill="#38bdf8"/>
                    <circle cx="${x}" cy="100" r="2.5" fill="#f59e0b"/>
                `).join('')}
            `;
        }
        // 4. INTAKE MANIFOLD / THROTTLE BODY / PLENUM / AIR INTAKE
        else if (/intake\s*manifold|throttle\s*body|plenum|air\s*intake/i.test(raw)) {
            content = `
                <rect x="35" y="40" width="130" height="30" rx="10" fill="url(#${id}_metal)"/>
                <rect x="20" y="44" width="20" height="22" rx="4" fill="#1e293b"/>
                <circle cx="28" cy="55" r="7" fill="#f59e0b"/>
                ${[55, 85, 115, 145].map(x => `
                    <path d="M ${x} 65 Q ${x+8} 110 ${x} 145" fill="none" stroke="url(#${id}_metal)" stroke-width="14" stroke-linecap="round"/>
                    <path d="M ${x} 65 Q ${x+8} 110 ${x} 145" fill="none" stroke="#ffffff" stroke-width="2" stroke-opacity="0.5"/>
                    <circle cx="${x}" cy="148" r="7" fill="${pColor}"/>
                `).join('')}
                <rect x="42" y="145" width="116" height="12" rx="3" fill="#334155"/>
            `;
        }
        // 5. EXHAUST MANIFOLD / EXHAUST HEADER / HEADERS / DOWNPIPE
        else if (/exhaust\s*manifold|header|downpipe|extractors/i.test(raw)) {
            content = `
                <rect x="40" y="35" width="120" height="14" rx="3" fill="#334155"/>
                ${[55, 85, 115, 145].map((x, i) => `
                    <circle cx="${x}" cy="42" r="5" fill="#0f172a"/>
                    <path d="M ${x} 45 Q ${x} 95 ${90 + i*6} 125" fill="none" stroke="url(#${id}_metal)" stroke-width="9" stroke-linecap="round"/>
                `).join('')}
                <rect x="85" y="125" width="30" height="28" rx="6" fill="#d97706"/>
                <rect x="80" y="153" width="40" height="12" rx="3" fill="#1e293b"/>
                <circle cx="87" cy="159" r="3.5" fill="#94a3b8"/>
                <circle cx="113" cy="159" r="3.5" fill="#94a3b8"/>
            `;
        }
        // 6. OIL PAN / SUMP / OIL SUMP
        else if (/oil\s*pan|sump|oil\s*sump/i.test(raw)) {
            content = `
                <polygon points="30,45 170,45 160,140 115,160 40,150" fill="url(#${id}_metal)" stroke="#334155" stroke-width="2"/>
                <rect x="25" y="42" width="150" height="10" rx="3" fill="#334155"/>
                ${[35, 55, 75, 95, 115, 135, 155].map(x => `<circle cx="${x}" cy="47" r="2.5" fill="#94a3b8"/>`).join('')}
                <rect x="110" y="110" width="40" height="40" rx="6" fill="#1e293b"/>
                <circle cx="130" cy="135" r="8" fill="#f59e0b"/>
                <circle cx="130" cy="135" r="4" fill="#0f172a"/>
            `;
        }
        // 7. TIMING CHAIN / TIMING BELT / TIMING KIT / CAM GEAR / TENSIONER
        else if (/timing\s*chain|timing\s*belt|timing\s*kit|cam\s*gear|tensioner/i.test(raw)) {
            content = `
                <circle cx="65" cy="65" r="28" fill="url(#${id}_metal)" stroke="#334155" stroke-width="2"/>
                ${Array.from({length: 12}).map((_, i) => {
                    const rad = (i * 30) * Math.PI / 180;
                    return `<rect x="${(65 + 26 * Math.cos(rad) - 3).toFixed(1)}" y="${(65 + 26 * Math.sin(rad) - 3).toFixed(1)}" width="6" height="6" fill="#1e293b"/>`;
                }).join('')}
                <circle cx="65" cy="65" r="10" fill="${pColor}"/>

                <circle cx="135" cy="65" r="28" fill="url(#${id}_metal)" stroke="#334155" stroke-width="2"/>
                ${Array.from({length: 12}).map((_, i) => {
                    const rad = (i * 30) * Math.PI / 180;
                    return `<rect x="${(135 + 26 * Math.cos(rad) - 3).toFixed(1)}" y="${(65 + 26 * Math.sin(rad) - 3).toFixed(1)}" width="6" height="6" fill="#1e293b"/>`;
                }).join('')}
                <circle cx="135" cy="65" r="10" fill="${pColor}"/>

                <circle cx="100" cy="150" r="20" fill="url(#${id}_metal)"/>
                <circle cx="100" cy="150" r="7" fill="#0f172a"/>
                <path d="M 65 37 L 135 37 Q 165 65 135 95 L 115 135 Q 100 172 85 135 L 65 95 Q 35 65 65 37 Z" fill="none" stroke="#f59e0b" stroke-width="6" stroke-dasharray="4,3"/>
            `;
        }
        // 8. PISTON / CONNECTING ROD / WRIST PIN / CRANKSHAFT / CAMSHAFT
        else if (/piston|connecting\s*rod|wrist\s*pin|crankshaft|camshaft|engine\s*block/i.test(raw)) {
            content = `
                <rect x="60" y="35" width="80" height="50" rx="6" fill="url(#${id}_metal)"/>
                <line x1="60" y1="46" x2="140" y2="46" stroke="#334155" stroke-width="2"/>
                <line x1="60" y1="54" x2="140" y2="54" stroke="#334155" stroke-width="2"/>
                <line x1="60" y1="62" x2="140" y2="62" stroke="#334155" stroke-width="2"/>
                <circle cx="100" cy="70" r="10" fill="#334155"/>
                <path d="M 94 70 L 92 145 L 82 145 L 82 175 L 118 175 L 118 145 L 108 145 L 106 70 Z" fill="url(#${id}_metal)"/>
                <circle cx="100" cy="160" r="16" fill="#0f172a"/>
                <circle cx="100" cy="160" r="10" fill="#94a3b8"/>
            `;
        }
        // 9. TURBOCHARGER / SUPERCHARGER / INTERCOOLER / WASTEGATE / BLOW OFF
        else if (/turbo|supercharger|intercooler|wastegate|blow\s*off/i.test(raw)) {
            content = `
                <ellipse cx="100" cy="100" rx="60" ry="50" fill="url(#${id}_metal)"/>
                <path d="M 60 60 Q 100 20 145 50 L 155 35 Q 95 5 45 50 Z" fill="#64748b"/>
                <circle cx="100" cy="100" r="32" fill="#0f172a"/>
                ${Array.from({length: 8}).map((_, i) => {
                    const rad = (i * 45) * Math.PI / 180;
                    return `<line x1="100" y1="100" x2="${(100 + 28 * Math.cos(rad)).toFixed(1)}" y2="${(100 + 28 * Math.sin(rad)).toFixed(1)}" stroke="#38bdf8" stroke-width="3" stroke-linecap="round"/>`;
                }).join('')}
                <circle cx="100" cy="100" r="10" fill="url(#${id}_metal)"/>
            `;
        }
        // 10. CV AXLE / DRIVESHAFT / PROPELLER SHAFT / HALF SHAFT / CV JOINT
        else if (/cv\s*axle|axle|driveshaft|propeller\s*shaft|half\s*shaft|cv\s*joint|universal\s*joint/i.test(raw)) {
            content = `
                <rect x="65" y="93" width="70" height="14" rx="3" fill="url(#${id}_metal)"/>
                <rect x="25" y="85" width="20" height="30" rx="4" fill="#334155"/>
                <rect x="15" y="95" width="14" height="10" fill="#94a3b8"/>
                ${[40, 48, 56].map(x => `
                    <path d="M ${x} 80 L ${x+4} 100 L ${x} 120" fill="none" stroke="#0f172a" stroke-width="9" stroke-linecap="round"/>
                `).join('')}
                ${[135, 143, 151].map(x => `
                    <path d="M ${x} 80 L ${x+4} 100 L ${x} 120" fill="none" stroke="#0f172a" stroke-width="9" stroke-linecap="round"/>
                `).join('')}
                <rect x="155" y="85" width="20" height="30" rx="4" fill="#334155"/>
                <rect x="172" y="94" width="16" height="12" fill="url(#${id}_metal)"/>
            `;
        }
        // 11. DIFFERENTIAL / DIFF / LIMITED SLIP / LSD / FINAL DRIVE
        else if (/differential|diff|limited\s*slip|lsd|final\s*drive/i.test(raw)) {
            content = `
                <circle cx="100" cy="100" r="55" fill="url(#${id}_metal)"/>
                <circle cx="100" cy="100" r="45" fill="#1e293b"/>
                <circle cx="100" cy="100" r="35" fill="#d97706" stroke="#f59e0b" stroke-width="4" stroke-dasharray="6,4"/>
                <circle cx="100" cy="100" r="15" fill="url(#${id}_metal)"/>
                <line x1="15" y1="100" x2="48" y2="100" stroke="#475569" stroke-width="16" stroke-linecap="round"/>
                <line x1="152" y1="100" x2="185" y2="100" stroke="#475569" stroke-width="16" stroke-linecap="round"/>
                <rect x="92" y="25" width="16" height="28" rx="4" fill="#334155"/>
            `;
        }
        // 12. CLUTCH / FLYWHEEL / PRESSURE PLATE / TRANSMISSION / GEARBOX
        else if (/clutch|flywheel|pressure\s*plate|transmission|gearbox/i.test(raw)) {
            content = `
                <circle cx="100" cy="100" r="80" fill="#334155"/>
                <circle cx="100" cy="100" r="72" fill="url(#${id}_ceramic)"/>
                <circle cx="100" cy="100" r="45" fill="url(#${id}_metal)"/>
                ${[0, 90, 180, 270].map(a => {
                    const rad = a * Math.PI / 180;
                    return `<rect x="${(100 + 26 * Math.cos(rad) - 8).toFixed(1)}" y="${(100 + 26 * Math.sin(rad) - 8).toFixed(1)}" width="16" height="16" rx="3" fill="#f59e0b"/>`;
                }).join('')}
                <circle cx="100" cy="100" r="16" fill="#0f172a"/>
            `;
        }
        // 13. BRAKE MASTER CYLINDER / BRAKE BOOSTER / HYDROBOOST
        else if (/master\s*cylinder|brake\s*booster|hydroboost/i.test(raw)) {
            content = `
                <ellipse cx="65" cy="100" rx="35" ry="50" fill="#1e293b" stroke="#334155" stroke-width="3"/>
                <ellipse cx="65" cy="100" rx="25" ry="38" fill="#0f172a"/>
                <rect x="85" y="90" width="75" height="20" rx="4" fill="url(#${id}_metal)"/>
                <rect x="100" y="50" width="50" height="42" rx="8" fill="#e0f2fe" opacity="0.9" stroke="#38bdf8" stroke-width="1.5"/>
                <rect x="102" y="70" width="46" height="20" rx="4" fill="#fef08a" opacity="0.8"/>
                <rect x="115" y="40" width="20" height="12" rx="3" fill="#eab308"/>
                <circle cx="135" cy="100" r="4" fill="#ef4444"/>
                <circle cx="150" cy="100" r="4" fill="#ef4444"/>
            `;
        }
        // 14. BRAKE ROTOR / BRAKE DISC / ROTOR
        else if (/rotor|disc|disk|brake\s*rotor/i.test(raw)) {
            const drilledHoles = isDrilled ? Array.from({length: 12}).map((_, i) => {
                const rad1 = (i * 30) * Math.PI / 180;
                const rad2 = (i * 30 + 15) * Math.PI / 180;
                return `
                    <circle cx="${(100 + 64 * Math.cos(rad1)).toFixed(1)}" cy="${(100 + 64 * Math.sin(rad1)).toFixed(1)}" r="2.5" fill="#1e293b"/>
                    <circle cx="${(100 + 74 * Math.cos(rad2)).toFixed(1)}" cy="${(100 + 74 * Math.sin(rad2)).toFixed(1)}" r="2.5" fill="#1e293b"/>
                `;
            }).join('') : '';

            const lugBolts = [0, 72, 144, 216, 288].map(a => {
                const rad = (a - 90) * Math.PI / 180;
                return `<circle cx="${(100 + 26 * Math.cos(rad)).toFixed(1)}" cy="${(100 + 26 * Math.sin(rad)).toFixed(1)}" r="5.5" fill="#f8fafc"/><circle cx="${(100 + 26 * Math.cos(rad)).toFixed(1)}" cy="${(100 + 26 * Math.sin(rad)).toFixed(1)}" r="3" fill="#0f172a"/>`;
            }).join('');

            content = `
                <circle cx="100" cy="100" r="82" fill="url(#${id}_rotor)"/>
                <circle cx="100" cy="100" r="76" fill="none" stroke="#e2e8f0" stroke-width="1.5"/>
                <circle cx="100" cy="100" r="68" fill="none" stroke="#64748b" stroke-width="0.75"/>
                <circle cx="100" cy="100" r="60" fill="none" stroke="#cbd5e1" stroke-width="1.5"/>
                ${drilledHoles}
                <circle cx="100" cy="100" r="46" fill="url(#${id}_hat)"/>
                <circle cx="100" cy="100" r="42" fill="none" stroke="#f8fafc" stroke-width="1.5" stroke-opacity="0.4"/>
                ${lugBolts}
                <circle cx="100" cy="100" r="14" fill="#0f172a"/>
                <circle cx="100" cy="100" r="11" fill="#020617"/>
            `;
        }
        // 15. BRAKE PADS / CALIPER / BRAKE SHOE
        else if (/pad|brake|caliper|brake\s*shoe/i.test(raw)) {
            content = `
                <rect x="30" y="45" width="140" height="45" rx="6" fill="#334155"/>
                <rect x="42" y="52" width="116" height="34" rx="4" fill="${pColor}"/>
                <rect x="98" y="52" width="4" height="34" fill="#1e293b"/>
                <rect x="25" y="105" width="150" height="52" rx="8" fill="#1e293b"/>
                <rect x="38" y="112" width="124" height="40" rx="4" fill="url(#${id}_ceramic)"/>
                <polygon points="38,112 55,112 38,152" fill="#78350f" opacity="0.6"/>
                <polygon points="162,112 145,112 162,152" fill="#78350f" opacity="0.6"/>
                <rect x="98" y="112" width="4" height="40" fill="#451a03"/>
                <rect x="20" y="145" width="12" height="15" rx="2" fill="#94a3b8"/>
                <rect x="168" y="145" width="12" height="15" rx="2" fill="#94a3b8"/>
            `;
        }
        // 16. SHOCK ABSORBER / STRUT / COILOVER / SPRING / SUSPENSION
        else if (/shock|strut|coilover|absorber|spring|suspension|damper/i.test(raw)) {
            content = `
                <rect x="65" y="16" width="70" height="12" rx="4" fill="${pColor}"/>
                <circle cx="100" cy="22" r="5" fill="#f8fafc"/>
                <rect x="94" y="28" width="12" height="72" rx="2" fill="url(#${id}_metal)"/>
                ${[35, 47, 59, 71, 83, 95].map(y => `
                    <path d="M 68 ${y+6} Q 100 ${y} 132 ${y+6}" fill="none" stroke="url(#${id}_spring)" stroke-width="9" stroke-linecap="round"/>
                    <path d="M 68 ${y+6} Q 100 ${y+2} 132 ${y+6}" fill="none" stroke="${aColor}" stroke-width="2" stroke-linecap="round"/>
                `).join('')}
                <rect x="74" y="106" width="52" height="8" rx="2" fill="${pColor}"/>
                <rect x="78" y="114" width="44" height="6" rx="2" fill="${sColor}"/>
                <rect x="82" y="120" width="36" height="52" rx="4" fill="url(#${id}_body)"/>
                <line x1="88" y1="120" x2="88" y2="172" stroke="#94a3b8" stroke-width="1.5" stroke-opacity="0.5"/>
                <circle cx="100" cy="180" r="14" fill="url(#${id}_body)"/>
                <circle cx="100" cy="180" r="7" fill="${pColor}"/>
                <circle cx="100" cy="180" r="4" fill="#0f172a"/>
            `;
        }
        // 17. CONTROL ARM / WISHBONE / TRAILING ARM / A-ARM / SWAY BAR / STABILIZER LINK
        else if (/control\s*arm|wishbone|trailing\s*arm|a-arm|sway\s*bar|stabilizer\s*link/i.test(raw)) {
            content = `
                <path d="M 45 135 L 155 135 L 100 45 Z" fill="none" stroke="url(#${id}_metal)" stroke-width="22" stroke-linejoin="round"/>
                <circle cx="45" cy="135" r="20" fill="#1e293b"/>
                <circle cx="45" cy="135" r="8" fill="#94a3b8"/>
                <circle cx="155" cy="135" r="20" fill="#1e293b"/>
                <circle cx="155" cy="135" r="8" fill="#94a3b8"/>
                <circle cx="100" cy="45" r="18" fill="${pColor}"/>
                <circle cx="100" cy="45" r="8" fill="#f8fafc"/>
                <rect x="96" y="20" width="8" height="20" rx="2" fill="url(#${id}_metal)"/>
            `;
        }
        // 18. STEERING RACK / PINION / TIE ROD / STEERING COLUMN
        else if (/steering\s*rack|pinion|tie\s*rod|steering\s*column/i.test(raw)) {
            content = `
                <rect x="40" y="93" width="120" height="14" rx="3" fill="url(#${id}_metal)"/>
                <rect x="85" y="55" width="30" height="48" rx="6" fill="#334155"/>
                <circle cx="100" cy="65" r="8" fill="#f59e0b"/>
                ${[30, 36, 42, 148, 154, 160].map(x => `
                    <rect x="${x}" y="85" width="5" height="30" rx="2" fill="#0f172a"/>
                `).join('')}
                <circle cx="20" cy="100" r="7" fill="${pColor}"/>
                <circle cx="180" cy="100" r="7" fill="${pColor}"/>
            `;
        }
        // 19. STEERING WHEEL
        else if (/steering\s*wheel/i.test(raw)) {
            content = `
                <circle cx="100" cy="100" r="72" fill="none" stroke="#1e293b" stroke-width="16"/>
                <circle cx="100" cy="100" r="72" fill="none" stroke="#475569" stroke-width="2" stroke-dasharray="3,3"/>
                <circle cx="100" cy="100" r="28" fill="${pColor}"/>
                <circle cx="100" cy="100" r="14" fill="#f8fafc"/>
                <line x1="36" y1="100" x2="72" y2="100" stroke="#334155" stroke-width="10" stroke-linecap="round"/>
                <line x1="164" y1="100" x2="128" y2="100" stroke="#334155" stroke-width="10" stroke-linecap="round"/>
                <line x1="100" y1="128" x2="100" y2="164" stroke="#334155" stroke-width="10" stroke-linecap="round"/>
            `;
        }
        // 20. WHEEL BEARING / WHEEL HUB / HUB ASSEMBLY / SPINDLE
        else if (/wheel\s*bearing|wheel\s*hub|hub\s*assembly|spindle/i.test(raw)) {
            content = `
                <circle cx="100" cy="100" r="75" fill="url(#${id}_metal)"/>
                <circle cx="100" cy="100" r="58" fill="#1e293b"/>
                ${Array.from({length: 8}).map((_, i) => {
                    const rad = (i * 45) * Math.PI / 180;
                    return `<circle cx="${(100 + 44 * Math.cos(rad)).toFixed(1)}" cy="${(100 + 44 * Math.sin(rad)).toFixed(1)}" r="9" fill="url(#${id}_metal)"/>`;
                }).join('')}
                <circle cx="100" cy="100" r="30" fill="url(#${id}_metal)"/>
                <circle cx="100" cy="100" r="18" fill="#0f172a"/>
            `;
        }
        // 21. TIRES / WHEELS / RIMS / MAGS / ALLOY WHEEL
        else if (/tire|tyre|wheel|rim|mag|alloy\s*wheel/i.test(raw)) {
            const spokes = [0, 72, 144, 216, 288].map(a => {
                const rad = a * Math.PI / 180;
                return `<circle cx="${(100 + 38 * Math.cos(rad)).toFixed(1)}" cy="${(100 + 38 * Math.sin(rad)).toFixed(1)}" r="18" fill="#e2e8f0"/><circle cx="${(100 + 38 * Math.cos(rad)).toFixed(1)}" cy="${(100 + 38 * Math.sin(rad)).toFixed(1)}" r="11" fill="#1e293b"/>`;
            }).join('');

            const treads = Array.from({length: 24}).map((_, i) => {
                const rad = (i * 15) * Math.PI / 180;
                return `<line x1="${(100 + 72 * Math.cos(rad)).toFixed(1)}" y1="${(100 + 72 * Math.sin(rad)).toFixed(1)}" x2="${(100 + 82 * Math.cos(rad)).toFixed(1)}" y2="${(100 + 82 * Math.sin(rad)).toFixed(1)}" stroke="#0f172a" stroke-width="2.5"/>`;
            }).join('');

            content = `
                <circle cx="100" cy="100" r="82" fill="#1e293b"/>
                <circle cx="100" cy="100" r="74" fill="#334155"/>
                <circle cx="100" cy="100" r="62" fill="#0f172a"/>
                ${treads}
                <circle cx="100" cy="100" r="54" fill="#cbd5e1"/>
                <circle cx="100" cy="100" r="48" fill="#94a3b8"/>
                ${spokes}
                <circle cx="100" cy="100" r="22" fill="#f8fafc"/>
                <circle cx="100" cy="100" r="16" fill="${pColor}"/>
                <circle cx="100" cy="100" r="8" fill="#ffffff"/>
            `;
        }
        // 22. RADIATOR HOSE / COOLANT HOSE / HEATER HOSE
        else if (/radiator\s*hose|coolant\s*hose|heater\s*hose/i.test(raw)) {
            content = `
                <path d="M 35 140 Q 55 55 125 55 L 165 75" fill="none" stroke="${pColor}" stroke-width="26" stroke-linecap="round"/>
                <path d="M 35 140 Q 55 55 125 55 L 165 75" fill="none" stroke="#ffffff" stroke-width="3" stroke-linecap="round" opacity="0.4"/>
                <rect x="25" y="125" width="14" height="30" rx="3" fill="url(#${id}_metal)"/>
                <circle cx="32" cy="140" r="3" fill="#0f172a"/>
                <rect x="150" y="60" width="14" height="30" rx="3" fill="url(#${id}_metal)"/>
                <circle cx="157" cy="75" r="3" fill="#0f172a"/>
            `;
        }
        // 23. RADIATOR / CONDENSER / COOLING FAN / FAN BLADE
        else if (/radiator|condenser|cooling\s*fan|fan\s*blade/i.test(raw)) {
            content = `
                <rect x="35" y="45" width="130" height="110" rx="8" fill="#334155"/>
                <rect x="42" y="55" width="116" height="90" fill="#94a3b8"/>
                ${[60, 68, 76, 84, 92, 100, 108, 116, 124, 132, 140].map(y => `<line x1="42" y1="${y}" x2="158" y2="${y}" stroke="#475569" stroke-width="2"/>`).join('')}
                <rect x="35" y="40" width="130" height="15" rx="3" fill="#0284c7"/>
                <rect x="35" y="145" width="130" height="15" rx="3" fill="#0284c7"/>
                <circle cx="55" cy="47" r="5" fill="#f8fafc"/>
                <circle cx="145" cy="152" r="5" fill="#f8fafc"/>
            `;
        }
        // 24. WATER PUMP / THERMOSTAT / WATER NECK
        else if (/water\s*pump|thermostat|water\s*neck/i.test(raw)) {
            content = `
                <circle cx="100" cy="100" r="52" fill="url(#${id}_metal)"/>
                <circle cx="100" cy="100" r="36" fill="#1e293b"/>
                ${Array.from({length: 6}).map((_, i) => {
                    const rad = (i * 60) * Math.PI / 180;
                    return `<path d="M 100 100 Q ${(100 + 30 * Math.cos(rad)).toFixed(1)} ${(100 + 30 * Math.sin(rad)).toFixed(1)} ${(100 + 48 * Math.cos(rad+0.5)).toFixed(1)} ${(100 + 48 * Math.sin(rad+0.5)).toFixed(1)}" stroke="#38bdf8" stroke-width="4" fill="none"/>`;
                }).join('')}
                <rect x="35" y="85" width="30" height="30" rx="4" fill="${pColor}"/>
                <rect x="135" y="85" width="30" height="30" rx="4" fill="${pColor}"/>
            `;
        }
        // 25. FUEL PUMP / FUEL TANK / GAS TANK
        else if (/fuel\s*pump|fuel\s*tank|gas\s*tank/i.test(raw)) {
            content = `
                <rect x="40" y="55" width="120" height="90" rx="14" fill="#1e293b" stroke="#475569" stroke-width="2"/>
                <circle cx="100" cy="100" r="30" fill="url(#${id}_metal)"/>
                <circle cx="100" cy="100" r="18" fill="#334155"/>
                <rect x="94" y="35" width="12" height="25" rx="3" fill="#f59e0b"/>
                <rect x="135" y="45" width="16" height="20" rx="4" fill="#38bdf8"/>
            `;
        }
        // 26. FUEL INJECTOR / INJECTOR / FUEL RAIL / NOZZLE
        else if (/fuel\s*injector|injector|fuel\s*rail|nozzle/i.test(raw)) {
            content = `
                <rect x="90" y="25" width="20" height="35" rx="4" fill="#1e293b"/>
                <rect x="94" y="60" width="12" height="80" rx="2" fill="url(#${id}_metal)"/>
                <circle cx="100" cy="75" r="8" fill="#ef4444"/>
                <circle cx="100" cy="130" r="8" fill="#3b82f6"/>
                <path d="M 96 140 L 104 140 L 100 180 Z" fill="url(#${id}_metal)"/>
                <circle cx="100" cy="180" r="3" fill="#f59e0b"/>
            `;
        }
        // 27. SPARK PLUG / IGNITION COIL / GLOW PLUG / IRIDIUM
        else if (/spark|plug|ignition\s*coil|glow\s*plug|iridium/i.test(raw)) {
            content = `
                <rect x="94" y="18" width="12" height="15" rx="2" fill="#cbd5e1"/>
                <rect x="84" y="33" width="32" height="70" rx="4" fill="#f8fafc"/>
                <ellipse cx="100" cy="45" rx="19" ry="5" fill="#e2e8f0"/>
                <ellipse cx="100" cy="60" rx="19" ry="5" fill="#e2e8f0"/>
                <ellipse cx="100" cy="75" rx="19" ry="5" fill="#e2e8f0"/>
                <ellipse cx="100" cy="90" rx="19" ry="5" fill="#e2e8f0"/>
                <rect x="78" y="103" width="44" height="26" rx="2" fill="#64748b"/>
                <rect x="86" y="129" width="28" height="42" fill="#475569"/>
                ${[133, 139, 145, 151, 157, 163].map(y => `<line x1="86" y1="${y}" x2="114" y2="${y+2}" stroke="#94a3b8" stroke-width="2"/>`).join('')}
                <rect x="97" y="171" width="6" height="14" fill="#f8fafc"/>
                <rect x="98.5" y="185" width="3" height="6" fill="#f59e0b"/>
                <path d="M 88 171 L 88 193 L 100 193" fill="none" stroke="#64748b" stroke-width="3.5" stroke-linecap="round"/>
            `;
        }
        // 28. ALTERNATOR / GENERATOR / DYNAMO / STATOR
        else if (/alternator|generator|dynamo|stator/i.test(raw)) {
            content = `
                <circle cx="100" cy="105" r="58" fill="#94a3b8"/>
                ${Array.from({length: 8}).map((_, i) => {
                    const rad = (i * 45) * Math.PI / 180;
                    return `<circle cx="${(100 + 40 * Math.cos(rad)).toFixed(1)}" cy="${(105 + 40 * Math.sin(rad)).toFixed(1)}" r="10" fill="#c2410c"/>`;
                }).join('')}
                <circle cx="100" cy="105" r="32" fill="#1e293b"/>
                <circle cx="100" cy="105" r="12" fill="#e2e8f0"/>
                <rect x="88" y="24" width="24" height="24" rx="4" fill="${pColor}"/>
            `;
        }
        // 29. STARTER MOTOR / STARTER SOLENOID
        else if (/starter|starter\s*motor|starter\s*solenoid/i.test(raw)) {
            content = `
                <rect x="40" y="80" width="100" height="55" rx="12" fill="url(#${id}_metal)"/>
                <rect x="70" y="45" width="70" height="35" rx="8" fill="#1e293b"/>
                <circle cx="155" cy="107" r="16" fill="#f59e0b"/>
                ${Array.from({length: 8}).map((_, i) => {
                    const rad = (i * 45) * Math.PI / 180;
                    return `<rect x="${(155 + 14 * Math.cos(rad) - 2).toFixed(1)}" y="${(107 + 14 * Math.sin(rad) - 2).toFixed(1)}" width="4" height="4" fill="#0f172a"/>`;
                }).join('')}
            `;
        }
        // 30. BATTERY / MOTOLITE / AMARON / CAR BATTERY / 12V
        else if (/battery|motolite|amaron|car\s*battery|12v/i.test(raw)) {
            content = `
                <rect x="35" y="60" width="130" height="115" rx="8" fill="#1e293b"/>
                <rect x="30" y="50" width="140" height="30" rx="4" fill="${pColor}"/>
                <rect x="48" y="32" width="18" height="18" rx="2" fill="#ef4444"/>
                <circle cx="57" cy="32" r="6" fill="#94a3b8"/>
                <rect x="134" y="32" width="18" height="18" rx="2" fill="#3b82f6"/>
                <circle cx="143" cy="32" r="6" fill="#94a3b8"/>
                <rect x="48" y="95" width="104" height="65" rx="6" fill="#0f172a"/>
                <rect x="54" y="102" width="92" height="24" rx="3" fill="${sColor}"/>
                <circle cx="100" cy="142" r="8" fill="#22c55e"/>
            `;
        }
        // 31. AC COMPRESSOR / AIRCON COMPRESSOR / AC CLUTCH
        else if (/ac\s*compressor|aircon\s*compressor|ac\s*clutch/i.test(raw)) {
            content = `
                <rect x="60" y="60" width="90" height="80" rx="16" fill="url(#${id}_metal)"/>
                <rect x="35" y="70" width="28" height="60" rx="6" fill="#1e293b"/>
                <circle cx="48" cy="100" r="18" fill="#64748b"/>
                <circle cx="48" cy="100" r="8" fill="#cbd5e1"/>
                <rect x="95" y="42" width="18" height="20" rx="4" fill="#ef4444"/>
                <rect x="122" y="42" width="18" height="20" rx="4" fill="#3b82f6"/>
            `;
        }
        // 32. SENSORS & PROBES (O2, ABS, MAF, MAP, TPS, LAMBDA)
        else if (/sensor|probe|o2|abs|maf|map|tps|lambda/i.test(raw)) {
            content = `
                <rect x="92" y="25" width="16" height="65" rx="3" fill="url(#${id}_metal)"/>
                <rect x="88" y="90" width="24" height="20" rx="2" fill="#eab308"/>
                <rect x="84" y="110" width="32" height="35" rx="4" fill="#1e293b"/>
                <path d="M 100 145 Q 85 170 120 180" fill="none" stroke="#dc2626" stroke-width="5" stroke-linecap="round"/>
                <path d="M 95 145 Q 75 165 110 185" fill="none" stroke="#2563eb" stroke-width="5" stroke-linecap="round"/>
            `;
        }
        // 33. FUSE / RELAY / FUSE BOX / SWITCH / ECU / MODULE
        else if (/fuse|relay|fuse\s*box|switch|ecu|module/i.test(raw)) {
            content = `
                <rect x="50" y="40" width="100" height="90" rx="8" fill="${pColor}" opacity="0.85"/>
                <path d="M 70 85 Q 100 50 100 85 Q 100 120 130 85" fill="none" stroke="#facc15" stroke-width="6" stroke-linecap="round"/>
                <rect x="65" y="130" width="20" height="45" rx="3" fill="url(#${id}_metal)"/>
                <rect x="115" y="130" width="20" height="45" rx="3" fill="url(#${id}_metal)"/>
            `;
        }
        // 34. HORN / SNAIL HORN / ALARM / SIREN
        else if (/horn|snail\s*horn|alarm|siren/i.test(raw)) {
            content = `
                <circle cx="100" cy="100" r="55" fill="#1e293b"/>
                <circle cx="100" cy="100" r="46" fill="${pColor}"/>
                <path d="M 100 65 A 35 35 0 0 1 135 100 A 35 35 0 0 1 100 135 A 25 25 0 0 1 75 100 A 15 15 0 0 1 100 85" fill="none" stroke="url(#${id}_metal)" stroke-width="12" stroke-linecap="round"/>
                <circle cx="100" cy="100" r="12" fill="#0f172a"/>
                <rect x="90" y="32" width="20" height="15" rx="3" fill="#64748b"/>
            `;
        }
        // 35. WINDOW / GLASS / WINDSHIELD / WINDSCREEN / TINT / REGULATOR
        else if (/window|glass|windshield|windscreen|tint|regulator/i.test(raw)) {
            content = `
                <path d="M 45 40 L 155 32 Q 165 32 168 42 L 160 148 Q 158 155 150 155 L 42 155 Q 35 155 35 146 L 38 48 Q 39 40 45 40 Z" fill="url(#${id}_glass)" stroke="#38bdf8" stroke-width="2.5"/>
                <polygon points="58,40 85,38 72,155 45,155" fill="#ffffff" opacity="0.35"/>
                <polygon points="105,36 120,35 108,155 93,155" fill="#ffffff" opacity="0.2"/>
                <rect x="35" y="152" width="130" height="14" rx="3" fill="#334155"/>
                <circle cx="65" cy="159" r="3.5" fill="#94a3b8"/>
                <circle cx="135" cy="159" r="3.5" fill="#94a3b8"/>
                <path d="M 85 166 L 115 166 L 100 185 Z" fill="${pColor}"/>
            `;
        }
        // 36. MIRROR / SIDE MIRROR / REARVIEW MIRROR / WING MIRROR
        else if (/mirror|side\s*mirror|rearview\s*mirror|wing\s*mirror/i.test(raw)) {
            content = `
                <path d="M 45 60 Q 155 35 165 75 Q 170 120 120 135 L 55 130 Z" fill="#1e293b"/>
                <path d="M 52 68 Q 145 46 153 78 Q 157 114 115 125 L 60 122 Z" fill="url(#${id}_glass)" stroke="#38bdf8" stroke-width="1.5"/>
                <path d="M 45 110 L 25 145 L 65 145 Z" fill="#334155"/>
                <line x1="85" y1="58" x2="135" y2="95" stroke="#ffffff" stroke-width="3" stroke-linecap="round" opacity="0.6"/>
                <rect x="135" y="105" width="22" height="6" rx="2" fill="#f59e0b"/>
            `;
        }
        // 37. BUMPER / GRILLE / HOOD / TRUNK / FENDER / SPOILER
        else if (/bumper|grille|hood|trunk|fender|spoiler/i.test(raw)) {
            content = `
                <path d="M 25 70 Q 100 50 175 70 L 165 135 Q 100 150 35 135 Z" fill="${pColor}"/>
                <rect x="45" y="78" width="110" height="38" rx="8" fill="#0f172a"/>
                ${[84, 90, 96, 102, 108].map(y => `<line x1="50" y1="${y}" x2="150" y2="${y}" stroke="#475569" stroke-width="2"/>`).join('')}
                <ellipse cx="100" cy="97" rx="16" ry="10" fill="${sColor}"/>
            `;
        }
        // 38. DOOR / DOOR HANDLE / LATCH / LOCK
        else if (/door|handle|latch|lock/i.test(raw)) {
            content = `
                <rect x="35" y="35" width="130" height="130" rx="14" fill="${pColor}"/>
                <path d="M 35 35 Q 100 45 165 35" stroke="#ffffff" stroke-width="2.5" stroke-opacity="0.5" fill="none"/>
                <rect x="55" y="85" width="90" height="30" rx="8" fill="#1e293b"/>
                <rect x="62" y="92" width="76" height="16" rx="5" fill="url(#${id}_metal)"/>
                <circle cx="128" cy="100" r="3" fill="#0f172a"/>
            `;
        }
        // 39. LIGHTING / HEADLIGHT / TAILLIGHT / BULB / FOG / LED / XENON
        else if (/headlight|light|lamp|bulb|taillight|fog|led|xenon/i.test(raw)) {
            content = `
                <path d="M 35 60 Q 140 45 165 85 Q 155 135 45 135 Z" fill="#1e293b"/>
                <path d="M 42 66 Q 133 53 154 88 Q 145 128 50 128 Z" fill="url(#${id}_glass)" stroke="#67e8f9" stroke-width="2"/>
                <circle cx="85" cy="98" r="22" fill="#0284c7"/>
                <circle cx="85" cy="98" r="15" fill="#e0f2fe"/>
                <circle cx="85" cy="98" r="8" fill="#ffffff"/>
                <circle cx="130" cy="95" r="10" fill="#f59e0b"/>
            `;
        }
        // 40. WIPER / WIPER BLADE / WIPER ARM
        else if (/wiper|blade/i.test(raw)) {
            content = `
                <path d="M 25 145 Q 100 65 175 125" fill="none" stroke="#1e293b" stroke-width="12" stroke-linecap="round"/>
                <path d="M 25 151 Q 100 71 175 131" fill="none" stroke="#64748b" stroke-width="4" stroke-linecap="round"/>
                <rect x="90" y="80" width="20" height="16" rx="3" fill="${pColor}"/>
            `;
        }
        // 41. SEAT / SEATBELT / CUSHION / UPHOLSTERY / INTERIOR / DASHBOARD
        else if (/seat|seatbelt|cushion|upholstery|interior|dashboard/i.test(raw)) {
            content = `
                <rect x="80" y="24" width="40" height="25" rx="8" fill="#1e293b"/>
                <path d="M 60 52 L 140 52 L 132 140 L 68 140 Z" fill="${pColor}"/>
                <path d="M 75 56 L 125 56 L 120 135 L 80 135 Z" fill="#0f172a" opacity="0.3"/>
                <rect x="50" y="135" width="100" height="35" rx="10" fill="#1e293b"/>
                <rect x="58" y="140" width="84" height="25" rx="6" fill="${sColor}"/>
            `;
        }
        // 42. OIL & FLUIDS / COOLANT / SYNTHETIC / LUBRICANT / ATF
        else if (/oil|fluid|coolant|synthetic|lubricant|atf/i.test(raw)) {
            content = `
                <rect x="85" y="24" width="30" height="22" rx="3" fill="${pColor}"/>
                <rect x="90" y="46" width="20" height="18" fill="${sColor}"/>
                <path d="M 55 64 L 145 64 L 155 175 L 45 175 Z" fill="${pColor}"/>
                <path d="M 55 64 L 75 64 L 65 175 L 45 175 Z" fill="#0f172a" opacity="0.2"/>
                <rect x="62" y="75" width="6" height="85" rx="2" fill="#facc15"/>
                <rect x="78" y="90" width="65" height="65" rx="6" fill="#f8fafc"/>
                <circle cx="110" cy="122" r="16" fill="${sColor}"/>
                <path d="M 110 110 Q 120 125 110 132 Q 100 125 110 110 Z" fill="#facc15"/>
            `;
        }
        // 43. FILTERS (OIL, AIR, CABIN, FUEL)
        else if (/filter/i.test(raw)) {
            if (/oil/i.test(raw)) {
                content = `
                    <rect x="60" y="45" width="80" height="120" rx="14" fill="#0f172a"/>
                    <ellipse cx="100" cy="55" rx="40" ry="12" fill="#334155"/>
                    <ellipse cx="100" cy="165" rx="40" ry="12" fill="#475569"/>
                    <ellipse cx="100" cy="165" rx="32" ry="8" fill="#dc2626"/>
                    <ellipse cx="100" cy="165" rx="14" ry="4" fill="#0f172a"/>
                `;
            } else {
                content = `
                    <rect x="35" y="45" width="130" height="110" rx="8" fill="${pColor}"/>
                    <rect x="46" y="56" width="108" height="88" rx="4" fill="#fef08a"/>
                    ${[52, 60, 68, 76, 84, 92, 100, 108, 116, 124, 132, 140, 148].map(x => `<line x1="${x}" y1="56" x2="${x}" y2="144" stroke="#ca8a04" stroke-width="2.5"/>`).join('')}
                `;
            }
        }
        // 44. BOLT / NUT / SCREW / FASTENER / BRACKET / MOUNT
        else if (/bolt|nut|screw|fastener|bracket|mount/i.test(raw)) {
            content = `
                <polygon points="100,25 140,48 140,94 100,117 60,94 60,48" fill="url(#${id}_metal)"/>
                <circle cx="100" cy="71" r="24" fill="#64748b"/>
                <rect x="85" y="117" width="30" height="65" rx="2" fill="url(#${id}_metal)"/>
                ${[125, 137, 149, 161, 173].map(y => `<line x1="85" y1="${y}" x2="115" y2="${y+3}" stroke="#334155" stroke-width="2.5"/>`).join('')}
            `;
        }
        // 45. DYNAMIC DETERMINISTIC PROCEDURAL BLUEPRINT (For ANY other unique custom automotive name)
        else {
            let hash = 0;
            for (let i = 0; i < raw.length; i++) hash = (hash << 5) - hash + raw.charCodeAt(i);
            const teeth = 6 + (Math.abs(hash) % 6);
            const innerR = 24 + (Math.abs(hash) % 14);

            content = `
                <circle cx="100" cy="100" r="70" fill="url(#${id}_metal)" stroke="#334155" stroke-width="2"/>
                ${Array.from({length: teeth}).map((_, i) => {
                    const rad = (i * (360 / teeth)) * Math.PI / 180;
                    return `<rect x="${(100 + 66 * Math.cos(rad) - 7).toFixed(1)}" y="${(100 + 66 * Math.sin(rad) - 7).toFixed(1)}" width="14" height="14" rx="3" fill="${pColor}"/>`;
                }).join('')}
                <circle cx="100" cy="100" r="${innerR}" fill="#1e293b"/>
                <circle cx="100" cy="100" r="${(innerR * 0.6).toFixed(1)}" fill="url(#${id}_metal)"/>
                <circle cx="100" cy="100" r="${(innerR * 0.25).toFixed(1)}" fill="#0f172a"/>
                <line x1="45" y1="45" x2="155" y2="155" stroke="#ffffff" stroke-width="2" stroke-opacity="0.4"/>
            `;
        }

        const svg = `
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" width="100%" height="100%">
                <defs>
                    <linearGradient id="${id}_spring" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="${aColor}"/>
                        <stop offset="50%" stop-color="${sColor}"/>
                        <stop offset="100%" stop-color="${pColor}"/>
                    </linearGradient>
                    <linearGradient id="${id}_metal" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#94a3b8"/>
                        <stop offset="50%" stop-color="#f8fafc"/>
                        <stop offset="100%" stop-color="#64748b"/>
                    </linearGradient>
                    <linearGradient id="${id}_glass" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#0284c7" stop-opacity="0.85"/>
                        <stop offset="40%" stop-color="#38bdf8" stop-opacity="0.7"/>
                        <stop offset="100%" stop-color="#0369a1" stop-opacity="0.9"/>
                    </linearGradient>
                    <linearGradient id="${id}_body" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#1e293b"/>
                        <stop offset="50%" stop-color="#475569"/>
                        <stop offset="100%" stop-color="#0f172a"/>
                    </linearGradient>
                    <linearGradient id="${id}_rotor" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#f8fafc"/>
                        <stop offset="40%" stop-color="#cbd5e1"/>
                        <stop offset="80%" stop-color="#94a3b8"/>
                        <stop offset="100%" stop-color="#64748b"/>
                    </linearGradient>
                    <linearGradient id="${id}_hat" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="${pColor}"/>
                        <stop offset="100%" stop-color="${sColor}"/>
                    </linearGradient>
                    <linearGradient id="${id}_ceramic" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#92400e"/>
                        <stop offset="50%" stop-color="#b45309"/>
                        <stop offset="100%" stop-color="#78350f"/>
                    </linearGradient>
                    <filter id="${id}_shadow" x="-10%" y="-10%" width="120%" height="120%">
                        <feDropShadow dx="0" dy="4" stdDeviation="4" flood-color="#0f172a" flood-opacity="0.2"/>
                    </filter>
                </defs>
                <g filter="url(#${id}_shadow)">
                    ${content}
                </g>
            </svg>
        `.trim();

        return 'data:image/svg+xml;utf8,' + encodeURIComponent(svg);
    }

    // --- AI Semantic Classification Engine ---
    function getPartAIMeta(partName) {
        const raw = (partName || '').toLowerCase().trim();
        const customGeneratedSvg = generateDynamicPartSVG(partName);

        // 1. Engine & Cylinder Head
        if (/cylinder\s*head|cylinder\s*block|head\s*assembly/i.test(raw)) {
            return {
                category: 'Engine & Cylinder Head',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-indigo-50 to-blue-50',
                badgeBorder: 'border-indigo-200',
                textClass: 'text-indigo-600',
                dotClass: 'bg-indigo-500',
                glowClass: 'group-hover:border-indigo-400'
            };
        }

        // 2. Engine & Valve Cover
        if (/engine\s*cover|valve\s*cover|cam\s*cover|rocker\s*cover|beauty\s*cover/i.test(raw)) {
            return {
                category: 'Engine & Valve Cover',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-red-50 to-rose-50',
                badgeBorder: 'border-red-200',
                textClass: 'text-red-600',
                dotClass: 'bg-red-500',
                glowClass: 'group-hover:border-red-400'
            };
        }

        // 3. Gaskets & Seals
        if (/head\s*gasket|gasket|seal|o-ring|grommet/i.test(raw)) {
            return {
                category: 'Gaskets & Seals',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-amber-50 to-orange-50',
                badgeBorder: 'border-amber-200',
                textClass: 'text-amber-600',
                dotClass: 'bg-amber-500',
                glowClass: 'group-hover:border-amber-400'
            };
        }

        // 4. Air Intake & Throttle
        if (/intake\s*manifold|throttle\s*body|plenum|air\s*intake/i.test(raw)) {
            return {
                category: 'Air Intake & Throttle',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-sky-50 to-cyan-50',
                badgeBorder: 'border-sky-200',
                textClass: 'text-sky-600',
                dotClass: 'bg-sky-500',
                glowClass: 'group-hover:border-sky-400'
            };
        }

        // 5. Exhaust & Headers
        if (/exhaust\s*manifold|header|downpipe|extractors|exhaust|muffler|catalytic|resonator/i.test(raw)) {
            return {
                category: 'Exhaust & Headers',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-violet-50 to-purple-50',
                badgeBorder: 'border-violet-200',
                textClass: 'text-violet-600',
                dotClass: 'bg-violet-500',
                glowClass: 'group-hover:border-violet-400'
            };
        }

        // 6. Lubrication & Sump
        if (/oil\s*pan|sump|oil\s*sump/i.test(raw)) {
            return {
                category: 'Lubrication & Sump',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-slate-100 to-amber-50',
                badgeBorder: 'border-amber-200',
                textClass: 'text-amber-700',
                dotClass: 'bg-amber-600',
                glowClass: 'group-hover:border-amber-400'
            };
        }

        // 7. Timing & Valvetrain
        if (/timing\s*chain|timing\s*belt|timing\s*kit|cam\s*gear|tensioner/i.test(raw)) {
            return {
                category: 'Timing & Valvetrain',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-yellow-50 to-amber-50',
                badgeBorder: 'border-yellow-200',
                textClass: 'text-yellow-700',
                dotClass: 'bg-yellow-500',
                glowClass: 'group-hover:border-yellow-400'
            };
        }

        // 8. Engine Internals
        if (/piston|connecting\s*rod|wrist\s*pin|crankshaft|camshaft|engine\s*block/i.test(raw)) {
            return {
                category: 'Engine Internals',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-red-50 to-orange-50',
                badgeBorder: 'border-red-200',
                textClass: 'text-red-600',
                dotClass: 'bg-red-500',
                glowClass: 'group-hover:border-red-400'
            };
        }

        // 9. Turbo & Forced Induction
        if (/turbo|turbocharger|supercharger|intercooler|wastegate|blow\s*off/i.test(raw)) {
            return {
                category: 'Turbo & Forced Induction',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-fuchsia-50 to-pink-50',
                badgeBorder: 'border-fuchsia-200',
                textClass: 'text-fuchsia-600',
                dotClass: 'bg-fuchsia-500',
                glowClass: 'group-hover:border-fuchsia-400'
            };
        }

        // 10. Drivetrain & Axles
        if (/cv\s*axle|axle|driveshaft|propeller\s*shaft|half\s*shaft|cv\s*joint|universal\s*joint/i.test(raw)) {
            return {
                category: 'Drivetrain & Axles',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-slate-100 to-zinc-100',
                badgeBorder: 'border-slate-300',
                textClass: 'text-slate-700',
                dotClass: 'bg-slate-600',
                glowClass: 'group-hover:border-slate-400'
            };
        }

        // 11. Drivetrain & Differential
        if (/differential|diff|limited\s*slip|lsd|final\s*drive/i.test(raw)) {
            return {
                category: 'Drivetrain & Differential',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-amber-50 to-yellow-50',
                badgeBorder: 'border-amber-200',
                textClass: 'text-amber-600',
                dotClass: 'bg-amber-500',
                glowClass: 'group-hover:border-amber-400'
            };
        }

        // 12. Clutch & Transmission
        if (/clutch|flywheel|pressure\s*plate|transmission|gearbox/i.test(raw)) {
            return {
                category: 'Clutch & Transmission',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-zinc-100 to-stone-100',
                badgeBorder: 'border-zinc-300',
                textClass: 'text-zinc-700',
                dotClass: 'bg-zinc-600',
                glowClass: 'group-hover:border-zinc-400'
            };
        }

        // 13. Braking System
        if (/brake|pad|rotor|disc|caliper|master\s*cylinder|booster/i.test(raw)) {
            return {
                category: 'Braking System',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-rose-50 to-red-50',
                badgeBorder: 'border-rose-200',
                textClass: 'text-rose-600',
                dotClass: 'bg-rose-500',
                glowClass: 'group-hover:border-rose-400'
            };
        }

        // 14. Suspension System
        if (/shock|strut|coilover|spring|suspension|damper|control\s*arm|wishbone|sway\s*bar/i.test(raw)) {
            return {
                category: 'Suspension System',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-amber-50 to-orange-50',
                badgeBorder: 'border-amber-200',
                textClass: 'text-amber-600',
                dotClass: 'bg-amber-500',
                glowClass: 'group-hover:border-amber-400'
            };
        }

        // 15. Steering System
        if (/steering|rack|pinion|tie\s*rod/i.test(raw)) {
            return {
                category: 'Steering System',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-slate-100 to-blue-50',
                badgeBorder: 'border-slate-300',
                textClass: 'text-slate-700',
                dotClass: 'bg-slate-600',
                glowClass: 'group-hover:border-slate-400'
            };
        }

        // 16. Wheel Hub & Bearings
        if (/wheel\s*bearing|wheel\s*hub|hub\s*assembly|spindle/i.test(raw)) {
            return {
                category: 'Wheel Hub & Bearings',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-slate-100 to-gray-100',
                badgeBorder: 'border-slate-300',
                textClass: 'text-slate-700',
                dotClass: 'bg-slate-600',
                glowClass: 'group-hover:border-slate-400'
            };
        }

        // 17. Tires & Wheels
        if (/tire|tyre|wheel|rim|mag|alloy\s*wheel/i.test(raw)) {
            return {
                category: 'Tires & Wheels',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-slate-100 to-zinc-100',
                badgeBorder: 'border-slate-300',
                textClass: 'text-slate-700',
                dotClass: 'bg-slate-600',
                glowClass: 'group-hover:border-slate-400'
            };
        }

        // 18. Cooling System & Water Pump
        if (/radiator|condenser|cooling|coolant|water\s*pump|thermostat/i.test(raw)) {
            return {
                category: 'Cooling System',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-cyan-50 to-blue-50',
                badgeBorder: 'border-cyan-200',
                textClass: 'text-cyan-600',
                dotClass: 'bg-cyan-500',
                glowClass: 'group-hover:border-cyan-400'
            };
        }

        // 19. Fuel Delivery & Injection
        if (/fuel\s*pump|fuel\s*tank|injector|nozzle|fuel\s*rail/i.test(raw)) {
            return {
                category: 'Fuel Delivery & Injection',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-emerald-50 to-green-50',
                badgeBorder: 'border-emerald-200',
                textClass: 'text-emerald-600',
                dotClass: 'bg-emerald-500',
                glowClass: 'group-hover:border-emerald-400'
            };
        }

        // 20. Ignition System
        if (/spark|plug|ignition|glow\s*plug|iridium/i.test(raw)) {
            return {
                category: 'Ignition System',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-slate-100 to-sky-50',
                badgeBorder: 'border-slate-300',
                textClass: 'text-slate-700',
                dotClass: 'bg-slate-600',
                glowClass: 'group-hover:border-slate-400'
            };
        }

        // 21. Electrical & Charging
        if (/battery|alternator|starter|generator|fuse|relay|horn|ecu/i.test(raw)) {
            return {
                category: 'Electrical & Charging',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-blue-50 to-indigo-50',
                badgeBorder: 'border-blue-200',
                textClass: 'text-blue-600',
                dotClass: 'bg-blue-500',
                glowClass: 'group-hover:border-blue-400'
            };
        }

        // 22. Air Conditioning & Climate
        if (/ac|aircon|compressor|climate|blower|evaporator/i.test(raw)) {
            return {
                category: 'Air Conditioning & Climate',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-sky-50 to-blue-50',
                badgeBorder: 'border-sky-200',
                textClass: 'text-sky-600',
                dotClass: 'bg-sky-500',
                glowClass: 'group-hover:border-sky-400'
            };
        }

        // 23. Sensors & Electronics
        if (/sensor|probe|o2|abs|maf|map|tps|lambda/i.test(raw)) {
            return {
                category: 'Sensors & Electronics',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-teal-50 to-cyan-50',
                badgeBorder: 'border-teal-200',
                textClass: 'text-teal-600',
                dotClass: 'bg-teal-500',
                glowClass: 'group-hover:border-teal-400'
            };
        }

        // 24. Body, Glass & Exterior
        if (/window|glass|windshield|mirror|bumper|door|handle|hood|trunk|fender|grille/i.test(raw)) {
            return {
                category: 'Body & Exterior',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-cyan-50 to-sky-50',
                badgeBorder: 'border-cyan-200',
                textClass: 'text-cyan-600',
                dotClass: 'bg-cyan-500',
                glowClass: 'group-hover:border-cyan-400'
            };
        }

        // 25. Lighting & Lamps
        if (/headlight|light|lamp|bulb|taillight|fog|led|xenon/i.test(raw)) {
            return {
                category: 'Lighting & Lamps',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-amber-50 to-yellow-50',
                badgeBorder: 'border-amber-200',
                textClass: 'text-amber-600',
                dotClass: 'bg-amber-500',
                glowClass: 'group-hover:border-amber-400'
            };
        }

        // 26. Filtration System
        if (/filter/i.test(raw)) {
            return {
                category: 'Filtration System',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-sky-50 to-blue-50/80',
                badgeBorder: 'border-sky-200/90',
                textClass: 'text-sky-600',
                dotClass: 'bg-sky-500',
                glowClass: 'group-hover:border-sky-400'
            };
        }

        // 27. Fluids & Lubricants
        if (/oil|fluid|lubricant|synthetic|atf/i.test(raw)) {
            return {
                category: 'Fluids & Lubricants',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-amber-50 to-yellow-50/80',
                badgeBorder: 'border-amber-200/90',
                textClass: 'text-amber-600',
                dotClass: 'bg-amber-500',
                glowClass: 'group-hover:border-amber-400'
            };
        }

        // 28. Hardware & Fasteners
        if (/bolt|nut|screw|fastener|bracket|mount/i.test(raw)) {
            return {
                category: 'Hardware & Fasteners',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-slate-100 to-zinc-100',
                badgeBorder: 'border-slate-300',
                textClass: 'text-slate-700',
                dotClass: 'bg-slate-600',
                glowClass: 'group-hover:border-slate-400'
            };
        }

        // Default Auto Component
        return {
            category: 'Auto Component',
            imageUrl: customGeneratedSvg,
            badgeBg: 'bg-gradient-to-br from-slate-50 to-blue-50/60',
            badgeBorder: 'border-slate-200/90',
            textClass: 'text-slate-600',
            dotClass: 'bg-blue-500',
            glowClass: 'group-hover:border-blue-400'
        };
    }

    // ── Instant Real-Time Input Handler ──
    function onPartNameInput(name) {
        updateAIMiniModalPreview(name);
    }

    // ── Live AI Preview Update in Add/Edit Part Modal ──
    function updateAIMiniModalPreview(name) {
        const meta = getPartAIMeta(name);
        const iconBox = document.getElementById('aiDetectorIconBox');
        const catLabel = document.getElementById('aiDetectorCategoryName');
        const previewContainer = document.getElementById('aiPartDetectorPreview');
        const confidenceBadge = document.getElementById('aiConfidenceBadge');
        const imgSourceLabel = document.getElementById('imgSourceLabel');

        const activeImg = meta.imageUrl;
        const hiddenImg = document.getElementById('newPartImageUrl');
        if (hiddenImg) hiddenImg.value = activeImg;

        if (iconBox) {
            iconBox.className = `w-16 h-16 rounded-2xl p-1.5 flex items-center justify-center shrink-0 border ${meta.badgeBorder} ${meta.badgeBg} shadow-sm transition-all duration-300 transform scale-100 hover:scale-105 cursor-pointer bg-white`;
            iconBox.innerHTML = `<img src="${activeImg}" alt="Procedural Vector Preview" class="w-full h-full object-contain filter drop-shadow-sm rounded-xl">`;
            iconBox.onclick = () => openImageModal(activeImg);
        }

        if (catLabel) {
            catLabel.innerText = meta.category;
            catLabel.className = `text-sm font-black ${meta.textClass} tracking-tight mt-0.5 truncate`;
        }

        if (previewContainer) {
            previewContainer.className = `p-4 rounded-2xl border transition-all duration-300 ${meta.badgeBg} ${meta.badgeBorder} flex items-center gap-4 shadow-xs`;
        }

        if (confidenceBadge) {
            confidenceBadge.innerText = name && name.trim().length > 1 ? '✨ Realtime SVG' : 'Live Generator';
            confidenceBadge.className = name && name.trim().length > 1 ? `text-[9px] font-black uppercase px-2 py-0.5 rounded-full bg-white shadow-xs ${meta.textClass}` : 'text-[9px] font-bold text-slate-400';
        }

        if (imgSourceLabel) {
            imgSourceLabel.innerText = `Procedural Vector · ${meta.category}`;
            imgSourceLabel.className = `${meta.textClass} font-bold`;
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
            const partImg = generateDynamicPartSVG(p.name);

            return `
            <tr class="hover:bg-gray-50/80 transition-colors group">
                <td class="px-8 py-4">
                    <div class="flex items-center gap-3.5">
                        <div class="relative w-12 h-12 rounded-2xl p-1 flex items-center justify-center shrink-0 border ${aiMeta.badgeBorder} ${aiMeta.badgeBg} shadow-xs group-hover:scale-105 ${aiMeta.glowClass} transition-all cursor-pointer bg-white overflow-hidden"
                             onclick="openImageModal('${addslashes(partImg)}')"
                             title="Click to view procedural vector">
                            <img src="${partImg}" alt="${escapeHtml(p.name)}" class="w-full h-full object-contain filter drop-shadow-sm rounded-xl">
                        </div>
                        <div class="min-w-0">
                            <div class="text-sm font-black text-gray-900 tracking-tight group-hover:text-blue-600 transition-colors truncate">${escapeHtml(p.name)}</div>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider ${aiMeta.textClass}">
                                    <span class="w-1.5 h-1.5 rounded-full ${aiMeta.dotClass}"></span>
                                    ${p.category || aiMeta.category}
                                </span>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-8 py-4">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider ${p.supplier ? 'bg-purple-50 text-purple-700' : 'bg-gray-100 text-gray-500'}">
                        ${escapeHtml(p.supplier || 'Unspecified')}
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
            const partImg = generateDynamicPartSVG(ph.description);

            return `
            <tr class="hover:bg-gray-50/80 transition-colors">
                <td class="px-8 py-5 whitespace-nowrap">
                    <div class="text-sm font-bold text-gray-700">${dateStr}</div>
                </td>
                <td class="px-8 py-5">
                    <div class="flex items-center gap-3.5">
                        <div class="relative w-11 h-11 rounded-2xl p-1 flex items-center justify-center shrink-0 border ${aiMeta.badgeBorder} ${aiMeta.badgeBg} shadow-xs cursor-pointer bg-white overflow-hidden"
                             onclick="openImageModal('${addslashes(partImg)}')"
                             title="Click to view procedural vector">
                            <img src="${partImg}" alt="${escapeHtml(ph.description)}" class="w-full h-full object-contain filter drop-shadow-sm rounded-xl">
                        </div>
                        <div class="min-w-0">
                            <div class="text-sm font-black text-gray-800 tracking-tight">${escapeHtml(ph.description)}</div>
                            <div class="text-xs text-blue-500 font-bold uppercase mt-0.5 tracking-wider">${escapeHtml(ph.category)}</div>
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
            const partImg = generateDynamicPartSVG(p.name);

            return `
            <tr class="hover:bg-gray-50/80 transition-colors group">
                <td class="px-8 py-4">
                    <div class="flex items-center gap-3.5">
                        <div class="relative w-11 h-11 rounded-2xl p-1 flex items-center justify-center shrink-0 border ${aiMeta.badgeBorder} ${aiMeta.badgeBg} shadow-xs cursor-pointer bg-white overflow-hidden"
                             onclick="openImageModal('${addslashes(partImg)}')"
                             title="Click to view procedural vector">
                            <img src="${partImg}" alt="${escapeHtml(p.name)}" class="w-full h-full object-contain filter drop-shadow-sm rounded-xl">
                        </div>
                        <div class="min-w-0">
                            <div class="text-sm font-black text-gray-700 tracking-tight">${escapeHtml(p.name)}</div>
                            <div class="flex items-center gap-1 mt-0.5">
                                <span class="text-[10px] font-black uppercase tracking-wider ${aiMeta.textClass}">${p.category || aiMeta.category}</span>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-8 py-4 text-xs font-bold text-gray-400 uppercase">${escapeHtml(p.supplier || 'Unspecified')}</td>
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
            document.getElementById('newPartImageUrl').value = '';
            
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
        document.getElementById('newPartImageUrl').value = generateDynamicPartSVG(name);

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
        const name = document.getElementById('newPartName').value.trim();
        const price = document.getElementById('newPartPrice').value;
        const qty_to_add = parseInt(document.getElementById('newPartQty').value) || 0;
        const supplier = document.getElementById('newPartSupplier').value;
        const image_url = generateDynamicPartSVG(name);
        const meta = getPartAIMeta(name);

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
                body: JSON.stringify({
                    id,
                    name,
                    category: meta.category,
                    price,
                    qty_to_add,
                    supplier,
                    image_url
                })
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

    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/[&<>"']/g, function(m) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
        });
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

    /* ─── Part Image Lightbox Modal ────────────────────────────── */
    function openImageModal(src) {
        if (!src) return;
        let modal = document.getElementById('imagePreviewModalOverlay');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'imagePreviewModalOverlay';
            modal.className = 'fixed inset-0 bg-slate-900/95 backdrop-blur-md z-[9999] flex items-center justify-center hidden opacity-0 transition-opacity duration-300';
            modal.onclick = function(e) {
                if (e.target === modal) closeImageModal();
            };
            modal.innerHTML = `
                <button type="button" class="absolute top-6 right-6 text-white/70 hover:text-white bg-white/10 hover:bg-white/20 p-3 rounded-full transition-colors group cursor-pointer" onclick="closeImageModal()">
                    <i data-lucide="x" class="w-6 h-6 group-hover:scale-110 transition-transform"></i>
                </button>
                <div class="relative w-full max-w-[85vw] max-h-[85vh] flex justify-center items-center p-6">
                    <img id="imagePreviewModalImg" src="" class="max-w-full max-h-[75vh] object-contain rounded-3xl shadow-2xl scale-95 transition-transform duration-300 bg-white/5 p-6 border border-white/10" />
                </div>
            `;
            document.body.appendChild(modal);
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
        
        const img = document.getElementById('imagePreviewModalImg');
        img.src = src;
        
        modal.classList.remove('hidden');
        void modal.offsetWidth;
        modal.classList.remove('opacity-0');
        img.classList.remove('scale-95');
        img.classList.add('scale-100');
    }

    function closeImageModal() {
        const modal = document.getElementById('imagePreviewModalOverlay');
        const img = document.getElementById('imagePreviewModalImg');
        if (modal) {
            modal.classList.add('opacity-0');
            if (img) {
                img.classList.remove('scale-100');
                img.classList.add('scale-95');
            }
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    }

    // ── Bind All Functions to Window to Guarantee Global Accessibility ──
    window.showToast = showToast;
    window.switchTab = switchTab;
    window.filterTables = filterTables;
    window.loadActiveParts = loadActiveParts;
    window.loadHistory = loadHistory;
    window.loadArchivedParts = loadArchivedParts;
    window.loadSuppliers = loadSuppliers;
    window.generateDynamicPartSVG = generateDynamicPartSVG;
    window.getPartAIMeta = getPartAIMeta;
    window.onPartNameInput = onPartNameInput;
    window.updateAIMiniModalPreview = updateAIMiniModalPreview;
    window.renderActiveParts = renderActiveParts;
    window.renderHistory = renderHistory;
    window.renderArchivedParts = renderArchivedParts;
    window.openPartMiniModal = openPartMiniModal;
    window.closePartMiniModal = closePartMiniModal;
    window.editPart = editPart;
    window.saveNewPart = saveNewPart;
    window.escapeHtml = escapeHtml;
    window.archivePart = archivePart;
    window.restorePart = restorePart;
    window.forceDeletePart = forceDeletePart;
    window.openSuppliersModal = openSuppliersModal;
    window.closeSuppliersModal = closeSuppliersModal;
    window.resetSupplierForm = resetSupplierForm;
    window.renderSuppliers = renderSuppliers;
    window.saveSupplier = saveSupplier;
    window.editSupplier = editSupplier;
    window.deleteSupplier = deleteSupplier;
    window.addslashes = addslashes;
    window.openImageModal = openImageModal;
    window.closeImageModal = closeImageModal;
    window.initInventoryPage = initInventoryPage;
</script>
@endsection


