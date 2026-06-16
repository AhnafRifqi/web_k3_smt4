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
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Pengguna
        </a>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 rounded-lg bg-green-50 text-green-800 border border-green-200">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="mb-4 p-4 rounded-lg bg-red-50 text-red-800 border border-red-200">{{ session('error') }}</div>
    @endif

    {{-- Filters --}}
    <form method="GET" class="mb-4">
        <div class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
                class="w-full max-w-xs text-sm bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <select name="role" class="text-sm bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">Semua Role</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="supervisor_k3" {{ request('role') === 'supervisor_k3' ? 'selected' : '' }}>Supervisor K3</option>
                <option value="auditor" {{ request('role') === 'auditor' ? 'selected' : '' }}>Auditor</option>
                <option value="karyawan" {{ request('role') === 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                <option value="pending" {{ request('role') === 'pending' ? 'selected' : '' }}>Pending</option>
            </select>
            <button type="submit" class="px-3 py-2 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Filter</button>
            @if(request()->hasAny(['search', 'role']))
            <a href="{{ route('users.index') }}" class="px-3 py-2 text-xs text-gray-500 bg-gray-100 rounded-lg hover:bg-gray-200">Reset</a>
            @endif
        </div>
    </form>

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
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30">
                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            @if($user->avatar_url)
                            <img src="{{ $user->avatar_url }}" class="w-7 h-7 rounded-full object-cover">
                            @else
                            <div class="w-7 h-7 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 text-xs font-bold">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            @endif
                            <span class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-xs">{{ $user->email }}</td>
                    <td class="px-4 py-3">
                        @php
                            $roleColors = [
                                'admin' => 'bg-purple-50 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                                'supervisor_k3' => 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                'auditor' => 'bg-orange-50 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                'karyawan' => 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                'pending' => 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                            ];
                            $colorClass = $roleColors[$user->role] ?? 'bg-gray-50 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $colorClass }}">
                            {{ $user->role_label }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @if($user->role === 'pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-400">
                                Pending
                            </span>
                        @elseif($user->is_validated)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400">
                                Sudah Validasi
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-400">
                                Belum Validasi
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($user->isImmutableAdmin())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 dark:bg-purple-950/30 dark:text-purple-400">
                                System
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $user->is_active ? 'bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400' : 'bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-400' }}">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-1 flex-nowrap">
                            @if(in_array($user->role, ['pending', 'karyawan']) && !$user->is_validated)
                            <form action="{{ route('users.validate', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Validasi akun {{ $user->role === 'pending' ? 'pending' : 'karyawan' }} ini?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-2 py-1 text-xs font-medium text-emerald-600 bg-emerald-50 rounded-lg hover:bg-emerald-100 dark:text-emerald-400 dark:bg-emerald-900/20 dark:hover:bg-emerald-900/40">
                                    Validasi
                                </button>
                            </form>
                            @endif

                            <a href="{{ route('users.show', $user) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg dark:hover:text-blue-400 dark:hover:bg-blue-900/20" title="Detail">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>

                            <a href="{{ route('users.edit', $user) }}" class="p-1.5 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg dark:hover:text-yellow-400 dark:hover:bg-yellow-900/20" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>

                            @if(!$user->isImmutableAdmin())
                            <form action="{{ route('users.toggle-active', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} user {{ $user->name }}?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg dark:hover:text-orange-400 dark:hover:bg-orange-900/20" title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($user->is_active)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        @endif
                                    </svg>
                                </button>
                            </form>

                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus pengguna {{ $user->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg dark:hover:text-red-400 dark:hover:bg-red-900/20" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-5 text-center text-gray-500">Belum ada pengguna.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection