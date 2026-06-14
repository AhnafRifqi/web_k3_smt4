@extends('layouts.app')

@section('title', 'Tambah Departemen')
@section('page-title', 'Tambah Departemen')
@section('page-subtitle', 'Buat departemen baru untuk organisasi')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
        <form action="{{ route('departments.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="form-label">Nama Departemen</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-input" required>
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Kode Departemen</label>
                <input type="text" name="code" value="{{ old('code') }}" class="form-input" required>
                @error('code') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Deskripsi</label>
                <textarea name="description" rows="4" class="form-input">{{ old('description') }}</textarea>
                @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('departments.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Simpan Departemen</button>
            </div>
        </form>
    </div>
</div>
@endsection
