@extends('admin.layout')

@section('title', 'Issue Reports')

@section('content')
<div class="card">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
        <div>
            <h1 style="margin-top:0;">Issue Reports</h1>
            <p style="color:#667085;margin-top:0.35rem;">Review and update customer issues.</p>
        </div>
    </div>

    <div style="margin-top:1.4rem;overflow:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:0.9rem;">
            <thead>
                <tr style="text-align:left;color:#667085;">
                    <th style="padding:0.6rem 0.4rem;">ID</th>
                    <th style="padding:0.6rem 0.4rem;">Subject</th>
                    <th style="padding:0.6rem 0.4rem;">Customer</th>
                    <th style="padding:0.6rem 0.4rem;">Priority</th>
                    <th style="padding:0.6rem 0.4rem;">Status</th>
                    <th style="padding:0.6rem 0.4rem;">Created</th>
                    <th style="padding:0.6rem 0.4rem;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                <tr style="border-top:1px solid #e5e7f1;">
                    <td style="padding:0.7rem 0.4rem;">#{{ $report->id }}</td>
                    <td style="padding:0.7rem 0.4rem;">{{ $report->subject }}</td>
                    <td style="padding:0.7rem 0.4rem;">{{ $report->user?->name ?? 'Unknown' }}</td>
                    <td style="padding:0.7rem 0.4rem;">{{ ucfirst($report->priority) }}</td>
                    <td style="padding:0.7rem 0.4rem;">{{ ucfirst(str_replace('_', ' ', $report->status)) }}</td>
                    <td style="padding:0.7rem 0.4rem;">{{ $report->created_at->format('M d, Y') }}</td>
                    <td style="padding:0.7rem 0.4rem;text-align:right;">
                        <a href="{{ route('admin.issue-reports.show', $report) }}" style="text-decoration:none;color:#5b4cff;font-weight:700;">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding:1.2rem;text-align:center;color:#667085;">No issue reports yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
