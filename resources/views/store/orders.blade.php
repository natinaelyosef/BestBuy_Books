@extends('store.registration-layout')

@section('title', 'Order Management')

@section('content')
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h1 class="h3 mb-0">Order Management</h1>
            <span class="text-muted">Review and manage store orders.</span>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted">No orders to display yet.</div>
            </div>
        </div>
    </div>
@endsection
