@extends('layouts.app')
@section('title', 'Audit K3')
@section('page-title', 'Audit K3')
@section('page-subtitle', 'Manajemen audit internal & eksternal')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
    <div>
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Daftar Audit</h2>
        <p class="text-sm text-gray-500 mt-0.5">{{ $audits->total() }} audit ditemukan</p>
    </div>
    
    {{-- TOMBOL BUAT AUDIT: Hanya untuk K3 Manager --}}
    @if(auth()->check() && auth()->user()->role === 'k3_manager')
    <a href="{{ route('audits.create') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Buat Audit
    </a>
    @endif
</div>

{{-- Filter --}}
<form method="GET" class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700 mb-4 shadow-sm">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no. audit / area..."
            class="col-span-2 text-sm bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500">
        <select name="type" class="text-sm bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <option value="">Semua Tipe</option>
            <option value="internal" {{ request('type') === 'internal' ? 'selected' : '' }}>Internal</option>
            <option value="eksternal" {{ request('type') === 'eksternal' ? 'selected' : '' }}>Eksternal</option>
        </select>
        <div class="flex gap-2">
            <select name="status" class="flex-1 text-sm bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">Semua Status</option>
                <option value="planned" {{ request('status') === 'planned' ? 'selected' : '' }}>Direncanakan</option>
                <option value="ongoing" {{ request('status') === 'ongoing' ? 'selected' : '' }}>Berlangsung</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">Cari</button>
        </div>
    </div>
</form>

{{-- Cards grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
    @forelse($audits as $audit)
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
        <div class="p-5">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <span class="text-xs font-mono font-medium text-gray-500 dark:text-gray-400">{{ $audit->audit_number }}</span>
                    <p class="font-semibold text-gray-900 dark:text-white mt-0.5">{{ $audit->area }}</p>
                </div>
                <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                    {{ $audit->type === 'internal' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400' }}">
                    {{ $audit->type_label }}
                </span>
            </div>
            <div class="space-y-1.5 text-xs text-gray-500 dark:text-gray-400 mb-4">
                <div class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ $audit->audit_date->format('d M Y') }}{{ $audit->audit_date_end ? ' — ' . $audit->audit_date_end->format('d M Y') : '' }}
                </div>
                <div class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    {{ $audit->auditor_name }}{{ $audit->audit_agency ? ' — ' . $audit->audit_agency : '' }}
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                        bg-{{ $audit->status_color }}-100 text-{{ $audit->status_color }}-700 dark:bg-{{ $audit->status_color }}-900/30 dark:text-{{ $audit->status_color }}-400">
                        {{ $audit->status_label }}
                    </span>
                    @if($audit->findings->count() > 0)
                    <span class="text-xs text-gray-400">{{ $audit->findings->count() }} temuan</span>
                    @endif
                </div>
                <div class="flex items-center gap-1">
                    <a href="{{ route('audits.show', $audit) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </a>
                    <a href="{{ route('audits.export-pdf', $audit) }}" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Export PDF">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </a>
                    
                    {{-- TOMBOL EDIT AUDIT: Hanya untuk K3 Manager --}}
                    @if(auth()->check() && auth()->user()->role === 'k3_manager')
                    <a href="{{ route('audits.edit', $audit) }}" class="p-1.5 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-3 py-16 text-center">
        <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        <p class="text-gray-500">Tidak ada data audit.</p>
    </div>
    @endforelse
</div>

@if($audits->hasPages())
<div class="mt-4">{{ $audits->links() }}</div>
@endif
@endsection