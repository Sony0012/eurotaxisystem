// Initialize Lucide icons when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Re-initialize Lucide icons if library is loaded
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});

// Auto-hide flash messages after 5 seconds
setTimeout(() => {
    const alerts = document.querySelectorAll('.alert-slide');
    alerts.forEach(alert => {
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 300);
    });
}, 5000);

// Common AJAX function
async function makeRequest(url, options = {}) {
    try {
        const response = await fetch(url, {
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                ...options.headers
            },
            ...options
        });
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return await response.json();
    } catch (error) {
        console.error('Request failed:', error);
        throw error;
    }
}

function updateNotificationCount() {
    const list = document.getElementById('notificationList');
    const countSpan = document.querySelector('#notificationDropdown .border-b span.text-xs');
    const badge = document.querySelector('#notificationBell span');
    const count = list ? list.querySelectorAll('.notification-item').length : 0;
    
    if (countSpan) countSpan.textContent = count + ' item(s)';
    if (badge) {
        if (count > 0) {
            badge.textContent = count;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    }

    // Sound logic — play only when count INCREASES (new notifications)
    const storedCount = sessionStorage.getItem('notif_count');
    const storedCountNum = storedCount !== null ? parseInt(storedCount, 10) : -1;
    
    if (count > 0 && (storedCountNum === -1 || count > storedCountNum)) {
        const assetUrlMeta = document.querySelector('meta[name="asset-url"]');
        const assetBase = assetUrlMeta ? assetUrlMeta.getAttribute('content') : '/';
        const audio = new Audio(assetBase + 'assets/sounds/notification.mp3');
        audio.play().catch(e => console.log('Audio autoplay prevented'));
    }
    
    // Always store the current count
    sessionStorage.setItem('notif_count', count.toString());
}

function dismissNotification(button) {
    if (event) event.stopPropagation();
    const item = button.closest('.notification-item');
    if (!item) return;
    const type = item.getAttribute('data-type');
    const id = item.getAttribute('data-id');

    // Animate out
    item.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
    item.style.opacity = '0';
    item.style.transform = 'translateX(10px)';
    setTimeout(() => { item.remove(); updateNotificationCount(); }, 200);

    // Call backend for DB-backed alerts (violation_alert, surveillance)
    if ((type === 'violation_alert' || type === 'surveillance') && id) {
        fetch('/notifications/dismiss', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: 'id=' + encodeURIComponent(id)
        }).catch(err => console.error('Failed to dismiss:', err));
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const bell = document.getElementById('notificationBell');
    const dropdown = document.getElementById('notificationDropdown');
    if (!bell || !dropdown) return;

    bell.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdown.classList.toggle('hidden');
    });

    dropdown.addEventListener('click', (e) => {
        e.stopPropagation();
    });

    document.addEventListener('click', () => {
        if (!dropdown.classList.contains('hidden')) {
            dropdown.classList.add('hidden');
        }
    });

    updateNotificationCount();
});
