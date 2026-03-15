@extends('store.registration-layout')

@section('content')
<div class="container-fluid px-4 py-5">
    <!-- Header Section with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-gradient-primary text-white shadow-lg" 
                 style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-white bg-opacity-25 p-3">
                                <i class="bi bi-flag-fill fs-2"></i>
                            </div>
                            <div>
                                <h1 class="h2 mb-2 fw-bold">My Reports</h1>
                                <p class="mb-0 opacity-90">Track and manage reports you've filed against customers</p>
                            </div>
                        </div>
                        <a href="{{ route('store.issue-reports.create') }}" class="btn btn-light btn-lg px-4 py-2">
                            <i class="bi bi-plus-circle-fill me-2"></i>
                            New Report
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary bg-opacity-10">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary p-3 me-3">
                            <i class="bi bi-flag text-white"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Total Reports</h6>
                            <h3 class="mb-0 fw-bold">{{ $reports->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning bg-opacity-10">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-warning p-3 me-3">
                            <i class="bi bi-hourglass-split text-white"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Pending</h6>
                            <h3 class="mb-0 fw-bold">{{ $reports->where('status', 'open')->count() + $reports->where('status', 'in_review')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success bg-opacity-10">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-success p-3 me-3">
                            <i class="bi bi-check-circle text-white"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Resolved</h6>
                            <h3 class="mb-0 fw-bold">{{ $reports->where('status', 'resolved')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-danger bg-opacity-10">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-danger p-3 me-3">
                            <i class="bi bi-exclamation-triangle text-white"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">High Priority</h6>
                            <h3 class="mb-0 fw-bold">{{ $reports->where('priority', 'high')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="bi bi-check-circle-fill fs-4"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <p class="mb-0 fw-semibold">{{ session('status') }}</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- Filters Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Search reports...">
                    </div>
                </div>
                <div class="col-md-2">
                    <select id="statusFilter" class="form-select">
                        <option value="">All Status</option>
                        <option value="open">Open</option>
                        <option value="in_review">In Review</option>
                        <option value="resolved">Resolved</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select id="priorityFilter" class="form-select">
                        <option value="">All Priority</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select id="dateFilter" class="form-select">
                        <option value="">All Time</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                    </select>
                </div>
                <div class="col-md-3 text-end">
                    <button class="btn btn-outline-secondary" onclick="resetFilters()">
                        <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports Table Card -->
    <div class="card border-0 shadow-lg">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="reportsTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 text-nowrap">
                                <i class="bi bi-info-circle me-2 text-primary"></i>Report Info
                            </th>
                            <th class="px-4 py-3 text-nowrap">
                                <i class="bi bi-person me-2 text-primary"></i>Reported Customer
                            </th>
                            <th class="px-4 py-3 text-nowrap">
                                <i class="bi bi-tag me-2 text-primary"></i>Status
                            </th>
                            <th class="px-4 py-3 text-nowrap">
                                <i class="bi bi-calendar me-2 text-primary"></i>Date
                            </th>
                            <th class="px-4 py-3 text-nowrap text-end">
                                <i class="bi bi-gear me-2 text-primary"></i>Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                        <tr class="report-row" 
                            data-status="{{ $report->status }}"
                            data-priority="{{ $report->priority }}"
                            data-date="{{ $report->created_at->format('Y-m-d') }}"
                            data-search="{{ strtolower($report->subject . ' ' . ($report->reportedUser?->name ?? '')) }}">
                            
                            <!-- Report Info -->
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="priority-indicator me-3">
                                        @if($report->priority == 'high')
                                            <span class="badge bg-danger bg-opacity-10 p-2 rounded-circle">
                                                <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                                            </span>
                                        @elseif($report->priority == 'medium')
                                            <span class="badge bg-warning bg-opacity-10 p-2 rounded-circle">
                                                <i class="bi bi-dash-circle-fill text-warning"></i>
                                            </span>
                                        @else
                                            <span class="badge bg-success bg-opacity-10 p-2 rounded-circle">
                                                <i class="bi bi-check-circle-fill text-success"></i>
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ $report->subject }}</h6>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-{{ $report->priority === 'high' ? 'danger' : ($report->priority === 'medium' ? 'warning' : 'success') }} bg-opacity-10 text-{{ $report->priority === 'high' ? 'danger' : ($report->priority === 'medium' ? 'warning' : 'success') }} px-2 py-1 rounded-pill">
                                                <i class="bi bi-flag-fill me-1" style="font-size: 0.7rem;"></i>
                                                {{ ucfirst($report->priority) }}
                                            </span>
                                            @if($report->hasEvidence())
                                                <span class="badge bg-info bg-opacity-10 text-info px-2 py-1 rounded-pill">
                                                    <i class="bi bi-paperclip me-1"></i>
                                                    Evidence
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Reported Customer -->
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    @if($report->reportedUser?->avatar)
                                        <img class="rounded-circle me-2" src="{{ asset('storage/' . $report->reportedUser->avatar) }}" 
                                             alt="" width="32" height="32" style="object-fit: cover;">
                                    @else
                                        <div class="avatar-placeholder rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-2" 
                                             style="width: 32px; height: 32px;">
                                            <span class="fw-bold text-primary">{{ substr($report->reportedUser?->name ?? '?', 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-semibold">{{ $report->reportedUser?->name ?? 'Unknown' }}</div>
                                        <small class="text-muted">{{ $report->reportedUser?->email ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Status -->
                            <td class="px-4 py-3">
                                @php
                                    $statusConfig = [
                                        'open' => ['bg' => 'primary', 'icon' => 'bi-envelope-open'],
                                        'in_review' => ['bg' => 'warning', 'icon' => 'bi-eye'],
                                        'resolved' => ['bg' => 'success', 'icon' => 'bi-check-circle'],
                                        'closed' => ['bg' => 'secondary', 'icon' => 'bi-x-circle'],
                                    ];
                                    $config = $statusConfig[$report->status] ?? $statusConfig['open'];
                                @endphp
                                <span class="badge bg-{{ $config['bg'] }} bg-opacity-10 text-{{ $config['bg'] }} px-3 py-2 rounded-pill fw-semibold">
                                    <i class="bi {{ $config['icon'] }} me-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                                </span>
                            </td>
                            
                            <!-- Date -->
                            <td class="px-4 py-3">
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">{{ $report->created_at->format('M d, Y') }}</span>
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $report->created_at->format('h:i A') }}
                                    </small>
                                </div>
                            </td>
                            
                            <!-- Actions -->
                            <td class="px-4 py-3 text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('store.issue-reports.show', $report) }}" 
                                       class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="bi bi-eye me-1"></i>
                                        View
                                    </a>
                                    @if($report->status === 'open' || $report->status === 'in_review')
                                        <span class="badge bg-{{ $config['bg'] }} bg-opacity-10 text-{{ $config['bg'] }} px-3 py-2 rounded-pill d-flex align-items-center">
                                            <span class="spinner-grow spinner-grow-sm me-1" style="width: 0.5rem; height: 0.5rem;"></span>
                                            Active
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-5 text-center">
                                <div class="empty-state py-4">
                                    <div class="empty-state-icon mb-3">
                                        <i class="bi bi-flag display-1 text-muted"></i>
                                    </div>
                                    <h4 class="fw-bold mb-2">No Reports Found</h4>
                                    <p class="text-muted mb-4">You haven't filed any reports against customers yet.</p>
                                    <a href="{{ route('store.issue-reports.create') }}" class="btn btn-primary btn-lg px-5">
                                        <i class="bi bi-plus-circle me-2"></i>
                                        File Your First Report
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Table Footer with Summary -->
        @if($reports->count() > 0)
        <div class="card-footer bg-light border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    <i class="bi bi-info-circle me-1"></i>
                    Showing {{ $reports->count() }} report{{ $reports->count() !== 1 ? 's' : '' }}
                </div>
                <div class="d-flex gap-3">
                    <span class="small">
                        <i class="bi bi-circle-fill text-primary me-1" style="font-size: 0.5rem;"></i>
                        Open: {{ $reports->where('status', 'open')->count() }}
                    </span>
                    <span class="small">
                        <i class="bi bi-circle-fill text-warning me-1" style="font-size: 0.5rem;"></i>
                        In Review: {{ $reports->where('status', 'in_review')->count() }}
                    </span>
                    <span class="small">
                        <i class="bi bi-circle-fill text-success me-1" style="font-size: 0.5rem;"></i>
                        Resolved: {{ $reports->where('status', 'resolved')->count() }}
                    </span>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
/* Avatar Placeholder */
.avatar-placeholder {
    transition: all 0.3s;
}

/* Priority Indicators */
.priority-indicator .badge {
    transition: all 0.3s;
}

/* Table Row Hover Effect */
.report-row {
    transition: all 0.3s;
}

.report-row:hover {
    background-color: rgba(78, 115, 223, 0.05) !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
}

/* Status Badge Animation */
.badge .spinner-grow {
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.5;
        transform: scale(0.8);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

/* Empty State */
.empty-state {
    animation: fadeInUp 0.5s ease-out;
}

.empty-state-icon {
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-10px);
    }
    100% {
        transform: translateY(0px);
    }
}

/* Card Hover Effect */
.card {
    transition: all 0.3s;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.1) !important;
}

/* Filter Inputs */
.form-control:focus, .form-select:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

/* Responsive */
@media (max-width: 768px) {
    .stats-row .col-md-3 {
        margin-bottom: 1rem;
    }
    
    .table-responsive {
        font-size: 0.9rem;
    }
    
    .badge {
        white-space: nowrap;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const priorityFilter = document.getElementById('priorityFilter');
    const dateFilter = document.getElementById('dateFilter');
    const rows = document.querySelectorAll('.report-row');
    
    function filterTable() {
        const searchTerm = searchInput?.value.toLowerCase().trim() || '';
        const statusValue = statusFilter?.value || '';
        const priorityValue = priorityFilter?.value || '';
        const dateValue = dateFilter?.value || '';
        
        let visibleCount = 0;
        
        rows.forEach(row => {
            let show = true;
            
            // Search filter
            if (searchTerm) {
                const searchText = row.dataset.search || '';
                if (!searchText.includes(searchTerm)) {
                    show = false;
                }
            }
            
            // Status filter
            if (show && statusValue) {
                const rowStatus = row.dataset.status;
                if (rowStatus !== statusValue) {
                    show = false;
                }
            }
            
            // Priority filter
            if (show && priorityValue) {
                const rowPriority = row.dataset.priority;
                if (rowPriority !== priorityValue) {
                    show = false;
                }
            }
            
            // Date filter
            if (show && dateValue) {
                const rowDate = new Date(row.dataset.date);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                switch(dateValue) {
                    case 'today':
                        show = rowDate.toDateString() === today.toDateString();
                        break;
                    case 'week':
                        const weekAgo = new Date(today);
                        weekAgo.setDate(weekAgo.getDate() - 7);
                        show = rowDate >= weekAgo;
                        break;
                    case 'month':
                        const monthAgo = new Date(today);
                        monthAgo.setMonth(monthAgo.getMonth() - 1);
                        show = rowDate >= monthAgo;
                        break;
                }
            }
            
            row.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });
        
        // Show empty state message if no rows visible
        const emptyMessage = document.querySelector('.empty-state')?.closest('tr');
        if (emptyMessage) {
            emptyMessage.style.display = visibleCount === 0 ? '' : 'none';
        }
    }
    
    // Add event listeners
    if (searchInput) searchInput.addEventListener('input', filterTable);
    if (statusFilter) statusFilter.addEventListener('change', filterTable);
    if (priorityFilter) priorityFilter.addEventListener('change', filterTable);
    if (dateFilter) dateFilter.addEventListener('change', filterTable);
    
    // Reset filters
    window.resetFilters = function() {
        if (searchInput) searchInput.value = '';
        if (statusFilter) statusFilter.value = '';
        if (priorityFilter) priorityFilter.value = '';
        if (dateFilter) dateFilter.value = '';
        filterTable();
    };
    
    // Auto-dismiss alert
    const alert = document.querySelector('.alert');
    if (alert) {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    }
    
    // Add tooltips
    const evidenceBadges = document.querySelectorAll('.badge.bg-info');
    evidenceBadges.forEach(badge => {
        badge.setAttribute('title', 'This report includes supporting evidence');
    });
});
</script>
@endsection