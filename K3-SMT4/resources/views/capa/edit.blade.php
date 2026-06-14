@extends('layouts.app')
@section('title', 'Edit CAPA')
@section('page-title', 'Edit CAPA')
@section('page-subtitle', 'Perbarui data CAPA')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
        <form method="POST" action="{{ route('capa.update', $capa) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="form-label">No. CAPA</label>
                    <input type="text" name="capa_number" value="{{ old('capa_number', $capa->capa_number) }}" class="form-input" required>
                    @error('capa_number') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Audit Terkait</label>
                    <select name="audit_id" class="form-input">
                        <option value="">Pilih audit</option>
                        @foreach($audits as $audit)
                        <option value="{{ $audit->id }}" {{ old('audit_id', $capa->audit_id) == $audit->id ? 'selected' : '' }}>{{ $audit->audit_number }} - {{ $audit->area }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Temuan Audit</label>
                    <select name="finding_id" class="form-input">
                        <option value="">Pilih temuan</option>
                        @foreach($findings as $finding)
                        <option value="{{ $finding->id }}" {{ old('finding_id', $capa->finding_id) == $finding->id ? 'selected' : '' }}>{{ $finding->finding_number }} - {{ Str::limit($finding->description, 50) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" rows="3" class="form-input" required>{{ old('description', $capa->description) }}</textarea>
                    @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">PIC</label>
                    <select name="pic_id" class="form-input">
                        <option value="">Pilih PIC</option>
                        @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('pic_id', $capa->pic_id) == $employee->id ? 'selected' : '' }}>{{ $employee->name }} ({{ $employee->position }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Tanggal Target</label>
                    <input type="date" name="target_date" value="{{ old('target_date', $capa->target_date->format('Y-m-d')) }}" class="form-input" required>
                    @error('target_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input" required>
                        <option value="open" {{ old('status', $capa->status) === 'open' ? 'selected' : '' }}>Open</option>
                        <option value="in_progress" {{ old('status', $capa->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="closed" {{ old('status', $capa->status) === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                    @error('status') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Catatan Verifikasi</label>
                    <textarea name="verification_notes" rows="3" class="form-input">{{ old('verification_notes', $capa->verification_notes) }}</textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <a href="{{ route('capa.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
