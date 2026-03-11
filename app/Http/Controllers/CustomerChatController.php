<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class CustomerChatController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $conversations = ChatConversation::query()
            ->where('customer_id', $user->id)
            ->with(['latestMessage', 'assignedAdmin'])
            ->withCount([
                'messages as unread_count' => function ($query) use ($user) {
                    $query->whereNull('read_at')
                        ->where('sender_id', '!=', $user->id);
                },
            ])
            ->latest('updated_at')
            ->get();

        return view('customer.chat_index', [
            'conversations' => $conversations,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        $conversation = ChatConversation::create([
            'customer_id' => $user->id,
            'subject' => $data['subject'],
            'status' => 'open',
            'last_message_at' => now(),
        ]);

        ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'sender_role' => $user->account_type,
            'message' => $data['message'],
        ]);

        return redirect()
            ->route('chat.show', $conversation)
            ->with('status', 'Chat started successfully.');
    }

    public function show(Request $request, ChatConversation $conversation)
    {
        $user = $request->user();

        if ((int) $conversation->customer_id !== (int) $user->id) {
            abort(403);
        }

        $conversation->messages()
            ->whereNull('read_at')
            ->where('sender_id', '!=', $user->id)
            ->update(['read_at' => now()]);

        $conversation->load(['messages.sender', 'assignedAdmin']);

        return view('customer.chat_show', [
            'conversation' => $conversation,
            'messages' => $conversation->messages,
        ]);
    }

    public function sendMessage(Request $request, ChatConversation $conversation)
    {
        $user = $request->user();

        if ((int) $conversation->customer_id !== (int) $user->id) {
            abort(403);
        }

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
        ]);

        return redirect()
            ->route('chat.show', $conversation)
            ->with('status', 'Message sent.');
    }

    public function unreadCount(Request $request)
    {
        $user = $request->user();

        $count = ChatMessage::query()
            ->whereHas('conversation', function ($query) use ($user) {
                $query->where('customer_id', $user->id);
            })
            ->whereNull('read_at')
            ->where('sender_id', '!=', $user->id)
            ->count();

        return response()->json(['count' => $count]);
    }
}
