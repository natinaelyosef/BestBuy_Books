@extends('store.registration-layout')
@section('title', 'Chat with ' . ($conversation->customer->name ?? 'Customer'))
@section('content')
<div class="container-fluid" style="max-width: 1200px;">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('store.chat.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Chats
        </a>
    </div>

    <!-- Chat Room -->
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
                        @if($conversation->customer?->isOnline())
                            <span class="text-success"><i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>Online</span>
                        @else
                            <span class="text-muted"><i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>Offline</span>
                        @endif
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
        <div class="card-body" id="messages-container" style="height: 500px; overflow-y: auto; background: #f8f9fa;">
            @forelse($messages as $message)
                <div class="message-item mb-3 {{ $message->sender_id === auth()->id() ? 'text-end' : '' }}">
                    <div class="d-inline-block" style="max-width: 70%;">
                        <div class="message-bubble p-3 rounded-3 {{ $message->sender_id === auth()->id() ? 'bg-primary text-white' : 'bg-white' }}">
                            @if($message->message)
                                <p class="mb-1">{{ $message->message }}</p>
                            @endif
                            
                            @if($message->hasAttachment())
                                <div class="attachment-preview mt-2 p-2 {{ $message->sender_id === auth()->id() ? 'bg-primary bg-opacity-25' : 'bg-light' }} rounded-2">
                                    @if($message->isImage())
                                        <img src="{{ $message->attachment_url }}" 
                                             alt="Attachment" 
                                             class="img-fluid rounded-2 mb-2"
                                             style="max-height: 150px; max-width: 100%; cursor: pointer;"
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
            @empty
                <div class="text-center text-muted py-5">
                    <i class="bi bi-chat-dots display-1"></i>
                    <p class="mt-3">No messages yet. Start the conversation!</p>
                </div>
            @endforelse
        </div>

        <!-- Message Input -->
        <div class="card-footer bg-white py-3">
            <form id="message-form" enctype="multipart/form-data" class="d-flex gap-2">
                @csrf
                <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                
                <div class="flex-grow-1">
                    <textarea 
                        name="message" 
                        id="message-input"
                        class="form-control" 
                        rows="2" 
                        placeholder="Type your message..."
                        style="resize: none;"
                    ></textarea>
                </div>
                
                <div class="d-flex flex-column gap-2">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="dropdown" id="attach-btn">
                            <i class="bi bi-paperclip"></i>
                        </button>
                        <div class="dropdown-menu p-3" style="min-width: 300px;">
                            <input 
                                type="file" 
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

.attachment-preview img {
    max-width: 100%;
    height: auto;
    border-radius: 4px;
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
    let isSubmitting = false;

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

    window.clearAttachment = function() {
        attachmentInput.value = '';
        attachmentPreview.style.display = 'none';
        attachmentName.textContent = '';
    };

    // Handle form submission
    messageForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (isSubmitting) return;
        
        const message = messageInput.value.trim();
        const attachment = attachmentInput.files[0];
        
        if (!message && !attachment) {
            alert('Please enter a message or select a file to attach.');
            return;
        }

        const formData = new FormData();
        if (message) {
            formData.append('message', message);
        }
        if (attachment) {
            formData.append('attachment', attachment);
        }

        // Disable form
        isSubmitting = true;
        setFormDisabled(true);

        try {
            const response = await fetch(`/store/chat/${conversationId}/send`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
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
                
                // Update last message ID
                if (data.message.id > lastMessageId) {
                    lastMessageId = data.message.id;
                }
            } else {
                alert('Failed to send message: ' + (data.error || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to send message. Please check your connection and try again.');
        } finally {
            isSubmitting = false;
            setFormDisabled(false);
        }
    });

    // Poll for new messages
    function startPolling() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
        }
        pollingInterval = setInterval(pollMessages, 3000);
    }

    async function pollMessages() {
        try {
            const response = await fetch(`/store/chat/${conversationId}/poll?last_id=${lastMessageId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success && data.messages && data.messages.length > 0) {
                data.messages.forEach(msg => {
                    appendMessage(msg);
                    if (msg.id > lastMessageId) {
                        lastMessageId = msg.id;
                    }
                });
                scrollToBottom();
            }
        } catch (error) {
            console.error('Polling error:', error);
        }
    }

    function appendMessage(message) {
        // Check if message already exists
        if (document.querySelector(`[data-message-id="${message.id}"]`)) {
            return;
        }

        const messageDiv = document.createElement('div');
        messageDiv.className = `message-item mb-3 ${message.is_me ? 'text-end' : ''}`;
        messageDiv.setAttribute('data-message-id', message.id);

        let attachmentHtml = '';
        if (message.attachment_url) {
            attachmentHtml = `
                <div class="attachment-preview mt-2 p-2 ${message.is_me ? 'bg-primary bg-opacity-25' : 'bg-light'} rounded-2">
                    ${message.is_image ? 
                        `<img src="${message.attachment_url}" alt="Attachment" class="img-fluid rounded-2 mb-2" style="max-height: 150px; max-width: 100%; cursor: pointer;" onclick="window.open('${message.attachment_url}', '_blank')">`
                        : ''
                    }
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi ${message.is_image ? 'bi-image' : 'bi-file-earmark'}"></i>
                        <small class="text-truncate" style="max-width: 200px;">${escapeHtml(message.attachment_name || 'Attachment')}</small>
                        <a href="${message.attachment_url}" class="${message.is_me ? 'text-white' : 'text-primary'}" target="_blank" download>
                            <i class="bi bi-download"></i>
                        </a>
                    </div>
                </div>
            `;
        }

        messageDiv.innerHTML = `
            <div class="d-inline-block" style="max-width: 70%;">
                <div class="message-bubble p-3 rounded-3 ${message.is_me ? 'bg-primary text-white' : 'bg-white'}">
                    ${message.content ? `<p class="mb-1">${escapeHtml(message.content)}</p>` : ''}
                    ${attachmentHtml}
                    <small class="d-block mt-2 ${message.is_me ? 'text-white-50' : 'text-muted'}">
                        ${message.created_at || message.formatted_time}
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

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Start polling
    startPolling();

    // Clean up interval on page unload
    window.addEventListener('beforeunload', function() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
        }
    });
});
</script>
@endsection