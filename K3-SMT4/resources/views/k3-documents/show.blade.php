@extends('layouts.app')

@section('title', 'Detail Dokumen K3')
@section('page-title', 'Detail Dokumen K3')
@section('page-subtitle', 'Informasi lengkap dokumen K3')

@section('content')
<div class="space-y-5">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $k3Document->title }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $k3Document->document_number }} · Revisi {{ $k3Document->revision }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('k3-documents.edit', $k3Document) }}" class="btn-secondary">Edit</a>
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
                    <h3 class="text-sm text-gray-500 dark:text-gray-400">File</h3>
                    <a href="{{ $k3Document->file_url }}" target="_blank" class="inline-flex items-center gap-2 text-blue-600 hover:underline">
                        Buka PDF
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
