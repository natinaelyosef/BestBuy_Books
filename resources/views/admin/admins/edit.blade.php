@extends('admin.layout')

@section('title', 'Edit Admin User')

@section('content')
<div class="card" style="max-width: 600px;">
    <a href="{{ route('admin.admins.index') }}" style="text-decoration:none;color:#5b4cff;font-weight:700;display:inline-block;margin-bottom:1rem;">
        <i class="bi bi-arrow-left"></i> Back to Admin Users
    </a>

    <h1 style="margin-top:0;">Edit Admin User</h1>
    <p style="color:#667085;margin-bottom:2rem;">Modify details or permissions for {{ $admin->name }}.</p>

    <form method="POST" action="{{ route('admin.admins.update', $admin) }}" style="display:flex;flex-direction:column;gap:1.2rem;">
        @csrf
        @method('PUT')

        <div style="display:flex;flex-direction:column;gap:0.4rem;">
            <label for="name" style="font-weight:600;color:#344054;">Full Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $admin->name) }}" required style="padding:0.6rem 0.8rem;border:1px solid #d0d5dd;border-radius:8px;">
            @error('name')<span style="color:#d92d20;font-size:0.85rem;">{{ $message }}</span>@enderror
        </div>

        <div style="display:flex;flex-direction:column;gap:0.4rem;">
            <label for="email" style="font-weight:600;color:#344054;">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email', $admin->email) }}" required style="padding:0.6rem 0.8rem;border:1px solid #d0d5dd;border-radius:8px;">
            @error('email')<span style="color:#d92d20;font-size:0.85rem;">{{ $message }}</span>@enderror
        </div>

        <div style="display:flex;flex-direction:column;gap:0.4rem;">
            <label for="account_type" style="font-weight:600;color:#344054;">Role</label>
            <select id="account_type" name="account_type" required style="padding:0.6rem 0.8rem;border:1px solid #d0d5dd;border-radius:8px;">
                <option value="sub_admin" @selected(old('account_type', $admin->account_type) === 'sub_admin')>Sub Admin (Can manage reports, users, chats)</option>
                <option value="super_admin" @selected(old('account_type', $admin->account_type) === 'super_admin')>Super Admin (Full access, can manage admins)</option>
            </select>
            @error('account_type')<span style="color:#d92d20;font-size:0.85rem;">{{ $message }}</span>@enderror
        </div>

        <div style="display:flex;flex-direction:column;gap:0.4rem;">
            <label style="font-weight:600;color:#344054;display:flex;align-items:center;gap:0.5rem;">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $admin->is_active ?? true))>
                Active Account
            </label>
            <p style="margin:0;font-size:0.85rem;color:#667085;">Uncheck this to prevent the user from logging in without deleting their history.</p>
        </div>

        <hr style="border:none;border-top:1px solid #e5e7f1;margin:1rem 0;">

        <div style="display:flex;flex-direction:column;gap:0.4rem;">
            <label for="password" style="font-weight:600;color:#344054;">New Password (Optional)</label>
            <input type="password" id="password" name="password" placeholder="Leave blank to keep current password" style="padding:0.6rem 0.8rem;border:1px solid #d0d5dd;border-radius:8px;">
            @error('password')<span style="color:#d92d20;font-size:0.85rem;">{{ $message }}</span>@enderror
        </div>

        <div style="display:flex;flex-direction:column;gap:0.4rem;">
            <label for="password_confirmation" style="font-weight:600;color:#344054;">Confirm New Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" style="padding:0.6rem 0.8rem;border:1px solid #d0d5dd;border-radius:8px;">
        </div>

        <div style="margin-top:1rem;">
            <button type="submit" style="background:#5b4cff;color:#fff;border:none;padding:0.7rem 1.5rem;border-radius:8px;font-weight:600;cursor:pointer;">
                <i class="bi bi-save"></i> Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
