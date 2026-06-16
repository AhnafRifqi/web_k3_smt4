@extends('layouts.app')

@section('title', 'Activity Logs')
@section('page-title', 'Activity Logs')
@section('page-subtitle', 'System audit trail and activity records')

@section('content')
<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm overflow-hidden">
    <div class="p-4 border-b border-slate-100 dark:border-slate-700">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <select name="module" class="form-input">
                <option value="">All Modules</option>
                @foreach($modules as $mod)
                <option value="{{ $mod }}" @selected(request('module') === $mod)>{{ ucfirst($mod) }}</option>
                @endforeach
            </select>
            <input type="text" name="action" placeholder="Search action..." value="{{ request('action') }}" class="form-input">
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input">
            <button type="submit" class="btn-primary bg-blue-600 text-white rounded-lg px-4 py-2 text-sm font-medium">Filter</button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-900/50">
                <tr>
                    <th class="text-left px-4 py-3 font-semibold text-slate-600">Timestamp</th>
                    <th class="text-left px-4 py-3 font-semibold text-slate-600">User</th>
                    <th class="text-left px-4 py-3 font-semibold text-slate-600">Module</th>
                    <th class="text-left px-4 py-3 font-semibold text-slate-600">Action</th>
                    <th class="text-left px-4 py-3 font-semibold text-slate-600">Description</th>
                    <th class="text-left px-4 py-3 font-semibold text-slate-600">IP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($logs as $log)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                    <td class="px-4 py-3 text-xs">{{ $log->created_at->format('d M Y H:i:s') }}</td>
                    <td class="px-4 py-3">
                        <span class="font-medium">{{ $log->user_name }}</span>
                        <span class="text-xs text-slate-400 block">{{ $log->user_role }}</span>
                    </td>
                    <td class="px-4 py-3"><span class="badge-blue">{{ $log->module }}</span></td>
                    <td class="px-4 py-3 text-xs font-mono">{{ $log->action }}</td>
                    <td class="px-4 py-3 max-w-xs truncate">{{ $log->description }}</td>
                    <td class="px-4 py-3 text-xs text-slate-400">{{ $log->ip_address ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-slate-400">
                        <p class="text-sm">No activity logs found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-slate-100 dark:border-slate-700">
        {{ $logs->links() }}
    </div>
</div>
@endsection