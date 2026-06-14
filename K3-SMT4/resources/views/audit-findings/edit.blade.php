@extends('layouts.app')

@section('title', 'Edit Temuan Audit')
@section('page-title', 'Edit Temuan Audit')
@section('page-subtitle', 'Perbarui detail temuan audit')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
        <form action="{{ route('audit-findings.update', $auditFinding) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="form-label">Audit</label>
                <select name="audit_id" class="form-input" disabled>
                    <option value="{{ $auditFinding->audit_id }}">{{ $auditFinding->audit?->name ?? 'Tidak diketahui' }}</option>
                </select>
            </div>

            <div>
                <label class="form-label">No. Temuan</label>
                <input type="text" value="{{ $auditFinding->finding_number }}" class="form-input" disabled>
            </div>

            <div>
                <label class="form-label">Deskripsi</label>
                <textarea class="form-input" rows="5" disabled>{{ $auditFinding->description }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Tingkat</label>
                    <select name="severity" class="form-input" required>
                        <option value="minor" {{ old('severity', $auditFinding->severity) == 'minor' ? 'selected' : '' }}>Minor</option>
                        <option value="major" {{ old('severity', $auditFinding->severity) == 'major' ? 'selected' : '' }}>Major</option>
                        <option value="critical" {{ old('severity', $auditFinding->severity) == 'critical' ? 'selected' : '' }}>Critical</option>
                    </select>
                    @error('severity') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input" required>
                        <option value="open" {{ old('status', $auditFinding->status) == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="in_progress" {{ old('status', $auditFinding->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="closed" {{ old('status', $auditFinding->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                    @error('status') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="form-label">Area</label>
                <input type="text" name="area" value="{{ old('area', $auditFinding->area) }}" class="form-input">
                @error('area') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Referensi Standar</label>
                <input type="text" name="standard_ref" value="{{ old('standard_ref', $auditFinding->standard_ref) }}" class="form-input">
                @error('standard_ref') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Rekomendasi</label>
                <textarea name="recommendation" rows="4" class="form-input">{{ old('recommendation', $auditFinding->recommendation) }}</textarea>
                @error('recommendation') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('audit-findings.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
