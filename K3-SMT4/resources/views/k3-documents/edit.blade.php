@extends('layouts.app')

@section('title', 'Edit Dokumen K3')
@section('page-title', 'Edit Dokumen K3')
@section('page-subtitle', 'Perbarui informasi dokumen K3')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
        <form action="{{ route('k3-documents.update', $k3Document) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="form-label">Judul Dokumen</label>
                <input type="text" name="title" value="{{ old('title', $k3Document->title) }}" class="form-input" required>
                @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Nomor Dokumen</label>
                    <input type="text" name="document_number" value="{{ old('document_number', $k3Document->document_number) }}" class="form-input" required>
                    @error('document_number') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Revisi</label>
                    <input type="text" name="revision" value="{{ old('revision', $k3Document->revision) }}" class="form-input" required>
                    @error('revision') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Kategori</label>
                    <input type="text" name="category" value="{{ old('category', $k3Document->category) }}" class="form-input" required>
                    @error('category') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Tanggal Berlaku</label>
                    <input type="date" name="effective_date" value="{{ old('effective_date', $k3Document->effective_date->format('Y-m-d')) }}" class="form-input" required>
                    @error('effective_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="form-label">Status</label>
                <select name="status" class="form-input" required>
                    <option value="aktif" {{ old('status', $k3Document->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="obsolete" {{ old('status', $k3Document->status) == 'obsolete' ? 'selected' : '' }}>Usang</option>
                    <option value="draft" {{ old('status', $k3Document->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
                @error('status') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Deskripsi</label>
                <textarea name="description" rows="4" class="form-input">{{ old('description', $k3Document->description) }}</textarea>
                @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Upload PDF Baru</label>
                <input type="file" name="file" accept="application/pdf" class="form-input">
                @error('file') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                @if($k3Document->file_url)
                <p class="text-xs text-gray-400 mt-2">File saat ini tersedia <a href="{{ $k3Document->file_url }}" class="text-blue-600 hover:underline">Lihat PDF</a>.</p>
                @endif
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('k3-documents.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Perbarui Dokumen</button>
            </div>
        </form>
    </div>
</div>
@endsection
