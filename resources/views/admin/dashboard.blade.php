@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid py-4">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient-primary text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h2 mb-2">Welcome back, {{ $user->name ?? 'Admin' }}!</h1>
                            <p class="mb-0 opacity-75">{{ now()->format('l, F j, Y') }}</p>
                        </div>
                        <div class="d-none d-md-block">
                            <i class="bi bi-shield-shaded fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary bg-opacity-10 p-3 rounded-3">
                            <i class="bi bi-flag-fill text-primary fs-3"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Open Reports</h6>
                            <h3 class="mb-0 fw-bold">{{ $openReports }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-info bg-opacity-10 p-3 rounded-3">
                            <i class="bi bi-chat-dots-fill text-info fs-3"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Open Chats</h6>
                            <h3 class="mb-0 fw-bold">{{ $openChats }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success bg-opacity-10 p-3 rounded-3">
                            <i class="bi bi-people-fill text-success fs-3"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Admin Users</h6>
                            <h3 class="mb-0 fw-bold">{{ $adminCount }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <a href="{{ route('admin.issue-reports.index') }}" class="text-decoration-none">
                                <div class="border rounded-3 p-3 text-center hover-shadow">
                                    <i class="bi bi-flag-fill text-primary fs-2 mb-2"></i>
                                    <h6 class="mb-0">Issue Reports</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('admin.chats.index') }}" class="text-decoration-none">
                                <div class="border rounded-3 p-3 text-center hover-shadow">
                                    <i class="bi bi-chat-dots-fill text-info fs-2 mb-2"></i>
                                    <h6 class="mb-0">Support Chats</h6>
                                </div>
                            </a>
                        </div>
                        @if(auth()->user()?->account_type === 'super_admin')
                        <div class="col-md-3 col-6">
                            <a href="{{ route('admin.admins.index') }}" class="text-decoration-none">
                                <div class="border rounded-3 p-3 text-center hover-shadow">
                                    <i class="bi bi-person-plus-fill text-success fs-2 mb-2"></i>
                                    <h6 class="mb-0">Admin Users</h6>
                                </div>
                            </a>
                        </div>
                        @endif
                        <div class="col-md-3 col-6">
                            <a href="{{ route('admin.admins.create') }}" class="text-decoration-none">
                                <div class="border rounded-3 p-3 text-center hover-shadow">
                                    <i class="bi bi-plus-circle-fill text-warning fs-2 mb-2"></i>
                                    <h6 class="mb-0">Create Admin</h6>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
.hover-shadow {
    transition: all 0.3s ease;
}
.hover-shadow:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
}
.bg-gradient-primary {
    background: linear-gradient(135deg, #1f7a8c 0%, #2f9ca8 55%, #f2a65a 120%);
}
</style>
