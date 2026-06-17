<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard K3 - {{ now()->format('d M Y') }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; margin: 30px; }
        h1 { font-size: 18px; color: #1e40af; border-bottom: 2px solid #1e40af; padding-bottom: 8px; }
        p.subtitle { color: #64748b; margin-top: 2px; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #1e40af; color: white; padding: 8px 12px; text-align: left; font-size: 11px; }
        td { padding: 7px 12px; border-bottom: 1px solid #e2e8f0; }
        tr:nth-child(even) td { background: #f8fafc; }
        .value { font-weight: bold; text-align: right; }
        .footer { margin-top: 30px; font-size: 10px; color: #94a3b8; text-align: right; }
    </style>
</head>
<body>
    <h1>Laporan Dashboard SMK3</h1>
    <p class="subtitle">Dicetak: {{ now()->format('d M Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>Indikator KPI</th>
                <th style="text-align:right">Nilai</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Safe Days (tanpa LTI)</td><td class="value">{{ number_format($stats['safe_days']) }} hari</td></tr>
            <tr><td>Karyawan Aktif</td><td class="value">{{ number_format($stats['total_employees']) }}</td></tr>
            <tr><td>SOP Aktif</td><td class="value">{{ number_format($stats['total_sops']) }}</td></tr>
            <tr><td>Open Incidents</td><td class="value">{{ number_format($stats['open_incidents']) }}</td></tr>
            <tr><td>Total Audit</td><td class="value">{{ number_format($stats['total_audits']) }}</td></tr>
            <tr><td>Total Temuan Audit</td><td class="value">{{ number_format($stats['total_findings']) }}</td></tr>
            <tr><td>Temuan Open</td><td class="value">{{ number_format($stats['open_findings']) }}</td></tr>
            <tr><td>Temuan Closed</td><td class="value">{{ number_format($stats['closed_findings']) }}</td></tr>
            <tr><td>CAPA Open</td><td class="value">{{ number_format($stats['open_capa']) }}</td></tr>
            <tr><td>CAPA Overdue</td><td class="value">{{ number_format($stats['overdue_capa']) }}</td></tr>
            <tr><td>Kepatuhan SOP (bulan ini)</td><td class="value">{{ $stats['sop_compliance'] }}%</td></tr>
            <tr><td>Dokumen Akan Kadaluarsa (30 hari)</td><td class="value">{{ number_format($stats['documents_expiring_soon']) }}</td></tr>
            <tr><td>Form Overdue</td><td class="value">{{ number_format($stats['overdue_forms']) }}</td></tr>
        </tbody>
    </table>

    <p class="footer">Sistem Manajemen K3 &mdash; {{ config('app.name') }}</p>
</body>
</html>
