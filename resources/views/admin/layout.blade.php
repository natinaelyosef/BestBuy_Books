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
    
    <style>
        :root {
            --bg: #f4f5fb;
            --card: #ffffff;
            --text: #1f2433;
            --muted: #667085;
            --primary: #5b4cff;
            --border: #e5e7f1;
            --radius: 14px;
            --shadow: 0 10px 30px rgba(31,36,51,0.08);
        }
        
        * { box-sizing: border-box; }
        
        body {
            margin: 0;
            font-family: "Segoe UI", system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
        }
        
        .admin-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 260px 1fr;
        }
        
        .sidebar {
            background: #0f1224;
            color: #fff;
            padding: 1.5rem 1.2rem;
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
            height: 100vh;
            position: sticky;
            top: 0;
        }
        
        .brand {
            font-weight: 800;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .brand i {
            color: #f5b042;
            font-size: 1.3rem;
        }
        
        .nav-links {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            flex: 1;
        }
        
        .nav-links a {
            text-decoration: none;
            color: #e4e7ff;
            padding: 0.65rem 0.8rem;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        
        .nav-links a.active,
        .nav-links a:hover {
            background: rgba(91,76,255,0.2);
            color: #fff;
        }
        
        .nav-links a i {
            width: 20px;
            color: #f5b042;
        }
        
        .sidebar .user-info {
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            font-size: 0.85rem;
        }
        
        .sidebar .user-name {
            color: #fff;
            font-weight: 600;
            margin-bottom: 0.3rem;
        }
        
        .sidebar .user-role {
            color: #f5b042;
            font-size: 0.75rem;
            margin-bottom: 0.8rem;
        }
        
        .sidebar .logout-form {
            margin-top: 0.5rem;
        }
        
        .sidebar .logout-form button {
            background: transparent;
            border: 1px solid rgba(255,255,255,0.2);
            color: #fff;
            padding: 0.5rem 0.8rem;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }
        
        .sidebar .logout-form button:hover {
            background: rgba(255,77,109,0.2);
            border-color: #ff4d6d;
        }
        
        .content {
            padding: 2rem;
            overflow-y: auto;
            max-height: 100vh;
        }
        
        .flash {
            background: #e7f7ef;
            border: 1px solid #bde5ce;
            color: #0f5132;
            padding: 0.7rem 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
        
        @media (max-width: 900px) {
            .admin-shell {
                grid-template-columns: 1fr;
            }
            .sidebar {
                display: none;
            }
        }
    </style>
    @yield('extra_css')
</head>
<body>
    <div class="admin-shell">
        <aside class="sidebar">
            <div class="brand">
                <i class="bi bi-shield-lock-fill"></i> 
                <span>BookHub Admin</span>
            </div>
            
            <nav class="nav-links">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="{{ route('admin.issue-reports.index') }}" class="{{ request()->routeIs('admin.issue-reports.*') ? 'active' : '' }}">
                    <i class="bi bi-flag-fill"></i> Issue Reports
                </a>
                <a href="{{ route('admin.chats.index') }}" class="{{ request()->routeIs('admin.chats.*') ? 'active' : '' }}">
                    <i class="bi bi-chat-dots-fill"></i> Support Chats
                </a>
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Site Users
                </a>
                <a href="{{ route('admin.admins.index') }}" class="{{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
                    <i class="bi bi-person-fill-gear"></i> Admin Users
                </a>
                @if(auth()->user()?->account_type === 'super_admin')
                <a href="{{ route('admin.admins.create') }}" class="{{ request()->routeIs('admin.admins.create') ? 'active' : '' }}">
                    <i class="bi bi-person-plus-fill"></i> Create Admin
                </a>
                @endif
            </nav>
            
            <div class="user-info">
                <div class="user-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div class="user-role">{{ ucfirst(str_replace('_', ' ', auth()->user()->account_type ?? 'user')) }}</div>
                
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </aside>
        
        <main class="content">
            @if (session('status'))
                <div class="flash">{{ session('status') }}</div>
            @endif
            
            @yield('content')
        </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('extra_js')
</body>
</html>