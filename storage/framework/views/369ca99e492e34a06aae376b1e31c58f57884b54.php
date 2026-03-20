<?php
/** @var \Illuminate\Support\Collection $boundaries */
/** @var array $pagination */
/** @var string $search */
/** @var string $date_from */
/** @var string $date_to */
/** @var object $totals */
/** @var \Illuminate\Support\Collection $units */
/** @var \Illuminate\Support\Collection $drivers */
?>


<?php $__env->startSection('title', 'Boundaries - Euro System'); ?>
<?php $__env->startSection('page-heading', 'Boundary Collection'); ?>
<?php $__env->startSection('page-subheading', 'Track daily boundary payments from drivers'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="<?php echo e(route('boundaries.index')); ?>" class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="<?php echo e($search); ?>"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 focus:outline-none"
                    placeholder="Search unit, plate, driver...">
            </div>
            <div class="lg:w-36">
                <input type="date" name="date_from" value="<?php echo e($date_from); ?>"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
            </div>
            <div class="lg:w-36">
                <input type="date" name="date_to" value="<?php echo e($date_to); ?>"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
            </div>
            <div class="lg:w-36">
                <select name="status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                    <option value="">All Status</option>
                    <option value="paid" <?php echo e(request('status') === 'paid' ? 'selected' : ''); ?>>Paid</option>
                    <option value="short" <?php echo e(request('status') === 'short' ? 'selected' : ''); ?>>Short</option>
                    <option value="late" <?php echo e(request('status') === 'late' ? 'selected' : ''); ?>>Late</option>
                    <option value="excess" <?php echo e(request('status') === 'excess' ? 'selected' : ''); ?>>Excess</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 flex items-center gap-2">
                    <i data-lucide="search" class="w-4 h-4"></i> Search
                </button>
                <button type="button" onclick="document.getElementById('addBoundaryModal').classList.remove('hidden')"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i> Record Boundary
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-5">
            <p class="text-sm text-gray-500">Today's Collection</p>
            <p class="text-2xl font-bold text-green-600"><?php echo e(formatCurrency($totals->today_total ?? 0)); ?></p>
            <p class="text-xs text-gray-400"><?php echo e(date('M d, Y')); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-5">
            <p class="text-sm text-gray-500">Total Collected</p>
            <p class="text-2xl font-bold text-blue-600"><?php echo e(formatCurrency($totals->total_amount ?? 0)); ?></p>
            <p class="text-xs text-gray-400"><?php echo e($totals->total_records ?? 0); ?> records</p>
        </div>
        <div class="bg-white rounded-lg shadow p-5">
            <p class="text-sm text-gray-500">Shortage</p>
            <p class="text-2xl font-bold text-red-600"><?php echo e(formatCurrency($totals->total_shortage ?? 0)); ?></p>
            <p class="text-xs text-gray-400">Deficit amount</p>
        </div>
        <div class="bg-white rounded-lg shadow p-5">
            <p class="text-sm text-gray-500">Period</p>
            <p class="text-base font-bold text-gray-900"><?php echo e(formatDate($date_from)); ?> – <?php echo e(formatDate($date_to)); ?></p>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Driver</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Boundary Amt</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actual Paid</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shortage / Excess</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $boundaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $actual = $b->actual_amount ?? $b->boundary_amount ?? 0;
                            $diff = $actual - ($b->boundary_amount ?? 0);
                        ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e(formatDate($b->date)); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo e($b->unit_number ?? 'N/A'); ?></div>
                                <div class="text-xs text-gray-500"><?php echo e($b->plate_number ?? ''); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($b->driver_name ?? '—'); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                <?php echo e(formatCurrency($b->boundary_amount ?? 0)); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e(formatCurrency($actual)); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold <?php echo e($diff >= 0 ? 'text-green-600' : 'text-red-600'); ?>">
                                <?php echo e($diff >= 0 ? '+' : ''); ?><?php echo e(formatCurrency($diff)); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full
                                        <?php if($b->status === 'paid'): ?> bg-green-100 text-green-800
                                        <?php elseif($b->status === 'late'): ?> bg-yellow-100 text-yellow-800
                                        <?php elseif($b->status === 'short'): ?> bg-red-100 text-red-800
                                        <?php elseif($b->status === 'excess'): ?> bg-blue-100 text-blue-800
                                        <?php else: ?> bg-gray-100 text-gray-800 <?php endif; ?>">
                                    <?php echo e(ucfirst($b->status)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <form method="POST" action="<?php echo e(route('boundaries.destroy', $b->id)); ?>"
                                    onsubmit="return confirm('Delete this record?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <i data-lucide="dollar-sign" class="w-12 h-12 mx-auto mb-4 text-gray-300"></i>
                                <p>No boundary records found</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if($pagination['total_pages'] > 1): ?>
            <div class="px-6 py-4 border-t flex items-center justify-between">
                <p class="text-sm text-gray-700">
                    Showing <?php echo e($pagination['total_items']); ?> records / Page <?php echo e($pagination['page']); ?> of
                    <?php echo e($pagination['total_pages']); ?>

                </p>
                <div class="flex gap-2">
                    <?php if($pagination['has_prev']): ?>
                        <a href="?page=<?php echo e($pagination['prev_page']); ?>&search=<?php echo e($search); ?>&date_from=<?php echo e($date_from); ?>&date_to=<?php echo e($date_to); ?>"
                            class="px-3 py-1 border rounded text-sm hover:bg-gray-50">Previous</a>
                    <?php endif; ?>
                    <?php if($pagination['has_next']): ?>
                        <a href="?page=<?php echo e($pagination['next_page']); ?>&search=<?php echo e($search); ?>&date_from=<?php echo e($date_from); ?>&date_to=<?php echo e($date_to); ?>"
                            class="px-3 py-1 border rounded text-sm hover:bg-gray-50">Next</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Add Boundary Modal -->
    <div id="addBoundaryModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Record Boundary</h3>
                <button onclick="document.getElementById('addBoundaryModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form method="POST" action="<?php echo e(route('boundaries.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                        <select name="unit_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none"
                            required>
                            <option value="">Select unit...</option>
                            <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($unit->id); ?>"><?php echo e($unit->unit_number); ?> - <?php echo e($unit->plate_number); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Boundary Amount (₱) *</label>
                        <input type="number" name="boundary_amount" step="0.01" id="boundaryAmt"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none"
                            oninput="computeShortage()" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Actual Amount Paid (₱)</label>
                        <input type="number" name="actual_amount" step="0.01" id="actualAmt"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none"
                            oninput="computeShortage()">
                        <p id="shortageDisplay" class="text-xs mt-1 font-medium"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date *</label>
                        <input type="date" name="date" value="<?php echo e(date('Y-m-d')); ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                            <option value="paid">Paid</option>
                            <option value="late">Late</option>
                            <option value="short">Short</option>
                            <option value="excess">Excess</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none"></textarea>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="submit"
                        class="flex-1 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Save</button>
                    <button type="button" onclick="document.getElementById('addBoundaryModal').classList.add('hidden')"
                        class="flex-1 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancel</button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function computeShortage() {
    const boundary = parseFloat(document.getElementById('boundaryAmt')?.value) || 0;
    const actual   = parseFloat(document.getElementById('actualAmt')?.value) || 0;
    const display  = document.getElementById('shortageDisplay');
    if (!display) return;
    if (actual === 0) { display.textContent = ''; return; }
    const diff = actual - boundary;
    if (diff < 0) {
        display.className = 'text-xs mt-1 font-medium text-red-600';
        display.textContent = 'Shortage: ₱' + Math.abs(diff).toLocaleString('en-PH', {minimumFractionDigits:2});
    } else if (diff > 0) {
        display.className = 'text-xs mt-1 font-medium text-green-600';
        display.textContent = 'Excess: +₱' + diff.toLocaleString('en-PH', {minimumFractionDigits:2});
    } else {
        display.className = 'text-xs mt-1 font-medium text-green-600';
        display.textContent = 'Exact payment ✓';
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\eurotaxisystem\resources\views/boundaries/index.blade.php ENDPATH**/ ?>