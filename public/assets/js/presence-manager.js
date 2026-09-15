/**
 * EuroTaxi Presence Manager (Multi-Tab & Device Resilient Presence System)
 * Handles client-side heartbeat dispatch, connection ID isolation, and user interaction detection.
 */
(function() {
    'use strict';

    if (window._EuroTaxiPresenceManager) return; // Prevent duplicate initialization

    // 1. Connection ID Isolation (per browser tab via sessionStorage)
    let connectionId = sessionStorage.getItem('eurotaxi_connection_id');
    if (!connectionId) {
        connectionId = 'tab_' + Math.random().toString(36).substring(2, 12) + '_' + Date.now().toString(36);
        sessionStorage.setItem('eurotaxi_connection_id', connectionId);
    }

    let hasInteraction = false;
    let isSending = false;
    let heartbeatTimer = null;

    // Detect device characteristics
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    const deviceType = isMobile ? 'mobile' : 'desktop';

    // 2. Activity Listeners (Debounced interaction detection)
    function flagInteraction() {
        hasInteraction = true;
    }

    ['mousedown', 'keydown', 'scroll', 'touchstart', 'focus'].forEach(evt => {
        window.addEventListener(evt, flagInteraction, { passive: true });
    });

    // 3. Heartbeat Dispatch Function
    async function sendHeartbeat() {
        if (isSending) return;
        isSending = true;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const payload = {
            connection_id: connectionId,
            has_interaction: hasInteraction,
            device_type: deviceType,
            browser: navigator.userAgent.substring(0, 60),
            platform: navigator.platform || 'unknown'
        };

        // Reset interaction flag after reading
        hasInteraction = false;

        try {
            const res = await fetch('/presence/heartbeat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            if (res.status === 401) {
                // User logged out or session expired
                if (heartbeatTimer) clearInterval(heartbeatTimer);
            }
        } catch (e) {
            // Network hiccup - will retry next interval automatically
        } finally {
            isSending = false;
        }
    }

    // 4. Tab / Window Disconnect Handler
    function handleDisconnect() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const data = JSON.stringify({
            connection_id: connectionId,
            _token: csrfToken
        });

        if (navigator.sendBeacon) {
            const blob = new Blob([data], { type: 'application/json' });
            navigator.sendBeacon('/presence/disconnect', blob);
        } else {
            fetch('/presence/disconnect', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: data,
                keepalive: true
            }).catch(() => {});
        }
    }

    window.addEventListener('pagehide', handleDisconnect);

    // 5. Initial Immediate Heartbeat on Page Load (0s Delay)
    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        sendHeartbeat();
    } else {
        document.addEventListener('DOMContentLoaded', sendHeartbeat);
    }

    // 6. Periodic Heartbeat every 10 seconds
    heartbeatTimer = setInterval(sendHeartbeat, 10000);

    // Expose instance for debugging/testing if needed
    window._EuroTaxiPresenceManager = {
        connectionId,
        sendHeartbeat
    };
})();
