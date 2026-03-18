<?php
/** @var float $total_boundary */
/** @var float $total_expenses */
/** @var float $net_income */
/** @var int $active_drivers */
/** @var \Illuminate\Support\Collection $daily_trend */
/** @var \Illuminate\Support\Collection $top_units */
/** @var \Illuminate\Support\Collection $expense_by_category */
/** @var string $date_from */
/** @var string $date_to */
?>


<?php $__env->startSection('title', 'Analytics - Euro System'); ?>
<?php $__env->startSection('page-heading', 'Analytics & Reports'); ?>
<?php $__env->startSection('page-subheading', 'Financial performance and operational insights'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Date Range Filter -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="<?php echo e(route('analytics.index')); ?>" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-700 block mb-1">From</label>
                <input type="date" name="date_from" value="<?php echo e($date_from); ?>"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
            </div>
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-700 block mb-1">To</label>
                <input type="date" name="date_to" value="<?php echo e($date_to); ?>"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
            </div>
            <div class="flex items-end">
                <button type="submit"
                    class="px-6 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 flex items-center gap-2">
                    <i data-lucide="bar-chart" class="w-4 h-4"></i> Generate Report
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow card-hover p-5">
            <p class="text-sm text-gray-500">Total Boundary</p>
            <p class="text-2xl font-bold text-green-600"><?php echo e(formatCurrency($total_boundary)); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow card-hover p-5">
            <p class="text-sm text-gray-500">Total Expenses</p>
            <p class="text-2xl font-bold text-red-600"><?php echo e(formatCurrency($total_expenses)); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow card-hover p-5">
            <p class="text-sm text-gray-500">Net Income</p>
            <p class="text-2xl font-bold <?php echo e($net_income >= 0 ? 'text-green-600' : 'text-red-600'); ?>">
                <?php echo e(formatCurrency($net_income)); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow card-hover p-5">
            <p class="text-sm text-gray-500">Active Drivers</p>
            <p class="text-2xl font-bold text-blue-600"><?php echo e($active_drivers); ?></p>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Daily Trend -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Daily Boundary Trend</h3>
            </div>
            <div class="p-6">
                <canvas id="dailyTrendChart" height="250"></canvas>
            </div>
        </div>

        <!-- Expense by Category -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Expenses by Category</h3>
            </div>
            <div class="p-6">
                <canvas id="expenseCategoryChart" height="250"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Performing Units -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-900">Top Performing Units</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Collected</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Days Operated</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $top_units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm
                                        <?php echo e($i === 0 ? 'bg-yellow-100 text-yellow-800' : ($i === 1 ? 'bg-gray-100 text-gray-700' : 'bg-orange-50 text-orange-700')); ?>">
                                    <?php echo e($i + 1); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo e($u->unit_number); ?></div>
                                <div class="text-xs text-gray-500"><?php echo e($u->plate_number); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                                <?php echo e(formatCurrency($u->total_collected)); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo e($u->days_operated); ?> days</td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500 text-sm">No data for this period</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        const dailyData = <?php echo json_encode($daily_trend, 15, 512) ?>;
        const expenseData = <?php echo json_encode($expense_by_category, 15, 512) ?>;

        const dailyCtx = document.getElementById('dailyTrendChart').getContext('2d');
        new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: dailyData.map(d => d.day),
                datasets: [{
                    label: 'Daily Boundary',
                    data: dailyData.map(d => d.total),
                    borderColor: '#eab308',
                    backgroundColor: 'rgba(234,179,8,0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'top' } },
                scales: {
                    y: { beginAtZero: true, ticks: { callback: v => '₱' + v.toLocaleString() } }
                }
            }
        });

        const expCtx = document.getElementById('expenseCategoryChart').getContext('2d');
        new Chart(expCtx, {
            type: 'doughnut',
            data: {
                labels: expenseData.map(d => d.category),
                datasets: [{
                    data: expenseData.map(d => d.total),
                    backgroundColor: ['#eab308', '#3b82f6', '#ef4444', '#22c55e', '#8b5cf6', '#f97316', '#06b6d4', '#84cc16']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'right' },
                    tooltip: {
                        callbacks: {
                            label: ctx => '₱' + parseFloat(ctx.raw).toLocaleString()
                        }
                    }
                }
            }
        });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\eurotaxisystem\resources\views/analytics/index.blade.php ENDPATH**/ ?>