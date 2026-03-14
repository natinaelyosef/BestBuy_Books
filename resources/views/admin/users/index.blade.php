@extends('admin.layout')

@section('title', 'Manage Users')

@section('content')
<div class="card">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
        <div>
            <h1 style="margin-top:0;">Site Users</h1>
            <p style="color:#667085;margin-top:0.35rem;">Manage customers and store owners.</p>
        </div>
        
        <form action="{{ route('admin.users.index') }}" method="GET" style="display:flex;gap:0.5rem;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email..." style="padding:0.5rem 0.8rem;border:1px solid #e5e7f1;border-radius:8px;font-size:0.9rem;">
            <select name="type" style="padding:0.5rem 0.8rem;border:1px solid #e5e7f1;border-radius:8px;font-size:0.9rem;">
                <option value="">All Types</option>
                <option value="customer" @selected(request('type') == 'customer')>Customers</option>
                <option value="store_owner" @selected(request('type') == 'store_owner')>Store Owners</option>
            </select>
            <select name="status" style="padding:0.5rem 0.8rem;border:1px solid #e5e7f1;border-radius:8px;font-size:0.9rem;">
                <option value="">All Statuses</option>
                <option value="active" @selected(request('status') == 'active')>Active</option>
                <option value="banned" @selected(request('status') == 'banned')>Banned</option>
                <option value="restricted" @selected(request('status') == 'restricted')>Restricted</option>
            </select>
            <button type="submit" style="background:#5b4cff;color:#fff;border:none;padding:0.5rem 1rem;border-radius:8px;cursor:pointer;"><i class="bi bi-search"></i></button>
        </form>
    </div>

    <div style="margin-top:1.4rem;overflow:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:0.9rem;">
            <thead>
                <tr style="text-align:left;color:#667085;">
                    <th style="padding:0.6rem 0.4rem;">User</th>
                    <th style="padding:0.6rem 0.4rem;">Type</th>
                    <th style="padding:0.6rem 0.4rem;">Status</th>
                    <th style="padding:0.6rem 0.4rem;">Warnings</th>
                    <th style="padding:0.6rem 0.4rem;">Joined</th>
                    <th style="padding:0.6rem 0.4rem;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr style="border-top:1px solid #e5e7f1;">
                    <td style="padding:0.7rem 0.4rem;">
                        <div style="font-weight:600;">{{ $user->name }}</div>
                        <div style="color:#667085;font-size:0.8rem;">{{ $user->email }}</div>
                    </td>
                    <td style="padding:0.7rem 0.4rem;">{{ ucfirst(str_replace('_', ' ', $user->account_type)) }}</td>
                    <td style="padding:0.7rem 0.4rem;">
                        @if($user->isBanned())
                            <span style="background:#fee2e2;color:#991b1b;padding:0.2rem 0.6rem;border-radius:999px;font-size:0.75rem;font-weight:700;">Banned</span>
                        @elseif($user->isRestricted())
                            <span style="background:#ffedd5;color:#9a3412;padding:0.2rem 0.6rem;border-radius:999px;font-size:0.75rem;font-weight:700;">Restricted</span>
                        @else
                            <span style="background:#dcfce7;color:#166534;padding:0.2rem 0.6rem;border-radius:999px;font-size:0.75rem;font-weight:700;">Active</span>
                        @endif
                    </td>
                    <td style="padding:0.7rem 0.4rem;">
                        @if($user->warning_count > 0)
                            <span style="color:#eab308;font-weight:bold;">{{ $user->warning_count }} <i class="bi bi-exclamation-triangle-fill"></i></span>
                        @else
                            <span style="color:#9ca3af;">0</span>
                        @endif
                    </td>
                    <td style="padding:0.7rem 0.4rem;">{{ $user->created_at->format('M Y') }}</td>
                    <td style="padding:0.7rem 0.4rem;text-align:right;">
                        <a href="{{ route('admin.users.show', $user) }}" style="text-decoration:none;color:#5b4cff;font-weight:700;">View Profile</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:2rem;text-align:center;color:#667085;">No users found matching your criteria.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="margin-top:1.5rem;">
        {{ $users->links() }}
    </div>
</div>
@endsection
