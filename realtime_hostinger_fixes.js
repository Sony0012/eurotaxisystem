/**
 * Real-Time Features Optimizations for Hostinger
 * Add this to your JavaScript files to improve performance on shared hosting
 */

// Optimization 1: Increase polling intervals for shared hosting
const HOSTINGER_CONFIG = {
    // Slower polling for shared hosting (was 5000ms)
    DASHBOARD_UPDATE_INTERVAL: 10000,  // 10 seconds
    GPS_UPDATE_INTERVAL: 15000,        // 15 seconds  
    MAP_UPDATE_INTERVAL: 20000,        // 20 seconds
    
    // Retry configuration
    MAX_RETRIES: 3,
    RETRY_DELAY: 5000,
    
    // Timeout configuration
    API_TIMEOUT: 30000,                // 30 seconds
    MAP_LOAD_TIMEOUT: 15000            // 15 seconds
};

// Optimization 2: Better error handling for slow connections
class HostingerRealTimeManager {
    constructor() {
        this.isOnline = navigator.onLine;
        this.retryCount = 0;
        this.lastSuccessfulUpdate = null;
        this.updateInterval = null;
        
        // Monitor connection status
        window.addEventListener('online', () => this.handleConnectionChange(true));
        window.addEventListener('offline', () => this.handleConnectionChange(false));
    }
    
    handleConnectionChange(isOnline) {
        this.isOnline = isOnline;
        if (isOnline) {
            console.log('🌐 Connection restored - restarting real-time updates');
            this.startRealTimeUpdates();
        } else {
            console.log('📵 Connection lost - pausing real-time updates');
            this.stopRealTimeUpdates();
        }
    }
    
    async makeRequest(url, options = {}) {
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), HOSTINGER_CONFIG.API_TIMEOUT);
        
        try {
            const response = await fetch(url, {
                ...options,
                signal: controller.signal,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Cache-Control': 'no-cache',
                    ...options.headers
                }
            });
            
            clearTimeout(timeoutId);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            this.retryCount = 0;
            this.lastSuccessfulUpdate = new Date();
            return await response.json();
            
        } catch (error) {
            clearTimeout(timeoutId);
            
            if (error.name === 'AbortError') {
                console.warn('⏰ Request timeout:', url);
            } else {
                console.error('❌ Request failed:', error.message);
            }
            
            // Retry logic
            if (this.retryCount < HOSTINGER_CONFIG.MAX_RETRIES) {
                this.retryCount++;
                console.log(`🔄 Retrying (${this.retryCount}/${HOSTINGER_CONFIG.MAX_RETRIES})...`);
                
                await new Promise(resolve => setTimeout(resolve, HOSTINGER_CONFIG.RETRY_DELAY));
                return this.makeRequest(url, options);
            } else {
                console.error('❌ Max retries reached - giving up');
                throw error;
            }
        }
    }
    
    startRealTimeUpdates() {
        if (this.updateInterval) {
            clearInterval(this.updateInterval);
        }
        
        this.updateInterval = setInterval(() => {
            if (this.isOnline) {
                this.updateDashboardData();
            }
        }, HOSTINGER_CONFIG.DASHBOARD_UPDATE_INTERVAL);
    }
    
    stopRealTimeUpdates() {
        if (this.updateInterval) {
            clearInterval(this.updateInterval);
            this.updateInterval = null;
        }
    }
    
    async updateDashboardData() {
        try {
            const data = await this.makeRequest('/api/dashboard/realtime');
            this.updateDashboardUI(data);
        } catch (error) {
            console.error('Dashboard update failed:', error);
            // Show offline indicator in UI
            this.showOfflineIndicator();
        }
    }
    
    updateDashboardUI(data) {
        // Update your dashboard elements here
        // This is where you'd update the actual UI elements
        console.log('📊 Dashboard updated:', data);
        
        // Hide offline indicator if it was showing
        this.hideOfflineIndicator();
    }
    
    showOfflineIndicator() {
        const indicator = document.getElementById('connection-status');
        if (indicator) {
            indicator.innerHTML = `
                <span style="color: #dc3545;">
                    <i class="fas fa-exclamation-triangle"></i> 
                    Connection issues - updates paused
                </span>
            `;
        }
    }
    
    hideOfflineIndicator() {
        const indicator = document.getElementById('connection-status');
        if (indicator) {
            indicator.innerHTML = `
                <span style="color: #28a745;">
                    <i class="fas fa-check-circle"></i> 
                    Live updates active
                </span>
            `;
        }
    }
}

// Optimization 3: Efficient GPS tracking
class HostingerGPSTracker {
    constructor() {
        this.trackingInterval = null;
        this.units = new Map();
        this.lastUpdate = null;
    }
    
    startTracking() {
        if (this.trackingInterval) {
            clearInterval(this.trackingInterval);
        }
        
        this.trackingInterval = setInterval(() => {
            this.updateGPSData();
        }, HOSTINGER_CONFIG.GPS_UPDATE_INTERVAL);
    }
    
    async updateGPSData() {
        try {
            const data = await window.realtimeManager.makeRequest('/live-tracking/units-live');
            this.updateMapMarkers(data);
        } catch (error) {
            console.error('GPS update failed:', error);
        }
    }
    
    updateMapMarkers(data) {
        // Update map markers efficiently
        // Only update markers that actually changed
        data.units?.forEach(unit => {
            const existing = this.units.get(unit.id);
            if (!existing || this.hasPositionChanged(existing, unit)) {
                this.updateMarker(unit);
                this.units.set(unit.id, unit);
            }
        });
    }
    
    hasPositionChanged(oldUnit, newUnit) {
        return oldUnit.lat !== newUnit.lat || oldUnit.lng !== newUnit.lng;
    }
    
    updateMarker(unit) {
        // Update individual marker on map
        console.log('📍 Updating marker for unit:', unit.plate_number);
    }
}

// Optimization 4: Performance monitoring
class PerformanceMonitor {
    constructor() {
        this.metrics = {
            apiCalls: 0,
            errors: 0,
            avgResponseTime: 0,
            lastUpdate: null
        };
    }
    
    recordApiCall(startTime, success) {
        const endTime = performance.now();
        const responseTime = endTime - startTime;
        
        this.metrics.apiCalls++;
        this.metrics.lastUpdate = new Date();
        
        if (!success) {
            this.metrics.errors++;
        }
        
        // Update average response time
        this.metrics.avgResponseTime = 
            (this.metrics.avgResponseTime * (this.metrics.apiCalls - 1) + responseTime) / 
            this.metrics.apiCalls;
        
        // Log performance issues
        if (responseTime > 5000) {
            console.warn('🐌 Slow API response:', responseTime.toFixed(2), 'ms');
        }
        
        if (this.metrics.errors > this.metrics.apiCalls * 0.1) {
            console.error('📈 High error rate:', 
                ((this.metrics.errors / this.metrics.apiCalls) * 100).toFixed(1), '%');
        }
    }
    
    getMetrics() {
        return {
            ...this.metrics,
            errorRate: this.metrics.apiCalls > 0 ? 
                (this.metrics.errors / this.metrics.apiCalls) * 100 : 0,
            uptime: this.metrics.lastUpdate ? 
                Date.now() - this.metrics.lastUpdate.getTime() : 0
        };
    }
}

// Initialize optimizations
window.realtimeManager = new HostingerRealTimeManager();
window.gpsTracker = new HostingerGPSTracker();
window.performanceMonitor = new PerformanceMonitor();

// Auto-start when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    if (window.location.pathname.includes('dashboard')) {
        window.realtimeManager.startRealTimeUpdates();
    }
    
    if (window.location.pathname.includes('live-tracking')) {
        window.gpsTracker.startTracking();
    }
});

// Add connection status indicator to page
function addConnectionIndicator() {
    const indicator = document.createElement('div');
    indicator.id = 'connection-status';
    indicator.style.cssText = `
        position: fixed;
        top: 10px;
        right: 10px;
        padding: 8px 12px;
        background: rgba(0,0,0,0.8);
        color: white;
        border-radius: 4px;
        font-size: 12px;
        z-index: 9999;
        transition: all 0.3s ease;
    `;
    document.body.appendChild(indicator);
    
    // Initialize status
    if (navigator.onLine) {
        window.realtimeManager.hideOfflineIndicator();
    } else {
        window.realtimeManager.showOfflineIndicator();
    }
}

// Add connection indicator to all pages
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', addConnectionIndicator);
} else {
    addConnectionIndicator();
}

console.log('🚀 Hostinger real-time optimizations loaded');
