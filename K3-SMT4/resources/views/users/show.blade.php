@extends('layouts.app')

@section('title', 'Detail Pengguna')
@section('page-title', 'Detail Pengguna')
@section('page-subtitle', 'Informasi lengkap akun pengguna')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $user->name }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
        </div>
        <a href="{{ route('users.index') }}" class="btn-secondary">Kembali</a>
    </div>

    <div class="mt-6 grid gap-6 md:grid-cols-2">
        <div class="space-y-4">
            <div>
                <h3 class="text-sm text-gray-500 dark:text-gray-400">Nama</h3>
                <p class="mt-1 text-gray-900 dark:text-white">{{ $user->name }}</p>
            </div>
            <div>
                <h3 class="text-sm text-gray-500 dark:text-gray-400">Email</h3>
                <p class="mt-1 text-gray-900 dark:text-white">{{ $user->email }}</p>
            </div>
        </div>
        <div class="space-y-4">
            <div>
                <h3 class="text-sm text-gray-500 dark:text-gray-400">Role</h3>
                <p class="mt-1 text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</p>
            </div>
            <div>
                <h3 class="text-sm text-gray-500 dark:text-gray-400">Status Aktif</h3>
                <p class="mt-1 text-gray-900 dark:text-white">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</p>
            </div>
        </div>
    </div>
</div>
