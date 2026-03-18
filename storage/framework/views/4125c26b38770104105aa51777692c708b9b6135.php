<?php $__env->startSection('title', 'Decision Management - Euro System'); ?>
<?php $__env->startSection('page-heading', 'Decision Management'); ?>
<?php $__env->startSection('page-subheading', 'Franchise Cases & Applications'); ?>

<?php $__env->startSection('content'); ?>

<?php if($stats['expiring_soon'] > 0): ?>
<div class="mb-4 p-4 bg-yellow-50 border border-yellow-300 rounded-lg flex items-center gap-3">
    <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-600"></i>
    <span class="text-sm text-yellow-800 font-medium"><?php echo e($stats['expiring_soon']); ?> case(s) expiring within 30 days.</span>
</div>
<?php endif; ?>
<?php if($stats['expired'] > 0): ?>
<div class="mb-4 p-4 bg-red-50 border border-red-300 rounded-lg flex items-center gap-3">
    <i data-lucide="x-circle" class="w-5 h-5 text-red-600"></i>
    <span class="text-sm text-red-800 font-medium"><?php echo e($stats['expired']); ?> case(s) have already expired.</span>
</div>
<?php endif; ?>


<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="<?php echo e(route('decision-management.index')); ?>" class="flex flex-col md:flex-row gap-3">
        <input type="text" name="search" value="<?php echo e($search); ?>" placeholder="Search applicant, case no, type..."
            class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none text-sm">
        <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 text-sm flex items-center gap-2">
            <i data-lucide="search" class="w-4 h-4"></i> Search
        </button>
        <button type="button" onclick="document.getElementById('addCaseModal').classList.remove('hidden')"
            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> Add Case
        </button>
    </form>
</div>


<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Case No.</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Applicant</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type of Application</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Denomination</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date Filed</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expiry Date</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $cases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $case): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $isExpired  = $case->expiry_date && $case->expiry_date < date('Y-m-d');
                    $isExpiring = !$isExpired && $case->expiry_date && $case->expiry_date <= date('Y-m-d', strtotime('+30 days'));
                    $badgeClass = $isExpired ? 'bg-red-100 text-red-800' : ($isExpiring ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800');
                    $badgeText  = $isExpired ? 'Expired' : ($isExpiring ? 'Expiring Soon' : 'Active');
                ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-4 font-semibold text-gray-900"><?php echo e($case->case_no); ?></td>
                    <td class="px-5 py-4 text-gray-700"><?php echo e($case->applicant_name); ?></td>
                    <td class="px-5 py-4 text-gray-600"><?php echo e($case->type_of_application); ?></td>
                    <td class="px-5 py-4 text-gray-600"><?php echo e($case->denomination); ?></td>
                    <td class="px-5 py-4 text-gray-600"><?php echo e(formatDate($case->date_filed)); ?></td>
                    <td class="px-5 py-4 text-gray-600"><?php echo e($case->expiry_date ? formatDate($case->expiry_date) : '—'); ?></td>
                    <td class="px-5 py-4">
                        <span class="px-2 py-1 text-xs rounded-full <?php echo e($badgeClass); ?>"><?php echo e($badgeText); ?></span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <button onclick='openEditModal(<?php echo json_encode($case, 15, 512) ?>)' class="text-blue-600 hover:text-blue-900">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </button>
                            <form method="POST" action="<?php echo e(route('decision-management.destroy', $case->id)); ?>" onsubmit="return confirm('Delete this case?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="px-5 py-12 text-center text-gray-400">
                        <i data-lucide="file-text" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                        <p>No franchise cases found.</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if($pagination['total_pages'] > 1): ?>
    <div class="px-5 py-4 border-t flex items-center justify-between text-sm text-gray-600">
        <span>Page <?php echo e($pagination['page']); ?> of <?php echo e($pagination['total_pages']); ?> (<?php echo e($pagination['total_items']); ?> total)</span>
        <div class="flex gap-2">
            <?php if($pagination['has_prev']): ?>
                <a href="?page=<?php echo e($pagination['prev_page']); ?>&search=<?php echo e($search); ?>" class="px-3 py-1 bg-gray-100 rounded hover:bg-gray-200">← Prev</a>
            <?php endif; ?>
            <?php if($pagination['has_next']): ?>
                <a href="?page=<?php echo e($pagination['next_page']); ?>&search=<?php echo e($search); ?>" class="px-3 py-1 bg-gray-100 rounded hover:bg-gray-200">Next →</a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>


<div id="addCaseModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Add Franchise Case</h3>
            <button onclick="document.getElementById('addCaseModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form method="POST" action="<?php echo e(route('decision-management.store')); ?>">
            <?php echo csrf_field(); ?>
            <div class="space-y-3">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Case No. *</label>
                        <input type="text" name="case_no" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Date Filed *</label>
                        <input type="date" name="date_filed" value="<?php echo e(date('Y-m-d')); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none" required>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Applicant Name *</label>
                    <input type="text" name="applicant_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Type of Application *</label>
                    <input type="text" name="type_of_application" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Denomination *</label>
                    <input type="text" name="denomination" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Expiry Date</label>
                    <input type="date" name="expiry_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                </div>
            </div>
            <div class="flex gap-3 mt-5">
                <button type="submit" class="flex-1 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">Save</button>
                <button type="button" onclick="document.getElementById('addCaseModal').classList.add('hidden')" class="flex-1 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm">Cancel</button>
            </div>
        </form>
    </div>
</div>


<div id="editCaseModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Edit Franchise Case</h3>
            <button onclick="document.getElementById('editCaseModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form id="editCaseForm" method="POST">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="space-y-3">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Case No. *</label>
                        <input type="text" name="case_no" id="edit_case_no" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Date Filed *</label>
                        <input type="date" name="date_filed" id="edit_date_filed" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none" required>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Applicant Name *</label>
                    <input type="text" name="applicant_name" id="edit_applicant_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Type of Application *</label>
                    <input type="text" name="type_of_application" id="edit_type_of_application" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Denomination *</label>
                    <input type="text" name="denomination" id="edit_denomination" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Expiry Date</label>
                    <input type="date" name="expiry_date" id="edit_expiry_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                </div>
            </div>
            <div class="flex gap-3 mt-5">
                <button type="submit" class="flex-1 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">Update</button>
                <button type="button" onclick="document.getElementById('editCaseModal').classList.add('hidden')" class="flex-1 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm">Cancel</button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function openEditModal(c) {
    const base = "<?php echo e(url('decision-management')); ?>";
    document.getElementById('editCaseForm').action = base + '/' + c.id;
    document.getElementById('edit_case_no').value            = c.case_no;
    document.getElementById('edit_applicant_name').value     = c.applicant_name;
    document.getElementById('edit_type_of_application').value= c.type_of_application;
    document.getElementById('edit_denomination').value       = c.denomination;
    document.getElementById('edit_date_filed').value         = c.date_filed;
    document.getElementById('edit_expiry_date').value        = c.expiry_date || '';
    document.getElementById('editCaseModal').classList.remove('hidden');
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\eurotaxisystem\resources\views/decision-management/index.blade.php ENDPATH**/ ?>