<?php
// app/Http/Controllers/AdminChatController.php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class AdminChatController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $conversations = ChatConversation::query()
            ->with(['customer', 'latestMessage', 'assignedAdmin']) // Make sure this relationship exists
            ->withCount([
                'messages as unread_count' => function ($query) use ($user) {
                    $query->whereNull('read_at')
                        ->where('sender_id', '!=', $user->id);
                },
            ])
            ->latest('updated_at')
            ->get();

        return view('admin.chats.index', ['conversations' => $conversations]);
    }

    public function show(Request $request, ChatConversation $conversation)
    {
        $user = $request->user();

        // Mark messages as read
        $conversation->messages()
            ->whereNull('read_at')
            ->where('sender_id', '!=', $user->id)
            ->update(['read_at' => now()]);

        // Load relationships
        $conversation->load(['customer', 'messages.sender', 'assignedAdmin']);

        return view('admin.chats.show', [
            'conversation' => $conversation,
            'messages' => $conversation->messages,
        ]);
    }

    public function sendMessage(Request $request, ChatConversation $conversation)
    {
        $user = $request->user();

        $data = $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'sender_role' => $user->account_type,
            'message' => $data['message'],
        ]);

        $conversation->update([
            'last_message_at' => now(),
            'last_message' => $data['message'],
            'last_message_sender_id' => $user->id,
            'assigned_admin_id' => $conversation->assigned_admin_id ?? $user->id,
        ]);

        return redirect()
            ->route('admin.chats.show', $conversation)
            ->with('status', 'Reply sent.');
    }

    public function unreadCount(Request $request)
    {
        $user = $request->user();

        $count = ChatMessage::query()
            ->whereNull('read_at')
            ->where('sender_id', '!=', $user->id)
            ->count();

        return response()->json(['count' => $count]);
    }
}