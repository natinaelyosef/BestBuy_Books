@extends('admin.layout')

@section('title', $user->name . ' - User Profile')

@section('content')
<div class="card">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
        <div>
            <a href="{{ route('admin.users.index') }}" style="text-decoration:none;color:var(--shell-brand);font-size:0.85rem;font-weight:600;"><i class="bi bi-arrow-left"></i> Back to Users</a>
            <h1 style="margin:0.5rem 0 0 0;">{{ $user->name }}</h1>
            <p style="color:#667085;margin-top:0.35rem;">{{ ucfirst(str_replace('_', ' ', $user->account_type)) }} Profile</p>
        </div>
        <div>
            {!! $user->statusBadge() === 'Active' ? '<span style="background:#dcfce7;color:#166534;padding:0.4rem 0.8rem;border-radius:999px;font-weight:700;">Active</span>' : '' !!}
            {!! $user->statusBadge() === 'Banned' ? '<span style="background:#fee2e2;color:#991b1b;padding:0.4rem 0.8rem;border-radius:999px;font-weight:700;">Banned</span>' : '' !!}
            {!! $user->statusBadge() === 'Restricted' ? '<span style="background:#ffedd5;color:#9a3412;padding:0.4rem 0.8rem;border-radius:999px;font-weight:700;">Restricted</span>' : '' !!}
            {!! $user->statusBadge() === 'Inactive' ? '<span style="background:#f3f4f6;color:#374151;padding:0.4rem 0.8rem;border-radius:999px;font-weight:700;">Inactive</span>' : '' !!}
        </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(300px, 1fr));gap:1.5rem;margin-top:2rem;">
        <!-- User Info -->
        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:1.5rem;">
            <h3 style="margin-top:0;font-size:1.1rem;">Details</h3>
            
            <table style="width:100%;font-size:0.9rem;margin-top:1rem;">
                <tr>
                    <td style="padding:0.5rem 0;color:#64748b;width:120px;">Email:</td>
                    <td style="padding:0.5rem 0;font-weight:500;">{{ $user->email }}</td>
                </tr>
                <tr>
                    <td style="padding:0.5rem 0;color:#64748b;">Joined:</td>
                    <td style="padding:0.5rem 0;font-weight:500;">{{ $user->created_at->format('F j, Y') }}</td>
                </tr>
                <tr>
                    <td style="padding:0.5rem 0;color:#64748b;">Warnings:</td>
                    <td style="padding:0.5rem 0;font-weight:500;color:{{ $user->warning_count > 0 ? '#ea580c' : 'inherit' }}">{{ $user->warning_count }}</td>
                </tr>
                @if($user->isRestricted())
                <tr>
                    <td style="padding:0.5rem 0;color:#64748b;">Restricted Until:</td>
                    <td style="padding:0.5rem 0;font-weight:500;color:#ea580c;">{{ $user->restricted_until->format('F j, Y H:i') }}</td>
                </tr>
                @endif
                @if($user->isBanned())
                <tr>
                    <td style="padding:0.5rem 0;color:#64748b;">Banned On:</td>
                    <td style="padding:0.5rem 0;font-weight:500;color:#dc2626;">{{ $user->banned_at->format('F j, Y H:i') }}</td>
                </tr>
                <tr>
                    <td style="padding:0.5rem 0;color:#64748b;vertical-align:top;">Ban Reason:</td>
                    <td style="padding:0.5rem 0;font-weight:500;color:#dc2626;">{{ $user->ban_reason }}</td>
                </tr>
                @endif
            </table>
        </div>

        <!-- Moderation Actions -->
        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:1.5rem;">
            <h3 style="margin-top:0;font-size:1.1rem;">Moderation Actions</h3>
            
            <div style="display:flex;flex-direction:column;gap:1rem;margin-top:1rem;">
                <!-- Warn -->
                <form action="{{ route('admin.users.warn', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to issue a warning to this user?');">
                    @csrf
                    <button type="submit" style="width:100%;text-align:left;background:#fff;border:1px solid #fbd38d;color:#dd6b20;padding:0.8rem 1rem;border-radius:8px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:0.5rem;">
                        <i class="bi bi-exclamation-triangle"></i> Issue Warning (Currently: {{ $user->warning_count }})
                    </button>
                </form>

                <!-- Restrict -->
                @if($user->isRestricted())
                    <form action="{{ route('admin.users.unrestrict', $user) }}" method="POST">
                        @csrf
                        <button type="submit" style="width:100%;text-align:left;background:#fff;border:1px solid #38a169;color:#2f855a;padding:0.8rem 1rem;border-radius:8px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:0.5rem;">
                            <i class="bi bi-unlock"></i> Lift Restriction
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.users.restrict', $user) }}" method="POST" style="display:flex;gap:0.5rem;align-items:center;">
                        @csrf
                        <input type="number" name="days" value="7" min="1" max="365" style="width:70px;padding:0.75rem;border:1px solid #e2e8f0;border-radius:8px;">
                        <span style="font-size:0.9rem;color:#64748b;">days</span>
                        <button type="submit" style="flex:1;text-align:center;background:#fff;border:1px solid #f6ad55;color:#c05621;padding:0.8rem 1rem;border-radius:8px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:0.5rem;" onclick="return confirm('Restrict this user?');">
                            <i class="bi bi-dash-circle"></i> Restrict User
                        </button>
                    </form>
                @endif

                <!-- Ban -->
                @if($user->isBanned())
                    <form action="{{ route('admin.users.unban', $user) }}" method="POST">
                        @csrf
                        <button type="submit" style="width:100%;text-align:center;background:#fff;border:1px solid #38a169;color:#2f855a;padding:0.8rem 1rem;border-radius:8px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:0.5rem;" onclick="return confirm('Are you sure you want to unban this user?');">
                            <i class="bi bi-shield-check"></i> Unban User
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.users.ban', $user) }}" method="POST" style="display:flex;flex-direction:column;gap:0.5rem;">
                        @csrf
                        <input type="text" name="reason" placeholder="Reason for ban (required)" required style="padding:0.75rem;border:1px solid #e2e8f0;border-radius:8px;width:100%;">
                        <button type="submit" style="width:100%;text-align:center;background:#fee2e2;border:1px solid #fc8181;color:#c53030;padding:0.8rem 1rem;border-radius:8px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:0.5rem;" onclick="return confirm('Are you absolutely sure you want to permanently ban this user?');">
                            <i class="bi bi-x-circle"></i> Permanently Ban
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Related Reports -->
    <div style="margin-top:2.5rem;">
        <h3 style="margin-bottom:1rem;">Recent Reports Involving User</h3>
        <div style="overflow:auto;background:#fff;border:1px solid #e2e8f0;border-radius:8px;">
            <table style="width:100%;border-collapse:collapse;font-size:0.9rem;">
                <thead>
                    <tr style="text-align:left;background:#f8fafc;color:#64748b;">
                        <th style="padding:0.8rem;">Role</th>
                        <th style="padding:0.8rem;">Subject</th>
                        <th style="padding:0.8rem;">Status</th>
                        <th style="padding:0.8rem;">Date</th>
                        <th style="padding:0.8rem;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                    <tr style="border-top:1px solid #e2e8f0;">
                        <td style="padding:0.8rem;">
                            @if($report->user_id === $user->id)
                                <span style="background:#e0e7ff;color:#4338ca;padding:0.2rem 0.5rem;border-radius:4px;font-size:0.75rem;">Reporter</span>
                            @else
                                <span style="background:#fee2e2;color:#b91c1c;padding:0.2rem 0.5rem;border-radius:4px;font-size:0.75rem;">Reported</span>
                            @endif
                        </td>
                        <td style="padding:0.8rem;">{{ $report->subject }}</td>
                        <td style="padding:0.8rem;">{{ ucfirst(str_replace('_', ' ', $report->status)) }}</td>
                        <td style="padding:0.8rem;">{{ $report->created_at->format('M d, Y') }}</td>
                        <td style="padding:0.8rem;text-align:right;">
                            <a href="{{ route('admin.issue-reports.show', $report) }}" style="color:var(--shell-brand);text-decoration:none;font-weight:600;">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding:1.5rem;text-align:center;color:#64748b;">No recent reports found for this user.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
