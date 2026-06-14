@extends('layouts.app')

@section('title', 'Tambah Temuan Audit')
@section('page-title', 'Tambah Temuan Audit')
@section('page-subtitle', 'Catat temuan audit untuk audit terkait')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
    <form action="{{ route('audit-findings.store') }}" method="POST" class="space-y-6">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Audit</label>
            <select name="audit_id" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                <option value="">Pilih audit</option>
                @foreach($audits as $audit)
                <option value="{{ $audit->id }}" {{ old('audit_id', $selectedAuditId) == $audit->id ? 'selected' : '' }}>
                    {{ $audit->name }} - {{ $audit->date->translatedFormat('d F Y') }}
                </option>
                @endforeach
            </select>
            @error('audit_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">No. Temuan</label>
            <input type="text" name="finding_number" value="{{ old('finding_number') }}" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
            @error('finding_number') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi Temuan</label>
            <textarea name="description" rows="4" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('description') }}</textarea>
            @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tingkat</label>
                <select name="severity" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="minor" {{ old('severity') == 'minor' ? 'selected' : '' }}>Minor</option>
                    <option value="major" {{ old('severity') == 'major' ? 'selected' : '' }}>Major</option>
                    <option value="critical" {{ old('severity') == 'critical' ? 'selected' : '' }}>Critical</option>
                </select>
                @error('severity') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select name="status" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="open" {{ old('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
                @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Simpan Temuan</button>
            <a href="{{ url()->previous() }}" class="text-sm text-gray-600 hover:text-gray-900">Batal</a>
        </div>
    </form>
</div>
@endsection
