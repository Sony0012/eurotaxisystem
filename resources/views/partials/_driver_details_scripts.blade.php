    function openDriverDetails(id) {
        const modal = document.getElementById('driverDetailsModal');
        modal.classList.remove('hidden');

        document.querySelectorAll('.driver-tab').forEach(btn => {
            btn.classList.remove('border-yellow-500', 'text-yellow-600', 'active');
            btn.classList.add('border-transparent', 'text-gray-500');
        });
        document.querySelectorAll('.driver-tab-panel').forEach(panel => { panel.classList.add('hidden'); });
        
        const firstTab = document.querySelector('.driver-tab[data-tab="basic"]');
        const firstPanel = document.querySelector('.driver-tab-panel[data-tab-panel="basic"]');
        if (firstTab && firstPanel) {
            firstTab.classList.add('border-yellow-500', 'text-yellow-600', 'active');
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
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-3">Outstanding Liabilities</span>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="p-3.5 rounded-xl border ${unpaidShortage > 0 ? 'bg-rose-50/80 border-rose-200 text-rose-800' : 'bg-slate-50 border-slate-100 text-slate-700'}">
                                <span class="text-[9px] font-black uppercase tracking-wider text-slate-400 block mb-1">Unpaid Shortage</span>
                                <p class="text-sm font-black ${unpaidShortage > 0 ? 'text-rose-600' : 'text-slate-900'}">₱${unpaidShortage.toLocaleString('en-PH', {minimumFractionDigits: 2})}</p>
                            </div>
                            <div class="p-3.5 rounded-xl border ${pendingDebt > 0 ? 'bg-amber-50/80 border-amber-200 text-amber-800' : 'bg-slate-50 border-slate-100 text-slate-700'}">
                                <span class="text-[9px] font-black uppercase tracking-wider text-slate-400 block mb-1">Pending Debt</span>
                                <p class="text-sm font-black ${pendingDebt > 0 ? 'text-amber-600' : 'text-slate-900'}">₱${pendingDebt.toLocaleString('en-PH', {minimumFractionDigits: 2})}</p>
                            </div>
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
                                <p class="text-sm font-black text-white">₱${data.assigned_boundary_rate ? parseFloat(data.assigned_boundary_rate).toLocaleString('en-PH', {minimumFractionDigits:2}) : '0.00'}</p>
                            </div>
                            <div class="p-3 rounded-xl bg-blue-950/60 border border-blue-500/30">
                                <span class="text-[9px] font-bold text-blue-300 uppercase tracking-wider block mb-1">Active Targeted Rate</span>
                                <p class="text-sm font-black text-blue-400">₱${data.daily_boundary_target ? parseFloat(data.daily_boundary_target).toLocaleString('en-PH', {minimumFractionDigits:2}) : '0.00'}</p>
                            </div>
                        </div>
                        ${data.current_pricing && data.current_pricing.label ? `
                            <div class="mt-3 bg-blue-500/10 border border-blue-500/20 px-3 py-1.5 rounded-lg flex items-center justify-between text-xs">
                                <span class="text-blue-300 font-bold">Applied Pricing Scheme:</span>
                                <span class="text-blue-400 font-black uppercase text-[10px]">${data.current_pricing.label}</span>
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
                            <span class="text-xs font-black text-emerald-600">${data.paid_shifts_count || 0} / ${data.shifts_count || 0} Paid Shifts</span>
                        </div>
                        <div class="grid grid-cols-2 gap-3 pt-1">
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-center">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Missed Incentives</span>
                                <span class="text-xs font-black text-slate-700">${data.missed_incentive_count || 0}</span>
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
                <div>
                    <p><span class="font-semibold text-gray-500">License Number:</span> ${data.license_number || ''}</p>
                    <p><span class="font-semibold text-gray-500">License Expiry:</span> ${data.license_expiry || ''}</p>
                </div>
                <div class="bg-blue-50 p-3 rounded-lg border border-blue-100">
                    <p class="text-[11px] text-blue-700 font-medium">Auto-Status Detection</p>
                    <p class="text-xs text-blue-600 mt-1">Based on expiry date: No active issues detected.</p>
                </div>
            `;

            // ===================== INCENTIVES TAB =====================
            const incentiveRate = data.incentive_rate || 0;
            const rateColor = incentiveRate >= 80 ? 'text-green-600' : incentiveRate >= 50 ? 'text-yellow-600' : 'text-red-600';
            const rateBar  = incentiveRate >= 80 ? 'bg-green-500' : incentiveRate >= 50 ? 'bg-yellow-400' : 'bg-red-500';

            let incentiveRowsHtml = '';
            if (data.incentive_breakdown && data.incentive_breakdown.length > 0) {
                data.incentive_breakdown.forEach(b => {
                    const notes = (b.notes || '').toLowerCase();
                    let reason = '';
                    if (!b.has_incentive) {
                        if (notes.includes('vehicle damaged')) reason = '<span class="text-orange-600 font-bold">Vehicle Damage</span>';
                        else if (notes.includes('maintenance')) reason = '<span class="text-red-600 font-bold">Breakdown</span>';
                        else reason = '<span class="text-gray-500">Late Turn</span>';
                    }
                    const statusColors = {paid:'text-green-600',shortage:'text-red-600',excess:'text-blue-600'};
                    incentiveRowsHtml += `
                    <tr class="border-b border-gray-50 ${b.has_incentive ? '' : 'bg-red-50/40'}">
                        <td class="p-2">${new Date(b.date).toLocaleDateString('en-PH',{month:'short',day:'numeric'})}</td>
                        <td class="p-2 font-bold">${b.plate_number||'—'}</td>
                        <td class="p-2">₱${parseFloat(b.actual_boundary||0).toLocaleString('en-PH',{minimumFractionDigits:2})}</td>
                        <td class="p-2 font-bold ${statusColors[b.status]||'text-gray-600'}">${(b.status||'').toUpperCase()}</td>
                        <td class="p-2 text-center">${b.has_incentive ? '<span class="text-green-600 font-black">✓</span>' : '<span class="text-red-500 font-black">✗</span>'}</td>
                        <td class="p-2">${reason}</td>
                    </tr>`;
                });
            } else {
                incentiveRowsHtml = '<tr><td colspan="6" class="p-4 text-center text-gray-400">No shifts recorded this month.</td></tr>';
            }

            document.getElementById('incentivesContent').innerHTML = `
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center">
                        <p class="text-[10px] text-green-500 font-black uppercase tracking-widest mb-1">Monthly Incentive</p>
                        <p class="text-xl font-black text-green-700">₱${parseFloat(data.monthly_incentive||0).toLocaleString('en-PH',{minimumFractionDigits:2})}</p>
                        <p class="text-[10px] text-green-500 mt-0.5">5% of eligible collections</p>
                    </div>
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-center">
                        <p class="text-[10px] text-blue-500 font-black uppercase tracking-widest mb-1">Shifts This Month</p>
                        <p class="text-xl font-black text-blue-700">${data.total_shifts_month||0}</p>
                        <p class="text-[10px] text-blue-500 mt-0.5">${data.incentive_earned_count||0} earned / ${data.incentive_missed_count||0} missed</p>
                    </div>
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center">
                        <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest mb-1">Incentive Rate</p>
                        <p class="text-xl font-black ${rateColor}">${incentiveRate}%</p>
                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1"><div class="${rateBar} h-1.5 rounded-full" style="width:${incentiveRate}%"></div></div>
                    </div>
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-center">
                        <p class="text-[10px] text-red-500 font-black uppercase tracking-widest mb-1">Missed Reasons</p>
                        <p class="text-[11px] text-red-700 font-bold">Late Turn: ${data.late_turn_missed||0}</p>
                        <p class="text-[11px] text-orange-600 font-bold">Vehicle Damage: ${data.damage_missed||0}</p>
                        <p class="text-[11px] text-red-600 font-bold">Breakdown: ${data.breakdown_missed||0}</p>
                    </div>
                </div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Per-Shift Incentive Log (Last 15)</p>
                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-gray-50 text-gray-500 font-bold">
                            <tr>
                                <th class="p-2">Date</th><th class="p-2">Unit</th><th class="p-2">Actual</th>
                                <th class="p-2">Status</th><th class="p-2 text-center">Incentive</th><th class="p-2">Reason (if missed)</th>
                            </tr>
                        </thead>
                        <tbody>${incentiveRowsHtml}</tbody>
                    </table>
                </div>`;

            // ===================== PERFORMANCE TAB =====================
            let perfRowsHtml = '';
            if (data.recent_performance && data.recent_performance.length > 0) {
                data.recent_performance.forEach(log => {
                    const statusColors = {paid:'text-green-600',shortage:'text-red-600',excess:'text-blue-600'};
                    const shortage = parseFloat(log.shortage||0);
                    const excess   = parseFloat(log.excess||0);
                    perfRowsHtml += `
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="p-2">${new Date(log.date).toLocaleDateString('en-PH',{month:'short',day:'numeric',year:'numeric'})}</td>
                        <td class="p-2 font-bold">${log.plate_number||'N/A'}</td>
                        <td class="p-2">₱${parseFloat(log.boundary_amount||0).toLocaleString('en-PH',{minimumFractionDigits:2})}</td>
                        <td class="p-2 font-bold">₱${parseFloat(log.actual_boundary||0).toLocaleString('en-PH',{minimumFractionDigits:2})}</td>
                        <td class="p-2 font-bold ${statusColors[log.status]||''}">${(log.status||'').toUpperCase()}</td>
                        <td class="p-2">${shortage > 0 ? '<span class="text-red-600">-₱'+parseFloat(shortage).toLocaleString()+'</span>' : excess > 0 ? '<span class="text-blue-600">+₱'+parseFloat(excess).toLocaleString()+'</span>' : '<span class="text-green-600">—</span>'}</td>
                        <td class="p-2 text-center">${log.has_incentive ? '<span class="text-green-500 font-black">✓</span>' : '<span class="text-red-400 font-black">✗</span>'}</td>
                    </tr>`;
                });
            } else {
                perfRowsHtml = '<tr><td colspan="7" class="p-4 text-center text-gray-400">No performance records found.</td></tr>';
            }

            // Behavior incidents section
            let incidentRowsHtml = '';
            if (data.incidents && data.incidents.length > 0) {
                const sevColors = {critical:'bg-red-100 text-red-700',high:'bg-orange-100 text-orange-700',medium:'bg-yellow-100 text-yellow-700',low:'bg-blue-100 text-blue-700'};
                data.incidents.forEach(i => {
                    incidentRowsHtml += `
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="p-2">${new Date(i.created_at).toLocaleDateString('en-PH',{month:'short',day:'numeric'})}</td>
                        <td class="p-2 font-bold">${i.plate_number||'—'}</td>
                        <td class="p-2"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700">${(i.incident_type||'').replace('_',' ').toUpperCase()}</span></td>
                        <td class="p-2"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold ${sevColors[i.severity]||'bg-gray-100 text-gray-600'}">${(i.severity||'').toUpperCase()}</span></td>
                        <td class="p-2 text-[10px] text-gray-500 max-w-[180px] truncate" title="${i.description||''}">${i.description||''}</td>
                    </tr>`;
                });
            } else {
                incidentRowsHtml = '<tr><td colspan="5" class="p-4 text-center text-gray-400">No behavior incidents recorded.</td></tr>';
            }

            // Absences section
            let absenceRowsHtml = '';
            if (data.absentee_logs && data.absentee_logs.length > 0) {
                data.absentee_logs.forEach(a => {
                    absenceRowsHtml += `
                    <tr class="border-b border-gray-50 hover:bg-red-50/50">
                        <td class="p-2 text-red-600 font-bold">${new Date(a.date).toLocaleDateString('en-PH',{month:'short',day:'numeric',year:'numeric'})}</td>
                        <td class="p-2 text-gray-600"><span class="px-2 py-0.5 bg-gray-100 rounded text-xs">Covered by: <strong>${a.first_name||''} ${a.last_name||''}</strong></span></td>
                        <td class="p-2"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700">ABSENT</span></td>
                    </tr>`;
                });
            } else {
                absenceRowsHtml = '<tr><td colspan="3" class="p-4 text-center text-gray-400">No unattended shifts (absences) on record.</td></tr>';
            }

            document.getElementById('performanceContent').innerHTML = `
                <div class="flex gap-3 mb-4">
                    <div class="flex-1 bg-gray-50 rounded-xl p-3 border border-gray-100 text-center">
                        <p class="text-[10px] text-gray-400 uppercase font-black tracking-wider mb-1">Performance Rating</p>
                        <p class="text-lg font-black text-yellow-600">${data.performance_rating||'—'}</p>
                    </div>
                    <div class="flex-1 bg-red-50 rounded-xl p-3 border border-red-100 text-center">
                        <p class="text-[10px] text-red-400 uppercase font-black tracking-wider mb-1">Incidents (30 days)</p>
                        <p class="text-lg font-black text-red-600">${data.total_incidents_30d||0}</p>
                    </div>
                    <div class="flex-1 bg-orange-50 rounded-xl p-3 border border-orange-100 text-center">
                        <p class="text-[10px] text-orange-400 uppercase font-black tracking-wider mb-1">High Severity</p>
                        <p class="text-lg font-black text-orange-600">${data.high_severity_incidents||0}</p>
                    </div>
                </div>
                
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Unattended Shifts / Absences (Last 10)</p>
                <div class="overflow-x-auto rounded-xl border border-gray-100 mb-5">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-gray-50 text-gray-500 font-bold">
                            <tr><th class="p-2">Expected Date</th><th class="p-2">Actual Driver</th><th class="p-2">Status</th></tr>
                        </thead>
                        <tbody>${absenceRowsHtml}</tbody>
                    </table>
                </div>

                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Boundary History (Last 10)</p>
                <div class="overflow-x-auto rounded-xl border border-gray-100 mb-5">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-gray-50 text-gray-500 font-bold">
                            <tr><th class="p-2">Date</th><th class="p-2">Unit</th><th class="p-2">Target</th><th class="p-2">Actual</th><th class="p-2">Status</th><th class="p-2">Diff</th><th class="p-2 text-center">Incentive</th></tr>
                        </thead>
                        <tbody>${perfRowsHtml}</tbody>
                    </table>
                </div>

                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Behavior Incidents (Last 10)</p>
                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-gray-50 text-gray-500 font-bold">
                            <tr><th class="p-2">Date</th><th class="p-2">Unit</th><th class="p-2">Type</th><th class="p-2">Severity</th><th class="p-2">Description</th></tr>
                        </thead>
                        <tbody>${incidentRowsHtml}</tbody>
                    </table>
                </div>`;

            // ===================== INSIGHTS TAB =====================
            const score = Math.max(0, Math.min(100,
                (data.incentive_rate||0) * 0.5
                + Math.max(0, 100 - (data.total_incidents_30d||0) * 10) * 0.3
                + (data.high_severity_incidents === 0 ? 20 : 0)
            ));
            const scoreColor = score >= 80 ? 'text-green-600' : score >= 50 ? 'text-yellow-600' : 'text-red-600';
            const scoreBar   = score >= 80 ? 'bg-green-500' : score >= 50 ? 'bg-yellow-400' : 'bg-red-500';

            const eligStatus = data.is_eligible && data.is_first_week 
                ? '<div class="bg-green-100 border border-green-300 text-green-800 p-4 rounded-xl mb-4 text-center"><h3 class="text-xl font-black uppercase mb-1">🎉 GRAND INCENTIVE SECURED</h3><p class="text-sm font-bold">Driver is eligible for the 1st Week Reward.</p></div>'
                : data.is_eligible && !data.is_first_week
                ? '<div class="bg-blue-100 border border-blue-300 text-blue-800 p-4 rounded-xl mb-4 text-center"><h3 class="text-lg font-black uppercase mb-1">✅ On Track for Grand Incentive</h3><p class="text-sm font-bold">Driver has 0 violations. Awaiting 1st week of the month.</p></div>'
                : '<div class="bg-red-100 border border-red-300 text-red-800 p-4 rounded-xl mb-4 text-center"><h3 class="text-lg font-black uppercase mb-1">❌ Not Eligible for Grand Incentive</h3><p class="text-sm font-bold">Driver has violations in the evaluation period.</p></div>';

            const reqList = [
                { passed: (data.violations_absences||0) === 0, text: 'No unattended shifts (Zero Absences)' },
                { passed: data.violations_no_incentive === 0, text: 'No skipped / late remittance returns' },
                { passed: (!data.damage_missed && data.damage_missed === 0) && data.violations_incidents === 0, text: 'Zero vehicle damage incidents' },
                { passed: (!data.breakdown_missed && data.breakdown_missed === 0), text: 'Zero breakdown incidents' },
                { passed: data.violations_incidents === 0, text: 'Zero behavioral / traffic violations' }
            ];

            const reqsHtml = reqList.map(r => `
                <div class="flex items-center gap-3 py-2 border-b border-gray-100 last:border-0">
                    <span class="text-lg">${r.passed ? '<i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>' : '<i data-lucide="x-circle" class="w-5 h-5 text-red-500"></i>'}</span>
                    <span class="text-sm font-bold ${r.passed ? 'text-gray-700' : 'text-red-600 line-through'}">${r.text}</span>
                </div>
            `).join('');

            const blocksHtml = data.blocking_violations && data.blocking_violations.length > 0 
                ? '<div class="mt-4 p-3 bg-red-50 rounded-lg border border-red-100"><p class="text-[10px] font-black text-red-600 uppercase tracking-widest mb-2">Blocking Violations Found</p><ul class="list-disc pl-4 text-xs text-red-800 font-bold space-y-1">' + data.blocking_violations.map(b => `<li>${b}</li>`).join('') + '</ul></div>'
                : '<div class="mt-4 p-3 bg-green-50 rounded-lg border border-green-100"><p class="text-xs font-black text-green-700 uppercase tracking-widest text-center">No blocking violations</p></div>';

            document.getElementById('insightsContent').innerHTML = `
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Grand Incentive Package (1st Week)</p>
                        ${eligStatus}
                        <div class="bg-gray-50 border border-gray-200 rounded-xl overflow-hidden">
                            <div class="bg-yellow-500 p-3 text-center">
                                <p class="text-white font-black text-lg uppercase tracking-tight shadow-sm">Reward Package</p>
                            </div>
                            <div class="p-4 flex gap-4 justify-center items-center">
                                <div class="text-center"><span class="block text-3xl mb-1">🎫</span><span class="text-[10px] font-black uppercase">Free<br>Coding</span></div>
                                <div class="w-px h-10 bg-gray-200"></div>
                                <div class="text-center"><span class="block text-3xl mb-1">🍚</span><span class="text-[10px] font-black uppercase">25kg<br>Rice</span></div>
                                <div class="w-px h-10 bg-gray-200"></div>
                                <div class="text-center"><span class="block text-3xl mb-1">💵</span><span class="text-[10px] font-black uppercase">₱500<br>Cash</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm">
                        <div class="flex justify-between items-center mb-4">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Eligibility Criteria</p>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-black uppercase tracking-wider">${data.is_dual_driver ? '2 Months (Dual Driver)' : '1 Month (Solo Driver)'} Lookback</span>
                        </div>
                        <p class="text-xs text-gray-500 mb-4 leading-relaxed tracking-wide">Driver is evaluated strictly against the last <strong class="text-gray-800">${data.lookback_days} days</strong>. Must have zero violations to claim.</p>
                        
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            ${reqsHtml}
                        </div>
                        ${blocksHtml}
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white border border-gray-100 rounded-xl p-4 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Overall Core Score</p>
                            <p class="text-[10px] text-gray-400">Based on incentive rate and total incidents.</p>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-black ${scoreColor}">${Math.round(score)}<span class="text-base font-medium text-gray-400">/100</span></p>
                        </div>
                    </div>
                </div>
            `;

            lucide.createIcons();
        });
    }

    function closeDriverDetails() {
        document.getElementById('driverDetailsModal').classList.add('hidden');
    }

    document.querySelectorAll('.driver-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.driver-tab').forEach(t => {
                t.classList.remove('border-yellow-500', 'text-yellow-600', 'active');
                t.classList.add('border-transparent', 'text-gray-500');
            });
            document.querySelectorAll('.driver-tab-panel').forEach(p => p.classList.add('hidden'));
            tab.classList.add('border-yellow-500', 'text-yellow-600', 'active');
            const panel = document.querySelector(`.driver-tab-panel[data-tab-panel="${tab.dataset.tab}"]`);
            if (panel) panel.classList.remove('hidden');
        });
    });

    let searchTimer;
