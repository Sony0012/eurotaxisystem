/**
 * Real-time Tracking Updates
 * Handles live updates for unit status and statistics without page flickering.
 */
class RealTimeTracking {
    constructor() {
        this.updateInterval = 15000; // 15 seconds
        this.init();
    }

    init() {
        console.log('📡 Real-time tracking activated');
        this.startUpdateCycle();
    }

    startUpdateCycle() {
        setInterval(() => {
            this.fetchLatestTracking();
        }, this.updateInterval);
    }

    async fetchLatestTracking() {
        try {
            const response = await fetch('/api/units-live');
            const data = await response.json();

            if (data.success) {
                this.updateUnitList(data.units);
                this.updateStats(data.stats);
            }
        } catch (error) {
            console.error('Error fetching live tracking:', error);
        }
    }

    updateUnitList(units) {
        units.forEach(unit => {
            const el = document.querySelector(`.unit-item[data-unit-id="${unit.unit_id}"]`);
            if (!el) return;

            // Update GPS status indicator
            const statusBadge = el.querySelector('.status-badge');
            if (statusBadge) {
                const currentStatus = el.dataset.status;
                if (currentStatus !== unit.gps_status) {
                    this.updateStatusBadge(statusBadge, unit.gps_status);
                    el.dataset.status = unit.gps_status;
                    
                    // Update opacity based on offline status
                    if (unit.gps_status === 'offline') {
                        el.classList.add('opacity-70');
                    } else {
                        el.classList.remove('opacity-70');
                    }
                }
            }

            // Update speed if available (adding a speed indicator if we want)
            // For now, let's just handle the status transitions smoothly
        });
    }

    updateStatusBadge(badge, status) {
        // Subtle fade out/in
        badge.style.transition = 'all 0.3s ease';
        badge.style.opacity = '0';
        
        setTimeout(() => {
            let html = '';
            if (status === 'active') {
                badge.className = 'status-badge px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 flex items-center gap-1';
                html = '<i data-lucide="wifi" class="w-3 h-3"></i> Linked';
            } else if (status === 'idle') {
                badge.className = 'status-badge px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 flex items-center gap-1';
                html = '<i data-lucide="clock" class="w-3 h-3"></i> Idle';
            } else {
                badge.className = 'status-badge px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600';
                html = 'Offline';
            }
            
            badge.innerHTML = html;
            badge.style.opacity = '1';
            
            if (window.lucide) {
                window.lucide.createIcons();
            }
        }, 300);
    }

    updateStats(stats) {
        const mappings = [
            { id: 'stat-total', value: stats.total },
            { id: 'stat-active', value: stats.active + stats.idle }, // Show combined "online"
            { id: 'stat-offline', value: stats.offline }
        ];

        mappings.forEach(map => {
            const el = document.getElementById(map.id);
            if (el && el.textContent != map.value) {
                el.style.transition = 'color 0.3s';
                el.style.color = '#22c55e';
                el.textContent = map.value;
                setTimeout(() => el.style.color = '', 1000);
            }
        });
    }
}

// Initialize when library is ready
document.addEventListener('DOMContentLoaded', () => {
    window.realTimeTracking = new RealTimeTracking();
});
