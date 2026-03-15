<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StoreChatController extends Controller
{
    /**
     * Display a listing of conversations for the store owner.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $conversations = ChatConversation::with([
                'customer', 
                'latestMessage', 
                'book'
            ])
            ->where('store_id', $user->id)
            ->when($request->search, function ($query, $search) {
                $query->whereHas('customer', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('subject', 'like', "%{$search}%");
            })
            ->when($request->book_id, function ($query, $bookId) {
                $query->where('book_id', $bookId);
            })
            ->latest('updated_at')
            ->get();

        // Get unread counts
        foreach ($conversations as $conv) {
            $conv->unread_count = $conv->messages()
                ->where('sender_id', '!=', $user->id)
                ->where('is_read', false)
                ->count();
        }

        // Get store's books for filtering
        $books = Book::where('user_id', $user->id)->get();

        return view('store.chat_list', [
            'conversations' => $conversations,
            'books' => $books,
            'search' => $request->search,
            'selected_book' => $request->book_id,
            'unread_total' => $user->unreadMessagesCount(),
        ]);
    }

    /**
     * Show a specific conversation.
     */
    public function show(Request $request, ChatConversation $conversation)
    {
        // Authorization
        if ($conversation->store_id !== $request->user()->id) {
            abort(403, 'Unauthorized access to this conversation.');
        }

        // Load relationships
        $conversation->load(['customer', 'book']);

        // Mark messages as read
        $conversation->markAsRead($request->user()->id);

        // Get messages
        $messages = $conversation->messages()
            ->with('sender')
            ->get();

        // Get other conversations for sidebar
        $otherConversations = ChatConversation::with(['customer', 'book'])
            ->where('store_id', $request->user()->id)
            ->where('id', '!=', $conversation->id)
            ->latest('updated_at')
            ->limit(5)
            ->get();

        return view('store.chat_room', [
            'conversation' => $conversation,
            'messages' => $messages,
            'otherConversations' => $otherConversations,
        ]);
    }

    /**
     * Send a new message in a conversation.

 * Send a message in a conversation.
 */


public function sendMessage(Request $request, ChatConversation $conversation)
{
    // Authorization
    if ($conversation->store_id !== $request->user()->id) {
        return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
    }

    try {
        $data = $request->validate([
            'message' => 'nullable|string|max:2000',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt',
        ]);

        if (empty($data['message']) && !$request->hasFile('attachment')) {
            return response()->json(['success' => false, 'error' => 'Message or attachment required'], 422);
        }

        $messageData = [
            'conversation_id' => $conversation->id,
            'sender_id' => $request->user()->id,
            'sender_role' => 'store_owner',
            'message' => $data['message'] ?? '',
        ];

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('chat-attachments', 'public');
            
            $messageData['attachment_path'] = $path;
            $messageData['attachment_name'] = $file->getClientOriginalName();
            $messageData['attachment_size'] = $file->getSize();
            $messageData['attachment_type'] = $file->getMimeType();
        }

        $message = ChatMessage::create($messageData);

        $conversation->update([
            'last_message_at' => now(),
            'last_message' => $data['message'] ?? '[Attachment]',
            'last_message_sender_id' => $request->user()->id,
        ]);

        $message->load('sender');

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'content' => $message->message,
                'created_at' => $message->created_at->format('g:i A'),
                'is_me' => true,
                'attachment_url' => $message->attachment_url,
                'attachment_name' => $message->attachment_name,
                'is_image' => $message->isImage(),
            ]
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'error' => 'Validation failed: ' . implode(', ', $e->errors())
        ], 422);
    } catch (\Exception $e) {
        \Log::error('Store send message error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
        return response()->json([
            'success' => false,
            'error' => 'Failed to send message: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Poll for new messages.
 */
public function pollMessages(Request $request, ChatConversation $conversation)
{
    if ($conversation->store_id !== $request->user()->id) {
        return response()->json(['success' => false], 403);
    }

    try {
        $lastId = $request->input('last_id', 0);
        
        $messages = $conversation->messages()
            ->with('sender')
            ->where('id', '>', $lastId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) use ($request) {
                return [
                    'id' => $message->id,
                    'content' => $message->message,
                    'created_at' => $message->created_at->format('g:i A'),
                    'formatted_time' => $message->created_at->format('g:i A'),
                    'is_me' => $message->sender_id === $request->user()->id,
                    'attachment_url' => $message->attachment_url,
                    'attachment_name' => $message->attachment_name,
                    'is_image' => $message->isImage(),
                ];
            });

        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    } catch (\Exception $e) {
        \Log::error('Store poll messages error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => 'Failed to poll messages'
        ], 500);
    }
}

    /**
     * Mark messages as read via AJAX.
     */
    public function markAsRead(Request $request, ChatConversation $conversation)
    {
        if ($conversation->store_id !== $request->user()->id) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
        }

        $count = $conversation->messages()
            ->where('sender_id', '!=', $request->user()->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'is_read_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'marked_count' => $count
        ]);
    }

    /**
     * Poll for new messages.
 
        */
    public function startConversation(Request $request)
    {
        try {
            $request->validate([
                'customer_id' => 'required|exists:users,id',
                'book_id' => 'nullable|exists:books,id',
                'subject' => 'nullable|string|max:255',
                'message' => 'required|string|max:2000',
            ]);

            // Verify customer exists
            $customer = User::where('id', $request->customer_id)
                ->where('account_type', 'customer')
                ->first();

            if (!$customer) {
                return back()->with('error', 'Invalid customer selected.')->withInput();
            }

            // Check if conversation already exists
            $existingConversation = ChatConversation::where('store_id', $request->user()->id)
                ->where('customer_id', $request->customer_id)
                ->when($request->book_id, function ($query, $bookId) {
                    $query->where('book_id', $bookId);
                })
                ->first();

            if ($existingConversation) {
                // Add message to existing conversation
                ChatMessage::create([
                    'conversation_id' => $existingConversation->id,
                    'sender_id' => $request->user()->id,
                    'sender_role' => 'store_owner',
                    'message' => $request->message,
                    'is_read' => false,
                ]);

                $existingConversation->update([
                    'last_message_at' => now(),
                    'last_message' => $request->message,
                    'last_message_sender_id' => $request->user()->id,
                ]);

                return redirect()->route('store.chat.show', $existingConversation)
                    ->with('success', 'Message sent to existing conversation.');
            }

            // Create new conversation
            $conversation = ChatConversation::create([
                'customer_id' => $request->customer_id,
                'store_id' => $request->user()->id,
                'book_id' => $request->book_id,
                'subject' => $request->subject,
                'status' => 'active',
            ]);

            // Create first message
            ChatMessage::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $request->user()->id,
                'sender_role' => 'store_owner',
                'message' => $request->message,
                'is_read' => false,
            ]);

            $conversation->update([
                'last_message_at' => now(),
                'last_message' => $request->message,
                'last_message_sender_id' => $request->user()->id,
            ]);

            return redirect()->route('store.chat.show', $conversation)
                ->with('success', 'Conversation started successfully.');

        } catch (\Exception $e) {
            Log::error('Store start conversation error: ' . $e->getMessage());
            return back()->with('error', 'Failed to start conversation. Please try again.')->withInput();
        }
    }

    /**
     * Get unread count for AJAX polling.
     */
    public function getUnreadCount(Request $request)
    {
        try {
            $user = $request->user();
            return response()->json([
                'unread_count' => $user->unreadMessagesCount()
            ]);
        } catch (\Exception $e) {
            return response()->json(['unread_count' => 0]);
        }
    }

    /**
     * Delete a conversation.
     */
    public function destroy(Request $request, ChatConversation $conversation)
    {
        if ($conversation->store_id !== $request->user()->id) {
            abort(403);
        }

        // Delete attachments
        foreach ($conversation->messages as $message) {
            if ($message->attachment_path) {
                Storage::disk('public')->delete($message->attachment_path);
            }
        }

        $conversation->delete();

        return redirect()->route('store.chat.index')
            ->with('success', 'Conversation deleted successfully.');
    }
}