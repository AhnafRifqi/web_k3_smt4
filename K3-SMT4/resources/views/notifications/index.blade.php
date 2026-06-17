@extends('layouts.app')

@section('title', 'Notifications')
@section('page-title', 'Notifications')
@section('page-subtitle', 'Your system notifications')

@section('content')
<div class="mb-4 flex items-center justify-between">
    <div>
        <a href="{{ route('notifications.mark-all-read') }}" class="btn-primary inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium" onclick="event.preventDefault(); document.getElementById('mark-all-read-form').submit();">
            Mark All as Read
        </a>
        <form id="mark-all-read-form" method="POST" action="{{ route('notifications.mark-all-read') }}" class="hidden">@csrf</form>
    </div>
</div>

<div class="space-y-3">
    @forelse($notifications as $notification)
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm p-4 {{ $notification->is_read ? '' : 'border-l-4 border-l-blue-500' }}">
        <div class="flex items-start justify-between gap-3">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xs font-semibold text-slate-400 uppercase">{{ $notification->type }}</span>
                    @if(!$notification->is_read)
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                    @endif
                </div>
                <h4 class="font-semibold text-slate-900 dark:text-white text-sm">{{ $notification->title }}</h4>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $notification->message }}</p>
                @if($notification->link)
                <a href="{{ $notification->link }}" class="text-xs text-blue-600 hover:underline mt-2 inline-block">View details &rarr;</a>
                @endif
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <span class="text-xs text-slate-400">{{ $notification->created_at->diffForHumans() }}</span>
                @if(!$notification->is_read)
                <form method="POST" action="{{ route('notifications.mark-read', $notification) }}" class="inline">
                    @csrf
                    <button type="submit" class="text-xs text-blue-600 hover:underline">Mark read</button>
                </form>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm p-8 text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        </div>
        <p class="text-sm font-medium text-slate-900 dark:text-white">No notifications</p>
        <p class="text-xs text-slate-500 mt-1">You're all caught up!</p>
    </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $notifications->links() }}
</div>
@endsection