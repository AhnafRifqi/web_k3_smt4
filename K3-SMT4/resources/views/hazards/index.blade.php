@extends('layouts.app')

@section('title', 'HIRARC')
@section('page-title', 'Hazard Identification & Risk Assessment')
@section('page-subtitle', 'HIRARC Register')

@section('content')
<div class="mb-4 flex flex-wrap items-center justify-between gap-3">
    <div class="flex items-center gap-2">
        <a href="{{ route('hazards.create') }}" class="btn-primary inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Hazard Identification
        </a>
    </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm overflow-hidden">
    <div class="p-4 border-b border-slate-100 dark:border-slate-700">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <input type="text" name="search" placeholder="Search hazards..." value="{{ request('search') }}" class="form-input">
            <select name="hazard_type" class="form-input">
                <option value="">All Types</option>
                @foreach(['physical','chemical','biological','ergonomic','psychosocial','electrical','mechanical'] as $type)
                <option value="{{ $type }}" @selected(request('hazard_type') === $type)>{{ ucfirst($type) }}</option>
                @endforeach
            </select>
            <select name="risk_level" class="form-input">
                <option value="">All Risk Levels</option>
                @foreach(['low','medium','high','extreme'] as $level)
                <option value="{{ $level }}" @selected(request('risk_level') === $level)>{{ ucfirst($level) }}</option>
                @endforeach
            </select>
            <select name="status" class="form-input">
                <option value="">All Status</option>
                <option value="identified" @selected(request('status') === 'identified')>Identified</option>
                <option value="controlled" @selected(request('status') === 'controlled')>Controlled</option>
                <option value="closed" @selected(request('status') === 'closed')>Closed</option>
            </select>
            <button type="submit" class="btn-primary bg-blue-600 text-white rounded-lg px-4 py-2 text-sm font-medium">Filter</button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-900/50">
                <tr>
                    <th class="text-left px-4 py-3 font-semibold text-slate-600">Hazard #</th>
                    <th class="text-left px-4 py-3 font-semibold text-slate-600">Type</th>
                    <th class="text-left px-4 py-3 font-semibold text-slate-600">Task</th>
                    <th class="text-center px-4 py-3 font-semibold text-slate-600">L</th>
                    <th class="text-center px-4 py-3 font-semibold text-slate-600">S</th>
                    <th class="text-center px-4 py-3 font-semibold text-slate-600">Score</th>
                    <th class="text-center px-4 py-3 font-semibold text-slate-600">Level</th>
                    <th class="text-left px-4 py-3 font-semibold text-slate-600">Status</th>
                    <th class="text-left px-4 py-3 font-semibold text-slate-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($hazards as $hazard)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                    <td class="px-4 py-3 font-medium">{{ $hazard->hazard_number }}</td>
                    <td class="px-4 py-3"><span class="badge-blue">{{ $hazard->hazard_type_label }}</span></td>
                    <td class="px-4 py-3 max-w-[200px] truncate">{{ $hazard->task_description }}</td>
                    <td class="px-4 py-3 text-center">{{ $hazard->likelihood }}</td>
                    <td class="px-4 py-3 text-center">{{ $hazard->severity }}</td>
                    <td class="px-4 py-3 text-center font-bold">{{ $hazard->risk_score }}</td>
                    <td class="px-4 py-3 text-center"><span class="badge-{{ $hazard->risk_level_color }}">{{ $hazard->risk_level_label }}</span></td>
                    <td class="px-4 py-3"><span class="badge-{{ $hazard->status_color }}">{{ $hazard->status_label }}</span></td>
                    <td class="px-4 py-3">
                        <a href="{{ route('hazards.show', $hazard) }}" class="text-blue-600 hover:underline text-xs font-medium">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-4 py-8 text-center text-slate-400">
                        <p class="text-sm">No hazards found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-slate-100 dark:border-slate-700">
        {{ $hazards->links() }}
    </div>
</div>
@endsection