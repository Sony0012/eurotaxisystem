<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Liabilities & Settlement Report &mdash; {{ date('Y-m-d') }}</title>
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
            color: #111827;
            font-size: 10px;
            line-height: 1.35;
        }

        /* Screen Toolbar (hidden in print) */
        .no-print-bar {
            background: #1e293b;
            color: #fff;
            padding: 10px 18px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.12);
        }
        .btn-print {
            background: #dc2626;
            color: #fff;
            border: none;
            padding: 7px 16px;
            border-radius: 6px;
            font-weight: 800;
            font-size: 11px;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .btn-print:hover { background: #b91c1c; }
        .btn-back {
            background: #475569;
            color: #fff;
            padding: 7px 14px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 11px;
            text-decoration: none;
        }
        .btn-back:hover { background: #334155; }

        /* Company Header */
        .company-header {
            text-align: center;
            border-bottom: 2px solid #111827;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }
        .company-logo {
            max-height: 48px;
            width: auto;
            display: block;
            margin: 0 auto 6px auto;
        }
        .company-sub {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #4b5563;
        }
        .doc-title {
            font-size: 16px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #111827;
            margin: 2px 0;
        }
        .doc-subtitle {
            font-size: 9.5px;
            color: #4b5563;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        /* Summary Meta Table */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 9.5px;
            border: 1px solid #d1d5db;
        }
        .summary-table td {
            padding: 5px 8px;
            border: 1px solid #d1d5db;
            background: #f9fafb;
        }
        .summary-table .lbl {
            font-weight: 800;
            text-transform: uppercase;
            color: #4b5563;
            font-size: 8.5px;
            width: 18%;
        }
        .summary-table .val {
            font-weight: 900;
            color: #111827;
            width: 32%;
        }
        .summary-table .val.danger { color: #dc2626; font-size: 11px; }
        .summary-table .val.success { color: #16a34a; font-size: 11px; }

        /* Driver Block */
        .driver-card {
            border: 1.5px solid #374151;
            border-radius: 6px;
            margin-bottom: 14px;
            page-break-inside: avoid;
            break-inside: avoid;
            overflow: hidden;
        }
        .driver-info-bar {
            background: #1f2937;
            color: #ffffff;
            padding: 6px 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 10px;
        }
        .driver-name-tag {
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .driver-meta-text {
            font-size: 9px;
            color: #e5e7eb;
            font-weight: 600;
        }
        .driver-balance-box {
            background: #dc2626;
            color: #fff;
            padding: 2px 7px;
            border-radius: 4px;
            font-weight: 900;
            font-size: 10px;
        }

        /* Section Head inside Driver */
        .section-subhead {
            background: #f3f4f6;
            padding: 4px 10px;
            font-weight: 800;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
        }

        /* Tables */
        table.debt-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }
        table.debt-table th {
            background: #ffffff;
            color: #374151;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 8px;
            letter-spacing: 0.04em;
            padding: 4px 6px;
            border-bottom: 1px solid #9ca3af;
            text-align: left;
        }
        table.debt-table td {
            padding: 4px 6px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
            color: #1f2937;
        }
        table.debt-table tr:nth-child(even) td {
            background: #f9fafb;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .type-badge {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 3px;
            font-size: 7.5px;
            font-weight: 800;
            text-transform: uppercase;
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            color: #374151;
        }

        .driver-subtotal-bar {
            background: #f9fafb;
            border-top: 1.5px solid #9ca3af;
            padding: 5px 8px;
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            font-size: 9.5px;
            font-weight: 800;
        }
        .driver-subtotal-bar .amt {
            color: #dc2626;
            font-weight: 900;
        }

        /* Grand Total Box */
        .grand-box {
            background: #111827;
            color: #ffffff;
            border-radius: 6px;
            padding: 10px 14px;
            margin: 16px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            page-break-inside: avoid;
        }
        .grand-title {
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .grand-stats {
            display: flex;
            gap: 20px;
            text-align: right;
            font-size: 10px;
        }
        .grand-stat-item .g-lbl {
            font-size: 8px;
            text-transform: uppercase;
            font-weight: 700;
            color: #9ca3af;
        }
        .grand-stat-item .g-val {
            font-size: 13px;
            font-weight: 900;
        }
        .grand-stat-item .g-val.danger { color: #f87171; }
        .grand-stat-item .g-val.success { color: #4ade80; }

        /* Signatories */
        .signatories {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 25px;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .sig-box {
            border-top: 1px solid #374151;
            padding-top: 4px;
            font-size: 9px;
        }
        .sig-role {
            font-weight: 800;
            text-transform: uppercase;
            font-size: 7.5px;
            color: #6b7280;
            margin-bottom: 22px;
        }
        .sig-name {
            font-weight: 800;
            text-transform: uppercase;
            color: #111827;
        }
        .sig-title {
            font-size: 8px;
            color: #4b5563;
        }

        /* Footer */
        .report-footer {
            text-align: center;
            margin-top: 15px;
            padding-top: 8px;
            border-top: 1px dashed #d1d5db;
            font-size: 8px;
            color: #6b7280;
        }

        /* Print Media Styles */
        @media print {
            body { padding: 0; }
            .no-print, .no-print-bar { display: none !important; }
            .driver-card, .grand-box, .signatories {
                break-inside: avoid;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body @if(!request()->has('preview')) onload="window.print()" @endif>

    {{-- Screen Interactive Action Bar (Hidden in Print) --}}
    <div class="no-print-bar no-print">
        <div style="display: flex; align-items: center; gap: 8px;">
            <span style="font-size: 15px;">📄</span>
            <div>
                <strong style="text-transform: uppercase; letter-spacing: 0.05em;">Financial Liabilities &amp; Settlement Statement</strong>
                <span style="font-size: 9.5px; color: #94a3b8; margin-left: 8px;">Official Company Fleet Document</span>
            </div>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('driver-management.debts') }}" class="btn-back">
                ← Back to Dashboard
            </a>
            <button type="button" onclick="window.print()" class="btn-print">
                🖨️ Print / Save as PDF
            </button>
        </div>
    </div>

    {{-- Company Header --}}
    <div class="company-header">
        <img src="{{ asset('image/logo.png') }}" alt="Euro Taxi Fleet Logo" class="company-logo">
        <div class="company-sub">Euro Taxi Management System &bull; Fleet Operations</div>
        <h1 class="doc-title">Driver Financial Liabilities &amp; Settlement Statement</h1>
        <div class="doc-subtitle">Official Statement of Outstanding Balances, Deductions &amp; Settlement Records</div>
    </div>

    {{-- Summary Metadata Table --}}
    <table class="summary-table">
        <tr>
            <td class="lbl">Date Generated:</td>
            <td class="val">{{ date('F d, Y') }} &bull; {{ date('h:i A') }}</td>
            <td class="lbl">Total Active Debtors:</td>
            <td class="val">{{ $totalActiveDebtors }} Driver{{ $totalActiveDebtors > 1 ? 's' : '' }}</td>
        </tr>
        <tr>
            <td class="lbl">Generated By:</td>
            <td class="val">{{ auth()->user()->full_name ?? (auth()->user()->name ?? 'Fleet Billing Auditor') }}</td>
            <td class="lbl">Net Outstanding Balance:</td>
            <td class="val danger">₱{{ number_format($grandTotalPending, 2) }}</td>
        </tr>
        <tr>
            <td class="lbl">Report Scope:</td>
            <td class="val">All Driver Liabilities &amp; Settlement History</td>
            <td class="lbl">Total Collections Recovered:</td>
            <td class="val success">₱{{ number_format($totalCollections, 2) }}</td>
        </tr>
    </table>

    {{-- Per-Driver Breakdown Section --}}
    @if(count($drivers) === 0)
        <div style="text-align: center; padding: 30px; border: 1.5px dashed #9ca3af; border-radius: 6px; margin: 20px 0;">
            <strong style="color: #16a34a; text-transform: uppercase;">Zero Active Financial Liabilities</strong>
            <p style="color: #6b7280; font-size: 9px; margin-top: 4px;">All driver boundary charges, damage repairs, and parts shortages have been fully settled.</p>
        </div>
    @else
        @foreach($drivers as $driver)
            <div class="driver-card">
                {{-- Driver Header Bar --}}
                <div class="driver-info-bar">
                    <div>
                        <span class="driver-name-tag">{{ $driver['driver_name'] }}</span>
                        <span style="margin-left: 8px; background: rgba(255,255,255,0.15); padding: 1px 6px; border-radius: 3px; font-weight: 800; font-size: 8.5px;">
                            Unit: {{ $driver['unit_plate'] }}
                        </span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <span class="driver-meta-text">Lic: {{ $driver['license_number'] }} &bull; Phone: {{ $driver['contact_number'] }}</span>
                        <span class="driver-balance-box">
                            Pending Due: ₱{{ number_format($driver['total_pending'], 2) }}
                        </span>
                    </div>
                </div>

                {{-- 1. Pending Liabilities Table --}}
                @if(count($driver['pending_debts']) > 0)
                    <div class="section-subhead">
                        <span>Active / Outstanding Liabilities ({{ count($driver['pending_debts']) }} item{{ count($driver['pending_debts']) > 1 ? 's' : '' }})</span>
                        <span style="color: #dc2626; font-weight: 900;">Subtotal Due: ₱{{ number_format($driver['total_pending'], 2) }}</span>
                    </div>
                    <table class="debt-table">
                        <thead>
                            <tr>
                                <th style="width: 25px;" class="text-center">#</th>
                                <th style="width: 75px;">Date Incurred</th>
                                <th style="width: 100px;">Liability Type</th>
                                <th>Description / Incident Particulars</th>
                                <th style="width: 75px;" class="text-right">Total Charge</th>
                                <th style="width: 70px;" class="text-right">Paid</th>
                                <th style="width: 80px;" class="text-right">Balance Due</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($driver['pending_debts'] as $idx => $debt)
                                <tr>
                                    <td class="text-center" style="font-weight: 700; color: #6b7280;">{{ $idx + 1 }}</td>
                                    <td style="font-weight: 700; white-space: nowrap;">
                                        {{ date('M d, Y', strtotime($debt->date)) }}
                                    </td>
                                    <td>
                                        <span class="type-badge">{{ $debt->incident_type ?: 'General Debt' }}</span>
                                    </td>
                                    <td style="font-weight: 600;">{{ $debt->description }}</td>
                                    <td class="text-right">₱{{ number_format($debt->total_charge, 2) }}</td>
                                    <td class="text-right" style="color: #16a34a;">₱{{ number_format($debt->total_paid, 2) }}</td>
                                    <td class="text-right" style="font-weight: 900; color: #dc2626;">₱{{ number_format($debt->remaining_balance, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="section-subhead">
                        <span>Active / Outstanding Liabilities</span>
                        <span style="color: #16a34a; font-weight: 800;">✓ All active charges cleared</span>
                    </div>
                @endif

                {{-- 2. Settlement & Payment History --}}
                @if(count($driver['settled_debts']) > 0 || count($driver['expense_payments']) > 0)
                    <div class="section-subhead" style="background: #ecfdf5; color: #065f46; border-top: 1px solid #d1fae5;">
                        <span>Settlement &amp; Payment History</span>
                        <span style="color: #059669; font-weight: 800;">Total Settled / Paid: ₱{{ number_format($driver['total_paid'], 2) }}</span>
                    </div>
                    <table class="debt-table">
                        <thead>
                            <tr>
                                <th style="width: 75px;">Settled Date</th>
                                <th style="width: 100px;">Record Type</th>
                                <th>Particulars / Description</th>
                                <th style="width: 80px;" class="text-right">Amount Settled</th>
                                <th style="width: 75px;" class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($driver['settled_debts'] as $sDebt)
                                <tr>
                                    <td style="font-weight: 700; white-space: nowrap;">
                                        {{ date('M d, Y', strtotime($sDebt->settled_at ?: $sDebt->date)) }}
                                    </td>
                                    <td>
                                        <span class="type-badge" style="background: #dcfce7; color: #166534; border-color: #bbf7d0;">
                                            {{ $sDebt->incident_type ?: 'Settled Charge' }}
                                        </span>
                                    </td>
                                    <td style="color: #4b5563;">{{ $sDebt->description }}</td>
                                    <td class="text-right" style="font-weight: 800; color: #15803d;">₱{{ number_format($sDebt->total_paid ?: $sDebt->total_charge, 2) }}</td>
                                    <td class="text-center">
                                        <span style="font-size: 7.5px; font-weight: 900; color: #15803d; text-transform: uppercase;">
                                            Fully Settled
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                            @foreach($driver['expense_payments'] as $pay)
                                <tr>
                                    <td style="font-weight: 700; white-space: nowrap;">
                                        {{ date('M d, Y', strtotime($pay->date)) }}
                                    </td>
                                    <td>
                                        <span class="type-badge" style="background: #e0f2fe; color: #0369a1; border-color: #bae6fd;">
                                            Direct Cash-In
                                        </span>
                                    </td>
                                    <td style="color: #4b5563;">{{ $pay->description }}</td>
                                    <td class="text-right" style="font-weight: 800; color: #0369a1;">₱{{ number_format($pay->amount, 2) }}</td>
                                    <td class="text-center">
                                        <span style="font-size: 7.5px; font-weight: 900; color: #0369a1; text-transform: uppercase;">
                                            Recorded Cash
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Driver Subtotal Footer Bar --}}
                <div class="driver-subtotal-bar">
                    <span>Total Incurred: <strong>₱{{ number_format($driver['total_charge'], 2) }}</strong></span>
                    <span>Total Settled: <strong style="color: #16a34a;">₱{{ number_format($driver['total_paid'], 2) }}</strong></span>
                    <span>Net Outstanding: <strong class="amt">₱{{ number_format($driver['total_pending'], 2) }}</strong></span>
                </div>
            </div>
        @endforeach
    @endif

    {{-- Grand Consolidated Totals --}}
    <div class="grand-box">
        <div>
            <div class="grand-title">Consolidated Fleet Financial Totals</div>
            <div style="font-size: 8.5px; color: #9ca3af; margin-top: 2px;">
                Covering {{ count($drivers) }} driver records with {{ $totalActiveDebtors }} active debtor accounts.
            </div>
        </div>
        <div class="grand-stats">
            <div class="grand-stat-item">
                <div class="g-lbl">Total Charges Incurred</div>
                <div class="g-val">₱{{ number_format($grandTotalCharge, 2) }}</div>
            </div>
            <div class="grand-stat-item">
                <div class="g-lbl">Total Recovered Payments</div>
                <div class="g-val success">₱{{ number_format($grandTotalPaid, 2) }}</div>
            </div>
            <div class="grand-stat-item">
                <div class="g-lbl">Net Outstanding Balance</div>
                <div class="g-val danger">₱{{ number_format($grandTotalPending, 2) }}</div>
            </div>
        </div>
    </div>

    {{-- Formal Signatories Block --}}
    <div class="signatories">
        <div class="sig-box">
            <div class="sig-role">Prepared &amp; Verified By:</div>
            <div class="sig-name">{{ auth()->user()->full_name ?? (auth()->user()->name ?? 'Fleet Billing Cashier') }}</div>
            <div class="sig-title">Billing &amp; Cashier Operations</div>
        </div>
        <div class="sig-box">
            <div class="sig-role">Audited &amp; Checked By:</div>
            <div class="sig-name">Accounting Department</div>
            <div class="sig-title">Finance &amp; Audit Officer</div>
        </div>
        <div class="sig-box">
            <div class="sig-role">Approved By:</div>
            <div class="sig-name">Fleet Management</div>
            <div class="sig-title">General Operations Manager</div>
        </div>
    </div>

    {{-- Document Footer --}}
    <div class="report-footer">
        Euro Taxi Inc. &bull; Official Driver Financial Statement &amp; Settlement Report &bull; Document Verified &bull; Generated: {{ date('m/d/Y h:i:s A') }}
    </div>

</body>
</html>
