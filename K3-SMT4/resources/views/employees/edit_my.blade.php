@extends('layouts.app')
@section('title', isset($employee) ? 'Edit Profil Karyawan' : 'Lengkapi Profil Karyawan')
@section('page-title', isset($employee) ? 'Edit Profil Karyawan Saya' : 'Lengkapi Profil Karyawan Baru')
@section('page-subtitle', 'Isi data diri Anda secara lengkap dan akurat')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700/50 shadow-sm p-6">
        <form method="POST" action="{{ isset($employee) ? route('my-employee.update') : route('my-employee.store') }}" enctype="multipart/form-data">
            @csrf
            @if(isset($employee)) @method('PUT') @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="form-label">NIK <span class="text-red-500">*</span></label>
                    <input type="text" name="nik" value="{{ old('nik', $employee->nik ?? '') }}" class="form-input" required placeholder="Contoh: JNE-001">
                    @error('nik') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $employee->name ?? auth()->user()->name) }}" class="form-input" required placeholder="Nama Lengkap Anda">
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Jabatan <span class="text-red-500">*</span></label>
                    <input type="text" name="position" value="{{ old('position', $employee->position ?? '') }}" class="form-input" required placeholder="Staf Gudang, Kurir, Operator, dll">
                    @error('position') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Departemen <span class="text-red-500">*</span></label>
                    <select name="department_id" class="form-input" required>
                        <option value="">Pilih Departemen</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id', $employee->department_id ?? '') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Nomor HP</label>
                    <input type="text" name="phone" value="{{ old('phone', $employee->phone ?? '') }}" class="form-input" placeholder="08xxxxxxxxxx">
                    @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Tanggal Masuk <span class="text-red-500">*</span></label>
                    <input type="date" name="join_date" value="{{ old('join_date', isset($employee) ? $employee->join_date->format('Y-m-d') : '') }}" class="form-input" required>
                    @error('join_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="form-label">Foto Profil Karyawan</label>
                    <input type="file" name="photo" accept="image/*" class="form-input">
                    @if(isset($employee) && $employee->photo_url)
                    <div class="mt-3 flex items-center gap-3">
                        <img src="{{ $employee->photo_url }}" class="w-16 h-16 rounded-2xl object-cover shadow-sm border border-gray-100">
                        <span class="text-xs text-gray-500">Foto profil saat ini. Unggah baru untuk mengganti.</span>
                    </div>
                    @endif
                    <p class="text-[10px] text-gray-400 mt-1">Maksimal 2 MB. Format file: JPG, PNG, WEBP</p>
                    @error('photo') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

            </div>

            <div class="flex items-center justify-end gap-3 mt-8 pt-5 border-t border-gray-100 dark:border-gray-700/50">
                @if(isset($employee))
                <a href="{{ route('my-employee') }}" class="btn-secondary">Batal</a>
                @else
                <a href="{{ route('dashboard') }}" class="btn-secondary">Kembali ke Dashboard</a>
                @endif
                <button type="submit" class="btn-primary">
                    {{ isset($employee) ? 'Simpan Perubahan' : 'Simpan Profil Saya' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
