<?php $__env->startSection('title', 'Boundary Management - Euro System'); ?>
<?php $__env->startSection('page-heading', 'Boundary Management'); ?>
<?php $__env->startSection('page-subheading', 'Track daily boundary collections and payments'); ?>

<?php $__env->startSection('content'); ?>

<!-- Search and Filters -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form class="flex flex-col sm:flex-row gap-4" method="GET" action="<?php echo e(route('boundaries.index')); ?>">
        <div class="flex-1">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="search" class="h-5 w-5 text-gray-400"></i>
                </div>
                <input
                    type="text"
                    id="search"
                    name="search"
                    value="<?php echo e($search); ?>"
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 focus:outline-none"
                    placeholder="Search by unit, plate, or driver..."
                >
            </div>
        </div>
        
        <div class="sm:w-40">
            <input
                type="date"
                id="date_filter"
                name="date"
                value="<?php echo e($date_filter); ?>"
                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 focus:outline-none"
            >
        </div>
        
        <div class="sm:w-40">
            <select
                id="status_filter"
                name="status"
                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 focus:outline-none"
            >
                <option value="">All Status</option>
                <option value="pending" <?php echo e($status_filter === 'pending' ? 'selected' : ''); ?>>Pending</option>
                <option value="paid" <?php echo e($status_filter === 'paid' ? 'selected' : ''); ?>>Paid</option>
                <option value="shortage" <?php echo e($status_filter === 'shortage' ? 'selected' : ''); ?>>Shortage</option>
                <option value="excess" <?php echo e($status_filter === 'excess' ? 'selected' : ''); ?>>Excess</option>
            </select>
        </div>
        
        <button type="button"
            onclick="addBoundary()"
            class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 flex items-center gap-2"
        >
            <i data-lucide="plus" class="w-4 h-4"></i>
            Add Boundary
        </button>
    </form>
</div>

<!-- Boundaries Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Boundary</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actual</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if(empty($boundariesArray)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i data-lucide="dollar-sign" class="w-12 h-12 mx-auto mb-4 text-gray-300"></i>
                            <p>No boundary records found</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $__currentLoopData = $boundariesArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $boundary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e(formatDate($boundary['date'])); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($boundary['unit_number']); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo e($boundary['plate_number']); ?></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e($boundary['driver_name'] ?? 'Unassigned'); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e(formatCurrency($boundary['boundary_amount'])); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e(formatCurrency($boundary['actual_boundary'] ?? 0)); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                    $statusClass = 'bg-yellow-100 text-yellow-800';
                                    if ($boundary['status'] === 'paid') $statusClass = 'bg-green-100 text-green-800';
                                    if ($boundary['status'] === 'shortage') $statusClass = 'bg-red-100 text-red-800';
                                    if ($boundary['status'] === 'excess') $statusClass = 'bg-blue-100 text-blue-800';
                                ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($statusClass); ?>">
                                    <?php echo e(ucfirst($boundary['status'])); ?>

                                </span>
                                <?php if($boundary['shortage'] > 0): ?>
                                    <div class="text-xs text-red-600 mt-1">Shortage: <?php echo e(formatCurrency($boundary['shortage'])); ?></div>
                                <?php elseif($boundary['excess'] > 0): ?>
                                    <div class="text-xs text-blue-600 mt-1">Excess: <?php echo e(formatCurrency($boundary['excess'])); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button
                                    type="button"
                                    onclick="editBoundary(<?php echo e($boundary['id']); ?>)"
                                    class="text-yellow-600 hover:text-yellow-900 mr-3"
                                    title="Edit Boundary"
                                >
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if($pagination['total_pages'] > 1): ?>
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                <?php if($pagination['has_prev']): ?>
                    <a href="?page=<?php echo e($pagination['prev_page']); ?>&search=<?php echo e(urlencode($search)); ?>&date=<?php echo e(urlencode($date_filter)); ?>&status=<?php echo e(urlencode($status_filter)); ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Previous</a>
                <?php endif; ?>
                <?php if($pagination['has_next']): ?>
                    <a href="?page=<?php echo e($pagination['next_page']); ?>&search=<?php echo e(urlencode($search)); ?>&date=<?php echo e(urlencode($date_filter)); ?>&status=<?php echo e(urlencode($status_filter)); ?>" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Next</a>
                <?php endif; ?>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium"><?php echo e($pagination['offset'] + 1); ?></span> to 
                        <span class="font-medium"><?php echo e(min($pagination['offset'] + $pagination['items_per_page'], $pagination['total_items'])); ?></span> of 
                        <span class="font-medium"><?php echo e($pagination['total_items']); ?></span> results
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                        <?php if($pagination['has_prev']): ?>
                            <a href="?page=<?php echo e($pagination['prev_page']); ?>&search=<?php echo e(urlencode($search)); ?>&date=<?php echo e(urlencode($date_filter)); ?>&status=<?php echo e(urlencode($status_filter)); ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php
                        $start_page = max(1, $pagination['page'] - 2);
                        $end_page = min($pagination['total_pages'], $pagination['page'] + 2);
                        ?>
                        
                        <?php for($i = $start_page; $i <= $end_page; $i++): ?>
                            <a href="?page=<?php echo e($i); ?>&search=<?php echo e(urlencode($search)); ?>&date=<?php echo e(urlencode($date_filter)); ?>&status=<?php echo e(urlencode($status_filter)); ?>" class="relative inline-flex items-center px-4 py-2 border text-sm font-medium <?php echo e($i === $pagination['page'] ? 'z-10 bg-yellow-50 border-yellow-500 text-yellow-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'); ?>">
                                <?php echo e($i); ?>

                            </a>
                        <?php endfor; ?>
                        
                        <?php if($pagination['has_next']): ?>
                            <a href="?page=<?php echo e($pagination['next_page']); ?>&search=<?php echo e(urlencode($search)); ?>&date=<?php echo e(urlencode($date_filter)); ?>&status=<?php echo e(urlencode($status_filter)); ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </a>
                        <?php endif; ?>
                    </nav>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Boundary Modal -->
<div id="boundaryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-sm p-3 max-h-[95vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-2">
            <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Add Boundary Record</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <form id="boundaryForm" method="POST" action="<?php echo e(route('boundaries.store')); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="action" id="formAction" value="add_boundary">
            <input type="hidden" name="id" id="boundaryId">
            
            <div class="space-y-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit *</label>
                    <div class="relative">
                        <input type="text" id="unitDisplay" required 
                               class="w-full px-2 py-1.5 border border-gray-300 rounded-lg bg-white cursor-pointer focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                               placeholder="Type to search units...">
                        <input type="hidden" name="unit_id" id="unitId" required>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        
                        <!-- Unit Dropdown -->
                        <div id="unit_dropdown" class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-32 overflow-y-auto hidden">
                            <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="unit-option px-2 py-1 hover:bg-yellow-50 cursor-pointer border-b border-gray-100 last:border-b-0"
                                     data-id="<?php echo e($unit['id']); ?>"
                                     data-name="<?php echo e($unit['unit_number']); ?>"
                                     data-plate="<?php echo e($unit['plate_number']); ?>"
                                     data-model="<?php echo e($unit['make_model'] ?? ''); ?>"
                                     data-rate="<?php echo e($unit['boundary_rate'] ?? 0); ?>">
                                    <div class="font-medium text-xs"><?php echo e($unit['unit_number']); ?></div>
                                    <div class="text-xs text-gray-500"><?php echo e($unit['plate_number']); ?> - <?php echo e($unit['make_model'] ?? 'N/A'); ?></div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Driver *</label>
                    <div class="relative">
                        <input type="text" id="driverDisplay" required 
                               class="w-full px-2 py-1.5 border border-gray-300 rounded-lg bg-white cursor-pointer focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                               placeholder="Type to search drivers...">
                        <input type="hidden" name="driver_id" id="driverId" required>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        
                        <!-- Driver Dropdown -->
                        <div id="driver_dropdown" class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-32 overflow-y-auto hidden">
                            <!-- All drivers option -->
                            <div class="driver-option px-2 py-1 hover:bg-yellow-50 cursor-pointer border-b border-gray-100"
                                 data-id="all"
                                 data-name="All Drivers"
                                 data-unit=""
                                 data-plate="">
                                <div class="font-medium text-xs">All Drivers</div>
                                <div class="text-xs text-gray-500">Show all available drivers</div>
                            </div>
                            <!-- Unit-specific drivers -->
                            <?php $__currentLoopData = $unit_drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit_id => $drivers): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="unit-drivers-group" data-unit-id="<?php echo e($unit_id); ?>" style="display: none;">
                                    <?php $__currentLoopData = $drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="driver-option px-2 py-1 hover:bg-yellow-50 cursor-pointer border-b border-gray-100 last:border-b-0"
                                             data-id="<?php echo e($driver['id']); ?>"
                                             data-name="<?php echo e($driver['name']); ?>"
                                             data-unit="<?php echo e($driver['current_unit']); ?>"
                                             data-plate="<?php echo e($driver['current_plate']); ?>">
                                            <div class="font-medium text-xs"><?php echo e($driver['name']); ?></div>
                                            <div class="text-xs text-gray-500"><?php echo e($driver['current_plate']); ?> - <?php echo e($driver['current_unit']); ?></div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <!-- All drivers list -->
                            <div class="all-drivers-list">
                                <?php $__currentLoopData = $drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="driver-option px-2 py-1 hover:bg-yellow-50 cursor-pointer border-b border-gray-100 last:border-b-0"
                                         data-id="<?php echo e($driver['id']); ?>"
                                         data-name="<?php echo e($driver['name']); ?>"
                                         data-unit="<?php echo e($driver['current_unit']); ?>"
                                         data-plate="<?php echo e($driver['current_plate']); ?>">
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <!-- Hidden data for unit-specific drivers -->
                            <div id="unitDriversData" style="display: none;">
                                <?php $__currentLoopData = $unit_drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit_id => $drivers): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="unit-drivers" 
                                         data-unit-id="<?php echo e($unit_id); ?>">
                                        <?php $__currentLoopData = $drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="unit-driver" 
                                                 data-id="<?php echo e($driver['id']); ?>"
                                                 data-name="<?php echo e($driver['name']); ?>"
                                                 data-unit="<?php echo e($driver['current_unit']); ?>"
                                                 data-plate="<?php echo e($driver['current_plate']); ?>">
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date *</label>
                    <input type="date" name="date" id="date" required value="<?php echo e(date('Y-m-d')); ?>" class="w-full px-2 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Boundary Amount *</label>
                    <input type="number" name="boundary_amount" id="boundaryAmount" required step="0.01" min="0" class="w-full px-2 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Actual Boundary</label>
                    <input type="hidden" name="actual_boundary" id="actualBoundary" step="0.01" min="0">
                    <div class="w-full px-2 py-1.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 text-sm" id="actualBoundaryDisplay">
                        Auto-filled based on boundary amount
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" id="notes" rows="2" class="w-full px-2 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"></textarea>
                </div>
            </div>
            
            <div class="mt-4 flex gap-3">
                <button type="submit" class="flex-1 bg-yellow-600 text-white py-2 px-4 rounded-lg hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    Save
                </button>
                <button type="button" onclick="closeModal()" class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Search and filter functionality
document.getElementById('search').addEventListener('input', function() {
    applyFilters();
});

document.getElementById('date_filter').addEventListener('change', function() {
    applyFilters();
});

document.getElementById('status_filter').addEventListener('change', function() {
    applyFilters();
});

function applyFilters() {
    const search = document.getElementById('search').value;
    const date = document.getElementById('date_filter').value;
    const status = document.getElementById('status_filter').value;
    const params = new URLSearchParams();
    
    if (search) params.append('search', search);
    if (date) params.append('date', date);
    if (status) params.append('status', status);
    
    window.location.href = '?' + params.toString();
}

// Auto-fill boundary amount and refresh drivers when unit is selected
document.getElementById('unitId').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    if (!selectedOption.value) return;
    const rate = parseFloat(selectedOption.getAttribute('data-rate'));
    const codingDay = selectedOption.getAttribute('data-coding-day');
    const dateInput = document.getElementById('date');
    
    if (rate) {
        // Calculate adjusted boundary based on date and coding day
        const adjustedBoundary = calculateAdjustedBoundary(rate, codingDay, dateInput.value);
        document.getElementById('boundaryAmount').value = adjustedBoundary;
        document.getElementById('actualBoundary').value = adjustedBoundary;
    }
    
    // Clear driver search and reset dropdown to show new unit's drivers
    document.getElementById('driver_search').value = '';
    document.getElementById('driverId').value = '';
    
    // Refresh driver dropdown to show only drivers for selected unit
    showDriverDropdown();
});

// Auto-recalculate boundary when date changes
document.getElementById('date').addEventListener('change', function() {
    const unitSelect = document.getElementById('unitId');
    if(unitSelect.selectedIndex < 0) return;
    const selectedOption = unitSelect.options[unitSelect.selectedIndex];
    const rate = parseFloat(selectedOption.getAttribute('data-rate'));
    const codingDay = selectedOption.getAttribute('data-coding-day');
    
    if (rate) {
        // Recalculate adjusted boundary based on new date
        const adjustedBoundary = calculateAdjustedBoundary(rate, codingDay, this.value);
        document.getElementById('boundaryAmount').value = adjustedBoundary;
        document.getElementById('actualBoundary').value = adjustedBoundary;
    }
});

function calculateAdjustedBoundary(baseRate, codingDay, dateStr) {
    if (!dateStr || !baseRate) return baseRate;
    
    const date = new Date(dateStr);
    const dayOfWeek = date.getDay(); // 0 = Sunday, 6 = Saturday
    const dayName = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'][dayOfWeek];
    
    let adjustedRate = baseRate;
    
    // Check if it's coding day
    if (codingDay && codingDay.toLowerCase() === dayName) {
        adjustedRate = baseRate * 0.5; // 50% on coding day
    }
    // Check for Saturday adjustments
    else if (dayOfWeek === 6) { // Saturday
        adjustedRate = baseRate - 100;
    }
    // Check for Sunday adjustments
    else if (dayOfWeek === 0) { // Sunday
        adjustedRate = baseRate - 200;
    }
    
    // Ensure boundary doesn't go below zero
    return Math.max(0, adjustedRate);
}

// Searchable Driver Dropdown Functions
let driverDropdownTimeout = {};

function initializeDriverDropdown() {
    populateDriverOptions();
}

function populateDriverOptions() {
    const select = document.getElementById('driverId');
    const dropdown = document.getElementById('driver_dropdown');
    const options = Array.from(select.options).slice(1); // Skip first empty option
    
    let html = '';
    options.forEach(option => {
        const name = option.getAttribute('data-name');
        const unit = option.getAttribute('data-unit');
        const plate = option.getAttribute('data-plate');
        const value = option.value;
        const assigned_units_count = option.getAttribute('data-assigned-count');
        
        // Add visual indicators for driver availability
        const isAvailable = unit === 'No Assignment' || assigned_units_count < 2;
        const availabilityClass = isAvailable ? '' : 'bg-yellow-50 border-l-2 border-yellow-300';
        const availabilityText = isAvailable ? '' : `<span class="text-xs text-yellow-600 ml-2">(Occupied: ${assigned_units_count}/2 units)</span>`;
        
        html += `
            <div class="px-4 py-3 hover:bg-yellow-50 cursor-pointer border-b border-gray-100 last:border-b-0 driver-option ${availabilityClass}"
                 onclick="selectDriver('${value}', '${name.replace(/'/g,"\\'").replace(/"/g,"&quot;")}', '${unit}', '${plate}')"
                 data-value="${value}"
                 data-name="${name.toLowerCase()}"
                 data-unit="${unit}"
                 data-plate="${plate.toLowerCase()}">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="font-medium text-gray-900">${name}</div>
                        ${availabilityText}
                    </div>
                    <div class="text-xs text-gray-500">${plate}</div>
                </div>
            </div>
        `;
    });
    
    dropdown.innerHTML = html;
}

function showDriverDropdown() {
    clearTimeout(driverDropdownTimeout);
    const dropdown = document.getElementById('driver_dropdown');
    const searchInput = document.getElementById('driver_search');
    const unitSelect = document.getElementById('unitId');
    const selectedUnitId = unitSelect.value;
    
    // If search is empty, show ONLY drivers assigned to the selected unit
    if (searchInput.value === '') {
        showUnitSpecificDrivers(selectedUnitId);
    } else {
        // If user is typing, show all drivers filtered by search
        filterDrivers();
    }
    
    dropdown.classList.remove('hidden');
}

function showUnitSpecificDrivers(unitId) {
    const dropdown = document.getElementById('driver_dropdown');
    const unitDriversData = document.getElementById('unitDriversData');
    
    // Find the drivers for the selected unit
    const unitDriversContainer = unitDriversData.querySelector(`[data-unit-id="${unitId}"]`);
    
    let html = '';
    if (unitDriversContainer) {
        const unitDrivers = unitDriversContainer.querySelectorAll('.unit-driver');
        
        unitDrivers.forEach(driver => {
            const id = driver.getAttribute('data-id');
            const name = driver.getAttribute('data-name');
            const unit = driver.getAttribute('data-unit');
            const plate = driver.getAttribute('data-plate');
            
            // Show drivers assigned to this specific unit
            html += `
                <div class="px-4 py-3 hover:bg-yellow-50 cursor-pointer border-b border-gray-100 last:border-b-0 driver-option bg-yellow-50 border-l-2 border-yellow-300"
                     onclick="selectDriver('${id}', '${name.replace(/'/g,"\\'").replace(/"/g,"&quot;")}', '${unit}', '${plate}')"
                     data-value="${id}"
                     data-name="${name.toLowerCase()}"
                     data-unit="${unit}"
                     data-plate="${plate.toLowerCase()}">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="font-medium text-gray-900">${name}</div>
                            <span class="text-xs text-yellow-600 ml-2">(Current: ${unit})</span>
                        </div>
                        <div class="text-xs text-gray-500">${plate}</div>
                    </div>
                </div>
            `;
        });
    }
    
    if (html === '') {
        html = `
            <div class="px-4 py-3 text-gray-500 text-center">
                <i data-lucide="users" class="w-4 h-4 inline mr-2"></i>
                No drivers currently assigned to this unit
            </div>
        `;
    }
    
    dropdown.innerHTML = html;
}

function hideDriverDropdown() {
    driverDropdownTimeout = setTimeout(() => {
        const dropdown = document.getElementById('driver_dropdown');
        dropdown.classList.add('hidden');
    }, 300);
}

function filterDrivers() {
    const searchInput = document.getElementById('driver_search');
    const dropdown = document.getElementById('driver_dropdown');
    const select = document.getElementById('driverId');
    const searchTerm = searchInput.value.toLowerCase();
    
    // Always show dropdown when typing
    dropdown.classList.remove('hidden');
    
    if (searchTerm === '') {
        showUnitSpecificDrivers(document.getElementById('unitId').value);
        return;
    }
    
    // When typing, show ALL drivers filtered by search term
    const options = Array.from(select.options).slice(1); // Skip first empty option
    let html = '';
    
    options.forEach(option => {
        const nameAttr = option.getAttribute('data-name') || '';
        const unitAttr = option.getAttribute('data-unit') || '';
        const plateAttr = option.getAttribute('data-plate') || '';
        
        const name = nameAttr.toLowerCase();
        const unit = unitAttr.toLowerCase();
        const plate = plateAttr.toLowerCase();
        const value = option.value;
        const assignedCount = parseInt(option.getAttribute('data-assigned-count')) || 0;
        
        // Check if search term matches name, unit, or plate
        if (name.includes(searchTerm) || unit.includes(searchTerm) || plate.includes(searchTerm)) {
            // Highlight matching text
            const highlightedName = highlightMatch(nameAttr, searchTerm);
            const highlightedUnit = highlightMatch(unitAttr, searchTerm);
            const highlightedPlate = highlightMatch(plateAttr, searchTerm);
            
            // Add visual indicators for driver availability
            const isAvailable = unitAttr === 'No Assignment' || assignedCount < 2;
            const availabilityClass = isAvailable ? '' : 'bg-yellow-50 border-l-2 border-yellow-300';
            const availabilityText = isAvailable ? '' : `<span class="text-xs text-yellow-600 ml-2">(Occupied: ${assignedCount}/2 units)</span>`;
            const currentUnitText = unitAttr !== 'No Assignment' ? `<span class="text-xs text-yellow-600 ml-2">(Current: ${unitAttr})</span>` : '';
            
            html += `
                <div class="px-4 py-3 hover:bg-yellow-50 cursor-pointer border-b border-gray-100 last:border-b-0 driver-option ${availabilityClass}"
                     onclick="selectDriver('${value}', '${nameAttr.replace(/'/g,"\\'").replace(/"/g,"&quot;")}', '${unitAttr}', '${plateAttr}')"
                     data-value="${value}">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="font-medium text-gray-900">${highlightedName}</div>
                            ${availabilityText || currentUnitText}
                        </div>
                        <div class="text-xs text-gray-500">${highlightedPlate}</div>
                    </div>
                </div>
            `;
        }
    });
    
    if (html === '') {
        html = `
            <div class="px-4 py-3 text-gray-500 text-center">
                <i data-lucide="search" class="w-4 h-4 inline mr-2"></i>
                No drivers found matching "${searchTerm}"
            </div>
        `;
    }
    
    dropdown.innerHTML = html;
}

function highlightMatch(text, searchTerm) {
    if (!searchTerm) return text;
    
    const regex = new RegExp(`(${searchTerm})`, 'gi');
    return text.replace(regex, '<span class="bg-yellow-200 font-semibold">$1</span>');
}

function selectDriver(value, name, unit, plate) {
    const select = document.getElementById('driverId');
    const searchInput = document.getElementById('driver_search');
    const dropdown = document.getElementById('driver_dropdown');
    
    // Update hidden select
    select.value = value;
    
    // Update search input with driver info
    if (unit !== 'No Assignment' && unit !== '') {
        searchInput.value = `${name} (${unit} - ${plate})`;
    } else {
        searchInput.value = name;
    }
    
    // Hide dropdown
    dropdown.classList.add('hidden');
    
    // Trigger change event for any listeners
    select.dispatchEvent(new Event('change'));
}

function clearDriverSelection() {
    const select = document.getElementById('driverId');
    const searchInput = document.getElementById('driver_search');
    
    select.value = '';
    searchInput.value = '';
    searchInput.focus();
}

// Initialize driver dropdown when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeDriverDropdown();
    initializeUnitDropdown();
    
    // Sync actual boundary with boundary amount when changed
    document.getElementById('boundaryAmount').addEventListener('input', function() {
        const value = this.value || '0.00';
        document.getElementById('actualBoundary').value = value;
        document.getElementById('actualBoundaryDisplay').textContent = `₱${parseFloat(value).toFixed(2)}`;
    });
});

// Unit dropdown functionality
function initializeUnitDropdown() {
    const unitDisplay = document.getElementById('unitDisplay');
    const unitDropdown = document.getElementById('unit_dropdown');
    const unitOptions = document.querySelectorAll('.unit-option');
    
    if (unitDisplay && unitDropdown) {
        // Show dropdown on focus
        unitDisplay.addEventListener('focus', function() {
            filterUnits('');
            unitDropdown.classList.remove('hidden');
        });
        
        // Filter units on input
        unitDisplay.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            filterUnits(searchTerm);
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!unitDisplay.contains(e.target) && !unitDropdown.contains(e.target)) {
                unitDropdown.classList.add('hidden');
            }
        });
        
        // Handle unit selection
        unitOptions.forEach(option => {
            option.addEventListener('click', function() {
                const unitId = this.getAttribute('data-id');
                const unitName = this.getAttribute('data-name');
                const unitPlate = this.getAttribute('data-plate');
                const unitRate = parseFloat(this.getAttribute('data-rate') || 0);
                
                document.getElementById('unitId').value = unitId;
                unitDisplay.value = `${unitName} - ${unitPlate}`;
                unitDropdown.classList.add('hidden');
                
                // Auto-fill boundary amount and actual boundary
                document.getElementById('boundaryAmount').value = unitRate.toFixed(2);
                document.getElementById('actualBoundary').value = unitRate.toFixed(2);
                document.getElementById('actualBoundaryDisplay').textContent = `₱${unitRate.toFixed(2)}`;
                
                // Trigger change event
                document.getElementById('unitId').dispatchEvent(new Event('change'));
            });
        });
    }
}

function filterUnits(searchTerm) {
    const unitOptions = document.querySelectorAll('.unit-option');
    const unitDropdown = document.getElementById('unit_dropdown');
    
    let hasResults = false;
    unitOptions.forEach(option => {
        const unitName = option.getAttribute('data-name').toLowerCase();
        const unitPlate = option.getAttribute('data-plate').toLowerCase();
        const unitModel = (option.getAttribute('data-model') || '').toLowerCase();
        
        if (unitName.includes(searchTerm) || unitPlate.includes(searchTerm) || unitModel.includes(searchTerm)) {
            option.style.display = 'block';
            hasResults = true;
        } else {
            option.style.display = 'none';
        }
    });
    
    // Show/hide no results message
    let noResultsMsg = unitDropdown.querySelector('.no-results');
    if (!hasResults) {
        if (!noResultsMsg) {
            noResultsMsg = document.createElement('div');
            noResultsMsg.className = 'no-results px-2 py-1 text-xs text-gray-500 text-center';
            noResultsMsg.textContent = 'No units found';
            unitDropdown.appendChild(noResultsMsg);
        }
    } else if (noResultsMsg) {
        noResultsMsg.remove();
    }
}

// Driver dropdown functionality  
function initializeDriverDropdown() {
    const driverDisplay = document.getElementById('driverDisplay');
    const driverDropdown = document.getElementById('driver_dropdown');
    const driverOptions = document.querySelectorAll('.driver-option');
    
    if (driverDisplay && driverDropdown) {
        // Show dropdown on focus
        driverDisplay.addEventListener('focus', function() {
            filterDrivers('');
            driverDropdown.classList.remove('hidden');
        });
        
        // Filter drivers on input
        driverDisplay.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            filterDrivers(searchTerm);
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!driverDisplay.contains(e.target) && !driverDropdown.contains(e.target)) {
                driverDropdown.classList.add('hidden');
            }
        });
        
        // Handle driver selection
        driverOptions.forEach(option => {
            option.addEventListener('click', function() {
                const driverId = this.getAttribute('data-id');
                const driverName = this.getAttribute('data-name');
                
                document.getElementById('driverId').value = driverId;
                driverDisplay.value = driverName;
                driverDropdown.classList.add('hidden');
                
                // Trigger change event
                document.getElementById('driverId').dispatchEvent(new Event('change'));
            });
        });
    }
}

function filterDrivers(searchTerm) {
    const driverOptions = document.querySelectorAll('.driver-option');
    const driverDropdown = document.getElementById('driver_dropdown');
    
    let hasResults = false;
    driverOptions.forEach(option => {
        const driverName = option.getAttribute('data-name').toLowerCase();
        const driverUnit = (option.getAttribute('data-unit') || '').toLowerCase();
        const driverPlate = (option.getAttribute('data-plate') || '').toLowerCase();
        
        if (driverName.includes(searchTerm) || driverUnit.includes(searchTerm) || driverPlate.includes(searchTerm)) {
            option.style.display = 'block';
            hasResults = true;
        } else {
            option.style.display = 'none';
        }
    });
    
    // Show/hide no results message
    let noResultsMsg = driverDropdown.querySelector('.no-results');
    if (!hasResults) {
        if (!noResultsMsg) {
            noResultsMsg = document.createElement('div');
            noResultsMsg.className = 'no-results px-2 py-1 text-xs text-gray-500 text-center';
            noResultsMsg.textContent = 'No drivers found';
            driverDropdown.appendChild(noResultsMsg);
        }
    } else if (noResultsMsg) {
        noResultsMsg.remove();
    }
}

// Modal functions
function addBoundary() {
    document.getElementById('modalTitle').textContent = 'Add Boundary Record';
    document.getElementById('formAction').value = 'add_boundary';
    document.getElementById('boundaryForm').reset();
    document.getElementById('date').value = new Date().toISOString().split('T')[0];
    document.getElementById('boundaryModal').classList.remove('hidden');
    
    // Set to POST action empty base (which routes to store)
    lucide.createIcons();
}

function editBoundary(id) {
    // Find the boundary data directly from the page
    const boundaryData = <?php echo json_encode($boundariesArray, 15, 512) ?>;
    const boundary = boundaryData.find(b => b.id == id);
    
    if (boundary) {
        document.getElementById('modalTitle').textContent = 'Edit Boundary Record';
        document.getElementById('formAction').value = 'update_boundary';
        document.getElementById('boundaryId').value = boundary.id;
        document.getElementById('unitId').value = boundary.unit_id;
        document.getElementById('driverId').value = boundary.driver_id;
        document.getElementById('date').value = boundary.date;
        document.getElementById('boundaryAmount').value = boundary.boundary_amount;
        document.getElementById('actualBoundary').value = boundary.actual_boundary || '';
        document.getElementById('notes').value = boundary.notes || '';
        
        // Update driver search input with selected driver name
        const driverOption = document.querySelector(`#driverId option[value="${boundary.driver_id}"]`);
        if (driverOption) {
            const name = driverOption.getAttribute('data-name');
            const unit = driverOption.getAttribute('data-unit');
            const plate = driverOption.getAttribute('data-plate');
            
            if (unit && unit !== 'No Assignment') {
                document.getElementById('driver_search').value = `${name} (${unit} - ${plate})`;
            } else {
                document.getElementById('driver_search').value = name;
            }
        }
        
        // Make boundary amount editable for editing
        document.getElementById('boundaryAmount').readOnly = false;
        
        document.getElementById('boundaryModal').classList.remove('hidden');
        lucide.createIcons();
    } else {
        alert('Boundary record not found');
    }
}

function closeModal() {
    document.getElementById('boundaryModal').classList.add('hidden');
    document.getElementById('boundaryAmount').readOnly = false;
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\eurotaxisystem\resources\views/boundaries/index.blade.php ENDPATH**/ ?>