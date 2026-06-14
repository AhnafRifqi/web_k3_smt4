@extends('layouts.app')

@section('title', 'Rekap Tahunan')
@section('page-title', 'Rekap Tahunan')
@section('page-subtitle', 'Laporan tahunan SMK3 otomatis')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
    <div class="mb-5">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Rekap Tahunan</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Pilih tahun untuk melihat rekap tahunan.</p>
    </div>

    <form method="GET" class="grid gap-4 md:grid-cols-2 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Tahun</label>
            <select name="year" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @for($y = now()->year; $y >= now()->year - 4; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>

        <div>
            <button type="submit" class="w-full rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Tampilkan Rekap</button>
        </div>
    </form>

    @if(isset($data))
    <div class="mt-6 grid gap-4 lg:grid-cols-4">
        <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
            <div class="text-sm text-gray-500">Audit</div>
            <div class="mt-2 text-3xl font-semibold text-gray-900 dark:text-white">{{ $data['total_audit'] ?? 0 }}</div>
        </div>
        <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
            <div class="text-sm text-gray-500">Temuan</div>
            <div class="mt-2 text-3xl font-semibold text-gray-900 dark:text-white">{{ $data['total_findings'] ?? 0 }}</div>
        </div>
        <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
            <div class="text-sm text-gray-500">CAPA</div>
            <div class="mt-2 text-3xl font-semibold text-gray-900 dark:text-white">{{ $data['total_capa'] ?? 0 }}</div>
        </div>
        <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
            <div class="text-sm text-gray-500">Kepatuhan</div>
            <div class="mt-2 text-3xl font-semibold text-gray-900 dark:text-white">{{ $data['compliance'] ?? 0 }}%</div>
        </div>
    </div>
    @endif
</div>
@endsection
