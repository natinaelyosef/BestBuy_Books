@extends('customer.base')

@section('title', 'Support Chat - BestBuy_Books')

@section('content')
<section class="chat-shell">
    <div class="chat-head">
        <div>
            <h1>Support Chat</h1>
            <p>Start a conversation with our support team and track replies.</p>
        </div>
        <a href="{{ route('issue-reports.create') }}" class="btn-pill">Report an Issue</a>
    </div>

    <div class="chat-grid">
        <div class="chat-panel">
            <h2>Start a New Conversation</h2>
            <form method="POST" action="{{ route('chat.start') }}" class="chat-form">
                @csrf
                <label class="field">
                    <span>Subject</span>
                    <input type="text" name="subject" value="{{ old('subject') }}" required maxlength="255" placeholder="Payment issue, rental question, etc.">
                </label>
                <label class="field">
                    <span>Message</span>
                    <textarea name="message" rows="5" required maxlength="2000" placeholder="Tell us how we can help.">{{ old('message') }}</textarea>
                </label>
                <button class="btn-primary" type="submit">
                    <i class="bi bi-chat-dots-fill"></i>
                    Start Chat
                </button>
            </form>
        </div>

        <div class="chat-panel">
            <div class="panel-head">
                <h2>Your Conversations</h2>
                <span class="pill">{{ $conversations->count() }}</span>
            </div>

            <div class="chat-list">
                @forelse($conversations as $conversation)
                    <a href="{{ route('chat.show', $conversation) }}" class="chat-item">
                        <div class="chat-item-main">
                            <h3>{{ $conversation->subject ?? 'Support Request' }}</h3>
                            <p>
                                @if($conversation->latestMessage)
                                    {{ \Illuminate\Support\Str::limit($conversation->latestMessage->message, 90) }}
                                @else
                                    No messages yet.
                                @endif
                            </p>
                        </div>
                        <div class="chat-item-meta">
                            <span>{{ optional($conversation->last_message_at)->format('M d, Y H:i') ?? $conversation->created_at->format('M d, Y H:i') }}</span>
                            @if($conversation->unread_count)
                                <span class="badge">{{ $conversation->unread_count }}</span>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="chat-empty">
                        <i class="bi bi-chat-square-dots"></i>
                        <p>No conversations yet. Start a new chat to reach support.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>
@endsection

@section('extra_css')
<style>
.chat-shell {
    max-width: 1180px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}
.chat-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 1.2rem 1.5rem;
    box-shadow: var(--shadow-sm);
}
.chat-head h1 {
    margin: 0 0 0.35rem 0;
    font-size: 1.6rem;
}
.chat-head p {
    margin: 0;
    color: var(--text-secondary);
    font-size: 0.9rem;
}
.btn-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.1rem;
    border-radius: 999px;
    background: var(--primary-soft);
    color: var(--primary);
    font-weight: 700;
    text-decoration: none;
    border: 1px solid var(--border);
}
.chat-grid {
    display: grid;
    grid-template-columns: minmax(280px, 1fr) minmax(300px, 1.2fr);
    gap: 1.2rem;
}
.chat-panel {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 1.2rem 1.4rem;
    box-shadow: var(--shadow-sm);
}
.chat-panel h2 {
    margin: 0 0 1rem 0;
    font-size: 1.1rem;
}
.panel-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.pill {
    background: var(--bg-raised);
    border: 1px solid var(--border);
    padding: 0.2rem 0.6rem;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--text-secondary);
}
.chat-form {
    display: grid;
    gap: 0.9rem;
}
.field {
    display: grid;
    gap: 0.4rem;
    font-size: 0.82rem;
    color: var(--text-secondary);
    font-weight: 600;
}
.field input,
.field textarea {
    background: var(--bg-raised);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 0.7rem 0.8rem;
    font-family: 'Outfit', sans-serif;
    color: var(--text-primary);
}
.field textarea { resize: vertical; }
.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.7rem 1.2rem;
    border-radius: 999px;
    background: var(--primary);
    color: #fff;
    border: none;
    font-weight: 700;
    cursor: pointer;
}
.chat-list {
    display: grid;
    gap: 0.8rem;
}
.chat-item {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    padding: 0.85rem 1rem;
    border-radius: var(--radius);
    border: 1px solid var(--border);
    background: var(--bg-raised);
    text-decoration: none;
    color: inherit;
    transition: all var(--t-fast);
}
.chat-item:hover {
    border-color: var(--primary);
    box-shadow: var(--shadow-sm);
}
.chat-item-main h3 {
    margin: 0 0 0.3rem 0;
    font-size: 0.95rem;
}
.chat-item-main p {
    margin: 0;
    color: var(--text-secondary);
    font-size: 0.8rem;
}
.chat-item-meta {
    display: grid;
    justify-items: end;
    gap: 0.4rem;
    font-size: 0.7rem;
    color: var(--text-muted);
}
.badge {
    background: var(--primary);
    color: #fff;
    font-weight: 700;
    font-size: 0.7rem;
    padding: 0.1rem 0.5rem;
    border-radius: 999px;
}
.chat-empty {
    text-align: center;
    padding: 1.5rem 0;
    color: var(--text-muted);
}
.chat-empty i {
    font-size: 2rem;
    display: block;
    margin-bottom: 0.4rem;
}
@media (max-width: 900px) {
    .chat-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
