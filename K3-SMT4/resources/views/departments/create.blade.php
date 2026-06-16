@extends('layouts.app')

@section('title', 'Tambah Departemen')
@section('page-title', 'Tambah Departemen')
@section('page-subtitle', 'Buat departemen baru untuk organisasi')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-8">
        <form action="{{ route('departments.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Nama Departemen --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Nama Departemen</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50 pl-11 pr-4 py-3 text-gray-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500 outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                        placeholder="Nama departemen">
                </div>
                @error('name') <p class="text-xs text-red-500 mt-1.5 ml-1">{{ $message }}</p> @enderror
            </div>

            {{-- Kode Departemen --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Kode Departemen</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                    </div>
                    <input type="text" name="code" value="{{ old('code') }}" required
                        class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50 pl-11 pr-4 py-3 text-gray-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500 outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                        placeholder="Contoh: HRD, IT, OPS">
                </div>
                @error('code') <p class="text-xs text-red-500 mt-1.5 ml-1">{{ $message }}</p> @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Deskripsi</label>
                <div class="relative">
                    <div class="absolute top-3 left-4 flex items-start pointer-events-none pt-0.5">
                        <svg class="w-5 h-5 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                    </div>
                    <textarea name="description" rows="4"
                        class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50 pl-11 pr-4 py-3 text-gray-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500 outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                        placeholder="Deskripsi departemen (opsional)">{{ old('description') }}</textarea>
                </div>
                @error('description') <p class="text-xs text-red-500 mt-1.5 ml-1">{{ $message }}</p> @enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100 dark:border-slate-700">
                <a href="{{ route('departments.index') }}"
                    class="inline-flex items-center gap-2 px-5 py-3 text-sm font-medium text-gray-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-500 shadow-lg shadow-blue-500/25 transition-all transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Simpan Departemen
                </button>
            </div>
        </form>
    </div>
</div>
@endsection