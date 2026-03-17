@extends('store.registration-layout')

@section('title', 'PDF Requests')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <div>
            <h1 class="h3 mb-1">PDF Requests</h1>
            <p class="text-muted mb-0">Approve or decline customer PDF download requests.</p>
        </div>
        <form method="GET" class="d-flex gap-2">
            <select name="status" class="form-select">
                <option value="all" @selected(($statusFilter ?? 'all') === 'all')>All</option>
                <option value="pending" @selected(($statusFilter ?? 'all') === 'pending')>Pending</option>
                <option value="approved" @selected(($statusFilter ?? 'all') === 'approved')>Approved</option>
                <option value="rejected" @selected(($statusFilter ?? 'all') === 'rejected')>Declined</option>
            </select>
            <button class="btn btn-outline-primary" type="submit">Filter</button>
        </form>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Book</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Requested</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $request)
                            @php
                                $statusClass = match($request->status) {
                                    'pending' => 'warning',
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                    default => 'secondary',
                                };
                                $hasPdf = $request->book && $request->book->pdf_path;
                            @endphp
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $request->book?->title ?? 'Book' }}</div>
                                    <div class="text-muted small">{{ $request->book?->author }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $request->customer?->name ?? 'Customer' }}</div>
                                    <div class="text-muted small">{{ $request->customer?->email }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $statusClass }}">{{ ucfirst($request->status) }}</span>
                                    @if($request->status === 'pending' && !$request->book?->pdf_path)
                                        <div class="text-danger small mt-1">No PDF uploaded</div>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $request->created_at->format('M d, Y') }}</td>
                                <td class="text-end">
                                    @if($request->status === 'pending')
                                        <form method="POST" action="{{ route('store.pdf-requests.approve', $request->id) }}" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button class="btn btn-sm btn-success" type="submit" @disabled(!$hasPdf)>
                                                <i class="bi bi-check2"></i> Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('store.pdf-requests.reject', $request->id) }}" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button class="btn btn-sm btn-outline-danger" type="submit">
                                                <i class="bi bi-x"></i> Decline
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small">No actions</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No PDF requests yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
