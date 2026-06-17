<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\AdminEmail;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    // User chat page
    public function index()
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.chat');
        }

        // Admin accounts
        $adminEmails = AdminEmail::pluck('email')->toArray();
        $admin = User::whereIn('email', $adminEmails)->first(); // We route user messages to the main admin

        if (!$admin) {
            // Fallback: use first user created as admin if none registered in table (should not happen due to seeder)
            $admin = User::first();
        }

        // Get recent messages
        $messages = Message::where(function($q) use ($user, $admin) {
            $q->where('sender_id', $user->id)->where('receiver_id', $admin->id);
        })->orWhere(function($q) use ($user, $admin) {
            $q->where('sender_id', $admin->id)->where('receiver_id', $user->id);
        })->orderBy('created_at', 'asc')->get();

        // Mark messages from admin as read
        Message::where('sender_id', $admin->id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('user.chat', compact('admin', 'messages'));
    }

    // Admin chat page
    public function adminChat(Request $request)
    {
        $admin = Auth::user();
        
        // Find all users who have chatted with the admin or who are customers
        // Contacts are users who are not admins
        $adminEmails = AdminEmail::pluck('email')->toArray();
        $contacts = User::whereNotIn('email', $adminEmails)
            ->withCount(['messagesSent as unread_count' => function($q) use ($admin) {
                $q->where('receiver_id', $admin->id)->where('is_read', false);
            }])
            ->addSelect([
                'latest_message_time' => Message::select('created_at')
                    ->where(function($q) use ($admin) {
                        $q->where(function($sq) use ($admin) {
                            $sq->whereColumn('sender_id', 'users.id')->where('receiver_id', $admin->id);
                        })->orWhere(function($sq) use ($admin) {
                            $sq->where('sender_id', $admin->id)->whereColumn('receiver_id', 'users.id');
                        });
                    })
                    ->orderByDesc('created_at')
                    ->limit(1)
            ])
            ->orderByRaw('latest_message_time DESC NULLS LAST')
            ->get();

        $activeContact = null;
        $messages = collect();

        if ($request->filled('contact_id')) {
            $activeContact = User::findOrFail($request->contact_id);
            
            $messages = Message::where(function($q) use ($admin, $activeContact) {
                $q->where('sender_id', $admin->id)->where('receiver_id', $activeContact->id);
            })->orWhere(function($q) use ($admin, $activeContact) {
                $q->where('sender_id', $activeContact->id)->where('receiver_id', $admin->id);
            })->orderBy('created_at', 'asc')->get();

            // Mark received messages as read
            Message::where('sender_id', $activeContact->id)
                ->where('receiver_id', $admin->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        return view('admin.chat', compact('contacts', 'activeContact', 'messages'));
    }

    // Get messages (AJAX Polling)
    public function getMessages(Request $request)
    {
        $request->validate([
            'other_id' => 'required|exists:users,id',
        ]);

        $user = Auth::user();
        $otherId = $request->other_id;

        // Fetch last 50 messages in the conversation (both sent and received)
        $messages = Message::where(function($q) use ($user, $otherId) {
            $q->where('sender_id', $user->id)->where('receiver_id', $otherId);
        })->orWhere(function($q) use ($user, $otherId) {
            $q->where('sender_id', $otherId)->where('receiver_id', $user->id);
        })
        ->orderBy('created_at', 'desc')
        ->limit(50)
        ->get()
        ->reverse()
        ->values();

        // Mark incoming messages as read
        Message::where('sender_id', $otherId)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }

    // Get unread counts (AJAX Polling)
    public function getUnreadCounts()
    {
        $admin = Auth::user();
        if (!$admin->isAdmin()) {
            return response()->json([]);
        }

        $adminEmails = AdminEmail::pluck('email')->toArray();
        $contacts = User::whereNotIn('email', $adminEmails)
            ->withCount(['messagesSent as unread_count' => function($q) use ($admin) {
                $q->where('receiver_id', $admin->id)->where('is_read', false);
            }])
            ->pluck('unread_count', 'id')
            ->toArray();

        return response()->json($contacts);
    }

    // Send message (AJAX)
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message_text' => 'required|string',
        ]);

        $user = Auth::user();
        $receiverId = $request->receiver_id;
        $receiver = User::findOrFail($receiverId);

        if ($user->id === $receiver->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak bisa mengirim pesan ke diri sendiri.'
            ], 400);
        }

        $message = Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'message_text' => $request->message_text,
            'is_read' => false,
        ]);

        // Send Notification
        $adminEmails = AdminEmail::pluck('email')->toArray();
        $isReceiverAdmin = in_value($receiver->email, $adminEmails) || AdminEmail::where('email', $receiver->email)->exists();

        if ($isReceiverAdmin) {
            // Notify all admins
            $admins = User::whereIn('email', $adminEmails)->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Pesan Baru dari Customer',
                    'message' => "Ada pesan baru dari {$user->name}: \"" . Str::limit($request->message_text, 35) . "\"",
                ]);
            }
        } else {
            // Notify customer
            Notification::create([
                'user_id' => $receiver->id,
                'title' => 'Pesan Baru dari Admin',
                'message' => "Admin AstridMart membalas pesan Anda: \"" . Str::limit($request->message_text, 35) . "\"",
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    // Edit message
    public function editMessage(Request $request, $id)
    {
        $request->validate([
            'message_text' => 'required|string',
        ]);

        $user = Auth::user();
        $message = Message::findOrFail($id);

        if ($message->sender_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.'
            ], 403);
        }

        $message->message_text = $request->message_text;
        $message->save();

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    // Delete message
    public function deleteMessage($id)
    {
        $user = Auth::user();
        $message = Message::findOrFail($id);

        if ($message->sender_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.'
            ], 403);
        }

        $message->delete();

        return response()->json([
            'success' => true
        ]);
    }
}

// Helper function to check item in array to prevent warning
function in_value($value, $array) {
    return in_array($value, $array);
}
