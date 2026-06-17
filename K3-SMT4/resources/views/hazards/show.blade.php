@extends('layouts.app')

@section('title', $hazard->hazard_number)
@section('page-title', $hazard->hazard_number)
@section('page-subtitle', 'Hazard Identification')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    <div class="lg:col-span-2 space-y-5">
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm p-6">
            <h3 class="font-bold text-slate-900 dark:text-white mb-4">Hazard Details</h3>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div><dt class="text-slate-500">Hazard #</dt><dd class="font-medium">{{ $hazard->hazard_number }}</dd></div>
                <div><dt class="text-slate-500">Status</dt><dd><span class="badge-{{ $hazard->status_color }}">{{ $hazard->status_label }}</span></dd></div>
                <div><dt class="text-slate-500">Type</dt><dd><span class="badge-blue">{{ $hazard->hazard_type_label }}</span></dd></div>
                <div><dt class="text-slate-500">Department</dt><dd class="font-medium">{{ $hazard->department?->name ?? '-' }}</dd></div>
                <div><dt class="text-slate-500">Location</dt><dd class="font-medium">{{ $hazard->location }}</dd></div>
                <div><dt class="text-slate-500">Identified By</dt><dd class="font-medium">{{ $hazard->identifier?->name ?? '-' }}</dd></div>
            </dl>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm p-6">
            <h3 class="font-bold text-slate-900 dark:text-white mb-4">Task Description</h3>
            <p class="text-sm whitespace-pre-wrap">{{ $hazard->task_description }}</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm p-6">
            <h3 class="font-bold text-slate-900 dark:text-white mb-4">Hazard Description</h3>
            <p class="text-sm whitespace-pre-wrap">{{ $hazard->hazard_description }}</p>
        </div>

        @if($hazard->existing_controls)
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm p-6">
            <h3 class="font-bold text-slate-900 dark:text-white mb-4">Existing Controls</h3>
            <p class="text-sm whitespace-pre-wrap">{{ $hazard->existing_controls }}</p>
        </div>
        @endif

        @if($hazard->additional_controls)
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm p-6">
            <h3 class="font-bold text-slate-900 dark:text-white mb-4">Additional Controls Recommended</h3>
            <p class="text-sm whitespace-pre-wrap">{{ $hazard->additional_controls }}</p>
        </div>
        @endif
    </div>

    <div class="space-y-5">
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm p-6">
            <h3 class="font-bold text-slate-900 dark:text-white mb-4">Risk Assessment</h3>
            <div class="text-center">
                <div class="text-5xl font-extrabold {{ $hazard->risk_level === 'extreme' ? 'text-red-600' : ($hazard->risk_level === 'high' ? 'text-orange-500' : ($hazard->risk_level === 'medium' ? 'text-yellow-500' : 'text-green-500')) }}">
                    {{ $hazard->risk_score }}
                </div>
                <p class="text-sm text-slate-500 mt-1">Risk Score (L×S)</p>
                <div class="mt-3">
                    <span class="badge-{{ $hazard->risk_level_color }} text-lg px-4 py-1">{{ $hazard->risk_level_label }}</span>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                    <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-3">
                        <p class="text-2xl font-bold">{{ $hazard->likelihood }}</p>
                        <p class="text-xs text-slate-500">Likelihood</p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-3">
                        <p class="text-2xl font-bold">{{ $hazard->severity }}</p>
                        <p class="text-xs text-slate-500">Severity</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm p-6">
            <h3 class="font-bold text-slate-900 dark:text-white mb-4">Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('hazards.edit', $hazard) }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 text-sm font-medium">Edit Hazard</a>
                @if($hazard->responsiblePerson)
                <div class="text-sm">
                    <p class="text-slate-500">Responsible:</p>
                    <p class="font-medium">{{ $hazard->responsiblePerson->name }}</p>
                    @if($hazard->target_completion_date)
                    <p class="text-xs text-slate-400">Target: {{ $hazard->target_completion_date->format('d M Y') }}</p>
                    @endif
                </div>
                @endif
                @if($hazard->sop)
                <a href="{{ route('sops.show', $hazard->sop) }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm font-medium">View Related SOP</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection