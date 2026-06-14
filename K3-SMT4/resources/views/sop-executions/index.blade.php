@extends('layouts.app')

@section('title', 'Pelaksanaan SOP')
@section('page-title', 'Pelaksanaan SOP')
@section('page-subtitle', 'Catat dan pantau pelaksanaan SOP')

@section('content')
<div class="space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Pelaksanaan SOP</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Daftar pelaksanaan SOP karyawan.</p>
        </div>
        <a href="{{ route('sop-executions.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
            Tambah Pelaksanaan
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                <thead class="bg-gray-100 dark:bg-gray-900 text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">SOP</th>
                        <th class="px-4 py-3">Karyawan</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($executions as $execution)
                    <tr>
                        <td class="px-4 py-3">{{ $execution->execution_date->format('d F Y') }}</td>
                        <td class="px-4 py-3">{{ $execution->sop->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $execution->employee->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ ucfirst(str_replace('_', ' ', $execution->status)) }}</td>
                        <td class="px-4 py-3 space-x-2">
                            <a href="{{ route('sop-executions.show', $execution) }}" class="text-blue-600 hover:underline">Lihat</a>
                        <a href="{{ route('sop-executions.edit', $execution) }}" class="text-orange-600 hover:underline">Edit</a>
                            <form action="{{ route('sop-executions.destroy', $execution) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus pelaksanaan SOP ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada pelaksanaan SOP tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($executions->hasPages())
        <div class="mt-4">{{ $executions->links() }}</div>
        @endif
    </div>
</div>
@endsection
