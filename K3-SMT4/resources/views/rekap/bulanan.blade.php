@extends('layouts.app')

@section('title', 'Rekap Bulanan')
@section('page-title', 'Rekap Bulanan')
@section('page-subtitle', 'Laporan dan narasi otomatis SMK3')

@section('content')

{{-- Filter Form --}}
<form method="GET" class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700 mb-5 shadow-sm">
    <div class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Tahun</label>
            <select name="year" class="text-sm bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @for($y = now()->year; $y >= now()->year - 4; $y--)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Bulan</label>
            <select name="month" class="text-sm bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @foreach(range(1,12) as $m)
                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
            Generate Rekap
        </button>
        <a href="{{ route('rekap.export-pdf', ['type' => 'bulanan', 'year' => $year, 'month' => $month]) }}"
            class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Export PDF
        </a>
    </div>
</form>

{{-- Stats Cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-5">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700 shadow-sm text-center">
        <p class="text-3xl font-bold {{ $data['compliance'] >= 80 ? 'text-green-600' : ($data['compliance'] >= 60 ? 'text-yellow-500' : 'text-red-500') }}">{{ $data['compliance'] }}%</p>
        <p class="text-xs text-gray-500 mt-1 font-medium uppercase tracking-wide">Kepatuhan SOP</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700 shadow-sm text-center">
        <p class="text-3xl font-bold text-blue-600">{{ $data['total_audit'] }}</p>
        <p class="text-xs text-gray-500 mt-1 font-medium uppercase tracking-wide">Audit Dilakukan</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700 shadow-sm text-center">
        <p class="text-3xl font-bold text-orange-500">{{ $data['minor'] + $data['major'] + $data['critical'] }}</p>
        <p class="text-xs text-gray-500 mt-1 font-medium uppercase tracking-wide">Total Temuan</p>
        <p class="text-xs text-gray-400 mt-0.5">{{ $data['minor'] }}M / {{ $data['major'] }}Mj / {{ $data['critical'] }}C</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700 shadow-sm text-center">
        <p class="text-3xl font-bold text-green-600">{{ $data['capa_rate'] }}%</p>
        <p class="text-xs text-gray-500 mt-1 font-medium uppercase tracking-wide">CAPA Selesai</p>
        <p class="text-xs text-gray-400 mt-0.5">{{ $data['capa_done'] }}/{{ $data['capa_total'] }}</p>
    </div>
</div>

{{-- Pelaksanaan SOP Detail --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
        <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">Detail Pelaksanaan SOP</h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600 dark:text-gray-400">Total Pelaksanaan</span>
                <span class="font-semibold text-gray-900 dark:text-white">{{ $data['total_exec'] }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="flex items-center gap-1.5 text-sm text-gray-600 dark:text-gray-400">
                    <span class="w-2.5 h-2.5 rounded-full bg-green-500 inline-block"></span> Sesuai
                </span>
                <span class="font-semibold text-green-600">{{ $data['sesuai'] }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="flex items-center gap-1.5 text-sm text-gray-600 dark:text-gray-400">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500 inline-block"></span> Tidak Sesuai
                </span>
                <span class="font-semibold text-red-600">{{ $data['tidak_sesuai'] }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="flex items-center gap-1.5 text-sm text-gray-600 dark:text-gray-400">
                    <span class="w-2.5 h-2.5 rounded-full bg-yellow-500 inline-block"></span> Perlu Perbaikan
                </span>
                <span class="font-semibold text-yellow-600">{{ $data['perbaikan'] }}</span>
            </div>
        </div>
        @if($data['total_exec'] > 0)
        <div class="mt-4">
            <div class="flex justify-between text-xs text-gray-500 mb-1">
                <span>Tingkat Kepatuhan</span>
                <span>{{ $data['compliance'] }}%</span>
            </div>
            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2">
                <div class="h-2 rounded-full {{ $data['compliance'] >= 80 ? 'bg-green-500' : ($data['compliance'] >= 60 ? 'bg-yellow-500' : 'bg-red-500') }}"
                    style="width: {{ $data['compliance'] }}%"></div>
            </div>
        </div>
        @endif
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
        <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">Detail Temuan Audit</h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                <span class="text-sm font-medium text-yellow-800 dark:text-yellow-400">Minor</span>
                <span class="font-bold text-yellow-800 dark:text-yellow-400 text-lg">{{ $data['minor'] }}</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
                <span class="text-sm font-medium text-orange-800 dark:text-orange-400">Major</span>
                <span class="font-bold text-orange-800 dark:text-orange-400 text-lg">{{ $data['major'] }}</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                <span class="text-sm font-medium text-red-800 dark:text-red-400">Critical</span>
                <span class="font-bold text-red-800 dark:text-red-400 text-lg">{{ $data['critical'] }}</span>
            </div>
        </div>
    </div>
</div>

{{-- NARASI OTOMATIS --}}
<div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-950/30 dark:to-indigo-950/30 rounded-xl p-6 border border-blue-100 dark:border-blue-900/50 shadow-sm">
    <div class="flex items-start gap-3 mb-4">
        <div class="w-9 h-9 rounded-lg bg-blue-600 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m1.636-6.364l.707.707M12 21v-1m-6.364-1.636l.707-.707M3 12l.707.707M20.364 5.636l-.707.707M17 12l-1 1"/></svg>
        </div>
        <div>
            <h3 class="font-bold text-blue-900 dark:text-blue-300 text-sm">Narasi Otomatis Sistem</h3>
            <p class="text-xs text-blue-600 dark:text-blue-400 mt-0.5">Dihasilkan otomatis berdasarkan data sistem — {{ $data['period'] }}</p>
        </div>
    </div>
    <blockquote class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed bg-white/60 dark:bg-gray-800/60 rounded-lg p-4 border-l-4 border-blue-400">
        {{ $data['narasi'] }}
    </blockquote>
    <div class="mt-3 text-xs text-blue-500 dark:text-blue-400 flex items-center gap-1">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Narasi ini dibuat secara otomatis oleh NarasiService berdasarkan data real-time sistem.
    </div>
</div>

@endsection
