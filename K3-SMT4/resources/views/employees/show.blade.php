@extends('layouts.app')
@section('title', 'Detail Karyawan')
@section('page-title', 'Detail Karyawan')
@section('page-subtitle', 'Informasi lengkap karyawan')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $employee->name }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $employee->position }} - {{ $employee->department?->name ?? '-' }}</p>
            </div>
            <a href="{{ route('employees.index') }}" class="btn-secondary">Kembali ke Daftar</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-3">
                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">NIK</p>
                    <p class="mt-1 text-gray-900 dark:text-gray-100 font-medium">{{ $employee->nik }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Email</p>
                    <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $employee->email ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Nomor HP</p>
                    <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $employee->phone ?? '-' }}</p>
                </div>
            </div>
            <div class="space-y-3">
                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Tanggal Masuk</p>
                    <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $employee->join_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Status</p>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">{{ $employee->status_label }}</span>
                </div>
                @if($employee->photo_url)
                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Foto</p>
                    <img src="{{ $employee->photo_url }}" alt="Foto {{ $employee->name }}" class="mt-2 w-32 h-32 rounded-xl object-cover">
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
