@extends('layouts.app')

@section('title', 'Detail SOP')
@section('page-title', 'Detail SOP')
@section('page-subtitle', 'Informasi lengkap SOP')

@section('content')
@php
    $fileExt = $sop->file_url
        ? strtolower(pathinfo(parse_url($sop->file_url, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION))
        : null;
    $isImage = in_array($fileExt, ['jpg', 'jpeg', 'png'], true);
    $isPdf = $fileExt === 'pdf';
@endphp
<div class="space-y-5">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $sop->name }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $sop->code }} · {{ ucfirst($sop->status) }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                {{-- HANYA Admin, Manager, dan Officer yang bisa Edit --}}
                @if(auth()->check() && in_array(auth()->user()->role, ['super_admin', 'k3_manager', 'k3_officer']))
                <a href="{{ route('sops.edit', $sop) }}" class="btn-secondary">Edit</a>
                @endif
                <a href="{{ route('sops.index') }}" class="btn-secondary">Kembali</a>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2 mt-6">
            <div class="space-y-4">
                <div>
                    <h3 class="text-sm text-gray-500 dark:text-gray-400">Kategori</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $sop->category ?? 'Tidak ditentukan' }}</p>
                </div>
                <div>
                    <h3 class="text-sm text-gray-500 dark:text-gray-400">Tanggal Efektif</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $sop->effective_date->format('d F Y') }}</p>
                </div>
                <div>
                    <h3 class="text-sm text-gray-500 dark:text-gray-400">Dibuat oleh</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $sop->creator?->name ?? 'Unknown' }}</p>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <h3 class="text-sm text-gray-500 dark:text-gray-400">Deskripsi</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $sop->description ?? 'Tidak ada deskripsi.' }}</p>
                </div>
                @if($sop->file_url)
                <div>
                    <h3 class="text-sm text-gray-500 dark:text-gray-400 mb-2">File SOP</h3>
                    @if($isImage)
                    <img src="{{ $sop->file_url }}" alt="{{ $sop->name }}" class="max-w-full max-h-64 rounded-lg border border-gray-200 dark:border-gray-700 object-contain">
                    @endif
                    <div class="flex flex-wrap gap-3 mt-2">
                    @if($isPdf)
                        <a href="{{ route('sops.stream', $sop) }}" target="_blank" class="inline-flex items-center gap-2 text-blue-600 hover:underline font-medium">
                            Lihat PDF
                        </a>
                        @endif
                        <a href="{{ route('sops.download', $sop) }}" class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300">
                            Unduh File
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="mt-6 bg-gray-50 dark:bg-gray-900 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-3">Ringkasan SOP</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $sop->description ?? 'Tidak ada ringkasan yang tersedia.' }}</p>
        </div>
    </div>
</div>
@endsection