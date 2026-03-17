@extends('store.registration-layout')

@section('title', 'Manage Books')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <div>
            <h1 class="h3 mb-1">Manage Books</h1>
            <p class="text-muted mb-0">Edit, remove, and upload PDFs for your catalog.</p>
        </div>
        <a href="{{ route('add.book.registration') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Add Book
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Book</th>
                            <th>Inventory</th>
                            <th>PDF</th>
                            <th>Updated</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($books as $book)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div style="width:48px;height:64px;">
                                            @if($book->cover_image_path)
                                                <img src="{{ asset($book->cover_image_path) }}" alt="{{ $book->title }}" class="rounded" style="width:100%;height:100%;object-fit:cover;">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center bg-light text-muted rounded" style="width:100%;height:100%;font-size:0.7rem;">
                                                    No Image
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $book->title }}</div>
                                            <div class="text-muted small">{{ $book->author }}</div>
                                            <div class="text-muted small">{{ $book->genre ? ucfirst($book->genre) : 'General' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small text-muted">Total: {{ $book->total_copies }}</div>
                                    <div class="small text-muted">Rent: {{ $book->available_rent }}</div>
                                    <div class="small text-muted">Sale: {{ $book->available_sale }}</div>
                                </td>
                                <td>
                                    @if($book->pdf_path)
                                        <div class="d-flex flex-column gap-1">
                                            <span class="badge bg-success">Uploaded</span>
                                            <span class="small text-muted text-truncate" style="max-width: 200px;">
                                                {{ $book->pdf_name ?? 'PDF file' }}
                                            </span>
                                            <a href="{{ route('store.books.pdf', $book->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-download me-1"></i> Download
                                            </a>
                                        </div>
                                    @else
                                        <span class="badge bg-secondary">No PDF</span>
                                    @endif
                                </td>
                                <td class="text-muted small">{{ $book->updated_at->format('M d, Y') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('store.books.edit', $book->id) }}" class="btn btn-outline-primary btn-sm me-1">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('store.books.destroy', $book->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this book permanently?');">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No books to manage yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
