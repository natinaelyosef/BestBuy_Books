<?php
// app/Http/Controllers/AdminChatController.php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AdminChatController extends Controller
{
    /**
     * Display a listing of conversations for the admin.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get conversations where admin is involved (either assigned or general support)
        $conversations = ChatConversation::with([
                'customer', 
                'store',
                'latestMessage'
            ])
            ->where(function($query) use ($user) {
                // Conversations where admin is directly involved
                $query->where('assigned_admin_id', $user->id)
                      ->orWhereNull('assigned_admin_id'); // Or unassigned conversations
            })
            ->when($request->search, function ($query, $search) {
                $query->whereHas('customer', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('store', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhere('subject', 'like', "%{$search}%");
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

        return view('admin.chats.index', [
            'conversations' => $conversations,
            'search' => $request->search,
            'unread_total' => $user->unreadMessagesCount(),
        ]);
    }

    /**
     * Show a specific conversation.
     */
    public function show(Request $request, ChatConversation $conversation)
    {
        $user = $request->user();
        
        // Authorization - only allow admins who are assigned or can handle support chats
        $isAdminAssigned = $conversation->assigned_admin_id && $conversation->assigned_admin_id == $user->id;
        $isAdminCanAccess = in_array($user->account_type, ['sub_admin', 'super_admin']);
        
        if (!$isAdminAssigned && !$isAdminCanAccess) {
            abort(403, 'Unauthorized access to this conversation.');
        }

        // Load relationships
        $conversation->load(['customer', 'store', 'book']);

        // Mark messages as read
        $conversation->markAsRead($user->id);

        // Get messages
        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.chats.show', [
            'conversation' => $conversation,
            'messages' => $messages,
        ]);
    }

    /**
     * Send a new message in a conversation.
     */
    public function sendMessage(Request $request, ChatConversation $conversation)
    {
        $user = $request->user();
        
        // Authorization
        $isAdminAssigned = $conversation->assigned_admin_id && $conversation->assigned_admin_id == $user->id;
        $isAdminCanAccess = in_array($user->account_type, ['sub_admin', 'super_admin']);
        
        if (!$isAdminAssigned && !$isAdminCanAccess) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
        }

        try {
            $data = $request->validate([
                'message' => 'required_without:attachment|string|max:2000',
                'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt',
            ]);

            if (empty($data['message']) && !$request->hasFile('attachment')) {
                return response()->json(['success' => false, 'error' => 'Message or attachment required'], 422);
            }

            $messageData = [
                'conversation_id' => $conversation->id,
                'sender_id' => $user->id,
                'sender_role' => $user->account_type, // admin role
                'message' => $data['message'] ?? '',
                'is_read' => false,
            ];

            // Handle attachment
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $path = $file->store('chat-attachments/' . $conversation->id, 'public');
                
                $messageData['attachment_path'] = $path;
                $messageData['attachment_name'] = $file->getClientOriginalName();
                $messageData['attachment_size'] = $file->getSize();
                $messageData['attachment_type'] = $file->getMimeType();
            }

            $message = ChatMessage::create($messageData);

            // Update conversation
            $conversation->update([
                'last_message_at' => now(),
                'last_message' => $data['message'] ?? '[Attachment]',
                'last_message_sender_id' => $user->id,
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
                        'sender_role' => $message->sender_role,
                        'is_me' => true,
                        'attachment_url' => $message->attachment_url,
                        'attachment_name' => $message->attachment_name,
                        'is_image' => $message->isImage(),
                    ]
                ]);
            }

            return redirect()->route('admin.chats.show', $conversation)
                ->with('success', 'Message sent successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed: ' . implode(', ', $e->errors())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Admin chat message send error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to send message: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assign conversation to an admin.
     */
    public function assignToAdmin(Request $request, ChatConversation $conversation)
    {
        $user = $request->user();
        
        if (!in_array($user->account_type, ['sub_admin', 'super_admin'])) {
            abort(403, 'Only admins can assign conversations');
        }

        $request->validate([
            'admin_id' => 'required|exists:users,id'
        ]);

        $conversation->update([
            'assigned_admin_id' => $request->admin_id
        ]);

        return redirect()->back()->with('success', 'Conversation assigned successfully');
    }

    /**
     * Poll for new messages.
     */
    public function pollMessages(Request $request, ChatConversation $conversation)
    {
        $user = $request->user();
        
        // Authorization
        $isAdminAssigned = $conversation->assigned_admin_id && $conversation->assigned_admin_id == $user->id;
        $isAdminCanAccess = in_array($user->account_type, ['sub_admin', 'super_admin']);
        
        if (!$isAdminAssigned && !$isAdminCanAccess) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
        }

        try {
            $lastId = $request->last_id ?? 0;

            $messages = $conversation->messages()
                ->with('sender')
                ->where('id', '>', $lastId)
                ->orderBy('id', 'asc')
                ->get()
                ->map(function ($message) use ($user) {
                    return [
                        'id' => $message->id,
                        'content' => $message->message,
                        'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                        'formatted_time' => $message->created_at->format('g:i A'),
                        'sender_id' => $message->sender_id,
                        'sender_name' => $message->sender->name,
                        'sender_role' => $message->sender_role,
                        'is_me' => $message->sender_id === $user->id,
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
            Log::error('Admin poll messages error: ' . $e->getMessage());
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
        $user = $request->user();
        
        // Authorization
        $isAdminAssigned = $conversation->assigned_admin_id && $conversation->assigned_admin_id == $user->id;
        $isAdminCanAccess = in_array($user->account_type, ['sub_admin', 'super_admin']);
        
        if (!$isAdminAssigned && !$isAdminCanAccess) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
        }

        $count = $conversation->messages()
            ->where('sender_id', '!=', $user->id)
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