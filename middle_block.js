        // Maintenance Units Modal Functions
        </script><script>function showMaintenanceUnitsModal() {
            document.getElementById('maintenanceUnitsModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Set default filter to all
            window.currentMaintenanceFilter = 'all';
            updateMaintenanceFilterUI('all');
            
            loadMaintenanceUnitsData();
        }
        
        function hideMaintenanceUnitsModal() {
            document.getElementById('maintenanceUnitsModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        function loadMaintenanceUnitsData() {
            const filter = window.currentMaintenanceFilter || 'all';
            const url = `/api/maintenance-units?filter=${filter}`;
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayMaintenanceUnitsData(data);
                    } else {
                        showMaintenanceError(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error loading maintenance units data:', error);
                    showMaintenanceError('Error loading maintenance units data. Please try again.');
                });
        }
        
        function setMaintenanceFilter(filter) {
            window.currentMaintenanceFilter = filter;
            updateMaintenanceFilterUI(filter);
            loadMaintenanceUnitsData();
        }
        
        function updateMaintenanceFilterUI(filter) {
            const filters = ['all', 'preventive', 'corrective', 'emergency', 'complete'];
            filters.forEach(f => {
                const btn = document.getElementById('mFilter' + f.charAt(0).toUpperCase() + f.slice(1));
                if (btn) {
                    if (f === filter) {
                        btn.classList.remove('text-white', 'hover:bg-white/10', 'font-medium');
                        btn.classList.add('bg-white', 'text-orange-600', 'font-bold', 'shadow-sm');
                    } else {
                        btn.classList.add('text-white', 'hover:bg-white/10', 'font-medium');
                        btn.classList.remove('bg-white', 'text-orange-600', 'font-bold', 'shadow-sm');
                    }
                }
            });
        }
        
        function displayMaintenanceUnitsData(data) {
            const grid = document.getElementById('maintenanceGrid');
            const units = data.units || [];
            const stats = data.stats || {};
            const filter = window.currentMaintenanceFilter || 'all';
            
            // Update summary stats (Global Overview)
            document.getElementById('maintenanceUnitsCount').textContent = stats.total_maintenance || 0;
            document.getElementById('preventiveMaintenanceCount').textContent = stats.preventive_maintenance || 0;
            document.getElementById('correctiveMaintenanceCount').textContent = stats.corrective_maintenance || 0;
            document.getElementById('emergencyMaintenanceCount').textContent = stats.emergency_maintenance || 0;
            document.getElementById('completedTotalCount').textContent = stats.completed_total || 0;
            
            // Store original data for filtering
            window.originalMaintenanceData = units;
            window.maintenanceSortOrder = window.maintenanceSortOrder || 'desc';
            
            // Render maintenance units
            filterMaintenanceUnits();
            
            // Re-initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons({ root: grid });
            }
        }
        
        function renderMaintenanceUnits(units) {
            const grid = document.getElementById('maintenanceGrid');
            const filter = window.currentMaintenanceFilter || 'all';
            
            if (units.length === 0) {
                grid.innerHTML = `
                    <div class="col-span-full text-center py-20">
                        <div class="inline-flex flex-col items-center">
                            <div class="p-4 bg-gray-100 rounded-full mb-4">
                                <i data-lucide="wrench" class="w-8 h-8 text-gray-400"></i>
                            </div>
                            <span class="text-xl text-gray-600 font-semibold mb-2">No maintenance units found</span>
                            <p class="text-sm text-gray-400">Try adjusting your search or filter</p>
                        </div>
                    </div>
                `;
                return;
            }
            
            grid.innerHTML = units.map(unit => {
                const isComplete = filter === 'complete';
                const mainDate = isComplete ? (unit.end_date || unit.start_date) : unit.start_date;
                const statusColor = isComplete ? 'border-green-500' : 'border-orange-500';
                const typeColor = isComplete ? 'text-green-600' : 'text-orange-600';
                const iconBg = isComplete ? 'bg-green-100' : 'bg-orange-100';
                const iconColor = isComplete ? 'text-green-600' : 'text-orange-600';

                return `
                <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden border-l-4 ${statusColor} hover:scale-102">
                    <div class="p-4">
                        <!-- Header -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="p-2 ${iconBg} rounded-lg">
                                    <i data-lucide="wrench" class="w-4 h-4 ${iconColor}"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900">${unit.plate_number || 'N/A'}</h4>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold ${typeColor}">${unit.maintenance_type || 'Unknown'}</div>
                                <div class="text-xs text-gray-500">${mainDate || 'N/A'}</div>
                            </div>
                        </div>
                        
                        <!-- Maintenance Details -->
                        <div class="bg-gray-50 rounded-lg p-3 mb-3">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-900">Status: ${unit.maintenance_status || 'Unknown'}</span>
                                <span class="text-xs font-bold text-orange-600">${isComplete ? 'â‚±' + (unit.maintenance_cost || 0).toLocaleString() : (unit.estimated_completion || 'N/A')}</span>
                            </div>
                            <div class="text-xs text-gray-600">
                                <span class="font-medium">Description:</span> ${unit.description || 'No description available'}
                            </div>
                        </div>
                        
                        <!-- Footer -->
                        <div class="flex items-center justify-between text-xs text-gray-500">
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
                lucide.createIcons({ root: grid });
            }
        }
        
        function filterMaintenanceUnits() {
            const searchTerm = document.getElementById('maintenanceSearchInput').value.toLowerCase();
            const filter = window.currentMaintenanceFilter || 'all';
            
            let filteredUnits = [...(window.originalMaintenanceData || [])];
            
            // Apply search filter
            if (searchTerm) {
                filteredUnits = filteredUnits.filter(unit => {
                    const searchableText = [
                        unit.plate_number || '',
                        unit.maintenance_type || '',
                        unit.maintenance_status || '',
                        unit.description || '',
                        unit.start_date || '',
                        unit.end_date || '',
                        unit.estimated_completion || ''
                    ].join(' ').toLowerCase();
                    
                    return searchableText.includes(searchTerm);
                });
            }

            // Apply Sort Newest First (Backend already sorts, but search needs re-render)
            filteredUnits.sort((a, b) => {
                const dateA = new Date((filter === 'complete' ? a.end_date : a.start_date) || '1970-01-01');
                const dateB = new Date((filter === 'complete' ? b.end_date : b.start_date) || '1970-01-01');
                return dateB - dateA;
            });
            
            window.currentFilteredMaintenanceData = filteredUnits;
            renderMaintenanceUnits(filteredUnits);
        }

        // ToggleMaintenanceSort is now handled by buttons but keeping for compatibility if needed
        function toggleMaintenanceSort() {
            filterMaintenanceUnits();
        }
        
        function clearMaintenanceSearch() {
            document.getElementById('maintenanceSearchInput').value = '';
            filterMaintenanceUnits();
        }
        
        function showMaintenanceError(message, debugInfo = null) {
            const grid = document.getElementById('maintenanceGrid');
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
                        <span class="text-xl text-gray-600 font-semibold mb-2">Error Loading Maintenance Data</span>
                        <p class="text-sm text-gray-400 mb-4">${message}</p>
                        <div class="flex gap-2">
                            <button onclick="loadMaintenanceUnitsData()" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                                <i data-lucide="refresh-cw" class="w-4 h-4 inline mr-2"></i>
                                Retry
                            </button>
                            <button onclick="testMaintenanceAPI()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
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
                lucide.createIcons({ root: grid });
            }
        }
        
        function testMaintenanceAPI() {
            const grid = document.getElementById('maintenanceGrid');
            grid.innerHTML = `
                <div class="col-span-full text-center py-20">
                    <div class="inline-flex flex-col items-center">
                        <div class="p-4 bg-blue-100 rounded-full mb-4">
                            <i data-lucide="bug" class="w-8 h-8 text-blue-600"></i>
                        </div>
                        <span class="text-xl text-gray-600 font-semibold mb-2">Testing API Connection</span>
                        <p class="text-sm text-gray-400 mb-4">Checking API endpoint...</p>
                        <div class="w-64 bg-gray-200 rounded-full h-2 mb-4">
                            <div class="bg-blue-600 h-2 rounded-full animate-pulse" style="width: 60%"></div>
                        </div>
                    </div>
                </div>
            `;
            
            // Test the API endpoint
            fetch('/api/maintenance-units')
                .then(response => {
                    return response.text();
                })
                .then(text => {
                    try {
                        const data = JSON.parse(text);
                        showMaintenanceError('API Test Complete - Check Console for Details', {
                            response_status: 'success',
                            data_keys: Object.keys(data),
                            data: data
                        });
                    } catch (parseError) {
                        showMaintenanceError('API Test Complete - JSON Parse Error', {
                            response_status: 'parse_error',
                            raw_response: text.substring(0, 500) + (text.length > 500 ? '...' : ''),
                            parse_error: parseError.message
                        });
                    }
                })
                .catch(error => {
                    showMaintenanceError('API Test Complete - Fetch Error', {
                        response_status: 'fetch_error',
                        error: error.message,
                        stack: error.stack
                    });
                });
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
            fetch('/api/active-drivers')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayActiveDriversData(data);
                    } else {
                        showActiveDriversError(data.message);
                    }
                })
                .catch(error => {
                    showActiveDriversError('Error loading active drivers data. Please try again.');
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
                lucide.createIcons({ root: grid });
            }
        }
        
        function renderActiveDrivers(drivers) {
            const grid = document.getElementById('activeDriversGrid');
            
            if (drivers.length === 0) {
                grid.innerHTML = `
                    <div class="col-span-full text-center py-20">
                        <div class="inline-flex flex-col items-center">
                            <div class="p-4 bg-gray-100 rounded-full mb-4">
                                <i data-lucide="users" class="w-8 h-8 text-gray-400"></i>
                            </div>
                            <span class="text-xl text-gray-600 font-semibold mb-2">No active drivers found</span>
                            <p class="text-sm text-gray-400">Try adjusting your search or date filter</p>
                        </div>
                    </div>
                `;
                return;
            }
            
            grid.innerHTML = drivers.map(driver => `
                <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden border-l-4 border-blue-500 hover:scale-102">
                    <div class="p-4">
                        <!-- Header -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <i data-lucide="users" class="w-4 h-4 text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900">${driver.name || 'Unknown'}</h4>
                                    <span class="text-xs text-gray-500">${driver.license_number || 'N/A'}</span>
                                </div>
                            </div>
                            <div class="text-right mt-1 flex flex-col items-end gap-1">
                                ${driver.assigned_units > 0 
                                    ? `<span class="px-2.5 py-1 text-xs font-bold text-green-700 bg-green-100 rounded-full border border-green-200">Assigned</span>
                                       ${driver.plate_numbers ? `<span class="text-[10px] font-black text-gray-400 capitalize bg-gray-100 px-2 rounded-md">${driver.plate_numbers}</span>` : ''}`
                                    : `<span class="px-2.5 py-1 text-xs font-bold text-red-700 bg-red-100 rounded-full border border-red-200">Unassigned</span>`
                                }
                            </div>
                        </div>
                        
                        <!-- Driver Details -->
                        <div class="bg-gray-50 rounded-lg p-3 mb-3">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-900">Contact: ${driver.phone || 'N/A'}</span>
                            </div>
                            <div class="text-xs text-gray-600">
                                <span class="font-medium">Address:</span> ${driver.address || 'No address available'}
                            </div>
                        </div>
                        
                        <!-- Performance Stats -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full ${driver.performance_rating === 'excellent' ? 'bg-green-500' : driver.performance_rating === 'good' ? 'bg-yellow-500' : driver.performance_rating === 'average' ? 'bg-orange-500' : 'bg-gray-400'} animate-pulse"></div>
                                <span class="text-xs font-medium text-gray-600">
                                    ${driver.performance_rating ? driver.performance_rating.charAt(0).toUpperCase() + driver.performance_rating.slice(1) : 'Unknown'}
                                </span>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-blue-600">â‚±${driver.total_boundary ? driver.total_boundary.toLocaleString() : '0'}</div>
                                <div class="text-xs text-gray-500">Total Collected</div>
                            </div>
                        </div>
                        
                        <!-- Footer -->
                        <div class="flex items-center justify-between text-xs text-gray-500">
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
                });
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
                    lucide.createIcons({ root: grid });
                }
            }
        }

        function clearDriversSearch() {
            document.getElementById('driversSearchInput').value = '';
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
                        <p class="text-sm text-gray-400 mb-4">${message}</p>
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
                lucide.createIcons({ root: grid });
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
                        <p class="text-sm text-gray-400 mb-4">Checking API endpoint...</p>
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

        // Coding Units Modal Functions
        function showCodingUnitsModal() {
            document.getElementById('codingUnitsModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            loadCodingUnitsData();
        }
        
        function hideCodingUnitsModal() {
            document.getElementById('codingUnitsModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        function loadCodingUnitsData() {
            fetch('/api/coding-units')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayCodingUnitsData(data);
                    } else {
                        showCodingError(data.message);
                    }
                })
                .catch(error => {
                    showCodingError('Error loading coding units data. Please try again.');
                });
        }
        
        function displayCodingUnitsData(data) {
            const grid = document.getElementById('codingGrid');
            const units = data.units || [];
            const stats = data.stats || {};
            
            // Update summary stats
            document.getElementById('codingUnitsCount').textContent = units.length || 0;
            updateCodingSummary(units);
            
            // Store original data for filtering
            window.originalCodingUnitsData = units;
            window.currentFilteredCodingUnitsData = units;
            
            // Render coding units
            renderCodingUnits(units);
            
            // Re-initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons({ root: grid });
            }
        }
        
        function renderCodingUnits(units) {
            const grid = document.getElementById('codingGrid');
            
            if (units.length === 0) {
                grid.innerHTML = `
                    <div class="col-span-full text-center py-20">
                        <div class="inline-flex flex-col items-center">
                            <div class="p-4 bg-gray-100 rounded-full mb-4">
                                <i data-lucide="code" class="w-8 h-8 text-gray-400"></i>
                            </div>
                            <span class="text-xl text-gray-600 font-semibold mb-2">No coding units found</span>
                            <p class="text-sm text-gray-400">Try adjusting your search or date filter</p>
                        </div>
                    </div>
                `;
                return;
            }
            
            grid.innerHTML = units.map(unit => `
                <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden border-l-4 border-purple-500 hover:scale-102">
                    <div class="p-4">
                        <!-- Header -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-purple-100 rounded-lg">
                                    <i data-lucide="code" class="w-4 h-4 text-purple-600"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900">${unit.plate_number || 'N/A'}</h4>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-purple-600">${unit.coding_type || 'Coding'}</div>
                                <div class="text-xs text-gray-500">${unit.start_date ? unit.start_date : (unit.coding_day !== 'Unknown' ? 'Every ' + unit.coding_day : 'No date')}</div>
                            </div>
                        </div>
                        
                        <!-- Coding Details -->
                        <div class="bg-gray-50 rounded-lg p-3 mb-3">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-900">Status: ${unit.status || 'Unknown'}</span>
                                <span class="text-xs text-gray-600">${unit.estimated_completion || 'N/A'}</span>
                            </div>
                            <div class="text-xs text-gray-600">
                                <span class="font-medium">Description:</span> ${unit.description || 'No description available'}
                            </div>
                        </div>
                        
                        <!-- Footer -->
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span class="flex items-center gap-1">
                                <i data-lucide="calendar" class="w-3 h-3"></i>
                                ${unit.start_date ? unit.start_date : (unit.coding_day !== 'Unknown' ? 'Every ' + unit.coding_day : 'No start date')}
                            </span>
                            <span class="flex items-center gap-1">
                                <i data-lucide="check-circle" class="w-3 h-3"></i>
                                ${unit.status || 'Unknown'}
                            </span>
                        </div>
                    </div>
                </div>
            `).join('');
        }
        
        function updateCodingSummary(units) {
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
            
            const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const todayDayName = dayNames[today.getDay()];
            const tomorrowDayName = dayNames[tomorrow.getDay()];
            
            const counts = {
                today: 0,
                tomorrow: 0,
                past: 0
            };
            
            units.forEach(unit => {
                const unitDate = unit.start_date;
                const codingDay = unit.coding_day;
                const isCompleted = unit.coding_status === 'completed';
                
                if (isCompleted || (unitDate && unitDate < todayStr)) {
                    counts.past++;
                } else if (unitDate === todayStr || (!unitDate && codingDay === todayDayName)) {
                    counts.today++;
                } else if (unitDate === tomorrowStr || (!unitDate && codingDay === tomorrowDayName)) {
                    counts.tomorrow++;
                }
            });
            
            document.getElementById('todayCodingCount').textContent = counts.today;
            document.getElementById('tomorrowCodingCount').textContent = counts.tomorrow;
            document.getElementById('pastCodingCount').textContent = counts.past;
        }
        
        window.currentCodingPeriod = 'all';

        function setCodingPeriod(period) {
            window.currentCodingPeriod = period;
            
            // Update UI
            const periods = ['all', 'today', 'tomorrow', 'past'];
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
            const currentPeriod = window.currentCodingPeriod || 'all';
            
            let filteredUnits = window.originalCodingUnitsData || [];

            // Get current dates
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
            
            const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const todayDayName = dayNames[today.getDay()];
            const tomorrowDayName = dayNames[tomorrow.getDay()];
            
            // Apply period filter
            if (currentPeriod !== 'all') {
                filteredUnits = filteredUnits.filter(unit => {
                    const unitDate = unit.start_date;
                    const codingDay = unit.coding_day;
                    const isCompleted = unit.coding_status === 'completed';
                    
                    if (currentPeriod === 'today') {
                        return !isCompleted && (unitDate === todayStr || (!unitDate && codingDay === todayDayName));
                    }
                    if (currentPeriod === 'tomorrow') {
                        return !isCompleted && (unitDate === tomorrowStr || (!unitDate && codingDay === tomorrowDayName));
                    }
                    if (currentPeriod === 'past') {
                        return isCompleted || (unitDate && unitDate < todayStr);
                    }
                    return true;
                });
            }
            
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
                        <p class="text-sm text-gray-400 mb-4">${message}</p>
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
                lucide.createIcons({ root: grid });
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
                        <p class="text-sm text-gray-400 mb-4">Checking API endpoint...</p>
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
                lucide.createIcons({ root: grid });
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
                            <p class="text-gray-500 font-medium font-mono">NO TRANSACTIONS FOUND FOR THIS PERIOD</p>
                        </div>
                    </div>
                `;
                return;
            }

            // Receipt-style list (clean table-like rows)
            grid.classList.remove('grid-cols-1', 'md:grid-cols-2');
            grid.classList.add('grid-cols-1');
            
            grid.innerHTML = `
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden font-mono text-sm max-w-4xl mx-auto">
                    <div class="bg-gray-100 px-6 py-3 border-b-2 border-gray-200 flex justify-between text-[11px] font-black text-gray-500 uppercase tracking-widest">
                        <span>Description / Category</span>
                        <span class="text-right">Amount (â‚±)</span>
                    </div>
                    <div class="divide-y divide-gray-100">
                        ${incomeData.map(item => `
                            <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors border-l-4 ${item.type === 'income' ? 'border-green-500/20' : 'border-red-500/20'}">
                                <div class="flex items-center gap-4">
                                    <div class="flex-shrink-0 w-8 h-8 rounded bg-gray-50 border border-gray-200 flex items-center justify-center ${item.type === 'income' ? 'text-green-600' : 'text-red-600'}">
                                        <i data-lucide="${item.type === 'income' ? 'arrow-down-left' : 'arrow-up-right'}" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <div class="font-black text-gray-900 tracking-tight">${(item.description || 'Unknown').toUpperCase()}</div>
                                        <div class="flex items-center gap-3 text-[10px] text-gray-400 font-bold mt-0.5">
                                            <span class="text-gray-500">${(item.category || 'GENERAL').toUpperCase()}</span>
                                            <span class="text-gray-300">â€¢</span>
                                            <span>${(item.date || '').split(' ')[0]}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-black text-lg ${item.type === 'income' ? 'text-green-600' : 'text-red-600'}">
                                        ${item.type === 'income' ? '+' : '-'} â‚±${Math.abs(parseFloat(item.amount) || 0).toLocaleString(undefined, {minimumFractionDigits: 2})}
                                    </div>
                                    <div class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">${item.source || 'OFFICE'}</div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                    <div class="bg-gray-50 px-6 py-4 border-t-2 border-dashed border-gray-200 text-center">
                        <p class="text-[10px] text-gray-400 uppercase font-black tracking-[0.2em]">End of transaction list</p>
                    </div>
                </div>
            `;

            if (typeof lucide !== 'undefined') {
                lucide.createIcons({ root: grid });
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
            
            const netIncome = totalIncome - totalExpenses;
            const profitMargin = totalIncome > 0 ? (netIncome / totalIncome) * 100 : 0;
            
            // Helper to format currency
            const fmt = (num) => 'â‚±' + num.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});

            // Update Primary Report Fields
            const safeSet = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.textContent = val;
            };

            safeSet('reportTotalIncome', fmt(totalIncome));
            /* Tailwind imports removed to prevent 404s in production */
            safeSet('reportTotalExpenses', fmt(totalExpenses));
            safeSet('reportMaintenanceTotal', 'Total: ' + fmt(breakdown.maintenanceTotal));
            safeSet('reportGeneralExpensesTotal', 'Total: ' + fmt(breakdown.officeTotal));
            safeSet('reportNetIncome', fmt(netIncome));
            safeSet('reportProfitMargin', profitMargin.toFixed(1) + '%');
            safeSet('reportTimestamp', new Date().toLocaleString());
            
            // Helper to render lists
            const renderList = (id, items) => {
                const el = document.getElementById(id);
                if (!el) return;
                
                if (items.length > 0) {
                    el.innerHTML = `
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50 border-b border-gray-100 text-[8px] uppercase tracking-widest text-gray-400">
                                    <th class="px-6 py-2 font-bold w-1/4">Date</th>
                                    <th class="px-6 py-2 font-bold w-1/2">Description</th>
                                    <th class="px-6 py-2 font-bold text-right w-1/4">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                ${items.map(item => `
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-2 text-[9px] text-gray-400 font-bold uppercase whitespace-nowrap">${item.date}</td>
                                        <td class="px-6 py-2 text-[10px] font-black text-gray-800 tracking-tight">${item.description}</td>
                                        <td class="px-6 py-2 text-xs font-black ${id === 'revenueDetailList' ? 'text-emerald-600' : 'text-red-500'} text-right whitespace-nowrap">${fmt(item.amount)}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    `;
                } else {
                    el.innerHTML = `
                        <div class="px-6 py-4 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50">
                            No records found
                        </div>
                    `;
                }
            };

            renderList('revenueDetailList', breakdown.revenueItems);
            renderList('maintenanceDetailList', breakdown.maintenanceItems);
            renderList('officeExpensesDetailList', breakdown.officeItems);
        }

        function renderIncomeData(data) {
            // Grid rendering is now integrated into updateIncomeSummary
            // This function is kept for compatibility with fetchIncomeData flow
        }

        function printReport() {
            const content = document.getElementById('incomeReport').innerHTML;
            const periodLabel = document.getElementById('reportPeriodLabelPrint');
            const period = periodLabel ? periodLabel.textContent : 'TODAY';

            const win = window.open('', '_blank');
            if (!win) {
                window.print();
                return;
            }

            win.document.write(`<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Report &mdash; ${period}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: #fff; font-family: 'Segoe UI', system-ui, sans-serif; padding: 40px; color: #111; }
        h1 { text-align: center; font-size: 24px; font-weight: 900; text-transform: uppercase; letter-spacing: .15em; margin-bottom: 4px; }
        .subtitle { text-align: center; font-size: 11px; color: #64748b; font-weight: 700; letter-spacing: .15em; text-transform: uppercase; margin-bottom: 32px; }
        .section-header { display: flex; justify-content: space-between; align-items: center; padding: 10px 20px; border-radius: 6px 6px 0 0; }
        .sub-header { display: flex; justify-content: space-between; background: #f8f8f8; padding: 6px 20px; border-left: 1px solid #eee; border-right: 1px solid #eee; font-size: 9px; font-weight: 900; text-transform: uppercase; letter-spacing: .12em; color: #94a3b8; }
        table { width: 100%; border-collapse: collapse; border: 1px solid #f0f0f0; border-top: none; margin-bottom: 24px; }
        thead tr { background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
        thead th { padding: 8px 20px; font-size: 8px; text-transform: uppercase; letter-spacing: .12em; color: #94a3b8; font-weight: 700; text-align: left; }
        thead th:last-child { text-align: right; }
        tbody tr { border-bottom: 1px solid #f8f8f8; }
        td { padding: 8px 20px; font-size: 11px; color: #1e293b; }
        td:last-child { text-align: right; font-weight: 900; white-space: nowrap; }
        .no-records { padding: 16px 20px; text-align: center; font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .1em; border: 1px solid #f0f0f0; border-top: none; margin-bottom: 24px; }
        .footer { text-align: center; margin-top: 40px; padding-top: 16px; border-top: 1px solid #e2e8f0; font-size: 9px; color: #94a3b8; }
        img { max-height: 64px !important; width: auto !important; display: block; margin: 0 auto 8px auto; }
        .print-only { display: block !important; }
        @media print { body { padding: 20px; } }
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

            win.document.close();
            win.focus();
            setTimeout(() => { win.print(); }, 300);
        }

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

            const win = window.open('', '_blank');
            if (!win) {
                window.print();
                return;
            }

            win.document.write(`<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Statement &mdash; ${period}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: #fff; font-family: 'Segoe UI', system-ui, sans-serif; padding: 40px; color: #111; }
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
        tbody tr { border-bottom: 1px solid #f8f8f8; }
        tbody tr:hover { background: #fafafa; }
        td { padding: 8px 20px; font-size: 11px; color: #1e293b; }
        td.date { color: #94a3b8; font-weight: 700; font-size: 9px; text-transform: uppercase; }
        td.amount { text-align: right; font-weight: 900; color: #dc2626; white-space: nowrap; }
        .no-records { padding: 16px 20px; text-align: center; font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .1em; border: 1px solid #f0f0f0; border-top: none; margin-bottom: 24px; }
        .footer { text-align: center; margin-top: 40px; padding-top: 16px; border-top: 1px solid #e2e8f0; font-size: 9px; color: #94a3b8; }
        img { max-height: 64px !important; width: auto !important; display: block; margin: 0 auto 8px auto; }
        .print-only { display: block !important; }
        @media print { body { padding: 20px; } }
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

            win.document.close();
            win.focus();
            setTimeout(() => { win.print(); }, 300);
        }

        function renderExpensesReport(data) {
            const breakdown = {
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

            const totalExpenses = breakdown.maintenanceTotal + breakdown.officeTotal;
            const fmt = (num) => 'â‚±' + num.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});

            const safeSet = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.textContent = val;
            };

            safeSet('expensesTotalValue', fmt(totalExpenses));
            safeSet('expensesMaintenanceTotal', 'Total: ' + fmt(breakdown.maintenanceTotal));
            safeSet('expensesOfficeTotal', 'Total: ' + fmt(breakdown.officeTotal));
            safeSet('finalExpensesTotal', fmt(totalExpenses));
            safeSet('expensesTimestamp', new Date().toLocaleString());

            const renderList = (id, items) => {
                const el = document.getElementById(id);
                if (!el) return;
                
                if (items.length > 0) {
                    el.innerHTML = `
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50 border-b border-gray-100 text-[8px] uppercase tracking-widest text-gray-400">
                                    <th class="px-6 py-2 font-bold w-1/4">Date</th>
                                    <th class="px-6 py-2 font-bold w-1/2">Description</th>
                                    <th class="px-6 py-2 font-bold text-right w-1/4">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                ${items.map(item => `
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-2 text-[9px] text-gray-400 font-bold uppercase whitespace-nowrap">${item.date}</td>
                                        <td class="px-6 py-2 text-[10px] font-black text-gray-800 tracking-tight">${item.description}</td>
                                        <td class="px-6 py-2 text-xs font-black text-red-500 text-right whitespace-nowrap">${fmt(item.amount)}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    `;
                } else {
                    el.innerHTML = `
                        <div class="px-6 py-4 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50">
                            No records found
                        </div>
                    `;
                }
            };

            renderList('expensesMaintenanceList', breakdown.maintenanceItems);
            renderList('expensesOfficeList', breakdown.officeItems);
        }

        
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
                btn.classList.remove('bg-white', 'text-green-700');
                btn.classList.add('text-white/70', 'hover:text-white', 'hover:bg-white/10');
            });
            
            const activeBtn = document.getElementById('btn-' + period + '-income');
            if (activeBtn) {
                activeBtn.classList.remove('text-white/70', 'hover:text-white', 'hover:bg-white/10');
                activeBtn.classList.add('bg-white', 'text-green-700');
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
                        <p class="text-sm text-gray-400 mb-4">${message}</p>
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
                lucide.createIcons({ root: grid });
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
                        <p class="text-sm text-gray-400 mb-4">Checking API endpoint...</p>
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
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayBoundaryCollections(data);
                    } else {
                        showBoundaryError(data.message);
                    }
                })
                .catch(error => {
                    showBoundaryError('Error loading boundary collections. Please try again.');
                });
        }
        
        function displayBoundaryCollections(data) {
            const grid = document.getElementById('boundaryGrid');
            const collections = data.collections || [];
            const stats = data.stats || {};
            
            // Update summary stats with new logic (amounts for Yesterday and Monthly)
            document.getElementById('totalBoundaryCount').textContent = stats.total_today || 0;
            document.getElementById('uniqueUnitsCount').textContent = 'â‚±' + (stats.amount_yesterday || 0).toLocaleString();
            document.getElementById('uniqueDriversCount').textContent = 'â‚±' + (stats.amount_monthly || 0).toLocaleString();
            document.getElementById('totalBoundaryAmount').textContent = 'â‚±' + (stats.total_yearly_amount || 0).toLocaleString();
            
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
                lucide.createIcons({ root: grid });
            }
        }
        
        function renderBoundaryCollections(collections) {
            const grid = document.getElementById('boundaryGrid');
            
            if (collections.length === 0) {
                grid.innerHTML = `
                    <div class="col-span-full text-center py-20">
                        <div class="inline-flex flex-col items-center">
                            <div class="p-4 bg-gray-100 rounded-full mb-4">
                                <i data-lucide="calendar" class="w-8 h-8 text-gray-400"></i>
                            </div>
                            <span class="text-xl text-gray-600 font-semibold mb-2">No boundary collections found</span>
                            <p class="text-sm text-gray-400">Try adjusting your search or date filter</p>
                        </div>
                    </div>
                `;
                return;
            }
            
            grid.innerHTML = collections.map(collection => `
                <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden border-l-4 border-green-500 hover:scale-102">
                    <div class="p-4">
                        <!-- Header -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-green-100 rounded-lg">
                                    <i data-lucide="car" class="w-4 h-4 text-green-600"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900">${collection.plate_number}</h4>
                                    <span class="text-xs text-gray-500">${collection.plate_number || 'N/A'}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-green-600">â‚±${collection.boundary_amount.toLocaleString()}</div>
                                <div class="text-xs text-gray-500">${collection.date}</div>
                            </div>
                        </div>
                        
                        <!-- Driver Information -->
                        <div class="bg-gray-50 rounded-lg p-3 mb-3">
                            <div class="flex items-center gap-2 mb-2">
                                <i data-lucide="user" class="w-4 h-4 text-gray-600"></i>
                                <span class="text-sm font-medium text-gray-900">Driver: ${collection.driver_name || 'N/A'}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i data-lucide="clock" class="w-4 h-4 text-gray-600"></i>
                                <span class="text-xs text-gray-600">Time: ${collection.time || 'N/A'}</span>
                            </div>
                        </div>
                        
                        <!-- Collection Details -->
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span class="flex items-center gap-1">
                                <i data-lucide="map-pin" class="w-3 h-3"></i>
                                ${collection.location || 'Main Office'}
                            </span>
                            <span class="flex items-center gap-1">
                                <i data-lucide="check-circle" class="w-3 h-3"></i>
                                Verified
                            </span>
                        </div>
                    </div>
                </div>
            `).join('');
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
                        <p class="text-sm text-gray-400 mb-4">${message}</p>
                        <button onclick="loadBoundaryCollections()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <i data-lucide="refresh-cw" class="w-4 h-4 inline mr-2"></i>
                            Retry
                        </button>
                    </div>
                </div>
            `;
        }

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
                        <p class="text-sm text-gray-400">Please wait while we fetch your fleet information</p>
                    </div>
                </div>
            `;
            
            fetch('/api/units-overview')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
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
                                <p class="text-sm text-gray-400 mb-4">${error.message}</p>
                                <button onclick="loadUnitsData()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i>
                                    Try Again
                                </button>
                            </div>
                        </div>
                    `;
                    
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
