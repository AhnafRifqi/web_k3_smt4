@extends('layouts.app')
@section('title', isset($audit) ? 'Edit Audit' : 'Buat Audit')
@section('page-title', isset($audit) ? 'Edit Audit' : 'Buat Audit Baru')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
        <form method="POST" action="{{ isset($audit) ? route('audits.update', $audit) : route('audits.store') }}">
            @csrf
            @if(isset($audit)) @method('PUT') @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="form-label">No. Audit <span class="text-red-500">*</span></label>
                    <input type="text" name="audit_number" value="{{ old('audit_number', $audit->audit_number ?? '') }}"
                        placeholder="AUD-INT-2024-001" class="form-input" required>
                    @error('audit_number') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Tipe Audit <span class="text-red-500">*</span></label>
                    <select name="type" class="form-input" required>
                        <option value="">Pilih Tipe</option>
                        <option value="internal" {{ old('type', $audit->type ?? '') === 'internal' ? 'selected' : '' }}>Audit Internal</option>
                        <option value="eksternal" {{ old('type', $audit->type ?? '') === 'eksternal' ? 'selected' : '' }}>Audit Eksternal</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Tanggal Mulai <span class="text-red-500">*</span></label>
                    <input type="date" name="audit_date" value="{{ old('audit_date', isset($audit) ? $audit->audit_date->format('Y-m-d') : '') }}"
                        class="form-input" required>
                </div>

                <div>
                    <label class="form-label">Tanggal Selesai</label>
                    <input type="date" name="audit_date_end" value="{{ old('audit_date_end', isset($audit) && $audit->audit_date_end ? $audit->audit_date_end->format('Y-m-d') : '') }}"
                        class="form-input">
                </div>

                <div>
                    <label class="form-label">Nama Auditor <span class="text-red-500">*</span></label>
                    <input type="text" name="auditor_name" value="{{ old('auditor_name', $audit->auditor_name ?? '') }}"
                        class="form-input" required>
                </div>

                <div>
                    <label class="form-label">Lembaga Audit <span class="text-xs text-gray-400">(Eksternal)</span></label>
                    <input type="text" name="audit_agency" value="{{ old('audit_agency', $audit->audit_agency ?? '') }}"
                        class="form-input" placeholder="Nama lembaga sertifikasi">
                </div>

                <div class="md:col-span-2">
                    <label class="form-label">Area Audit <span class="text-red-500">*</span></label>
                    <input type="text" name="area" value="{{ old('area', $audit->area ?? '') }}"
                        class="form-input" placeholder="Gudang Sortir, Zona Inbound..." required>
                </div>

                <div>
                    <label class="form-label">Standar <span class="text-red-500">*</span></label>
                    <select name="standard" class="form-input" required>
                        <option value="iso_45001" {{ old('standard', $audit->standard ?? 'keduanya') === 'iso_45001' ? 'selected' : '' }}>ISO 45001:2018</option>
                        <option value="pp_50_2012" {{ old('standard', $audit->standard ?? '') === 'pp_50_2012' ? 'selected' : '' }}>PP 50/2012</option>
                        <option value="keduanya" {{ old('standard', $audit->standard ?? '') === 'keduanya' ? 'selected' : '' }}>ISO 45001 + PP 50/2012</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="form-input" required>
                        <option value="planned"   {{ old('status', $audit->status ?? 'planned') === 'planned' ? 'selected' : '' }}>Direncanakan</option>
                        <option value="ongoing"   {{ old('status', $audit->status ?? '') === 'ongoing' ? 'selected' : '' }}>Berlangsung</option>
                        <option value="completed" {{ old('status', $audit->status ?? '') === 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ old('status', $audit->status ?? '') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="form-label">Ruang Lingkup</label>
                    <textarea name="scope" rows="2" class="form-input" placeholder="Deskripsi ruang lingkup audit...">{{ old('scope', $audit->scope ?? '') }}</textarea>
                </div>

                @if(isset($audit))
                <div class="md:col-span-2">
                    <label class="form-label">Ringkasan Hasil Audit</label>
                    <textarea name="summary" rows="3" class="form-input">{{ old('summary', $audit->summary ?? '') }}</textarea>
                </div>
                @endif

            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('audits.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">
                    {{ isset($audit) ? 'Simpan Perubahan' : 'Buat Audit' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
