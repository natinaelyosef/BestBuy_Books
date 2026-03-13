@extends('customer.base')

@section('title', 'My Chats')

@section('content')
<div class="container">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">My Conversations</h1>
            <p class="text-muted mt-2">Chat with store owners about books and orders</p>
        </div>
        <div class="d-flex gap-2">
            <span class="badge bg-danger p-3">
                <i class="bi bi-envelope-paper-fill me-2"></i>
                {{ $unread_total ?? 0 }} Unread
            </span>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newChatModal">
                <i class="bi bi-plus-lg me-2"></i>New Chat
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" 
                               id="search-input"
                               class="form-control border-start-0 ps-0" 
                               placeholder="Search conversations...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select id="status-filter" class="form-select">
                        <option value="all">All Conversations</option>
                        <option value="unread">Unread Only</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                        <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Conversations List -->
    <div id="conversations-list">
        @if($conversations->count() > 0)
            @foreach($conversations as $conv)
                <div class="conversation-item mb-3" 
                     data-unread="{{ $conv->unread_count > 0 ? 'true' : 'false' }}"
                     data-search="{{ strtolower($conv->store->name ?? '') }} {{ strtolower($conv->subject ?? '') }}">
                    <a href="{{ route('chat.show', $conv) }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm hover-shadow {{ $conv->unread_count > 0 ? 'border-start border-danger border-4' : '' }}">
                            <div class="card-body">
                                <div class="d-flex align-items-start gap-3">
                                    <!-- Avatar -->
                                    <div class="flex-shrink-0">
                                        <div class="avatar-circle bg-primary text-white">
                                            {{ substr($conv->store->name ?? 'S', 0, 1) }}
                                        </div>
                                    </div>
                                    
                                    <!-- Content -->
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <h5 class="mb-1 fw-bold">
                                                    {{ $conv->store->name ?? 'Store' }}
                                                    @if($conv->unread_count > 0)
                                                        <span class="badge bg-danger ms-2">{{ $conv->unread_count }} new</span>
                                                    @endif
                                                </h5>
                                            </div>
                                            <div class="text-end">
                                                <small class="text-muted">
                                                    {{ $conv->last_message_at?->diffForHumans() ?? $conv->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                        </div>
                                        
                                        @if($conv->book)
                                            <div class="mb-2">
                                                <span class="badge bg-info">
                                                    <i class="bi bi-book me-1"></i>
                                                    {{ $conv->book->title }}
                                                </span>
                                            </div>
                                        @endif
                                        
                                        @if($conv->subject)
                                            <p class="fw-semibold mb-1">{{ $conv->subject }}</p>
                                        @endif
                                        
                                        <p class="mb-0 text-muted">
                                            <i class="bi bi-chat-dots me-1"></i>
                                            {{ $conv->last_message ?? 'No messages yet' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        @else
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="py-5">
                        <i class="bi bi-chat-dots display-1 text-muted"></i>
                        <h3 class="mt-3">No Conversations Yet</h3>
                        <p class="text-muted">Start a chat with a store owner to ask about books!</p>
                        <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#newChatModal">
                            <i class="bi bi-plus-lg me-2"></i>Start New Chat
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- New Chat Modal -->
<div class="modal fade" id="newChatModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Start New Chat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('chat.start') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Store</label>
                        <select name="store_id" class="form-select" required>
                            <option value="">Choose a store...</option>
                            @foreach($recentStores as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Subject (Optional)</label>
                        <input type="text" name="subject" class="form-control" 
                               placeholder="e.g., Question about a book">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea name="message" class="form-control" rows="4" required 
                                  placeholder="What would you like to ask?"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Start Chat</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.2rem;
}
.hover-shadow {
    transition: all 0.3s ease;
}
.hover-shadow:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
}
.border-start.border-danger {
    border-left-width: 4px !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const statusFilter = document.getElementById('status-filter');
    const conversationItems = document.querySelectorAll('.conversation-item');
    
    function filterConversations() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const showUnreadOnly = statusFilter.value === 'unread';
        
        conversationItems.forEach(item => {
            const searchText = item.dataset.search || '';
            const isUnread = item.dataset.unread === 'true';
            
            let show = true;
            
            // Search filter
            if (searchTerm && !searchText.includes(searchTerm)) {
                show = false;
            }
            
            // Unread filter
            if (showUnreadOnly && !isUnread) {
                show = false;
            }
            
            item.style.display = show ? 'block' : 'none';
        });
    }
    
    searchInput.addEventListener('input', filterConversations);
    statusFilter.addEventListener('change', filterConversations);
    
    window.resetFilters = function() {
        searchInput.value = '';
        statusFilter.value = 'all';
        filterConversations();
    };
});
</script>
@endsection