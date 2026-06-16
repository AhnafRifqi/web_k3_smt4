@extends('layouts.app')

@section('title', 'Edit Divisi')
@section('page-title', 'Edit Divisi')
@section('page-subtitle', 'Perbarui informasi divisi')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-8">
        <form action="{{ route('divisions.update', $division) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Nama Divisi</label>
                <input type="text" name="name" value="{{ old('name', $division->name) }}" required
                    class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50 px-4 py-3 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Kode Divisi</label>
                <input type="text" name="code" value="{{ old('code', $division->code) }}" required
                    class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50 px-4 py-3 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                @error('code') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Deskripsi</label>
                <textarea name="description" rows="4"
                    class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50 px-4 py-3 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">{{ old('description', $division->description) }}</textarea>
            </div>

            <div>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $division->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded text-blue-600">
                    <span class="text-sm font-medium text-gray-700 dark:text-slate-300">Divisi Aktif</span>
                </label>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 dark:border-slate-700">
                <a href="{{ route('divisions.index') }}" class="px-5 py-3 text-sm font-medium text-gray-700 border border-gray-300 rounded-xl hover:bg-gray-50">Batal</a>
                <button type="submit" class="px-6 py-3 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-500">Perbarui Divisi</button>
            </div>
        </form>
    </div>
</div>
@endsection
