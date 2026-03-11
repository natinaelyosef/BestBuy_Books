@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="card">
    <h1 style="margin-top:0;">Admin Dashboard</h1>
    <p style="color:#667085;margin-top:0.4rem;">Overview of support activity and admin coverage.</p>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-top:1.4rem;">
        <div style="background:#f1f0ff;border-radius:12px;padding:1rem;">
            <div style="font-size:0.8rem;color:#5b4cff;font-weight:700;">Open Reports</div>
            <div style="font-size:1.8rem;font-weight:800;">{{ $openReports }}</div>
        </div>
        <div style="background:#ecf8ff;border-radius:12px;padding:1rem;">
            <div style="font-size:0.8rem;color:#0b6bcb;font-weight:700;">Open Chats</div>
            <div style="font-size:1.8rem;font-weight:800;">{{ $openChats }}</div>
        </div>
        <div style="background:#fff5e8;border-radius:12px;padding:1rem;">
            <div style="font-size:0.8rem;color:#d97706;font-weight:700;">Admin Users</div>
            <div style="font-size:1.8rem;font-weight:800;">{{ $adminCount }}</div>
        </div>
    </div>
</div>
@endsection
