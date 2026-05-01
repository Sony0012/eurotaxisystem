class RealTimeDashboard {
    constructor() {
        this.updateInterval = 5000; // Update every 5 seconds
        this.isUpdating = false;
        this.previousStats = {};
        this.init();
    }

    init() {
        this.startRealTimeUpdates();
        
        // Listen for visibility change to pause/resume updates
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.stopRealTimeUpdates();
            } else {
                this.startRealTimeUpdates();
                this.updateDashboardData(); // Update immediately on return
            }
        });
    }

    startRealTimeUpdates() {
        if (!this.pollInterval) {
            this.pollInterval = setInterval(() => {
                this.updateDashboardData();
            }, this.updateInterval);
        }
    }

    stopRealTimeUpdates() {
        if (this.pollInterval) {
            clearInterval(this.pollInterval);
            this.pollInterval = null;
        }
    }

    async updateDashboardData() {
        if (this.isUpdating) return;
        this.isUpdating = true;

        try {
            // Fetch latest dashboard data with cache-buster
            const response = await fetch(`/api/dashboard/realtime?_=${Date.now()}`);
            if (!response.ok) throw new Error('Network response was not ok');
            
            const data = await response.json();
            
            if (data.success && data.stats) {
                this.updateStats(data.stats);
                this.updateCharts(data.charts);
                
                if (data.alerts) {
                    this.updateAlerts(data.alerts);
                }
            }
        } catch (error) {
            console.error('Error updating dashboard:', error);
        } finally {
            this.isUpdating = false;
        }
    }

    updateStats(stats) {
        const statConfig = [
            { key: 'active_units', selector: '[data-stat="active_units"]', format: 'number' },
            { key: 'roi_achieved', selector: '[data-stat="roi_achieved"]', format: 'number' },
            { key: 'coding_units', selector: '[data-stat="coding_units"]', format: 'number' },
            { key: 'maintenance_units', selector: '[data-stat="maintenance_units"]', format: 'number' },
            { key: 'active_drivers', selector: '[data-stat="active_drivers"]', format: 'number' },
            { key: 'today_boundary', selector: '[data-stat="today_boundary"]', format: 'currency' },
            { key: 'today_expenses', selector: '[data-stat="today_expenses"]', format: 'currency' },
            { key: 'net_income', selector: '[data-stat="net_income"]', format: 'currency' },
            { key: 'daily_target', selector: '[data-stat="daily_target"]', format: 'currency' }
        ];

        statConfig.forEach(config => {
            const newValue = stats[config.key];
            const prevValue = this.previousStats[config.key];

            // Only update if value changed and is valid
            if (newValue !== undefined && newValue !== null && newValue !== prevValue) {
                const element = document.querySelector(config.selector);
                if (element) {
                    this.animateValue(element, newValue, config.format);
                    this.previousStats[config.key] = newValue;
                }
            }
        });
    }

    animateValue(element, newValue, format) {
        // Strip non-numeric chars from current text to get start value
        const currentText = element.textContent || '0';
        const startValue = parseFloat(currentText.replace(/[^\d.-]/g, '')) || 0;
        const endValue = parseFloat(newValue);
        
        if (isNaN(endValue)) return;
        if (startValue === endValue) return;

        const duration = 1000;
        const startTime = performance.now();

        const formatFn = (val) => {
            if (format === 'currency') {
                return '₱' + Math.floor(val).toLocaleString();
            }
            return Math.floor(val).toLocaleString();
        };

        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Easing function: easeOutExpo
            const easeProgress = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
            
            const currentVal = startValue + (endValue - startValue) * easeProgress;
            element.textContent = formatFn(currentVal);

            if (progress < 1) {
                requestAnimationFrame(animate);
            } else {
                element.textContent = formatFn(endValue); // Ensure final value is exact
            }
        };

        requestAnimationFrame(animate);
    }

    updateCharts(charts) {
        if (!charts) return;

        // Weekly Chart
        if (window.weeklyChart && charts.weekly_data) {
            window.weeklyChart.data.labels = charts.weekly_data.map(d => d.day);
            window.weeklyChart.data.datasets[0].data = charts.weekly_data.map(d => d.boundary);
            window.weeklyChart.data.datasets[1].data = charts.weekly_data.map(d => d.expenses);
            window.weeklyChart.data.datasets[2].data = charts.weekly_data.map(d => d.net);
            window.weeklyChart.update('none'); // Update without full animation for smoothness
        }

        // Unit Status Chart
        if (window.unitStatusChart && charts.unit_status_data) {
            window.unitStatusChart.data.labels = charts.unit_status_data.map(d => d.status);
            window.unitStatusChart.data.datasets[0].data = charts.unit_status_data.map(d => d.count);
            window.unitStatusChart.update('none');
        }

        // Revenue Trend Chart
        if (window.revenueTrendChart && charts.revenue_trend) {
            window.revenueTrendChart.data.labels = charts.revenue_trend.map(d => d.date);
            window.revenueTrendChart.data.datasets[0].data = charts.revenue_trend.map(d => d.revenue);
            window.revenueTrendChart.update('none');
        }

        // Unit Performance Chart
        if (window.unitPerformanceChart && charts.unit_performance) {
            window.unitPerformanceChart.data.labels = charts.unit_performance.map(d => d.unit);
            window.unitPerformanceChart.data.datasets[0].data = charts.unit_performance.map(d => d.performance);
            window.unitPerformanceChart.data.datasets[1].data = charts.unit_performance.map(d => d.target);
            window.unitPerformanceChart.update('none');
            
            // Update Top Performer insight
            if (charts.unit_performance.length > 0) {
                const insightPlate = document.getElementById('insightTopPlate');
                if (insightPlate) insightPlate.textContent = charts.unit_performance[0].unit;
            }
        }
    }

    updateAlerts(alerts) {
        const container = document.getElementById('alerts-container');
        if (!container) return;

        // Simple check to see if alerts count or content changed
        const currentAlertsHash = JSON.stringify(alerts);
        if (this.lastAlertsHash === currentAlertsHash) return;
        this.lastAlertsHash = currentAlertsHash;

        if (alerts.length === 0) {
            container.innerHTML = `
                <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                    <div class="p-4 bg-gray-50 rounded-full mb-4">
                        <i data-lucide="check-circle-2" class="w-12 h-12 text-gray-200"></i>
                    </div>
                    <p class="font-black uppercase tracking-widest text-xs">All Clear</p>
                    <p class="text-[10px] mt-1">No pending system alerts detected</p>
                </div>
            `;
        } else {
            container.innerHTML = alerts.map(alert => `
                <div class="group relative bg-white border border-gray-100 rounded-2xl p-4 transition-all duration-300 hover:shadow-lg hover:border-orange-100 mb-3 overflow-hidden">
                    <div class="absolute top-0 left-0 w-1 h-full bg-orange-500"></div>
                    <div class="flex items-start gap-4">
                        <div class="p-2.5 bg-orange-50 rounded-xl border border-orange-100">
                            <i data-lucide="alert-circle" class="w-5 h-5 text-orange-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h5 class="text-sm font-black text-gray-900 mb-1 truncate">${alert.message}</h5>
                            <div class="flex items-center gap-3">
                                <span class="px-2 py-0.5 bg-orange-100 text-orange-600 text-[9px] font-black uppercase tracking-widest rounded-full border border-orange-200">${alert.severity}</span>
                                <span class="text-[10px] text-gray-400 font-bold">${alert.alert_type}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }
        
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }
}

// Initialize on load
document.addEventListener('DOMContentLoaded', () => {
    window.dashboardManager = new RealTimeDashboard();
});