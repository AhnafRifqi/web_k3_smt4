@extends('layouts.app')

@section('title', 'Daftar Departemen')
@section('page-title', 'Departemen')
@section('page-subtitle', 'Kelola struktur departemen perusahaan')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Departemen</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola semua departemen.</p>
        </div>
        <a href="{{ route('departments.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
            Tambah Departemen
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
                    <th class="px-4 py-3">Nama Departemen</th>
                    <th class="px-4 py-3">Deskripsi</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($departments as $department)
                <tr>
                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3">{{ $department->name }}</td>
                    <td class="px-4 py-3">{{ $department->description ?? '-' }}</td>
                    <td class="px-4 py-3 space-x-2">
                        <a href="{{ route('departments.edit', $department) }}" class="text-orange-600 hover:underline">Edit</a>
                        <form action="{{ route('departments.destroy', $department) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus departemen ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-5 text-center text-gray-500">Belum ada departemen.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
