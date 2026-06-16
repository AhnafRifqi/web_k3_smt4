@extends('layouts.app')
@section('title', 'Detail Audit')
@section('page-title', 'Detail Audit')
@section('page-subtitle', $audit->audit_number)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- LEFT: Info Audit --}}
    <div class="lg:col-span-2 space-y-4">

        {{-- Header Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="font-mono text-xs text-gray-500">{{ $audit->audit_number }}</span>
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium
                            bg-{{ $audit->status_color }}-100 text-{{ $audit->status_color }}-700">
                            {{ $audit->status_label }}
                        </span>
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $audit->type === 'internal' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                            {{ $audit->type_label }}
                        </span>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $audit->area }}</h2>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('audits.export-pdf', $audit) }}"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        PDF
                    </a>
                    @if(in_array(auth()->user()->role, ['admin','auditor']))
                    <a href="{{ route('audits.edit', $audit) }}"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors dark:text-gray-300 dark:bg-gray-700">
                        Edit
                    </a>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Tanggal Audit</p>
                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $audit->audit_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Auditor</p>
                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $audit->auditor_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Standar</p>
                    <p class="font-medium text-gray-800 dark:text-gray-200">
                        {{ match($audit->standard) { 'iso_45001' => 'ISO 45001:2018', 'pp_50_2012' => 'PP 50/2012', 'keduanya' => 'ISO 45001 + PP 50/2012', default => '-' } }}
                    </p>
                </div>
                @if($audit->audit_agency)
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Lembaga Audit</p>
                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $audit->audit_agency }}</p>
                </div>
                @endif
            </div>

            @if($audit->scope)
            <div class="mt-4 pt-4 border-t border-gray-50 dark:border-gray-700">
                <p class="text-xs text-gray-400 mb-1">Ruang Lingkup</p>
                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $audit->scope }}</p>
            </div>
            @endif

            @if($audit->summary)
            <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <p class="text-xs text-blue-600 font-medium mb-1">Ringkasan Audit</p>
                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $audit->summary }}</p>
            </div>
            @endif
        </div>

        {{-- Findings --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-50 dark:border-gray-700">
                <h3 class="font-semibold text-gray-900 dark:text-white text-sm">
                    Temuan Audit
                    <span class="ml-1 text-xs text-gray-400 font-normal">({{ $audit->findings->count() }})</span>
                </h3>
                @if(in_array(auth()->user()->role, ['admin','auditor','supervisor_k3']))
                <a href="{{ route('audit-findings.create', ['audit_id' => $audit->id]) }}"
                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Temuan
                </a>
                @endif
            </div>

            <div class="divide-y divide-gray-50 dark:divide-gray-700">
                @forelse($audit->findings as $finding)
                <div class="px-5 py-4">
                    <div class="flex items-start gap-3">
                        <span class="mt-0.5 inline-flex px-2 py-0.5 rounded text-xs font-bold
                            {{ $finding->severity === 'critical' ? 'bg-red-100 text-red-700' : ($finding->severity === 'major' ? 'bg-orange-100 text-orange-700' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ strtoupper($finding->severity) }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-mono text-gray-500">{{ $finding->finding_number }}</span>
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $finding->status === 'closed' ? 'bg-green-100 text-green-700' : ($finding->status === 'in_progress' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                    {{ $finding->status === 'closed' ? 'Closed' : ($finding->status === 'in_progress' ? 'In Progress' : 'Open') }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $finding->description }}</p>
                            @if($finding->recommendation)
                            <p class="text-xs text-gray-500 mt-1"><span class="font-medium">Rekomendasi:</span> {{ $finding->recommendation }}</p>
                            @endif
                            @if($finding->standard_ref)
                            <p class="text-xs text-blue-500 mt-1">Ref: {{ $finding->standard_ref }}</p>
                            @endif

                            @if($finding->capa)
                            <div class="mt-2 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-xs text-gray-500">CAPA: </span>
                                <a href="{{ route('capa.show', $finding->capa) }}" class="text-xs text-blue-600 hover:underline">{{ $finding->capa->capa_number }}</a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-5 py-10 text-center text-sm text-gray-400">
                    Belum ada temuan untuk audit ini.
                </div>
                @endforelse
            </div>
        </div>

        {{-- Checklist --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-50 dark:border-gray-700">
                <h3 class="font-semibold text-gray-900 dark:text-white text-sm">
                    Checklist Audit
                    <span class="ml-1 text-xs text-gray-400 font-normal">({{ $audit->checklistItems->count() }})</span>
                </h3>
            </div>

            @if(in_array(auth()->user()->role, ['super_admin', 'auditor', 'k3_manager']))
            <div class="px-5 py-4 border-b border-gray-50 dark:border-gray-700 bg-gray-50 dark:bg-slate-900/30">
                <form action="{{ route('audit-checklist.store', $audit) }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    @csrf
                    <input type="text" name="item_number" placeholder="No. Item" required
                        class="text-sm rounded-lg border border-gray-200 dark:border-slate-600 px-3 py-2 dark:bg-slate-800 dark:text-white">
                    <input type="text" name="description" placeholder="Deskripsi item" required
                        class="md:col-span-2 text-sm rounded-lg border border-gray-200 dark:border-slate-600 px-3 py-2 dark:bg-slate-800 dark:text-white">
                    <button type="submit" class="text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 px-3 py-2">Tambah</button>
                </form>
            </div>
            @endif

            <div class="divide-y divide-gray-50 dark:divide-gray-700">
                @forelse($audit->checklistItems as $item)
                <div class="px-5 py-4">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-mono font-bold text-gray-500">{{ $item->item_number }}</span>
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $item->conformance_status_color }}-100 text-{{ $item->conformance_status_color }}-700 dark:bg-{{ $item->conformance_status_color }}-900/30 dark:text-{{ $item->conformance_status_color }}-400">
                                    {{ $item->conformance_status_label }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $item->description }}</p>
                            @if($item->standard_ref)
                            <p class="text-xs text-blue-500 mt-1">Ref: {{ $item->standard_ref }}</p>
                            @endif
                            @if($item->notes)
                            <p class="text-xs text-gray-500 mt-1">{{ $item->notes }}</p>
                            @endif
                        </div>

                        @if(in_array(auth()->user()->role, ['super_admin', 'auditor', 'k3_manager']))
                        <form action="{{ route('audit-checklist.update', $item) }}" method="POST" class="flex items-center gap-2 shrink-0">
                            @csrf @method('PATCH')
                            <select name="conformance_status"
                                class="text-xs rounded-lg border border-gray-200 dark:border-slate-600 px-2 py-1.5 dark:bg-slate-800 dark:text-white">
                                <option value="not_assessed" {{ $item->conformance_status === 'not_assessed' ? 'selected' : '' }}>Not Assessed</option>
                                <option value="conforming" {{ $item->conformance_status === 'conforming' ? 'selected' : '' }}>Conforming</option>
                                <option value="minor_nc" {{ $item->conformance_status === 'minor_nc' ? 'selected' : '' }}>Minor NC</option>
                                <option value="major_nc" {{ $item->conformance_status === 'major_nc' ? 'selected' : '' }}>Major NC</option>
                                <option value="observation" {{ $item->conformance_status === 'observation' ? 'selected' : '' }}>Observation</option>
                            </select>
                            <button type="submit" class="text-xs font-medium text-blue-600 hover:text-blue-800 px-2 py-1.5">Update</button>
                        </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="px-5 py-10 text-center text-sm text-gray-400">
                    Belum ada item checklist untuk audit ini.
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- RIGHT: Stats Sidebar --}}
    <div class="space-y-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">Ringkasan Temuan</h3>
            <div class="space-y-3">
                @php
                    $minor    = $audit->findings->where('severity', 'minor')->count();
                    $major    = $audit->findings->where('severity', 'major')->count();
                    $critical = $audit->findings->where('severity', 'critical')->count();
                    $total    = $audit->findings->count();
                @endphp
                <div class="flex items-center justify-between">
                    <span class="text-sm text-yellow-700 dark:text-yellow-400 font-medium">Minor</span>
                    <span class="text-xl font-bold text-yellow-600">{{ $minor }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-orange-700 dark:text-orange-400 font-medium">Major</span>
                    <span class="text-xl font-bold text-orange-600">{{ $major }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-red-700 dark:text-red-400 font-medium">Critical</span>
                    <span class="text-xl font-bold text-red-600">{{ $critical }}</span>
                </div>
                <hr class="border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 dark:text-gray-400 font-medium">Total</span>
                    <span class="text-xl font-bold text-gray-800 dark:text-white">{{ $total }}</span>
                </div>
            </div>
        </div>

        {{-- CAPA linked --}}
        @if($audit->capas->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-3">CAPA Terkait ({{ $audit->capas->count() }})</h3>
            <div class="space-y-2">
                @foreach($audit->capas as $capa)
                <a href="{{ route('capa.show', $capa) }}" class="flex items-center justify-between p-2.5 rounded-lg bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                    <span class="text-xs font-mono text-gray-600 dark:text-gray-400">{{ $capa->capa_number }}</span>
                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium
                        {{ $capa->status === 'closed' ? 'bg-green-100 text-green-700' : ($capa->status === 'in_progress' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                        {{ $capa->status_label }}
                    </span>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
