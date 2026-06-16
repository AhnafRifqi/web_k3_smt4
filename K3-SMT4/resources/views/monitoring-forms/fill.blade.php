@extends('layouts.app')
@section('title', 'Isi Form: ' . $monitoringForm->title)
@section('page-title', 'Isi Form Monitoring')
@section('page-subtitle', $monitoringForm->title)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-8">
        <div class="mb-6 pb-4 border-b border-gray-100 dark:border-gray-700">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $monitoringForm->title }}</h2>
            @if($monitoringForm->description)
            <p class="text-sm text-gray-500 mt-1">{{ $monitoringForm->description }}</p>
            @endif
            <p class="text-xs text-gray-400 mt-2">
                Penugasan: {{ $assignment->frequency_label }}
                @if($assignment->due_date) · Tenggat {{ $assignment->due_date->format('d M Y') }} @endif
            </p>
        </div>

        <form action="{{ route('monitoring-forms.submit', [$monitoringForm, $assignment]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            @foreach($monitoringForm->fields as $field)
            @php
                $fieldKey = 'field_' . $field->id;
                $existingValue = $existingSubmission?->data[(string)$field->id]['value'] ?? old($fieldKey);
            @endphp
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                    {{ $field->label }}
                    @if($field->is_required) <span class="text-red-500">*</span> @endif
                </label>

                @switch($field->field_type)
                    @case('text')
                        <input type="text" name="{{ $fieldKey }}" value="{{ $existingValue }}"
                            class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50 px-4 py-3 text-gray-900 dark:text-slate-100 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        @break

                    @case('number')
                        <input type="number" name="{{ $fieldKey }}" value="{{ $existingValue }}" step="any"
                            class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50 px-4 py-3 text-gray-900 dark:text-slate-100 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        @break

                    @case('yes_no')
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="{{ $fieldKey }}" value="yes" {{ $existingValue === 'yes' ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600">
                                <span class="text-sm text-gray-700 dark:text-slate-300">Ya</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="{{ $fieldKey }}" value="no" {{ $existingValue === 'no' ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600">
                                <span class="text-sm text-gray-700 dark:text-slate-300">Tidak</span>
                            </label>
                        </div>
                        @break

                    @case('checklist')
                        <div class="space-y-2">
                            @foreach($field->options ?? [] as $opt)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="{{ $fieldKey }}[]" value="{{ $opt }}"
                                    {{ is_array($existingValue) && in_array($opt, $existingValue) ? 'checked' : '' }}
                                    class="w-4 h-4 rounded text-blue-600">
                                <span class="text-sm text-gray-700 dark:text-slate-300">{{ $opt }}</span>
                            </label>
                            @endforeach
                        </div>
                        @break

                    @case('dropdown')
                        <select name="{{ $fieldKey }}"
                            class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50 px-4 py-3 text-gray-900 dark:text-slate-100 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="">— Pilih —</option>
                            @foreach($field->options ?? [] as $opt)
                            <option value="{{ $opt }}" {{ $existingValue === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                        @break

                    @case('date')
                        <input type="date" name="{{ $fieldKey }}" value="{{ $existingValue }}"
                            class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50 px-4 py-3 text-gray-900 dark:text-slate-100 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        @break

                    @case('photo')
                        @if($existingValue)
                        <div class="mb-2">
                            <img src="{{ $existingValue }}" alt="Foto" class="h-24 rounded-lg object-cover">
                            <p class="text-xs text-gray-400 mt-1">Upload ulang untuk mengganti</p>
                        </div>
                        @endif
                        <input type="file" name="{{ $fieldKey }}" accept="image/*"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @break

                    @case('signature')
                        <textarea name="{{ $fieldKey }}" rows="2" placeholder="Ketik nama / catatan tanda tangan"
                            class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50 px-4 py-3 text-gray-900 dark:text-slate-100 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">{{ $existingValue }}</textarea>
                        @break

                    @case('rating')
                        <div class="flex gap-2">
                            @for($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer">
                                <input type="radio" name="{{ $fieldKey }}" value="{{ $i }}" {{ (int)$existingValue === $i ? 'checked' : '' }} class="sr-only peer">
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full border-2 border-gray-200 text-sm font-bold text-gray-400 peer-checked:border-blue-500 peer-checked:bg-blue-500 peer-checked:text-white transition-all hover:border-blue-300">{{ $i }}</span>
                            </label>
                            @endfor
                        </div>
                        @break
                @endswitch

                @error($fieldKey) <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p> @enderror
            </div>
            @endforeach

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100 dark:border-slate-700">
                <a href="{{ route('monitoring-forms.show', $monitoringForm) }}"
                    class="inline-flex items-center gap-2 px-5 py-3 text-sm font-medium text-gray-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-xl hover:bg-gray-50 transition-all">
                    Batal
                </a>
                <button type="submit" name="status" value="draft"
                    class="inline-flex items-center gap-2 px-5 py-3 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-all dark:bg-slate-700 dark:text-slate-300">
                    Simpan Draft
                </button>
                <button type="submit" name="status" value="submitted"
                    class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-500 shadow-lg shadow-blue-500/25 transition-all">
                    Submit Form
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
