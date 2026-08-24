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

            <!-- AI Live Auto-Identification Preview -->
            <div id="aiPartDetectorPreview" class="p-3.5 rounded-2xl border transition-all duration-300 bg-slate-50 border-slate-200/80 flex items-center gap-3.5 shadow-xs">
                <div id="aiDetectorIconBox" class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 border bg-white border-slate-200 shadow-xs transition-all cursor-pointer p-1 overflow-hidden">
                    <!-- Image rendered dynamically -->
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
                    <div class="text-[10px] text-slate-400 mt-0.5 flex items-center gap-1">
                        <span>Click preview to zoom</span> · <span id="imgSourceLabel" class="text-blue-500 font-bold">Standard 3D Asset</span>
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Part Name <span class="text-red-500">*</span></label>
                <input type="text" id="newPartName" maxlength="100" oninput="onPartNameInput(this.value)" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-gray-50 focus:bg-white transition-colors" placeholder="e.g. Shock Absorber (Front), Brake Pad, Air Filter...">
            </div>

            <!-- AI Real Photo Suggestions Section -->
            <div id="aiPhotoSuggestionsSection" class="p-3.5 rounded-2xl border border-blue-100 bg-blue-50/40 space-y-2.5">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-blue-700 flex items-center gap-1.5">
                        <i data-lucide="camera" class="w-3.5 h-3.5 text-blue-600"></i> Choose Real Photo (AI Suggestions)
                    </span>
                    <span id="aiPhotoSearchLoader" class="hidden text-[10px] font-bold text-blue-600 flex items-center gap-1 animate-pulse">
                        <i data-lucide="loader-2" class="w-3 h-3 animate-spin"></i> Searching real photos...
                    </span>
                </div>
                
                <!-- Quick Search Custom Keywords -->
                <div class="flex gap-2">
                    <input type="text" id="customPhotoQuery" onkeydown="if(event.key === 'Enter'){ event.preventDefault(); searchCustomRealPhotos(); }" placeholder="Search Google / web photos (e.g. shock absorber car)..." class="flex-1 px-3 py-1.5 text-xs bg-white border border-blue-200 rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="button" onclick="searchCustomRealPhotos()" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition shadow-xs flex items-center gap-1 shrink-0">
                        <i data-lucide="search" class="w-3 h-3"></i> Search
                    </button>
                </div>

                <!-- Suggested Real Photos Grid -->
                <div id="aiSuggestedPhotosGrid" class="grid grid-cols-4 gap-2 max-h-40 overflow-y-auto p-1.5 bg-white rounded-xl border border-blue-100">
                    <div class="col-span-full py-4 text-center text-xs text-slate-400 font-medium">
                        Type a part name above to auto-suggest real photos.
                    </div>
                </div>

                <!-- Action button to reset to default 3D vector or custom url -->
                <div class="flex justify-between items-center pt-1 text-[11px]">
                    <button type="button" onclick="resetToDefault3DImage()" class="text-slate-500 hover:text-blue-600 font-bold flex items-center gap-1 transition">
                        <i data-lucide="rotate-ccw" class="w-3 h-3"></i> Use Default 3D Asset
                    </button>
                    <button type="button" onclick="promptCustomImageUrl()" class="text-blue-600 hover:text-blue-700 font-bold flex items-center gap-1 transition">
                        <i data-lucide="link" class="w-3 h-3"></i> Paste Custom URL
                    </button>
                </div>
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

    // ── AI Smart Auto Part Classifier & 3D Image Asset Engine ──
    function getPartAIMeta(name = '') {
        const raw = (name || '').toLowerCase().trim();

        // 1. Specific Braking System Components
        if (/brake\s*fluid/i.test(raw)) {
            return {
                category: 'Braking System',
                imageUrl: '{{ asset('image/parts/brake_fluid.svg') }}',
                badgeBg: 'bg-gradient-to-br from-amber-50 to-yellow-50/80',
                badgeBorder: 'border-amber-200/90',
                textClass: 'text-amber-600',
                dotClass: 'bg-amber-500',
                glowClass: 'group-hover:border-amber-400'
            };
        }
        if (/brake\s*hose|brake\s*line/i.test(raw)) {
            return {
                category: 'Braking System',
                imageUrl: '{{ asset('image/parts/brake_hose.svg') }}',
                badgeBg: 'bg-gradient-to-br from-slate-100 to-zinc-100',
                badgeBorder: 'border-slate-300',
                textClass: 'text-slate-700',
                dotClass: 'bg-slate-600',
                glowClass: 'group-hover:border-slate-400'
            };
    // ── AI Procedural Vector Generator Engine (Synthesizes custom SVG graphics on the fly) ──
    function generateDynamicPartSVG(partName) {
        const raw = (partName || 'Auto Part').toLowerCase();
        const id = 'svg_' + Math.random().toString(36).substring(2, 9);

        // Color extraction from keywords
        let pColor = '#2563eb', sColor = '#3b82f6', aColor = '#60a5fa';
        if (/red|brembo|ferodo|sport/i.test(raw)) {
            pColor = '#dc2626'; sColor = '#ef4444'; aColor = '#f87171';
        } else if (/gold|yellow|motolite|ohlins/i.test(raw)) {
            pColor = '#d97706'; sColor = '#f59e0b'; aColor = '#fbbf24';
        } else if (/green|tein|monster/i.test(raw)) {
            pColor = '#16a34a'; sColor = '#22c55e'; aColor = '#4ade80';
        } else if (/black|dark|carbon/i.test(raw)) {
            pColor = '#1e293b'; sColor = '#334155'; aColor = '#64748b';
        } else if (/purple|hks/i.test(raw)) {
            pColor = '#9333ea'; sColor = '#a855f7'; aColor = '#c084fc';
        }

        const isDrilled = /drill|slot|vent/i.test(raw);
        let content = '';

        // 1. SHOCK ABSORBER / STRUT / COILOVER
        if (/shock|strut|coilover|absorber|spring|suspension/i.test(raw)) {
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
        // 2. BRAKE ROTOR / DISK
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
        // 3. BRAKE PADS / CALIPER
        else if (/pad|brake|caliper/i.test(raw)) {
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
        // 4. TIRES / WHEELS / RIMS
        else if (/tire|tyre|wheel|rim|mag/i.test(raw)) {
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
        // 5. SPARK PLUG
        else if (/spark|plug|iridium/i.test(raw)) {
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
        // 6. BATTERY
        else if (/battery|motolite/i.test(raw)) {
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
        // 7. OIL & FLUIDS
        else if (/oil|fluid|coolant|synthetic/i.test(raw)) {
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
        // 8. FILTERS
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
        // 9. WIPER BLADES
        else if (/wiper|blade/i.test(raw)) {
            content = `
                <path d="M 25 145 Q 100 65 175 125" fill="none" stroke="#1e293b" stroke-width="12" stroke-linecap="round"/>
                <path d="M 25 151 Q 100 71 175 131" fill="none" stroke="#64748b" stroke-width="4" stroke-linecap="round"/>
                <rect x="90" y="80" width="20" height="16" rx="3" fill="${pColor}"/>
            `;
        }
        // 10. BELTS
        else if (/belt|timing|serpentine/i.test(raw)) {
            content = `
                <ellipse cx="100" cy="100" rx="72" ry="48" fill="none" stroke="#0f172a" stroke-width="14"/>
                <ellipse cx="100" cy="100" rx="72" ry="48" fill="none" stroke="#475569" stroke-width="2" stroke-dasharray="4,4"/>
                <circle cx="65" cy="100" r="24" fill="#94a3b8"/>
                <circle cx="65" cy="100" r="8" fill="#1e293b"/>
                <circle cx="135" cy="100" r="18" fill="#cbd5e1"/>
                <circle cx="135" cy="100" r="6" fill="#1e293b"/>
            `;
        }
        // 11. ALTERNATOR / MOTOR
        else if (/alternator|starter|motor|pump|generator/i.test(raw)) {
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
        // 12. GENERAL PRECISION GEARS & CHASSIS
        else {
            content = `
                <circle cx="85" cy="90" r="48" fill="#94a3b8"/>
                ${Array.from({length: 8}).map((_, i) => {
                    const rad = (i * 45) * Math.PI / 180;
                    return `<rect x="${(85 + 46 * Math.cos(rad) - 6).toFixed(1)}" y="${(90 + 46 * Math.sin(rad) - 6).toFixed(1)}" width="12" height="12" rx="2" fill="#64748b"/>`;
                }).join('')}
                <circle cx="85" cy="90" r="28" fill="#f8fafc"/>
                <circle cx="85" cy="90" r="14" fill="#0f172a"/>
                <circle cx="130" cy="135" r="34" fill="${pColor}"/>
                <circle cx="130" cy="135" r="18" fill="#f8fafc"/>
                <circle cx="130" cy="135" r="8" fill="#0f172a"/>
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

        // 1. Braking System
        if (/brake\s*fluid/i.test(raw)) {
            return {
                category: 'Braking System',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-amber-50 to-orange-50/80',
                badgeBorder: 'border-amber-200/90',
                textClass: 'text-amber-600',
                dotClass: 'bg-amber-500',
                glowClass: 'group-hover:border-amber-400'
            };
        }
        if (/brake\s*hose/i.test(raw)) {
            return {
                category: 'Braking System',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-slate-100 to-zinc-100',
                badgeBorder: 'border-slate-300',
                textClass: 'text-slate-700',
                dotClass: 'bg-slate-600',
                glowClass: 'group-hover:border-slate-400'
            };
        }
        if (/brake\s*shoe/i.test(raw)) {
            return {
                category: 'Braking System',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-rose-50 to-orange-50/80',
                badgeBorder: 'border-rose-200/90',
                textClass: 'text-rose-600',
                dotClass: 'bg-rose-500',
                glowClass: 'group-hover:border-rose-400'
            };
        }
        if (/brake\s*disk|brake\s*disc|rotor/i.test(raw)) {
            return {
                category: 'Braking System',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-slate-100 to-gray-100',
                badgeBorder: 'border-slate-300',
                textClass: 'text-slate-700',
                dotClass: 'bg-slate-600',
                glowClass: 'group-hover:border-slate-400'
            };
        }
        if (/brake\s*pad|brake|caliper/i.test(raw)) {
            return {
                category: 'Braking System',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-rose-50 to-red-50/80',
                badgeBorder: 'border-rose-200/90',
                textClass: 'text-rose-600',
                dotClass: 'bg-rose-500',
                glowClass: 'group-hover:border-rose-400'
            };
        }

        // 2. Suspension & Steering
        if (/shock|strut|absorber|spring|coil/i.test(raw)) {
            return {
                category: 'Suspension & Steering',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-amber-50 to-orange-50/80',
                badgeBorder: 'border-amber-200/90',
                textClass: 'text-amber-600',
                dotClass: 'bg-amber-500',
                glowClass: 'group-hover:border-amber-400'
            };
        }
        if (/bushing|ball\s*joint|tie\s*rod|rack\s*end|link|stabilizer|control\s*arm/i.test(raw)) {
            return {
                category: 'Suspension & Steering',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-slate-100 to-zinc-100',
                badgeBorder: 'border-slate-300',
                textClass: 'text-slate-700',
                dotClass: 'bg-slate-600',
                glowClass: 'group-hover:border-slate-400'
            };
        }

        // 3. Filtration
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

        // 4. Fluids & Oils
        if (/oil|fluid|lubricant|synthetic/i.test(raw)) {
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

        // 5. Electrical
        if (/battery|alternator|starter|generator|bulb|light/i.test(raw)) {
            return {
                category: 'Electrical & Lighting',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-slate-100 to-zinc-100',
                badgeBorder: 'border-slate-300',
                textClass: 'text-slate-700',
                dotClass: 'bg-slate-600',
                glowClass: 'group-hover:border-slate-400'
            };
        }

        // 6. Engine & Ignition
        if (/spark|plug|ignition|timing|belt|serpentine/i.test(raw)) {
            return {
                category: 'Engine & Belts',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-slate-100 to-sky-50',
                badgeBorder: 'border-slate-300',
                textClass: 'text-slate-700',
                dotClass: 'bg-slate-600',
                glowClass: 'group-hover:border-slate-400'
            };
        }

        // 7. Tires & Wheels
        if (/tire|tyre|wheel|rim|bearing|hub/i.test(raw)) {
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

    // ── Global Real Photo Suggester State ──
    let photoSearchDebounce = null;
    let cachedPhotoResults = [];

    function onPartNameInput(name) {
        updateAIMiniModalPreview(name);
        const customInput = document.getElementById('customPhotoQuery');
        if (customInput && (!document.activeElement || document.activeElement !== customInput)) {
            customInput.value = name;
        }
        if (!name || name.trim().length < 2) return;
        
        clearTimeout(photoSearchDebounce);
        photoSearchDebounce = setTimeout(() => {
            fetchRealPhotoSuggestions(name);
        }, 500);
    }

    async function fetchRealPhotoSuggestions(query) {
        if (!query || query.trim().length < 2) return;

        const loader = document.getElementById('aiPhotoSearchLoader');
        const grid = document.getElementById('aiSuggestedPhotosGrid');
        
        if (loader) loader.classList.remove('hidden');
        if (grid) {
            grid.innerHTML = `
                <div class="col-span-full py-4 flex items-center justify-center gap-2 text-blue-600 text-xs font-bold">
                    <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Finding real photos for "${escapeHtml(query)}"...
                </div>
            `;
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        try {
            const res = await fetch(`{{ route('spare-parts.suggest-images') }}?query=${encodeURIComponent(query)}`);
            const data = await res.json();
            if (loader) loader.classList.add('hidden');

            if (data.success && data.images && data.images.length > 0) {
                cachedPhotoResults = data.images;
                renderPhotoSuggestionsGrid(data.images);
            } else {
                if (grid) {
                    grid.innerHTML = `
                        <div class="col-span-full py-4 text-center text-xs text-slate-400 font-medium">
                            No online photos found for "${escapeHtml(query)}". Defaulting to accurate 3D render.
                        </div>
                    `;
                }
            }
        } catch (e) {
            console.error('Photo search error:', e);
            if (loader) loader.classList.add('hidden');
            if (grid) {
                grid.innerHTML = `
                    <div class="col-span-full py-3 text-center text-xs text-slate-400 font-medium">
                        Unable to connect to image search.
                    </div>
                `;
            }
        }
    }

    function renderPhotoSuggestionsGrid(images) {
        const grid = document.getElementById('aiSuggestedPhotosGrid');
        if (!grid) return;

        const currentSelected = document.getElementById('newPartImageUrl').value;
        const partName = document.getElementById('newPartName').value || 'Automotive Part';
        const dynamicSvgUrl = generateDynamicPartSVG(partName);
        const isSvgSelected = (!currentSelected || currentSelected === dynamicSvgUrl);

        // Prepend AI Generated SVG card
        const svgCardHtml = `
            <div class="relative group cursor-pointer rounded-xl overflow-hidden border-2 ${isSvgSelected ? 'border-blue-600 ring-2 ring-blue-400/50 shadow-md' : 'border-slate-200 hover:border-blue-400'} bg-blue-50/40 aspect-square flex flex-col items-center justify-center p-1.5 transition-all hover:scale-105"
                 onclick="selectRealPhoto('', '')"
                 title="✨ AI Generated 3D Vector for '${escapeHtml(partName)}'">
                <img src="${dynamicSvgUrl}" alt="AI Generated 3D Asset" class="w-full h-full object-contain filter drop-shadow-xs">
                <div class="absolute bottom-0 inset-x-0 bg-blue-600/90 text-[8px] text-white text-center font-bold py-0.5 tracking-wider uppercase">✨ AI Vector</div>
                ${isSvgSelected ? '<div class="absolute top-1 right-1 bg-blue-600 text-white rounded-full p-0.5 shadow-sm"><i data-lucide="check" class="w-3 h-3"></i></div>' : ''}
            </div>
        `;

        const photosHtml = images.map((img) => {
            const isSelected = (currentSelected === img.image || currentSelected === img.thumb);
            return `
                <div class="relative group cursor-pointer rounded-xl overflow-hidden border-2 ${isSelected ? 'border-blue-600 ring-2 ring-blue-400/50 shadow-md' : 'border-slate-200 hover:border-blue-400'} bg-white aspect-square flex items-center justify-center p-1.5 transition-all hover:scale-105"
                     onclick="selectRealPhoto('${addslashes(img.image)}', '${addslashes(img.thumb)}')"
                     title="${escapeHtml(img.title || 'Click to select this actual photo')}">
                    <img src="${img.thumb}" alt="${escapeHtml(img.title || 'Part')}" class="w-full h-full object-contain filter drop-shadow-xs" onerror="this.src='${img.image}'">
                    <div class="absolute bottom-0 inset-x-0 bg-slate-900/70 text-[8px] text-white text-center font-semibold py-0.5 truncate px-1">📸 Photo</div>
                    ${isSelected ? '<div class="absolute top-1 right-1 bg-blue-600 text-white rounded-full p-0.5 shadow-sm"><i data-lucide="check" class="w-3 h-3"></i></div>' : ''}
                </div>
            `;
        }).join('');

        grid.innerHTML = svgCardHtml + photosHtml;

        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function selectRealPhoto(imageUrl, thumbUrl) {
        const finalUrl = imageUrl || thumbUrl || '';
        document.getElementById('newPartImageUrl').value = finalUrl;
        
        const iconBox = document.getElementById('aiDetectorIconBox');
        const imgSourceLabel = document.getElementById('imgSourceLabel');
        const name = document.getElementById('newPartName').value;
        const meta = getPartAIMeta(name);

        const activeImg = finalUrl || meta.imageUrl;

        if (iconBox) {
            iconBox.innerHTML = `<img src="${activeImg}" alt="Selected Asset" class="w-full h-full object-contain filter drop-shadow-sm rounded-xl" onerror="this.src='{{ asset('image/parts/general_part.svg') }}'">`;
            iconBox.onclick = () => openImageModal(activeImg);
        }

        if (imgSourceLabel) {
            if (finalUrl) {
                imgSourceLabel.innerText = '✨ Real Photo Selected';
                imgSourceLabel.className = 'text-green-600 font-black';
            } else {
                imgSourceLabel.innerText = `🎨 AI 3D Asset (${meta.category})`;
                imgSourceLabel.className = 'text-blue-600 font-bold';
            }
        }

        if (cachedPhotoResults.length > 0) {
            renderPhotoSuggestionsGrid(cachedPhotoResults);
        }
    }

    function searchCustomRealPhotos() {
        const query = document.getElementById('customPhotoQuery').value;
        if (!query || query.trim().length < 2) {
            showToast('Please enter search keywords', 'error');
            return;
        }
        fetchRealPhotoSuggestions(query.trim());
    }

    function resetToDefault3DImage() {
        const name = document.getElementById('newPartName').value;
        const meta = getPartAIMeta(name);
        document.getElementById('newPartImageUrl').value = '';
        
        const iconBox = document.getElementById('aiDetectorIconBox');
        const imgSourceLabel = document.getElementById('imgSourceLabel');

        if (iconBox) {
            iconBox.className = `w-14 h-14 rounded-2xl p-1.5 flex items-center justify-center shrink-0 border ${meta.badgeBorder} ${meta.badgeBg} shadow-xs transition-all duration-300 transform scale-100 hover:scale-105 cursor-pointer bg-white`;
            iconBox.innerHTML = `<img src="${meta.imageUrl}" alt="AI 3D Asset" class="w-full h-full object-contain filter drop-shadow-sm" onerror="this.src='{{ asset('image/parts/general_part.svg') }}'">`;
            iconBox.onclick = () => openImageModal(meta.imageUrl);
        }

        if (imgSourceLabel) {
            imgSourceLabel.innerText = `🎨 AI 3D Asset (${meta.category})`;
            imgSourceLabel.className = 'text-blue-600 font-bold';
        }

        if (cachedPhotoResults.length > 0) {
            renderPhotoSuggestionsGrid(cachedPhotoResults);
        }
    }

    function promptCustomImageUrl() {
        const url = prompt('Enter or paste image URL:');
        if (url && url.startsWith('http')) {
            selectRealPhoto(url, url);
        }
    }

    // ── Live AI Preview Update in Add/Edit Part Modal ──
    function updateAIMiniModalPreview(name) {
        const meta = getPartAIMeta(name);
        const iconBox = document.getElementById('aiDetectorIconBox');
        const catLabel = document.getElementById('aiDetectorCategoryName');
        const previewContainer = document.getElementById('aiPartDetectorPreview');
        const confidenceBadge = document.getElementById('aiConfidenceBadge');
        const customUrl = document.getElementById('newPartImageUrl').value;
        const imgSourceLabel = document.getElementById('imgSourceLabel');

        const activeImg = customUrl || meta.imageUrl;

        if (iconBox) {
            iconBox.className = `w-14 h-14 rounded-2xl p-1.5 flex items-center justify-center shrink-0 border ${meta.badgeBorder} ${meta.badgeBg} shadow-xs transition-all duration-300 transform scale-100 hover:scale-105 cursor-pointer bg-white`;
            iconBox.innerHTML = `<img src="${activeImg}" alt="Part Preview" class="w-full h-full object-contain filter drop-shadow-sm rounded-xl" onerror="this.onerror=null; this.src='{{ asset('image/parts/general_part.svg') }}';">`;
            iconBox.onclick = () => openImageModal(activeImg);
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

        if (imgSourceLabel) {
            if (customUrl) {
                imgSourceLabel.innerText = '✨ Real Photo Selected';
                imgSourceLabel.className = 'text-green-600 font-black';
            } else {
                imgSourceLabel.innerText = 'Standard 3D Asset';
                imgSourceLabel.className = 'text-blue-500 font-bold';
            }
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
            const partImg = p.image_url || aiMeta.imageUrl;

            return `
            <tr class="hover:bg-gray-50/80 transition-colors group">
                <td class="px-8 py-4">
                    <div class="flex items-center gap-3.5">
                        <div class="relative w-12 h-12 rounded-2xl p-1 flex items-center justify-center shrink-0 border ${aiMeta.badgeBorder} ${aiMeta.badgeBg} shadow-xs group-hover:scale-105 ${aiMeta.glowClass} transition-all cursor-pointer bg-white overflow-hidden"
                             onclick="openImageModal('${addslashes(partImg)}')"
                             title="Click to view full image">
                            <img src="${partImg}" alt="${escapeHtml(p.name)}" class="w-full h-full object-contain filter drop-shadow-sm rounded-xl" onerror="this.onerror=null; this.src='${aiMeta.imageUrl}';">
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
                        <button onclick="editPart(${p.id}, '${addslashes(p.name)}', ${p.price}, ${p.stock_quantity}, '${addslashes(p.supplier||'')}', '${addslashes(p.image_url||'')}')" class="p-2 text-blue-600 hover:bg-blue-100 rounded-xl transition-all" title="Purchase / Edit Part">
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
                        <div class="relative w-11 h-11 rounded-2xl p-1 flex items-center justify-center shrink-0 border ${aiMeta.badgeBorder} ${aiMeta.badgeBg} shadow-xs cursor-pointer bg-white overflow-hidden"
                             onclick="openImageModal('${aiMeta.imageUrl}')"
                             title="Click to view full image">
                            <img src="${aiMeta.imageUrl}" alt="${escapeHtml(ph.description)}" class="w-full h-full object-contain filter drop-shadow-sm rounded-xl" onerror="this.onerror=null; this.src='{{ asset('image/parts/general_part.svg') }}';">
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
            const partImg = p.image_url || aiMeta.imageUrl;

            return `
            <tr class="hover:bg-gray-50/80 transition-colors group">
                <td class="px-8 py-4">
                    <div class="flex items-center gap-3.5">
                        <div class="relative w-11 h-11 rounded-2xl p-1 flex items-center justify-center shrink-0 border ${aiMeta.badgeBorder} ${aiMeta.badgeBg} shadow-xs cursor-pointer bg-white overflow-hidden"
                             onclick="openImageModal('${addslashes(partImg)}')"
                             title="Click to view full image">
                            <img src="${partImg}" alt="${escapeHtml(p.name)}" class="w-full h-full object-contain filter drop-shadow-sm rounded-xl" onerror="this.onerror=null; this.src='{{ asset('image/parts/general_part.svg') }}';">
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
            document.getElementById('customPhotoQuery').value = '';
            
            document.getElementById('miniModalTitle').innerText = 'Add New Part';
            document.getElementById('miniModalSubtitle').innerText = 'Create a new item in the spare parts catalog';
            document.getElementById('lblQtyMode').innerText = 'Initial Qty';
            document.getElementById('txtSavePart').innerText = 'Save Part';
            document.getElementById('newPartName').readOnly = false;
            
            const iconContainer = document.getElementById('miniModalIcon');
            iconContainer.className = 'w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center';
            iconContainer.innerHTML = '<i data-lucide="plus" class="w-5 h-5 text-blue-600"></i>';
            
            document.getElementById('aiSuggestedPhotosGrid').innerHTML = `
                <div class="col-span-full py-4 text-center text-xs text-slate-400 font-medium">
                    Type a part name above to auto-suggest real photos.
                </div>
            `;
            
            updateAIMiniModalPreview('');
            lucide.createIcons();
        }
    }

    function closePartMiniModal() {
        document.getElementById('partMiniModal').classList.add('hidden');
    }

    function editPart(id, name, price, qty, supplier, imageUrl = '') {
        openPartMiniModal(true);
        document.getElementById('newPartId').value = id;
        document.getElementById('newPartCurrentStock').value = qty;
        document.getElementById('newPartName').value = name;
        document.getElementById('newPartPrice').value = price;
        document.getElementById('newPartQty').value = ''; 
        document.getElementById('newPartSupplier').value = supplier || '';
        document.getElementById('newPartImageUrl').value = imageUrl || '';
        document.getElementById('customPhotoQuery').value = name || '';

        document.getElementById('miniModalTitle').innerText = 'Purchase / Edit Part';
        document.getElementById('miniModalSubtitle').innerText = 'Add stock or update part details';
        document.getElementById('lblQtyMode').innerHTML = `Add Stock <span class="text-gray-400 font-normal ml-1">(Current: ${qty})</span>`;
        document.getElementById('txtSavePart').innerText = 'Save Changes';
        document.getElementById('newPartName').readOnly = true;

        const iconContainer = document.getElementById('miniModalIcon');
        iconContainer.className = 'w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center';
        iconContainer.innerHTML = '<i data-lucide="shopping-cart" class="w-5 h-5 text-blue-600"></i>';
        
        updateAIMiniModalPreview(name);
        fetchRealPhotoSuggestions(name);
        lucide.createIcons();
    }

    async function saveNewPart() {
        const id = document.getElementById('newPartId').value;
        const name = document.getElementById('newPartName').value;
        const price = document.getElementById('newPartPrice').value;
        const qty_to_add = parseInt(document.getElementById('newPartQty').value) || 0;
        const supplier = document.getElementById('newPartSupplier').value;
        const image_url = document.getElementById('newPartImageUrl').value;
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
</script>
@endpush

