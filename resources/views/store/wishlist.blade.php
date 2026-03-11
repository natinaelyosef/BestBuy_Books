@extends('store.registration-layout')

@section('title', 'Wishlisted Books')

@section('content')
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h1 class="h3 mb-0">Wishlisted Books</h1>
            <span class="text-muted">Track books customers have wishlisted.</span>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted">No wishlist items to display yet.</div>
            </div>
        </div>
    </div>
@endsection
