<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - BookHub</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app-shell.css') }}">
    
    <style>
        :root {
            --sidebar-width: 260px;
            --topbar-height: 64px;
            --shell-brand: #1f7a8c;
            --shell-brand-strong: #16606d;
            --shell-accent: #f2a65a;
            --shell-bg: #f4f6fb;
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

        .flash {
            background: rgba(31, 122, 140, 0.12);
            border: 1px solid rgba(31, 122, 140, 0.2);
            color: #0f3c45;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            font-weight: 600;
            margin-bottom: 1rem;
        }
    </style>
    @yield('extra_css')
</head>
<body class="admin-ui has-topbar">
    <header class="app-topbar">
        <div class="topbar-left">
            <button class="icon-btn d-lg-none" id="sidebarToggle" type="button" aria-label="Toggle sidebar">
                <i class="bi bi-list"></i>
            </button>
            <a class="topbar-brand" href="{{ route('admin.dashboard') }}">
                <span class="brand-icon"><i class="bi bi-shield-lock-fill"></i></span>
                <span class="brand-text">BookHub Admin</span>
            </a>
        </div>
        <div class="topbar-right">
            <span class="topbar-chip">
                <i class="bi bi-stars"></i>
                {{ ucfirst(str_replace('_', ' ', auth()->user()->account_type ?? 'admin')) }}
            </span>
            <div class="d-none d-md-flex align-items-center gap-2">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=1f7a8c&color=fff&size=128" class="topbar-avatar" alt="Admin">
                <span class="fw-semibold">{{ auth()->user()->name ?? 'Admin' }}</span>
            </div>
        </div>
    </header>

    @php
        $adminSections = [
            [
                'label' => 'Overview',
                'items' => [
                    [
                        'label' => 'Dashboard',
                        'icon' => 'bi bi-speedometer2',
                        'route' => 'admin.dashboard',
                        'active' => request()->routeIs('admin.dashboard'),
                    ],
                ],
            ],
            [
                'label' => 'Support',
                'items' => [
                    [
                        'label' => 'Issue Reports',
                        'icon' => 'bi bi-flag-fill',
                        'route' => 'admin.issue-reports.index',
                        'active' => request()->routeIs('admin.issue-reports.*'),
                    ],
                    [
                        'label' => 'Support Chats',
                        'icon' => 'bi bi-chat-dots-fill',
                        'route' => 'admin.chats.index',
                        'active' => request()->routeIs('admin.chats.*'),
                    ],
                ],
            ],
            [
                'label' => 'Management',
                'items' => array_values(array_filter([
                    [
                        'label' => 'Site Users',
                        'icon' => 'bi bi-people',
                        'route' => 'admin.users.index',
                        'active' => request()->routeIs('admin.users.*'),
                    ],
                    [
                        'label' => 'Admin Users',
                        'icon' => 'bi bi-person-fill-gear',
                        'route' => 'admin.admins.index',
                        'active' => request()->routeIs('admin.admins.*'),
                    ],
                    auth()->user()?->account_type === 'super_admin' ? [
                        'label' => 'Create Admin',
                        'icon' => 'bi bi-person-plus-fill',
                        'route' => 'admin.admins.create',
                        'active' => request()->routeIs('admin.admins.create'),
                    ] : null,
                ])),
            ],
        ];
    @endphp

    <div class="app-shell">
        <x-app-sidebar id="sidebar" brand="BookHub Admin" brandIcon="bi bi-shield-lock-fill" :sections="$adminSections">
            <div class="sidebar-user">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="user-meta">
                    <div class="user-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                    <div class="user-role">{{ ucfirst(str_replace('_', ' ', auth()->user()->account_type ?? 'admin')) }}</div>
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

        <main class="app-content">
            @if (session('status'))
                <div class="flash">{{ session('status') }}</div>
            @endif

            @yield('content')
        </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        const body = document.body;

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                body.classList.toggle('sidebar-open');
            });
        }

        document.addEventListener('click', (e) => {
            if (
                window.innerWidth <= 992 &&
                body.classList.contains('sidebar-open') &&
                !document.getElementById('sidebar')?.contains(e.target) &&
                !sidebarToggle?.contains(e.target)
            ) {
                body.classList.remove('sidebar-open');
            }
        });
    </script>
    @yield('extra_js')
</body>
</html>
