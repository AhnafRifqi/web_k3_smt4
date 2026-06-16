@extends('layouts.app')

@section('title', 'Edit Incident')
@section('page-title', 'Edit Incident')
@section('page-subtitle', $incident->incident_number)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm p-6">
        <form method="POST" action="{{ route('incidents.update', $incident) }}">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="md:col-span-2">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" value="{{ old('title', $incident->title) }}" class="form-input w-full" required>
                </div>

                <div>
                    <label class="form-label">Incident Date *</label>
                    <input type="datetime-local" name="incident_date" value="{{ old('incident_date', $incident->incident_date->format('Y-m-d\TH:i')) }}" class="form-input w-full" required>
                </div>

                <div>
                    <label class="form-label">Location *</label>
                    <input type="text" name="location" value="{{ old('location', $incident->location) }}" class="form-input w-full" required>
                </div>

                <div>
                    <label class="form-label">Type *</label>
                    <select name="incident_type" class="form-input w-full" required>
                        @foreach(['near_miss','first_aid','medical_treatment','lost_time_injury','fatality','property_damage','environmental'] as $type)
                        <option value="{{ $type }}" @selected(old('incident_type', $incident->incident_type) === $type)>{{ ucwords(str_replace('_', ' ', $type)) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Severity *</label>
                    <select name="severity" class="form-input w-full" required>
                        @foreach(['low','medium','high','critical'] as $sev)
                        <option value="{{ $sev }}" @selected(old('severity', $incident->severity) === $sev)>{{ ucfirst($sev) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-input w-full" required>
                        @foreach(['reported','under_investigation','corrective_action','closed'] as $st)
                        <option value="{{ $st }}" @selected(old('status', $incident->status) === $st)>{{ ucwords(str_replace('_', ' ', $st)) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Department</label>
                    <select name="department_id" class="form-input w-full">
                        <option value="">Select...</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" @selected(old('department_id', $incident->department_id) == $dept->id)>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Investigator</label>
                    <select name="investigated_by" class="form-input w-full">
                        <option value="">Not assigned</option>
                        @foreach(\App\Models\User::whereIn('role', ['k3_manager','k3_officer'])->get() as $user)
                        <option value="{{ $user->id }}" @selected(old('investigated_by', $incident->investigated_by) == $user->id)>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Injured Persons</label>
                    <input type="text" name="injured_persons" value="{{ old('injured_persons', $incident->injured_persons) }}" class="form-input w-full">
                </div>

                <div>
                    <label class="form-label">Witnesses</label>
                    <input type="text" name="witnesses" value="{{ old('witnesses', $incident->witnesses) }}" class="form-input w-full">
                </div>

                <div class="md:col-span-2">
                    <label class="form-label">Immediate Action Taken</label>
                    <textarea name="immediate_action_taken" rows="3" class="form-input w-full">{{ old('immediate_action_taken', $incident->immediate_action_taken) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="form-label">Description *</label>
                    <textarea name="description" rows="4" class="form-input w-full" required>{{ old('description', $incident->description) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="form-label">Root Cause</label>
                    <textarea name="root_cause" rows="3" class="form-input w-full">{{ old('root_cause', $incident->root_cause) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="form-label">Lesson Learned</label>
                    <textarea name="lesson_learned" rows="3" class="form-input w-full">{{ old('lesson_learned', $incident->lesson_learned) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="capa_required" value="1" @checked(old('capa_required', $incident->capa_required)) class="rounded border-slate-300">
                        <span class="text-sm font-medium">CAPA Required</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                <button type="submit" class="btn-primary px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">Update Incident</button>
                <a href="{{ route('incidents.show', $incident) }}" class="text-sm text-slate-500 hover:text-slate-700">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection