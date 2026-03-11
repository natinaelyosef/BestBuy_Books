@extends('customer.base')

@section('title', 'Issue Reports - BookHub')

@section('content')
<section class="issue-shell">
    <div class="issue-head">
        <div>
            <h1>Your Issue Reports</h1>
            <p>Track the status of your submitted issues.</p>
        </div>
        <a href="{{ route('issue-reports.create') }}" class="btn-primary">
            <i class="bi bi-plus-circle"></i>
            New Report
        </a>
    </div>

    <div class="issue-list">
        @forelse($reports as $report)
            <a href="{{ route('issue-reports.show', $report) }}" class="issue-card">
                <div>
                    <h3>{{ $report->subject }}</h3>
                    <p>{{ \Illuminate\Support\Str::limit($report->description, 110) }}</p>
                </div>
                <div class="issue-meta">
                    <span class="pill priority-{{ $report->priority }}">{{ ucfirst($report->priority) }}</span>
                    <span class="pill status-{{ $report->status }}">{{ ucfirst(str_replace('_', ' ', $report->status)) }}</span>
                    <span class="date">{{ $report->created_at->format('M d, Y') }}</span>
                </div>
            </a>
        @empty
            <div class="issue-empty">
                <i class="bi bi-flag"></i>
                <h3>No reports yet</h3>
                <p>If something is wrong, submit a report and our admins will help.</p>
                <a href="{{ route('issue-reports.create') }}" class="btn-primary">
                    <i class="bi bi-plus-circle"></i> Create Report
                </a>
            </div>
        @endforelse
    </div>
</section>
@endsection

@section('extra_css')
<style>
.issue-shell {
    max-width: 980px;
    margin: 0 auto;
    display: grid;
    gap: 1.2rem;
}
.issue-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 1.2rem 1.4rem;
    box-shadow: var(--shadow-sm);
}
.issue-head h1 {
    margin: 0 0 0.35rem 0;
    font-size: 1.5rem;
}
.issue-head p {
    margin: 0;
    color: var(--text-secondary);
    font-size: 0.9rem;
}
.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.6rem 1.1rem;
    border-radius: 999px;
    background: var(--primary);
    color: #fff;
    font-weight: 700;
    text-decoration: none;
    border: none;
}
.issue-list {
    display: grid;
    gap: 0.9rem;
}
.issue-card {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    padding: 1rem 1.2rem;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    text-decoration: none;
    color: inherit;
    box-shadow: var(--shadow-sm);
    transition: all var(--t-fast);
}
.issue-card:hover {
    border-color: var(--primary);
    box-shadow: var(--shadow);
}
.issue-card h3 {
    margin: 0 0 0.35rem 0;
    font-size: 1rem;
}
.issue-card p {
    margin: 0;
    font-size: 0.82rem;
    color: var(--text-secondary);
}
.issue-meta {
    display: grid;
    gap: 0.4rem;
    align-content: center;
    text-align: right;
    font-size: 0.75rem;
    color: var(--text-muted);
}
.pill {
    padding: 0.18rem 0.6rem;
    border-radius: 999px;
    font-weight: 700;
    font-size: 0.7rem;
    border: 1px solid transparent;
}
.priority-low { background: var(--bg-raised); color: var(--text-secondary); border-color: var(--border); }
.priority-medium { background: var(--accent-soft); color: var(--accent); border-color: rgba(245,176,66,0.3); }
.priority-high { background: var(--danger-soft); color: var(--danger); border-color: rgba(255,77,109,0.3); }
.status-open { background: var(--primary-soft); color: var(--primary); border-color: var(--border); }
.status-in_review { background: var(--bg-raised); color: var(--text-secondary); border-color: var(--border); }
.status-resolved { background: var(--success-soft); color: var(--success); border-color: rgba(0,201,139,0.3); }
.status-closed { background: var(--bg-raised); color: var(--text-muted); border-color: var(--border); }
.issue-empty {
    text-align: center;
    padding: 2rem 1rem;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
}
.issue-empty i {
    font-size: 2rem;
    display: block;
    margin-bottom: 0.6rem;
    color: var(--text-muted);
}
@media (max-width: 720px) {
    .issue-card {
        flex-direction: column;
        text-align: left;
    }
    .issue-meta {
        text-align: left;
    }
}
</style>
@endsection
