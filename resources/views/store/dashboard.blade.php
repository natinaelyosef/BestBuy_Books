@extends('store.registration-layout')

@section('title', 'Store Dashboard')

@section('extra_css')
<style>
    .stat-card {
        border-radius: var(--shell-radius);
        border: 1px solid var(--shell-border);
        background: var(--shell-card);
        box-shadow: var(--shell-shadow);
        padding: 1.2rem;
        height: 100%;
    }

    .stat-card .stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.9rem;
    }

    .stat-card .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(31, 122, 140, 0.12);
        color: var(--shell-brand);
        font-size: 1.2rem;
    }

    .stat-card .stat-label {
        text-transform: uppercase;
        letter-spacing: 0.12em;
        font-size: 0.68rem;
        color: var(--shell-muted);
        font-weight: 700;
    }

    .stat-card .stat-value {
        font-size: 1.7rem;
        font-weight: 800;
        color: var(--shell-ink);
        margin-top: 0.35rem;
    }

    .stat-card .stat-foot {
        font-size: 0.82rem;
        color: var(--shell-muted);
    }

    .quick-actions .btn {
        border-radius: 12px;
        font-weight: 700;
        padding: 0.65rem 1rem;
    }

    .insight-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        gap: 0.75rem;
    }

    .insight-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        background: var(--shell-soft);
        color: var(--shell-ink);
        font-weight: 600;
    }

    .insight-item span {
        color: var(--shell-muted);
        font-weight: 600;
    }

    .table thead th {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--shell-muted);
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Dashboard</h1>
            <p class="text-muted mb-0">A quick look at your store activity and customer engagement.</p>
        </div>
        <div class="quick-actions d-flex gap-2">
            <a href="{{ route('add.book.registration') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add Book
            </a>
            <a href="{{ route('store.chat.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-chat-dots me-1"></i> Customer Chats
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Total Books</div>
                        <div class="stat-value">{{ $storeMetrics['total_books'] ?? 0 }}</div>
                    </div>
                    <div class="stat-icon"><i class="bi bi-book"></i></div>
                </div>
                <div class="stat-foot">Inventory across all listings</div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Pending Orders</div>
                        <div class="stat-value">{{ $pendingOrdersCount ?? 0 }}</div>
                    </div>
                    <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
                </div>
                <div class="stat-foot">Orders awaiting action</div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Wishlist Items</div>
                        <div class="stat-value">{{ $wishlistCount ?? 0 }}</div>
                    </div>
                    <div class="stat-icon"><i class="bi bi-heart"></i></div>
                </div>
                <div class="stat-foot">Books customers saved</div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Unread Chats</div>
                        <div class="stat-value">{{ $totalUnread ?? 0 }}</div>
                    </div>
                    <div class="stat-icon"><i class="bi bi-chat-text"></i></div>
                </div>
                <div class="stat-foot">Customer messages waiting</div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-xl-7">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Recent Orders</h5>
                    <span class="text-muted small">Last 7 days</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#BH-1043</td>
                                    <td>Chris M.</td>
                                    <td><span class="badge bg-warning-subtle text-warning">Processing</span></td>
                                    <td class="text-end">$89.99</td>
                                </tr>
                                <tr>
                                    <td>#BH-1042</td>
                                    <td>Jane L.</td>
                                    <td><span class="badge bg-success-subtle text-success">Completed</span></td>
                                    <td class="text-end">$45.50</td>
                                </tr>
                                <tr>
                                    <td>#BH-1041</td>
                                    <td>Michael P.</td>
                                    <td><span class="badge bg-info-subtle text-info">Shipped</span></td>
                                    <td class="text-end">$120.00</td>
                                </tr>
                                <tr>
                                    <td>#BH-1040</td>
                                    <td>Nina R.</td>
                                    <td><span class="badge bg-danger-subtle text-danger">Cancelled</span></td>
                                    <td class="text-end">$34.95</td>
                                </tr>
                                <tr>
                                    <td>#BH-1039</td>
                                    <td>Samuel K.</td>
                                    <td><span class="badge bg-success-subtle text-success">Completed</span></td>
                                    <td class="text-end">$67.30</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-5">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Store Insights</h5>
                </div>
                <div class="card-body">
                    <ul class="insight-list">
                        <li class="insight-item">
                            Average order value
                            <span>$64.30</span>
                        </li>
                        <li class="insight-item">
                            New followers this week
                            <span>+28</span>
                        </li>
                        <li class="insight-item">
                            Chat response time
                            <span>12 min</span>
                        </li>
                        <li class="insight-item">
                            Books low in stock
                            <span>{{ $storeMetrics['low_stock'] ?? 5 }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Next Best Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('view.inventory') }}" class="btn btn-outline-secondary">
                            Review inventory alerts
                        </a>
                        <a href="{{ route('store.issue-reports.index') }}" class="btn btn-outline-secondary">
                            Check customer reports
                        </a>
                        <a href="{{ route('store.chat.index') }}" class="btn btn-outline-secondary">
                            Reply to chats
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
