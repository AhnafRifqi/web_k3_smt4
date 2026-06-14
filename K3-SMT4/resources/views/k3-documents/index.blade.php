@extends('layouts.app')

@section('title', 'Daftar Dokumen K3')
@section('page-title', 'Dokumen K3')
@section('page-subtitle', 'Kelola dokumen K3 perusahaan')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Dokumen K3</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Semua dokumen K3 yang tersimpan di sistem.</p>
        </div>
        <a href="{{ route('k3-documents.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
            Tambah Dokumen
        </a>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 rounded-lg bg-green-50 text-green-800 border border-green-200">{{ session('success') }}</div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
            <thead class="bg-gray-100 dark:bg-gray-900 text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Judul</th>
                    <th class="px-4 py-3">Tipe</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($documents as $document)
                <tr>
                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3">{{ $document->title }}</td>
                    <td class="px-4 py-3">{{ $document->type }}</td>
                    <td class="px-4 py-3">{{ $document->created_at->translatedFormat('d F Y') }}</td>
                    <td class="px-4 py-3 space-x-2">
                        <a href="{{ route('k3-documents.show', $document) }}" class="text-blue-600 hover:underline">Lihat</a>
                        <a href="{{ route('k3-documents.edit', $document) }}" class="text-orange-600 hover:underline">Edit</a>
                        <form action="{{ route('k3-documents.destroy', $document) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus dokumen ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-5 text-center text-gray-500">Belum ada dokumen K3.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
