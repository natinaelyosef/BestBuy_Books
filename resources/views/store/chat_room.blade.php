@extends('store.registration-layout')

@section('title', 'Chat with ' . ($conversation->customer->name ?? 'Customer'))

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('store.chat.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Chats
        </a>
    </div>

    <!-- Chat Room -->
    <div class="row">
        <!-- Main Chat Area -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <!-- Chat Header -->
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-circle bg-primary text-white">
                            {{ substr($conversation->customer->name ?? 'C', 0, 1) }}
                        </div>
                        <div>
                            <h5 class="mb-1">{{ $conversation->customer->name ?? 'Customer' }}</h5>
                            <p class="small text-muted mb-0">
                                <i class="bi bi-envelope me-1"></i>
                                {{ $conversation->customer->email ?? 'No email' }}
                            </p>
                        </div>
                    </div>
                    <div>
                        @if($conversation->book)
                            <span class="badge bg-info">
                                <i class="bi bi-book me-1"></i>
                                {{ $conversation->book->title }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Messages -->
                <div class="card-body" id="messages-container" style="height: 500px; overflow-y: auto;">
                    @foreach($messages as $message)
                        <div class="message-item mb-3 {{ $message->sender_id === auth()->id() ? 'text-end' : '' }}">
                            <div class="d-inline-block" style="max-width: 70%;">
                                <div class="message-bubble p-3 rounded-3 {{ $message->sender_id === auth()->id() ? 'bg-primary text-white' : 'bg-light' }}">
                                    @if($message->message)
                                        <p class="mb-1">{{ $message->message }}</p>
                                    @endif
                                    
                                    @if($message->hasAttachment())
                                        <div class="attachment-preview mt-2 p-2 {{ $message->sender_id === auth()->id() ? 'bg-primary bg-opacity-25' : 'bg-white' }} rounded-2">
                                            @if($message->isImage())
                                                <img src="{{ $message->attachment_url }}" 
                                                     alt="Attachment" 
                                                     class="img-fluid rounded-2 mb-2"
                                                     style="max-height: 150px; cursor: pointer;"
                                                     onclick="window.open('{{ $message->attachment_url }}', '_blank')">
                                            @endif
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi {{ $message->isImage() ? 'bi-image' : 'bi-file-earmark' }}"></i>
                                                <small class="text-truncate" style="max-width: 200px;">
                                                    {{ $message->attachment_name }}
                                                </small>
                                                <a href="{{ $message->attachment_url }}" 
                                                   class="{{ $message->sender_id === auth()->id() ? 'text-white' : 'text-primary' }}"
                                                   target="_blank"
                                                   download>
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <small class="d-block mt-2 {{ $message->sender_id === auth()->id() ? 'text-white-50' : 'text-muted' }}">
                                        {{ $message->created_at->format('g:i A') }}
                                        @if($message->sender_id === auth()->id())
                                            @if($message->is_read)
                                                <i class="bi bi-check2-all ms-1" title="Read"></i>
                                            @else
                                                <i class="bi bi-check2 ms-1" title="Sent"></i>
                                            @endif
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Message Input -->
                <div class="card-footer bg-white py-3">
                    <form id="message-form" enctype="multipart/form-data" class="d-flex gap-2">
                        @csrf
                        <div class="flex-grow-1">
                            <textarea name="message" 
                                      id="message-input"
                                      class="form-control" 
                                      rows="2" 
                                      placeholder="Type your message..."
                                      style="resize: none;"></textarea>
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-paperclip"></i>
                                </button>
                                <div class="dropdown-menu p-3" style="min-width: 300px;">
                                    <input type="file" 
                                           id="attachment-input"
                                           name="attachment" 
                                           class="form-control"
                                           accept="image/*,.pdf,.doc,.docx,.txt">
                                    <div class="form-text mt-2">
                                        Max size: 10MB
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" id="send-btn">
                                <i class="bi bi-send-fill"></i>
                            </button>
                        </div>
                    </form>
                    <div id="attachment-preview" class="mt-2" style="display: none;">
                        <div class="alert alert-info d-flex align-items-center justify-content-between py-2">
                            <span id="attachment-name"></span>
                            <button type="button" class="btn-close" onclick="clearAttachment()"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Conversation Info -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Conversation Info</h5>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-2">
                        <i class="bi bi-calendar3 me-2"></i>
                        Started: {{ $conversation->created_at->format('M d, Y g:i A') }}
                    </p>
                    
                    @if($conversation->last_message_at)
                        <p class="small text-muted mb-3">
                            <i class="bi bi-clock-history me-2"></i>
                            Last activity: {{ $conversation->last_message_at->diffForHumans() }}
                        </p>
                    @endif
                    
                    <hr>
                    
                    <h6 class="fw-bold mb-2">Customer Details</h6>
                    <p class="small mb-1">
                        <strong>Name:</strong> {{ $conversation->customer->name ?? 'N/A' }}
                    </p>
                    <p class="small mb-1">
                        <strong>Email:</strong> {{ $conversation->customer->email ?? 'N/A' }}
                    </p>
                    <p class="small mb-1">
                        <strong>Joined:</strong> {{ $conversation->customer->created_at?->format('M d, Y') ?? 'N/A' }}
                    </p>
                    
                    @if($conversation->book)
                        <hr>
                        <h6 class="fw-bold mb-2">Book Reference</h6>
                        <p class="small mb-1">
                            <strong>Title:</strong> {{ $conversation->book->title }}
                        </p>
                        <p class="small mb-1">
                            <strong>Author:</strong> {{ $conversation->book->author }}
                        </p>
                        <a href="#" class="btn btn-sm btn-outline-primary mt-2">
                            <i class="bi bi-book me-1"></i>View Book
                        </a>
                    @endif
                    
                    <hr>
                    
                    <form action="{{ route('store.chat.destroy', $conversation) }}" 
                          method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this conversation?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-trash me-2"></i>Delete Conversation
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1rem;
}
.message-bubble {
    word-wrap: break-word;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
.bg-primary .text-white-50 {
    color: rgba(255,255,255,0.7) !important;
}
.attachment-preview {
    border: 1px solid rgba(0,0,0,0.1);
}
#messages-container {
    scroll-behavior: smooth;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const conversationId = {{ $conversation->id }};
    const messagesContainer = document.getElementById('messages-container');
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const sendBtn = document.getElementById('send-btn');
    const attachmentInput = document.getElementById('attachment-input');
    const attachmentPreview = document.getElementById('attachment-preview');
    const attachmentName = document.getElementById('attachment-name');
    
    let lastMessageId = {{ $messages->last()->id ?? 0 }};
    let pollingInterval;
    
    // Scroll to bottom on load
    scrollToBottom();
    
    // Auto-resize textarea
    messageInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
    
    // Handle file selection
    attachmentInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            const file = this.files[0];
            attachmentName.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
            attachmentPreview.style.display = 'block';
        } else {
            clearAttachment();
        }
    });
    
    // Handle form submission
    messageForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData();
        const message = messageInput.value.trim();
        const attachment = attachmentInput.files[0];
        
        if (!message && !attachment) {
            return;
        }
        
        if (message) {
            formData.append('message', message);
        }
        
        if (attachment) {
            formData.append('attachment', attachment);
        }
        
        // Disable form
        setFormDisabled(true);
        
        try {
            const response = await fetch(`/store/chat/${conversationId}/send`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Clear form
                messageInput.value = '';
                messageInput.style.height = 'auto';
                clearAttachment();
                
                // Append new message
                appendMessage(data.message);
                scrollToBottom();
            } else {
                alert('Failed to send message: ' + data.error);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to send message');
        } finally {
            setFormDisabled(false);
        }
    });
    
    // Poll for new messages
    function startPolling() {
        pollingInterval = setInterval(pollMessages, 3000);
    }
    
    async function pollMessages() {
        try {
            const response = await fetch(`/store/chat/${conversationId}/poll?last_id=${lastMessageId}`);
            const data = await response.json();
            
            if (data.success && data.messages.length > 0) {
                data.messages.forEach(msg => {
                    appendMessage(msg);
                    lastMessageId = Math.max(lastMessageId, msg.id);
                });
                scrollToBottom();
            }
        } catch (error) {
            console.error('Polling error:', error);
        }
    }
    
    function appendMessage(message) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message-item mb-3 ${message.is_me ? 'text-end' : ''}`;
        
        let attachmentHtml = '';
        if (message.attachment_url) {
            attachmentHtml = `
                <div class="attachment-preview mt-2 p-2 ${message.is_me ? 'bg-primary bg-opacity-25' : 'bg-white'} rounded-2">
                    ${message.is_image ? 
                        `<img src="${message.attachment_url}" alt="Attachment" class="img-fluid rounded-2 mb-2" style="max-height: 150px; cursor: pointer;" onclick="window.open('${message.attachment_url}', '_blank')">` 
                        : ''
                    }
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi ${message.is_image ? 'bi-image' : 'bi-file-earmark'}"></i>
                        <small class="text-truncate" style="max-width: 200px;">${message.attachment_name}</small>
                        <a href="${message.attachment_url}" class="${message.is_me ? 'text-white' : 'text-primary'}" target="_blank" download>
                            <i class="bi bi-download"></i>
                        </a>
                    </div>
                </div>
            `;
        }
        
        messageDiv.innerHTML = `
            <div class="d-inline-block" style="max-width: 70%;">
                <div class="message-bubble p-3 rounded-3 ${message.is_me ? 'bg-primary text-white' : 'bg-light'}">
                    ${message.content ? `<p class="mb-1">${escapeHtml(message.content)}</p>` : ''}
                    ${attachmentHtml}
                    <small class="d-block mt-2 ${message.is_me ? 'text-white-50' : 'text-muted'}">
                        ${message.formatted_time}
                        ${message.is_me ? '<i class="bi bi-check2 ms-1" title="Sent"></i>' : ''}
                    </small>
                </div>
            </div>
        `;
        
        messagesContainer.appendChild(messageDiv);
    }
    
    function setFormDisabled(disabled) {
        messageInput.disabled = disabled;
        attachmentInput.disabled = disabled;
        sendBtn.disabled = disabled;
        if (disabled) {
            sendBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        } else {
            sendBtn.innerHTML = '<i class="bi bi-send-fill"></i>';
        }
    }
    
    function clearAttachment() {
        attachmentInput.value = '';
        attachmentPreview.style.display = 'none';
        attachmentName.textContent = '';
    }
    
    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
    
    function escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
    
    // Start polling
    startPolling();
    
    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
        }
    });
});
</script>
@endsection