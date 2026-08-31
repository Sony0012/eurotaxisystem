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

    // ── AI Morphological & Attribute-Aware Automotive Vector Engine (100% Unique & Distinct Graphics) ──
    function generateDynamicPartSVG(partName) {
        const raw = (partName || 'Auto Part').toLowerCase().trim();
        const id = 'svg_' + Math.random().toString(36).substring(2, 9);

        // Attribute Analysis
        const isFront = /front|fr|f\//i.test(raw);
        const isRear = /rear|rr|r\//i.test(raw);
        const isLeft = /left|lh|driver/i.test(raw);
        const isRight = /right|rh|pass/i.test(raw);
        const isGenuine = /genuine|orig|oem|toyota|mitsubishi|nissan|honda|hyundai|isuzu/i.test(raw);
        const isCeramic = /ceramic|carbon|kevlar/i.test(raw);
        const isDrilled = /drill|slot|vent/i.test(raw);

        // Dynamic Color Extraction
        let pColor = '#2563eb', sColor = '#3b82f6', aColor = '#60a5fa'; // Default Racing Blue
        if (/red|brembo|ferodo|sport|racing|sti|type\s*r|momo/i.test(raw)) {
            pColor = '#dc2626'; sColor = '#ef4444'; aColor = '#f87171';
        } else if (/gold|yellow|motolite|ohlins|amaron|amber/i.test(raw)) {
            pColor = '#d97706'; sColor = '#f59e0b'; aColor = '#fbbf24';
        } else if (/green|tein|monster|eco|hybrid|lime/i.test(raw)) {
            pColor = '#16a34a'; sColor = '#22c55e'; aColor = '#4ade80';
        } else if (/black|dark|carbon|shadow|stealth|matte/i.test(raw)) {
            pColor = '#1e293b'; sColor = '#334155'; aColor = '#64748b';
        } else if (/purple|hks/i.test(raw)) {
            pColor = '#9333ea'; sColor = '#a855f7'; aColor = '#c084fc';
        } else if (/cyan|sky|teal|cool|blue|ice/i.test(raw)) {
            pColor = '#0284c7'; sColor = '#38bdf8'; aColor = '#bae6fd';
        }

        let content = '';

        // ══════════════════════════════════════════════════════════════════
        // TIER 1: HIGH-PRECISION BRAKE SUB-COMPONENTS (Shoes vs Pads vs Calipers)
        // ══════════════════════════════════════════════════════════════════
        
        // 1. DRUM BRAKE SHOES (Curved crescent shoes with springs & wheel cylinder)
        if (/brake\s*shoe|drum\s*brake|rear\s*shoe/i.test(raw)) {
            content = `
                <circle cx="100" cy="100" r="76" fill="none" stroke="#1e293b" stroke-width="4" stroke-dasharray="6,6"/>
                <rect x="75" y="24" width="50" height="24" rx="6" fill="url(#${id}_metal)" stroke="#334155" stroke-width="2"/>
                <rect x="68" y="28" width="12" height="16" rx="3" fill="#0f172a"/>
                <rect x="120" y="28" width="12" height="16" rx="3" fill="#0f172a"/>

                <path d="M 68 38 A 68 68 0 0 0 68 162 L 60 156 A 76 76 0 0 1 60 44 Z" fill="url(#${id}_ceramic)" stroke="#78350f" stroke-width="1.5"/>
                <path d="M 72 45 A 60 60 0 0 0 72 155" fill="none" stroke="url(#${id}_metal)" stroke-width="8" stroke-linecap="round"/>

                <path d="M 132 38 A 68 68 0 0 1 132 162 L 140 156 A 76 76 0 0 0 140 44 Z" fill="url(#${id}_ceramic)" stroke="#78350f" stroke-width="1.5"/>
                <path d="M 128 45 A 60 60 0 0 1 128 155" fill="none" stroke="url(#${id}_metal)" stroke-width="8" stroke-linecap="round"/>

                <path d="M 75 55 L 82 50 L 90 58 L 98 50 L 106 58 L 114 50 L 125 55" fill="none" stroke="#f59e0b" stroke-width="3" stroke-linecap="round"/>
                <rect x="85" y="148" width="30" height="14" rx="3" fill="url(#${id}_metal)"/>
                <circle cx="100" cy="155" r="5" fill="#f59e0b"/>
                <path d="M 75 145 L 82 140 L 90 148 L 98 140 L 106 148 L 114 140 L 125 145" fill="none" stroke="#f59e0b" stroke-width="3" stroke-linecap="round"/>

                <circle cx="65" cy="100" r="6" fill="#38bdf8"/>
                <circle cx="135" cy="100" r="6" fill="#38bdf8"/>
                <circle cx="100" cy="100" r="16" fill="#1e293b"/>
                <rect x="65" y="174" width="70" height="14" rx="3" fill="#1e293b"/>
                <text x="100" y="184" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#f59e0b">DRUM SHOES</text>
            `;
        }
        // 2. BRAKE CALIPER (Multi-piston monoblock housing with bleed screw)
        else if (/caliper|monoblock|brake\s*caliper/i.test(raw)) {
            content = `
                <path d="M 35 60 Q 100 45 165 60 L 165 140 Q 100 155 35 140 Z" fill="${pColor}" stroke="#0f172a" stroke-width="3"/>
                <rect x="50" y="70" width="100" height="60" rx="8" fill="#1e293b"/>
                <circle cx="75" cy="100" r="18" fill="url(#${id}_metal)" stroke="#94a3b8" stroke-width="2"/>
                <circle cx="75" cy="100" r="10" fill="#0f172a"/>
                <circle cx="125" cy="100" r="18" fill="url(#${id}_metal)" stroke="#94a3b8" stroke-width="2"/>
                <circle cx="125" cy="100" r="10" fill="#0f172a"/>
                <rect x="94" y="32" width="12" height="20" rx="3" fill="url(#${id}_metal)"/>
                <circle cx="100" cy="38" r="3" fill="#ef4444"/>
                <text x="100" y="148" font-size="9" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#ffffff">4-PISTON CALIPER</text>
            `;
        }
        // 3. BRAKE PADS (Distinct Front vs Rear vs Genuine OEM vs Standard)
        else if (/brake\s*pad|disc\s*pad/i.test(raw)) {
            if (isFront || isGenuine) {
                // High-End Heavy Duty Front Pad Assembly with Chamfers & OEM Stamp
                content = `
                    <rect x="25" y="38" width="150" height="52" rx="8" fill="#1e293b" stroke="#334155" stroke-width="2"/>
                    <rect x="38" y="44" width="124" height="40" rx="4" fill="url(#${id}_ceramic)"/>
                    <polygon points="38,44 54,44 38,84" fill="#78350f" opacity="0.7"/>
                    <polygon points="162,44 146,44 162,84" fill="#78350f" opacity="0.7"/>
                    <line x1="100" y1="44" x2="100" y2="84" stroke="#451a03" stroke-width="4"/>
                    <rect x="18" y="70" width="14" height="16" rx="2" fill="#eab308"/>
                    
                    <rect x="25" y="108" width="150" height="52" rx="8" fill="#1e293b" stroke="#334155" stroke-width="2"/>
                    <rect x="38" y="114" width="124" height="40" rx="4" fill="url(#${id}_ceramic)"/>
                    <polygon points="38,114 54,114 38,154" fill="#78350f" opacity="0.7"/>
                    <polygon points="162,114 146,114 162,154" fill="#78350f" opacity="0.7"/>
                    <line x1="100" y1="114" x2="100" y2="154" stroke="#451a03" stroke-width="4"/>
                    <rect x="168" y="140" width="14" height="16" rx="2" fill="#eab308"/>

                    <rect x="65" y="93" width="70" height="14" rx="3" fill="#facc15"/>
                    <text x="100" y="103" font-size="7" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#0f172a">GENUINE FRONT</text>
                `;
            } else if (isRear) {
                // Compact Rear Pad Assembly with Parking Brake Retainer
                content = `
                    <rect x="35" y="48" width="130" height="42" rx="6" fill="#334155"/>
                    <rect x="45" y="54" width="110" height="30" rx="4" fill="url(#${id}_ceramic)"/>
                    <rect x="35" y="108" width="130" height="42" rx="6" fill="#334155"/>
                    <rect x="45" y="114" width="110" height="30" rx="4" fill="url(#${id}_ceramic)"/>
                    <circle cx="100" cy="70" r="6" fill="#94a3b8"/>
                    <circle cx="100" cy="130" r="6" fill="#94a3b8"/>
                    <rect x="70" y="94" width="60" height="12" rx="2" fill="#e2e8f0"/>
                    <text x="100" y="103" font-size="7" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#1e293b">REAR AXLE</text>
                `;
            } else {
                // Standard Disc Brake Pads
                content = `
                    <rect x="30" y="45" width="140" height="45" rx="6" fill="#334155"/>
                    <rect x="42" y="52" width="116" height="34" rx="4" fill="${pColor}"/>
                    <rect x="98" y="52" width="4" height="34" fill="#1e293b"/>
                    <rect x="25" y="105" width="150" height="52" rx="8" fill="#1e293b"/>
                    <rect x="38" y="112" width="124" height="40" rx="4" fill="url(#${id}_ceramic)"/>
                    <polygon points="38,112 55,112 38,152" fill="#78350f" opacity="0.6"/>
                    <polygon points="162,112 145,112 162,152" fill="#78350f" opacity="0.6"/>
                    <rect x="98" y="112" width="4" height="40" fill="#451a03"/>
                `;
            }
        }

        // ══════════════════════════════════════════════════════════════════
        // TIER 2: CAPS, LIDS, RINGS & GASKETS
        // ══════════════════════════════════════════════════════════════════
        else if (/radiator\s*cap/i.test(raw)) {
            content = `
                <rect x="25" y="86" width="150" height="28" rx="14" fill="url(#${id}_metal)" stroke="#334155" stroke-width="2"/>
                <circle cx="38" cy="100" r="8" fill="#1e293b"/>
                <circle cx="162" cy="100" r="8" fill="#1e293b"/>
                <circle cx="100" cy="100" r="54" fill="url(#${id}_metal)" stroke="#475569" stroke-width="2.5"/>
                <circle cx="100" cy="100" r="44" fill="${pColor}"/>
                <circle cx="100" cy="100" r="36" fill="#0f172a"/>
                <polygon points="100,78 118,110 82,110" fill="#facc15" stroke="#ca8a04" stroke-width="1.5"/>
                <text x="100" y="105" font-size="12" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#0f172a">!</text>
                <text x="100" y="124" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#f8fafc">1.1 BAR</text>
                <text x="100" y="133" font-size="6" font-weight="bold" font-family="sans-serif" text-anchor="middle" fill="#94a3b8">DO NOT OPEN HOT</text>
                <rect x="94" y="32" width="12" height="54" rx="2" fill="url(#${id}_metal)"/>
                ${[38, 48, 58, 68].map(y => `
                    <line x1="88" y1="${y}" x2="112" y2="${y+4}" stroke="#cbd5e1" stroke-width="3.5" stroke-linecap="round"/>
                `).join('')}
                <ellipse cx="100" cy="32" rx="28" ry="8" fill="#0f172a"/>
                <ellipse cx="100" cy="30" rx="22" ry="6" fill="#ef4444"/>
            `;
        }
        else if (/fuel\s*cap|gas\s*cap|filler\s*cap/i.test(raw)) {
            content = `
                <circle cx="100" cy="100" r="68" fill="#1e293b" stroke="#334155" stroke-width="3"/>
                <rect x="45" y="86" width="110" height="28" rx="14" fill="${pColor}"/>
                <circle cx="100" cy="100" r="16" fill="#0f172a"/>
                <path d="M 94 92 L 106 92 L 106 108 L 94 108 Z" fill="#facc15"/>
                <path d="M 100 130 Q 80 160 50 165" fill="none" stroke="#64748b" stroke-width="4" stroke-linecap="round"/>
                <circle cx="50" cy="165" r="5" fill="#f59e0b"/>
            `;
        }
        else if (/oil\s*(filler\s*)?cap/i.test(raw)) {
            content = `
                <circle cx="100" cy="100" r="68" fill="#1e293b" stroke="#334155" stroke-width="3"/>
                ${Array.from({length: 12}).map((_, i) => {
                    const rad = (i * 30) * Math.PI / 180;
                    return `<rect x="${(100 + 64 * Math.cos(rad) - 4).toFixed(1)}" y="${(100 + 64 * Math.sin(rad) - 4).toFixed(1)}" width="8" height="8" rx="2" fill="#475569"/>`;
                }).join('')}
                <circle cx="100" cy="100" r="50" fill="${pColor}"/>
                <circle cx="100" cy="100" r="42" fill="#0f172a"/>
                <path d="M 85 95 L 115 95 L 122 112 L 78 112 Z" fill="#facc15"/>
                <path d="M 115 95 Q 128 85 132 90 L 125 102" fill="none" stroke="#facc15" stroke-width="3" stroke-linecap="round"/>
                <circle cx="134" cy="98" r="2.5" fill="#facc15"/>
                <rect x="90" y="88" width="20" height="7" rx="2" fill="#facc15"/>
                <text x="100" y="125" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#f8fafc">SAE 5W-30</text>
            `;
        }
        else if (/piston\s*ring|compression\s*ring|oil\s*ring|scraper\s*ring/i.test(raw)) {
            content = `
                <path d="M 45 48 A 65 65 0 1 1 155 48" fill="none" stroke="url(#${id}_metal)" stroke-width="11" stroke-linecap="round"/>
                <rect x="94" y="24" width="12" height="7" fill="#0f172a"/>
                <path d="M 55 98 A 55 55 0 1 1 145 98" fill="none" stroke="#475569" stroke-width="9" stroke-linecap="round"/>
                <rect x="95" y="86" width="10" height="6" fill="#0f172a"/>
                <path d="M 65 146 A 45 45 0 1 1 135 146" fill="none" stroke="#d97706" stroke-width="6" stroke-dasharray="4,2"/>
                <path d="M 68 144 A 42 42 0 1 1 132 144" fill="none" stroke="url(#${id}_metal)" stroke-width="2.5"/>
                <path d="M 68 148 A 48 48 0 1 1 132 148" fill="none" stroke="url(#${id}_metal)" stroke-width="2.5"/>
            `;
        }
        else if (/head\s*gasket/i.test(raw)) {
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
        else if (/gasket|seal|o-ring/i.test(raw)) {
            content = `
                <circle cx="100" cy="100" r="70" fill="none" stroke="#334155" stroke-width="18"/>
                <circle cx="100" cy="100" r="70" fill="none" stroke="${pColor}" stroke-width="12"/>
                <circle cx="100" cy="100" r="58" fill="none" stroke="#0f172a" stroke-width="2"/>
            `;
        }

        // ══════════════════════════════════════════════════════════════════
        // TIER 3: HOSES, PIPES, DUCTS, FANS & CABLES
        // ══════════════════════════════════════════════════════════════════
        else if (/radiator\s*hose|coolant\s*hose|heater\s*hose|coolant\s*pipe/i.test(raw)) {
            content = `
                <path d="M 35 140 Q 55 55 125 55 L 165 75" fill="none" stroke="${pColor}" stroke-width="26" stroke-linecap="round"/>
                <path d="M 35 140 Q 55 55 125 55 L 165 75" fill="none" stroke="#ffffff" stroke-width="3" stroke-linecap="round" opacity="0.4"/>
                <rect x="25" y="125" width="14" height="30" rx="3" fill="url(#${id}_metal)"/>
                <circle cx="32" cy="140" r="3" fill="#0f172a"/>
                <rect x="150" y="60" width="14" height="30" rx="3" fill="url(#${id}_metal)"/>
                <circle cx="157" cy="75" r="3" fill="#0f172a"/>
            `;
        }
        else if (/radiator\s*fan|cooling\s*fan|fan\s*blade/i.test(raw)) {
            content = `
                <rect x="25" y="25" width="150" height="150" rx="16" fill="#1e293b" stroke="#334155" stroke-width="2.5"/>
                <circle cx="100" cy="100" r="68" fill="#0f172a"/>
                ${Array.from({length: 7}).map((_, i) => {
                    const rad = (i * (360/7)) * Math.PI / 180;
                    const radEnd = (i * (360/7) + 38) * Math.PI / 180;
                    const x1 = (100 + 26 * Math.cos(rad)).toFixed(1);
                    const y1 = (100 + 26 * Math.sin(rad)).toFixed(1);
                    const x2 = (100 + 64 * Math.cos(radEnd)).toFixed(1);
                    const y2 = (100 + 64 * Math.sin(radEnd)).toFixed(1);
                    return `<path d="M 100 100 Q ${x1} ${y1} ${x2} ${y2} A 64 64 0 0 0 ${(100 + 64 * Math.cos(rad)).toFixed(1)} ${(100 + 64 * Math.sin(rad)).toFixed(1)} Z" fill="${pColor}" opacity="0.9"/>`;
                }).join('')}
                <circle cx="100" cy="100" r="25" fill="url(#${id}_metal)"/>
                <circle cx="100" cy="100" r="14" fill="#0f172a"/>
                <circle cx="100" cy="100" r="6" fill="#f59e0b"/>
            `;
        }
        else if (/brake\s*hose|brake\s*line/i.test(raw)) {
            content = `
                <path d="M 35 160 Q 80 50 165 40" fill="none" stroke="#475569" stroke-width="12" stroke-linecap="round"/>
                <path d="M 35 160 Q 80 50 165 40" fill="none" stroke="url(#${id}_metal)" stroke-width="4" stroke-dasharray="3,2"/>
                <rect x="25" y="150" width="20" height="20" rx="4" fill="#f59e0b"/>
                <circle cx="35" cy="160" r="5" fill="#0f172a"/>
                <rect x="155" y="30" width="20" height="20" rx="4" fill="#f59e0b"/>
                <circle cx="165" cy="40" r="5" fill="#0f172a"/>
            `;
        }

        // ══════════════════════════════════════════════════════════════════
        // TIER 4: MAJOR MECHANICAL ASSEMBLIES
        // ══════════════════════════════════════════════════════════════════
        else if (/cylinder\s*head|head\s*assembly/i.test(raw)) {
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
        else if (/crankshaft|stroker\s*crank/i.test(raw)) {
            content = `
                <line x1="30" y1="100" x2="170" y2="100" stroke="#334155" stroke-width="12" stroke-linecap="round"/>
                <rect x="25" y="90" width="20" height="20" rx="3" fill="url(#${id}_metal)"/>
                <rect x="65" y="70" width="18" height="22" rx="3" fill="url(#${id}_metal)"/>
                <rect x="95" y="110" width="18" height="22" rx="3" fill="url(#${id}_metal)"/>
                <rect x="125" y="70" width="18" height="22" rx="3" fill="url(#${id}_metal)"/>
                <rect x="155" y="90" width="20" height="20" rx="3" fill="url(#${id}_metal)"/>
                <path d="M 45 90 C 40 45, 80 45, 75 90 Z" fill="#1e293b"/>
                <path d="M 75 110 C 70 155, 110 155, 105 110 Z" fill="#1e293b"/>
                <path d="M 105 90 C 100 45, 140 45, 135 90 Z" fill="#1e293b"/>
                <path d="M 135 110 C 130 155, 170 155, 165 110 Z" fill="#1e293b"/>
            `;
        }
        else if (/camshaft|cam\s*shaft/i.test(raw)) {
            content = `
                <line x1="25" y1="100" x2="175" y2="100" stroke="url(#${id}_metal)" stroke-width="16" stroke-linecap="round"/>
                ${[45, 75, 105, 135, 165].map((x, i) => `
                    <ellipse cx="${x}" cy="${i%2===0?90:110}" rx="14" ry="22" fill="#1e293b" stroke="url(#${id}_metal)" stroke-width="2"/>
                    <circle cx="${x}" cy="100" r="6" fill="#94a3b8"/>
                `).join('')}
                <circle cx="25" cy="100" r="16" fill="${pColor}"/>
            `;
        }
        else if (/piston|connecting\s*rod|conrod/i.test(raw)) {
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
        else if (/shock|strut|coilover|spring|damper/i.test(raw)) {
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
        else if (/radiator/i.test(raw)) {
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
        else if (/water\s*pump|thermostat/i.test(raw)) {
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
        else if (/alternator|generator/i.test(raw)) {
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
        else if (/battery|motolite|amaron|12v/i.test(raw)) {
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

    // --- AI Semantic Categorization Engine (Strict Distinct Categories) ---
    function getPartAIMeta(partName) {
        const raw = (partName || '').toLowerCase().trim();
        const customGeneratedSvg = generateDynamicPartSVG(partName);

        // 1. Drum Brake Shoes
        if (/brake\s*shoe|drum\s*brake|rear\s*shoe/i.test(raw)) {
            return {
                category: 'Braking (Drum Brake Shoes)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-amber-50 to-orange-50',
                badgeBorder: 'border-amber-200',
                textClass: 'text-amber-600',
                dotClass: 'bg-amber-500',
                glowClass: 'group-hover:border-amber-400'
            };
        }

        // 2. Brake Calipers
        if (/caliper|monoblock|brake\s*caliper/i.test(raw)) {
            return {
                category: 'Braking (Brake Calipers)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-red-50 to-rose-50',
                badgeBorder: 'border-red-200',
                textClass: 'text-red-600',
                dotClass: 'bg-red-500',
                glowClass: 'group-hover:border-red-400'
            };
        }

        // 3. Brake Pads (Front, Rear, OEM)
        if (/brake\s*pad|disc\s*pad/i.test(raw)) {
            const isFront = /front|fr/i.test(raw);
            const isRear = /rear|rr/i.test(raw);
            const label = isFront ? 'Braking (Front Brake Pads)' : (isRear ? 'Braking (Rear Brake Pads)' : 'Braking (Brake Pads)');
            return {
                category: label,
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-rose-50 to-red-50',
                badgeBorder: 'border-rose-200',
                textClass: 'text-rose-600',
                dotClass: 'bg-rose-500',
                glowClass: 'group-hover:border-rose-400'
            };
        }

        // 4. Radiator Cap
        if (/radiator\s*cap/i.test(raw)) {
            return {
                category: 'Cooling (Radiator Cap)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-cyan-50 to-blue-50',
                badgeBorder: 'border-cyan-200',
                textClass: 'text-cyan-600',
                dotClass: 'bg-cyan-500',
                glowClass: 'group-hover:border-cyan-400'
            };
        }

        // 5. Fuel Cap
        if (/fuel\s*cap|gas\s*cap|filler\s*cap/i.test(raw)) {
            return {
                category: 'Fuel Delivery (Fuel Cap)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-emerald-50 to-green-50',
                badgeBorder: 'border-emerald-200',
                textClass: 'text-emerald-600',
                dotClass: 'bg-emerald-500',
                glowClass: 'group-hover:border-emerald-400'
            };
        }

        // 6. Oil Cap
        if (/oil\s*(filler\s*)?cap/i.test(raw)) {
            return {
                category: 'Lubrication (Oil Cap)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-amber-50 to-yellow-50',
                badgeBorder: 'border-amber-200',
                textClass: 'text-amber-600',
                dotClass: 'bg-amber-500',
                glowClass: 'group-hover:border-amber-400'
            };
        }

        // 7. Piston Rings
        if (/piston\s*ring|compression\s*ring|oil\s*ring|scraper\s*ring/i.test(raw)) {
            return {
                category: 'Engine Internals (Piston Rings)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-amber-50 to-orange-50',
                badgeBorder: 'border-amber-200',
                textClass: 'text-amber-600',
                dotClass: 'bg-amber-500',
                glowClass: 'group-hover:border-amber-400'
            };
        }

        // 8. Head Gasket
        if (/head\s*gasket/i.test(raw)) {
            return {
                category: 'Gaskets & Seals (Head Gasket)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-amber-50 to-yellow-50',
                badgeBorder: 'border-amber-200',
                textClass: 'text-amber-600',
                dotClass: 'bg-amber-500',
                glowClass: 'group-hover:border-amber-400'
            };
        }

        // 9. Radiator Hose
        if (/radiator\s*hose|coolant\s*hose|heater\s*hose|coolant\s*pipe/i.test(raw)) {
            return {
                category: 'Cooling Hoses & Pipes',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-cyan-50 to-blue-50',
                badgeBorder: 'border-cyan-200',
                textClass: 'text-cyan-600',
                dotClass: 'bg-cyan-500',
                glowClass: 'group-hover:border-cyan-400'
            };
        }

        // 10. Radiator Fan
        if (/radiator\s*fan|cooling\s*fan|fan\s*blade/i.test(raw)) {
            return {
                category: 'Cooling (Radiator Fan)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-cyan-50 to-blue-50',
                badgeBorder: 'border-cyan-200',
                textClass: 'text-cyan-600',
                dotClass: 'bg-cyan-500',
                glowClass: 'group-hover:border-cyan-400'
            };
        }

        // 11. Cylinder Head
        if (/cylinder\s*head|head\s*assembly/i.test(raw)) {
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

        // 12. Valve Cover
        if (/engine\s*cover|valve\s*cover|cam\s*cover|rocker\s*cover/i.test(raw)) {
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

        // 13. Crankshaft
        if (/crankshaft|stroker\s*crank/i.test(raw)) {
            return {
                category: 'Engine Internals (Crankshaft)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-slate-100 to-zinc-100',
                badgeBorder: 'border-slate-300',
                textClass: 'text-slate-700',
                dotClass: 'bg-slate-600',
                glowClass: 'group-hover:border-slate-400'
            };
        }

        // 14. Pistons
        if (/piston|connecting\s*rod|conrod/i.test(raw)) {
            return {
                category: 'Engine Internals (Pistons)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-red-50 to-orange-50',
                badgeBorder: 'border-red-200',
                textClass: 'text-red-600',
                dotClass: 'bg-red-500',
                glowClass: 'group-hover:border-red-400'
            };
        }

        // 15. Brake Rotors
        if (/rotor|disc|disk|brake\s*rotor/i.test(raw)) {
            return {
                category: 'Braking (Rotors)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-rose-50 to-red-50',
                badgeBorder: 'border-rose-200',
                textClass: 'text-rose-600',
                dotClass: 'bg-rose-500',
                glowClass: 'group-hover:border-rose-400'
            };
        }

        // 16. Radiator
        if (/radiator/i.test(raw)) {
            return {
                category: 'Cooling System (Radiator)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-cyan-50 to-blue-50',
                badgeBorder: 'border-cyan-200',
                textClass: 'text-cyan-600',
                dotClass: 'bg-cyan-500',
                glowClass: 'group-hover:border-cyan-400'
            };
        }

        // 17. Suspension Shocks & Coilovers
        if (/shock|strut|coilover|spring|damper/i.test(raw)) {
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

        // 18. Drivetrain & Axles
        if (/cv\s*axle|axle|driveshaft|differential|clutch|transmission/i.test(raw)) {
            return {
                category: 'Drivetrain & Transmission',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-slate-100 to-zinc-100',
                badgeBorder: 'border-slate-300',
                textClass: 'text-slate-700',
                dotClass: 'bg-slate-600',
                glowClass: 'group-hover:border-slate-400'
            };
        }

        // 19. Tires & Wheels
        if (/tire|tyre|wheel|rim|mag/i.test(raw)) {
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

        // 20. Filtration & Fluids
        if (/filter|oil|fluid|synthetic|lubricant|atf/i.test(raw)) {
            return {
                category: 'Filtration & Fluids',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-amber-50 to-yellow-50',
                badgeBorder: 'border-amber-200',
                textClass: 'text-amber-600',
                dotClass: 'bg-amber-500',
                glowClass: 'group-hover:border-amber-400'
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
    window.openEditPartModal = openEditPartModal;
    window.openRestockPartModal = openRestockPartModal;
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


