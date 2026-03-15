@extends('store.registration-layout')

@section('content')
<div class="container-fluid px-4 py-5">
    <!-- Header Section with Gradient -->
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
                            <h1 class="h2 mb-2 fw-bold">Report a Customer</h1>
                            <p class="mb-0 opacity-90">Help maintain a safe marketplace by reporting policy violations</p>
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
            <!-- Form Card -->
            <div class="card border-0 shadow-lg">
                <div class="card-body p-4 p-lg-5">
                    <!-- Progress Indicator -->
                    <div class="mb-5">
                        <div class="progress-steps d-flex justify-content-between position-relative">
                            <div class="step-item text-center position-relative" style="z-index: 2;">
                                <div class="step-circle bg-primary text-white mx-auto mb-2 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person"></i>
                                </div>
                                <span class="step-label small fw-semibold text-primary">Customer</span>
                            </div>
                            <div class="step-line position-absolute top-0 start-0 w-100" style="height: 2px; background: #e9ecef; top: 20px !important; z-index: 1;"></div>
                            <div class="step-item text-center position-relative" style="z-index: 2;">
                                <div class="step-circle bg-white border border-primary text-primary mx-auto mb-2 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-pencil"></i>
                                </div>
                                <span class="step-label small fw-semibold text-primary">Details</span>
                            </div>
                            <div class="step-item text-center position-relative" style="z-index: 2;">
                                <div class="step-circle bg-white border border-secondary text-secondary mx-auto mb-2 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cloud-arrow-up"></i>
                                </div>
                                <span class="step-label small fw-semibold text-secondary">Evidence</span>
                            </div>
                            <div class="step-item text-center position-relative" style="z-index: 2;">
                                <div class="step-circle bg-white border border-secondary text-secondary mx-auto mb-2 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-check-lg"></i>
                                </div>
                                <span class="step-label small fw-semibold text-secondary">Submit</span>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('store.issue-reports.store') }}" method="POST" enctype="multipart/form-data" id="reportForm" class="space-y-4">
                        @csrf
                        
                        <!-- Customer Selection with Search -->
                        <div class="mb-4">
                            <label for="reported_user_id" class="form-label fw-semibold">
                                <i class="bi bi-person-circle text-primary me-2"></i>
                                Customer to Report
                                <span class="text-danger">*</span>
                            </label>
                            <div class="position-relative">
                                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                                <select name="reported_user_id" id="reported_user_id" 
                                        class="form-select ps-5 py-3 @error('reported_user_id') is-invalid @enderror" 
                                        required>
                                    <option value="">Search or select a customer...</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" @selected(old('reported_user_id') == $customer->id)>
                                            {{ $customer->name }} ({{ $customer->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('reported_user_id')
                                <div class="invalid-feedback d-block mt-2">
                                    <i class="bi bi-exclamation-circle-fill me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-text mt-2">
                                <i class="bi bi-info-circle me-1"></i>
                                Select the customer who violated our policies
                            </div>
                        </div>

                        <!-- Subject Input with Icon -->
                        <div class="mb-4">
                            <label for="subject" class="form-label fw-semibold">
                                <i class="bi bi-tag text-primary me-2"></i>
                                Issue Subject
                                <span class="text-danger">*</span>
                            </label>
                            <div class="position-relative">
                                <i class="bi bi-pencil-square position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                                <input type="text" 
                                       name="subject" 
                                       id="subject" 
                                       value="{{ old('subject') }}" 
                                       required 
                                       placeholder="e.g., Harassment, Fake Listing, Payment Issue"
                                       class="form-control ps-5 py-3 @error('subject') is-invalid @enderror">
                            </div>
                            @error('subject')
                                <div class="invalid-feedback d-block mt-2">
                                    <i class="bi bi-exclamation-circle-fill me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="d-flex justify-content-end mt-1">
                                <small class="text-muted" id="subjectCount">0/255</small>
                            </div>
                        </div>

                        <!-- Description with Character Counter -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">
                                <i class="bi bi-chat-text text-primary me-2"></i>
                                Full Details
                                <span class="text-danger">*</span>
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="6" 
                                      required 
                                      placeholder="Provide all relevant context about the incident, including dates, times, and any other important details..."
                                      class="form-control p-3 @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback d-block mt-2">
                                    <i class="bi bi-exclamation-circle-fill me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Be as specific as possible to help our admin team
                                </small>
                                <small class="text-muted" id="descCount">0/4000</small>
                            </div>
                        </div>

                        <!-- Priority and Evidence Row -->
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label for="priority" class="form-label fw-semibold">
                                    <i class="bi bi-exclamation-triangle text-primary me-2"></i>
                                    Priority
                                </label>
                                <select name="priority" id="priority" class="form-select py-3">
                                    <option value="low" @selected(old('priority') == 'low') class="text-success">
                                        <i class="bi bi-arrow-down"></i> Low Priority
                                    </option>
                                    <option value="medium" @selected(old('priority', 'medium') == 'medium') class="text-warning">
                                        <i class="bi bi-dash"></i> Medium Priority
                                    </option>
                                    <option value="high" @selected(old('priority') == 'high') class="text-danger">
                                        <i class="bi bi-arrow-up"></i> High Priority - Urgent
                                    </option>
                                </select>
                                <div class="mt-2">
                                    <span class="priority-badge priority-{{ old('priority', 'medium') }} d-inline-block px-3 py-1 rounded-pill small fw-semibold">
                                        {{ ucfirst(old('priority', 'medium')) }} Priority
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-paperclip text-primary me-2"></i>
                                    Supporting Evidence
                                    <small class="text-muted fw-normal">(Optional)</small>
                                </label>
                                <div class="upload-area border-2 border-dashed rounded-3 p-4 text-center" 
                                     id="uploadArea"
                                     style="border: 2px dashed #dee2e6; transition: all 0.3s; cursor: pointer;">
                                    <input type="file" 
                                           name="evidence" 
                                           id="evidence" 
                                           class="d-none" 
                                           accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt">
                                    
                                    <div id="uploadPrompt" class="py-3">
                                        <i class="bi bi-cloud-arrow-up fs-1 text-primary mb-3 d-block"></i>
                                        <h6 class="fw-semibold mb-2">Drag & drop or <span class="text-primary">browse</span> files</h6>
                                        <p class="small text-muted mb-0">Supported: JPG, PNG, GIF, PDF, DOC, TXT (Max 10MB)</p>
                                    </div>
                                    
                                    <div id="filePreview" class="d-none">
                                        <div class="d-flex align-items-center justify-content-between bg-light p-3 rounded-3">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-file-earmark-text fs-3 text-primary me-3"></i>
                                                <div class="text-start">
                                                    <div id="fileName" class="fw-semibold"></div>
                                                    <div id="fileSize" class="small text-muted"></div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile()">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @error('evidence')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Guidelines Card -->
                        <div class="card bg-light border-0 mb-4">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3">
                                    <i class="bi bi-shield-check text-primary me-2"></i>
                                    Reporting Guidelines
                                </h6>
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="d-flex">
                                            <i class="bi bi-check-circle-fill text-success me-2 flex-shrink-0"></i>
                                            <small>Only report genuine policy violations</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex">
                                            <i class="bi bi-check-circle-fill text-success me-2 flex-shrink-0"></i>
                                            <small>Include screenshots as evidence when possible</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex">
                                            <i class="bi bi-check-circle-fill text-success me-2 flex-shrink-0"></i>
                                            <small>Reports reviewed within 24-48 hours</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex">
                                            <i class="bi bi-check-circle-fill text-success me-2 flex-shrink-0"></i>
                                            <small>You'll be notified of status changes</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('store.issue-reports.index') }}" class="btn btn-light px-4 py-2">
                                <i class="bi bi-x-lg me-2"></i>
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-5 py-2" id="submitBtn">
                                <i class="bi bi-send-fill me-2"></i>
                                Submit Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Progress Steps */
.progress-steps {
    position: relative;
    padding: 0 15px;
}

.step-circle {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    font-size: 1.2rem;
    transition: all 0.3s;
}

.step-item {
    flex: 1;
    max-width: 80px;
}

.step-line {
    transform: translateY(-50%);
}

/* Priority Badges */
.priority-badge {
    transition: all 0.3s;
}

.priority-low {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.priority-medium {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeeba;
}

.priority-high {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* Upload Area */
.upload-area {
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.upload-area:hover {
    border-color: #4e73df !important;
    background: #f1f4ff;
}

.upload-area.highlight {
    border-color: #4e73df !important;
    background: #e8f0fe;
}

/* Hover Effects */
.hover-opacity-100:hover {
    opacity: 1 !important;
}

.transition-opacity {
    transition: opacity 0.3s;
}

/* Form Controls */
.form-control:focus, .form-select:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

/* Animations */
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

.card {
    animation: fadeInUp 0.5s ease-out;
}

/* Responsive */
@media (max-width: 768px) {
    .step-circle {
        width: 35px;
        height: 35px;
        font-size: 1rem;
    }
    
    .step-label {
        font-size: 0.7rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character counters
    const subject = document.getElementById('subject');
    const description = document.getElementById('description');
    const subjectCount = document.getElementById('subjectCount');
    const descCount = document.getElementById('descCount');
    
    function updateCounts() {
        if (subject && subjectCount) {
            subjectCount.textContent = `${subject.value.length}/255`;
            subjectCount.style.color = subject.value.length > 255 ? '#dc3545' : '#6c757d';
        }
        
        if (description && descCount) {
            descCount.textContent = `${description.value.length}/4000`;
            descCount.style.color = description.value.length > 4000 ? '#dc3545' : '#6c757d';
        }
    }
    
    if (subject) subject.addEventListener('input', updateCounts);
    if (description) description.addEventListener('input', updateCounts);
    updateCounts();

    // File upload handling
    const uploadArea = document.getElementById('uploadArea');
    const evidence = document.getElementById('evidence');
    const uploadPrompt = document.getElementById('uploadPrompt');
    const filePreview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    
    // Drag & drop events
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        uploadArea.addEventListener(eventName, () => {
            uploadArea.classList.add('highlight');
        });
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, () => {
            uploadArea.classList.remove('highlight');
        });
    });
    
    uploadArea.addEventListener('drop', (e) => {
        const file = e.dataTransfer.files[0];
        if (file) handleFile(file);
    });
    
    uploadArea.addEventListener('click', () => {
        evidence.click();
    });
    
    evidence.addEventListener('change', function() {
        if (this.files[0]) handleFile(this.files[0]);
    });
    
    window.removeFile = function() {
        evidence.value = '';
        uploadPrompt.classList.remove('d-none');
        filePreview.classList.add('d-none');
    };
    
    function handleFile(file) {
        // Validate file size (10MB)
        if (file.size > 10 * 1024 * 1024) {
            alert('File size must be less than 10MB');
            return;
        }
        
        // Validate file type
        const allowedTypes = [
            'image/jpeg', 'image/png', 'image/gif', 'application/pdf',
            'application/msword', 
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'text/plain'
        ];
        
        if (!allowedTypes.includes(file.type)) {
            alert('File type not supported. Please upload JPG, PNG, GIF, PDF, DOC, or TXT files.');
            return;
        }
        
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        uploadPrompt.classList.add('d-none');
        filePreview.classList.remove('d-none');
    }
    
    function formatFileSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    }

    // Priority badge update
    const prioritySelect = document.getElementById('priority');
    const priorityBadge = document.querySelector('.priority-badge');
    
    if (prioritySelect && priorityBadge) {
        prioritySelect.addEventListener('change', function() {
            const priority = this.value;
            priorityBadge.textContent = priority.charAt(0).toUpperCase() + priority.slice(1) + ' Priority';
            priorityBadge.className = `priority-badge priority-${priority} d-inline-block px-3 py-1 rounded-pill small fw-semibold`;
        });
    }

    // Form submission loading state
    const form = document.getElementById('reportForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';
        });
    }

    // Customer search functionality
    const customerSelect = document.getElementById('reported_user_id');
    if (customerSelect) {
        // Create search input
        const searchDiv = document.createElement('div');
        searchDiv.className = 'mb-2';
        searchDiv.innerHTML = `
            <input type="text" 
                   id="customerSearch" 
                   class="form-control form-control-sm" 
                   placeholder="Search customers...">
        `;
        customerSelect.parentNode.insertBefore(searchDiv, customerSelect);
        
        const searchInput = document.getElementById('customerSearch');
        const originalOptions = Array.from(customerSelect.options).slice(1); // Skip first option
        
        searchInput.addEventListener('keyup', function() {
            const search = this.value.toLowerCase();
            
            // Clear and repopulate select
            Array.from(customerSelect.options).slice(1).forEach(opt => opt.remove());
            
            const filtered = originalOptions.filter(opt => 
                opt.text.toLowerCase().includes(search)
            );
            
            filtered.forEach(opt => customerSelect.appendChild(opt.cloneNode(true)));
            
            if (filtered.length === 0) {
                const noResult = document.createElement('option');
                noResult.disabled = true;
                noResult.selected = true;
                noResult.textContent = 'No customers found';
                customerSelect.appendChild(noResult);
            }
        });
    }
});
</script>
@endsection