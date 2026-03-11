<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - BookHub</title>
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
            font-family: "Segoe UI", system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
        }
        .admin-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 240px 1fr;
        }
        .sidebar {
            background: #0f1224;
            color: #fff;
            padding: 1.5rem 1.2rem;
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
        }
        .brand {
            font-weight: 800;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .nav-links {
            display: grid;
            gap: 0.5rem;
        }
        .nav-links a {
            text-decoration: none;
            color: #e4e7ff;
            padding: 0.55rem 0.8rem;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 0.9rem;
        }
        .nav-links a.active,
        .nav-links a:hover {
            background: rgba(91,76,255,0.2);
            color: #fff;
        }
        .sidebar .user {
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            font-size: 0.85rem;
            color: #cbd0ff;
        }
        .sidebar form button {
            margin-top: 0.6rem;
            background: transparent;
            border: 1px solid rgba(255,255,255,0.2);
            color: #fff;
            padding: 0.4rem 0.7rem;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
        }
        .content {
            padding: 2rem;
        }
        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 1.4rem;
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
                flex-direction: row;
                flex-wrap: wrap;
            }
            .nav-links {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
    </style>
    @yield('extra_css')
</head>
<body>
<div class="admin-shell">
    <aside class="sidebar">
        <div class="brand"><i class="bi bi-shield-lock-fill"></i> BookHub Admin</div>
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
            <a href="{{ route('admin.admins.index') }}" class="{{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> Admin Users
            </a>
        </nav>

        <div class="user">
            Signed in as {{ auth()->user()->name ?? 'Admin' }}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"><i class="bi bi-box-arrow-right"></i> Logout</button>
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
</body>
</html>
