@extends('customer.base')

@section('title', 'Issue Report - BestBuy_Books')

@section('content')
<section class="issue-detail">
    <div class="detail-head">
        <div>
            <a href="{{ route('customer.issue-reports.index') }}" class="back-link"><i class="bi bi-arrow-left"></i> Back to Reports</a>
            <h1>{{ $report->subject }}</h1>
            <p>Created {{ $report->created_at->format('M d, Y H:i') }}</p>
        </div>
        <div class="detail-meta">
            <span class="pill priority-{{ $report->priority }}">{{ ucfirst($report->priority) }}</span>
            <span class="pill status-{{ $report->status }}">{{ ucfirst(str_replace('_', ' ', $report->status)) }}</span>
        </div>
    </div>

    <div class="detail-body">
        <h3>Description</h3>
        <p>{{ $report->description }}</p>

        @if($report->hasEvidence())
            <hr style="margin:1.5rem 0;border-color:var(--border);">
            <h3>Evidence</h3>
            <div style="background:var(--bg);padding:1rem;border-radius:var(--radius);border:1px solid var(--border);margin-bottom:1rem;">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <i class="bi {{ $report->isEvidenceImage() ? 'bi-image' : 'bi-file-earmark-text' }} fs-4 text-muted"></i>
                    <div style="flex:1;">
                        <div style="font-weight:600;font-size:0.9rem;">{{ $report->evidence_name }}</div>
                        <a href="{{ $report->evidence_url }}" target="_blank" style="font-size:0.8rem;color:var(--primary);text-decoration:none;font-weight:600;">View / Download</a>
                    </div>
                </div>
                @if($report->isEvidenceImage())
                    <div style="margin-top:1rem;">
                        <img src="{{ $report->evidence_url }}" alt="Evidence" style="max-width:100%;max-height:300px;border-radius:8px;border:1px solid var(--border);">
                    </div>
                @endif
            </div>
        @endif

        @if($report->assignedAdmin)
            <div class="assigned">Assigned admin: {{ $report->assignedAdmin->name }}</div>
        @endif
    </div>
</section>
@endsection

@section('extra_css')
<style>
.issue-detail {
    max-width: 800px;
    margin: 0 auto;
    display: grid;
    gap: 1rem;
}
.detail-head {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 1.2rem 1.4rem;
    box-shadow: var(--shadow-sm);
    display: flex;
    justify-content: space-between;
    gap: 1rem;
}
.detail-head h1 {
    margin: 0.5rem 0 0.35rem;
    font-size: 1.4rem;
}
.detail-head p {
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
.detail-meta {
    display: grid;
    gap: 0.4rem;
    align-content: center;
    text-align: right;
}
.pill {
    padding: 0.2rem 0.6rem;
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
.detail-body {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 1.2rem 1.4rem;
    box-shadow: var(--shadow-sm);
}
.detail-body h3 {
    margin: 0 0 0.5rem 0;
}
.detail-body p {
    margin: 0 0 0.8rem 0;
    color: var(--text-secondary);
    font-size: 0.9rem;
    line-height: 1.6;
}
.assigned {
    font-size: 0.85rem;
    color: var(--text-muted);
}
@media (max-width: 700px) {
    .detail-head {
        flex-direction: column;
        text-align: left;
    }
    .detail-meta {
        text-align: left;
    }
}
</style>
@endsection
