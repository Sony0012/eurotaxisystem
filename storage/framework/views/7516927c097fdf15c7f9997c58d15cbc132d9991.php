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

    <!-- Add Unit Modal -->
    <div id="addUnitModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl p-6 max-h-screen overflow-y-auto">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Add New Unit</h3>
                <button onclick="document.getElementById('addUnitModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form method="POST" action="<?php echo e(route('units.store')); ?>" id="addUnitForm">
                <?php echo csrf_field(); ?>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit Number *</label>
                        <input type="text" name="unit_number"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Plate Number *</label>
                        <input type="text" name="plate_number"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Make *</label>
                        <input type="text" name="make"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Model *</label>
                        <input type="text" name="model"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Year *</label>
                        <input type="number" name="year" value="<?php echo e(date('Y')); ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                        <input type="text" name="color"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                        <select name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                            <option value="active">Active</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="coding">Coding</option>
                            <option value="retired">Retired</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Boundary Rate (₱) *</label>
                        <input type="number" name="boundary_rate" value="1100.00" step="0.01"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit Type</label>
                        <select name="unit_type"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                            <option value="new">New</option>
                            <option value="old">Old</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fuel Status</label>
                        <select name="fuel_status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                            <option value="full">Full</option>
                            <option value="half">Half</option>
                            <option value="low">Low</option>
                            <option value="empty">Empty</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Coding Day</label>
                        <select name="coding_day"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                            <option value="">No Coding</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Purchase Date</label>
                        <input type="date" name="purchase_date"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Purchase Cost (₱)</label>
                        <input type="number" name="purchase_cost" value="0" step="0.01"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Driver 1</label>
                        <select name="driver_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                            <option value="">Select Driver</option>
                            <?php $__currentLoopData = $all_drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($driver->id); ?>"><?php echo e($driver->full_name); ?> (<?php echo e($driver->contact_number); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Driver 2</label>
                        <select name="secondary_driver_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                            <option value="">Select Driver</option>
                            <?php $__currentLoopData = $all_drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($driver->id); ?>"><?php echo e($driver->full_name); ?> (<?php echo e($driver->contact_number); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="submit" class="flex-1 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Add
                        Unit</button>
                    <button type="button" onclick="document.getElementById('addUnitModal').classList.add('hidden')"
                        class="flex-1 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Unit Modal -->
    <div id="editUnitModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl p-6 max-h-screen overflow-y-auto">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Edit Unit</h3>
                <button onclick="document.getElementById('editUnitModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form method="POST" id="editUnitForm">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit Number</label>
                        <input type="text" name="unit_number" id="editUnitNumber"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Plate Number</label>
                        <input type="text" name="plate_number" id="editPlateNumber"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Make</label>
                        <input type="text" name="make" id="editMake"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                        <input type="text" name="model" id="editModel"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                        <input type="number" name="year" id="editYear"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                        <input type="text" name="color" id="editColor"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="editStatus"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                            <option value="active">Active</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="coding">Coding</option>
                            <option value="retired">Retired</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Boundary Rate (₱)</label>
                        <input type="number" name="boundary_rate" id="editBoundaryRate" step="0.01"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit Type</label>
                        <select name="unit_type" id="editUnitType"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                            <option value="new">New</option>
                            <option value="old">Old</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fuel Status</label>
                        <select name="fuel_status" id="editFuelStatus"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                            <option value="full">Full</option>
                            <option value="half">Half</option>
                            <option value="low">Low</option>
                            <option value="empty">Empty</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Primary Driver</label>
                        <select name="driver_id" id="editDriver1"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                            <option value="">No Driver</option>
                            <?php $__currentLoopData = $all_drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($d->id); ?>"><?php echo e($d->full_name); ?> (<?php echo e($d->contact_number); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Secondary Driver</label>
                        <select name="secondary_driver_id" id="editDriver2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                            <option value="">No Driver</option>
                            <?php $__currentLoopData = $all_drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($d->id); ?>"><?php echo e($d->full_name); ?> (<?php echo e($d->contact_number); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Purchase Date</label>
                        <input type="date" name="purchase_date" id="editPurchaseDate"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Purchase Cost (₱)</label>
                        <input type="number" name="purchase_cost" id="editPurchaseCost" step="0.01"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="submit" class="flex-1 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update
                        Unit</button>
                    <button type="button" onclick="document.getElementById('editUnitModal').classList.add('hidden')"
                        class="flex-1 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Unit Details Modal -->
    <div id="unitDetailsModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl p-6 max-h-screen overflow-y-auto">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Unit Details</h3>
                <button onclick="document.getElementById('unitDetailsModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div id="unitDetailsContent" class="text-center py-8">
                <div class="animate-spin w-8 h-8 border-4 border-yellow-500 border-t-transparent rounded-full mx-auto">
                </div>
                <p class="text-gray-500 mt-2">Loading...</p>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        function editUnit(id) {
            fetch('<?php echo e(route("units.details")); ?>?id=' + id, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(r => r.json())
                .then(data => {
                    const unit = data.unit;
                    document.getElementById('editUnitNumber').value = unit.unit_number || '';
                    document.getElementById('editPlateNumber').value = unit.plate_number || '';
                    document.getElementById('editMake').value = unit.make || '';
                    document.getElementById('editModel').value = unit.model || '';
                    document.getElementById('editYear').value = unit.year || '';
                    document.getElementById('editColor').value = unit.color || '';
                    document.getElementById('editStatus').value = unit.status || 'active';
                    document.getElementById('editBoundaryRate').value = unit.boundary_rate || '';
                    document.getElementById('editUnitType').value = unit.unit_type || 'new';
                    document.getElementById('editFuelStatus').value = unit.fuel_status || 'full';
                    document.getElementById('editDriver1').value = unit.driver_id || '';
                    document.getElementById('editDriver2').value = unit.secondary_driver_id || '';
                    document.getElementById('editPurchaseDate').value = unit.purchase_date || '';
                    document.getElementById('editPurchaseCost').value = unit.purchase_cost || 0;
                    document.getElementById('editUnitForm').action = '/units/' + id;
                    document.getElementById('editUnitModal').classList.remove('hidden');
                    lucide.createIcons();
                })
                .catch(err => alert('Failed to load unit: ' + err));
        }

        function viewUnitDetails(id) {
            document.getElementById('unitDetailsContent').innerHTML = '<div class="animate-spin w-8 h-8 border-4 border-yellow-500 border-t-transparent rounded-full mx-auto"></div><p class="text-gray-500 mt-2">Loading...</p>';
            document.getElementById('unitDetailsModal').classList.remove('hidden');

            fetch('<?php echo e(route("units.details")); ?>?id=' + id, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(r => r.json())
                .then(data => {
                    const unit = data.unit;
                    const roi = data.roi_data;
                    let html = `
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div><p class="text-xs text-gray-500">Unit Number</p><p class="font-bold">${unit.unit_number}</p></div>
                    <div><p class="text-xs text-gray-500">Plate</p><p class="font-bold">${unit.plate_number}</p></div>
                    <div><p class="text-xs text-gray-500">Make/Model</p><p class="font-bold">${unit.make} ${unit.model} ${unit.year}</p></div>
                    <div><p class="text-xs text-gray-500">Status</p><p class="font-bold capitalize">${unit.status}</p></div>
                    <div><p class="text-xs text-gray-500">Boundary Rate</p><p class="font-bold text-green-600">₱${parseFloat(unit.boundary_rate).toLocaleString()}</p></div>
                    <div><p class="text-xs text-gray-500">Coding Day</p><p class="font-bold">${data.coding_day || 'N/A'}</p></div>
                </div>
                <div class="border-t pt-4 mb-4">
                    <h4 class="font-semibold mb-3">ROI Summary</h4>
                    <div class="grid grid-cols-3 gap-3">
                        <div class="bg-green-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Total Revenue</p>
                            <p class="font-bold text-green-700">₱${parseFloat(roi.total_revenue || 0).toLocaleString()}</p>
                        </div>
                        <div class="bg-red-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Total Expenses</p>
                            <p class="font-bold text-red-700">₱${parseFloat(roi.total_expenses || 0).toLocaleString()}</p>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">ROI %</p>
                            <p class="font-bold text-blue-700">${parseFloat(roi.roi_percentage || 0).toFixed(1)}%</p>
                        </div>
                    </div>
                </div>
            `;
                    if (data.assigned_drivers && data.assigned_drivers.length > 0) {
                        html += '<div class="border-t pt-4"><h4 class="font-semibold mb-3">Assigned Drivers</h4><div class="space-y-2">';
                        data.assigned_drivers.forEach(d => {
                            html += `<div class="bg-gray-50 rounded-lg p-3 flex items-center gap-3">
                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center text-white font-bold text-sm">${d.full_name.charAt(0)}</div>
                        <div>
                            <p class="font-medium">${d.full_name}</p>
                            <p class="text-xs text-gray-500">License: ${d.license_number} &bull; ${d.contact_number || ''}</p>
                        </div>
                    </div>`;
                        });
                        html += '</div></div>';
                    }
                    document.getElementById('unitDetailsContent').innerHTML = html;
                    lucide.createIcons();
                })
                .catch(err => {
                    document.getElementById('unitDetailsContent').innerHTML = '<p class="text-red-500">Failed to load details.</p>';
                });
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\eurotaxisystem\resources\views/units/index.blade.php ENDPATH**/ ?>