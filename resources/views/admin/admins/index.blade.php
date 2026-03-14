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
                    <th style="padding:0.6rem 0.4rem;">Status</th>
                    @if(auth()->user()?->account_type === 'super_admin')
                    <th style="padding:0.6rem 0.4rem;text-align:right;">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                <tr style="border-top:1px solid #e5e7f1;">
                    <td style="padding:0.7rem 0.4rem;">{{ $admin->name }}</td>
                    <td style="padding:0.7rem 0.4rem;">{{ $admin->email }}</td>
                    <td style="padding:0.7rem 0.4rem;">
                        <span style="background:var(--bg);padding:0.2rem 0.5rem;border-radius:4px;font-size:0.8rem;white-space:nowrap;">
                            {{ ucfirst(str_replace('_', ' ', $admin->account_type)) }}
                        </span>
                    </td>
                    <td style="padding:0.7rem 0.4rem;">
                        @if($admin->is_active ?? true)
                            <span style="color:#10b981;font-weight:600;"><i class="bi bi-check-circle-fill"></i> Active</span>
                        @else
                            <span style="color:#ef4444;font-weight:600;"><i class="bi bi-x-circle-fill"></i> Inactive</span>
                        @endif
                    </td>
                    @if(auth()->user()?->account_type === 'super_admin')
                    <td style="padding:0.7rem 0.4rem;text-align:right;">
                        <div style="display:flex;gap:0.4rem;justify-content:flex-end;">
                            <a href="{{ route('admin.admins.edit', $admin) }}" style="padding:0.4rem 0.6rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;color:#475569;text-decoration:none;" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            
                            @if($admin->id !== auth()->id())
                                <form action="{{ route('admin.admins.toggle-active', $admin) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" style="padding:0.4rem 0.6rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;color:{{ $admin->is_active ? '#eab308' : '#10b981' }};cursor:pointer;" title="{{ $admin->is_active ? 'Deactivate' : 'Activate' }}">
                                        <i class="bi {{ $admin->is_active ? 'bi-pause-circle' : 'bi-play-circle' }}"></i>
                                    </button>
                                </form>
                                
                                <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this admin account?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="padding:0.4rem 0.6rem;background:#fef2f2;border:1px solid #fecaca;border-radius:6px;color:#ef4444;cursor:pointer;" title="Delete">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding:1.5rem;text-align:center;color:#667085;">No admin accounts found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
