@extends('layouts.app')

@section('title', 'Detail Departemen')
@section('page-title', 'Detail Departemen')
@section('page-subtitle', 'Informasi lengkap tentang departemen')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $department->name }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kode: {{ $department->code }}</p>
        </div>
        <a href="{{ route('departments.index') }}" class="btn-secondary">Kembali</a>
    </div>

    <div class="mt-6 grid gap-6 md:grid-cols-2">
        <div class="space-y-4">
            <div>
                <h3 class="text-sm text-gray-500 dark:text-gray-400">Nama Departemen</h3>
                <p class="mt-1 text-gray-900 dark:text-white">{{ $department->name }}</p>
            </div>
            <div>
                <h3 class="text-sm text-gray-500 dark:text-gray-400">Kode</h3>
                <p class="mt-1 text-gray-900 dark:text-white">{{ $department->code }}</p>
            </div>
        </div>
        <div class="space-y-4">
            <div>
                <h3 class="text-sm text-gray-500 dark:text-gray-400">Deskripsi</h3>
                <p class="mt-1 text-gray-900 dark:text-white">{{ $department->description ?? '-' }}</p>
            </div>
            <div>
                <h3 class="text-sm text-gray-500 dark:text-gray-400">Jumlah Karyawan</h3>
                <p class="mt-1 text-gray-900 dark:text-white">{{ $department->employees_count ?? $department->employees()->count() }}</p>
            </div>
        </div>
    </div>
</div>
