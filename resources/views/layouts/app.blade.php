<!DOCTYPE html>
<html lang="en">

<head>
    <!-- CRITICAL DESKTOP SELF-HEALING GUARD:
         If the user is on a desktop/laptop browser, programmatically purge any active Service Workers
         instantly on page load to prevent POST requests (like Test Chime) from being intercepted and failing. -->
    <script>
        (function() {
            const isCapacitor = (typeof window !== 'undefined' && window.Capacitor) || 
                                navigator.userAgent.includes('Capacitor') || 
                                navigator.userAgent.includes('Android');
            if (!isCapacitor && 'serviceWorker' in navigator) {
                navigator.serviceWorker.getRegistrations().then(registrations => {
                    if (registrations.length === 0) return;
                    const promises = registrations.map(reg => {
                        return reg.unregister().then(success => {
                            if (success) {
                                console.log('[Self-Healing] Stale desktop Service Worker successfully unregistered.');
                                return true;
                            }
                            return false;
                        });
                    });
                    Promise.all(promises).then(results => {
                        if (results.some(r => r === true)) {
                            // Reload once to clear browser service worker interception caches instantly!
                            setTimeout(() => window.location.reload(), 300);
                        }
                    });
                }).catch(err => console.error('Service Worker unregister failed:', err));
            }
        })();
    </script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Aggressive silence for Tailwind and other dev warnings - MUST BE FIRST -->
    <script>
        (function() {
            window.tailwind = { config: { silent: true } };
            const suppressStrings = ['cdn.tailwindcss.com', 'Tailwind CSS', 'Play CDN', 'production warning'];
            const methods = ['warn', 'log', 'info', 'error', 'debug'];
            methods.forEach(method => {
                const original = console[method];
                console[method] = function(...args) {
                    const msg = args.map(arg => String(arg)).join(' ').toLowerCase();
                    if (msg && suppressStrings.some(s => msg.includes(s.toLowerCase()))) {
                        return;
                    }
                    if (original) original.apply(console, args);
                };
            });
        })();
    </script>

    <!-- SEO Meta Tags -->
    <meta name="description" content="Euro Taxi System - Professional taxi fleet management system in the Philippines. Real-time tracking, driver management, and comprehensive taxi business solutions.">
    <meta name="keywords" content="euro taxi, taxi system, fleet management, taxi business philippines, vehicle tracking, driver management, taxi dispatch, transportation system">
    <meta name="author" content="Euro Taxi System">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="Euro Taxi System | Professional Taxi Fleet Management">
    <meta property="og:description" content="Complete taxi fleet management system with real-time tracking and driver management in the Philippines">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ config('app.url', 'https://www.eurotaxisystem.site') }}">
    <meta property="og:image" content="{{ asset('image/logo.png') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Euro Taxi System | Taxi Fleet Management">
    <meta name="twitter:description" content="Professional taxi fleet management system in the Philippines">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Base Asset URL -->
    <meta name="asset-url" content="{{ asset('') }}">

    <!-- Capacitor Native Bridge -->
    <script src="/capacitor.js"></script>
    <script src="/capacitor_plugins.js"></script>

    <title>{{ config('app.name', 'Euro Taxi System') }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon_euro_transparent.png') }}?v=1.6">
    <link rel="icon" type="image/png" href="{{ asset('favicon_euro_transparent.png') }}?v=1.6">
    <link rel="apple-touch-icon" href="{{ asset('favicon_euro_transparent.png') }}?v=1.6">
    <link rel="manifest" href="{{ asset('manifest.json') }}?v=1.7">

    <!-- Critical Assets (Local) -->
    <script src="{{ asset('assets/tailwind.min.js') }}?v=stable_3.4.1"></script>
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/all.min.css') }}?v=stable_6.4.0">
    <!-- Premium Typography: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body, p, h1, h2, h3, h4, h5, h6, span:not(.fa):not(.fas):not(.far):not(.fab), div, a, button, input, select, textarea {
            font-family: 'Outfit', sans-serif !important;
        }
        i[class*="fa-"], .fa, .fas, .far, .fab {
            font-family: "Font Awesome 6 Free" !important;
        }
        .fab { font-family: "Font Awesome 6 Brands" !important; }
    </style>
    <!-- Interactive Tutorial Assets -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.3.1/dist/driver.css"/>
    <link rel="stylesheet" href="{{ asset('assets/css/tutorial.css') }}?v=18.0">

    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }
        /* Prevent FOUC: pre-size icon placeholders so sidebar doesn't reflow */
        i[data-lucide] { display: inline-block; width: 1rem; height: 1rem; vertical-align: middle; flex-shrink: 0; }
        .sidebar-item i[data-lucide] { width: 1.25rem; height: 1.25rem; }
        
        /* Smooth page transitions are handled by instant swap after fetch now. No fade/blanking. */
        
        /* Prevent sidebar flicker during navigation on desktop only */
        @media (min-width: 768px) {
            #appSidebar {
                transition: none !important;
            }
        }
        
        /* Loading state for navigation */
        .nav-loading {
            opacity: 0.6;
            pointer-events: none;
        }
        .nav-loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 16px;
            height: 16px;
            margin: -8px 0 0 -8px;
            border: 2px solid transparent;
            border-top-color: #fbbf24;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Responsive Mobile Drawer Styles (Buttery-Smooth Transitions) */
        @media (max-width: 767px) {
            #appSidebar {
                position: fixed !important;
                top: 0;
                bottom: 0;
                height: 100dvh !important;
                max-height: 100dvh !important;
                left: 0 !important;
                width: 280px !important;
                z-index: 100 !important;
                transform: translateX(-105%) !important;
                transition: transform 0.45s cubic-bezier(0.25, 1, 0.5, 1) !important;
                display: flex !important;
                visibility: hidden;
                pointer-events: none;
                overflow-y: auto !important;
                will-change: transform;
            }
            #appSidebar.show {
                transform: translateX(0) !important;
                visibility: visible !important;
                pointer-events: auto !important;
            }
            #sidebarBackdrop {
                position: fixed;
                inset: 0;
                background-color: rgba(15, 23, 42, 0);
                backdrop-filter: blur(0px);
                z-index: 90;
                visibility: hidden;
                pointer-events: none;
                transition: background-color 0.45s cubic-bezier(0.25, 1, 0.5, 1), backdrop-filter 0.45s cubic-bezier(0.25, 1, 0.5, 1), visibility 0.45s cubic-bezier(0.25, 1, 0.5, 1) !important;
                display: block !important; /* Always active layout-wise, visual state controlled by visibility */
                will-change: background-color, backdrop-filter;
            }
            #sidebarBackdrop.show {
                background-color: rgba(15, 23, 42, 0.5) !important;
                backdrop-filter: blur(4px) !important;
                visibility: visible !important;
                pointer-events: auto !important;
            }
        }
    </style>
    
    <!-- Lucide Icons (Local) -->
    <script src="{{ asset('assets/lucide.min.js') }}"></script>

    <!-- Custom CSS -->
    <link href="{{ asset('assets/app.css') }}?v=1.8" rel="stylesheet">
    @stack('styles')

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .card-hover {
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .card-hover:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.08), 0 4px 12px -4px rgba(0, 0, 0, 0.04) !important;
        }
        @media print {
            @page {
                margin: 0;
            }
            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>

    <!-- Custom JS -->
    <script src="{{ asset('assets/app.js') }}?v=1.8"></script>
    <script>
        function printInHiddenIframe(url) {
            const isTutorialActive = !!localStorage.getItem('tutorial_current_step') || window.location.search.includes('tutorial=1');
            if (isTutorialActive) {
                if (typeof openDriverPdfPreview === 'function') {
                    openDriverPdfPreview();
                } else if (typeof openTutorialPdfPreview === 'function') {
                    openTutorialPdfPreview();
                }
                return;
            }

            const iframe = document.createElement('iframe');
            iframe.style.position = 'absolute';
            iframe.style.top = '-9999px';
            iframe.style.left = '-9999px';
            iframe.style.width = '0';
            iframe.style.height = '0';
            iframe.src = url;
            document.body.appendChild(iframe);
            // The loaded page should call window.print() on load
            // Cleanup iframe after some time
            setTimeout(() => {
                if (document.body.contains(iframe)) {
                    document.body.removeChild(iframe);
                }
            }, 60000); // 60 seconds is enough for the print dialog to open
        }
    </script>

    <!-- Chart.js for Dashboard (Local) -->
    <script src="{{ asset('assets/chart.min.js') }}"></script>
    <script src="{{ asset('assets/chartjs-plugin-datalabels.min.js') }}"></script>

    @auth
        @php
            $user = auth()->user();
            $cacheKey = 'header_notifs_' . $user->uuid;
            
            $notificationService = app(\App\Services\NotificationService::class);
            $headerNotifications = $notificationService->getGlobalNotifications();


            // ─── SYNC WITH READ STATUS (COOKIE) ───
            $readNotifIds = [];
            if (isset($_COOKIE['read_notifs'])) {
                try {
                    $rawCookie = $_COOKIE['read_notifs'];
                    $decodedVal = stripslashes($rawCookie);
                    $readData = json_decode($decodedVal, true);
                    if (!$readData) {
                        $readData = json_decode($rawCookie, true);
                    }
                    
                    // Handle legacy array format gracefully
                    if (is_array($readData) && array_is_list($readData)) {
                        $readNotifIds = array_map('strval', $readData);
                    } elseif (is_array($readData)) {
                        $nowMs = time() * 1000;
                        foreach ($readData as $id => $timestamp) {
                            if ($nowMs - $timestamp < 2592000000) { // 30 days in milliseconds
                                $readNotifIds[] = (string)$id;
                            }
                        }
                    }
                } catch (\Exception $e) {}
            }
            
            file_put_contents(storage_path('logs/notif_debug.log'), "Time: " . date('Y-m-d H:i:s') . "\nCookie: " . (isset($_COOKIE['read_notifs']) ? $_COOKIE['read_notifs'] : 'NULL') . "\nParsed IDs: " . json_encode($readNotifIds) . "\n", FILE_APPEND);

            
            // Filter out ALL read notifications across all categories
            $headerNotifications = array_filter($headerNotifications, function($n) use ($readNotifIds) {
                $notifId = isset($n['id']) ? (string)$n['id'] : md5(($n['title'] ?? '') . ($n['message'] ?? ''));
                return !in_array($notifId, $readNotifIds);
            });

            $headerNotificationCount = count($headerNotifications);
            
            // Calculate specific counts
            $stockNotifCount = collect($headerNotifications)->where('type', 'low_stock')->count();
            $systemNotifCount = $headerNotificationCount - $stockNotifCount;

            // Sort logic: "Action Required" items first, then others by recency
            // We'll use a custom property 'priority' (0 for standard, 1 for Action Required/High)
            foreach($headerNotifications as &$notif) {
                if (isset($notif['time'])) {
                    $t = strtoupper($notif['time']);
                    $notif['priority'] = ($t === 'ACTION REQUIRED' || $t === 'REORDER NOW' || $t === 'NOW' || $t === 'CRITICAL') ? 1 : 0;
                } else {
                    $notif['priority'] = 0;
                }
            }
            unset($notif);

            usort($headerNotifications, function($a, $b) {
                // Priority descending (1 first)
                if ($a['priority'] !== $b['priority']) {
                    return $b['priority'] - $a['priority'];
                }
                
                // Secondary sort: Recency (Newest first)
                $timeA = isset($a['timestamp']) ? $a['timestamp']->timestamp : 0;
                $timeB = isset($b['timestamp']) ? $b['timestamp']->timestamp : 0;
                
                return $timeB - $timeA;
            });
        @endphp

        <!-- Main Layout -->
        <div class="flex h-screen overflow-hidden" id="appLayout">
            <!-- Sidebar Mobile Backdrop -->
            <div id="sidebarBackdrop" onclick="toggleMobileSidebar()" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-40 hidden md:hidden"></div>
            <aside id="appSidebar" class="hidden md:flex w-16 lg:w-60 bg-white shadow-lg flex-shrink-0 transition-all duration-300 overflow-x-hidden relative h-full">
                <div class="h-full flex flex-col w-full">
                    <!-- Logo & Mobile Close Trigger -->
                    <div class="px-4 py-3 md:p-2 lg:p-4 border-b flex flex-row md:flex-col items-center justify-between md:justify-center flex-shrink-0 w-full relative bg-white">
                        <!-- Logo & Brand info -->
                        <div class="flex flex-col items-start md:items-center min-w-0">
                            <img src="{{ asset('uploads/logo.png') }}" alt="Euro System Logo" class="h-9 md:h-8 lg:h-12 w-auto object-contain">
                            <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest leading-none mt-1.5 block md:hidden lg:block">Fleet Management</span>
                        </div>
                        
                        <!-- Close Button on Mobile (Aligned & Styled exactly same as Dashboard Header) -->
                        <button type="button" onclick="toggleMobileSidebar()" 
                            class="p-2 -mr-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg md:!hidden flex items-center justify-center shrink-0 transition-colors focus:outline-none">
                            <i data-lucide="menu" class="w-6 h-6"></i>
                        </button>
                    </div>

                    <!-- Navigation -->
                    <nav class="flex-1 p-2 lg:p-4 space-y-1 overflow-y-auto overflow-x-hidden w-full">
                        @if(auth()->user()->role === 'super_admin')
                        <a href="{{ route('super-admin.index') }}"
                            class="sidebar-item flex items-center justify-start md:justify-center lg:justify-start gap-2.5 px-4 md:px-0 lg:px-4 py-1.5 md:py-2 rounded-lg font-semibold {{ request()->routeIs('super-admin.*') ? 'bg-yellow-100 text-yellow-800' : 'text-yellow-700 hover:bg-yellow-50 hover:text-yellow-800' }}">
                            <i data-lucide="crown" class="w-5 md:w-5 lg:w-4 h-5 md:h-5 lg:h-4"></i>
                            <span class="text-sm block md:hidden lg:block">Owner Panel</span>
                        </a>
                        <hr class="my-2 border-gray-100 block md:hidden lg:block">
                        @endif

                        @if(auth()->user()->hasAccessTo('dashboard'))
                        <a href="{{ route('dashboard') }}"
                            class="sidebar-item flex items-center justify-start md:justify-center lg:justify-start gap-2.5 px-4 md:px-0 lg:px-4 py-1.5 md:py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('dashboard') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                            <i data-lucide="layout-dashboard" class="w-5 md:w-5 lg:w-4 h-5 md:h-5 lg:h-4"></i>
                            <span class="text-sm block md:hidden lg:block">Dashboard</span>
                        </a>
                        @endif

                        @if(auth()->user()->hasAccessTo('units.*'))
                        <div class="relative group w-full sidebar-dropdown-container">
                            <a href="{{ route('units.index') }}"
                                class="sidebar-item sidebar-has-dropdown flex items-center justify-start md:justify-center lg:justify-start gap-2.5 px-4 md:px-0 lg:px-4 py-1.5 md:py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('units.*') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                                <i data-lucide="car" class="w-5 md:w-5 lg:w-4 h-5 md:h-5 lg:h-4"></i>
                                <span class="text-sm block md:hidden lg:block flex-1 whitespace-nowrap">Unit Management</span>
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400 group-hover:text-yellow-700 transition-transform duration-200 group-hover:rotate-180 sidebar-chevron"></i>
                            </a>
                            {{-- Dropdown Sub-menu --}}
                            <div class="sidebar-dropdown-menu hidden group-hover:block lg:pl-10 pl-4 space-y-1 mt-1 transition-all duration-300 {{ request()->routeIs('units.*') ? 'active-route-menu !block' : '' }}">
                                <a href="{{ route('units.index') }}" class="{{ request()->routeIs('units.index') ? 'text-yellow-700 font-bold bg-yellow-50/50 block rounded-xl py-2 px-3' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50 block rounded-xl py-2 px-3' }} flex items-center gap-2">
                                    <i data-lucide="layout-grid" class="w-3.5 h-3.5 {{ request()->routeIs('units.index') ? 'text-yellow-600' : 'text-slate-400' }}"></i> 
                                    <span class="text-[10px] uppercase tracking-wider font-bold">All Units List</span>
                                </a>
                                <a href="{{ route('units.flagged') }}" class="{{ request()->routeIs('units.flagged') ? 'text-orange-700 font-bold bg-orange-50/50 block rounded-xl py-2 px-3' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50 block rounded-xl py-2 px-3' }} flex items-center gap-2">
                                    <i data-lucide="flag" class="w-3.5 h-3.5 {{ request()->routeIs('units.flagged') ? 'text-orange-600' : 'text-slate-400' }}"></i> 
                                    <span class="text-[10px] uppercase tracking-wider font-bold">Flagged Units</span>
                                </a>
                            </div>
                        </div>
                        @endif

                        @if(auth()->user()->hasAccessTo('driver-management.*'))
                        <div class="relative group w-full sidebar-dropdown-container">
                            <a href="{{ route('driver-management.index') }}"
                                class="sidebar-item sidebar-has-dropdown flex items-center justify-start md:justify-center lg:justify-start gap-2.5 px-4 md:px-0 lg:px-4 py-1.5 md:py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('driver-management.*') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                                <i data-lucide="users" class="w-5 md:w-5 lg:w-4 h-5 md:h-5 lg:h-4"></i>
                                <span class="text-sm block md:hidden lg:block flex-1 whitespace-nowrap">Driver Management</span>
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400 group-hover:text-yellow-700 transition-transform duration-200 group-hover:rotate-180 sidebar-chevron"></i>
                            </a>
                            {{-- Dropdown Sub-menu --}}
                            <div class="sidebar-dropdown-menu hidden group-hover:block lg:pl-10 pl-4 space-y-1 mt-1 transition-all duration-300 {{ request()->routeIs('driver-management.*') ? 'active-route-menu !block' : '' }}">
                                <a href="{{ route('driver-management.index') }}" class="{{ request()->routeIs('driver-management.index') ? 'text-yellow-700 font-bold bg-yellow-50/50 block rounded-xl py-2 px-3' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50 block rounded-xl py-2 px-3' }} flex items-center gap-2">
                                    <i data-lucide="users" class="w-3.5 h-3.5 {{ request()->routeIs('driver-management.index') ? 'text-yellow-600' : 'text-slate-400' }}"></i> 
                                    <span class="text-[10px] uppercase tracking-wider font-bold">All Drivers Roster</span>
                                </a>
                                <a href="{{ route('driver-management.banned') }}" class="{{ request()->routeIs('driver-management.banned') ? 'text-red-500 font-bold bg-red-50/50 block rounded-xl py-2 px-3' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50 block rounded-xl py-2 px-3' }} flex items-center gap-2">
                                    <i data-lucide="ban" class="w-3.5 h-3.5 {{ request()->routeIs('driver-management.banned') ? 'text-red-500' : 'text-slate-400' }}"></i> 
                                    <span class="text-[10px] uppercase tracking-wider font-bold">Banned Drivers</span>
                                </a>
                                <a href="{{ route('driver-management.terms') }}" class="{{ request()->routeIs('driver-management.terms') ? 'text-blue-600 font-bold bg-blue-50/50 block rounded-xl py-2 px-3' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50 block rounded-xl py-2 px-3' }} flex items-center gap-2">
                                    <i data-lucide="file-signature" class="w-3.5 h-3.5 {{ request()->routeIs('driver-management.terms') ? 'text-blue-600' : 'text-slate-400' }}"></i> 
                                    <span class="text-[10px] uppercase tracking-wider font-bold">Driver Terms</span>
                                </a>
                                <a href="{{ route('driver-management.debts') }}" class="{{ request()->routeIs('driver-management.debts') ? 'text-rose-600 font-bold bg-rose-50/50 block rounded-xl py-2 px-3' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50 block rounded-xl py-2 px-3' }} flex items-center gap-2">
                                    <i data-lucide="wallet" class="w-3.5 h-3.5 {{ request()->routeIs('driver-management.debts') ? 'text-rose-600' : 'text-slate-400' }}"></i> 
                                    <span class="text-[10px] uppercase tracking-wider font-bold">Pending Debts</span>
                                </a>
                            </div>
                        </div>
                        @endif

                        @if(auth()->user()->hasAccessTo('live-tracking.*'))
                        <a href="{{ route('live-tracking.index') }}"
                            class="sidebar-item flex items-center justify-start md:justify-center lg:justify-start gap-2.5 px-4 md:px-0 lg:px-4 py-1.5 md:py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('live-tracking.*') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                            <i data-lucide="map-pin" class="w-5 md:w-5 lg:w-4 h-5 md:h-5 lg:h-4"></i>
                            <span class="text-sm block md:hidden lg:block">Live Tracking</span>
                        </a>
                        @endif

                        @if(auth()->user()->hasAccessTo('decision-management.*'))
                        <a href="{{ route('decision-management.index') }}"
                            class="sidebar-item flex items-center justify-start md:justify-center lg:justify-start gap-2.5 px-4 md:px-0 lg:px-4 py-1.5 md:py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('decision-management.*') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                            <i data-lucide="file-text" class="w-5 md:w-5 lg:w-4 h-5 md:h-5 lg:h-4"></i>
                            <span class="text-sm block md:hidden lg:block">Franchise</span>
                        </a>
                        @endif

                        @if(auth()->user()->hasAccessTo('boundaries.*'))
                        <a href="{{ route('boundaries.index') }}"
                            class="sidebar-item flex items-center justify-start md:justify-center lg:justify-start gap-2.5 px-4 md:px-0 lg:px-4 py-1.5 md:py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('boundaries.*') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                            <i data-lucide="wallet" class="w-5 md:w-5 lg:w-4 h-5 md:h-5 lg:h-4"></i>
                            <span class="text-sm block md:hidden lg:block">Boundaries</span>
                        </a>
                        @endif

                        @if(auth()->user()->hasAccessTo('maintenance.*'))
                        <div class="relative group w-full sidebar-dropdown-container">
                            <a href="{{ route('maintenance.index') }}"
                                class="sidebar-item sidebar-has-dropdown flex items-center justify-start md:justify-center lg:justify-start gap-2.5 px-4 md:px-0 lg:px-4 py-1.5 md:py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('maintenance.*') || request()->routeIs('inventory.*') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                                <i data-lucide="wrench" class="w-5 md:w-5 lg:w-4 h-5 md:h-5 lg:h-4"></i>
                                <span class="text-sm block md:hidden lg:block flex-1 whitespace-nowrap">Maintenance</span>
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400 group-hover:text-yellow-700 transition-transform duration-200 group-hover:rotate-180 sidebar-chevron"></i>
                            </a>
                            {{-- Dropdown Sub-menu --}}
                            <div class="sidebar-dropdown-menu hidden group-hover:block lg:pl-10 pl-4 space-y-1 mt-1 transition-all duration-300 {{ request()->routeIs('maintenance.*') || request()->routeIs('inventory.*') ? 'active-route-menu !block' : '' }}">
                                <a href="{{ route('maintenance.index') }}" class="{{ request()->routeIs('maintenance.index') ? 'text-yellow-700 font-bold bg-yellow-50/50 block rounded-xl py-2 px-3' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50 block rounded-xl py-2 px-3' }} flex items-center gap-2">
                                    <i data-lucide="wrench" class="w-3.5 h-3.5 {{ request()->routeIs('maintenance.index') ? 'text-yellow-600' : 'text-slate-400' }}"></i> 
                                    <span class="text-[10px] uppercase tracking-wider font-bold">Maintenance Records</span>
                                </a>
                                <a href="{{ route('inventory.manage') }}" class="{{ request()->routeIs('inventory.manage') ? 'text-blue-600 font-bold bg-blue-50/50 block rounded-xl py-2 px-3' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50 block rounded-xl py-2 px-3' }} flex items-center gap-2">
                                    <i data-lucide="package" class="w-3.5 h-3.5 {{ request()->routeIs('inventory.manage') ? 'text-blue-600' : 'text-slate-400' }}"></i> 
                                    <span class="text-[10px] uppercase tracking-wider font-bold">Manage Inventory</span>
                                </a>
                            </div>
                        </div>
                        @endif

                        @if(auth()->user()->hasAccessTo('coding.*'))
                        <a href="{{ route('coding.index') }}"
                            class="sidebar-item flex items-center justify-start md:justify-center lg:justify-start gap-2.5 px-4 md:px-0 lg:px-4 py-1.5 md:py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('coding.*') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                            <i data-lucide="calendar" class="w-5 md:w-5 lg:w-4 h-5 md:h-5 lg:h-4"></i>
                            <span class="text-sm block md:hidden lg:block">Coding Management</span>
                        </a>
                        @endif

                        @if(auth()->user()->hasAccessTo('driver-behavior.*'))
                        <div class="relative group w-full sidebar-dropdown-container">
                            <a href="{{ route('driver-behavior.incidents') }}"
                                class="sidebar-item sidebar-has-dropdown flex items-center justify-start md:justify-center lg:justify-start gap-2.5 px-4 md:px-0 lg:px-4 py-1.5 md:py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('driver-behavior.*') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                                <i data-lucide="alert-triangle" class="w-5 md:w-5 lg:w-4 h-5 md:h-5 lg:h-4"></i>
                                <span class="text-sm block md:hidden lg:block flex-1 whitespace-nowrap">Driver Behavior</span>
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400 group-hover:text-yellow-700 transition-transform duration-200 group-hover:rotate-180 sidebar-chevron"></i>
                            </a>
                            
                            {{-- Dropdown Sub-menu --}}
                            <div class="sidebar-dropdown-menu hidden group-hover:block lg:pl-10 pl-4 space-y-1 mt-1 transition-all duration-300 {{ request()->routeIs('driver-behavior.*') ? 'active-route-menu !block' : '' }}">
                                <a href="{{ route('driver-behavior.incidents') }}" class="{{ request()->routeIs('driver-behavior.incidents') ? 'text-yellow-700 font-bold bg-yellow-50/50 block rounded-xl py-2 px-3' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50 block rounded-xl py-2 px-3' }} flex items-center gap-2">
                                    <i data-lucide="alert-triangle" class="w-3.5 h-3.5 {{ request()->routeIs('driver-behavior.incidents') ? 'text-yellow-600' : 'text-slate-400' }}"></i> 
                                    <span class="text-[10px] uppercase tracking-wider font-bold">Behavior & Incidents</span>
                                </a>
                                <a href="{{ route('driver-behavior.incentives') }}" class="{{ request()->routeIs('driver-behavior.incentives') ? 'text-green-600 font-bold bg-green-50/50 block rounded-xl py-2 px-3' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50 block rounded-xl py-2 px-3' }} flex items-center gap-2">
                                    <i data-lucide="award" class="w-3.5 h-3.5 {{ request()->routeIs('driver-behavior.incentives') ? 'text-green-600' : 'text-slate-400' }}"></i> 
                                    <span class="text-[10px] uppercase tracking-wider font-bold">Weekly Incentives</span>
                                </a>
                                <a href="{{ route('driver-behavior.performance') }}" class="{{ request()->routeIs('driver-behavior.performance') ? 'text-blue-600 font-bold bg-blue-50/50 block rounded-xl py-2 px-3' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50 block rounded-xl py-2 px-3' }} flex items-center gap-2">
                                    <i data-lucide="bar-chart-2" class="w-3.5 h-3.5 {{ request()->routeIs('driver-behavior.performance') ? 'text-blue-600' : 'text-slate-400' }}"></i> 
                                    <span class="text-[10px] uppercase tracking-wider font-bold">Performance Summary</span>
                                </a>
                                <a href="{{ route('driver-behavior.accidents') }}" class="{{ request()->routeIs('driver-behavior.accidents') ? 'text-red-600 font-bold bg-red-50/50 block rounded-xl py-2 px-3' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50 block rounded-xl py-2 px-3' }} flex items-center gap-2">
                                    <i data-lucide="alert-octagon" class="w-3.5 h-3.5 {{ request()->routeIs('driver-behavior.accidents') ? 'text-red-600' : 'text-slate-400' }}"></i> 
                                    <span class="text-[10px] uppercase tracking-wider font-bold">Accident Reports</span>
                                </a>
                            </div>
                        </div>
                        @endif

                        @if(auth()->user()->hasAccessTo('office-expenses.*'))
                        <a href="{{ route('office-expenses.index') }}"
                            class="sidebar-item flex items-center justify-start md:justify-center lg:justify-start gap-2.5 px-4 md:px-0 lg:px-4 py-1.5 md:py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('office-expenses.*') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                            <i data-lucide="philippine-peso" class="w-5 md:w-5 lg:w-4 h-5 md:h-5 lg:h-4"></i>
                            <span class="text-sm block md:hidden lg:block">Office Expenses</span>
                        </a>
                        @endif

                        @if(auth()->user()->hasAccessTo('salary.*'))
                        <a href="{{ route('salary.index') }}"
                            class="sidebar-item flex items-center justify-start md:justify-center lg:justify-start gap-2.5 px-4 md:px-0 lg:px-4 py-1.5 md:py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('salary.*') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                            <i data-lucide="calculator" class="w-5 md:w-5 lg:w-4 h-5 md:h-5 lg:h-4"></i>
                            <span class="text-sm block md:hidden lg:block">Salary Management</span>
                        </a>
                        @endif

                        @if(auth()->user()->hasAccessTo('analytics.*'))
                        <div class="relative group w-full sidebar-dropdown-container">
                            <a href="{{ route('analytics.index') }}"
                                class="sidebar-item sidebar-has-dropdown flex items-center justify-start md:justify-center lg:justify-start gap-2.5 px-4 md:px-0 lg:px-4 py-1.5 md:py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('analytics.*') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                                <i data-lucide="bar-chart" class="w-4 md:w-5 lg:w-4 h-4 md:h-5 lg:h-4"></i>
                                <span class="text-sm block md:hidden lg:block flex-1 whitespace-nowrap">Analytics</span>
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400 group-hover:text-yellow-700 transition-transform duration-200 group-hover:rotate-180 sidebar-chevron"></i>
                            </a>
                            
                            {{-- Dropdown Sub-menu --}}
                            <div class="sidebar-dropdown-menu hidden group-hover:block lg:pl-10 pl-4 space-y-1 mt-1 transition-all duration-300 {{ request()->routeIs('analytics.*') ? 'active-route-menu !block' : '' }}">
                                <a href="{{ route('analytics.index') }}" class="{{ request()->routeIs('analytics.index') ? 'text-yellow-700 font-bold bg-yellow-50/50 block rounded-xl py-2 px-3' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50 block rounded-xl py-2 px-3' }} flex items-center gap-2">
                                    <i data-lucide="bar-chart-3" class="w-3.5 h-3.5 {{ request()->routeIs('analytics.index') ? 'text-yellow-600' : 'text-slate-400' }}"></i> 
                                    <span class="text-[10px] uppercase tracking-wider font-bold">Predictive Analytics</span>
                                </a>
                                <a href="{{ route('analytics.history') }}" class="{{ request()->routeIs('analytics.history') ? 'text-indigo-600 font-bold bg-indigo-50/50 block rounded-xl py-2 px-3' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50 block rounded-xl py-2 px-3' }} flex items-center gap-2">
                                    <i data-lucide="history" class="w-3.5 h-3.5 {{ request()->routeIs('analytics.history') ? 'text-indigo-600' : 'text-slate-400' }}"></i> 
                                    <span class="text-[10px] uppercase tracking-wider font-bold">Daily Ledger</span>
                                </a>
                            </div>
                        </div>
                        @endif

                        @if(auth()->user()->hasAccessTo('unit-profitability.*'))
                        <a href="{{ route('unit-profitability.index') }}"
                            class="sidebar-item flex items-center justify-start md:justify-center lg:justify-start gap-2.5 px-4 md:px-0 lg:px-4 py-1.5 md:py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('unit-profitability.*') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                            <i data-lucide="trending-up" class="w-5 md:w-5 lg:w-4 h-5 md:h-5 lg:h-4"></i>
                            <span class="text-sm block md:hidden lg:block">Unit Profitability</span>
                        </a>
                        @endif

                        @if(auth()->user()->hasAccessTo('staff.*'))
                        <div class="relative group w-full sidebar-dropdown-container">
                            <a href="{{ route('staff.index') }}"
                                class="sidebar-item sidebar-has-dropdown flex items-center justify-start md:justify-center lg:justify-start gap-2.5 px-4 md:px-0 lg:px-4 py-1.5 md:py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('staff.*') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                                <i data-lucide="user-cog" class="w-5 md:w-5 lg:w-5 h-5 md:h-5 lg:h-5"></i>
                                <span class="text-sm block md:hidden lg:block flex-1 whitespace-nowrap">General Staff Records</span>
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400 group-hover:text-yellow-700 transition-transform duration-200 group-hover:rotate-180 sidebar-chevron"></i>
                            </a>
                            {{-- Dropdown Sub-menu --}}
                            <div class="sidebar-dropdown-menu hidden group-hover:block lg:pl-10 pl-4 space-y-1 mt-1 transition-all duration-300 {{ request()->routeIs('staff.*') ? 'active-route-menu !block' : '' }}">
                                <a href="{{ route('staff.index') }}" class="{{ request()->routeIs('staff.index') ? 'text-yellow-700 font-bold bg-yellow-50/50 block rounded-xl py-2 px-3' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50 block rounded-xl py-2 px-3' }} flex items-center gap-2">
                                    <i data-lucide="user-cog" class="w-3.5 h-3.5 {{ request()->routeIs('staff.index') ? 'text-yellow-600' : 'text-slate-400' }}"></i> 
                                    <span class="text-[10px] uppercase tracking-wider font-bold">Staff Directory</span>
                                </a>
                                <a href="{{ route('staff.admin') }}" class="{{ request()->routeIs('staff.admin') ? 'text-blue-600 font-bold bg-blue-50/50 block rounded-xl py-2 px-3' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50 block rounded-xl py-2 px-3' }} flex items-center gap-2">
                                    <i data-lucide="shield-check" class="w-3.5 h-3.5 {{ request()->routeIs('staff.admin') ? 'text-blue-600' : 'text-slate-400' }}"></i> 
                                    <span class="text-[10px] uppercase tracking-wider font-bold">Admin Staff</span>
                                </a>
                                <a href="{{ route('staff.drivers') }}" class="{{ request()->routeIs('staff.drivers') ? 'text-green-600 font-bold bg-green-50/50 block rounded-xl py-2 px-3' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50 block rounded-xl py-2 px-3' }} flex items-center gap-2">
                                    <i data-lucide="smartphone" class="w-3.5 h-3.5 {{ request()->routeIs('staff.drivers') ? 'text-green-600' : 'text-slate-400' }}"></i> 
                                    <span class="text-[10px] uppercase tracking-wider font-bold">Mobile App Drivers</span>
                                </a>
                            </div>
                        </div>
                        @endif

                        <hr class="my-2 border-gray-100 block md:hidden lg:block">

                        @if(auth()->user()->hasAccessTo('support.*'))
                        <a href="{{ route('support.index') }}"
                            class="sidebar-item flex items-center justify-start md:justify-center lg:justify-start gap-2.5 px-4 md:px-0 lg:px-4 py-1.5 md:py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('support.*') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                            <i data-lucide="message-square" class="w-5 md:w-5 lg:w-4 h-5 md:h-5 lg:h-4"></i>
                            <span class="text-sm block md:hidden lg:block">Support Center</span>
                        </a>
                        @endif

                        @if(auth()->user()->hasAccessTo('announcements.*'))
                        <a href="{{ route('announcements.index') }}"
                            class="sidebar-item flex items-center justify-start md:justify-center lg:justify-start gap-2.5 px-4 md:px-0 lg:px-4 py-1.5 md:py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('announcements.*') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                            <i data-lucide="megaphone" class="w-5 md:w-5 lg:w-4 h-5 md:h-5 lg:h-4"></i>
                            <span class="text-sm block md:hidden lg:block">Announcements</span>
                        </a>
                        @endif

                        @if(auth()->user()->hasAccessTo('activity-logs.*'))
                        <a href="{{ route('activity-logs.index') }}"
                            class="sidebar-item flex items-center justify-start md:justify-center lg:justify-start gap-2.5 px-4 md:px-0 lg:px-4 py-1.5 md:py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('activity-logs.*') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                            <i data-lucide="history" class="w-5 md:w-5 lg:w-4 h-5 md:h-5 lg:h-4"></i>
                            <span class="text-sm block md:hidden lg:block">History Logs</span>
                        </a>
                        @endif

                        @if(auth()->user()->hasAccessTo('archive.*'))
                        <a href="{{ route('archive.index') }}"
                            class="sidebar-item flex items-center justify-start md:justify-center lg:justify-start gap-2.5 px-4 md:px-0 lg:px-4 py-1.5 md:py-2 rounded-lg text-gray-700 hover:bg-red-50 hover:text-red-700 {{ request()->routeIs('archive.*') ? 'bg-red-50 text-red-700 font-semibold' : '' }}">
                            <i data-lucide="archive" class="w-5 md:w-5 lg:w-4 h-5 md:h-5 lg:h-4"></i>
                            <span class="text-sm block md:hidden lg:block">Archive</span>
                        </a>
                        @endif
                    </nav>

                    <!-- User Menu -->
                    <div class="p-2 lg:p-4 border-t bg-white relative z-50 flex-shrink-0 w-full">
                        <a href="{{ route('my-account') }}" 
                           class="flex items-center justify-start md:justify-center lg:justify-start gap-3 mb-3 p-1 lg:p-2 rounded-lg hover:bg-gray-50 transition-colors group w-full">
                            <div
                                class="w-8 h-8 lg:w-10 lg:h-10 bg-yellow-600 rounded-full flex items-center justify-center text-white font-semibold group-hover:bg-yellow-700 transition-colors overflow-hidden flex-shrink-0 border border-gray-100">
                                @if(auth()->user()->profile_image)
                                    @php
                                        $imagePath = str_replace('resources/', '', auth()->user()->profile_image);
                                    @endphp
                                    @if(str_contains(auth()->user()->profile_image, 'resources/assets/') || str_starts_with(auth()->user()->profile_image, 'image/'))
                                        <img src="{{ asset($imagePath) }}" alt="Profile" class="w-full h-full object-cover">
                                    @else
                                        <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="Profile" class="w-full h-full object-cover">
                                    @endif
                                @else
                                    {{ strtoupper(substr(auth()->user()->full_name ?? 'U', 0, 1)) }}
                                @endif
                            </div>
                            <div class="block md:hidden lg:block min-w-0 flex-1">
                                <h4 class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h4>
                                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->role === 'super_admin' ? 'Owner' : ucfirst(auth()->user()->role ?? 'user') }}</p>
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400 group-hover:text-yellow-600 transition-colors hidden lg:block"></i>
                        </a>
                        
                        <!-- Take the Tour Again -->
                        <button type="button"
                            onclick="if(window.TutorialManager) window.TutorialManager.restart();"
                            class="flex items-center justify-start md:justify-center lg:justify-start gap-2 px-3 md:px-1 lg:px-3 py-2 mb-1 text-sm text-blue-600 hover:bg-blue-50 rounded-lg w-full transition-colors">
                            <i data-lucide="help-circle" class="w-4 h-4"></i>
                            <span class="block md:hidden lg:block font-semibold">Take the Tour Again</span>
                        </button>
                        
                        <!-- Logout Form -->
                        <form id="logout-form" action="{{ route('logout') }}" method="GET" class="hidden"></form>
                        
                        <button type="button"
                            onclick="if(confirm('Are you sure you want to logout?')) { document.getElementById('logout-form').submit(); }"
                            class="flex items-center justify-start md:justify-center lg:justify-start gap-2 px-3 md:px-1 lg:px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg w-full transition-colors">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                            <span class="block md:hidden lg:block font-semibold">Logout</span>
                        </button>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main id="appMainContent" class="flex-1 flex flex-col min-h-0 min-w-0">
                <!-- Top Bar -->
                <header class="bg-white shadow-sm border-b px-4 md:px-6 py-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <!-- Mobile Menu Trigger -->
                            <button onclick="toggleMobileSidebar()" class="p-2 -ml-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg md:!hidden flex items-center justify-center shrink-0">
                                <i data-lucide="menu" class="w-6 h-6"></i>
                            </button>
                            <div>
                                <h2 class="text-lg md:text-2xl font-black text-gray-900 leading-tight">@yield('page-heading', 'Dashboard')</h2>
                                @hasSection('page-subheading')
                                    <p class="text-[11px] md:text-sm text-gray-500 mt-0.5 md:mt-1">@yield('page-subheading')</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            {{-- Consolidating all notifications into the Main Bell --}}


                            <!-- Main Notification Bell -->
                            <div class="relative">
                                <button id="notificationBell"
                                    class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg">
                                    <i data-lucide="bell" class="w-5 h-5"></i>
                                    <span id="main-nav-notif-badge"
                                            class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 bg-red-500 text-white text-[10px] font-black leading-[18px] rounded-full text-center transition-all duration-300 {{ $headerNotificationCount > 0 ? '' : 'hidden' }}">
                                            {{ $headerNotificationCount }}
                                        </span>
                                </button>

                                <div id="notificationDropdown"
                                    class="hidden fixed md:absolute inset-x-4 md:inset-x-auto md:right-0 mt-2 md:w-80 bg-white shadow-2xl md:shadow-xl rounded-2xl border border-gray-100 z-[9999] overflow-hidden">
                                    <div class="px-4 py-3 border-b bg-gray-50/50 flex items-center justify-between">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-black text-gray-900 tracking-tight">Notifications</span>
                                            <span id="notif-dropdown-subtitle" class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ $headerNotificationCount }} item(s)</span>
                                        </div>
                                        @if($headerNotificationCount > 0)
                                            <button onclick="markAllAsRead()" class="text-[10px] font-bold text-yellow-600 hover:text-yellow-700 hover:underline transition-all">
                                                Mark All Read
                                            </button>
                                        @endif
                                    </div>

                                    {{-- Filter Tabs --}}
                                    <div class="flex border-b bg-white">
                                        <button onclick="filterNotifs('system')" id="btn-filter-system" class="flex-1 py-2.5 text-[11px] font-bold uppercase tracking-wider text-yellow-600 border-b-2 border-yellow-500 transition-all">
                                            System
                                            <span id="badge-filter-system" class="bg-red-500 text-white text-[9px] px-1.5 py-0.5 rounded-full ml-1 {{ $systemNotifCount > 0 ? '' : 'hidden' }}">{{ $systemNotifCount }}</span>
                                        </button>
                                        <button onclick="filterNotifs('low_stock')" id="btn-filter-parts" class="flex-1 py-2.5 text-[11px] font-bold uppercase tracking-wider text-gray-400 hover:text-gray-600 transition-all flex items-center justify-center gap-1.5">
                                            Parts Stock
                                            <span id="badge-filter-parts" class="bg-orange-500 text-white text-[9px] px-1.5 py-0.5 rounded-full {{ $stockNotifCount > 0 ? '' : 'hidden' }}">{{ $stockNotifCount }}</span>
                                        </button>
                                    </div>

                                    <div class="max-h-80 overflow-y-auto" id="notificationList">
                                        @if(empty($headerNotifications))
                                            <div class="px-4 py-4 text-sm text-gray-500 text-center">No notifications.</div>
                                        @else
                                            @foreach($headerNotifications as $n)
                                                @php 
                                                    $notifId = $n['id'] ?? md5($n['title'] . ($n['message'] ?? '')); 
                                                    $isHidden = ($n['type'] === 'low_stock');
                                                @endphp
                                                <div class="notification-item px-4 py-3 border-b last:border-b-0 hover:bg-gray-50 flex items-start gap-2 transition-all unread-notif {{ $isHidden ? 'hidden' : '' }}"
                                                     id="notif-{{ $notifId }}"
                                                     data-type="{{ $n['type'] }}" 
                                                     data-notif-id="{{ $notifId }}"
                                                     style="background-color: #f0f9ff;">
                                                    <a href="{{ $n['url'] ?? '#' }}" class="flex-1 flex gap-3 min-w-0" onclick="markAsRead('{{ $notifId }}')">

                                                        <div class="mt-0.5 flex-shrink-0">
                                                            @if($n['type'] === 'case_expiry')
                                                                <i data-lucide="file-warning" class="w-4 h-4 text-yellow-600"></i>
                                                            @elseif($n['type'] === 'coding_today' || $n['type'] === 'coding_notice' || str_contains(strtolower($n['title']), 'coding'))
                                                                <i data-lucide="car-front" class="w-4 h-4 text-blue-600"></i>
                                                            @elseif($n['type'] === 'violation_alert' || str_contains(strtolower($n['title']), 'violation'))
                                                                <i data-lucide="shield-alert" class="w-4 h-4 text-red-600"></i>
                                                            @elseif($n['type'] === 'missing_unit' || str_contains(strtolower($n['title']), 'missing unit'))
                                                                <i data-lucide="map-pin-off" class="w-4 h-4 text-red-500"></i>
                                                            @elseif($n['type'] === 'low_stock')
                                                                <i data-lucide="package-search" class="w-4 h-4 text-orange-500"></i>
                                                            @elseif($n['type'] === 'license_expiry')
                                                                <i data-lucide="id-card" class="w-4 h-4 text-rose-500"></i>
                                                            @elseif($n['type'] === 'odo_maint_due')
                                                                <i data-lucide="settings-2" class="w-4 h-4 text-orange-600"></i>
                                                            @elseif(str_contains(strtolower($n['title']), 'payment') || str_contains(strtolower($n['title']), 'remit'))
                                                                <i data-lucide="credit-card" class="w-4 h-4 text-emerald-600"></i>
                                                            @elseif(str_contains(strtolower($n['title']), 'broadcast') || str_contains(strtolower($n['title']), 'chime') || str_contains(strtolower($n['title']), 'sound') || $n['type'] === 'push_broadcast')
                                                                <i data-lucide="volume-2" class="w-4 h-4 text-indigo-500"></i>
                                                            @elseif(str_contains(strtolower($n['title']), 'success') || str_contains(strtolower($n['title']), 'approved'))
                                                                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i>
                                                            @elseif(str_contains(strtolower($n['title']), 'alert') || str_contains(strtolower($n['title']), 'warning') || str_contains(strtolower($n['title']), 'failed') || str_contains(strtolower($n['title']), 'error'))
                                                                <i data-lucide="alert-circle" class="w-4 h-4 text-red-600"></i>
                                                            @else
                                                                <i data-lucide="bell" class="w-4 h-4 text-blue-500"></i>
                                                            @endif
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-xs font-semibold text-gray-800 truncate">
                                                                {{ $n['title'] }}</p>
                                                            <p class="text-xs text-gray-600 mt-0.5 line-clamp-2">{{ $n['message'] }}</p>
                                                            @if(isset($n['time']))
                                                                <p class="text-[10px] text-gray-400 mt-1 font-medium">{{ $n['time'] }}</p>
                                                            @endif
                                                        </div>
                                                    </a>
                                                    <button type="button"
                                                        class="ml-1 text-gray-400 hover:text-gray-600 flex-shrink-0"
                                                        onclick="dismissNotification(this);">
                                                        <span class="sr-only">Dismiss</span>
                                                        <i data-lucide="x" class="w-3 h-3"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                            <script>
                                                (function() {
                                                    try {
                                                        const readNotifs = JSON.parse(localStorage.getItem('read_notifs') || '{}');
                                                        const items = document.querySelectorAll('.notification-item');
                                                        let sysCnt = 0; let partCnt = 0;
                                                        items.forEach(i => {
                                                            const id = i.dataset.notifId;
                                                            if (id && readNotifs[id]) {
                                                                i.style.display = 'none';
                                                                i.classList.remove('unread-notif');
                                                                i.style.backgroundColor = 'transparent';
                                                            } else if (i.classList.contains('unread-notif') && i.style.display !== 'none') {
                                                                if(i.dataset.type === 'low_stock') partCnt++;
                                                                else sysCnt++;
                                                            }
                                                        });
                                                        const total = sysCnt + partCnt;
                                                        const badge = document.getElementById('main-nav-notif-badge');
                                                        if (badge) {
                                                            badge.textContent = total;
                                                            if (total > 0) badge.classList.remove('hidden'); else badge.classList.add('hidden');
                                                        }
                                                        const subtitle = document.getElementById('notif-dropdown-subtitle');
                                                        if (subtitle) subtitle.textContent = total + ' item(s)';
                                                        
                                                        const sysBadge = document.getElementById('badge-filter-system');
                                                        if(sysBadge) { sysBadge.textContent = sysCnt; if(sysCnt > 0) sysBadge.classList.remove('hidden'); else sysBadge.classList.add('hidden'); }
                                                        
                                                        const partsBadge = document.getElementById('badge-filter-parts');
                                                        if(partsBadge) { partsBadge.textContent = partCnt; if(partCnt > 0) partsBadge.classList.remove('hidden'); else partsBadge.classList.add('hidden'); }
                                                    } catch(e) {}
                                                })();
                                            </script>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Staff Chat Header Dropdown (Right beside Notification Bell - matching Image 2) -->
                            <div class="relative">
                                <button id="headerChatBtn" type="button" onclick="toggleHeaderChatDropdown(event)"
                                    class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors flex items-center justify-center"
                                    title="Staff Chat & Messages">
                                    <i data-lucide="mail" class="w-5 h-5"></i>
                                    <span id="headerChatBadge"
                                        class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] px-1 bg-red-500 text-white text-[10px] font-black leading-[18px] rounded-full text-center shadow-md animate-pulse transition-all duration-300 hidden">
                                        0
                                    </span>
                                </button>

                                <!-- Chat Dropdown Panel (Exact match with Image 2 - Mobile Fixed & Responsive) -->
                                <div id="headerChatDropdown"
                                    class="hidden fixed md:absolute inset-x-4 md:inset-x-auto md:right-0 mt-2 md:w-[340px] max-w-[calc(100vw-2rem)] bg-white shadow-2xl rounded-2xl border border-gray-100 z-[9999] overflow-hidden transition-all duration-200">
                                    
                                    <!-- Dropdown Header: "Messages" -->
                                    <div class="px-4 py-3 border-b bg-gray-50/50 flex items-center justify-between">
                                        <span class="text-sm font-black text-gray-900 tracking-tight">Messages</span>
                                    </div>

                                    <!-- Segmented Tab Toggle: [ GC ] [ PM ] -->
                                    <div class="p-3 border-b bg-white flex justify-center">
                                        <div class="inline-flex rounded-lg border border-blue-500 p-0.5 bg-white text-xs font-bold shadow-sm">
                                            <button type="button" id="btnHeaderChatTabGC" onclick="switchHeaderChatTab('GC', event)"
                                                class="px-5 py-1.5 rounded-md text-white bg-blue-600 font-black transition-all shadow-sm">
                                                GC
                                            </button>
                                            <button type="button" id="btnHeaderChatTabPM" onclick="switchHeaderChatTab('PM', event)"
                                                class="px-5 py-1.5 rounded-md text-blue-600 hover:bg-blue-50 font-black transition-all">
                                                PM
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Chat List Items Container -->
                                    <div id="headerChatItems" class="max-h-80 overflow-y-auto divide-y divide-gray-50">
                                        <div class="p-4 text-center text-gray-400 text-xs flex items-center justify-center gap-2">
                                            <i data-lucide="loader-2" class="w-4 h-4 animate-spin text-blue-600"></i> Loading messages...
                                        </div>
                                    </div>

                                    <!-- Dropdown Footer: "View more messages >" -->
                                    <div class="p-3 bg-gray-50/50 border-t text-center">
                                        <button type="button" onclick="openFullStaffChat()"
                                            class="text-xs font-black text-yellow-600 hover:text-amber-700 inline-flex items-center gap-1 transition-colors hover:underline">
                                            View more messages <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Date/Time -->
                            <div class="text-right hidden md:block">
                                <p id="header-date" class="text-[13px] font-medium text-gray-900">{{ date('l, F j, Y') }}</p>
                                <p id="header-time" class="text-[11px] text-gray-500 transition-all duration-300">{{ date('h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                @hasSection('main-padding')
                    <div id="appContentArea" class="flex-1 overflow-y-auto overflow-x-hidden @yield('main-padding')">
                @else
                    <div id="appContentArea" class="flex-1 overflow-y-auto overflow-x-hidden p-4 pb-16 md:p-6 md:pb-0">
                @endif
                    {{-- Flash Messages --}}
                    @foreach(['success', 'error', 'warning', 'info'] as $type)
                        @if(session($type))
                            <div class="alert-slide mb-4 p-4 rounded-lg border
                                    @if($type === 'success') bg-green-50 border-green-200 text-green-800
                                    @elseif($type === 'error') bg-red-50 border-red-200 text-red-800
                                    @elseif($type === 'warning') bg-yellow-50 border-yellow-200 text-yellow-800
                                    @else bg-blue-50 border-blue-200 text-blue-800
                                    @endif">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="@if($type === 'success') check-circle @elseif($type === 'error') x-circle @elseif($type === 'warning') alert-triangle @else info @endif"
                                        class="w-5 h-5"></i>
                                    <span>{{ session($type) }}</span>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    {{-- Validation Errors --}}
                    @if($errors->any())
                        <div class="alert-slide mb-4 p-4 rounded-lg border bg-red-50 border-red-200 text-red-800">
                            <div class="flex items-center gap-2 mb-2">
                                <i data-lucide="x-circle" class="w-5 h-5"></i>
                                <span class="font-semibold">Please fix the following errors:</span>
                            </div>
                            <ul class="list-disc list-inside text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>

        {{-- Global Archive Deletion Security Modal --}}
        <div id="globalArchiveSecurityModal" class="fixed inset-0 z-[9999] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeGlobalArchiveSecurityModal()"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full p-6 border border-red-100">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-50 border-4 border-red-100 mb-4">
                            <i data-lucide="shield-alert" class="h-8 w-8 text-red-600"></i>
                        </div>
                        <h3 class="text-xl font-black text-red-900 mb-2">Security Verification</h3>
                        <p class="text-sm text-gray-500 mb-6">This action is irreversible. To permanently delete this record, please enter the **Archive Deletion Password**.</p>
                        
                        <div class="mb-6">
                            <input type="password" id="global-archive-pwd" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-red-500 focus:border-red-500 text-center text-lg tracking-widest outline-none transition-all" placeholder="••••••">
                        </div>

                        <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 flex gap-3 text-left mb-6">
                            <i data-lucide="alert-triangle" class="h-5 w-5 text-amber-600 flex-shrink-0 mt-0.5"></i>
                            <p class="text-[11px] text-amber-800 font-medium leading-relaxed">
                                Warning: Permanently deleting this item will remove it and all related data from the database forever. This cannot be undone.
                            </p>
                        </div>

                        <div class="flex gap-3">
                            <button type="button" onclick="closeGlobalArchiveSecurityModal()" class="flex-1 px-4 py-3 bg-white border border-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition-all">Cancel</button>
                            <button type="button" id="global-confirm-archive-delete" class="flex-1 px-4 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 shadow-lg shadow-red-200 transition-all">Confirm Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            let pendingDeleteForm = null;
            let pendingArchivePwdResolve = null;

            function closeGlobalArchiveSecurityModal() {
                const modal = document.getElementById('globalArchiveSecurityModal');
                if (modal) {
                    modal.classList.add('hidden');
                    modal.style.display = 'none';
                }
                const pwdInput = document.getElementById('global-archive-pwd');
                if (pwdInput) pwdInput.value = '';
                pendingDeleteForm = null;
                if (typeof pendingArchivePwdResolve === 'function') {
                    pendingArchivePwdResolve(null);
                }
                pendingArchivePwdResolve = null;
            }

            // Allow JS-driven destructive actions (fetch/AJAX) to reuse this modal.
            // Returns the password string, or null if cancelled.
            window.promptArchiveDeletionPassword = function () {
                return new Promise((resolve) => {
                    pendingArchivePwdResolve = resolve;
                    pendingDeleteForm = null; // ensure we are not in form-submit mode
                    const modal = document.getElementById('globalArchiveSecurityModal');
                    if (modal) {
                        modal.classList.remove('hidden');
                        modal.style.display = 'block';
                    }
                    if (window.lucide) window.lucide.createIcons();
                    setTimeout(() => document.getElementById('global-archive-pwd')?.focus(), 100);
                });
            };

            document.addEventListener('submit', function(e) {
                // Intercept forms that look like permanent deletes (force-delete only)
                const form = e.target;
                const action = form.getAttribute('action') || '';
                const method = form.querySelector('input[name="_method"]')?.value || form.getAttribute('method');

                // ONLY intercept permanent force-delete forms — not regular archive forms
                const isArchiveDelete = action.includes('force-delete') && 
                                        (method?.toUpperCase() === 'DELETE' || method?.toUpperCase() === 'POST');

                // Skip if it's already handled or not an archive delete
                if (!isArchiveDelete || form.dataset.verified === 'true') return;

                e.preventDefault();
                pendingDeleteForm = form;
                
                const modal = document.getElementById('globalArchiveSecurityModal');
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.style.display = 'block';
                }
                if (window.lucide) window.lucide.createIcons();
                setTimeout(() => document.getElementById('global-archive-pwd')?.focus(), 100);
            });

            document.getElementById('global-confirm-archive-delete').addEventListener('click', function() {
                const password = document.getElementById('global-archive-pwd').value;
                if (!password) { alert('Please enter the password.'); return; }

                if (pendingDeleteForm) {
                    // Add password as a hidden input to the form
                    let pwdInput = pendingDeleteForm.querySelector('input[name="archive_password"]');
                    if (!pwdInput) {
                        pwdInput = document.createElement('input');
                        pwdInput.type = 'hidden';
                        pwdInput.name = 'archive_password';
                        pendingDeleteForm.appendChild(pwdInput);
                    }
                    pwdInput.value = password;
                    pendingDeleteForm.dataset.verified = 'true';
                    pendingDeleteForm.submit();
                }
                // If opened programmatically (fetch/AJAX), resolve instead of submitting a form.
                if (!pendingDeleteForm && typeof pendingArchivePwdResolve === 'function') {
                    const resolve = pendingArchivePwdResolve;
                    pendingArchivePwdResolve = null;
                    closeGlobalArchiveSecurityModal();
                    resolve(password);
                    return;
                }
                closeGlobalArchiveSecurityModal();
            });

            // Toggle Mobile Sidebar
            window.toggleMobileSidebar = function() {
                const sidebar = document.getElementById('appSidebar');
                const backdrop = document.getElementById('sidebarBackdrop');
                if (sidebar && backdrop) {
                    sidebar.classList.toggle('show');
                    backdrop.classList.toggle('show');
                }
            };

            // Mobile & Touch Sidebar Dropdown Accordion Handler (Standard 1-tap Accordion Expansion)
            const dropdownContainers = document.querySelectorAll('.sidebar-dropdown-container');
            dropdownContainers.forEach(container => {
                const link = container.querySelector('.sidebar-has-dropdown');
                const menu = container.querySelector('.sidebar-dropdown-menu');
                const chevron = container.querySelector('.sidebar-chevron');

                if (link && menu) {
                    link.addEventListener('click', function(e) {
                        const isMobileView = window.innerWidth < 1024 || ('ontouchstart' in window);

                        if (isMobileView) {
                            e.preventDefault();
                            const isMenuHidden = menu.classList.contains('hidden') || getComputedStyle(menu).display === 'none';

                            if (isMenuHidden) {
                                // Close other open sidebar dropdowns for clean accordion behavior
                                dropdownContainers.forEach(c => {
                                    const m = c.querySelector('.sidebar-dropdown-menu');
                                    const ch = c.querySelector('.sidebar-chevron');
                                    if (m && m !== menu && !m.classList.contains('active-route-menu')) {
                                        m.classList.add('hidden');
                                        m.style.setProperty('display', 'none', 'important');
                                    }
                                    if (ch && ch !== chevron) {
                                        ch.classList.remove('rotate-180');
                                    }
                                });

                                // Expand target dropdown menu smoothly
                                menu.classList.remove('hidden');
                                menu.style.setProperty('display', 'block', 'important');
                                if (chevron) chevron.classList.add('rotate-180');
                            } else {
                                // Collapse dropdown menu
                                menu.classList.add('hidden');
                                menu.style.setProperty('display', 'none', 'important');
                                if (chevron) chevron.classList.remove('rotate-180');
                            }
                        }
                    });
                }
            });
        </script>

        {{-- Mobile Bottom Navigation Bar (visible on mobile only, hidden on md+) --}}
        <nav id="mobileBottomNav" class="fixed bottom-0 left-0 right-0 z-[1060] bg-white border-t border-gray-200 shadow-lg items-stretch" style="display:none; padding-bottom: env(safe-area-inset-bottom);">
            @auth
            @if(auth()->user()->hasAccessTo('dashboard'))
            <a href="{{ route('dashboard') }}" class="flex-1 flex flex-col items-center justify-center py-2 gap-0.5 {{ request()->routeIs('dashboard') ? 'text-yellow-600' : 'text-gray-500' }} hover:text-yellow-600 transition-colors">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span class="text-[9px] font-bold uppercase tracking-wider">Home</span>
            </a>
            @endif
            @if(auth()->user()->hasAccessTo('boundaries.*'))
            <a href="{{ route('boundaries.index') }}" class="flex-1 flex flex-col items-center justify-center py-2 gap-0.5 {{ request()->routeIs('boundaries.*') ? 'text-yellow-600' : 'text-gray-500' }} hover:text-yellow-600 transition-colors">
                <i data-lucide="wallet" class="w-5 h-5"></i>
                <span class="text-[9px] font-bold uppercase tracking-wider">Boundary</span>
            </a>
            @endif
            @if(auth()->user()->hasAccessTo('live-tracking.*'))
            <a href="{{ route('live-tracking.index') }}" onclick="window.location.href='{{ route('live-tracking.index') }}'" class="flex-1 flex flex-col items-center justify-end pb-2 group relative z-[1070] cursor-pointer">
                {{-- Seamless White Dome Arch --}}
                <div onclick="event.stopPropagation(); window.location.href='{{ route('live-tracking.index') }}'" class="absolute -top-7 w-[60px] h-[60px] rounded-full bg-white shadow-md flex items-center justify-center pointer-events-auto cursor-pointer z-[1075]">
                    {{-- Yellow Elevated Action Button --}}
                    <div class="w-[46px] h-[46px] rounded-full bg-gradient-to-tr from-yellow-500 via-amber-500 to-yellow-400 text-white flex items-center justify-center shadow-md shadow-amber-500/40 transition-all duration-200 group-hover:scale-110 active:scale-95 z-[1080] {{ request()->routeIs('live-tracking.*') ? 'ring-2 ring-yellow-400 scale-105' : '' }}">
                        <i data-lucide="map-pin" class="w-5.5 h-5.5 stroke-[2.5] pointer-events-none"></i>
                    </div>
                </div>
                <span class="text-[9px] font-bold uppercase tracking-wider {{ request()->routeIs('live-tracking.*') ? 'text-yellow-600 font-extrabold' : 'text-gray-500' }} group-hover:text-yellow-600 pointer-events-none leading-none">Tracking</span>
            </a>
            @endif
            @if(auth()->user()->hasAccessTo('analytics.*'))
            <a href="{{ route('analytics.index') }}" class="flex-1 flex flex-col items-center justify-center py-2 gap-0.5 {{ request()->routeIs('analytics.*') ? 'text-yellow-600' : 'text-gray-500' }} hover:text-yellow-600 transition-colors">
                <i data-lucide="bar-chart" class="w-5 h-5"></i>
                <span class="text-[9px] font-bold uppercase tracking-wider">Analytics</span>
            </a>
            @endif
            <button onclick="chatToggleDrawer()" class="flex-1 flex flex-col items-center justify-center py-2 gap-0.5 text-gray-500 hover:text-yellow-600 transition-colors relative">
                <div class="relative flex items-center justify-center">
                    <i data-lucide="message-circle" class="w-5 h-5"></i>
                    <span id="mobileChatUnreadBadge" class="absolute -top-1 -right-2.5 min-w-[16px] h-4 px-1 bg-red-500 text-white text-[9px] font-black leading-4 rounded-full text-center hidden animate-pulse">0</span>
                </div>
                <span class="text-[9px] font-bold uppercase tracking-wider">Staff Chat</span>
            </button>
            @endauth
        </nav>

        {{-- JS: Show mobile bottom nav only on real mobile screens (<768px) --}}
        <script>
            (function () {
                var nav = document.getElementById('mobileBottomNav');
                if (!nav) return;
                function applyMobileNav() {
                    if (window.innerWidth < 768) {
                        nav.style.display = 'flex';
                    } else {
                        nav.style.display = 'none';
                    }
                }
                applyMobileNav();
                window.addEventListener('resize', applyMobileNav);
            })();
        </script>

    @else
        <!-- Login/Signup Layout -->
        <div class="min-h-screen bg-gradient-to-br from-yellow-50 to-orange-50 flex items-center justify-center p-4">
            <div class="w-full max-w-md">
                @yield('content')
            </div>
        </div>
    @endauth

    <!-- Initialize Lucide icons (page content + bfcache restore) -->
    <script>
        lucide.createIcons();
        window.addEventListener('pageshow', function(e) {
            if (e.persisted) { lucide.createIcons(); }
        });
    </script>

    <!-- Common JavaScript -->
    <script>
        // makeRequest — global AJAX helper used across all pages
        async function makeRequest(url, options = {}) {
            const showLoader = options.showLoader;
            if (showLoader && typeof window.showGlobalLoader === 'function') {
                window.showGlobalLoader(typeof showLoader === 'string' ? showLoader : 'Loading...');
            }
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
            } finally {
                if (showLoader && typeof window.hideGlobalLoader === 'function') {
                    window.hideGlobalLoader();
                }
            }
        }

        // Header clock — updates every second
        function updateHeaderClock() {
            const now = new Date();
            const dateEl = document.getElementById('header-date');
            const timeEl = document.getElementById('header-time');
            if (dateEl && timeEl) {
                const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                dateEl.textContent = now.toLocaleDateString('en-US', dateOptions);
                const timeOptions = { hour: '2-digit', minute: '2-digit', hour12: true };
                timeEl.textContent = now.toLocaleTimeString('en-US', timeOptions);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Globally inject Laravel User ID for native android background services
            window.LaravelUserId = "{{ Auth::id() }}";

            // Re-initialize Lucide icons
            if (window.lucide && window.lucide.createIcons) {
                window.lucide.createIcons();
            }
            // Start header clock
            updateHeaderClock();
            setInterval(updateHeaderClock, 1000);

            // Diagnostic reporter for remote mobile debugging
            async function reportDiag(message, data = {}) {
                try {
                    await fetch('/api/diagnose-capacitor', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify({ message: message, data: data, user_id: "{{ Auth::id() }}" })
                    });
                } catch (e) {
                    console.error('Diag log failed:', e);
                }
            }

            // Capacitor Native Push Notification Bridge for Hybrid App with Retry Logic
            function tryInitCapacitorPush(retries = 0) {
                const hasCapacitor = typeof window.Capacitor !== 'undefined';
                const hasPlugins = hasCapacitor && !!window.Capacitor.Plugins;
                const hasPush = hasPlugins && !!window.Capacitor.Plugins.PushNotifications;
                
                if (retries === 0 || retries === 5 || retries === 10 || retries === 14) {
                    reportDiag("tryInitCapacitorPush status check", { 
                        retries: retries, 
                        hasCapacitor: hasCapacitor, 
                        hasPlugins: hasPlugins, 
                        hasPush: hasPush,
                        href: window.location.href,
                        user_id: "{{ Auth::id() }}"
                    });
                }

                if (hasPush) {
                    console.log('Capacitor Native Platform and PushNotifications plugin detected! Initializing bridge...');
                    reportDiag("Capacitor Push found, initializing bridge", { user_id: "{{ Auth::id() }}" });
                    const PushNotifications = window.Capacitor.Plugins.PushNotifications;
                    const currentUserId = "{{ Auth::id() }}";

                    async function syncTokenWithBackend(token) {
                        try {
                            reportDiag("Syncing token with backend starting...", { token: token });
                            const res = await makeRequest('/web-notifications/save-token', {
                                method: 'POST',
                                body: JSON.stringify({ token: token })
                            });
                            reportDiag("Sync token backend response", { response: res });
                            if (res && res.success) {
                                console.log('FCM Device Token successfully synced with backend!');
                                localStorage.setItem('fcm_token_synced', 'true');
                                if (currentUserId) {
                                    localStorage.setItem('fcm_token_user_id', currentUserId);
                                }
                                window.dispatchEvent(new CustomEvent('fcm_token_synced_event', { detail: { token: token } }));
                            }
                        } catch (e) {
                            console.error('Failed to sync hybrid FCM token with backend:', e);
                            reportDiag("Sync token backend error", { error: e.message });
                        }
                    }
                    
                    async function initNativePush() {
                        try {
                            // Check if we have a cached token in localStorage that needs syncing
                            const savedToken = localStorage.getItem('fcm_token');
                            reportDiag("initNativePush starting", { cached_token: savedToken, currentUserId: currentUserId });
                            
                            if (savedToken && currentUserId) {
                                const lastSyncedUser = localStorage.getItem('fcm_token_user_id');
                                const isSynced = localStorage.getItem('fcm_token_synced') === 'true';
                                reportDiag("Checking cached token sync requirements", { lastSyncedUser: lastSyncedUser, isSynced: isSynced });
                                if (!isSynced || lastSyncedUser !== currentUserId) {
                                    console.log('Cached FCM token found and needs sync. Syncing now...');
                                    await syncTokenWithBackend(savedToken);
                                }
                            }

                            let permStatus = await PushNotifications.checkPermissions();
                            reportDiag("Initial permission status", { permStatus: permStatus });
                            
                            if (permStatus.receive === 'prompt') {
                                reportDiag("Requesting push permissions...");
                                permStatus = await PushNotifications.requestPermissions();
                                reportDiag("After request permission status", { permStatus: permStatus });
                            }
                            if (permStatus.receive === 'granted') {
                                // Check if MainActivity injected the token early via continuous window injection
                                const checkNativeBridge = async () => {
                                    if (window.AndroidNativeError) {
                                        reportDiag("Native Token Error in Bridge", { error: window.AndroidNativeError });
                                        return true; // Stop checking
                                    }
                                    if (window.AndroidNativeToken && window.AndroidNativeToken !== 'null') {
                                        const earlyNativeToken = window.AndroidNativeToken;
                                        console.log('Hybrid FCM Device Token pulled directly from AndroidNativeToken:', earlyNativeToken);
                                        reportDiag("Native Token via AndroidNativeToken", { token: earlyNativeToken });
                                        const lastToken = localStorage.getItem('fcm_token');
                                        if (lastToken !== earlyNativeToken) {
                                            localStorage.setItem('fcm_token', earlyNativeToken);
                                            localStorage.setItem('fcm_token_synced', 'false');
                                        }
                                        await syncTokenWithBackend(earlyNativeToken);
                                        return true;
                                    }
                                    return false;
                                };

                                const bridgeSuccess = await checkNativeBridge();
                                if (!bridgeSuccess) {
                                    // Retry checking the injected variable forever until found
                                    let attempts = 0;
                                    const interval = setInterval(async () => {
                                        attempts++;
                                        const success = await checkNativeBridge();
                                        if (success || attempts > 30) { // Stop after 30 seconds
                                            clearInterval(interval);
                                            if (!success) reportDiag("Bridge timeout", { reason: "Neither token nor error injected" });
                                        }
                                    }, 1000);
                                }

                                // Custom listener for our bypassed Native Token Injector in MainActivity.java!
                                window.addEventListener('native_fcm_token_ready', async (e) => {
                                    const tokenVal = e.detail.token;
                                    console.log('Hybrid FCM Device Token natively injected:', tokenVal);
                                    reportDiag("Native injection event fired", { token: tokenVal });
                                    const lastToken = localStorage.getItem('fcm_token');
                                    if (lastToken !== tokenVal) {
                                        localStorage.setItem('fcm_token', tokenVal);
                                        localStorage.setItem('fcm_token_synced', 'false');
                                    }
                                    await syncTokenWithBackend(tokenVal);
                                });

                                // Capacitor's listeners (may drop events on server.url)
                                await PushNotifications.addListener('registration', async (token) => {
                                    console.log('Capacitor Listener: Hybrid FCM Device Token retrieved:', token.value);
                                    reportDiag("Capacitor registration event fired", { token: token.value });
                                    const lastToken = localStorage.getItem('fcm_token');
                                    if (lastToken !== token.value) {
                                        localStorage.setItem('fcm_token', token.value);
                                        localStorage.setItem('fcm_token_synced', 'false');
                                    }
                                    await syncTokenWithBackend(token.value);
                                });
                                
                                await PushNotifications.addListener('registrationError', (error) => {
                                    console.error('Hybrid FCM Registration Error:', error);
                                    reportDiag("Native registrationError event fired", { error: error });
                                });

                                // Trigger the native registration process after listeners are ready
                                reportDiag("Calling PushNotifications.register()...");
                                await PushNotifications.register();
                                reportDiag("PushNotifications.register() completed!");
                            } else {
                                reportDiag("Push permissions not granted", { final_status: permStatus.receive });
                            }
                        } catch (err) {
                            console.error('Error in hybrid native push initialization:', err);
                            reportDiag("initNativePush fatal error catch", { error: err.message });
                        }
                    }
                    
                    initNativePush();
                } else if (retries < 15) {
                    console.log(`Capacitor plugins not fully loaded yet (Attempt ${retries + 1}/15)... Retrying in 150ms...`);
                    setTimeout(() => tryInitCapacitorPush(retries + 1), 150);
                } else {
                    console.log('Capacitor or PushNotifications plugin not found. Running in browser or native plugins disabled.');
                    reportDiag("tryInitCapacitorPush timed out - Capacitor not detected");
                }
            }

            tryInitCapacitorPush();

            // Restore Read States
            let readNotifs = {};
            try {
                readNotifs = JSON.parse(localStorage.getItem('read_notifs') || '{}');
            } catch (e) {
                readNotifs = {};
                localStorage.removeItem('read_notifs');
            }
            
            // Migrate legacy array to object format
            if (Array.isArray(readNotifs)) {
                readNotifs = {};
                localStorage.setItem('read_notifs', JSON.stringify(readNotifs));
            }

            const nowMs = Date.now();
            let needsCleanup = false;

            Object.keys(readNotifs).forEach(id => {
                if (nowMs - readNotifs[id] < 2592000000) { // Still within 30 days
                    const el = document.getElementById('notif-' + id);
                    if (el) {
                        el.style.display = 'none';
                        el.classList.remove('unread-notif');
                    }
                } else {
                    delete readNotifs[id]; // Expired, remove it
                    needsCleanup = true;
                }
            });
            
            // Self-heal and cleanup expired cookies
            if (needsCleanup || Object.keys(readNotifs).length > 0) {
                localStorage.setItem('read_notifs', JSON.stringify(readNotifs));
                document.cookie = "read_notifs=" + encodeURIComponent(JSON.stringify(readNotifs)) + "; path=/; max-age=" + (30 * 24 * 60 * 60);
            }

            // Update badge counts after restoring states
            if (typeof updateNotificationCount === 'function') {
                updateNotificationCount();
            }
        });

        function filterNotifs(type) {
            const items = document.querySelectorAll('.notification-item');
            const btnSystem = document.getElementById('btn-filter-system');
            const btnParts = document.getElementById('btn-filter-parts');

            if (type === 'system') {
                items.forEach(i => {
                    if (i.dataset.type !== 'low_stock') i.classList.remove('hidden');
                    else i.classList.add('hidden');
                });
                btnSystem.classList.add('border-b-2', 'border-yellow-500', 'text-yellow-600');
                btnSystem.classList.remove('text-gray-400');
                btnParts.classList.remove('border-b-2', 'border-yellow-500', 'text-yellow-600');
                btnParts.classList.add('text-gray-400');
            } else {
                items.forEach(i => {
                    if (i.dataset.type === type) i.classList.remove('hidden');
                    else i.classList.add('hidden');
                });
                btnParts.classList.add('border-b-2', 'border-yellow-500', 'text-yellow-600');
                btnParts.classList.remove('text-gray-400');
                btnSystem.classList.remove('border-b-2', 'border-yellow-500', 'text-yellow-600');
                btnSystem.classList.add('text-gray-400');
            }
        }

        function markAsRead(id) {
            id = String(id);
            let readNotifs = {};
            try {
                readNotifs = JSON.parse(localStorage.getItem('read_notifs') || '{}');
            } catch (e) {
                readNotifs = {};
            }
            if (Array.isArray(readNotifs)) readNotifs = {};

            readNotifs[id] = Date.now();
            
            // Cleanup expired entries
            const now = Date.now();
            for (const key in readNotifs) {
                if (now - readNotifs[key] >= 2592000000) {
                    delete readNotifs[key];
                }
            }

            localStorage.setItem('read_notifs', JSON.stringify(readNotifs));
            // Set cookie for PHP awareness (30 days)
            document.cookie = "read_notifs=" + encodeURIComponent(JSON.stringify(readNotifs)) + "; path=/; max-age=" + (30 * 24 * 60 * 60);
            
            const el = document.getElementById('notif-' + id);
            if (el) {
                el.style.display = 'none';
                el.classList.remove('unread-notif');
                // Decrement badge count
                if (typeof updateNotificationCount === 'function') {
                    updateNotificationCount();
                }
            }

            // PERMANENT FIX: Tell the backend to resolve this notification so it never returns!
            fetch('/notifications/dismiss', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: 'id=' + encodeURIComponent(id)
            }).catch(err => console.error('Failed to mark as read in DB:', err));
        }

        function markAllAsRead() {
            const items = document.querySelectorAll('.notification-item');
            let readNotifs = {};
            try {
                readNotifs = JSON.parse(localStorage.getItem('read_notifs') || '{}');
            } catch (e) {
                readNotifs = {};
            }
            if (Array.isArray(readNotifs)) readNotifs = {};
            
            const now = Date.now();
            
            items.forEach(item => {
                // Do not bulk mark 'low_stock' / important items as read
                if (item.dataset.type === 'low_stock') {
                    return;
                }

                const id = String(item.dataset.notifId);
                if (id) {
                    readNotifs[id] = now;
                }
                item.style.display = 'none';
                item.classList.remove('unread-notif');
            });

            // Cleanup expired entries
            for (const key in readNotifs) {
                if (now - readNotifs[key] >= 2592000000) { // 30 days
                    delete readNotifs[key];
                }
            }
            
            localStorage.setItem('read_notifs', JSON.stringify(readNotifs));
            // Set cookie for PHP awareness (30 days)
            document.cookie = "read_notifs=" + encodeURIComponent(JSON.stringify(readNotifs)) + "; path=/; max-age=" + (30 * 24 * 60 * 60);
            
            // Zero out badge counts
            if (typeof updateNotificationCount === 'function') {
                updateNotificationCount();
            }

            // PERMANENT FIX: Tell the backend to resolve all these notifications so they never return!
            fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }
            }).catch(err => console.error('Failed to mark all as read in DB:', err));
        }

        function updateNotificationCount() {
            const items = document.querySelectorAll('.notification-item');
            let systemCount = 0;
            let partsCount = 0;

            items.forEach(item => {
                // An item is unread if it doesn't have the background removed or is still marked unread
                if (item.classList.contains('unread-notif')) {
                    if (item.dataset.type === 'low_stock') partsCount++;
                    else systemCount++;
                }
            });

            const total = systemCount + partsCount;

            // Update Main Bell Badge
            const mainBadge = document.getElementById('main-nav-notif-badge');
            if (mainBadge) {
                mainBadge.textContent = total;
                mainBadge.classList.toggle('hidden', total === 0);
            }

            // Update Dropdown Subtitle
            const subtitle = document.getElementById('notif-dropdown-subtitle');
            if (subtitle) {
                subtitle.textContent = `${total} item(s)`;
            }

            // Update Filter Tab Badges
            const systemBadge = document.getElementById('badge-filter-system');
            if (systemBadge) {
                systemBadge.textContent = systemCount;
                systemBadge.classList.toggle('hidden', systemCount === 0);
            }

            const partsBadge = document.getElementById('badge-filter-parts');
            if (partsBadge) {
                partsBadge.textContent = partsCount;
                partsBadge.classList.toggle('hidden', partsCount === 0);
            }
        }

        // Premium Web Audio API double chime synthesizer (Ding-Dong!)
        // 100% network-independent, CORS-safe, and offline-compatible.
        function playNotificationChime() {
            try {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                if (!AudioContext) return;
                const ctx = new AudioContext();
                
                // Note 1 (Ding! - D5)
                let osc1 = ctx.createOscillator();
                let gain1 = ctx.createGain();
                osc1.connect(gain1);
                gain1.connect(ctx.destination);
                osc1.frequency.value = 587.33; 
                gain1.gain.setValueAtTime(0.3, ctx.currentTime);
                gain1.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.4);
                osc1.start(ctx.currentTime);
                osc1.stop(ctx.currentTime + 0.4);
                
                // Note 2 (Dong! - A5)
                setTimeout(() => {
                    let osc2 = ctx.createOscillator();
                    let gain2 = ctx.createGain();
                    osc2.connect(gain2);
                    gain2.connect(ctx.destination);
                    osc2.frequency.value = 880.00; 
                    gain2.gain.setValueAtTime(0.3, ctx.currentTime);
                    gain2.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.6);
                    osc2.start(ctx.currentTime);
                    osc2.stop(ctx.currentTime + 0.6);
                }, 120);
            } catch (e) {
                console.error("Failed to play synthesized chime:", e);
            }
        }

        // Stunning Glassmorphism Slide-Down Notification Banner
        // Renders instantly inside the WebView, feeling 100% native.
        function showInAppNotificationBanner(title, message, url) {
            const existing = document.getElementById('in-app-notif-banner');
            if (existing) existing.remove();
            
            const banner = document.createElement('div');
            banner.id = 'in-app-notif-banner';
            // Styling uses a beautiful, sleek, modern design with animations
            banner.className = 'fixed top-4 left-4 right-4 z-[99999] bg-white/95 backdrop-blur-md rounded-2xl border border-yellow-200 shadow-2xl p-4 flex gap-4 transition-all duration-500 transform -translate-y-40 opacity-0 pointer-events-auto cursor-pointer max-w-md mx-auto';
            banner.innerHTML = `
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-tr from-yellow-400 to-amber-500 flex items-center justify-center text-white shadow-lg shadow-yellow-100 animate-pulse">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bell"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-[10px] font-black text-yellow-600 tracking-wider uppercase">System Alert</h4>
                    <p class="text-xs font-bold text-gray-900 mt-0.5 truncate">${title}</p>
                    <p class="text-[11px] text-gray-500 mt-0.5 line-clamp-2 leading-normal">${message}</p>
                </div>
                <button type="button" class="flex-shrink-0 text-gray-400 hover:text-gray-600 self-start" onclick="event.stopPropagation(); this.parentElement.remove();">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            `;
            
            banner.onclick = () => {
                if (url && url !== '#') {
                    window.location.href = url;
                } else {
                    banner.classList.add('-translate-y-40', 'opacity-0');
                    setTimeout(() => banner.remove(), 500);
                }
            };
            
            document.body.appendChild(banner);
            
            setTimeout(() => {
                banner.classList.remove('-translate-y-40', 'opacity-0');
            }, 50);
            
            setTimeout(() => {
                if (banner.parentNode) {
                    banner.classList.add('-translate-y-40', 'opacity-0');
                    setTimeout(() => {
                        if (banner.parentNode) banner.remove();
                    }, 500);
                }
            }, 6000);
        }

        // Real-Time Notification Polling & UI Sync (Lightweight background tasks)
        let pollInterval = null;

        function updateNotificationUI(data) {
            // BULLETPROOF FIX: Check local storage for read notifications and filter the server response
            // This prevents flickering if the browser drops the 'read_notifs' cookie because it got too big.
            let readNotifsObj = {};
            try {
                readNotifsObj = JSON.parse(localStorage.getItem('read_notifs')) || {};
            } catch(e) {}
            const readNotifIds = Object.keys(readNotifsObj);

            if (data && data.notifications) {
                // Filter out notifications that the frontend already knows are read
                data.notifications = data.notifications.filter(n => !readNotifIds.includes(String(n.id)));
                
                // Recalculate totals
                data.total = data.notifications.length;
                data.parts_count = data.notifications.filter(n => n.type === 'low_stock').length;
                data.system_count = data.total - data.parts_count;
            }

            // Track new notification IDs to play chime and show in-app banner
            if (data && data.notifications) {
                if (!window.notifiedIds) {
                    let stored = [];
                    try {
                        stored = JSON.parse(localStorage.getItem('notified_ids')) || [];
                    } catch(e) {}
                    if (!Array.isArray(stored)) stored = [];
                    
                    // Initialize with existing notifications to avoid spam on first load
                    data.notifications.forEach(n => {
                        const idStr = String(n.id);
                        if (n.type === 'test_chime_alert') {
                            // CRITICAL: DO NOT suppress test chime broadcasts on first load!
                            // Trigger sound and banner instantly!
                            if (!stored.includes(idStr)) {
                                stored.push(idStr);
                                playNotificationChime();
                                showInAppNotificationBanner(n.title, n.message, n.url);
                            }
                        } else {
                            if (!stored.includes(idStr)) {
                                stored.push(idStr);
                            }
                        }
                    });
                    window.notifiedIds = stored;
                    localStorage.setItem('notified_ids', JSON.stringify(stored));
                } else {
                    data.notifications.forEach(n => {
                        const idStr = String(n.id);
                        if (!window.notifiedIds.includes(idStr)) {
                            window.notifiedIds.push(idStr);
                            localStorage.setItem('notified_ids', JSON.stringify(window.notifiedIds));
                            
                            // Play custom double-tone and display gorgeous banner!
                            playNotificationChime();
                            showInAppNotificationBanner(n.title, n.message, n.url);
                        }
                    });
                }
            }

            const total = data.total;
            const mainBadge = document.getElementById('main-nav-notif-badge');
            if (mainBadge) {
                mainBadge.textContent = total;
                mainBadge.classList.toggle('hidden', total === 0);
            }

            const subtitle = document.getElementById('notif-dropdown-subtitle');
            if (subtitle) {
                subtitle.textContent = `${total} item(s)`;
            }

            const systemBadge = document.getElementById('badge-filter-system');
            if (systemBadge) {
                systemBadge.textContent = data.system_count;
                systemBadge.classList.toggle('hidden', data.system_count === 0);
            }

            const partsBadge = document.getElementById('badge-filter-parts');
            if (partsBadge) {
                partsBadge.textContent = data.parts_count;
                partsBadge.classList.toggle('hidden', data.parts_count === 0);
            }

            const btnParts = document.getElementById('btn-filter-parts');
            const isPartsSelected = btnParts && btnParts.classList.contains('text-yellow-600');

            const listContainer = document.getElementById('notificationList');
            if (listContainer) {
                if (data.notifications.length === 0) {
                    listContainer.innerHTML = '<div class="px-4 py-4 text-sm text-gray-500 text-center">No notifications.</div>';
                } else {
                    let html = '';
                    data.notifications.forEach(n => {
                        const isHidden = (n.type === 'low_stock') ? !isPartsSelected : isPartsSelected;
                        let icon = 'alert-circle';
                        let iconClass = 'text-red-600';
                        
                        if (n.type === 'case_expiry') {
                            icon = 'file-warning';
                            iconClass = 'text-yellow-600';
                        } else if (n.type === 'coding_today') {
                            icon = 'car-front';
                            iconClass = 'text-blue-600';
                        } else if (n.type === 'violation_alert') {
                            icon = 'shield-alert';
                            iconClass = 'text-red-600';
                        } else if (n.type === 'low_stock') {
                            icon = 'package-search';
                            iconClass = 'text-orange-500';
                        } else if (n.type === 'license_expiry') {
                            icon = 'id-card';
                            iconClass = 'text-rose-500';
                        } else if (n.type === 'odo_maint_due') {
                            icon = 'settings-2';
                            iconClass = 'text-orange-600';
                        }
                        
                        html += `
                            <div class="notification-item px-4 py-3 border-b last:border-b-0 hover:bg-gray-50 flex items-start gap-2 transition-all unread-notif ${isHidden ? 'hidden' : ''}"
                                 id="notif-${n.id}"
                                 data-type="${n.type}" 
                                 data-notif-id="${n.id}"
                                 style="background-color: #f0f9ff;">
                                <a href="${n.url || '#'}" class="flex-1 flex gap-3 min-w-0" onclick="markAsRead('${n.id}')">
                                    <div class="mt-0.5 flex-shrink-0">
                                        <i data-lucide="${icon}" class="w-4 h-4 ${iconClass}"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-semibold text-gray-800 truncate">${n.title}</p>
                                        <p class="text-xs text-gray-600 mt-0.5 line-clamp-2">${n.message}</p>
                                        ${n.time ? `<p class="text-[10px] text-gray-400 mt-1 font-medium">${n.time}</p>` : ''}
                                    </div>
                                </a>
                                <button type="button"
                                    class="ml-1 text-gray-400 hover:text-gray-600 flex-shrink-0"
                                    onclick="dismissNotification(this);">
                                    <span class="sr-only">Dismiss</span>
                                    <i data-lucide="x" class="w-3 h-3"></i>
                                </button>
                            </div>
                        `;
                    });
                    listContainer.innerHTML = html;
                    if (window.lucide) {
                        window.lucide.createIcons();
                    }
                }
            }
        }

        async function pollNotifications() {
            try {
                const res = await makeRequest('/web-notifications/poll');
                if (res && res.success) {
                    updateNotificationUI(res);
                }
            } catch (e) {
                console.error('Notification poll failed:', e);
            }
        }

        function startNotificationPolling() {
            if (pollInterval) clearInterval(pollInterval);
            pollInterval = setInterval(pollNotifications, 6000); // Snappy real-time polling every 6 seconds
        }

        async function triggerTestNotificationBroadcast() {
            const btn = document.getElementById('test-chime-broadcast-btn');
            if (btn) btn.disabled = true;
            try {
                const res = await makeRequest('/web-notifications/trigger-test-chime', { method: 'POST' });
                if (res && res.success) {
                    alert('📢 Chime Broadcast triggered! Check your Oppo phone screen/sound in the next 6 seconds!');
                } else {
                    alert('Failed to trigger broadcast: ' + (res.error || 'Unknown error'));
                }
            } catch (e) {
                console.error(e);
                alert('Broadcast request failed: ' + (e.message || e) + '\n\nTip: Kung may lumang Service Worker cache ang iyong desktop browser, mangyaring pindutin ang CTRL + F5 sa keyboard (o Cmd + Shift + R sa Mac) upang tuluyang ma-clear ang cache, at subukan muli.');
            } finally {
                if (btn) btn.disabled = false;
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            @auth
                startNotificationPolling();
            @endauth
        });
    </script>

    <!-- Structured Data (JSON-LD) -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "Euro Taxi System",
        "url": "https://www.eurotaxisystem.site",
        "logo": "https://www.eurotaxisystem.site/{{ asset('image/logo.png') }}",
        "description": "Professional taxi fleet management system in the Philippines with real-time tracking, driver management, and comprehensive business solutions.",
        "address": {
            "@type": "PostalAddress",
            "addressCountry": "PH",
            "addressRegion": "Philippines"
        },
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+63-XXX-XXXX-XXXX",
            "contactType": "customer service",
            "availableLanguage": ["English", "Filipino"]
        },
        "sameAs": [
            "https://www.eurotaxisystem.site"
        ]
    }
    </script>

    <!-- Service Worker disabled to prevent stale data caching on dashboard -->
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.getRegistrations().then(function(registrations) {
                for(let registration of registrations) {
                    registration.unregister();
                }
            });
        }
        // Initialize all Lucide icons after the entire DOM is parsed to prevent FOUC
        if(window.lucide) {
            window.lucide.createIcons();
        }
        
        // Client-Side Routing System - No Page Reloads
        document.addEventListener('DOMContentLoaded', function() {
            // Ensure Lucide icons are immediately visible
            if(window.lucide) {
                window.lucide.createIcons();
            }
            
            // Cache for loaded pages (disabled to prevent serving stale DOM/scripts)
            const pageCache = new Map();
            
            // Hover prefetching disabled to prevent database connection exhaustion on shared hosting
            
            // Fetch page content
            async function fetchPage(url, prefetch = false) {
                try {
                    const response = await fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'text/html'
                        }
                    });
                    
                    if (!response.ok) throw new Error('Network response was not ok');
                    
                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    // Extract main content
                    const mainContent = doc.querySelector('#appMainContent');
                    const pageTitle = doc.querySelector('title')?.textContent || '';
                    
                    const pageData = { mainContent, pageTitle, html };
                    
                    return pageData;
                } catch (error) {
                    console.error('Error fetching page:', error);
                    if (!prefetch) {
                        window.location.href = url; // Fallback to normal navigation
                    }
                }
            }
            
            // Update page content without reload
            async function navigateToPage(url) {
                try {
                    const pageData = await fetchPage(url);

                    if (pageData && pageData.mainContent) {
                        // Swap content ONLY after fetch is done — no blank screen, no fade
                        const mainContent = document.querySelector('#appMainContent');
                        mainContent.innerHTML = pageData.mainContent.innerHTML;

                        // Update page title
                        if (pageData.pageTitle) {
                            document.title = pageData.pageTitle;
                        }

                        // Update URL without reload
                        history.pushState({}, '', url);

                        // Re-initialize Lucide icons in new content
                        if (window.lucide) {
                            window.lucide.createIcons();
                        }

                        // Re-run inline scripts in true global context
                        const scripts = mainContent.querySelectorAll('script');
                        scripts.forEach(script => {
                            const newScript = document.createElement('script');
                            if (script.src) {
                                newScript.src = script.src;
                                document.head.appendChild(newScript);
                            } else if (script.textContent && script.textContent.trim()) {
                                newScript.textContent = script.textContent;
                                document.body.appendChild(newScript);
                                setTimeout(() => { try { newScript.remove(); } catch(e){} }, 50);
                            }
                        });

                        // Notify child pages they were loaded via AJAX
                        document.dispatchEvent(new CustomEvent('page:loaded', { detail: { url: url } }));
                    } else if (!pageData) {
                        // fetchPage already did window.location.href fallback, just return
                        return;
                    }
                } catch (error) {
                    console.error('Navigation error:', error);
                    window.location.href = url;
                } finally {
                    // Always clear loading states from the sidebar links
                    document.querySelectorAll('.nav-loading').forEach(el => el.classList.remove('nav-loading'));
                }
            }
            
            // Handle sidebar navigation
            document.querySelectorAll('.sidebar-item').forEach(link => {
                link.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    
                    // Skip external links, anchors, and if modifier keys are pressed
                    if (!href || href.startsWith('#') || href.startsWith('http') || e.ctrlKey || e.metaKey || e.shiftKey) {
                        return;
                    }
                    
                    e.preventDefault();
                    
                    // Add loading state
                    this.classList.add('nav-loading');

                    // Smoothly close mobile sidebar if active
                    const sidebar = document.getElementById('appSidebar');
                    const backdrop = document.getElementById('sidebarBackdrop');
                    if (sidebar) sidebar.classList.remove('show');
                    if (backdrop) backdrop.classList.remove('show');
                    
                    // Navigate without page reload
                    navigateToPage(href);
                });
            });
            
            // Handle browser back/forward
            window.addEventListener('popstate', function(e) {
                if (e.state !== null) {
                    navigateToPage(window.location.href);
                }
            });
            
            // Initialize history state
            history.replaceState({}, '', window.location.href);
        });
    </script>
    @stack('scripts')

    <!-- Beautiful Global Web-Based Pull-To-Refresh Loader for Mobile/Android WebView -->
    <div id="globalPullToRefreshIndicator" class="fixed left-0 right-0 flex items-center justify-center pointer-events-none z-[9999] transition-transform duration-100 ease-out" style="top: -60px; transform: translateY(0px); opacity: 0;">
        <div class="bg-white border border-gray-100 rounded-full p-2.5 shadow-xl flex items-center justify-center">
            <!-- SVG Spinning loader -->
            <div id="globalPullToRefreshIcon" class="transition-transform duration-100">
                <svg id="globalPullArrow" class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 13l-7 7-7-7m14-6l-7 7-7-7"></path>
                </svg>
                <svg id="globalPullSpinner" class="w-5 h-5 text-amber-500 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>
    </div>

    <script>
        (function() {
            // Only enable pull-to-refresh on mobile devices or Capacitor instances
            const isMobileDevice = window.innerWidth <= 1024 || 
                                   navigator.userAgent.includes('Capacitor') || 
                                   navigator.userAgent.includes('Android');
            if (!isMobileDevice) return;

            let startY = 0;
            let pullDistance = 0;
            let isDragging = false;
            let isRefreshing = false;
            const threshold = 90; // Pull down at least 90px to refresh
            const maxPull = 140; // Max visual translation limit

            const indicator = document.getElementById('globalPullToRefreshIndicator');
            const arrow = document.getElementById('globalPullArrow');
            const spinner = document.getElementById('globalPullSpinner');
            const iconContainer = document.getElementById('globalPullToRefreshIcon');

            if (!indicator || !arrow || !spinner) return;

            window.addEventListener('touchstart', function(e) {
                // Only trigger pull if we are at the very top of scrollable container
                if (window.scrollY === 0 && !isRefreshing) {
                    startY = e.touches[0].pageY;
                    isDragging = true;
                    
                    // Prepare indicator initial state
                    indicator.style.opacity = '0';
                    indicator.style.transform = 'translateY(0px)';
                    arrow.classList.remove('hidden');
                    spinner.classList.add('hidden');
                }
            }, { passive: true });

            window.addEventListener('touchmove', function(e) {
                if (!isDragging || isRefreshing) return;

                const currentY = e.touches[0].pageY;
                const diff = currentY - startY;

                if (diff > 0) {
                    // Apply touch friction damping
                    const resistance = 0.35;
                    pullDistance = Math.min(diff * resistance, maxPull);

                    // Prevent default browser refresh gestures
                    if (e.cancelable && pullDistance > 10) {
                        e.preventDefault();
                    }

                    // Animate indicator sliding down
                    indicator.style.opacity = Math.min(pullDistance / 50, 1).toString();
                    indicator.style.transform = `translateY(${pullDistance}px)`;

                    // Rotate the arrow down as they drag
                    const rotation = Math.min((pullDistance / threshold) * 180, 180);
                    iconContainer.style.transform = `rotate(${rotation}deg)`;
                } else {
                    isDragging = false;
                    pullDistance = 0;
                    indicator.style.opacity = '0';
                    indicator.style.transform = 'translateY(0px)';
                }
            }, { passive: false });

            window.addEventListener('touchend', function() {
                if (!isDragging || isRefreshing) return;
                isDragging = false;

                if (pullDistance >= threshold) {
                    isRefreshing = true;
                    pullDistance = threshold;

                    // Lock indicator position
                    indicator.style.transform = `translateY(${pullDistance}px)`;
                    
                    // Switch icons
                    arrow.classList.add('hidden');
                    spinner.classList.remove('hidden');
                    iconContainer.style.transform = 'rotate(0deg)';

                    // Trigger actual reload after 600ms delay for high fidelity feedback
                    setTimeout(function() {
                        window.location.reload();
                    }, 600);
                } else {
                    // Smoothly reset
                    pullDistance = 0;
                    indicator.style.opacity = '0';
                    indicator.style.transform = 'translateY(0px)';
                }
            });
        })();

        // --- GLOBAL SUPPORT CHAT NOTIFICATION CENTER ---
        (function() {
            const isSupportPage = @json(request()->routeIs('support.*'));
            const notifSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2358/2358-preview.mp3');
            let originalTitle = document.title;
            let lastNotifiedTotal = parseInt(localStorage.getItem('last_support_notif_total') || '0');

            function flashTitle(text) {
                let count = 0;
                const interval = setInterval(() => {
                    document.title = (count % 2 === 0) ? text : originalTitle;
                    if (++count >= 10) {
                        clearInterval(interval);
                        document.title = originalTitle;
                    }
                }, 500);
            }

            async function checkGlobalChatStatus() {
                try {
                    const response = await fetch('/support-center/status');
                    const data = await response.json();
                    if (data.success) {
                        let currentTotal = 0;
                        data.drivers.forEach(d => {
                            currentTotal += parseInt(d.unread_count || 0);
                        });

                        const sharedLastTotal = parseInt(localStorage.getItem('last_support_notif_total') || '0');
                        
                        if (currentTotal > sharedLastTotal) {
                            localStorage.setItem('last_support_notif_total', currentTotal);
                            lastNotifiedTotal = currentTotal;

                            notifSound.play().catch(() => {});
                            flashTitle('NEW MESSAGE!');
                        } else if (currentTotal < sharedLastTotal) {
                            localStorage.setItem('last_support_notif_total', currentTotal);
                            lastNotifiedTotal = currentTotal;
                        }

                        const navBadge = document.getElementById('support-nav-badge');
                        if (navBadge) {
                            navBadge.innerText = currentTotal;
                            navBadge.classList.toggle('hidden', currentTotal === 0);
                        }
                    }
                } catch (e) {}
            }

            // Poll every 4 seconds
            setInterval(checkGlobalChatStatus, 4000);
            
            // On load, set initial state from current unread
            setTimeout(async () => {
                try {
                    const response = await fetch('/support-center/status');
                    const data = await response.json();
                    if (data.success) {
                        let initialTotal = 0;
                        data.drivers.forEach(d => initialTotal += parseInt(d.unread_count || 0));
                        localStorage.setItem('last_support_notif_total', initialTotal);
                        lastNotifiedTotal = initialTotal;
                    }
                } catch(e) {}
            }, 1000);
        })();
    </script>

    <!-- ─── HEADER STAFF CHAT DROPDOWN SCRIPT (Exact match with Image 2) ─── -->
    <script>
        (function() {
            window._headerChatTab = 'GC';
            window._headerChatData = [];

            window.toggleHeaderChatDropdown = function(e) {
                if (e) e.stopPropagation();
                const dropdown = document.getElementById('headerChatDropdown');
                const notifDropdown = document.getElementById('notificationDropdown');
                
                // Close notification dropdown if open
                if (notifDropdown && !notifDropdown.classList.contains('hidden')) {
                    notifDropdown.classList.add('hidden');
                }

                if (dropdown) {
                    const isHidden = dropdown.classList.contains('hidden');
                    if (isHidden) {
                        dropdown.classList.remove('hidden');
                        window.loadHeaderChatData();
                    } else {
                        dropdown.classList.add('hidden');
                    }
                }
            };

            // Global click listener to close header chat dropdown on outside click
            document.addEventListener('click', function(e) {
                const dropdown = document.getElementById('headerChatDropdown');
                const btn = document.getElementById('headerChatBtn');
                if (dropdown && !dropdown.classList.contains('hidden')) {
                    if (!dropdown.contains(e.target) && !btn?.contains(e.target)) {
                        dropdown.classList.add('hidden');
                    }
                }
            });

            // Make sure notification bell click closes header chat dropdown
            document.addEventListener('DOMContentLoaded', function() {
                const notifBtn = document.getElementById('notificationBell');
                if (notifBtn) {
                    notifBtn.addEventListener('click', function() {
                        const chatDropdown = document.getElementById('headerChatDropdown');
                        if (chatDropdown && !chatDropdown.classList.contains('hidden')) {
                            chatDropdown.classList.add('hidden');
                        }
                    });
                }
            });

            window.switchHeaderChatTab = function(tab, e) {
                if (e) e.stopPropagation();
                window._headerChatTab = tab;
                
                const btnGC = document.getElementById('btnHeaderChatTabGC');
                const btnPM = document.getElementById('btnHeaderChatTabPM');
                
                if (tab === 'GC') {
                    btnGC.className = 'px-5 py-1.5 rounded-md text-white bg-blue-600 font-black transition-all shadow-sm';
                    btnPM.className = 'px-5 py-1.5 rounded-md text-blue-600 hover:bg-blue-50 font-black transition-all';
                } else {
                    btnPM.className = 'px-5 py-1.5 rounded-md text-white bg-blue-600 font-black transition-all shadow-sm';
                    btnGC.className = 'px-5 py-1.5 rounded-md text-blue-600 hover:bg-blue-50 font-black transition-all';
                }

                window.renderHeaderChatList();
            };

            window.loadHeaderChatData = async function() {
                const container = document.getElementById('headerChatItems');

                try {
                    const response = await fetch('/chat/staff-users', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        }
                    });
                    const users = await response.json();
                    window._headerChatData = Array.isArray(users) ? users : [];

                    // Calculate total unread count across all users/group
                    let totalUnread = 0;
                    window._headerChatData.forEach(u => {
                        totalUnread += parseInt(u.unread || 0);
                    });

                    const badge = document.getElementById('headerChatBadge');
                    const mobileBadge = document.getElementById('mobileChatUnreadBadge');
                    const badgeText = totalUnread > 99 ? '99+' : totalUnread;

                    if (badge) {
                        badge.textContent = badgeText;
                        badge.classList.toggle('hidden', totalUnread === 0);
                    }
                    if (mobileBadge) {
                        mobileBadge.textContent = badgeText;
                        mobileBadge.classList.toggle('hidden', totalUnread === 0);
                    }

                    window.renderHeaderChatList();
                } catch (e) {
                    console.error('Error loading header chat data', e);
                    if (container) {
                        container.innerHTML = '<div class="p-4 text-center text-red-500 text-xs">Failed to load messages.</div>';
                    }
                }
            };

            window.renderHeaderChatList = function() {
                const container = document.getElementById('headerChatItems');
                if (!container) return;

                const data = window._headerChatData || [];
                const tab = window._headerChatTab || 'GC';

                let filtered = [];
                if (tab === 'GC') {
                    // Group Chat items (id === 0 or role === 'Group')
                    filtered = data.filter(u => u.id === 0 || u.role === 'Group');
                } else {
                    // PM (Private Messages) items (individual staff members)
                    filtered = data.filter(u => u.id !== 0 && u.role !== 'Group');
                }

                if (filtered.length === 0) {
                    container.innerHTML = `
                        <div class="p-6 text-center text-gray-400 text-xs">
                            <i data-lucide="message-square-off" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                            No ${tab === 'GC' ? 'group chats' : 'private messages'} available.
                        </div>
                    `;
                    if (window.lucide) lucide.createIcons();
                    return;
                }

                // Color palette matching Image 2 circular badges
                const bgColors = [
                    'bg-emerald-500', 'bg-blue-600', 'bg-indigo-600', 'bg-purple-600', 
                    'bg-amber-500', 'bg-rose-500', 'bg-teal-600'
                ];

                container.innerHTML = filtered.map((u, idx) => {
                    const colorClass = bgColors[idx % bgColors.length];
                    const isGC = u.id === 0 || u.role === 'Group';
                    const badgeText = isGC ? 'GC' : (u.avatar || u.name.substring(0, 2).toUpperCase());
                    
                    return `
                        <div onclick="selectHeaderChatItem(${u.id}, '${escapeHtml(u.name)}', ${u.is_online ? 'true' : 'false'}, '${escapeHtml(u.last_active || '')}')"
                             class="px-4 py-3 hover:bg-gray-50/80 transition-colors flex items-center justify-between cursor-pointer group">
                            <div class="flex items-center gap-3 min-w-0 flex-1">
                                <!-- Circle Icon Badge (Exact match Image 2) -->
                                <div class="relative shrink-0">
                                    <div class="w-10 h-10 rounded-full ${colorClass} text-white font-black text-xs flex items-center justify-center shadow-sm">
                                        ${badgeText}
                                    </div>
                                    ${u.is_online ? '<span class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 border-2 border-white rounded-full"></span>' : ''}
                                </div>
                                
                                <!-- Details -->
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-1 mb-0.5">
                                        <h4 class="text-xs font-black text-gray-900 truncate group-hover:text-blue-600 transition-colors">${escapeHtml(u.name)}</h4>
                                        ${u.last_time ? `<span class="text-[10px] text-gray-400 shrink-0 font-medium">${escapeHtml(u.last_time)}</span>` : ''}
                                    </div>
                                    <p class="text-[11px] text-gray-500 truncate font-medium">${escapeHtml(u.last_msg || u.role || 'No messages yet')}</p>
                                </div>
                            </div>

                            ${u.unread > 0 ? `
                                <span class="ml-2 bg-blue-600 text-white text-[10px] font-black px-2 py-0.5 rounded-full shrink-0">
                                    ${u.unread}
                                </span>
                            ` : ''}
                        </div>
                    `;
                }).join('');

                if (window.lucide) lucide.createIcons();
            };

            window.selectHeaderChatItem = function(id, name, isOnline, lastActive) {
                // Close header dropdown
                const dropdown = document.getElementById('headerChatDropdown');
                if (dropdown) dropdown.classList.add('hidden');

                // Open chat thread in staff chat drawer
                if (typeof window.chatOpenThread === 'function') {
                    if (typeof window.chatToggleDrawer === 'function') {
                        const drawer = document.getElementById('chatDrawer');
                        if (drawer && drawer.classList.contains('opacity-0')) {
                            window.chatToggleDrawer();
                        }
                    }
                    window.chatOpenThread(id, name, isOnline, lastActive);
                }
            };

            window.openFullStaffChat = function() {
                const dropdown = document.getElementById('headerChatDropdown');
                if (dropdown) dropdown.classList.add('hidden');

                if (typeof window.chatToggleDrawer === 'function') {
                    const drawer = document.getElementById('chatDrawer');
                    if (drawer && drawer.classList.contains('opacity-0')) {
                        window.chatToggleDrawer();
                    }
                }
            };

            function escapeHtml(text) {
                if (!text) return '';
                return String(text)
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }

            // Auto refresh header chat data periodically and immediately
            window.loadHeaderChatData();
            document.addEventListener('DOMContentLoaded', window.loadHeaderChatData);
            setInterval(window.loadHeaderChatData, 5000);
        })();
    </script>
    <style>
        @keyframes bounce-in {
            0% { transform: scale(0.9); opacity: 0; }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); opacity: 1; }
        }
        .animate-bounce-in { animation: bounce-in 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    </style>
    
    @include('partials.chat-drawer')

    <!-- ─── GLOBAL SOS ACCIDENT ALERT (21st.dev Luxury Redesign) ─── -->
    <div id="globalSosAlert" class="fixed inset-0 z-[100000] items-center justify-center bg-slate-950/85 backdrop-blur-md p-4 sm:p-6" style="display:none;">
        <div class="relative w-full max-w-xl overflow-hidden rounded-3xl border border-red-500/30 bg-gradient-to-b from-white via-rose-50/30 to-red-50/40 p-6 sm:p-8 shadow-2xl shadow-red-950/50 animate-bounce-in">
            <!-- Top Ambient Laser Beacon Glow -->
            <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-64 h-32 bg-red-500/25 blur-3xl rounded-full pointer-events-none"></div>
            <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-red-600 via-rose-500 to-amber-500 animate-pulse"></div>

            <!-- Emergency Live Header Chip -->
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-100/80 border border-red-300 text-red-700 text-xs font-black uppercase tracking-wider shadow-xs">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-600"></span>
                    </span>
                    Live Emergency Broadcast
                </div>
                <button type="button" onclick="toggleSosMute()" id="btnMuteSos" class="text-xs font-bold text-slate-500 hover:text-slate-800 px-3 py-1 rounded-xl bg-white/80 border border-slate-200 shadow-xs transition-all flex items-center gap-1.5 cursor-pointer">
                    <i data-lucide="volume-2" class="w-3.5 h-3.5 text-slate-600" id="iconMuteSos"></i>
                    <span id="textMuteSos">Mute Siren</span>
                </button>
            </div>

            <!-- 3D Siren Hero Visual -->
            <div class="flex flex-col items-center text-center relative z-10 mb-5">
                <div class="relative w-28 h-28 sm:w-32 sm:h-32 mb-2">
                    <div class="absolute inset-0 rounded-full bg-red-500/20 blur-xl animate-pulse"></div>
                    <img src="{{ asset('image/kpi/emergency_siren_3d.svg') }}" alt="Emergency Siren" class="relative z-10 w-full h-full object-contain filter drop-shadow-xl animate-pulse">
                </div>
                <h1 class="text-3xl sm:text-4xl font-black text-red-600 tracking-tight uppercase leading-none mb-1">
                    Emergency Alert!
                </h1>
                <p class="text-xs sm:text-sm font-bold text-slate-500">Immediate fleet operator intervention required</p>
            </div>

            <!-- Driver & Vehicle Profile Card -->
            <div class="rounded-2xl border border-red-200/80 bg-white/90 p-4 mb-3 shadow-xs relative z-10">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-rose-700 text-white flex items-center justify-center font-black text-lg shadow-sm shrink-0">
                        <i data-lucide="shield-alert" class="w-6 h-6"></i>
                    </div>
                    <div class="min-w-0 flex-1 text-left">
                        <span class="text-[10px] font-black uppercase tracking-wider text-red-500 block">Reported Driver & Taxi Unit</span>
                        <h2 class="text-base sm:text-lg font-black text-slate-900 leading-snug truncate" id="sosAlertDriver">Driver Name — Plate Number</h2>
                    </div>
                </div>
            </div>

            <!-- Location & Telemetry Card -->
            <div class="rounded-2xl border border-slate-200/80 bg-white/90 p-4 mb-6 shadow-xs relative z-10 text-left">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center shrink-0 mt-0.5 border border-sky-100">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block mb-0.5">GPS Location & Telemetry</span>
                        <div class="text-xs sm:text-sm font-bold text-slate-800" id="sosAlertLocation">Lat: --, Lng: --</div>
                        <p class="text-[11px] font-semibold text-slate-400 mt-1.5 flex items-center gap-1" id="sosAlertTime">
                            <i data-lucide="clock" class="w-3 h-3 text-slate-400"></i>
                            <span>Time: --</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- CTA Action Button -->
            <div class="relative z-10">
                <button onclick="acknowledgeSosAlert()" id="btnAcknowledgeSos" class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-red-600 via-rose-600 to-red-700 hover:from-red-500 hover:to-rose-600 text-white text-base sm:text-lg font-black tracking-wider uppercase shadow-lg shadow-red-600/30 hover:shadow-xl hover:shadow-red-600/40 active:scale-[0.98] transition-all cursor-pointer flex items-center justify-center gap-2 border border-red-400/30">
                    <i data-lucide="check-check" class="w-5 h-5"></i>
                    <span>Acknowledge Alert</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        (function() {
            let sosAlertSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3'); // Klaxon sound
            sosAlertSound.loop = true;
            let isAlertShowing = false;
            let currentAlertId = null;
            let isMuted = false;

            window.toggleSosMute = function() {
                isMuted = !isMuted;
                const textEl = document.getElementById('textMuteSos');
                const iconEl = document.getElementById('iconMuteSos');
                if (isMuted) {
                    sosAlertSound.pause();
                    if (textEl) textEl.innerText = 'Unmute Siren';
                    if (iconEl) iconEl.setAttribute('data-lucide', 'volume-x');
                } else {
                    sosAlertSound.play().catch(e => console.log('Audio resume error', e));
                    if (textEl) textEl.innerText = 'Mute Siren';
                    if (iconEl) iconEl.setAttribute('data-lucide', 'volume-2');
                }
                if (window.lucide) lucide.createIcons();
            };

            async function pollSosAlerts() {
                try {
                    const response = await fetch('/api/accident-alerts/check');
                    const data = await response.json();
                    
                    if (data.count > 0 && data.alerts && data.alerts.length > 0) {
                        const alert = data.alerts[0]; // Get oldest pending alert
                        
                        if (!isAlertShowing || currentAlertId !== alert.id) {
                            currentAlertId = alert.id;
                            
                            // Format details
                            const driverName = alert.driver ? `${alert.driver.first_name} ${alert.driver.last_name}` : 'Unknown Driver';
                            const plateNum = alert.unit ? alert.unit.plate_number : 'Unknown Unit';
                            
                            const driverEl = document.getElementById('sosAlertDriver');
                            if (driverEl) driverEl.innerText = `${driverName} — ${plateNum}`;
                            
                            const locEl = document.getElementById('sosAlertLocation');
                            if (locEl) {
                                if (alert.latitude && alert.longitude) {
                                    locEl.innerHTML = `<a href="https://maps.google.com/?q=${alert.latitude},${alert.longitude}" target="_blank" class="text-blue-600 hover:text-blue-700 font-bold hover:underline inline-flex items-center gap-1"><i data-lucide="external-link" class="w-3.5 h-3.5"></i>View on Google Maps (${alert.latitude}, ${alert.longitude})</a>`;
                                } else {
                                    locEl.innerText = 'GPS coordinates unavailable';
                                }
                            }
                            
                            const d = new Date(alert.created_at);
                            const timeEl = document.getElementById('sosAlertTime');
                            if (timeEl) {
                                timeEl.innerHTML = `<i data-lucide="clock" class="w-3 h-3 text-slate-400 inline mr-1"></i>Reported at: ${d.toLocaleString()}`;
                            }
                            
                            // Show alert UI
                            const alertModal = document.getElementById('globalSosAlert');
                            if (alertModal) alertModal.style.display = 'flex';
                            isAlertShowing = true;
                            
                            // Play sound if not muted
                            if (!isMuted) {
                                sosAlertSound.play().catch(e => console.log('Autoplay blocked for SOS sound', e));
                            }
                            
                            if (window.lucide) lucide.createIcons();
                        }
                    } else {
                        // No alerts
                        if (isAlertShowing) {
                            hideSosAlert();
                        }
                    }
                } catch (e) {
                    console.error('Error polling SOS alerts', e);
                }
            }

            window.hideSosAlert = function() {
                const alertModal = document.getElementById('globalSosAlert');
                if (alertModal) alertModal.style.display = 'none';
                sosAlertSound.pause();
                sosAlertSound.currentTime = 0;
                isAlertShowing = false;
                currentAlertId = null;
            }

            window.acknowledgeSosAlert = async function() {
                if (!currentAlertId) return;
                
                const btn = document.getElementById('btnAcknowledgeSos');
                if (btn) {
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Acknowledging...';
                    btn.disabled = true;
                }
                
                try {
                    const response = await fetch(`/accident-alerts/${currentAlertId}/acknowledge`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    const data = await response.json();
                    if (data.success) {
                        hideSosAlert();
                        // Redirect to the Accident Reports tab
                        window.location.href = '{{ route("driver-behavior.accidents") }}';
                    } else {
                        alert('Failed to acknowledge alert.');
                    }
                } catch (e) {
                    alert('Error: ' + e.message);
                } finally {
                    if (btn) {
                        btn.innerHTML = '<i data-lucide="check-check" class="w-5 h-5"></i><span>Acknowledge Alert</span>';
                        btn.disabled = false;
                        if (window.lucide) lucide.createIcons();
                    }
                }
            }

            // Poll every 10 seconds
            setInterval(pollSosAlerts, 10000);
            setTimeout(pollSosAlerts, 2000); // Initial check
        })();
    </script>
    
    @auth
    <script>
        // Heartbeat Auto-Offline Tracker
        (function() {
            function sendHeartbeat() {
                fetch('{{ route("heartbeat") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }).catch(e => console.error('Heartbeat failed:', e));
            }
            
            // Send heartbeat every 20 seconds for continuous active presence tracking
            setInterval(sendHeartbeat, 20000);
            
            // Send one immediately on load
            setTimeout(sendHeartbeat, 2000);
        })();
    </script>
    
    <!-- Interactive Tutorial System -->
    <script src="https://cdn.jsdelivr.net/npm/driver.js@1.3.1/dist/driver.js.iife.js"></script>
    <script src="{{ asset('assets/js/tutorial.js') }}?v=18.0"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof window.TutorialManager !== 'undefined') {
                window.TutorialManager.init({{ auth()->user()->tutorial_completed ? 'true' : 'false' }});
            }
        });
    </script>
    @endauth

    {{-- Global Page Loader Overlay (GPU-Accelerated Ultra-Smooth Euro Loader) --}}
    <div id="globalPageLoader"
         class="fixed inset-0 z-[999998] bg-slate-950/85 backdrop-blur-sm flex flex-col items-center justify-center transition-opacity duration-200 opacity-0 pointer-events-none select-none"
         style="will-change: opacity; transform: translateZ(0);">
        <div class="flex flex-col items-center justify-center gap-5">
            <!-- GPU-Accelerated Spinner & Logo Container -->
            <div class="relative flex items-center justify-center w-24 h-24">
                <!-- Outer Smooth Spinning Halo (GPU Compositor Thread) -->
                <div class="absolute inset-0 rounded-full border-[3px] border-amber-500/20 border-t-amber-400 border-r-yellow-500 euro-gpu-spin shadow-[0_0_20px_rgba(234,179,8,0.35)]"></div>
                
                <!-- Inner Reverse Counter-Orbit Ring -->
                <div class="absolute inset-2 rounded-full border-[2px] border-amber-400/10 border-b-amber-300 euro-gpu-spin-reverse"></div>

                <!-- Glowing Euro Logo / Emblem in Center -->
                <div class="relative z-10 flex items-center justify-center w-14 h-14 euro-gpu-pulse">
                    <img src="{{ asset('favicon_euro_transparent.png') }}" class="w-12 h-12 object-contain drop-shadow-[0_0_14px_rgba(234,179,8,0.8)]" alt="Euro Taxi">
                </div>
            </div>
            
            <!-- Animated Loading Text & Bouncing Wave Dots -->
            <div class="flex items-center justify-center gap-1.5">
                <span class="text-xs font-black uppercase euro-text-shimmer drop-shadow-[0_0_10px_rgba(234,179,8,0.5)]" id="globalPageLoaderText">Loading</span>
                <span class="inline-flex items-center gap-1.5 euro-dots-wrapper" aria-hidden="true">
                    <span class="euro-dot euro-dot-1"></span>
                    <span class="euro-dot euro-dot-2"></span>
                    <span class="euro-dot euro-dot-3"></span>
                </span>
            </div>
        </div>
    </div>

    <style>
    /* ── GPU-Accelerated Hardware-Composited Loader Animations ── */
    /* These properties run on the GPU Compositor thread and will NEVER stutter or freeze even when main thread is busy */
    .euro-gpu-spin {
        will-change: transform;
        transform: translateZ(0);
        animation: euroGpuRotate 0.95s cubic-bezier(0.45, 0.05, 0.55, 0.95) infinite;
    }

    .euro-gpu-spin-reverse {
        will-change: transform;
        transform: translateZ(0);
        animation: euroGpuRotateReverse 1.5s linear infinite;
    }

    .euro-gpu-pulse {
        will-change: transform, opacity;
        transform: translateZ(0);
        animation: euroGpuBreathing 1.8s ease-in-out infinite;
    }

    /* ── Animated Text Shimmer & Bouncing Glowing Dots ── */
    .euro-text-shimmer {
        background: linear-gradient(90deg, #eab308 0%, #fef08a 35%, #f59e0b 70%, #eab308 100%);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: euroShimmerText 2.2s linear infinite;
        letter-spacing: 0.18em;
    }

    .euro-dots-wrapper {
        display: inline-flex;
        align-items: center;
        margin-left: 2px;
        height: 12px;
    }

    .euro-dot {
        width: 5px;
        height: 5px;
        background-color: #facc15;
        border-radius: 9999px;
        box-shadow: 0 0 8px rgba(250, 204, 21, 0.9);
        display: inline-block;
        will-change: transform, opacity;
        transform: translateZ(0);
    }

    .euro-dot-1 {
        animation: euroDotBounce 1.2s infinite ease-in-out;
    }

    .euro-dot-2 {
        animation: euroDotBounce 1.2s infinite ease-in-out 0.2s;
    }

    .euro-dot-3 {
        animation: euroDotBounce 1.2s infinite ease-in-out 0.4s;
    }

    @keyframes euroGpuRotate {
        0% { transform: rotate(0deg) translateZ(0); }
        100% { transform: rotate(360deg) translateZ(0); }
    }

    @keyframes euroGpuRotateReverse {
        0% { transform: rotate(360deg) translateZ(0); }
        100% { transform: rotate(0deg) translateZ(0); }
    }

    @keyframes euroGpuBreathing {
        0%, 100% { transform: scale(1) translateZ(0); opacity: 0.9; }
        50% { transform: scale(1.1) translateZ(0); opacity: 1; }
    }

    @keyframes euroShimmerText {
        0% { background-position: 0% 50%; }
        100% { background-position: 200% 50%; }
    }

    @keyframes euroDotBounce {
        0%, 60%, 100% {
            transform: translateY(0) scale(0.85);
            opacity: 0.35;
            background-color: #ca8a04;
        }
        30% {
            transform: translateY(-5px) scale(1.4);
            opacity: 1;
            background-color: #fef08a;
            box-shadow: 0 0 12px rgba(254, 240, 138, 1);
        }
    }
    </style>

    <script>
    (function() {
        const loader = document.getElementById('globalPageLoader');
        const loaderText = document.getElementById('globalPageLoaderText');
        let hideTimer = null;
        let isNavigating = false;

        window.showGlobalLoader = function(text = 'Loading...', safetyTimeout = 12000) {
            if (!loader) return;
            if (loaderText) {
                // Strip trailing dots since we have dedicated animated bouncing dots
                const cleanText = text.replace(/\.+$/, '');
                loaderText.textContent = cleanText || 'Loading';
            }
            loader.classList.remove('opacity-0', 'pointer-events-none');
            loader.classList.add('opacity-100', 'pointer-events-auto');

            // Safety timeout so UI never permanently freezes on network drop
            if (hideTimer) clearTimeout(hideTimer);
            if (safetyTimeout > 0) {
                hideTimer = setTimeout(window.hideGlobalLoader, safetyTimeout);
            }
        };

        window.hideGlobalLoader = function() {
            if (!loader) return;
            if (hideTimer) {
                clearTimeout(hideTimer);
                hideTimer = null;
            }
            isNavigating = false;
            loader.classList.remove('opacity-100', 'pointer-events-auto');
            loader.classList.add('opacity-0', 'pointer-events-none');
        };

        // 1. Instant Navigation on Link Clicks (Internal links)
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (!link) return;

            const href = link.getAttribute('href');
            if (!href || href === '#' || href.startsWith('javascript:') || href.startsWith('tel:') || href.startsWith('mailto:')) return;
            if (link.getAttribute('target') === '_blank' || link.hasAttribute('download') || link.dataset.noLoader === 'true') return;
            if (link.hasAttribute('onclick') || link.getAttribute('role') === 'button' || link.dataset.tab) return;
            
            // Ignore modified clicks (new tab / window)
            if (e.ctrlKey || e.shiftKey || e.metaKey || e.altKey || e.button !== 0) return;

            // Check if same origin and actually navigating to a different page/search
            try {
                const targetUrl = new URL(link.href, window.location.origin);
                if (targetUrl.origin === window.location.origin) {
                    // Ignore file downloads
                    if (/\.(pdf|csv|xlsx|xls|zip|rar|png|jpg|jpeg|gif|webp|svg|mp3|mp4|doc|docx)$/i.test(targetUrl.pathname)) {
                        return;
                    }
                    // If only anchor on same page, don't show loader
                    if (targetUrl.pathname === window.location.pathname && 
                        targetUrl.search === window.location.search && 
                        targetUrl.hash && targetUrl.hash !== '') {
                        return;
                    }
                    // Check in next tick to make sure the click wasn't prevented
                    setTimeout(() => {
                        if (!e.defaultPrevented) {
                            isNavigating = true;
                            showGlobalLoader('Loading...');
                        }
                    }, 0);
                }
            } catch (err) {
                // Invalid URL, ignore
            }
        });

        // 2. Instant Form Submissions
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.getAttribute('target') === '_blank' || form.dataset.noLoader === 'true') return;
            showGlobalLoader('Processing...');
        });

        // 3. Browser beforeunload fallback
        window.addEventListener('beforeunload', function() {
            if (!isNavigating) {
                showGlobalLoader('Loading...');
            }
        });

        // 4. Smooth & Accurate Dismissal when new page finishes loading or restoring
        function finishLoading() {
            requestAnimationFrame(() => {
                setTimeout(window.hideGlobalLoader, 80);
            });
        }

        if (document.readyState === 'complete') {
            finishLoading();
        } else {
            window.addEventListener('load', finishLoading);
            document.addEventListener('DOMContentLoaded', finishLoading);
        }

        // 5. Back/Forward Cache & Navigation popstate support
        window.addEventListener('pageshow', function(e) {
            window.hideGlobalLoader();
        });
        window.addEventListener('popstate', function() {
            window.hideGlobalLoader();
        });

        // 6. Escape key failsafe
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                window.hideGlobalLoader();
            }
        });
    })();
    </script>
</body>

</html>

