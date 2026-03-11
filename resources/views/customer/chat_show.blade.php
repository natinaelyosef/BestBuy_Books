@extends('customer.base')

@section('title', 'Chat - BookHub')

@section('content')
<section class="chat-room">
    <div class="chat-room-head">
        <div>
            <a href="{{ route('chat.index') }}" class="back-link"><i class="bi bi-arrow-left"></i> Back to chats</a>
            <h1>{{ $conversation->subject ?? 'Support Request' }}</h1>
            <p>Status: <strong>{{ ucfirst(str_replace('_', ' ', $conversation->status)) }}</strong></p>
        </div>
        <div class="chat-meta">
            <span>Started {{ $conversation->created_at->format('M d, Y') }}</span>
            @if($conversation->assignedAdmin)
                <span>Assigned to {{ $conversation->assignedAdmin->name }}</span>
            @endif
        </div>
    </div>

    <div class="chat-messages">
        @forelse($messages as $message)
            @php $mine = (int) $message->sender_id === (int) auth()->id(); @endphp
            <div class="message {{ $mine ? 'outgoing' : 'incoming' }}">
                <div class="message-bubble">
                    <p>{{ $message->message }}</p>
                    <span>{{ $message->created_at->format('M d, Y H:i') }}</span>
                </div>
            </div>
        @empty
            <div class="chat-empty">
                <i class="bi bi-chat-square-dots"></i>
                <p>No messages yet. Send the first message below.</p>
            </div>
        @endforelse
    </div>

    <form method="POST" action="{{ route('chat.message', $conversation) }}" class="chat-input">
        @csrf
        <textarea name="message" rows="3" required maxlength="2000" placeholder="Type your message..."></textarea>
        <button type="submit" class="btn-send">
            <i class="bi bi-send-fill"></i>
            Send
        </button>
    </form>
</section>
@endsection

@section('extra_css')
<style>
.chat-room {
    max-width: 980px;
    margin: 0 auto;
    display: grid;
    gap: 1.2rem;
}
.chat-room-head {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 1.2rem 1.4rem;
    box-shadow: var(--shadow-sm);
    display: flex;
    justify-content: space-between;
    gap: 1rem;
}
.chat-room-head h1 {
    margin: 0.4rem 0 0.3rem;
    font-size: 1.4rem;
}
.chat-room-head p {
    margin: 0;
    color: var(--text-secondary);
    font-size: 0.85rem;
}
.back-link {
    text-decoration: none;
    color: var(--primary);
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
}
.chat-meta {
    display: grid;
    gap: 0.35rem;
    font-size: 0.75rem;
    color: var(--text-muted);
    text-align: right;
}
.chat-messages {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 1.2rem;
    min-height: 280px;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    box-shadow: var(--shadow-sm);
}
.message {
    display: flex;
}
.message.incoming {
    justify-content: flex-start;
}
.message.outgoing {
    justify-content: flex-end;
}
.message-bubble {
    max-width: 70%;
    background: var(--bg-raised);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 0.75rem 0.9rem;
    box-shadow: var(--shadow-sm);
}
.message.outgoing .message-bubble {
    background: var(--primary);
    color: #fff;
    border-color: transparent;
}
.message-bubble p {
    margin: 0 0 0.35rem 0;
    font-size: 0.88rem;
}
.message-bubble span {
    font-size: 0.7rem;
    opacity: 0.7;
}
.chat-input {
    display: grid;
    gap: 0.6rem;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 1rem 1.2rem;
    box-shadow: var(--shadow-sm);
}
.chat-input textarea {
    background: var(--bg-raised);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 0.7rem 0.8rem;
    font-family: 'Outfit', sans-serif;
    color: var(--text-primary);
    resize: vertical;
}
.btn-send {
    justify-self: flex-end;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.6rem 1.1rem;
    border-radius: 999px;
    border: none;
    background: var(--primary);
    color: #fff;
    font-weight: 700;
    cursor: pointer;
}
.chat-empty {
    text-align: center;
    color: var(--text-muted);
    margin: auto 0;
}
.chat-empty i {
    font-size: 2rem;
    display: block;
    margin-bottom: 0.4rem;
}
@media (max-width: 700px) {
    .chat-room-head {
        flex-direction: column;
        align-items: flex-start;
    }
    .chat-meta {
        text-align: left;
    }
    .message-bubble {
        max-width: 100%;
    }
}
</style>
@endsection
