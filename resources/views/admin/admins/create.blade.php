@extends('admin.layout')

@section('title', 'Create Admin')

@section('content')
<div class="card" style="max-width:720px;">
    <a href="{{ route('admin.admins.index') }}" style="text-decoration:none;color:#5b4cff;font-weight:700;">
        <i class="bi bi-arrow-left"></i> Back to admin users
    </a>
    <h1 style="margin-top:0.8rem;">Create Admin Account</h1>

    <form method="POST" action="{{ route('admin.admins.store') }}" style="display:grid;gap:0.9rem;margin-top:1rem;">
        @csrf
        <label style="display:grid;gap:0.4rem;font-weight:600;color:#475467;">
            Full Name
            <input type="text" name="name" required maxlength="255" value="{{ old('name') }}" style="padding:0.6rem;border-radius:10px;border:1px solid #d0d5dd;">
        </label>
        <label style="display:grid;gap:0.4rem;font-weight:600;color:#475467;">
            Email
            <input type="email" name="email" required maxlength="255" value="{{ old('email') }}" style="padding:0.6rem;border-radius:10px;border:1px solid #d0d5dd;">
        </label>
        <label style="display:grid;gap:0.4rem;font-weight:600;color:#475467;">
            Role
            <select name="account_type" required style="padding:0.6rem;border-radius:10px;border:1px solid #d0d5dd;">
                <option value="sub_admin" @selected(old('account_type') === 'sub_admin')>Sub Admin</option>
                <option value="super_admin" @selected(old('account_type') === 'super_admin')>Super Admin</option>
            </select>
        </label>
        <label style="display:grid;gap:0.4rem;font-weight:600;color:#475467;">
            Password
            <input type="password" name="password" required style="padding:0.6rem;border-radius:10px;border:1px solid #d0d5dd;">
        </label>
        <label style="display:grid;gap:0.4rem;font-weight:600;color:#475467;">
            Confirm Password
            <input type="password" name="password_confirmation" required style="padding:0.6rem;border-radius:10px;border:1px solid #d0d5dd;">
        </label>
        <button type="submit" style="padding:0.7rem 1.2rem;border:none;border-radius:10px;background:#5b4cff;color:#fff;font-weight:700;width:fit-content;">
            Create Admin
        </button>
    </form>
</div>
@endsection
