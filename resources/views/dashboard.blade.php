@extends('layouts.app')

@section('title', 'Euro Taxi System | Professional Fleet Management Dashboard')
@section('page-heading', 'Euro Taxi System')
@section('page-subheading', 'Professional taxi fleet management and real-time tracking solutions')

@push('styles')
    <style>
        @media print {
            @page {
                margin: 0;
            }
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            body * {
                visibility: hidden;
            }
            #incomeReport, #incomeReport *,
            #expensesReport, #expensesReport * {
                visibility: visible !important;
            }
            #incomeReport, #expensesReport {
                position: absolute;
                left: 0;
                top: 0;
                width: 100% !important;
                max-width: none !important;
                border: none !important;
                box-shadow: none !important;
                padding: 15mm !important;
                margin: 0 !important;
                background: white !important;
            }
            #netIncomeModal, #expensesModal {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                background: white !important;
                visibility: visible !important;
            }
            .print-only {
                display: block !important;
            }
            .no-print {
                display: none !important;
            }
            /* Hidden elements must stay hidden even in print */
            .hidden {
                display: none !important;
                visibility: hidden !important;
            }
        }
        
        @media screen {
            .print-only {
                display: none !important;
            }
        }
        
        .receipt-paper::before {
            content: '';
            position: absolute;
            top: -10px;
            left: 0;
            right: 0;
            height: 10px;
            background: linear-gradient(135deg, transparent 33.333%, white 33.333%, white 66.666%, transparent 66.666%), 
                        linear-gradient(45deg, transparent 33.333%, white 33.333%, white 66.666%, transparent 66.666%);
            background-size: 15px 15px;
            background-repeat: repeat-x;
        }
        
        .receipt-paper::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            right: 0;
            height: 10px;
            background: linear-gradient(-135deg, transparent 33.333%, white 33.333%, white 66.666%, transparent 66.666%), 
                        linear-gradient(-45deg, transparent 33.333%, white 33.333%, white 66.666%, transparent 66.666%);
            background-size: 15px 15px;
            background-repeat: repeat-x;
        }

        @keyframes drawChart {
            0% { 
                clip-path: polygon(0 0, 0% 0, 0% 100%, 0 100%); 
                opacity: 0; 
            }
            15% {
                opacity: 1;
            }
            100% { 
                clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%); 
                opacity: 1; 
            }
        }
        .card-hover::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 55%;
            background-size: 100% 100%;
            background-repeat: no-repeat;
            opacity: 0;
            z-index: 0;
            pointer-events: none;
            clip-path: polygon(0 0, 0% 0, 0% 100%, 0 100%);
        }
        .card-hover.in-view::after {
            animation: drawChart 1.2s cubic-bezier(0.22, 1, 0.36, 1) forwards !important;
        }
        .wave-blue::after { background-image: url('data:image/svg+xml;utf8,<svg viewBox="0 0 100 50" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><polygon fill="rgba(59,130,246,0.15)" stroke="rgba(59,130,246,0.4)" stroke-width="1.5" vector-effect="non-scaling-stroke" stroke-linejoin="miter" points="0,50 0,35 15,20 30,30 45,10 60,25 75,5 90,15 100,0 100,50" /></svg>'); }
        .wave-emerald::after { background-image: url('data:image/svg+xml;utf8,<svg viewBox="0 0 100 50" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><polygon fill="rgba(16,185,129,0.15)" stroke="rgba(16,185,129,0.4)" stroke-width="1.5" vector-effect="non-scaling-stroke" stroke-linejoin="miter" points="0,50 0,35 15,20 30,30 45,10 60,25 75,5 90,15 100,0 100,50" /></svg>'); }
        .wave-green::after { background-image: url('data:image/svg+xml;utf8,<svg viewBox="0 0 100 50" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><polygon fill="rgba(34,197,94,0.15)" stroke="rgba(34,197,94,0.4)" stroke-width="1.5" vector-effect="non-scaling-stroke" stroke-linejoin="miter" points="0,50 0,35 15,20 30,30 45,10 60,25 75,5 90,15 100,0 100,50" /></svg>'); }
        .wave-orange::after { background-image: url('data:image/svg+xml;utf8,<svg viewBox="0 0 100 50" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><polygon fill="rgba(249,115,22,0.15)" stroke="rgba(249,115,22,0.4)" stroke-width="1.5" vector-effect="non-scaling-stroke" stroke-linejoin="miter" points="0,50 0,35 15,20 30,30 45,10 60,25 75,5 90,15 100,0 100,50" /></svg>'); }
        .wave-indigo::after { background-image: url('data:image/svg+xml;utf8,<svg viewBox="0 0 100 50" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><polygon fill="rgba(99,102,241,0.15)" stroke="rgba(99,102,241,0.4)" stroke-width="1.5" vector-effect="non-scaling-stroke" stroke-linejoin="miter" points="0,50 0,35 15,20 30,30 45,10 60,25 75,5 90,15 100,0 100,50" /></svg>'); }
        .wave-rose::after { background-image: url('data:image/svg+xml;utf8,<svg viewBox="0 0 100 50" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><polygon fill="rgba(244,63,94,0.15)" stroke="rgba(244,63,94,0.4)" stroke-width="1.5" vector-effect="non-scaling-stroke" stroke-linejoin="miter" points="0,50 0,35 15,20 30,30 45,10 60,25 75,5 90,15 100,0 100,50" /></svg>'); }
        .wave-violet::after { background-image: url('data:image/svg+xml;utf8,<svg viewBox="0 0 100 50" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><polygon fill="rgba(139,92,246,0.15)" stroke="rgba(139,92,246,0.4)" stroke-width="1.5" vector-effect="non-scaling-stroke" stroke-linejoin="miter" points="0,50 0,35 15,20 30,30 45,10 60,25 75,5 90,15 100,0 100,50" /></svg>'); }

        @keyframes animatedShinyText {
            0% {
                background-position: 200% 0;
            }
            100% {
                background-position: -200% 0;
            }
        }
        .animated-shiny-units-text {
            background: linear-gradient(110deg, #94a3b8 0%, #e2e8f0 20%, #ffffff 40%, #fbbf24 50%, #ffffff 60%, #e2e8f0 80%, #94a3b8 100%);
            background-size: 200% 100%;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            background-clip: text !important;
            display: inline-block;
            animation: animatedShinyText 3.5s linear infinite !important;
            will-change: background-position;
            transform: translateZ(0);
            contain: layout style paint;
        }
        .animated-shiny-boundary-text {
            background: linear-gradient(110deg, #6ee7b7 0%, #a7f3d0 25%, #ffffff 45%, #fef08a 55%, #ffffff 65%, #a7f3d0 80%, #6ee7b7 100%);
            background-size: 200% 100%;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            background-clip: text !important;
            display: inline-block;
            animation: animatedShinyText 3.5s linear infinite !important;
            will-change: background-position;
            transform: translateZ(0);
            contain: layout style paint;
        }
        .animated-shiny-netincome-text {
            background: linear-gradient(110deg, #818cf8 0%, #c084fc 25%, #ffffff 45%, #fde047 55%, #ffffff 65%, #c084fc 80%, #818cf8 100%);
            background-size: 200% 100%;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            background-clip: text !important;
            display: inline-block;
            animation: animatedShinyText 3.5s linear infinite !important;
            will-change: background-position;
            transform: translateZ(0);
            contain: layout style paint;
        }

        @keyframes blob {
            0% {
                transform: translate(-100%, -100%);
            }
            25% {
                transform: translate(20%, -100%);
            }
            50% {
                transform: translate(20%, 20%);
            }
            75% {
                transform: translate(-100%, 20%);
            }
            100% {
                transform: translate(-100%, -100%);
            }
        }
        .animate-blob {
            animation: blob 5s linear infinite;
        }
        .animate-blob-fast {
            animation: blob 4s linear infinite;
        }
        .animate-blob-slow {
            animation: blob 7s linear infinite;
        }
        .animate-blob-reverse {
            animation: blob 6s linear infinite reverse;
        }
    </style>
@endpush
@section('content')


    <script>
        // Inject initial stats for JS to prevent flickering on load
        window.__INITIAL_STATS__ = @json($stats);
        window.__INITIAL_MAINTENANCE__ = @json($initial_maintenance ?? null);

        // Intersection Observer for scroll-triggered wave animation (same as unit performance)
        window.initWaveObserver = function() {
            const cards = document.querySelectorAll('.card-hover');
            if (!cards || cards.length === 0) return;

            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('in-view');
                        } else {
                            entry.target.classList.remove('in-view');
                        }
                    });
                }, { threshold: 0.1 });

                cards.forEach(card => observer.observe(card));
            } else {
                cards.forEach(card => card.classList.add('in-view'));
            }
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', window.initWaveObserver);
        } else {
            setTimeout(window.initWaveObserver, 50);
        }
        window.addEventListener('load', window.initWaveObserver);

        window.showMaintenanceUnitsModal = function showMaintenanceUnitsModal() {
            const modal = document.getElementById('maintenanceUnitsModal');
            if (modal) modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            window.currentMaintenanceFilter = 'all';
            window.updateMaintenanceFilterUI('all');
            
            if (window.__INITIAL_MAINTENANCE__) {
                window.displayMaintenanceUnitsData(window.__INITIAL_MAINTENANCE__);
            }
            
            window.loadMaintenanceUnitsData();
        };

        window.hideMaintenanceUnitsModal = function hideMaintenanceUnitsModal() {
            const modal = document.getElementById('maintenanceUnitsModal');
            if (modal) modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        };

        window.showMaintenanceDetailsModal = function showMaintenanceDetailsModal(maintenanceId) {
            if (!maintenanceId) return;
            const unitList = window.originalMaintenanceData || (window.__INITIAL_MAINTENANCE__ && window.__INITIAL_MAINTENANCE__.units) || [];
            const unit = unitList.find(u => u.maintenance_id == maintenanceId || u.id == maintenanceId);
            if (!unit) return;

            const setSafe = (id, val) => {
                const el = document.getElementById(id);
                if (!el) return;
                const span = el.querySelector('span');
                if (span) span.textContent = val;
                else el.textContent = val;
            };

            setSafe('mdm-plate', unit.plate_number || 'N/A');
            setSafe('mdm-type', (unit.maintenance_type || 'Maintenance').toUpperCase());
            setSafe('mdm-start-date', unit.start_date || 'N/A');
            setSafe('mdm-end-date', unit.estimated_completion || 'TBD');
            setSafe('mdm-mechanic', unit.mechanic_name || 'Not specified');
            setSafe('mdm-driver', unit.driver_name || 'No driver assigned');
            setSafe('mdm-total-cost', '₱' + (parseFloat(unit.maintenance_cost) || 0).toLocaleString('en-PH', {minimumFractionDigits: 2}));
            setSafe('mdm-status-badge', (unit.maintenance_status || 'Ongoing').toUpperCase());
            
            const detailModal = document.getElementById('maintenanceDetailsModal');
            if (detailModal) detailModal.classList.remove('hidden');
            
            const partsLoading = document.getElementById('mdm-parts-loading');
            const partsList = document.getElementById('mdm-parts-list');
            if (partsLoading) partsLoading.classList.remove('hidden');
            if (partsList) {
                partsList.classList.add('hidden');
                partsList.innerHTML = '';
            }

            fetch(`/maintenance/${maintenanceId}/parts`, {
                credentials: 'same-origin',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if (partsLoading) partsLoading.classList.add('hidden');
                if (partsList) {
                    partsList.classList.remove('hidden');
                    
                    if (data.success && data.data && data.data.length > 0) {
                        data.data.forEach(p => {
                            const supplier = p.supplier ? `<span class="px-1.5 py-0.5 bg-gray-100 text-slate-500 rounded text-[9px] font-bold uppercase truncate max-w-[100px]" title="${p.supplier}">${p.supplier}</span>` : '';
                            partsList.innerHTML += `
                                <li class="px-4 py-3 flex justify-between items-start gap-3 hover:bg-orange-50/30 transition-colors">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-800 truncate">${p.part_name}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-[10px] text-slate-500 font-bold bg-white border border-gray-200 px-1.5 py-0.5 rounded shadow-md shadow-slate-200/40">x${p.quantity}</span>
                                            ${supplier}
                                        </div>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <p class="text-sm font-bold text-orange-600">₱${(parseFloat(p.total) || 0).toLocaleString('en-PH', {minimumFractionDigits:2})}</p>
                                        <p class="text-[9px] text-slate-400 font-medium">₱${(parseFloat(p.price) || 0).toLocaleString('en-PH', {minimumFractionDigits:2})} / ea</p>
                                    </div>
                                </li>
                            `;
                        });
                    } else {
                        partsList.innerHTML = `
                            <li class="py-8 text-center">
                                <div class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i data-lucide="package-x" class="w-5 h-5 text-slate-400"></i>
                                </div>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">No specific parts listed</p>
                                <p class="text-[10px] text-slate-400 mt-1">${unit.description || 'See description for details'}</p>
                            </li>
                        `;
                    }
                }
                if (typeof lucide !== 'undefined') lucide.createIcons();
            })
            .catch(err => {
                if (partsLoading) partsLoading.innerHTML = `<p class="text-xs text-red-500"><i data-lucide="alert-circle" class="w-4 h-4 inline mr-1"></i> Failed to load parts</p>`;
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        };

        window.hideMaintenanceDetailsModal = function hideMaintenanceDetailsModal() {
            const modal = document.getElementById('maintenanceDetailsModal');
            if (modal) modal.classList.add('hidden');
        };

        window.updateMaintenanceFilterUI = function updateMaintenanceFilterUI(filter) {
            const filters = ['all', 'preventive', 'corrective', 'emergency', 'complete'];
            filters.forEach(f => {
                const btn = document.getElementById('mFilter' + f.charAt(0).toUpperCase() + f.slice(1));
                if (btn) {
                    if (f === filter) {
                        btn.className = 'px-3 py-1.5 text-xs font-bold rounded-md transition-all duration-200 bg-white text-orange-600 shadow-sm cursor-pointer';
                    } else {
                        btn.className = 'px-3 py-1.5 text-xs font-medium rounded-md transition-all duration-200 text-white/80 hover:text-white hover:bg-white/10 cursor-pointer';
                    }
                }
            });
        };

        window.setMaintenanceFilter = function setMaintenanceFilter(filter) {
            window.currentMaintenanceFilter = filter;
            window.updateMaintenanceFilterUI(filter);
            window.filterMaintenanceUnits();
            window.loadMaintenanceUnitsData();
        };

        window.displayMaintenanceUnitsData = function displayMaintenanceUnitsData(data) {
            const units = (data && data.units) ? data.units : [];
            const stats = (data && data.stats) ? data.stats : {};
            
            const setTxt = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.textContent = val;
            };
            setTxt('maintenanceUnitsCount', stats.total_maintenance || 0);
            setTxt('preventiveMaintenanceCount', stats.preventive_maintenance || 0);
            setTxt('correctiveMaintenanceCount', stats.corrective_maintenance || 0);
            setTxt('emergencyMaintenanceCount', stats.emergency_maintenance || 0);
            setTxt('completedTotalCount', stats.completed_total || 0);
            
            window.originalMaintenanceData = units;
            window.maintenanceSortOrder = window.maintenanceSortOrder || 'desc';
            
            window.filterMaintenanceUnits();
            
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        };

        window.filterMaintenanceUnits = function filterMaintenanceUnits() {
            const searchInput = document.getElementById('maintenanceSearchInput');
            const searchTerm = searchInput ? (searchInput.value || '').toLowerCase() : '';
            const filter = window.currentMaintenanceFilter || 'all';
            
            let filteredUnits = [...(window.originalMaintenanceData || (window.__INITIAL_MAINTENANCE__ && window.__INITIAL_MAINTENANCE__.units) || [])];
            
            if (filter === 'preventive' || filter === 'corrective' || filter === 'emergency') {
                filteredUnits = filteredUnits.filter(u => (u.maintenance_type || '').toLowerCase() === filter);
            } else if (filter === 'complete') {
                filteredUnits = filteredUnits.filter(u => {
                    const st = (u.maintenance_status || '').toLowerCase();
                    return st === 'complete' || st === 'completed';
                });
            }

            if (searchTerm) {
                filteredUnits = filteredUnits.filter(unit => {
                    const searchableText = [
                        unit.plate_number || '',
                        unit.maintenance_type || '',
                        unit.maintenance_status || '',
                        unit.description || '',
                        unit.mechanic_name || '',
                        unit.driver_name || '',
                        unit.start_date || '',
                        unit.end_date || '',
                        unit.estimated_completion || ''
                    ].join(' ').toLowerCase();
                    
                    return searchableText.includes(searchTerm);
                });
            }

            filteredUnits.sort((a, b) => {
                const dateA = new Date((filter === 'complete' ? (a.end_date || a.start_date) : a.start_date) || '1970-01-01');
                const dateB = new Date((filter === 'complete' ? (b.end_date || b.start_date) : b.start_date) || '1970-01-01');
                return dateB - dateA;
            });
            
            window.currentFilteredMaintenanceData = filteredUnits;
            window.renderMaintenanceUnits(filteredUnits);
        };

        window.renderMaintenanceUnits = function renderMaintenanceUnits(units) {
            const grid = document.getElementById('maintenanceGrid');
            if (!grid) return;
            const filter = window.currentMaintenanceFilter || 'all';
            
            if (!units || units.length === 0) {
                grid.innerHTML = `
                    <div class="col-span-full text-center py-20">
                        <div class="inline-flex flex-col items-center">
                            <div class="p-4 bg-gray-100 rounded-full mb-4">
                                <i data-lucide="wrench" class="w-8 h-8 text-slate-400"></i>
                            </div>
                            <span class="text-xl text-gray-600 font-semibold mb-2">No maintenance units found</span>
                            <p class="text-sm text-slate-400">Try adjusting your search or filter</p>
                        </div>
                    </div>
                `;
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
                return;
            }
            
            grid.innerHTML = units.map(unit => {
                const isComplete = filter === 'complete';
                const mainDate = isComplete ? (unit.end_date || unit.start_date) : unit.start_date;
                const statusColor = isComplete ? 'border-green-500' : 'border-orange-500';
                const typeColor = isComplete ? 'text-green-600' : 'text-orange-600';
                const iconBg = isComplete ? 'bg-green-100' : 'bg-orange-100';
                const iconColor = isComplete ? 'text-green-600' : 'text-orange-600';
                const costVal = parseFloat(unit.maintenance_cost) || 0;

                return `
                <div onclick="showMaintenanceDetailsModal(${unit.maintenance_id || unit.id || 0})" class="cursor-pointer bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden border-l-4 ${statusColor} hover:scale-102">
                    <div class="p-4">
                        <!-- Header -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="p-2 ${iconBg} rounded-lg">
                                    <i data-lucide="wrench" class="w-4 h-4 ${iconColor}"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-slate-800">${unit.plate_number || 'N/A'}</h4>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold ${typeColor}">${unit.maintenance_type || 'Unknown'}</div>
                                <div class="text-xs text-slate-500">${mainDate || 'N/A'}</div>
                            </div>
                        </div>
                        
                        <!-- Maintenance Details -->
                        <div class="bg-gray-50 rounded-lg p-3 mb-3">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-slate-800">Status: ${unit.maintenance_status || 'Unknown'}</span>
                                <span class="text-xs font-bold text-orange-600">${isComplete ? '₱' + costVal.toLocaleString('en-PH', {minimumFractionDigits: 2}) : (unit.estimated_completion ? 'Est: ' + unit.estimated_completion : '')}</span>
                            </div>
                            <div class="text-xs text-gray-600">
                                <span class="font-medium">Description:</span> ${unit.description || 'No description available'}
                            </div>
                        </div>
                        
                        <!-- Footer -->
                        <div class="flex items-center justify-between text-xs text-slate-500">
                            <span class="flex items-center gap-1">
                                <i data-lucide="calendar" class="w-3 h-3"></i>
                                ${isComplete ? 'Completed: ' + (unit.end_date || 'N/A') : 'Started: ' + (unit.start_date || 'N/A')}
                            </span>
                            <span class="flex items-center gap-1">
                                <i data-lucide="check-circle" class="w-3 h-3"></i>
                                ${unit.maintenance_status || 'Unknown'}
                            </span>
                        </div>
                    </div>
                </div>
            `;}).join('');
            
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        };

        window.loadMaintenanceUnitsData = async function loadMaintenanceUnitsData() {
            const filter = window.currentMaintenanceFilter || 'all';
            const url = `/api/maintenance-units?filter=${encodeURIComponent(filter)}&_t=${Date.now()}`;
            
            const grid = document.getElementById('maintenanceGrid');
            if (grid && (!window.originalMaintenanceData || window.originalMaintenanceData.length === 0)) {
                grid.innerHTML = `
                    <div class="col-span-full text-center py-16">
                        <div class="inline-flex flex-col items-center">
                            <div class="animate-spin rounded-full h-12 w-12 border-4 border-orange-600 border-t-transparent mb-4"></div>
                            <span class="text-lg text-gray-600 font-semibold mb-2">Loading maintenance data...</span>
                            <p class="text-sm text-slate-400">Please wait while we fetch maintenance details</p>
                        </div>
                    </div>
                `;
            }
            
            try {
                const response = await fetch(url, {
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const text = await response.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (pe) {
                    console.error('API returned non-JSON:', text);
                    window.showMaintenanceError('Server returned invalid response format.');
                    return;
                }
                
                if (!response.ok || !data.success) {
                    window.showMaintenanceError((data && data.message) || `Server Error (${response.status})`);
                    return;
                }
                
                window.displayMaintenanceUnitsData(data);
            } catch (error) {
                console.error('Error loading maintenance units data:', error);
                window.showMaintenanceError(error.message || 'Error loading maintenance units data. Please try again.');
            }
        };

        window.clearMaintenanceSearch = function clearMaintenanceSearch() {
            const input = document.getElementById('maintenanceSearchInput');
            if (input) input.value = '';
            window.filterMaintenanceUnits();
        };

        window.showMaintenanceError = function showMaintenanceError(message, debugInfo = null) {
            const grid = document.getElementById('maintenanceGrid');
            if (!grid) return;
            const debugHtml = debugInfo ? `
                <div class="mt-4 p-3 bg-gray-100 rounded-lg text-xs">
                    <h4 class="font-bold text-gray-700 mb-2">Debug Information:</h4>
                    <pre class="text-gray-600 whitespace-pre-wrap">${JSON.stringify(debugInfo, null, 2)}</pre>
                </div>
            ` : '';
            
            grid.innerHTML = `
                <div class="col-span-full text-center py-20">
                    <div class="inline-flex flex-col items-center">
                        <div class="p-4 bg-red-100 rounded-full mb-4">
                            <i data-lucide="alert-circle" class="w-8 h-8 text-red-600"></i>
                        </div>
                        <span class="text-xl text-red-600 font-semibold mb-2">Error Loading Maintenance Data</span>
                        <p class="text-sm text-slate-400 mb-4">${message}</p>
                        <div class="flex gap-2">
                            <button onclick="loadMaintenanceUnitsData()" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                                <i data-lucide="refresh-cw" class="w-4 h-4 inline mr-2"></i>
                                Retry
                            </button>
                        </div>
                        ${debugHtml}
                    </div>
                </div>
            `;
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        };
    </script>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 gap-3 sm:gap-5 md:grid-cols-2 lg:grid-cols-4">

        {{-- Total Units --}}
        <div onclick="showUnitsModal()" class="card-hover wave-blue cursor-pointer group relative overflow-hidden rounded-2xl shadow-lg border border-white/60 p-[3px] bg-slate-100/50 transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl hover:border-blue-400/80">
            {{-- Animated Gradient Blob --}}
            <div class="absolute top-1/2 left-1/2 w-[240px] h-[240px] rounded-full opacity-100 filter blur-[14px] z-0 animate-blob bg-gradient-to-r from-blue-600 via-cyan-400 to-indigo-600 pointer-events-none"></div>

            {{-- Glassy Card Content Container --}}
            <div class="relative w-full h-full bg-white/90 backdrop-blur-xl rounded-[13px] outline outline-1 outline-white/80 p-3.5 sm:p-5 flex items-center justify-between z-10 overflow-hidden">
                {{-- Higher & More Visible Hover Gradient Overlay --}}
                <div class="opacity-0 group-hover:opacity-100 transition-all duration-300 absolute inset-0 h-full w-full bg-gradient-to-t from-blue-200/80 via-blue-100/40 to-transparent pointer-events-none rounded-[13px] z-0"></div>

                {{-- Left Accent Pill (from 21st.dev feature hover effect) --}}
                <div class="absolute left-0 inset-y-0 h-5 group-hover:h-9 w-1.5 rounded-tr-full rounded-br-full bg-slate-300 group-hover:bg-blue-600 transition-all duration-300 origin-center my-auto z-10"></div>

                <div class="flex-1 min-w-0 relative z-10 pl-1.5">
                    <p class="text-blue-600 text-[9px] sm:text-[10px] font-bold uppercase tracking-widest mb-1 group-hover:translate-x-1.5 transition-transform duration-300 inline-block">Total Units</p>
                    <p class="text-slate-800 text-xl sm:text-3xl font-bold tracking-tight leading-none mb-1 group-hover:translate-x-1 transition-transform duration-300" data-stat="active_units">{{ $stats['active_units'] }}</p>
                    <p class="text-blue-600 text-[10px] sm:text-xs font-medium group-hover:translate-x-1 transition-transform duration-300"><span class="text-emerald-600 font-bold" data-stat="roi_achieved">{{ $stats['roi_units'] }}</span> ROI Achieved</p>
                </div>
            </div>
            {{-- 3D Graphic Element in Background (21st.dev style) --}}
            <img src="{{ asset('image/kpi/taxi_3d.svg') }}" alt="Taxi 3D" class="absolute -right-3 -bottom-3 w-28 h-28 sm:w-32 sm:h-32 object-contain pointer-events-none opacity-40 group-hover:opacity-85 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300" style="z-index: 12 !important;">
        </div>

        {{-- Daily Boundary Collection --}}
        <div onclick="showDailyBoundaryModal()" class="card-hover wave-emerald cursor-pointer group relative overflow-hidden rounded-2xl shadow-lg border border-white/60 p-[3px] bg-slate-100/50 transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl hover:border-emerald-400/80">
            {{-- Animated Gradient Blob --}}
            <div class="absolute top-1/2 left-1/2 w-[240px] h-[240px] rounded-full opacity-100 filter blur-[14px] z-0 animate-blob-slow bg-gradient-to-r from-emerald-500 via-teal-300 to-green-500 pointer-events-none"></div>

            {{-- Glassy Card Content Container --}}
            <div class="relative w-full h-full bg-white/90 backdrop-blur-xl rounded-[13px] outline outline-1 outline-white/80 p-3.5 sm:p-5 flex items-center justify-between z-10 overflow-hidden">
                {{-- Higher & More Visible Hover Gradient Overlay --}}
                <div class="opacity-0 group-hover:opacity-100 transition-all duration-300 absolute inset-0 h-full w-full bg-gradient-to-t from-emerald-200/80 via-emerald-100/40 to-transparent pointer-events-none rounded-[13px] z-0"></div>

                {{-- Left Accent Pill (from 21st.dev feature hover effect) --}}
                <div class="absolute left-0 inset-y-0 h-5 group-hover:h-9 w-1.5 rounded-tr-full rounded-br-full bg-slate-300 group-hover:bg-emerald-600 transition-all duration-300 origin-center my-auto z-10"></div>

                <div class="flex-1 min-w-0 relative z-10 pl-1.5">
                    <p class="text-emerald-700 text-[9px] sm:text-[10px] font-bold uppercase tracking-widest mb-1 group-hover:translate-x-1.5 transition-transform duration-300 inline-block">Boundary Revenue</p>
                    <div class="flex flex-col group-hover:translate-x-1 transition-transform duration-300">
                        <span class="text-slate-800 text-lg sm:text-2xl font-bold tracking-tight leading-none mb-0.5" data-stat="today_boundary">{{ formatCurrency($stats['today_boundary']) }}</span>
                        <span class="text-[9px] sm:text-[10px] font-bold text-emerald-600 uppercase">Today</span>
                    </div>
                    <div class="mt-2 pt-2 sm:mt-3 sm:pt-3 border-t border-emerald-200/80 group-hover:translate-x-1 transition-transform duration-300">
                        <p class="text-slate-800 text-base sm:text-lg font-bold leading-none mb-0.5" data-stat="month_boundary">{{ formatCurrency($stats['month_boundary'] ?? 0) }}</p>
                        <p class="text-emerald-700 text-[8px] sm:text-[9px] font-bold uppercase tracking-widest">This Month</p>
                    </div>
                </div>
            </div>
            {{-- 3D Graphic Element in Background (21st.dev style) --}}
            <img src="{{ asset('image/kpi/revenue_3d.svg') }}" alt="Revenue 3D" class="absolute -right-3 -bottom-3 w-28 h-28 sm:w-32 sm:h-32 object-contain pointer-events-none opacity-40 group-hover:opacity-85 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300" style="z-index: 12 !important;">
        </div>

        {{-- Net Income --}}
        <div onclick="showNetIncomeModal()" class="card-hover wave-green cursor-pointer group relative overflow-hidden rounded-2xl shadow-lg border border-white/60 p-[3px] bg-slate-100/50 transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl hover:border-green-400/80">
            {{-- Animated Gradient Blob --}}
            <div class="absolute top-1/2 left-1/2 w-[240px] h-[240px] rounded-full opacity-100 filter blur-[14px] z-0 animate-blob-fast bg-gradient-to-r from-green-500 via-lime-300 to-emerald-600 pointer-events-none"></div>

            {{-- Glassy Card Content Container --}}
            <div class="relative w-full h-full bg-white/90 backdrop-blur-xl rounded-[13px] outline outline-1 outline-white/80 p-3.5 sm:p-5 flex items-center justify-between z-10 overflow-hidden">
                {{-- Higher & More Visible Hover Gradient Overlay --}}
                <div class="opacity-0 group-hover:opacity-100 transition-all duration-300 absolute inset-0 h-full w-full bg-gradient-to-t from-green-200/80 via-green-100/40 to-transparent pointer-events-none rounded-[13px] z-0"></div>

                {{-- Left Accent Pill (from 21st.dev feature hover effect) --}}
                <div class="absolute left-0 inset-y-0 h-5 group-hover:h-9 w-1.5 rounded-tr-full rounded-br-full bg-slate-300 group-hover:bg-green-600 transition-all duration-300 origin-center my-auto z-10"></div>

                <div class="flex-1 min-w-0 relative z-10 pl-1.5">
                    <p class="text-green-700 text-[9px] sm:text-[10px] font-bold uppercase tracking-widest mb-1 group-hover:translate-x-1.5 transition-transform duration-300 inline-block">Net Income (Kita)</p>
                    <div class="flex flex-col group-hover:translate-x-1 transition-transform duration-300">
                        <span class="text-slate-800 text-lg sm:text-2xl font-bold tracking-tight leading-none mb-0.5" data-stat="net_income">{{ formatCurrency($stats['net_income']) }}</span>
                        <span class="text-[9px] sm:text-[10px] font-bold text-green-600 uppercase">Today</span>
                    </div>
                    <div class="mt-2 pt-2 sm:mt-3 sm:pt-3 border-t border-green-200/80 group-hover:translate-x-1 transition-transform duration-300">
                        <p class="text-slate-800 text-base sm:text-lg font-bold leading-none mb-0.5" data-stat="net_income_month">{{ formatCurrency($stats['net_income_month'] ?? 0) }}</p>
                        <p class="text-green-700 text-[8px] sm:text-[9px] font-bold uppercase tracking-widest">This Month</p>
                    </div>
                </div>
            </div>
            {{-- 3D Graphic Element in Background (21st.dev style) --}}
            <img src="{{ asset('image/kpi/profit_3d.svg') }}" alt="Profit 3D" class="absolute -right-3 -bottom-3 w-28 h-28 sm:w-32 sm:h-32 object-contain pointer-events-none opacity-40 group-hover:opacity-85 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300" style="z-index: 12 !important;">
        </div>

        {{-- Units Under Maintenance --}}
        <div onclick="showMaintenanceUnitsModal()" class="card-hover wave-orange cursor-pointer group relative overflow-hidden rounded-2xl shadow-lg border border-white/60 p-[3px] bg-slate-100/50 transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl hover:border-orange-400/80">
            {{-- Animated Gradient Blob --}}
            <div class="absolute top-1/2 left-1/2 w-[240px] h-[240px] rounded-full opacity-100 filter blur-[14px] z-0 animate-blob-reverse bg-gradient-to-r from-orange-500 via-amber-300 to-red-500 pointer-events-none"></div>

            {{-- Glassy Card Content Container --}}
            <div class="relative w-full h-full bg-white/90 backdrop-blur-xl rounded-[13px] outline outline-1 outline-white/80 p-3.5 sm:p-5 flex items-center justify-between z-10 overflow-hidden">
                {{-- Higher & More Visible Hover Gradient Overlay --}}
                <div class="opacity-0 group-hover:opacity-100 transition-all duration-300 absolute inset-0 h-full w-full bg-gradient-to-t from-orange-200/80 via-amber-100/40 to-transparent pointer-events-none rounded-[13px] z-0"></div>

                {{-- Left Accent Pill (from 21st.dev feature hover effect) --}}
                <div class="absolute left-0 inset-y-0 h-5 group-hover:h-9 w-1.5 rounded-tr-full rounded-br-full bg-slate-300 group-hover:bg-orange-600 transition-all duration-300 origin-center my-auto z-10"></div>

                <div class="flex-1 min-w-0 relative z-10 pl-1.5">
                    <p class="text-orange-600 text-[9px] sm:text-[10px] font-bold uppercase tracking-widest mb-1 group-hover:translate-x-1.5 transition-transform duration-300 inline-block">Under Maintenance</p>
                    <p class="text-slate-800 text-xl sm:text-3xl font-bold tracking-tight leading-none mb-1 group-hover:translate-x-1 transition-transform duration-300" data-stat="maintenance_units">{{ $stats['maintenance_units'] }}</p>
                    <p class="text-orange-600 text-[10px] sm:text-xs font-medium truncate group-hover:translate-x-1 transition-transform duration-300" data-stat="maintenance_subtitle">Ongoing units</p>
                </div>
            </div>
            {{-- 3D Graphic Element in Background (21st.dev style) --}}
            <img src="{{ asset('image/kpi/maintenance_3d.svg') }}" alt="Maintenance 3D" class="absolute -right-3 -bottom-3 w-28 h-28 sm:w-32 sm:h-32 object-contain pointer-events-none opacity-40 group-hover:opacity-85 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300" style="z-index: 12 !important;">
        </div>

    </div>

    <!-- Quick Stats -->
    <div class="mt-4 grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">

        {{-- Active Drivers --}}
        <div onclick="showActiveDriversModal()" class="card-hover wave-indigo cursor-pointer group relative overflow-hidden rounded-2xl shadow-lg border border-white/60 p-[3px] bg-slate-100/50 transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl hover:border-indigo-400/80">
            {{-- Animated Gradient Blob --}}
            <div class="absolute top-1/2 left-1/2 w-[240px] h-[240px] rounded-full opacity-100 filter blur-[14px] z-0 animate-blob-slow bg-gradient-to-r from-indigo-600 via-violet-400 to-blue-600 pointer-events-none"></div>

            {{-- Glassy Card Content Container --}}
            <div class="relative w-full h-full bg-white/90 backdrop-blur-xl rounded-[13px] outline outline-1 outline-white/80 p-3.5 sm:p-5 flex items-center justify-between z-10 overflow-hidden">
                {{-- Higher & More Visible Hover Gradient Overlay --}}
                <div class="opacity-0 group-hover:opacity-100 transition-all duration-300 absolute inset-0 h-full w-full bg-gradient-to-t from-indigo-200/80 via-indigo-100/40 to-transparent pointer-events-none rounded-[13px] z-0"></div>

                {{-- Left Accent Pill (from 21st.dev feature hover effect) --}}
                <div class="absolute left-0 inset-y-0 h-5 group-hover:h-9 w-1.5 rounded-tr-full rounded-br-full bg-slate-300 group-hover:bg-indigo-600 transition-all duration-300 origin-center my-auto z-10"></div>

                <div class="flex-1 min-w-0 relative z-10 pl-1.5">
                    <p class="text-indigo-600 text-[9px] sm:text-[10px] font-bold uppercase tracking-widest mb-1 group-hover:translate-x-1.5 transition-transform duration-300 inline-block">Active Drivers</p>
                    <p class="text-slate-800 text-xl sm:text-3xl font-bold tracking-tight leading-none group-hover:translate-x-1 transition-transform duration-300" data-stat="active_drivers">{{ $stats['active_drivers'] }}</p>
                </div>
            </div>
            {{-- 3D Graphic Element in Background (21st.dev style) --}}
            <img src="{{ asset('image/kpi/drivers_3d.svg') }}" alt="Drivers 3D" class="absolute -right-3 -bottom-3 w-28 h-28 sm:w-32 sm:h-32 object-contain pointer-events-none opacity-40 group-hover:opacity-85 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300" style="z-index: 12 !important;">
        </div>

        {{-- Total Expenses Today --}}
        <div onclick="showExpensesModal()" class="card-hover wave-rose cursor-pointer group relative overflow-hidden rounded-2xl shadow-lg border border-white/60 p-[3px] bg-slate-100/50 transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl hover:border-rose-400/80">
            {{-- Animated Gradient Blob --}}
            <div class="absolute top-1/2 left-1/2 w-[240px] h-[240px] rounded-full opacity-100 filter blur-[14px] z-0 animate-blob bg-gradient-to-r from-rose-500 via-red-400 to-pink-500 pointer-events-none"></div>

            {{-- Glassy Card Content Container --}}
            <div class="relative w-full h-full bg-white/90 backdrop-blur-xl rounded-[13px] outline outline-1 outline-white/80 p-3.5 sm:p-5 flex items-center justify-between z-10 overflow-hidden">
                {{-- Higher & More Visible Hover Gradient Overlay --}}
                <div class="opacity-0 group-hover:opacity-100 transition-all duration-300 absolute inset-0 h-full w-full bg-gradient-to-t from-rose-200/80 via-rose-100/40 to-transparent pointer-events-none rounded-[13px] z-0"></div>

                {{-- Left Accent Pill (from 21st.dev feature hover effect) --}}
                <div class="absolute left-0 inset-y-0 h-5 group-hover:h-9 w-1.5 rounded-tr-full rounded-br-full bg-slate-300 group-hover:bg-rose-600 transition-all duration-300 origin-center my-auto z-10"></div>

                <div class="flex-1 min-w-0 relative z-10 pl-1.5">
                    <p class="text-rose-600 text-[9px] sm:text-[10px] font-bold uppercase tracking-widest mb-1 group-hover:translate-x-1.5 transition-transform duration-300 inline-block">Expenses Today</p>
                    <p class="text-slate-800 text-lg sm:text-2xl font-bold tracking-tight leading-none group-hover:translate-x-1 transition-transform duration-300" data-stat="today_expenses">{{ formatCurrency($stats['total_expenses_today']) }}</p>
                </div>
            </div>
            {{-- 3D Graphic Element in Background (21st.dev style) --}}
            <img src="{{ asset('image/kpi/expenses_3d.svg') }}" alt="Expenses 3D" class="absolute -right-3 -bottom-3 w-28 h-28 sm:w-32 sm:h-32 object-contain pointer-events-none opacity-40 group-hover:opacity-85 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300" style="z-index: 12 !important;">
        </div>

        {{-- Coding Units Today --}}
        <div onclick="showCodingUnitsModal()" class="col-span-2 lg:col-span-1 card-hover wave-violet cursor-pointer group relative overflow-hidden rounded-2xl shadow-lg border border-white/60 p-[3px] bg-slate-100/50 transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl hover:border-violet-400/80">
            {{-- Animated Gradient Blob --}}
            <div class="absolute top-1/2 left-1/2 w-[240px] h-[240px] rounded-full opacity-100 filter blur-[14px] z-0 animate-blob-reverse bg-gradient-to-r from-violet-600 via-fuchsia-400 to-purple-600 pointer-events-none"></div>

            {{-- Glassy Card Content Container --}}
            <div class="relative w-full h-full bg-white/90 backdrop-blur-xl rounded-[13px] outline outline-1 outline-white/80 p-3.5 sm:p-5 flex items-center justify-between z-10 overflow-hidden">
                {{-- Higher & More Visible Hover Gradient Overlay --}}
                <div class="opacity-0 group-hover:opacity-100 transition-all duration-300 absolute inset-0 h-full w-full bg-gradient-to-t from-violet-200/80 via-violet-100/40 to-transparent pointer-events-none rounded-[13px] z-0"></div>

                {{-- Left Accent Pill (from 21st.dev feature hover effect) --}}
                <div class="absolute left-0 inset-y-0 h-5 group-hover:h-9 w-1.5 rounded-tr-full rounded-br-full bg-slate-300 group-hover:bg-violet-600 transition-all duration-300 origin-center my-auto z-10"></div>

                <div class="flex-1 min-w-0 relative z-10 pl-1.5">
                    <p class="text-violet-600 text-[9px] sm:text-[10px] font-bold uppercase tracking-widest mb-1 group-hover:translate-x-1.5 transition-transform duration-300 inline-block">Coding Units Today</p>
                    <p class="text-slate-800 text-xl sm:text-3xl font-bold tracking-tight leading-none mb-1 group-hover:translate-x-1 transition-transform duration-300" data-stat="coding_units">{{ $stats['coding_units'] }}</p>
                    <p class="text-violet-600 text-[9px] sm:text-[10px] font-bold uppercase tracking-tight group-hover:translate-x-1 transition-transform duration-300">{{ now()->timezone('Asia/Manila')->format('l') }}</p>
                </div>
            </div>
            {{-- 3D Graphic Element in Background (21st.dev style) --}}
            <img src="{{ asset('image/kpi/coding_3d.svg') }}" alt="Coding 3D" class="absolute -right-3 -bottom-3 w-28 h-28 sm:w-32 sm:h-32 object-contain pointer-events-none opacity-40 group-hover:opacity-85 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300" style="z-index: 12 !important;">
        </div>

    </div>

    <!-- Unit Performance (Full Width) -->
    <div class="mt-4 bg-white rounded-2xl shadow-md border-2 border-slate-200/90 overflow-hidden">
        <div class="p-4 border-b bg-gray-50/50 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="p-1.5 bg-blue-100 rounded-lg">
                    <i data-lucide="bar-chart-3" class="w-4 h-4 text-blue-600"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800 uppercase tracking-tight">Unit Performance</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5 leading-tight">Total actual boundary collections over the <strong>last 30 days</strong> vs their 30-day target.</p>
                </div>
            </div>
            <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-full uppercase tracking-widest border border-blue-100">Top 10 Performers</span>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-4">
            <div class="lg:col-span-3 p-6">
                <div style="height: 380px;">
                    <canvas id="unitPerformanceChart"></canvas>
                </div>
            </div>
            <!-- Executive Insight Panel -->
            <div class="bg-gray-50 p-6 border-l border-gray-100 flex flex-col justify-center">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Executive Insights</h4>
                <div class="space-y-8">
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-2">Fleet Health</p>
                        <div class="flex items-end gap-2">
                            <p class="text-3xl font-bold text-slate-800 leading-none">82%</p>
                            <p class="text-xs font-bold text-green-600 flex items-center mb-0.5">
                                <i data-lucide="trending-up" class="w-3 h-3 mr-0.5"></i> +2.4%
                            </p>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-2 leading-relaxed font-medium">Most units are meeting over 80% of their monthly boundary targets.</p>
                    </div>
                    
                    <div class="pt-6 border-t border-gray-200">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-2">Top Performer</p>
                        <p class="text-base font-bold text-slate-800" id="insightTopPlate">--</p>
                        <p class="text-[11px] text-slate-500 mt-2 font-medium">Consistency in daily collections makes this your most reliable asset.</p>
                    </div>

                    <div class="pt-6 border-t border-gray-200">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-3">Legend</p>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded bg-blue-500 shadow-md shadow-slate-200/40"></div>
                                <span class="text-[10px] font-bold text-gray-600 uppercase tracking-widest">Actual Collection</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded border-2 border-amber-500 bg-amber-500/20"></div>
                                <span class="text-[10px] font-bold text-gray-600 uppercase tracking-widest">Monthly Target</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Trend (Full Width) -->
    <div class="mt-4 bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 bg-blue-50 rounded-lg">
                        <i data-lucide="trending-up" class="w-4 h-4 text-blue-600"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 uppercase tracking-tight">Revenue Trend</h3>
                </div>
                <div class="flex gap-2">
                    <button onclick="updateRevenueTrend('7')" id="btn-7days" class="px-3 py-1 text-[10px] font-bold uppercase rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-all shadow-md shadow-slate-200/40">
                        7 Days
                    </button>
                    <button onclick="updateRevenueTrend('30')" id="btn-30days" class="px-3 py-1 text-[10px] font-bold uppercase rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all border border-gray-200">
                        30 Days
                    </button>
                    <button onclick="updateRevenueTrend('90')" id="btn-90days" class="px-3 py-1 text-[10px] font-bold uppercase rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all border border-gray-200">
                        3 Months
                    </button>
                    <button onclick="updateRevenueTrend('365')" id="btn-365days" class="px-3 py-1 text-[10px] font-bold uppercase rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all border border-gray-200">
                        1 Year
                    </button>
                </div>
            </div>
        </div>
        <div class="p-4">
            <canvas id="revenueTrendChart" style="width: 100%; height: 320px;"></canvas>
        </div>
    </div>

    <!-- Secondary Analytics Grid (Aligned for Balance) -->
    <div class="mt-4 grid grid-cols-1 lg:grid-cols-2 gap-4">

        <div class="bg-white rounded-lg shadow">
            <div class="p-4 border-b">
                <h3 class="text-base font-semibold text-slate-800">Expense Breakdown & Distribution</h3>
            </div>
            <div class="p-4">
                <canvas id="expenseBreakdownChart" width="400" height="200"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="p-4 border-b">
                <h3 class="text-base font-semibold text-slate-800">Weekly Financial Overview</h3>
            </div>
            <div class="p-4">
                <canvas id="weeklyChart" width="400" height="200"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="p-4 border-b">
                <h3 class="text-base font-semibold text-slate-800">Unit Status Distribution</h3>
            </div>
            <div class="p-4">
                <canvas id="unitStatusChart" width="400" height="200"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="p-4 border-b">
                <h3 class="text-base font-semibold text-slate-800">Top Performing Drivers</h3>
            </div>
            <div class="p-4">
                <canvas id="topDriversChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Maintenance Units Modal -->
<div id="maintenanceUnitsModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-6xl h-[95vh] flex flex-col overflow-hidden">
        <!-- Modal Header -->
        <div class="relative pt-6 pb-4 px-5 sm:pt-7 sm:pb-5 sm:px-6 border-b bg-gradient-to-r from-orange-600 via-amber-600 to-yellow-600 flex-shrink-0 overflow-hidden">
            <!-- Large Animated Shiny Background Text (Shifted up for clear full view) -->
            <div class="absolute inset-x-0 top-1 sm:top-2 flex items-center justify-center pointer-events-none select-none z-0 overflow-hidden px-4" aria-hidden="true">
                <h1 class="animated-shiny-units-text text-lg sm:text-2xl md:text-3xl lg:text-4xl xl:text-5xl font-black uppercase tracking-wider whitespace-nowrap opacity-90">
                    Units Under Maintenance
                </h1>
            </div>

            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4 sm:mb-5">
                    <div class="flex items-end gap-3">
                        <div class="p-2.5 bg-white/20 backdrop-blur-sm rounded-xl border border-white/30">
                            <i data-lucide="wrench" class="w-6 h-6 text-white"></i>
                        </div>
                        <div class="pb-1">
                            <p class="text-orange-100 text-xs font-medium">Complete maintenance tracking details</p>
                        </div>
                    </div>
                    <button onclick="hideMaintenanceUnitsModal()" class="text-white/80 hover:text-white hover:bg-white/20 p-2 rounded-lg transition-all duration-200 backdrop-blur-sm">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                
                <!-- Search and Date Filter -->
                <div class="flex items-center gap-3">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="search" class="w-4 h-4 text-white/60"></i>
                        </div>
                        <input type="search" 
                            id="maintenanceSearchInput"
                            placeholder="Search by unit number, plate, or maintenance type..."
                            class="w-full pl-10 pr-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent transition-all duration-200 text-sm"
                            onkeyup="filterMaintenanceUnits()"
                         autocomplete="new-password" spellcheck="false" autocorrect="off" autocapitalize="off" readonly onfocus="this.removeAttribute('readonly');">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button onclick="clearMaintenanceSearch()" class="text-white/60 hover:text-white transition-colors">
                                <i data-lucide="x-circle" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    <div class="flex bg-white/10 backdrop-blur-sm border border-white/30 rounded-lg p-1">
                        <button onclick="setMaintenanceFilter('all')" id="mFilterAll" class="px-3 py-1.5 text-xs font-bold rounded-md transition-all duration-200 bg-white text-orange-600 shadow-sm cursor-pointer">
                            All
                        </button>
                        <button onclick="setMaintenanceFilter('preventive')" id="mFilterPreventive" class="px-3 py-1.5 text-xs font-medium rounded-md transition-all duration-200 text-white/80 hover:text-white hover:bg-white/10 cursor-pointer">
                            Preventive
                        </button>
                        <button onclick="setMaintenanceFilter('corrective')" id="mFilterCorrective" class="px-3 py-1.5 text-xs font-medium rounded-md transition-all duration-200 text-white/80 hover:text-white hover:bg-white/10 cursor-pointer">
                            Corrective
                        </button>
                        <button onclick="setMaintenanceFilter('emergency')" id="mFilterEmergency" class="px-3 py-1.5 text-xs font-medium rounded-md transition-all duration-200 text-white/80 hover:text-white hover:bg-white/10 cursor-pointer">
                            Emergency
                        </button>
                        <button onclick="setMaintenanceFilter('complete')" id="mFilterComplete" class="px-3 py-1.5 text-xs font-medium rounded-md transition-all duration-200 text-white/80 hover:text-white hover:bg-white/10 cursor-pointer">
                            Complete
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex-1 overflow-hidden flex flex-col min-h-0">
            <!-- Summary Stats -->
            <div class="bg-gradient-to-r from-orange-50 to-amber-50 p-4 border-b border-orange-200 flex-shrink-0">
                <div class="grid grid-cols-2 lg:grid-cols-5 gap-3">
                    <!-- Total Maintenance -->
                    <div class="bg-white rounded-lg p-3 shadow-md shadow-slate-200/40 border border-orange-100 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 bg-orange-100 rounded">
                                <i data-lucide="wrench" class="w-4 h-4 text-orange-600"></i>
                            </div>
                            <div>
                                <div class="text-lg font-bold text-orange-600" id="maintenanceUnitsCount">{{ $initial_maintenance['stats']['total_maintenance'] ?? 0 }}</div>
                                <div class="text-[10px] text-gray-600 uppercase tracking-wide font-bold">Maintenance</div>
                            </div>
                        </div>
                    </div>
                    <!-- Preventive -->
                    <div class="bg-white rounded-lg p-3 shadow-md shadow-slate-200/40 border border-blue-100 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 bg-blue-100 rounded">
                                <i data-lucide="shield-check" class="w-4 h-4 text-blue-600"></i>
                            </div>
                            <div>
                                <div class="text-lg font-bold text-blue-600" id="preventiveMaintenanceCount">{{ $initial_maintenance['stats']['preventive_maintenance'] ?? 0 }}</div>
                                <div class="text-[10px] text-gray-600 uppercase tracking-wide font-bold">Preventive</div>
                            </div>
                        </div>
                    </div>
                    <!-- Corrective -->
                    <div class="bg-white rounded-lg p-3 shadow-md shadow-slate-200/40 border border-amber-100 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 bg-amber-100 rounded">
                                <i data-lucide="wrench" class="w-4 h-4 text-amber-600"></i>
                            </div>
                            <div>
                                <div class="text-lg font-bold text-amber-600" id="correctiveMaintenanceCount">{{ $initial_maintenance['stats']['corrective_maintenance'] ?? 0 }}</div>
                                <div class="text-[10px] text-gray-600 uppercase tracking-wide font-bold">Corrective</div>
                            </div>
                        </div>
                    </div>
                    <!-- Emergency -->
                    <div class="bg-white rounded-lg p-3 shadow-md shadow-slate-200/40 border border-red-100 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 bg-red-100 rounded">
                                <i data-lucide="alert-triangle" class="w-4 h-4 text-red-600"></i>
                            </div>
                            <div>
                                <div class="text-lg font-bold text-red-600" id="emergencyMaintenanceCount">{{ $initial_maintenance['stats']['emergency_maintenance'] ?? 0 }}</div>
                                <div class="text-[10px] text-gray-600 uppercase tracking-wide font-bold">Emergency</div>
                            </div>
                        </div>
                    </div>
                    <!-- Completed -->
                    <div class="bg-white rounded-lg p-3 shadow-md shadow-slate-200/40 border border-green-100 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 bg-green-100 rounded">
                                <i data-lucide="check-circle" class="w-4 h-4 text-green-600"></i>
                            </div>
                            <div>
                                <div class="text-lg font-bold text-green-600" id="completedTotalCount">{{ $initial_maintenance['stats']['completed_total'] ?? 0 }}</div>
                                <div class="text-[10px] text-gray-600 uppercase tracking-wide font-bold">Complete</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Maintenance Units Grid -->
            <div class="flex-1 overflow-y-auto p-4 bg-gray-50 min-h-0">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 pb-4" id="maintenanceGrid">
                    @if(!empty($initial_maintenance['units']) && count($initial_maintenance['units']) > 0)
                        @foreach($initial_maintenance['units'] as $unit)
                            <div onclick="showMaintenanceDetailsModal({{ $unit['maintenance_id'] ?? $unit['id'] ?? 0 }})" class="cursor-pointer bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden border-l-4 border-orange-500 hover:scale-102">
                                <div class="p-4">
                                    <!-- Header -->
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-orange-100 rounded-lg">
                                                <i data-lucide="wrench" class="w-4 h-4 text-orange-600"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-lg font-bold text-slate-800">{{ $unit['plate_number'] ?? 'N/A' }}</h4>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-lg font-bold text-orange-600">{{ $unit['maintenance_type'] ?? 'Unknown' }}</div>
                                            <div class="text-xs text-slate-500">{{ $unit['start_date'] ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Maintenance Details -->
                                    <div class="bg-gray-50 rounded-lg p-3 mb-3">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm font-medium text-slate-800">Status: {{ $unit['maintenance_status'] ?? 'Unknown' }}</span>
                                            <span class="text-xs font-bold text-orange-600">{{ !empty($unit['estimated_completion']) ? 'Est: ' . $unit['estimated_completion'] : '' }}</span>
                                        </div>
                                        <div class="text-xs text-gray-600">
                                            <span class="font-medium">Description:</span> {{ $unit['description'] ?? 'No description available' }}
                                        </div>
                                    </div>
                                    
                                    <!-- Footer -->
                                    <div class="flex items-center justify-between text-xs text-slate-500">
                                        <span class="flex items-center gap-1">
                                            <i data-lucide="calendar" class="w-3 h-3"></i>
                                            Started: {{ $unit['start_date'] ?? 'N/A' }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <i data-lucide="check-circle" class="w-3 h-3"></i>
                                            {{ $unit['maintenance_status'] ?? 'Unknown' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-span-full text-center py-20">
                            <div class="inline-flex flex-col items-center">
                                <div class="p-4 bg-gray-100 rounded-full mb-4">
                                    <i data-lucide="wrench" class="w-8 h-8 text-slate-400"></i>
                                </div>
                                <span class="text-xl text-gray-600 font-semibold mb-2">No maintenance units found</span>
                                <p class="text-sm text-slate-400">All units are active and running</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Maintenance Details Modal -->
<div id="maintenanceDetailsModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-[60] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-4xl max-h-[90vh] flex flex-col shadow-2xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-orange-600 to-amber-600 p-4 sm:p-5 flex justify-between items-center shrink-0">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                    <i data-lucide="wrench" class="w-5 h-5 text-white"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white tracking-wide uppercase" id="mdm-plate">PLATE</h3>
                    <p class="text-[10px] text-orange-100 font-bold uppercase tracking-widest" id="mdm-type">Maintenance Details</p>
                </div>
            </div>
            <button onclick="hideMaintenanceDetailsModal()" class="text-white/80 hover:text-white hover:bg-white/20 p-2 rounded-lg transition-all duration-200">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <!-- Content -->
        <div class="p-4 sm:p-6 overflow-y-auto flex-1 bg-gray-50">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-3 rounded-xl ring-1 ring-slate-900/5 shadow-md shadow-slate-200/40 flex flex-col justify-center">
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mb-1">Assigned Mechanic</p>
                    <p class="text-sm font-bold text-gray-800 flex items-center gap-2" id="mdm-mechanic">
                        <i data-lucide="user-cog" class="w-4 h-4 text-orange-500 flex-shrink-0"></i> <span>Name</span>
                    </p>
                </div>
                <div class="bg-white p-3 rounded-xl ring-1 ring-slate-900/5 shadow-md shadow-slate-200/40 flex flex-col justify-center">
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mb-1">Driver</p>
                    <p class="text-sm font-bold text-gray-800 flex items-center gap-2" id="mdm-driver">
                        <i data-lucide="user" class="w-4 h-4 text-blue-500 flex-shrink-0"></i> <span>Name</span>
                    </p>
                </div>
                <div class="bg-white p-3 rounded-xl ring-1 ring-slate-900/5 shadow-md shadow-slate-200/40 flex flex-col justify-center">
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mb-1">Date Started</p>
                    <p class="text-sm font-bold text-gray-800 flex items-center gap-2" id="mdm-start-date">
                        <i data-lucide="calendar" class="w-4 h-4 text-green-500 flex-shrink-0"></i> <span>Date</span>
                    </p>
                </div>
                <div class="bg-white p-3 rounded-xl ring-1 ring-slate-900/5 shadow-md shadow-slate-200/40 flex flex-col justify-center">
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mb-1">Target Completion</p>
                    <p class="text-sm font-bold text-gray-800 flex items-center gap-2" id="mdm-end-date">
                        <i data-lucide="clock" class="w-4 h-4 text-purple-500 flex-shrink-0"></i> <span>TBD</span>
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-xl ring-1 ring-slate-900/5 shadow-md shadow-slate-200/40 overflow-hidden mb-6">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-100 flex items-center gap-2">
                    <i data-lucide="list-checks" class="w-4 h-4 text-slate-500"></i>
                    <h4 class="text-[10px] font-bold text-gray-700 uppercase tracking-widest">Parts & Services Breakdown</h4>
                </div>
                <div class="p-0 min-h-[150px]">
                    <div id="mdm-parts-loading" class="py-12 text-center text-slate-400">
                        <i data-lucide="loader-2" class="w-8 h-8 animate-spin mx-auto mb-3 text-orange-500"></i>
                        <p class="text-[10px] font-bold uppercase tracking-widest">Fetching data...</p>
                    </div>
                    <ul id="mdm-parts-list" class="divide-y divide-gray-50 hidden max-h-[320px] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-200 scrollbar-track-transparent">
                        <!-- Populated by JS -->
                    </ul>
                </div>
            </div>

            <!-- Totals -->
            <div class="bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl p-4 border border-orange-100 flex justify-between items-center">
                <div>
                    <p class="text-[10px] text-orange-600/70 font-bold uppercase tracking-widest">Total Cost</p>
                    <p class="text-xs font-bold uppercase tracking-widest mt-0.5" id="mdm-status-badge">Status</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-orange-600" id="mdm-total-cost">₱0.00</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Active Drivers Modal -->
<div id="activeDriversModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-6xl h-[95vh] flex flex-col overflow-hidden">
        <!-- Modal Header -->
        <div class="relative pt-6 pb-4 px-5 sm:pt-7 sm:pb-5 sm:px-6 border-b bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 flex-shrink-0 overflow-hidden">
            <!-- Large Animated Shiny Background Text (Shifted up for clear full view) -->
            <div class="absolute inset-x-0 top-1 sm:top-2 flex items-center justify-center pointer-events-none select-none z-0 overflow-hidden px-4" aria-hidden="true">
                <h1 class="animated-shiny-units-text text-2xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-black uppercase tracking-wider whitespace-nowrap opacity-90">
                    Active Drivers
                </h1>
            </div>

            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4 sm:mb-5">
                    <div class="flex items-end gap-3">
                        <div class="p-2.5 bg-white/20 backdrop-blur-sm rounded-xl border border-white/30">
                            <i data-lucide="users" class="w-6 h-6 text-white"></i>
                        </div>
                        <div class="pb-1">
                            <p class="text-blue-100 text-xs font-medium">Complete driver management details</p>
                        </div>
                    </div>
                    <button onclick="hideActiveDriversModal()" class="text-white/80 hover:text-white hover:bg-white/20 p-2 rounded-lg transition-all duration-200 backdrop-blur-sm">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                
                <!-- Search and Date Filter -->
                <div class="flex items-center gap-3">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="search" class="w-4 h-4 text-white/60"></i>
                        </div>
                        <input type="search" 
                            id="driversSearchInput"
                            placeholder="Search by name, license, or contact..."
                            class="w-full pl-10 pr-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent transition-all duration-200 text-sm"
                            onkeyup="filterActiveDrivers()"
                         autocomplete="new-password" spellcheck="false" autocorrect="off" autocapitalize="off" readonly onfocus="this.removeAttribute('readonly');">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button onclick="clearDriversSearch()" class="text-white/60 hover:text-white transition-colors">
                                <i data-lucide="x-circle" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    
                    <button 
                        onclick="toggleDriversSort()" 
                        id="driversSortBtn"
                        class="px-3 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg text-white hover:bg-white/30 transition-all duration-200 text-sm flex items-center gap-2 min-w-[90px] justify-center"
                    >
                        <i data-lucide="sort-asc" id="driversSortIcon" class="w-4 h-4"></i>
                        <span id="driversSortText">A-Z</span>
                    </button>

                    <!-- Category Filter Buttons -->
                    <div class="hidden md:flex items-center gap-1 bg-white/20 backdrop-blur-sm p-1 rounded-xl border border-white/30">
                        <button onclick="setDriversFilter('all')" id="dFilterAll" class="px-4 py-1.5 rounded-lg text-sm font-bold transition-all duration-200 bg-white text-blue-600 shadow-md shadow-slate-200/40">
                            All
                        </button>
                        <button onclick="setDriversFilter('vacant')" id="dFilterVacant" class="px-4 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 text-white hover:bg-white/10">
                            Vacant
                        </button>
                        <button onclick="setDriversFilter('active')" id="dFilterActive" class="px-4 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 text-white hover:bg-white/10">
                            Active
                        </button>
                        <button onclick="setDriversFilter('top')" id="dFilterTop" class="px-4 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 text-white hover:bg-white/10">
                            Top Performers
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex-1 overflow-hidden flex flex-col min-h-0">
            <!-- Summary Stats -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 border-b border-blue-200 flex-shrink-0">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white rounded-lg p-3 shadow-md shadow-slate-200/40 border border-blue-100 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 bg-blue-100 rounded">
                                <i data-lucide="users" class="w-4 h-4 text-blue-600"></i>
                            </div>
                            <div>
                                <div class="text-lg font-bold text-blue-600" id="totalDriversCount">0</div>
                                <div class="text-xs text-gray-600 uppercase tracking-wide font-medium">Total Drivers</div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-3 shadow-md shadow-slate-200/40 border border-green-100 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 bg-green-100 rounded">
                                <i data-lucide="user-minus" class="w-4 h-4 text-green-600"></i>
                            </div>
                            <div>
                                <div class="text-lg font-bold text-green-600" id="vacantDriversCount">0</div>
                                <div class="text-xs text-gray-600 uppercase tracking-wide font-medium">Total Vacant Drivers</div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-3 shadow-md shadow-slate-200/40 border border-orange-100 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 bg-orange-100 rounded">
                                <i data-lucide="user-check" class="w-4 h-4 text-orange-600"></i>
                            </div>
                            <div>
                                <div class="text-lg font-bold text-orange-600" id="activeWithUnitsCount">0</div>
                                <div class="text-xs text-gray-600 uppercase tracking-wide font-medium">Total Active Drivers</div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-3 shadow-md shadow-slate-200/40 border border-purple-100 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 bg-purple-100 rounded">
                                <i data-lucide="award" class="w-4 h-4 text-purple-600"></i>
                            </div>
                            <div>
                                <div class="text-lg font-bold text-purple-600" id="topPerformersCount">0</div>
                                <div class="text-xs text-gray-600 uppercase tracking-wide font-medium">Top Performers</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Drivers Grid -->
            <div class="flex-1 overflow-y-auto p-4 bg-gray-50 min-h-0">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 pb-4" id="activeDriversGrid">
                    <!-- Loading State -->
                    <div class="col-span-full text-center py-16">
                        <div class="inline-flex flex-col items-center">
                            <div class="animate-spin rounded-full h-12 w-12 border-4 border-blue-600 border-t-transparent mb-4"></div>
                            <span class="text-lg text-gray-600 font-semibold mb-2">Loading driver data...</span>
                            <p class="text-sm text-slate-400">Please wait while we fetch driver details</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Coding Units Modal -->
<div id="codingUnitsModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-6xl h-[95vh] flex flex-col overflow-hidden">
        <!-- Modal Header -->
        <div class="relative pt-6 pb-4 px-5 sm:pt-7 sm:pb-5 sm:px-6 border-b bg-gradient-to-r from-purple-600 via-pink-600 to-rose-600 flex-shrink-0 overflow-hidden">
            <!-- Large Animated Shiny Background Text (Shifted up for clear full view) -->
            <div class="absolute inset-x-0 top-1 sm:top-2 flex items-center justify-center pointer-events-none select-none z-0 overflow-hidden px-4" aria-hidden="true">
                <h1 class="animated-shiny-units-text text-2xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-black uppercase tracking-wider whitespace-nowrap opacity-90">
                    Coding Units
                </h1>
            </div>

            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4 sm:mb-5">
                    <div class="flex items-end gap-3">
                        <div class="p-2.5 bg-white/20 backdrop-blur-sm rounded-xl border border-white/30">
                            <i data-lucide="code" class="w-6 h-6 text-white"></i>
                        </div>
                        <div class="pb-1">
                            <p class="text-purple-100 text-xs font-medium">Complete coding unit management details</p>
                        </div>
                    </div>
                    <button onclick="hideCodingUnitsModal()" class="text-white/80 hover:text-white hover:bg-white/20 p-2 rounded-lg transition-all duration-200 backdrop-blur-sm">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                
                <!-- Search and Date Filter -->
                <div class="flex items-center gap-3">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="search" class="w-4 h-4 text-white/60"></i>
                        </div>
                        <input type="search" 
                            id="codingSearchInput"
                            placeholder="Search by unit number, plate, or coding status..."
                            class="w-full pl-10 pr-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent transition-all duration-200 text-sm"
                            oninput="filterCodingUnits()"
                         autocomplete="new-password" spellcheck="false" autocorrect="off" autocapitalize="off" readonly onfocus="this.removeAttribute('readonly');">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button onclick="clearCodingSearch()" class="text-white/60 hover:text-white transition-colors">
                                <i data-lucide="x-circle" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Coding Period Filters -->
                    <div class="flex bg-white/10 backdrop-blur-sm border border-white/30 rounded-lg p-1">
                        <button 
                            id="btn-today-coding" 
                            onclick="setCodingPeriod('today')"
                            class="px-3 py-1.5 text-xs font-medium rounded-md transition-all duration-200 bg-white text-purple-700"
                        >
                            Today
                        </button>
                        <button 
                            id="btn-tomorrow-coding" 
                            onclick="setCodingPeriod('tomorrow')"
                            class="px-3 py-1.5 text-xs font-medium rounded-md transition-all duration-200 text-white/70 hover:text-white hover:bg-white/10"
                        >
                            Tomorrow
                        </button>
                        <button 
                            id="btn-past-coding" 
                            onclick="setCodingPeriod('past')"
                            class="px-3 py-1.5 text-xs font-medium rounded-md transition-all duration-200 text-white/70 hover:text-white hover:bg-white/10"
                        >
                            Past
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex-1 overflow-hidden flex flex-col min-h-0">
            <!-- Summary Stats -->
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-4 border-b border-purple-200 flex-shrink-0">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white rounded-lg p-3 shadow-md shadow-slate-200/40 border border-blue-100 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 bg-blue-100 rounded">
                                <i data-lucide="calendar" class="w-4 h-4 text-blue-600"></i>
                            </div>
                            <div>
                                <div class="text-lg font-bold text-blue-600" id="todayCodingCount">0</div>
                                <div class="text-xs text-gray-600 uppercase tracking-wide font-medium">Today's Coding</div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-3 shadow-md shadow-slate-200/40 border border-green-100 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 bg-green-100 rounded">
                                <i data-lucide="check-circle" class="w-4 h-4 text-green-600"></i>
                            </div>
                            <div>
                                <div class="text-lg font-bold text-green-600" id="tomorrowCodingCount">0</div>
                                <div class="text-xs text-gray-600 uppercase tracking-wide font-medium">Tomorrow's Coding</div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-3 shadow-md shadow-slate-200/40 border border-orange-100 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 bg-orange-100 rounded">
                                <i data-lucide="alert-circle" class="w-4 h-4 text-orange-600"></i>
                            </div>
                            <div>
                                <div class="text-lg font-bold text-orange-600" id="pastCodingCount">0</div>
                                <div class="text-xs text-gray-600 uppercase tracking-wide font-medium">Past Coding</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coding Units Grid -->
            <div class="flex-1 overflow-y-auto p-4 bg-gray-50 min-h-0">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 pb-4" id="codingGrid">
                    <!-- Loading State -->
                    <div class="col-span-full text-center py-16">
                        <div class="inline-flex flex-col items-center">
                            <div class="animate-spin rounded-full h-12 w-12 border-4 border-purple-600 border-t-transparent mb-4"></div>
                            <span class="text-lg text-gray-600 font-semibold mb-2">Loading coding data...</span>
                            <p class="text-sm text-slate-400">Please wait while we fetch coding details</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Net Income Modal -->
<div id="netIncomeModal" class="fixed inset-0 bg-slate-950/85 backdrop-blur-md hidden z-50 flex items-center justify-center p-3 sm:p-5 transition-all duration-300">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-6xl h-[92vh] flex flex-col overflow-hidden border border-slate-700/30">
        <!-- Modal Header (21st.dev Royal Midnight Indigo & Cyber Amethyst Theme) -->
        <div class="relative bg-[#070a1e] border-b border-indigo-950/80 p-4 sm:p-5 flex-shrink-0 overflow-hidden">
            <!-- Micro-grid Dot Pattern Background -->
            <div class="absolute inset-0 opacity-[0.09] pointer-events-none" style="background-image: radial-gradient(rgba(129,140,248,0.9) 1px, transparent 1px); background-size: 16px 16px;"></div>
            
            <!-- Ambient Royal Blue, Violet & Fuchsia Radial Glows -->
            <div class="absolute -top-20 left-1/2 -translate-x-1/2 w-96 h-40 bg-indigo-500/25 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute top-1/2 -right-16 w-60 h-60 bg-violet-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-0 left-10 w-48 h-20 bg-blue-500/15 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col gap-3">
                <!-- Top Row: Title, Pill & Close Action (100% Mathematically Centered Title) -->
                <div class="relative flex items-center justify-between min-h-[38px]">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/15 border border-indigo-400/30 text-indigo-300 text-[10px] font-extrabold uppercase tracking-widest backdrop-blur-md shadow-xs relative z-10">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                        Net Profit Audit
                    </div>

                    <!-- 100% Mathematically Centered Title Overlay -->
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none px-28">
                        <h1 class="animated-shiny-netincome-text text-base sm:text-xl md:text-2xl lg:text-3xl font-black uppercase tracking-tight text-center truncate">
                            Net Income Details
                        </h1>
                    </div>

                    <button onclick="hideNetIncomeModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition-all duration-200 backdrop-blur-sm border border-white/10 relative z-10" title="Close Modal">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <!-- Bottom Row: Period Segmented Buttons & Print Report -->
                <div class="flex flex-wrap items-center justify-between gap-2.5">
                    <div class="flex items-center p-1 bg-slate-900/90 rounded-xl border border-indigo-900/60 shadow-inner">
                        <button id="btn-today-income" onclick="setIncomePeriod('today')" class="px-3 sm:px-3.5 py-1.5 text-xs font-black rounded-lg transition-all duration-200 bg-gradient-to-r from-indigo-600 to-violet-600 text-white shadow-sm cursor-pointer">Today</button>
                        <button id="btn-week-income" onclick="setIncomePeriod('week')" class="px-3 sm:px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all duration-200 text-slate-300 hover:text-white hover:bg-white/10 cursor-pointer">Weekly</button>
                        <button id="btn-month-income" onclick="setIncomePeriod('month')" class="px-3 sm:px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all duration-200 text-slate-300 hover:text-white hover:bg-white/10 cursor-pointer">Monthly</button>
                        <button id="btn-year-income" onclick="setIncomePeriod('year')" class="px-3 sm:px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all duration-200 text-slate-300 hover:text-white hover:bg-white/10 cursor-pointer">Yearly</button>
                    </div>

                    <button onclick="printReport()" class="px-3.5 sm:px-4 py-1.5 rounded-xl bg-indigo-500/15 hover:bg-indigo-500/30 text-indigo-200 hover:text-white font-extrabold text-xs flex items-center gap-2 border border-indigo-400/30 backdrop-blur-md shadow-sm transition-all duration-200 cursor-pointer">
                        <i data-lucide="printer" class="w-3.5 h-3.5 text-indigo-400"></i>
                        PRINT REPORT
                    </button>
                </div>
            </div>
        </div>
        
        <div class="flex-1 overflow-hidden flex flex-col min-h-0 bg-slate-100/60 relative">
            <!-- ─── 3D SVG KPI Executive Metrics Strip (21st.dev Style) ─── -->
            <div class="bg-white/90 backdrop-blur-md p-3 sm:p-3.5 border-b border-slate-200/80 flex-shrink-0 shadow-xs relative z-20">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 sm:gap-3.5 max-w-7xl mx-auto">
                    
                    <!-- 1. Total Revenue Card -->
                    <div class="relative overflow-hidden rounded-2xl shadow-xs border border-emerald-200/80 bg-gradient-to-br from-white via-emerald-50/50 to-emerald-100/40 p-3">
                        <div class="absolute left-0 inset-y-0 h-6 w-1 rounded-r-full bg-emerald-500 my-auto"></div>
                        <div class="relative z-10 pl-2 pr-12">
                            <div class="text-emerald-700 text-[8px] sm:text-[9px] font-extrabold uppercase tracking-widest leading-none mb-1">Total Revenue</div>
                            <div class="text-xl sm:text-2xl font-black text-emerald-600 leading-none mb-0.5 tracking-tight" id="reportTotalIncome">₱0.00</div>
                            <div class="text-[9px] font-bold text-emerald-600/75 uppercase tracking-tight">Gross Inflow</div>
                        </div>
                        <img src="{{ asset('image/kpi/revenue_3d.svg') }}" alt="Total Revenue" class="absolute -right-2 -bottom-2 w-14 h-14 sm:w-16 sm:h-16 object-contain pointer-events-none opacity-90 drop-shadow-sm">
                    </div>

                    <!-- 2. Total Expenses Card -->
                    <div class="relative overflow-hidden rounded-2xl shadow-xs border border-rose-200/80 bg-gradient-to-br from-white via-rose-50/50 to-rose-100/40 p-3">
                        <div class="absolute left-0 inset-y-0 h-6 w-1 rounded-r-full bg-rose-500 my-auto"></div>
                        <div class="relative z-10 pl-2 pr-12">
                            <div class="text-rose-700 text-[8px] sm:text-[9px] font-extrabold uppercase tracking-widest leading-none mb-1">Total Expenses</div>
                            <div class="text-xl sm:text-2xl font-black text-rose-600 leading-none mb-0.5 tracking-tight" id="reportTotalExpenses">₱0.00</div>
                            <div class="text-[9px] font-bold text-rose-600/75 uppercase tracking-tight">Operating Outflow</div>
                        </div>
                        <img src="{{ asset('image/kpi/expenses_3d.svg') }}" alt="Total Expenses" class="absolute -right-2 -bottom-2 w-14 h-14 sm:w-16 sm:h-16 object-contain pointer-events-none opacity-90 drop-shadow-sm">
                    </div>

                    <!-- 3. Net Income & Margin Card -->
                    <div class="relative overflow-hidden rounded-2xl shadow-xs border border-indigo-200/80 bg-gradient-to-br from-white via-indigo-50/50 to-violet-100/40 p-3">
                        <div class="absolute left-0 inset-y-0 h-6 w-1 rounded-r-full bg-indigo-500 my-auto"></div>
                        <div class="relative z-10 pl-2 pr-12">
                            <div class="flex items-center gap-1.5 mb-1">
                                <span class="text-indigo-700 text-[8px] sm:text-[9px] font-extrabold uppercase tracking-widest leading-none">Net Income</span>
                                <span id="reportProfitMargin" class="text-[8px] px-1.5 py-0.5 rounded-full font-bold bg-indigo-100 text-indigo-800 border border-indigo-200">0.0% Margin</span>
                            </div>
                            <div class="text-xl sm:text-2xl font-black text-slate-900 leading-none mb-0.5 tracking-tight" id="reportNetIncome">₱0.00</div>
                            <div class="text-[9px] font-bold text-indigo-600/75 uppercase tracking-tight">Net Profit Takeaway</div>
                        </div>
                        <img src="{{ asset('image/kpi/crown_3d.svg') }}" alt="Net Income" class="absolute -right-2 -bottom-2 w-14 h-14 sm:w-16 sm:h-16 object-contain pointer-events-none opacity-90 drop-shadow-sm">
                    </div>

                </div>
            </div>

            <!-- 💎 Fixed Bold Savage Cyber Diamond Profit Crystal & Analytics Matrix Watermark Background (High Visibility) -->
            <div class="absolute inset-0 top-16 flex items-center justify-center pointer-events-none select-none z-0 overflow-hidden px-4" aria-hidden="true" style="transform: translateZ(0);">
                <svg viewBox="0 0 540 240" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-[96%] max-w-5xl h-auto opacity-[0.38] transform select-none pointer-events-none">
                    <defs>
                        <linearGradient id="crystalDiamondGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#3b82f6" />
                            <stop offset="30%" stop-color="#6366f1" />
                            <stop offset="65%" stop-color="#a855f7" />
                            <stop offset="100%" stop-color="#eab308" />
                        </linearGradient>
                        <linearGradient id="waveProfitGrad" x1="0%" y1="100%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#4f46e5" />
                            <stop offset="50%" stop-color="#9333ea" />
                            <stop offset="100%" stop-color="#06b6d4" />
                        </linearGradient>
                        <radialGradient id="crystalCoreGlow" cx="50%" cy="50%" r="50%">
                            <stop offset="0%" stop-color="#a855f7" stop-opacity="0.45" />
                            <stop offset="100%" stop-color="#3b82f6" stop-opacity="0" />
                        </radialGradient>
                    </defs>

                    <!-- Orbiting Gyro Financial Matrix Rings (Bold Lines) -->
                    <circle cx="270" cy="115" r="105" stroke="url(#waveProfitGrad)" stroke-width="4.5" stroke-dasharray="16 10" />
                    <ellipse cx="270" cy="115" rx="160" ry="60" stroke="url(#crystalDiamondGrad)" stroke-width="4" stroke-dasharray="10 8" transform="rotate(-15 270 115)" />
                    <ellipse cx="270" cy="115" rx="160" ry="60" stroke="url(#waveProfitGrad)" stroke-width="4" stroke-dasharray="10 8" transform="rotate(15 270 115)" />

                    <!-- Multi-tier Holographic Sinusoidal Profit Waves (Bold Vectors) -->
                    <path d="M15 190 Q110 120 180 160 T360 70 T525 20" stroke="url(#crystalDiamondGrad)" stroke-width="6.5" stroke-linecap="round" fill="none" />
                    <path d="M15 210 Q130 160 210 185 T390 105 T525 50" stroke="url(#waveProfitGrad)" stroke-width="4.5" stroke-dasharray="10 6" stroke-linecap="round" fill="none" />
                    <path d="M30 225 H510" stroke="url(#crystalDiamondGrad)" stroke-width="4" stroke-dasharray="16 8" stroke-linecap="round" />

                    <!-- Massive 3D Savage Cyber Diamond Crystal Ledger Core -->
                    <circle cx="270" cy="115" r="75" fill="url(#crystalCoreGlow)" />
                    <!-- Outer Facets with Vibrant Infill -->
                    <polygon points="270,25 365,90 330,195 210,195 175,90" stroke="url(#crystalDiamondGrad)" stroke-width="6.5" fill="#6366f1" fill-opacity="0.1" stroke-linejoin="round" />
                    <!-- Crown & Pavilion Facet Intersections -->
                    <line x1="175" y1="90" x2="365" y2="90" stroke="url(#crystalDiamondGrad)" stroke-width="5" />
                    <line x1="270" y1="25" x2="238" y2="90" stroke="url(#crystalDiamondGrad)" stroke-width="4.5" />
                    <line x1="270" y1="25" x2="302" y2="90" stroke="url(#crystalDiamondGrad)" stroke-width="4.5" />
                    <line x1="238" y1="90" x2="270" y2="195" stroke="url(#crystalDiamondGrad)" stroke-width="5.5" />
                    <line x1="302" y1="90" x2="270" y2="195" stroke="url(#crystalDiamondGrad)" stroke-width="5.5" />
                    <line x1="175" y1="90" x2="270" y2="195" stroke="url(#waveProfitGrad)" stroke-width="4" />
                    <line x1="365" y1="90" x2="270" y2="195" stroke="url(#waveProfitGrad)" stroke-width="4" />

                    <!-- Central Floating Glowing Hexagon Node -->
                    <polygon points="270,80 300,98 300,132 270,150 240,132 240,98" stroke="url(#crystalDiamondGrad)" stroke-width="4" fill="#ffffff" fill-opacity="0.4" stroke-linejoin="round" />
                    <circle cx="270" cy="115" r="10" fill="#eab308" />

                    <!-- Precision Analytics Crosshairs & Vectors -->
                    <line x1="270" y1="6" x2="270" y2="24" stroke="url(#crystalDiamondGrad)" stroke-width="4" stroke-linecap="round" />
                    <line x1="270" y1="196" x2="270" y2="214" stroke="url(#crystalDiamondGrad)" stroke-width="4" stroke-linecap="round" />
                    <line x1="155" y1="115" x2="173" y2="115" stroke="url(#crystalDiamondGrad)" stroke-width="4" stroke-linecap="round" />
                    <line x1="367" y1="115" x2="385" y2="115" stroke="url(#crystalDiamondGrad)" stroke-width="4" stroke-linecap="round" />

                    <!-- Dynamic Floating Cyber Currency Crystals & Data Sparks -->
                    <polygon points="525,20 535,8 545,20 535,32" fill="#eab308" />
                    <polygon points="460,65 467,56 474,65 467,74" fill="#a855f7" />
                    <polygon points="75,150 82,141 89,150 82,159" fill="#3b82f6" />
                    <polygon points="120,70 126,62 132,70 126,78" fill="#06b6d4" />
                </svg>
            </div>

            <!-- Detailed Report Document (Scrollable Area) -->
            <div class="flex-1 overflow-y-auto p-4 sm:p-5 min-h-0 relative z-10 print-section" style="transform: translateZ(0);">
                <div class="max-w-5xl mx-auto relative" id="incomeReport">
                    <!-- Report Header (Print Only) -->
                    <div class="text-center mb-10 print-only">
                        <div class="flex flex-col items-center mb-4">
                            <img src="{{ asset('image/logo.png') }}" alt="Euro Taxi Logo" class="h-16 w-auto mb-2">
                        </div>
                        <h4 class="text-4xl font-bold uppercase tracking-[0.4em] text-slate-800 mb-2">Financial Report</h4>
                        <div class="text-base text-gray-600 uppercase font-bold tracking-widest" id="reportPeriodLabelPrint">Period: TODAY</div>
                        <div class="text-[12px] text-slate-400 mt-3 font-bold tracking-[0.2em]">EURO TAXI MANAGEMENT SYSTEM • OFFICIAL RECORD</div>
                        <div class="border-t-2 border-gray-100 mt-8 pt-2 h-0 border-dashed"></div>
                        
                        <!-- Print-Only Summary Box -->
                        <div class="mt-6 border border-gray-200 p-4 text-left mx-auto max-w-sm" style="font-family: monospace;">
                            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #eee; padding-bottom: 4px; margin-bottom: 4px; font-weight: bold;">
                                <span style="text-transform: uppercase; font-size: 10px; letter-spacing: 0.1em; color: #666;">Total Revenue</span> 
                                <span id="reportTotalIncomePrint">₱0.00</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #eee; padding-bottom: 4px; margin-bottom: 4px; font-weight: bold;">
                                <span style="text-transform: uppercase; font-size: 10px; letter-spacing: 0.1em; color: #666;">Total Expenses</span> 
                                <span id="reportTotalExpensesPrint" style="color: #dc2626;">₱0.00</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-weight: 900; font-size: 12px; margin-top: 8px;">
                                <span style="text-transform: uppercase; letter-spacing: 0.1em;">Net Income</span> 
                                <span id="reportNetIncomePrint">₱0.00</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Revenue Section (Ultra-Clear Translucent Glass Card) -->
                    <div class="mb-5 bg-[#f0f4ff]/30 hover:bg-[#f0f4ff]/50 backdrop-blur-[2px] rounded-2xl border border-indigo-300/60 shadow-sm overflow-hidden transition-all duration-200">
                        <div class="flex justify-between items-center bg-indigo-500/10 px-4 sm:px-6 py-2.5 border-b border-indigo-200/50">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-lg bg-indigo-600 text-white flex items-center justify-center flex-shrink-0 shadow-xs">
                                    <i data-lucide="trending-up" class="w-3.5 h-3.5"></i>
                                </div>
                                <span class="text-xs font-black text-indigo-950 uppercase tracking-wider">Revenue Breakdown</span>
                            </div>
                        </div>
                        <div id="revenueDetailList" class="min-h-[80px] flex flex-col justify-center bg-transparent">
                            <!-- Dynamically populated -->
                        </div>
                    </div>
                    
                    <!-- Operating Expenses Section (Ultra-Clear Translucent Glass Card) -->
                    <div class="mb-5 bg-[#fff1f2]/30 hover:bg-[#fff1f2]/50 backdrop-blur-[2px] rounded-2xl border border-rose-300/60 shadow-sm overflow-hidden transition-all duration-200">
                        <div class="flex justify-between items-center bg-rose-500/10 px-4 sm:px-6 py-2.5 border-b border-rose-200/50">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-lg bg-rose-600 text-white flex items-center justify-center flex-shrink-0 shadow-xs">
                                    <i data-lucide="trending-down" class="w-3.5 h-3.5"></i>
                                </div>
                                <span class="text-xs font-black text-rose-950 uppercase tracking-wider">Operating Expenses Breakdown</span>
                            </div>
                        </div>
                        <div class="divide-y divide-rose-200/40 bg-transparent">
                            <!-- Maintenance Breakdown -->
                            <div>
                                <div class="bg-rose-500/5 px-4 sm:px-6 py-1.5 border-b border-rose-200/40 flex justify-between items-center text-[10px] font-extrabold text-slate-600 uppercase tracking-wider">
                                    <span class="flex items-center gap-1.5"><i data-lucide="wrench" class="w-3 h-3 text-orange-600"></i> Maintenance & Repairs Itemized</span>
                                    <span id="reportMaintenanceTotal" class="text-orange-600 font-black">Total: ₱0.00</span>
                                </div>
                                <div id="maintenanceDetailList" class="min-h-[60px] flex flex-col justify-center bg-transparent">
                                    <!-- Dynamically populated -->
                                </div>
                            </div>

                            <!-- Office Breakdown -->
                            <div>
                                <div class="bg-rose-500/5 px-4 sm:px-6 py-1.5 border-b border-rose-200/40 flex justify-between items-center text-[10px] font-extrabold text-slate-600 uppercase tracking-wider">
                                    <span class="flex items-center gap-1.5"><i data-lucide="building" class="w-3 h-3 text-red-600"></i> General Office Expenses Itemized</span>
                                    <span id="reportGeneralExpensesTotal" class="text-rose-600 font-black">Total: ₱0.00</span>
                                </div>
                                <div id="officeExpensesDetailList" class="min-h-[60px] flex flex-col justify-center bg-transparent">
                                    <!-- Dynamically populated -->
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Report Footer (Print Only) -->
                    <div class="text-center mt-8 pt-6 border-t border-gray-100 print-only">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em] mb-1">Authenticated Financial Statement</p>
                        <p class="text-[9px] text-gray-300 font-medium tracking-widest">TIMESTAMP: <span id="reportTimestamp"></span></p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Total Expenses Details Modal (NEW) -->
<div id="expensesModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-6xl h-[95vh] flex flex-col overflow-hidden border border-white/20">
        <!-- Modal Header -->
        <div class="relative pt-6 pb-4 px-5 sm:pt-7 sm:pb-5 sm:px-6 border-b bg-gradient-to-r from-red-600 to-rose-700 flex-shrink-0 text-white overflow-hidden">
            <!-- Large Animated Shiny Background Text (Shifted up for clear full view) -->
            <div class="absolute inset-x-0 top-1 sm:top-2 flex items-center justify-center pointer-events-none select-none z-0 overflow-hidden px-4" aria-hidden="true">
                <h1 class="animated-shiny-units-text text-xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-black uppercase tracking-wider whitespace-nowrap opacity-90">
                    Total Expenses Today
                </h1>
            </div>

            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-end gap-4">
                        <div class="p-2.5 bg-white/20 backdrop-blur-md rounded-xl border border-white/30 shadow-inner">
                            <i data-lucide="trending-down" class="w-7 h-7"></i>
                        </div>
                        <div class="pb-1">
                            <p class="text-red-100 text-[11px] font-bold uppercase tracking-widest opacity-80">Detailed expense records and computation</p>
                        </div>
                    </div>
                    <button onclick="hideExpensesModal()" class="p-2 hover:bg-white/10 text-white rounded-full transition-colors">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
                
                <!-- Period Filters & Print Row (Expenses) -->
                <div class="mt-4 flex flex-wrap items-center justify-between gap-3 bg-black/15 rounded-xl p-2 backdrop-blur-sm border border-white/10">
                    <div class="flex gap-1 p-0.5 bg-black/20 rounded-lg shadow-inner">
                        <button id="btn-today-expenses" onclick="setExpensesPeriod('today')" class="px-3 py-1.5 text-xs font-bold rounded-md transition-all duration-200">Today</button>
                        <button id="btn-week-expenses" onclick="setExpensesPeriod('week')" class="px-3 py-1.5 text-xs font-bold rounded-md transition-all duration-200">Weekly</button>
                        <button id="btn-month-expenses" onclick="setExpensesPeriod('month')" class="px-3 py-1.5 text-xs font-bold rounded-md transition-all duration-200">Monthly</button>
                        <button id="btn-year-expenses" onclick="setExpensesPeriod('year')" class="px-3 py-1.5 text-xs font-bold rounded-md transition-all duration-200">Yearly</button>
                    </div>

                    <button onclick="printExpensesNewTab()" class="flex items-center gap-2 px-4 py-1.5 bg-white text-rose-700 hover:bg-rose-50 rounded-lg font-bold text-xs uppercase tracking-widest transition-all shadow-md border border-white hover:scale-105">
                        <i data-lucide="printer" class="w-4 h-4 text-rose-700"></i>
                        Print Expenses
                    </button>
                </div>
            </div>
        </div>
        
        <div class="flex-1 overflow-hidden flex flex-col min-h-0">
            <!-- Detailed Report Document (Expenses Focused) -->
            <div class="bg-gray-50 p-4 border-b border-gray-200 flex-shrink-0 print-section overflow-y-auto max-h-[85vh]">
                <div class="max-w-5xl mx-auto bg-white border border-gray-200 rounded-xl p-6 shadow-md shadow-slate-200/40 relative" id="expensesReport">
                    <!-- Report Header (Print Only) -->
                    <div class="text-center mb-10 print-only">
                        <div class="flex flex-col items-center mb-4">
                            <img src="{{ asset('image/logo.png') }}" alt="Euro Taxi Logo" class="h-16 w-auto mb-2" style="height:64px; width:auto;">
                        </div>
                        <h4 class="text-4xl font-bold uppercase tracking-[0.4em] text-slate-800 mb-2">Expense Statement</h4>
                        <div class="text-base text-gray-600 uppercase font-bold tracking-widest" id="expensesPeriodLabelPrint">Period: TODAY</div>
                        <div class="text-[12px] text-slate-400 mt-3 font-bold tracking-[0.2em]">EURO TAXI MANAGEMENT SYSTEM • OFFICIAL EXPENSE RECORD</div>
                        <div class="border-t-2 border-gray-100 mt-8 pt-2 h-0 border-dashed"></div>
                        
                        <!-- Print-Only Summary Box -->
                        <div class="mt-6 border border-gray-200 p-4 text-left mx-auto max-w-sm" style="font-family: monospace;">
                            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #eee; padding-bottom: 4px; margin-bottom: 4px; font-weight: bold;">
                                <span style="text-transform: uppercase; font-size: 10px; letter-spacing: 0.1em; color: #666;">Maintenance Total</span> 
                                <span id="expensesMaintenanceTotalPrint">₱0.00</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #eee; padding-bottom: 4px; margin-bottom: 4px; font-weight: bold;">
                                <span style="text-transform: uppercase; font-size: 10px; letter-spacing: 0.1em; color: #666;">Office Expenses Total</span> 
                                <span id="expensesOfficeTotalPrint" style="color: #dc2626;">₱0.00</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-weight: 900; font-size: 12px; margin-top: 8px;">
                                <span style="text-transform: uppercase; letter-spacing: 0.1em;">Total Expenses</span> 
                                <span id="expensesTotalValuePrint">₱0.00</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Total Expenses Summary Section (Screen Only) -->
                    <div class="mb-8 p-6 bg-gradient-to-br from-red-900 to-red-800 rounded-xl shadow-lg border border-red-700 text-white relative overflow-hidden no-print">
                        <!-- Decorative element -->
                        <div class="absolute top-0 right-0 opacity-10 pointer-events-none">
                            <i data-lucide="receipt" class="w-48 h-48 -mt-8 -mr-8"></i>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
                            <!-- Maintenance Expenses -->
                            <div class="flex flex-col border-b md:border-b-0 md:border-r border-red-700 pb-4 md:pb-0 md:pr-6">
                                <span class="text-xs font-bold text-red-200 uppercase tracking-widest mb-1">Maintenance Total</span>
                                <span class="text-2xl font-bold text-white" id="expensesMaintenanceTotal">₱0.00</span>
                            </div>
                            
                            <!-- Office Expenses -->
                            <div class="flex flex-col md:pl-2">
                                <span class="text-xs font-bold text-red-200 uppercase tracking-widest mb-1 flex items-center">
                                    Office Expenses Total 
                                </span>
                                <span class="text-2xl font-bold text-white" id="expensesOfficeTotal">₱0.00</span>
                            </div>
                        </div>

                        <!-- Grand Total -->
                        <div class="mt-6 pt-4 border-t border-red-700/50">
                            <span class="text-xs font-bold text-red-200 uppercase tracking-widest mb-1 block">Total Expenses</span>
                            <span class="text-5xl font-bold text-white drop-shadow-md" id="expensesTotalValue">₱0.00</span>
                        </div>
                    </div>
                    
                    <!-- Operating Expenses Section -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center bg-red-50 text-red-900 px-6 py-3 rounded-t-lg border-x border-t border-red-100">
                            <span class="text-[11px] uppercase font-bold tracking-[0.1em]">Detailed Expenses Breakdown</span>
                        </div>
                        <div class="border-x border-b border-gray-200 rounded-b-lg p-0">
                            <!-- Maintenance Breakdown -->
                            <div class="border-b border-gray-200">
                                <div class="bg-gray-50 px-6 py-2 border-b border-gray-200 flex justify-between text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                                    <span>Maintenance & Repairs Itemized</span>
                                </div>
                                <div id="expensesMaintenanceList" class="bg-white min-h-[60px] flex flex-col justify-center"></div>
                            </div>

                            <!-- Office Breakdown -->
                            <div>
                                <div class="bg-gray-50 px-6 py-2 border-b border-gray-200 flex justify-between text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                                    <span>General Office Expenses Itemized</span>
                                </div>
                                <div id="expensesOfficeList" class="bg-white min-h-[60px] flex flex-col justify-center rounded-b-lg"></div>
                            </div>
                        </div>
                    </div>
                    
                    

                    <!-- Report Footer (Print Only) -->
                    <div class="text-center mt-8 pt-6 border-t border-gray-100 print-only">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em] mb-1">Authenticated Expense Summary</p>
                        <p class="text-[9px] text-gray-300 font-medium tracking-widest">TIMESTAMP: <span id="expensesTimestamp"></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Daily Boundary Collection Modal -->
<div id="dailyBoundaryModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md hidden z-50 flex items-center justify-center p-3 sm:p-5 transition-all duration-300">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-6xl h-[92vh] flex flex-col overflow-hidden border border-slate-700/30">
        <!-- Modal Header (21st.dev Cyber Emerald Matrix Dark Glass Theme) -->
        <div class="relative bg-[#061a12] border-b border-emerald-950/60 p-4 sm:p-5 flex-shrink-0 overflow-hidden">
            <!-- Micro-grid Dot Pattern Background -->
            <div class="absolute inset-0 opacity-[0.08] pointer-events-none" style="background-image: radial-gradient(rgba(16,185,129,0.9) 1px, transparent 1px); background-size: 16px 16px;"></div>
            
            <!-- Ambient Emerald & Mint Radial Glows -->
            <div class="absolute -top-20 left-1/2 -translate-x-1/2 w-96 h-40 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute top-1/2 -right-16 w-60 h-60 bg-teal-500/15 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col gap-3">
                <!-- Top Row: Title, Pill & Close Action (100% Mathematically Centered Title) -->
                <div class="relative flex items-center justify-between min-h-[38px]">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/25 text-emerald-400 text-[10px] font-extrabold uppercase tracking-widest backdrop-blur-md shadow-xs relative z-10">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Revenue Stream Live
                    </div>

                    <!-- 100% Mathematically Centered Title Overlay -->
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none px-28">
                        <h1 class="animated-shiny-boundary-text text-base sm:text-xl md:text-2xl lg:text-3xl font-black uppercase tracking-tight text-center truncate">
                            Daily Boundary Collections
                        </h1>
                    </div>

                    <button onclick="hideDailyBoundaryModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition-all duration-200 backdrop-blur-sm border border-white/10 relative z-10" title="Close Modal">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <!-- Bottom Row: Search Bar & Date Filter -->
                <div class="flex items-center gap-2.5 sm:gap-3.5">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-emerald-400/70">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </div>
                        <input type="search" 
                            id="boundarySearchInput"
                            placeholder="Search by unit number, driver, or amount..."
                            class="w-full pl-10 pr-9 py-2 bg-slate-900/80 border border-slate-700/80 rounded-xl text-white placeholder-slate-400 text-xs sm:text-sm focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400/50 transition-all shadow-inner"
                            onkeyup="filterBoundaryCollections()"
                            autocomplete="new-password" spellcheck="false" autocorrect="off" autocapitalize="off" readonly onfocus="this.removeAttribute('readonly');">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button onclick="clearBoundarySearch()" class="text-slate-400 hover:text-white transition-colors">
                                <i data-lucide="x-circle" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="relative flex items-center flex-shrink-0">
                        <input 
                            type="date" 
                            id="boundaryDateFilter"
                            class="px-3.5 py-2 bg-slate-900/80 border border-slate-700/80 rounded-xl text-emerald-300 text-xs sm:text-sm font-semibold focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400/50 transition-all shadow-inner cursor-pointer"
                            onchange="filterBoundaryCollections()"
                        >
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex-1 overflow-hidden flex flex-col min-h-0 bg-slate-100/60 relative">
            <!-- ─── Compact Summary Metrics Strip with 3D SVG Assets (21st.dev Style) ─── -->
            <div class="bg-white/90 backdrop-blur-md p-3 sm:p-3.5 border-b border-slate-200/80 flex-shrink-0 shadow-xs relative z-20">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-3.5 max-w-7xl mx-auto">
                    
                    <!-- 1. Total Today Card -->
                    <div class="relative overflow-hidden rounded-2xl shadow-xs border border-emerald-200/80 bg-gradient-to-br from-white via-emerald-50/50 to-emerald-100/40 p-2.5 sm:p-3">
                        <div class="absolute left-0 inset-y-0 h-6 w-1 rounded-r-full bg-emerald-500 my-auto"></div>
                        <div class="relative z-10 pl-2 pr-12">
                            <div class="text-emerald-700 text-[8px] sm:text-[9px] font-extrabold uppercase tracking-widest leading-none mb-1">Total Today</div>
                            <div class="text-lg sm:text-2xl font-black text-emerald-600 leading-none mb-0.5 tracking-tight" id="totalBoundaryCount">0</div>
                            <div class="text-[9px] font-bold text-emerald-600/75 uppercase tracking-tight">Collections Count</div>
                        </div>
                        <img src="{{ asset('image/kpi/revenue_3d.svg') }}" alt="Total Today" class="absolute -right-2 -bottom-2 w-14 h-14 sm:w-16 sm:h-16 object-contain pointer-events-none opacity-90 drop-shadow-sm">
                    </div>

                    <!-- 2. Yesterday Total Card -->
                    <div class="relative overflow-hidden rounded-2xl shadow-xs border border-blue-200/80 bg-gradient-to-br from-white via-blue-50/50 to-blue-100/40 p-2.5 sm:p-3">
                        <div class="absolute left-0 inset-y-0 h-6 w-1 rounded-r-full bg-blue-500 my-auto"></div>
                        <div class="relative z-10 pl-2 pr-12">
                            <div class="text-blue-600 text-[8px] sm:text-[9px] font-extrabold uppercase tracking-widest leading-none mb-1 truncate">Yesterday Total</div>
                            <div class="text-lg sm:text-2xl font-black text-blue-600 leading-none mb-0.5 tracking-tight" id="uniqueUnitsCount">₱0</div>
                            <div class="text-[9px] font-bold text-blue-600/75 uppercase tracking-tight">Previous Day</div>
                        </div>
                        <img src="{{ asset('image/kpi/history_3d.svg') }}" alt="Yesterday Total" class="absolute -right-2 -bottom-2 w-14 h-14 sm:w-16 sm:h-16 object-contain pointer-events-none opacity-90 drop-shadow-sm">
                    </div>

                    <!-- 3. Monthly Total Card -->
                    <div class="relative overflow-hidden rounded-2xl shadow-xs border border-purple-200/80 bg-gradient-to-br from-white via-purple-50/50 to-purple-100/40 p-2.5 sm:p-3">
                        <div class="absolute left-0 inset-y-0 h-6 w-1 rounded-r-full bg-purple-500 my-auto"></div>
                        <div class="relative z-10 pl-2 pr-12">
                            <div class="text-purple-700 text-[8px] sm:text-[9px] font-extrabold uppercase tracking-widest leading-none mb-1 truncate">Monthly Total</div>
                            <div class="text-lg sm:text-2xl font-black text-purple-600 leading-none mb-0.5 tracking-tight" id="uniqueDriversCount">₱0</div>
                            <div class="text-[9px] font-bold text-purple-600/75 uppercase tracking-tight">This Month Revenue</div>
                        </div>
                        <img src="{{ asset('image/kpi/profit_3d.svg') }}" alt="Monthly Total" class="absolute -right-2 -bottom-2 w-14 h-14 sm:w-16 sm:h-16 object-contain pointer-events-none opacity-90 drop-shadow-sm">
                    </div>

                    <!-- 4. Yearly Total Amount Card -->
                    <div class="relative overflow-hidden rounded-2xl shadow-xs border border-amber-200/80 bg-gradient-to-br from-white via-amber-50/50 to-amber-100/40 p-2.5 sm:p-3">
                        <div class="absolute left-0 inset-y-0 h-6 w-1 rounded-r-full bg-amber-500 my-auto"></div>
                        <div class="relative z-10 pl-2 pr-12">
                            <div class="text-amber-700 text-[8px] sm:text-[9px] font-extrabold uppercase tracking-widest leading-none mb-1 truncate">Yearly Total Amount</div>
                            <div class="text-lg sm:text-2xl font-black text-amber-600 leading-none mb-0.5 tracking-tight" id="totalBoundaryAmount">₱0</div>
                            <div class="text-[9px] font-bold text-amber-600/75 uppercase tracking-tight">Annual Boundary</div>
                        </div>
                        <img src="{{ asset('image/kpi/crown_3d.svg') }}" alt="Yearly Total" class="absolute -right-2 -bottom-2 w-14 h-14 sm:w-16 sm:h-16 object-contain pointer-events-none opacity-90 drop-shadow-sm">
                    </div>

                </div>
            </div>

            <!-- 💎 Fixed Savage Cyber Financial Vault & Growth Matrix Watermark Background (Stays Stationary on Scroll) -->
            <div class="absolute inset-x-0 bottom-0 top-16 flex items-center justify-center pointer-events-none select-none z-0 overflow-hidden px-4" aria-hidden="true" style="transform: translateZ(0);">
                <svg viewBox="0 0 520 200" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-[94%] max-w-4xl h-auto opacity-[0.26] transform select-none pointer-events-none">
                    <defs>
                        <linearGradient id="boundaryVaultGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#10b981" />
                            <stop offset="50%" stop-color="#059669" />
                            <stop offset="100%" stop-color="#eab308" />
                        </linearGradient>
                        <linearGradient id="boundaryPulseGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#34d399" />
                            <stop offset="100%" stop-color="#10b981" />
                        </linearGradient>
                    </defs>

                    <!-- Background High-Tech Financial Grid & Data Matrix Lines -->
                    <path d="M20 170 H500" stroke="url(#boundaryVaultGrad)" stroke-width="2.5" stroke-dasharray="10 6" stroke-linecap="round" />
                    <path d="M50 185 H470" stroke="url(#boundaryVaultGrad)" stroke-width="3" stroke-linecap="round" />

                    <!-- Massive Ascending Cyber Growth Momentum Bars -->
                    <rect x="70" y="115" width="28" height="55" rx="6" stroke="url(#boundaryVaultGrad)" stroke-width="3" fill="#10b981" fill-opacity="0.08" />
                    <rect x="115" y="85" width="28" height="85" rx="6" stroke="url(#boundaryVaultGrad)" stroke-width="3" fill="#10b981" fill-opacity="0.1" />
                    <rect x="375" y="60" width="28" height="110" rx="6" stroke="url(#boundaryVaultGrad)" stroke-width="3" fill="#10b981" fill-opacity="0.12" />
                    <rect x="420" y="30" width="28" height="140" rx="6" stroke="url(#boundaryVaultGrad)" stroke-width="3.5" fill="#eab308" fill-opacity="0.15" />

                    <!-- Savage Cyber Wealth Shield & Central Vault Mechanism -->
                    <polygon points="260,18 350,55 350,135 260,185 170,135 170,55" stroke="url(#boundaryVaultGrad)" stroke-width="5" fill="#061a12" fill-opacity="0.03" stroke-linejoin="round" />
                    <polygon points="260,32 335,63 335,127 260,170 185,127 185,63" stroke="url(#boundaryPulseGrad)" stroke-width="2.5" stroke-dasharray="8 4" fill="none" stroke-linejoin="round" />

                    <!-- Vault Gear Wheel & Luminous Core -->
                    <circle cx="260" cy="100" r="46" stroke="url(#boundaryVaultGrad)" stroke-width="4.5" fill="#ffffff" fill-opacity="0.25" />
                    <circle cx="260" cy="100" r="34" stroke="url(#boundaryPulseGrad)" stroke-width="3" stroke-dasharray="10 5" />
                    <circle cx="260" cy="100" r="18" fill="url(#boundaryVaultGrad)" fill-opacity="0.3" stroke="url(#boundaryVaultGrad)" stroke-width="3" />
                    <circle cx="260" cy="100" r="6" fill="#eab308" />

                    <!-- Radial Vault Bolt Latches -->
                    <path d="M260 48 V62 M260 138 V152 M208 100 H222 M298 100 H312 M223 63 L233 73 M287 127 L297 137 M223 137 L233 127 M287 73 L297 63" stroke="url(#boundaryVaultGrad)" stroke-width="3.5" stroke-linecap="round" />

                    <!-- Aggressive Upward Rocketing Trend & Surge Vectors -->
                    <path d="M60 145 L150 100 L210 115 L320 45 L460 20" stroke="url(#boundaryVaultGrad)" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" />
                    <!-- Dynamic Arrow Tip -->
                    <path d="M435 16 L465 20 L452 48" stroke="url(#boundaryVaultGrad)" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" fill="url(#boundaryVaultGrad)" fill-opacity="0.2" />

                    <!-- Luminous Sparkle & Diamond Nodes -->
                    <polygon points="460,20 467,13 474,20 467,27" fill="#eab308" />
                    <polygon points="60,145 64,141 68,145 64,149" fill="#10b981" />
                    <polygon points="320,45 325,40 330,45 325,50" fill="#34d399" />
                </svg>
            </div>

            <!-- Enhanced Boundary Collections Grid (Scrolls Smoothly Above Fixed Watermark) -->
            <div class="flex-1 overflow-y-auto p-4 sm:p-5 min-h-0 relative z-10" style="transform: translateZ(0);">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 pb-6" id="boundaryGrid">
                    <!-- Loading State -->
                    <div class="col-span-full text-center py-16">
                        <div class="inline-flex flex-col items-center">
                            <div class="animate-spin rounded-full h-12 w-12 border-4 border-emerald-500 border-t-transparent mb-4"></div>
                            <span class="text-lg text-slate-700 font-bold mb-1">Loading boundary collections...</span>
                            <p class="text-xs text-slate-400">Fetching live revenue records and transaction logs</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Units Overview Modal -->
    <div id="unitsModal" class="hidden fixed inset-0 bg-slate-950/80 z-50 flex items-center justify-center p-3 sm:p-5 backdrop-blur-md transition-all duration-300">
        <div class="bg-white rounded-3xl shadow-2xl max-w-7xl w-full mx-auto h-[92vh] sm:h-[95vh] flex flex-col ring-1 ring-white/10 overflow-hidden border border-slate-800/40">
            <!-- ─── 21st.dev Inspired Dark Glassmorphism Header ─── -->
            <div class="relative pt-6 pb-5 px-5 sm:px-7 border-b border-slate-800/90 bg-[#090d16] flex-shrink-0 overflow-hidden select-none">
                
                <!-- Subtle Radial Ambient Glow -->
                <div class="absolute inset-0 pointer-events-none opacity-70" style="background: radial-gradient(circle at 50% -20%, rgba(245, 158, 11, 0.22), rgba(99, 102, 241, 0.15) 45%, transparent 80%);"></div>
                
                <!-- High-Tech Micro-Dot Grid Pattern SVG Background -->
                <div class="absolute inset-0 opacity-[0.08] pointer-events-none" style="background-image: radial-gradient(rgba(255, 255, 255, 0.9) 1px, transparent 1px); background-size: 20px 20px;"></div>

                <!-- Top Row: Completely Unobstructed Shiny Centerpiece -->
                <div class="relative z-10">
                    <div class="flex items-center justify-between gap-3 mb-4 sm:mb-5">
                        <!-- Left: Minimal Live Badge -->
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-900/80 border border-slate-700/80 text-amber-400 shadow-sm backdrop-blur-md">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                <span class="text-[11px] font-black uppercase tracking-wider text-slate-200">Fleet Live Matrix</span>
                            </div>
                        </div>

                        <!-- Center: Radiant Shiny Title (Unblocked & Front Stage) -->
                        <div class="flex-1 text-center px-2 overflow-hidden">
                            <h1 class="animated-shiny-units-text text-2xl sm:text-4xl md:text-5xl lg:text-6xl font-black uppercase tracking-[0.16em] whitespace-nowrap drop-shadow-lg">
                                Units Overview
                            </h1>
                        </div>

                        <!-- Right: Close Button -->
                        <div class="flex items-center justify-end flex-shrink-0">
                            <button onclick="hideUnitsModal()" class="w-9 h-9 rounded-xl bg-slate-800/80 hover:bg-rose-500/20 border border-slate-700 hover:border-rose-500/40 text-slate-400 hover:text-rose-300 flex items-center justify-center transition-all duration-200 backdrop-blur-md shadow-sm group">
                                <i data-lucide="x" class="w-4 h-4 group-hover:rotate-90 transition-transform duration-200"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Search and Filter Row -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        <!-- 21st.dev Style Search Bar -->
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="search" class="w-4 h-4 text-slate-400"></i>
                            </div>
                            <input type="search" 
                                id="unitSearchInput"
                                placeholder="Search units by plate number, driver, status..."
                                class="w-full pl-10 pr-9 py-2.5 bg-slate-900/90 backdrop-blur-md border border-slate-700/80 rounded-xl text-slate-100 placeholder-slate-400 text-xs sm:text-sm focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all duration-200 shadow-inner"
                                onkeyup="filterUnits()"
                                autocomplete="new-password" spellcheck="false" autocorrect="off" autocapitalize="off" readonly onfocus="this.removeAttribute('readonly');">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button onclick="clearSearch()" class="text-slate-500 hover:text-slate-300 transition-colors">
                                    <i data-lucide="x-circle" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Status Filter Segmented Buttons -->
                        <div class="flex bg-slate-900/95 backdrop-blur-md border border-slate-800 p-1 rounded-xl shadow-inner gap-1 flex-shrink-0">
                            <button 
                                id="btn-all-units" 
                                onclick="setUnitStatusFilter('all')"
                                class="px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all duration-200 bg-amber-500 text-slate-950 shadow-md shadow-amber-500/20"
                            >
                                All
                            </button>
                            <button 
                                id="btn-active-units" 
                                onclick="setUnitStatusFilter('active')"
                                class="px-3.5 py-1.5 text-xs font-semibold rounded-lg transition-all duration-200 text-slate-400 hover:text-white hover:bg-slate-800/80"
                            >
                                Active
                            </button>
                            <button 
                                id="btn-maintenance-units" 
                                onclick="setUnitStatusFilter('maintenance')"
                                class="px-3.5 py-1.5 text-xs font-semibold rounded-lg transition-all duration-200 text-slate-400 hover:text-white hover:bg-slate-800/80"
                            >
                                Maintenance
                            </button>
                            <button 
                                id="btn-coding-units" 
                                onclick="setUnitStatusFilter('coding')"
                                class="px-3.5 py-1.5 text-xs font-semibold rounded-lg transition-all duration-200 text-slate-400 hover:text-white hover:bg-slate-800/80"
                            >
                                Coding
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex-1 overflow-hidden flex flex-col min-h-0 bg-slate-100/60 relative">
                <!-- ─── Compact Summary Metrics Strip with 3D SVG Assets (21st.dev Style) ─── -->
                <div class="bg-white/90 backdrop-blur-md p-3 sm:p-3.5 border-b border-slate-200/80 flex-shrink-0 shadow-xs relative z-20">
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-3.5 max-w-7xl mx-auto">
                        
                        <!-- 1. Total Fleet Card -->
                        <div class="relative overflow-hidden rounded-2xl shadow-xs border border-blue-200/80 bg-gradient-to-br from-white via-blue-50/50 to-blue-100/40 p-2.5 sm:p-3">
                            <div class="absolute left-0 inset-y-0 h-6 w-1 rounded-r-full bg-blue-500 my-auto"></div>
                            <div class="relative z-10 pl-2 pr-12">
                                <div class="text-blue-600 text-[8px] sm:text-[9px] font-extrabold uppercase tracking-widest leading-none mb-1">Total Fleet</div>
                                <div class="text-lg sm:text-2xl font-black text-slate-800 leading-none mb-0.5 tracking-tight" id="totalUnitsCount">0</div>
                                <div class="text-[9px] font-bold text-blue-600/75 uppercase tracking-tight">Registered Fleet</div>
                            </div>
                            <img src="{{ asset('image/kpi/taxi_3d.svg') }}" alt="Total Fleet" class="absolute -right-2 -bottom-2 w-14 h-14 sm:w-16 sm:h-16 object-contain pointer-events-none opacity-90 drop-shadow-sm">
                        </div>

                        <!-- 2. Vacant Units Card -->
                        <div class="relative overflow-hidden rounded-2xl shadow-xs border border-emerald-200/80 bg-gradient-to-br from-white via-emerald-50/50 to-emerald-100/40 p-2.5 sm:p-3">
                            <div class="absolute left-0 inset-y-0 h-6 w-1 rounded-r-full bg-emerald-500 my-auto"></div>
                            <div class="relative z-10 pl-2 pr-12">
                                <div class="text-emerald-700 text-[8px] sm:text-[9px] font-extrabold uppercase tracking-widest leading-none mb-1 truncate">Vacant Units</div>
                                <div class="text-lg sm:text-2xl font-black text-emerald-600 leading-none mb-0.5 tracking-tight" id="activeUnitsCount">0</div>
                                <div class="text-[9px] font-bold text-emerald-600/75 uppercase tracking-tight">No Driver Assigned</div>
                            </div>
                            <img src="{{ asset('image/kpi/drivers_3d.svg') }}" alt="Vacant Units" class="absolute -right-2 -bottom-2 w-14 h-14 sm:w-16 sm:h-16 object-contain pointer-events-none opacity-90 drop-shadow-sm">
                        </div>

                        <!-- 3. Active (With Driver) Card -->
                        <div class="relative overflow-hidden rounded-2xl shadow-xs border border-amber-200/80 bg-gradient-to-br from-white via-amber-50/50 to-amber-100/40 p-2.5 sm:p-3">
                            <div class="absolute left-0 inset-y-0 h-6 w-1 rounded-r-full bg-amber-500 my-auto"></div>
                            <div class="relative z-10 pl-2 pr-12">
                                <div class="text-amber-700 text-[8px] sm:text-[9px] font-extrabold uppercase tracking-widest leading-none mb-1 truncate">Active Units</div>
                                <div class="text-lg sm:text-2xl font-black text-amber-600 leading-none mb-0.5 tracking-tight" id="roiUnitsCount">0</div>
                                <div class="text-[9px] font-bold text-amber-600/75 uppercase tracking-tight">With Driver</div>
                            </div>
                            <img src="{{ asset('image/kpi/owner_active_3d.svg') }}" alt="Active Units" class="absolute -right-2 -bottom-2 w-14 h-14 sm:w-16 sm:h-16 object-contain pointer-events-none opacity-90 drop-shadow-sm">
                        </div>

                        <!-- 4. Average ROI Card -->
                        <div class="relative overflow-hidden rounded-2xl shadow-xs border border-violet-200/80 bg-gradient-to-br from-white via-violet-50/50 to-violet-100/40 p-2.5 sm:p-3">
                            <div class="absolute left-0 inset-y-0 h-6 w-1 rounded-r-full bg-violet-500 my-auto"></div>
                            <div class="relative z-10 pl-2 pr-12">
                                <div class="text-violet-700 text-[8px] sm:text-[9px] font-extrabold uppercase tracking-widest leading-none mb-1 truncate">Average ROI</div>
                                <div class="text-lg sm:text-2xl font-black text-violet-600 leading-none mb-0.5 tracking-tight" id="avgRoiCount">0%</div>
                                <div class="text-[9px] font-bold text-violet-600/75 uppercase tracking-tight">ROI Achieved</div>
                            </div>
                            <img src="{{ asset('image/kpi/crown_3d.svg') }}" alt="Average ROI" class="absolute -right-2 -bottom-2 w-14 h-14 sm:w-16 sm:h-16 object-contain pointer-events-none opacity-90 drop-shadow-sm">
                        </div>

                    </div>
                </div>

                <!-- 🏎️ Fixed Savage Fleet Cyber Taxi Watermark Background (Stays Stationary on Scroll) -->
                <div class="absolute inset-x-0 bottom-0 top-16 flex items-center justify-center pointer-events-none select-none z-0 overflow-hidden px-4" aria-hidden="true" style="transform: translateZ(0);">
                    <svg viewBox="0 0 520 180" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-[96%] max-w-5xl h-auto opacity-[0.28] transform -rotate-1 select-none pointer-events-none">
                        <defs>
                            <linearGradient id="savageBodyGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#f59e0b" />
                                <stop offset="50%" stop-color="#6366f1" />
                                <stop offset="100%" stop-color="#f59e0b" />
                            </linearGradient>
                            <linearGradient id="savageWheelGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" stop-color="#d97706" />
                                <stop offset="100%" stop-color="#4f46e5" />
                            </linearGradient>
                        </defs>
                        <!-- Speed Lines & Dynamic Motion Trails -->
                        <path d="M10 135 H150" stroke="url(#savageBodyGrad)" stroke-width="4.5" stroke-dasharray="8 6" stroke-linecap="round" />
                        <path d="M35 150 H210" stroke="url(#savageBodyGrad)" stroke-width="5.5" stroke-dasharray="16 8" stroke-linecap="round" />
                        <path d="M70 162 H340" stroke="url(#savageBodyGrad)" stroke-width="6.5" stroke-linecap="round" />
                        <!-- Aggressive Savage GT / Cyber Sedan Silhouette -->
                        <path d="M110 138 L160 92 Q190 62 250 60 L335 60 Q385 64 415 102 L465 116 Q495 125 505 142 L500 152 Q492 158 470 158 H135 Q115 158 108 146 Z" stroke="url(#savageBodyGrad)" stroke-width="6" fill="#f59e0b" fill-opacity="0.08" stroke-linejoin="round" />
                        <!-- Cockpit Aero Chiseled Canopy -->
                        <path d="M235 64 L180 92 H320 L360 64 Z" stroke="url(#savageBodyGrad)" stroke-width="4" fill="#6366f1" fill-opacity="0.12" stroke-linejoin="round" />
                        <path d="M328 92 L368 66 L402 98 H328 Z" stroke="url(#savageBodyGrad)" stroke-width="3.5" fill="#f59e0b" fill-opacity="0.1" stroke-linejoin="round" />
                        <!-- Aggressive Headlight Blade & Splitter -->
                        <path d="M460 120 L498 126 L475 134 Z" fill="#f59e0b" />
                        <path d="M470 152 H510 L495 158 H460 Z" fill="#f59e0b" />
                        <!-- Futuristic Turbine Wheels -->
                        <circle cx="180" cy="154" r="26" stroke="url(#savageWheelGrad)" stroke-width="6" fill="#ffffff" fill-opacity="0.35" />
                        <circle cx="180" cy="154" r="14" stroke="url(#savageBodyGrad)" stroke-width="4" stroke-dasharray="8 4" />
                        <circle cx="420" cy="154" r="26" stroke="url(#savageWheelGrad)" stroke-width="6" fill="#ffffff" fill-opacity="0.35" />
                        <circle cx="420" cy="154" r="14" stroke="url(#savageBodyGrad)" stroke-width="4" stroke-dasharray="8 4" />
                        <!-- Savage GT Wing / Spoiler -->
                        <path d="M100 122 L115 105 H145 L130 122 Z" stroke="url(#savageBodyGrad)" stroke-width="4" fill="#f59e0b" fill-opacity="0.2" stroke-linejoin="round" />
                        <!-- Cyber Taxi Roof Beacon -->
                        <path d="M280 52 L290 40 H330 L340 52 Z" stroke="url(#savageBodyGrad)" stroke-width="3.5" fill="#f59e0b" fill-opacity="0.3" stroke-linejoin="round" />
                    </svg>
                </div>

                <!-- Enhanced Units Grid (Scrolls Smoothly Above Fixed Watermark) -->
                <div class="flex-1 overflow-y-auto p-4 sm:p-5 min-h-0 relative z-10" style="transform: translateZ(0);">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 pb-6" id="unitsGrid">
                        <!-- Enhanced Loading State -->
                        <div class="col-span-full text-center py-16">
                            <div class="inline-flex flex-col items-center">
                                <div class="animate-spin rounded-full h-12 w-12 border-4 border-amber-500 border-t-transparent mb-4"></div>
                                <span class="text-lg text-slate-700 font-bold mb-1">Loading units matrix...</span>
                                <p class="text-xs text-slate-400">Fetching live fleet status and financial records</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Flagged Units Modal -->
    <div id="flaggedUnitsModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-60 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden border border-red-100">
            <div class="p-4 bg-gradient-to-r from-red-600 to-rose-700 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 bg-white/20 rounded-lg backdrop-blur-sm">
                        <i data-lucide="shield-alert" class="w-5 h-5 text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white uppercase tracking-tight">Security Flags / Missing Units</h3>
                        <p class="text-red-100 text-[10px] font-bold uppercase tracking-widest">Fleet Lockdown Management</p>
                    </div>
                </div>
                <button onclick="document.getElementById('flaggedUnitsModal').classList.add('hidden')" class="text-white/80 hover:text-white hover:bg-white/20 p-2 rounded-lg transition-all duration-200 backdrop-blur-sm">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div id="flaggedUnitsList" class="max-h-[70vh] overflow-y-auto p-4 space-y-4 bg-gray-50/50">
                <div class="text-center py-12">
                    <div class="animate-spin rounded-full h-10 w-10 border-4 border-red-600 border-t-transparent mx-auto mb-3"></div>
                    <p class="text-slate-500 font-bold uppercase tracking-widest text-[10px]">Loading security status...</p>
                </div>
            </div>
        </div>
    </div>

    @include('driver-management.partials._driver_details_modal')

    <script src="{{ asset('js/realtime-dashboard.js') }}"></script>
    <script>
        // Register Chart.js datalabels plugin
        try {
            if (typeof Chart !== 'undefined' && typeof ChartDataLabels !== 'undefined') {
                Chart.register(ChartDataLabels);
            }
        } catch (e) {
            console.warn('ChartDataLabels plugin not available:', e);
        }
        
        
        // Weekly Financial Chart
        try {
            const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
            const weeklyData = @json($weekly_data);
            const wGrad1 = weeklyCtx.createLinearGradient(0, 0, 0, 300);
            wGrad1.addColorStop(0, 'rgba(234,179,8,0.25)'); wGrad1.addColorStop(1, 'rgba(234,179,8,0.01)');
            const wGrad2 = weeklyCtx.createLinearGradient(0, 0, 0, 300);
            wGrad2.addColorStop(0, 'rgba(239,68,68,0.2)'); wGrad2.addColorStop(1, 'rgba(239,68,68,0.01)');
            const wGrad3 = weeklyCtx.createLinearGradient(0, 0, 0, 300);
            wGrad3.addColorStop(0, 'rgba(34,197,94,0.25)'); wGrad3.addColorStop(1, 'rgba(34,197,94,0.01)');
            let isWeeklyChartInitialized = false;
            function getWeeklyChartConfig() {
                return {
                    type: 'line',
                    data: {
                        labels: weeklyData.map(d => d.day),
                        datasets: [
                            { label: 'Boundary', data: weeklyData.map(d => 0), borderColor: '#eab308', backgroundColor: wGrad1, borderWidth: 2.5, tension: 0.45, fill: true, pointBackgroundColor: '#eab308', pointRadius: 4, pointHoverRadius: 7 },
                            { label: 'Expenses', data: weeklyData.map(d => 0), borderColor: '#ef4444', backgroundColor: wGrad2, borderWidth: 2.5, tension: 0.45, fill: true, pointBackgroundColor: '#ef4444', pointRadius: 4, pointHoverRadius: 7 },
                            { label: 'Net Income', data: weeklyData.map(d => 0), borderColor: '#22c55e', backgroundColor: wGrad3, borderWidth: 2.5, tension: 0.45, fill: true, pointBackgroundColor: '#22c55e', pointRadius: 4, pointHoverRadius: 7 }
                        ]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        animation: { duration: 1500, easing: 'easeOutQuart' },
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: { position: 'top', labels: { usePointStyle: true, pointStyleWidth: 10, font: { size: 12, weight: '600' }, padding: 18 } },
                            tooltip: { backgroundColor: 'rgba(15,23,42,0.95)', padding: 14, cornerRadius: 12, callbacks: { label: ctx => ` ${ctx.dataset.label}: ₱${ctx.parsed.y.toLocaleString()}` } }
                        },
                        scales: {
                            x: { grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { font: { size: 11, weight: '600' }, color: '#64748b' } },
                            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { font: { size: 11 }, color: '#64748b', callback: v => '₱' + v.toLocaleString() } }
                        }
                    }
                };
            }

            const weeklyObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        if (!isWeeklyChartInitialized) {
                            window.weeklyChart = new Chart(weeklyCtx, getWeeklyChartConfig());
                            isWeeklyChartInitialized = true;
                        }
                        window.weeklyChart.data.datasets[0].data = weeklyData.map(d => d.boundary);
                        window.weeklyChart.data.datasets[1].data = weeklyData.map(d => d.expenses);
                        window.weeklyChart.data.datasets[2].data = weeklyData.map(d => d.net);
                        window.weeklyChart.update();
                    } else {
                        if (isWeeklyChartInitialized && window.weeklyChart) {
                            window.weeklyChart.data.datasets[0].data = weeklyData.map(d => 0);
                            window.weeklyChart.data.datasets[1].data = weeklyData.map(d => 0);
                            window.weeklyChart.data.datasets[2].data = weeklyData.map(d => 0);
                            window.weeklyChart.update('none');
                        }
                    }
                });
            }, { threshold: 0.3 });
            weeklyObserver.observe(document.getElementById('weeklyChart'));
        } catch (error) { console.error('Weekly Chart Error:', error); }

        // Unit Status Chart - (Handled by the premium donut chart below)


        // Revenue Trend Chart - Premium Line
        try {
            const revenueTrendCtx = document.getElementById('revenueTrendChart').getContext('2d');
            const revenueTrendData = @json($revenue_trend);
            
            window.currentRevenueTrendValues = revenueTrendData.map(d => d.revenue);
            window.isRevenueTrendIntersecting = false;
            let isRevenueTrendChartInitialized = false;

            const rGrad = revenueTrendCtx.createLinearGradient(0, 0, 0, 320);
            rGrad.addColorStop(0, 'rgba(37,99,235,0.3)'); rGrad.addColorStop(0.6, 'rgba(37,99,235,0.08)'); rGrad.addColorStop(1, 'rgba(37,99,235,0)');
            
            function getRevenueTrendChartConfig() {
                return {
                    type: 'line',
                    data: {
                        labels: revenueTrendData.map(d => d.date),
                        datasets: [{
                            label: 'Revenue', data: window.currentRevenueTrendValues.map(d => 0),
                            borderColor: '#2563eb', backgroundColor: rGrad,
                            borderWidth: 3, tension: 0.45, fill: true,
                            pointBackgroundColor: '#fff', pointBorderColor: '#2563eb', pointBorderWidth: 2.5,
                            pointRadius: 5, pointHoverRadius: 8, pointHoverBackgroundColor: '#2563eb'
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        animation: { duration: 1500, easing: 'easeOutQuart' },
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(15,23,42,0.95)', padding: 14, cornerRadius: 12,
                                callbacks: { label: ctx => ` Revenue: ₱${ctx.parsed.y.toLocaleString()}` }
                            }
                        },
                        scales: {
                            x: { grid: { display: false }, ticks: { font: { size: 11, weight: '600' }, color: '#94a3b8', maxRotation: 45 } },
                            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                                 ticks: { font: { size: 11 }, color: '#94a3b8', callback: v => '₱' + v.toLocaleString() } }
                        }
                    }
                };
            }

            const revenueTrendObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    window.isRevenueTrendIntersecting = entry.isIntersecting;
                    if (entry.isIntersecting) {
                        if (!isRevenueTrendChartInitialized) {
                            window.revenueTrendChart = new Chart(revenueTrendCtx, getRevenueTrendChartConfig());
                            isRevenueTrendChartInitialized = true;
                        }
                        window.revenueTrendChart.data.datasets[0].data = window.currentRevenueTrendValues;
                        window.revenueTrendChart.update();
                    } else {
                        if (isRevenueTrendChartInitialized && window.revenueTrendChart) {
                            window.revenueTrendChart.data.datasets[0].data = window.currentRevenueTrendValues.map(d => 0);
                            window.revenueTrendChart.update('none');
                        }
                    }
                });
            }, { threshold: 0.3 });
            revenueTrendObserver.observe(document.getElementById('revenueTrendChart'));
        } catch (error) { console.error('Revenue Trend Chart Error:', error); }

        // Unit Performance Chart - Modernized Horizontal Enterprise View
        try {
            const unitPerformanceCtx = document.getElementById('unitPerformanceChart').getContext('2d');
            const unitPerformanceData = @json($unit_performance);
            
            // Create sleek gradients for a premium feel
            const actualGradient = unitPerformanceCtx.createLinearGradient(0, 0, 400, 0);
            actualGradient.addColorStop(0, '#3b82f6'); // Blue 500
            actualGradient.addColorStop(1, '#60a5fa'); // Blue 400
            
            const targetGradient = unitPerformanceCtx.createLinearGradient(0, 0, 400, 0);
            targetGradient.addColorStop(0, '#f59e0b'); // Amber 500
            targetGradient.addColorStop(1, '#fbbf24'); // Amber 400

            // Initialize chart via IntersectionObserver so animation runs when scrolled into view
            let isUnitChartInitialized = false;
            
            function getChartConfig() {
                return {
                    type: 'bar',
                    data: {
                        labels: unitPerformanceData.map(d => d.unit),
                        datasets: [
                            {
                                label: 'Actual Collected',
                                // Start at 0 for initial animation state
                                data: unitPerformanceData.map(d => 0),
                                backgroundColor: actualGradient,
                                borderColor: '#2563eb',
                                borderWidth: 0,
                                borderRadius: 6,
                                barThickness: 12,
                            },
                            {
                                label: 'Monthly Target (30 Days)',
                                // Start at 0 for initial animation state
                                data: unitPerformanceData.map(d => 0),
                                backgroundColor: 'rgba(245, 158, 11, 0.15)', // Subtle target indicator
                                borderColor: '#f59e0b',
                                borderWidth: 1,
                                borderRadius: 6,
                                barThickness: 12,
                                borderDash: [5, 5] // Dashed look for target
                            }
                        ]
                    },
                    options: {
                        indexAxis: 'y', // Switch to horizontal for better Plate Number readability
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 2000,
                            easing: 'easeOutQuart'
                        },
                        plugins: {
                            legend: {
                                display: false // Using custom legend in sidebar instead
                            },
                            datalabels: {
                                color: function(context) {
                                    return context.datasetIndex === 0 ? '#ffffff' : '#64748b'; // White for actual, Slate for target
                                },
                                anchor: 'end',
                                align: 'start',
                                offset: 4,
                                font: { weight: 'bold', size: 10 },
                                formatter: function(value) {
                                    return value > 0 ? value.toLocaleString() : '';
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.95)',
                                padding: 12,
                                cornerRadius: 10,
                                titleFont: { size: 14, weight: 'bold' },
                                callbacks: {
                                    label: function(context) {
                                        const val = context.parsed.x || 0;
                                        return ` ₱${val.toLocaleString()}`;
                                    },
                                    footer: (items) => {
                                        const index = items[0].dataIndex;
                                        const data = unitPerformanceData[index];
                                        const diff = data.performance - data.target;
                                        const pct = ((data.performance / data.target) * 100).toFixed(1);
                                        return ` Achievement: ${pct}% of target\n Variance: ₱${diff.toLocaleString()}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                grid: { color: 'rgba(0,0,0,0.03)', drawBorder: false },
                                ticks: { 
                                    callback: function (value) { return '₱' + value.toLocaleString(); },
                                    font: { size: 10 }
                                }
                            },
                            y: {
                                grid: { display: false, drawBorder: false },
                                ticks: { 
                                    font: { size: 11, weight: '700' },
                                    color: '#334155'
                                }
                            }
                        }
                    }
                };
            }

            const chartObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        if (!isUnitChartInitialized) {
                            window.unitPerformanceChart = new Chart(unitPerformanceCtx, getChartConfig());
                            isUnitChartInitialized = true;
                        }
                        
                        // Animate bars growing to actual values
                        window.unitPerformanceChart.data.datasets[0].data = unitPerformanceData.map(d => d.performance);
                        window.unitPerformanceChart.data.datasets[1].data = unitPerformanceData.map(d => d.target);
                        window.unitPerformanceChart.update();
                        
                    } else {
                        // Reset bars to zero instantly when out of view
                        if (isUnitChartInitialized && window.unitPerformanceChart) {
                            window.unitPerformanceChart.data.datasets[0].data = unitPerformanceData.map(d => 0);
                            window.unitPerformanceChart.data.datasets[1].data = unitPerformanceData.map(d => 0);
                            window.unitPerformanceChart.update('none'); // Update instantly without animation
                        }
                    }
                });
            }, { threshold: 0.3 }); // Use 0.3 so it safely triggers on small screens too
            
            chartObserver.observe(document.getElementById('unitPerformanceChart'));

            // Update Executive Insight: Top Performer
            if (unitPerformanceData && unitPerformanceData.length > 0) {
                const topUnit = unitPerformanceData[0]; // Data is sorted by performance descending
                document.getElementById('insightTopPlate').textContent = topUnit.unit;
            }
        } catch (error) {
            console.error('Unit Performance Chart Error:', error);
        }

        // Expense Breakdown Chart - Premium Pie
        try {
            const expenseBreakdownCtx = document.getElementById('expenseBreakdownChart').getContext('2d');
            let expenseBreakdownData = @json($expense_breakdown);
            let isPlaceholder = false;
            if (!expenseBreakdownData || expenseBreakdownData.length === 0 ||
                (Array.isArray(expenseBreakdownData) && expenseBreakdownData.every(d => d.amount === 0))) {
                isPlaceholder = true;
                expenseBreakdownData = [
                    { category: 'Maintenance', amount: 4500 },
                    { category: 'Fuel & Oil', amount: 3200 },
                    { category: 'Salaries', amount: 8000 },
                    { category: 'Parts', amount: 2100 },
                    { category: 'Others', amount: 1200 }
                ];
            }
            const pieColors = ['#ef4444','#f59e0b','#10b981','#3b82f6','#8b5cf6','#ec4899','#06b6d4'];
            const pieHover = ['#dc2626','#d97706','#059669','#2563eb','#7c3aed','#db2777','#0891b2'];
            let isExpenseChartInitialized = false;
            function getExpenseChartConfig() {
                return {
                    type: 'pie',
                    data: {
                        labels: expenseBreakdownData.map(d => d.category),
                        datasets: [{ data: expenseBreakdownData.map(d => 0), backgroundColor: pieColors, hoverBackgroundColor: pieHover, borderWidth: 3, borderColor: '#fff', hoverOffset: 12 }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'right', labels: { usePointStyle: true, pointStyleWidth: 12, font: { size: 12, weight: '600' }, padding: 16, color: '#374151' } },
                            tooltip: {
                                backgroundColor: 'rgba(15,23,42,0.95)', padding: 14, cornerRadius: 12,
                                callbacks: {
                                    label: function(ctx) {
                                        const total = ctx.dataset.data.reduce((a,b) => a+b, 0);
                                        const pct = total > 0 ? ((ctx.parsed / total) * 100).toFixed(1) : 0;
                                        return ` ${ctx.label}: ₱${ctx.parsed.toLocaleString()} (${pct}%)`;
                                    }
                                }
                            },
                            datalabels: { display: true, color: '#fff', font: { weight: 'bold', size: 16 }, formatter: (val, ctx) => { let sum = 0; ctx.dataset.data.forEach(n => { sum += Number(n) || 0; }); const v = Number(val) || 0; if (sum === 0 || v === 0) return ''; return Math.round((v / sum) * 100) + '%'; } }
                        },
                        animation: { animateRotate: true, animateScale: true, duration: 1500, easing: 'easeOutQuart' }
                    }
                };
            }

            const expenseObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        if (!isExpenseChartInitialized) {
                            window.expenseBreakdownChart = new Chart(expenseBreakdownCtx, getExpenseChartConfig());
                            isExpenseChartInitialized = true;
                        }
                        window.expenseBreakdownChart.data.datasets[0].data = expenseBreakdownData.map(d => d.amount);
                        window.expenseBreakdownChart.update();
                    } else {
                        if (isExpenseChartInitialized && window.expenseBreakdownChart) {
                            window.expenseBreakdownChart.data.datasets[0].data = expenseBreakdownData.map(d => 0);
                            window.expenseBreakdownChart.update('none');
                        }
                    }
                });
            }, { threshold: 0.3 });
            expenseObserver.observe(document.getElementById('expenseBreakdownChart'));
        } catch (error) { console.error('Expense Chart Error:', error); }




        // Top Drivers Chart - Premium Horizontal Bar
        try {
            const topDriversCtx = document.getElementById('topDriversChart').getContext('2d');
            let topDriversData = @json($top_drivers);
            let isPlaceholder = false;
            if (!topDriversData || topDriversData.length === 0 ||
                (Array.isArray(topDriversData) && topDriversData.every(d => d.score === 0))) {
                isPlaceholder = true;
                topDriversData = [
                    { name: 'Bernardo Silva', score: 28, total: 42000 },
                    { name: 'Kevin De Bruyne', score: 26, total: 39000 },
                    { name: 'Erling Haaland', score: 25, total: 37500 },
                    { name: 'Phil Foden', score: 22, total: 33000 },
                    { name: 'Rodri Hernandez', score: 20, total: 30000 }
                ];
            }
            const barColors = topDriversData.map((_, i) => i===0?'#2563eb':i===1?'#7c3aed':i===2?'#0891b2':'#64748b');
            let isTopDriversChartInitialized = false;
            function getTopDriversChartConfig() {
                return {
                    type: 'bar',
                    data: {
                        labels: topDriversData.map((d,i) => { const medals=['🥇','🥈','🥉']; return `${medals[i]||'  '} ${d.name}`; }),
                        datasets: [{ label: 'Reliability Score', data: topDriversData.map(d => 0),
                            backgroundColor: barColors, borderColor: barColors, borderWidth: 0,
                            borderRadius: 10, borderSkipped: false, barThickness: 28 }]
                    },
                    options: {
                        indexAxis: 'y', responsive: true, maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: { backgroundColor: 'rgba(15,23,42,0.95)', padding: 14, cornerRadius: 12, displayColors: false,
                                callbacks: {
                                    label: ctx => ` ⭐ Reliability: ${ctx.parsed.x} clean service days`,
                                    footer: items => { const amt = topDriversData[items[0].dataIndex].total; return ` ₱ Total Revenue: ₱${amt.toLocaleString()}`; }
                                }
                            },
                            datalabels: { color: '#fff', font: { weight: 'bold', size: 12 }, anchor: 'end', align: 'start', offset: 8, formatter: v => v>0?v:'' }
                        },
                        scales: {
                            x: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false }, ticks: { font: { size: 11, weight: '500' }, color: '#94a3b8' } },
                            y: { grid: { display: false, drawBorder: false }, ticks: { font: { size: 13, weight: '600' }, color: '#1e293b' } }
                        },
                        animation: { duration: 1500, easing: 'easeOutQuart' }
                    }
                };
            }

            const topDriversObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        if (!isTopDriversChartInitialized) {
                            window.topDriversChart = new Chart(topDriversCtx, getTopDriversChartConfig());
                            isTopDriversChartInitialized = true;
                        }
                        window.topDriversChart.data.datasets[0].data = topDriversData.map(d => d.score);
                        window.topDriversChart.update();
                    } else {
                        if (isTopDriversChartInitialized && window.topDriversChart) {
                            window.topDriversChart.data.datasets[0].data = topDriversData.map(d => 0);
                            window.topDriversChart.update('none');
                        }
                    }
                });
            }, { threshold: 0.3 });
            topDriversObserver.observe(document.getElementById('topDriversChart'));
        } catch (error) { console.error('Top Drivers Chart Error:', error); }




        // Unit Status Distribution Chart - Premium Donut
        try {
            const unitStatusDistCtx = document.getElementById('unitStatusChart').getContext('2d');
            const unitStatusDistData = @json($unit_status_distribution_data);
            const donutColors = ['#10b981','#3b82f6','#f59e0b','#ef4444'];
            const donutHover = ['#059669','#2563eb','#d97706','#dc2626'];
            let distLabels, distValues, distIsPlaceholder = false;
            if (!unitStatusDistData || unitStatusDistData.length === 0 || unitStatusDistData.every(d => d.count === 0)) {
                distIsPlaceholder = true;
                distLabels = ['Active','Maintenance','Coding','Retired'];
                distValues = [5,2,1,0];
            } else {
                distLabels = unitStatusDistData.map(d => d.status);
                distValues = unitStatusDistData.map(d => d.count);
            }
            const totalUnits = distValues.reduce((a,b) => a+b, 0);
            let isUnitStatusChartInitialized = false;
            function getUnitStatusChartConfig() {
                return {
                    type: 'doughnut',
                    data: { labels: distLabels, datasets: [{ data: distValues.map(d => 0), backgroundColor: donutColors, hoverBackgroundColor: donutHover, borderWidth: 4, borderColor: '#fff', hoverOffset: 16 }] },
                    options: {
                        responsive: true, maintainAspectRatio: false, cutout: '72%',
                        plugins: {
                            legend: { position: 'right', labels: { usePointStyle: true, pointStyleWidth: 12, font: { size: 12, weight: '600' }, padding: 18, color: '#374151',
                                generateLabels: (chart) => chart.data.labels.map((label, i) => ({ text: `${label}: ${chart.data.datasets[0].data[i]}`, fillStyle: donutColors[i], strokeStyle: '#fff', lineWidth: 2, index: i })) } },
                            tooltip: { backgroundColor: 'rgba(15,23,42,0.95)', padding: 14, cornerRadius: 12,
                                callbacks: { label: ctx => { const total = ctx.dataset.data.reduce((a,b)=>a+b,0); const pct = total>0?((ctx.parsed/total)*100).toFixed(1):0; return ` ${ctx.label}: ${ctx.parsed} units (${pct}%)`; } } },
                            datalabels: { color: '#fff', font: { weight: 'bold', size: 13 }, formatter: (val, ctx) => { const sum = ctx.dataset.data.reduce((a,b)=>a+b,0); const pct = sum>0?((val/sum)*100).toFixed(0):0; return pct>5?pct+'%':''; } }
                        },
                        animation: { animateRotate: true, animateScale: true, duration: 1500, easing: 'easeOutQuart' }
                    },
                    plugins: [{ id: 'donutCenter', afterDraw(chart) {
                        const { ctx, chartArea: { left, top, right, bottom } } = chart;
                        const cx = (left+right)/2, cy = (top+bottom)/2;
                        const currentTotal = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                        ctx.save();
                        ctx.font = 'bold 28px Inter, sans-serif'; ctx.fillStyle = '#0f172a'; ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
                        ctx.fillText(currentTotal, cx, cy-10);
                        ctx.font = '600 11px Inter, sans-serif'; ctx.fillStyle = '#94a3b8';
                        ctx.fillText('TOTAL UNITS', cx, cy+14);
                        ctx.restore();
                    }}]
                };
            }

            const unitStatusObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        if (!isUnitStatusChartInitialized) {
                            window.unitStatusChart = new Chart(unitStatusDistCtx, getUnitStatusChartConfig());
                            isUnitStatusChartInitialized = true;
                        }
                        window.unitStatusChart.data.datasets[0].data = distValues;
                        window.unitStatusChart.update();
                    } else {
                        if (isUnitStatusChartInitialized && window.unitStatusChart) {
                            window.unitStatusChart.data.datasets[0].data = distValues.map(d => 0);
                            window.unitStatusChart.update('none');
                        }
                    }
                });
            }, { threshold: 0.3 });
            unitStatusObserver.observe(document.getElementById('unitStatusChart'));
        } catch (error) { console.error('Unit Status Distribution Chart Error:', error); }

        // Revenue Trend Period Selection
        function updateRevenueTrend(period) {
            // Update button styles
            document.querySelectorAll('[id^="btn-"]').forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white', 'hover:bg-blue-700');
                btn.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
            });
            
            // Highlight active button
            const activeBtn = document.getElementById('btn-' + period + 'days');
            if (activeBtn) {
                activeBtn.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
                activeBtn.classList.add('bg-blue-600', 'text-white', 'hover:bg-blue-700');
            }
            
            // Fetch new data
            fetch(`/api/revenue-trend?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && window.revenueTrendChart) {
                        window.revenueTrendChart.data.labels = data.data.map(d => d.date);
                        window.currentRevenueTrendValues = data.data.map(d => d.revenue);
                        
                        if (window.isRevenueTrendIntersecting) {
                            window.revenueTrendChart.data.datasets[0].data = window.currentRevenueTrendValues;
                            window.revenueTrendChart.update();
                        } else {
                            window.revenueTrendChart.data.datasets[0].data = window.currentRevenueTrendValues.map(d => 0);
                            window.revenueTrendChart.update('none');
                        }
                    }
                })
                .catch(error => console.error('Error updating revenue trend:', error));
        }



        // Active Drivers Modal Functions
        function showActiveDriversModal() {
            document.getElementById('activeDriversModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            loadActiveDriversData();
        }
        
        function hideActiveDriversModal() {
            document.getElementById('activeDriversModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        function loadActiveDriversData() {
            fetch('/api/active-drivers', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    return response.json().then(data => {
                        if (!response.ok) {
                            throw new Error(data.message || `Server error ${response.status}`);
                        }
                        return data;
                    }).catch(err => {
                        if (!response.ok) {
                            throw new Error(`HTTP Error ${response.status}: ${response.statusText}`);
                        }
                        throw err;
                    });
                })
                .then(data => {
                    if (data && data.success) {
                        displayActiveDriversData(data);
                    } else {
                        showActiveDriversError(data ? data.message : 'Unknown error');
                    }
                })
                .catch(error => {
                    showActiveDriversError(error.message || 'Error loading active drivers data. Please try again.');
                });
        }
        
        function displayActiveDriversData(data) {
            const grid = document.getElementById('activeDriversGrid');
            const drivers = data.drivers || [];
            const stats = data.stats || {};
            
            // Initialize Sort Order
            window.driversSortOrder = window.driversSortOrder || 'asc';
            updateDriversSortUI();
            
            // Update summary stats
            document.getElementById('totalDriversCount').textContent = stats.total_drivers || 0;
            document.getElementById('vacantDriversCount').textContent = stats.vacant_drivers || 0;
            document.getElementById('activeWithUnitsCount').textContent = stats.active_with_units || 0;
            document.getElementById('topPerformersCount').textContent = stats.top_performers || 0;
            
            // Store original data for filtering
            window.originalActiveDriversData = drivers;
            window.currentFilteredActiveDriversData = drivers;
            
            // Render active drivers
            renderActiveDrivers(drivers);
            
            // Re-initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
        
        function renderActiveDrivers(drivers) {
            const grid = document.getElementById('activeDriversGrid');
            
            if (drivers.length === 0) {
                grid.innerHTML = `
                    <div class="col-span-full text-center py-20">
                        <div class="inline-flex flex-col items-center">
                            <div class="p-4 bg-gray-100 rounded-full mb-4">
                                <i data-lucide="users" class="w-8 h-8 text-slate-400"></i>
                            </div>
                            <span class="text-xl text-gray-600 font-semibold mb-2">No active drivers found</span>
                            <p class="text-sm text-slate-400">Try adjusting your search or date filter</p>
                        </div>
                    </div>
                `;
                return;
            }
            
            grid.innerHTML = drivers.map(driver => `
                <div onclick="openDriverDetails(${driver.id})" class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden border-l-4 border-blue-500 hover:scale-102 cursor-pointer">
                    <div class="p-4">
                        <!-- Header -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <i data-lucide="users" class="w-4 h-4 text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-slate-800">${driver.name || 'Unknown'}</h4>
                                    <span class="text-xs text-slate-500">${driver.license_number || 'N/A'}</span>
                                </div>
                            </div>
                            <div class="text-right mt-1 flex flex-col items-end gap-1">
                                ${driver.assigned_units > 0 
                                    ? `<span class="px-2.5 py-1 text-xs font-bold text-green-700 bg-green-100 rounded-full border border-green-200">Assigned</span>
                                       ${driver.plate_numbers ? `<span class="text-[10px] font-bold text-slate-400 capitalize bg-gray-100 px-2 rounded-md">${driver.plate_numbers}</span>` : ''}`
                                    : `<span class="px-2.5 py-1 text-xs font-bold text-red-700 bg-red-100 rounded-full border border-red-200">Unassigned</span>`
                                }
                            </div>
                        </div>
                        

                        
                        <!-- Performance Stats -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full ${driver.performance_rating === 'excellent' ? 'bg-green-500' : driver.performance_rating === 'good' ? 'bg-yellow-500' : driver.performance_rating === 'average' ? 'bg-orange-500' : 'bg-gray-400'} animate-pulse"></div>
                                <span class="text-xs font-medium text-gray-600">
                                    ${driver.performance_rating ? driver.performance_rating.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) : 'Unknown'}
                                </span>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-blue-600">₱${driver.total_boundary ? driver.total_boundary.toLocaleString() : '0'}</div>
                                <div class="text-xs text-slate-500">Total Collected</div>
                            </div>
                        </div>
                        
                        <!-- Footer -->
                        <div class="flex items-center justify-between text-xs text-slate-500">
                            <span class="flex items-center gap-1">
                                <i data-lucide="calendar" class="w-3 h-3"></i>
                                ${driver.hire_date || 'No hire date'}
                            </span>
                            <span class="flex items-center gap-1">
                                <i data-lucide="check-circle" class="w-3 h-3"></i>
                                Active
                            </span>
                        </div>
                    </div>
                </div>
            `).join('');
        }
        
        function filterActiveDrivers() {
            const searchTerm = document.getElementById('driversSearchInput').value.toLowerCase();
            const sortOrder = window.driversSortOrder || 'asc';
            
            let filteredDrivers = [...(window.originalActiveDriversData || [])];
            
            // Apply search filter
            if (searchTerm) {
                filteredDrivers = filteredDrivers.filter(driver => {
                    const searchableText = [
                        driver.name || '',
                        driver.license_number || '',
                        driver.phone || '',
                        '', // email removed
                        driver.address || '',
                        driver.performance_rating || '',
                        driver.total_boundary ? driver.total_boundary.toString() : '',
                        driver.assigned_units ? driver.assigned_units.toString() : '',
                        driver.hire_date || ''
                    ].join(' ').toLowerCase();
                    
                    return searchableText.includes(searchTerm);
                    return searchableText.includes(searchTerm);
                });
            }
            
            // Apply Category Filter
            const category = window.currentDriversFilterCategory || 'all';
            if (category === 'vacant') {
                filteredDrivers = filteredDrivers.filter(driver => driver.assigned_units === 0);
            } else if (category === 'active') {
                filteredDrivers = filteredDrivers.filter(driver => driver.assigned_units > 0);
            } else if (category === 'top') {
                filteredDrivers = filteredDrivers.filter(driver => driver.is_top_performer === true);
            }

            // Apply Sorting (Alphabetical by Name)
            filteredDrivers.sort((a, b) => {
                const nameA = (a.name || '').toLowerCase();
                const nameB = (b.name || '').toLowerCase();
                
                if (sortOrder === 'asc') {
                    return nameA.localeCompare(nameB);
                } else {
                    return nameB.localeCompare(nameA);
                }
            });
            
            window.currentFilteredActiveDriversData = filteredDrivers;
            renderActiveDrivers(filteredDrivers);
        }
        
        function toggleDriversSort() {
            window.driversSortOrder = window.driversSortOrder === 'asc' ? 'desc' : 'asc';
            updateDriversSortUI();
            filterActiveDrivers();
        }

        function updateDriversSortUI() {
            const icon = document.getElementById('driversSortIcon');
            const text = document.getElementById('driversSortText');
            const order = window.driversSortOrder || 'asc';
            
            if (icon && text) {
                if (order === 'asc') {
                    icon.setAttribute('data-lucide', 'sort-asc');
                    text.textContent = 'A-Z';
                } else {
                    icon.setAttribute('data-lucide', 'sort-desc');
                    text.textContent = 'Z-A';
                }
                
                // Re-initialize Lucide for the new icon
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }
        }

        function clearDriversSearch() {
            document.getElementById('driversSearchInput').value = '';
            filterActiveDrivers();
        }
        
        function setDriversFilter(category) {
            window.currentDriversFilterCategory = category;
            
            const buttons = {
                'all': document.getElementById('dFilterAll'),
                'vacant': document.getElementById('dFilterVacant'),
                'active': document.getElementById('dFilterActive'),
                'top': document.getElementById('dFilterTop')
            };
            
            Object.keys(buttons).forEach(key => {
                const btn = buttons[key];
                if (!btn) return;
                
                if (key === category) {
                    btn.className = 'px-4 py-1.5 rounded-lg text-sm font-bold transition-all duration-200 bg-white text-blue-600 shadow-md shadow-slate-200/40';
                } else {
                    btn.className = 'px-4 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 text-white hover:bg-white/10';
                }
            });
            
            filterActiveDrivers();
        }
        
        function showActiveDriversError(message, debugInfo = null) {
            const grid = document.getElementById('activeDriversGrid');
            const debugHtml = debugInfo ? `
                <div class="mt-4 p-3 bg-gray-100 rounded-lg text-xs">
                    <h4 class="font-bold text-gray-700 mb-2">Debug Information:</h4>
                    <pre class="text-gray-600 whitespace-pre-wrap">${JSON.stringify(debugInfo, null, 2)}</pre>
                </div>
            ` : '';
            
            grid.innerHTML = `
                <div class="col-span-full text-center py-20">
                    <div class="inline-flex flex-col items-center">
                        <div class="p-4 bg-red-100 rounded-full mb-4">
                            <i data-lucide="alert-circle" class="w-8 h-8 text-red-600"></i>
                        </div>
                        <span class="text-xl text-gray-600 font-semibold mb-2">Error Loading Driver Data</span>
                        <p class="text-sm text-slate-400 mb-4">${message}</p>
                        <div class="flex gap-2">
                            <button onclick="loadActiveDriversData()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i data-lucide="refresh-cw" class="w-4 h-4 inline mr-2"></i>
                                Retry
                            </button>
                            <button onclick="testActiveDriversAPI()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i data-lucide="bug" class="w-4 h-4 inline mr-2"></i>
                                Test API
                            </button>
                        </div>
                        ${debugHtml}
                    </div>
                </div>
            `;
            
            // Re-initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
        
        function testActiveDriversAPI() {
            const grid = document.getElementById('activeDriversGrid');
            grid.innerHTML = `
                <div class="col-span-full text-center py-20">
                    <div class="inline-flex flex-col items-center">
                        <div class="p-4 bg-blue-100 rounded-full mb-4">
                            <i data-lucide="bug" class="w-8 h-8 text-blue-600"></i>
                        </div>
                        <span class="text-xl text-gray-600 font-semibold mb-2">Testing API Connection</span>
                        <p class="text-sm text-slate-400 mb-4">Checking API endpoint...</p>
                        <div class="w-64 bg-gray-200 rounded-full h-2 mb-4">
                            <div class="bg-blue-600 h-2 rounded-full animate-pulse" style="width: 60%"></div>
                        </div>
                    </div>
                </div>
            `;
            
            // Test the API endpoint
            fetch('/api/active-drivers')
                .then(response => {
                    return response.text();
                })
                .then(text => {
                    try {
                        const data = JSON.parse(text);
                        showActiveDriversError('API Test Complete - Check Console for Details', {
                            response_status: 'success',
                            data_keys: Object.keys(data),
                            data: data
                        });
                    } catch (parseError) {
                        showActiveDriversError('API Test Complete - JSON Parse Error', {
                            response_status: 'parse_error',
                            raw_response: text.substring(0, 500) + (text.length > 500 ? '...' : ''),
                            parse_error: parseError.message
                        });
                    }
                })
                .catch(error => {
                    showActiveDriversError('API Test Complete - Fetch Error', {
                        response_status: 'fetch_error',
                        error: error.message,
                        stack: error.stack
                    });
                });
        }
        window.showActiveDriversModal = showActiveDriversModal;
        window.hideActiveDriversModal = hideActiveDriversModal;
        window.loadActiveDriversData = loadActiveDriversData;
        window.displayActiveDriversData = displayActiveDriversData;
        window.renderActiveDrivers = renderActiveDrivers;
        window.filterActiveDrivers = filterActiveDrivers;
        window.setActiveDriversCategory = setActiveDriversCategory;
        window.showActiveDriversError = showActiveDriversError;

        // Coding Units Modal Functions
        function getUnitPeriod(unit) {
            if (!unit) return 'future';
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const tomorrow = new Date(today);
            tomorrow.setDate(today.getDate() + 1);
            
            const formatDate = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };
            
            const todayStr = formatDate(today);
            const tomorrowStr = formatDate(tomorrow);
            
            const dayNames = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
            const todayDayIndex = today.getDay();
            const tomorrowDayIndex = tomorrow.getDay();
            
            const unitDate = unit.start_date;
            const codingDay = (unit.coding_day || '').trim().toLowerCase();
            const isCompleted = unit.coding_status === 'completed';
            
            if (isCompleted || (unitDate && unitDate < todayStr)) return 'past';
            if (unitDate === todayStr || (!unitDate && codingDay === dayNames[todayDayIndex])) return 'today';
            if (unitDate === tomorrowStr || (!unitDate && codingDay === dayNames[tomorrowDayIndex])) return 'tomorrow';
            
            const codingDayIndex = dayNames.indexOf(codingDay);
            if (!unitDate && codingDayIndex !== -1 && codingDayIndex < todayDayIndex) return 'past';
            
            return 'future';
        }
        window.getUnitPeriod = getUnitPeriod;

        function showCodingUnitsModal() {
            document.getElementById('codingUnitsModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            window.currentCodingPeriod = 'today';
            setCodingPeriod('today');
            loadCodingUnitsData();
        }
        
        function hideCodingUnitsModal() {
            document.getElementById('codingUnitsModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        async function loadCodingUnitsData() {
            const grid = document.getElementById('codingGrid');
            if (grid) {
                grid.innerHTML = `
                    <div class="col-span-full text-center py-16">
                        <div class="inline-flex flex-col items-center">
                            <div class="animate-spin rounded-full h-12 w-12 border-4 border-purple-600 border-t-transparent mb-4"></div>
                            <span class="text-lg text-gray-600 font-semibold mb-2">Loading coding data...</span>
                            <p class="text-sm text-slate-400">Please wait while we fetch coding schedule</p>
                        </div>
                    </div>
                `;
            }

            try {
                const response = await fetch('/api/coding-units', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const text = await response.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (pe) {
                    console.error('Coding API returned non-JSON:', text);
                    showCodingError('Server returned invalid response format.');
                    return;
                }
                
                if (!response.ok || !data.success) {
                    showCodingError((data && data.message) || `Server Error (${response.status})`);
                    return;
                }
                
                displayCodingUnitsData(data);
            } catch (error) {
                console.error('Error loading coding units:', error);
                showCodingError(error.message || 'Error loading coding units data. Please try again.');
            }
        }
        
        function displayCodingUnitsData(data) {
            const grid = document.getElementById('codingGrid');
            const units = (data && data.units) ? data.units : [];
            const stats = (data && data.stats) ? data.stats : {};
            
            // Store original data for filtering
            window.originalCodingUnitsData = units;
            window.currentFilteredCodingUnitsData = units;
            
            // Update summary stats
            updateCodingSummary(units);
            
            // Render coding units with active filter
            filterCodingUnits();
            
            // Re-initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
        
        function renderCodingUnits(units) {
            const grid = document.getElementById('codingGrid');
            if (!grid) return;
            
            if (!units || units.length === 0) {
                grid.innerHTML = `
                    <div class="col-span-full text-center py-20">
                        <div class="inline-flex flex-col items-center">
                            <div class="p-4 bg-gray-100 rounded-full mb-4">
                                <i data-lucide="code" class="w-8 h-8 text-slate-400"></i>
                            </div>
                            <span class="text-xl text-gray-600 font-semibold mb-2">No coding units found</span>
                            <p class="text-sm text-slate-400">Try adjusting your search or date filter</p>
                        </div>
                    </div>
                `;
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
                return;
            }
            grid.innerHTML = units.map(unit => {
                const hasDriver1 = unit.driver1_name && unit.driver1_name.trim() !== '';
                const hasDriver2 = unit.driver2_name && unit.driver2_name.trim() !== '';
                const hasAnyDriver = hasDriver1 || hasDriver2;

                let driverOverlayHTML = `
                    <div class="absolute inset-0 bg-slate-900/95 opacity-0 group-hover:opacity-100 transition-all duration-250 flex flex-col items-center justify-center gap-1.5 z-10 pointer-events-none rounded-xl">
                        <p class="text-white/60 text-[9px] font-bold uppercase tracking-widest mb-0.5">Assigned Drivers</p>
                `;

                if (hasAnyDriver) {
                    if (hasDriver1) {
                        driverOverlayHTML += `
                            <div class="flex items-center gap-2.5 bg-white/10 rounded-lg px-3 py-1.5 w-[90%]">
                                <div class="w-5 h-5 rounded-full bg-blue-500 flex items-center justify-center flex-shrink-0 shadow">
                                    <span class="text-white text-[8px] font-bold">P1</span>
                                </div>
                                <div class="text-left overflow-hidden min-w-0">
                                    <p class="text-[7px] text-blue-300 font-bold uppercase tracking-wider leading-none truncate">Primary 1</p>
                                    <p class="text-white text-[11px] font-bold leading-tight truncate">${unit.driver1_name.trim()}</p>
                                </div>
                            </div>
                        `;
                    }
                    if (hasDriver2) {
                        driverOverlayHTML += `
                            <div class="flex items-center gap-2.5 bg-white/10 rounded-lg px-3 py-1.5 w-[90%]">
                                <div class="w-5 h-5 rounded-full bg-purple-500 flex items-center justify-center flex-shrink-0 shadow">
                                    <span class="text-white text-[8px] font-bold">P2</span>
                                </div>
                                <div class="text-left overflow-hidden min-w-0">
                                    <p class="text-[7px] text-purple-300 font-bold uppercase tracking-wider leading-none truncate">Primary 2</p>
                                    <p class="text-white text-[11px] font-bold leading-tight truncate">${unit.driver2_name.trim()}</p>
                                </div>
                            </div>
                        `;
                    }
                } else {
                    driverOverlayHTML += `
                        <div class="flex flex-col items-center justify-center opacity-50">
                            <i data-lucide="user-x" class="w-5 h-5 text-white mb-1.5"></i>
                            <span class="text-[9px] text-white font-bold uppercase tracking-widest text-center leading-tight">No Driver<br>Assigned</span>
                        </div>
                    `;
                }

                driverOverlayHTML += `</div>`;

                return `
                <div class="bg-white rounded-xl shadow-md shadow-slate-200/40 hover:shadow-md transition-all duration-300 overflow-hidden ring-1 ring-slate-900/5 hover:border-purple-200 relative group min-h-[130px]">
                    ${driverOverlayHTML}
                    <div class="p-4 border-l-4 border-purple-500 h-full flex flex-col relative z-0">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2.5 bg-purple-50 rounded-lg text-purple-600">
                                    <i data-lucide="car" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-slate-800 tracking-tight leading-none">${unit.plate_number || 'N/A'}</h4>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="px-2.5 py-1 bg-purple-50 text-purple-700 text-[10px] font-bold uppercase tracking-widest rounded-md border border-purple-100">${unit.coding_type || 'Coding'}</span>
                            </div>
                        </div>
                        
                        <div class="mt-auto">
                            <!-- Details / Footer -->
                            <div class="pt-3 border-t border-gray-100 flex items-center justify-between">
                                <div class="flex items-center gap-1.5 text-[11px] font-bold text-slate-500 uppercase tracking-wide">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                                    ${unit.start_date ? unit.start_date : (unit.coding_day !== 'Unknown' ? 'Every ' + unit.coding_day : 'No date')}
                                </div>
                                <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                    <i data-lucide="activity" class="w-3.5 h-3.5 text-slate-400"></i>
                                    ${unit.status || 'Unknown'}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `}).join('');
            
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        function updateCodingSummary(units) {
            const counts = { today: 0, tomorrow: 0, past: 0 };
            
            (units || []).forEach(unit => {
                const period = getUnitPeriod(unit);
                if (period === 'today') counts.today++;
                else if (period === 'tomorrow') counts.tomorrow++;
                else if (period === 'past') counts.past++;
            });
            
            const setTxt = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.textContent = val;
            };
            setTxt('todayCodingCount', counts.today);
            setTxt('tomorrowCodingCount', counts.tomorrow);
            setTxt('pastCodingCount', counts.past);
        }
        
        window.currentCodingPeriod = 'today';

        function setCodingPeriod(period) {
            window.currentCodingPeriod = period;
            
            // Update UI
            const periods = ['today', 'tomorrow', 'past'];
            periods.forEach(p => {
                const btn = document.getElementById('btn-' + p + '-coding');
                if (btn) {
                    if (p === period) {
                        btn.classList.remove('text-white/70', 'hover:text-white', 'hover:bg-white/10');
                        btn.classList.add('bg-white', 'text-purple-700');
                    } else {
                        btn.classList.add('text-white/70', 'hover:text-white', 'hover:bg-white/10');
                        btn.classList.remove('bg-white', 'text-purple-700');
                    }
                }
            });
            
            filterCodingUnits();
        }

        function filterCodingUnits() {
            const searchTerm = document.getElementById('codingSearchInput').value.toLowerCase();
            const currentPeriod = window.currentCodingPeriod || 'today';
            
            let filteredUnits = window.originalCodingUnitsData || [];

            // Apply period filter
            filteredUnits = filteredUnits.filter(unit => {
                return getUnitPeriod(unit) === currentPeriod;
            });
            
            // Apply search filter
            if (searchTerm) {
                filteredUnits = filteredUnits.filter(unit => {
                    const searchableText = [
                        unit.plate_number || '',
                        unit.coding_type || '',
                        unit.status || '',
                        unit.description || '',
                        unit.start_date || '',
                        unit.estimated_completion || ''
                    ].join(' ').toLowerCase();
                    
                    return searchableText.includes(searchTerm);
                });
            }
            
            window.currentFilteredCodingUnitsData = filteredUnits;
            renderCodingUnits(filteredUnits);
        }
        
        function clearCodingSearch() {
            document.getElementById('codingSearchInput').value = '';
            filterCodingUnits();
        }
        
        function showCodingError(message, debugInfo = null) {
            const grid = document.getElementById('codingGrid');
            const debugHtml = debugInfo ? `
                <div class="mt-4 p-3 bg-gray-100 rounded-lg text-xs">
                    <h4 class="font-bold text-gray-700 mb-2">Debug Information:</h4>
                    <pre class="text-gray-600 whitespace-pre-wrap">${JSON.stringify(debugInfo, null, 2)}</pre>
                </div>
            ` : '';
            
            grid.innerHTML = `
                <div class="col-span-full text-center py-20">
                    <div class="inline-flex flex-col items-center">
                        <div class="p-4 bg-red-100 rounded-full mb-4">
                            <i data-lucide="alert-circle" class="w-8 h-8 text-red-600"></i>
                        </div>
                        <span class="text-xl text-gray-600 font-semibold mb-2">Error Loading Coding Data</span>
                        <p class="text-sm text-slate-400 mb-4">${message}</p>
                        <div class="flex gap-2">
                            <button onclick="loadCodingUnitsData()" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                <i data-lucide="refresh-cw" class="w-4 h-4 inline mr-2"></i>
                                Retry
                            </button>
                            <button onclick="testCodingUnitsAPI()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i data-lucide="bug" class="w-4 h-4 inline mr-2"></i>
                                Test API
                            </button>
                        </div>
                        ${debugHtml}
                    </div>
                </div>
            `;
            
            // Re-initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
        
        function testCodingUnitsAPI() {
            const grid = document.getElementById('codingGrid');
            grid.innerHTML = `
                <div class="col-span-full text-center py-20">
                    <div class="inline-flex flex-col items-center">
                        <div class="p-4 bg-blue-100 rounded-full mb-4">
                            <i data-lucide="bug" class="w-8 h-8 text-blue-600"></i>
                        </div>
                        <span class="text-xl text-gray-600 font-semibold mb-2">Testing API Connection</span>
                        <p class="text-sm text-slate-400 mb-4">Checking API endpoint...</p>
                        <div class="w-64 bg-gray-200 rounded-full h-2 mb-4">
                            <div class="bg-blue-600 h-2 rounded-full animate-pulse" style="width: 60%"></div>
                        </div>
                    </div>
                </div>
            `;
            
            // Test the API endpoint
            fetch('/api/coding-units')
                .then(response => {
                    return response.text();
                })
                .then(text => {
                    try {
                        const data = JSON.parse(text);
                        showCodingError('API Test Complete - Check Console for Details', {
                            response_status: 'success',
                            data_keys: Object.keys(data),
                            data: data
                        });
                    } catch (parseError) {
                        showCodingError('API Test Complete - JSON Parse Error', {
                            response_status: 'parse_error',
                            raw_response: text.substring(0, 500) + (text.length > 500 ? '...' : ''),
                            parse_error: parseError.message
                        });
                    }
                })
                .catch(error => {
                    showCodingError('API Test Complete - Fetch Error', {
                        response_status: 'fetch_error',
                        error: error.message,
                        stack: error.stack
                    });
                });
        }
        window.showCodingUnitsModal = showCodingUnitsModal;
        window.hideCodingUnitsModal = hideCodingUnitsModal;
        window.loadCodingUnitsData = loadCodingUnitsData;
        window.displayCodingUnitsData = displayCodingUnitsData;
        window.renderCodingUnits = renderCodingUnits;
        window.filterCodingUnits = filterCodingUnits;
        window.showCodingError = showCodingError;

        // Net Income Modal Functions
        function showNetIncomeModal() {
            document.getElementById('netIncomeModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Initialize default period to today
            window.currentIncomePeriod = 'today';
            setIncomePeriod('today');
            
            loadIncomeData();
        }
        
        function hideNetIncomeModal() {
            document.getElementById('netIncomeModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        function loadIncomeData() {
            fetch('/api/net-income-details')
                .then(response => {
                    // Check if response is HTML (error page) or JSON
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('text/html')) {
                        return response.text().then(text => {
                            throw new Error('API returned HTML instead of JSON. This usually means a Laravel error occurred. Check the Laravel logs for details.');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        displayIncomeData(data);
                    } else {
                        showIncomeError(data.message, data.debug_info || null);
                    }
                })
                .catch(error => {
                    showIncomeError('Error loading income data. Please try again.', {
                        fetch_error: error.message,
                        stack: error.stack
                    });
                });
        }
        
        function displayIncomeData(data) {
            const incomeData = data.income_data || [];
            
            // Store original data for filtering
            window.originalIncomeData = incomeData;
            
            // Apply filtering directly via setIncomePeriod
            setIncomePeriod(window.currentIncomePeriod || 'today');
            
            // Re-initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
        
        function renderIncomeData(incomeData) {
            const grid = document.getElementById('incomeGrid');
            if (!grid) return;
            
            if (!incomeData || incomeData.length === 0) {
                grid.innerHTML = `
                    <div class="col-span-full py-12 text-center">
                        <div class="bg-gray-50 rounded-xl p-8 border-2 border-dashed border-gray-200">
                            <i data-lucide="info" class="w-8 h-8 text-gray-300 mx-auto mb-3"></i>
                            <p class="text-slate-500 font-medium font-mono">NO TRANSACTIONS FOUND FOR THIS PERIOD</p>
                        </div>
                    </div>
                `;
                return;
            }

            // Receipt-style list (clean table-like rows)
            grid.classList.remove('grid-cols-1', 'md:grid-cols-2');
            grid.classList.add('grid-cols-1');
            
            grid.innerHTML = `
                <div class="bg-white rounded-xl shadow-md shadow-slate-200/40 border border-gray-200 overflow-hidden font-mono text-sm max-w-4xl mx-auto">
                    <div class="bg-gray-100 px-6 py-3 border-b-2 border-gray-200 flex justify-between text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                        <span>Description / Category</span>
                        <span class="text-right">Amount (₱)</span>
                    </div>
                    <div class="divide-y divide-gray-100">
                        ${incomeData.map(item => `
                            <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors border-l-4 ${item.type === 'income' ? 'border-green-500/20' : 'border-red-500/20'}">
                                <div class="flex items-center gap-4">
                                    <div class="flex-shrink-0 w-8 h-8 rounded bg-gray-50 border border-gray-200 flex items-center justify-center ${item.type === 'income' ? 'text-green-600' : 'text-red-600'}">
                                        <i data-lucide="${item.type === 'income' ? 'arrow-down-left' : 'arrow-up-right'}" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800 tracking-tight">${(item.description || 'Unknown').toUpperCase()}</div>
                                        <div class="flex items-center gap-3 text-[10px] text-slate-400 font-bold mt-0.5">
                                            <span class="text-slate-500">${(item.category || 'GENERAL').toUpperCase()}</span>
                                            <span class="text-gray-300">•</span>
                                            <span>${(item.date || '').split(' ')[0]}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold text-lg ${item.type === 'income' ? 'text-green-600' : 'text-red-600'}">
                                        ${item.type === 'income' ? '+' : '-'} ₱${Math.abs(parseFloat(item.amount) || 0).toLocaleString(undefined, {minimumFractionDigits: 2})}
                                    </div>
                                    <div class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter">${item.source || 'OFFICE'}</div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                    <div class="bg-gray-50 px-6 py-4 border-t-2 border-dashed border-gray-200 text-center">
                        <p class="text-[10px] text-slate-400 uppercase font-bold tracking-[0.2em]">End of transaction list</p>
                    </div>
                </div>
            `;

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
        
        
        function updateIncomeSummary(data) {
            let totalIncome = 0;
            let totalExpenses = 0;
            let breakdown = {
                revenueItems: [],
                maintenanceItems: [],
                officeItems: [],
                maintenanceTotal: 0,
                officeTotal: 0
            };
            
            data.forEach(item => {
                const amount = Math.abs(parseFloat(item.amount) || 0);
                const category = (item.category || '').toLowerCase();
                const type = (item.type || '').toLowerCase();
                const description = (item.description || 'Record').toUpperCase();
                const date = item.date ? new Date(item.date).toLocaleDateString() : '';

                if (item.type === 'income') {
                    totalIncome += amount;
                    breakdown.revenueItems.push({
                        description: description,
                        amount: amount,
                        date: date
                    });
                } else {
                    // Skip coding as per user request
                    if (category.includes('coding') || type === 'coding') {
                        return;
                    }

                    totalExpenses += amount;
                    
                    if (category.includes('maintenance') || type === 'maintenance') {
                        breakdown.maintenanceTotal += amount;
                        breakdown.maintenanceItems.push({
                            description: description,
                            amount: amount,
                            date: date
                        });
                    } else {
                        breakdown.officeTotal += amount;
                        breakdown.officeItems.push({
                            description: description,
                            amount: amount,
                            date: date
                        });
                    }
                }
            });
            
            // Summarize items for Yearly and Monthly views
            if (window.currentIncomePeriod === 'year' || window.currentIncomePeriod === 'month') {
                const summarizeItems = (items) => {
                    const map = {};
                    
                    // Generate dynamic period label
                    let periodLabel = '';
                    if (window.currentIncomePeriod === 'year') {
                        periodLabel = new Date().getFullYear() + ' SUMMARY';
                    } else if (window.currentIncomePeriod === 'month') {
                        const date = new Date();
                        periodLabel = date.toLocaleString('default', { month: 'long', year: 'numeric' }).toUpperCase() + ' SUMMARY';
                    }

                    items.forEach(item => {
                        if (!map[item.description]) {
                            map[item.description] = { 
                                description: item.description, 
                                amount: 0, 
                                date: periodLabel
                            };
                        }
                        map[item.description].amount += item.amount;
                    });
                    return Object.values(map).sort((a, b) => b.amount - a.amount);
                };
                
                breakdown.revenueItems = summarizeItems(breakdown.revenueItems);
                breakdown.maintenanceItems = summarizeItems(breakdown.maintenanceItems);
                breakdown.officeItems = summarizeItems(breakdown.officeItems);
            }
            
            const netIncome = totalIncome - totalExpenses;
            const profitMargin = totalIncome > 0 ? (netIncome / totalIncome) * 100 : 0;
            
            // Helper to format currency
            const fmt = (num) => '₱' + num.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});

            // Update Primary Report Fields
            const safeSet = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.textContent = val;
            };

            safeSet('reportTotalIncome', fmt(totalIncome));
            safeSet('reportTotalIncomePrint', fmt(totalIncome));
            /* Tailwind imports removed to prevent 404s in production */
            safeSet('reportTotalExpenses', fmt(totalExpenses));
            safeSet('reportTotalExpensesPrint', fmt(totalExpenses));
            safeSet('reportMaintenanceTotal', 'Total: ' + fmt(breakdown.maintenanceTotal));
            safeSet('reportGeneralExpensesTotal', 'Total: ' + fmt(breakdown.officeTotal));
            safeSet('reportNetIncome', fmt(netIncome));
            safeSet('reportNetIncomePrint', fmt(netIncome));
            
            const netIncomeEl = document.getElementById('reportNetIncome');
            if (netIncomeEl) {
                netIncomeEl.className = 'text-xl sm:text-2xl font-black leading-none mb-0.5 tracking-tight ' + 
                    (netIncome > 0 ? 'text-emerald-600' : (netIncome < 0 ? 'text-rose-600' : 'text-slate-900'));
            }
            
            const profitMarginEl = document.getElementById('reportProfitMargin');
            if (profitMarginEl) {
                profitMarginEl.textContent = profitMargin.toFixed(1) + '% Margin';
                profitMarginEl.className = 'text-[8px] px-1.5 py-0.5 rounded-full font-bold border ' + 
                    (netIncome > 0 ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : (netIncome < 0 ? 'bg-rose-100 text-rose-800 border-rose-200' : 'bg-slate-100 text-slate-700 border-slate-200'));
            }
            
            safeSet('reportTimestamp', new Date().toLocaleString());
            
            // Helper to render lists
            const renderList = (id, items) => {
                const el = document.getElementById(id);
                if (!el) return;
                
                if (items.length > 0) {
                    el.innerHTML = `
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-white/40 border-b border-slate-200/80 text-[8px] uppercase tracking-widest text-slate-500 font-extrabold">
                                        <th class="px-4 sm:px-6 py-2.5 w-1/4">Date</th>
                                        <th class="px-4 sm:px-6 py-2.5 w-1/2">Description</th>
                                        <th class="px-4 sm:px-6 py-2.5 text-right w-1/4">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200/50 text-xs">
                                    ${items.map(item => `
                                        <tr class="hover:bg-white/50 transition-colors">
                                            <td class="px-4 sm:px-6 py-2 text-[10px] text-slate-500 font-bold uppercase whitespace-nowrap">${item.date}</td>
                                            <td class="px-4 sm:px-6 py-2 font-bold text-slate-800 tracking-tight">${item.description}</td>
                                            <td class="px-4 sm:px-6 py-2 font-black ${id === 'revenueDetailList' ? 'text-emerald-700' : 'text-rose-600'} text-right whitespace-nowrap">${fmt(item.amount)}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    `;
                } else {
                    el.innerHTML = `
                        <div class="px-6 py-6 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            No records found
                        </div>
                    `;
                }
            };

            renderList('revenueDetailList', breakdown.revenueItems);
            renderList('maintenanceDetailList', breakdown.maintenanceItems);
            renderList('officeExpensesDetailList', breakdown.officeItems);
            
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        function renderIncomeData(data) {
            // Grid rendering is now integrated into updateIncomeSummary
            // This function is kept for compatibility with fetchIncomeData flow
        }

        function printReport() {
            const content = document.getElementById('incomeReport').innerHTML;
            const periodLabel = document.getElementById('reportPeriodLabelPrint');
            const period = periodLabel ? periodLabel.textContent : 'TODAY';

            const iframe = document.createElement('iframe');
            iframe.style.position = 'absolute';
            iframe.style.top = '-9999px';
            iframe.style.left = '-9999px';
            iframe.style.width = '0';
            iframe.style.height = '0';
            document.body.appendChild(iframe);

            const doc = iframe.contentWindow.document;
            doc.open();
            doc.write(`<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Report &mdash; ${period}</title>
    <style>
        @page { margin: 0; size: auto; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: #fff; font-family: 'Segoe UI', system-ui, sans-serif; padding: 20mm; color: #111; }
        h1 { text-align: center; font-size: 24px; font-weight: 900; text-transform: uppercase; letter-spacing: .15em; margin-bottom: 4px; }
        .subtitle { text-align: center; font-size: 11px; color: #64748b; font-weight: 700; letter-spacing: .15em; text-transform: uppercase; margin-bottom: 32px; }
        .section-header { display: flex; justify-content: space-between; align-items: center; padding: 10px 20px; border-radius: 6px 6px 0 0; }
        .sub-header { display: flex; justify-content: space-between; background: #f8f8f8; padding: 6px 20px; border-left: 1px solid #eee; border-right: 1px solid #eee; font-size: 9px; font-weight: 900; text-transform: uppercase; letter-spacing: .12em; color: #94a3b8; }
        table { width: 100%; border-collapse: collapse; border: 1px solid #f0f0f0; border-top: none; margin-bottom: 24px; }
        thead tr { background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
        thead th { padding: 8px 20px; font-size: 8px; text-transform: uppercase; letter-spacing: .12em; color: #94a3b8; font-weight: 700; text-align: left; }
        thead th:last-child { text-align: right; }
        tr { page-break-inside: avoid; break-inside: avoid; }
        tbody tr { border-bottom: 1px solid #f8f8f8; }
        td { padding: 8px 20px; font-size: 11px; color: #1e293b; }
        td:last-child { text-align: right; font-weight: 900; white-space: nowrap; }
        .no-records { padding: 16px 20px; text-align: center; font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .1em; border: 1px solid #f0f0f0; border-top: none; margin-bottom: 24px; }
        .footer { text-align: center; margin-top: 40px; padding-top: 16px; border-top: 1px solid #e2e8f0; font-size: 9px; color: #94a3b8; }
        img { max-height: 64px !important; width: auto !important; display: block; margin: 0 auto 8px auto; }
        .print-only { display: block !important; }
        .no-print { display: none !important; }
    </style>
</head>
<body>
    <h1>Financial Report</h1>
    <p class="subtitle">Euro Taxi Management System &mdash; ${period}</p>
    ${content}
    <div class="footer">
        <p>Authenticated Financial Statement &mdash; Generated: ${new Date().toLocaleString()}</p>
    </div>
</body>
</html>`);
            doc.close();
            
            setTimeout(() => {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
                setTimeout(() => { document.body.removeChild(iframe); }, 1000);
            }, 300);
        }
        window.showNetIncomeModal = showNetIncomeModal;
        window.hideNetIncomeModal = hideNetIncomeModal;
        window.loadIncomeData = loadIncomeData;
        window.setIncomePeriod = setIncomePeriod;
        window.showIncomeError = showIncomeError;

        // --- Expenses Modal Functions ---
        function showExpensesModal() {
            document.getElementById('expensesModal').classList.remove('hidden');
            setExpensesPeriod('today');
        }

        function hideExpensesModal() {
            document.getElementById('expensesModal').classList.add('hidden');
        }

        function setExpensesPeriod(period) {
            window.currentExpensesPeriod = period;
            
            const periodLabels = {
                'today': 'Period: TODAY',
                'week': 'Period: THIS WEEK',
                'month': 'Period: THIS MONTH',
                'year': 'Period: THIS YEAR'
            };
            const labelText = periodLabels[period] || 'Period: Custom';
            const labelElPrint = document.getElementById('expensesPeriodLabelPrint');
            if (labelElPrint) labelElPrint.textContent = labelText;

            // Update button styles
            document.querySelectorAll('[id^="btn-"][id$="-expenses"]').forEach(btn => {
                btn.classList.remove('bg-white', 'text-red-700');
                btn.classList.add('text-white/70', 'hover:text-white', 'hover:bg-white/10');
            });
            
            const activeBtn = document.getElementById('btn-' + period + '-expenses');
            if (activeBtn) {
                activeBtn.classList.remove('text-white/70', 'hover:text-white', 'hover:bg-white/10');
                activeBtn.classList.add('bg-white', 'text-red-700');
            }
            
            updateExpensesSummary(period);
        }

        async function updateExpensesSummary(period) {
            try {
                const response = await fetch('/api/net-income-details');
                const result = await response.json();
                
                if (result.success) {
                    const filteredData = filterIncomeByPeriod(result.income_data, period);
                    renderExpensesReport(filteredData);
                }
            } catch (error) {
                console.error("Error fetching expenses data:", error);
            }
        }

        function printExpensesNewTab() {
            const content = document.getElementById('expensesReport').innerHTML;
            const periodLabel = document.getElementById('expensesPeriodLabelPrint');
            const period = periodLabel ? periodLabel.textContent : 'TODAY';

            const iframe = document.createElement('iframe');
            iframe.style.position = 'absolute';
            iframe.style.top = '-9999px';
            iframe.style.left = '-9999px';
            iframe.style.width = '0';
            iframe.style.height = '0';
            document.body.appendChild(iframe);

            const doc = iframe.contentWindow.document;
            doc.open();
            doc.write(`<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Statement &mdash; ${period}</title>
    <style>
        @page { margin: 0; size: auto; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: #fff; font-family: 'Segoe UI', system-ui, sans-serif; padding: 20mm; color: #111; }
        h1 { text-align: center; font-size: 24px; font-weight: 900; text-transform: uppercase; letter-spacing: .15em; margin-bottom: 4px; }
        .subtitle { text-align: center; font-size: 11px; color: #64748b; font-weight: 700; letter-spacing: .15em; text-transform: uppercase; margin-bottom: 32px; }
        .section-header { display: flex; justify-content: space-between; align-items: center; background: #7f1d1d; color: white; padding: 10px 20px; border-radius: 6px 6px 0 0; }
        .section-header span { font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: .08em; }
        .sub-header { display: flex; justify-content: space-between; background: #f8f8f8; padding: 6px 20px; border-left: 1px solid #eee; border-right: 1px solid #eee; font-size: 9px; font-weight: 900; text-transform: uppercase; letter-spacing: .12em; color: #94a3b8; }
        .sub-total { color: #dc2626; }
        table { width: 100%; border-collapse: collapse; border: 1px solid #f0f0f0; border-top: none; margin-bottom: 24px; }
        thead tr { background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
        thead th { padding: 8px 20px; font-size: 8px; text-transform: uppercase; letter-spacing: .12em; color: #94a3b8; font-weight: 700; text-align: left; }
        thead th:last-child { text-align: right; }
        tr { page-break-inside: avoid; break-inside: avoid; }
        tbody tr { border-bottom: 1px solid #f8f8f8; }
        tbody tr:hover { background: #fafafa; }
        td { padding: 8px 20px; font-size: 11px; color: #1e293b; }
        td.date { color: #94a3b8; font-weight: 700; font-size: 9px; text-transform: uppercase; }
        td.amount { text-align: right; font-weight: 900; color: #dc2626; white-space: nowrap; }
        .no-records { padding: 16px 20px; text-align: center; font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .1em; border: 1px solid #f0f0f0; border-top: none; margin-bottom: 24px; }
        .footer { text-align: center; margin-top: 40px; padding-top: 16px; border-top: 1px solid #e2e8f0; font-size: 9px; color: #94a3b8; }
        img { max-height: 64px !important; width: auto !important; display: block; margin: 0 auto 8px auto; }
        .print-only { display: block !important; }
        .no-print { display: none !important; }
    </style>
</head>
<body>
    <h1>Expense Statement</h1>
    <p class="subtitle">Euro Taxi Management System &mdash; ${period}</p>
    ${content}
    <div class="footer">
        <p>Authenticated Expense Summary &mdash; Generated: ${new Date().toLocaleString()}</p>
    </div>
</body>
</html>`);
            doc.close();
            
            setTimeout(() => {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
                setTimeout(() => { document.body.removeChild(iframe); }, 1000);
            }, 300);
        }

        function renderExpensesReport(data) {
            let breakdown = {
                maintenanceTotal: 0,
                maintenanceItems: [],
                officeTotal: 0,
                officeItems: []
            };

            data.forEach(item => {
                const amount = parseFloat(item.amount) || 0;
                const description = item.description || 'No Description';
                const date = (item.date || '').split(' ')[0];

                if (item.type === 'maintenance') {
                    breakdown.maintenanceTotal += amount;
                    breakdown.maintenanceItems.push({ description, amount, date });
                } else if (item.type === 'expense') {
                    breakdown.officeTotal += amount;
                    breakdown.officeItems.push({ description, amount, date });
                }
            });

            // Summarize items for Yearly and Monthly views
            if (window.currentExpensesPeriod === 'year' || window.currentExpensesPeriod === 'month') {
                const summarizeItems = (items) => {
                    const map = {};
                    
                    let periodLabel = '';
                    if (window.currentExpensesPeriod === 'year') {
                        periodLabel = new Date().getFullYear() + ' SUMMARY';
                    } else if (window.currentExpensesPeriod === 'month') {
                        const date = new Date();
                        periodLabel = date.toLocaleString('default', { month: 'long', year: 'numeric' }).toUpperCase() + ' SUMMARY';
                    }

                    items.forEach(item => {
                        if (!map[item.description]) {
                            map[item.description] = { 
                                description: item.description, 
                                amount: 0, 
                                date: periodLabel
                            };
                        }
                        map[item.description].amount += item.amount;
                    });
                    return Object.values(map).sort((a, b) => b.amount - a.amount);
                };
                
                breakdown.maintenanceItems = summarizeItems(breakdown.maintenanceItems);
                breakdown.officeItems = summarizeItems(breakdown.officeItems);
            }

            const totalExpenses = breakdown.maintenanceTotal + breakdown.officeTotal;
            const fmt = (num) => '₱' + num.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});

            const safeSet = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.textContent = val;
            };

            safeSet('expensesTotalValue', fmt(totalExpenses));
            safeSet('expensesTotalValuePrint', fmt(totalExpenses));
            safeSet('expensesMaintenanceTotal', fmt(breakdown.maintenanceTotal));
            safeSet('expensesMaintenanceTotalPrint', fmt(breakdown.maintenanceTotal));
            safeSet('expensesOfficeTotal', fmt(breakdown.officeTotal));
            safeSet('expensesOfficeTotalPrint', fmt(breakdown.officeTotal));
            safeSet('expensesTimestamp', new Date().toLocaleString());

            const renderList = (id, items) => {
                const el = document.getElementById(id);
                if (!el) return;
                
                if (items.length > 0) {
                    el.innerHTML = `
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200 text-[9px] uppercase tracking-widest text-slate-500">
                                    <th class="px-6 py-2 font-bold w-1/4 border-r border-gray-200">Date</th>
                                    <th class="px-6 py-2 font-bold w-1/2 border-r border-gray-200">Description</th>
                                    <th class="px-6 py-2 font-bold text-right w-1/4">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                ${items.map(item => `
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-2.5 text-[10px] text-slate-500 font-bold uppercase whitespace-nowrap border-r border-gray-100">${item.date}</td>
                                        <td class="px-6 py-2.5 text-[10px] font-bold text-gray-800 tracking-tight border-r border-gray-100">${item.description}</td>
                                        <td class="px-6 py-2.5 text-[11px] font-bold text-red-600 text-right whitespace-nowrap">${fmt(item.amount)}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    `;
                } else {
                    el.innerHTML = `
                        <div class="py-6 w-full flex flex-col items-center justify-center text-slate-400">
                            <span class="text-[10px] font-bold uppercase tracking-widest bg-gray-50 px-4 py-1.5 rounded-full ring-1 ring-slate-900/5">No records found for this period</span>
                        </div>
                    `;
                }
            };

            renderList('expensesMaintenanceList', breakdown.maintenanceItems);
            renderList('expensesOfficeList', breakdown.officeItems);
        }
        window.showExpensesModal = showExpensesModal;
        window.hideExpensesModal = hideExpensesModal;
        window.setExpensesPeriod = setExpensesPeriod;
        window.renderExpensesReport = renderExpensesReport;

        
        function filterIncomeByPeriod(data, period) {
            // Get local date in YYYY-MM-DD format
            const today = new Date();
            today.setHours(0, 0, 0, 0); // Set to local midnight
            
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            const todayStr = `${year}-${month}-${day}`;
            
            switch(period) {
                case 'today':
                    return data.filter(item => {
                        const itemDateStr = (item.date || '').split(' ')[0];
                        return itemDateStr === todayStr;
                    });
                    
                case 'week':
                    const weekStart = new Date(today);
                    weekStart.setDate(today.getDate() - today.getDay());
                    const weekEnd = new Date(weekStart);
                    weekEnd.setDate(weekStart.getDate() + 6);
                    weekEnd.setHours(23, 59, 59, 999);
                    
                    return data.filter(item => {
                        const itemDateStr = (item.date || '').split(' ')[0];
                        const itemDate = new Date(itemDateStr + 'T00:00:00');
                        return itemDate >= weekStart && itemDate <= weekEnd;
                    });
                    
                case 'month':
                    const monthStart = new Date(today.getFullYear(), today.getMonth(), 1);
                    const monthEnd = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                    monthEnd.setHours(23, 59, 59, 999);
                    
                    return data.filter(item => {
                        const itemDateStr = (item.date || '').split(' ')[0];
                        const itemDate = new Date(itemDateStr + 'T00:00:00');
                        return itemDate >= monthStart && itemDate <= monthEnd;
                    });
                    
                case 'year':
                    const yearStart = new Date(today.getFullYear(), 0, 1);
                    const yearEnd = new Date(today.getFullYear(), 11, 31);
                    yearEnd.setHours(23, 59, 59, 999);
                    
                    return data.filter(item => {
                        const itemDateStr = (item.date || '').split(' ')[0];
                        const itemDate = new Date(itemDateStr + 'T00:00:00');
                        return itemDate >= yearStart && itemDate <= yearEnd;
                    });
                    
                default:
                    return data;
            }
        }
        
        function setIncomePeriod(period) {
            window.currentIncomePeriod = period;
            
            // Update labels
            const periodLabels = {
                'today': 'Period: TODAY',
                'week': 'Period: THIS WEEK',
                'month': 'Period: THIS MONTH',
                'year': 'Period: THIS YEAR'
            };
            const labelText = periodLabels[period] || 'Period: Custom';
            const labelElPrint = document.getElementById('reportPeriodLabelPrint');
            if (labelElPrint) labelElPrint.textContent = labelText;

            // Update button styles
            document.querySelectorAll('[id^="btn-"][id$="-income"]').forEach(btn => {
                btn.classList.remove('bg-gradient-to-r', 'from-indigo-600', 'to-violet-600', 'text-white', 'font-black', 'shadow-sm', 'bg-white', 'text-emerald-800', 'text-green-700');
                btn.classList.add('text-slate-300', 'font-bold', 'hover:text-white', 'hover:bg-white/10');
            });
            
            const activeBtn = document.getElementById('btn-' + period + '-income');
            if (activeBtn) {
                activeBtn.classList.remove('text-slate-300', 'hover:text-white', 'hover:bg-white/10');
                activeBtn.classList.add('bg-gradient-to-r', 'from-indigo-600', 'to-violet-600', 'text-white', 'font-black', 'shadow-sm');
            }
            
            // Re-apply filters directly
            const filtered = filterIncomeByPeriod(window.originalIncomeData || [], period);
            updateIncomeSummary(filtered);
        }
        
        function showIncomeError(message, debugInfo = null) {
            const grid = document.getElementById('incomeGrid');
            const debugHtml = debugInfo ? `
                <div class="mt-4 p-3 bg-gray-100 rounded-lg text-xs">
                    <h4 class="font-bold text-gray-700 mb-2">Debug Information:</h4>
                    <pre class="text-gray-600 whitespace-pre-wrap">${JSON.stringify(debugInfo, null, 2)}</pre>
                </div>
            ` : '';
            
            grid.innerHTML = `
                <div class="col-span-full text-center py-20">
                    <div class="inline-flex flex-col items-center">
                        <div class="p-4 bg-red-100 rounded-full mb-4">
                            <i data-lucide="alert-circle" class="w-8 h-8 text-red-600"></i>
                        </div>
                        <span class="text-xl text-gray-600 font-semibold mb-2">Error Loading Income Data</span>
                        <p class="text-sm text-slate-400 mb-4">${message}</p>
                        <div class="flex gap-2">
                            <button onclick="loadIncomeData()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <i data-lucide="refresh-cw" class="w-4 h-4 inline mr-2"></i>
                                Retry
                            </button>
                            <button onclick="testIncomeAPI()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i data-lucide="bug" class="w-4 h-4 inline mr-2"></i>
                                Test API
                            </button>
                        </div>
                        ${debugHtml}
                    </div>
                </div>
            `;
            
            // Re-initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
        
        function testIncomeAPI() {
            const grid = document.getElementById('incomeGrid');
            grid.innerHTML = `
                <div class="col-span-full text-center py-20">
                    <div class="inline-flex flex-col items-center">
                        <div class="p-4 bg-blue-100 rounded-full mb-4">
                            <i data-lucide="bug" class="w-8 h-8 text-blue-600"></i>
                        </div>
                        <span class="text-xl text-gray-600 font-semibold mb-2">Testing API Connection</span>
                        <p class="text-sm text-slate-400 mb-4">Checking API endpoint...</p>
                        <div class="w-64 bg-gray-200 rounded-full h-2 mb-4">
                            <div class="bg-blue-600 h-2 rounded-full animate-pulse" style="width: 60%"></div>
                        </div>
                    </div>
                </div>
            `;
            
            // Test the API endpoint
            fetch('/api/net-income-details')
                .then(response => {
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('text/html')) {
                        return response.text().then(text => {
                            throw new Error('API returned HTML instead of JSON. This usually means a Laravel error occurred. Response: ' + text.substring(0, 200) + '...');
                        });
                    }
                    
                    return response.text();
                })
                .then(text => {
                    if (text.trim().startsWith('<')) {
                        throw new Error('API returned HTML instead of JSON. Response starts with: ' + text.substring(0, 100) + '...');
                    }
                    
                    try {
                        const data = JSON.parse(text);
                        showIncomeError('API Test Complete - Check Console for Details', {
                            response_status: 'success',
                            data_keys: Object.keys(data),
                            data: data
                        });
                    } catch (parseError) {
                        showIncomeError('API Test Complete - JSON Parse Error', {
                            response_status: 'parse_error',
                            raw_response: text.substring(0, 500) + (text.length > 500 ? '...' : ''),
                            parse_error: parseError.message
                        });
                    }
                })
                .catch(error => {
                    showIncomeError('API Test Complete - Fetch Error', {
                        response_status: 'fetch_error',
                        error: error.message,
                        stack: error.stack
                    });
                });
        }

        // Daily Boundary Collection Modal Functions
        function showDailyBoundaryModal() {
            document.getElementById('dailyBoundaryModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Set default date to today if not set
            const dateInput = document.getElementById('boundaryDateFilter');
            if (dateInput && !dateInput.value) {
                dateInput.value = new Date().toISOString().split('T')[0];
            }
            
            loadBoundaryCollections();
        }
        
        function hideDailyBoundaryModal() {
            document.getElementById('dailyBoundaryModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        function loadBoundaryCollections() {
            const date = document.getElementById('boundaryDateFilter').value;
            const url = `/api/daily-boundary-collections${date ? '?date=' + date : ''}`;
            
            fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    return response.json().then(data => {
                        if (!response.ok) {
                            throw new Error(data.message || `Server error ${response.status}`);
                        }
                        return data;
                    }).catch(err => {
                        if (!response.ok) {
                            throw new Error(`HTTP Error ${response.status}: ${response.statusText}`);
                        }
                        throw err;
                    });
                })
                .then(data => {
                    if (data && data.success) {
                        displayBoundaryCollections(data);
                    } else {
                        showBoundaryError(data ? data.message : 'Unknown error');
                    }
                })
                .catch(error => {
                    showBoundaryError(error.message || 'Error loading boundary collections. Please try again.');
                });
        }
        
        function displayBoundaryCollections(data) {
            const grid = document.getElementById('boundaryGrid');
            const collections = data.collections || [];
            const stats = data.stats || {};
            
            // Update summary stats with new logic (amounts for Yesterday and Monthly)
            document.getElementById('totalBoundaryCount').textContent = stats.total_today || 0;
            document.getElementById('uniqueUnitsCount').textContent = '₱' + (stats.amount_yesterday || 0).toLocaleString();
            document.getElementById('uniqueDriversCount').textContent = '₱' + (stats.amount_monthly || 0).toLocaleString();
            document.getElementById('totalBoundaryAmount').textContent = '₱' + (stats.total_yearly_amount || 0).toLocaleString();
            
            // Store original data for filtering and sync with date input
            window.originalBoundaryData = collections;
            
            const dateInput = document.getElementById('boundaryDateFilter');
            if (dateInput && stats.filter_date) {
                dateInput.value = stats.filter_date;
            }

            window.lastFetchedBoundaryDate = stats.filter_date;
            
            // Re-apply current search filter starting from the new background data
            filterBoundaryCollections();
            
            // Re-initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
        
        function renderBoundaryCollections(collections) {
            const grid = document.getElementById('boundaryGrid');
            
            if (collections.length === 0) {
                grid.innerHTML = `
                    <div class="col-span-full text-center py-20">
                        <div class="inline-flex flex-col items-center">
                            <div class="w-14 h-14 rounded-2xl bg-white/70 backdrop-blur-sm border border-slate-200/60 flex items-center justify-center mb-3 text-slate-400 shadow-sm">
                                <i data-lucide="search-x" class="w-7 h-7"></i>
                            </div>
                            <span class="text-base font-bold text-slate-700 mb-1">No boundary collections found</span>
                            <p class="text-xs text-slate-400">Try adjusting your search criteria or date filter</p>
                        </div>
                    </div>
                `;
                if (typeof lucide !== 'undefined') lucide.createIcons();
                return;
            }
            
            grid.innerHTML = collections.map(collection => `
                <div class="bg-[#f0fdf4]/35 hover:bg-[#f0fdf4]/60 rounded-2xl border border-emerald-300/60 shadow-sm hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 relative group overflow-hidden cursor-default min-h-[120px] flex flex-col justify-between p-3.5">
                    
                    <!-- Card Header -->
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <div class="w-7 h-7 rounded-lg bg-white/40 flex items-center justify-center flex-shrink-0 text-emerald-700 shadow-xs border border-white/50">
                                <i data-lucide="car" class="w-3.5 h-3.5"></i>
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs sm:text-sm font-black text-slate-900 truncate tracking-tight">${collection.plate_number}</h4>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none">${collection.time || 'N/A'}</span>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <div class="text-xs sm:text-sm font-black text-emerald-700 leading-tight">₱${collection.boundary_amount ? Number(collection.boundary_amount).toLocaleString() : '0'}</div>
                            <span class="px-2 py-0.5 text-[8px] font-extrabold rounded-full bg-emerald-100/90 text-emerald-800 border border-emerald-300 uppercase tracking-wider inline-block mt-0.5">
                                COLLECTED
                            </span>
                        </div>
                    </div>
                    
                    <!-- Driver Details Ribbon (Translucent) -->
                    <div class="flex items-center justify-between py-1.5 px-2 bg-white/35 border border-white/50 rounded-xl mb-2">
                        <div class="flex items-center gap-1.5 min-w-0 flex-1">
                            <i data-lucide="user" class="w-3.5 h-3.5 text-slate-500 flex-shrink-0"></i>
                            <span class="text-xs font-bold text-slate-800 truncate">${collection.driver_name || 'Unassigned Driver'}</span>
                        </div>
                        <span class="text-[9px] font-bold text-slate-500 uppercase tracking-tight flex-shrink-0 pl-2">${collection.date || ''}</span>
                    </div>

                    <!-- Card Footer -->
                    <div class="flex items-center justify-between text-[9px] font-bold text-slate-500 uppercase tracking-tight pt-1 border-t border-slate-300/30">
                        <span class="flex items-center gap-1">
                            <i data-lucide="map-pin" class="w-3 h-3 text-emerald-600"></i>
                            ${collection.location || 'Main Office'}
                        </span>
                        <span class="flex items-center gap-1 text-emerald-700 font-extrabold">
                            <i data-lucide="check-circle-2" class="w-3 h-3 text-emerald-600"></i>
                            Verified
                        </span>
                    </div>
                </div>
            `).join('');

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
        
        function filterBoundaryCollections() {
            const searchTerm = document.getElementById('boundarySearchInput').value.toLowerCase();
            const dateFilter = document.getElementById('boundaryDateFilter').value;
            
            // Check if we need to re-fetch (if date changed)
            if (window.lastFetchedBoundaryDate !== dateFilter) {
                window.lastFetchedBoundaryDate = dateFilter;
                loadBoundaryCollections();
                return;
            }

            let filteredCollections = window.originalBoundaryData || [];
            
            // Apply search filter
            if (searchTerm) {
                filteredCollections = filteredCollections.filter(collection => {
                    const searchableText = [
                        collection.plate_number || '',
                        collection.plate_number || '',
                        collection.driver_name || '',
                        collection.boundary_amount ? collection.boundary_amount.toString() : '',
                        collection.date || '',
                        collection.time || '',
                        collection.location || ''
                    ].join(' ').toLowerCase();
                    
                    return searchableText.includes(searchTerm);
                });
            }
            
            window.currentFilteredBoundaryData = filteredCollections;
            renderBoundaryCollections(filteredCollections);
        }
        
        function clearBoundarySearch() {
            document.getElementById('boundarySearchInput').value = '';
            document.getElementById('boundaryDateFilter').value = '';
            filterBoundaryCollections();
        }
        
        function showBoundaryError(message) {
            const grid = document.getElementById('boundaryGrid');
            grid.innerHTML = `
                <div class="col-span-full text-center py-20">
                    <div class="inline-flex flex-col items-center">
                        <div class="p-4 bg-red-100 rounded-full mb-4">
                            <i data-lucide="alert-circle" class="w-8 h-8 text-red-600"></i>
                        </div>
                        <span class="text-xl text-gray-600 font-semibold mb-2">Error Loading Collections</span>
                        <p class="text-sm text-slate-400 mb-4">${message}</p>
                        <button onclick="loadBoundaryCollections()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <i data-lucide="refresh-cw" class="w-4 h-4 inline mr-2"></i>
                            Retry
                        </button>
                    </div>
                </div>
            `;
        }
        window.showDailyBoundaryModal = showDailyBoundaryModal;
        window.hideDailyBoundaryModal = hideDailyBoundaryModal;
        window.loadBoundaryCollections = loadBoundaryCollections;
        window.displayBoundaryCollections = displayBoundaryCollections;
        window.filterBoundaryCollections = filterBoundaryCollections;
        window.clearBoundarySearch = clearBoundarySearch;
        window.showBoundaryError = showBoundaryError;

        // Units Modal Functions
        function showUnitsModal() {
            const modal = document.getElementById('unitsModal');
            const grid = document.getElementById('unitsGrid');
            
            if (modal && grid) {
                modal.classList.remove('hidden');
                loadUnitsData();
            }
        }

        function hideUnitsModal() {
            const modal = document.getElementById('unitsModal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        function loadUnitsData() {
            const grid = document.getElementById('unitsGrid');
            
            // Show loading state
            grid.innerHTML = `
                <div class="col-span-full text-center py-20">
                    <div class="inline-flex flex-col items-center">
                        <div class="animate-spin rounded-full h-16 w-16 border-4 border-blue-600 border-t-transparent mb-6"></div>
                        <span class="text-xl text-gray-600 font-semibold mb-2">Loading units data...</span>
                        <p class="text-sm text-slate-400">Please wait while we fetch your fleet information</p>
                    </div>
                </div>
            `;
            
            fetch('/api/units-overview', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    return response.json().then(data => {
                        if (!response.ok) {
                            throw new Error(data.message || `Server error ${response.status}`);
                        }
                        return data;
                    }).catch(err => {
                        if (!response.ok) {
                            throw new Error(`HTTP Error ${response.status}: ${response.statusText}`);
                        }
                        throw err;
                    });
                })
                .then(data => {
                    if (data.success) {
                        displayUnitsData(data);
                    } else {
                        throw new Error(data.message || 'Unknown error occurred');
                    }
                })
                .catch(error => {
                    console.error('Error loading units:', error);
                    grid.innerHTML = `
                        <div class="col-span-full text-center py-20">
                            <div class="inline-flex flex-col items-center">
                                <div class="p-4 bg-red-100 rounded-full mb-4">
                                    <i data-lucide="alert-circle" class="w-8 h-8 text-red-600"></i>
                                </div>
                                <span class="text-xl text-red-600 font-semibold mb-2">Error Loading Units</span>
                                <p class="text-sm text-slate-400 mb-4">${error.message}</p>
                                <button onclick="loadUnitsData()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i>
                                    Try Again
                                </button>
                            </div>
                        </div>
                    `;
                    
                    // Re-initialize Lucide icons for error state
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                });
        }

        function renderUnits(units, statusColors, statusIcons, statusGradients) {
            const grid = document.getElementById('unitsGrid');
            
            if (units.length === 0) {
                grid.innerHTML = `
                    <div class="col-span-full text-center py-20">
                        <div class="inline-flex flex-col items-center">
                            <div class="w-14 h-14 rounded-2xl bg-white/70 backdrop-blur-sm border border-slate-200/60 flex items-center justify-center mb-3 text-slate-400 shadow-sm">
                                <i data-lucide="search-x" class="w-7 h-7"></i>
                            </div>
                            <span class="text-base font-bold text-slate-700 mb-1">No matching units found</span>
                            <p class="text-xs text-slate-400">Try adjusting your search criteria or status filter</p>
                        </div>
                    </div>
                `;
                if (typeof lucide !== 'undefined') lucide.createIcons();
                return;
            }
            
            const statusCardStyles = {
                'active': 'bg-emerald-50/30 hover:bg-emerald-50/55 border-emerald-400/60 shadow-sm',
                'vacant': 'bg-amber-50/30 hover:bg-amber-50/55 border-amber-400/60 shadow-sm',
                'maintenance': 'bg-rose-50/30 hover:bg-rose-50/55 border-rose-400/60 shadow-sm',
                'coding': 'bg-yellow-50/30 hover:bg-yellow-50/55 border-yellow-400/60 shadow-sm',
                'retired': 'bg-slate-100/30 hover:bg-slate-100/55 border-slate-400/60 shadow-sm'
            };
            
            const statusBadgeStyles = {
                'active': 'bg-emerald-100/90 text-emerald-800 border-emerald-300',
                'vacant': 'bg-amber-100/90 text-amber-800 border-amber-300',
                'maintenance': 'bg-rose-100/90 text-rose-800 border-rose-300',
                'coding': 'bg-yellow-100/90 text-yellow-800 border-yellow-300',
                'retired': 'bg-slate-200/90 text-slate-700 border-slate-300'
            };

            grid.innerHTML = units.map(unit => {
                const isVacant = (unit.status === 'vacant') || (unit.status === 'active' && !unit.driver1_name && !unit.driver2_name);
                const effectiveStatusKey = isVacant ? 'vacant' : (unit.status || 'active').toLowerCase();
                const cardStyle = statusCardStyles[effectiveStatusKey] || statusCardStyles['vacant'];
                const badgeStyle = statusBadgeStyles[effectiveStatusKey] || statusBadgeStyles['vacant'];
                const displayStatusText = isVacant ? 'VACANT' : (unit.status || 'ACTIVE');

                return `
                <div class="${cardStyle} rounded-2xl border hover:shadow-xl hover:-translate-y-1 transition-all duration-200 relative group overflow-hidden cursor-pointer min-h-[118px] flex flex-col justify-between p-3.5" onclick="window.location.href='/units?id=${unit.id}'">
                    
                    <!-- Hover Driver Overlay -->
                    <div class="absolute inset-0 bg-slate-950/95 opacity-0 group-hover:opacity-100 transition-all duration-200 flex flex-col items-center justify-center z-20 pointer-events-none p-3 backdrop-blur-sm">
                        <p class="text-amber-400 text-[9px] font-extrabold uppercase tracking-[0.2em] mb-2 flex items-center gap-1">
                            <i data-lucide="user-check" class="w-3 h-3"></i>
                            Assigned Drivers
                        </p>
                        ${(unit.driver1_name || unit.driver2_name) ? `
                            ${unit.driver1_name ? `
                            <div class="flex items-center gap-2 bg-white/10 rounded-xl px-2.5 py-1.5 w-full border border-white/10 shadow-lg mb-1.5">
                                <div class="w-6 h-6 rounded-lg bg-blue-500 flex items-center justify-center flex-shrink-0 text-white font-extrabold text-[9px] shadow-sm">
                                    P1
                                </div>
                                <div class="min-w-0 flex-1 text-left">
                                    <p class="text-[7px] text-blue-300 font-bold uppercase tracking-widest leading-none mb-0.5">Primary Driver</p>
                                    <p class="text-white text-[11px] font-bold leading-tight truncate tracking-wide">${unit.driver1_name}</p>
                                </div>
                            </div>
                            ` : ''}
                            ${unit.driver2_name ? `
                            <div class="flex items-center gap-2 bg-white/10 rounded-xl px-2.5 py-1.5 w-full border border-white/10 shadow-lg">
                                <div class="w-6 h-6 rounded-lg bg-purple-500 flex items-center justify-center flex-shrink-0 text-white font-extrabold text-[9px] shadow-sm">
                                    P2
                                </div>
                                <div class="min-w-0 flex-1 text-left">
                                    <p class="text-[7px] text-purple-300 font-bold uppercase tracking-widest leading-none mb-0.5">Secondary Driver</p>
                                    <p class="text-white text-[11px] font-bold leading-tight truncate tracking-wide">${unit.driver2_name}</p>
                                </div>
                            </div>
                            ` : ''}
                        ` : `
                            <div class="flex flex-col items-center justify-center h-full text-center">
                                <div class="w-7 h-7 rounded-xl bg-slate-800 text-slate-400 flex items-center justify-center mb-1.5 border border-slate-700">
                                    <i data-lucide="user-x" class="w-4 h-4 text-slate-400"></i>
                                </div>
                                <p class="text-slate-300 text-[10px] font-bold uppercase tracking-wider">No Assigned Driver</p>
                                <span class="text-[8px] text-slate-500">Available for assignment</span>
                            </div>
                        `}
                    </div>

                    <!-- Card Header -->
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <div class="w-7 h-7 rounded-lg bg-white/40 flex items-center justify-center flex-shrink-0 text-slate-700 shadow-xs border border-white/50">
                                <i data-lucide="car" class="w-3.5 h-3.5"></i>
                            </div>
                            <h4 class="text-xs sm:text-sm font-black text-slate-900 truncate tracking-tight">${unit.plate_number}</h4>
                        </div>
                        <span class="px-2.5 py-0.5 text-[9px] font-extrabold rounded-full ${badgeStyle} uppercase border tracking-wider">
                            ${displayStatusText}
                        </span>
                    </div>
                    
                    <!-- Essential Metrics Ribbon (High Transparency) -->
                    <div class="grid grid-cols-2 gap-1.5 text-center py-1.5 px-2 bg-white/35 border border-white/50 rounded-xl mb-2">
                        <div class="text-left pl-1">
                            <div class="text-[8px] text-slate-500 uppercase font-bold tracking-wider leading-none mb-0.5">Total Coll.</div>
                            <div class="text-xs font-black text-emerald-700">₱${unit.total_boundary ? unit.total_boundary.toLocaleString() : '0'}</div>
                        </div>
                        <div class="text-right pr-1">
                            <div class="text-[8px] text-slate-500 uppercase font-bold tracking-wider leading-none mb-0.5">ROI Rate</div>
                            <div class="text-xs font-black ${unit.roi_percentage >= 100 ? 'text-blue-700' : 'text-slate-800'}">${unit.roi_percentage ? unit.roi_percentage.toFixed(1) : '0.0'}%</div>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="flex items-center justify-between text-[9px] font-bold text-slate-500 uppercase tracking-tight pt-1 border-t border-slate-300/30">
                        <span>ID: ${unit.plate_number || 'N/A'}</span>
                        <span class="${unit.today_boundary > 0 ? 'text-blue-700 font-extrabold' : 'text-slate-400'}">
                            ${unit.today_boundary > 0 ? `+₱${unit.today_boundary.toLocaleString()}` : 'No Daily'}
                        </span>
                    </div>
                </div>
                `;
            }).join('');
            
            // Re-initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        function displayUnitsData(data) {
            const grid = document.getElementById('unitsGrid');
            const units = data.units || [];
            const stats = data.stats || {};
            
            // Store original units data for filtering
            window.originalUnitsData = units;
            window.currentFilteredUnits = units;
            
            // Update summary stats with new logic (Vacant vs Active focus)
            document.getElementById('totalUnitsCount').textContent = stats.total_units || 0;
            document.getElementById('activeUnitsCount').textContent = stats.vacant_units || 0;
            document.getElementById('roiUnitsCount').textContent = stats.active_units || 0;
            document.getElementById('avgRoiCount').textContent = stats.avg_roi ? stats.avg_roi.toFixed(1) + '%' : '0%';
            
            // Remove any existing database data indicators to save space
            const indicator = grid.parentNode.querySelector('.data-source-indicator');
            if (indicator) indicator.remove();
            
            const statusColors = {
                'active': 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'maintenance': 'bg-rose-50 text-rose-700 border-rose-200',
                'coding': 'bg-amber-50 text-amber-700 border-amber-200',
                'retired': 'bg-slate-100 text-slate-700 border-slate-200'
            };
            
            const statusIcons = {
                'active': '<i data-lucide="check-circle" class="w-3 h-3"></i>',
                'maintenance': '<i data-lucide="wrench" class="w-3 h-3"></i>',
                'coding': '<i data-lucide="code" class="w-3 h-3"></i>',
                'retired': '<i data-lucide="x-circle" class="w-3 h-3"></i>'
            };
            
            const statusGradients = {
                'active': 'from-green-500 to-emerald-600',
                'maintenance': 'from-red-500 to-rose-600', 
                'coding': 'from-yellow-500 to-amber-600',
                'retired': 'from-gray-500 to-slate-600'
            };
            
            renderUnits(units, statusColors, statusIcons, statusGradients);
            
            // Re-initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
        
        // Search and Filter Functions
        window.currentUnitStatusFilter = 'all';

        function setUnitStatusFilter(status) {
            window.currentUnitStatusFilter = status;
            
            // Update UI with 21st.dev style active buttons
            const statusBtns = ['all', 'active', 'maintenance', 'coding'];
            statusBtns.forEach(s => {
                const btn = document.getElementById('btn-' + s + '-units');
                if (btn) {
                    if (s === status) {
                        btn.className = "px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all duration-200 bg-amber-500 text-slate-950 shadow-md shadow-amber-500/20";
                    } else {
                        btn.className = "px-3.5 py-1.5 text-xs font-semibold rounded-lg transition-all duration-200 text-slate-400 hover:text-white hover:bg-slate-800/80";
                    }
                }
            });
            
            filterUnits();
        }

        function filterUnits() {
            const searchTerm = document.getElementById('unitSearchInput').value.toLowerCase();
            const currentStatus = window.currentUnitStatusFilter || 'all';
            
            let filteredUnits = window.originalUnitsData || [];
            
            // Apply status filter
            if (currentStatus !== 'all') {
                filteredUnits = filteredUnits.filter(unit => (unit.status || '').toLowerCase() === currentStatus);
            }
            
            // Apply search filter
            if (searchTerm) {
                filteredUnits = filteredUnits.filter(unit => {
                    const searchableText = [
                        unit.plate_number || '',
                        unit.status || '',
                        unit.driver_name || '',
                        unit.performance_rating || '',
                        unit.roi_percentage >= 100 ? 'excellent profitable' : 
                        unit.roi_percentage >= 75 ? 'good' : 
                        unit.roi_percentage >= 50 ? 'average growing' : 'growing investment',
                        unit.boundary_rate ? unit.boundary_rate.toString() : '',
                        unit.total_boundary ? unit.total_boundary.toString() : '',
                        unit.today_boundary ? unit.today_boundary.toString() : '',
                        unit.purchase_cost ? unit.purchase_cost.toString() : ''
                    ].join(' ').toLowerCase();
                    
                    return searchableText.includes(searchTerm);
                });
            }
            
            window.currentFilteredUnits = filteredUnits;
            
            // Re-render with current filters
            const statusColors = {
                'active': 'bg-green-100 text-green-800 border-green-200',
                'maintenance': 'bg-red-100 text-red-800 border-red-200',
                'coding': 'bg-yellow-100 text-yellow-800 border-yellow-200',
                'retired': 'bg-gray-100 text-gray-800 border-gray-200'
            };
            
            const statusIcons = {
                'active': '<i data-lucide="check-circle" class="w-3 h-3"></i>',
                'maintenance': '<i data-lucide="wrench" class="w-3 h-3"></i>',
                'coding': '<i data-lucide="code" class="w-3 h-3"></i>',
                'retired': '<i data-lucide="x-circle" class="w-3 h-3"></i>'
            };
            
            const statusGradients = {
                'active': 'from-green-500 to-emerald-600',
                'maintenance': 'from-red-500 to-rose-600', 
                'coding': 'from-yellow-500 to-amber-600',
                'retired': 'from-gray-500 to-slate-600'
            };
            
            renderUnits(filteredUnits, statusColors, statusIcons, statusGradients);
        }
        
        function filterByStatus(status) {
            // Update active filter tag
            document.querySelectorAll('.filter-tag').forEach(tag => {
                tag.classList.remove('active', 'bg-white/40');
                if (tag.dataset.status === status) {
                    tag.classList.add('active', 'bg-white/40');
                }
            });
            
            // Apply filter
            filterUnits();
        }
        
        function filterByMonth(month) {
            // Update active filter tag
            document.querySelectorAll('.filter-tag').forEach(tag => {
                tag.classList.remove('active', 'bg-white/40');
                if (tag.dataset.month === month) {
                    tag.classList.add('active', 'bg-white/40');
                }
            });
            
            // Apply filter
            filterUnits();
        }
        
        function clearSearch() {
            document.getElementById('unitSearchInput').value = '';
            setUnitStatusFilter('all');
        }
        window.showUnitsModal = showUnitsModal;
        window.hideUnitsModal = hideUnitsModal;
        window.loadUnitsData = loadUnitsData;
        window.displayUnitsData = displayUnitsData;
        window.renderUnits = renderUnits;
        window.filterUnits = filterUnits;
        window.setUnitStatusFilter = setUnitStatusFilter;
        window.filterByYear = filterByYear;
        window.filterByMonth = filterByMonth;
        window.clearSearch = clearSearch;

        window.showFlaggedUnitsModal = function() {
            const modal = document.getElementById('flaggedUnitsModal');
            modal.classList.remove('hidden');
            const container = document.getElementById('flaggedUnitsList');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/api/flagged-units')
                .then(res => res.json())
                .then(data => {
                    if (!data.success || data.units.length === 0) {
                        container.innerHTML = `
                            <div class="text-center py-12 bg-white rounded-2xl ring-1 ring-slate-900/5 shadow-md shadow-slate-200/40">
                                <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-green-100">
                                    <i data-lucide="shield-check" class="w-8 h-8 text-green-600"></i>
                                </div>
                                <h4 class="text-lg font-bold text-slate-800 uppercase tracking-tight">All Clear</h4>
                                <p class="text-slate-500 text-xs px-8 mt-1 font-medium">No units are currently flagged or missing. Fleet integrity is secured.</p>
                            </div>
                        `;
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                        return;
                    }

                    let html = '<div class="space-y-3">';
                    data.units.forEach(unit => {
                        const isMissing = (unit.status || '').toLowerCase() === 'missing';
                        const badgeClass = isMissing ? 'bg-red-100 text-red-700 border-red-200' : 'bg-amber-100 text-amber-700 border-amber-200';
                        const icon = isMissing ? 'alert-octagon' : 'eye';
                        const statusText = isMissing ? 'MISSING / STOLEN' : 'SURVEILLANCE';
                        
                        const daysMissing = unit.days_missing || 0;
                        const daysColor = daysMissing > 3 ? 'text-red-600' : 'text-amber-600';

                        const contactDisplay = unit.driver_phone ? 
                            `<a href="tel:${unit.driver_phone}" class="text-blue-600 font-bold hover:underline flex items-center gap-1">
                                <i data-lucide="phone" class="w-3 h-3"></i> ${unit.driver_phone}
                            </a>` : '<span class="text-slate-400">Not recorded</span>';

                        html += `
                            <div class="bg-white p-4 rounded-2xl shadow-md shadow-slate-200/40 ring-1 ring-slate-900/5 border-l-4 ${isMissing ? 'border-red-600' : 'border-amber-600'} hover:shadow-md transition-all relative overflow-hidden group">
                                ${isMissing ? '<div class="absolute top-0 right-0 p-1 bg-red-600 text-white text-[8px] font-bold uppercase tracking-widest px-3">Lockdown</div>' : ''}
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="text-lg font-bold text-slate-800 tracking-tighter">${unit.plate_number}</span>
                                            <span class="px-2 py-0.5 rounded text-[9px] font-bold border ${badgeClass} flex items-center gap-1 uppercase tracking-widest shadow-md shadow-slate-200/40">
                                                <i data-lucide="${icon}" class="w-3 h-3"></i> ${statusText}
                                            </span>
                                        </div>
                                        <div class="space-y-1.5">
                                            <div class="flex items-center gap-2 text-[10px]">
                                                <span class="text-slate-400 w-24 flex-shrink-0 font-bold uppercase tracking-tight">Current Driver:</span>
                                                <span class="text-gray-800 font-bold uppercase tracking-tighter">${unit.driver_name || 'No Driver'}</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-[10px]">
                                                <span class="text-slate-400 w-24 flex-shrink-0 font-bold uppercase tracking-tight">Contact # :</span>
                                                ${contactDisplay}
                                            </div>
                                            <div class="flex items-center gap-2 text-[10px] pt-1.5 mt-1.5 border-t border-gray-100">
                                                <span class="text-slate-400 w-24 flex-shrink-0 font-bold uppercase tracking-tight">Last Return:</span>
                                                <span class="text-gray-600 italic font-bold uppercase tracking-tighter">${unit.last_known_driver || 'None'}</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-[10px]">
                                                <span class="text-slate-400 w-24 flex-shrink-0 font-bold uppercase tracking-tight">Return Date:</span>
                                                <span class="text-gray-600 font-bold uppercase">${unit.last_boundary_date || 'No record'}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-center gap-3 flex-shrink-0 ml-4">
                                        <div class="text-center bg-gray-50 p-2 rounded-xl ring-1 ring-slate-900/5 min-w-[70px]">
                                            <div class="text-[9px] uppercase font-bold text-slate-400 tracking-widest">Time Elapsed</div>
                                            <div class="text-xl ${daysColor} leading-none mt-1 font-bold">${daysMissing}</div>
                                            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">day(s)</div>
                                        </div>
                                        ${isMissing ? `
                                        <form method="POST" action="/units/${unit.id}/recover" class="m-0 w-full" onsubmit="return confirm('Confirm RECOVERY of Unit ${unit.plate_number}? This will restore its active status and clear security alerts.');">
                                            <input type="hidden" name="_token" value="${csrfToken}">
                                            <button type="submit" class="w-full py-2 bg-green-600 text-white hover:bg-green-700 rounded-xl transition-all shadow-lg shadow-green-200 flex items-center justify-center gap-2 group/btn" title="Mark as Recovered">
                                                <i data-lucide="check-circle" class="w-4 h-4 group-hover/btn:scale-110 transition-transform"></i>
                                                <span class="text-[10px] font-bold uppercase tracking-widest">Recover</span>
                                            </button>
                                        </form>
                                        ` : `
                                        <a href="/units?id=${unit.id}" class="w-full py-2 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl transition-all flex items-center justify-center gap-2 border border-gray-200">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                            <span class="text-[10px] font-bold uppercase tracking-widest text-center">Manage</span>
                                        </a>
                                        `}
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                    container.innerHTML = html;
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                })
                .catch(err => {
                    container.innerHTML = `
                        <div class="text-center py-12 bg-red-50 rounded-2xl border border-red-100">
                            <i data-lucide="alert-circle" class="w-12 h-12 text-red-500 mx-auto mb-3"></i>
                            <p class="text-red-700 font-bold uppercase tracking-widest text-xs">Failed to load security flags.</p>
                            <button onclick="showFlaggedUnitsModal()" class="mt-4 px-4 py-2 bg-red-600 text-white rounded-lg text-xs font-bold uppercase tracking-widest">Retry</button>
                        </div>
                    `;
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                });
        }

    </script>
@endsection
