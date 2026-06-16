@extends('layouts.app')

@section('title', 'Edit Pengguna')
@section('page-title', 'Edit Pengguna')
@section('page-subtitle', 'Perbarui informasi akun pengguna')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
        <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="form-label">Nama</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input" required>
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required>
                @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-input">
                    @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-input">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="form-label">Peran</label>
                    <select name="role" class="form-input" required>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="supervisor_k3" {{ old('role', $user->role) == 'supervisor_k3' ? 'selected' : '' }}>Supervisor K3</option>
                        <option value="auditor" {{ old('role', $user->role) == 'auditor' ? 'selected' : '' }}>Auditor</option>
                        <option value="karyawan" {{ old('role', $user->role) == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                    </select>
                    @error('role') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Status Aktif</label>
                    <select name="is_active" class="form-input">
                        <option value="1" {{ old('is_active', $user->is_active) ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ !old('is_active', $user->is_active) ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Status Validasi</label>
                    <select name="is_validated" class="form-input">
                        <option value="1" {{ old('is_validated', $user->is_validated) ? 'selected' : '' }}>Sudah Validasi</option>
                        <option value="0" {{ !old('is_validated', $user->is_validated) ? 'selected' : '' }}>Belum Validasi</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('users.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Perbarui Pengguna</button>
            </div>
        </form>
    </div>
</div>
@endsection
