@extends('store.registration-layout')

@section('title', 'Inventory')

@section('content')
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h1 class="h3 mb-0">Inventory</h1>
            <span class="text-muted">Track books currently in stock.</span>
        </div>

        @if(session('messages'))
            <div class="alert alert-success">
                @foreach((array) session('messages') as $message)
                    <div>{{ $message }}</div>
                @endforeach
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body">
              
<div class="table-responsive">
    <table class="table table-hover table-striped align-middle shadow-sm">
        <thead class="table-dark text-center">
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Title</th>
                <th>Author</th>
                <th>Genre</th>
                <th>Publication Year</th>
                <th>Total Copies</th>
                <th>Available Rent</th>
                <th>Available Sale</th>
                <th>Rental Price</th>
                <th>Sale Price</th>
                <th>
                    <a href="{{ route('add.book.registration') }}" 
                       class="btn btn-success btn-sm px-3">
                        <i class="fas fa-plus-circle me-1"></i> Add New
                    </a>
                </th>
            </tr>
        </thead>

        <tbody class="text-center">
            @forelse($books as $book)
                <tr>
                    <td class="fw-semibold">{{ $book->id }}</td>
                    <td>
                        <div style="width:48px; height:64px; margin:0 auto;">
                            @if($book->cover_image_path)
                                <img src="{{ asset($book->cover_image_path) }}"
                                     alt="{{ $book->title }}"
                                     loading="lazy"
                                     style="width:100%; height:100%; object-fit:cover; border-radius:6px; border:1px solid #e5e7eb;">
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-light text-muted"
                                     style="width:100%; height:100%; border-radius:6px; border:1px solid #e5e7eb; font-size:0.7rem;">
                                    No Image
                                </div>
                            @endif
                        </div>
                    </td>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->author }}</td>
                    <td>{{ $book->genre }}</td>
                    <td>{{ $book->publication_year ?? '-' }}</td>
                    <td>{{ $book->total_copies }}</td>
                    <td>{{ $book->available_rent }}</td>
                    <td>{{ $book->available_sale }}</td>
                    <td>${{ number_format($book->rental_price, 2) }}</td>
                    <td>${{ number_format($book->sale_price, 2) }}</td>
                    <td class="text-nowrap">
                        <a href="{{ route('books.edit', $book->id) }}" class="btn btn-outline-primary btn-sm me-2">
                            <i class="far fa-edit"></i> edit
                        </a>

                        <form action="{{ route('books.destroy', $book->id) }}" method="post" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="btn btn-outline-danger btn-sm"
                                    onclick="return confirm('Delete this book permanently?');">
                                <i class="far fa-trash-alt"></i>delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-muted py-4">No books in inventory yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>



            </div>
        </div>
    </div>
@endsection
