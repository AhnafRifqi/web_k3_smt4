@extends('layouts.app')

@section('title', 'Daftar SOP')
@section('page-title', 'SOP')
@section('page-subtitle', 'Kelola Standar Operasional Prosedur')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar SOP</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola semua SOP perusahaan.</p>
        </div>
        
        @if(auth()->check() && in_array(auth()->user()->role, ['super_admin', 'k3_manager', 'k3_officer']))
        <a href="{{ route('sops.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
            Tambah SOP
        </a>
        @endif
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 rounded-lg bg-green-50 text-green-800 border border-green-200">{{ session('success') }}</div>
    @endif
    
    @if(session('error'))
    <div class="mb-4 p-4 rounded-lg bg-red-50 text-red-800 border border-red-200">{{ session('error') }}</div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
            <thead class="bg-gray-100 dark:bg-gray-900 text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Judul SOP</th>
                    <th class="px-4 py-3">Kategori</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($sops as $sop)
                <tr>
                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $sop->name }}</td>
                    <td class="px-4 py-3">{{ $sop->category ?? '-' }}</td>
                    <td class="px-4 py-3">
                        {{-- Logika warna & tulisan status --}}
                        @if(strtolower($sop->status) === 'aktif')
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Aktif</span>
                        @elseif(strtolower($sop->status) === 'tidak_aktif')
                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Draft / Menunggu Persetujuan</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">{{ ucfirst($sop->status) }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 space-x-2">
                        
                        <a href="{{ route('sops.show', $sop) }}" class="text-blue-600 hover:underline">Lihat</a>
                        
                        {{-- Tombol Approve & Revisi KHUSUS Manager/Admin saat Draft --}}
                        @if(auth()->check() && in_array(auth()->user()->role, ['super_admin', 'k3_manager']) && strtolower($sop->status) === 'tidak_aktif')
                        
                        <form action="{{ route('sops.approve-status', $sop) }}" method="POST" class="inline-block" onsubmit="return confirm('Setujui SOP ini menjadi Aktif?');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-green-600 hover:underline font-medium">Approve</button>
                        </form>

                        <form action="{{ route('sops.reject-status', $sop) }}" method="POST" class="inline-block ml-1" onsubmit="return confirm('Tolak SOP ini dan minta direvisi?');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-red-500 hover:underline font-medium">Revisi</button>
                        </form>
                        
                        @endif

                        @if(auth()->check() && in_array(auth()->user()->role, ['super_admin', 'k3_manager', 'k3_officer']))
                        <a href="{{ route('sops.edit', $sop) }}" class="text-orange-600 hover:underline ml-1">Edit</a>
                        <form action="{{ route('sops.destroy', $sop) }}" method="POST" class="inline-block ml-1" onsubmit="return confirm('Hapus SOP ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                        @endif

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-5 text-center text-gray-500">Belum ada SOP.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($sops->hasPages())
    <div class="mt-4">
        {{ $sops->links() }}
    </div>
    @endif
</div>
@endsection