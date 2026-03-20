<?php $__env->startSection('title', 'Unit Management - Euro System'); ?>
<?php $__env->startSection('page-heading', 'Unit Management'); ?>
<?php $__env->startSection('page-subheading', 'Manage your fleet of taxi units'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="<?php echo e(route('units.index')); ?>" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="h-5 w-5 text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="<?php echo e($search); ?>"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 focus:outline-none"
                        placeholder="Search by unit number, plate, make, or model...">
                </div>
            </div>
            <div class="md:w-48">
                <select name="status"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 focus:outline-none">
                    <option value="">All Status</option>
                    <option value="active" <?php echo e($status_filter === 'active' ? 'selected' : ''); ?>>Active</option>
                    <option value="maintenance" <?php echo e($status_filter === 'maintenance' ? 'selected' : ''); ?>>Maintenance</option>
                    <option value="coding" <?php echo e($status_filter === 'coding' ? 'selected' : ''); ?>>Coding</option>
                    <option value="retired" <?php echo e($status_filter === 'retired' ? 'selected' : ''); ?>>Retired</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 flex items-center gap-2">
                    <i data-lucide="search" class="w-4 h-4"></i> Search
                </button>
                <button type="button" onclick="document.getElementById('addUnitModal').classList.remove('hidden')"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i> Add Unit
                </button>
                <a href="<?php echo e(route('units.import')); ?>" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                    <i data-lucide="upload" class="w-4 h-4"></i> Import CSV
                </a>
                <a href="<?php echo e(route('units.import')); ?>" 
                   class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 flex items-center gap-2">
                    <i data-lucide="file-text" class="w-4 h-4"></i> Import Excel
                </a>
            </div>
        </form>
    </div>

    <!-- Units Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Units Management - Euro System</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Info
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle
                            Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Availability</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned
                            Drivers</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Boundary
                            Rate</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Devices
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ROI</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $is_available = (!$unit->driver_id && !$unit->secondary_driver_id) && $unit->status === 'active';
                            $primary_driver = $unit->primary_driver ?? null;
                            $secondary_driver = $unit->secondary_driver ?? null;
                            $total_collected = $unit->total_collected ?? 0;
                            $purchase_cost = $unit->purchase_cost ?? 0;
                            $roi_achieved = $unit->roi_achieved ?? false;
                        ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-1">
                                    <div class="text-sm font-bold text-gray-900"><?php echo e($unit->unit_number); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo e($unit->plate_number); ?></div>
                                    <?php if($unit->color): ?>
                                        <div class="text-xs text-gray-400">Color: <?php echo e($unit->color); ?></div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-1">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($unit->make); ?> <?php echo e($unit->model); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo e($unit->year); ?></div>
                                    <div class="flex items-center gap-2 text-xs">
                                        <span
                                            class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full"><?php echo e(ucfirst($unit->unit_type ?? 'new')); ?></span>
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full">
                                            <i data-lucide="droplet" class="w-3 h-3 inline"></i>
                                            <?php echo e(ucfirst($unit->fuel_status ?? 'full')); ?>

                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full <?php echo e($is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                        <?php echo e($is_available ? 'Available' : 'Occupied'); ?>

                                    </span>
                                    <?php if($is_available): ?>
                                        <i data-lucide="check-circle" class="w-4 h-4 text-green-600"></i>
                                    <?php else: ?>
                                        <i data-lucide="users" class="w-4 h-4 text-red-600"></i>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-1">
                                    <?php if($unit->driver_id && $primary_driver): ?>
                                        <?php $d1 = explode('|', $primary_driver); ?>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-medium text-gray-900">Driver 1:</span>
                                            <span class="text-xs text-gray-700"><?php echo e($d1[0] ?? ''); ?></span>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-xs text-gray-400">No Driver 1</div>
                                    <?php endif; ?>
                                    <?php if($unit->secondary_driver_id && $secondary_driver): ?>
                                        <?php $d2 = explode('|', $secondary_driver); ?>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-medium text-gray-900">Driver 2:</span>
                                            <span class="text-xs text-gray-700"><?php echo e($d2[0] ?? ''); ?></span>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-xs text-gray-400">No Driver 2</div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full
                                        <?php if($unit->status === 'active'): ?> bg-green-100 text-green-800
                                        <?php elseif($unit->status === 'maintenance'): ?> bg-yellow-100 text-yellow-800
                                        <?php elseif($unit->status === 'coding'): ?> bg-red-100 text-red-800
                                        <?php else: ?> bg-gray-100 text-gray-800
                                        <?php endif; ?>">
                                    <?php echo e(ucfirst($unit->status)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e(formatCurrency($unit->boundary_rate)); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-wrap gap-1">
                                    <?php if($unit->gps_device_count > 0): ?>
                                        <span
                                            class="px-3 py-1 bg-green-100 text-green-800 rounded-full flex items-center gap-1 text-xs font-medium">
                                            <i data-lucide="map-pin" class="w-3 h-3"></i> GPS: <?php echo e($unit->gps_device_count); ?>

                                        </span>
                                    <?php endif; ?>
                                    <?php if($unit->dashcam_device_count > 0): ?>
                                        <span
                                            class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full flex items-center gap-1 text-xs font-medium">
                                            <i data-lucide="camera" class="w-3 h-3"></i> Cam: <?php echo e($unit->dashcam_device_count); ?>

                                        </span>
                                    <?php endif; ?>
                                    <?php if(!$unit->gps_device_count && !$unit->dashcam_device_count): ?>
                                        <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-medium">No
                                            Devices</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if($purchase_cost > 0): ?>
                                    <?php if($roi_achieved): ?>
                                        <span class="text-green-600 flex items-center gap-1">
                                            <i data-lucide="check-circle" class="w-4 h-4"></i> Achieved
                                        </span>
                                    <?php else: ?>
                                        <?php $pct = $purchase_cost > 0 ? ($total_collected / $purchase_cost) * 100 : 0; ?>
                                        <div class="text-gray-600">
                                            <div class="flex items-center gap-1 text-xs">
                                                <i data-lucide="trending-up" class="w-4 h-4"></i>
                                                <?php echo e(number_format($pct, 1)); ?>%
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-gray-400 flex items-center gap-1"><i data-lucide="clock" class="w-4 h-4"></i>
                                        No Cost Set</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex gap-2">
                                    <button onclick="editUnit(<?php echo e($unit->id); ?>)"
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg" title="Edit Unit">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </button>
                                    <button onclick="viewUnitDetails(<?php echo e($unit->id); ?>)"
                                        class="p-2 text-green-600 hover:bg-green-50 rounded-lg" title="View Details">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>
                                    <form method="POST" action="<?php echo e(route('units.destroy', $unit->id)); ?>"
                                        onsubmit="return confirm('Delete unit <?php echo e($unit->unit_number); ?>?')">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg"
                                            title="Delete Unit">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                <i data-lucide="car" class="w-12 h-12 mx-auto mb-4 text-gray-300"></i>
                                <p>No units found</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if($pagination['total_pages'] > 1): ?>
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Showing <?php echo e($pagination['total_items']); ?> results / Page <?php echo e($pagination['page']); ?> of
                        <?php echo e($pagination['total_pages']); ?>

                    </div>
                    <div class="flex items-center gap-2">
                        <?php if($pagination['has_prev']): ?>
                            <a href="?page=<?php echo e($pagination['prev_page']); ?>&search=<?php echo e(urlencode($search)); ?>&status=<?php echo e(urlencode($status_filter)); ?>"
                                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                            </a>
                        <?php endif; ?>
                        <?php for($i = max(1, $pagination['page'] - 2); $i <= min($pagination['total_pages'], $pagination['page'] + 2); $i++): ?>
                            <a href="?page=<?php echo e($i); ?>&search=<?php echo e(urlencode($search)); ?>&status=<?php echo e(urlencode($status_filter)); ?>"
                                class="relative inline-flex items-center px-4 py-2 border text-sm font-medium
                                           <?php echo e($i === $pagination['page'] ? 'z-10 bg-yellow-50 border-yellow-500 text-yellow-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'); ?>">
                                <?php echo e($i); ?>

                            </a>
                        <?php endfor; ?>
                        <?php if($pagination['has_next']): ?>
                            <a href="?page=<?php echo e($pagination['next_page']); ?>&search=<?php echo e(urlencode($search)); ?>&status=<?php echo e(urlencode($status_filter)); ?>"
                                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    
    <div id="addUnitModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden h-full w-full z-50 flex items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">

            
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 p-6 rounded-t-lg">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                            <i data-lucide="car" class="w-6 h-6 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-white">Add New Unit</h3>
                            <p class="text-yellow-100 text-sm">Enter vehicle information and add devices</p>
                        </div>
                    </div>
                    <button onclick="document.getElementById('addUnitModal').classList.add('hidden'); resetAddUnitModal()"
                        class="text-white hover:bg-white hover:bg-opacity-20 rounded-lg p-2 transition-colors">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>

            
            <form method="POST" action="<?php echo e(route('units.store')); ?>" id="addUnitForm" class="p-6">
                <?php echo csrf_field(); ?>

                
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <i data-lucide="info" class="w-5 h-5 text-blue-600"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900">Basic Information</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Unit Number <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="hash" class="w-5 h-5 text-gray-400"></i>
                                </div>
                                <input type="text" name="unit_number" required
                                    class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                    placeholder="e.g., TAXI-001"
                                    oninput="this.value = this.value.toUpperCase()">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Plate Number <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="credit-card" class="w-5 h-5 text-gray-400"></i>
                                </div>
                                <input type="text" name="plate_number" id="addPlateNumber" required
                                    class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                    placeholder="e.g., ABC 123"
                                    oninput="this.value = this.value.toUpperCase(); addUnitUpdateCoding()">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                            <select name="status" id="addUnitStatus" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                                <option value="active">🟢 Active</option>
                                <option value="maintenance">🔧 Maintenance</option>
                                <option value="coding">📝 Coding</option>
                                <option value="retired">⚫ Retired</option>
                            </select>
                        </div>
                    </div>
                </div>

                
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <i data-lucide="truck" class="w-5 h-5 text-green-600"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900">Vehicle Details</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Make <span class="text-red-500">*</span></label>
                            <input type="text" name="make" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                placeholder="e.g., Toyota, Honda, Nissan"
                                oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Model <span class="text-red-500">*</span></label>
                            <input type="text" name="model" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                placeholder="e.g., Vios, Civic, Sentra"
                                oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Year <span class="text-red-500">*</span></label>
                            <input type="number" name="year" required min="2000" max="<?php echo e(date('Y')); ?>" value="<?php echo e(date('Y')); ?>"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                placeholder="e.g., 2023">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Color</label>
                            <input type="text" name="color" value="White"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                placeholder="e.g., White, Red, Blue">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Unit Type</label>
                            <select name="unit_type"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                                <option value="new">🆕 New</option>
                                <option value="used">📦 Used</option>
                                <option value="rented">🔄 Rented</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Fuel Status</label>
                            <select name="fuel_status"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                                <option value="full">⛽ Full</option>
                                <option value="half">⛽ Half</option>
                                <option value="low">⛽ Low</option>
                                <option value="empty">⛽ Empty</option>
                            </select>
                        </div>
                    </div>
                </div>

                
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <i data-lucide="dollar-sign" class="w-5 h-5 text-purple-600"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900">Financial Information</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Boundary Rate <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">₱</span>
                                </div>
                                <input type="number" name="boundary_rate" id="addBoundaryRate" required step="0.01" value="1100.00"
                                    class="w-full pl-8 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                    onblur="this.value = parseFloat(this.value).toFixed(2)">
                            </div>
                            <p class="text-xs text-gray-500">Daily boundary collection target</p>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Purchase Cost</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">₱</span>
                                </div>
                                <input type="number" name="purchase_cost" step="0.01" value="0.00"
                                    class="w-full pl-8 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                    onblur="this.value = parseFloat(this.value || 0).toFixed(2)">
                            </div>
                            <p class="text-xs text-gray-500">Total purchase amount</p>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Purchase Date</label>
                            <input type="date" name="purchase_date"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                            <p class="text-xs text-gray-500">When the unit was purchased</p>
                        </div>
                    </div>
                </div>

                
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <i data-lucide="users" class="w-5 h-5 text-blue-600"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900">Driver Assignment</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Primary Driver</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                    <i data-lucide="user" class="w-5 h-5 text-gray-400"></i>
                                </div>
                                <input type="text" id="add_driver1_search" autocomplete="off"
                                    placeholder="Start typing to search drivers..."
                                    class="w-full pl-10 pr-10 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                    onkeyup="addUnitFilterDrivers('add_driver1')"
                                    onfocus="addUnitShowDropdown('add_driver1')"
                                    onblur="setTimeout(()=>addUnitHideDropdown('add_driver1'), 200)"
                                    oninput="addUnitFilterDrivers('add_driver1')">
                                <button type="button" onclick="addUnitClearDriver('add_driver1')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i data-lucide="x" class="w-4 h-4 text-gray-400 hover:text-gray-600"></i>
                                </button>
                                <select id="add_driver1" name="driver_id" class="hidden">
                                    <option value="">Select Primary Driver</option>
                                    <?php $__currentLoopData = $all_drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($driver->id); ?>" data-name="<?php echo e($driver->full_name); ?>" data-license="<?php echo e($driver->license_number ?? ''); ?>">
                                            <?php echo e($driver->full_name); ?> - <?php echo e($driver->license_number ?? 'No License'); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div id="add_driver1_dropdown" class="absolute z-20 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto hidden"></div>
                            </div>
                            <p class="text-xs text-gray-500">Main driver assigned to this unit</p>
                        </div>

                        
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Secondary Driver (Optional)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                    <i data-lucide="user-plus" class="w-5 h-5 text-gray-400"></i>
                                </div>
                                <input type="text" id="add_driver2_search" autocomplete="off"
                                    placeholder="Start typing to search drivers..."
                                    class="w-full pl-10 pr-10 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                    onkeyup="addUnitFilterDrivers('add_driver2')"
                                    onfocus="addUnitShowDropdown('add_driver2')"
                                    onblur="setTimeout(()=>addUnitHideDropdown('add_driver2'), 200)"
                                    oninput="addUnitFilterDrivers('add_driver2')">
                                <button type="button" onclick="addUnitClearDriver('add_driver2')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i data-lucide="x" class="w-4 h-4 text-gray-400 hover:text-gray-600"></i>
                                </button>
                                <select id="add_driver2" name="secondary_driver_id" class="hidden">
                                    <option value="">Select Secondary Driver</option>
                                    <?php $__currentLoopData = $all_drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($driver->id); ?>" data-name="<?php echo e($driver->full_name); ?>" data-license="<?php echo e($driver->license_number ?? ''); ?>">
                                            <?php echo e($driver->full_name); ?> - <?php echo e($driver->license_number ?? 'No License'); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div id="add_driver2_dropdown" class="absolute z-20 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto hidden"></div>
                            </div>
                            <p class="text-xs text-gray-500">Backup or relief driver (optional)</p>
                        </div>

                        
                        <div class="pt-2">
                            <button type="button" onclick="addUnitClearDriver('add_driver1'); addUnitClearDriver('add_driver2')"
                                class="w-full bg-red-50 text-red-600 py-2 px-4 rounded-lg hover:bg-red-100 transition-colors flex items-center justify-center gap-2 border border-red-200">
                                <i data-lucide="user-x" class="w-4 h-4"></i> Remove All Drivers
                            </button>
                            <p class="text-xs text-gray-500 mt-1">Clear both driver assignments for this unit</p>
                        </div>
                    </div>
                </div>

                
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="p-2 bg-indigo-100 rounded-lg">
                            <i data-lucide="calendar" class="w-5 h-5 text-indigo-600"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900">Coding Information</h4>
                    </div>

                    
                    <div class="mb-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex items-center gap-2 mb-3">
                            <i data-lucide="info" class="w-4 h-4 text-blue-600"></i>
                            <h5 class="font-semibold text-blue-900">MMDA Coding Schedule (Metro Manila)</h5>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-2 text-xs">
                            <div class="flex items-center gap-1"><span class="font-medium">Mon:</span><span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">1, 2</span></div>
                            <div class="flex items-center gap-1"><span class="font-medium">Tue:</span><span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">3, 4</span></div>
                            <div class="flex items-center gap-1"><span class="font-medium">Wed:</span><span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">5, 6</span></div>
                            <div class="flex items-center gap-1"><span class="font-medium">Thu:</span><span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">7, 8</span></div>
                            <div class="flex items-center gap-1"><span class="font-medium">Fri:</span><span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">9, 0</span></div>
                        </div>
                        <p class="text-xs text-blue-600 mt-2">Based on the last digit of your plate number</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Coding Day</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="calendar" class="w-5 h-5 text-gray-400"></i>
                                </div>
                                <input type="text" id="addCodingDay" name="coding_day" readonly
                                    class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-lg bg-gray-50"
                                    placeholder="Auto-generated">
                            </div>
                            <p class="text-xs text-gray-500">Automatically calculated from plate number</p>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Next Coding Date</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="calendar" class="w-5 h-5 text-gray-400"></i>
                                </div>
                                <input type="text" id="addNextCodingDate" readonly
                                    class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-lg bg-gray-50"
                                    placeholder="Auto-generated">
                            </div>
                            <p class="text-xs text-gray-500">Next scheduled coding date</p>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Days Until Next Coding</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="clock" class="w-5 h-5 text-gray-400"></i>
                                </div>
                                <input type="text" id="addDaysUntilCoding" readonly
                                    class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-lg bg-gray-50"
                                    placeholder="Auto-calculated">
                            </div>
                            <p class="text-xs text-gray-500">Days remaining until next coding</p>
                        </div>
                    </div>
                    <div id="addCodingStatusDisplay" class="mt-4"></div>
                </div>

                
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="p-2 bg-indigo-100 rounded-lg">
                            <i data-lucide="smartphone" class="w-5 h-5 text-indigo-600"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900">Device Management</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-indigo-400 transition-colors">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="map-pin" class="w-5 h-5 text-indigo-600"></i>
                                    <h5 class="font-semibold text-gray-900">GPS Devices</h5>
                                </div>
                                <button type="button" onclick="addUnitAddGPS()" class="px-3 py-1 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition-colors">
                                    + Add GPS
                                </button>
                            </div>
                            <div id="addGPSDevicesList" class="space-y-2">
                                <p class="text-sm text-gray-500 text-center py-2">No GPS devices added</p>
                            </div>
                        </div>
                        
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-purple-400 transition-colors">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="camera" class="w-5 h-5 text-purple-600"></i>
                                    <h5 class="font-semibold text-gray-900">Dashcam Devices</h5>
                                </div>
                                <button type="button" onclick="addUnitAddDashcam()" class="px-3 py-1 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition-colors">
                                    + Add Dashcam
                                </button>
                            </div>
                            <div id="addDashcamDevicesList" class="space-y-2">
                                <p class="text-sm text-gray-500 text-center py-2">No dashcam devices added</p>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="flex gap-3 mt-6 pt-4 border-t">
                    <button type="submit" class="flex-1 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold flex items-center justify-center gap-2">
                        <i data-lucide="plus-circle" class="w-5 h-5"></i> Add Unit
                    </button>
                    <button type="button" onclick="document.getElementById('addUnitModal').classList.add('hidden'); resetAddUnitModal()"
                        class="flex-1 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    
    <div id="editUnitModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden h-full w-full z-50 flex items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">

            
            <div class="bg-gradient-to-r from-blue-500 to-blue-700 p-6 rounded-t-lg">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                            <i data-lucide="edit-2" class="w-6 h-6 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-white">Edit Unit</h3>
                            <p class="text-blue-100 text-sm">Update vehicle information and settings</p>
                        </div>
                    </div>
                    <button onclick="closeEditUnitModal()"
                        class="text-white hover:bg-white hover:bg-opacity-20 rounded-lg p-2 transition-colors">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>

            
            <form method="POST" id="editUnitForm" class="p-6">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

                
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="p-2 bg-blue-100 rounded-lg"><i data-lucide="info" class="w-5 h-5 text-blue-600"></i></div>
                        <h4 class="text-lg font-semibold text-gray-900">Basic Information</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Unit Number <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="hash" class="w-5 h-5 text-gray-400"></i>
                                </div>
                                <input type="text" name="unit_number" id="editUnitNumber" required
                                    class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    oninput="this.value = this.value.toUpperCase()">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Plate Number <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="credit-card" class="w-5 h-5 text-gray-400"></i>
                                </div>
                                <input type="text" name="plate_number" id="editPlateNumber" required
                                    class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    oninput="this.value = this.value.toUpperCase(); editUnitUpdateCoding()">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                            <select name="status" id="editStatus" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="active">🟢 Active</option>
                                <option value="maintenance">🔧 Maintenance</option>
                                <option value="coding">📝 Coding</option>
                                <option value="retired">⚫ Retired</option>
                            </select>
                        </div>
                    </div>
                </div>

                
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="p-2 bg-green-100 rounded-lg"><i data-lucide="truck" class="w-5 h-5 text-green-600"></i></div>
                        <h4 class="text-lg font-semibold text-gray-900">Vehicle Details</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Make <span class="text-red-500">*</span></label>
                            <input type="text" name="make" id="editMake" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Model <span class="text-red-500">*</span></label>
                            <input type="text" name="model" id="editModel" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Year <span class="text-red-500">*</span></label>
                            <input type="number" name="year" id="editYear" min="2000" max="<?php echo e(date('Y')); ?>"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Color</label>
                            <input type="text" name="color" id="editColor"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Unit Type</label>
                            <select name="unit_type" id="editUnitType"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="new">🆕 New</option>
                                <option value="used">📦 Used</option>
                                <option value="rented">🔄 Rented</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Fuel Status</label>
                            <select name="fuel_status" id="editFuelStatus"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="full">⛽ Full</option>
                                <option value="half">⛽ Half</option>
                                <option value="low">⛽ Low</option>
                                <option value="empty">⛽ Empty</option>
                            </select>
                        </div>
                    </div>
                </div>

                
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="p-2 bg-purple-100 rounded-lg"><i data-lucide="dollar-sign" class="w-5 h-5 text-purple-600"></i></div>
                        <h4 class="text-lg font-semibold text-gray-900">Financial Information</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Boundary Rate <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">₱</span>
                                </div>
                                <input type="number" name="boundary_rate" id="editBoundaryRate" step="0.01"
                                    class="w-full pl-8 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    onblur="this.value = parseFloat(this.value || 0).toFixed(2)">
                            </div>
                            <p class="text-xs text-gray-500">Daily boundary collection target</p>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Purchase Cost</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">₱</span>
                                </div>
                                <input type="number" name="purchase_cost" id="editPurchaseCost" step="0.01"
                                    class="w-full pl-8 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    onblur="this.value = parseFloat(this.value || 0).toFixed(2)">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Purchase Date</label>
                            <input type="date" name="purchase_date" id="editPurchaseDate"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="p-2 bg-blue-100 rounded-lg"><i data-lucide="users" class="w-5 h-5 text-blue-600"></i></div>
                        <h4 class="text-lg font-semibold text-gray-900">Driver Assignment</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Primary Driver</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                    <i data-lucide="user" class="w-5 h-5 text-gray-400"></i>
                                </div>
                                <input type="text" id="edit_driver1_search" autocomplete="off"
                                    placeholder="Start typing to search drivers..."
                                    class="w-full pl-10 pr-10 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    onkeyup="editUnitFilterDrivers('edit_driver1')"
                                    onfocus="editUnitShowDropdown('edit_driver1')"
                                    onblur="setTimeout(()=>editUnitHideDropdown('edit_driver1'), 200)"
                                    oninput="editUnitFilterDrivers('edit_driver1')">
                                <button type="button" onclick="editUnitClearDriver('edit_driver1')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i data-lucide="x" class="w-4 h-4 text-gray-400 hover:text-gray-600"></i>
                                </button>
                                <select id="edit_driver1" name="driver_id" class="hidden">
                                    <option value="">No Driver</option>
                                    <?php $__currentLoopData = $all_drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($d->id); ?>" data-name="<?php echo e($d->full_name); ?>" data-license="<?php echo e($d->license_number ?? ''); ?>">
                                            <?php echo e($d->full_name); ?> - <?php echo e($d->license_number ?? 'No License'); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div id="edit_driver1_dropdown" class="absolute z-20 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto hidden"></div>
                            </div>
                            <p class="text-xs text-gray-500">Main driver assigned to this unit</p>
                        </div>

                        
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Secondary Driver (Optional)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                    <i data-lucide="user-plus" class="w-5 h-5 text-gray-400"></i>
                                </div>
                                <input type="text" id="edit_driver2_search" autocomplete="off"
                                    placeholder="Start typing to search drivers..."
                                    class="w-full pl-10 pr-10 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    onkeyup="editUnitFilterDrivers('edit_driver2')"
                                    onfocus="editUnitShowDropdown('edit_driver2')"
                                    onblur="setTimeout(()=>editUnitHideDropdown('edit_driver2'), 200)"
                                    oninput="editUnitFilterDrivers('edit_driver2')">
                                <button type="button" onclick="editUnitClearDriver('edit_driver2')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i data-lucide="x" class="w-4 h-4 text-gray-400 hover:text-gray-600"></i>
                                </button>
                                <select id="edit_driver2" name="secondary_driver_id" class="hidden">
                                    <option value="">No Driver</option>
                                    <?php $__currentLoopData = $all_drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($d->id); ?>" data-name="<?php echo e($d->full_name); ?>" data-license="<?php echo e($d->license_number ?? ''); ?>">
                                            <?php echo e($d->full_name); ?> - <?php echo e($d->license_number ?? 'No License'); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div id="edit_driver2_dropdown" class="absolute z-20 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto hidden"></div>
                            </div>
                            <p class="text-xs text-gray-500">Backup or relief driver (optional)</p>
                        </div>

                        
                        <div class="pt-2">
                            <button type="button" onclick="editUnitClearDriver('edit_driver1'); editUnitClearDriver('edit_driver2')"
                                class="w-full bg-red-50 text-red-600 py-2 px-4 rounded-lg hover:bg-red-100 transition-colors flex items-center justify-center gap-2 border border-red-200">
                                <i data-lucide="user-x" class="w-4 h-4"></i> Remove All Drivers
                            </button>
                            <p class="text-xs text-gray-500 mt-1">Clear both driver assignments for this unit</p>
                        </div>
                    </div>
                </div>

                
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="p-2 bg-indigo-100 rounded-lg"><i data-lucide="calendar" class="w-5 h-5 text-indigo-600"></i></div>
                        <h4 class="text-lg font-semibold text-gray-900">Coding Information</h4>
                    </div>
                    <div class="mb-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex items-center gap-2 mb-3">
                            <i data-lucide="info" class="w-4 h-4 text-blue-600"></i>
                            <h5 class="font-semibold text-blue-900">MMDA Coding Schedule (Metro Manila)</h5>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-2 text-xs">
                            <div class="flex items-center gap-1"><span class="font-medium">Mon:</span><span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">1, 2</span></div>
                            <div class="flex items-center gap-1"><span class="font-medium">Tue:</span><span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">3, 4</span></div>
                            <div class="flex items-center gap-1"><span class="font-medium">Wed:</span><span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">5, 6</span></div>
                            <div class="flex items-center gap-1"><span class="font-medium">Thu:</span><span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">7, 8</span></div>
                            <div class="flex items-center gap-1"><span class="font-medium">Fri:</span><span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">9, 0</span></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Coding Day</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="calendar" class="w-5 h-5 text-gray-400"></i>
                                </div>
                                <input type="text" id="editCodingDay" name="coding_day" readonly
                                    class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-lg bg-gray-50"
                                    placeholder="Auto-generated">
                            </div>
                            <p class="text-xs text-gray-500">Auto-calculated from plate number</p>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Next Coding Date</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="calendar" class="w-5 h-5 text-gray-400"></i>
                                </div>
                                <input type="text" id="editNextCodingDate" readonly
                                    class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-lg bg-gray-50"
                                    placeholder="Auto-generated">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Days Until Next Coding</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="clock" class="w-5 h-5 text-gray-400"></i>
                                </div>
                                <input type="text" id="editDaysUntilCoding" readonly
                                    class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-lg bg-gray-50"
                                    placeholder="Auto-calculated">
                            </div>
                        </div>
                    </div>
                    <div id="editCodingStatusDisplay" class="mt-4"></div>
                </div>

                
                <div class="flex gap-3 mt-6 pt-4 border-t">
                    <button type="submit" class="flex-1 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold flex items-center justify-center gap-2">
                        <i data-lucide="save" class="w-5 h-5"></i> Update Unit
                    </button>
                    <button type="button" onclick="closeEditUnitModal()"
                        class="flex-1 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    
    <div id="unitDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden h-full w-full z-50 flex items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">

            
            <div class="bg-gradient-to-r from-green-500 to-green-700 p-6 rounded-t-lg">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                            <i data-lucide="eye" class="w-6 h-6 text-white"></i>
                        </div>
                        <div>
                            <h3 id="unitDetailsTitle" class="text-2xl font-bold text-white">Unit Details</h3>
                            <p class="text-green-100 text-sm">Complete unit information and history</p>
                        </div>
                    </div>
                    <button onclick="document.getElementById('unitDetailsModal').classList.add('hidden')"
                        class="text-white hover:bg-white hover:bg-opacity-20 rounded-lg p-2 transition-colors">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

                
                <div class="flex gap-1 mt-5 border-b border-green-400">
                    <button onclick="showUnitTab('overview')" id="tab-btn-overview"
                        class="unit-tab-btn px-4 py-2 text-sm font-medium text-white border-b-2 border-white transition-colors">
                        <i data-lucide="layout-dashboard" class="w-4 h-4 inline mr-1"></i> Overview
                    </button>
                    <button onclick="showUnitTab('financial')" id="tab-btn-financial"
                        class="unit-tab-btn px-4 py-2 text-sm font-medium text-green-200 border-b-2 border-transparent hover:text-white transition-colors">
                        <i data-lucide="dollar-sign" class="w-4 h-4 inline mr-1"></i> Financial
                    </button>
                    <button onclick="showUnitTab('drivers')" id="tab-btn-drivers"
                        class="unit-tab-btn px-4 py-2 text-sm font-medium text-green-200 border-b-2 border-transparent hover:text-white transition-colors">
                        <i data-lucide="users" class="w-4 h-4 inline mr-1"></i> Drivers
                    </button>
                    <button onclick="showUnitTab('devices')" id="tab-btn-devices"
                        class="unit-tab-btn px-4 py-2 text-sm font-medium text-green-200 border-b-2 border-transparent hover:text-white transition-colors">
                        <i data-lucide="smartphone" class="w-4 h-4 inline mr-1"></i> Devices
                    </button>
                    <button onclick="showUnitTab('maintenance')" id="tab-btn-maintenance"
                        class="unit-tab-btn px-4 py-2 text-sm font-medium text-green-200 border-b-2 border-transparent hover:text-white transition-colors">
                        <i data-lucide="wrench" class="w-4 h-4 inline mr-1"></i> Maintenance
                    </button>
                </div>
            </div>

            
            <div class="p-6">
                
                <div id="unitDetailsLoading" class="text-center py-12 hidden">
                    <div class="animate-spin w-10 h-10 border-4 border-green-500 border-t-transparent rounded-full mx-auto"></div>
                    <p class="text-gray-500 mt-3">Loading unit details...</p>
                </div>

                
                <div id="tab-overview" class="unit-tab-content">
                    <div id="unitOverviewContent" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="text-center py-8 col-span-2 text-gray-400">
                            <i data-lucide="car" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                            <p>Select a unit to view details</p>
                        </div>
                    </div>
                </div>

                
                <div id="tab-financial" class="unit-tab-content hidden">
                    <div id="unitFinancialContent" class="space-y-4">
                        <div class="text-center py-8 text-gray-400">Loading financial data...</div>
                    </div>
                </div>

                
                <div id="tab-drivers" class="unit-tab-content hidden">
                    <div id="unitDriversContent" class="space-y-4">
                        <div class="text-center py-8 text-gray-400">Loading driver data...</div>
                    </div>
                </div>

                
                <div id="tab-devices" class="unit-tab-content hidden">
                    <div id="unitDevicesContent" class="space-y-4">
                        <div class="text-center py-8 text-gray-400">Loading device data...</div>
                    </div>
                </div>

                
                <div id="tab-maintenance" class="unit-tab-content hidden">
                    <div id="unitMaintenanceContent" class="space-y-4">
                        <div class="text-center py-8 text-gray-400">Loading maintenance data...</div>
                    </div>
                </div>
            </div>

            
            <div class="px-6 pb-4 flex gap-3 border-t pt-4">
                <button id="editFromDetailsBtn" onclick="" class="flex-1 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center justify-center gap-2 font-medium">
                    <i data-lucide="edit-2" class="w-4 h-4"></i> Edit This Unit
                </button>
                <button onclick="document.getElementById('unitDetailsModal').classList.add('hidden')"
                    class="flex-1 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">Close</button>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        function editUnit(id) {
            fetch('<?php echo e(route("units.details")); ?>?id=' + id, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(r => {
                if (!r.ok) throw new Error('Server returned HTTP ' + r.status);
                return r.json();
            })
            .then(data => {
                // Guard: check for errors
                if (data.error) {
                    alert('Error: ' + data.error);
                    return;
                }
                const unit = data.unit;
                if (!unit) {
                    alert('Unit not found. Please refresh the page and try again.');
                    return;
                }

                // Basic info
                document.getElementById('editUnitNumber').value = unit.unit_number || '';
                document.getElementById('editPlateNumber').value = unit.plate_number || '';
                document.getElementById('editStatus').value = unit.status || 'active';

                // Vehicle
                document.getElementById('editMake').value = unit.make || '';
                document.getElementById('editModel').value = unit.model || '';
                document.getElementById('editYear').value = unit.year || '';
                document.getElementById('editColor').value = unit.color || '';
                document.getElementById('editUnitType').value = unit.unit_type || 'new';
                document.getElementById('editFuelStatus').value = unit.fuel_status || 'full';

                // Financial
                document.getElementById('editBoundaryRate').value = parseFloat(unit.boundary_rate || 0).toFixed(2);
                document.getElementById('editPurchaseCost').value = parseFloat(unit.purchase_cost || 0).toFixed(2);
                document.getElementById('editPurchaseDate').value = unit.purchase_date || '';

                // Drivers - set hidden selects and populate search inputs
                const d1Val = unit.driver_id || '';
                const d2Val = unit.secondary_driver_id || '';
                document.getElementById('edit_driver1').value = d1Val;
                document.getElementById('edit_driver2').value = d2Val;

                // Populate search inputs from select option text
                if (d1Val) {
                    const opt1 = document.querySelector(`#edit_driver1 option[value="${d1Val}"]`);
                    document.getElementById('edit_driver1_search').value = opt1 ? opt1.getAttribute('data-name') + (opt1.getAttribute('data-license') ? ' - ' + opt1.getAttribute('data-license') : '') : '';
                } else {
                    document.getElementById('edit_driver1_search').value = '';
                }
                if (d2Val) {
                    const opt2 = document.querySelector(`#edit_driver2 option[value="${d2Val}"]`);
                    document.getElementById('edit_driver2_search').value = opt2 ? opt2.getAttribute('data-name') + (opt2.getAttribute('data-license') ? ' - ' + opt2.getAttribute('data-license') : '') : '';
                } else {
                    document.getElementById('edit_driver2_search').value = '';
                }

                // Coding info - compute from plate number using top-level coding_day from API
                if (unit.plate_number) {
                    editUnitUpdateCodingFromPlate(unit.plate_number, data.coding_day || unit.coding_day || '');
                } else {
                    document.getElementById('editCodingDay').value = data.coding_day || unit.coding_day || '';
                    document.getElementById('editNextCodingDate').value = '';
                    document.getElementById('editDaysUntilCoding').value = '';
                }

                // Set form action
                document.getElementById('editUnitForm').action = '/units/' + id;

                // Show modal
                document.getElementById('editUnitModal').classList.remove('hidden');
                lucide.createIcons();
            })
            .catch(err => alert('Failed to load unit: ' + err));
        }

        function closeEditUnitModal() {
            document.getElementById('editUnitModal').classList.add('hidden');
            document.getElementById('editCodingStatusDisplay').innerHTML = '';
        }

        // Edit Unit - Searchable Driver Dropdowns
        function editUnitShowDropdown(driverType) {
            editUnitFilterDrivers(driverType);
            document.getElementById(driverType + '_dropdown').classList.remove('hidden');
        }
        function editUnitHideDropdown(driverType) {
            document.getElementById(driverType + '_dropdown').classList.add('hidden');
        }
        function editUnitFilterDrivers(driverType) {
            const searchInput = document.getElementById(driverType + '_search');
            const select = document.getElementById(driverType);
            const dropdown = document.getElementById(driverType + '_dropdown');
            const query = searchInput ? searchInput.value.toLowerCase() : '';
            const options = Array.from(select.options).slice(1);

            let html = '';
            options.forEach(opt => {
                const name = opt.getAttribute('data-name') || '';
                const license = opt.getAttribute('data-license') || '';
                if (!query || name.toLowerCase().includes(query) || license.toLowerCase().includes(query)) {
                    html += `<div class="px-4 py-3 hover:bg-blue-50 cursor-pointer border-b border-gray-100 last:border-b-0"
                                 onmousedown="editUnitSelectDriver('${driverType}','${opt.value}','${name.replace(/'/g,"\\'")}','${license.replace(/'/g,"\\'")}')">
                                <div class="font-medium text-gray-900">${name}</div>
                                <div class="text-sm text-gray-500">${license || 'No License'}</div>
                             </div>`;
                }
            });
            dropdown.innerHTML = html || '<p class="px-4 py-3 text-sm text-gray-500">No drivers found</p>';
            dropdown.classList.remove('hidden');
        }
        function editUnitSelectDriver(driverType, value, name, license) {
            document.getElementById(driverType).value = value;
            document.getElementById(driverType + '_search').value = name + (license ? ' - ' + license : '');
            editUnitHideDropdown(driverType);
        }
        function editUnitClearDriver(driverType) {
            document.getElementById(driverType).value = '';
            document.getElementById(driverType + '_search').value = '';
        }

        // Edit Unit - coding helper (shared logic)
        function editUnitGetLastDigit(plateNumber) {
            plateNumber = plateNumber.toUpperCase().trim().replace(/[^A-Z0-9]/g, '');
            if (plateNumber.length > 0) {
                const last = plateNumber.slice(-1);
                if (/[A-Z]/.test(last)) return last.charCodeAt(0) - 64;
                if (/[0-9]/.test(last)) return parseInt(last);
            }
            return null;
        }
        function editUnitUpdateCodingFromPlate(plate, existingCodingDay) {
            const schedule = { Monday:[1,2], Tuesday:[3,4], Wednesday:[5,6], Thursday:[7,8], Friday:[9,0] };
            const lastDigit = editUnitGetLastDigit(plate);
            let codingDay = existingCodingDay || '';
            if (!codingDay) {
                for (const [day, endings] of Object.entries(schedule)) {
                    if (endings.includes(lastDigit)) { codingDay = day; break; }
                }
            }

            const today = new Date();
            const daysOfWeek = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
            const todayName = daysOfWeek[today.getDay()];
            let isCodingToday = (todayName === codingDay);
            let daysUntil = 0;
            let nextDate = new Date(today);

            if (!isCodingToday && codingDay) {
                for (let i = 1; i <= 7; i++) {
                    const test = new Date(today);
                    test.setDate(today.getDate() + i);
                    if (daysOfWeek[test.getDay()] === codingDay) { nextDate = test; daysUntil = i; break; }
                }
            }

            document.getElementById('editCodingDay').value = codingDay || '';
            document.getElementById('editNextCodingDate').value = codingDay ? nextDate.toLocaleDateString('en-US') : '';
            document.getElementById('editDaysUntilCoding').value = codingDay ? (isCodingToday ? 0 : daysUntil) : '';

            const display = document.getElementById('editCodingStatusDisplay');
            if (display) {
                if (!codingDay) {
                    display.innerHTML = '';
                } else if (isCodingToday) {
                    display.innerHTML = `<div class="p-3 rounded-lg border-2 border-red-500 bg-red-50 flex items-center gap-2"><i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i><div><p class="text-sm font-semibold text-red-800">CODING TODAY!</p><p class="text-xs text-red-600">This unit is scheduled for coding today (${codingDay})</p></div></div>`;
                } else if (daysUntil === 1) {
                    display.innerHTML = `<div class="p-3 rounded-lg border-2 border-yellow-500 bg-yellow-50 flex items-center gap-2"><i data-lucide="clock" class="w-5 h-5 text-yellow-600"></i><div><p class="text-sm font-semibold text-yellow-800">CODING TOMORROW</p><p class="text-xs text-yellow-600">Next coding: ${codingDay}</p></div></div>`;
                } else {
                    display.innerHTML = `<div class="p-3 rounded-lg border-2 border-blue-400 bg-blue-50 flex items-center gap-2"><i data-lucide="calendar" class="w-5 h-5 text-blue-600"></i><div><p class="text-sm font-semibold text-blue-800">NEXT CODING</p><p class="text-xs text-blue-600">${codingDay} (${daysUntil} days)</p></div></div>`;
                }
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }
        }
        function editUnitUpdateCoding() {
            const plate = document.getElementById('editPlateNumber')?.value || '';
            if (plate) editUnitUpdateCodingFromPlate(plate, '');
        }

        // =============================================
        // VIEW UNIT DETAILS - Tabbed Modal
        // =============================================
        let currentViewUnitId = null;

        function viewUnitDetails(id) {
            currentViewUnitId = id;
            document.getElementById('unitDetailsModal').classList.remove('hidden');
            document.getElementById('unitDetailsLoading').classList.remove('hidden');
            document.getElementById('editFromDetailsBtn').onclick = () => {
                document.getElementById('unitDetailsModal').classList.add('hidden');
                editUnit(id);
            };

            fetch('<?php echo e(route("units.details")); ?>?id=' + id, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(r => {
                if (!r.ok) throw new Error('Server returned HTTP ' + r.status);
                return r.json();
            })
            .then(data => {
                document.getElementById('unitDetailsLoading').classList.add('hidden');
                const unit = data.unit;

                // Guard: if unit is null/undefined, show error
                if (!unit) {
                    document.getElementById('unitOverviewContent').innerHTML = '<p class="text-red-500 col-span-2 text-center py-8">Unit not found or failed to load.</p>';
                    showUnitTab('overview');
                    return;
                }

                document.getElementById('unitDetailsTitle').textContent = `Unit ${unit.unit_number || ''}`;

                // Extract driver names from assigned_drivers array
                const assignedDrivers = data.assigned_drivers || [];
                const primaryDriverObj = assignedDrivers.find(d => String(d.id) === String(unit.driver_id));
                const secondaryDriverObj = assignedDrivers.find(d => String(d.id) === String(unit.secondary_driver_id));
                const codingDayFromApi = data.coding_day || unit.coding_day || 'Not Set';

                // Overview Tab
                const statusColors = { active: 'green', maintenance: 'yellow', coding: 'blue', retired: 'gray' };
                const sc = statusColors[unit.status] || 'gray';
                document.getElementById('unitOverviewContent').innerHTML = `
                    <div class="bg-gray-50 rounded-xl p-5 border">
                        <h5 class="font-semibold text-gray-700 mb-4 flex items-center gap-2"><i data-lucide="car" class="w-5 h-5 text-blue-500"></i> Unit Information</h5>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div><span class="text-gray-500">Unit #:</span> <span class="font-medium">${unit.unit_number || '-'}</span></div>
                            <div><span class="text-gray-500">Plate:</span> <span class="font-medium">${unit.plate_number || '-'}</span></div>
                            <div><span class="text-gray-500">Make:</span> <span class="font-medium">${unit.make || '-'}</span></div>
                            <div><span class="text-gray-500">Model:</span> <span class="font-medium">${unit.model || '-'}</span></div>
                            <div><span class="text-gray-500">Year:</span> <span class="font-medium">${unit.year || '-'}</span></div>
                            <div><span class="text-gray-500">Color:</span> <span class="font-medium">${unit.color || '-'}</span></div>
                            <div><span class="text-gray-500">Type:</span> <span class="font-medium capitalize">${unit.unit_type || '-'}</span></div>
                            <div><span class="text-gray-500">Fuel:</span> <span class="font-medium capitalize">${unit.fuel_status || '-'}</span></div>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-5 border">
                        <h5 class="font-semibold text-gray-700 mb-4 flex items-center gap-2"><i data-lucide="activity" class="w-5 h-5 text-green-500"></i> Status & Coding</h5>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div class="col-span-2"><span class="text-gray-500">Status:</span>
                                <span class="ml-2 px-2 py-1 rounded-full text-xs font-semibold bg-${sc}-100 text-${sc}-800 capitalize">${unit.status || '-'}</span>
                            </div>
                            <div><span class="text-gray-500">Coding Day:</span> <span class="font-medium">${codingDayFromApi}</span></div>
                            <div><span class="text-gray-500">Boundary Rate:</span> <span class="font-medium">₱${parseFloat(unit.boundary_rate || 0).toLocaleString('en-PH', {minimumFractionDigits:2})}</span></div>
                        </div>
                    </div>
                `;

                // Financial Tab - use real roi_data from API
                const roi = data.roi_data || {};
                const roiPct = roi.roi_percentage || 0;
                const roiColor = roiPct >= 100 ? 'green' : roiPct >= 50 ? 'yellow' : 'red';
                document.getElementById('unitFinancialContent').innerHTML = `
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-yellow-50 rounded-xl p-4 border border-yellow-200 text-center">
                            <p class="text-xs text-yellow-700 font-semibold mb-1">BOUNDARY RATE</p>
                            <p class="text-xl font-bold text-yellow-800">₱${parseFloat(unit.boundary_rate || 0).toLocaleString('en-PH', {minimumFractionDigits:2})}</p>
                            <p class="text-xs text-yellow-600">Daily target</p>
                        </div>
                        <div class="bg-blue-50 rounded-xl p-4 border border-blue-200 text-center">
                            <p class="text-xs text-blue-700 font-semibold mb-1">TOTAL REVENUE</p>
                            <p class="text-xl font-bold text-blue-800">₱${parseFloat(roi.total_revenue || 0).toLocaleString('en-PH', {minimumFractionDigits:2})}</p>
                            <p class="text-xs text-blue-600">Paid boundary total</p>
                        </div>
                        <div class="bg-red-50 rounded-xl p-4 border border-red-200 text-center">
                            <p class="text-xs text-red-700 font-semibold mb-1">TOTAL EXPENSES</p>
                            <p class="text-xl font-bold text-red-800">₱${parseFloat(roi.total_expenses || 0).toLocaleString('en-PH', {minimumFractionDigits:2})}</p>
                            <p class="text-xs text-red-600">Maintenance costs</p>
                        </div>
                        <div class="bg-${roiColor}-50 rounded-xl p-4 border border-${roiColor}-200 text-center">
                            <p class="text-xs text-${roiColor}-700 font-semibold mb-1">ROI</p>
                            <p class="text-xl font-bold text-${roiColor}-800">${roiPct.toFixed(1)}%</p>
                            <p class="text-xs text-${roiColor}-600">${roiPct >= 100 ? '✅ Achieved' : '⏳ In progress'}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="bg-gray-50 rounded-xl p-4 border text-center">
                            <p class="text-xs text-gray-500 font-semibold mb-1">PURCHASE COST</p>
                            <p class="text-lg font-bold text-gray-800">₱${parseFloat(roi.total_investment || 0).toLocaleString('en-PH', {minimumFractionDigits:2})}</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 border text-center">
                            <p class="text-xs text-gray-500 font-semibold mb-1">MONTHLY BOUNDARY</p>
                            <p class="text-lg font-bold text-gray-800">₱${parseFloat(roi.monthly_boundary || 0).toLocaleString('en-PH', {minimumFractionDigits:2})}</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 border text-center">
                            <p class="text-xs text-gray-500 font-semibold mb-1">PAYBACK PERIOD</p>
                            <p class="text-lg font-bold text-gray-800">${roi.payback_period > 0 ? roi.payback_period.toFixed(1) + ' mo' : 'N/A'}</p>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 border">
                        <p class="text-xs text-gray-500 mb-1">Purchase Date</p>
                        <p class="font-medium text-gray-800">${unit.purchase_date || 'Not Set'}</p>
                    </div>
                `;

                // Drivers Tab - use assigned_drivers array from API
                const primaryDriver = primaryDriverObj ? primaryDriverObj.full_name : (unit.driver_id ? 'Driver ID: ' + unit.driver_id : null);
                const primaryLicense = primaryDriverObj ? (primaryDriverObj.license_number || 'No License') : '';
                const primaryContact = primaryDriverObj ? (primaryDriverObj.contact_number || '') : '';
                const secondaryDriver = secondaryDriverObj ? secondaryDriverObj.full_name : (unit.secondary_driver_id ? 'Driver ID: ' + unit.secondary_driver_id : null);
                const secondaryLicense = secondaryDriverObj ? (secondaryDriverObj.license_number || 'No License') : '';
                const secondaryContact = secondaryDriverObj ? (secondaryDriverObj.contact_number || '') : '';
                document.getElementById('unitDriversContent').innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-blue-50 rounded-xl p-5 border border-blue-200">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="p-2 bg-blue-500 rounded-lg"><i data-lucide="user" class="w-5 h-5 text-white"></i></div>
                                <h5 class="font-semibold text-blue-900">Primary Driver</h5>
                            </div>
                            ${primaryDriver
                                ? `<p class="text-blue-800 font-semibold">${primaryDriver}</p>
                                   <p class="text-xs text-blue-600 mt-1">📋 ${primaryLicense}</p>
                                   ${primaryContact ? `<p class="text-xs text-blue-500">📞 ${primaryContact}</p>` : ''}
                                   <p class="text-xs text-blue-400 mt-1">Main assigned driver</p>`
                                : `<p class="text-blue-400 italic text-sm">No primary driver assigned</p>`
                            }
                        </div>
                        <div class="bg-indigo-50 rounded-xl p-5 border border-indigo-200">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="p-2 bg-indigo-500 rounded-lg"><i data-lucide="user-plus" class="w-5 h-5 text-white"></i></div>
                                <h5 class="font-semibold text-indigo-900">Secondary Driver</h5>
                            </div>
                            ${secondaryDriver
                                ? `<p class="text-indigo-800 font-semibold">${secondaryDriver}</p>
                                   <p class="text-xs text-indigo-600 mt-1">📋 ${secondaryLicense}</p>
                                   ${secondaryContact ? `<p class="text-xs text-indigo-500">📞 ${secondaryContact}</p>` : ''}
                                   <p class="text-xs text-indigo-400 mt-1">Backup or relief driver</p>`
                                : `<p class="text-indigo-400 italic text-sm">No secondary driver assigned</p>`
                            }
                        </div>
                    </div>
                `;

                // Devices Tab - use location_info from API
                const locInfo = data.location_info || {};
                document.getElementById('unitDevicesContent').innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="border-2 ${locInfo.gps_enabled ? 'border-indigo-400 bg-indigo-50' : 'border-dashed border-gray-300 bg-gray-50'} rounded-xl p-5">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="map-pin" class="w-5 h-5 ${locInfo.gps_enabled ? 'text-indigo-600' : 'text-gray-400'}"></i>
                                    <h5 class="font-semibold text-gray-900">GPS Tracking</h5>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs font-semibold ${locInfo.gps_enabled ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600'}">
                                    ${locInfo.gps_enabled ? '🟢 Active' : '⚫ Inactive'}
                                </span>
                            </div>
                            <div class="text-sm space-y-2">
                                <div class="flex justify-between"><span class="text-gray-500">Location:</span><span class="font-medium">${locInfo.current_location || 'Unknown'}</span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Last Update:</span><span class="font-medium">${locInfo.last_location_update || 'Never'}</span></div>
                                ${locInfo.coordinates ? `<div class="flex justify-between"><span class="text-gray-500">Coordinates:</span><span class="font-medium text-xs">${locInfo.coordinates}</span></div>` : ''}
                            </div>
                        </div>
                        <div class="border-2 ${data.dashcam_info?.dashcam_enabled ? 'border-purple-400 bg-purple-50' : 'border-dashed border-gray-300 bg-gray-50'} rounded-xl p-5">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="camera" class="w-5 h-5 ${data.dashcam_info?.dashcam_enabled ? 'text-purple-600' : 'text-gray-400'}"></i>
                                    <h5 class="font-semibold text-gray-900">Dashcam</h5>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs font-semibold ${data.dashcam_info?.dashcam_enabled ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600'}">
                                    ${data.dashcam_info?.dashcam_enabled ? '🟢 Active' : '⚫ Inactive'}
                                </span>
                            </div>
                            <div class="text-sm space-y-2">
                                <div class="flex justify-between"><span class="text-gray-500">Status:</span><span class="font-medium">${data.dashcam_info?.dashcam_status || 'Offline'}</span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Last Recording:</span><span class="font-medium">${data.dashcam_info?.last_recording || 'Never'}</span></div>
                            </div>
                        </div>
                    </div>
                `;

                // Maintenance Tab - use real maintenance_records from API
                const mainRecs = data.maintenance_records || [];
                if (mainRecs.length === 0) {
                    document.getElementById('unitMaintenanceContent').innerHTML = `
                        <div class="bg-gray-50 rounded-xl p-5 border text-center">
                            <i data-lucide="wrench" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                            <p class="text-gray-500">No maintenance records found.</p>
                            <p class="text-xs text-gray-400 mt-1">Records will appear here when maintenance is logged.</p>
                        </div>
                    `;
                } else {
                    const rows = mainRecs.map(rec => `
                        <div class="flex items-start justify-between p-4 bg-gray-50 rounded-lg border">
                            <div>
                                <p class="font-semibold text-gray-800">${rec.type || rec.maintenance_type || 'Maintenance'}</p>
                                <p class="text-sm text-gray-600">${rec.description || rec.notes || '-'}</p>
                                <p class="text-xs text-gray-400 mt-1">${rec.date_started || rec.date || '-'}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-800">₱${parseFloat(rec.cost || 0).toLocaleString('en-PH', {minimumFractionDigits:2})}</p>
                                <span class="px-2 py-1 rounded-full text-xs ${rec.status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">${rec.status || 'pending'}</span>
                            </div>
                        </div>
                    `).join('');
                    document.getElementById('unitMaintenanceContent').innerHTML = `<div class="space-y-3">${rows}</div>`;
                }

                // Show overview tab by default
                showUnitTab('overview');
                lucide.createIcons();
            })
            .catch(err => {
                document.getElementById('unitDetailsLoading').classList.add('hidden');
                document.getElementById('unitOverviewContent').innerHTML = '<p class="text-red-500 col-span-2 text-center py-8">Failed to load unit details. Please try again.</p>';
                showUnitTab('overview');
            });
        }

        function showUnitTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.unit-tab-content').forEach(el => el.classList.add('hidden'));
            // Reset all tab buttons
            document.querySelectorAll('.unit-tab-btn').forEach(btn => {
                btn.classList.remove('text-white', 'border-white');
                btn.classList.add('text-green-200', 'border-transparent');
            });
            // Show selected tab
            const tab = document.getElementById('tab-' + tabName);
            if (tab) tab.classList.remove('hidden');
            // Activate selected button
            const btn = document.getElementById('tab-btn-' + tabName);
            if (btn) {
                btn.classList.remove('text-green-200', 'border-transparent');
                btn.classList.add('text-white', 'border-white');
            }
        }
    </script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// =============================================
// ADD UNIT MODAL - Driver Searchable Dropdown
// =============================================
function addUnitShowDropdown(driverType) {
    addUnitFilterDrivers(driverType);
    document.getElementById(driverType + '_dropdown').classList.remove('hidden');
}
function addUnitHideDropdown(driverType) {
    document.getElementById(driverType + '_dropdown').classList.add('hidden');
}
function addUnitFilterDrivers(driverType) {
    const searchInput = document.getElementById(driverType + '_search');
    const select = document.getElementById(driverType);
    const dropdown = document.getElementById(driverType + '_dropdown');
    const query = searchInput ? searchInput.value.toLowerCase() : '';
    const options = Array.from(select.options).slice(1);

    let html = '';
    options.forEach(opt => {
        const name = opt.getAttribute('data-name') || '';
        const license = opt.getAttribute('data-license') || '';
        const display = name + ' - ' + license;
        if (!query || name.toLowerCase().includes(query) || license.toLowerCase().includes(query)) {
            html += `<div class="px-4 py-3 hover:bg-yellow-50 cursor-pointer border-b border-gray-100 last:border-b-0"
                         onmousedown="addUnitSelectDriver('${driverType}','${opt.value}','${name.replace(/'/g,"\\'")}','${license.replace(/'/g,"\\'")}')">
                        <div class="font-medium text-gray-900">${name}</div>
                        <div class="text-sm text-gray-500">${license || 'No License'}</div>
                     </div>`;
        }
    });
    dropdown.innerHTML = html || '<p class="px-4 py-3 text-sm text-gray-500">No drivers found</p>';
    dropdown.classList.remove('hidden');
}
function addUnitSelectDriver(driverType, value, name, license) {
    document.getElementById(driverType).value = value;
    document.getElementById(driverType + '_search').value = name + (license ? ' - ' + license : '');
    addUnitHideDropdown(driverType);
}
function addUnitClearDriver(driverType) {
    document.getElementById(driverType).value = '';
    document.getElementById(driverType + '_search').value = '';
}

// =============================================
// ADD UNIT MODAL - Auto Coding Calculation
// =============================================
function addUnitGetLastDigit(plateNumber) {
    plateNumber = plateNumber.toUpperCase().trim().replace(/[^A-Z0-9]/g, '');
    if (plateNumber.length > 0) {
        const last = plateNumber.slice(-1);
        if (/[A-Z]/.test(last)) return last.charCodeAt(0) - 64;
        if (/[0-9]/.test(last)) return parseInt(last);
    }
    return null;
}
function addUnitUpdateCoding() {
    const plate = document.getElementById('addPlateNumber')?.value || '';
    if (!plate) return;

    const schedule = { Monday:[1,2], Tuesday:[3,4], Wednesday:[5,6], Thursday:[7,8], Friday:[9,0] };
    const lastDigit = addUnitGetLastDigit(plate);
    let codingDay = '';
    for (const [day, endings] of Object.entries(schedule)) {
        if (endings.includes(lastDigit)) { codingDay = day; break; }
    }

    const today = new Date();
    const daysOfWeek = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
    const todayName = daysOfWeek[today.getDay()];
    let isCodingToday = (todayName === codingDay);
    let daysUntil = 0;
    let nextDate = new Date(today);

    if (!isCodingToday && codingDay) {
        for (let i = 1; i <= 7; i++) {
            const test = new Date(today);
            test.setDate(today.getDate() + i);
            if (daysOfWeek[test.getDay()] === codingDay) { nextDate = test; daysUntil = i; break; }
        }
    }

    // Boundary rate by coding day
    const rates = { Monday:1200, Tuesday:1100, Wednesday:1150, Thursday:1050, Friday:1300 };
    if (codingDay && rates[codingDay]) {
        document.getElementById('addBoundaryRate').value = rates[codingDay].toFixed(2);
    }

    document.getElementById('addCodingDay').value = codingDay || '';
    document.getElementById('addNextCodingDate').value = codingDay ? nextDate.toLocaleDateString('en-US') : '';
    document.getElementById('addDaysUntilCoding').value = codingDay ? (isCodingToday ? 0 : daysUntil) : '';

    // Auto-set status to coding if today is coding day
    if (isCodingToday) {
        document.getElementById('addUnitStatus').value = 'coding';
    }

    // Update coding status display
    const display = document.getElementById('addCodingStatusDisplay');
    if (!codingDay) {
        display.innerHTML = '<div class="p-3 rounded-lg border-2 border-gray-300 bg-gray-50 flex items-center gap-2"><i data-lucide="info" class="w-5 h-5 text-gray-500"></i><div><p class="text-sm font-semibold text-gray-800">NO CODING SCHEDULE</p><p class="text-xs text-gray-500">Plate number does not match MMDA schedule</p></div></div>';
    } else if (isCodingToday) {
        display.innerHTML = `<div class="p-3 rounded-lg border-2 border-red-500 bg-red-50 flex items-center gap-2"><i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i><div><p class="text-sm font-semibold text-red-800">CODING TODAY!</p><p class="text-xs text-red-600">This unit is scheduled for coding today (${codingDay})</p></div></div>`;
    } else if (daysUntil === 1) {
        display.innerHTML = `<div class="p-3 rounded-lg border-2 border-yellow-500 bg-yellow-50 flex items-center gap-2"><i data-lucide="clock" class="w-5 h-5 text-yellow-600"></i><div><p class="text-sm font-semibold text-yellow-800">CODING TOMORROW</p><p class="text-xs text-yellow-600">Next coding: ${codingDay}</p></div></div>`;
    } else {
        display.innerHTML = `<div class="p-3 rounded-lg border-2 border-blue-400 bg-blue-50 flex items-center gap-2"><i data-lucide="calendar" class="w-5 h-5 text-blue-600"></i><div><p class="text-sm font-semibold text-blue-800">NEXT CODING</p><p class="text-xs text-blue-600">${codingDay} (${daysUntil} days)</p></div></div>`;
    }
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

// =============================================
// ADD UNIT MODAL - GPS/Dashcam Devices
// =============================================
let addUnitGPS = [], addUnitDashcam = [];

function addUnitAddGPS() {
    const id = prompt('Enter GPS Device ID:');
    if (id && id.trim()) {
        addUnitGPS.push({ id: id.trim() });
        addUnitRenderGPS();
    }
}
function addUnitAddDashcam() {
    const id = prompt('Enter Dashcam Device ID:');
    if (id && id.trim()) {
        addUnitDashcam.push({ id: id.trim() });
        addUnitRenderDashcam();
    }
}
function addUnitRemoveGPS(index) { addUnitGPS.splice(index, 1); addUnitRenderGPS(); }
function addUnitRemoveDashcam(index) { addUnitDashcam.splice(index, 1); addUnitRenderDashcam(); }
function addUnitRenderGPS() {
    const list = document.getElementById('addGPSDevicesList');
    if (!addUnitGPS.length) { list.innerHTML = '<p class="text-sm text-gray-500 text-center py-2">No GPS devices added</p>'; return; }
    list.innerHTML = addUnitGPS.map((d, i) => `
        <div class="flex items-center justify-between p-2 bg-indigo-50 rounded-lg">
            <div class="flex items-center gap-2"><i data-lucide="map-pin" class="w-4 h-4 text-indigo-600"></i>
                <span class="text-sm font-medium">${d.id}</span>
                <span class="px-2 py-0.5 bg-green-100 text-green-800 text-xs rounded-full">Active</span>
            </div>
            <button type="button" onclick="addUnitRemoveGPS(${i})" class="text-red-500 hover:text-red-700"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
        <input type="hidden" name="gps_devices[]" value="${d.id}">
    `).join('');
    if (typeof lucide !== 'undefined') lucide.createIcons();
}
function addUnitRenderDashcam() {
    const list = document.getElementById('addDashcamDevicesList');
    if (!addUnitDashcam.length) { list.innerHTML = '<p class="text-sm text-gray-500 text-center py-2">No dashcam devices added</p>'; return; }
    list.innerHTML = addUnitDashcam.map((d, i) => `
        <div class="flex items-center justify-between p-2 bg-purple-50 rounded-lg">
            <div class="flex items-center gap-2"><i data-lucide="camera" class="w-4 h-4 text-purple-600"></i>
                <span class="text-sm font-medium">${d.id}</span>
                <span class="px-2 py-0.5 bg-green-100 text-green-800 text-xs rounded-full">Active</span>
            </div>
            <button type="button" onclick="addUnitRemoveDashcam(${i})" class="text-red-500 hover:text-red-700"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
        <input type="hidden" name="dashcam_devices[]" value="${d.id}">
    `).join('');
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

// Reset the Add Unit modal
function resetAddUnitModal() {
    document.getElementById('addUnitForm')?.reset();
    addUnitClearDriver('add_driver1');
    addUnitClearDriver('add_driver2');
    document.getElementById('addCodingDay').value = '';
    document.getElementById('addNextCodingDate').value = '';
    document.getElementById('addDaysUntilCoding').value = '';
    document.getElementById('addCodingStatusDisplay').innerHTML = '';
    addUnitGPS = []; addUnitDashcam = [];
    addUnitRenderGPS(); addUnitRenderDashcam();
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\eurotaxisystem\resources\views/units/index.blade.php ENDPATH**/ ?>