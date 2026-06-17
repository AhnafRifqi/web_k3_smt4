@extends('layouts.app')

@section('title', 'Report Incident')
@section('page-title', 'Report New Incident')
@section('page-subtitle', 'Document workplace incidents and near misses')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm p-6">
        <form method="POST" action="{{ route('incidents.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="md:col-span-2">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="form-input w-full" required>
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Incident Date & Time *</label>
                    <input type="datetime-local" name="incident_date" value="{{ old('incident_date') }}" class="form-input w-full" required>
                </div>

                <div>
                    <label class="form-label">Location *</label>
                    <input type="text" name="location" value="{{ old('location') }}" class="form-input w-full" required>
                </div>

                <div>
                    <label class="form-label">Incident Type *</label>
                    <select name="incident_type" class="form-input w-full" required>
                        <option value="">Select Type...</option>
                        <option value="near_miss" @selected(old('incident_type') === 'near_miss')>Near Miss</option>
                        <option value="first_aid" @selected(old('incident_type') === 'first_aid')>First Aid</option>
                        <option value="medical_treatment" @selected(old('incident_type') === 'medical_treatment')>Medical Treatment</option>
                        <option value="lost_time_injury" @selected(old('incident_type') === 'lost_time_injury')>Lost Time Injury</option>
                        <option value="fatality" @selected(old('incident_type') === 'fatality')>Fatality</option>
                        <option value="property_damage" @selected(old('incident_type') === 'property_damage')>Property Damage</option>
                        <option value="environmental" @selected(old('incident_type') === 'environmental')>Environmental</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Severity *</label>
                    <select name="severity" class="form-input w-full" required>
                        <option value="">Select Severity...</option>
                        <option value="low" @selected(old('severity') === 'low')>Low</option>
                        <option value="medium" @selected(old('severity') === 'medium')>Medium</option>
                        <option value="high" @selected(old('severity') === 'high')>High</option>
                        <option value="critical" @selected(old('severity') === 'critical')>Critical</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Department</label>
                    <select name="department_id" class="form-input w-full">
                        <option value="">Select Department...</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" @selected(old('department_id') == $dept->id)>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Injured Persons</label>
                    <input type="text" name="injured_persons" value="{{ old('injured_persons') }}" class="form-input w-full" placeholder="Names of injured persons">
                </div>

                <div>
                    <label class="form-label">Witnesses</label>
                    <input type="text" name="witnesses" value="{{ old('witnesses') }}" class="form-input w-full" placeholder="Names of witnesses">
                </div>

                <div class="md:col-span-2">
                    <label class="form-label">Immediate Action Taken</label>
                    <textarea name="immediate_action_taken" rows="3" class="form-input w-full">{{ old('immediate_action_taken') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="form-label">Immediate Cause</label>
                    <textarea name="immediate_cause" rows="3" class="form-input w-full" placeholder="Penyebab langsung kejadian (e.g. tindakan tidak aman, kondisi tidak aman)">{{ old('immediate_cause') }}</textarea>
                    @error('immediate_cause') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="form-label">Description *</label>
                    <textarea name="description" rows="4" class="form-input w-full" required>{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="capa_required" value="1" @checked(old('capa_required')) class="rounded border-slate-300">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">CAPA Required (investigation needed)</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                <button type="submit" class="btn-primary px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">Submit Report</button>
                <a href="{{ route('incidents.index') }}" class="text-sm text-slate-500 hover:text-slate-700">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection