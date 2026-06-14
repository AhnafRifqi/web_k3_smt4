@extends('layouts.app')

@section('title', 'Detail Temuan Audit')
@section('page-title', 'Detail Temuan Audit')
@section('page-subtitle', 'Informasi lengkap temuan audit')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $auditFinding->finding_number }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Audit: {{ $auditFinding->audit?->name ?? 'Tidak diketahui' }}</p>
        </div>
        <a href="{{ route('audit-findings.index') }}" class="btn-secondary">Kembali</a>
    </div>

    <div class="mt-6 grid gap-6 md:grid-cols-2">
        <div class="space-y-4">
            <div>
                <h3 class="text-sm text-gray-500 dark:text-gray-400">Deskripsi</h3>
                <p class="mt-1 text-gray-900 dark:text-white">{{ $auditFinding->description }}</p>
            </div>
            <div>
                <h3 class="text-sm text-gray-500 dark:text-gray-400">Tingkat</h3>
                <p class="mt-1 text-gray-900 dark:text-white">{{ ucfirst($auditFinding->severity) }}</p>
            </div>
            <div>
                <h3 class="text-sm text-gray-500 dark:text-gray-400">Status</h3>
                <p class="mt-1 text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $auditFinding->status)) }}</p>
            </div>
        </div>
        <div class="space-y-4">
            <div>
                <h3 class="text-sm text-gray-500 dark:text-gray-400">Area</h3>
                <p class="mt-1 text-gray-900 dark:text-white">{{ $auditFinding->area ?? '-' }}</p>
            </div>
            <div>
                <h3 class="text-sm text-gray-500 dark:text-gray-400">Referensi Standar</h3>
                <p class="mt-1 text-gray-900 dark:text-white">{{ $auditFinding->standard_ref ?? '-' }}</p>
            </div>
            <div>
                <h3 class="text-sm text-gray-500 dark:text-gray-400">Rekomendasi</h3>
                <p class="mt-1 text-gray-900 dark:text-white">{{ $auditFinding->recommendation ?? '-' }}</p>
            </div>
            @if($auditFinding->capa)
            <div>
                <h3 class="text-sm text-gray-500 dark:text-gray-400">CAPA terkait</h3>
                <a href="{{ route('capa.show', $auditFinding->capa) }}" class="text-blue-600 hover:underline">{{ $auditFinding->capa->capa_number }}</a>
            </div>
            @endif
        </div>
    </div>
</div>
