// Real-time Dashboard Updates
class RealTimeDashboard {
    constructor() {
        this.updateInterval = 5000; // Update every 5 seconds (Real-time Feel)
        this.init();
    }

    init() {
        // Start real-time updates
        this.startRealTimeUpdates();
        
        // Setup WebSocket connection if available
        this.setupWebSocket();
    }

    startRealTimeUpdates() {
        // Update dashboard data every 30 seconds
        setInterval(() => {
            this.updateDashboardData();
        }, this.updateInterval);

        // Also update on page visibility change (when user returns to tab)
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                this.updateDashboardData();
            }
        });
    }

    async updateDashboardData() {
        try {
            // Fetch latest dashboard data with cache-buster
            const response = await fetch(`/api/dashboard/realtime?_=${Date.now()}`);
            const data = await response.json();
            
            if (data.success) {
                // Store previous values to prevent unnecessary updates/flashing
                this.updateStats(data.stats);
                this.updateCharts(data.charts);
                
                // Only update alerts if they've changed
                if (data.alerts) {
                    this.updateAlerts(data.alerts);
                }
            }
        } catch (error) {
            console.error('Error updating dashboard:', error);
            // Don't update time if fetch failed
        }
    }

    updateStats(stats) {
        // Update stat cards with animation
        const updates = [
            { selector: '[data-stat="active_units"]', value: stats.active_units },
            { selector: '[data-stat="today_boundary"]', value: this.formatCurrency(stats.today_boundary) },
            { selector: '[data-stat="net_income"]', value: this.formatCurrency(stats.net_income) },
            { selector: '[data-stat="total_expenses_today"]', value: this.formatCurrency(stats.total_expenses_today) },
            { selector: '[data-stat="maintenance_units"]', value: stats.maintenance_units },
            { selector: '[data-stat="active_drivers"]', value: stats.active_drivers },
            { selector: '[data-stat="avg_boundary"]', value: this.formatCurrency(stats.avg_boundary) },
            { selector: '[data-stat="coding_units"]', value: stats.coding_units },
            { selector: '[data-stat="daily_target"]', value: this.formatCurrency(stats.daily_target) }
        ];

        updates.forEach(update => {
            const element = document.querySelector(update.selector);
            if (element) {
                this.animateValue(element, update.value);
            }
        });
    }

    updateCharts(chartData) {
        if (!chartData) return;

        // Helper to check if a global variable is a valid Chart instance
        const isValidChart = (chart) => chart && typeof chart.update === 'function' && chart.data && chart.data.datasets;

        // Helper to check if two arrays are equal
        const arraysEqual = (a, b) => {
            if (a === b) return true;
            if (a == null || b == null) return false;
            if (a.length !== b.length) return false;
            for (let i = 0; i < a.length; ++i) {
                if (a[i] !== b[i]) return false;
            }
            return true;
        };

        // Update weekly financial chart
        if (isValidChart(window.weeklyChart) && chartData.weekly_data) {
            const bData = chartData.weekly_data.map(d => d.boundary);
            const eData = chartData.weekly_data.map(d => d.expenses);
            const nData = chartData.weekly_data.map(d => d.net);
            
            if (!arraysEqual(window.weeklyChart.data.datasets[0].data, bData) ||
                !arraysEqual(window.weeklyChart.data.datasets[1].data, eData) ||
                !arraysEqual(window.weeklyChart.data.datasets[2].data, nData)) {
                
                window.weeklyChart.data.datasets[0].data = bData;
                window.weeklyChart.data.datasets[1].data = eData;
                window.weeklyChart.data.datasets[2].data = nData;
                window.weeklyChart.update('none');
            }
        }

        // Update unit status chart
        if (isValidChart(window.unitStatusChart) && chartData.unit_status_data) {
            const sData = chartData.unit_status_data.map(d => d.count);
            if (!arraysEqual(window.unitStatusChart.data.datasets[0].data, sData)) {
                window.unitStatusChart.data.datasets[0].data = sData;
                window.unitStatusChart.update('none');
            }
        }

        // Update revenue trend chart
        if (isValidChart(window.revenueTrendChart) && chartData.revenue_trend) {
            const tData = chartData.revenue_trend.map(d => d.revenue);
            if (!arraysEqual(window.revenueTrendChart.data.datasets[0].data, tData)) {
                window.revenueTrendChart.data.datasets[0].data = tData;
                window.revenueTrendChart.update('none');
            }
        }

        // Update unit performance chart
        if (isValidChart(window.unitPerformanceChart) && chartData.unit_performance) {
            const pData = chartData.unit_performance.map(d => d.performance);
            const tgData = chartData.unit_performance.map(d => d.target);
            
            if (!arraysEqual(window.unitPerformanceChart.data.datasets[0].data, pData) ||
                !arraysEqual(window.unitPerformanceChart.data.datasets[1].data, tgData)) {
                
                window.unitPerformanceChart.data.datasets[0].data = pData;
                window.unitPerformanceChart.data.datasets[1].data = tgData;
                window.unitPerformanceChart.update('none');
            }
        }

        // Update expense breakdown chart
        if (isValidChart(window.expenseBreakdownChart) && chartData.expense_breakdown) {
            const amData = chartData.expense_breakdown.map(d => d.amount);
            const lbData = chartData.expense_breakdown.map(d => d.category);
            
            if (!arraysEqual(window.expenseBreakdownChart.data.datasets[0].data, amData) ||
                !arraysEqual(window.expenseBreakdownChart.data.labels, lbData)) {
                
                window.expenseBreakdownChart.data.datasets[0].data = amData;
                window.expenseBreakdownChart.data.labels = lbData;
                window.expenseBreakdownChart.update('none');
            }
        }
    }

    updateAlerts(alerts) {
        const alertsContainer = document.querySelector('[data-alerts-container]');
        if (!alertsContainer) return;

        if (!alerts || alerts.length === 0) {
            const emptyHtml = '<p class="text-gray-500 text-center py-4">No active alerts</p>';
            if (alertsContainer.innerHTML !== emptyHtml) {
                alertsContainer.innerHTML = emptyHtml;
            }
            return;
        }

        const alertsHtml = `<div class="space-y-3">${alerts.map(alert => `
            <div class="flex items-start gap-3 p-3 rounded-lg border ${this.getAlertClass(alert.severity)} transition-all duration-300">
                <div class="mt-0.5">
                    ${this.getAlertIcon(alert.severity)}
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-900 font-medium">${alert.message}</p>
                    <span class="text-[10px] text-gray-400 capitalize font-bold uppercase tracking-wider">${alert.alert_type}</span>
                </div>
            </div>
        `).join('')}</div>`;

        // Only update if HTML actually changed to avoid flicker
        if (alertsContainer.innerHTML !== alertsHtml) {
            alertsContainer.innerHTML = alertsHtml;
            
            // Re-initialize lucide icons for new alerts
            if (window.lucide) {
                window.lucide.createIcons();
            }
        }
    }

    getAlertClass(severity) {
        const classes = {
            'high': 'bg-red-50 border-red-200',
            'critical': 'bg-red-50 border-red-200',
            'medium': 'bg-yellow-50 border-yellow-200',
            'low': 'bg-blue-50 border-blue-200'
        };
        return classes[severity] || 'bg-gray-50 border-gray-200';
    }

    getAlertIcon(severity) {
        if (['high', 'critical'].includes(severity)) {
            return '<i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i>';
        } else if (severity === 'medium') {
            return '<i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-600"></i>';
        } else {
            return '<i data-lucide="info" class="w-5 h-5 text-blue-600"></i>';
        }
    }

    animateValue(element, newValue) {
        // Only update if value changed and newValue is valid
        if (newValue === undefined || newValue === null) return;
        
        const currentValue = element.textContent.trim();
        const formattedNewValue = newValue.toString();
        
        // Robust comparison: remove currency symbols and commas for numeric comparison if possible
        const cleanCurrent = currentValue.replace(/[₱,]/g, '').trim();
        const cleanNew = formattedNewValue.replace(/[₱,]/g, '').trim();
        
        // Prevent update if values are identical
        if (cleanCurrent === cleanNew) return;
        
        // Update immediately to prevent lag
        element.textContent = formattedNewValue;
        
        // Add a subtle transition effect instead of a jarring flash
        element.style.transition = 'transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), color 0.3s ease';
        element.style.color = '#10b981'; // Emerald-500
        element.style.transform = 'scale(1.1)';
        
        setTimeout(() => {
            element.style.color = '';
            element.style.transform = '';
        }, 1000);
    }

    formatCurrency(value) {
        if (value === null || value === undefined || value === '') return '₱0.00';
        return '₱' + new Intl.NumberFormat('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(value);
    }

    setupWebSocket() {
        // WebSocket setup for real-time updates (optional)
        if (typeof io !== 'undefined') {
            const socket = io();
            
            socket.on('dashboard:update', (data) => {
                this.updateStats(data.stats);
                this.updateCharts(data.charts);
                this.updateAlerts(data.alerts);
                this.updateLastUpdatedTime();
            });
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.realTimeDashboard = new RealTimeDashboard();
});

// Make charts globally accessible for updates
window.weeklyChart = null;
window.unitStatusChart = null;

// Store chart instances when created
document.addEventListener('DOMContentLoaded', () => {
    // Wait for Chart.js to initialize
    setTimeout(() => {
        if (typeof Chart !== 'undefined') {
            Chart.helpers.each(Chart.instances, (instance) => {
                if (instance.canvas.id === 'weeklyChart') {
                    window.weeklyChart = instance;
                } else if (instance.canvas.id === 'unitStatusChart') {
                    window.unitStatusChart = instance;
                } else if (instance.canvas.id === 'revenueTrendChart') {
                    window.revenueTrendChart = instance;
                } else if (instance.canvas.id === 'unitPerformanceChart') {
                    window.unitPerformanceChart = instance;
                } else if (instance.canvas.id === 'expenseBreakdownChart') {
                    window.expenseBreakdownChart = instance;
                }
            });
        }
    }, 1000);
});
