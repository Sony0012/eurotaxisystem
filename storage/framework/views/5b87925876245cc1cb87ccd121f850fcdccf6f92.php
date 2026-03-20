<?php $__env->startSection('title', 'Analytics - Euro System'); ?>
<?php $__env->startSection('page-heading', 'Descriptive Analytics & Insights'); ?>
<?php $__env->startSection('page-subheading', 'Answer WHY problems: idle units, maintenance costs, driver preferences, and seasonal trends'); ?>

<?php $__env->startSection('content'); ?>
    
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

    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow card-hover">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Revenue (6mo)</p>
                        <p class="text-2xl"><?php echo e(formatCurrency($total_boundary)); ?></p>
                        <div class="flex items-center text-green-600 text-sm mt-1">
                            <i data-lucide="trending-up" class="h-4 w-4 mr-1"></i>
                            +12.5%
                        </div>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <i data-lucide="dollar-sign" class="h-8 w-8 text-green-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow card-hover">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Expenses</p>
                        <p class="text-2xl"><?php echo e(formatCurrency($total_expenses)); ?></p>
                        <div class="flex items-center text-red-600 text-sm mt-1">
                            <i data-lucide="trending-up" class="h-4 w-4 mr-1"></i>
                            +5.8%
                        </div>
                    </div>
                    <div class="p-3 bg-red-100 rounded-full">
                        <i data-lucide="dollar-sign" class="h-8 w-8 text-red-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow card-hover">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Net Income</p>
                        <p class="text-2xl <?php echo e($net_income >= 0 ? 'text-green-600' : 'text-red-600'); ?>"><?php echo e(formatCurrency($net_income)); ?></p>
                        <div class="flex items-center text-green-600 text-sm mt-1">
                            <i data-lucide="trending-up" class="h-4 w-4 mr-1"></i>
                            +18.2%
                        </div>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <i data-lucide="trending-up" class="h-8 w-8 text-green-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow card-hover">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Active Drivers</p>
                        <p class="text-2xl"><?php echo e($active_drivers); ?></p>
                        <p class="text-xs text-gray-500 mt-1">Currently active</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i data-lucide="users" class="h-8 w-8 text-blue-600"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <?php if(isset($unit_idle_analysis) && count($unit_idle_analysis) > 0): ?>
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <i data-lucide="alert-triangle" class="h-5 w-5 text-orange-600"></i>
                WHY are units idle? - Root Cause Analysis
            </h3>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                <?php $__currentLoopData = $unit_idle_analysis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-4 border rounded-lg flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-1">
                                <p class="font-medium"><?php echo e($item['unit']); ?></p>
                                <span class="px-2 py-1 text-xs font-medium rounded-full border border-gray-200"><?php echo e($item['idleDays']); ?> days idle</span>
                            </div>
                            <p class="text-sm text-gray-600">Reason: <?php echo e($item['reason']); ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-red-600 font-medium">₱<?php echo e(number_format($item['impact'])); ?></p>
                            <p class="text-xs text-gray-500">Revenue lost</p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php
                $totalIdleDays = collect($unit_idle_analysis)->sum('idleDays');
                $totalImpact   = collect($unit_idle_analysis)->sum('impact');
            ?>
            <div class="mt-4 p-3 bg-orange-50 rounded-lg">
                <p class="text-sm text-orange-800">
                    <strong>Insight:</strong> <?php echo e($totalIdleDays); ?> days total idle time = ₱<?php echo e(number_format($totalImpact)); ?> potential revenue loss. Main causes: Breakdowns (40%) and driver vacancy (25%).
                </p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Revenue vs Expenses Trend</h3>
            </div>
            <div class="p-6">
                <canvas id="revenueChart" height="250"></canvas>
            </div>
        </div>

        
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Expense Distribution</h3>
            </div>
            <div class="p-6">
                <canvas id="expenseChart" height="250"></canvas>
            </div>
        </div>

        
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Daily Boundary Trend</h3>
            </div>
            <div class="p-6">
                <canvas id="dailyTrendChart" height="250"></canvas>
            </div>
        </div>

        
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">WHY high maintenance cost? - Per Unit Analysis</h3>
            </div>
            <div class="p-6">
                <canvas id="maintenanceChart" height="250"></canvas>
            </div>
        </div>
    </div>

    
    <?php if(isset($maintenance_cost_trend) && count($maintenance_cost_trend) > 0): ?>
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-900">Which units have high maintenance costs?</h3>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-3 px-4 text-sm">Unit</th>
                            <th class="text-left py-3 px-4 text-sm">Total Cost</th>
                            <th class="text-left py-3 px-4 text-sm">Frequency</th>
                            <th class="text-left py-3 px-4 text-sm">Cost per Incident</th>
                            <th class="text-left py-3 px-4 text-sm">Category</th>
                            <th class="text-left py-3 px-4 text-sm">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $maintenance_cost_trend; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4 text-sm font-medium"><?php echo e($unit['unit']); ?></td>
                                <td class="py-3 px-4 text-sm">₱<?php echo e(number_format($unit['cost'])); ?></td>
                                <td class="py-3 px-4 text-sm"><?php echo e($unit['frequency']); ?> times</td>
                                <td class="py-3 px-4 text-sm">₱<?php echo e($unit['frequency'] > 0 ? number_format($unit['cost'] / $unit['frequency']) : 0); ?></td>
                                <td class="py-3 px-4 text-sm">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        <?php if($unit['category'] === 'High Risk'): ?> bg-red-100 text-red-800
                                        <?php elseif($unit['category'] === 'Normal'): ?> bg-yellow-100 text-yellow-800
                                        <?php else: ?> bg-green-100 text-green-800
                                        <?php endif; ?>">
                                        <?php echo e($unit['category']); ?>

                                    </span>
                                </td>
                                <td class="py-3 px-4 text-sm">
                                    <?php if($unit['category'] === 'High Risk'): ?>
                                        <span class="text-red-600">Consider retirement</span>
                                    <?php else: ?>
                                        <span class="text-green-600">Monitor</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
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
                                <span class="w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm
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

    
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-900">Strategic Decision Support</h3>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                <div class="p-4 bg-blue-50 rounded-lg">
                    <p class="font-medium text-blue-900 mb-2">✅ When to buy new units?</p>
                    <p class="text-sm text-blue-800">
                        Current net income: <?php echo e(formatCurrency($net_income)); ?>/month. With units at ROI (eligible for boundary reduction), consider acquiring 1-2 new units in Q3 to capitalize on Q4 peak season.
                    </p>
                </div>
                <div class="p-4 bg-green-50 rounded-lg">
                    <p class="font-medium text-green-900 mb-2">✅ When to lower boundary?</p>
                    <p class="text-sm text-green-800">
                        Units that achieved ROI: Recommend boundary reduction to incentivize drivers and improve retention.
                    </p>
                </div>
                <div class="p-4 bg-red-50 rounded-lg">
                    <p class="font-medium text-red-900 mb-2">❌ Which units to retire?</p>
                    <p class="text-sm text-red-800">
                        High-risk units identified (maintenance cost &gt; ₱15k with 3-4 breakdowns). Review unit profitability for replacement candidates within 3 months.
                    </p>
                </div>
                <div class="p-4 bg-yellow-50 rounded-lg">
                    <p class="font-medium text-yellow-900 mb-2">⚠️ Which drivers to retain/remove?</p>
                    <p class="text-sm text-yellow-800">
                        Top performers identified - offer unit upgrade incentive. At-risk drivers with multiple shortages - issue final warning or consider replacement.
                    </p>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        const dailyData = <?php echo json_encode($daily_trend, 15, 512) ?>;
        const expenseData = <?php echo json_encode($expense_by_category, 15, 512) ?>;
        const maintenanceCostData = <?php echo json_encode($maintenance_cost_trend ?? [], 15, 512) ?>;

        // Revenue vs Expenses Trend Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: dailyData.map(d => d.day),
                datasets: [
                    {
                        label: 'Boundary',
                        data: dailyData.map(d => d.total),
                        borderColor: '#eab308',
                        backgroundColor: 'rgba(234, 179, 8, 0.1)',
                        borderWidth: 2,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: v => '₱' + (v / 1000).toFixed(0) + 'k' }
                    }
                }
            }
        });

        // Expense Distribution Chart
        const expCtx = document.getElementById('expenseChart').getContext('2d');
        new Chart(expCtx, {
            type: 'pie',
            data: {
                labels: expenseData.map(d => d.category),
                datasets: [{
                    data: expenseData.map(d => d.total),
                    backgroundColor: ['#ef4444', '#3b82f6', '#eab308', '#8b5cf6', '#ec4899', '#22c55e', '#f97316', '#06b6d4'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right' },
                    tooltip: {
                        callbacks: {
                            label: ctx => ctx.label + ': ₱' + parseFloat(ctx.raw).toLocaleString()
                        }
                    }
                }
            }
        });

        // Daily Trend Chart (second chart slot)
        const dailyCtx = document.getElementById('dailyTrendChart').getContext('2d');
        new Chart(dailyCtx, {
            type: 'bar',
            data: {
                labels: dailyData.map(d => d.day),
                datasets: [{
                    label: 'Daily Boundary (₱)',
                    data: dailyData.map(d => d.total),
                    backgroundColor: '#eab308',
                    borderColor: '#ca8a04',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: v => '₱' + v.toLocaleString() }
                    }
                }
            }
        });

        // Maintenance Cost Chart
        const maintCtx = document.getElementById('maintenanceChart').getContext('2d');
        new Chart(maintCtx, {
            type: 'bar',
            data: {
                labels: maintenanceCostData.map(d => d.unit),
                datasets: [{
                    label: 'Maintenance Cost (₱)',
                    data: maintenanceCostData.map(d => d.cost),
                    backgroundColor: '#ef4444',
                    borderColor: '#dc2626',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: v => '₱' + v.toLocaleString() }
                    }
                }
            }
        });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\eurotaxisystem\resources\views\analytics\index.blade.php ENDPATH**/ ?>