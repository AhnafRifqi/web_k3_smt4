@extends('layouts.app')
@section('title', 'Form Monitoring')
@section('page-title', 'Form Monitoring')
@section('page-subtitle', 'Form monitoring dinamis untuk petugas K3')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
    <div>
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Daftar Form Monitoring</h2>
        <p class="text-sm text-gray-500 mt-0.5">{{ $forms->total() }} form ditemukan</p>
    </div>
    @if(auth()->user()->canManage())
    <a href="{{ route('monitoring-forms.create') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Buat Form
    </a>
    @endif
</div>

<form method="GET" class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700 mb-4 shadow-sm">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul form..."
            class="col-span-2 text-sm bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500">
        <select name="department_id" class="text-sm bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <option value="">Semua Departemen</option>
            @foreach($departments as $dept)
            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
            @endforeach
        </select>
        <div class="flex gap-2">
            <select name="is_active" class="flex-1 text-sm bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">Semua Status</option>
                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">Cari</button>
        </div>
    </div>
</form>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
    @forelse($forms as $form)
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
        <div class="p-5">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $form->title }}</p>
                    @if($form->department)
                    <p class="text-xs text-gray-500 mt-0.5">{{ $form->department->name }}</p>
                    @endif
                </div>
                <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                    {{ $form->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                    {{ $form->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>

            @if($form->description)
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3 line-clamp-2">{{ $form->description }}</p>
            @endif

            <div class="flex items-center gap-3 text-xs text-gray-400 mb-4">
                <span>{{ $form->fields_count ?? $form->fields->count() }} field</span>
                <span>{{ $form->assignments_count }} penugasan</span>
                <span>{{ $form->submissions_count }} submit</span>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-xs text-gray-400">Oleh {{ $form->creator?->name ?? '-' }}</span>
                <a href="{{ route('monitoring-forms.show', $form) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors dark:bg-blue-900/20 dark:text-blue-400">
                    Detail
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-12 text-gray-400">
        <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <p>Belum ada form monitoring.</p>
    </div>
    @endforelse
</div>

@if($forms->hasPages())
<div class="mt-6">{{ $forms->links() }}</div>
@endif
@endsection
