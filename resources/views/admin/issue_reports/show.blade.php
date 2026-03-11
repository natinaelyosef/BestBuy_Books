@extends('admin.layout')

@section('title', 'Issue Report')

@section('content')
<div class="card" style="max-width:900px;">
    <a href="{{ route('admin.issue-reports.index') }}" style="text-decoration:none;color:#5b4cff;font-weight:700;">
        <i class="bi bi-arrow-left"></i> Back to reports
    </a>

    <h1 style="margin-top:0.8rem;">{{ $report->subject }}</h1>
    <p style="color:#667085;">Reported by {{ $report->user?->name ?? 'Unknown' }} · {{ $report->created_at->format('M d, Y H:i') }}</p>

    <div style="margin:1rem 0;padding:1rem;border:1px solid #e5e7f1;border-radius:12px;background:#fafbff;">
        <strong>Description</strong>
        <p style="margin-top:0.6rem;color:#344054;">{{ $report->description }}</p>
    </div>

    <form method="POST" action="{{ route('admin.issue-reports.update', $report) }}" style="display:grid;gap:0.8rem;">
        @csrf
        @method('PUT')

        <label style="display:grid;gap:0.4rem;font-weight:600;color:#475467;">
            Status
            <select name="status" required style="padding:0.6rem;border-radius:10px;border:1px solid #d0d5dd;">
                <option value="open" @selected($report->status === 'open')>Open</option>
                <option value="in_review" @selected($report->status === 'in_review')>In Review</option>
                <option value="resolved" @selected($report->status === 'resolved')>Resolved</option>
                <option value="closed" @selected($report->status === 'closed')>Closed</option>
            </select>
        </label>

        <label style="display:grid;gap:0.4rem;font-weight:600;color:#475467;">
            Assigned Admin
            <select name="assigned_admin_id" style="padding:0.6rem;border-radius:10px;border:1px solid #d0d5dd;">
                <option value="">Unassigned</option>
                @foreach($admins as $admin)
                    <option value="{{ $admin->id }}" @selected($report->assigned_admin_id === $admin->id)>
                        {{ $admin->name }} ({{ $admin->account_type }})
                    </option>
                @endforeach
            </select>
        </label>

        <button type="submit" style="padding:0.7rem 1.2rem;border:none;border-radius:10px;background:#5b4cff;color:#fff;font-weight:700;width:fit-content;">
            Update Report
        </button>
    </form>
</div>
@endsection
