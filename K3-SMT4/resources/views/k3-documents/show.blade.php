@extends('layouts.app')

@section('title', 'Detail Dokumen K3')
@section('page-title', 'Detail Dokumen K3')
@section('page-subtitle', 'Informasi lengkap dokumen K3')

@section('content')
@php
    $fileExt = $k3Document->file_url
        ? strtolower(pathinfo(parse_url($k3Document->file_url, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION))
        : null;
    $isImage = in_array($fileExt, ['jpg', 'jpeg', 'png'], true);
    $isPdf = $fileExt === 'pdf';
@endphp
<div class="space-y-5">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $k3Document->title }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $k3Document->document_number }} · Revisi {{ $k3Document->revision }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                
                {{-- HANYA Admin, Manager, dan Officer yang bisa Edit --}}
                @if(auth()->check() && in_array(auth()->user()->role, ['super_admin', 'k3_manager', 'k3_officer']))
                <a href="{{ route('k3-documents.edit', $k3Document) }}" class="btn-secondary">Edit</a>
                @endif
                
                <a href="{{ route('k3-documents.index') }}" class="btn-secondary">Kembali</a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mt-6">
            <div class="space-y-3">
                <div>
                    <h3 class="text-sm text-gray-500 dark:text-gray-400">Kategori</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $k3Document->category }}</p>
                </div>
                <div>
                    <h3 class="text-sm text-gray-500 dark:text-gray-400">Tanggal Berlaku</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $k3Document->effective_date->format('d F Y') }}</p>
                </div>
                <div>
                    <h3 class="text-sm text-gray-500 dark:text-gray-400">Status</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ ucfirst($k3Document->status) }}</p>
                </div>
                <div>
                    <h3 class="text-sm text-gray-500 dark:text-gray-400">Visibilitas</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $k3Document->visibility === 'restricted' ? 'Terbatas' : 'Publik' }}</p>
                </div>
                <div>
                    <h3 class="text-sm text-gray-500 dark:text-gray-400">Diupload oleh</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $k3Document->uploader?->name ?? 'Unknown' }}</p>
                </div>
            </div>
            <div class="space-y-3">
                <div>
                    <h3 class="text-sm text-gray-500 dark:text-gray-400">Deskripsi</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $k3Document->description ?? 'Tidak ada deskripsi.' }}</p>
                </div>
                @if($k3Document->file_url)
                <div>
                    <h3 class="text-sm text-gray-500 dark:text-gray-400 mb-2">File</h3>
                    @if($isImage)
                    <img src="{{ $k3Document->file_url }}" alt="{{ $k3Document->title }}" class="max-w-full max-h-64 rounded-lg border border-gray-200 dark:border-gray-700 object-contain">
                    @endif
                    <div class="flex flex-wrap gap-3 mt-2">
                    @if($isPdf)
                        <a href="{{ route('k3-documents.stream', $k3Document) }}" target="_blank" class="inline-flex items-center gap-2 text-blue-600 hover:underline font-medium">
                            Lihat PDF
                        </a>
                        @endif
                        <a href="{{ route('k3-documents.download', $k3Document) }}" class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300">
                            Unduh File
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection