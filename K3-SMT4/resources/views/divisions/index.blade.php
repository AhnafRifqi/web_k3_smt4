@extends('layouts.app')

@section('title', 'Daftar Divisi')
@section('page-title', 'Divisi')
@section('page-subtitle', 'Kelola divisi / business unit organisasi')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Divisi</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Institusi → Divisi → Departemen → Unit Kerja</p>
        </div>
        <a href="{{ route('divisions.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
            Tambah Divisi
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
            <thead class="bg-gray-100 dark:bg-gray-900 text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3">Kode</th>
                    <th class="px-4 py-3">Nama Divisi</th>
                    <th class="px-4 py-3">Departemen</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($divisions as $division)
                <tr>
                    <td class="px-4 py-3 font-mono text-xs">{{ $division->code }}</td>
                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $division->name }}</td>
                    <td class="px-4 py-3">{{ $division->departments_count }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $division->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $division->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 space-x-2">
                        <a href="{{ route('divisions.edit', $division) }}" class="text-orange-600 hover:underline">Edit</a>
                        <form action="{{ route('divisions.destroy', $division) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus divisi ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-5 text-center text-gray-500">Belum ada divisi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($divisions->hasPages())
    <div class="mt-4">{{ $divisions->links() }}</div>
    @endif
</div>
@endsection
