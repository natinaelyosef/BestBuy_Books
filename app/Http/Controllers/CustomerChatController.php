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

class CustomerChatController extends Controller
{
    /**
     * Display a listing of conversations for the customer.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $conversations = ChatConversation::with([
            'store', 
            'latestMessage', 
            'book'
        ])
        ->where('customer_id', $user->id)
        ->when($request->search, function ($query, $search) {
            $query->whereHas('store', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('subject', 'like', "%{$search}%");
        })
        ->when($request->status, function ($query, $status) {
            if ($status === 'unread') {
                $query->whereHas('messages', function ($q) use ($user) {
                    $q->where('sender_id', '!=', $user->id)
                      ->where('is_read', false);
                });
            }
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

        // Get stores that have books
        $recentStores = User::where('account_type', 'store_owner')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('books')
                    ->whereRaw('books.user_id = users.id');
            })
            ->limit(10)
            ->get();

        return view('customer.chat_list', [
            'conversations' => $conversations,
            'recentStores' => $recentStores,
            'search' => $request->search,
            'status_filter' => $request->status,
            'unread_total' => $user->unreadMessagesCount(),
        ]);
    }

    /**
     * Show a specific conversation.
     */
    public function show(Request $request, ChatConversation $conversation)
    {
        // Authorization
        if ($conversation->customer_id !== $request->user()->id) {
            abort(403, 'Unauthorized access to this conversation.');
        }

        // Load relationships
        $conversation->load(['store', 'book', 'messages.sender']);

        // Mark messages as read
        $conversation->markAsRead($request->user()->id);

        // Get messages
        $messages = $conversation->messages()
            ->with('sender')
            ->get();

        return view('customer.chat_room', [
            'conversation' => $conversation,
            'messages' => $messages,
        ]);
    }

    /**
     * Start a new conversation with a store.
     */
    public function startConversation(Request $request)
    {
        $request->validate([
            'store_id' => 'required|exists:users,id',
            'book_id' => 'nullable|exists:books,id',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // Verify store exists and is a store owner
        $store = User::where('id', $request->store_id)
            ->where('account_type', 'store_owner')
            ->first();

        if (!$store) {
            return back()->with('error', 'Invalid store selected.')->withInput();
        }

        // Check if conversation already exists between this customer and store
        $existingConversation = ChatConversation::where('customer_id', $request->user()->id)
            ->where('store_id', $request->store_id)
            ->when($request->book_id, function ($query) use ($request) {
                $query->where('book_id', $request->book_id);
            })
            ->first();

        if ($existingConversation) {
            // Add message to existing conversation
            ChatMessage::create([
                'conversation_id' => $existingConversation->id,
                'sender_id' => $request->user()->id,
                'sender_role' => 'customer',
                'message' => $request->message,
                'is_read' => false,
            ]);

            $existingConversation->update([
                'last_message_at' => now(),
                'last_message' => $request->message,
                'last_message_sender_id' => $request->user()->id,
            ]);

            return redirect()->route('customer.chat.show', ['conversation' => $existingConversation->id])
                ->with('success', 'Message sent to existing conversation.');
        }

        // Create new conversation
        $conversation = ChatConversation::create([
            'customer_id' => $request->user()->id,
            'store_id' => $request->store_id,
            'book_id' => $request->book_id,
            'subject' => $request->subject,
            'status' => 'active',
        ]);

        // Add first message
        ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $request->user()->id,
            'sender_role' => 'customer',
            'message' => $request->message,
            'is_read' => false,
        ]);

        $conversation->update([
            'last_message_at' => now(),
            'last_message' => $request->message,
            'last_message_sender_id' => $request->user()->id,
        ]);

        return redirect()->route('customer.chat.show', ['conversation' => $conversation->id])
            ->with('success', 'Conversation started successfully.');
    }

    /**
     * Start a conversation with a specific store owner (from book detail page)
     */
    public function startWithStore(Request $request, $storeId)
    {
        $user = $request->user();
        $store = User::where('id', $storeId)
            ->where('account_type', 'store_owner')
            ->firstOrFail();
        
        $bookId = $request->input('book');
        $messagePreview = $request->input('message_preview', '');
        
        // Check if conversation already exists
        $existingConversation = ChatConversation::where('customer_id', $user->id)
            ->where('store_id', $store->id)
            ->when($bookId, function ($query) use ($bookId) {
                $query->where('book_id', $bookId);
            })
            ->latest()
            ->first();
        
        if ($existingConversation) {
            // If there's a message preview, add it as a new message
            if (!empty($messagePreview)) {
                ChatMessage::create([
                    'conversation_id' => $existingConversation->id,
                    'sender_id' => $user->id,
                    'sender_role' => 'customer',
                    'message' => $messagePreview,
                ]);
                
                $existingConversation->update([
                    'last_message_at' => now(),
                    'last_message' => $messagePreview,
                    'last_message_sender_id' => $user->id,
                ]);
            }
            
            return redirect()->route('customer.chat.show', ['conversation' => $existingConversation->id]);
        }
        
        // Create new conversation
        $conversation = ChatConversation::create([
            'customer_id' => $user->id,
            'store_id' => $store->id,
            'book_id' => $bookId ?: null,
            'subject' => $bookId ? 'Question about book' : 'General inquiry',
            'status' => 'active',
            'last_message_at' => now(),
        ]);
        
        // Add initial message if provided
        if (!empty($messagePreview)) {
            ChatMessage::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $user->id,
                'sender_role' => 'customer',
                'message' => $messagePreview,
            ]);
            
            $conversation->update([
                'last_message' => $messagePreview,
                'last_message_sender_id' => $user->id,
            ]);
        } else {
            // Add a default welcome message
            $book = $bookId ? Book::find($bookId) : null;
            $defaultMessage = $book 
                ? "Hello, I'm interested in your book: {$book->title}"
                : "Hello, I have a question about your store.";
                
            ChatMessage::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $user->id,
                'sender_role' => 'customer',
                'message' => $defaultMessage,
            ]);
            
            $conversation->update([
                'last_message' => $defaultMessage,
                'last_message_sender_id' => $user->id,
            ]);
        }
        
        return redirect()->route('customer.chat.show', ['conversation' => $conversation->id])
            ->with('status', 'Conversation started with the store owner.');
    }

    /**
     * Send a new message in a conversation.
     */
    public function sendMessage(Request $request, ChatConversation $conversation)
    {
        // Authorization - only allow the customer who owns the conversation
        if ($conversation->customer_id !== $request->user()->id) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
        }

        // Validate request
        $request->validate([
            'message' => 'required_without:attachment|string|max:2000',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt',
        ]);

        $data = [
            'conversation_id' => $conversation->id,
            'sender_id' => $request->user()->id,
            'sender_role' => 'customer',
            'is_read' => false,
        ];

        if ($request->filled('message')) {
            $data['message'] = $request->message;
        }

        // Handle attachment
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store("chat-attachments/{$conversation->id}", 'public');
            
            $data['attachment_path'] = $path;
            $data['attachment_name'] = $file->getClientOriginalName();
            $data['attachment_size'] = $file->getSize();
            $data['attachment_type'] = $file->getMimeType();
        }

        // Create message
        $message = ChatMessage::create($data);

        // Update conversation
        $conversation->update([
            'last_message_at' => now(),
            'last_message' => $request->message ?? '[Attachment]',
            'last_message_sender_id' => $request->user()->id,
        ]);

        $message->load('sender');

        // For AJAX request
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'content' => $message->message,
                    'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                    'formatted_time' => $message->created_at->format('g:i A'),
                    'sender_id' => $message->sender_id,
                    'sender_name' => $message->sender->name,
                    'is_me' => true,
                    'attachment_url' => $message->attachment_url,
                    'attachment_name' => $message->attachment_name,
                    'is_image' => $message->isImage(),
                ]
            ]);
        }

        return redirect()->route('customer.chat.show', ['conversation' => $conversation->id])
            ->with('success', 'Message sent successfully.');
    }

    /**
     * Mark messages as read via AJAX.
     */
    public function markAsRead(Request $request, ChatConversation $conversation)
    {
        if ($conversation->customer_id !== $request->user()->id) {
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
    public function pollMessages(Request $request, ChatConversation $conversation)
    {
        // Authorization - only allow the customer who owns the conversation
        if ($conversation->customer_id !== $request->user()->id) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
        }

        try {
            $lastId = $request->last_id ?? 0;

            $messages = $conversation->messages()
                ->with('sender')
                ->where('id', '>', $lastId)
                ->orderBy('id', 'asc')
                ->get()
                ->map(function ($message) use ($request) {
                    return [
                        'id' => $message->id,
                        'content' => $message->message,
                        'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                        'formatted_time' => $message->created_at->format('g:i A'),
                        'sender_id' => $message->sender_id,
                        'sender_name' => $message->sender->name,
                        'sender_role' => $message->sender_role,
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
            Log::error('Customer poll messages error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to poll messages'
            ], 500);
        }
    }

    /**
     * Get list of stores for starting a new chat.
     */
    public function getStores(Request $request)
    {
        try {
            $stores = User::where('account_type', 'store_owner')
                ->withCount('books')
                ->when($request->search, function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                })
                ->limit(20)
                ->get()
                ->map(function ($store) {
                    return [
                        'id' => $store->id,
                        'name' => $store->name,
                        'avatar' => $store->avatar,
                        'books_count' => $store->books_count,
                    ];
                });

            return response()->json($stores);
        } catch (\Exception $e) {
            Log::error('Get stores error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch stores'], 500);
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
}