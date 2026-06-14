@extends('layouts.app')

@section('title', 'Tambah SOP')
@section('page-title', 'Tambah SOP')
@section('page-subtitle', 'Unggah SOP baru dan atur detailnya')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
        <form action="{{ route('sops.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="form-label">Kode SOP</label>
                <input type="text" name="code" value="{{ old('code') }}" class="form-input" required>
                @error('code') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Nama SOP</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-input" required>
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Kategori</label>
                    <input type="text" name="category" value="{{ old('category') }}" class="form-input">
                    @error('category') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Tanggal Efektif</label>
                    <input type="date" name="effective_date" value="{{ old('effective_date') }}" class="form-input" required>
                    @error('effective_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="form-label">Status</label>
                <select name="status" class="form-input" required>
                    <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="revisi" {{ old('status') == 'revisi' ? 'selected' : '' }}>Revisi</option>
                    <option value="tidak_aktif" {{ old('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
                @error('status') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Deskripsi</label>
                <textarea name="description" rows="4" class="form-input">{{ old('description') }}</textarea>
                @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Upload File PDF</label>
                <input type="file" name="file" accept="application/pdf" class="form-input">
                @error('file') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-500 mt-1">Maks. 10 MB. Format PDF.</p>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('sops.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Simpan SOP</button>
            </div>
        </form>
    </div>
</div>
@endsection
