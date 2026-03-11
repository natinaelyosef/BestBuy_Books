@extends('store.registration-layout')

@section('title', 'Manage Books')

@section('content')
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h1 class="h3 mb-0">Manage Books</h1>
            <span class="text-muted">Edit or remove books from your catalog.</span>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted">No books to manage yet.</div>
            </div>
        </div>
    </div>
@endsection
