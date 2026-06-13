@extends('layouts.app')
@section('title', 'Edit Karyawan')
@section('page-title', 'Edit Karyawan')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
        <form method="POST" action="{{ route('employees.update', $employee) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">NIK *</label>
                    <input type="text" name="nik" value="{{ old('nik', $employee->nik) }}" class="form-input" required>
                    @error('nik') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" name="name" value="{{ old('name', $employee->name) }}" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Jabatan *</label>
                    <input type="text" name="position" value="{{ old('position', $employee->position) }}" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Departemen *</label>
                    <select name="department_id" class="form-input" required>
                        @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id', $employee->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $employee->email) }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">Nomor HP</label>
                    <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">Tanggal Masuk *</label>
                    <input type="date" name="join_date" value="{{ old('join_date', $employee->join_date->format('Y-m-d')) }}" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-input" required>
                        <option value="aktif"       {{ old('status', $employee->status) === 'aktif'       ? 'selected' : '' }}>Aktif</option>
                        <option value="tidak_aktif" {{ old('status', $employee->status) === 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        <option value="cuti"        {{ old('status', $employee->status) === 'cuti'        ? 'selected' : '' }}>Cuti</option>
                        <option value="resign"      {{ old('status', $employee->status) === 'resign'      ? 'selected' : '' }}>Resign</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Foto Karyawan</label>
                    <input type="file" name="photo" accept="image/*" class="form-input">
                    @if($employee->photo_url)
                    <div class="mt-2 flex items-center gap-3">
                        <img src="{{ $employee->photo_url }}" class="w-12 h-12 rounded-full object-cover">
                        <span class="text-xs text-gray-500">Foto saat ini. Upload baru untuk mengganti.</span>
                    </div>
                    @endif
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('employees.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
