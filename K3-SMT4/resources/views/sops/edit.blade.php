@extends('layouts.app')

@section('title', 'Edit SOP')
@section('page-title', 'Edit SOP')
@section('page-subtitle', 'Perbarui data SOP yang sudah ada')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
        <form action="{{ route('sops.update', $sop) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="form-label">Kode SOP</label>
                <input type="text" name="code" value="{{ old('code', $sop->code) }}" class="form-input" required>
                @error('code') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Nama SOP</label>
                <input type="text" name="name" value="{{ old('name', $sop->name) }}" class="form-input" required>
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Kategori</label>
                    <input type="text" name="category" value="{{ old('category', $sop->category) }}" class="form-input">
                    @error('category') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Tanggal Efektif</label>
                    <input type="date" name="effective_date" value="{{ old('effective_date', $sop->effective_date->format('Y-m-d')) }}" class="form-input" required>
                    @error('effective_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="form-label">Status</label>
                <select name="status" class="form-input" required>
                    <option value="aktif" {{ old('status', $sop->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="revisi" {{ old('status', $sop->status) == 'revisi' ? 'selected' : '' }}>Revisi</option>
                    <option value="tidak_aktif" {{ old('status', $sop->status) == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
                @error('status') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Deskripsi</label>
                <textarea name="description" rows="4" class="form-input">{{ old('description', $sop->description) }}</textarea>
                @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Upload PDF Baru</label>
                <input type="file" name="file" accept="application/pdf" class="form-input">
                @error('file') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                @if($sop->file_url)
                <p class="text-xs text-gray-400 mt-2">File saat ini tersedia <a href="{{ $sop->file_url }}" target="_blank" class="text-blue-600 hover:underline">Lihat PDF</a>.</p>
                @endif
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('sops.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Perbarui SOP</button>
            </div>
        </form>
    </div>
</div>
@endsection
