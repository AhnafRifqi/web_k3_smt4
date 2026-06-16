@extends('layouts.app')

@section('title', 'Edit Hazard')
@section('page-title', 'Edit Hazard Identification')
@section('page-subtitle', $hazard->hazard_number)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm p-6">
        <form method="POST" action="{{ route('hazards.update', $hazard) }}">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="md:col-span-2">
                    <label class="form-label">Location *</label>
                    <input type="text" name="location" value="{{ old('location', $hazard->location) }}" class="form-input w-full" required>
                </div>

                <div>
                    <label class="form-label">Department</label>
                    <select name="department_id" class="form-input w-full">
                        <option value="">Select...</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" @selected(old('department_id', $hazard->department_id) == $dept->id)>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Hazard Type *</label>
                    <select name="hazard_type" class="form-input w-full" required>
                        @foreach(['physical','chemical','biological','ergonomic','psychosocial','electrical','mechanical'] as $type)
                        <option value="{{ $type }}" @selected(old('hazard_type', $hazard->hazard_type) === $type)>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="form-label">Task Description *</label>
                    <textarea name="task_description" rows="3" class="form-input w-full" required>{{ old('task_description', $hazard->task_description) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="form-label">Hazard Description *</label>
                    <textarea name="hazard_description" rows="3" class="form-input w-full" required>{{ old('hazard_description', $hazard->hazard_description) }}</textarea>
                </div>

                <div>
                    <label class="form-label">Likelihood (1-5) *</label>
                    <select name="likelihood" class="form-input w-full" required>
                        @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" @selected(old('likelihood', $hazard->likelihood) == $i)>{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="form-label">Severity (1-5) *</label>
                    <select name="severity" class="form-input w-full" required>
                        @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" @selected(old('severity', $hazard->severity) == $i)>{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="form-label">Existing Controls</label>
                    <textarea name="existing_controls" rows="3" class="form-input w-full">{{ old('existing_controls', $hazard->existing_controls) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="form-label">Additional Controls Recommended</label>
                    <textarea name="additional_controls" rows="3" class="form-input w-full">{{ old('additional_controls', $hazard->additional_controls) }}</textarea>
                </div>

                <div>
                    <label class="form-label">Responsible Person</label>
                    <select name="responsible_person_id" class="form-input w-full">
                        <option value="">Select...</option>
                        @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" @selected(old('responsible_person_id', $hazard->responsible_person_id) == $emp->id)>{{ $emp->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Target Completion</label>
                    <input type="date" name="target_completion_date" value="{{ old('target_completion_date', $hazard->target_completion_date?->format('Y-m-d')) }}" class="form-input w-full">
                </div>

                <div>
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-input w-full" required>
                        <option value="identified" @selected(old('status', $hazard->status) === 'identified')>Identified</option>
                        <option value="controlled" @selected(old('status', $hazard->status) === 'controlled')>Controlled</option>
                        <option value="closed" @selected(old('status', $hazard->status) === 'closed')>Closed</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Related SOP</label>
                    <select name="sop_id" class="form-input w-full">
                        <option value="">Select...</option>
                        @foreach($sops as $sop)
                        <option value="{{ $sop->id }}" @selected(old('sop_id', $hazard->sop_id) == $sop->id)>{{ $sop->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                <button type="submit" class="btn-primary px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">Update Hazard</button>
                <a href="{{ route('hazards.show', $hazard) }}" class="text-sm text-slate-500 hover:text-slate-700">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection