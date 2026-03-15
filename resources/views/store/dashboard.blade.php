<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookTracker | Store Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
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
            --sidebar-collapsed-width: 80px;
            --transition-speed: 0.3s;
            --book-color: #4361ee;
            --read-color: #1cc88a;
            --reading-color: #f6c23e;
            --toread-color: #e74a3b;
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

        /* Navbar Styling */
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
            font-size: 1.5rem;
            color: var(--book-color);
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

        /* Sidebar Styling */
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
            color: var(--book-color);
            background-color: #f8f9fc;
            border-left-color: var(--book-color);
        }

        .sidebar-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .sidebar-item span {
            transition: opacity var(--transition-speed);
        }

        /* Logout button in sidebar */
        .sidebar-item.logout-btn {
            margin-top: auto;
            border-top: 1px solid #e3e6f0;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            color: #3a3b45;
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: all 0.2s;
            white-space: nowrap;
            width: 100%;
            background: none;
            border: none;
            cursor: pointer;
            text-align: left;
        }

        .sidebar-item.logout-btn:hover {
            color: var(--book-color);
            background-color: #f8f9fc;
            border-left-color: var(--book-color);
        }

        /* Main Content Area */
        #content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            min-height: calc(100vh - 70px);
            transition: all var(--transition-speed) ease;
        }

        /* Cards */
        .dashboard-card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            transition: all 0.3s;
            overflow: hidden;
            height: 100%;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
        }

        .card-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }

        .border-left-book {
            border-left: 4px solid var(--book-color) !important;
        }

        .border-left-revenue {
            border-left: 4px solid var(--success-color) !important;
        }

        .border-left-rental {
            border-left: 4px solid var(--warning-color) !important;
        }

        .border-left-delivery {
            border-left: 4px solid var(--danger-color) !important;
        }

        /* Store Stats Cards */
        .store-stat-card {
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .store-stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.1);
        }

        .store-stat-card.revenue {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .store-stat-card.books {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .store-stat-card.rentals {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }

        .store-stat-card.deliveries {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }

        .store-stat-card.customers {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            color: #333;
        }

        .store-stat-card.owner {
            background: linear-gradient(135deg, #3a506b 0%, #1c2541 100%);
        }

        .store-stat-card.pending {
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
        }

        .store-stat-card.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .store-stat-card.completed {
            background: linear-gradient(135deg, #2af598 0%, #009efd 100%);
        }

        .store-stat-card.declined {
            background: linear-gradient(135deg, #ff758c 0%, #ff7eb3 100%);
        }

        .store-stat-card.today {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .store-stat-card.month {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }

        /* Recent Orders Styling */
        .order-card {
            border: 1px solid #e3e6f0;
            border-radius: 8px;
            margin-bottom: 15px;
            padding: 15px;
            transition: all 0.3s;
            background: white;
        }

        .order-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #e3e6f0;
        }

        .order-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        /* Inventory Alerts */
        .alert-card {
            border-left: 4px solid #f6c23e;
            background-color: #fff9e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .alert-card.danger {
            border-left-color: #e74a3b;
            background-color: #ffeaea;
        }

        /* Quick Actions Grid */
        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .quick-action-btn {
            padding: 20px;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            background: white;
            border: 2px solid #e3e6f0;
            transition: all 0.3s;
            cursor: pointer;
        }

        .quick-action-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border-color: var(--book-color);
        }

        /* Dark Mode Styles */
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

        body[data-bs-theme="dark"] .dashboard-card {
            background-color: #1a1d28;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(0, 0, 0, 0.3);
        }

        body[data-bs-theme="dark"] .sidebar-item {
            color: #adb5bd;
        }

        body[data-bs-theme="dark"] .sidebar-item:hover,
        body[data-bs-theme="dark"] .sidebar-item.active {
            background-color: #12151e;
            color: var(--book-color);
        }

        body[data-bs-theme="dark"] .sidebar-item.logout-btn {
            border-top-color: #2a2d38;
            color: #adb5bd;
        }

        body[data-bs-theme="dark"] .sidebar-item.logout-btn:hover {
            background-color: #12151e;
            color: var(--book-color);
        }

        body[data-bs-theme="dark"] .table {
            color: #adb5bd;
        }

        body[data-bs-theme="dark"] .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        /* Quick Action Buttons */
        .quick-action-btn {
            padding: 15px;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            height: 100%;
            transition: all 0.3s;
        }

        .quick-action-btn:hover {
            transform: translateY(-5px);
        }

        /* Status Badges */
        .badge-read {
            background-color: var(--read-color);
        }

        .badge-reading {
            background-color: var(--reading-color);
        }

        .badge-toread {
            background-color: var(--toread-color);
        }

        /* Activity Timeline */
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-marker {
            position: absolute;
            left: -30px;
            top: 0;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.8rem;
        }

        .timeline-content {
            padding: 10px 15px;
            background-color: rgba(78, 115, 223, 0.05);
            border-radius: 8px;
            border-left: 3px solid var(--book-color);
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-100%);
                width: 280px;
            }

            #content {
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

            .quick-actions-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Scrollbar Styling */
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

        body[data-bs-theme="dark"] #sidebar::-webkit-scrollbar-track {
            background: #2a2d38;
        }

        body[data-bs-theme="dark"] #sidebar::-webkit-scrollbar-thumb {
            background: #4a4d58;
        }

        body[data-bs-theme="dark"] #sidebar::-webkit-scrollbar-thumb:hover {
            background: #5a5d68;
        }

        /* Badge Notification */
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            padding: 0.25em 0.6em;
            font-size: 0.75rem;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.2);
            border-radius: 10px;
        }

        body[data-bs-theme="dark"] .dropdown-menu {
            background-color: #1a1d28;
        }

        body[data-bs-theme="dark"] .dropdown-item {
            color: #adb5bd;
        }

        body[data-bs-theme="dark"] .dropdown-item:hover {
            background-color: #12151e;
            color: var(--book-color);
        }

        /* Dropdown Logout Button Styling */
        .dropdown-item.logout-btn {
            padding: 0.5rem 1rem;
            color: #212529;
            text-decoration: none;
            display: flex;
            align-items: center;
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            cursor: pointer;
        }

        .dropdown-item.logout-btn:hover {
            background-color: #f8f9fa;
            color: var(--book-color);
        }

        body[data-bs-theme="dark"] .dropdown-item.logout-btn {
            color: #adb5bd;
        }

        body[data-bs-theme="dark"] .dropdown-item.logout-btn:hover {
            background-color: #12151e;
            color: var(--book-color);
        }

        /* Book Status Colors */
        .book-status-read {
            color: var(--read-color);
        }

        .book-status-reading {
            color: var(--reading-color);
        }

        .book-status-toread {
            color: var(--toread-color);
        }

        /* Header Navigation */
        .header-nav {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 10px 0;
            border-bottom: 1px solid #e3e6f0;
            margin-bottom: 20px;
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
        }

        .header-nav-item:hover {
            background: var(--book-color);
            color: white;
        }

        .header-nav-item.active {
            background: var(--book-color);
            color: white;
        }

        .header-nav-item .badge {
            font-size: 0.7em;
            padding: 2px 6px;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid px-4">
            <!-- Sidebar Toggle Button -->
            <button class="btn btn-link text-dark me-3 d-lg-none" id="sidebarToggle">
                <i class="bi bi-list" style="font-size: 1.5rem;"></i>
            </button>

            <!-- Brand -->
            <a class="navbar-brand" href="#">
                <i class="bi bi-shop me-2"></i>
                <span>🏪 My Book Store</span>
            </a>

            <!-- Navbar Items -->
            <div class="d-flex align-items-center ms-auto">
                <!-- Store Name Display -->
                <div class="me-4 d-none d-md-block">
                    <span class="text-muted">Welcome,</span>
                       <span class="fw-bold ms-1">{{ $accountOwner ?? (Auth::user()->name ?? Auth::user()->username) }}</span>
                </div>

                <!-- Dark Mode Toggle -->
                <div class="form-check form-switch me-4">
                    <input class="form-check-input" type="checkbox" id="darkModeToggle">
                    <label class="form-check-label" for="darkModeToggle">
                        <i class="bi bi-moon-fill"></i>
                    </label>
                </div>

                <!-- Notifications -->
                <div class="dropdown me-4">
                    <a href="#" class="text-dark position-relative" id="notificationDropdown" data-bs-toggle="dropdown">
                        <i class="bi bi-bell" style="font-size: 1.3rem;"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge">
                            3
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end p-3" style="width: 300px;">
                        <li>
                            <h6 class="dropdown-header">Book Alerts</h6>
                        </li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-exclamation-triangle text-warning me-2"></i>Low stock: The Great Gatsby</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-clock text-info me-2"></i>5 rentals due tomorrow</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-cart-check text-success me-2"></i>New order received</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-center" href="#">View all alerts</a></li>
                    </ul>
                </div>

               <!-- User Dropdown -->
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
            My Reports
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

  <form method="POST" action="{{ route('logout') }}" class="w-100">
    @csrf
    <button type="submit" class="sidebar-item logout-btn w-100">
        <i class="bi bi-box-arrow-right"></i>
        <span>Logout</span>
    </button>
</form>
    </div>

    <!-- Main Content -->
    <div id="content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            </div>

            <!-- Stats Row -->
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="store-stat-card owner h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-white-50 small text-uppercase fw-bold mb-1">Account Owner</div>
                                <div class="h5 mb-0 fw-bold text-white">
                                    John Doe
                                </div>
                            </div>
                            <i class="bi bi-person-badge card-icon text-white-50"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="store-stat-card pending h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-white-50 small text-uppercase fw-bold mb-1">Pending Approval</div>
                                <div class="h5 mb-0 fw-bold text-white">
                                    8
                                </div>
                            </div>
                            <i class="bi bi-hourglass-split card-icon text-white-50"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="store-stat-card active h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-white-50 small text-uppercase fw-bold mb-1">Active Orders</div>
                                <div class="h5 mb-0 fw-bold text-white">12</div>
                            </div>
                            <i class="bi bi-gear card-icon text-white-50"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="store-stat-card completed h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-white-50 small text-uppercase fw-bold mb-1">Completed</div>
                                <div class="h5 mb-0 fw-bold text-white">45</div>
                            </div>
                            <i class="bi bi-check-circle card-icon text-white-50"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="store-stat-card declined h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-white-50 small text-uppercase fw-bold mb-1">Declined</div>
                                <div class="h5 mb-0 fw-bold text-white">3</div>
                            </div>
                            <i class="bi bi-x-circle card-icon text-white-50"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="store-stat-card today h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-white-50 small text-uppercase fw-bold mb-1">Today's Revenue</div>
                                <div class="h5 mb-0 fw-bold text-white">
                                    $1,245.50
                                </div>
                            </div>
                            <i class="bi bi-cash-stack card-icon text-white-50"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="store-stat-card month h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-white-50 small text-uppercase fw-bold mb-1">Monthly Revenue</div>
                                <div class="h5 mb-0 fw-bold text-white">
                                    $15,780.25
                                </div>
                            </div>
                            <i class="bi bi-calendar-range card-icon text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 fw-bold text-primary">Recent Orders</h6>
                            <div class="dropdown no-arrow">
                                <a href="#" class="btn btn-sm btn-primary">View All</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Customer</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>#1001</td>
                                            <td>Alice Johnson</td>
                                            <td>Mar 10, 2026</td>
                                            <td>$89.99</td>
                                            <td>
                                                <span class="badge bg-warning">
                                                    Pending
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>#1002</td>
                                            <td>Bob Smith</td>
                                            <td>Mar 10, 2026</td>
                                            <td>$45.50</td>
                                            <td>
                                                <span class="badge bg-success">
                                                    Completed
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>#1003</td>
                                            <td>Carol Davis</td>
                                            <td>Mar 9, 2026</td>
                                            <td>$120.00</td>
                                            <td>
                                                <span class="badge bg-success">
                                                    Completed
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>#1004</td>
                                            <td>David Wilson</td>
                                            <td>Mar 9, 2026</td>
                                            <td>$34.95</td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    Processing
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>#1005</td>
                                            <td>Emma Brown</td>
                                            <td>Mar 8, 2026</td>
                                            <td>$67.30</td>
                                            <td>
                                                <span class="badge bg-success">
                                                    Completed
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // DOM Elements
        const sidebarToggle = document.getElementById('sidebarToggle');
        const darkModeToggle = document.getElementById('darkModeToggle');
        const sidebar = document.getElementById('sidebar');
        const body = document.body;

        // Check for saved dark mode preference
        const isDarkMode = localStorage.getItem('darkMode') === 'true';

        // Apply dark mode if previously enabled
        if (isDarkMode) {
            body.setAttribute('data-bs-theme', 'dark');
            darkModeToggle.checked = true;
        }

        // Toggle Sidebar on Mobile
        sidebarToggle.addEventListener('click', () => {
            body.classList.toggle('sidebar-open');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768 &&
                body.classList.contains('sidebar-open') &&
                !sidebar.contains(e.target) &&
                !sidebarToggle.contains(e.target)) {
                body.classList.remove('sidebar-open');
            }
        });

        // Toggle Dark Mode
        darkModeToggle.addEventListener('change', () => {
            if (darkModeToggle.checked) {
                body.setAttribute('data-bs-theme', 'dark');
                localStorage.setItem('darkMode', 'true');
            } else {
                body.setAttribute('data-bs-theme', 'light');
                localStorage.setItem('darkMode', 'false');
            }
        });

        // Sidebar Navigation
        document.querySelectorAll('.sidebar-item').forEach(item => {
            item.addEventListener('click', function(e) {
                // Don't trigger active state for logout button
                if (this.classList.contains('logout-btn')) return;
                
                document.querySelectorAll('.sidebar-item').forEach(el => {
                    el.classList.remove('active');
                });
                this.classList.add('active');

                if (window.innerWidth <= 768) {
                    body.classList.remove('sidebar-open');
                }
            });
        });

        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>
</html>
