                    
                    // Re-initialize Lucide icons for error state
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons({ root: grid });
                    }
                });
        }

        </script><script>function renderUnits(units, statusColors, statusIcons, statusGradients) {
            const grid = document.getElementById('unitsGrid');
            
            if (units.length === 0) {
                grid.innerHTML = `
                    <div class="col-span-full text-center py-20">
                        <div class="inline-flex flex-col items-center">
                            <div class="p-4 bg-gray-100 rounded-full mb-4">
                                <i data-lucide="search" class="w-8 h-8 text-gray-400"></i>
                            </div>
                            <span class="text-xl text-gray-600 font-semibold mb-2">No units found</span>
                            <p class="text-sm text-gray-400">Try adjusting your search or filters</p>
                        </div>
                    </div>
                `;
                return;
            }
            
            const cardStyles = {
                'active': 'bg-white border-y border-r border-gray-200 border-l-4 border-l-green-500',
                'maintenance': 'bg-white border-y border-r border-gray-200 border-l-4 border-l-red-500',
                'coding': 'bg-white border-y border-r border-gray-200 border-l-4 border-l-yellow-400',
                'missing': 'bg-white border-y border-r border-gray-200 border-l-4 border-l-purple-500',
                'at_risk': 'bg-white border-y border-r border-gray-200 border-l-4 border-l-orange-500',
                'vacant': 'bg-white border-y border-r border-gray-200 border-l-4 border-l-blue-500',
                'available': 'bg-white border-y border-r border-gray-200 border-l-4 border-l-blue-500',
                'default': 'bg-white border-y border-r border-gray-200 border-l-4 border-l-gray-300'
            };

            const iconBgStyles = {
                'active': 'bg-green-50 text-green-600 border border-green-100',
                'maintenance': 'bg-red-50 text-red-600 border border-red-100',
                'coding': 'bg-yellow-50 text-yellow-600 border border-yellow-100',
                'missing': 'bg-purple-50 text-purple-600 border border-purple-100',
                'at_risk': 'bg-orange-50 text-orange-600 border border-orange-100',
                'vacant': 'bg-blue-50 text-blue-600 border border-blue-100',
                'available': 'bg-blue-50 text-blue-600 border border-blue-100',
                'default': 'bg-gray-50 text-gray-500 border border-gray-200'
            };

            const statusIconsList = {
                'active': 'check-circle',
                'maintenance': 'wrench',
                'coding': 'alert-circle',
                'missing': 'help-circle',
                'at_risk': 'alert-triangle',
                'vacant': 'user-x',
                'available': 'user-x',
                'default': 'car'
            };

            grid.innerHTML = units.map(unit => {
                const cardStyle = cardStyles[unit.status] || cardStyles['default'];
                const iconStyle = iconBgStyles[unit.status] || iconBgStyles['default'];
                const lucideIcon = statusIconsList[unit.status] || statusIconsList['default'];
                
                return `
                <div onclick="window.location.href='/units?id=${unit.id}'" class="${cardStyle} rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 relative group overflow-hidden hover:-translate-y-0.5 cursor-pointer">
                    
                    <!-- Hover Driver Tooltip Overlay -->
                    <div class="absolute inset-0 bg-slate-900/95 opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col items-center justify-center gap-2 z-10 pointer-events-none p-2">
                        <p class="text-white/70 text-[9px] font-black uppercase tracking-[0.2em] mb-1">Assigned Drivers</p>
                        ${(unit.driver1_name || unit.driver2_name) ? `
                            ${unit.driver1_name ? `
                            <div class="flex items-center gap-2.5 bg-white/10 rounded-lg px-3 py-2 w-[90%] border border-white/10 shadow-lg">
                                <div class="w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center flex-shrink-0 shadow-inner">
                                    <span class="text-white text-[9px] font-black">P1</span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[7px] text-blue-300 font-bold uppercase tracking-widest leading-none mb-0.5">Primary 1</p>
                                    <p class="text-white text-[11px] font-black leading-tight truncate tracking-wide">${unit.driver1_name}</p>
                                </div>
                            </div>
                            ` : ''}
                            ${unit.driver2_name ? `
                            <div class="flex items-center gap-2.5 bg-white/10 rounded-lg px-3 py-2 w-[90%] border border-white/10 shadow-lg mt-1">
                                <div class="w-6 h-6 rounded-full bg-purple-500 flex items-center justify-center flex-shrink-0 shadow-inner">
                                    <span class="text-white text-[9px] font-black">P2</span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[7px] text-purple-300 font-bold uppercase tracking-widest leading-none mb-0.5">Primary 2</p>
                                    <p class="text-white text-[11px] font-black leading-tight truncate tracking-wide">${unit.driver2_name}</p>
                                </div>
                            </div>
                            ` : ''}
                        ` : `
                            <div class="flex flex-col items-center justify-center mt-3 opacity-60">
                                <i data-lucide="user-x" class="w-7 h-7 text-white mb-2"></i>
                                <span class="text-[10px] text-white font-black uppercase tracking-widest text-center leading-tight">No Driver<br>Assigned</span>
                            </div>
                        `}
                    </div>

                    <div class="p-3 relative z-0">
                        <!-- Summary Header -->
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="${iconStyle} w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm">
                                    <i data-lucide="${lucideIcon}" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <h4 class="text-[13px] font-black text-slate-800 truncate tracking-wide">${unit.plate_number}</h4>
                                    <div class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">ID: ${unit.plate_number || 'N/A'}</div>
                                </div>
                            </div>
                            <span class="px-2 py-1 text-[8px] font-black rounded-md ${statusColors[unit.status] || 'bg-gray-100 text-gray-600'} uppercase tracking-widest shadow-sm border border-black/5">
                                ${unit.status}
                            </span>
                        </div>
                        
                        <!-- Essential Stats -->
                        <div class="grid grid-cols-2 gap-2 mt-3">
                            <div class="bg-gray-50 rounded-lg p-2 border border-gray-100 shadow-sm">
                                <div class="text-[8px] text-slate-500 uppercase font-black tracking-widest mb-0.5">Total Coll.</div>
                                <div class="text-sm font-black text-slate-800">â‚±${unit.total_boundary ? unit.total_boundary.toLocaleString() : '0'}</div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-2 border border-gray-100 shadow-sm">
                                <div class="text-[8px] text-slate-500 uppercase font-black tracking-widest mb-0.5">ROI</div>
                                <div class="text-sm font-black ${unit.roi_percentage >= 100 ? 'text-green-600' : 'text-slate-800'}">${unit.roi_percentage ? unit.roi_percentage.toFixed(1) : '0.0'}%</div>
                            </div>
                        </div>

                        <!-- Mini Footer -->
                        <div class="mt-2.5 flex items-center justify-between text-[9px] font-black uppercase tracking-widest border-t border-black/5 pt-2">
                            <span class="text-slate-400">Daily Status</span>
                            <span class="${unit.today_boundary > 0 ? 'text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-100' : 'text-slate-500 bg-slate-50 px-2 py-0.5 rounded-full border border-slate-200'}">
                                ${unit.today_boundary > 0 ? '+â‚±' + unit.today_boundary.toLocaleString() : 'â‚±0 Today'}
                            </span>
                        </div>
                    </div>
                </div>
                `;
            }).join('');
            
            // Re-initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons({ root: grid });
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
            
            renderUnits(units, statusColors, statusIcons, statusGradients);
            
            // Re-initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons({ root: grid });
            }
        }
        
        // Search and Filter Functions
        window.currentUnitStatusFilter = 'all';

        function setUnitStatusFilter(status) {
            window.currentUnitStatusFilter = status;
            
            // Update UI
            const statusBtns = ['all', 'active', 'maintenance', 'coding'];
            statusBtns.forEach(s => {
                const btn = document.getElementById('btn-' + s + '-units');
                if (btn) {
                    if (s === status) {
                        btn.classList.remove('text-white/70', 'hover:text-white', 'hover:bg-white/10');
                        btn.classList.add('bg-white', 'text-blue-700');
                    } else {
                        btn.classList.add('text-white/70', 'hover:text-white', 'hover:bg-white/10');
                        btn.classList.remove('bg-white', 'text-blue-700');
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
                            <div class="text-center py-12 bg-white rounded-2xl border border-gray-100 shadow-sm">
                                <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-green-100">
                                    <i data-lucide="shield-check" class="w-8 h-8 text-green-600"></i>
                                </div>
                                <h4 class="text-lg font-black text-gray-900 uppercase tracking-tight">All Clear</h4>
                                <p class="text-gray-500 text-xs px-8 mt-1 font-medium">No units are currently flagged or missing. Fleet integrity is secured.</p>
                            </div>
                        `;
                        if (typeof lucide !== 'undefined') lucide.createIcons({ root: grid });
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
                            </a>` : '<span class="text-gray-400">Not recorded</span>';

                        html += `
                            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 border-l-4 ${isMissing ? 'border-red-600' : 'border-amber-600'} hover:shadow-md transition-all relative overflow-hidden group">
                                ${isMissing ? '<div class="absolute top-0 right-0 p-1 bg-red-600 text-white text-[8px] font-black uppercase tracking-widest px-3">Lockdown</div>' : ''}
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="text-lg font-black text-gray-900 tracking-tighter">${unit.plate_number}</span>
                                            <span class="px-2 py-0.5 rounded text-[9px] font-black border ${badgeClass} flex items-center gap-1 uppercase tracking-widest shadow-sm">
                                                <i data-lucide="${icon}" class="w-3 h-3"></i> ${statusText}
                                            </span>
                                        </div>
                                        <div class="space-y-1.5">
                                            <div class="flex items-center gap-2 text-[10px]">
                                                <span class="text-gray-400 w-24 flex-shrink-0 font-bold uppercase tracking-tight">Current Driver:</span>
                                                <span class="text-gray-800 font-black uppercase tracking-tighter">${unit.driver_name || 'No Driver'}</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-[10px]">
                                                <span class="text-gray-400 w-24 flex-shrink-0 font-bold uppercase tracking-tight">Contact # :</span>
                                                ${contactDisplay}
                                            </div>
                                            <div class="flex items-center gap-2 text-[10px] pt-1.5 mt-1.5 border-t border-gray-100">
                                                <span class="text-gray-400 w-24 flex-shrink-0 font-bold uppercase tracking-tight">Last Return:</span>
                                                <span class="text-gray-600 italic font-black uppercase tracking-tighter">${unit.last_known_driver || 'None'}</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-[10px]">
                                                <span class="text-gray-400 w-24 flex-shrink-0 font-bold uppercase tracking-tight">Return Date:</span>
                                                <span class="text-gray-600 font-bold uppercase">${unit.last_boundary_date || 'No record'}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-center gap-3 flex-shrink-0 ml-4">
                                        <div class="text-center bg-gray-50 p-2 rounded-xl border border-gray-100 min-w-[70px]">
                                            <div class="text-[9px] uppercase font-black text-gray-400 tracking-widest">Time Elapsed</div>
                                            <div class="text-xl ${daysColor} leading-none mt-1 font-black">${daysMissing}</div>
                                            <div class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">day(s)</div>
                                        </div>
                                        ${isMissing ? `
                                        <form method="POST" action="/units/${unit.id}/recover" class="m-0 w-full" onsubmit="return confirm('Confirm RECOVERY of Unit ${unit.plate_number}? This will restore its active status and clear security alerts.');">
                                            <input type="hidden" name="_token" value="${csrfToken}">
                                            <button type="submit" class="w-full py-2 bg-green-600 text-white hover:bg-green-700 rounded-xl transition-all shadow-lg shadow-green-200 flex items-center justify-center gap-2 group/btn" title="Mark as Recovered">
                                                <i data-lucide="check-circle" class="w-4 h-4 group-hover/btn:scale-110 transition-transform"></i>
                                                <span class="text-[10px] font-black uppercase tracking-widest">Recover</span>
                                            </button>
                                        </form>
                                        ` : `
                                        <a href="/units?id=${unit.id}" class="w-full py-2 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl transition-all flex items-center justify-center gap-2 border border-gray-200">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                            <span class="text-[10px] font-black uppercase tracking-widest text-center">Manage</span>
                                        </a>
                                        `}
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                    container.innerHTML = html;
                    if (typeof lucide !== 'undefined') lucide.createIcons({ root: grid });
                })
                .catch(err => {
                    container.innerHTML = `
                        <div class="text-center py-12 bg-red-50 rounded-2xl border border-red-100">
                            <i data-lucide="alert-circle" class="w-12 h-12 text-red-500 mx-auto mb-3"></i>
                            <p class="text-red-700 font-black uppercase tracking-widest text-xs">Failed to load security flags.</p>
                            <button onclick="showFlaggedUnitsModal()" class="mt-4 px-4 py-2 bg-red-600 text-white rounded-lg text-xs font-bold uppercase tracking-widest">Retry</button>
                        </div>
                    `;
                    if (typeof lucide !== 'undefined') lucide.createIcons({ root: grid });
                });
        }
    </script>
@endpush
