@extends('store.registration-layout')

@section('title', 'Customer Chats')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Customer Chats</h1>
            <p class="text-muted mt-2">Communicate with your customers about their inquiries</p>
        </div>
        <div class="d-flex gap-2">
            <span class="badge bg-danger p-3">
                <i class="bi bi-envelope-paper-fill me-2"></i>
                {{ $unread_total ?? 0 }} Unread
            </span>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" 
                               name="search" 
                               class="form-control border-start-0 ps-0" 
                               placeholder="Search by customer name or subject..."
                               value="{{ $search ?? '' }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="book_id" class="form-select">
                        <option value="">All Books</option>
                        @foreach($storeBooks as $book)
                            <option value="{{ $book->id }}" {{ $book_filter == $book->id ? 'selected' : '' }}>
                                {{ $book->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Conversations List -->
    @if($conversations->count() > 0)
        <div class="row g-4">
            @foreach($conversations as $conv)
                <div class="col-12">
                    <a href="{{ route('store.chat.show', $conv) }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm hover-shadow {{ $conv->unread_count > 0 ? 'bg-light' : '' }}">
                            <div class="card-body">
                                <div class="d-flex align-items-start gap-3">
                                    <!-- Avatar -->
                                    <div class="flex-shrink-0">
                                        <div class="avatar-circle bg-primary text-white">
                                            {{ substr($conv->customer->name ?? 'C', 0, 1) }}
                                        </div>
                                    </div>
                                    
                                    <!-- Content -->
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <h5 class="mb-1 fw-bold">
                                                    {{ $conv->customer->name ?? 'Customer' }}
                                                    @if($conv->unread_count > 0)
                                                        <span class="badge bg-danger ms-2">{{ $conv->unread_count }} new</span>
                                                    @endif
                                                </h5>
                                                <p class="small text-muted mb-0">
                                                    <i class="bi bi-envelope me-1"></i>
                                                    {{ $conv->customer->email ?? 'No email' }}
                                                </p>
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
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="py-5">
                    <i class="bi bi-chat-dots display-1 text-muted"></i>
                    <h3 class="mt-3">No Conversations Yet</h3>
                    <p class="text-muted">When customers start conversations, they'll appear here.</p>
                </div>
            </div>
        </div>
    @endif
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
</style>
@endsection