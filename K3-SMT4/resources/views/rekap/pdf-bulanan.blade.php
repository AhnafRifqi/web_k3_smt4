<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Rekap K3 - {{ $data['period'] }}</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'DejaVu Sans',sans-serif; font-size:11px; color:#1f2937; line-height:1.5; }
.header { background:#1e40af; color:white; padding:20px 24px; margin-bottom:20px; }
.header h1 { font-size:16px; font-weight:bold; }
.header p { font-size:10px; opacity:.8; margin-top:2px; }
.content { padding:0 24px 24px; }
.section { margin-bottom:18px; }
.section-title { font-size:11px; font-weight:bold; color:#1e40af; border-bottom:2px solid #bfdbfe; padding-bottom:4px; margin-bottom:10px; text-transform:uppercase; }
.stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:8px; margin-bottom:16px; }
.stat-box { background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; padding:10px; text-align:center; }
.stat-value { font-size:20px; font-weight:bold; color:#1e40af; }
.stat-label { font-size:9px; color:#64748b; margin-top:2px; text-transform:uppercase; }
.narasi-box { background:#eff6ff; border:1px solid #bfdbfe; border-left:4px solid #3b82f6; padding:12px 14px; border-radius:6px; font-size:10px; line-height:1.7; }
.progress-bar { background:#e2e8f0; border-radius:4px; height:8px; margin-top:4px; }
.progress-fill { height:8px; border-radius:4px; }
.compliance-green  { background:#22c55e; }
.compliance-yellow { background:#eab308; }
.compliance-red    { background:#ef4444; }
.footer { margin-top:24px; padding-top:8px; border-top:1px solid #e2e8f0; text-align:center; font-size:9px; color:#94a3b8; }
</style>
</head>
<body>
<div class="header">
    <h1>Rekap K3 — {{ $data['period'] }}</h1>
    <p>PT Jalur Nugraha Ekakurir (JNE) | Sistem Manajemen K3 | Digenerate: {{ now()->format('d F Y H:i') }} WIB</p>
</div>
<div class="content">

    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-value" style="color:{{ $data['compliance'] >= 80 ? '#16a34a' : ($data['compliance'] >= 60 ? '#ca8a04' : '#dc2626') }}">{{ $data['compliance'] }}%</div>
            <div class="stat-label">Kepatuhan SOP</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ $data['total_exec'] }}</div>
            <div class="stat-label">Total Pelaksanaan</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ $data['total_audit'] }}</div>
            <div class="stat-label">Audit Dilakukan</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ $data['capa_rate'] }}%</div>
            <div class="stat-label">CAPA Selesai</div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Detail Pelaksanaan SOP</div>
        <table width="100%" style="font-size:10px; border-collapse:collapse;">
            <tr><td style="padding:4px 0; width:40%">Total Pelaksanaan</td><td style="font-weight:bold">{{ $data['total_exec'] }}</td></tr>
            <tr><td style="padding:4px 0;">Sesuai</td><td style="font-weight:bold; color:#16a34a">{{ $data['sesuai'] }}</td></tr>
            <tr><td style="padding:4px 0;">Tidak Sesuai</td><td style="font-weight:bold; color:#dc2626">{{ $data['tidak_sesuai'] }}</td></tr>
            <tr><td style="padding:4px 0;">Perlu Perbaikan</td><td style="font-weight:bold; color:#ca8a04">{{ $data['perbaikan'] }}</td></tr>
        </table>
        @if($data['total_exec'] > 0)
        <div style="margin-top:8px;">
            <div style="display:flex;justify-content:space-between;font-size:9px;color:#6b7280;margin-bottom:2px;">
                <span>Tingkat Kepatuhan</span><span>{{ $data['compliance'] }}%</span>
            </div>
            <div class="progress-bar"><div class="progress-fill {{ $data['compliance'] >= 80 ? 'compliance-green' : ($data['compliance'] >= 60 ? 'compliance-yellow' : 'compliance-red') }}" style="width:{{ $data['compliance'] }}%"></div></div>
        </div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Temuan Audit</div>
        <table width="100%" style="font-size:10px; border-collapse:collapse;">
            <tr><td style="padding:4px 0; width:40%;">Minor</td><td style="font-weight:bold; color:#854d0e">{{ $data['minor'] }}</td></tr>
            <tr><td style="padding:4px 0;">Major</td><td style="font-weight:bold; color:#9a3412">{{ $data['major'] }}</td></tr>
            <tr><td style="padding:4px 0;">Critical</td><td style="font-weight:bold; color:#7f1d1d">{{ $data['critical'] }}</td></tr>
            <tr><td style="padding:4px 0; border-top:1px solid #e2e8f0;">Total</td><td style="font-weight:bold; border-top:1px solid #e2e8f0">{{ $data['minor'] + $data['major'] + $data['critical'] }}</td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Narasi Kesimpulan Otomatis</div>
        <div class="narasi-box">{{ $data['narasi'] }}</div>
    </div>

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh Sistem SMK3 JNE berdasarkan data real-time</p>
    </div>
</div>
</body>
</html>
