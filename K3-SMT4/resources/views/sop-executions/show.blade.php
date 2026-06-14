@extends('layouts.app')

@section('title', 'Detail Pelaksanaan SOP')
@section('page-title', 'Detail Pelaksanaan SOP')
@section('page-subtitle', 'Informasi lengkap pelaksanaan SOP')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Pelaksanaan SOP</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $sopExecution->sop?->name ?? 'SOP tidak ditemukan' }}</p>
        </div>
        <a href="{{ route('sop-executions.index') }}" class="btn-secondary">Kembali</a>
    </div>

    <div class="grid gap-6 lg:grid-cols-2 mt-6">
        <div class="space-y-4">
            <div>
                <h3 class="text-sm text-gray-500 dark:text-gray-400">Tanggal Pelaksanaan</h3>
                <p class="mt-1 text-gray-900 dark:text-white">{{ $sopExecution->execution_date->format('d F Y') }}</p>
            </div>
            <div>
                <h3 class="text-sm text-gray-500 dark:text-gray-400">Karyawan</h3>
                <p class="mt-1 text-gray-900 dark:text-white">{{ $sopExecution->employee?->name ?? 'Tidak diketahui' }}</p>
            </div>
            <div>
                <h3 class="text-sm text-gray-500 dark:text-gray-400">Status</h3>
                <p class="mt-1 text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $sopExecution->status)) }}</p>
            </div>
        </div>
        <div class="space-y-4">
            <div>
                <h3 class="text-sm text-gray-500 dark:text-gray-400">SOP</h3>
                <p class="mt-1 text-gray-900 dark:text-white">{{ $sopExecution->sop?->name ?? 'Tidak diketahui' }}</p>
            </div>
            <div>
                <h3 class="text-sm text-gray-500 dark:text-gray-400">Catatan</h3>
                <p class="mt-1 text-gray-900 dark:text-white">{{ $sopExecution->notes ?? '-' }}</p>
            </div>
            <div>
                <h3 class="text-sm text-gray-500 dark:text-gray-400">Direkam oleh</h3>
                <p class="mt-1 text-gray-900 dark:text-white">{{ $sopExecution->recorder?->name ?? 'Tidak diketahui' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
