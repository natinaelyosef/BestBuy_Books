<?php

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
            ->with(['customer', 'latestMessage', 'assignedAdmin'])
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

        $conversation->messages()
            ->whereNull('read_at')
            ->where('sender_id', '!=', $user->id)
            ->update(['read_at' => now()]);

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