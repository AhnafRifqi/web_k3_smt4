@extends('layouts.app')
@section('title', $monitoringForm->title)
@section('page-title', 'Detail Form Monitoring')
@section('page-subtitle', $monitoringForm->title)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- LEFT --}}
    <div class="lg:col-span-2 space-y-4">

        {{-- Info Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $monitoringForm->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-600' }}">
                            {{ $monitoringForm->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $monitoringForm->title }}</h2>
                    @if($monitoringForm->description)
                    <p class="text-sm text-gray-500 mt-2">{{ $monitoringForm->description }}</p>
                    @endif
                </div>
                @if(auth()->user()->canManage())
                <div class="flex gap-2">
                    <a href="{{ route('monitoring-forms.edit', $monitoringForm) }}"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors dark:text-gray-300 dark:bg-gray-700">
                        Edit
                    </a>
                    <form action="{{ route('monitoring-forms.destroy', $monitoringForm) }}" method="POST"
                        onsubmit="return confirm('Hapus form monitoring ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                            Hapus
                        </button>
                    </form>
                </div>
                @endif
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Departemen</p>
                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $monitoringForm->department?->name ?? 'Semua' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Dibuat Oleh</p>
                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $monitoringForm->creator?->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Jumlah Field</p>
                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $monitoringForm->fields->count() }}</p>
                </div>
            </div>
        </div>

        {{-- Fields --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Struktur Field</h3>
            <div class="space-y-2">
                @foreach($monitoringForm->fields as $field)
                <div class="flex items-center justify-between py-2 px-3 bg-gray-50 dark:bg-slate-900/50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-mono text-gray-400 w-6">{{ $field->order + 1 }}.</span>
                        <span class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $field->label }}</span>
                        @if($field->is_required)
                        <span class="text-xs text-red-500">*</span>
                        @endif
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                        {{ $field->field_type_label }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Submissions --}}
        @if($monitoringForm->submissions->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Riwayat Submit ({{ $monitoringForm->submissions->count() }})</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-gray-400 border-b border-gray-100 dark:border-gray-700">
                            <th class="text-left py-2 pr-4">Pengisi</th>
                            <th class="text-left py-2 pr-4">Departemen</th>
                            <th class="text-left py-2 pr-4">Tanggal</th>
                            <th class="text-left py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monitoringForm->submissions->take(10) as $sub)
                        <tr class="border-b border-gray-50 dark:border-gray-700/50">
                            <td class="py-2 pr-4 text-gray-800 dark:text-gray-200">{{ $sub->submitter?->name ?? '-' }}</td>
                            <td class="py-2 pr-4 text-gray-500">{{ $sub->department?->name ?? '-' }}</td>
                            <td class="py-2 pr-4 text-gray-500">{{ $sub->submitted_at?->format('d M Y H:i') ?? '-' }}</td>
                            <td class="py-2">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $sub->status_color }}-100 text-{{ $sub->status_color }}-700">
                                    {{ $sub->status_label }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    {{-- RIGHT --}}
    <div class="space-y-4">

        {{-- My Assignments (for employees/dept_heads) --}}
        @if(!auth()->user()->canManage() && $myAssignments->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Penugasan Saya</h3>
            <div class="space-y-3">
                @foreach($myAssignments as $assignment)
                <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <p class="text-xs text-gray-500 mb-1">{{ $assignment->frequency_label }}
                        @if($assignment->due_date) · Due {{ $assignment->due_date->format('d M Y') }} @endif
                    </p>
                    <a href="{{ route('monitoring-forms.fill', [$monitoringForm, $assignment]) }}"
                        class="inline-flex items-center gap-1.5 mt-2 px-3 py-2 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors w-full justify-center">
                        Isi Form
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Assign Form (K3 officers) --}}
        @if(auth()->user()->canManage())
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5" x-data="{ assignType: 'department' }">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Tugaskan Form</h3>
            <form action="{{ route('monitoring-forms.assign', $monitoringForm) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1">Tugaskan Ke</label>
                    <select name="assigned_to_type" x-model="assignType"
                        class="w-full text-sm rounded-lg border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-900/50 px-3 py-2 dark:text-white">
                        <option value="department">Departemen</option>
                        <option value="user">User</option>
                    </select>
                </div>

                <div x-show="assignType === 'department'">
                    <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1">Departemen</label>
                    <select name="assigned_to_id" :disabled="assignType !== 'department'"
                        class="w-full text-sm rounded-lg border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-900/50 px-3 py-2 dark:text-white">
                        @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div x-show="assignType === 'user'" x-cloak>
                    <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1">User</label>
                    <select :name="assignType === 'user' ? 'assigned_to_id' : '_user_placeholder'" :disabled="assignType !== 'user'"
                        class="w-full text-sm rounded-lg border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-900/50 px-3 py-2 dark:text-white">
                        @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role_label }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1">Frekuensi</label>
                    <select name="frequency"
                        class="w-full text-sm rounded-lg border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-900/50 px-3 py-2 dark:text-white">
                        <option value="daily">Harian</option>
                        <option value="weekly">Mingguan</option>
                        <option value="monthly">Bulanan</option>
                        <option value="once">Sekali</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1">Tenggat (Opsional)</label>
                    <input type="date" name="due_date"
                        class="w-full text-sm rounded-lg border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-900/50 px-3 py-2 dark:text-white">
                </div>

                <button type="submit" class="w-full px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                    Tugaskan
                </button>
            </form>
        </div>
        @endif

        {{-- Assignments List --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Penugasan ({{ $monitoringForm->assignments->count() }})</h3>
            @forelse($monitoringForm->assignments as $assignment)
            <div class="py-3 border-b border-gray-50 dark:border-gray-700/50 last:border-0">
                <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $assignment->assigned_to_name }}</p>
                <p class="text-xs text-gray-500 mt-0.5">
                    {{ $assignment->assigned_to_type === 'department' ? 'Departemen' : 'User' }}
                    · {{ $assignment->frequency_label }}
                    @if($assignment->due_date) · Due {{ $assignment->due_date->format('d M Y') }} @endif
                </p>
                @if(auth()->user()->canManage() || in_array(auth()->user()->role, ['dept_head', 'employee']))
                <a href="{{ route('monitoring-forms.fill', [$monitoringForm, $assignment]) }}"
                    class="inline-flex items-center gap-1 mt-2 text-xs text-blue-600 hover:text-blue-800 font-medium">
                    Isi Form →
                </a>
                @endif
            </div>
            @empty
            <p class="text-xs text-gray-400">Belum ada penugasan.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
