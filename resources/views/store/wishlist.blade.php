@extends('store.registration-layout')

@section('title', 'Wishlisted Books')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <div>
            <h1 class="h3 mb-1">Wishlisted Books</h1>
            <p class="text-muted mb-0">See which customers saved your books.</p>
        </div>
        <a href="{{ route('manage.books') }}" class="btn btn-outline-primary">
            <i class="bi bi-book"></i> Manage Books
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Total Wishlists</div>
                    <div class="fs-3 fw-bold">{{ $wishlistCount ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Unique Books</div>
                    <div class="fs-3 fw-bold">{{ $uniqueBooks ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Interested Customers</div>
                    <div class="fs-3 fw-bold">{{ $uniqueCustomers ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Book</th>
                            <th>Customer</th>
                            <th>Wishlisted On</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($wishlistItems as $item)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $item->book?->title ?? 'Book' }}</div>
                                    <div class="text-muted small">{{ $item->book?->author }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $item->customer?->name ?? 'Customer' }}</div>
                                    <div class="text-muted small">{{ $item->customer?->email }}</div>
                                </td>
                                <td class="text-muted">{{ $item->created_at->format('M d, Y') }}</td>
                                <td class="text-end">
                                    @if($item->book)
                                        <a href="{{ route('books.edit', $item->book->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i> Edit Book
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No wishlisted books yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
