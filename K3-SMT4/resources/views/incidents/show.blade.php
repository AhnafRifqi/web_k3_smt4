@extends('layouts.app')

@section('title', $incident->incident_number)
@section('page-title', $incident->incident_number)
@section('page-subtitle', $incident->title)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    <div class="lg:col-span-2 space-y-5">
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm p-6">
            <h3 class="font-bold text-slate-900 dark:text-white mb-4">Incident Details</h3>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="text-slate-500 dark:text-slate-400">Incident Number</dt>
                    <dd class="font-medium text-slate-900 dark:text-white">{{ $incident->incident_number }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500 dark:text-slate-400">Status</dt>
                    <dd><span class="badge-{{ $incident->status_color }}">{{ $incident->status_label }}</span></dd>
                </div>
                <div>
                    <dt class="text-slate-500 dark:text-slate-400">Incident Date</dt>
                    <dd class="font-medium">{{ $incident->incident_date->format('d M Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500 dark:text-slate-400">Location</dt>
                    <dd class="font-medium">{{ $incident->location }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500 dark:text-slate-400">Type</dt>
                    <dd><span class="badge-{{ $incident->severity_color }}">{{ $incident->incident_type_label }}</span></dd>
                </div>
                <div>
                    <dt class="text-slate-500 dark:text-slate-400">Severity</dt>
                    <dd><span class="badge-{{ $incident->severity_color }}">{{ $incident->severity_label }}</span></dd>
                </div>
                <div>
                    <dt class="text-slate-500 dark:text-slate-400">Department</dt>
                    <dd class="font-medium">{{ $incident->department?->name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500 dark:text-slate-400">Reported By</dt>
                    <dd class="font-medium">{{ $incident->reporter?->name ?? '-' }}</dd>
                </div>
                @if($incident->injured_persons)
                <div class="md:col-span-2">
                    <dt class="text-slate-500 dark:text-slate-400">Injured Persons</dt>
                    <dd class="font-medium">{{ $incident->injured_persons }}</dd>
                </div>
                @endif
                @if($incident->witnesses)
                <div class="md:col-span-2">
                    <dt class="text-slate-500 dark:text-slate-400">Witnesses</dt>
                    <dd class="font-medium">{{ $incident->witnesses }}</dd>
                </div>
                @endif
            </dl>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm p-6">
            <h3 class="font-bold text-slate-900 dark:text-white mb-4">Description</h3>
            <p class="text-sm text-slate-600 dark:text-slate-400 whitespace-pre-wrap">{{ $incident->description }}</p>
        </div>

        @if($incident->immediate_action_taken)
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm p-6">
            <h3 class="font-bold text-slate-900 dark:text-white mb-4">Immediate Action Taken</h3>
            <p class="text-sm text-slate-600 dark:text-slate-400 whitespace-pre-wrap">{{ $incident->immediate_action_taken }}</p>
        </div>
        @endif

        @if($incident->root_cause || $incident->lesson_learned)
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm p-6">
            <h3 class="font-bold text-slate-900 dark:text-white mb-4">Investigation Results</h3>
            @if($incident->root_cause)
            <div class="mb-4">
                <dt class="text-sm text-slate-500 dark:text-slate-400">Root Cause</dt>
                <dd class="text-sm font-medium text-slate-900 dark:text-white whitespace-pre-wrap">{{ $incident->root_cause }}</dd>
            </div>
            @endif
            @if($incident->lesson_learned)
            <div>
                <dt class="text-sm text-slate-500 dark:text-slate-400">Lesson Learned</dt>
                <dd class="text-sm font-medium text-slate-900 dark:text-white whitespace-pre-wrap">{{ $incident->lesson_learned }}</dd>
            </div>
            @endif
        </div>
        @endif
    </div>

    <div class="space-y-5">
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm p-6">
            <h3 class="font-bold text-slate-900 dark:text-white mb-4">Actions</h3>
            <div class="space-y-3">
                @can('update', $incident)
                <a href="{{ route('incidents.edit', $incident) }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit Incident
                </a>
                @endcan

                @if($incident->capa_required && !$incident->capa)
                <a href="{{ route('capa.create', ['incident_id' => $incident->id]) }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Create CAPA
                </a>
                @endif

                @if($incident->capa)
                <a href="{{ route('capa.show', $incident->capa) }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm font-medium">
                    View CAPA
                </a>
                @endif

                @if($incident->status === 'reported')
                <form method="POST" action="{{ route('incidents.assign-investigation', $incident) }}">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-xs font-medium text-slate-500">Assign Investigator</label>
                        <div class="flex gap-2">
                            <select name="investigated_by" class="form-input flex-1" required>
                                <option value="">Select user...</option>
                                @foreach(\App\Models\User::whereIn('role', ['k3_manager', 'k3_officer'])->get() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn-primary bg-blue-600 text-white px-3 py-2 rounded-lg text-sm">Assign</button>
                        </div>
                    </div>
                </form>
                @endif
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm p-6">
            <h3 class="font-bold text-slate-900 dark:text-white mb-4">Status Timeline</h3>
            <div class="space-y-3 text-sm">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-green-500"></div>
                    <div>
                        <p class="font-medium text-slate-900 dark:text-white">Reported</p>
                        <p class="text-xs text-slate-500">{{ $incident->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
                @if($incident->investigated_by)
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-yellow-500"></div>
                    <div>
                        <p class="font-medium text-slate-900 dark:text-white">Under Investigation</p>
                        <p class="text-xs text-slate-500">Assigned to {{ $incident->investigator?->name ?? '-' }}</p>
                    </div>
                </div>
                @endif
                @if($incident->closed_at)
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-slate-400"></div>
                    <div>
                        <p class="font-medium text-slate-900 dark:text-white">Closed</p>
                        <p class="text-xs text-slate-500">{{ $incident->closed_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection