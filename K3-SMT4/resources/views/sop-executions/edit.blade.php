@extends('layouts.app')

@section('title', 'Edit Pelaksanaan SOP')
@section('page-title', 'Edit Pelaksanaan SOP')
@section('page-subtitle', 'Perbarui data pelaksanaan SOP')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
        <form action="{{ route('sop-executions.update', $sopExecution) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Tanggal Pelaksanaan</label>
                    <input type="date" name="execution_date" value="{{ old('execution_date', $sopExecution->execution_date->format('Y-m-d')) }}" class="form-input" required>
                    @error('execution_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">SOP</label>
                    <select name="sop_id" class="form-input" required>
                        <option value="">Pilih SOP</option>
                        @foreach($sops as $sop)
                        <option value="{{ $sop->id }}" {{ old('sop_id', $sopExecution->sop_id) == $sop->id ? 'selected' : '' }}>{{ $sop->name }}</option>
                        @endforeach
                    </select>
                    @error('sop_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Karyawan</label>
                    <select name="employee_id" class="form-input" required>
                        <option value="">Pilih Karyawan</option>
                        @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('employee_id', $sopExecution->employee_id) == $employee->id ? 'selected' : '' }}>{{ $employee->name }} ({{ $employee->position }})</option>
                        @endforeach
                    </select>
                    @error('employee_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input" required>
                        <option value="sesuai" {{ old('status', $sopExecution->status) == 'sesuai' ? 'selected' : '' }}>Sesuai</option>
                        <option value="tidak_sesuai" {{ old('status', $sopExecution->status) == 'tidak_sesuai' ? 'selected' : '' }}>Tidak Sesuai</option>
                        <option value="perlu_perbaikan" {{ old('status', $sopExecution->status) == 'perlu_perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                    </select>
                    @error('status') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="form-label">Catatan</label>
                <textarea name="notes" rows="4" class="form-input">{{ old('notes', $sopExecution->notes) }}</textarea>
                @error('notes') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('sop-executions.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
