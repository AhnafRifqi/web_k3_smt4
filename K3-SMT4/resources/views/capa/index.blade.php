@extends('layouts.app')
@section('title', 'CAPA')
@section('page-title', 'CAPA')
@section('page-subtitle', 'Corrective Action Preventive Action')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
    <div>
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Daftar CAPA</h2>
        <p class="text-sm text-gray-500 mt-0.5">{{ $capas->total() }} CAPA ditemukan</p>
    </div>
    
    {{-- PERBAIKAN ROLE: Hanya K3 Manager dan K3 Officer --}}
    @if(auth()->check() && in_array(auth()->user()->role, ['k3_manager', 'k3_officer']))
    <a href="{{ route('capa.create') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah CAPA
    </a>
    @endif
</div>

<form method="GET" class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700 mb-4 shadow-sm">
    <div class="flex flex-wrap gap-3">
        <select name="status" class="text-sm bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <option value="">Semua Status</option>
            <option value="open"        {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
            <option value="closed"      {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
        </select>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">Filter</button>
        @if(request('status'))<a href="{{ route('capa.index') }}" class="px-3 py-2 text-sm text-gray-500 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-400 transition-colors">Reset</a>@endif
    </div>
</form>

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">No. CAPA</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Deskripsi</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden md:table-cell">PIC</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden lg:table-cell">Target</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                @forelse($capas as $capa)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors {{ $capa->isOverdue() ? 'bg-red-50/30 dark:bg-red-900/10' : '' }}">
                    <td class="px-4 py-3">
                        <div>
                            <p class="font-mono text-xs font-medium text-gray-800 dark:text-gray-200">{{ $capa->capa_number }}</p>
                            @if($capa->audit)
                            <p class="text-xs text-gray-400 mt-0.5">Audit: {{ $capa->audit->audit_number }}</p>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <p class="text-gray-700 dark:text-gray-300 line-clamp-2">{{ Str::limit($capa->description, 80) }}</p>
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell text-gray-600 dark:text-gray-400 text-xs">
                        {{ $capa->pic?->name ?? '-' }}
                    </td>
                    <td class="px-4 py-3 hidden lg:table-cell">
                        <span class="text-xs {{ $capa->isOverdue() ? 'text-red-600 font-semibold' : 'text-gray-600 dark:text-gray-400' }}">
                            {{ $capa->target_date->format('d M Y') }}
                            @if($capa->isOverdue()) <span class="block text-red-500">⚠ Overdue</span> @endif
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            bg-{{ $capa->status_color }}-100 text-{{ $capa->status_color }}-700 dark:bg-{{ $capa->status_color }}-900/30 dark:text-{{ $capa->status_color }}-400">
                            {{ $capa->status_label }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('capa.show', $capa) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            
                            {{-- PERBAIKAN ROLE: Hanya K3 Manager dan K3 Officer --}}
                            @if(auth()->check() && in_array(auth()->user()->role, ['k3_manager', 'k3_officer']))
                            <a href="{{ route('capa.edit', $capa) }}" class="p-1.5 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-12 text-center text-sm text-gray-400">Tidak ada data CAPA.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($capas->hasPages())
    <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">{{ $capas->links() }}</div>
    @endif
</div>
@endsection