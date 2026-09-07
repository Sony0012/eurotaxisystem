{{-- Driver Details Modal with Tabs --}}
<div id="driverDetailsModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden h-full w-full z-[60] flex items-center justify-center p-4 transition-all duration-300">
    <div class="relative bg-white rounded-2xl shadow-2xl max-w-5xl w-full h-[90vh] overflow-hidden flex flex-col scale-95 transition-transform duration-300" id="driverDetailsModalContainer">
        {{-- Modal Header (Deep Navy) --}}
        <div class="bg-slate-800 p-5 shrink-0">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3.5">
                    <div class="relative w-12 h-12 rounded-2xl overflow-hidden flex-shrink-0 border-2 border-amber-400 bg-slate-900 shadow-sm cursor-pointer group/modalAvatar" onclick="viewDriverModalAvatar()" title="Click to view full photo">
                        <img id="driverDetailsAvatar" src="{{ asset('image/avatars/driver.svg') }}" alt="Driver Avatar" class="w-full h-full object-cover group-hover/modalAvatar:scale-110 transition-transform duration-300" onerror="this.onerror=null; this.src='{{ asset('image/avatars/driver.svg') }}';">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/modalAvatar:opacity-100 flex items-center justify-center transition-opacity">
                            <i data-lucide="maximize-2" class="w-4 h-4 text-white"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-white tracking-wide uppercase" id="driverDetailsName">Driver Details</h3>
                        <p class="text-[10px] font-bold text-slate-400 mt-0.5 uppercase tracking-widest" id="driverDetailsSubtitle">Profiling & Performance Analysis</p>
                    </div>
                </div>
                <button type="button" onclick="closeDriverDetails()" class="text-slate-400 hover:text-white bg-slate-700/50 hover:bg-slate-700 p-2 rounded-full transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="px-6 bg-slate-50 border-b shrink-0 overflow-x-auto custom-scrollbar">
            <nav class="-mb-px flex space-x-1" aria-label="Tabs">
                <button type="button" class="driver-tab active py-4 px-4 text-[10px] font-black uppercase tracking-widest border-b-2 border-blue-500 text-blue-600 transition-all flex items-center gap-2" data-tab="basic">
                    <i data-lucide="user" class="w-3.5 h-3.5"></i> Basic Info
                </button>
                <button type="button" class="driver-tab py-4 px-4 text-[10px] font-black uppercase tracking-widest border-b-2 border-transparent text-slate-400 hover:text-slate-600 transition-all flex items-center gap-2" data-tab="license">
                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i> License & Documents
                </button>
                <button type="button" class="driver-tab py-4 px-4 text-[10px] font-black uppercase tracking-widest border-b-2 border-transparent text-slate-400 hover:text-slate-600 transition-all flex items-center gap-2" data-tab="boundaries">
                    <i data-lucide="receipt" class="w-3.5 h-3.5"></i> Boundary History
                </button>
                <button type="button" class="driver-tab py-4 px-4 text-[10px] font-black uppercase tracking-widest border-b-2 border-transparent text-slate-400 hover:text-slate-600 transition-all flex items-center gap-2" data-tab="debts">
                    <i data-lucide="wallet" class="w-3.5 h-3.5"></i> Debts & Liabilities
                </button>
                <button type="button" class="driver-tab py-4 px-4 text-[10px] font-black uppercase tracking-widest border-b-2 border-transparent text-slate-400 hover:text-slate-600 transition-all flex items-center gap-2" data-tab="incentives">
                    <i data-lucide="award" class="w-3.5 h-3.5"></i> Incentives & Insights
                </button>
                <button type="button" class="driver-tab py-4 px-4 text-[10px] font-black uppercase tracking-widest border-b-2 border-transparent text-slate-400 hover:text-slate-600 transition-all flex items-center gap-2" data-tab="performance">
                    <i data-lucide="trending-up" class="w-3.5 h-3.5"></i> Performance
                </button>
            </nav>
        </div>

        {{-- Tab Panels --}}
        <div class="p-8 overflow-y-auto flex-1 custom-scrollbar">
            <div class="driver-tab-panel" data-tab-panel="basic">
                <div class="flex items-center gap-2 mb-6">
                    <div class="w-1 h-6 bg-blue-500 rounded-full"></div>
                    <h4 class="text-sm font-black text-slate-800 uppercase tracking-wider">Personal & Employment Details</h4>
                </div>
                <div id="basicInfoContent" class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-slate-600">
                    <p class="text-slate-400 animate-pulse">Synchronizing basic profile...</p>
                </div>
            </div>

            <div class="driver-tab-panel hidden" data-tab-panel="license">
                <div class="flex items-center gap-2 mb-6">
                    <div class="w-1 h-6 bg-indigo-500 rounded-full"></div>
                    <h4 class="text-sm font-black text-slate-800 uppercase tracking-wider">License & Credentials</h4>
                </div>
                <div id="licenseInfoContent" class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-slate-600">
                    <p class="text-slate-400 animate-pulse">Verifying license data...</p>
                </div>

                <div class="mt-8 pt-8 border-t border-slate-100">
                    <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200">
                        <h5 class="text-xs font-black text-slate-800 uppercase tracking-widest mb-2 flex items-center gap-2">
                            <i data-lucide="upload-cloud" class="w-4 h-4 text-blue-500"></i> Secure Document Vault
                        </h5>
                        <p class="text-[11px] text-slate-500 mb-6 font-medium">Upload encrypted copies of NBI, Barangay, and Medical clearances. New uploads will overwrite legacy records.</p>
                        
                        <form id="driverDocumentsForm" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="driver_id" id="driverDocumentsDriverId" value="">

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="driverDocumentsGrid">
                                <!-- Documents will be populated via JS -->
                            </div>

                            <div class="pt-4 flex justify-end">
                                <button type="submit" class="px-8 py-3 bg-slate-800 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-xl hover:bg-slate-900 transition-all shadow-lg shadow-slate-200 active:scale-95 flex items-center gap-2">
                                    <i data-lucide="shield-check" class="w-4 h-4 text-blue-400"></i> Commit Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="driver-tab-panel hidden" data-tab-panel="boundaries">
                <div class="flex items-center gap-2 mb-6">
                    <div class="w-1 h-6 bg-cyan-500 rounded-full"></div>
                    <div>
                        <h4 class="text-sm font-black text-slate-800 uppercase tracking-wider">Boundary History & Shift Records</h4>
                        <p class="text-[11px] text-slate-400 font-medium">Historical breakdown of daily boundaries, actual collections, shortages, and incentives.</p>
                    </div>
                </div>
                <div id="boundariesContent" class="text-sm text-slate-600 space-y-6">
                    <p class="text-slate-400 animate-pulse">Loading boundary logs...</p>
                </div>
            </div>

            <div class="driver-tab-panel hidden" data-tab-panel="debts">
                <div class="flex items-center gap-2 mb-6">
                    <div class="w-1 h-6 bg-rose-500 rounded-full"></div>
                    <div>
                        <h4 class="text-sm font-black text-slate-800 uppercase tracking-wider">Driver Debts & Financial Liabilities</h4>
                        <p class="text-[11px] text-slate-400 font-medium">Full ledger of incident damage charges, boundary shortages, cash settlements, and remaining balances.</p>
                    </div>
                </div>
                <div id="debtsContent" class="text-sm text-slate-600 space-y-6">
                    <p class="text-slate-400 animate-pulse">Calculating debt ledger...</p>
                </div>
            </div>

            <div class="driver-tab-panel hidden" data-tab-panel="incentives">
                <div class="flex items-center gap-2 mb-6">
                    <div class="w-1 h-6 bg-emerald-500 rounded-full"></div>
                    <div>
                        <h4 class="text-sm font-black text-slate-800 uppercase tracking-wider">Incentives & Operational Insights</h4>
                        <p class="text-[11px] text-slate-400 font-medium">Strategic performance index, reward eligibility manifest, KPI metric summaries, and chronological shift logs.</p>
                    </div>
                </div>
                <div id="incentivesContent" class="text-sm text-slate-600 space-y-6">
                    <p class="text-slate-400 animate-pulse">Calculating reward eligibility and operational insights...</p>
                </div>
            </div>

            <div class="driver-tab-panel hidden" data-tab-panel="performance">
                <div class="flex items-center gap-2 mb-6">
                    <div class="w-1 h-6 bg-orange-500 rounded-full"></div>
                    <h4 class="text-sm font-black text-slate-800 uppercase tracking-wider">Telemetry & Metrics</h4>
                </div>
                <div id="performanceContent" class="text-sm text-slate-600 space-y-2">
                    <p class="text-slate-400 animate-pulse">Fetching operational data...</p>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="p-4 border-t flex justify-end shadow-inner bg-slate-50 shrink-0">
            <button type="button" onclick="closeDriverDetails()" 
                class="px-8 py-2.5 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 text-sm font-black transition-all">
                Close Details
            </button>
        </div>
    </div>
</div>

<script>
    function openDriverDetails(id) {
        const modal = document.getElementById('driverDetailsModal');
        modal.classList.remove('hidden');
        
        setTimeout(() => {
            document.getElementById('driverDetailsModalContainer').classList.remove('scale-95');
        }, 10);

        document.querySelectorAll('.driver-tab').forEach(btn => {
            btn.classList.remove('border-blue-500', 'text-blue-600', 'active');
            btn.classList.add('border-transparent', 'text-slate-400');
        });
        document.querySelectorAll('.driver-tab-panel').forEach(panel => { panel.classList.add('hidden'); });
        
        const firstTab = document.querySelector('.driver-tab[data-tab="basic"]');
        const firstPanel = document.querySelector('.driver-tab-panel[data-tab-panel="basic"]');
        if (firstTab && firstPanel) {
            firstTab.classList.add('border-blue-500', 'text-blue-600', 'active');
            firstPanel.classList.remove('hidden');
        }

        document.getElementById('driverDocumentsDriverId').value = id;
        document.getElementById('driverDocumentsForm').action = '{{ url('driver-management/upload-documents') }}/' + id;

        fetch('{{ route('driver-management.index') }}/' + id + '?format=json', {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            document.getElementById('driverDetailsName').textContent = data.full_name || 'Driver Details';
            document.getElementById('driverDetailsSubtitle').textContent = data.assigned_unit ? `Assigned to ${data.assigned_unit}` : 'Not currently assigned';

            const avatarEl = document.getElementById('driverDetailsAvatar');
            if (avatarEl) {
                if (data.profile_photo) {
                    avatarEl.src = data.profile_photo.startsWith('http') ? data.profile_photo : '{{ asset("") }}' + data.profile_photo.replace(/^\//, '');
                } else {
                    avatarEl.src = '{{ asset("image/avatars/driver.svg") }}';
                }
            }

            const statusColorMap = {
                'available': 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'assigned': 'bg-blue-50 text-blue-700 border-blue-200',
                'on_leave': 'bg-amber-50 text-amber-700 border-amber-200',
                'suspended': 'bg-orange-50 text-orange-700 border-orange-200',
                'banned': 'bg-red-50 text-red-700 border-red-200'
            };
            const statusClass = statusColorMap[data.driver_status] || 'bg-slate-100 text-slate-700 border-slate-200';
            const driverStatusLabel = (data.driver_status || 'available').replace('_', ' ').toUpperCase();
            const regKey = 'DRV-' + String(data.id || 0).padStart(4, '0');
            const unpaidShortage = parseFloat(data.net_shortage || 0);
            const pendingDebt = parseFloat(data.total_pending_debt || 0);
            const totalPaidDebts = parseFloat(data.total_paid_debts || 0);

            document.getElementById('basicInfoContent').innerHTML = `
                <!-- Left Column: Identity, Contacts & Liabilities -->
                <div class="space-y-5">
                    <!-- Identity Card -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Personal Identification</span>
                            <span class="text-[10px] font-mono font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200">${regKey}</span>
                        </div>
                        <div class="mt-3">
                            <h3 class="text-lg font-black text-slate-900 leading-tight">${data.first_name || ''} ${data.last_name || ''}</h3>
                            <div class="flex items-center gap-2 mt-2 flex-wrap">
                                <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider border ${statusClass}">
                                    ${driverStatusLabel}
                                </span>
                                <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                                    ${(data.driver_type || 'regular').toUpperCase()} DRIVER
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Details Card -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                        <div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Primary Contact</span>
                            <a href="tel:${(data.contact_number || '').replace(/[^0-9+]/g, '')}" class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 hover:underline">
                                <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                    <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                                </div>
                                <span>${data.contact_number || 'No contact provided'}</span>
                            </a>
                        </div>
                        <div class="pt-3 border-t border-slate-100">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Residential Address</span>
                            <div class="flex items-start gap-2 text-xs font-semibold text-slate-700">
                                <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 mt-0.5">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                                </div>
                                <span class="leading-relaxed">${data.address || 'No residential address recorded'}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Contact Card -->
                    <div class="bg-gradient-to-br from-rose-50/80 to-red-50/40 p-4 rounded-2xl border border-rose-100/90 shadow-2xs">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="p-1 bg-rose-500 text-white rounded-md">
                                <i data-lucide="shield-alert" class="w-3 h-3"></i>
                            </div>
                            <span class="text-[10px] font-black text-rose-800 uppercase tracking-widest">Emergency Contact</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-black text-slate-900">${data.emergency_contact || 'None Listed'}</p>
                                <p class="text-[11px] font-bold text-rose-600 font-mono mt-0.5">${data.emergency_phone || 'No phone recorded'}</p>
                            </div>
                            ${data.emergency_phone ? `
                                <a href="tel:${data.emergency_phone.replace(/[^0-9+]/g, '')}" class="px-3 py-1.5 bg-rose-600 text-white text-[10px] font-black uppercase tracking-wider rounded-lg shadow-xs hover:bg-rose-700 transition-all flex items-center gap-1.5">
                                    <i data-lucide="phone-call" class="w-3 h-3"></i> Call
                                </a>
                            ` : ''}
                        </div>
                    </div>

                    <!-- Outstanding Liabilities Card -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Financial Liabilities & Debts</span>
                            <button type="button" onclick="switchDriverTab('debts')" class="text-[10px] font-bold text-blue-600 hover:text-blue-800 uppercase tracking-wider flex items-center gap-1">
                                Open Ledger <i data-lucide="arrow-right" class="w-3 h-3"></i>
                            </button>
                        </div>
                        <div class="grid grid-cols-2 gap-3 mt-3">
                            <div class="p-3.5 rounded-xl border ${pendingDebt > 0 ? 'bg-rose-50/80 border-rose-200 text-rose-800' : 'bg-emerald-50/80 border-emerald-200 text-emerald-800'}">
                                <span class="text-[9px] font-black uppercase tracking-wider text-slate-400 block mb-1">Outstanding Balance</span>
                                <p class="text-sm font-black ${pendingDebt > 0 ? 'text-rose-600' : 'text-emerald-600'}">₱${pendingDebt.toLocaleString('en-PH', {minimumFractionDigits: 2})}</p>
                            </div>
                            <div class="p-3.5 rounded-xl border bg-emerald-50/80 border-emerald-200 text-emerald-800">
                                <span class="text-[9px] font-black uppercase tracking-wider text-slate-400 block mb-1">Total Settled / Paid</span>
                                <p class="text-sm font-black text-emerald-600">₱${totalPaidDebts.toLocaleString('en-PH', {minimumFractionDigits: 2})}</p>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-slate-500 font-bold">Unpaid Shortage Dues:</span>
                            <span class="font-black ${unpaidShortage > 0 ? 'text-rose-600' : 'text-slate-700'}">₱${unpaidShortage.toLocaleString('en-PH', {minimumFractionDigits: 2})}</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Employment & Financial Operations -->
                <div class="space-y-5">
                    <!-- Employment Tenure & Assigned Unit Card -->
                    <div class="bg-slate-900 text-white p-5 rounded-2xl border border-slate-800 shadow-md">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                            <div>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Employment Tenure</span>
                                <p class="text-sm font-black text-white mt-0.5">Joined ${data.hire_date || 'N/A'}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Assigned Taxi Unit</span>
                                <p class="text-sm font-black text-amber-400 font-mono mt-0.5">${data.assigned_unit || 'UNASSIGNED'}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3 mt-4">
                            <div class="p-3 rounded-xl bg-slate-800/80 border border-slate-700/60">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Standard Daily Rate</span>
                                <p class="text-sm font-black text-white">₱${(data.assigned_boundary_rate ? parseFloat(data.assigned_boundary_rate) : (data.current_pricing && data.current_pricing.base ? parseFloat(data.current_pricing.base) : (data.daily_boundary_target ? parseFloat(data.daily_boundary_target) : 0))).toLocaleString('en-PH', {minimumFractionDigits:2})}</p>
                            </div>
                            <div class="p-3 rounded-xl bg-blue-950/60 border border-blue-500/30">
                                <span class="text-[9px] font-bold text-blue-300 uppercase tracking-wider block mb-1">Active Targeted Rate</span>
                                <p class="text-sm font-black text-blue-400">₱${(data.current_pricing ? parseFloat(data.current_pricing.rate) : (data.daily_boundary_target ? parseFloat(data.daily_boundary_target) : (data.assigned_boundary_rate ? parseFloat(data.assigned_boundary_rate) : 0))).toLocaleString('en-PH', {minimumFractionDigits:2})}</p>
                            </div>
                        </div>
                        ${data.current_pricing && data.current_pricing.label ? `
                            <div class="mt-3 bg-blue-500/10 border border-blue-500/20 px-3 py-2 rounded-lg flex items-center justify-between text-xs">
                                <span class="text-blue-300 font-bold">Applied Pricing Scheme:</span>
                                <span class="text-blue-400 font-black uppercase text-[10px] tracking-wide">${data.current_pricing.label} ${data.current_pricing.type === 'coding' ? '(50% CODING DISCOUNT)' : ''}</span>
                            </div>
                        ` : ''}
                    </div>

                    <!-- 30-Day Operational Snapshot Card -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">30-Day Shift Activity</span>
                            </div>
                            <button type="button" onclick="switchDriverTab('boundaries')" class="text-[10px] font-bold text-blue-600 hover:text-blue-800 uppercase tracking-wider flex items-center gap-1">
                                View History <i data-lucide="arrow-right" class="w-3 h-3"></i>
                            </button>
                        </div>
                        <div class="grid grid-cols-2 gap-3 pt-1">
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-center">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Paid / Total Shifts</span>
                                <span class="text-xs font-black text-emerald-600">${data.paid_shifts_count || 0} / ${data.shifts_count || 0}</span>
                            </div>
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-center">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Reported Incidents</span>
                                <span class="text-xs font-black ${parseInt(data.incidents_count || 0) > 0 ? 'text-rose-600' : 'text-slate-700'}">${data.incidents_count || 0}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('licenseInfoContent').innerHTML = `
                <div class="space-y-4">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Professional License Number</span>
                        <p class="text-base font-mono font-black text-slate-900 mt-1 tracking-wider">${data.license_number || ''}</p>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Validation Expiry Date</span>
                        <p class="text-sm font-bold ${new Date(data.license_expiry) < new Date() ? 'text-red-600' : 'text-slate-700'} mt-0.5 flex items-center gap-2">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i> ${data.license_expiry || 'N/A'}
                        </p>
                    </div>
                </div>
                <div class="bg-indigo-50/50 p-5 rounded-2xl border border-indigo-100 self-start">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-1.5 bg-indigo-100 rounded-lg">
                            <i data-lucide="shield-check" class="w-4 h-4 text-indigo-600"></i>
                        </div>
                        <p class="text-xs font-black text-indigo-800 uppercase tracking-widest">Integrity Guard</p>
                    </div>
                    <p class="text-xs text-indigo-600/80 leading-relaxed font-medium">Automatic system verification of driver credentials. All documents uploaded are cross-referenced with fleet security protocols.</p>
                </div>
            `;

            // ===================== DOCUMENTS VAULT =====================
            const docs = [
                { id: 'profile_photo', label: 'Profile Photo', file: data.profile_photo },
                { id: 'license_photo', label: 'License Photo', file: data.license_photo },
                { id: 'nbi_clearance_photo', label: 'NBI Clearance', file: data.nbi_clearance_photo },
                { id: 'pnp_clearance_photo', label: 'PNP/Barangay Clearance', file: data.pnp_clearance_photo },
            ];

            let docsHtml = '';
            docs.forEach(doc => {
                let previewHtml = '';
                if (doc.file) {
                    const isPdf = doc.file.toLowerCase().endsWith('.pdf');
                    if (isPdf) {
                        previewHtml = `
                            <div id="preview_box_${doc.id}" class="mt-2 mb-3 bg-slate-50 border border-slate-100 rounded-lg p-3 flex flex-col items-center justify-center">
                                <i data-lucide="file-text" class="w-8 h-8 text-slate-400 mb-2"></i>
                                <a href="/${doc.file}" target="_blank" class="inline-flex items-center gap-1 text-[10px] font-black text-blue-600 hover:text-blue-800 uppercase tracking-widest bg-blue-50 px-3 py-1.5 rounded-full">
                                    <i data-lucide="external-link" class="w-3 h-3"></i> Open PDF
                                </a>
                            </div>
                        `;
                    } else {
                        previewHtml = `
                            <div id="preview_box_${doc.id}" class="mt-2 mb-3">
                                <div class="relative w-full h-32 bg-slate-100 rounded-lg overflow-hidden border border-slate-200 group cursor-pointer" onclick="openImageModal('/${doc.file}')">
                                    <img src="/${doc.file}" alt="${doc.label}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <span class="bg-white/90 text-slate-800 text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full flex items-center gap-1 shadow-xl">
                                            <i data-lucide="maximize-2" class="w-3 h-3"></i> View Full Size
                                        </span>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                } else {
                    previewHtml = `
                        <div id="preview_box_${doc.id}" class="mt-2 mb-3 bg-slate-50 border border-dashed border-slate-200 rounded-lg h-32 flex flex-col items-center justify-center text-slate-400">
                            <i data-lucide="image-off" class="w-6 h-6 mb-2 opacity-50"></i>
                            <span class="text-[9px] font-black uppercase tracking-widest opacity-60">No Document</span>
                        </div>
                    `;
                }

                docsHtml += `
                    <div class="space-y-1.5 p-4 bg-white border border-slate-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                        <label class="block text-[10px] font-black text-slate-600 uppercase tracking-widest flex items-center gap-1.5 border-b border-slate-100 pb-2 mb-2">
                            <i data-lucide="file-check-2" class="w-3.5 h-3.5 text-blue-500"></i> ${doc.label}
                        </label>
                        ${previewHtml}
                        <div class="pt-2">
                            <input type="file" name="${doc.id}" accept=".jpg,.jpeg,.png,.pdf" class="block w-full text-[10px] font-bold text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer" onchange="previewDocumentVault(this, '${doc.id}')">
                            <p class="text-[8px] text-slate-400 mt-1.5 uppercase tracking-widest font-bold">Upload new to overwrite</p>
                        </div>
                    </div>
                `;
            });
            document.getElementById('driverDocumentsGrid').innerHTML = docsHtml;
            // Re-initialize lucide icons for the new HTML
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // ===================== BOUNDARY HISTORY TAB =====================
            const totalBoundaries = data.total_boundary_count || (data.boundary_history ? data.boundary_history.length : 0);
            const totalColl = parseFloat(data.total_boundary_collected || 0);
            const totalTarget = parseFloat(data.total_boundary_target || 0);
            const totalShortage = parseFloat(data.total_boundary_shortage || 0);
            const paidCount = data.total_boundary_paid_count || (data.boundary_history ? data.boundary_history.filter(b => b.status === 'paid').length : 0);
            const shortCount = data.total_boundary_shortage_count || (data.boundary_history ? data.boundary_history.filter(b => b.status === 'shortage').length : 0);

            let boundaryRowsHtml = '';
            if (data.boundary_history && data.boundary_history.length > 0) {
                data.boundary_history.forEach(b => {
                    const actual = parseFloat(b.actual_boundary || 0);
                    const target = parseFloat(b.boundary_amount || 0);
                    const short = parseFloat(b.shortage || 0);
                    const exc = parseFloat(b.excess || 0);
                    const notes = (b.notes || '').trim();

                    let statusBadge = '';
                    if (b.status === 'paid') {
                        statusBadge = '<span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-[9px] font-black uppercase tracking-wider">PAID</span>';
                    } else if (b.status === 'shortage') {
                        statusBadge = '<span class="px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded-lg text-[9px] font-black uppercase tracking-wider">SHORTAGE</span>';
                    } else if (b.status === 'excess') {
                        statusBadge = '<span class="px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-[9px] font-black uppercase tracking-wider">EXCESS</span>';
                    } else if (b.is_absent || b.status === 'absent') {
                        statusBadge = '<span class="px-2.5 py-1 bg-purple-50 text-purple-700 border border-purple-200 rounded-lg text-[9px] font-black uppercase tracking-wider">ABSENT</span>';
                    } else {
                        statusBadge = `<span class="px-2.5 py-1 bg-slate-100 text-slate-700 border border-slate-200 rounded-lg text-[9px] font-black uppercase tracking-wider">${(b.status || 'RECORD').toUpperCase()}</span>`;
                    }

                    let varianceBadge = '';
                    if (short > 0) {
                        varianceBadge = `<span class="px-2 py-0.5 bg-rose-50 text-rose-600 border border-rose-100 rounded-md font-black text-xs">-₱${short.toLocaleString('en-PH', {minimumFractionDigits:2})}</span>`;
                    } else if (exc > 0) {
                        varianceBadge = `<span class="px-2 py-0.5 bg-blue-50 text-blue-600 border border-blue-100 rounded-md font-black text-xs">+₱${exc.toLocaleString('en-PH', {minimumFractionDigits:2})}</span>`;
                    } else {
                        varianceBadge = `<span class="text-emerald-600 font-bold text-xs">Balanced</span>`;
                    }

                    let incentiveBadge = b.has_incentive
                        ? '<span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-md text-[9px] font-black uppercase tracking-wider inline-flex items-center gap-1"><i data-lucide="check" class="w-3 h-3"></i> EARNED</span>'
                        : '<span class="px-2 py-0.5 bg-rose-100 text-rose-700 rounded-md text-[9px] font-black uppercase tracking-wider inline-flex items-center gap-1"><i data-lucide="x" class="w-3 h-3"></i> MISSED</span>';

                    boundaryRowsHtml += `
                        <tr class="border-b border-slate-100 hover:bg-slate-50/80 transition-colors">
                            <td class="p-4 font-bold text-slate-700 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                                    <span>${new Date(b.date).toLocaleDateString('en-PH', {month: 'short', day: 'numeric', year: 'numeric'})}</span>
                                </div>
                            </td>
                            <td class="p-4 font-black font-mono text-slate-900 whitespace-nowrap">
                                <span class="px-2 py-1 bg-slate-100 border border-slate-200 rounded-lg text-xs">${b.plate_number || '—'}</span>
                            </td>
                            <td class="p-4 font-bold text-slate-500 whitespace-nowrap">₱${target.toLocaleString('en-PH', {minimumFractionDigits:2})}</td>
                            <td class="p-4 font-black text-slate-900 whitespace-nowrap">₱${actual.toLocaleString('en-PH', {minimumFractionDigits:2})}</td>
                            <td class="p-4 whitespace-nowrap">${varianceBadge}</td>
                            <td class="p-4 whitespace-nowrap">${statusBadge}</td>
                            <td class="p-4 whitespace-nowrap">${incentiveBadge}</td>
                            <td class="p-4 text-xs font-semibold text-slate-500 max-w-[200px] truncate" title="${notes}">${notes || '—'}</td>
                        </tr>
                    `;
                });
            } else {
                boundaryRowsHtml = `<tr><td colspan="8" class="p-8 text-center text-slate-400 font-bold uppercase tracking-widest text-[10px]">No boundary history recorded for this driver</td></tr>`;
            }

            const boundariesEl = document.getElementById('boundariesContent');
            if (boundariesEl) {
                boundariesEl.innerHTML = `
                    <!-- Boundary Summary KPI Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <!-- Card 1: Total Shifts -->
                        <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-br from-white via-cyan-50/30 to-sky-50/20 p-5 shadow-xs">
                            <div class="flex items-center justify-between gap-3 relative z-10">
                                <div class="min-w-0 flex-1">
                                    <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-400 block mb-1">Total Shifts</span>
                                    <div class="text-xl sm:text-2xl font-black text-slate-900 leading-tight tracking-tight tabular-nums truncate">
                                        ${totalBoundaries}
                                    </div>
                                    <div class="mt-2 flex items-center gap-1.5 text-[11px] font-bold text-cyan-600">
                                        <span class="inline-flex h-1.5 w-1.5 rounded-full bg-cyan-500"></span>
                                        <span>${paidCount} Paid / ${shortCount} Short</span>
                                    </div>
                                </div>
                                <div class="w-12 h-12 sm:w-14 sm:h-14 shrink-0">
                                    <img src="{{ asset("image/kpi/service_cycle_3d.svg") }}" alt="Shifts" class="w-full h-full object-contain filter drop-shadow-md">
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Total Collected -->
                        <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-br from-white via-emerald-50/40 to-teal-50/20 p-5 shadow-xs">
                            <div class="flex items-center justify-between gap-3 relative z-10">
                                <div class="min-w-0 flex-1">
                                    <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-400 block mb-1">Total Collected</span>
                                    <div class="text-xl sm:text-2xl font-black text-emerald-600 leading-tight tracking-tight tabular-nums truncate">
                                        ₱${totalColl.toLocaleString('en-PH', {minimumFractionDigits:2})}
                                    </div>
                                    <div class="mt-2 flex items-center gap-1.5 text-[11px] font-bold text-emerald-600">
                                        <span class="inline-flex h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        <span>All-Time Collections</span>
                                    </div>
                                </div>
                                <div class="w-12 h-12 sm:w-14 sm:h-14 shrink-0">
                                    <img src="{{ asset("image/kpi/reward_cash_3d.svg") }}" alt="Collections" class="w-full h-full object-contain filter drop-shadow-md">
                                </div>
                            </div>
                        </div>

                        <!-- Card 3: Target Expected -->
                        <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-br from-white via-blue-50/40 to-indigo-50/20 p-5 shadow-xs">
                            <div class="flex items-center justify-between gap-3 relative z-10">
                                <div class="min-w-0 flex-1">
                                    <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-400 block mb-1">Target Expected</span>
                                    <div class="text-xl sm:text-2xl font-black text-slate-900 leading-tight tracking-tight tabular-nums truncate">
                                        ₱${totalTarget.toLocaleString('en-PH', {minimumFractionDigits:2})}
                                    </div>
                                    <div class="mt-2 flex items-center gap-1.5 text-[11px] font-bold text-blue-600">
                                        <span class="inline-flex h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                        <span>Cumulative Target</span>
                                    </div>
                                </div>
                                <div class="w-12 h-12 sm:w-14 sm:h-14 shrink-0">
                                    <img src="{{ asset("image/kpi/owner_active_3d.svg") }}" alt="Target" class="w-full h-full object-contain filter drop-shadow-md">
                                </div>
                            </div>
                        </div>

                        <!-- Card 4: Shortages Incurred -->
                        <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-br from-white via-rose-50/40 to-red-50/20 p-5 shadow-xs">
                            <div class="flex items-center justify-between gap-3 relative z-10">
                                <div class="min-w-0 flex-1">
                                    <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-400 block mb-1">Total Shortages</span>
                                    <div class="text-xl sm:text-2xl font-black ${totalShortage > 0 ? 'text-rose-600' : 'text-slate-900'} leading-tight tracking-tight tabular-nums truncate">
                                        ₱${totalShortage.toLocaleString('en-PH', {minimumFractionDigits:2})}
                                    </div>
                                    <div class="mt-2 flex items-center gap-1.5 text-[11px] font-bold ${totalShortage > 0 ? 'text-rose-600' : 'text-emerald-600'}">
                                        <span class="inline-flex h-1.5 w-1.5 rounded-full ${totalShortage > 0 ? 'bg-rose-500' : 'bg-emerald-500'}"></span>
                                        <span>${totalShortage > 0 ? `${shortCount} Shortage Shift(s)` : 'Zero Shortage Track'}</span>
                                    </div>
                                </div>
                                <div class="w-12 h-12 sm:w-14 sm:h-14 shrink-0">
                                    <img src="{{ asset("image/kpi/friction_points_3d.svg") }}" alt="Shortages" class="w-full h-full object-contain filter drop-shadow-md">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Boundary Records Table -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                        <div class="p-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between flex-wrap gap-2">
                            <div class="flex items-center gap-2">
                                <i data-lucide="list" class="w-4 h-4 text-cyan-600"></i>
                                <h5 class="text-xs font-black text-slate-800 uppercase tracking-wider">Complete Boundary Shift Ledger</h5>
                            </div>
                            <span class="text-[10px] font-mono font-bold text-slate-500 bg-white px-2.5 py-1 rounded-md border border-slate-200">${totalBoundaries} Total Entries</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-left">
                                <thead class="bg-slate-50/50 text-slate-400 font-black uppercase tracking-widest border-b border-slate-100">
                                    <tr>
                                        <th class="p-4">Shift Date</th>
                                        <th class="p-4">Taxi Unit</th>
                                        <th class="p-4">Target Rate</th>
                                        <th class="p-4">Actual Collected</th>
                                        <th class="p-4">Variance</th>
                                        <th class="p-4">Status</th>
                                        <th class="p-4">Incentive</th>
                                        <th class="p-4">Telemetry Notes</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">${boundaryRowsHtml}</tbody>
                            </table>
                        </div>
                    </div>
                `;
            }

            // ===================== DEBTS & LIABILITIES TAB =====================
            const totalCharged = parseFloat(data.total_charged_all_time || 0);
            const totalPaid = parseFloat(data.total_paid_debts || 0);
            const totalPending = parseFloat(data.total_pending_debt || 0);
            const pendingList = data.pending_debts || [];
            const settledList = data.settled_debts || [];
            const expenseList = data.expense_payments || [];

            // Pending Debts Rows
            let pendingRowsHtml = '';
            if (pendingList.length > 0) {
                pendingList.forEach(d => {
                    const chg = parseFloat(d.total_charge || 0);
                    const pd = parseFloat(d.total_paid || 0);
                    const rem = parseFloat(d.remaining_balance || 0);
                    const dateStr = d.date || d.timestamp || d.created_at;

                    pendingRowsHtml += `
                        <tr class="border-b border-slate-100 hover:bg-rose-50/20 transition-colors">
                            <td class="p-4 font-bold text-slate-700 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                                    <span>${dateStr ? new Date(dateStr).toLocaleDateString('en-PH', {month: 'short', day: 'numeric', year: 'numeric'}) : '—'}</span>
                                </div>
                            </td>
                            <td class="p-4 font-black text-slate-900">
                                <span class="px-2 py-1 bg-slate-100 border border-slate-200 rounded-lg text-[10px] uppercase font-black tracking-wider">${(d.incident_type || 'INCIDENT').replace('_', ' ')}</span>
                            </td>
                            <td class="p-4 font-mono font-bold text-slate-800">${d.plate_number || '—'}</td>
                            <td class="p-4 font-bold text-slate-600 whitespace-nowrap">₱${chg.toLocaleString('en-PH', {minimumFractionDigits:2})}</td>
                            <td class="p-4 font-bold text-emerald-600 whitespace-nowrap">₱${pd.toLocaleString('en-PH', {minimumFractionDigits:2})}</td>
                            <td class="p-4 font-black text-rose-600 whitespace-nowrap text-sm">₱${rem.toLocaleString('en-PH', {minimumFractionDigits:2})}</td>
                            <td class="p-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg text-[9px] font-black uppercase tracking-wider">${(d.charge_status || 'PENDING').toUpperCase()}</span>
                            </td>
                            <td class="p-4 text-xs font-semibold text-slate-500 max-w-[220px] truncate" title="${d.description || ''}">${d.description || '—'}</td>
                        </tr>
                    `;
                });
            } else {
                pendingRowsHtml = `
                    <tr>
                        <td colspan="8" class="p-8 text-center">
                            <div class="flex flex-col items-center justify-center gap-2 text-emerald-600">
                                <div class="w-10 h-10 rounded-full bg-emerald-50 border border-emerald-200 flex items-center justify-center">
                                    <i data-lucide="shield-check" class="w-6 h-6 text-emerald-500"></i>
                                </div>
                                <p class="font-black uppercase tracking-wider text-xs">No Pending Liabilities</p>
                                <p class="text-[11px] text-slate-400 font-medium">Driver has zero outstanding debts or unpaid charges.</p>
                            </div>
                        </td>
                    </tr>
                `;
            }

            // Settled Debts Rows
            let settledRowsHtml = '';
            if (settledList.length > 0) {
                settledList.forEach(s => {
                    const chg = parseFloat(s.total_charge || 0);
                    const pd = parseFloat(s.total_paid || 0);
                    const dateStr = s.settled_at || s.updated_at || s.date || s.created_at;

                    settledRowsHtml += `
                        <tr class="border-b border-slate-100 hover:bg-emerald-50/20 transition-colors">
                            <td class="p-4 font-bold text-slate-700 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-500"></i>
                                    <span>${dateStr ? new Date(dateStr).toLocaleDateString('en-PH', {month: 'short', day: 'numeric', year: 'numeric'}) : '—'}</span>
                                </div>
                            </td>
                            <td class="p-4 font-black text-slate-900">
                                <span class="px-2 py-1 bg-slate-100 border border-slate-200 rounded-lg text-[10px] uppercase font-black tracking-wider">${(s.incident_type || 'CHARGE').replace('_', ' ')}</span>
                            </td>
                            <td class="p-4 font-mono font-bold text-slate-800">${s.plate_number || '—'}</td>
                            <td class="p-4 font-bold text-slate-500 whitespace-nowrap">₱${chg.toLocaleString('en-PH', {minimumFractionDigits:2})}</td>
                            <td class="p-4 font-black text-emerald-600 whitespace-nowrap">₱${pd.toLocaleString('en-PH', {minimumFractionDigits:2})}</td>
                            <td class="p-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-lg text-[9px] font-black uppercase tracking-widest inline-flex items-center gap-1">
                                    <i data-lucide="check-check" class="w-3 h-3"></i> FULLY SETTLED
                                </span>
                            </td>
                            <td class="p-4 text-xs font-semibold text-slate-500 max-w-[220px] truncate" title="${s.description || ''}">${s.description || '—'}</td>
                        </tr>
                    `;
                });
            } else {
                settledRowsHtml = `<tr><td colspan="7" class="p-8 text-center text-slate-400 font-bold uppercase tracking-widest text-[10px]">No settled debt records found</td></tr>`;
            }

            // Expense damage recovery cash-in payments
            let expenseRowsHtml = '';
            if (expenseList.length > 0) {
                expenseList.forEach(e => {
                    expenseRowsHtml += `
                        <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                            <td class="p-4 font-bold text-slate-700">${new Date(e.date).toLocaleDateString('en-PH', {month: 'short', day: 'numeric', year: 'numeric'})}</td>
                            <td class="p-4 font-black text-slate-800">${e.unit_plate || '—'}</td>
                            <td class="p-4 font-black text-emerald-600">₱${parseFloat(e.amount || 0).toLocaleString('en-PH', {minimumFractionDigits:2})}</td>
                            <td class="p-4"><span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded-md text-[10px] font-black uppercase">${e.payment_method || 'Cash'}</span></td>
                            <td class="p-4 text-xs text-slate-600">${e.description || 'Damage Recovery Payment'}</td>
                        </tr>
                    `;
                });
            }

            const debtsEl = document.getElementById('debtsContent');
            if (debtsEl) {
                debtsEl.innerHTML = `
                    <!-- Debts Summary KPI Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                        <!-- Card 1: Total Charged All-Time -->
                        <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-br from-white via-slate-50/80 to-slate-100/40 p-5 shadow-xs">
                            <div class="flex items-center justify-between gap-3 relative z-10">
                                <div class="min-w-0 flex-1">
                                    <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-400 block mb-1">Total Charged All-Time</span>
                                    <div class="text-xl sm:text-2xl font-black text-slate-900 leading-tight tracking-tight tabular-nums truncate">
                                        ₱${totalCharged.toLocaleString('en-PH', {minimumFractionDigits:2})}
                                    </div>
                                    <div class="mt-2 flex items-center gap-1.5 text-[11px] font-bold text-slate-500">
                                        <span class="inline-flex h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                        <span>${pendingList.length + settledList.length} Total Incident Charge(s)</span>
                                    </div>
                                </div>
                                <div class="w-12 h-12 sm:w-14 sm:h-14 shrink-0">
                                    <img src="{{ asset("image/kpi/friction_points_3d.svg") }}" alt="Total Charges" class="w-full h-full object-contain filter drop-shadow-md">
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Total Paid & Settled -->
                        <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-br from-white via-emerald-50/40 to-teal-50/20 p-5 shadow-xs">
                            <div class="flex items-center justify-between gap-3 relative z-10">
                                <div class="min-w-0 flex-1">
                                    <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-400 block mb-1">Total Paid & Settled</span>
                                    <div class="text-xl sm:text-2xl font-black text-emerald-600 leading-tight tracking-tight tabular-nums truncate">
                                        ₱${totalPaid.toLocaleString('en-PH', {minimumFractionDigits:2})}
                                    </div>
                                    <div class="mt-2 flex items-center gap-1.5 text-[11px] font-bold text-emerald-600">
                                        <span class="inline-flex h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        <span>${settledList.length} Debt Record(s) Settled</span>
                                    </div>
                                </div>
                                <div class="w-12 h-12 sm:w-14 sm:h-14 shrink-0">
                                    <img src="{{ asset("image/kpi/reward_cash_3d.svg") }}" alt="Settled" class="w-full h-full object-contain filter drop-shadow-md">
                                </div>
                            </div>
                        </div>

                        <!-- Card 3: Active Outstanding Debt -->
                        <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-br from-white via-${totalPending > 0 ? 'rose-50/40' : 'emerald-50/40'} to-${totalPending > 0 ? 'red-50/20' : 'teal-50/20'} p-5 shadow-xs">
                            <div class="flex items-center justify-between gap-3 relative z-10">
                                <div class="min-w-0 flex-1">
                                    <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-400 block mb-1">Outstanding Balance</span>
                                    <div class="text-xl sm:text-2xl font-black ${totalPending > 0 ? 'text-rose-600' : 'text-emerald-600'} leading-tight tracking-tight tabular-nums truncate">
                                        ₱${totalPending.toLocaleString('en-PH', {minimumFractionDigits:2})}
                                    </div>
                                    <div class="mt-2 flex items-center gap-1.5 text-[11px] font-bold ${totalPending > 0 ? 'text-rose-600' : 'text-emerald-600'}">
                                        <span class="inline-flex h-1.5 w-1.5 rounded-full ${totalPending > 0 ? 'bg-rose-500' : 'bg-emerald-500'}"></span>
                                        <span>${totalPending > 0 ? `${pendingList.length} Active Unpaid Item(s)` : 'Fully Cleared & Zero Balance'}</span>
                                    </div>
                                </div>
                                <div class="w-12 h-12 sm:w-14 sm:h-14 shrink-0">
                                    <img src="{{ asset("image/kpi/owner_active_3d.svg") }}" alt="Balance" class="w-full h-full object-contain filter drop-shadow-md">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Active & Pending Debts Table -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden mb-6">
                        <div class="p-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between flex-wrap gap-2">
                            <div class="flex items-center gap-2">
                                <i data-lucide="alert-circle" class="w-4 h-4 text-rose-500"></i>
                                <h5 class="text-xs font-black text-slate-800 uppercase tracking-wider">Active & Pending Liabilities</h5>
                            </div>
                            <span class="text-[10px] font-mono font-bold ${totalPending > 0 ? 'text-rose-600 bg-rose-50 border-rose-200' : 'text-emerald-600 bg-emerald-50 border-emerald-200'} px-2.5 py-1 rounded-md border">${pendingList.length} Unsettled Item(s)</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-left">
                                <thead class="bg-slate-50/50 text-slate-400 font-black uppercase tracking-widest border-b border-slate-100">
                                    <tr>
                                        <th class="p-4">Incident Date</th>
                                        <th class="p-4">Classification</th>
                                        <th class="p-4">Unit</th>
                                        <th class="p-4">Initial Charge</th>
                                        <th class="p-4">Paid So Far</th>
                                        <th class="p-4">Remaining Balance</th>
                                        <th class="p-4">Status</th>
                                        <th class="p-4">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">${pendingRowsHtml}</tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Paid & Settled Debts History Table -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden mb-6">
                        <div class="p-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between flex-wrap gap-2">
                            <div class="flex items-center gap-2">
                                <i data-lucide="check-check" class="w-4 h-4 text-emerald-600"></i>
                                <h5 class="text-xs font-black text-slate-800 uppercase tracking-wider">Paid & Settled Debts History</h5>
                            </div>
                            <span class="text-[10px] font-mono font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200">${settledList.length} Settled Record(s)</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-left">
                                <thead class="bg-slate-50/50 text-slate-400 font-black uppercase tracking-widest border-b border-slate-100">
                                    <tr>
                                        <th class="p-4">Settled Date</th>
                                        <th class="p-4">Classification</th>
                                        <th class="p-4">Unit</th>
                                        <th class="p-4">Total Charge</th>
                                        <th class="p-4">Amount Paid</th>
                                        <th class="p-4">Resolution</th>
                                        <th class="p-4">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">${settledRowsHtml}</tbody>
                            </table>
                        </div>
                    </div>

                    ${expenseList.length > 0 ? `
                        <!-- Damage Recovery Direct Cash Receipts -->
                        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                            <div class="p-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between flex-wrap gap-2">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="receipt" class="w-4 h-4 text-blue-600"></i>
                                    <h5 class="text-xs font-black text-slate-800 uppercase tracking-wider">Damage Recovery Accounting Cash-Ins</h5>
                                </div>
                                <span class="text-[10px] font-mono font-bold text-slate-500 bg-white px-2.5 py-1 rounded-md border border-slate-200">${expenseList.length} Payment(s)</span>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-xs text-left">
                                    <thead class="bg-slate-50/50 text-slate-400 font-black uppercase tracking-widest border-b border-slate-100">
                                        <tr>
                                            <th class="p-4">Date</th>
                                            <th class="p-4">Unit</th>
                                            <th class="p-4">Amount</th>
                                            <th class="p-4">Method</th>
                                            <th class="p-4">Particulars</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">${expenseRowsHtml}</tbody>
                                </table>
                            </div>
                        </div>
                    ` : ''}
                `;
            }

            // ===================== INCENTIVES TAB =====================
            // ===================== INCENTIVES & INSIGHTS TAB =====================
            const incentiveRate = data.incentive_rate || 0;
            const rateColor = incentiveRate >= 80 ? 'text-emerald-600' : incentiveRate >= 50 ? 'text-amber-600' : 'text-rose-600';
            const rateBar  = incentiveRate >= 80 ? 'bg-emerald-500' : incentiveRate >= 50 ? 'bg-amber-400' : 'bg-rose-500';

            // Strategic Score calculation
            const score = Math.max(0, Math.min(100,
                (data.incentive_rate || 0) * 0.5
                + Math.max(0, 100 - (data.total_incidents_30d || 0) * 10) * 0.3
                + (data.high_severity_incidents === 0 ? 20 : 0)
            ));
            const scoreColor = score >= 80 ? 'text-emerald-600' : score >= 50 ? 'text-amber-600' : 'text-rose-600';

            // Dynamic Eligibility Status Banner
            const eligStatus = data.is_eligible && data.is_first_week 
                ? '<div class="bg-gradient-to-r from-emerald-900 to-teal-950 border border-emerald-800/80 text-emerald-50 p-6 rounded-3xl shadow-xl relative overflow-hidden flex items-center justify-between"><div class="relative z-10 max-w-sm"><div class="flex items-center gap-2 mb-1"><span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-400/30"><i data-lucide="sparkles" class="w-3 h-3 text-amber-400"></i> Qualified</span></div><h3 class="text-xl font-black uppercase tracking-tight text-white mb-1">Grand Incentive Unlocked</h3><p class="text-xs font-semibold text-emerald-200/90 leading-relaxed">Driver has met all operational excellence criteria for the current cycle.</p></div><div class="w-16 h-16 shrink-0 z-10"><img src="{{ asset("image/kpi/owner_active_3d.svg") }}" alt="Incentive Unlocked" class="w-full h-full object-contain filter drop-shadow-lg"></div></div>'
                : data.is_eligible && !data.is_first_week
                ? '<div class="bg-gradient-to-r from-blue-900 to-indigo-950 border border-blue-800/80 text-blue-50 p-6 rounded-3xl shadow-xl relative overflow-hidden flex items-center justify-between"><div class="relative z-10 max-w-sm"><div class="flex items-center gap-2 mb-1"><span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-blue-500/20 text-blue-300 border border-blue-400/30"><i data-lucide="timer" class="w-3 h-3"></i> In Progress</span></div><h3 class="text-xl font-black uppercase tracking-tight text-white mb-1">Excellence Track Active</h3><p class="text-xs font-semibold text-blue-200/90 leading-relaxed">Zero violations detected. Awaiting final validation during 1st cycle week.</p></div><div class="w-16 h-16 shrink-0 z-10"><img src="{{ asset("image/kpi/owner_active_3d.svg") }}" alt="Excellence Track" class="w-full h-full object-contain filter drop-shadow-lg"></div></div>'
                : '<div class="bg-gradient-to-r from-rose-900 to-red-950 border border-rose-800/80 text-rose-50 p-6 rounded-3xl shadow-xl relative overflow-hidden flex items-center justify-between"><div class="relative z-10 max-w-sm"><div class="flex items-center gap-2 mb-1"><span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-rose-500/20 text-rose-300 border border-rose-400/30"><i data-lucide="shield-alert" class="w-3 h-3 text-rose-400"></i> Disqualified</span></div><h3 class="text-xl font-black uppercase tracking-tight text-white mb-1">Eligibility Revoked</h3><p class="text-xs font-semibold text-rose-200/90 leading-relaxed">Violation anomalies detected during the evaluation lookback period.</p></div><div class="w-16 h-16 shrink-0 z-10"><img src="{{ asset("image/kpi/owner_rejected_3d.svg") }}" alt="Revoked" class="w-full h-full object-contain filter drop-shadow-lg"></div></div>';

            // Verification Protocols Checklist
            const reqList = [
                { passed: (data.violations_absences || 0) === 0, text: 'Continuity: Zero Unattended Shifts' },
                { passed: data.violations_no_incentive === 0, text: 'Reliability: Perfect Boundary Discipline' },
                { passed: (!data.damage_missed && data.damage_missed === 0) && data.violations_incidents === 0, text: 'Safety: Zero Fleet Asset Damage' },
                { passed: (!data.breakdown_missed && data.breakdown_missed === 0), text: 'Maintenance: Zero Breakdown Factors' },
                { passed: data.violations_incidents === 0, text: 'Protocol: Zero Behavioral Deviations' }
            ];

            const reqsHtml = reqList.map(r => `
                <div class="flex items-center gap-3.5 py-2.5 border-b border-slate-100 last:border-0">
                    <span class="flex-shrink-0">${r.passed ? '<div class="p-1 bg-emerald-100 rounded-full"><i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600"></i></div>' : '<div class="p-1 bg-rose-100 rounded-full"><i data-lucide="x" class="w-3.5 h-3.5 text-rose-600"></i></div>'}</span>
                    <span class="text-xs font-black uppercase tracking-wider ${r.passed ? 'text-slate-700' : 'text-rose-400 line-through'}">${r.text}</span>
                </div>
            `).join('');

            const blocksHtml = data.blocking_violations && data.blocking_violations.length > 0 
                ? '<div class="mt-4 p-4 bg-rose-50 rounded-2xl border border-rose-100 shadow-sm"><p class="text-[9px] font-black text-rose-600 uppercase tracking-[0.2em] mb-2.5 flex items-center gap-2"><i data-lucide="alert-octagon" class="w-3.5 h-3.5"></i> Critical Deviation Factors</p><ul class="space-y-1.5">' + data.blocking_violations.map(b => `<li class="text-[10px] text-rose-900 font-black uppercase tracking-tight flex items-start gap-2"><span class="w-1.5 h-1.5 bg-rose-500 rounded-full mt-1 shrink-0"></span> ${b}</li>`).join('') + '</ul></div>'
                : '<div class="mt-4 p-4 bg-emerald-50 rounded-2xl border border-emerald-100 shadow-sm"><p class="text-[9px] font-black text-emerald-700 uppercase tracking-[0.2em] text-center flex justify-center items-center gap-2"><i data-lucide="shield-check" class="w-3.5 h-3.5"></i> All Security Protocols Passed</p></div>';

            // Chronological Incentive Table Rows
            let incentiveRowsHtml = '';
            if (data.incentive_breakdown && data.incentive_breakdown.length > 0) {
                data.incentive_breakdown.forEach(b => {
                    const notes = (b.notes || '').toLowerCase();
                    const actualBound = parseFloat(b.actual_boundary || 0);
                    const isEarned = Boolean(b.has_incentive && (b.status === 'paid' || b.status === 'excess'));
                    const incentiveAmount = isEarned ? (actualBound * 0.05) : 0;

                    let reasonBadge = '';
                    if (isEarned) {
                        reasonBadge = '<span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-[9px] font-black uppercase tracking-wider inline-flex items-center gap-1.5"><i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-600"></i> Qualified: Clean Shift</span>';
                    } else {
                        if (notes.includes('vehicle damaged')) {
                            reasonBadge = '<span class="px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded-lg text-[9px] font-black uppercase tracking-wider inline-flex items-center gap-1.5"><i data-lucide="alert-triangle" class="w-3.5 h-3.5 text-rose-600"></i> Voided: Vehicle Damage</span>';
                        } else if (notes.includes('maintenance')) {
                            reasonBadge = '<span class="px-2.5 py-1 bg-orange-50 text-orange-700 border border-orange-200 rounded-lg text-[9px] font-black uppercase tracking-wider inline-flex items-center gap-1.5"><i data-lucide="wrench" class="w-3.5 h-3.5 text-orange-600"></i> Voided: Maintenance Breakdown</span>';
                        } else if (b.status === 'shortage' || parseFloat(b.shortage || 0) > 0) {
                            reasonBadge = '<span class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg text-[9px] font-black uppercase tracking-wider inline-flex items-center gap-1.5"><i data-lucide="wallet" class="w-3.5 h-3.5 text-amber-600"></i> Voided: Boundary Shortage</span>';
                        } else if (b.is_absent || b.status === 'absent') {
                            reasonBadge = '<span class="px-2.5 py-1 bg-purple-50 text-purple-700 border border-purple-200 rounded-lg text-[9px] font-black uppercase tracking-wider inline-flex items-center gap-1.5"><i data-lucide="user-x" class="w-3.5 h-3.5 text-purple-600"></i> Voided: Unattended Shift</span>';
                        } else {
                            reasonBadge = '<span class="px-2.5 py-1 bg-slate-100 text-slate-700 border border-slate-200 rounded-lg text-[9px] font-black uppercase tracking-wider inline-flex items-center gap-1.5"><i data-lucide="clock" class="w-3.5 h-3.5 text-slate-500"></i> Voided: Late Remittance / Cutoff</span>';
                        }
                    }

                    const rewardAmountBadge = isEarned
                        ? `<span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg font-black text-xs font-mono inline-block">+₱${incentiveAmount.toLocaleString('en-PH', {minimumFractionDigits: 2})}</span>`
                        : `<span class="px-2.5 py-1 bg-slate-100 text-slate-400 rounded-lg font-bold text-xs font-mono inline-block">₱0.00</span>`;

                    const outcomeBadge = isEarned
                        ? '<span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 border border-emerald-300 rounded-lg text-[9px] font-black uppercase tracking-widest inline-flex items-center gap-1 shadow-2xs"><i data-lucide="check-check" class="w-3 h-3"></i> EARNED (5%)</span>'
                        : '<span class="px-2.5 py-1 bg-rose-100 text-rose-700 border border-rose-200 rounded-lg text-[9px] font-black uppercase tracking-widest inline-flex items-center gap-1"><i data-lucide="x-circle" class="w-3 h-3"></i> FORFEITED</span>';

                    incentiveRowsHtml += `
                    <tr class="border-b border-slate-100 hover:bg-slate-50/80 transition-colors ${isEarned ? '' : 'bg-rose-50/20'}">
                        <td class="p-4 font-bold text-slate-700 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                                <span>${new Date(b.date).toLocaleDateString('en-PH', {month: 'short', day: 'numeric', year: 'numeric'})}</span>
                            </div>
                        </td>
                        <td class="p-4 font-black font-mono text-slate-900 whitespace-nowrap">
                            <span class="px-2 py-1 bg-slate-100 border border-slate-200 rounded-lg text-xs">${b.plate_number || '—'}</span>
                        </td>
                        <td class="p-4 font-bold text-slate-600 whitespace-nowrap">₱${actualBound.toLocaleString('en-PH', {minimumFractionDigits: 2})}</td>
                        <td class="p-4 whitespace-nowrap">${rewardAmountBadge}</td>
                        <td class="p-4 whitespace-nowrap">${outcomeBadge}</td>
                        <td class="p-4 whitespace-nowrap">${reasonBadge}</td>
                    </tr>`;
                });
            } else {
                incentiveRowsHtml = '<tr><td colspan="6" class="p-8 text-center text-slate-400 font-bold uppercase tracking-widest text-[10px]">No shift incentive records found for this driver</td></tr>';
            }

            const incentivesEl = document.getElementById('incentivesContent');
            if (incentivesEl) {
                const totalIncentiveCash = parseFloat(data.total_incentive_earned !== undefined && data.total_incentive_earned !== null ? data.total_incentive_earned : (data.monthly_incentive || 0));
                incentivesEl.innerHTML = `
                    <!-- SECTION 1: Operational Excellence & Strategic Intelligence (2 Columns) -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <!-- Left Subcolumn: Status Banner + Premium Reward Manifest + Strategic Index -->
                        <div class="space-y-4 flex flex-col justify-between">
                            <!-- Dynamic Status Banner -->
                            ${eligStatus}

                            <!-- Premium Reward Manifest Card -->
                            <div class="bg-slate-900 rounded-3xl border border-slate-800 shadow-xl overflow-hidden">
                                <div class="bg-gradient-to-r from-amber-400 to-amber-500 px-4 py-2.5 text-center shadow-md">
                                    <p class="text-slate-950 font-black text-xs uppercase tracking-[0.2em] flex justify-center items-center gap-2">
                                        <i data-lucide="gift" class="w-4 h-4"></i> Premium Reward Manifest
                                    </p>
                                </div>
                                <div class="p-5 grid grid-cols-3 gap-3 text-center items-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-12 h-12 sm:w-14 sm:h-14 mb-2">
                                            <img src="{{ asset("image/kpi/reward_ticket_3d.svg") }}" alt="Free Coding" class="w-full h-full object-contain filter drop-shadow-md">
                                        </div>
                                        <span class="text-[10px] font-black text-slate-300 uppercase tracking-wider leading-tight">Free<br>Coding</span>
                                    </div>
                                    <div class="flex flex-col items-center justify-center border-x border-slate-800 px-2">
                                        <div class="w-12 h-12 sm:w-14 sm:h-14 mb-2">
                                            <img src="{{ asset("image/kpi/reward_rice_3d.svg") }}" alt="25kg Premium Rice" class="w-full h-full object-contain filter drop-shadow-md">
                                        </div>
                                        <span class="text-[10px] font-black text-slate-300 uppercase tracking-wider leading-tight">25kg Premium<br>Rice</span>
                                    </div>
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-12 h-12 sm:w-14 sm:h-14 mb-2">
                                            <img src="{{ asset("image/kpi/reward_cash_3d.svg") }}" alt="₱500 Cash" class="w-full h-full object-contain filter drop-shadow-md">
                                        </div>
                                        <span class="text-[10px] font-black text-slate-300 uppercase tracking-wider leading-tight">₱500 Performance<br>Cash</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Strategic Index Card -->
                            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-5 flex items-center justify-between shadow-xl relative overflow-hidden">
                                <div class="relative z-10">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-1">Fleet Strategic Index</span>
                                    <p class="text-[11px] text-slate-400 font-semibold leading-relaxed">Composite velocity of incentive consistency<br>& zero safety anomalies.</p>
                                </div>
                                <div class="flex items-center gap-3 relative z-10 shrink-0">
                                    <div class="w-11 h-11">
                                        <img src="{{ asset("image/kpi/fleet_index_3d.svg") }}" alt="Strategic Index" class="w-full h-full object-contain filter drop-shadow-md">
                                    </div>
                                    <div class="text-right">
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Index Score</span>
                                        <span class="text-3xl font-black ${scoreColor}">${Math.round(score)}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Subcolumn: Excellence Verification Protocols & Lookback Period -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100 flex-wrap gap-2">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] flex items-center gap-1.5">
                                        <i data-lucide="shield-check" class="w-3.5 h-3.5 text-blue-500"></i> Verification Protocols
                                    </span>
                                    <span class="px-2.5 py-0.5 bg-blue-50 text-blue-700 rounded-full text-[9px] font-black uppercase tracking-wider border border-blue-200">
                                        ${data.is_dual_driver ? '2 Months (Dual Driver)' : '1 Month (Solo Driver)'} Lookback (${data.lookback_days || 30}D)
                                    </span>
                                </div>
                                <div class="divide-y divide-slate-100">${reqsHtml}</div>
                            </div>
                            ${blocksHtml}
                        </div>
                    </div>

                    <!-- SECTION 2: 4 KPI Summary Cards -->
                    <div class="mb-8">
                        <div class="flex items-center gap-2 mb-3">
                            <i data-lucide="gauge" class="w-4 h-4 text-emerald-600"></i>
                            <h5 class="text-xs font-black text-slate-800 uppercase tracking-wider">Incentive Performance & Revenue Share Metrics</h5>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Card 1: Total Earned Incentive -->
                            <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-br from-white via-emerald-50/40 to-teal-50/20 p-5 shadow-xs">
                                <div class="flex items-center justify-between gap-3 relative z-10">
                                    <div class="min-w-0 flex-1">
                                        <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-400 block mb-1">
                                            Earned Incentive
                                        </span>
                                        <div class="text-xl sm:text-2xl font-black text-emerald-600 leading-tight tracking-tight tabular-nums truncate">
                                            ₱${totalIncentiveCash.toLocaleString('en-PH',{minimumFractionDigits:2})}
                                        </div>
                                        <div class="mt-2 flex items-center gap-1.5 text-[11px] font-bold text-emerald-600">
                                            <span class="inline-flex h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            <span>5% Net Revenue Share</span>
                                        </div>
                                    </div>
                                    <div class="w-12 h-12 sm:w-14 sm:h-14 shrink-0">
                                        <img src="{{ asset("image/kpi/reward_cash_3d.svg") }}" alt="Earned Incentive" class="w-full h-full object-contain filter drop-shadow-md">
                                    </div>
                                </div>
                            </div>

                            <!-- Card 2: Service Cycles -->
                            <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-br from-white via-blue-50/40 to-indigo-50/20 p-5 shadow-xs">
                                <div class="flex items-center justify-between gap-3 relative z-10">
                                    <div class="min-w-0 flex-1">
                                        <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-400 block mb-1">
                                            Service Cycles
                                        </span>
                                        <div class="text-xl sm:text-2xl font-black text-slate-900 leading-tight tracking-tight tabular-nums truncate">
                                            ${data.incentive_earned_count||0} / ${data.total_shifts_month||0}
                                        </div>
                                        <div class="mt-2 flex items-center gap-1.5 text-[11px] font-bold text-blue-600">
                                            <span class="inline-flex h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                            <span>${data.incentive_earned_count||0} Shift(s) Qualified</span>
                                        </div>
                                    </div>
                                    <div class="w-12 h-12 sm:w-14 sm:h-14 shrink-0">
                                        <img src="{{ asset("image/kpi/service_cycle_3d.svg") }}" alt="Service Cycles" class="w-full h-full object-contain filter drop-shadow-md">
                                    </div>
                                </div>
                            </div>

                            <!-- Card 3: Quality Index -->
                            <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-br from-white via-purple-50/40 to-indigo-50/20 p-5 shadow-xs">
                                <div class="flex items-center justify-between gap-3 relative z-10">
                                    <div class="min-w-0 flex-1">
                                        <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-400 block mb-1">
                                            Incentive Rate
                                        </span>
                                        <div class="text-xl sm:text-2xl font-black ${rateColor} leading-tight tracking-tight tabular-nums truncate">
                                            ${incentiveRate}%
                                        </div>
                                        <div class="w-full bg-slate-100 rounded-full h-1.5 mt-2 overflow-hidden">
                                            <div class="${rateBar} h-1.5 rounded-full transition-all duration-700" style="width:${incentiveRate}%"></div>
                                        </div>
                                    </div>
                                    <div class="w-12 h-12 sm:w-14 sm:h-14 shrink-0">
                                        <img src="{{ asset("image/kpi/quality_index_3d.svg") }}" alt="Quality Index" class="w-full h-full object-contain filter drop-shadow-md">
                                    </div>
                                </div>
                            </div>

                            <!-- Card 4: Friction Points -->
                            <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-br from-white via-rose-50/40 to-red-50/20 p-5 shadow-xs">
                                <div class="flex items-start justify-between gap-3 relative z-10">
                                    <div class="min-w-0 flex-1">
                                        <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-400 block mb-1.5">
                                            Friction Points
                                        </span>
                                        <div class="grid grid-cols-2 gap-x-3 gap-y-1 text-[10px] font-black uppercase">
                                            <div class="flex justify-between text-slate-600">Damage: <span class="${(data.damage_missed||0) > 0 ? 'text-rose-600 font-bold' : 'text-slate-900'}">${data.damage_missed||0}</span></div>
                                            <div class="flex justify-between text-slate-600">Late: <span class="${(data.late_turn_missed||0) > 0 ? 'text-rose-600 font-bold' : 'text-slate-900'}">${data.late_turn_missed||0}</span></div>
                                            <div class="flex justify-between text-slate-600">Shortage: <span class="${(data.shortage_missed||0) > 0 ? 'text-rose-600 font-bold' : 'text-slate-900'}">${data.shortage_missed||0}</span></div>
                                            <div class="flex justify-between text-slate-600">Behavior: <span class="${(data.behavior_missed||0) > 0 ? 'text-rose-600 font-bold' : 'text-slate-900'}">${data.behavior_missed||0}</span></div>
                                        </div>
                                    </div>
                                    <div class="w-12 h-12 sm:w-14 sm:h-14 shrink-0">
                                        <img src="{{ asset("image/kpi/friction_points_3d.svg") }}" alt="Friction Points" class="w-full h-full object-contain filter drop-shadow-md">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 3: Shift-by-Shift Incentive Reward Ledger -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                        <div class="p-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between flex-wrap gap-2">
                            <div class="flex items-center gap-2">
                                <i data-lucide="award" class="w-4 h-4 text-emerald-600"></i>
                                <div>
                                    <h5 class="text-xs font-black text-slate-800 uppercase tracking-wider">Shift-by-Shift Incentive Reward Ledger</h5>
                                    <p class="text-[10px] text-slate-400 font-medium">Record of 5% reward calculations, qualification status, and policy factors per shift.</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-mono font-bold text-slate-500 bg-white px-2.5 py-1 rounded-md border border-slate-200">${data.incentive_breakdown ? data.incentive_breakdown.length : 0} Shift Record(s)</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-left">
                                <thead class="bg-slate-50/50 text-slate-400 font-black uppercase tracking-widest border-b border-slate-100">
                                    <tr>
                                        <th class="p-4">Shift Date</th>
                                        <th class="p-4">Taxi Unit</th>
                                        <th class="p-4">Shift Remittance</th>
                                        <th class="p-4">5% Reward Amount</th>
                                        <th class="p-4">Reward Status</th>
                                        <th class="p-4">Qualification / Policy Factor</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">${incentiveRowsHtml}</tbody>
                            </table>
                        </div>
                    </div>
                `;
            }

            // ===================== PERFORMANCE TAB =====================
            let perfRowsHtml = '';
            if (data.recent_performance && data.recent_performance.length > 0) {
                data.recent_performance.forEach(log => {
                    const shortage = parseFloat(log.shortage||0);
                    const excess   = parseFloat(log.excess||0);
                    perfRowsHtml += `
                    <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors">
                        <td class="p-4 font-bold text-slate-600">${new Date(log.date).toLocaleDateString('en-PH',{month:'short',day:'numeric',year:'numeric'})}</td>
                        <td class="p-4 font-black text-slate-800">${log.plate_number||'N/A'}</td>
                        <td class="p-4 text-slate-500 font-medium">₱${parseFloat(log.boundary_amount||0).toLocaleString('en-PH',{minimumFractionDigits:2})}</td>
                        <td class="p-4 font-black text-slate-900">₱${parseFloat(log.actual_boundary||0).toLocaleString('en-PH',{minimumFractionDigits:2})}</td>
                        <td class="p-4 text-[10px] uppercase tracking-widest ${log.status === 'paid' ? 'text-emerald-600 font-black' : log.status === 'shortage' ? 'text-rose-600 font-black' : 'text-blue-600 font-black'}">${(log.status||'')}</td>
                        <td class="p-4">${shortage > 0 ? '<span class="px-2 py-0.5 bg-rose-50 text-rose-600 rounded-lg font-black">-₱'+parseFloat(shortage).toLocaleString()+'</span>' : excess > 0 ? '<span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded-lg font-black">+₱'+parseFloat(excess).toLocaleString()+'</span>' : '<span class="text-emerald-600 font-black">—</span>'}</td>
                        <td class="p-4 text-center">${log.has_incentive ? '<i data-lucide="check-circle" class="w-4 h-4 text-emerald-500 mx-auto"></i>' : '<i data-lucide="x-circle" class="w-4 h-4 text-rose-400 mx-auto"></i>'}</td>
                    </tr>`;
                });
            } else {
                perfRowsHtml = '<tr><td colspan="7" class="p-8 text-center text-slate-400 font-bold uppercase tracking-widest text-[10px]">Telemetry data unavailable</td></tr>';
            }

            // Behavior incidents section
            let incidentRowsHtml = '';
            if (data.incidents && data.incidents.length > 0) {
                const sevColors = {critical:'bg-rose-100 text-rose-700 border-rose-200',high:'bg-orange-100 text-orange-700 border-orange-200',medium:'bg-amber-100 text-amber-700 border-amber-200',low:'bg-blue-100 text-blue-700 border-blue-200'};
                data.incidents.forEach(i => {
                    incidentRowsHtml += `
                    <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors">
                        <td class="p-4 font-bold text-slate-600">${new Date(i.created_at).toLocaleDateString('en-PH',{month:'short',day:'numeric'})}</td>
                        <td class="p-4 font-black text-slate-800">${i.plate_number||'—'}</td>
                        <td class="p-4"><span class="px-2 py-1 border rounded-lg text-[9px] font-black uppercase tracking-widest bg-slate-100 text-slate-700">${(i.incident_type||'').replace('_',' ')}</span></td>
                        <td class="p-4"><span class="px-2 py-1 border rounded-lg text-[9px] font-black uppercase tracking-widest ${sevColors[i.severity]||'bg-slate-100 text-slate-600'}">${(i.severity||'')}</span></td>
                        <td class="p-4 text-[11px] font-bold text-slate-500 max-w-[220px] truncate" title="${i.description||''}">${i.description||''}</td>
                    </tr>`;
                });
            } else {
                incidentRowsHtml = '<tr><td colspan="5" class="p-8 text-center text-slate-400 font-bold uppercase tracking-widest text-[10px]">No behavioral anomalies detected</td></tr>';
            }

            // Absences section
            let absenceRowsHtml = '';
            if (data.absentee_logs && data.absentee_logs.length > 0) {
                data.absentee_logs.forEach(a => {
                    absenceRowsHtml += `
                    <tr class="border-b border-slate-50 hover:bg-rose-50/20 transition-colors">
                        <td class="p-4 text-rose-600 font-black tracking-tight">${new Date(a.date).toLocaleDateString('en-PH',{month:'short',day:'numeric',year:'numeric'})}</td>
                        <td class="p-4 text-slate-600 font-bold"><span class="px-3 py-1 bg-white border border-slate-200 rounded-xl text-[11px]">RELIEF: <strong>${a.first_name||''} ${a.last_name||''}</strong></span></td>
                        <td class="p-4 text-right"><span class="px-2 py-1 rounded-lg text-[9px] font-black tracking-[0.2em] bg-rose-100 text-rose-700 uppercase">Unattended</span></td>
                    </tr>`;
                });
            } else {
                absenceRowsHtml = '<tr><td colspan="3" class="p-8 text-center text-slate-400 font-bold uppercase tracking-widest text-[10px]">Perfect attendance record detected</td></tr>';
            }

            document.getElementById('performanceContent').innerHTML = `
                <div class="grid grid-cols-3 gap-4 mb-8">
                    <div class="bg-slate-900 rounded-2xl p-5 border border-slate-800 shadow-xl relative overflow-hidden group">
                        <div class="absolute right-0 top-0 p-2"><i data-lucide="star" class="w-4 h-4 text-amber-400 fill-amber-400"></i></div>
                        <p class="text-[9px] text-slate-400 font-black uppercase tracking-[0.2em] mb-2">Aggregate Rating</p>
                        <p class="text-2xl font-black text-white">${data.performance_rating ? data.performance_rating.label : 'N/A'}</p>
                        <p class="text-[10px] text-amber-400 font-bold mt-1">${data.performance_rating ? '★'.repeat(data.performance_rating.stars) + '☆'.repeat(Math.max(0, 5 - data.performance_rating.stars)) : ''}</p>
                    </div>
                    <div class="bg-rose-50 rounded-2xl p-5 border border-rose-100 shadow-sm group">
                        <p class="text-[9px] text-rose-500 font-black uppercase tracking-[0.2em] mb-2">30D Incidents</p>
                        <p class="text-2xl font-black text-rose-900">${data.total_incidents_30d||0}</p>
                    </div>
                    <div class="bg-orange-50 rounded-2xl p-5 border border-orange-100 shadow-sm group">
                        <p class="text-[9px] text-orange-500 font-black uppercase tracking-[0.2em] mb-2">Critical Events</p>
                        <p class="text-2xl font-black text-orange-900">${data.high_severity_incidents||0}</p>
                    </div>
                </div>
                
                <div class="space-y-10">
                    <div>
                        <div class="flex items-center justify-between mb-4 px-1">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2"><i data-lucide="clock" class="w-3.5 h-3.5"></i> Service Continuity (Absences)</p>
                        </div>
                        <div class="overflow-x-auto rounded-2xl border border-slate-100 shadow-sm">
                            <table class="w-full text-xs text-left">
                                <thead class="bg-slate-50 text-slate-400 font-black uppercase tracking-widest">
                                    <tr><th class="p-4">Schedule Date</th><th class="p-4">Relief Driver</th><th class="p-4 text-right">Status</th></tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">${absenceRowsHtml}</tbody>
                            </table>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-4 px-1">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2"><i data-lucide="bar-chart-3" class="w-3.5 h-3.5"></i> Telemetry History (Last 10)</p>
                        </div>
                        <div class="overflow-x-auto rounded-2xl border border-slate-100 shadow-sm">
                            <table class="w-full text-xs text-left">
                                <thead class="bg-slate-50 text-slate-400 font-black uppercase tracking-widest border-b border-slate-100">
                                    <tr><th class="p-4">Date</th><th class="p-4">Unit</th><th class="p-4">Target</th><th class="p-4">Actual</th><th class="p-4">Status</th><th class="p-4">Variance</th><th class="p-4 text-center">Reward</th></tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">${perfRowsHtml}</tbody>
                            </table>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-4 px-1">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2"><i data-lucide="shield-alert" class="w-3.5 h-3.5"></i> Behavioral Logs</p>
                        </div>
                        <div class="overflow-x-auto rounded-2xl border border-slate-100 shadow-sm">
                            <table class="w-full text-xs text-left">
                                <thead class="bg-slate-50 text-slate-400 font-black uppercase tracking-widest border-b border-slate-100">
                                    <tr><th class="p-4">Date</th><th class="p-4">Vessel</th><th class="p-4">Classification</th><th class="p-4">Severity</th><th class="p-4">Telemetry Notes</th></tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">${incidentRowsHtml}</tbody>
                            </table>
                        </div>
                    </div>
                </div>`;

            lucide.createIcons();
        });
    }

    function closeDriverDetails() {
        document.getElementById('driverDetailsModalContainer').classList.add('scale-95');
        setTimeout(() => {
            document.getElementById('driverDetailsModal').classList.add('hidden');
        }, 150);
    }

    function previewDocumentVault(input, docId) {
        const file = input.files[0];
        const previewBox = document.getElementById(`preview_box_${docId}`);
        if (!previewBox) return;

        if (file) {
            const isPdf = file.name.toLowerCase().endsWith('.pdf');
            if (isPdf) {
                previewBox.outerHTML = `
                    <div id="preview_box_${docId}" class="mt-2 mb-3 bg-blue-50 border border-blue-200 rounded-lg h-32 flex flex-col items-center justify-center text-blue-600 ring-2 ring-blue-500 ring-offset-1 transition-all">
                        <i data-lucide="file-check-2" class="w-8 h-8 mb-2"></i>
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-700 px-4 text-center truncate w-full" title="${file.name}">${file.name}</span>
                        <span class="text-[8px] font-black uppercase tracking-widest text-blue-700 mt-1 bg-blue-200/50 px-2 py-0.5 rounded-full">Ready to Upload</span>
                    </div>
                `;
                if (typeof lucide !== 'undefined') lucide.createIcons();
            } else {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewBox.outerHTML = `
                        <div id="preview_box_${docId}" class="mt-2 mb-3 ring-2 ring-blue-500 ring-offset-1 transition-all rounded-lg">
                            <div class="relative w-full h-32 bg-slate-100 rounded-lg overflow-hidden border border-slate-200 group cursor-pointer" onclick="openImageModal('${e.target.result}')">
                                <img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <span class="bg-white/90 text-slate-800 text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full flex items-center gap-1 shadow-xl">
                                        <i data-lucide="maximize-2" class="w-3 h-3"></i> View Preview
                                    </span>
                                </div>
                            </div>
                        </div>
                    `;
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
                reader.readAsDataURL(file);
            }
        } else {
            // Revert back
            previewBox.outerHTML = `
                <div id="preview_box_${docId}" class="mt-2 mb-3 bg-slate-50 border border-dashed border-slate-200 rounded-lg h-32 flex flex-col items-center justify-center text-slate-400">
                    <i data-lucide="image-off" class="w-6 h-6 mb-2 opacity-50"></i>
                    <span class="text-[9px] font-black uppercase tracking-widest opacity-60">No Document</span>
                </div>
            `;
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    }

    function viewDriverModalAvatar() {
        const avatarEl = document.getElementById('driverDetailsAvatar');
        if (avatarEl && avatarEl.src) {
            openImageModal(avatarEl.src);
        }
    }

    function openImageModal(src) {
        let modal = document.getElementById('imagePreviewModalOverlay');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'imagePreviewModalOverlay';
            modal.className = 'fixed inset-0 bg-slate-900/95 backdrop-blur-sm z-[9999] flex items-center justify-center hidden opacity-0 transition-opacity duration-300';
            modal.innerHTML = `
                <button type="button" class="absolute top-6 right-6 text-white/50 hover:text-white bg-white/10 hover:bg-white/20 p-3 rounded-full transition-colors group" onclick="closeImageModal()">
                    <i data-lucide="x" class="w-6 h-6 group-hover:scale-110 transition-transform"></i>
                </button>
                <div class="relative w-full max-w-[90vw] h-[90vh] flex justify-center items-center">
                    <img id="imagePreviewModalImg" src="" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl scale-95 transition-transform duration-300" />
                </div>
            `;
            document.body.appendChild(modal);
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
        
        const img = document.getElementById('imagePreviewModalImg');
        img.src = src;
        
        modal.classList.remove('hidden');
        // Trigger reflow
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

    window.switchDriverTab = function(tabName) {
        document.querySelectorAll('.driver-tab').forEach(t => {
            t.classList.remove('border-blue-500', 'text-blue-600', 'active', 'border-yellow-500', 'text-yellow-600');
            t.classList.add('border-transparent', 'text-slate-400');
            if (t.dataset.tab === tabName) {
                t.classList.add('border-blue-500', 'text-blue-600', 'active');
                t.classList.remove('border-transparent', 'text-slate-400');
            }
        });
        document.querySelectorAll('.driver-tab-panel').forEach(p => {
            if (p.dataset.tabPanel === tabName) {
                p.classList.remove('hidden');
            } else {
                p.classList.add('hidden');
            }
        });
        if (typeof lucide !== 'undefined') lucide.createIcons();
    };

    document.querySelectorAll('.driver-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            if (tab.dataset.tab) {
                switchDriverTab(tab.dataset.tab);
            }
        });
    });

    document.getElementById('driverDocumentsForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const driverId = document.getElementById('driverDocumentsDriverId').value;
        const btn = this.querySelector('button[type="submit"]');
        const origHtml = btn.innerHTML;
        
        // Check if there are any files selected
        const fileInputs = this.querySelectorAll('input[type="file"]');
        let hasFiles = false;
        fileInputs.forEach(input => {
            if(input.files.length > 0) hasFiles = true;
        });
        
        if(!hasFiles) {
            if(typeof showNotification === 'function') showNotification('Please select at least one document to upload.', 'error');
            else alert('Please select at least one document to upload.');
            return;
        }
        
        btn.disabled = true;
        btn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 text-blue-400 animate-spin"></i> Uploading...';
        if(typeof lucide !== 'undefined') lucide.createIcons();
        
        const formData = new FormData(this);
        
        fetch(`/driver-management/upload-documents/${driverId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = origHtml;
            if(typeof lucide !== 'undefined') lucide.createIcons();
            
            if (data.success) {
                if(typeof showNotification === 'function') showNotification(data.message, 'success');
                else alert(data.message);
                
                // Clear file inputs
                this.reset();
                
                // Reload the modal data to show updated images
                openDriverDetails(driverId);
            } else {
                alert('Error: ' + (data.message || 'Upload failed.'));
            }
        })
        .catch(err => {
            console.error(err);
            btn.disabled = false;
            btn.innerHTML = origHtml;
            if(typeof lucide !== 'undefined') lucide.createIcons();
            alert('An error occurred during upload.');
        });
    });
</script>
