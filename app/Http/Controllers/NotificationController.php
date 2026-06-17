<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $notifications = Notification::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('user.notifications', compact('notifications'));
    }

    public function getUnreadCount()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['count' => 0]);
        }

        $count = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    public function markAllAsRead()
    {
        $user = Auth::user();
        Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'Semua notifikasi ditandai sebagai dibaca.');
    }

    public function apiGetNotifications()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([]);
        }

        $notifications = Notification::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return response()->json($notifications);
    }
}
