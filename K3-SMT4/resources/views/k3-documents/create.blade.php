@extends('layouts.app')

@section('title', 'Tambah Dokumen K3')
@section('page-title', 'Tambah Dokumen K3')
@section('page-subtitle', 'Tambah dokumen standar K3 baru ke sistem')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
        <form action="{{ route('k3-documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="form-label">Judul Dokumen</label>
                <input type="text" name="title" value="{{ old('title') }}" class="form-input" required>
                @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Nomor Dokumen</label>
                    <input type="text" name="document_number" value="{{ old('document_number') }}" class="form-input" required>
                    @error('document_number') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Revisi</label>
                    <input type="text" name="revision" value="{{ old('revision') }}" class="form-input" required>
                    @error('revision') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Kategori</label>
                    <input type="text" name="category" value="{{ old('category') }}" class="form-input" required>
                    @error('category') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Tanggal Berlaku</label>
                    <input type="date" name="effective_date" value="{{ old('effective_date') }}" class="form-input" required>
                    @error('effective_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="form-label">Status</label>
                <select name="status" class="form-input" required>
                    <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="obsolete" {{ old('status') == 'obsolete' ? 'selected' : '' }}>Usang</option>
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
                @error('status') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Deskripsi</label>
                <textarea name="description" rows="4" class="form-input">{{ old('description') }}</textarea>
                @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">File PDF</label>
                <input type="file" name="file" accept="application/pdf" class="form-input">
                @error('file') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-500 mt-1">Maksimal 20 MB. Format PDF.</p>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('k3-documents.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Simpan Dokumen</button>
            </div>
        </form>
    </div>
</div>
@endsection
