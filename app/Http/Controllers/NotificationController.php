<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /** JSON list for topbar polling */
    public function index()
    {
        $notifs = UserNotification::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        $unreadCount = $notifs->whereNull('read_at')->count();

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $notifs->map(fn($n) => [
                'id'           => $n->id,
                'type'         => $n->type,
                'icon'         => $n->icon,
                'title'        => $n->title,
                'body'         => $n->body,
                'action_url'   => $n->action_url,
                'action_label' => $n->action_label,
                'read_at'      => $n->read_at?->diffForHumans(),
                'created_at'   => $n->created_at->diffForHumans(),
                'is_read'      => !is_null($n->read_at),
            ]),
        ]);
    }

    /** Mark single notification read */
    public function markRead(UserNotification $notification)
    {
        abort_if($notification->user_id !== auth()->id(), 403);
        $notification->markRead();
        return response()->json(['ok' => true]);
    }

    /** Mark all read */
    public function markAllRead()
    {
        UserNotification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        return response()->json(['ok' => true]);
    }
}
