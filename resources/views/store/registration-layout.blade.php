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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #4e73df;
            --primary-dark: #3a56c4;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --light-color: #f8f9fc;
            --dark-color: #5a5c69;
            --sidebar-width: 250px;
            --transition-speed: 0.3s;
            --brand-color: #4361ee;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fc;
            color: #333;
            overflow-x: hidden;
            min-height: 100vh;
            padding-top: 70px;
        }

        .navbar {
            background-color: white;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            padding: 0.75rem 0;
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1030;
            transition: all var(--transition-speed) ease;
            height: 70px;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.3rem;
            color: var(--brand-color);
            display: flex;
            align-items: center;
        }

        .nav-user-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e3e6f0;
        }

        #sidebar {
            width: var(--sidebar-width);
            background-color: white;
            color: #858796;
            position: fixed;
            top: 70px;
            left: 0;
            height: calc(100vh - 70px);
            z-index: 1000;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            transition: all var(--transition-speed) ease;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .sidebar-heading {
            padding: 1.5rem 1rem 0.5rem;
            font-size: 0.8rem;
            text-transform: uppercase;
            color: #b7b9cc;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .sidebar-item {
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            color: #3a3b45;
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .sidebar-item:hover,
        .sidebar-item.active {
            color: var(--brand-color);
            background-color: #f8f9fc;
            border-left-color: var(--brand-color);
        }

        .sidebar-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .sidebar-item.logout-bottom {
            margin-top: auto;
            border-top: 1px solid #e3e6f0;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            min-height: calc(100vh - 70px);
            transition: all var(--transition-speed) ease;
        }

        .header-nav-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 20px;
            background: #f8f9fc;
            color: #5a5c69;
            text-decoration: none;
            transition: all 0.3s;
            font-weight: 500;
            margin-right: 10px;
        }

        .header-nav-item:hover,
        .header-nav-item.active {
            background: var(--brand-color);
            color: white;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            padding: 0.25em 0.6em;
            font-size: 0.75rem;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.2);
            border-radius: 10px;
        }

        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-100%);
                width: 280px;
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-open #sidebar {
                transform: translateX(0);
            }

            .sidebar-open::after {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }
        }

        body[data-bs-theme="dark"] {
            background-color: #12151e;
            color: #adb5bd;
        }

        body[data-bs-theme="dark"] .navbar {
            background-color: #1a1d28;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(0, 0, 0, 0.3);
        }

        body[data-bs-theme="dark"] #sidebar {
            background-color: #1a1d28;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(0, 0, 0, 0.3);
        }

        body[data-bs-theme="dark"] .sidebar-item {
            color: #adb5bd;
        }

        body[data-bs-theme="dark"] .sidebar-item:hover,
        body[data-bs-theme="dark"] .sidebar-item.active {
            background-color: #12151e;
            color: var(--brand-color);
        }

        body[data-bs-theme="dark"] .sidebar-item.logout-bottom {
            border-top-color: #2a2d38;
        }

        body[data-bs-theme="dark"] .dropdown-menu {
            background-color: #1a1d28;
        }

        body[data-bs-theme="dark"] .dropdown-item {
            color: #adb5bd;
        }

        body[data-bs-theme="dark"] .dropdown-item:hover {
            background-color: #12151e;
            color: var(--brand-color);
        }

        #sidebar::-webkit-scrollbar {
            width: 5px;
        }

        #sidebar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        #sidebar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        #sidebar::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>

    @yield('extra_css')
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid px-4">
            <button class="btn btn-link text-dark me-3 d-lg-none" id="sidebarToggle">
                <i class="bi bi-list" style="font-size: 1.5rem;"></i>
            </button>

            <a class="navbar-brand" href="{{ route('store.dashboard') }}">
                <i class="bi bi-shop me-2"></i>
                <span>My Book Store</span>
            </a>

            <div class="d-flex align-items-center ms-auto">
                <div class="me-4 d-none d-md-block">
                    <span class="text-muted">Welcome,</span>
                    <span class="fw-bold ms-1">{{ $accountOwner ?? (Auth::user()->name ?? Auth::user()->username) }}</span>
                </div>

                <div class="form-check form-switch me-4">
                    <input class="form-check-input" type="checkbox" id="darkModeToggle">
                    <label class="form-check-label" for="darkModeToggle">
                        <i class="bi bi-moon-fill"></i>
                    </label>
                </div>

                <div class="dropdown me-4">
                    <a href="#" class="text-dark position-relative" id="notificationDropdown" data-bs-toggle="dropdown">
                        <i class="bi bi-bell" style="font-size: 1.3rem;"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge">
                            {{ $pendingOrdersCount ?? 0 }}
                        </span>
                    </a>
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
                    </ul>
                </div>

                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown">
                        <img src="https://ui-avatars.com/api/?name=John+Doe&background=4361ee&color=fff&size=128" alt="User" class="nav-user-img me-2">
                        <span class="d-none d-md-inline">{{ $currentDisplayName ?? ($accountOwner ?? Auth::user()->username) }}</span>
                    </a>
                     <ul class="dropdown-menu dropdown-menu-end">
        <!-- ✅ FIXED: profile.edit instead of profile -->
        <li>
            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                <i class="bi bi-person me-2"></i>Profile
            </a>
        </li>
        <li><hr class="dropdown-divider"></li>
        <!-- ✅ Logout is correct - route name 'logout' exists in auth.php -->
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
        </div>
    </nav>

    <div id="sidebar">
        <div class="sidebar-heading">Order Management</div>
        <a href="{{ route('store.dashboard') }}" class="sidebar-item @if(request()->routeIs('store.dashboard')) active @endif">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>
        <a href="#" class="sidebar-item @if(request()->routeIs('store.orders')) active @endif">
            <i class="bi bi-receipt"></i>
            <span>Order Management</span>
            @if(!empty($pendingOrdersCount))
            <span class="badge bg-danger ms-auto">{{ $pendingOrdersCount }}</span>
            @endif
        </a>
        <a href="#" class="sidebar-item @if(request()->routeIs('store.wishlist')) active @endif">
            <i class="bi bi-heart"></i>
            <span>Wishlisted Books</span>
            <span class="badge bg-warning ms-auto">{{ $wishlistCount ?? 0 }}</span>
        </a>

        <div class="sidebar-heading">Messages Management</div>
        <a class="sidebar-item @if(request()->routeIs('store.chat.*')) active @endif" 
           href="{{ route('store.chat.index') }}">
            <i class="bi bi-chat-dots me-2"></i>
            Customer Chats
            @if(!empty($totalUnread))
            <span class="badge bg-danger ms-auto">{{ $totalUnread }}</span>
            @endif
        </a>
        <a class="sidebar-item @if(request()->routeIs('store.issue-reports.*')) active @endif" href="{{ route('store.issue-reports.index') }}">
            <i class="bi bi-headset me-2"></i>
            Support Chats
        </a>
       <a class="sidebar-item @if(request()->routeIs('store.issue-reports.create')) active @endif" href="{{ route('store.issue-reports.create') }}">
            <i class="bi bi-flag me-2"></i>
            Report Customer
        </a>

        <div class="sidebar-heading">Store Management</div>
        <a href="{{ route('store.registration.create') }}" class="sidebar-item @if(request()->routeIs('store.registration.create')) active @endif">
            <i class="bi bi-cart"></i> 
            <span>Register Store</span>
        </a>
        <a href="{{ route('store.registration.view') }}" class="sidebar-item @if(request()->routeIs('store.registration.view')) active @endif">
            <i class="bi bi-list"></i> 
            <span>View Stores</span>
        </a>

        <div class="sidebar-heading">Book Management</div>
        <a href="{{ route('add.book.registration') }}" class="sidebar-item @if(request()->routeIs('add.book.registration')) active @endif">
            <i class="bi bi-plus-circle"></i>
            <span>Add Book</span>
        </a>
        <a href="{{ route('view.inventory') }}" class="sidebar-item @if(request()->routeIs('view.inventory')) active @endif">
            <i class="bi bi-archive"></i>
            <span>View Inventory</span>
            <span class="badge bg-success ms-auto">{{ $storeMetrics['total_books'] ?? 0 }}</span>
        </a>
        <a href="{{ route('manage.books') }}" class="sidebar-item @if(request()->routeIs('manage.books')) active @endif">
            <i class="bi bi-pencil-square"></i>
            <span>Manage Books</span>
        </a>

        <form method="POST" action="{{ route('logout') }}" class="sidebar-item logout-bottom">
            @csrf
            <button type="submit" class="btn btn-link p-0 text-decoration-none d-flex align-items-center w-100" style="color: inherit; border: none; background: none;">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>

    <div class="main-content">
        @yield('content')
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
                window.innerWidth <= 768 &&
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