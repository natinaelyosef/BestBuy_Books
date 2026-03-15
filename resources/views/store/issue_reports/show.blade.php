@extends('store.registration-layout')

@section('content')
<div class="container-fluid px-4 py-5">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-gradient-primary text-white shadow-lg" 
                 style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('store.issue-reports.index') }}" class="text-white me-3 opacity-75 hover-opacity-100 transition-opacity">
                            <i class="bi bi-arrow-left-circle-fill fs-2"></i>
                        </a>
                        <div>
                            <h1 class="h2 mb-2 fw-bold">Report Details</h1>
                            <p class="mb-0 opacity-90">Report #{{ $issueReport->id }}</p>
                        </div>
                        <div class="ms-auto d-none d-md-block">
                            <i class="bi bi-flag-fill fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Status Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="status-icon bg-{{ $issueReport->priority === 'high' ? 'danger' : ($issueReport->priority === 'medium' ? 'warning' : 'success') }} bg-opacity-10 p-3 rounded-circle">
                                <i class="bi bi-flag-fill fs-3 text-{{ $issueReport->priority === 'high' ? 'danger' : ($issueReport->priority === 'medium' ? 'warning' : 'success') }}"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">{{ $issueReport->subject }}</h5>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    Submitted on {{ $issueReport->created_at->format('F j, Y \a\t g:i A') }}
                                </p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            @php
                                $statusColors = [
                                    'open' => ['bg' => 'primary', 'text' => 'Open'],
                                    'in_review' => ['bg' => 'warning', 'text' => 'In Review'],
                                    'resolved' => ['bg' => 'success', 'text' => 'Resolved'],
                                    'closed' => ['bg' => 'secondary', 'text' => 'Closed'],
                                ];
                                $statusColor = $statusColors[$issueReport->status] ?? $statusColors['open'];
                                
                                $priorityColors = [
                                    'low' => ['bg' => 'success', 'text' => 'Low Priority'],
                                    'medium' => ['bg' => 'warning', 'text' => 'Medium Priority'],
                                    'high' => ['bg' => 'danger', 'text' => 'High Priority'],
                                ];
                                $priorityColor = $priorityColors[$issueReport->priority] ?? $priorityColors['medium'];
                            @endphp
                            
                            <span class="badge bg-{{ $statusColor['bg'] }} bg-opacity-10 text-{{ $statusColor['bg'] }} px-3 py-2 rounded-pill fw-semibold">
                                <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>
                                {{ $statusColor['text'] }}
                            </span>
                            
                            <span class="badge bg-{{ $priorityColor['bg'] }} bg-opacity-10 text-{{ $priorityColor['bg'] }} px-3 py-2 rounded-pill fw-semibold">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                {{ $priorityColor['text'] }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reported Customer Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-person-badge text-primary me-2"></i>
                        Reported Customer
                    </h6>
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-circle bg-primary text-white d-flex align-items-center justify-content-center rounded-circle" style="width: 60px; height: 60px; font-size: 1.5rem;">
                            {{ substr($issueReport->reportedUser?->name ?? '?', 0, 1) }}
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">{{ $issueReport->reportedUser?->name ?? 'Unknown Customer' }}</h5>
                            <p class="text-muted mb-1">{{ $issueReport->reportedUser?->email ?? 'No email available' }}</p>
                            <p class="small text-muted mb-0">
                                <i class="bi bi-person-badge me-1"></i>
                                Account Type: {{ ucfirst(str_replace('_', ' ', $issueReport->reportedUser?->account_type ?? 'customer')) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-chat-text text-primary me-2"></i>
                        Issue Description
                    </h6>
                    <div class="bg-light p-4 rounded-3">
                        <p class="mb-0" style="white-space: pre-wrap;">{{ $issueReport->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Evidence Card -->
            @if($issueReport->hasEvidence())
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-paperclip text-primary me-2"></i>
                        Supporting Evidence
                    </h6>
                    <div class="border rounded-3 p-4 bg-light">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="file-icon">
                                @if($issueReport->isEvidenceImage())
                                    <i class="bi bi-file-image fs-1 text-primary"></i>
                                @elseif(str_contains($issueReport->evidence_type ?? '', 'pdf'))
                                    <i class="bi bi-file-pdf fs-1 text-danger"></i>
                                @elseif(str_contains($issueReport->evidence_type ?? '', 'word'))
                                    <i class="bi bi-file-word fs-1 text-primary"></i>
                                @else
                                    <i class="bi bi-file-earmark-text fs-1 text-secondary"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">{{ $issueReport->evidence_name }}</h6>
                                <p class="small text-muted mb-0">
                                    <i class="bi bi-file-earmark me-1"></i>
                                    {{ $issueReport->evidence_type ?? 'Unknown type' }}
                                </p>
                            </div>
                            <a href="{{ $issueReport->evidence_url }}" target="_blank" class="btn btn-outline-primary">
                                <i class="bi bi-download me-2"></i>
                                Download
                            </a>
                        </div>
                        
                        @if($issueReport->isEvidenceImage())
                            <div class="text-center mt-3 border rounded-3 overflow-hidden bg-white">
                                <img src="{{ $issueReport->evidence_url }}" 
                                     alt="Evidence" 
                                     class="img-fluid" 
                                     style="max-height: 400px; object-fit: contain;"
                                     onclick="window.open(this.src, '_blank')"
                                     role="button">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Admin Assignment Card -->
            @if($issueReport->assignedAdmin)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-shield-check fs-3 text-info"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Assigned Admin</h6>
                            <p class="mb-0">{{ $issueReport->assignedAdmin->name }}</p>
                            <small class="text-muted">{{ $issueReport->assignedAdmin->email }}</small>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Admin Notes Card -->
            @if($issueReport->admin_notes)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-stickies text-primary me-2"></i>
                        Admin Notes
                    </h6>
                    <div class="bg-info bg-opacity-10 p-4 rounded-3">
                        <p class="mb-0">{{ $issueReport->admin_notes }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Back Button -->
            <div class="text-center">
                <a href="{{ route('store.issue-reports.index') }}" class="btn btn-outline-secondary px-5 py-2">
                    <i class="bi bi-arrow-left me-2"></i>
                    Back to Reports
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.5rem;
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    color: white;
}

.hover-opacity-100:hover {
    opacity: 1 !important;
}

.transition-opacity {
    transition: opacity 0.3s;
}

/* Badge styles */
.badge {
    font-weight: 500;
    letter-spacing: 0.3px;
}

/* Card animations */
.card {
    animation: fadeInUp 0.5s ease-out;
    transition: all 0.3s;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.1) !important;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .avatar-circle {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
    }
    
    .status-icon {
        padding: 0.5rem !important;
    }
    
    .status-icon i {
        font-size: 1.2rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth image preview
    const evidenceImage = document.querySelector('.card img[alt="Evidence"]');
    if (evidenceImage) {
        evidenceImage.addEventListener('click', function() {
            window.open(this.src, '_blank');
        });
        
        // Add zoom effect on hover
        evidenceImage.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.02)';
            this.style.transition = 'transform 0.3s';
        });
        
        evidenceImage.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    }
});
</script>
@endsection