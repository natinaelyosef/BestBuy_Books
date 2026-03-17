<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Book Collection Tracker')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app-shell.css') }}">

    <style>
        :root {
            --sidebar-width: 270px;
            --topbar-height: 72px;
            --shell-brand: #1f7a8c;
            --shell-brand-strong: #16606d;
            --shell-accent: #f2a65a;
            --shell-bg: #f5f7fb;
            --shell-card: #ffffff;
            --shell-ink: #0f172a;
            --shell-muted: #6b7280;
            --shell-border: #e2e8f0;
            --shell-soft: #eef2f6;
            --shell-sidebar: #0f1b2a;
            --shell-sidebar-ink: #e2e8f0;
            --shell-sidebar-muted: rgba(226, 232, 240, 0.6);
            --shell-shadow: 0 14px 40px rgba(15, 23, 42, 0.08);
        }

        body.store-ui[data-bs-theme="dark"] {
            --shell-bg: #0b1220;
            --shell-card: #101826;
            --shell-ink: #e2e8f0;
            --shell-muted: #94a3b8;
            --shell-border: #1f2937;
            --shell-soft: #131c2e;
            --shell-sidebar: #0b1322;
            --shell-sidebar-ink: #e2e8f0;
            --shell-sidebar-muted: rgba(148, 163, 184, 0.7);
            --shell-shadow: 0 18px 40px rgba(0, 0, 0, 0.35);
        }

        .store-ui .theme-toggle .form-check-input {
            width: 2.2rem;
            height: 1.2rem;
            margin: 0;
            cursor: pointer;
            background-color: var(--shell-border);
            border-color: var(--shell-border);
        }

        .store-ui .theme-toggle .form-check-input:checked {
            background-color: var(--shell-brand);
            border-color: var(--shell-brand);
        }

        .store-ui .topbar-welcome {
            color: var(--shell-muted);
            font-weight: 600;
        }
    </style>

    @yield('extra_css')
</head>
<body class="store-ui has-topbar" data-bs-theme="light">
    <nav class="app-topbar">
        <div class="topbar-left">
            <button class="icon-btn d-lg-none" id="sidebarToggle" type="button" aria-label="Toggle sidebar">
                <i class="bi bi-list"></i>
            </button>

            <a class="topbar-brand" href="{{ route('store.dashboard') }}">
                <span class="brand-icon"><i class="bi bi-shop"></i></span>
                <span class="brand-text">My Book Store</span>
            </a>
        </div>

        <div class="topbar-right">
            <div class="topbar-pill d-none d-md-flex">
                <i class="bi bi-person-badge"></i>
                <span class="topbar-welcome">Welcome,</span>
                <span class="fw-semibold">{{ $accountOwner ?? (Auth::user()->name ?? Auth::user()->username) }}</span>
            </div>

            <div class="topbar-pill theme-toggle">
                <input class="form-check-input" type="checkbox" id="darkModeToggle">
                <label class="form-check-label" for="darkModeToggle" aria-label="Toggle dark mode">
                    <i class="bi bi-moon-stars"></i>
                </label>
            </div>

            <div class="dropdown">
                <button class="icon-btn position-relative" id="notificationDropdown" data-bs-toggle="dropdown" type="button" aria-expanded="false">
                    <i class="bi bi-bell"></i>
                    @if(!empty($pendingOrdersCount))
                        <span class="topbar-badge">{{ $pendingOrdersCount }}</span>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end p-3" style="width: 300px;">
                    <li><h6 class="dropdown-header">Quick Alerts</h6></li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-hourglass-split text-warning me-2"></i>
                            Pending Orders: {{ $pendingOrdersCount ?? 0 }}
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-heart text-danger me-2"></i>
                            Wishlist Items: {{ $wishlistCount ?? 0 }}
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('store.pdf-requests.index') }}">
                            <i class="bi bi-file-earmark-arrow-down text-primary me-2"></i>
                            PDF Requests: {{ $pdfRequests ?? 0 }}
                        </a>
                    </li>
                </ul>
            </div>

            <div class="dropdown">
                <a href="#" class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($currentDisplayName ?? ($accountOwner ?? Auth::user()->username ?? 'User')) }}&background=1f7a8c&color=fff&size=128" alt="User" class="topbar-avatar">
                    <span class="d-none d-md-inline">{{ $currentDisplayName ?? ($accountOwner ?? Auth::user()->username) }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person me-2"></i>Profile
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline w-100">
                            @csrf
                            <button type="submit" class="dropdown-item logout-btn">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @php
        $pendingOrders = $pendingOrdersCount ?? 0;
        $wishlistItems = $wishlistCount ?? 0;
        $unreadChats = $totalUnread ?? 0;
        $inventoryCount = $storeMetrics['total_books'] ?? 0;
        $pdfRequests = $pendingPdfRequestsCount ?? 0;

        $sidebarSections = [
            [
                'label' => 'Order Management',
                'items' => [
                    [
                        'label' => 'Dashboard',
                        'icon' => 'bi bi-speedometer2',
                        'route' => 'store.dashboard',
                        'active' => request()->routeIs('store.dashboard'),
                    ],
                    [
                        'label' => 'Order Management',
                        'icon' => 'bi bi-receipt',
                        'route' => 'store.orders',
                        'active' => request()->routeIs('store.orders'),
                        'badge' => $pendingOrders > 0 ? $pendingOrders : null,
                    ],
                    [
                        'label' => 'Wishlisted Books',
                        'icon' => 'bi bi-heart',
                        'route' => 'store.wishlist',
                        'active' => request()->routeIs('store.wishlist'),
                        'badge' => $wishlistItems > 0 ? $wishlistItems : null,
                    ],
                    [
                        'label' => 'PDF Requests',
                        'icon' => 'bi bi-file-earmark-arrow-down',
                        'route' => 'store.pdf-requests.index',
                        'active' => request()->routeIs('store.pdf-requests.*'),
                        'badge' => $pdfRequests > 0 ? $pdfRequests : null,
                    ],
                ],
            ],
            [
                'label' => 'Messages',
                'items' => [
                    [
                        'label' => 'Customer Chats',
                        'icon' => 'bi bi-chat-dots',
                        'route' => 'store.chat.index',
                        'active' => request()->routeIs('store.chat.*'),
                        'badge' => $unreadChats > 0 ? $unreadChats : null,
                    ],
                    [
                        'label' => 'My Reports',
                        'icon' => 'bi bi-headset',
                        'route' => 'store.issue-reports.index',
                        'active' => request()->routeIs('store.issue-reports.index'),
                    ],
                    [
                        'label' => 'Report Customer',
                        'icon' => 'bi bi-flag',
                        'route' => 'store.issue-reports.create',
                        'active' => request()->routeIs('store.issue-reports.create'),
                    ],
                ],
            ],
            [
                'label' => 'Store',
                'items' => [
                    [
                        'label' => 'Register Store',
                        'icon' => 'bi bi-cart',
                        'route' => 'store.registration.create',
                        'active' => request()->routeIs('store.registration.create'),
                    ],
                    [
                        'label' => 'View Stores',
                        'icon' => 'bi bi-list',
                        'route' => 'store.registration.view',
                        'active' => request()->routeIs('store.registration.view'),
                    ],
                ],
            ],
            [
                'label' => 'Books',
                'items' => [
                    [
                        'label' => 'Add Book',
                        'icon' => 'bi bi-plus-circle',
                        'route' => 'add.book.registration',
                        'active' => request()->routeIs('add.book.registration'),
                    ],
                    [
                        'label' => 'View Inventory',
                        'icon' => 'bi bi-archive',
                        'route' => 'view.inventory',
                        'active' => request()->routeIs('view.inventory'),
                        'badge' => $inventoryCount > 0 ? $inventoryCount : null,
                    ],
                    [
                        'label' => 'Manage Books',
                        'icon' => 'bi bi-pencil-square',
                        'route' => 'manage.books',
                        'active' => request()->routeIs('manage.books'),
                    ],
                ],
            ],
        ];
    @endphp

    <div class="app-shell">
        <x-app-sidebar id="sidebar" brand="My Book Store" brandIcon="bi bi-shop" :sections="$sidebarSections">
            <div class="sidebar-user">
                <div class="user-avatar">
                    {{ strtoupper(substr($currentDisplayName ?? ($accountOwner ?? Auth::user()->username ?? 'U'), 0, 1)) }}
                </div>
                <div class="user-meta">
                    <div class="user-name">{{ $currentDisplayName ?? ($accountOwner ?? Auth::user()->username) }}</div>
                    <div class="user-role">Store Owner</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="sidebar-logout">
                @csrf
                <button type="submit">
                    <i class="bi bi-box-arrow-right"></i>
                    Logout
                </button>
            </form>
        </x-app-sidebar>

        <main class="app-content main-content">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        const darkModeToggle = document.getElementById('darkModeToggle');
        const sidebar = document.getElementById('sidebar');
        const body = document.body;

        const isDarkMode = localStorage.getItem('darkMode') === 'true';
        if (isDarkMode && darkModeToggle) {
            body.setAttribute('data-bs-theme', 'dark');
            darkModeToggle.checked = true;
        }

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                body.classList.toggle('sidebar-open');
            });
        }

        document.addEventListener('click', (e) => {
            if (
                window.innerWidth <= 992 &&
                body.classList.contains('sidebar-open') &&
                sidebar &&
                !sidebar.contains(e.target) &&
                sidebarToggle &&
                !sidebarToggle.contains(e.target)
            ) {
                body.classList.remove('sidebar-open');
            }
        });

        if (darkModeToggle) {
            darkModeToggle.addEventListener('change', () => {
                if (darkModeToggle.checked) {
                    body.setAttribute('data-bs-theme', 'dark');
                    localStorage.setItem('darkMode', 'true');
                } else {
                    body.setAttribute('data-bs-theme', 'light');
                    localStorage.setItem('darkMode', 'false');
                }
            });
        }
    </script>
    @yield('extra_js')
</body>
</html>
