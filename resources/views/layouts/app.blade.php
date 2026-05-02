<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
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

    <title>{{ config('app.name', 'Euro Taxi System') }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon_euro_transparent.png') }}?v=1.5">
    <link rel="icon" type="image/png" href="{{ asset('favicon_euro_transparent.png') }}?v=1.5">
    <link rel="apple-touch-icon" href="{{ asset('favicon_euro_transparent.png') }}?v=1.5">
    <link rel="manifest" href="{{ asset('manifest.json') }}?v=1.5">
    <link rel="manifest" href="/public/manifest.json?v=1.5">

    <!-- Tailwind CSS (Local) -->
    <script>
        // Ultimate silence for Tailwind production warning
        (function() {
            const originalWarn = console.warn;
            console.warn = function() {
                if (arguments[0] && typeof arguments[0] === 'string' && arguments[0].includes('cdn.tailwindcss.com')) {
                    return;
                }
                originalWarn.apply(console, arguments);
            };
        })();
    </script>
    <script src="{{ asset('assets/tailwind.min.js') }}"></script>
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }
    </style>
    
    <!-- Lucide Icons (Local) -->
    <script src="{{ asset('assets/lucide.min.js') }}"></script>

    <!-- Custom CSS -->
    <link href="{{ asset('assets/app.css') }}?v=1.2" rel="stylesheet">
    @stack('styles')

    <!-- Custom JS -->
    <script src="{{ asset('assets/app.js') }}?v=1.2"></script>

    <!-- Chart.js for Dashboard (Local) -->
    <script src="{{ asset('assets/chart.min.js') }}"></script>
    <script src="{{ asset('assets/chartjs-plugin-datalabels.min.js') }}"></script>

    @auth
        @php
            // Notifications for header bell
            $headerNotifications = [];
            $user = auth()->user();
            
            // 1. HIGHEST PRIORITY: Manually flagged 'Surveillance' units
            if ($user->hasAccessTo('units.*')) {
                $flaggedUnits = DB::table('units')
                    ->whereNull('deleted_at')
                    ->where('status', 'surveillance')
                    ->get();
                    
                foreach($flaggedUnits as $fu) {
                    $headerNotifications[] = [
                        'id' => 'surveillance_' . $fu->id,
                        'title' => '🚨 Flagged: ' . $fu->plate_number,
                        'message' => 'This unit is currently flagged as At Risk.',
                        'type' => 'surveillance',
                        'url' => route('units.index') . '?open_flagged=1',
                        'time' => 'Action Required',
                        'timestamp' => \Carbon\Carbon::parse($fu->updated_at ?? now())
                    ];
                }
            }
            
            // 2. Fetch System Alerts from DB (REAL-TIME VIOLATIONS)
            $dbAlerts = DB::table('system_alerts')
                ->where('is_resolved', false)
                ->orderByDesc('created_at')
                ->limit(15)
                ->get();

            foreach($dbAlerts as $alert) {
                $targetUrl = '#';
                $canView = false;

                if ($alert->type === 'missing_unit') {
                    $targetUrl = route('units.index') . '?open_flagged=1';
                    $canView = $user->hasAccessTo('units.*');
                } elseif ($alert->type === 'coding_notice') {
                    $targetUrl = route('coding.index');
                    $canView = $user->hasAccessTo('coding.*');
                } else {
                    $targetUrl = route('driver-behavior.index');
                    $canView = $user->hasAccessTo('driver-behavior.*');
                }

                if ($canView) {
                    $headerNotifications[] = [
                        'id' => $alert->id,
                        'title' => $alert->title,
                        'message' => $alert->message,
                        'type' => 'violation_alert', 
                        'severity' => $alert->type, 
                        'url' => $targetUrl,
                        'time' => \Carbon\Carbon::parse($alert->created_at)->diffForHumans(),
                        'timestamp' => \Carbon\Carbon::parse($alert->created_at)
                    ];
                }
            }
            
            // 3. Merge specialized notifications from views if they exist
            if(isset($maintNotifs)) {
                foreach($maintNotifs as $n) {
                    $n['time'] = $n['time'] ?? 'Today';
                    $headerNotifications[] = $n;
                }
            }
            if(isset($expiringFranchise)) {
                foreach($expiringFranchise as $n) {
                    $n['time'] = $n['time'] ?? 'Now';
                    $headerNotifications[] = $n;
                }
            }

            // 4. Merge Stock and License notifications (previously separate buttons)
            if(isset($stockNotifs)) {
                foreach($stockNotifs as $n) {
                    $n['type'] = 'low_stock';
                    $n['url'] = route('maintenance.index', ['open_inventory' => 1]);
                    $headerNotifications[] = $n;
                }
            }
            if(isset($licenseNotifs)) {
                foreach($licenseNotifs as $n) {
                    $n['type'] = 'license_expiry';
                    $n['url'] = route('driver-management.index');
                    $headerNotifications[] = $n;
                }
            }

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
            <!-- Sidebar -->
            <aside id="appSidebar" class="w-16 lg:w-60 bg-white shadow-lg flex-shrink-0 transition-all duration-300 overflow-x-hidden">
                <div class="h-full flex flex-col">
                    <!-- Logo -->
                    <div class="p-2 lg:p-4 border-b flex flex-col items-center">
                        <img src="{{ asset('uploads/logo.png') }}" alt="Euro System Logo" class="h-8 lg:h-12 w-auto mb-1">
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold hidden lg:block">Fleet Management</p>
                    </div>

                    <!-- Navigation -->
                    <nav class="flex-1 p-2 lg:p-4 space-y-1 overflow-y-auto overflow-x-hidden">
                        @if(auth()->user()->role === 'super_admin')
                        <a href="{{ route('super-admin.index') }}"
                            class="sidebar-item flex items-center justify-center lg:justify-start lg:gap-2.5 px-0 lg:px-4 py-2 rounded-lg font-semibold {{ request()->routeIs('super-admin.*') ? 'bg-yellow-100 text-yellow-800' : 'text-yellow-700 hover:bg-yellow-50 hover:text-yellow-800' }}">
                            <i data-lucide="crown" class="w-5 lg:w-4 h-5 lg:h-4"></i>
                            <span class="text-sm hidden lg:block">Owner Panel</span>
                        </a>
                        <hr class="my-2 border-gray-100 hidden lg:block">
                        @endif

                        @if(auth()->user()->hasAccessTo('dashboard'))
                        <a href="{{ route('dashboard') }}"
                            class="sidebar-item flex items-center justify-center lg:justify-start lg:gap-2.5 px-0 lg:px-4 py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('dashboard') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                            <i data-lucide="layout-dashboard" class="w-5 lg:w-4 h-5 lg:h-4"></i>
                            <span class="text-sm hidden lg:block">Dashboard</span>
                        </a>
                        @endif

                        @if(auth()->user()->hasAccessTo('units.*'))
                        <a href="{{ route('units.index') }}"
                            class="sidebar-item flex items-center justify-center lg:justify-start lg:gap-2.5 px-0 lg:px-4 py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('units.*') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                            <i data-lucide="car" class="w-5 lg:w-4 h-5 lg:h-4"></i>
                            <span class="text-sm hidden lg:block">Unit Management</span>
                        </a>
                        @endif

                        @if(auth()->user()->hasAccessTo('driver-management.*'))
                        <a href="{{ route('driver-management.index') }}"
                            class="sidebar-item flex items-center justify-center lg:justify-start lg:gap-2.5 px-0 lg:px-4 py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('driver-management.*') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                            <i data-lucide="users" class="w-5 lg:w-4 h-5 lg:h-4"></i>
                            <span class="text-sm hidden lg:block">Driver Management</span>
                        </a>
                        @endif

                        @if(auth()->user()->hasAccessTo('live-tracking.*'))
                        <a href="{{ route('live-tracking.index') }}"
                            class="sidebar-item flex items-center justify-center lg:justify-start lg:gap-2.5 px-0 lg:px-4 py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('live-tracking.*') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                            <i data-lucide="map-pin" class="w-5 lg:w-4 h-5 lg:h-4"></i>
                            <span class="text-sm hidden lg:block">Live Tracking</span>
                        </a>
                        @endif

                        @if(auth()->user()->hasAccessTo('decision-management.*'))
                        <a href="{{ route('decision-management.index') }}"
                            class="sidebar-item flex items-center justify-center lg:justify-start lg:gap-2.5 px-0 lg:px-4 py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('decision-management.*') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                            <i data-lucide="file-text" class="w-5 lg:w-4 h-5 lg:h-4"></i>
                            <span class="text-sm hidden lg:block">Franchise</span>
                        </a>
                        @endif

                        @if(auth()->user()->hasAccessTo('boundaries.*'))
                        <a href="{{ route('boundaries.index') }}"
                            class="sidebar-item flex items-center justify-center lg:justify-start lg:gap-2.5 px-0 lg:px-4 py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('boundaries.*') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                            <i data-lucide="philippine-peso" class="w-5 lg:w-4 h-5 lg:h-4"></i>
                            <span class="text-sm hidden lg:block">Boundaries</span>
                        </a>
                        @endif

                        @if(auth()->user()->hasAccessTo('maintenance.*'))
                        <a href="{{ route('maintenance.index') }}"
                            class="sidebar-item flex items-center justify-center lg:justify-start lg:gap-2.5 px-0 lg:px-4 py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('maintenance.*') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                            <i data-lucide="wrench" class="w-5 lg:w-4 h-5 lg:h-4"></i>
                            <span class="text-sm hidden lg:block">Maintenance</span>
                        </a>
                        @endif

                        @if(auth()->user()->hasAccessTo('coding.*'))
                        <a href="{{ route('coding.index') }}"
                            class="sidebar-item flex items-center justify-center lg:justify-start lg:gap-2.5 px-0 lg:px-4 py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('coding.*') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                            <i data-lucide="calendar" class="w-5 lg:w-4 h-5 lg:h-4"></i>
                            <span class="text-sm hidden lg:block">Coding Management</span>
                        </a>
                        @endif

                        @if(auth()->user()->hasAccessTo('driver-behavior.*'))
                        <a href="{{ route('driver-behavior.index') }}"
                            class="sidebar-item flex items-center justify-center lg:justify-start lg:gap-2.5 px-0 lg:px-4 py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('driver-behavior.*') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                            <i data-lucide="alert-triangle" class="w-5 lg:w-4 h-5 lg:h-4"></i>
                            <span class="text-sm hidden lg:block">Driver Behavior</span>
                        </a>
                        @endif

                        @if(auth()->user()->hasAccessTo('office-expenses.*'))
                        <a href="{{ route('office-expenses.index') }}"
                            class="sidebar-item flex items-center justify-center lg:justify-start lg:gap-2.5 px-0 lg:px-4 py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('office-expenses.*') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                            <i data-lucide="philippine-peso" class="w-5 lg:w-4 h-5 lg:h-4"></i>
                            <span class="text-sm hidden lg:block">Office Expenses</span>
                        </a>
                        @endif

                        @if(auth()->user()->hasAccessTo('salary.*'))
                        <a href="{{ route('salary.index') }}"
                            class="sidebar-item flex items-center justify-center lg:justify-start lg:gap-2.5 px-0 lg:px-4 py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('salary.*') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                            <i data-lucide="calculator" class="w-5 lg:w-4 h-5 lg:h-4"></i>
                            <span class="text-sm hidden lg:block">Salary Management</span>
                        </a>
                        @endif


                        @if(auth()->user()->hasAccessTo('analytics.*'))
                        <a href="{{ route('analytics.index') }}"
                            class="sidebar-item flex items-center justify-center lg:justify-start lg:gap-2.5 px-0 lg:px-4 py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('analytics.*') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                            <i data-lucide="bar-chart" class="w-4 h-4"></i>
                            <span class="text-sm hidden lg:block">Analytics</span>
                        </a>
                        @endif

                        @if(auth()->user()->hasAccessTo('activity-logs.*'))
                        <a href="{{ route('activity-logs.index') }}"
                            class="sidebar-item flex items-center justify-center lg:justify-start lg:gap-2.5 px-0 lg:px-4 py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('activity-logs.*') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                            <i data-lucide="history" class="w-5 lg:w-4 h-5 lg:h-4"></i>
                            <span class="text-sm hidden lg:block">History Logs</span>
                        </a>
                        @endif


                        @if(auth()->user()->hasAccessTo('unit-profitability.*'))
                        <a href="{{ route('unit-profitability.index') }}"
                            class="sidebar-item flex items-center justify-center lg:justify-start lg:gap-2.5 px-0 lg:px-4 py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('unit-profitability.*') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                            <i data-lucide="trending-up" class="w-5 lg:w-4 h-5 lg:h-4"></i>
                            <span class="text-sm hidden lg:block">Unit Profitability</span>
                        </a>
                        @endif

                        @if(auth()->user()->hasAccessTo('staff.*'))
                        <a href="{{ route('staff.index') }}"
                            class="sidebar-item flex items-center justify-center lg:justify-start lg:gap-2.5 px-0 lg:px-4 py-2 rounded-lg text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 {{ request()->routeIs('staff.*') ? 'bg-yellow-50 text-yellow-700 font-semibold' : '' }}">
                            <i data-lucide="user-cog" class="w-5 lg:w-4 h-5 lg:h-4"></i>
                            <span class="text-sm hidden lg:block">Staff Records</span>
                        </a>
                        @endif

                        <hr class="my-2 border-gray-100 hidden lg:block">

                        @if(auth()->user()->hasAccessTo('archive.*'))
                        <a href="{{ route('archive.index') }}"
                            class="sidebar-item flex items-center justify-center lg:justify-start lg:gap-2.5 px-0 lg:px-4 py-2 rounded-lg text-gray-700 hover:bg-red-50 hover:text-red-700 {{ request()->routeIs('archive.*') ? 'bg-red-50 text-red-700 font-semibold' : '' }}">
                            <i data-lucide="archive" class="w-5 lg:w-4 h-5 lg:h-4"></i>
                            <span class="text-sm hidden lg:block">Archive</span>
                        </a>
                        @endif
                    </nav>

                    <!-- User Menu -->
                    <div class="p-2 lg:p-4 border-t bg-white relative z-50">
                        <a href="{{ route('my-account') }}" 
                           class="flex items-center justify-center lg:justify-start lg:gap-3 mb-3 p-1 lg:p-2 rounded-lg hover:bg-gray-50 transition-colors group">
                            <div
                                class="w-8 h-8 lg:w-10 lg:h-10 bg-yellow-600 rounded-full flex items-center justify-center text-white font-semibold group-hover:bg-yellow-700 transition-colors overflow-hidden flex-shrink-0 border border-gray-100">
                                @if(auth()->user()->profile_image)
                                    @php
                                        $imagePath = str_replace('resources/', '', auth()->user()->profile_image);
                                        $isIcon = str_contains($imagePath, 'image/') && !str_contains($imagePath, 'storage/');
                                    @endphp
                                    @if($isIcon)
                                        <img src="{{ asset($imagePath) }}" alt="Profile" class="w-full h-full object-cover">
                                    @else
                                        <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="Profile" class="w-full h-full object-cover">
                                    @endif
                                @else
                                    {{ strtoupper(substr(auth()->user()->full_name ?? 'U', 0, 1)) }}
                                @endif
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h4>
                                <p class="text-xs text-gray-500">{{ auth()->user()->role === 'super_admin' ? 'Owner' : ucfirst(auth()->user()->role ?? 'user') }}</p>
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400 group-hover:text-yellow-600 transition-colors hidden lg:block"></i>
                        </a>
                        
                        <!-- Logout Form -->
                        <form id="logout-form" action="{{ route('logout') }}" method="GET" class="hidden"></form>
                        
                        <button type="button"
                            onclick="if(confirm('Are you sure you want to logout?')) { document.getElementById('logout-form').submit(); }"
                            class="flex items-center justify-center lg:justify-start lg:gap-2 px-1 lg:px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg w-full transition-colors">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                            <span class="hidden lg:block">Logout</span>
                        </button>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main id="appMainContent" class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Bar -->
                <header class="bg-white shadow-sm border-b px-6 py-2">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-900">@yield('page-heading', 'Dashboard')</h2>
                            @hasSection('page-subheading')
                                <p class="text-sm text-gray-500 mt-1">@yield('page-subheading')</p>
                            @endif
                        </div>

                        <div class="flex items-center gap-4">
                            {{-- Consolidating all notifications into the Main Bell --}}


                            <!-- Main Notification Bell -->
                            <div class="relative">
                                <button id="notificationBell"
                                    class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg">
                                    <i data-lucide="bell" class="w-5 h-5"></i>
                                    @if($headerNotificationCount > 0)
                                        <span
                                            class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 bg-red-500 text-white text-[10px] leading-[18px] rounded-full text-center">
                                            {{ $headerNotificationCount }}
                                        </span>
                                    @endif
                                </button>

                                <div id="notificationDropdown"
                                    class="hidden absolute right-0 mt-2 w-80 bg-white shadow-xl rounded-2xl border border-gray-100 z-50 overflow-hidden">
                                    <div class="px-4 py-3 border-b bg-gray-50/50 flex items-center justify-between">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-black text-gray-900 tracking-tight">Notifications</span>
                                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ $headerNotificationCount }} item(s)</span>
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
                                            @if($systemNotifCount > 0)
                                                <span class="bg-red-500 text-white text-[9px] px-1.5 py-0.5 rounded-full ml-1">{{ $systemNotifCount }}</span>
                                            @endif
                                        </button>
                                        <button onclick="filterNotifs('low_stock')" id="btn-filter-parts" class="flex-1 py-2.5 text-[11px] font-bold uppercase tracking-wider text-gray-400 hover:text-gray-600 transition-all flex items-center justify-center gap-1.5">
                                            Parts Stock
                                            @if($stockNotifCount > 0)
                                                <span class="bg-orange-500 text-white text-[9px] px-1.5 py-0.5 rounded-full">{{ $stockNotifCount }}</span>
                                            @endif
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
                                                            @elseif($n['type'] === 'coding_today')
                                                                <i data-lucide="car-front" class="w-4 h-4 text-blue-600"></i>
                                                            @elseif($n['type'] === 'violation_alert')
                                                                <i data-lucide="shield-alert" class="w-4 h-4 text-red-600"></i>
                                                            @elseif($n['type'] === 'low_stock')
                                                                <i data-lucide="package-search" class="w-4 h-4 text-orange-500"></i>
                                                            @elseif($n['type'] === 'license_expiry')
                                                                <i data-lucide="id-card" class="w-4 h-4 text-rose-500"></i>
                                                            @else
                                                                <i data-lucide="alert-circle" class="w-4 h-4 text-red-600"></i>
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
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Date/Time -->
                            <div class="text-right">
                                <p id="header-date" class="text-[13px] font-medium text-gray-900">{{ date('l, F j, Y') }}</p>
                                <p id="header-time" class="text-[11px] text-gray-500 transition-all duration-300">{{ date('h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <div id="appContentArea" class="flex-1 overflow-y-auto @yield('main-padding', 'p-4')">
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
        <div id="globalArchiveSecurityModal" class="fixed inset-0 z-[9999] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
                document.getElementById('globalArchiveSecurityModal').classList.add('hidden');
                document.getElementById('global-archive-pwd').value = '';
                pendingDeleteForm = null;
                pendingArchivePwdResolve = null;
            }

            // Allow JS-driven destructive actions (fetch/AJAX) to reuse this modal.
            // Returns the password string, or null if cancelled.
            window.promptArchiveDeletionPassword = function () {
                return new Promise((resolve) => {
                    pendingArchivePwdResolve = resolve;
                    pendingDeleteForm = null; // ensure we are not in form-submit mode
                    document.getElementById('globalArchiveSecurityModal').classList.remove('hidden');
                    if (window.lucide) window.lucide.createIcons();
                    setTimeout(() => document.getElementById('global-archive-pwd')?.focus(), 100);
                });
            };

            document.addEventListener('submit', function(e) {
                // Intercept forms that look like permanent deletes
                const form = e.target;
                const action = form.getAttribute('action') || '';
                const method = form.querySelector('input[name="_method"]')?.value || form.getAttribute('method');

                // Check if it's a permanent delete (contains force-delete, permanent, or delete and is inside an archive context)
                const isArchiveDelete = (action.includes('force-delete') || action.includes('permanent') || action.includes('delete')) && 
                                        (method?.toUpperCase() === 'DELETE' || method?.toUpperCase() === 'POST');

                // Skip if it's already handled or not an archive delete
                if (!isArchiveDelete || form.dataset.verified === 'true') return;

                e.preventDefault();
                pendingDeleteForm = form;
                
                document.getElementById('globalArchiveSecurityModal').classList.remove('hidden');
                if (window.lucide) window.lucide.createIcons();
                setTimeout(() => document.getElementById('global-archive-pwd').focus(), 100);
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
                    closeGlobalArchiveSecurityModal();
                    resolve(password);
                    return;
                }
                closeGlobalArchiveSecurityModal();
            });
        </script>

    @else
        <!-- Login/Signup Layout -->
        <div class="min-h-screen bg-gradient-to-br from-yellow-50 to-orange-50 flex items-center justify-center p-4">
            <div class="w-full max-w-md">
                @yield('content')
            </div>
        </div>
    @endauth

    <!-- Initialize Lucide icons -->
    <script>
        lucide.createIcons();
    </script>

    <!-- Common JavaScript -->
    <script>
        // makeRequest — global AJAX helper used across all pages
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
            // Re-initialize Lucide icons
            if (window.lucide && window.lucide.createIcons) {
                window.lucide.createIcons();
            }
            // Start header clock
            updateHeaderClock();
            setInterval(updateHeaderClock, 1000);

            // Restore Read States
            const readNotifs = JSON.parse(localStorage.getItem('read_notifs') || '[]');
            readNotifs.forEach(id => {
                const el = document.getElementById('notif-' + id);
                if (el) {
                    el.style.backgroundColor = 'transparent';
                    el.classList.remove('unread-notif');
                }
            });
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
            const readNotifs = JSON.parse(localStorage.getItem('read_notifs') || '[]');
            if (!readNotifs.includes(id)) {
                readNotifs.push(id);
                localStorage.setItem('read_notifs', JSON.stringify(readNotifs));
            }
            const el = document.getElementById('notif-' + id);
            if (el) {
                el.style.backgroundColor = 'transparent';
                el.classList.remove('unread-notif');
            }
        }

        function markAllAsRead() {
            const items = document.querySelectorAll('.notification-item');
            const readNotifs = JSON.parse(localStorage.getItem('read_notifs') || '[]');
            
            items.forEach(item => {
                const id = item.dataset.notifId;
                if (id && !readNotifs.includes(id)) {
                    readNotifs.push(id);
                }
                item.style.backgroundColor = 'transparent';
                item.classList.remove('unread-notif');
            });
            
            localStorage.setItem('read_notifs', JSON.stringify(readNotifs));
        }
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
    </script>
    @stack('scripts')
</body>

</html>