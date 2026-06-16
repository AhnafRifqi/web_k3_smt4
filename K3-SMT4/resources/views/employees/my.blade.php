@extends('layouts.app')
@section('title', 'Data Karyawan Saya')
@section('page-title', 'Profil Karyawan Saya')
@section('page-subtitle', 'Lihat dan kelola informasi profil karyawan Anda')

@section('content')
<div class="max-w-4xl space-y-6">
    {{-- Main Profile Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700/50 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md">
        {{-- Banner Gradient --}}
        <div class="h-32 bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-700 dark:to-indigo-800 relative">
            <div class="absolute right-6 bottom-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white/20 text-white backdrop-blur-md">
                    {{ $employee->status_label }}
                </span>
            </div>
        </div>

        <div class="px-6 pb-6 relative">
            {{-- Photo & Header Grid --}}
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 -mt-16 mb-6">
                <div class="flex flex-col sm:flex-row items-center sm:items-end gap-4">
                    <div class="w-32 h-32 rounded-2xl border-4 border-white dark:border-gray-800 bg-gray-100 dark:bg-gray-700 overflow-hidden shadow-md shrink-0">
                        @if($employee->photo_url)
                            <img src="{{ $employee->photo_url }}" alt="Foto {{ $employee->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700 text-3xl font-bold">
                                {{ strtoupper(substr($employee->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="text-center sm:text-left pb-1">
                        <h2 class="text-2xl font-black text-gray-900 dark:text-white leading-tight">{{ $employee->name }}</h2>
                        <p class="text-sm font-semibold text-blue-600 dark:text-blue-400 mt-1">{{ $employee->position }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $employee->department?->name ?? '-' }}</p>
                    </div>
                </div>
                <div class="flex justify-center shrink-0">
                    <a href="{{ route('my-employee.edit') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-sm transition-all hover:scale-[1.02] active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit Profil Saya
                    </a>
                </div>
            </div>

            <hr class="border-gray-100 dark:border-gray-700/50 mb-6">

            {{-- Info Details Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex items-center gap-3 p-3 bg-gray-50/50 dark:bg-gray-900/30 rounded-2xl border border-gray-100/50 dark:border-gray-700/30">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.378 0 2.5-1.122 2.5-2.5S12.378 9 11 9H9v2h2z"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Nomor Induk Karyawan (NIK)</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white mt-0.5">{{ $employee->nik }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 bg-gray-50/50 dark:bg-gray-900/30 rounded-2xl border border-gray-100/50 dark:border-gray-700/30">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center text-purple-600 dark:text-purple-400 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L22 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Alamat Email</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white mt-0.5">{{ $employee->email ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center gap-3 p-3 bg-gray-50/50 dark:bg-gray-900/30 rounded-2xl border border-gray-100/50 dark:border-gray-700/30">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Nomor Telepon / HP</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white mt-0.5">{{ $employee->phone ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 bg-gray-50/50 dark:bg-gray-900/30 rounded-2xl border border-gray-100/50 dark:border-gray-700/30">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Tanggal Masuk Kerja</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white mt-0.5">{{ $employee->join_date->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SOP Executions List for Karyawan --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Pelaksanaan SOP Saya --}}
        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700/50 shadow-sm p-6">
            <h3 class="font-bold text-gray-900 dark:text-white text-base mb-4">Riwayat Pelaksanaan SOP Saya</h3>
            <div class="space-y-3 max-h-80 overflow-y-auto pr-1">
                @forelse($employee->sopExecutions ?? [] as $exec)
                <div class="p-3 bg-slate-50 dark:bg-slate-900/30 border border-slate-100 dark:border-slate-800 rounded-2xl flex items-center justify-between gap-3 text-xs">
                    <div class="min-w-0">
                        <p class="font-bold text-gray-800 dark:text-gray-200 truncate">{{ $exec->sop?->name }}</p>
                        <p class="text-gray-400 mt-0.5">{{ $exec->execution_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full font-bold
                            {{ $exec->status === 'sesuai' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : ($exec->status === 'tidak_sesuai' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400') }}">
                            {{ ucfirst(str_replace('_', ' ', $exec->status)) }}
                        </span>
                    </div>
                </div>
                @empty
                <p class="text-xs text-gray-500 text-center py-6">Belum ada riwayat pelaksanaan SOP.</p>
                @endforelse
            </div>
        </div>

        {{-- Tugas CAPA Saya --}}
        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700/50 shadow-sm p-6">
            <h3 class="font-bold text-gray-900 dark:text-white text-base mb-4">Tindakan CAPA yang Ditugaskan</h3>
            <div class="space-y-3 max-h-80 overflow-y-auto pr-1">
                @forelse($employee->capas ?? [] as $capa)
                <div class="p-3 bg-slate-50 dark:bg-slate-900/30 border border-slate-100 dark:border-slate-800 rounded-2xl flex items-center justify-between gap-3 text-xs">
                    <div class="min-w-0">
                        <p class="font-bold text-gray-800 dark:text-gray-200 truncate">{{ $capa->capa_number }}</p>
                        <p class="text-gray-500 truncate mt-0.5">{{ $capa->description }}</p>
                    </div>
                    <div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full font-bold
                            {{ $capa->status === 'closed' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : ($capa->status === 'in_progress' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400') }}">
                            {{ ucfirst(str_replace('_', ' ', $capa->status)) }}
                        </span>
                    </div>
                </div>
                @empty
                <p class="text-xs text-gray-500 text-center py-6">Belum ada tugas CAPA yang diberikan.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
