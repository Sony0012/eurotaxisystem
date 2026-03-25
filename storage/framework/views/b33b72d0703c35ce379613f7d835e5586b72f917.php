<?php $__env->startSection('page-heading', 'Staff Records'); ?>
<?php $__env->startSection('page-subheading', 'Manage non-account staff like mechanics and guards'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Search Bar -->
    <div class="bg-white p-4 rounded-xl shadow-sm border">
        <form action="<?php echo e(route('staff.index')); ?>" method="GET" class="relative max-w-md">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                placeholder="Search staff by name or role..." 
                class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none text-sm">
        </form>
    </div>

    <!-- Admin Staff Section -->
    <div class="space-y-4">
        <div class="flex items-center gap-2">
            <div class="p-2 bg-blue-100 rounded-lg">
                <i data-lucide="shield-check" class="w-5 h-5 text-blue-600"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Admin Staff</h2>
                <p class="text-sm text-gray-500">Personnel with web system accounts</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b">
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600">Name</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600">Role</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <?php $__empty_1 = true; $__currentLoopData = $adminStaff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $admin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-xs uppercase">
                                        <?php echo e(substr($admin->full_name, 0, 1)); ?>

                                    </div>
                                    <span class="font-medium text-gray-900"><?php echo e($admin->full_name); ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 capitalize"><?php echo e($admin->role); ?></td>
                            <td class="px-6 py-4 text-right">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium <?php echo e($admin->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'); ?>">
                                    <?php echo e($admin->is_active ? 'Active' : 'Inactive'); ?>

                                </span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500 text-sm italic">No admin staff found.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- General Staff Section -->
    <div class="space-y-4 pt-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i data-lucide="users" class="w-5 h-5 text-yellow-600"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">General Staff</h2>
                    <p class="text-sm text-gray-500">Personnel records without system accounts (Mechanics, Guards, etc.)</p>
                </div>
            </div>
            <button onclick="openModal('addStaffModal')" class="flex items-center gap-2 bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors shadow-sm text-sm font-medium">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Add Record</span>
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b">
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600">Name</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600">Role</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600">Phone</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600">Status</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <?php $__empty_1 = true; $__currentLoopData = $generalStaff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-700 font-bold text-xs uppercase">
                                        <?php echo e(substr($member->name, 0, 1)); ?>

                                    </div>
                                    <span class="font-medium text-gray-900"><?php echo e($member->name); ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 capitalize"><?php echo e($member->role); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?php echo e($member->phone ?? '---'); ?></td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium <?php echo e($member->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'); ?>">
                                    <?php echo e(ucfirst($member->status)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button onclick="editStaff(<?php echo e(json_encode($member)); ?>)" class="p-1 hover:bg-yellow-100 rounded text-yellow-600 transition-colors" title="Edit">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </button>
                                <form action="<?php echo e(route('staff.destroy', $member->id)); ?>" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this staff record?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="p-1 hover:bg-red-100 rounded text-red-600 transition-colors" title="Delete">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i data-lucide="users-2" class="w-12 h-12 mx-auto mb-4 text-gray-300"></i>
                                <p>No general staff records found.</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="addStaffModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all">
        <div class="p-6 border-b flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-900">Add Staff Record</h3>
            <button onclick="closeModal('addStaffModal')" class="p-2 hover:bg-gray-100 rounded-lg text-gray-400">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="<?php echo e(route('staff.store')); ?>" method="POST" class="p-6 space-y-4">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Role</label>
                <select name="role" id="add_role_select" onchange="toggleCustomRole('addStaffModal', this.value)" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500 outline-none">
                    <option value="">Select Role</option>
                    <option value="Mechanic">Mechanic</option>
                    <option value="Guard">Guard</option>
                    <option value="Others">Others</option>
                </select>
                <div id="add_custom_role_container" class="hidden mt-2">
                    <input type="text" id="add_custom_role" placeholder="Enter role (letters only)" oninput="validateTextOnly(this)" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500 outline-none bg-yellow-50">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Phone Number</label>
                <input type="text" name="phone" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2">
                        <input type="radio" name="status" value="active" checked class="text-yellow-600 focus:ring-yellow-500">
                        <span class="text-sm">Active</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="status" value="inactive" class="text-yellow-600 focus:ring-yellow-500">
                        <span class="text-sm">Inactive</span>
                    </label>
                </div>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeModal('addStaffModal')" class="flex-1 px-4 py-2 border rounded-lg hover:bg-gray-50 font-medium text-gray-700">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg font-medium shadow-sm transition-colors">Save Record</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editStaffModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all">
        <div class="p-6 border-b flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-900">Edit Staff Record</h3>
            <button onclick="closeModal('editStaffModal')" class="p-2 hover:bg-gray-100 rounded-lg text-gray-400">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form id="editStaffForm" method="POST" class="p-6 space-y-4">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" id="edit_name" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Role</label>
                <select name="role" id="edit_role_select" onchange="toggleCustomRole('editStaffModal', this.value)" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500 outline-none">
                    <option value="Mechanic">Mechanic</option>
                    <option value="Guard">Guard</option>
                    <option value="Others">Others</option>
                </select>
                <div id="edit_custom_role_container" class="hidden mt-2">
                    <input type="text" id="edit_custom_role" placeholder="Enter role (letters only)" oninput="validateTextOnly(this)" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500 outline-none bg-yellow-50">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Phone Number</label>
                <input type="text" name="phone" id="edit_phone" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2">
                        <input type="radio" name="status" id="edit_status_active" value="active" class="text-yellow-600 focus:ring-yellow-500">
                        <span class="text-sm">Active</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="status" id="edit_status_inactive" value="inactive" class="text-yellow-600 focus:ring-yellow-500">
                        <span class="text-sm">Inactive</span>
                    </label>
                </div>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeModal('editStaffModal')" class="flex-1 px-4 py-2 border rounded-lg hover:bg-gray-50 font-medium text-gray-700">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg font-medium shadow-sm transition-colors">Update Record</button>
            </div>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function toggleCustomRole(modalId, value) {
        const prefix = modalId === 'addStaffModal' ? 'add' : 'edit';
        const container = document.getElementById(`${prefix}_custom_role_container`);
        const input = document.getElementById(`${prefix}_custom_role`);
        const select = document.getElementById(`${prefix}_role_select`);

        if (value === 'Others') {
            container.classList.remove('hidden');
            input.required = true;
            // Transfer name attribute to input on submit logic below
        } else {
            container.classList.add('hidden');
            input.required = false;
            input.value = '';
        }
    }

    function validateTextOnly(input) {
        // Remove everything except letters and spaces
        input.value = input.value.replace(/[^A-Za-z\s]/g, '');
    }

    // Intercept form submissions to handle custom role
    document.querySelectorAll('form').forEach(form => {
        if (form.action.includes('/staff')) {
            form.addEventListener('submit', (e) => {
                const prefix = form.id === 'editStaffForm' ? 'edit' : 'add';
                const select = document.getElementById(`${prefix}_role_select`);
                const customInput = document.getElementById(`${prefix}_custom_role`);

                if (select && select.value === 'Others') {
                    if (!customInput.value.trim()) {
                        alert('Please enter a role name.');
                        e.preventDefault();
                        return;
                    }
                    // Temporarily set the value of the select to the custom input value
                    // This is a quick way since the select has name="role"
                    const tempOption = document.createElement('option');
                    tempOption.value = customInput.value;
                    tempOption.text = customInput.value;
                    tempOption.selected = true;
                    select.add(tempOption);
                }
            });
        }
    });

    function editStaff(member) {
        document.getElementById('edit_name').value = member.name;
        document.getElementById('edit_phone').value = member.phone || '';
        
        const roleSelect = document.getElementById('edit_role_select');
        const customContainer = document.getElementById('edit_custom_role_container');
        const customInput = document.getElementById('edit_custom_role');

        const standardRoles = ['Mechanic', 'Guard'];
        if (standardRoles.includes(member.role)) {
            roleSelect.value = member.role;
            customContainer.classList.add('hidden');
            customInput.value = '';
        } else {
            roleSelect.value = 'Others';
            customContainer.classList.remove('hidden');
            customInput.value = member.role;
        }

        if (member.status === 'active') {
            document.getElementById('edit_status_active').checked = true;
        } else {
            document.getElementById('edit_status_inactive').checked = true;
        }

        document.getElementById('editStaffForm').action = `/staff/${member.id}`;
        openModal('editStaffModal');
    }

    // Close modals on escape key
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal('addStaffModal');
            closeModal('editStaffModal');
        }
    });

    // Close modals on click outside
    window.addEventListener('click', (e) => {
        if (e.target.id === 'addStaffModal') closeModal('addStaffModal');
        if (e.target.id === 'editStaffModal') closeModal('editStaffModal');
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\eurotaxisystem\resources\views/staff/index.blade.php ENDPATH**/ ?>