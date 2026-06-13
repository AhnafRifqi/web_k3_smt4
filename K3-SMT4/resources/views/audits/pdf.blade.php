<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Audit {{ $audit->audit_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1f2937; line-height: 1.5; }
        .header { background: #1e40af; color: white; padding: 20px 24px; margin-bottom: 20px; }
        .header h1 { font-size: 18px; font-weight: bold; }
        .header p { font-size: 11px; opacity: 0.8; margin-top: 2px; }
        .logo-area { display: flex; justify-content: space-between; align-items: flex-start; }
        .logo-badge { background: white; color: #1e40af; font-weight: bold; font-size: 14px; padding: 6px 12px; border-radius: 6px; }
        .content { padding: 0 24px 24px; }
        .section { margin-bottom: 20px; }
        .section-title { font-size: 12px; font-weight: bold; color: #1e40af; border-bottom: 2px solid #bfdbfe; padding-bottom: 4px; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
        .info-item { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 8px 10px; }
        .info-label { font-size: 9px; color: #64748b; text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 2px; }
        .info-value { font-size: 11px; font-weight: 600; color: #1e293b; }
        table { width: 100%; border-collapse: collapse; font-size: 10px; }
        th { background: #1e40af; color: white; padding: 7px 10px; text-align: left; font-weight: 600; }
        td { padding: 7px 10px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
        tr:nth-child(even) td { background: #f8fafc; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: bold; }
        .badge-minor    { background: #fef9c3; color: #854d0e; }
        .badge-major    { background: #ffedd5; color: #9a3412; }
        .badge-critical { background: #fee2e2; color: #7f1d1d; }
        .badge-open     { background: #fee2e2; color: #7f1d1d; }
        .badge-progress { background: #fef9c3; color: #854d0e; }
        .badge-closed   { background: #dcfce7; color: #14532d; }
        .narasi-box { background: #eff6ff; border: 1px solid #bfdbfe; border-left: 4px solid #3b82f6; padding: 12px 14px; border-radius: 6px; font-size: 10px; line-height: 1.6; }
        .footer { margin-top: 30px; padding-top: 10px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 9px; color: #94a3b8; }
        .page-break { page-break-before: always; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-area">
            <div>
                <h1>Laporan Audit K3</h1>
                <p>PT Jalur Nugraha Ekakurir (JNE) — Sistem Manajemen K3</p>
            </div>
            <div class="logo-badge">SMK3 JNE</div>
        </div>
    </div>

    <div class="content">

        {{-- Info Audit --}}
        <div class="section">
            <div class="section-title">Informasi Audit</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nomor Audit</div>
                    <div class="info-value">{{ $audit->audit_number }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tipe Audit</div>
                    <div class="info-value">{{ $audit->type_label }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tanggal Audit</div>
                    <div class="info-value">{{ $audit->audit_date->format('d F Y') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div class="info-value">{{ $audit->status_label }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Auditor</div>
                    <div class="info-value">{{ $audit->auditor_name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Area Audit</div>
                    <div class="info-value">{{ $audit->area }}</div>
                </div>
                <div class="info-item" style="grid-column: span 2">
                    <div class="info-label">Standar</div>
                    <div class="info-value">{{ match($audit->standard) { 'iso_45001' => 'ISO 45001:2018', 'pp_50_2012' => 'PP No. 50 Tahun 2012', 'keduanya' => 'ISO 45001:2018 & PP No. 50 Tahun 2012', default => '-' } }}</div>
                </div>
            </div>
        </div>

        @if($audit->scope)
        <div class="section">
            <div class="section-title">Ruang Lingkup</div>
            <p>{{ $audit->scope }}</p>
        </div>
        @endif

        {{-- Findings --}}
        <div class="section">
            <div class="section-title">Temuan Audit ({{ $audit->findings->count() }})</div>
            @if($audit->findings->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th width="60">No.</th>
                        <th width="70">Severity</th>
                        <th>Deskripsi Temuan</th>
                        <th width="120">Referensi</th>
                        <th width="70">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($audit->findings as $finding)
                    <tr>
                        <td>{{ $finding->finding_number }}</td>
                        <td>
                            <span class="badge badge-{{ $finding->severity }}">{{ strtoupper($finding->severity) }}</span>
                        </td>
                        <td>
                            {{ $finding->description }}
                            @if($finding->recommendation)
                            <br><em style="color:#6b7280">Rekomendasi: {{ $finding->recommendation }}</em>
                            @endif
                        </td>
                        <td>{{ $finding->standard_ref ?? '-' }}</td>
                        <td>
                            <span class="badge badge-{{ $finding->status === 'closed' ? 'closed' : ($finding->status === 'in_progress' ? 'progress' : 'open') }}">
                                {{ $finding->status === 'closed' ? 'Closed' : ($finding->status === 'in_progress' ? 'Progress' : 'Open') }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p style="color:#6b7280; font-style:italic">Tidak ada temuan pada audit ini.</p>
            @endif
        </div>

        @if($audit->summary)
        <div class="section">
            <div class="section-title">Ringkasan & Kesimpulan</div>
            <div class="narasi-box">{{ $audit->summary }}</div>
        </div>
        @endif

        <div class="footer">
            <p>Laporan ini digenerate oleh Sistem SMK3 JNE pada {{ now()->format('d F Y H:i') }} WIB</p>
            <p>Dokumen ini bersifat resmi dan dapat digunakan sebagai bukti pelaksanaan audit K3</p>
        </div>
    </div>
</body>
</html>
