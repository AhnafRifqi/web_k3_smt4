@extends('layouts.app')

@php
    $isEdit = isset($monitoringForm);
    $existingFields = $isEdit
        ? $monitoringForm->fields->map(fn($f) => [
            'field_type' => $f->field_type,
            'label' => $f->label,
            'options' => $f->options ?? [],
            'optionsText' => $f->options ? implode("\n", $f->options) : '',
            'is_required' => $f->is_required,
        ])->values()->toArray()
        : [];
@endphp

@section('title', $isEdit ? 'Edit Form Monitoring' : 'Buat Form Monitoring')
@section('page-title', $isEdit ? 'Edit Form Monitoring' : 'Buat Form Monitoring')
@section('page-subtitle', 'Bangun form monitoring dengan field dinamis')

@section('content')
<div class="max-w-4xl mx-auto" x-data="formBuilder()">
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-8">
        <form action="{{ $isEdit ? route('monitoring-forms.update', $monitoringForm) : route('monitoring-forms.store') }}" method="POST" @submit="prepareSubmit">
            @csrf
            @if($isEdit) @method('PUT') @endif

            <div class="space-y-6">
                {{-- Judul --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Judul Form</label>
                    <input type="text" name="title" x-model="title" value="{{ old('title', $monitoringForm->title ?? '') }}" required
                        class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50 px-4 py-3 text-gray-900 dark:text-slate-100 outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                        placeholder="Contoh: Inspeksi Harian Area Gudang">
                    @error('title') <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p> @enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Deskripsi</label>
                    <textarea name="description" rows="3"
                        class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50 px-4 py-3 text-gray-900 dark:text-slate-100 outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                        placeholder="Deskripsi form (opsional)">{{ old('description', $monitoringForm->description ?? '') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Departemen --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Departemen (Opsional)</label>
                        <select name="department_id"
                            class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50 px-4 py-3 text-gray-900 dark:text-slate-100 outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="">— Semua Departemen —</option>
                            @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id', $monitoringForm->department_id ?? '') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status Aktif --}}
                    <div class="flex items-end">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1"
                                {{ old('is_active', $monitoringForm->is_active ?? true) ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium text-gray-700 dark:text-slate-300">Form Aktif</span>
                        </label>
                    </div>
                </div>

                {{-- Field Builder --}}
                <div class="border-t border-gray-100 dark:border-slate-700 pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white">Field Form</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Tambahkan field sesuai kebutuhan monitoring</p>
                        </div>
                        <button type="button" @click="addField()"
                            class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors dark:bg-blue-900/20 dark:text-blue-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah Field
                        </button>
                    </div>

                    @error('fields') <p class="text-xs text-red-500 mb-3">{{ $message }}</p> @enderror

                    <div class="space-y-4">
                        <template x-for="(field, index) in fields" :key="index">
                            <div class="bg-gray-50 dark:bg-slate-900/50 rounded-xl border border-gray-200 dark:border-slate-700 p-4">
                                <div class="flex items-start justify-between mb-3">
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider" x-text="'Field #' + (index + 1)"></span>
                                    <button type="button" @click="removeField(index)" x-show="fields.length > 1"
                                        class="text-xs text-red-500 hover:text-red-700 font-medium">Hapus</button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1">Tipe Field</label>
                                        <select x-model="field.field_type"
                                            class="w-full text-sm rounded-lg border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 dark:text-white">
                                            <option value="text">Teks</option>
                                            <option value="number">Angka</option>
                                            <option value="yes_no">Ya/Tidak</option>
                                            <option value="checklist">Checklist</option>
                                            <option value="dropdown">Dropdown</option>
                                            <option value="date">Tanggal</option>
                                            <option value="photo">Foto</option>
                                            <option value="signature">Tanda Tangan</option>
                                            <option value="rating">Rating (1-5)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1">Label</label>
                                        <input type="text" x-model="field.label" required
                                            class="w-full text-sm rounded-lg border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 dark:text-white"
                                            placeholder="Label field">
                                    </div>
                                </div>

                                <div x-show="['checklist','dropdown'].includes(field.field_type)" class="mt-3">
                                    <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1">Opsi (satu per baris)</label>
                                    <textarea x-model="field.optionsText" rows="3"
                                        class="w-full text-sm rounded-lg border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 dark:text-white"
                                        placeholder="Opsi 1&#10;Opsi 2&#10;Opsi 3"></textarea>
                                </div>

                                <div class="mt-3">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" x-model="field.is_required" class="w-4 h-4 rounded border-gray-300 text-blue-600">
                                        <span class="text-xs font-medium text-gray-600 dark:text-slate-400">Wajib diisi</span>
                                    </label>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Hidden fields JSON --}}
                <template x-for="(field, index) in fields" :key="'hidden-' + index">
                    <div>
                        <input type="hidden" :name="'fields[' + index + '][field_type]'" :value="field.field_type">
                        <input type="hidden" :name="'fields[' + index + '][label]'" :value="field.label">
                        <input type="hidden" :name="'fields[' + index + '][is_required]'" :value="field.is_required ? 1 : 0">
                        <input type="hidden" :name="'fields[' + index + '][order]'" :value="index">
                        <template x-for="(opt, oi) in parseOptions(field.optionsText)" :key="'opt-' + index + '-' + oi">
                            <input type="hidden" :name="'fields[' + index + '][options][' + oi + ']'" :value="opt">
                        </template>
                    </div>
                </template>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 mt-6 border-t border-gray-100 dark:border-slate-700">
                <a href="{{ route('monitoring-forms.index') }}"
                    class="inline-flex items-center gap-2 px-5 py-3 text-sm font-medium text-gray-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-all">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-500 shadow-lg shadow-blue-500/25 transition-all">
                    {{ $isEdit ? 'Perbarui Form' : 'Simpan Form' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function formBuilder() {
    const existing = @json($existingFields);

    return {
        title: @json(old('title', $monitoringForm->title ?? '')),
        fields: existing.length > 0 ? existing : [{
            field_type: 'text',
            label: '',
            options: [],
            optionsText: '',
            is_required: false,
        }],

        addField() {
            this.fields.push({
                field_type: 'text',
                label: '',
                options: [],
                optionsText: '',
                is_required: false,
            });
        },

        removeField(index) {
            this.fields.splice(index, 1);
        },

        parseOptions(text) {
            if (!text) return [];
            return text.split('\n').map(s => s.trim()).filter(s => s.length > 0);
        },

        prepareSubmit(e) {
            if (this.fields.length === 0) {
                e.preventDefault();
                alert('Tambahkan minimal satu field.');
                return;
            }
            for (const field of this.fields) {
                if (!field.label.trim()) {
                    e.preventDefault();
                    alert('Semua field harus memiliki label.');
                    return;
                }
            }
        },
    };
}
</script>
@endpush
