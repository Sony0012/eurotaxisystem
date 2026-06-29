
>         function displayMaintenanceUnitsData(data) {
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
                  <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 
overflow-hidden border-l-4 ${statusColor} hover:scale-102">
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
                                  <div class="text-lg font-bold ${typeColor}">${unit.maintenance_type || 
'Unknown'}</div>
                                  <div class="text-xs text-gray-500">${mainDate || 'N/A'}</div>
                              </div>
                          </div>
                          
                          <!-- Maintenance Details -->
                          <div class="bg-gray-50 rounded-lg p-3 mb-3">
                              <div class="flex items-center justify-between mb-2">
                                  <span class="text-sm font-medium text-gray-900">Status: ${unit.maintenance_status || 
'Unknown'}</span>
                                  <span class="text-xs font-bold text-orange-600">${isComplete ? 'â‚±' + 
(unit.maintenance_cost || 0).toLocaleString() : (unit.estimated_completion || 'N/A')}</span>
                              </div>
                              <div class="text-xs text-gray-600">
                                  <span class="font-medium">Description:</span> ${unit.description || 'No description 
available'}
                              </div>
                          </div>
                          
                          <!-- Footer -->
                          <div class="flex items-center justify-between text-xs text-gray-500">
                              <span class="flex items-center gap-1">
                                  <i data-lucide="calendar" class="w-3 h-3"></i>
                                  ${isComplete ? 'Completed: ' + (unit.end_date || 'N/A') : 'Started: ' + 
(unit.start_date || 'N/A')}
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
                              <button onclick="loadMaintenanceUnitsData()" class="px-4 py-2 bg-orange-600 text-white 
rounded-lg hover:bg-orange-700 transition-colors">
                                  <i data-lucide="refresh-cw" class="w-4 h-4 inline mr-2"></i>
                                  Retry
                              </button>
                              <button onclick="testMaintenanceAPI()" class="px-4 py-2 bg-blue-600 text-white 
rounded-lg hover:bg-blue-700 transition-colors">
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


