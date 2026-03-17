@extends('admin.layout')

@section('title', 'Chat Detail')

@section('content')
<div class="card" style="max-width:900px;">
    <a href="{{ route('admin.chats.index') }}" style="text-decoration:none;color:var(--shell-brand);font-weight:700;">
        <i class="bi bi-arrow-left"></i> Back to chats
    </a>

    <h1 style="margin-top:0.8rem;">{{ $conversation->subject ?? 'Support Request' }}</h1>
    <p style="color:#667085;">Customer: {{ $conversation->customer?->name ?? 'Customer' }}</p>

    <div style="margin:1rem 0;display:grid;gap:0.6rem;">
        @forelse($messages as $message)
            @php $mine = (int) $message->sender_id === (int) auth()->id(); @endphp
            <div style="display:flex;justify-content:{{ $mine ? 'flex-end' : 'flex-start' }};">
                <div style="max-width:70%;background:{{ $mine ? 'var(--shell-brand)' : '#f1f3f9' }};color:{{ $mine ? '#fff' : '#1f2433' }};padding:0.7rem 0.9rem;border-radius:12px;">
                    <div style="font-size:0.88rem;">{{ $message->message }}</div>
                    <div style="font-size:0.7rem;opacity:0.7;margin-top:0.35rem;">
                        {{ $message->created_at->format('M d, Y H:i') }}
                    </div>
                </div>
            </div>
        @empty
            <div style="text-align:center;color:#667085;padding:1rem;">No messages yet.</div>
        @endforelse
    </div>

    <form method="POST" action="{{ route('admin.chats.message', $conversation) }}" style="display:grid;gap:0.6rem;">
        @csrf
        <textarea name="message" rows="3" required maxlength="2000" style="padding:0.7rem;border-radius:10px;border:1px solid #d0d5dd;"></textarea>
        <button type="submit" style="padding:0.7rem 1.2rem;border:none;border-radius:10px;background:var(--shell-brand);color:#fff;font-weight:700;width:fit-content;">
            Send Reply
        </button>
    </form>
</div>
@endsection
