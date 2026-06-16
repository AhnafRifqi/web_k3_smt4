<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->markAsRead();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    public function markAllRead()
    {
        auth()->user()->notifications()->unread()->update(['read_at' => now()]);

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    public function unreadCount()
    {
        $count = auth()->user()->notifications()->unread()->count();

        return response()->json(['count' => $count]);
    }

    public function latest()
    {
        $notifications = auth()->user()->notifications()
            ->latest()
            ->take(5)
            ->get();

        $unreadCount = auth()->user()->notifications()->unread()->count();

        if (request()->wantsJson()) {
            return response()->json([
                'notifications' => $notifications,
                'unread_count' => $unreadCount,
            ]);
        }

        return view('notifications.partials.dropdown', compact('notifications', 'unreadCount'));
    }
}