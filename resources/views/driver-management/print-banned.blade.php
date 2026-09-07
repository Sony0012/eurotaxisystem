<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Banned & Suspended Drivers Roster &mdash; {{ date('Y-m-d') }}</title>
    <style>
        @page {
            margin: 8mm 10mm 10mm 10mm;
            size: portrait;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        body {
            background: #ffffff;
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Arial, sans-serif;
            padding: 6mm 10mm;
            color: #0f172a;
            font-size: 10px;
            line-height: 1.35;
        }

        /* Screen Toolbar (hidden in print) */
        .no-print-bar {
            background: #0f172a;
            color: #ffffff;
            padding: 10px 18px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.18);
        }
        .btn-print {
            background: #dc2626;
            color: #ffffff;
            border: none;
            padding: 8px 18px;
            border-radius: 6px;
            font-weight: 800;
            font-size: 11px;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-print:hover { background: #b91c1c; }
        .btn-back {
            background: #334155;
            color: #ffffff;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 11px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-back:hover { background: #1e293b; }

        /* Company Header */
        .company-header {
            text-align: center;
            border-bottom: 2.5px solid #0f172a;
            padding-bottom: 10px;
            margin-bottom: 12px;
            position: relative;
        }
        .company-name {
            font-size: 16px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #0f172a;
        }
        .company-sub {
            font-size: 8.5px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #64748b;
            margin-top: 1px;
        }
        .doc-title {
            font-size: 14px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #b91c1c;
            margin-top: 4px;
        }
        .doc-subtitle {
            font-size: 9px;
            color: #475569;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        /* Summary Meta Table */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 9.5px;
            border: 1px solid #cbd5e1;
        }
        .summary-table td {
            padding: 5px 8px;
            border: 1px solid #cbd5e1;
            background: #f8fafc;
        }
        .summary-table .lbl {
            font-weight: 800;
            text-transform: uppercase;
            color: #475569;
            font-size: 8.5px;
            width: 18%;
        }
        .summary-table .val {
            font-weight: 900;
            color: #0f172a;
            width: 32%;
        }
        .summary-table .val.danger { color: #dc2626; font-size: 10.5px; }
        .summary-table .val.warning { color: #d97706; font-size: 10.5px; }
        .summary-table .val.success { color: #16a34a; font-size: 10.5px; }

        /* Policy Notice Box */
        .policy-box {
            background: #fffbeb;
            border: 1px solid #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 6px 10px;
            margin-bottom: 12px;
            border-radius: 4px;
            font-size: 8.5px;
            color: #92400e;
        }
        .policy-box strong { font-weight: 800; color: #78350f; }

        /* Driver Block */
        .driver-card {
            border: 1.5px solid #334155;
            border-radius: 6px;
            margin-bottom: 12px;
            page-break-inside: avoid;
            break-inside: avoid;
            overflow: hidden;
            background: #ffffff;
        }
        .driver-info-bar {
            background: #0f172a;
            color: #ffffff;
            padding: 6px 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 9.5px;
        }
        .driver-name-tag {
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .driver-status-badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 4px;
            font-weight: 900;
            font-size: 8.5px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .badge-banned {
            background: #dc2626;
            color: #ffffff;
        }
        .badge-suspended {
            background: #d97706;
            color: #ffffff;
        }

        .driver-body {
            padding: 8px 10px;
        }

        .driver-details-grid {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            margin-bottom: 6px;
        }
        .driver-details-grid td {
            padding: 3px 6px;
            vertical-align: top;
        }
        .driver-details-grid .d-lbl {
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            font-size: 8px;
            width: 18%;
        }
        .driver-details-grid .d-val {
            font-weight: 700;
            color: #1e293b;
            width: 32%;
        }

        /* Reason Box */
        .reason-box {
            background: #fef2f2;
            border: 1px solid #fee2e2;
            border-left: 3px solid #ef4444;
            padding: 5px 8px;
            border-radius: 4px;
            font-size: 8.5px;
            color: #991b1b;
            margin: 4px 0 6px 0;
        }
        .reason-box.suspended-reason {
            background: #fffbeb;
            border-color: #fef3c7;
            border-left-color: #f59e0b;
            color: #92400e;
        }
        .reason-box strong {
            font-weight: 900;
            text-transform: uppercase;
            font-size: 8px;
            letter-spacing: 0.04em;
        }

        /* Incidents Mini-Table */
        .incident-header {
            font-size: 8.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #475569;
            margin: 6px 0 3px 0;
            padding-bottom: 2px;
            border-bottom: 1px dashed #cbd5e1;
            display: flex;
            justify-content: space-between;
        }
        table.incident-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5px;
            margin-bottom: 4px;
        }
        table.incident-table th {
            background: #f1f5f9;
            color: #334155;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 7.5px;
            letter-spacing: 0.04em;
            padding: 3px 5px;
            border-bottom: 1px solid #cbd5e1;
            text-align: left;
        }
        table.incident-table td {
            padding: 3px 5px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
            color: #334155;
        }
        table.incident-table tr:nth-child(even) td {
            background: #f8fafc;
        }
        .severity-badge {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: 900;
            text-transform: uppercase;
            background: #fee2e2;
            color: #b91c1c;
        }
        .severity-badge.medium {
            background: #fef3c7;
            color: #92400e;
        }

        /* Directives Box */
        .directives-box {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 8px 12px;
            margin: 14px 0;
            font-size: 8.5px;
            color: #334155;
            line-height: 1.45;
            page-break-inside: avoid;
        }
        .directives-box h5 {
            font-size: 9.5px;
            font-weight: 900;
            text-transform: uppercase;
            color: #0f172a;
            margin-bottom: 3px;
            letter-spacing: 0.04em;
        }

        /* Signatures block */
        .sig-section {
            margin-top: 24px;
            page-break-inside: avoid;
            break-inside: avoid;
        }
        .sig-grid {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }
        .sig-box {
            flex: 1;
            text-align: center;
        }
        .sig-line {
            border-bottom: 1.5px solid #0f172a;
            height: 38px;
            margin-bottom: 4px;
        }
        .sig-name {
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            color: #0f172a;
        }
        .sig-title {
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.05em;
        }

        /* Print Media Styles */
        @media print {
            .no-print-bar { display: none !important; }
            body { padding: 0; background: #fff; }
            .driver-card { break-inside: avoid; }
        }
    </style>
</head>
<body>

    {{-- Screen Toolbar --}}
    <div class="no-print-bar">
        <div>
            <span style="font-weight:900;font-size:13px;letter-spacing:0.04em;text-transform:uppercase;">Banned Drivers & Lockouts Official Audit Report</span>
            <span style="color:#94a3b8;font-size:11px;margin-left:12px;">Total Lockouts: {{ $totalLockouts }}</span>
        </div>
        <div style="display:flex;gap:10px;">
            <button class="btn-print" onclick="window.print()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Print / Save PDF
            </button>
            <a href="{{ route('driver-management.banned') }}" class="btn-back">
                &larr; Back to Roster
            </a>
        </div>
    </div>

    {{-- Company Header --}}
    <div class="company-header">
        <div class="company-name">EURO TAXI TRANSPORT SERVICES</div>
        <div class="company-sub">Fleet Operations & Safety Compliance Division &bull; Administrative Disciplinary Audit</div>
        <div class="doc-title">OFFICIAL BANNED & SUSPENDED DRIVERS ROSTER</div>
        <div class="doc-subtitle">CONFIDENTIAL FLEET LOCK-OUT & DRIVER STATUS VERIFICATION AUDIT</div>
    </div>

    {{-- Summary Executive KPI Table --}}
    <table class="summary-table">
        <tr>
            <td class="lbl">Report Audit Date:</td>
            <td class="val">{{ $generatedAt }}</td>
            <td class="lbl">Total Lockouts:</td>
            <td class="val danger">{{ $totalLockouts }} DRIVER(S)</td>
        </tr>
        <tr>
            <td class="lbl">Audited / Generated By:</td>
            <td class="val">{{ $generatedBy }}</td>
            <td class="lbl">Permanent Bans:</td>
            <td class="val danger">{{ $bannedCount }} DRIVER(S)</td>
        </tr>
        <tr>
            <td class="lbl">Policy Enforcement:</td>
            <td class="val success">
                {{ ($autoBanSettings['auto_ban_enabled'] ?? '1') == '1' ? 'ACTIVE & ENFORCING' : 'DISABLED' }}
                (Overdue Threshold: {{ $autoBanSettings['auto_ban_overdue_unit_days'] ?? 3 }} Days)
            </td>
            <td class="lbl">Temporary Suspensions:</td>
            <td class="val warning">{{ $suspendedCount }} DRIVER(S)</td>
        </tr>
        <tr>
            <td class="lbl">Document Ref Code:</td>
            <td class="val">ETS-BAN-{{ date('Ymd-His') }}</td>
            <td class="lbl">Outstanding Liabilities:</td>
            <td class="val danger">₱{{ number_format($totalOutstandingLiabilities, 2) }}</td>
        </tr>
    </table>

    {{-- Policy Summary Info --}}
    <div class="policy-box">
        <strong>AUTOMATED LOCKOUT POLICY STATUS:</strong>
        Drivers failing to remit daily boundary or return company units beyond <strong>{{ $autoBanSettings['auto_ban_overdue_unit_days'] ?? 3 }} consecutive overdue days</strong> are automatically placed on <strong>{{ strtoupper($autoBanSettings['auto_ban_action_type'] ?? 'BANNED') }}</strong> status. All listed individuals are strictly barred from receiving taxi dispatch keys or operating any fleet asset.
    </div>

    {{-- Drivers List --}}
    @if(count($drivers) === 0)
        <div style="text-align:center;padding:30px 10px;border:1px dashed #94a3b8;border-radius:8px;margin:20px 0;background:#f8fafc;">
            <p style="font-weight:800;font-size:12px;color:#1e293b;text-transform:uppercase;">No Drivers Currently Banned or Suspended</p>
            <p style="font-size:10px;color:#64748b;margin-top:4px;">All active fleet drivers are in good standing with zero active administrative lock-outs.</p>
        </div>
    @else
        @foreach($drivers as $idx => $driver)
            @php
                $isBanned = ($driver->driver_status === 'banned');
                $regKey = 'DRV-' . str_pad($driver->id, 4, '0', STR_PAD_LEFT);
                $lockoutDate = $driver->updated_at ? \Carbon\Carbon::parse($driver->updated_at)->timezone('Asia/Manila')->format('M d, Y h:i A') : 'N/A';
            @endphp
            <div class="driver-card">
                {{-- Header Bar --}}
                <div class="driver-info-bar">
                    <div class="driver-name-tag">
                        <span>#{{ $idx + 1 }} &mdash; {{ strtoupper($driver->full_name) }}</span>
                        <span style="font-size:8.5px;color:#cbd5e1;font-weight:700;">({{ $regKey }})</span>
                        @if($driver->assigned_unit)
                            <span style="font-size:8px;background:#334155;color:#f8fafc;padding:1px 5px;border-radius:3px;font-weight:800;">
                                UNIT: {{ $driver->assigned_unit }}
                            </span>
                        @endif
                    </div>
                    <div>
                        @if($isBanned)
                            <span class="driver-status-badge badge-banned">⛔ PERMANENT BANNED</span>
                        @else
                            <span class="driver-status-badge badge-suspended">
                                ⚠️ SUSPENDED 
                                @if($driver->suspended_until)
                                    (Until {{ \Carbon\Carbon::parse($driver->suspended_until)->format('M d, Y') }}{{ isset($driver->days_left) && $driver->days_left > 0 ? ' • ' . $driver->days_left . 'd left' : '' }})
                                @endif
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Card Content --}}
                <div class="driver-body">
                    <table class="driver-details-grid">
                        <tr>
                            <td class="d-lbl">Driver License No:</td>
                            <td class="d-val">{{ $driver->license_number ?: '---' }}</td>
                            <td class="d-lbl">Contact Number:</td>
                            <td class="d-val">{{ $driver->contact_number ?: '---' }}</td>
                        </tr>
                        <tr>
                            <td class="d-lbl">Lockout Effective Date:</td>
                            <td class="d-val">{{ $lockoutDate }}</td>
                            <td class="d-lbl">Enforcing Registrar:</td>
                            <td class="d-val">{{ $driver->creator_name ?: 'SYSTEM AUTOMATION' }}</td>
                        </tr>
                        <tr>
                            <td class="d-lbl">Outstanding Balance:</td>
                            <td class="d-val" style="color:#dc2626;font-weight:900;">
                                {{ $driver->total_unpaid > 0 ? '₱' . number_format($driver->total_unpaid, 2) : 'CLEARED / ₱0.00' }}
                            </td>
                            <td class="d-lbl">Registered Address:</td>
                            <td class="d-val">{{ $driver->address ?: 'N/A' }}</td>
                        </tr>
                    </table>

                    {{-- Lockout Reason --}}
                    <div class="reason-box {{ !$isBanned ? 'suspended-reason' : '' }}">
                        <strong>PRIMARY LOCKOUT REASON / CASE NARRATIVE:</strong><br>
                        "{{ $driver->suspension_reason ?: ($isBanned ? 'Driver placed on administrative permanent ban due to critical company violation or overdue boundary defaults.' : 'Driver temporarily suspended under administrative review.') }}"
                    </div>

                    {{-- Incident Triggers Table (if any) --}}
                    @if(isset($driver->incidents) && count($driver->incidents) > 0)
                        <div class="incident-header">
                            <span>RECORDED INCIDENTS &amp; VIOLATION TRIGGERS ({{ count($driver->incidents) }} ITEM/S)</span>
                            <span>TOTAL CHARGE: ₱{{ number_format($driver->incidents->sum('total_charge_to_driver'), 2) }}</span>
                        </div>
                        <table class="incident-table">
                            <thead>
                                <tr>
                                    <th style="width:12%;">Date</th>
                                    <th style="width:20%;">Incident Type</th>
                                    <th style="width:12%;">Severity</th>
                                    <th style="width:38%;">Particulars / Description</th>
                                    <th style="width:18%;text-align:right;">Charge / Unpaid</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($driver->incidents->take(5) as $inc)
                                    @php
                                        $incDate = $inc->incident_date ? \Carbon\Carbon::parse($inc->incident_date)->format('M d, Y') : '---';
                                        $severity = strtoupper($inc->severity ?: 'HIGH');
                                    @endphp
                                    <tr>
                                        <td>{{ $incDate }}</td>
                                        <td style="font-weight:800;">{{ $inc->incident_type ?: 'General Violation' }}</td>
                                        <td>
                                            <span class="severity-badge {{ in_array($severity, ['LOW','MEDIUM']) ? 'medium' : '' }}">{{ $severity }}</span>
                                        </td>
                                        <td>{{ Str::limit($inc->description ?: 'No details recorded', 75) }}</td>
                                        <td style="text-align:right;font-weight:800;color:{{ $inc->remaining_balance > 0 ? '#dc2626' : '#16a34a' }};">
                                            ₱{{ number_format($inc->total_charge_to_driver, 2) }}
                                            @if($inc->remaining_balance > 0)
                                                <div style="font-size:7px;color:#dc2626;">(₱{{ number_format($inc->remaining_balance, 2) }} bal)</div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if(count($driver->incidents) > 5)
                            <div style="font-size:7.5px;color:#64748b;font-style:italic;text-align:right;">
                                + {{ count($driver->incidents) - 5 }} additional incident records archived in system database.
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        @endforeach
    @endif

    {{-- Official Fleet Notice / Directive --}}
    <div class="directives-box">
        <h5>FLEET SECURITY &amp; DISPATCH DIRECTIVE</h5>
        All dispatchers, garage supervisors, and cashiers are strictly instructed to enforce the lock-out status of the drivers listed above. No company vehicle, shift assignment, or security pass shall be issued to these individuals without an authentic, signed <strong>Administrative Clearance Certificate</strong> issued by the Fleet Operations Management.
    </div>

    {{-- Formal Signatures Block --}}
    <div class="sig-section">
        <div class="sig-grid">
            <div class="sig-box">
                <div class="sig-line"></div>
                <div class="sig-name">{{ $generatedBy }}</div>
                <div class="sig-title">Prepared By / Auditor</div>
            </div>
            <div class="sig-box">
                <div class="sig-line"></div>
                <div class="sig-name">FLEET OPERATIONS MANAGER</div>
                <div class="sig-title">Reviewed &amp; Verified By</div>
            </div>
            <div class="sig-box">
                <div class="sig-line"></div>
                <div class="sig-name">EXECUTIVE DIRECTOR / GM</div>
                <div class="sig-title">Approved &amp; Enforced By</div>
            </div>
        </div>
    </div>

</body>
</html>