@extends('customer.base')

@section('title', 'My PDFs - BookHub')

@section('content')
<section class="pdf-shell">
    <div class="pdf-hero">
        <div>
            <p class="pdf-eyebrow"><i class="bi bi-file-earmark-pdf"></i> Digital Library</p>
            <h1>My PDF Requests</h1>
            <p>Track your approved downloads and pending requests from store owners.</p>
        </div>
        <div class="pdf-metrics">
            <div>
                <span>Approved</span>
                <strong>{{ $approvedRequests->count() }}</strong>
            </div>
            <div>
                <span>Pending</span>
                <strong>{{ $pendingRequests->count() }}</strong>
            </div>
            <div>
                <span>Declined</span>
                <strong>{{ $rejectedRequests->count() }}</strong>
            </div>
        </div>
    </div>

    <div class="pdf-section">
        <div class="pdf-section-head">
            <h2>Approved Downloads</h2>
        </div>
        @forelse($approvedRequests as $request)
            <article class="pdf-card">
                <div class="pdf-card-body">
                    <div class="pdf-book">
                        <div class="pdf-icon"><i class="bi bi-file-earmark-pdf"></i></div>
                        <div>
                            <h3>{{ $request->book?->title ?? 'Book' }}</h3>
                            <p>Store: {{ $request->store?->name ?? 'Store' }}</p>
                        </div>
                    </div>
                    <div class="pdf-actions">
                        <a href="{{ route('books.show', $request->book_id) }}" class="pdf-btn pdf-btn-ghost">
                            <i class="bi bi-eye"></i> View Book
                        </a>
                        <a href="{{ route('customer.pdfs.download', $request->id) }}" class="pdf-btn pdf-btn-primary">
                            <i class="bi bi-download"></i> Download PDF
                        </a>
                    </div>
                </div>
                <div class="pdf-card-meta">
                    Approved on {{ optional($request->approved_at)->format('M d, Y') ?? $request->updated_at->format('M d, Y') }}
                </div>
            </article>
        @empty
            <div class="pdf-empty">
                <i class="bi bi-cloud-arrow-down"></i>
                <h3>No approved PDFs yet</h3>
                <p>Request a PDF from any book detail page to see it here once approved.</p>
                <a href="{{ route('customer.dashboard') }}" class="pdf-btn pdf-btn-primary">
                    Browse Books
                </a>
            </div>
        @endforelse
    </div>

    <div class="pdf-section">
        <div class="pdf-section-head">
            <h2>Pending Requests</h2>
        </div>
        @forelse($pendingRequests as $request)
            <article class="pdf-card pdf-card-muted">
                <div class="pdf-card-body">
                    <div class="pdf-book">
                        <div class="pdf-icon"><i class="bi bi-hourglass-split"></i></div>
                        <div>
                            <h3>{{ $request->book?->title ?? 'Book' }}</h3>
                            <p>Store: {{ $request->store?->name ?? 'Store' }}</p>
                        </div>
                    </div>
                    <span class="pdf-status pdf-status-pending">Pending</span>
                </div>
                <div class="pdf-card-meta">
                    Requested on {{ $request->created_at->format('M d, Y') }}
                </div>
            </article>
        @empty
            <p class="pdf-muted">No pending requests right now.</p>
        @endforelse
    </div>

    <div class="pdf-section">
        <div class="pdf-section-head">
            <h2>Declined Requests</h2>
        </div>
        @forelse($rejectedRequests as $request)
            <article class="pdf-card pdf-card-muted">
                <div class="pdf-card-body">
                    <div class="pdf-book">
                        <div class="pdf-icon"><i class="bi bi-x-circle"></i></div>
                        <div>
                            <h3>{{ $request->book?->title ?? 'Book' }}</h3>
                            <p>Store: {{ $request->store?->name ?? 'Store' }}</p>
                        </div>
                    </div>
                    <span class="pdf-status pdf-status-rejected">Declined</span>
                </div>
                <div class="pdf-card-meta">
                    Updated on {{ $request->updated_at->format('M d, Y') }}
                </div>
            </article>
        @empty
            <p class="pdf-muted">No declined requests.</p>
        @endforelse
    </div>
</section>
@endsection

@section('extra_css')
<style>
.pdf-shell {
    max-width: 1200px;
    margin: 1rem auto 3rem;
    padding: 0 1.5rem;
}

.pdf-hero {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    align-items: center;
    justify-content: space-between;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-xl);
    padding: 1.5rem 1.8rem;
    box-shadow: var(--shadow);
    margin-bottom: 1.5rem;
}

.pdf-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.75rem;
    letter-spacing: 1px;
    text-transform: uppercase;
    font-weight: 800;
    color: var(--primary);
    margin-bottom: 0.6rem;
}

.pdf-hero h1 {
    margin: 0 0 0.4rem 0;
    font-size: clamp(1.5rem, 2.4vw, 2.2rem);
}

.pdf-hero p {
    margin: 0;
    color: var(--text-secondary);
}

.pdf-metrics {
    display: grid;
    grid-template-columns: repeat(3, minmax(90px, 1fr));
    gap: 0.75rem;
    text-align: center;
}

.pdf-metrics div {
    background: var(--bg-raised);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 0.75rem 0.85rem;
}

.pdf-metrics span {
    display: block;
    font-size: 0.7rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--text-muted);
    font-weight: 700;
}

.pdf-metrics strong {
    display: block;
    font-size: 1.4rem;
    color: var(--primary);
}

.pdf-section {
    margin-top: 1.5rem;
}

.pdf-section-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.8rem;
}

.pdf-section h2 {
    margin: 0;
    font-size: 1.1rem;
}

.pdf-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 1rem 1.2rem;
    box-shadow: var(--shadow-sm);
    margin-bottom: 0.85rem;
}

.pdf-card-muted {
    background: var(--bg-raised);
}

.pdf-card-body {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}

.pdf-book {
    display: flex;
    align-items: center;
    gap: 0.85rem;
}

.pdf-icon {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    background: rgba(245, 176, 66, 0.14);
    color: var(--accent);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.pdf-book h3 {
    margin: 0 0 0.2rem 0;
    font-size: 1rem;
}

.pdf-book p {
    margin: 0;
    color: var(--text-muted);
    font-size: 0.85rem;
}

.pdf-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.6rem;
}

.pdf-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.55rem 1rem;
    border-radius: 999px;
    font-weight: 700;
    font-size: 0.82rem;
    text-decoration: none;
    border: 1px solid transparent;
    transition: all 0.2s ease;
}

.pdf-btn-primary {
    background: var(--primary);
    color: #fff;
}

.pdf-btn-primary:hover {
    background: var(--primary-dark);
    color: #fff;
}

.pdf-btn-ghost {
    border-color: var(--border);
    color: var(--text-secondary);
    background: var(--bg-raised);
}

.pdf-btn-ghost:hover {
    border-color: var(--primary);
    color: var(--primary);
}

.pdf-card-meta {
    margin-top: 0.6rem;
    color: var(--text-muted);
    font-size: 0.78rem;
}

.pdf-status {
    padding: 0.35rem 0.85rem;
    border-radius: 999px;
    font-weight: 700;
    font-size: 0.75rem;
}

.pdf-status-pending {
    background: var(--accent-soft);
    color: var(--accent);
}

.pdf-status-rejected {
    background: var(--danger-soft);
    color: var(--danger);
}

.pdf-empty {
    text-align: center;
    padding: 2.5rem 1.5rem;
    border-radius: var(--radius-lg);
    border: 1px dashed var(--border);
    color: var(--text-muted);
}

.pdf-empty i {
    font-size: 2rem;
    color: var(--primary);
}

.pdf-empty h3 {
    margin: 0.8rem 0 0.4rem;
    color: var(--text-primary);
}

.pdf-muted {
    color: var(--text-muted);
    font-size: 0.85rem;
}

@media (max-width: 720px) {
    .pdf-metrics {
        grid-template-columns: 1fr;
        width: 100%;
    }
}
</style>
@endsection
