<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Evidence Package - {{ $audit->audit_number }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; line-height: 1.5; color: #1e293b; }
        .page { padding: 40px; }
        h1 { font-size: 20px; color: #1e40af; margin-bottom: 5px; }
        h2 { font-size: 16px; color: #334155; border-bottom: 2px solid #e2e8f0; padding-bottom: 5px; margin-top: 25px; }
        h3 { font-size: 14px; color: #475569; margin-top: 15px; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th { background: #f1f5f9; text-align: left; padding: 8px 10px; font-size: 11px; font-weight: 600; border: 1px solid #e2e8f0; }
        td { padding: 8px 10px; border: 1px solid #e2e8f0; font-size: 11px; }
        .header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 3px double #1e40af; }
        .header p { color: #64748b; font-size: 11px; }
        .meta { margin: 15px 0; }
        .meta td { border: none; padding: 3px 10px; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 600; }
        .badge-minor { background: #fef9c3; color: #854d0e; }
        .badge-major { background: #fed7aa; color: #9a3412; }
        .badge-critical { background: #fecaca; color: #991b1b; }
        .badge-open { background: #fecaca; color: #991b1b; }
        .badge-closed { background: #bbf7d0; color: #166534; }
        .footer { text-align: center; color: #94a3b8; font-size: 10px; margin-top: 40px; padding-top: 20px; border-top: 1px solid #e2e8f0; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <h1>Audit Evidence Package</h1>
            <p>{{ $audit->audit_number }} | {{ $audit->type }} | {{ $audit->audit_date->format('d M Y') }}</p>
        </div>

        <h2>Audit Summary</h2>
        <table class="meta">
            <tr><td width="150"><strong>Audit Number</strong></td><td>{{ $audit->audit_number }}</td></tr>
            <tr><td><strong>Type</strong></td><td>{{ ucfirst($audit->type) }}</td></tr>
            <tr><td><strong>Area</strong></td><td>{{ $audit->area }}</td></tr>
            <tr><td><strong>Auditor</strong></td><td>{{ $audit->auditor_name }}</td></tr>
            <tr><td><strong>Date</strong></td><td>{{ $audit->audit_date->format('d M Y') }} @if($audit->audit_date_end) - {{ $audit->audit_date_end->format('d M Y') }} @endif</td></tr>
            <tr><td><strong>Standard</strong></td><td>{{ str_replace('_', ' ', strtoupper($audit->standard)) }}</td></tr>
            <tr><td><strong>Scope</strong></td><td>{{ $audit->scope ?? '-' }}</td></tr>
            <tr><td><strong>Status</strong></td><td>{{ ucfirst($audit->status) }}</td></tr>
        </table>

        @if($audit->summary)
        <h3>Executive Summary</h3>
        <p>{{ $audit->summary }}</p>
        @endif

        <div class="page-break"></div>

        <h2>Findings</h2>
        @if($audit->findings->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Finding</th>
                    <th>Category</th>
                    <th>Severity</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($audit->findings as $i => $finding)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $finding->finding }}</td>
                    <td>{{ $finding->category ?? '-' }}</td>
                    <td><span class="badge badge-{{ $finding->severity }}">{{ ucfirst($finding->severity) }}</span></td>
                    <td><span class="badge badge-{{ $finding->status }}">{{ ucfirst($finding->status) }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>No findings recorded.</p>
        @endif

        <h2>CAPA Status</h2>
        @if($audit->capas->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>CAPA #</th>
                    <th>Description</th>
                    <th>PIC</th>
                    <th>Target</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($audit->capas as $capa)
                <tr>
                    <td>{{ $capa->capa_number }}</td>
                    <td>{{ Str::limit($capa->description, 60) }}</td>
                    <td>{{ $capa->pic?->name ?? '-' }}</td>
                    <td>{{ $capa->target_date?->format('d M Y') ?? '-' }}</td>
                    <td><span class="badge badge-{{ $capa->status }}">{{ $capa->status_label }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>No CAPAs linked to this audit.</p>
        @endif

        <h2>Related Documents</h2>
        @if($audit->documents->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Document #</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($audit->documents as $doc)
                <tr>
                    <td>{{ $doc->document_number }}</td>
                    <td>{{ $doc->title }}</td>
                    <td>{{ $doc->category_label }}</td>
                    <td>{{ $doc->workflow_status_label ?? $doc->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>No documents linked.</p>
        @endif

        <div class="footer">
            <p>Generated on {{ now()->format('d M Y H:i') }} | SMK3 JNE - K3 Information Management System</p>
        </div>
    </div>
</body>
</html>