@extends('layouts.app')

@section('title', 'Daftar Pengguna')
@section('page-title', 'Pengguna')
@section('page-subtitle', 'Kelola akses dan peran pengguna')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Pengguna</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola akun dan hak akses.</p>
        </div>
        <a href="{{ route('users.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
            Tambah Pengguna
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
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Peran</th>
                    <th class="px-4 py-3">Validasi</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($users as $user)
                <tr>
                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3">{{ $user->name }}</td>
                    <td class="px-4 py-3">{{ $user->email }}</td>
                    <td class="px-4 py-3">{{ ucfirst(str_replace('_', ' ', $user->role ?? 'user')) }}</td>
                    <td class="px-4 py-3">
                        @if($user->role === 'karyawan')
                            @if($user->is_validated)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400">
                                    Sudah Validasi
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-400">
                                    Belum Validasi
                                </span>
                            @endif
                        @else
                            <span class="text-gray-400 dark:text-gray-500">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $user->is_active ? 'bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400' : 'bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-400' }}">
                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 space-x-2">
                        @if($user->role === 'karyawan' && !$user->is_validated)
                        <form action="{{ route('users.validate', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Validasi akun karyawan ini?');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-emerald-600 dark:text-emerald-400 hover:underline font-semibold">Validasi</button>
                        </form>
                        @endif
                        <a href="{{ route('users.edit', $user) }}" class="text-orange-600 hover:underline">Edit</a>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus pengguna ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-5 text-center text-gray-500">Belum ada pengguna.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
