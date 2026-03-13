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
        $conversation->load(['store', 'book']);

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
     * Send a new message in a conversation.
     */
    public function sendMessage(Request $request, ChatConversation $conversation)
    {
        try {
            // Authorization
            if ($conversation->customer_id !== $request->user()->id) {
                return response()->json([
                    'success' => false, 
                    'error' => 'Unauthorized access to this conversation.'
                ], 403);
            }

            // Validate request
            $request->validate([
                'message' => 'nullable|string|max:2000',
                'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt,zip',
            ]);

            // Check if either message or attachment is provided
            if (!$request->filled('message') && !$request->hasFile('attachment')) {
                return response()->json([
                    'success' => false, 
                    'error' => 'Please provide a message or attachment.'
                ], 422);
            }

            $data = [
                'conversation_id' => $conversation->id,
                'sender_id' => $request->user()->id,
                'sender_role' => 'customer',
                'is_read' => false,
                'message' => $request->input('message', ''),
            ];

            // Handle attachment
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                
                // Create directory if it doesn't exist
                $path = 'chat-attachments/' . $conversation->id;
                
                // Store file
                $filePath = $file->store($path, 'public');
                
                $data['attachment_path'] = $filePath;
                $data['attachment_name'] = $file->getClientOriginalName();
                $data['attachment_size'] = $file->getSize();
                $data['attachment_type'] = $file->getMimeType();
                
                Log::info('File uploaded', [
                    'path' => $filePath,
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize()
                ]);
            }

            // Create message
            $message = ChatMessage::create($data);

            // Update conversation
            $conversation->update([
                'last_message_at' => now(),
                'last_message' => $request->input('message') ?: '[Attachment]',
                'last_message_sender_id' => $request->user()->id,
            ]);

            // Load sender for response
            $message->load('sender');

            // Prepare response
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
                    'attachment_size' => $message->attachment_size,
                    'is_image' => $message->isImage(),
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed: ' . implode(', ', $e->errors())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Chat message send error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to send message: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Start a new conversation with a store.
     */
    public function startConversation(Request $request)
    {
        try {
            $request->validate([
                'store_id' => 'required|exists:users,id',
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

            // Check if conversation already exists
            $existingConversation = ChatConversation::where('customer_id', $request->user()->id)
                ->where('store_id', $request->store_id)
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

                return redirect()->route('chat.show', $existingConversation)
                    ->with('success', 'Message sent to existing conversation.');
            }

            // Create new conversation
            $conversation = ChatConversation::create([
                'customer_id' => $request->user()->id,
                'store_id' => $request->store_id,
                'subject' => $request->subject,
                'status' => 'active',
            ]);

            // Create first message
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

            return redirect()->route('chat.show', $conversation)
                ->with('success', 'Conversation started successfully.');

        } catch (\Exception $e) {
            Log::error('Start conversation error: ' . $e->getMessage());
            return back()->with('error', 'Failed to start conversation. Please try again.')->withInput();
        }
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
        try {
            if ($conversation->customer_id !== $request->user()->id) {
                return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
            }

            $lastId = $request->last_id ?? 0;

            $messages = $conversation->messages()
                ->with('sender')
                ->where('id', '>', $lastId)
                ->get()
                ->map(function ($message) use ($request) {
                    return [
                        'id' => $message->id,
                        'content' => $message->message,
                        'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                        'formatted_time' => $message->created_at->format('g:i A'),
                        'sender_id' => $message->sender_id,
                        'sender_name' => $message->sender->name,
                        'is_me' => $message->sender_id === $request->user()->id,
                        'attachment_url' => $message->attachment_url,
                        'attachment_name' => $message->attachment_name,
                        'attachment_size' => $message->attachment_size,
                        'is_image' => $message->isImage(),
                    ];
                });

            return response()->json([
                'success' => true,
                'messages' => $messages
            ]);

        } catch (\Exception $e) {
            Log::error('Poll messages error: ' . $e->getMessage());
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