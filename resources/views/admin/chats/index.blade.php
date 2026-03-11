@extends('admin.layout')

@section('title', 'Support Chats')

@section('content')
<div class="card">
    <h1 style="margin-top:0;">Support Chats</h1>
    <p style="color:#667085;margin-top:0.4rem;">Active conversations with customers.</p>

    <div style="display:grid;gap:0.8rem;margin-top:1.2rem;">
        @forelse($conversations as $conversation)
            <a href="{{ route('admin.chats.show', $conversation) }}" style="text-decoration:none;color:inherit;">
                <div style="border:1px solid #e5e7f1;border-radius:12px;padding:0.9rem 1rem;display:flex;justify-content:space-between;gap:1rem;align-items:center;">
                    <div>
                        <strong>{{ $conversation->subject ?? 'Support Request' }}</strong>
                        <div style="font-size:0.85rem;color:#667085;margin-top:0.3rem;">
                            {{ $conversation->customer?->name ?? 'Customer' }}
                            @if($conversation->latestMessage)
                                · {{ \Illuminate\Support\Str::limit($conversation->latestMessage->message, 80) }}
                            @endif
                        </div>
                    </div>
                    <div style="text-align:right;font-size:0.75rem;color:#667085;">
                        <div>{{ optional($conversation->last_message_at)->format('M d, H:i') ?? $conversation->created_at->format('M d, H:i') }}</div>
                        @if($conversation->unread_count)
                            <span style="background:#5b4cff;color:#fff;border-radius:999px;padding:0.1rem 0.5rem;font-weight:700;">{{ $conversation->unread_count }}</span>
                        @endif
                    </div>
                </div>
            </a>
        @empty
            <div style="text-align:center;color:#667085;padding:1.5rem;">No support chats yet.</div>
        @endforelse
    </div>
</div>
@endsection
