/* public/assets/js/tutorial.js */
const TUTORIAL_DATA_VERSION = "9.5";

// Suppress native browser print dialog during tutorial mode to prevent UI blocking
const _originalWindowPrint = window.print;
window.print = function() {
    const isTutorialActive = !!localStorage.getItem('tutorial_current_step') || window.location.search.includes('tutorial=1');
    if (isTutorialActive) {
        console.log('[Tutorial Sandbox] Native window.print() suppressed during tutorial mode.');
        return false;
    }
    if (typeof _originalWindowPrint === 'function') {
        return _originalWindowPrint.apply(this, arguments);
    }
};

window.enforceTutorialViewMode = function(mode) {
    if (typeof setViewMode === 'function') {
        setViewMode(mode);
    }
    const tv = document.getElementById('units-table-view');
    const gv = document.getElementById('units-grid-view');
    if (tv && gv) {
        if (mode === 'table') {
            tv.style.setProperty('display', 'block', 'important');
            gv.style.setProperty('display', 'none', 'important');
        } else if (mode === 'grid') {
            gv.style.setProperty('display', 'block', 'important');
            tv.style.setProperty('display', 'none', 'important');
        }
    }
};

/**
 * Dedicated Static Tutorial Dataset
 * Isolated, Cached, Immutable Sample Data matching production schema.
 */
const TutorialStaticData = {
    version: TUTORIAL_DATA_VERSION,
    
    dashboard: {
        totalUnits: 91,
        roiAchievedUnits: 48,
        boundaryRevenueToday: 42500.00,
        boundaryRevenueMonth: 1185000.00,
        netIncomeToday: 32400.00,
        netIncomeMonth: 890000.00,
        underMaintenanceUnits: 3,
        activeDriversCount: 104,
        expensesToday: 10100.00,
        codingUnitsToday: 18,
        unitPerformance: [
            { plate: "AAA 4591", target: 33000, actual: 35200, roiPercent: 106.6 },
            { plate: "AAK 9196", target: 30000, actual: 31000, roiPercent: 103.3 },
            { plate: "AAQ 1743", target: 27000, actual: 28500, roiPercent: 105.5 }
        ],
        statusDistribution: { active: 70, maintenance: 3, coding: 18 }
    },
    
    units: [
        {
            id: "tut-unit-1",
            uuid: "tut-uuid-1",
            plate_number: "AAA 4591",
            make: "Toyota",
            model: "Vios",
            year: 2014,
            engine_number: "2NZ7307868",
            chassis_number: "NCP1522031009",
            status: "ACTIVE",
            unit_type: "new",
            boundary_rate: 1100.00,
            purchase_cost: 650000.00,
            purchase_date: "2014-03-15",
            pricing_type: "REGULAR RATE",
            pms_odometer: 5424,
            pms_interval: 5000,
            is_pms_overdue: true,
            day_driver: { id: "tut-d1", name: "July Sunico", phone: "0917-111-2233" },
            night_driver: { id: "tut-d2", name: "Arwin Azarcon", phone: "0918-444-5566" },
            coding_day: "Monday",
            next_coding_date: "2026-08-17",
            gps: { provider: "Tracksolid Pro", imei: "864209041234567" }
        },
        {
            id: "tut-unit-2",
            uuid: "tut-uuid-2",
            plate_number: "AAK 9196",
            make: "Toyota",
            model: "Vios",
            year: 2015,
            engine_number: "2NZ7747086",
            chassis_number: "NCP151-2042785",
            status: "ACTIVE",
            unit_type: "new",
            boundary_rate: 1000.00,
            purchase_cost: 680000.00,
            purchase_date: "2015-05-20",
            pricing_type: "REGULAR RATE",
            pms_odometer: 2150,
            pms_interval: 5000,
            is_pms_overdue: false,
            day_driver: { id: "tut-d3", name: "Ria Jane Perocho", phone: "0919-888-9900" },
            night_driver: null,
            coding_day: "Tuesday",
            next_coding_date: "2026-08-18",
            gps: { provider: "AKSH Aika168", imei: "868123049876543" }
        },
        {
            id: "tut-unit-3",
            uuid: "tut-uuid-3",
            plate_number: "ABC 1234",
            make: "Honda",
            model: "Civic",
            year: 2026,
            engine_number: "R18Z1-884920",
            chassis_number: "FDB-9920148",
            status: "MAINTENANCE",
            unit_type: "new",
            boundary_rate: 1200.00,
            purchase_cost: 950000.00,
            purchase_date: "2026-01-10",
            pricing_type: "REGULAR RATE",
            pms_odometer: 4980,
            pms_interval: 5000,
            is_pms_overdue: false,
            day_driver: null,
            night_driver: null,
            coding_day: "Friday",
            next_coding_date: "2026-08-14",
            gps: { provider: "Tracksolid Pro", imei: "864991045566778" }
        }
    ],

    drivers: [
        { id: "tut-d1", name: "July Sunico", phone: "0917-111-2233", license: "N01-12-345678", status: "ACTIVE", assigned_unit: "AAA 4591" },
        { id: "tut-d2", name: "Arwin Azarcon", phone: "0918-444-5566", license: "N02-98-765432", status: "ACTIVE", assigned_unit: "AAA 4591" },
        { id: "tut-d3", name: "Ria Jane Perocho", phone: "0919-888-9900", license: "N03-55-112233", status: "ACTIVE", assigned_unit: "AAK 9196" }
    ],

    expenses: [
        { id: "tut-exp-1", category: "Spare Parts & Maintenance", amount: 4500.00, date: "2026-08-10", notes: "PMS Oil & Filter Change" },
        { id: "tut-exp-2", category: "Office Supplies & Utilities", amount: 1200.00, date: "2026-08-09", notes: "Printer Ink & Forms" },
        { id: "tut-exp-3", category: "Insurance & LTO Permits", amount: 4400.00, date: "2026-08-05", notes: "Annual Franchise Renewal" }
    ],

    pdfReport: {
        title: "UNITS & DRIVERS MANAGEMENT REPORT",
        subtitle: "EURO TAXI MANAGEMENT SYSTEM — OFFICIAL RECORD",
        totalUnits: 91,
        timestamp: "AUG 11, 2026 15:45:00"
    }
};
window.TutorialStaticData = TutorialStaticData;

window.generateStaticTutorialPdfReport = function() {
    const data = window.TutorialStaticData ? window.TutorialStaticData.pdfReport : null;
    const units = window.TutorialStaticData ? window.TutorialStaticData.units : [];
    
    let rowsHtml = '';
    units.forEach(u => {
        rowsHtml += `
            <tr style="border-bottom: 1px solid #f1f5f9; font-size: 11px;">
                <td style="padding: 8px 12px; font-weight: bold; color: #1e293b;">
                    ${u.plate_number}<br>
                    <span style="font-size: 9px; color: #64748b; font-weight: normal;">${u.make} ${u.model} (${u.year})</span>
                </td>
                <td style="padding: 8px 12px; color: #334155;">${u.day_driver ? u.day_driver.name : '<span style="color:#94a3b8;">Vacant</span>'}</td>
                <td style="padding: 8px 12px; color: #334155;">${u.night_driver ? u.night_driver.name : '<span style="color:#94a3b8;">Vacant</span>'}</td>
                <td style="padding: 8px 12px; text-align: center; font-weight: bold; color: #0284c7;">${(u.day_driver ? 1 : 0) + (u.night_driver ? 1 : 0)}</td>
                <td style="padding: 8px 12px; text-align: right; font-weight: bold; color: #16a34a;">₱${u.boundary_rate.toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
            </tr>
        `;
    });

    return `
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <style>
                body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 24px; color: #1e293b; background: #ffffff; margin: 0; }
                .header { text-align: center; margin-bottom: 24px; border-bottom: 2px solid #2563eb; padding-bottom: 16px; }
                .logo { font-size: 24px; font-weight: 900; color: #1e3a8a; letter-spacing: -0.5px; }
                .logo span { color: #f59e0b; }
                .title { font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #0f172a; margin-top: 4px; }
                .subtitle { font-size: 10px; color: #64748b; margin-top: 2px; }
                .meta-bar { display: flex; justify-content: space-between; font-size: 10px; font-weight: bold; color: #475569; margin-bottom: 12px; text-transform: uppercase; border-bottom: 1px dashed #cbd5e1; padding-bottom: 8px; }
                table { width: 100%; border-collapse: collapse; margin-top: 8px; }
                th { background: #f8fafc; color: #475569; font-size: 9px; font-weight: 800; text-transform: uppercase; text-align: left; padding: 8px 12px; border-bottom: 2px solid #e2e8f0; }
                .signature-box { margin-top: 40px; display: flex; justify-content: space-between; font-size: 11px; }
                .sig-line { width: 200px; border-top: 1px solid #94a3b8; text-align: center; padding-top: 4px; font-weight: bold; color: #475569; }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="logo">EURO<span>TAXI</span> INC.</div>
                <div class="title">${data ? data.title : 'UNITS & DRIVERS MANAGEMENT REPORT'}</div>
                <div class="subtitle">${data ? data.subtitle : 'EURO TAXI MANAGEMENT SYSTEM — OFFICIAL RECORD'}</div>
            </div>
            <div class="meta-bar">
                <span>TOTAL REGISTERED UNITS: ${data ? data.totalUnits : 91}</span>
                <span>TIMESTAMP: ${data ? data.timestamp : 'AUG 11, 2026 15:45:00'}</span>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>UNIT INFO</th>
                        <th>PRIMARY DRIVER (D1)</th>
                        <th>SECONDARY DRIVER (D2)</th>
                        <th style="text-align:center;">DRIVERS</th>
                        <th style="text-align:right;">BOUNDARY RATE</th>
                    </tr>
                </thead>
                <tbody>
                    ${rowsHtml}
                </tbody>
            </table>
            <div class="signature-box">
                <div class="sig-line">Prepared By: Dispatch Officer</div>
                <div class="sig-line">Approved By: Operations Manager</div>
            </div>
        </body>
        </html>
    `;
};

window.generateStaticDriverTutorialPdfReport = function() {
    const drivers = [
        { name: "Arwin Azarcon", license: "D09-12-312312", contact: "0917-123-4567", unit: "AAA 4591", status: "ASSIGNED", boundary: "₱1,100.00" },
        { name: "Willy Bautista", license: "TBD-EC9C7E7D", contact: "0918-234-5678", unit: "DAJ 7468", status: "ASSIGNED", boundary: "₱650.00 (Coding)" },
        { name: "Henner Bonsol", license: "TBD-22953AE4", contact: "0919-345-6789", unit: "NEO 67116", status: "ASSIGNED", boundary: "₱1,400.00" },
        { name: "Morlino Boroy", license: "TBD-614DB287", contact: "0920-456-7890", unit: "NEO 67116", status: "ASSIGNED", boundary: "₱1,400.00" },
        { name: "Jayson Borromeo", license: "TBD-0AD5AF1A", contact: "0921-567-8901", unit: "NGF 1484", status: "ASSIGNED", boundary: "₱1,300.00" },
        { name: "Juanito Cabales", license: "TBD-D1DCF7F4", contact: "0922-678-9012", unit: "DBA 5420", status: "ASSIGNED", boundary: "₱1,300.00" },
        { name: "Ramil Cadalzo", license: "TBD-DAT13589", contact: "0923-789-0123", unit: "DAT 1358", status: "ASSIGNED", boundary: "₱700.00 (Coding)" }
    ];

    let rowsHtml = '';
    drivers.forEach(d => {
        rowsHtml += `
            <tr style="border-bottom: 1px solid #f1f5f9; font-size: 11px;">
                <td style="padding: 10px 12px; font-weight: 800; color: #0f172a;">${d.name}</td>
                <td style="padding: 10px 12px; color: #334155; font-family: monospace; font-size: 10px;">${d.license}</td>
                <td style="padding: 10px 12px; color: #475569;">${d.contact}</td>
                <td style="padding: 10px 12px; text-align: center; font-weight: 800; color: #1e293b;"><span style="background:#f1f5f9; padding:3px 8px; border-radius:6px; border:1px solid #e2e8f0;">${d.unit}</span></td>
                <td style="padding: 10px 12px; text-align: center;"><span style="background:#dcfce7; color:#166534; padding:3px 8px; border-radius:6px; font-size:9px; font-weight:800;">${d.status}</span></td>
                <td style="padding: 10px 12px; text-align: right; font-weight: 900; color: #059669;">${d.boundary}</td>
            </tr>
        `;
    });

    return `
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <style>
                body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 24px; color: #1e293b; background: #ffffff; margin: 0; }
                .header { text-align: center; margin-bottom: 24px; border-bottom: 2px solid #2563eb; padding-bottom: 16px; }
                .logo { font-size: 24px; font-weight: 900; color: #1e3a8a; letter-spacing: -0.5px; }
                .logo span { color: #f59e0b; }
                .title { font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #0f172a; margin-top: 4px; }
                .subtitle { font-size: 10px; color: #64748b; margin-top: 2px; }
                .meta-bar { display: flex; justify-content: space-between; font-size: 10px; font-weight: bold; color: #475569; margin-bottom: 12px; text-transform: uppercase; border-bottom: 1px dashed #cbd5e1; padding-bottom: 8px; }
                table { width: 100%; border-collapse: collapse; margin-top: 8px; }
                th { background: #f8fafc; color: #475569; font-size: 9px; font-weight: 800; text-transform: uppercase; text-align: left; padding: 8px 12px; border-bottom: 2px solid #e2e8f0; }
                .signature-box { margin-top: 40px; display: flex; justify-content: space-between; font-size: 11px; }
                .sig-line { width: 200px; border-top: 1px solid #94a3b8; text-align: center; padding-top: 4px; font-weight: bold; color: #475569; }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="logo">EURO<span>TAXI</span> INC.</div>
                <div class="title">DRIVER MASTER LIST & RECORDS REPORT</div>
                <div class="subtitle">EURO TAXI MANAGEMENT SYSTEM — OFFICIAL RECORD</div>
            </div>
            <div class="meta-bar">
                <span>TOTAL REGISTERED DRIVERS: 96</span>
                <span>TIMESTAMP: AUG 13, 2026 17:45:00</span>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>DRIVER NAME</th>
                        <th>LICENSE NUMBER</th>
                        <th>CONTACT PHONE</th>
                        <th style="text-align:center;">ASSIGNED UNIT</th>
                        <th style="text-align:center;">STATUS</th>
                        <th style="text-align:right;">BOUNDARY TARGET</th>
                    </tr>
                </thead>
                <tbody>
                    ${rowsHtml}
                </tbody>
            </table>
            <div class="signature-box">
                <div class="sig-line">Prepared By: Operations Officer</div>
                <div class="sig-line">Approved By: Fleet Director</div>
            </div>
        </body>
        </html>
    `;
};

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
            onBeforeShow: () => { if (typeof enforceTutorialViewMode === 'function') enforceTutorialViewMode('table'); },
            getElement: () => document.getElementById('quickStatsBar'),
            popover: { title: 'Fleet Status Counters', description: 'Real-time counters showing Total Fleet Units, Active Units on the road, Units under Maintenance in the garage, and Coding Units restricted for today.', position: 'bottom' }
        },
        {
            id: 'units-filter-search',
            route: '/units',
            onBeforeShow: () => { if (typeof enforceTutorialViewMode === 'function') enforceTutorialViewMode('table'); },
            getElement: () => document.getElementById('tableSearchInput') ? document.getElementById('tableSearchInput').closest('.bg-white') : null,
            popover: { title: 'Search, Sort & Filters', description: 'Quickly search any car by plate number or driver name, sort A-Z, or filter by Active, Coding, Maintenance, or Vacant status.', position: 'bottom' }
        },
        {
            id: 'units-view-toggle',
            route: '/units',
            onBeforeShow: () => { 
                if (typeof enforceTutorialViewMode === 'function') enforceTutorialViewMode('table'); 
                const btnGrid = document.getElementById('btn-view-grid');
                if (btnGrid) {
                    btnGrid.scrollIntoView({ behavior: 'auto', block: 'center' });
                    btnGrid.onclick = function() {
                        if (typeof enforceTutorialViewMode === 'function') enforceTutorialViewMode('grid');
                        if (window.TutorialManager) window.TutorialManager.moveToNextStep(17);
                    };
                }
            },
            getElement: () => document.getElementById('btn-view-grid') || document.getElementById('unitViewTogglePill'),
            popover: { title: 'Switch to Cards View Toggle', description: 'Click the CARDS button on the view toggle pill to switch from Table view to visual grid-based Cards view!', position: 'bottom' }
        },
        {
            id: 'units-cards-deepdive',
            route: '/units',
            onBeforeShow: () => { 
                if (typeof enforceTutorialViewMode === 'function') enforceTutorialViewMode('grid'); 
                const card = document.querySelector('#units-grid-view > div > div:first-child') || document.querySelector('#units-grid-view .grid > div') || document.getElementById('units-grid-view');
                if (card) card.scrollIntoView({ behavior: 'auto', block: 'center' });
            },
            getElement: () => document.querySelector('#units-grid-view > div > div:first-child') || document.querySelector('#units-grid-view .grid > div') || document.getElementById('units-grid-view'),
            popover: { title: 'Cards Grid View Showcase', description: 'In Cards View, each taxi unit is presented as a visual card with real-time status badges, assigned D1/D2 driver partners, and current odometer progress.', position: 'bottom' }
        },
        {
            id: 'units-table-restore',
            route: '/units',
            onBeforeShow: () => { 
                if (typeof enforceTutorialViewMode === 'function') enforceTutorialViewMode('grid'); 
                const btnTable = document.getElementById('btn-view-table');
                if (btnTable) {
                    btnTable.scrollIntoView({ behavior: 'auto', block: 'center' });
                    btnTable.onclick = function() {
                        if (typeof enforceTutorialViewMode === 'function') enforceTutorialViewMode('table');
                        if (window.TutorialManager) window.TutorialManager.moveToNextStep(19);
                    };
                }
            },
            getElement: () => document.getElementById('btn-view-table') || document.getElementById('unitViewTogglePill'),
            popover: { title: 'Switching Back to Table View', description: 'Clicking the TABLE button restores the structured row layout with full column details for deep analysis.', position: 'bottom' }
        },
        {
            id: 'units-print-pdf-btn',
            route: '/units',
            onBeforeShow: () => { 
                if (typeof enforceTutorialViewMode === 'function') enforceTutorialViewMode('table'); 
                if (typeof closeTutorialPdfPreview === 'function') closeTutorialPdfPreview(); 
            },
            getElement: () => document.getElementById('btn-print-pdf') || document.querySelector('button[onclick*="printInHiddenIframe"]'),
            popover: { title: 'Print Master List to PDF Button', description: 'Clicking this button generates an official PDF document of your entire fleet roster. Let us open the live document preview!', position: 'bottom' }
        },
        {
            id: 'units-print-pdf-preview',
            route: '/units',
            onBeforeShow: () => { 
                if (typeof enforceTutorialViewMode === 'function') enforceTutorialViewMode('table'); 
                if (typeof openTutorialPdfPreview === 'function') openTutorialPdfPreview(); 
                const m = document.getElementById('tutorialPrintPdfModal');
                if (m) { m.classList.remove('hidden'); m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;'; }
            },
            onAfterNext: () => { if (typeof closeTutorialPdfPreview === 'function') closeTutorialPdfPreview(); },
            getElement: () => {
                const m = document.getElementById('tutorialPrintPdfModal');
                if (m) { m.classList.remove('hidden'); m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;'; }
                return document.querySelector('#tutorialPrintPdfModal > div') || document.getElementById('btn-print-pdf');
            },
            popover: { title: 'Live Master Roster PDF Deep Dive', description: 'Here is the live generated PDF document! It compiles your official fleet records, including Plate #, Engine/Chassis IDs, D1/D2 Assigned Drivers, Smart Boundary Rates, and Official Signature Lines.', position: 'top' }
        },
        {
            id: 'units-add-unit-btn',
            route: '/units',
            onBeforeShow: () => { 
                if (typeof enforceTutorialViewMode === 'function') enforceTutorialViewMode('table'); 
                if (typeof closeTutorialPdfPreview === 'function') closeTutorialPdfPreview(); 
                const m = document.getElementById('addUnitModal'); 
                if (m) m.classList.add('hidden'); 
            },
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
            onBeforeShow: () => { 
                const m = document.getElementById('addUnitModal'); 
                if (m) m.classList.add('hidden'); 
                if (typeof setViewMode === 'function') setViewMode('table'); 
                const tv = document.getElementById('units-table-view');
                const gv = document.getElementById('units-grid-view');
                if (tv) tv.style.setProperty('display', 'block', 'important');
                if (gv) gv.style.setProperty('display', 'none', 'important');
                const container = document.getElementById('unitsTableScrollContainer') || document.querySelector('table');
                if (container) container.scrollIntoView({ behavior: 'auto', block: 'center' });
            },
            getElement: () => document.getElementById('unitsTableScrollContainer') || document.querySelector('#units-table-view table') || document.querySelector('table'),
            popover: { title: 'Fleet Master Inventory Table', description: 'Detailed table listing all taxi units in your fleet with live status indicators, assigned drivers, and boundary pricing tags.', position: 'bottom' }
        },
        {
            id: 'units-col-plate',
            route: '/units',
            onBeforeShow: () => {
                const tv = document.getElementById('units-table-view');
                const gv = document.getElementById('units-grid-view');
                if (tv) tv.style.setProperty('display', 'block', 'important');
                if (gv) gv.style.setProperty('display', 'none', 'important');
            },
            getElement: () => document.querySelector('tbody.modern-card-tbody tr td:first-child') || document.querySelector('tbody tr td:first-child'),
            popover: { title: 'Plate Number, Motor & Chassis Serial IDs', description: 'Displays the unit plate number (e.g. AAA 4591), registered Engine/Motor number, and Chassis serial number for complete vehicle legal tracking.', position: 'top' }
        },
        {
            id: 'units-col-specs',
            route: '/units',
            onBeforeShow: () => {
                const tv = document.getElementById('units-table-view');
                const gv = document.getElementById('units-grid-view');
                if (tv) tv.style.setProperty('display', 'block', 'important');
                if (gv) gv.style.setProperty('display', 'none', 'important');
            },
            getElement: () => document.querySelector('tbody.modern-card-tbody tr td:nth-child(2)') || document.querySelector('tbody tr td:nth-child(2)'),
            popover: { title: 'Vehicle Make, Model & Year', description: 'Shows the vehicle brand (e.g. Toyota), model name (e.g. Vios), model year (e.g. 2014), and NEW vehicle status tag.', position: 'top' }
        },
        {
            id: 'units-col-drivers',
            route: '/units',
            onBeforeShow: () => {
                const tv = document.getElementById('units-table-view');
                const gv = document.getElementById('units-grid-view');
                if (tv) tv.style.setProperty('display', 'block', 'important');
                if (gv) gv.style.setProperty('display', 'none', 'important');
            },
            getElement: () => document.querySelector('tbody.modern-card-tbody tr td:nth-child(3)') || document.querySelector('tbody tr td:nth-child(3)'),
            popover: { title: 'Assigned Drivers (D1 & D2)', description: 'Shows the assigned Day Driver (D1) and Night Driver (D2) for each car. Vacant driver slots are highlighted for quick driver pairing.', position: 'top' }
        },
        {
            id: 'units-col-status',
            route: '/units',
            onBeforeShow: () => {
                const tv = document.getElementById('units-table-view');
                const gv = document.getElementById('units-grid-view');
                if (tv) tv.style.setProperty('display', 'block', 'important');
                if (gv) gv.style.setProperty('display', 'none', 'important');
            },
            getElement: () => document.querySelector('tbody.modern-card-tbody tr td:nth-child(4)') || document.querySelector('tbody tr td:nth-child(4)'),
            popover: { title: 'Live Vehicle Status Badge', description: 'Real-time unit status indicator (Active on road, Maintenance in garage, MMDA Coding restriction, or Vacant).', position: 'top' }
        },
        {
            id: 'units-col-pricing',
            route: '/units',
            onBeforeShow: () => {
                const tv = document.getElementById('units-table-view');
                const gv = document.getElementById('units-grid-view');
                if (tv) tv.style.setProperty('display', 'block', 'important');
                if (gv) gv.style.setProperty('display', 'none', 'important');
            },
            getElement: () => document.querySelector('tbody.modern-card-tbody tr td:nth-child(5)') || document.querySelector('tbody tr td:nth-child(5)'),
            popover: { title: 'Smart Boundary Rate & Pricing Tag', description: 'Displays the active daily boundary rate (e.g. ₱1,100.00) and pricing tag (Regular Rate vs Coding Discount vs Sunday Discount).', position: 'top' }
        },
        {
            id: 'units-col-pms-alert',
            route: '/units',
            onBeforeShow: () => {
                const tv = document.getElementById('units-table-view');
                const gv = document.getElementById('units-grid-view');
                if (tv) tv.style.setProperty('display', 'block', 'important');
                if (gv) gv.style.setProperty('display', 'none', 'important');
            },
            getElement: () => document.querySelector('.modern-sub-row'),
            popover: { title: '5,000 KM Maintenance & PMS Odometer Alert', description: 'Tracks mileage progress towards 5,000 km oil change intervals and alerts you automatically with a red warning banner when PMS is overdue!', position: 'top' }
        },
        {
            id: 'units-actions-dropdown-open',
            route: '/units',
            onBeforeShow: () => {
                window._tutorialUnportalDropdown();
                const btn = document.querySelector('tbody tr button[onclick*="toggleUnitDropdown"]');
                if (btn) btn.scrollIntoView({ behavior: 'auto', block: 'center' });

                if (window._step38GlobalClick) {
                    window.removeEventListener('click', window._step38GlobalClick, true);
                }
                window._step38GlobalClick = function(e) {
                    const currentStep = parseInt(localStorage.getItem('tutorial_current_step') || '0');
                    if (currentStep === 37) { // Step 38
                        const targetBtn = document.querySelector('tbody tr button[onclick*="toggleUnitDropdown"]');
                        if (targetBtn) {
                            const rect = targetBtn.getBoundingClientRect();
                            const isInsideBox = (
                                e.clientX >= rect.left - 20 &&
                                e.clientX <= rect.right + 20 &&
                                e.clientY >= rect.top - 20 &&
                                e.clientY <= rect.bottom + 20
                            );
                            const isClosestBtn = e.target && e.target.closest && e.target.closest('button[onclick*="toggleUnitDropdown"]');

                            if (isInsideBox || isClosestBtn) {
                                e.preventDefault();
                                e.stopPropagation();
                                e.stopImmediatePropagation();
                                logDebug("Step 38 3-dots click caught via Geometry/DOM! Opening portal and advancing to Step 39.");
                                window._tutorialPortalDropdown();
                                if (window.TutorialManager) {
                                    window.TutorialManager.moveToNextStep(37);
                                }
                            }
                        }
                    }
                };
                window.addEventListener('click', window._step38GlobalClick, true);
            },
            onAfterNext: () => {
                if (window._step38GlobalClick) {
                    window.removeEventListener('click', window._step38GlobalClick, true);
                    window._step38GlobalClick = null;
                }
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
                window._tutorialPortalDropdown();
            },
            getElement: () => document.querySelector('#__tutorial-portal-dd button[onclick*="editUnit"]') || document.querySelector('.unit-action-dropdown--portal button[onclick*="editUnit"]') || document.querySelector('tbody tr button[onclick*="toggleUnitDropdown"]'),
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
                window._tutorialPortalDropdown();
            },
            getElement: () => document.querySelector('#__tutorial-portal-dd form[action*="reset-health"] button') || document.querySelector('#__tutorial-portal-dd button.text-green-600') || document.querySelector('.unit-action-dropdown--portal form[action*="reset-health"]') || document.querySelector('tbody tr button[onclick*="toggleUnitDropdown"]'),
            popover: { title: '🔄 Reset Service Action', description: 'Resets the 5,000 km oil change mileage counter back to zero after mechanic maintenance or oil replacement is completed!', position: 'left-center' }
        },
        {
            id: 'units-actions-archive',
            route: '/units',
            onBeforeShow: () => { 
                const detailsModal = document.getElementById('unitDetailsModal');
                if (detailsModal) detailsModal.classList.add('hidden');
                const btn = document.querySelector('tbody tr button[onclick*="toggleUnitDropdown"]');
                if (btn) btn.scrollIntoView({ behavior: 'auto', block: 'center' });
                window._tutorialPortalDropdown();
            },
            getElement: () => document.querySelector('#__tutorial-portal-dd form[action*="destroy"] button') || document.querySelector('#__tutorial-portal-dd button.text-amber-600') || document.querySelector('.unit-action-dropdown--portal form[action*="destroy"]') || document.querySelector('tbody tr button[onclick*="toggleUnitDropdown"]'),
            popover: { title: '📦 Archive Unit Action', description: 'Safely deactivates and archives the taxi unit without deleting its historical financial, boundary, and driver records.', position: 'left-center' }
        },
        {
            id: 'units-row-click-deepdive',
            route: '/units',
            onBeforeShow: () => { 
                if (typeof window._tutorialUnportalDropdown === 'function') {
                    window._tutorialUnportalDropdown();
                }
                const dd = document.querySelector('.unit-action-dropdown'); 
                if (dd) dd.classList.add('hidden'); 
                const modal = document.getElementById('unitDetailsModal');
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.style.setProperty('display', 'flex', 'important');
                    const card = modal.querySelector(':scope > div');
                    if (card) {
                        card.onclick = function(e) {
                            if (window.TutorialManager && !e.target.closest('button') && !e.target.closest('.tab-btn')) {
                                window.TutorialManager.moveToNextStep(42);
                            }
                        };
                    }
                }
                if (typeof viewUnitDetails === 'function') {
                    viewUnitDetails(1);
                }
                if (typeof showTab === 'function') showTab('overview');
            },
            getElement: () => document.querySelector('#unitDetailsModal > div') || document.getElementById('unitDetailsContent'),
            popover: { title: 'Unit Details Profile Showcase', description: 'Welcome inside the Unit Details profile! Here you can monitor complete unit specs, assigned D1/D2 driver partners, financial ROI, PMS maintenance logs, and live GPS tracking.', position: 'left-center' }
        },
        {
            id: 'unit-details-overview-tab',
            route: '/units',
            onBeforeShow: () => {
                const m = document.getElementById('unitDetailsModal');
                if (!m || m.classList.contains('hidden')) {
                    if (typeof viewUnitDetails === 'function') viewUnitDetails(1);
                }
                if (typeof showTab === 'function') showTab('overview');
                setTimeout(() => {
                    const btn = document.querySelector('#unitDetailsModal .tab-btn[data-tab="overview"]');
                    if (btn) {
                        btn.onclick = function(e) {
                            if (typeof showTab === 'function') showTab('overview');
                            if (window.TutorialManager) window.TutorialManager.moveToNextStep(43);
                        };
                    }
                }, 50);
            },
            getElement: () => document.querySelector('#unitDetailsModal .tab-btn[data-tab="overview"]'),
            popover: { title: '1. Overview Tab', description: 'Overview displays primary driver assignment, current status (Active/On Road), daily boundary target, and MMDA coding day at a glance.', position: 'left-center' }
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
                setTimeout(() => {
                    const btn = document.querySelector('#unitDetailsModal .tab-btn[data-tab="drivers"]');
                    if (btn) {
                        btn.onclick = function(e) {
                            if (typeof showTab === 'function') showTab('drivers');
                            if (window.TutorialManager) window.TutorialManager.moveToNextStep(44);
                        };
                    }
                }, 50);
            },
            getElement: () => document.querySelector('#unitDetailsModal .tab-btn[data-tab="drivers"]'),
            popover: { title: '2. Drivers Tab', description: 'Displays assigned Day Shift (D1) and Night Shift (D2) driver profiles, license details, and contact numbers.', position: 'left-center' }
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
                setTimeout(() => {
                    const btn = document.querySelector('#unitDetailsModal .tab-btn[data-tab="coding"]');
                    if (btn) {
                        btn.onclick = function(e) {
                            if (typeof showTab === 'function') showTab('coding');
                            if (window.TutorialManager) window.TutorialManager.moveToNextStep(45);
                        };
                    }
                }, 50);
            },
            getElement: () => document.querySelector('#unitDetailsModal .tab-btn[data-tab="coding"]'),
            popover: { title: '3. Coding Tab', description: 'Monitors Metro Manila MMDA number coding schedule, restriction day, and active time window.', position: 'left-center' }
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
                setTimeout(() => {
                    const btn = document.querySelector('#unitDetailsModal .tab-btn[data-tab="boundary"]');
                    if (btn) {
                        btn.onclick = function(e) {
                            if (typeof showTab === 'function') showTab('boundary');
                            if (window.TutorialManager) window.TutorialManager.moveToNextStep(46);
                        };
                    }
                }, 50);
            },
            getElement: () => document.querySelector('#unitDetailsModal .tab-btn[data-tab="boundary"]'),
            popover: { title: '4. Boundary Tab', description: 'Complete daily boundary payment history, actual collected amounts, shortages/excesses, payment dates, and cashier remarks.', position: 'left-center' }
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
                setTimeout(() => {
                    const btn = document.querySelector('#unitDetailsModal .tab-btn[data-tab="maintenance"]');
                    if (btn) {
                        btn.onclick = function(e) {
                            if (typeof showTab === 'function') showTab('maintenance');
                            if (window.TutorialManager) window.TutorialManager.moveToNextStep(47);
                        };
                    }
                }, 50);
            },
            getElement: () => document.querySelector('#unitDetailsModal .tab-btn[data-tab="maintenance"]'),
            popover: { title: '5. Maintenance Tab', description: 'Full breakdown of past repairs, oil changes, mechanic names, total repair costs, and itemized spare parts subtotal.', position: 'left-center' }
        },
        {
            id: 'unit-details-roi-tab',
            route: '/units',
            onBeforeShow: () => {
                const m = document.getElementById('unitDetailsModal');
                if (!m || m.classList.contains('hidden')) {
                    if (typeof viewUnitDetails === 'function') viewUnitDetails(1);
                }
                if (typeof showTab === 'function') showTab('roi');
                setTimeout(() => {
                    const btn = document.querySelector('#unitDetailsModal .tab-btn[data-tab="roi"]');
                    if (btn) {
                        btn.onclick = function(e) {
                            if (typeof showTab === 'function') showTab('roi');
                            if (window.TutorialManager) window.TutorialManager.moveToNextStep(48);
                        };
                    }
                }, 50);
            },
            getElement: () => document.querySelector('#unitDetailsModal .tab-btn[data-tab="roi"]'),
            popover: { title: '6. ROI Tab', description: 'Tracks financial return on investment percentage, vehicle purchase payback timeline, and profit performance.', position: 'left-center' }
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
                setTimeout(() => {
                    const btn = document.querySelector('#unitDetailsModal .tab-btn[data-tab="location"]');
                    if (btn) {
                        btn.onclick = function(e) {
                            if (typeof showTab === 'function') showTab('location');
                            if (window.TutorialManager) window.TutorialManager.moveToNextStep(49);
                        };
                    }
                }, 50);
            },
            getElement: () => document.querySelector('#unitDetailsModal .tab-btn[data-tab="location"]'),
            popover: { title: '7. Location Tab', description: 'Displays real-time GPS map coordinates, device IMEI number, signal strength, and live vehicle location tracking!', position: 'left-center' }
        },
        {
            id: 'unit-details-close',
            route: '/units',
            onBeforeShow: () => {
                const m = document.getElementById('unitDetailsModal');
                if (!m || m.classList.contains('hidden')) {
                    if (typeof viewUnitDetails === 'function') viewUnitDetails(1);
                }
                setTimeout(() => {
                    const closeBtn = document.querySelector('#unitDetailsModal button[onclick*="closeUnitDetailsModal"]');
                    if (closeBtn) {
                        closeBtn.onclick = function(e) {
                            if (typeof closeUnitDetailsModal === 'function') closeUnitDetailsModal();
                            const modal = document.getElementById('unitDetailsModal');
                            if (modal) {
                                modal.classList.add('hidden');
                                modal.style.removeProperty('display');
                                modal.style.removeProperty('z-index');
                                modal.style.display = 'none';
                            }
                            if (window._stepTabGlobalClick) {
                                window.removeEventListener('click', window._stepTabGlobalClick, true);
                                window._stepTabGlobalClick = null;
                            }
                            if (driverObj) {
                                try { driverObj.destroy(); } catch (err) {}
                            }
                            if (window.TutorialManager) {
                                window.TutorialManager.moveToNextStep(50);
                            }
                        };
                    }
                }, 50);
            },
            onAfterNext: () => {
                const modal = document.getElementById('unitDetailsModal');
                if (modal) {
                    modal.classList.add('hidden');
                    modal.style.removeProperty('display');
                    modal.style.removeProperty('z-index');
                    modal.style.display = 'none';
                }
                if (typeof closeUnitDetailsModal === 'function') closeUnitDetailsModal();
            },
            getElement: () => document.querySelector('#unitDetailsModal button[onclick*="closeUnitDetailsModal"]') || document.querySelector('#unitDetailsModal > div'),
            popover: { title: 'Close Unit Details Profile', description: 'Clicking close returns you to the main fleet management dashboard.', position: 'bottom' }
        },
        {
            id: 'sidebar-flagged-units',
            route: '/units',
            onBeforeShow: () => {
                const modal = document.getElementById('unitDetailsModal');
                if (modal) {
                    modal.classList.add('hidden');
                    modal.style.removeProperty('display');
                    modal.style.removeProperty('z-index');
                    modal.style.display = 'none';
                }
                if (typeof closeUnitDetailsModal === 'function') closeUnitDetailsModal();
            },
            getElement: () => findSidebarLink(['Flagged Units']) || document.querySelector('a[href*="/units/flagged"]'),
            popover: { title: '🚨 Flagged Units Registry Navigation', description: 'Click Flagged Units to inspect missing/stolen vehicles, overdue boundary auto-flags, and dispatch recovery teams.', position: 'right' }
        },
        {
            id: 'flagged-header-stats',
            route: '/units/flagged',
            onBeforeShow: () => {
                if (typeof closeManualFlagModal === 'function') closeManualFlagModal();
                const manualModal = document.getElementById('manualFlagModal');
                if (manualModal) { manualModal.classList.add('hidden'); manualModal.style.display = 'none'; }
            },
            getElement: () => document.querySelector('#total-flagged-count')?.closest('.bg-slate-900') || document.querySelector('.bg-slate-900') || document.querySelector('#total-flagged-count'),
            popover: { title: '🚨 Flagged Units Registry & Live Stats', description: 'Overview of your fleet\'s flagged status—combining total flagged units, police-reported missing/stolen vehicles, and system auto-detected boundary delays (overdue 48+ hours).', position: 'bottom' }
        },
        {
            id: 'flagged-search-bar',
            route: '/units/flagged',
            onBeforeShow: () => {
                if (typeof closeManualFlagModal === 'function') closeManualFlagModal();
                const manualModal = document.getElementById('manualFlagModal');
                if (manualModal) { manualModal.classList.add('hidden'); manualModal.style.display = 'none'; }
            },
            getElement: () => document.querySelector('.js-flag-search') || document.querySelector('input[placeholder*="Search plate"]'),
            popover: { title: '🔍 Real-Time Search Filter', description: 'Instantly filter flagged vehicles by plate number, vehicle make/model, or assigned suspect driver name in real time.', position: 'bottom' }
        },
        {
            id: 'flagged-filter-all',
            route: '/units/flagged',
            onBeforeShow: () => {
                if (typeof closeManualFlagModal === 'function') closeManualFlagModal();
                const manualModal = document.getElementById('manualFlagModal');
                if (manualModal) { manualModal.classList.add('hidden'); manualModal.style.display = 'none'; }
                setTimeout(() => {
                    const btn = document.querySelector('.filter-tab-btn[data-filter="all"]');
                    if (btn) {
                        btn.onclick = function() {
                            if (typeof setFilter === 'function') setFilter('all');
                            if (window.TutorialManager) window.TutorialManager.moveToNextStep(54);
                        };
                    }
                }, 50);
            },
            getElement: () => document.querySelector('.filter-tab-btn[data-filter="all"]'),
            popover: { title: '1. All Flagged Filter Tab', description: 'Click \'All Flagged\' to view the complete list of all flagged vehicles in your fleet (both police reports and automatic boundary delays).', position: 'bottom' }
        },
        {
            id: 'flagged-filter-stolen',
            route: '/units/flagged',
            onBeforeShow: () => {
                if (typeof closeManualFlagModal === 'function') closeManualFlagModal();
                const manualModal = document.getElementById('manualFlagModal');
                if (manualModal) { manualModal.classList.add('hidden'); manualModal.style.display = 'none'; }
                if (typeof setFilter === 'function') setFilter('manual_stolen');
                setTimeout(() => {
                    const btn = document.querySelector('.filter-tab-btn[data-filter="manual_stolen"]');
                    if (btn) {
                        btn.onclick = function() {
                            if (typeof setFilter === 'function') setFilter('manual_stolen');
                            if (window.TutorialManager) window.TutorialManager.moveToNextStep(55);
                        };
                    }
                }, 50);
            },
            getElement: () => document.querySelector('.filter-tab-btn[data-filter="manual_stolen"]'),
            popover: { title: '2. Missing / Stolen Filter Tab', description: 'Click \'Missing / Stolen\' to filter only vehicles manually reported as stolen, which automatically log critical incidents on suspect driver records!', position: 'bottom' }
        },
        {
            id: 'flagged-filter-autoboundary',
            route: '/units/flagged',
            onBeforeShow: () => {
                if (typeof closeManualFlagModal === 'function') closeManualFlagModal();
                const manualModal = document.getElementById('manualFlagModal');
                if (manualModal) { manualModal.classList.add('hidden'); manualModal.style.display = 'none'; }
                if (typeof setFilter === 'function') setFilter('auto_boundary');
                setTimeout(() => {
                    const btn = document.querySelector('.filter-tab-btn[data-filter="auto_boundary"]');
                    if (btn) {
                        btn.onclick = function() {
                            if (typeof setFilter === 'function') setFilter('auto_boundary');
                            if (window.TutorialManager) window.TutorialManager.moveToNextStep(56);
                        };
                    }
                }, 50);
            },
            getElement: () => document.querySelector('.filter-tab-btn[data-filter="auto_boundary"]'),
            popover: { title: '3. Auto-Detected Filter Tab', description: 'Click \'Auto-Detected\' to inspect vehicles automatically flagged by the system due to unremitted boundary payments exceeding 48 hours!', position: 'bottom' }
        },
        {
            id: 'flagged-manual-flag-btn',
            route: '/units/flagged',
            onBeforeShow: () => {
                if (typeof closeManualFlagModal === 'function') closeManualFlagModal();
                const m = document.getElementById('manualFlagModal');
                const b = document.getElementById('manualFlagBackdrop');
                const p = document.getElementById('manualFlagPanel');
                if (m) { m.classList.add('hidden'); m.style.cssText = 'display: none !important; z-index: -1 !important; visibility: hidden !important; opacity: 0 !important;'; }
                if (b) { b.classList.add('opacity-0'); b.style.cssText = 'display: none !important; visibility: hidden !important; opacity: 0 !important;'; }
                if (p) { p.classList.add('scale-95', 'opacity-0'); p.style.cssText = 'display: none !important; visibility: hidden !important; opacity: 0 !important;'; }
                setTimeout(() => {
                    const btn = document.querySelector('button[onclick*="openManualFlagModal"]');
                    if (btn) {
                        btn.onclick = function() {
                            if (typeof openManualFlagModal === 'function') openManualFlagModal();
                            const m = document.getElementById('manualFlagModal');
                            const b = document.getElementById('manualFlagBackdrop');
                            const p = document.getElementById('manualFlagPanel');
                            if (m) { m.classList.remove('hidden'); m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;'; }
                            if (b) { b.classList.remove('opacity-0'); b.classList.add('opacity-100'); b.style.cssText = 'display: block !important; visibility: visible !important; opacity: 1 !important; position: absolute; inset: 0;'; }
                            if (p) { p.classList.remove('scale-95', 'opacity-0'); p.classList.add('scale-100', 'opacity-100'); p.style.cssText = 'display: block !important; visibility: visible !important; opacity: 1 !important; position: relative; z-index: 100004 !important; margin-left: auto; margin-right: auto;'; }
                            if (window.TutorialManager) window.TutorialManager.moveToNextStep(58);
                        };
                    }
                }, 50);
            },
            getElement: () => document.querySelector('button[onclick*="openManualFlagModal"]') || document.querySelector('#flaggedActionButtonsBar button:last-child'),
            popover: { title: '🚩 Flag Unit Manually Action', description: 'Click \'Flag Unit Manually\' to open the emergency police report modal for missing or stolen vehicles.', position: 'bottom' }
        },
        {
            id: 'manual-flag-modal-panel',
            route: '/units/flagged',
            onBeforeShow: () => {
                if (typeof openManualFlagModal === 'function') openManualFlagModal();
                const m = document.getElementById('manualFlagModal');
                const b = document.getElementById('manualFlagBackdrop');
                const p = document.getElementById('manualFlagPanel');
                if (m) { m.classList.remove('hidden'); m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;'; }
                if (b) { b.classList.remove('opacity-0'); b.classList.add('opacity-100'); b.style.cssText = 'display: block !important; visibility: visible !important; opacity: 0.6 !important; position: absolute; inset: 0; background: #0f172a;'; }
                if (p) { p.classList.remove('scale-95', 'opacity-0'); p.classList.add('scale-100', 'opacity-100'); p.style.cssText = 'display: block !important; visibility: visible !important; opacity: 1 !important; position: relative; z-index: 100004 !important; margin: auto; transform: scale(1) !important;'; }
            },
            getElement: () => {
                const m = document.getElementById('manualFlagModal');
                const p = document.getElementById('manualFlagPanel');
                if (m) { m.classList.remove('hidden'); m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;'; }
                if (p) { p.classList.remove('scale-95', 'opacity-0'); p.style.cssText = 'display: block !important; visibility: visible !important; opacity: 1 !important; position: relative; z-index: 100004 !important; margin: auto; transform: scale(1) !important;'; }
                return p || m;
            },
            popover: { title: '🚩 Manual Flagging Emergency Modal', description: 'Use this dialog to record emergency incidents—specify unit ID, suspect driver, incident timestamp, and police case notes.', position: 'center' }
        },
        {
            id: 'manual-flag-select-unit',
            route: '/units/flagged',
            onBeforeShow: () => {
                if (typeof openManualFlagModal === 'function') openManualFlagModal();
                const m = document.getElementById('manualFlagModal');
                const b = document.getElementById('manualFlagBackdrop');
                const p = document.getElementById('manualFlagPanel');
                if (m) { m.classList.remove('hidden'); m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;'; }
                if (b) { b.classList.remove('opacity-0'); b.classList.add('opacity-100'); b.style.cssText = 'display: block !important; visibility: visible !important; opacity: 0.6 !important; position: absolute; inset: 0; background: #0f172a;'; }
                if (p) { p.classList.remove('scale-95', 'opacity-0'); p.classList.add('scale-100', 'opacity-100'); p.style.cssText = 'display: block !important; visibility: visible !important; opacity: 1 !important; position: relative; z-index: 100004 !important; margin: auto; transform: scale(1) !important;'; }
            },
            getElement: () => {
                const m = document.getElementById('manualFlagModal');
                const p = document.getElementById('manualFlagPanel');
                if (m) { m.classList.remove('hidden'); m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;'; }
                if (p) { p.classList.remove('scale-95', 'opacity-0'); p.style.cssText = 'display: block !important; visibility: visible !important; opacity: 1 !important; position: relative; z-index: 100004 !important; margin: auto; transform: scale(1) !important;'; }
                return document.getElementById('unitSearchContainer') || document.getElementById('unitDisplay') || p || m;
            },
            popover: { title: '1. Select Vehicle Unit', description: 'Choose the specific vehicle plate number to flag as missing or stolen from your active fleet list.', position: 'bottom' }
        },
        {
            id: 'manual-flag-suspect-driver',
            route: '/units/flagged',
            onBeforeShow: () => {
                if (typeof openManualFlagModal === 'function') openManualFlagModal();
                const m = document.getElementById('manualFlagModal');
                const b = document.getElementById('manualFlagBackdrop');
                const p = document.getElementById('manualFlagPanel');
                if (m) { m.classList.remove('hidden'); m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;'; }
                if (b) { b.classList.remove('opacity-0'); b.classList.add('opacity-100'); b.style.cssText = 'display: block !important; visibility: visible !important; opacity: 0.6 !important; position: absolute; inset: 0; background: #0f172a;'; }
                if (p) { p.classList.remove('scale-95', 'opacity-0'); p.classList.add('scale-100', 'opacity-100'); p.style.cssText = 'display: block !important; visibility: visible !important; opacity: 1 !important; position: relative; z-index: 100004 !important; margin: auto; transform: scale(1) !important;'; }
            },
            getElement: () => {
                const m = document.getElementById('manualFlagModal');
                const p = document.getElementById('manualFlagPanel');
                if (m) { m.classList.remove('hidden'); m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;'; }
                if (p) { p.classList.remove('scale-95', 'opacity-0'); p.style.cssText = 'display: block !important; visibility: visible !important; opacity: 1 !important; position: relative; z-index: 100004 !important; margin: auto; transform: scale(1) !important;'; }
                return document.getElementById('driverSearchContainer') || document.getElementById('driverDisplay') || p || m;
            },
            popover: { title: '2. Suspect Driver Selection', description: 'Optionally select the driver assigned to the unit to log critical behavioral records or trigger an automated driver ban.', position: 'bottom' }
        },
        {
            id: 'manual-flag-missing-date',
            route: '/units/flagged',
            onBeforeShow: () => {
                if (typeof openManualFlagModal === 'function') openManualFlagModal();
                const m = document.getElementById('manualFlagModal');
                const b = document.getElementById('manualFlagBackdrop');
                const p = document.getElementById('manualFlagPanel');
                if (m) { m.classList.remove('hidden'); m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;'; }
                if (b) { b.classList.remove('opacity-0'); b.classList.add('opacity-100'); b.style.cssText = 'display: block !important; visibility: visible !important; opacity: 0.6 !important; position: absolute; inset: 0; background: #0f172a;'; }
                if (p) { p.classList.remove('scale-95', 'opacity-0'); p.classList.add('scale-100', 'opacity-100'); p.style.cssText = 'display: block !important; visibility: visible !important; opacity: 1 !important; position: relative; z-index: 100004 !important; margin: auto; transform: scale(1) !important;'; }
            },
            getElement: () => {
                const m = document.getElementById('manualFlagModal');
                const p = document.getElementById('manualFlagPanel');
                if (m) { m.classList.remove('hidden'); m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;'; }
                if (p) { p.classList.remove('scale-95', 'opacity-0'); p.style.cssText = 'display: block !important; visibility: visible !important; opacity: 1 !important; position: relative; z-index: 100004 !important; margin: auto; transform: scale(1) !important;'; }
                return document.querySelector('#manualFlagModal input[name="missing_since"]') || p || m;
            },
            popover: { title: '3. Incident Timestamp', description: 'Specify the exact date when the vehicle became unreachable or went missing.', position: 'bottom' }
        },
        {
            id: 'manual-flag-reason-desc',
            route: '/units/flagged',
            onBeforeShow: () => {
                if (typeof openManualFlagModal === 'function') openManualFlagModal();
                const m = document.getElementById('manualFlagModal');
                const b = document.getElementById('manualFlagBackdrop');
                const p = document.getElementById('manualFlagPanel');
                if (m) { m.classList.remove('hidden'); m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;'; }
                if (b) { b.classList.remove('opacity-0'); b.classList.add('opacity-100'); b.style.cssText = 'display: block !important; visibility: visible !important; opacity: 0.6 !important; position: absolute; inset: 0; background: #0f172a;'; }
                if (p) { p.classList.remove('scale-95', 'opacity-0'); p.classList.add('scale-100', 'opacity-100'); p.style.cssText = 'display: block !important; visibility: visible !important; opacity: 1 !important; position: relative; z-index: 100004 !important; margin: auto; transform: scale(1) !important;'; }
            },
            getElement: () => {
                const m = document.getElementById('manualFlagModal');
                const p = document.getElementById('manualFlagPanel');
                if (m) { m.classList.remove('hidden'); m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;'; }
                if (p) { p.classList.remove('scale-95', 'opacity-0'); p.style.cssText = 'display: block !important; visibility: visible !important; opacity: 1 !important; position: relative; z-index: 100004 !important; margin: auto; transform: scale(1) !important;'; }
                return document.querySelector('#manualFlagModal textarea[name="description"]') || p || m;
            },
            popover: { title: '4. Incident Details & Remarks', description: 'Enter comprehensive incident notes (e.g. unreturned unit, uncontactable driver, or police report case numbers).', position: 'top' }
        },
        {
            id: 'manual-flag-submit-actions',
            route: '/units/flagged',
            onBeforeShow: () => {
                if (typeof openManualFlagModal === 'function') openManualFlagModal();
                const m = document.getElementById('manualFlagModal');
                const b = document.getElementById('manualFlagBackdrop');
                const p = document.getElementById('manualFlagPanel');
                if (m) { m.classList.remove('hidden'); m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;'; }
                if (b) { b.classList.remove('opacity-0'); b.classList.add('opacity-100'); b.style.cssText = 'display: block !important; visibility: visible !important; opacity: 0.6 !important; position: absolute; inset: 0; background: #0f172a;'; }
                if (p) { p.classList.remove('scale-95', 'opacity-0'); p.classList.add('scale-100', 'opacity-100'); p.style.cssText = 'display: block !important; visibility: visible !important; opacity: 1 !important; position: relative; z-index: 100004 !important; margin: auto; transform: scale(1) !important;'; }
            },
            onAfterNext: () => {
                if (typeof closeManualFlagModal === 'function') closeManualFlagModal();
                const m = document.getElementById('manualFlagModal');
                const b = document.getElementById('manualFlagBackdrop');
                const p = document.getElementById('manualFlagPanel');
                if (m) { m.classList.add('hidden'); m.style.cssText = 'display: none !important; z-index: -1 !important; visibility: hidden !important; opacity: 0 !important;'; }
                if (b) { b.classList.add('opacity-0'); b.style.cssText = 'display: none !important; visibility: hidden !important; opacity: 0 !important;'; }
                if (p) { p.classList.add('scale-95', 'opacity-0'); p.style.cssText = 'display: none !important; visibility: hidden !important; opacity: 0 !important;'; }
            },
            getElement: () => {
                const m = document.getElementById('manualFlagModal');
                if (m && m.classList.contains('hidden')) { m.classList.remove('hidden'); m.style.removeProperty('display'); }
                return document.querySelector('#manualFlagModal button[type="submit"]')?.closest('.border-t') || document.querySelector('#manualFlagModal button[type="submit"]') || document.getElementById('manualFlagPanel');
            },
            popover: { title: '5. Submit Report Action', description: 'Click \'Submit Flag\' to finalize the police report and broadcast the vehicle as missing across the fleet dashboard!', position: 'top' }
        },
        {
            id: 'flagged-unit-card-profile',
            route: '/units/flagged',
            onBeforeShow: () => {
                if (typeof closeManualFlagModal === 'function') closeManualFlagModal();
                const m = document.getElementById('manualFlagModal');
                const b = document.getElementById('manualFlagBackdrop');
                const p = document.getElementById('manualFlagPanel');
                if (m) { m.classList.add('hidden'); m.style.cssText = 'display: none !important; z-index: -1 !important; visibility: hidden !important; opacity: 0 !important;'; }
                if (b) { b.classList.add('opacity-0'); b.style.cssText = 'display: none !important; visibility: hidden !important; opacity: 0 !important;'; }
                if (p) { p.classList.add('scale-95', 'opacity-0'); p.style.cssText = 'display: none !important; visibility: hidden !important; opacity: 0 !important;'; }
                if (typeof setFilter === 'function') setFilter('all');
            },
            getElement: () => document.querySelector('#flaggedGrid > div') || document.getElementById('flaggedGrid'),
            popover: { title: '📋 Flagged Vehicle Profile Card', description: 'Displays complete incident profile—assigned driver at time of flag, missing timestamp, last submitted boundary date, contact info, and system failure remarks.', position: 'top' }
        },
        {
            id: 'flagged-card-header',
            route: '/units/flagged',
            onBeforeShow: () => {
                if (typeof closeManualFlagModal === 'function') closeManualFlagModal();
                const m = document.getElementById('manualFlagModal');
                const b = document.getElementById('manualFlagBackdrop');
                const p = document.getElementById('manualFlagPanel');
                if (m) { m.classList.add('hidden'); m.style.cssText = 'display: none !important; z-index: -1 !important; visibility: hidden !important; opacity: 0 !important;'; }
                if (b) { b.classList.add('opacity-0'); b.style.cssText = 'display: none !important; visibility: hidden !important; opacity: 0 !important;'; }
                if (p) { p.classList.add('scale-95', 'opacity-0'); p.style.cssText = 'display: none !important; visibility: hidden !important; opacity: 0 !important;'; }
            },
            getElement: () => document.querySelector('#flaggedGrid > div .p-6.bg-slate-50\\/60') || document.querySelector('#flaggedGrid > div'),
            popover: { title: '🚗 Vehicle Identity & Flag Badge', description: 'Shows plate number, flag status badge (AUTO-FLAGGED or MISSING), vehicle make/model/year, and internal system Unit ID.', position: 'top' }
        },
        {
            id: 'flagged-card-status-inactive',
            route: '/units/flagged',
            onBeforeShow: () => {
                if (typeof closeManualFlagModal === 'function') closeManualFlagModal();
                const m = document.getElementById('manualFlagModal');
                const b = document.getElementById('manualFlagBackdrop');
                const p = document.getElementById('manualFlagPanel');
                if (m) { m.classList.add('hidden'); m.style.cssText = 'display: none !important; z-index: -1 !important; visibility: hidden !important; opacity: 0 !important;'; }
                if (b) { b.classList.add('opacity-0'); b.style.cssText = 'display: none !important; visibility: hidden !important; opacity: 0 !important;'; }
                if (p) { p.classList.add('scale-95', 'opacity-0'); p.style.cssText = 'display: none !important; visibility: hidden !important; opacity: 0 !important;'; }
            },
            getElement: () => document.querySelector('#flaggedGrid > div .card-status-section') || document.querySelector('#flaggedGrid > div .grid.grid-cols-2') || document.querySelector('#flaggedGrid > div'),
            popover: { title: '⚠️ Flag Status & Inactive Duration', description: 'Tracks exact flag classification and calculates cumulative inactive days since the last boundary remittance.', position: 'top' }
        },
        {
            id: 'flagged-card-suspect-contact',
            route: '/units/flagged',
            onBeforeShow: () => {
                if (typeof closeManualFlagModal === 'function') closeManualFlagModal();
                const m = document.getElementById('manualFlagModal');
                const b = document.getElementById('manualFlagBackdrop');
                const p = document.getElementById('manualFlagPanel');
                if (m) { m.classList.add('hidden'); m.style.cssText = 'display: none !important; z-index: -1 !important; visibility: hidden !important; opacity: 0 !important;'; }
                if (b) { b.classList.add('opacity-0'); b.style.cssText = 'display: none !important; visibility: hidden !important; opacity: 0 !important;'; }
                if (p) { p.classList.add('scale-95', 'opacity-0'); p.style.cssText = 'display: none !important; visibility: hidden !important; opacity: 0 !important;'; }
            },
            getElement: () => document.querySelector('#flaggedGrid > div .card-driver-section') || document.querySelector('#flaggedGrid > div .grid.grid-cols-2') || document.querySelector('#flaggedGrid > div'),
            popover: { title: '👤 Suspect Driver & Contact Details', description: 'Identifies the driver assigned to the unit at the time of incident alongside their registered mobile contact number.', position: 'top' }
        },
        {
            id: 'flagged-card-description',
            route: '/units/flagged',
            onBeforeShow: () => {
                if (typeof closeManualFlagModal === 'function') closeManualFlagModal();
                const m = document.getElementById('manualFlagModal');
                const b = document.getElementById('manualFlagBackdrop');
                const p = document.getElementById('manualFlagPanel');
                if (m) { m.classList.add('hidden'); m.style.cssText = 'display: none !important; z-index: -1 !important; visibility: hidden !important; opacity: 0 !important;'; }
                if (b) { b.classList.add('opacity-0'); b.style.cssText = 'display: none !important; visibility: hidden !important; opacity: 0 !important;'; }
                if (p) { p.classList.add('scale-95', 'opacity-0'); p.style.cssText = 'display: none !important; visibility: hidden !important; opacity: 0 !important;'; }
            },
            getElement: () => document.querySelector('#flaggedGrid > div .card-description-section') || document.querySelector('#flaggedGrid > div .p-3\\.5.bg-slate-50') || document.querySelector('#flaggedGrid > div'),
            popover: { title: '📝 Incident Details & System Failure Notes', description: 'Displays exact missing timestamp and automatic system failure remarks (e.g. 48+ hours overdue shift deadline).', position: 'top' }
        },
        {
            id: 'flagged-card-boundary-history',
            route: '/units/flagged',
            onBeforeShow: () => {
                if (typeof closeManualFlagModal === 'function') closeManualFlagModal();
                const m = document.getElementById('manualFlagModal');
                const b = document.getElementById('manualFlagBackdrop');
                const p = document.getElementById('manualFlagPanel');
                if (m) { m.classList.add('hidden'); m.style.cssText = 'display: none !important; z-index: -1 !important; visibility: hidden !important; opacity: 0 !important;'; }
                if (b) { b.classList.add('opacity-0'); b.style.cssText = 'display: none !important; visibility: hidden !important; opacity: 0 !important;'; }
                if (p) { p.classList.add('scale-95', 'opacity-0'); p.style.cssText = 'display: none !important; visibility: hidden !important; opacity: 0 !important;'; }
            },
            getElement: () => document.querySelector('#flaggedGrid > div .card-audit-section') || document.querySelector('#flaggedGrid > div .pt-3.border-t') || document.querySelector('#flaggedGrid > div'),
            popover: { title: '📅 Boundary Payment & Driver Audit History', description: 'Logs the timestamp of the last submitted boundary payment and the last known driver on record.', position: 'top' }
        },
        {
            id: 'flagged-unit-card-actions',
            route: '/units/flagged',
            onBeforeShow: () => {
                if (typeof closeManualFlagModal === 'function') closeManualFlagModal();
                const m = document.getElementById('manualFlagModal');
                const b = document.getElementById('manualFlagBackdrop');
                const p = document.getElementById('manualFlagPanel');
                if (m) { m.classList.add('hidden'); m.style.cssText = 'display: none !important; z-index: -1 !important; visibility: hidden !important; opacity: 0 !important;'; }
                if (b) { b.classList.add('opacity-0'); b.style.cssText = 'display: none !important; visibility: hidden !important; opacity: 0 !important;'; }
                if (p) { p.classList.add('scale-95', 'opacity-0'); p.style.cssText = 'display: none !important; visibility: hidden !important; opacity: 0 !important;'; }
            },
            getElement: () => document.querySelector('#flaggedGrid > div .p-5.border-t') || document.querySelector('#flaggedGrid button[onclick*="openRecoverModal"]') || document.querySelector('#flaggedGrid button') || document.getElementById('flaggedGrid'),
            popover: { title: '⚡ Fleet Action Controls (View / Ignore / Recover)', description: 'Click \'View\' for complete vehicle history, \'Ignore\' to dismiss boundary warnings for 24h, or \'Mark Missing\' / \'Recover\' to update police status and reactivate the car back into the active fleet!', position: 'top' }
        },
        {
            id: 'sidebar-drivers',
            onBeforeShow: () => {
                if (typeof closeAddDriverModal === 'function') closeAddDriverModal();
                if (typeof closeDriverDetails === 'function') closeDriverDetails();
                const link = findSidebarLink(['Driver Management']);
                if (link) {
                    link.onclick = function(e) {
                        if (window.TutorialManager) window.TutorialManager.moveToNextStep(71);
                    };
                }
            },
            getElement: () => findSidebarLink(['Driver Management']),
            popover: { title: '🚕 Driver Management Module', description: 'Click \'Driver Management\' to enter the main driver roster, handle driver registrations, manage bans, contracts, and debt records!', position: 'right' }
        },
        {
            id: 'drivers-header-search',
            route: '/driver-management',
            onBeforeShow: () => {
                if (typeof closeAddDriverModal === 'function') closeAddDriverModal();
                if (typeof closeDriverDetails === 'function') closeDriverDetails();
            },
            getElement: () => document.querySelector('#tableSearchInput')?.closest('form') || document.querySelector('#tableSearchInput'),
            popover: { title: '🔍 Driver Search & Status Filters', description: 'Search drivers by full name or LTO license number, sort A-Z, or filter table by status (Active, Inactive, Available Without Unit).', position: 'bottom' }
        },
        {
            id: 'drivers-print-btn',
            route: '/driver-management',
            onBeforeShow: () => {
                if (typeof closeAddDriverModal === 'function') closeAddDriverModal();
                if (typeof closeDriverDetails === 'function') closeDriverDetails();
                if (typeof closeDriverPdfPreview === 'function') closeDriverPdfPreview();
                setTimeout(() => {
                    const btn = document.querySelector('button[onclick*="printInHiddenIframe"]') || document.querySelector('#driverActionButtonsBar button:first-child');
                    if (btn) {
                        btn.onclick = function() {
                            if (typeof openDriverPdfPreview === 'function') openDriverPdfPreview();
                            if (window.TutorialManager) window.TutorialManager.moveToNextStep(74);
                        };
                    }
                }, 50);
            },
            getElement: () => document.querySelector('button[onclick*="printInHiddenIframe"]') || document.querySelector('#driverActionButtonsBar button:first-child') || document.querySelector('#driverActionButtonsBar'),
            popover: { title: '🖨️ Print Driver Roster PDF Button', description: 'Clicking this button exports an official PDF summary report of all registered drivers. Let us open the live document preview!', position: 'bottom' }
        },
        {
            id: 'drivers-print-pdf-preview',
            route: '/driver-management',
            onBeforeShow: () => {
                if (typeof closeAddDriverModal === 'function') closeAddDriverModal();
                if (typeof closeDriverDetails === 'function') closeDriverDetails();
                if (typeof openDriverPdfPreview === 'function') openDriverPdfPreview();
                const m = document.getElementById('driverPrintPdfModal');
                if (m) { m.classList.remove('hidden'); m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;'; }
            },
            onAfterNext: () => {
                if (typeof closeDriverPdfPreview === 'function') closeDriverPdfPreview();
            },
            getElement: () => {
                const m = document.getElementById('driverPrintPdfModal');
                if (m) { m.classList.remove('hidden'); m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;'; }
                return document.querySelector('#driverPrintPdfModal > div') || document.querySelector('button[onclick*="printInHiddenIframe"]');
            },
            popover: { title: '📄 Live Driver Roster PDF Document Deep Dive', description: 'Here is the live generated Driver Roster PDF report! It compiles all official driver records—Driver Names, LTO License Numbers, Expiration Dates, Assigned Vehicle Units, and Status.', position: 'center' }
        },
        {
            id: 'drivers-add-btn',
            route: '/driver-management',
            onBeforeShow: () => {
                if (typeof closeAddDriverModal === 'function') closeAddDriverModal();
                if (typeof closeDriverDetails === 'function') closeDriverDetails();
                if (typeof closeDriverPdfPreview === 'function') closeDriverPdfPreview();
                setTimeout(() => {
                    const btn = document.querySelector('button[onclick*="openAddDriverModal"]') || document.querySelector('#driverActionButtonsBar button:nth-child(2)');
                    if (btn) {
                        btn.onclick = function() {
                            if (typeof openAddDriverModal === 'function') openAddDriverModal();
                            const m = document.getElementById('addDriverModal');
                            if (m) { m.classList.remove('hidden'); m.style.removeProperty('display'); m.style.setProperty('z-index', '100004', 'important'); }
                            if (window.TutorialManager) window.TutorialManager.moveToNextStep(75);
                        };
                    }
                }, 50);
            },
            getElement: () => document.querySelector('button[onclick*="openAddDriverModal"]') || document.querySelector('#driverActionButtonsBar button:nth-child(2)') || document.querySelector('#driverActionButtonsBar'),
            popover: { title: '➕ Add Driver Registration', description: 'Click \'Add Driver\' to open the driver registration form and enroll new drivers into your fleet database!', position: 'bottom' }
        },
        {
            id: 'add-driver-modal-overview',
            route: '/driver-management',
            onBeforeShow: () => {
                if (typeof closeDriverPdfPreview === 'function') closeDriverPdfPreview();
                const pm = document.getElementById('driverPrintPdfModal');
                if (pm) { pm.classList.add('hidden'); pm.style.cssText = 'display: none !important; z-index: -1 !important; visibility: hidden !important; opacity: 0 !important;'; }
                if (typeof openAddDriverModal === 'function') openAddDriverModal();
                const m = document.getElementById('addDriverModal');
                if (m) {
                    m.classList.remove('hidden');
                    m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;';
                    const sc = m.querySelector('.overflow-y-auto');
                    if (sc) sc.scrollTop = 0;
                }
            },
            getElement: () => {
                const m = document.getElementById('addDriverModal');
                if (m && m.classList.contains('hidden')) { m.classList.remove('hidden'); m.style.removeProperty('display'); }
                return document.querySelector('#addDriverModal > div') || document.getElementById('addDriverModal');
            },
            popover: { title: '➕ Add Driver Registration Form', description: 'This modal allows fleet managers to register new drivers, upload credentials (License, NBI, PNP Clearance), and assign vehicle units.', position: 'bottom' }
        },
        {
            id: 'add-driver-personal-info',
            route: '/driver-management',
            onBeforeShow: () => {
                if (typeof closeDriverPdfPreview === 'function') closeDriverPdfPreview();
                const pm = document.getElementById('driverPrintPdfModal');
                if (pm) { pm.classList.add('hidden'); pm.style.cssText = 'display: none !important; z-index: -1 !important; visibility: hidden !important; opacity: 0 !important;'; }
                if (typeof openAddDriverModal === 'function') openAddDriverModal();
                const m = document.getElementById('addDriverModal');
                if (m) {
                    m.classList.remove('hidden');
                    m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;';
                    const sc = m.querySelector('.overflow-y-auto');
                    const sec = document.getElementById('addDriverSecPersonal');
                    if (sc && sec) sc.scrollTop = Math.max(0, sec.offsetTop - 12);
                }
            },
            getElement: () => {
                const m = document.getElementById('addDriverModal');
                if (m && m.classList.contains('hidden')) { m.classList.remove('hidden'); m.style.removeProperty('display'); }
                return document.getElementById('addDriverSecPersonal') || document.getElementById('driverFirstName')?.closest('.p-6 > div');
            },
            popover: { title: '👤 Personal Information & Contact', description: 'Fill in driver\'s first name, last name, 11-digit mobile contact number, driver status (Active/Inactive), and full home address.', position: 'bottom' }
        },
        {
            id: 'add-driver-license-info',
            route: '/driver-management',
            onBeforeShow: () => {
                if (typeof closeDriverPdfPreview === 'function') closeDriverPdfPreview();
                const pm = document.getElementById('driverPrintPdfModal');
                if (pm) { pm.classList.add('hidden'); pm.style.cssText = 'display: none !important; z-index: -1 !important; visibility: hidden !important; opacity: 0 !important;'; }
                if (typeof openAddDriverModal === 'function') openAddDriverModal();
                const m = document.getElementById('addDriverModal');
                if (m) {
                    m.classList.remove('hidden');
                    m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;';
                    const sc = m.querySelector('.overflow-y-auto');
                    const sec = document.getElementById('addDriverSecLicense');
                    if (sc && sec) sc.scrollTop = Math.max(0, sec.offsetTop - 12);
                }
            },
            getElement: () => {
                const m = document.getElementById('addDriverModal');
                if (m && m.classList.contains('hidden')) { m.classList.remove('hidden'); m.style.removeProperty('display'); }
                return document.getElementById('addDriverSecLicense') || document.getElementById('driverLicense')?.closest('.p-6 > div');
            },
            popover: { title: '🪪 LTO License Details & Employment', description: 'Enter official LTO driver\'s license number (format: X00-00-000000), license expiry date, hire date, and auto-synced boundary target rate.', position: 'bottom' }
        },
        {
            id: 'add-driver-document-vault',
            route: '/driver-management',
            onBeforeShow: () => {
                if (typeof closeDriverPdfPreview === 'function') closeDriverPdfPreview();
                const pm = document.getElementById('driverPrintPdfModal');
                if (pm) { pm.classList.add('hidden'); pm.style.cssText = 'display: none !important; z-index: -1 !important; visibility: hidden !important; opacity: 0 !important;'; }
                if (typeof openAddDriverModal === 'function') openAddDriverModal();
                const m = document.getElementById('addDriverModal');
                if (m) {
                    m.classList.remove('hidden');
                    m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;';
                    const sc = m.querySelector('.overflow-y-auto');
                    const sec = document.getElementById('addDriverSecVault');
                    if (sc && sec) sc.scrollTop = Math.max(0, sec.offsetTop - 12);
                }
            },
            getElement: () => {
                const m = document.getElementById('addDriverModal');
                if (m && m.classList.contains('hidden')) { m.classList.remove('hidden'); m.style.removeProperty('display'); }
                return document.getElementById('addDriverSecVault') || document.getElementById('input_profile_photo')?.closest('.mt-6');
            },
            popover: { title: '📁 Secure Document Vault & Clearances', description: 'Upload copy of driver\'s Profile Photo, LTO License, NBI Clearance, and PNP/Barangay Clearance for official compliance verification.', position: 'bottom' }
        },
        {
            id: 'add-driver-emergency-contact',
            route: '/driver-management',
            onBeforeShow: () => {
                if (typeof closeDriverPdfPreview === 'function') closeDriverPdfPreview();
                const pm = document.getElementById('driverPrintPdfModal');
                if (pm) { pm.classList.add('hidden'); pm.style.cssText = 'display: none !important; z-index: -1 !important; visibility: hidden !important; opacity: 0 !important;'; }
                if (typeof openAddDriverModal === 'function') openAddDriverModal();
                const m = document.getElementById('addDriverModal');
                if (m) {
                    m.classList.remove('hidden');
                    m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;';
                    const sc = m.querySelector('.overflow-y-auto');
                    const sec = document.getElementById('addDriverSecEmergency');
                    if (sc && sec) sc.scrollTop = Math.max(0, sec.offsetTop - 12);
                }
            },
            getElement: () => {
                const m = document.getElementById('addDriverModal');
                if (m && m.classList.contains('hidden')) { m.classList.remove('hidden'); m.style.removeProperty('display'); }
                return document.getElementById('addDriverSecEmergency') || document.getElementById('driverEmergencyContact')?.closest('.p-6 > div');
            },
            popover: { title: '🚨 Emergency Contact Details', description: 'Record emergency contact person\'s full name and 11-digit mobile phone number to notify in case of accidents or road emergencies.', position: 'bottom' }
        },
        {
            id: 'add-driver-submit-actions',
            route: '/driver-management',
            onBeforeShow: () => {
                if (typeof closeDriverPdfPreview === 'function') closeDriverPdfPreview();
                const pm = document.getElementById('driverPrintPdfModal');
                if (pm) { pm.classList.add('hidden'); pm.style.cssText = 'display: none !important; z-index: -1 !important; visibility: hidden !important; opacity: 0 !important;'; }
                if (typeof openAddDriverModal === 'function') openAddDriverModal();
                const m = document.getElementById('addDriverModal');
                if (m) {
                    m.classList.remove('hidden');
                    m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;';
                    const sc = m.querySelector('.overflow-y-auto');
                    if (sc) sc.scrollTop = sc.scrollHeight;
                }
            },
            onAfterNext: () => {
                if (typeof closeAddDriverModal === 'function') closeAddDriverModal();
                const m = document.getElementById('addDriverModal');
                if (m) { m.classList.add('hidden'); m.style.cssText = 'display: none !important; z-index: -1 !important; visibility: hidden !important; opacity: 0 !important;'; }
            },
            getElement: () => {
                const m = document.getElementById('addDriverModal');
                if (m && m.classList.contains('hidden')) { m.classList.remove('hidden'); m.style.removeProperty('display'); }
                return document.getElementById('addDriverSecActions') || document.querySelector('#addDriverModal button[type="submit"]')?.closest('.p-4') || document.querySelector('#addDriverModal button[type="submit"]');
            },
            popover: { title: '💾 Save Driver Record & Submit Form', description: 'Click \'Save Driver\' to finalize registration and store driver profile into system database!', position: 'top' }
        },
        {
            id: 'drivers-table-info-overview',
            route: '/driver-management',
            onBeforeShow: () => {
                if (typeof closeAddDriverModal === 'function') closeAddDriverModal();
                if (typeof closeDriverDetails === 'function') closeDriverDetails();
                const m = document.getElementById('addDriverModal');
                if (m) { m.classList.add('hidden'); m.style.cssText = 'display: none !important; z-index: -1 !important; visibility: hidden !important; opacity: 0 !important;'; }
                window.scrollTo({ top: 0, behavior: 'instant' });
            },
            getElement: () => document.getElementById('driversTableContainer') || document.querySelector('#driversTableContainer table'),
            popover: { title: '📋 Driver Roster Information Table', description: 'Overview of all active, inactive, and available fleet drivers with live shortage and debt status badges.', position: 'bottom' }
        },
        {
            id: 'drivers-table-profile-col',
            route: '/driver-management',
            onBeforeShow: () => {
                if (typeof closeAddDriverModal === 'function') closeAddDriverModal();
                if (typeof closeDriverDetails === 'function') closeDriverDetails();
                window.scrollTo({ top: 0, behavior: 'instant' });
            },
            getElement: () => document.querySelector('#driversTableContainer table thead tr th:first-child') || document.querySelector('.modern-table-sep thead tr th:first-child') || document.querySelector('#driversTableContainer table tbody tr td:first-child'),
            popover: { title: '👤 Driver Profile & Shortage/Debt Alerts', description: 'Displays driver avatar initials, full registered name, and live warning badges for unpaid boundary shortages or pending accident debts.', position: 'bottom' }
        },
        {
            id: 'drivers-table-unit-col',
            route: '/driver-management',
            onBeforeShow: () => {
                if (typeof closeAddDriverModal === 'function') closeAddDriverModal();
                if (typeof closeDriverDetails === 'function') closeDriverDetails();
                window.scrollTo({ top: 0, behavior: 'instant' });
            },
            getElement: () => document.querySelector('#driversTableContainer table thead tr th:nth-child(2)') || document.querySelector('.modern-table-sep thead tr th:nth-child(2)') || document.querySelector('#driversTableContainer table tbody tr td:nth-child(2)'),
            popover: { title: '🚗 Assigned Taxi Unit', description: 'Shows the active vehicle plate number assigned to the driver (e.g. AAA 4591) for daily boundary operations.', position: 'bottom' }
        },
        {
            id: 'drivers-table-license-col',
            route: '/driver-management',
            onBeforeShow: () => {
                if (typeof closeAddDriverModal === 'function') closeAddDriverModal();
                if (typeof closeDriverDetails === 'function') closeDriverDetails();
                window.scrollTo({ top: 0, behavior: 'instant' });
            },
            getElement: () => document.querySelector('#driversTableContainer table thead tr th:nth-child(3)') || document.querySelector('.modern-table-sep thead tr th:nth-child(3)') || document.querySelector('#driversTableContainer table tbody tr td:nth-child(3)'),
            popover: { title: '🪪 Driver License & Expiration', description: 'Logs the LTO driver\'s license number and tracks document expiration dates for compliance auditing.', position: 'bottom' }
        },
        {
            id: 'drivers-table-status-col',
            route: '/driver-management',
            onBeforeShow: () => {
                if (typeof closeAddDriverModal === 'function') closeAddDriverModal();
                if (typeof closeDriverDetails === 'function') closeDriverDetails();
                window.scrollTo({ top: 0, behavior: 'instant' });
            },
            getElement: () => document.querySelector('#driversTableContainer table thead tr th:nth-child(4)') || document.querySelector('.modern-table-sep thead tr th:nth-child(4)') || document.querySelector('#driversTableContainer table tbody tr td:nth-child(4)'),
            popover: { title: '🟢 Driver Operational Status', description: 'Indicates current status—Active (on shift), Inactive, or Banned due to system/boundary violations.', position: 'bottom' }
        },
        {
            id: 'drivers-table-actions-col',
            route: '/driver-management',
            onBeforeShow: () => {
                if (typeof closeAddDriverModal === 'function') closeAddDriverModal();
                if (typeof closeDriverDetails === 'function') closeDriverDetails();
                window.scrollTo({ top: 0, behavior: 'instant' });
            },
            getElement: () => document.querySelector('#driversTableContainer table thead tr th:last-child') || document.querySelector('.modern-table-sep thead tr th:last-child') || document.querySelector('#driversTableContainer table tbody tr td:last-child'),
            popover: { title: '⚡ Driver Row Actions (3-Dots Menu)', description: 'Click the 3-dots action menu or row to view full driver history, edit driver profile, manage debt records, or issue a driver ban.', position: 'bottom' }
        },
        {
            id: 'driver-details-modal-overview',
            route: '/driver-management',
            onBeforeShow: () => {
                if (typeof closeAddDriverModal === 'function') closeAddDriverModal();
                if (typeof closeDriverPdfPreview === 'function') closeDriverPdfPreview();
                const pm = document.getElementById('driverPrintPdfModal');
                if (pm) { pm.classList.add('hidden'); pm.style.cssText = 'display: none !important; z-index: -1 !important; visibility: hidden !important; opacity: 0 !important;'; }
                const m = document.getElementById('driverDetailsModal');
                if (m) {
                    m.classList.remove('hidden');
                    m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;';
                }
                const firstRow = document.querySelector('.modern-table-sep tbody tr') || document.querySelector('#driversTableContainer table tbody tr');
                if (firstRow) {
                    const onclickAttr = firstRow.getAttribute('onclick');
                    if (onclickAttr && onclickAttr.includes('openDriverDetails')) {
                        try { eval(onclickAttr); } catch (e) {}
                    } else if (typeof openDriverDetails === 'function') {
                        openDriverDetails(1);
                    }
                }
            },
            getElement: () => {
                const m = document.getElementById('driverDetailsModal');
                if (m) {
                    m.classList.remove('hidden');
                    m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;';
                }
                return document.querySelector('#driverDetailsModal > div') || document.getElementById('driverDetailsModalContainer') || document.getElementById('driverDetailsModal');
            },
            popover: { title: '🔍 Driver Profiling & Details Dashboard', description: 'This interactive dashboard opens when clicking a driver row to review full personal records, performance metrics, document vault, and debt ledger.', position: 'right' }
        },
        {
            id: 'driver-details-tab-basic',
            route: '/driver-management',
            onBeforeShow: () => {
                if (typeof closeAddDriverModal === 'function') closeAddDriverModal();
                const m = document.getElementById('driverDetailsModal');
                if (m) {
                    m.classList.remove('hidden');
                    m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;';
                }
                const nameEl = document.getElementById('detailsDriverName');
                if (!nameEl || !nameEl.textContent.trim() || nameEl.textContent.includes('Loading')) {
                    const firstRow = document.querySelector('.modern-table-sep tbody tr') || document.querySelector('#driversTableContainer table tbody tr');
                    if (firstRow) {
                        const onclickAttr = firstRow.getAttribute('onclick');
                        if (onclickAttr && onclickAttr.includes('openDriverDetails')) {
                            try { eval(onclickAttr); } catch (e) {}
                        } else if (typeof openDriverDetails === 'function') {
                            openDriverDetails(1);
                        }
                    }
                }
                const tab = document.querySelector('.driver-tab[data-tab="basic"]');
                if (tab) tab.click();
            },
            getElement: () => {
                const m = document.getElementById('driverDetailsModal');
                if (m) {
                    m.classList.remove('hidden');
                    m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;';
                }
                return document.querySelector('#driverDetailsModal > div') || document.getElementById('driverDetailsModalContainer') || document.getElementById('driverDetailsModal');
            },
            popover: { title: '👤 Basic Info & Personal Details Tab', description: 'Displays full registered name, emergency contact details, date hired, address, and audit logs of creator and editor staff.', position: 'bottom' }
        },
        {
            id: 'driver-details-tab-license',
            route: '/driver-management',
            onBeforeShow: () => {
                if (typeof closeAddDriverModal === 'function') closeAddDriverModal();
                const m = document.getElementById('driverDetailsModal');
                if (m) {
                    m.classList.remove('hidden');
                    m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;';
                }
                const tab = document.querySelector('.driver-tab[data-tab="license"]');
                if (tab) tab.click();
            },
            getElement: () => {
                const m = document.getElementById('driverDetailsModal');
                if (m) {
                    m.classList.remove('hidden');
                    m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;';
                }
                return document.querySelector('#driverDetailsModal > div') || document.getElementById('driverDetailsModalContainer') || document.getElementById('driverDetailsModal');
            },
            popover: { title: '🪪 License & Document Vault Tab', description: 'View LTO license number, expiration validity alerts, and upload encrypted document clearances (NBI, PNP, Barangay).', position: 'bottom' }
        },
        {
            id: 'driver-details-tab-incentives',
            route: '/driver-management',
            onBeforeShow: () => {
                if (typeof closeAddDriverModal === 'function') closeAddDriverModal();
                const m = document.getElementById('driverDetailsModal');
                if (m) {
                    m.classList.remove('hidden');
                    m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;';
                }
                const tab = document.querySelector('.driver-tab[data-tab="incentives"]');
                if (tab) tab.click();
            },
            getElement: () => {
                const m = document.getElementById('driverDetailsModal');
                if (m) {
                    m.classList.remove('hidden');
                    m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;';
                }
                return document.querySelector('#driverDetailsModal > div') || document.getElementById('driverDetailsModalContainer') || document.getElementById('driverDetailsModal');
            },
            popover: { title: '🏆 Incentive & Rewards Performance Tab', description: 'Tracks driver remittance bonus eligibility, monthly top driver status, and reward payout tier calculations.', position: 'bottom' }
        },
        {
            id: 'driver-details-tab-performance',
            route: '/driver-management',
            onBeforeShow: () => {
                if (typeof closeAddDriverModal === 'function') closeAddDriverModal();
                const m = document.getElementById('driverDetailsModal');
                if (m) {
                    m.classList.remove('hidden');
                    m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;';
                }
                const tab = document.querySelector('.driver-tab[data-tab="performance"]');
                if (tab) tab.click();
            },
            getElement: () => {
                const m = document.getElementById('driverDetailsModal');
                if (m) {
                    m.classList.remove('hidden');
                    m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;';
                }
                return document.querySelector('#driverDetailsModal > div') || document.getElementById('driverDetailsModalContainer') || document.getElementById('driverDetailsModal');
            },
            popover: { title: '📈 Telemetry & Operational Metrics Tab', description: 'Monitors daily boundary payment history, shortage balances, total shifts completed, and accident incident logs.', position: 'bottom' }
        },
        {
            id: 'driver-details-tab-insights',
            route: '/driver-management',
            onBeforeShow: () => {
                if (typeof closeAddDriverModal === 'function') closeAddDriverModal();
                const m = document.getElementById('driverDetailsModal');
                if (m) {
                    m.classList.remove('hidden');
                    m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;';
                }
                const tab = document.querySelector('.driver-tab[data-tab="insights"]');
                if (tab) tab.click();
            },
            getElement: () => {
                const m = document.getElementById('driverDetailsModal');
                if (m) {
                    m.classList.remove('hidden');
                    m.style.cssText = 'display: flex !important; z-index: 100004 !important; visibility: visible !important; opacity: 1 !important; align-items: center; justify-content: center; position: fixed; inset: 0;';
                }
                return document.querySelector('#driverDetailsModal > div') || document.getElementById('driverDetailsModalContainer') || document.getElementById('driverDetailsModal');
            },
            popover: { title: '🧠 AI Insights & Fleet Risk Recommendations', description: 'Generates automated AI recommendations on driver reliability, risk factors, and boundary target adjustments.', position: 'bottom' }
        },
        {
            id: 'drivers-sub-navigation',
            route: '/driver-management',
            onBeforeShow: () => {
                if (typeof closeAddDriverModal === 'function') closeAddDriverModal();
                if (typeof closeDriverDetails === 'function') closeDriverDetails();
                const m = document.getElementById('driverDetailsModal');
                if (m) { m.classList.add('hidden'); m.style.setProperty('display', 'none', 'important'); }
            },
            getElement: () => {
                const link = findSidebarLink(['Banned Drivers', 'Driver Terms', 'Pending Debts']);
                return link ? link.closest('div') || link : findSidebarLink(['Driver Management']);
            },
            popover: { title: '📁 Driver Management Sub-Navigation', description: 'Access specialized driver registries—Banned Drivers list, Driver Terms & Contracts, and Pending Debts ledger.', position: 'right' }
        },
        {
            id: 'tour-complete-summary',
            route: '/driver-management',
            onBeforeShow: () => {
                if (typeof closeAddDriverModal === 'function') closeAddDriverModal();
                if (typeof closeDriverDetails === 'function') closeDriverDetails();
                const m = document.getElementById('driverDetailsModal');
                if (m) { m.classList.add('hidden'); m.style.setProperty('display', 'none', 'important'); }
            },
            getElement: () => document.querySelector('#driversTableContainer') || document.querySelector('body'),
            popover: { title: '🎉 EuroTaxi System Tour Completed!', description: 'Congratulations! You have completed the full interactive walkthrough of the EuroTaxi Fleet Management system. Retake this tour anytime from your profile menu!', position: 'bottom' }
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

        const hasActiveStep = localStorage.getItem('tutorial_current_step') !== null && 
                              localStorage.getItem('tutorial_current_step') !== '' &&
                              localStorage.getItem('tutorial_current_step') !== undefined;

        if (tutorialCompleted && !hasActiveStep && !localStorage.getItem('tutorial_force_restart')) {
            logDebug("Tutorial already completed and no active step in progress. Exiting.");
            return;
        }

        enableTutorialDataProtection();

        const welcomeShown = localStorage.getItem('tutorial_welcome_shown');
        const forceRestart = localStorage.getItem('tutorial_force_restart');
        
        if ((!welcomeShown || forceRestart) && !hasActiveStep) {
            logDebug("Showing welcome modal.");
            if (driverObj) {
                try { driverObj.destroy(); } catch (e) {}
                driverObj = null;
            }
            document.querySelectorAll('.driver-popover, .driver-overlay, #tutorial-global-progress').forEach(el => el.remove());
            showWelcomeModal();
        } else {
            const currentStepIndex = parseInt(localStorage.getItem('tutorial_current_step') || '0', 10);
            logDebug("Current step index loaded on page init: " + currentStepIndex);
            startTutorial(currentStepIndex);
        }
    }

    function showWelcomeModal() {
        if (driverObj) {
            try { driverObj.destroy(); } catch (e) {}
            driverObj = null;
        }
        document.querySelectorAll('.driver-popover, .driver-overlay, #tutorial-global-progress').forEach(el => el.remove());

        if (document.getElementById('tutorial-welcome-modal')) return;
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

        const startBtn = document.getElementById('tutorial-start-btn');
        if (startBtn) {
            setTimeout(() => startBtn.focus(), 50);
            startBtn.addEventListener('click', () => {
                logDebug("User clicked Start Tour");
                const m = document.getElementById('tutorial-welcome-modal');
                if (m) m.remove();
                localStorage.setItem('tutorial_welcome_shown', '1');
                localStorage.setItem('tutorial_current_step', '0');
                localStorage.removeItem('tutorial_force_restart');
                startTutorial(0);
            });
        }

        const skipBtn = document.getElementById('tutorial-skip-btn');
        if (skipBtn) {
            skipBtn.addEventListener('click', () => {
                logDebug("User skipped tour");
                const m = document.getElementById('tutorial-welcome-modal');
                if (m) m.remove();
                markTutorialComplete();
            });
        }
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

    let currentElevatedElement = null;
    let originalElementStyles = {};

    function clearElevatedTarget(currentStep) {
        if (currentElevatedElement) {
            if (originalElementStyles.zIndex !== undefined && originalElementStyles.zIndex !== '') {
                currentElevatedElement.style.zIndex = originalElementStyles.zIndex;
            } else {
                currentElevatedElement.style.removeProperty('z-index');
            }
            if (originalElementStyles.position !== undefined && originalElementStyles.position !== '') {
                currentElevatedElement.style.position = originalElementStyles.position;
            } else {
                currentElevatedElement.style.removeProperty('position');
            }
            if (originalElementStyles.pointerEvents !== undefined && originalElementStyles.pointerEvents !== '') {
                currentElevatedElement.style.pointerEvents = originalElementStyles.pointerEvents;
            } else {
                currentElevatedElement.style.removeProperty('pointer-events');
            }
            currentElevatedElement = null;
            originalElementStyles = {};
        }

        // Clean up unitDetailsModal inline styles if current step is NOT a modal profile step
        if (!currentStep || (!currentStep.id.startsWith('unit-details-') && currentStep.id !== 'units-row-click-deepdive')) {
            const modal = document.getElementById('unitDetailsModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.style.removeProperty('display');
                modal.style.removeProperty('z-index');
                modal.style.removeProperty('pointer-events');
                modal.style.display = 'none';
                const innerCard = modal.querySelector(':scope > div');
                if (innerCard) {
                    innerCard.style.removeProperty('z-index');
                }
            }
        }

        // Clean up manualFlagModal inline styles if current step is NOT a manual flag modal step
        if (!currentStep || (!currentStep.id.startsWith('manual-flag-') && currentStep.id !== 'manual-flag-modal-panel')) {
            const manualModal = document.getElementById('manualFlagModal');
            const manualBackdrop = document.getElementById('manualFlagBackdrop');
            const manualPanel = document.getElementById('manualFlagPanel');
            if (manualModal) {
                manualModal.classList.add('hidden');
                manualModal.style.cssText = 'display: none !important; z-index: -1 !important; visibility: hidden !important; opacity: 0 !important;';
            }
            if (manualBackdrop) {
                manualBackdrop.classList.add('opacity-0');
                manualBackdrop.style.cssText = 'display: none !important; visibility: hidden !important; opacity: 0 !important;';
            }
            if (manualPanel) {
                manualPanel.classList.add('scale-95', 'opacity-0');
                manualPanel.style.cssText = 'display: none !important; visibility: hidden !important; opacity: 0 !important;';
            }
        }

        // Clean up addDriverModal inline styles if current step is NOT an add driver modal step
        if (!currentStep || !currentStep.id.startsWith('add-driver-')) {
            const addDriverM = document.getElementById('addDriverModal');
            if (addDriverM) {
                addDriverM.classList.add('hidden');
                addDriverM.style.cssText = 'display: none !important; z-index: -1 !important; visibility: hidden !important; opacity: 0 !important;';
            }
        }

        // Clean up driverDetailsModal inline styles if current step is NOT a driver details modal step
        if (!currentStep || (!currentStep.id.startsWith('driver-details-') && currentStep.id !== 'drivers-row-click-deepdive')) {
            const driverDetailsM = document.getElementById('driverDetailsModal');
            if (driverDetailsM) {
                driverDetailsM.classList.add('hidden');
                driverDetailsM.style.cssText = 'display: none !important; z-index: -1 !important; visibility: hidden !important; opacity: 0 !important;';
            }
        }

        // Clean up driverPrintPdfModal and tutorialPrintPdfModal inline styles if current step is NOT a PDF preview step
        if (!currentStep || (currentStep.id !== 'drivers-print-pdf-preview' && currentStep.id !== 'units-print-pdf-preview')) {
            if (typeof closeDriverPdfPreview === 'function') closeDriverPdfPreview();
            if (typeof closeTutorialPdfPreview === 'function') closeTutorialPdfPreview();
            const driverPdfM = document.getElementById('driverPrintPdfModal');
            if (driverPdfM) {
                driverPdfM.classList.add('hidden');
                driverPdfM.style.cssText = 'display: none !important; z-index: -1 !important; visibility: hidden !important; opacity: 0 !important;';
            }
            const tutPdfM = document.getElementById('tutorialPrintPdfModal');
            if (tutPdfM) {
                tutPdfM.classList.add('hidden');
                tutPdfM.style.cssText = 'display: none !important; z-index: -1 !important; visibility: hidden !important; opacity: 0 !important;';
            }
        }
    }

    function startTutorial(stepIndex, retryCount = 0) {
        logDebug(`startTutorial called with stepIndex: ${stepIndex}, retryCount: ${retryCount}`);
        
        // If Welcome Modal is active, DO NOT run background steps
        if (document.getElementById('tutorial-welcome-modal')) {
            logDebug("Welcome modal is active. Blocking startTutorial.");
            if (driverObj) {
                try { driverObj.destroy(); } catch (err) {}
                driverObj = null;
            }
            document.querySelectorAll('.driver-popover, .driver-overlay, #tutorial-global-progress').forEach(el => el.remove());
            return;
        }

        if (stepIndex >= steps.length) {
            logDebug("Reached end of steps. Finishing tutorial.");
            finishTutorial();
            return;
        }

        const step = steps[stepIndex];
        logDebug(`Attempting to find element for step: ${step.id}`);

        clearElevatedTarget(step);

        // Auto-navigate to step route if specified and browser is not currently on that route
        if (step.route && !window.location.pathname.startsWith(step.route)) {
            logDebug(`Navigating to route ${step.route} for step '${step.id}'`);
            localStorage.setItem('tutorial_current_step', stepIndex.toString());
            window.location.href = step.route;
            return;
        }

        // Enforce strict View Mode for Unit Management steps: Step 19 ('units-cards-deepdive') is Cards view; ALL other steps are Table view.
        if (window.location.pathname === '/units' && typeof setViewMode === 'function') {
            if (step.id === 'units-cards-deepdive') {
                setViewMode('grid');
            } else {
                setViewMode('table');
            }
        }

        // Run onBeforeShow hook if defined
        if (typeof step.onBeforeShow === 'function') {
            try {
                step.onBeforeShow();
            } catch (e) {
                logDebug(`Error in onBeforeShow for step ${step.id}: ${e.message}`);
            }
        }

        let targetElement = step.getElement ? step.getElement() : null;
        
        if (!targetElement) {
            if (retryCount < 4) {
                logDebug(`Target element for step '${step.id}' not ready yet. Retrying (${retryCount + 1}/4)...`);
                setTimeout(() => {
                    startTutorial(stepIndex, retryCount + 1);
                }, 75);
                return;
            } else {
                logDebug(`Fallback container element used for step '${step.id}' after retries.`);
                targetElement = document.querySelector('#driverDetailsModal > div') ||
                                document.querySelector('#addDriverModal > div') ||
                                document.querySelector('#unitDetailsModal > div') ||
                                document.querySelector('#addUnitModal > div') ||
                                document.querySelector('#driversTableContainer') ||
                                document.body;
            }
        }

        // Apply a unique ID to guarantee Driver.js finds the exact DOM element via CSS selector
        const dynamicId = `tutorial-active-target-${stepIndex}`;
        targetElement.id = dynamicId;
        logDebug(`Target element found. Assigned ID: #${dynamicId}`);

        // Save original styles of current target element so we can cleanly restore them on step change
        currentElevatedElement = targetElement;
        originalElementStyles = {
            zIndex: targetElement.style.zIndex || '',
            position: targetElement.style.position || '',
            pointerEvents: targetElement.style.pointerEvents || ''
        };

        // Ensure ONLY the current highlighted element sits above Driver overlay (z-index 100005) and receives pointer events directly
        targetElement.style.setProperty('z-index', '100005', 'important');
        const computedPos = window.getComputedStyle(targetElement).position;
        if (computedPos !== 'fixed' && computedPos !== 'absolute' && !targetElement.id.endsWith('Modal') && !targetElement.classList.contains('fixed')) {
            targetElement.style.setProperty('position', 'relative', 'important');
        }
        targetElement.style.setProperty('pointer-events', 'auto', 'important');
        targetElement.style.setProperty('cursor', 'pointer', 'important');
        try {
            targetElement.querySelectorAll('*').forEach(child => child.style.setProperty('cursor', 'pointer', 'important'));
        } catch (e) {}

        // Elevate modal container parent stacking context if target is inside a modal so clicks pass through Driver overlay
        const parentModal = targetElement.closest('#unitDetailsModal, #addUnitModal, #editUnitModal, #manualFlagModal, #addDriverModal, #driverDetailsModal, .modal-container');
        if (parentModal) {
            parentModal.style.setProperty('z-index', '100004', 'important');
            parentModal.style.setProperty('pointer-events', 'auto', 'important');
            const innerCard = parentModal.querySelector(':scope > div');
            if (innerCard) {
                innerCard.style.setProperty('z-index', '100004', 'important');
            }
        }

        // Misstouch protection: If target is a tab button inside unitDetailsModal, block clicks on non-highlighted tabs
        if (targetElement.classList.contains('tab-btn')) {
            const allTabs = document.querySelectorAll('#unitDetailsModal .tab-btn');
            allTabs.forEach(tb => {
                if (tb !== targetElement) {
                    tb.style.setProperty('pointer-events', 'none', 'important');
                    tb.style.setProperty('opacity', '0.4', 'important');
                    tb.style.setProperty('cursor', 'not-allowed', 'important');
                } else {
                    tb.style.setProperty('pointer-events', 'auto', 'important');
                    tb.style.setProperty('opacity', '1', 'important');
                    tb.style.setProperty('cursor', 'pointer', 'important');
                }
            });
        }

        // Global capture click listener for tab buttons to bypass any CSS stacking context or Driver overlay barriers
        if (window._stepTabGlobalClick) {
            window.removeEventListener('click', window._stepTabGlobalClick, true);
            window._stepTabGlobalClick = null;
        }

        if (targetElement && (targetElement.classList.contains('tab-btn') || step.id.startsWith('unit-details-'))) {
            window._stepTabGlobalClick = function(e) {
                const currentStep = parseInt(localStorage.getItem('tutorial_current_step') || '0');
                if (currentStep !== stepIndex) return;

                const rect = targetElement.getBoundingClientRect();
                const isClickInside = (
                    e.clientX >= rect.left - 12 &&
                    e.clientX <= rect.right + 12 &&
                    e.clientY >= rect.top - 12 &&
                    e.clientY <= rect.bottom + 12
                );

                if (isClickInside) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();

                    const tabName = targetElement.getAttribute('data-tab');
                    if (tabName && typeof showTab === 'function') {
                        showTab(tabName);
                    }

                    if (window._stepTabGlobalClick) {
                        window.removeEventListener('click', window._stepTabGlobalClick, true);
                        window._stepTabGlobalClick = null;
                    }

                    if (driverObj) {
                        try { driverObj.destroy(); } catch (err) {}
                    }

                    if (window.TutorialManager) {
                        window.TutorialManager.moveToNextStep(stepIndex);
                    }
                }
            };
            window.addEventListener('click', window._stepTabGlobalClick, true);
        }

        const isLastStep = stepIndex === steps.length - 1;

        initProgressBar(steps.length);
        updateProgress(stepIndex, steps.length);

        try {
            logDebug(`Initializing Driver.js for #${dynamicId}`);
            driverObj = window.driver.js.driver({
                showProgress: false,
                allowClose: false,
                disableActiveInteraction: false,
                overlayColor: 'rgba(15, 23, 42, 0.8)',
                popoverOffset: 80, // Move the popover further away to give the arrow space
                animate: true,
                smoothScroll: false,
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
                        if (wrapper && targetElement) {
                            setTimeout(() => {
                                if (!document.body.contains(wrapper)) return;

                                const tRect = targetElement.getBoundingClientRect();
                                const pRect = wrapper.getBoundingClientRect();

                                let pos = step.popover.position || 'right';
                                let arrowSvg = '';

                                if (step.id === 'unit-details-close') {
                                    if (wrapper) wrapper.classList.add('popover-unit-details-close');
                                    pos = 'right';

                                    arrowSvg = `
                                        <div class="tutorial-arrow-wrapper arrow-fade-in" style="position:absolute; left:-120px; top:-65px; width:140px; height:100px; pointer-events:none; z-index:2000001;">
                                            <svg class="tutorial-curved-arrow" viewBox="0 0 140 100" style="width:100%; height:100%; overflow:visible;">
                                                <path d="M 130 65 Q 70 -5 18 18" stroke="#60a5fa" stroke-width="4" stroke-linecap="round" stroke-dasharray="8,8" fill="none" />
                                                <polygon points="18,18 30,10 26,24" fill="#60a5fa" />
                                            </svg>
                                        </div>
                                    `;
                                } else if (step.id.startsWith('unit-details-') && targetElement.classList.contains('tab-btn')) {
                                    pos = 'left';
                                    // Calculate exact dynamic target X & Y distance from popover right edge to target tab center
                                    const startX = 10;
                                    const startY = Math.max(30, Math.min(80, pRect.height / 2));

                                    const targetX = (tRect.left + tRect.width / 2) - pRect.right;
                                    const targetY = (tRect.top + tRect.height / 2) - pRect.top;

                                    const svgWidth = Math.max(140, Math.round(targetX + 40));
                                    const svgHeight = Math.max(100, Math.round(Math.abs(targetY) + 60));

                                    const endX = Math.round(targetX + 10);
                                    const endY = Math.round(targetY > 0 ? targetY + 20 : Math.max(15, targetY + 30));
                                    const controlX = Math.round(targetX * 0.4);
                                    const controlY = Math.round(Math.min(startX, endY) - 30);

                                    const pathD = `M ${startX} ${startY} Q ${controlX} ${controlY} ${endX} ${endY}`;
                                    const polyPoints = `${endX},${endY} ${endX - 12},${endY - 6} ${endX - 8},${endY + 10}`;

                                    arrowSvg = `
                                        <div class="tutorial-arrow-wrapper arrow-fade-in" style="position:absolute; right:-${svgWidth - 10}px; top:-20px; width:${svgWidth}px; height:${svgHeight}px; pointer-events:none; z-index:2000001;">
                                            <svg class="tutorial-curved-arrow" viewBox="0 0 ${svgWidth} ${svgHeight}" style="width:100%; height:100%; overflow:visible;">
                                                <path d="${pathD}" stroke="#60a5fa" stroke-width="4" stroke-linecap="round" stroke-dasharray="8,8" fill="none" />
                                                <polygon points="${polyPoints}" fill="#60a5fa" />
                                            </svg>
                                        </div>
                                    `;
                                } else {
                                    const tCenter = { x: tRect.left + tRect.width / 2, y: tRect.top + tRect.height / 2 };
                                    const pCenter = { x: pRect.left + pRect.width / 2, y: pRect.top + pRect.height / 2 };
                                    const dx = pCenter.x - tCenter.x;
                                    const dy = pCenter.y - tCenter.y;
                                    if (step.id === 'units-row-click-deepdive' || step.id.startsWith('unit-details-')) {
                                        pos = 'left';
                                    } else if (Math.abs(dx) > Math.abs(dy)) {
                                        pos = dx > 0 ? 'right' : 'left';
                                    } else {
                                        pos = dy > 0 ? 'bottom' : 'top';
                                    }

                                    let pathD = "M 10 90 Q 30 10 90 20";
                                    let polyPoints = "90,20 80,15 85,28";
                                    
                                    if (pos === 'right') {
                                        pathD = "M 90 70 Q 50 10 10 30";
                                        polyPoints = "10,30 25,25 20,40";
                                    } else if (pos === 'left') {
                                        pathD = "M 10 70 Q 50 10 90 30";
                                        polyPoints = "90,30 75,25 80,40";
                                    } else if (pos === 'bottom') {
                                        pathD = "M 50 90 Q 20 50 50 10";
                                        polyPoints = "50,10 40,25 60,25";
                                    } else if (pos === 'top') {
                                        pathD = "M 50 10 Q 80 50 50 90";
                                        polyPoints = "50,90 40,75 60,75";
                                    }

                                    arrowSvg = `
                                        <div class="tutorial-arrow-wrapper arrow-pos-${pos} arrow-fade-in">
                                            <svg class="tutorial-curved-arrow" viewBox="0 0 100 100">
                                                <path d="${pathD}" stroke-dasharray="8,8" />
                                                <polygon points="${polyPoints}" fill="#60a5fa" />
                                            </svg>
                                        </div>
                                    `;
                                }

                                const existingArrow = wrapper.querySelector('.tutorial-arrow-wrapper');
                                if (existingArrow) existingArrow.remove();
                                wrapper.insertAdjacentHTML('afterbegin', arrowSvg);
                            }, 350);
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

                clearElevatedTarget();

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
                }, 200);
                targetElement.removeEventListener('click', clickHandler, true);
            };
            targetElement.addEventListener('click', clickHandler, true);

            logDebug("Calling driverObj.drive()");
            driverObj.drive();
        } catch (error) {
            logDebug("EXCEPTION CAUGHT: " + error.message);
            console.error(error);
        }
    }

    let _isStepTransitioning = false;

    function moveToNextStep(currentIndex) {
        logDebug(`Moving to next step after ${currentIndex}`);
        if (_isStepTransitioning) {
            logDebug("Blocked moveToNextStep due to active step transition lock.");
            return;
        }
        _isStepTransitioning = true;

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
            _isStepTransitioning = false;
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
                setTimeout(() => {
                    _isStepTransitioning = false;
                }, 200);
            }, 100);
        }
    }

    function moveToPrevStep(currentIndex) {
        logDebug(`Moving to prev step from ${currentIndex}`);
        if (currentIndex <= 0 || _isStepTransitioning) return;
        _isStepTransitioning = true;

        clearElevatedTarget();

        try {
            if (driverObj) {
                driverObj.destroy();
                driverObj = null;
            }
        } catch (e) {
            logDebug("Safe destroy prev catch: " + e.message);
        }

        // Clean up portal dropdowns and modals before starting previous step
        if (typeof window._tutorialUnportalDropdown === 'function') {
            window._tutorialUnportalDropdown();
        }
        
        const portalDiv = document.querySelector('.unit-action-dropdown--portal');
        if (portalDiv) portalDiv.remove();

        const prevStepObj = steps[currentIndex - 1];
        const prevStepId = prevStepObj ? prevStepObj.id : '';

        const addModal = document.getElementById('addUnitModal');
        if (addModal && !prevStepId.includes('add-modal')) addModal.classList.add('hidden');

        const editModal = document.getElementById('editUnitModal');
        if (editModal && !prevStepId.includes('edit-modal')) editModal.classList.add('hidden');

        const pdfModal = document.getElementById('tutorialPrintPdfModal');
        if (pdfModal && !prevStepId.includes('pdf-preview')) pdfModal.classList.add('hidden');

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
            startTutorial(prevIndex);
            setTimeout(() => {
                _isStepTransitioning = false;
            }, 200);
        }, 50);
    }

    function finishTutorial() {
        logDebug("finishTutorial called.");
        if (window._stepTabGlobalClick) {
            window.removeEventListener('click', window._stepTabGlobalClick, true);
            window._stepTabGlobalClick = null;
        }
        clearElevatedTarget();
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

// Global Keyboard Shortcut Navigation for Tutorial Steps (Spacebar / Enter / ArrowRight -> Next Step, ArrowLeft -> Previous Step)
if (!window._tutorialKeyboardListenerAttached) {
    window._tutorialKeyboardListenerAttached = true;
    window.addEventListener('keydown', (e) => {
        // Ignore key repeat when holding down keys (prevents rapid step skipping)
        if (e.repeat) return;

        // If Welcome Modal is active, Handle Enter/Space to click "Start Tour" cleanly
        const welcomeModal = document.getElementById('tutorial-welcome-modal');
        if (welcomeModal) {
            if (e.key === 'Enter' || e.key === ' ' || e.code === 'Space' || e.code === 'Enter') {
                e.preventDefault();
                e.stopPropagation();
                const startBtn = document.getElementById('tutorial-start-btn');
                if (startBtn) startBtn.click();
            }
            return;
        }

        const stepStr = localStorage.getItem('tutorial_current_step');
        if (stepStr === null || stepStr === '' || stepStr === undefined) return;
        const currentStepIndex = parseInt(stepStr, 10);
        if (isNaN(currentStepIndex) || currentStepIndex < 0) return;

        // Skip keyboard navigation if user is currently typing in an input, textarea, select, or contenteditable element
        const activeEl = document.activeElement;
        const activeTag = activeEl ? activeEl.tagName.toUpperCase() : '';
        const isEditable = activeEl ? (activeEl.isContentEditable || activeTag === 'INPUT' || activeTag === 'TEXTAREA' || activeTag === 'SELECT') : false;
        if (isEditable) return;

        // Space, Enter, ArrowRight, ArrowDown -> Move to Next Step
        if (e.code === 'Space' || e.code === 'Enter' || e.key === ' ' || e.key === 'Enter' || e.key === 'ArrowRight' || e.key === 'ArrowDown') {
            e.preventDefault();
            e.stopPropagation();
            if (window.TutorialManager) {
                window.TutorialManager.moveToNextStep(currentStepIndex);
            }
        } 
        // ArrowLeft, ArrowUp -> Move to Previous Step
        else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
            e.preventDefault();
            e.stopPropagation();
            if (window.TutorialManager) {
                window.TutorialManager.moveToPrevStep(currentStepIndex);
            }
        }
    }, true);
}

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
    clone.style.setProperty('pointer-events', 'auto', 'important');
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

