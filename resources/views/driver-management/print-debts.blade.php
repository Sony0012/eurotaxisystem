<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Liabilities & Driver Pending Debts Report &mdash; {{ date('Y-m-d') }}</title>
    <style>
        @page {
            margin: 10mm 12mm 15mm 12mm;
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
            background: #fff;
            font-family: 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
            padding: 8mm 12mm 15mm 12mm;
            color: #0f172a;
            font-size: 11px;
            line-height: 1.4;
        }

        /* Screen Action Bar */
        .no-print-bar {
            background: #0f172a;
            color: #fff;
            padding: 12px 20px;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .no-print-bar .btn-group {
            display: flex;
            gap: 10px;
        }
        .btn-print {
            background: #e11d48;
            color: #fff;
            border: none;
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: 800;
            font-size: 12px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: background 0.2s;
        }
        .btn-print:hover {
            background: #be123c;
        }
        .btn-back {
            background: #334155;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 12px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.2s;
        }
        .btn-back:hover {
            background: #475569;
        }

        /* Header Branding */
        .report-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 15px;
        }
        .report-header img {
            max-height: 55px;
            width: auto;
            display: block;
            margin: 0 auto 10px auto;
        }
        .company-name {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 2px;
        }
        h1 {
            font-size: 19px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .report-subtitle {
            font-size: 10px;
            color: #475569;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        /* Metadata Banner */
        .meta-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 14px;
            margin-bottom: 20px;
        }
        .meta-item {
            font-size: 10px;
        }
        .meta-item .label {
            font-weight: 800;
            text-transform: uppercase;
            color: #64748b;
            font-size: 8.5px;
            letter-spacing: 0.05em;
            margin-bottom: 2px;
        }
        .meta-item .value {
            font-weight: 900;
            color: #0f172a;
            font-size: 11px;
        }

        /* Executive KPI Summary Cards */
        .kpi-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 25px;
        }
        .kpi-card {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 12px;
            position: relative;
        }
        .kpi-card.danger {
            border-color: #fca5a5;
            background: #fff5f5;
        }
        .kpi-card.success {
            border-color: #86efac;
            background: #f0fdf4;
        }
        .kpi-card.primary {
            border-color: #cbd5e1;
            background: #f8fafc;
        }
        .kpi-label {
            font-size: 8.5px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            margin-bottom: 3px;
        }
        .kpi-card.danger .kpi-label { color: #dc2626; }
        .kpi-card.success .kpi-label { color: #16a34a; }
        .kpi-val {
            font-size: 17px;
            font-weight: 900;
            letter-spacing: -0.02em;
            color: #0f172a;
        }
        .kpi-card.danger .kpi-val { color: #b91c1c; }
        .kpi-card.success .kpi-val { color: #15803d; }

        /* Driver Section */
        .driver-block {
            margin-bottom: 22px;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            overflow: hidden;
            page-break-inside: avoid;
            break-inside: avoid;
        }
        .driver-header {
            background: #0f172a;
            color: #ffffff;
            padding: 8px 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .driver-title {
            font-size: 12px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .driver-plate-tag {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            padding: 2px 7px;
            border-radius: 5px;
            font-size: 9.5px;
            font-weight: 800;
            letter-spacing: 0.05em;
        }
        .driver-submeta {
            font-size: 9.5px;
            color: #cbd5e1;
            font-weight: 600;
        }
        .driver-balance-badge {
            background: #dc2626;
            color: #ffffff;
            padding: 3px 9px;
            border-radius: 6px;
            font-weight: 900;
            font-size: 11px;
            letter-spacing: 0.02em;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        thead tr {
            background: #f1f5f9;
            border-bottom: 1.5px solid #cbd5e1;
        }
        th {
            padding: 6px 8px;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 8.5px;
            letter-spacing: 0.05em;
            color: #334155;
            text-align: left;
        }
        th.center, td.center { text-align: center; }
        th.right, td.right { text-align: right; }
        
        tbody tr {
            border-bottom: 1px solid #e2e8f0;
        }
        tbody tr:nth-child(even) {
            background: #f8fafc;
        }
        td {
            padding: 6px 8px;
            vertical-align: middle;
            color: #1e293b;
        }
        
        .debt-desc {
            font-weight: 600;
            color: #0f172a;
        }
        .badge-type {
            display: inline-block;
            padding: 1.5px 5.5px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .badge-shortage { background: #ffe4e6; color: #9f1239; border: 1px solid #fecdd3; }
        .badge-damage   { background: #ffedd5; color: #9a3412; border: 1px solid #fed7aa; }
        .badge-parts    { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .badge-general  { background: #e2e8f0; color: #334155; border: 1px solid #cbd5e1; }

        .driver-subtotal-row {
            background: #f8fafc;
            border-top: 1.5px solid #cbd5e1;
            font-weight: 900;
            font-size: 10px;
        }
        .driver-subtotal-row td {
            padding: 7px 8px;
        }

        /* Grand Total Box */
        .grand-total-box {
            background: #0f172a;
            color: #ffffff;
            border-radius: 10px;
            padding: 14px 18px;
            margin: 25px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            page-break-inside: avoid;
        }
        .grand-total-title {
            font-size: 12px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #94a3b8;
        }
        .grand-total-numbers {
            display: flex;
            gap: 25px;
            text-align: right;
        }
        .grand-item .label {
            font-size: 8.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #94a3b8;
        }
        .grand-item .amount {
            font-size: 15px;
            font-weight: 900;
            letter-spacing: -0.01em;
        }
        .grand-item .amount.highlight {
            color: #f87171;
            font-size: 17px;
        }
        .grand-item .amount.success {
            color: #4ade80;
        }

        /* Signatories */
        .signatories-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            margin-top: 35px;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .signatory-card {
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            padding: 12px 14px;
            background: #ffffff;
        }
        .signatory-role {
            font-size: 8.5px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            margin-bottom: 30px;
        }
        .signatory-line {
            border-top: 1px solid #334155;
            padding-top: 5px;
            font-size: 10px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
        }
        .signatory-title {
            font-size: 8.5px;
            color: #64748b;
            font-weight: 600;
        }

        /* Footer */
        .report-footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 12px;
            border-top: 1px dashed #cbd5e1;
            font-size: 9px;
            color: #64748b;
        }

        /* Print Media Queries */
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .no-print-bar {
                display: none !important;
            }
            .driver-block {
                break-inside: avoid;
                page-break-inside: avoid;
            }
            .grand-total-box {
                break-inside: avoid;
            }
            .signatories-container {
                break-inside: avoid;
            }
        }
    </style>
</head>
<body @if(!request()->has('preview')) onload="window.print()" @endif>

    {{-- Screen Interactive Action Bar --}}
    <div class="no-print-bar no-print">
        <div style="display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 16px;">📄</span>
            <div>
                <div style="font-weight: 900; font-size: 13px; text-transform: uppercase; letter-spacing: 0.05em;">Financial Liabilities Print & PDF Export</div>
                <div style="font-size: 10px; color: #94a3b8;">Review report layout or click Print to save as PDF.</div>
            </div>
        </div>
        <div class="btn-group">
            <a href="{{ route('driver-management.debts') }}" class="btn-back">
                ← Back to Liabilities
            </a>
            <button type="button" onclick="window.print()" class="btn-print">
                🖨️ Print / Save as PDF
            </button>
        </div>
    </div>

    {{-- Report Header --}}
    <div class="report-header">
        <img src="{{ asset('image/logo.png') }}" alt="Euro Taxi Fleet Logo">
        <div class="company-name">Euro Taxi Management System &bull; Fleet Operations</div>
        <h1>Financial Liabilities &amp; Driver Pending Debts Report</h1>
        <div class="report-subtitle">Official Audit, Incident Damages &amp; Boundary Shortages Summary</div>
    </div>

    {{-- Metadata Grid --}}
    <div class="meta-grid">
        <div class="meta-item">
            <div class="label">Report Generated</div>
            <div class="value">{{ date('M d, Y &bull; h:i:s A') }}</div>
        </div>
        <div class="meta-item">
            <div class="label">Generated By</div>
            <div class="value">{{ auth()->user()->full_name ?? (auth()->user()->name ?? 'Authorized Auditor') }}</div>
        </div>
        <div class="meta-item">
            <div class="label">Scope / Status</div>
            <div class="value">All Active Pending Liabilities</div>
        </div>
        <div class="meta-item">
            <div class="label">Total Records</div>
            <div class="value">{{ $totalItems }} Outstanding Incident{{ $totalItems > 1 ? 's' : '' }}</div>
        </div>
    </div>

    {{-- Executive Summary KPI Cards --}}
    <div class="kpi-row">
        <div class="kpi-card primary">
            <div class="kpi-label">Active Debtors</div>
            <div class="kpi-val">{{ $totalDebtors }}</div>
        </div>
        <div class="kpi-card danger">
            <div class="kpi-label">Total Outstanding Balance</div>
            <div class="kpi-val">₱{{ number_format($grandTotalRemaining, 2) }}</div>
        </div>
        <div class="kpi-card success">
            <div class="kpi-label">Total Recovered / Collections</div>
            <div class="kpi-val">₱{{ number_format($totalCollections, 2) }}</div>
        </div>
        <div class="kpi-card primary">
            <div class="kpi-label">Pending Debt Items</div>
            <div class="kpi-val">{{ $totalItems }}</div>
        </div>
    </div>

    {{-- Itemized Breakdown Per Driver --}}
    @if(count($drivers) === 0)
        <div style="text-align: center; padding: 40px 20px; border: 2px dashed #cbd5e1; border-radius: 12px; margin: 30px 0;">
            <div style="font-size: 14px; font-weight: 800; color: #16a34a; text-transform: uppercase;">Zero Active Financial Liabilities</div>
            <p style="font-size: 11px; color: #64748b; margin-top: 4px;">All driver charges, accident damages, and shortages have been fully settled.</p>
        </div>
    @else
        @foreach($drivers as $driver)
            <div class="driver-block">
                {{-- Driver Header --}}
                <div class="driver-header">
                    <div class="driver-title">
                        <span>{{ $driver['driver_name'] }}</span>
                        <span class="driver-plate-tag">Unit: {{ $driver['unit_plate'] }}</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <span class="driver-submeta">Lic: {{ $driver['license_number'] }} &bull; Contact: {{ $driver['contact_number'] }}</span>
                        <span class="driver-balance-badge">Balance: ₱{{ number_format($driver['total_remaining'], 2) }}</span>
                    </div>
                </div>

                {{-- Table of Driver's Debt Items --}}
                <table>
                    <thead>
                        <tr>
                            <th style="width: 32px;" class="center">#</th>
                            <th style="width: 90px;">Date</th>
                            <th style="width: 120px;">Incident Type</th>
                            <th>Description / Incident Particulars</th>
                            <th style="width: 85px;" class="right">Total Charge</th>
                            <th style="width: 85px;" class="right">Amount Paid</th>
                            <th style="width: 95px;" class="right">Balance Due</th>
                            <th style="width: 60px;" class="center">Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($driver['debts'] as $idx => $debt)
                            @php
                                $typeLower = strtolower($debt->incident_type ?? '');
                                $descLower = strtolower($debt->description ?? '');
                                
                                if (str_contains($typeLower, 'boundary') || str_contains($descLower, 'boundary') || str_contains($typeLower, 'shortage')) {
                                    $badgeCls = 'badge-shortage';
                                    $typeLabel = 'Boundary Shortage';
                                } elseif (str_contains($typeLower, 'damage') || str_contains($descLower, 'damage') || str_contains($typeLower, 'accident') || str_contains($descLower, 'accident')) {
                                    $badgeCls = 'badge-damage';
                                    $typeLabel = 'Vehicle Damage';
                                } elseif (str_contains($typeLower, 'part') || str_contains($descLower, 'part') || str_contains($descLower, 'missing')) {
                                    $badgeCls = 'badge-parts';
                                    $typeLabel = 'Missing Parts';
                                } else {
                                    $badgeCls = 'badge-general';
                                    $typeLabel = !empty($debt->incident_type) ? $debt->incident_type : 'General Liability';
                                }

                                $chg = (float)$debt->total_charge;
                                $paid = (float)$debt->total_paid;
                                $pct = $chg > 0 ? min(100, round(($paid / $chg) * 100)) : 0;
                            @endphp
                            <tr>
                                <td class="center" style="font-weight: 700; color: #64748b;">{{ $idx + 1 }}</td>
                                <td style="font-weight: 700; white-space: nowrap;">
                                    {{ date('M d, Y', strtotime($debt->date ?: $debt->timestamp)) }}
                                </td>
                                <td>
                                    <span class="badge-type {{ $badgeCls }}">{{ $typeLabel }}</span>
                                </td>
                                <td>
                                    <div class="debt-desc">{{ $debt->description }}</div>
                                </td>
                                <td class="right" style="font-weight: 700;">₱{{ number_format($debt->total_charge, 2) }}</td>
                                <td class="right" style="font-weight: 700; color: #16a34a;">₱{{ number_format($debt->total_paid, 2) }}</td>
                                <td class="right" style="font-weight: 900; color: #dc2626;">₱{{ number_format($debt->remaining_balance, 2) }}</td>
                                <td class="center" style="font-weight: 800; font-size: 9px; color: {{ $pct > 0 ? '#16a34a' : '#64748b' }};">
                                    {{ $pct }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="driver-subtotal-row">
                            <td colspan="4" style="text-align: right; text-transform: uppercase; letter-spacing: 0.05em; color: #475569;">
                                Subtotal for {{ $driver['driver_name'] }} ({{ count($driver['debts']) }} item{{ count($driver['debts']) > 1 ? 's' : '' }}):
                            </td>
                            <td class="right">₱{{ number_format($driver['subtotal_charge'], 2) }}</td>
                            <td class="right" style="color: #16a34a;">₱{{ number_format($driver['subtotal_paid'], 2) }}</td>
                            <td class="right" style="color: #dc2626; font-size: 11px;">₱{{ number_format($driver['total_remaining'], 2) }}</td>
                            <td class="center">&mdash;</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endforeach
    @endif

    {{-- Grand Totals Summary Box --}}
    <div class="grand-total-box">
        <div>
            <div class="grand-total-title">Consolidated Liabilities Grand Total</div>
            <div style="font-size: 10px; color: #cbd5e1; margin-top: 2px;">
                Covering {{ $totalDebtors }} active driver debtors across {{ $totalItems }} outstanding liability records.
            </div>
        </div>
        <div class="grand-total-numbers">
            <div class="grand-item">
                <div class="label">Total Incurred Charges</div>
                <div class="amount">₱{{ number_format($grandTotalCharge, 2) }}</div>
            </div>
            <div class="grand-item">
                <div class="label">Total Amount Paid</div>
                <div class="amount success">₱{{ number_format($grandTotalPaid, 2) }}</div>
            </div>
            <div class="grand-item">
                <div class="label">Net Outstanding Balance</div>
                <div class="amount highlight">₱{{ number_format($grandTotalRemaining, 2) }}</div>
            </div>
        </div>
    </div>

    {{-- Signatories & Authorization --}}
    <div class="signatories-container">
        <div class="signatory-card">
            <div class="signatory-role">Prepared &amp; Verified By:</div>
            <div class="signatory-line">{{ auth()->user()->full_name ?? (auth()->user()->name ?? 'Fleet Cashier / Staff') }}</div>
            <div class="signatory-title">Cashier / Billing Operations</div>
        </div>
        <div class="signatory-card">
            <div class="signatory-role">Audited &amp; Reviewed By:</div>
            <div class="signatory-line">Accounting Department</div>
            <div class="signatory-title">Finance &amp; Audit Officer</div>
        </div>
        <div class="signatory-card">
            <div class="signatory-role">Approved By:</div>
            <div class="signatory-line">Fleet Management</div>
            <div class="signatory-title">General Operations Manager</div>
        </div>
    </div>

    {{-- Report Footer --}}
    <div class="report-footer">
        <p>Euro Taxi Fleet Management System &bull; Official Financial Liability &amp; Debts Summary Report &bull; Generated: {{ date('m/d/Y, h:i:s A') }}</p>
    </div>

</body>
</html>
