@extends('layouts.app')

@section('title', 'Incidents')
@section('page-title', 'Incident & Hazard Reports')
@section('page-subtitle', 'Manage workplace incidents and near misses')

@section('content')
<div class="mb-4 flex flex-wrap items-center justify-between gap-3">
    <div class="flex items-center gap-2">
        {{-- Tombol HANYA muncul jika role BUKAN auditor dan BUKAN viewer --}}
        @if(auth()->check() && !in_array(auth()->user()->role, ['auditor', 'viewer']))
        <a href="{{ route('incidents.create') }}" class="btn-primary inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Report Incident
        </a>
        @endif
    </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm overflow-hidden">
    <div class="p-4 border-b border-slate-100 dark:border-slate-700">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-3">
            <input type="text" name="search" placeholder="Search incidents..." value="{{ request('search') }}" class="form-input">
            <select name="incident_type" class="form-input">
                <option value="">All Types</option>
                <option value="near_miss" @selected(request('incident_type') === 'near_miss')>Near Miss</option>
                <option value="first_aid" @selected(request('incident_type') === 'first_aid')>First Aid</option>
                <option value="medical_treatment" @selected(request('incident_type') === 'medical_treatment')>Medical Treatment</option>
                <option value="lost_time_injury" @selected(request('incident_type') === 'lost_time_injury')>Lost Time Injury</option>
                <option value="fatality" @selected(request('incident_type') === 'fatality')>Fatality</option>
                <option value="property_damage" @selected(request('incident_type') === 'property_damage')>Property Damage</option>
                <option value="environmental" @selected(request('incident_type') === 'environmental')>Environmental</option>
            </select>
            <select name="severity" class="form-input">
                <option value="">All Severities</option>
                <option value="low" @selected(request('severity') === 'low')>Low</option>
                <option value="medium" @selected(request('severity') === 'medium')>Medium</option>
                <option value="high" @selected(request('severity') === 'high')>High</option>
                <option value="critical" @selected(request('severity') === 'critical')>Critical</option>
            </select>
            <select name="status" class="form-input">
                <option value="">All Status</option>
                <option value="reported" @selected(request('status') === 'reported')>Reported</option>
                <option value="under_investigation" @selected(request('status') === 'under_investigation')>Under Investigation</option>
                <option value="corrective_action" @selected(request('status') === 'corrective_action')>Corrective Action</option>
                <option value="closed" @selected(request('status') === 'closed')>Closed</option>
            </select>
            @if(auth()->user()->isSuperAdmin() || auth()->user()->isK3Manager() || auth()->user()->isK3Officer())
            <select name="department_id" class="form-input">
                <option value="">All Departments</option>
                <option value="{{ $dept->id ?? '' }}" @selected(request('department_id') == ($dept->id ?? ''))>{{ $dept->name ?? 'Dept' }}</option>
            </select>
            @endif
            <button type="submit" class="btn-primary bg-blue-600 text-white rounded-lg px-4 py-2 text-sm font-medium">Filter</button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-900/50">
                <tr>
                    <th class="text-left px-4 py-3 font-semibold text-slate-600 dark:text-slate-400">Incident #</th>
                    <th class="text-left px-4 py-3 font-semibold text-slate-600 dark:text-slate-400">Title</th>
                    <th class="text-left px-4 py-3 font-semibold text-slate-600 dark:text-slate-400">Type</th>
                    <th class="text-left px-4 py-3 font-semibold text-slate-600 dark:text-slate-400">Severity</th>
                    <th class="text-left px-4 py-3 font-semibold text-slate-600 dark:text-slate-400">Date</th>
                    <th class="text-left px-4 py-3 font-semibold text-slate-600 dark:text-slate-400">Status</th>
                    <th class="text-left px-4 py-3 font-semibold text-slate-600 dark:text-slate-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($incidents as $incident)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                    <td class="px-4 py-3 font-medium">{{ $incident->incident_number }}</td>
                    <td class="px-4 py-3">{{ Str::limit($incident->title, 40) }}</td>
                    <td class="px-4 py-3"><span class="badge-{{ $incident->severity_color }}">{{ $incident->incident_type_label }}</span></td>
                    <td class="px-4 py-3">
                        <span class="badge-{{ $incident->severity_color }}">{{ $incident->severity_label }}</span>
                    </td>
                    <td class="px-4 py-3">{{ $incident->incident_date->format('d M Y H:i') }}</td>
                    <td class="px-4 py-3">
                        <span class="badge-{{ $incident->status_color }}">{{ $incident->status_label }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('incidents.show', $incident) }}" class="text-blue-600 hover:underline text-xs font-medium">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-slate-400">
                        <p class="text-sm">No incidents found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t border-slate-100 dark:border-slate-700">
        {{ $incidents->links() }}
    </div>
</div>
@endsection