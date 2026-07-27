<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Roster Report &mdash; {{ date('Y-m-d') }}</title>
    <style>
        @page { margin: 0; size: auto; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: #fff; font-family: 'Segoe UI', system-ui, sans-serif; padding: 8mm 15mm 15mm 15mm; color: #111; font-size: 11px; }
        h1 { text-align: center; font-size: 20px; font-weight: 900; text-transform: uppercase; letter-spacing: .15em; margin-bottom: 4px; }
        .subtitle { text-align: center; font-size: 10px; color: #64748b; font-weight: 700; letter-spacing: .15em; text-transform: uppercase; margin-bottom: 32px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        thead tr { border-bottom: 1px solid #000; }
        thead th { padding: 8px 10px; font-size: 9px; text-transform: uppercase; font-weight: 800; text-align: left; letter-spacing: .05em; }
        thead th.center { text-align: center; }
        thead th.right { text-align: right; }
        tr { page-break-inside: avoid; break-inside: avoid; }
        tbody tr { border-bottom: 1px solid #eee; }
        td { padding: 8px 10px; font-size: 11px; vertical-align: middle; }
        td.center { text-align: center; font-weight: bold; }
        td.right { text-align: right; font-weight: 900; }
        .driver-name { font-weight: 900; font-size: 12px; color: #000; }
        .partner-name { font-weight: 700; font-size: 11px; color: #334155; }
        .no-partner { font-size: 10px; color: #94a3b8; font-style: italic; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: 800; text-transform: uppercase; }
        .badge-active { background: #dcfce7; color: #166534; }
        .badge-inactive { background: #f3f4f6; color: #4b5563; }
        .footer { text-align: center; margin-top: 40px; padding-top: 16px; border-top: 1px dashed #ccc; font-size: 9px; color: #777; }
        img { max-height: 60px !important; width: auto !important; display: block; margin: 0 auto 15px auto; }
        .header-meta { display: flex; justify-content: space-between; font-size: 10px; font-weight: bold; margin-bottom: 20px; text-transform: uppercase; border-bottom: 1px solid #000; padding-bottom: 10px; color: #333; }
    </style>
</head>
<body onload="window.print()">
    <img src="{{ asset('image/logo.png') }}" alt="Euro System Logo">
    <h1>DRIVER ROSTER & RECORDS REPORT</h1>
    <p class="subtitle">EURO TAXI MANAGEMENT SYSTEM &mdash; OFFICIAL RECORD</p>
    
    <div class="header-meta">
        <div>Total Registered Drivers: {{ count($drivers) }}</div>
        <div>Timestamp: {{ date('M d, Y H:i:s') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Driver Name</th>
                <th>Partner Driver</th>
                <th>License Number</th>
                <th>Contact Phone</th>
                <th class="center">Assigned Unit</th>
                <th class="center">Status</th>
                <th class="right">Boundary Target</th>
            </tr>
        </thead>
        <tbody>
            @foreach($drivers as $driver)
            <tr>
                <td>
                    <div class="driver-name">{{ $driver->full_name }}</div>
                </td>
                <td>
                    @if(!empty(trim($driver->partner_driver_name ?? '')))
                        <div class="partner-name">{{ $driver->partner_driver_name }}</div>
                    @else
                        <div class="no-partner">---</div>
                    @endif
                </td>
                <td>{{ $driver->license_number ?: '---' }}</td>
                <td>{{ $driver->contact_number ?: '---' }}</td>
                <td class="center">{{ $driver->assigned_plate ?: 'NO UNIT' }}</td>
                <td class="center">
                    <span class="badge {{ in_array($driver->driver_status, ['active', 'available', 'assigned']) ? 'badge-active' : 'badge-inactive' }}">
                        {{ strtoupper($driver->driver_status) }}
                    </span>
                </td>
                <td class="right">₱{{ number_format($driver->daily_boundary_target ?? 0, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Authenticated Driver Records &mdash; Generated: {{ date('m/d/Y, h:i:s A') }}</p>
    </div>
</body>
</html>
