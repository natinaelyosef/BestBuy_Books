@extends('admin.layout')

@section('title', 'Issue Report')

@section('content')
<div class="card" style="max-width:900px;">
    <a href="{{ route('admin.issue-reports.index') }}" style="text-decoration:none;color:var(--shell-brand);font-weight:700;">
        <i class="bi bi-arrow-left"></i> Back to reports
    </a>

    <h1 style="margin-top:0.8rem;">{{ $report->subject }}</h1>
    <p style="color:#667085;">Reported by {{ $report->user?->name ?? 'Unknown' }} · {{ $report->created_at->format('M d, Y H:i') }}</p>

    <div style="margin:1rem 0;padding:1rem;border:1px solid #e5e7f1;border-radius:12px;background:#fafbff;">
        <strong>Description</strong>
        <p style="margin-top:0.6rem;color:#344054;">{{ $report->description }}</p>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-top:1.5rem;">
        <form method="POST" action="{{ route('admin.issue-reports.update', $report) }}" style="display:grid;gap:0.8rem;background:#fff;padding:1.2rem;border:1px solid #e5e7f1;border-radius:12px;">
            <h3 style="margin:0;">Update Status</h3>
            @csrf
            @method('PUT')

            <label style="display:grid;gap:0.4rem;font-weight:600;color:#475467;">
                Status
                <select name="status" required style="padding:0.6rem;border-radius:8px;border:1px solid #d0d5dd;">
                    <option value="open" @selected($report->status === 'open')>Open</option>
                    <option value="in_review" @selected($report->status === 'in_review')>In Review</option>
                    <option value="resolved" @selected($report->status === 'resolved')>Resolved</option>
                    <option value="closed" @selected($report->status === 'closed')>Closed</option>
                </select>
            </label>

            <label style="display:grid;gap:0.4rem;font-weight:600;color:#475467;">
                Assigned Admin
                <select name="assigned_admin_id" style="padding:0.6rem;border-radius:8px;border:1px solid #d0d5dd;">
                    <option value="">Unassigned</option>
                    @foreach($admins as $admin)
                        <option value="{{ $admin->id }}" @selected($report->assigned_admin_id === $admin->id)>
                            {{ $admin->name }} ({{ $admin->account_type }})
                        </option>
                    @endforeach
                </select>
            </label>

            <label style="display:grid;gap:0.4rem;font-weight:600;color:#475467;">
                Admin Notes
                <textarea name="admin_notes" rows="3" style="padding:0.6rem;border-radius:8px;border:1px solid #d0d5dd;">{{ $report->admin_notes }}</textarea>
            </label>

            <button type="submit" style="padding:0.7rem 1.2rem;border:none;border-radius:8px;background:var(--shell-brand);color:#fff;font-weight:700;cursor:pointer;">
                Update Status
            </button>
        </form>

        <div style="background:#fff;padding:1.2rem;border:1px solid #e5e7f1;border-radius:12px;">
            <h3 style="margin:0 0 1rem 0;">User Moderation</h3>
            @php $target = $report->reportedUser ?? $report->user; @endphp
            
            @if($target)
                <div style="display:flex;align-items:center;gap:0.8rem;margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px solid #e5e7f1;">
                    <div style="font-weight:bold;">{{ $target->name }} <span style="font-weight:normal;color:#667085;">({{ $target->account_type }})</span></div>
                    {!! $target->statusBadge() === 'Active' ? '<span style="background:#dcfce7;color:#166534;padding:0.2rem 0.6rem;border-radius:999px;font-size:0.75rem;font-weight:700;">Active</span>' : '' !!}
                    {!! $target->statusBadge() === 'Banned' ? '<span style="background:#fee2e2;color:#991b1b;padding:0.2rem 0.6rem;border-radius:999px;font-size:0.75rem;font-weight:700;">Banned</span>' : '' !!}
                    {!! $target->statusBadge() === 'Restricted' ? '<span style="background:#ffedd5;color:#9a3412;padding:0.2rem 0.6rem;border-radius:999px;font-size:0.75rem;font-weight:700;">Restricted</span>' : '' !!}
                </div>

                <div style="display:grid;gap:0.8rem;">
                    <!-- Warn -->
                    <form action="{{ route('admin.issue-reports.warn', $report) }}" method="POST" onsubmit="return confirm('Warn this user?');">
                        @csrf
                        <button type="submit" style="width:100%;text-align:left;background:#fff;border:1px solid #fbd38d;color:#dd6b20;padding:0.6rem 0.8rem;border-radius:8px;font-weight:600;cursor:pointer;">
                            <i class="bi bi-exclamation-triangle"></i> Issue Warning (Currently: {{ $target->warning_count }})
                        </button>
                    </form>

                    <!-- Restrict -->
                    @if(!$target->isRestricted())
                    <form action="{{ route('admin.issue-reports.restrict', $report) }}" method="POST" style="display:flex;gap:0.5rem;align-items:center;">
                        @csrf
                        <input type="number" name="days" value="7" min="1" max="365" style="width:70px;padding:0.5rem;border:1px solid #e2e8f0;border-radius:6px;" required>
                        <span style="font-size:0.8rem;color:#64748b;">days</span>
                        <button type="submit" style="flex:1;text-align:center;background:#fff;border:1px solid #f6ad55;color:#c05621;padding:0.6rem 0.8rem;border-radius:8px;font-weight:600;cursor:pointer;" onclick="return confirm('Restrict user?');">
                            <i class="bi bi-dash-circle"></i> Restrict
                        </button>
                    </form>
                    @endif

                    <!-- Ban -->
                    @if(!$target->isBanned())
                    <form action="{{ route('admin.issue-reports.ban', $report) }}" method="POST" style="display:flex;flex-direction:column;gap:0.5rem;">
                        @csrf
                        <input type="text" name="reason" placeholder="Reason for ban" required style="padding:0.5rem;border:1px solid #e2e8f0;border-radius:6px;font-size:0.9rem;">
                        <button type="submit" style="width:100%;text-align:center;background:#fee2e2;border:1px solid #fc8181;color:#c53030;padding:0.6rem 0.8rem;border-radius:8px;font-weight:600;cursor:pointer;" onclick="return confirm('Permanently ban this user?');">
                            <i class="bi bi-x-circle"></i> Permanently Ban
                        </button>
                    </form>
                    @endif
                    
                    <a href="{{ route('admin.users.show', $target) }}" style="display:inline-block;margin-top:0.5rem;text-align:center;color:var(--shell-brand);font-weight:600;text-decoration:none;">
                        View Full User Profile <i class="bi bi-box-arrow-up-right"></i>
                    </a>
                </div>
            @else
                <p style="color:#667085;">No target user associated with this report.</p>
            @endif
        </div>
    </div>
</div>
@endsection
