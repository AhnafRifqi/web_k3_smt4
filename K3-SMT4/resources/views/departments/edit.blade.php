@extends('layouts.app')

@section('title', 'Edit Departemen')
@section('page-title', 'Edit Departemen')
@section('page-subtitle', 'Perbarui informasi departemen')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
        <form action="{{ route('departments.update', $department) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="form-label">Nama Departemen</label>
                <input type="text" name="name" value="{{ old('name', $department->name) }}" class="form-input" required>
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Kode Departemen</label>
                <input type="text" name="code" value="{{ old('code', $department->code) }}" class="form-input" required>
                @error('code') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Deskripsi</label>
                <textarea name="description" rows="4" class="form-input">{{ old('description', $department->description) }}</textarea>
                @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Divisi (Opsional)</label>
                <select name="division_id" class="form-input">
                    <option value="">— Tanpa Divisi —</option>
                    @foreach($divisions as $division)
                    <option value="{{ $division->id }}" {{ old('division_id', $department->division_id) == $division->id ? 'selected' : '' }}>{{ $division->name }}</option>
                    @endforeach
                </select>
                @error('division_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('departments.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Perbarui Departemen</button>
            </div>
        </form>
    </div>
</div>
@endsection
