@extends('layouts.app')

@section('title', 'Daftar Temuan Audit')
@section('page-title', 'Temuan Audit')
@section('page-subtitle', 'Kelola semua temuan hasil audit')

@section('content')
<div class="space-y-5">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Temuan Audit</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Lihat semua temuan audit dan filter berdasarkan audit atau tingkat keparahan.</p>
            </div>
            <a href="{{ route('audit-findings.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Tambah Temuan</a>
        </div>
        <form method="GET" class="grid gap-3 md:grid-cols-3">
            <select name="audit_id" class="form-input">
                <option value="">Semua Audit</option>
                @foreach($audits as $audit)
                <option value="{{ $audit->id }}" {{ request('audit_id') == $audit->id ? 'selected' : '' }}>{{ $audit->name }} - {{ $audit->date->format('d/m/Y') }}</option>
                @endforeach
            </select>
            <select name="severity" class="form-input">
                <option value="">Semua Tingkat</option>
                <option value="minor" {{ request('severity') == 'minor' ? 'selected' : '' }}>Minor</option>
                <option value="major" {{ request('severity') == 'major' ? 'selected' : '' }}>Major</option>
                <option value="critical" {{ request('severity') == 'critical' ? 'selected' : '' }}>Critical</option>
            </select>
            <select name="status" class="form-input">
                <option value="">Semua Status</option>
                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
            <div class="flex items-center gap-2">
                <button type="submit" class="btn-primary">Filter</button>
                @if(request()->hasAny(['audit_id', 'severity', 'status']))
                <a href="{{ route('audit-findings.index') }}" class="btn-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
            <thead class="bg-gray-100 dark:bg-gray-900 text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3">Audit</th>
                    <th class="px-4 py-3">No. Temuan</th>
                    <th class="px-4 py-3">Tingkat</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($findings as $finding)
                <tr>
                    <td class="px-4 py-3">{{ $finding->audit?->name ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $finding->finding_number }}</td>
                    <td class="px-4 py-3">{{ ucfirst($finding->severity) }}</td>
                    <td class="px-4 py-3">{{ ucfirst(str_replace('_', ' ', $finding->status)) }}</td>
                    <td class="px-4 py-3 space-x-2">
                        <a href="{{ route('audit-findings.edit', $finding) }}" class="text-orange-600 hover:underline">Edit</a>
                        <form action="{{ route('audit-findings.destroy', $finding) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus temuan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">Tidak ada temuan audit ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pt-4">{{ $findings->links() }}</div>
</div>
@endsection
