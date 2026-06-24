@extends('layouts.app')

@section('title', 'Daftar SOP')
@section('page-title', 'SOP')
@section('page-subtitle', 'Kelola Standar Operasional Prosedur')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">

    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                Daftar SOP
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Kelola semua SOP perusahaan.
            </p>
        </div>

        <a href="{{ route('sops.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
            Tambah SOP
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
            <thead class="bg-gray-100 dark:bg-gray-900 text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Kode SOP</th>
                    <th class="px-4 py-3">Nama SOP</th>
                    <th class="px-4 py-3">Kategori</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Tanggal Efektif</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>

            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($sops as $sop)
                    <tr>
                        <td class="px-4 py-3">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-4 py-3 font-medium">
                            {{ $sop->code }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $sop->name }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $sop->category ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            @if($sop->status == 'aktif')
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                    Aktif
                                </span>
                            @elseif($sop->status == 'revisi')
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
                                    Revisi
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">
                                    Tidak Aktif
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            {{ $sop->effective_date ? \Carbon\Carbon::parse($sop->effective_date)->format('d M Y') : '-' }}
                        </td>

                        <td class="px-4 py-3 whitespace-nowrap">
                            <a href="{{ route('sops.show', $sop) }}"
                                class="text-blue-600 hover:underline mr-3">
                                Lihat
                            </a>

                            <a href="{{ route('sops.edit', $sop) }}"
                                class="text-orange-600 hover:underline mr-3">
                                Edit
                            </a>

                            <form action="{{ route('sops.destroy', $sop) }}"
                                method="POST"
                                class="inline-block"
                                onsubmit="return confirm('Hapus SOP ini?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="text-red-600 hover:underline">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7"
                            class="px-4 py-6 text-center text-gray-500">
                            Belum ada data SOP.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection