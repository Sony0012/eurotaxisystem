/* public/assets/js/tutorial.js */
const TutorialManager = (function () {
    // VISUAL DEBUGGER (Removed UI, keeping console logs only)
    function logDebug(msg) {
        console.log("[Tutorial] " + msg);
    }

    // Helper to find sidebar links robustly by text content
    function findSidebarLink(textMatches) {
        const links = Array.from(document.querySelectorAll('.sidebar span, aside span, nav span, a span'));
        for (let link of links) {
            if (link.children.length > 0) continue;
            // Crucial: Check if the element is actually visible on the screen
            if (link.offsetWidth === 0 && link.offsetHeight === 0) continue;
            
            const text = link.textContent.trim();
            for (let match of textMatches) {
                if (text === match) {
                    logDebug(`Found sidebar link span by text: "${match}"`);
                    let btn = link.closest('a, button, .sidebar-item');
                    return btn ? btn : link;
                }
            }
        }
        return null;
    }

    // Helper to scroll smoothly inside modal overflow container
    function scrollModalToElement(elementId) {
        const modal = document.getElementById('addUnitModal');
        if (modal) modal.classList.remove('hidden');
        const target = document.getElementById(elementId);
        if (!target) return;
        const scrollContainer = modal ? modal.querySelector('.overflow-y-auto') : null;
        if (scrollContainer) {
            const topPos = target.offsetTop - scrollContainer.offsetTop - 15;
            scrollContainer.scrollTo({ top: Math.max(0, topPos), behavior: 'smooth' });
        } else {
            target.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    // Helper to find dashboard cards by their headers
    function findDashboardCard(textMatches) {
        const els = Array.from(document.querySelectorAll('h1, h2, h3, h4, h5, h6, span, p, div'));
        for (let el of els) {
            if (el.tagName === 'SCRIPT' || el.tagName === 'STYLE') continue;
            if (el.children.length > 2) continue;

            const text = el.textContent.trim().toUpperCase();
            for (let match of textMatches) {
                if (text === match.toUpperCase() || text.includes(match.toUpperCase())) {
                    logDebug(`Found dashboard text element: "${match}"`);
                    let current = el;
                    while (current && current !== document.body) {
                        const hasShadow = Array.from(current.classList).some(c => c.startsWith('shadow'));
                        if (current.classList.contains('card-hover') || 
                            current.classList.contains('rounded-2xl') ||
                            current.classList.contains('rounded-xl') ||
                            current.classList.contains('card') ||
                            (current.classList.contains('bg-gray-50') && current.classList.contains('border-l')) ||
                            (current.classList.contains('bg-white') && (current.classList.contains('rounded-lg') || hasShadow))) {
                            // Verify it's not the whole screen width unless it's a full-width chart
                            if (current.offsetWidth < window.innerWidth * 0.98) {
                                return current;
                            }
                        }
                        current = current.parentElement;
                    }
                    return el; // fallback
                }
            }
        }
        return null;
    }

    const steps = [
        {
            id: 'sidebar-dashboard',
            getElement: () => findSidebarLink(['Dashboard']),
            popover: { title: 'Dashboard Menu', description: 'Welcome! This is the main Dashboard menu. Clicking here gives you a high-level overview of your entire fleet, revenues, and active drivers.', position: 'right' }
        },
        {
            id: 'dash-total-units',
            getElement: () => findDashboardCard(['TOTAL UNITS']),
            popover: { title: 'Total Units', description: 'Here you can see the total number of cars in your fleet and how many have achieved ROI.', position: 'bottom' }
        },
        {
            id: 'dash-boundary-revenue',
            getElement: () => findDashboardCard(['BOUNDARY REVENUE']),
            popover: { title: 'Boundary Revenue', description: 'This tracks your daily and monthly boundary collections from drivers.', position: 'bottom' }
        },
        {
            id: 'dash-net-income',
            getElement: () => findDashboardCard(['NET INCOME']),
            popover: { title: 'Net Income', description: 'Your actual profit after deducting office expenses and maintenance costs.', position: 'bottom' }
        },
        {
            id: 'dash-under-maintenance',
            getElement: () => findDashboardCard(['UNDER MAINTENANCE']),
            popover: { title: 'Under Maintenance', description: 'The number of units currently in the garage for repairs or regular maintenance.', position: 'bottom' }
        },
        {
            id: 'dash-active-drivers',
            getElement: () => findDashboardCard(['ACTIVE DRIVERS']),
            popover: { title: 'Active Drivers', description: 'The total number of currently active drivers on the road.', position: 'bottom' }
        },
        {
            id: 'dash-expenses',
            getElement: () => findDashboardCard(['EXPENSES TODAY']),
            popover: { title: 'Daily Expenses', description: 'Monitor your daily outflow for office and operational expenses.', position: 'bottom' }
        },
        {
            id: 'dash-coding-units',
            getElement: () => findDashboardCard(['CODING UNITS TODAY']),
            popover: { title: 'Coding Units', description: 'Units that are restricted from operating today due to number coding.', position: 'bottom' }
        },
        {
            id: 'dash-performance',
            getElement: () => findDashboardCard(['UNIT PERFORMANCE', 'Top 10 Performers']),
            popover: { 
                title: 'Unit Performance & Insights', 
                description: 'This chart compares the actual boundary collections against the 30-day target for each unit.<br><br><b>Executive Insights</b> on the right gives AI-generated summaries regarding your fleet health and top performers.', 
                position: 'top' 
            }
        },
        {
            id: 'dash-revenue-trend',
            getElement: () => findDashboardCard(['Revenue Trend']),
            popover: { title: 'Revenue Trend', description: 'This graph shows your revenue trend over time, helping you identify peak days and performance drops.', position: 'top' }
        },
        {
            id: 'dash-expense-breakdown',
            getElement: () => findDashboardCard(['Expense Breakdown & Distribution']),
            popover: { title: 'Expense Breakdown', description: 'A visual breakdown of where your expenses are going, such as maintenance, fuel, and salaries.', position: 'top' }
        },
        {
            id: 'dash-weekly-overview',
            getElement: () => findDashboardCard(['Weekly Financial Overview']),
            popover: { title: 'Weekly Overview', description: 'Compare your income versus expenses on a weekly basis.', position: 'top' }
        },
        {
            id: 'dash-status-distribution',
            getElement: () => findDashboardCard(['Unit Status Distribution']),
            popover: { title: 'Status Distribution', description: 'A quick view of your fleet\'s current status: active, coding, or under maintenance.', position: 'top' }
        },
        {
            id: 'dash-top-drivers',
            getElement: () => findDashboardCard(['Top Performing Drivers']),
            popover: { title: 'Top Performing Drivers', description: 'A ranking of your most consistent and highest-earning drivers based on their daily boundary collections.', position: 'top' }
        },
        {
            id: 'sidebar-units',
            getElement: () => findSidebarLink(['Unit Management']),
            popover: { title: 'Unit Management', description: 'Add new cars, monitor their status, and manage the entire fleet inventory.', position: 'right' }
        },
        {
            id: 'units-stats-bar',
            route: '/units',
            getElement: () => document.getElementById('quickStatsBar'),
            popover: { title: 'Fleet Status Counters', description: 'Real-time counters showing Total Fleet Units, Active Units on the road, Units under Maintenance in the garage, and Coding Units restricted for today.', position: 'bottom' }
        },
        {
            id: 'units-filter-search',
            route: '/units',
            getElement: () => document.getElementById('tableSearchInput') ? document.getElementById('tableSearchInput').closest('.bg-white') : null,
            popover: { title: 'Search, Sort & Filters', description: 'Quickly search any car by plate number or driver name, sort A-Z, or filter by Active, Coding, Maintenance, or Vacant status.', position: 'bottom' }
        },
        {
            id: 'units-view-toggle',
            route: '/units',
            getElement: () => document.getElementById('unitViewTogglePill') || document.getElementById('btn-view-table'),
            popover: { title: 'Table & Cards View Toggle', description: 'Switch between detailed Table view and grid-based Cards view to monitor your fleet inventory based on your visual preference.', position: 'bottom' }
        },
        {
            id: 'units-cards-deepdive',
            route: '/units',
            onBeforeShow: () => { if (typeof setViewMode === 'function') setViewMode('grid'); },
            getElement: () => document.getElementById('unitsTableContainer') || document.querySelector('.grid') || document.getElementById('unitViewTogglePill'),
            popover: { title: 'Cards View Deep Dive', description: 'In Cards View, each taxi unit is presented as a visual card with real-time status badges, assigned D1/D2 driver partners, and current odometer progress.', position: 'top' }
        },
        {
            id: 'units-table-restore',
            route: '/units',
            onBeforeShow: () => { if (typeof setViewMode === 'function') setViewMode('table'); },
            getElement: () => document.getElementById('btn-view-table'),
            popover: { title: 'Switching Back to Table View', description: 'Tapping Table view restores the structured row layout with full column details for deep analysis.', position: 'bottom' }
        },
        {
            id: 'units-print-pdf-btn',
            route: '/units',
            onBeforeShow: () => { if (typeof closeTutorialPdfPreview === 'function') closeTutorialPdfPreview(); },
            getElement: () => document.getElementById('btn-print-pdf') || document.querySelector('button[onclick*="printInHiddenIframe"]'),
            popover: { title: 'Print Master List to PDF Button', description: 'Clicking this button generates an official PDF document of your entire fleet roster. Let us open the live document preview!', position: 'bottom' }
        },
        {
            id: 'units-print-pdf-preview',
            route: '/units',
            onBeforeShow: () => { if (typeof openTutorialPdfPreview === 'function') openTutorialPdfPreview(); },
            onAfterNext: () => { if (typeof closeTutorialPdfPreview === 'function') closeTutorialPdfPreview(); },
            getElement: () => document.getElementById('tutorialPrintPdfModal') ? document.querySelector('#tutorialPrintPdfModal > div') : document.getElementById('btn-print-pdf'),
            popover: { title: 'Live Master Roster PDF Deep Dive', description: 'Here is the live generated PDF document! It compiles your official fleet records, including Plate #, Engine/Chassis IDs, D1/D2 Assigned Drivers, Smart Boundary Rates, and Official Signature Lines.', position: 'top' }
        },
        {
            id: 'units-add-unit-btn',
            route: '/units',
            onBeforeShow: () => { if (typeof closeTutorialPdfPreview === 'function') closeTutorialPdfPreview(); const m = document.getElementById('addUnitModal'); if (m) m.classList.add('hidden'); },
            getElement: () => document.getElementById('btn-add-unit') || document.querySelector('button[onclick*="addUnitModal"]'),
            popover: { title: 'Add New Unit Button', description: 'Clicking this button opens the vehicle registration form to onboard a new taxi into your fleet. Let us open the modal and explore inside!', position: 'bottom' }
        },
        {
            id: 'units-add-modal-plate',
            route: '/units',
            onBeforeShow: () => scrollModalToElement('addUnitSectionBasicInfo'),
            getElement: () => document.getElementById('addUnitSectionBasicInfo') || document.getElementById('addPlateNumber'),
            popover: { title: '1. Basic Info & Plate Number', description: 'Enter the vehicle plate number (e.g. ABC 1234). The system automatically detects the MMDA coding day based on the last digit!', position: 'bottom' }
        },
        {
            id: 'units-add-modal-details',
            route: '/units',
            onBeforeShow: () => scrollModalToElement('addUnitSectionVehicleDetails'),
            getElement: () => document.getElementById('addUnitSectionVehicleDetails'),
            popover: { title: '2. Vehicle Specs (Make, Model, Year & IDs)', description: 'Specify vehicle brand (e.g. Toyota), model (e.g. Vios), year model, and official Engine & Chassis serial numbers for legal LTO compliance.', position: 'bottom' }
        },
        {
            id: 'units-add-modal-finance',
            route: '/units',
            onBeforeShow: () => scrollModalToElement('addUnitSectionFinancialInfo'),
            getElement: () => document.getElementById('addUnitSectionFinancialInfo'),
            popover: { title: '3. Financial Info & Daily Boundary Target', description: 'Set the base daily boundary collection target (e.g. ₱1,100.00) and vehicle purchase cost for automated ROI calculations.', position: 'bottom' }
        },
        {
            id: 'units-add-modal-driver-assignment',
            route: '/units',
            onBeforeShow: () => scrollModalToElement('addUnitSectionDriverAssignment'),
            getElement: () => document.getElementById('addUnitSectionDriverAssignment'),
            popover: { title: '4. Driver Assignment (Primary D1 & Secondary D2)', description: 'Search and pair active drivers to this vehicle! Assign the Primary Day Driver (D1) and optional Secondary Relief Driver (D2) right during vehicle registration.', position: 'top' }
        },
        {
            id: 'units-add-modal-coding-info',
            route: '/units',
            onBeforeShow: () => scrollModalToElement('addUnitSectionCodingInfo'),
            getElement: () => document.getElementById('addUnitSectionCodingInfo'),
            popover: { title: '5. MMDA Coding Schedule & Auto Detection', description: 'View the MMDA Metro Manila coding schedule (Mon: 1,2 | Tue: 3,4 | Wed: 5,6 | Thu: 7,8 | Fri: 9,0). The system automatically calculates the Next Coding Date and Days Remaining!', position: 'top' }
        },
        {
            id: 'units-add-modal-gps-integration',
            route: '/units',
            onBeforeShow: () => scrollModalToElement('addUnitSectionGpsIntegration'),
            getElement: () => document.getElementById('addUnitSectionGpsIntegration'),
            popover: { title: '6. Live GPS Tracker Integration (IMEI & Provider)', description: 'Configure real-time GPS tracking! Select the provider (Tracksolid Pro or AKSH Aika168) and enter the device IMEI number for live map tracking.', position: 'top' }
        },
        {
            id: 'units-add-modal-close',
            route: '/units',
            onBeforeShow: () => scrollModalToElement('addUnitSectionFooter'),
            onAfterNext: () => { const m = document.getElementById('addUnitModal'); if (m) m.classList.add('hidden'); },
            getElement: () => document.getElementById('addUnitSectionFooter') || document.querySelector('#addUnitForm button[type="submit"]'),
            popover: { title: '7. Save & Register Unit', description: 'Clicking Save Unit validates all vehicle details, pairs assigned drivers, sets up MMDA coding, and immediately registers the new car into your active fleet roster!', position: 'top' }
        },
        {
            id: 'units-table-sep',
            route: '/units',
            onBeforeShow: () => { const m = document.getElementById('addUnitModal'); if (m) m.classList.add('hidden'); if (typeof setViewMode === 'function') setViewMode('table'); },
            getElement: () => document.querySelector('.modern-table-sep thead') || document.querySelector('table thead') || document.querySelector('.modern-table-sep'),
            popover: { title: 'Fleet Master Inventory Table', description: 'Detailed table listing all taxi units in your fleet with live status indicators, assigned drivers, and boundary pricing tags.', position: 'top' }
        },
        {
            id: 'units-col-plate',
            route: '/units',
            getElement: () => document.querySelector('tbody.modern-card-tbody tr td:first-child') || document.querySelector('tbody tr td:first-child'),
            popover: { title: 'Plate Number, Motor & Chassis Serial IDs', description: 'Displays the unit plate number (e.g. AAA 4591), registered Engine/Motor number, and Chassis serial number for complete vehicle legal tracking.', position: 'top' }
        },
        {
            id: 'units-col-specs',
            route: '/units',
            getElement: () => document.querySelector('tbody.modern-card-tbody tr td:nth-child(2)') || document.querySelector('tbody tr td:nth-child(2)'),
            popover: { title: 'Vehicle Make, Model & Year', description: 'Shows the vehicle brand (e.g. Toyota), model name (e.g. Vios), model year (e.g. 2014), and NEW vehicle status tag.', position: 'top' }
        },
        {
            id: 'units-col-drivers',
            route: '/units',
            getElement: () => document.querySelector('tbody.modern-card-tbody tr td:nth-child(3)') || document.querySelector('tbody tr td:nth-child(3)'),
            popover: { title: 'Assigned Drivers (D1 & D2)', description: 'Shows the assigned Day Driver (D1) and Night Driver (D2) for each car. Vacant driver slots are highlighted for quick driver pairing.', position: 'top' }
        },
        {
            id: 'units-col-status',
            route: '/units',
            getElement: () => document.querySelector('tbody.modern-card-tbody tr td:nth-child(4)') || document.querySelector('tbody tr td:nth-child(4)'),
            popover: { title: 'Live Vehicle Status Badge', description: 'Real-time unit status indicator (Active on road, Maintenance in garage, MMDA Coding restriction, or Vacant).', position: 'top' }
        },
        {
            id: 'units-col-pricing',
            route: '/units',
            getElement: () => document.querySelector('tbody.modern-card-tbody tr td:nth-child(5)') || document.querySelector('tbody tr td:nth-child(5)'),
            popover: { title: 'Smart Boundary Rate & Pricing Tag', description: 'Displays the active daily boundary rate (e.g. ₱1,100.00) and pricing tag (Regular Rate vs Coding Discount vs Sunday Discount).', position: 'top' }
        },
        {
            id: 'units-col-pms-alert',
            route: '/units',
            getElement: () => document.querySelector('.modern-sub-row'),
            popover: { title: '5,000 KM Maintenance & PMS Odometer Alert', description: 'Tracks mileage progress towards 5,000 km oil change intervals and alerts you automatically with a red warning banner when PMS is overdue!', position: 'top' }
        },
        {
            id: 'units-actions-dropdown-open',
            route: '/units',
            clickTrap: true, // FLAG: tells onPopoverRender to place a click-trap div above Driver.js overlay
            onBeforeShow: () => {
                if (window._step38ClickListener) {
                    window.removeEventListener('click', window._step38ClickListener, true);
                    window._step38ClickListener = null;
                }
                // Ensure dropdown is CLOSED and any leftover portal/trap is cleaned up
                window._tutorialUnportalDropdown();
                const oldTrap = document.getElementById('__tutorial-click-trap');
                if (oldTrap) oldTrap.remove();
                // NOTE: Do NOT create trap here — Driver.js hasn't scrolled yet.
                // Trap will be placed in onPopoverRender after Driver.js completes.
            },
            onAfterNext: () => {
                // Clean up trap if user pressed Next Step instead of clicking 3-dots
                const trap = document.getElementById('__tutorial-click-trap');
                if (trap) trap.remove();
            },
            getElement: () => document.querySelector('tbody tr button[onclick*="toggleUnitDropdown"]'),
            popover: { title: 'Unit Actions Menu (⋮)', description: '👆 Click the 3-dots (⋮) icon now to open the Actions menu and see the 3 management controls available!', position: 'left-center' }
        },
        {
            id: 'units-actions-edit',
            route: '/units',
            onBeforeShow: () => { 
                const btn = document.querySelector('tbody tr button[onclick*="toggleUnitDropdown"]');
                if (btn) btn.scrollIntoView({ behavior: 'auto', block: 'center' });
                setTimeout(() => {
                    window._tutorialPortalDropdown();
                }, 100);
            },
            getElement: () => document.querySelector('.unit-action-dropdown--portal button[onclick*="editUnit"]') || document.querySelector('.unit-action-dropdown button[onclick*="editUnit"]') || document.querySelector('tbody tr button[onclick*="toggleUnitDropdown"]'),
            popover: { title: '✏️ Edit Unit Action', description: 'Clicking Edit Unit opens the unit editor modal where you can update vehicle specs, plate numbers, daily boundary rates, or re-assign drivers.', position: 'left-center' }
        },
        {
            id: 'units-edit-modal-overview',
            route: '/units',
            onBeforeShow: () => {
                window._tutorialUnportalDropdown();
                if (typeof editUnit === 'function') editUnit(1);
            },
            onAfterNext: () => {
                const modal = document.getElementById('editUnitModal');
                if (modal) modal.classList.add('hidden');
            },
            getElement: () => document.querySelector('#editUnitModal > div') || document.getElementById('editUnitModal'),
            popover: { title: '✏️ Deep-Dive: Edit Unit Form Modal', description: 'Welcome inside the Edit Unit Modal! Here you can modify plate numbers, chassis/motor IDs, status, daily boundary targets, driver pairings, and GPS tracker details.', position: 'bottom' }
        },
        {
            id: 'units-actions-reset',
            route: '/units',
            onBeforeShow: () => { 
                const editModal = document.getElementById('editUnitModal');
                if (editModal) editModal.classList.add('hidden');
                const btn = document.querySelector('tbody tr button[onclick*="toggleUnitDropdown"]');
                if (btn) btn.scrollIntoView({ behavior: 'auto', block: 'center' });
                setTimeout(() => {
                    window._tutorialPortalDropdown();
                }, 100);
            },
            getElement: () => document.querySelector('.unit-action-dropdown--portal form[action*="reset-health"]') || document.querySelector('.unit-action-dropdown form[action*="reset-health"]') || document.querySelector('tbody tr button[onclick*="toggleUnitDropdown"]'),
            popover: { title: '🔄 Reset Service Action', description: 'Resets the 5,000 km oil change mileage counter back to zero after mechanic maintenance or oil replacement is completed!', position: 'left-center' }
        },
        {
            id: 'units-actions-archive',
            route: '/units',
            onBeforeShow: () => { 
                const btn = document.querySelector('tbody tr button[onclick*="toggleUnitDropdown"]');
                if (btn) btn.scrollIntoView({ behavior: 'auto', block: 'center' });
                setTimeout(() => {
                    window._tutorialPortalDropdown();
                }, 100);
            },
            getElement: () => document.querySelector('.unit-action-dropdown--portal form[action*="destroy"]') || document.querySelector('.unit-action-dropdown form[action*="destroy"]') || document.querySelector('tbody tr button[onclick*="toggleUnitDropdown"]'),
            popover: { title: '📦 Archive Unit Action', description: 'Safely deactivates and archives the taxi unit without deleting its historical financial, boundary, and driver records.', position: 'left-center' }
        },
        {
            id: 'units-row-click-deepdive',
            route: '/units',
            onBeforeShow: () => { 
                window._tutorialUnportalDropdown();
                stopActionDropdownSync();
                const dd = document.querySelector('.unit-action-dropdown'); 
                if (dd) dd.classList.add('hidden'); 
                const container = document.getElementById('unitsTableScrollContainer') || document.querySelector('.overflow-x-auto');
                if (container) container.style.overflow = '';
                const firstUnit = document.querySelector('tr.modern-row-has-sub, tr.modern-row');
                if (firstUnit && typeof viewUnitDetails === 'function') {
                    const onclickStr = firstUnit.getAttribute('onclick') || '';
                    const match = onclickStr.match(/viewUnitDetails\((\d+)\)/);
                    if (match) viewUnitDetails(match[1]);
                    else viewUnitDetails(1);
                }
            },
            getElement: () => document.querySelector('#unitDetailsContent .bg-gradient-to-r') || document.querySelector('#unitDetailsModal > div'),
            popover: { title: 'Unit Overview Header & Status Badge', description: 'Welcome inside the Unit Details profile! This summary header displays the unit plate number, status indicator (Active, Maintenance, Coding), vehicle make and model, and current daily boundary rate.', position: 'bottom' }
        },
        {
            id: 'unit-details-quick-stats',
            route: '/units',
            onBeforeShow: () => {
                const m = document.getElementById('unitDetailsModal');
                if (!m || m.classList.contains('hidden')) {
                    if (typeof viewUnitDetails === 'function') viewUnitDetails(1);
                }
            },
            getElement: () => document.querySelector('#overview-tab .grid-cols-2') || document.querySelector('#unitDetailsModal > div'),
            popover: { title: 'Quick Operational Performance Stats', description: 'Shows key operational indicators at a glance: assigned driver count (e.g. 2/2), days until next MMDA coding date, financial ROI percentage, and total maintenance jobs completed.', position: 'bottom' }
        },
        {
            id: 'unit-details-basic-info',
            route: '/units',
            onBeforeShow: () => {
                const m = document.getElementById('unitDetailsModal');
                if (!m || m.classList.contains('hidden')) {
                    if (typeof viewUnitDetails === 'function') viewUnitDetails(1);
                }
            },
            getElement: () => document.querySelector('#overview-tab .grid-cols-1 > div:first-child') || document.querySelector('#unitDetailsModal > div'),
            popover: { title: 'Vehicle Basic Information Card', description: 'Contains official vehicle specifications, plate number, make, model year, created by staff member, last updated timestamp, and active boundary rate.', position: 'top' }
        },
        {
            id: 'unit-details-drivers-tab',
            route: '/units',
            onBeforeShow: () => {
                const m = document.getElementById('unitDetailsModal');
                if (!m || m.classList.contains('hidden')) {
                    if (typeof viewUnitDetails === 'function') viewUnitDetails(1);
                }
                if (typeof showTab === 'function') showTab('drivers');
            },
            getElement: () => document.getElementById('tabContent') || document.querySelector('#unitDetailsModal > div'),
            popover: { title: 'Assigned Drivers Profile & Targets', description: 'Displays full driver contact cards, license numbers, license expiry dates, emergency contact numbers, and daily boundary targets.', position: 'top' }
        },
        {
            id: 'unit-details-coding-tab',
            route: '/units',
            onBeforeShow: () => {
                const m = document.getElementById('unitDetailsModal');
                if (!m || m.classList.contains('hidden')) {
                    if (typeof viewUnitDetails === 'function') viewUnitDetails(1);
                }
                if (typeof showTab === 'function') showTab('coding');
            },
            getElement: () => document.getElementById('tabContent') || document.querySelector('#unitDetailsModal > div'),
            popover: { title: 'MMDA Coding Schedule & Restriction Status', description: 'Monitors Metro Manila MMDA coding rules, next scheduled coding date, and days remaining until restriction.', position: 'top' }
        },
        {
            id: 'unit-details-boundary-tab',
            route: '/units',
            onBeforeShow: () => {
                const m = document.getElementById('unitDetailsModal');
                if (!m || m.classList.contains('hidden')) {
                    if (typeof viewUnitDetails === 'function') viewUnitDetails(1);
                }
                if (typeof showTab === 'function') showTab('boundary');
            },
            getElement: () => document.getElementById('tabContent') || document.querySelector('#unitDetailsModal > div'),
            popover: { title: 'Historical Boundary Collection Receipts', description: 'Complete daily boundary payment history, actual collected amounts, shortages/excesses, payment dates, and cashier remarks.', position: 'top' }
        },
        {
            id: 'unit-details-maint-tab',
            route: '/units',
            onBeforeShow: () => {
                const m = document.getElementById('unitDetailsModal');
                if (!m || m.classList.contains('hidden')) {
                    if (typeof viewUnitDetails === 'function') viewUnitDetails(1);
                }
                if (typeof showTab === 'function') showTab('maintenance');
            },
            getElement: () => document.getElementById('tabContent') || document.querySelector('#unitDetailsModal > div'),
            popover: { title: 'Maintenance & Mechanical Repair Logs', description: 'Full breakdown of past repairs, oil changes, mechanic names, total repair costs, and itemized spare parts subtotal.', position: 'top' }
        },
        {
            id: 'unit-details-location-tab',
            route: '/units',
            onBeforeShow: () => {
                const m = document.getElementById('unitDetailsModal');
                if (!m || m.classList.contains('hidden')) {
                    if (typeof viewUnitDetails === 'function') viewUnitDetails(1);
                }
                if (typeof showTab === 'function') showTab('location');
            },
            getElement: () => document.getElementById('tabContent') || document.querySelector('#unitDetailsModal > div'),
            popover: { title: 'Live GPS Map Tracking & Device Status', description: 'Displays real-time GPS map coordinates, device IMEI number, signal strength, and live vehicle location tracking!', position: 'top' }
        },
        {
            id: 'unit-details-close',
            route: '/units',
            onBeforeShow: () => {
                const m = document.getElementById('unitDetailsModal');
                if (!m || m.classList.contains('hidden')) {
                    if (typeof viewUnitDetails === 'function') viewUnitDetails(1);
                }
            },
            onAfterNext: () => {
                if (typeof closeUnitDetailsModal === 'function') closeUnitDetailsModal();
            },
            getElement: () => document.querySelector('#unitDetailsModal button[onclick*="closeUnitDetailsModal"]') || document.querySelector('#unitDetailsModal > div'),
            popover: { title: 'Close Unit Details Profile', description: 'Clicking close returns you to the main fleet management dashboard.', position: 'bottom' }
        },
        {
            id: 'sidebar-drivers',
            getElement: () => findSidebarLink(['Driver Management']),
            popover: { title: 'Driver Management', description: 'Register new drivers, handle bans, contracts, and debt records.', position: 'right' }
        },
        {
            id: 'sidebar-tracking',
            getElement: () => findSidebarLink(['Live Tracking']),
            popover: { title: 'Live Tracking', description: 'Track your units via real-time GPS map integration.', position: 'right' }
        },
        {
            id: 'sidebar-franchise',
            getElement: () => findSidebarLink(['Franchise']),
            popover: { title: 'Franchise Records', description: 'Manage franchise documents and expiration dates.', position: 'right' }
        },
        {
            id: 'sidebar-boundaries',
            getElement: () => findSidebarLink(['Boundaries']),
            popover: { title: 'Boundaries', description: 'Record daily boundary collections and driver remittances.', position: 'right' }
        },
        {
            id: 'sidebar-maintenance',
            getElement: () => findSidebarLink(['Maintenance']),
            popover: { title: 'Maintenance', description: 'Log car repairs, oil changes, and parts inventory.', position: 'right' }
        },
        {
            id: 'sidebar-coding',
            getElement: () => findSidebarLink(['Coding Management']),
            popover: { title: 'Coding Management', description: 'Set and monitor MMDA number coding schemes.', position: 'right' }
        },
        {
            id: 'sidebar-behavior',
            getElement: () => findSidebarLink(['Driver Behavior']),
            popover: { title: 'Driver Behavior', description: 'Track incentives, performance charts, and accident reports.', position: 'right' }
        },
        {
            id: 'sidebar-expenses',
            getElement: () => findSidebarLink(['Office Expenses']),
            popover: { title: 'Office Expenses', description: 'Log daily operational expenses and overhead.', position: 'right' }
        },
        {
            id: 'sidebar-salary',
            getElement: () => findSidebarLink(['Salary Management']),
            popover: { title: 'Salary Management', description: 'Process payroll and employee salaries.', position: 'right' }
        },
        {
            id: 'sidebar-analytics',
            getElement: () => findSidebarLink(['Analytics']),
            popover: { title: 'Analytics', description: 'Generate advanced financial and operational reports.', position: 'right' }
        },
        {
            id: 'sidebar-profitability',
            getElement: () => findSidebarLink(['Unit Profitability']),
            popover: { title: 'Unit Profitability', description: 'Track ROI and net profit for each specific car.', position: 'right' }
        },
        {
            id: 'sidebar-staff',
            getElement: () => findSidebarLink(['General Staff Records', 'Staff']),
            popover: { title: 'Staff Records', description: 'Manage system administrators and mobile app users.', position: 'right' }
        },
        {
            id: 'sidebar-profile',
            getElement: () => findSidebarLink(['Take the Tour Again', 'Logout']),
            popover: { title: 'Profile Menu', description: 'Access your account settings or retake this tour anytime from here.', position: 'right' }
        }
    ];

    let driverObj = null;

    function showProtectionToast(msg) {
        let toast = document.getElementById('tutorial-protection-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'tutorial-protection-toast';
            toast.className = 'fixed top-4 right-4 z-[9999999] bg-slate-900/95 text-amber-300 border border-amber-500/40 text-xs font-bold px-4 py-3 rounded-2xl shadow-2xl backdrop-blur-md flex items-center gap-2.5 transition-all duration-300';
            document.body.appendChild(toast);
        }
        toast.innerHTML = `<span class="relative flex h-2.5 w-2.5"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span></span> <span>${msg}</span>`;
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';
        
        clearTimeout(window.__protectionToastTimeout);
        window.__protectionToastTimeout = setTimeout(() => {
            if (toast) {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-10px)';
            }
        }, 3500);
    }

    function enableTutorialDataProtection() {
        if (window.__tutorialProtectionEnabled) return;
        window.__tutorialProtectionEnabled = true;

        logDebug("Enabling Global Tutorial Data Protection (Dummy Sandbox Mode)");

        // 1. Intercept fetch API calls (DELETE, POST, PUT, PATCH requests)
        const originalFetch = window.fetch;
        window.fetch = function (resource, config) {
            const isTutorialActive = !!localStorage.getItem('tutorial_current_step') || window.location.search.includes('tutorial=1');
            if (isTutorialActive && config && config.method) {
                const method = config.method.toUpperCase();
                const url = typeof resource === 'string' ? resource : (resource ? resource.url : '');
                
                // Allow GET read requests and tutorial completion endpoint
                if (method !== 'GET' && !url.includes('/api/tutorial/complete') && !url.includes('/heartbeat')) {
                    logDebug(`[Tutorial Protection] Intercepted live ${method} request to: ${url}`);
                    showProtectionToast(`🛡️ Tutorial Sandbox: ${method} request simulated with dummy data. Real DB protected!`);
                    
                    return Promise.resolve(new Response(JSON.stringify({
                        success: true,
                        tutorial_mock: true,
                        message: 'Tutorial mode: Action simulated on dummy data. Real database untouched!'
                    }), {
                        status: 200,
                        headers: { 'Content-Type': 'application/json' }
                    }));
                }
            }
            return originalFetch.apply(this, arguments);
        };

        // 2. Intercept XMLHttpRequest (for jQuery / Axios / AJAX requests)
        const originalXhrOpen = XMLHttpRequest.prototype.open;
        const originalXhrSend = XMLHttpRequest.prototype.send;
        
        XMLHttpRequest.prototype.open = function(method, url) {
            this._tutorialMethod = (method || 'GET').toUpperCase();
            this._tutorialUrl = url || '';
            return originalXhrOpen.apply(this, arguments);
        };

        XMLHttpRequest.prototype.send = function(body) {
            const isTutorialActive = !!localStorage.getItem('tutorial_current_step');
            if (isTutorialActive && this._tutorialMethod !== 'GET' && !this._tutorialUrl.includes('/api/tutorial/complete') && !this._tutorialUrl.includes('/heartbeat')) {
                logDebug(`[Tutorial Protection] Intercepted XHR ${this._tutorialMethod} to: ${this._tutorialUrl}`);
                showProtectionToast(`🛡️ Tutorial Sandbox: Live ${this._tutorialMethod} call prevented!`);
                
                try {
                    Object.defineProperty(this, 'status', { value: 200, writable: false });
                    Object.defineProperty(this, 'responseText', { value: JSON.stringify({ success: true, tutorial_mock: true, message: 'Simulated' }), writable: false });
                    Object.defineProperty(this, 'readyState', { value: 4, writable: false });
                } catch(e) {}
                
                if (typeof this.onreadystatechange === 'function') {
                    this.onreadystatechange();
                }
                if (typeof this.onload === 'function') {
                    this.onload();
                }
                return;
            }
            return originalXhrSend.apply(this, arguments);
        };

        // 3. Intercept Form Submissions (prevent forms from submitting & refreshing/deleting)
        document.addEventListener('submit', function(e) {
            const isTutorialActive = !!localStorage.getItem('tutorial_current_step');
            if (isTutorialActive) {
                const form = e.target;
                if (form && !form.action.includes('/api/tutorial/complete')) {
                    e.preventDefault();
                    e.stopPropagation();
                    logDebug(`[Tutorial Protection] Intercepted native form submit to: ${form.action}`);
                    showProtectionToast(`🛡️ Tutorial Sandbox: Form submission simulated using dummy data!`);
                    
                    // Close any active modal gracefully
                    const modal = form.closest('.modal, [id*="Modal"]');
                    if (modal) modal.classList.add('hidden');
                }
            }
        }, true);
    }

    function init(tutorialCompleted) {
        logDebug("Tutorial init() called. Completed status: " + tutorialCompleted);
        if (!window.driver) {
            logDebug("ERROR: window.driver is undefined! Driver.js CDN failed to load.");
            return;
        }

        if (tutorialCompleted && !localStorage.getItem('tutorial_force_restart')) {
            logDebug("Tutorial already completed. Exiting.");
            return;
        }

        enableTutorialDataProtection();

        const currentStepIndex = parseInt(localStorage.getItem('tutorial_current_step') || '0', 10);
        logDebug("Current step index loaded: " + currentStepIndex);
        
        if (currentStepIndex === 0 && !localStorage.getItem('tutorial_welcome_shown')) {
            logDebug("Showing welcome modal.");
            showWelcomeModal();
        } else {
            startTutorial(currentStepIndex);
        }
    }

    function showWelcomeModal() {
        if(document.getElementById('tutorial-welcome-modal')) return;
        const modalHtml = `
            <div id="tutorial-welcome-modal" class="fixed inset-0 flex items-center justify-center p-4" style="z-index: 999999; background: rgba(17, 24, 39, 0.85); backdrop-filter: blur(12px);">
                <div id="tutorial-welcome-content" class="bg-gray-900 rounded-3xl shadow-2xl p-8 max-w-sm w-full text-center relative border border-gray-700" style="animation: modal-pop-in 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;">
                    <div class="w-20 h-20 bg-blue-500/10 text-blue-400 rounded-full flex items-center justify-center mx-auto mb-6 shadow-[0_0_30px_rgba(59,130,246,0.3)]">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h2 class="text-3xl font-black text-white mb-3 tracking-tight">Welcome!</h2>
                    <p class="text-gray-400 mb-8 leading-relaxed text-sm">This quick tour will show you how to navigate and use the system effectively. We're glad you're here.</p>
                    <div class="flex flex-col gap-3">
                        <button id="tutorial-start-btn" class="w-full py-3.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5">Start Tour</button>
                        <button id="tutorial-skip-btn" class="w-full py-3.5 bg-transparent hover:bg-gray-800 text-gray-400 hover:text-white font-semibold rounded-xl transition-all border border-gray-700">Skip for now</button>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modalHtml);

        document.getElementById('tutorial-start-btn').addEventListener('click', () => {
            logDebug("User clicked Start Tour");
            document.getElementById('tutorial-welcome-modal').remove();
            localStorage.setItem('tutorial_welcome_shown', '1');
            startTutorial(0);
        });

        document.getElementById('tutorial-skip-btn').addEventListener('click', () => {
            logDebug("User skipped tour");
            document.getElementById('tutorial-welcome-modal').remove();
            markTutorialComplete();
        });
    }

    function initProgressBar(totalSteps) {
        let progressBar = document.getElementById('tutorial-global-progress');
        if (!progressBar) {
            const dotsHtml = Array.from({length: totalSteps}).map((_, i) => `<div id="tut-dot-${i}" class="tutorial-progress-dot"></div>`).join('');
            document.body.insertAdjacentHTML('beforeend', `
                <div id="tutorial-global-progress">
                    <div class="tutorial-progress-track">
                        <div id="tutorial-progress-fill" class="tutorial-progress-fill"></div>
                        ${dotsHtml}
                    </div>
                    <button class="tutorial-exit-btn" onclick="if(window.TutorialManager) window.TutorialManager.finishTutorial();">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Exit Tour
                    </button>
                </div>
            `);
        }
    }

    function updateProgress(index, totalSteps) {
        for (let i = 0; i < totalSteps; i++) {
            const dot = document.getElementById(`tut-dot-${i}`);
            if (dot) {
                if (i <= index) dot.classList.add('active');
                else dot.classList.remove('active');
            }
        }
        const fill = document.getElementById('tutorial-progress-fill');
        if (fill) {
            const percentage = (index / (totalSteps - 1)) * 100;
            fill.style.width = `${percentage}%`;
        }
    }

    function startTutorial(stepIndex) {
        logDebug(`startTutorial called with stepIndex: ${stepIndex}`);
        
        if (stepIndex >= steps.length) {
            logDebug("Reached end of steps. Finishing tutorial.");
            finishTutorial();
            return;
        }

        const step = steps[stepIndex];
        logDebug(`Attempting to find element for step: ${step.id}`);

        // Auto-navigate to step route if specified and browser is not currently on that route
        if (step.route && !window.location.pathname.startsWith(step.route)) {
            logDebug(`Navigating to route ${step.route} for step '${step.id}'`);
            localStorage.setItem('tutorial_current_step', stepIndex.toString());
            window.location.href = step.route;
            return;
        }

        // Run onBeforeShow hook if defined
        if (typeof step.onBeforeShow === 'function') {
            try {
                step.onBeforeShow();
            } catch (e) {
                logDebug(`Error in onBeforeShow for step ${step.id}: ${e.message}`);
            }
        }

        const targetElement = step.getElement ? step.getElement() : null;
        
        if (!targetElement) {
            logDebug(`WARNING: Target element for step '${step.id}' not found. Auto-skipping to next step.`);
            const nextIndex = stepIndex + 1;
            localStorage.setItem('tutorial_current_step', nextIndex);
            startTutorial(nextIndex);
            return;
        }

        // Apply a unique ID to guarantee Driver.js finds the exact DOM element via CSS selector
        const dynamicId = `tutorial-active-target-${stepIndex}`;
        targetElement.id = dynamicId;
        logDebug(`Target element found. Assigned ID: #${dynamicId}`);

        const isLastStep = stepIndex === steps.length - 1;

        initProgressBar(steps.length);
        updateProgress(stepIndex, steps.length);

        try {
            logDebug(`Initializing Driver.js for #${dynamicId}`);
            driverObj = window.driver.js.driver({
                showProgress: false,
                allowClose: false,
                overlayColor: 'rgba(15, 23, 42, 0.8)',
                popoverOffset: 80, // Move the popover further away to give the arrow space
                animate: true,
                smoothScroll: true,
                stagePadding: 6,
                keyboardControl: true,
                steps: [
                    {
                        element: `#${dynamicId}`,
                        popover: {
                            title: step.popover.title,
                            description: step.popover.description,
                            position: step.popover.position || 'right',
                            showButtons: ['close'], // Force custom click flow by removing next/prev buttons inside Driver
                            closeBtnText: '', 
                        }
                    }
                ],
                onPopoverRender: (popover, { config, state }) => {
                    try {
                        logDebug("onPopoverRender triggered.");
                        const currentStep = stepIndex + 1;
                        const totalSteps = steps.length;
                        
                        // Depending on driver.js version, popover might be the wrapper itself or an object containing it
                        let wrapper = popover.wrapper || popover;
                        
                        if (wrapper && wrapper.classList) {
                            wrapper.classList.add('tutorial-force-click');
                        }

                        // ── CLICK-TRAP for steps that need user to click a DOM element ──────────────────
                        // onPopoverRender fires AFTER Driver.js has scrolled the element into view.
                        // This is the ONLY safe place to get the element's final viewport coordinates.
                        if (step.clickTrap) {
                            // Remove any stale trap first
                            const stale = document.getElementById('__tutorial-click-trap');
                            if (stale) stale.remove();

                            // Wait 300ms for smooth-scroll to fully settle before measuring position
                            setTimeout(() => {
                                const trapBtn = document.querySelector('tbody tr button[onclick*="toggleUnitDropdown"]');
                                if (!trapBtn) { logDebug('clickTrap: button not found'); return; }

                                const r = trapBtn.getBoundingClientRect();
                                logDebug(`clickTrap placing at top:${r.top} left:${r.left} w:${r.width} h:${r.height}`);

                                if (r.width === 0 || r.height === 0) { logDebug('clickTrap: button has no size, skipping'); return; }

                                const trap = document.createElement('div');
                                trap.id = '__tutorial-click-trap';
                                trap.title = 'Click the 3-dots to open Actions menu';
                                // Use setProperty to correctly apply !important — cssText with !important is IGNORED by browsers
                                trap.style.setProperty('position', 'fixed', 'important');
                                trap.style.setProperty('top',    (r.top  - 8) + 'px', 'important');
                                trap.style.setProperty('left',   (r.left - 8) + 'px', 'important');
                                trap.style.setProperty('width',  (r.width + 16) + 'px', 'important');
                                trap.style.setProperty('height', (r.height + 16) + 'px', 'important');
                                // z-index 9999998 — ABOVE Driver.js overlay (~10000) and popover (100005)
                                trap.style.setProperty('z-index',       '9999998',                  'important');
                                trap.style.setProperty('cursor',        'pointer',                  'important');
                                // rgba with near-zero alpha so it IS a painted surface (receives events)
                                // but is invisible to the user
                                trap.style.setProperty('background',    'rgba(59,130,246,0.001)',   'important');
                                trap.style.setProperty('border-radius', '50%',                      'important');

                                trap.addEventListener('click', (e) => {
                                    e.stopPropagation();
                                    logDebug('clickTrap: clicked! Opening portal dropdown.');
                                    trap.remove();
                                    window._tutorialPortalDropdown();
                                    const idx = parseInt(localStorage.getItem('tutorial_current_step') || '0');
                                    if (window.TutorialManager) {
                                        window.TutorialManager.moveToNextStep(idx);
                                    }
                                });

                                document.body.appendChild(trap);
                                logDebug('clickTrap: trap appended to body, z-index 9999998');
                            }, 300);
                        }
                        // ────────────────────────────────────────────────────────────────────────────────

                        // Inject Step number
                        const titleEl = popover.title || wrapper.querySelector('.driver-popover-title');
                        if (titleEl && !titleEl.querySelector('.tutorial-progress-text')) {
                            titleEl.insertAdjacentHTML('afterbegin', `<span class="tutorial-progress-text">Step ${currentStep} of ${totalSteps}</span>`);
                        }

                        // Replace close button with an X icon
                        const closeBtn = popover.closeButton || popover.closeBtn || wrapper.querySelector('.driver-popover-close-btn');
                        if (closeBtn) {
                            closeBtn.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>`;
                            closeBtn.onclick = () => finishTutorial();
                        }

                        // Add custom Next/Finish and Skip buttons
                        const footerEl = popover.footer || wrapper.querySelector('.driver-popover-footer');
                        if (footerEl && !footerEl.querySelector('.tutorial-next-btn')) {
                            const nextText = isLastStep ? "Finish Tour 🎉" : "Next Step →";
                            const btnAction = isLastStep ? "if(window.TutorialManager) window.TutorialManager.finishTutorial();" : "if(window.TutorialManager) window.TutorialManager.moveToNextStep(" + stepIndex + ");";
                            footerEl.insertAdjacentHTML('beforeend', `
                                <button class="tutorial-next-btn" style="width:100%; margin-top:8px; padding:10px 16px; background-color: #2563eb !important; color: white !important; font-weight: 700 !important; border-radius: 8px !important; border: none !important; cursor: pointer !important; font-size: 0.9rem !important; shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.3) !important;" onclick="${btnAction}">${nextText}</button>
                                <button class="tutorial-skip-link" style="width:100%; margin-top:6px; background: none !important; border: none !important; color: #9ca3af !important; text-shadow: none !important; font-size: 0.75rem !important; cursor: pointer !important;" onclick="if(window.TutorialManager) window.TutorialManager.finishTutorial();">Skip Entire Tour</button>
                            `);
                        }

                        // Explicitly hide the native Next/Done AND Previous buttons to avoid Driver.js interference
                        const nextBtn = popover.nextButton || popover.nextBtn || wrapper.querySelector('.driver-popover-next-btn');
                        if (nextBtn) {
                            nextBtn.style.setProperty('display', 'none', 'important');
                        }

                        const prevBtn = popover.previousButton || popover.prevBtn || wrapper.querySelector('.driver-popover-prev-btn');
                        if (prevBtn) {
                            prevBtn.style.setProperty('display', 'none', 'important');
                        }

                        // Inject custom Previous button (only if not on the first step)
                        if (stepIndex > 0 && footerEl && !footerEl.querySelector('.tutorial-custom-prev-btn')) {
                            const mainNextBtn = footerEl.querySelector('.tutorial-next-btn');
                            const prevHtml = `<button class="tutorial-custom-prev-btn" style="background:none !important; border:none !important; color:#9ca3af !important; font-size:0.85rem !important; font-weight:600 !important; cursor:pointer !important; margin-bottom:5px; padding:0 !important; text-align:left; width:100%;" onclick="if(window.TutorialManager) window.TutorialManager.moveToPrevStep(${stepIndex});">&larr; Previous</button>`;
                            
                            if (mainNextBtn) {
                                mainNextBtn.insertAdjacentHTML('beforebegin', prevHtml);
                            } else {
                                footerEl.insertAdjacentHTML('afterbegin', prevHtml);
                            }
                        }

                        // Inject Curved Arrow SVG
                        if (wrapper) {
                            // Wait for Driver.js to finish moving the popover
                            setTimeout(() => {
                                if (!document.body.contains(wrapper)) return;

                                let pos = step.popover.position || 'right';
                                
                                // DYNAMIC POSITION CALCULATION: driver.js might fallback to another position if there's no space.
                                if (targetElement && wrapper) {
                                    const tRect = targetElement.getBoundingClientRect();
                                    const pRect = wrapper.getBoundingClientRect();
                                    
                                    const tCenter = { x: tRect.left + tRect.width / 2, y: tRect.top + tRect.height / 2 };
                                    const pCenter = { x: pRect.left + pRect.width / 2, y: pRect.top + pRect.height / 2 };
                                    
                                    const dx = pCenter.x - tCenter.x;
                                    const dy = pCenter.y - tCenter.y;
                                    
                                    if (Math.abs(dx) > Math.abs(dy)) {
                                        pos = dx > 0 ? 'right' : 'left';
                                    } else {
                                        pos = dy > 0 ? 'bottom' : 'top';
                                    }
                                    logDebug(`Dynamic position detected (delayed): ${pos}`);
                                }

                                let pathD = "M 10 90 Q 30 10 90 20";
                                let polyPoints = "90,20 80,15 85,28";
                                
                                if (pos === 'right') {
                                    // Pointing LEFT (towards target on left)
                                    pathD = "M 90 70 Q 50 10 10 30";
                                    polyPoints = "10,30 25,25 20,40";
                                } else if (pos === 'left') {
                                    // Pointing RIGHT
                                    pathD = "M 10 70 Q 50 10 90 30";
                                    polyPoints = "90,30 75,25 80,40";
                                } else if (pos === 'bottom') {
                                    // Pointing UP
                                    pathD = "M 50 90 Q 20 50 50 10";
                                    polyPoints = "50,10 40,25 60,25";
                                } else if (pos === 'top') {
                                    // Pointing DOWN
                                    pathD = "M 50 10 Q 80 50 50 90";
                                    polyPoints = "50,90 40,75 60,75";
                                }

                                const arrowSvg = `
                                    <div class="tutorial-arrow-wrapper arrow-pos-${pos} arrow-fade-in">
                                        <svg class="tutorial-curved-arrow" viewBox="0 0 100 100">
                                            <path d="${pathD}" stroke-dasharray="8,8" />
                                            <polygon points="${polyPoints}" fill="#60a5fa" />
                                        </svg>
                                    </div>
                                `;
                                if (!wrapper.querySelector('.tutorial-arrow-wrapper')) {
                                    wrapper.insertAdjacentHTML('afterbegin', arrowSvg);
                                }
                            }, 350); // 350ms to allow smooth popover transition first
                        }
                        logDebug("onPopoverRender finished successfully.");
                    } catch (err) {
                        logDebug("ERROR in onPopoverRender: " + err.message);
                    }
                },
                onDestroyStarted: () => {
                    logDebug("onDestroyStarted triggered.");
                    if(driverObj) driverObj.destroy();
                    markTutorialComplete();
                }
            });

            // Also allow clicking the actual highlighted element to proceed
            const clickHandler = (e) => {
                logDebug(`Target element #${dynamicId} clicked directly.`);
                
                // Determine if we should allow native behavior (if it's a link or an in-page button with onclick)
                let shouldNavigate = false;
                const linkEl = targetElement.tagName === 'A' ? targetElement : targetElement.closest('a');
                const btnEl = targetElement.tagName === 'BUTTON' ? targetElement : targetElement.closest('button');
                
                if (linkEl && linkEl.href) {
                    const currentUrl = window.location.href.split('?')[0].split('#')[0];
                    const linkUrl = linkEl.href.split('?')[0].split('#')[0];
                    if (linkUrl !== currentUrl && !linkUrl.includes('javascript:')) {
                        shouldNavigate = true;
                    }
                }
                
                if (btnEl && (btnEl.getAttribute('onclick') || btnEl.onclick || btnEl.type === 'button')) {
                    shouldNavigate = true;
                }

                if (!shouldNavigate) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    logDebug("Native click behavior blocked (Tutorial mode).");
                } else {
                    logDebug("Native click behavior allowed for element.");
                }
                
                // CRITICAL FIX: Immediately save progress BEFORE the browser navigates away on links!
                const nextIndex = stepIndex + 1;
                localStorage.setItem('tutorial_current_step', nextIndex.toString());

                if (targetElement && targetElement.id !== 'unitDetailsModal' && targetElement.id !== 'addUnitModal' && !targetElement.classList.contains('fixed') && window.getComputedStyle(targetElement).position !== 'fixed') {
                    if (targetElement.style.position !== 'relative' && targetElement.style.position !== 'absolute') {
                        targetElement.style.position = 'relative';
                        targetElement.style.overflow = 'hidden';
                    }
                }
                
                const ripple = document.createElement('div');
                ripple.className = 'tutorial-ripple';
                const rect = targetElement.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height) * 2;
                ripple.style.position = 'absolute';
                ripple.style.pointerEvents = 'none';
                ripple.style.width = `${size}px`;
                ripple.style.height = `${size}px`;
                ripple.style.left = `${e.clientX - rect.left - size/2}px`;
                ripple.style.top = `${e.clientY - rect.top - size/2}px`;
                targetElement.appendChild(ripple);

                // Destroy driver early so it doesn't block the click navigation
                if (driverObj) {
                    driverObj.destroy();
                }

                setTimeout(() => {
                    if (ripple.parentNode) ripple.remove();
                    // If it was a cross-page link, the page is unloading and this won't matter.
                    // If it was an on-page click, we manually start the next step.
                    if (nextIndex >= steps.length) {
                        finishTutorial();
                    } else {
                        startTutorial(nextIndex);
                    }
                }, 400);
                targetElement.removeEventListener('click', clickHandler, true);
            };
            const tag = targetElement ? targetElement.tagName.toUpperCase() : '';
            if (['A', 'BUTTON', 'INPUT', 'SELECT'].includes(tag) || (targetElement && (targetElement.classList.contains('cursor-pointer') || targetElement.onclick))) {
                if (tag !== 'TABLE' && tag !== 'THEAD' && tag !== 'TBODY') {
                    targetElement.addEventListener('click', clickHandler, true);
                }
            }

            logDebug("Calling driverObj.drive()");
            driverObj.drive();
        } catch (error) {
            logDebug("EXCEPTION CAUGHT: " + error.message);
            console.error(error);
        }
    }

    function moveToNextStep(currentIndex) {
        logDebug(`Moving to next step after ${currentIndex}`);
        const currentStep = steps[currentIndex];
        if (currentStep && typeof currentStep.onAfterNext === 'function') {
            try {
                currentStep.onAfterNext();
            } catch (e) {
                logDebug(`Error in onAfterNext for step ${currentIndex}: ${e.message}`);
            }
        }

        const nextIndex = currentIndex + 1;
        localStorage.setItem('tutorial_current_step', nextIndex);
        if (nextIndex >= steps.length) {
            finishTutorial();
        } else {
            setTimeout(() => {
                try {
                    if (driverObj) {
                        logDebug("Destroying current driver instance.");
                        driverObj.destroy();
                    }
                } catch (e) {
                    logDebug("Safe destroy next catch: " + e.message);
                }
                startTutorial(nextIndex);
            }, 300); // Shorter delay
        }
    }

    function moveToPrevStep(currentIndex) {
        logDebug(`Moving to prev step from ${currentIndex}`);
        if (currentIndex <= 0) return;
        const currentStep = steps[currentIndex];
        if (currentStep && typeof currentStep.onAfterPrev === 'function') {
            try {
                currentStep.onAfterPrev();
            } catch (e) {
                logDebug(`Error in onAfterPrev for step ${currentIndex}: ${e.message}`);
            }
        }

        const prevIndex = currentIndex - 1;
        localStorage.setItem('tutorial_current_step', prevIndex);
        setTimeout(() => {
            try {
                if (driverObj) {
                    driverObj.destroy();
                }
            } catch (e) {
                logDebug("Safe destroy prev catch: " + e.message);
            }
            startTutorial(prevIndex);
        }, 300);
    }

    function finishTutorial() {
        logDebug("finishTutorial called.");
        if (driverObj) driverObj.destroy();
        const progress = document.getElementById('tutorial-global-progress');
        if (progress) progress.remove();
        
        // Clean up open modals & view state
        const addModal = document.getElementById('addUnitModal');
        if (addModal) addModal.classList.add('hidden');
        if (typeof setViewMode === 'function') setViewMode('table');

        const debuggerEl = document.getElementById('tutorial-debugger');
        if (debuggerEl) {
            debuggerEl.innerHTML += `<div>> <strong>Tutorial Finished!</strong> You can safely refresh the page.</div>`;
        }
        
        markTutorialComplete();
    }

    function markTutorialComplete() {
        localStorage.removeItem('tutorial_current_step');
        localStorage.removeItem('tutorial_welcome_shown');
        localStorage.removeItem('tutorial_force_restart');
        
        fetch('/api/tutorial/complete', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        }).catch(err => logDebug("Failed to save complete status: " + err));
    }

    function restart() {
        logDebug("Restart triggered.");
        localStorage.setItem('tutorial_force_restart', '1');
        localStorage.setItem('tutorial_current_step', '0');
        localStorage.removeItem('tutorial_welcome_shown');
        window.location.href = '/'; 
    }

    return {
        init,
        restart,
        finishTutorial,
        moveToNextStep,
        moveToPrevStep
    };
})();
window.TutorialManager = TutorialManager;

/**
 * Portal technique: Move the dropdown to document.body with fixed positioning
 * so it escapes the z-30 stacking context of the parent <td> and appears
 * ABOVE the Driver.js overlay (z-index 100000).
 */
window._tutorialPortalDropdown = function() {
    // Clean up any previous portal
    const existing = document.getElementById('__tutorial-portal-dd');
    if (existing) existing.remove();

    const btn = document.querySelector('tbody tr button[onclick*="toggleUnitDropdown"]');
    if (!btn) return;

    const dd = btn.closest('td').querySelector('.unit-action-dropdown');
    if (!dd) return;

    const rect = btn.getBoundingClientRect();

    // Clone the dropdown so we don't disturb the original DOM
    const clone = dd.cloneNode(true);
    clone.id = '__tutorial-portal-dd';
    clone.classList.remove('hidden');
    clone.classList.add('unit-action-dropdown--portal');

    // Position it fixed near the button
    clone.style.setProperty('position', 'fixed', 'important');
    clone.style.setProperty('top', (rect.bottom + 4) + 'px', 'important');
    clone.style.setProperty('right', (window.innerWidth - rect.right) + 'px', 'important');
    clone.style.setProperty('left', 'auto', 'important');
    clone.style.setProperty('z-index', '100010', 'important');
    clone.style.setProperty('pointer-events', 'none', 'important');
    clone.style.setProperty('display', 'block', 'important');
    clone.style.setProperty('min-width', '11rem', 'important');
    clone.style.setProperty('background', 'white', 'important');
    clone.style.setProperty('border-radius', '0.75rem', 'important');
    clone.style.setProperty('box-shadow', '0 25px 50px -12px rgba(0,0,0,0.25)', 'important');
    clone.style.setProperty('border', '1px solid #e5e7eb', 'important');
    clone.style.setProperty('overflow', 'hidden', 'important');

    document.body.appendChild(clone);

    // IMPORTANT: Hide the original so the portal clone is the only one visible
    dd.classList.add('hidden');
    dd.style.removeProperty('display');
};

window._tutorialUnportalDropdown = function() {
    const portal = document.getElementById('__tutorial-portal-dd');
    if (portal) portal.remove();
    // Also ensure original is hidden
    const dd = document.querySelector('.unit-action-dropdown');
    if (dd) dd.classList.add('hidden');
};

