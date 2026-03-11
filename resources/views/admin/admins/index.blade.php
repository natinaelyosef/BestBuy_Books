@extends('admin.layout')

@section('title', 'Admin Users')

@section('content')
<div class="card">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
        <div>
            <h1 style="margin-top:0;">Admin Users</h1>
            <p style="color:#667085;margin-top:0.35rem;">Manage sub-admin and super-admin accounts.</p>
        </div>
        @if(auth()->user()?->account_type === 'super_admin')
            <a href="{{ route('admin.admins.create') }}" style="text-decoration:none;background:#5b4cff;color:#fff;padding:0.6rem 1rem;border-radius:10px;font-weight:700;">
                <i class="bi bi-plus-circle"></i> New Admin
            </a>
        @endif
    </div>

    <div style="margin-top:1.2rem;overflow:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:0.9rem;">
            <thead>
                <tr style="text-align:left;color:#667085;">
                    <th style="padding:0.6rem 0.4rem;">Name</th>
                    <th style="padding:0.6rem 0.4rem;">Email</th>
                    <th style="padding:0.6rem 0.4rem;">Role</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                <tr style="border-top:1px solid #e5e7f1;">
                    <td style="padding:0.7rem 0.4rem;">{{ $admin->name }}</td>
                    <td style="padding:0.7rem 0.4rem;">{{ $admin->email }}</td>
                    <td style="padding:0.7rem 0.4rem;">{{ ucfirst(str_replace('_', ' ', $admin->account_type)) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="padding:1rem;text-align:center;color:#667085;">No admin accounts found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
