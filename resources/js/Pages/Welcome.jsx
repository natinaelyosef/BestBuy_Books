import { Head, Link } from '@inertiajs/react';

const css = `
    :root {
        --primary:        #5b4cff;
        --primary-dark:   #3d2fe0;
        --primary-light:  #9b8fff;
        --primary-soft:   rgba(91,76,255,0.10);
        --primary-glow:   rgba(91,76,255,0.28);
        --accent:         #f5b042;
        --accent-soft:    rgba(245,176,66,0.12);
        --success:        #00c98b;
        --success-soft:   rgba(0,201,139,0.10);
        --danger:         #ff4d6d;
        --rent-color:     #5b4cff;
        --buy-color:      #00c98b;
        --bg-base:        #edecf8;
        --bg-surface:     #f2f1fc;
        --bg-raised:      #e8e7f4;
        --bg-card:        #faf9ff;
        --text-primary:   #14102b;
        --text-secondary: #4e4a64;
        --text-muted:     #8a86a0;
        --border:         rgba(91,76,255,0.13);
        --border-soft:    rgba(91,76,255,0.07);
        --shadow-sm:  0 2px 8px rgba(20,16,43,0.06);
        --shadow:     0 4px 18px rgba(20,16,43,0.10);
        --shadow-lg:  0 10px 36px rgba(20,16,43,0.13);
        --shadow-xl:  0 20px 60px rgba(20,16,43,0.18);
        --radius-sm: 10px; --radius: 14px; --radius-lg: 20px; --radius-xl: 28px;
        --t-fast: 140ms cubic-bezier(0.4,0,0.2,1);
        --t:      260ms cubic-bezier(0.4,0,0.2,1);
        --header-h: 68px;
    }
    [data-theme="dark"] {
        --primary: #7b6fff; --primary-dark: #5b4cff; --primary-light: #b3abff;
        --primary-soft: rgba(123,111,255,0.13); --primary-glow: rgba(123,111,255,0.25);
        --accent: #ffc155;
        --bg-base: #0c0a1a; --bg-surface: #12101e; --bg-raised: #1a1730; --bg-card: #161328;
        --text-primary: #ede9ff; --text-secondary: #9c97b8; --text-muted: #635e7a;
        --border: rgba(123,111,255,0.16); --border-soft: rgba(123,111,255,0.08);
        --shadow-xl: 0 20px 60px rgba(0,0,0,0.60);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }

    body {
        font-family: 'Outfit', system-ui, sans-serif;
        background: var(--bg-base);
        color: var(--text-primary);
        min-height: 100vh;
        -webkit-font-smoothing: antialiased;
        transition: background-color var(--t), color var(--t);
    }

    body::before {
        content: '';
        position: fixed; inset: 0; z-index: 0; pointer-events: none;
        background:
            radial-gradient(ellipse 65% 50% at 5% 0%,   rgba(91,76,255,0.12) 0%, transparent 60%),
            radial-gradient(ellipse 45% 40% at 95% 90%, rgba(155,143,255,0.09) 0%, transparent 55%),
            radial-gradient(ellipse 30% 35% at 52% 50%, rgba(245,176,66,0.04) 0%, transparent 65%);
    }
    [data-theme="dark"] body::before {
        background:
            radial-gradient(ellipse 60% 50% at 5% 0%,   rgba(123,111,255,0.18) 0%, transparent 60%),
            radial-gradient(ellipse 40% 40% at 95% 92%, rgba(0,201,139,0.06)   0%, transparent 55%);
    }

    header {
        position: sticky; top: 0; z-index: 200;
        height: var(--header-h);
        background: rgba(240,239,254,0.78);
        backdrop-filter: blur(18px) saturate(160%);
        -webkit-backdrop-filter: blur(18px) saturate(160%);
        border-bottom: 1px solid var(--border);
        transition: background var(--t), border-color var(--t);
    }
    [data-theme="dark"] header { background: rgba(18,16,30,0.82); }

    .header-inner {
        max-width: 1340px; margin: 0 auto;
        padding: 0 1.5rem;
        height: 100%;
        display: flex; align-items: center; justify-content: space-between; gap: 1rem;
    }

    .logo {
        display: flex; align-items: center; gap: 0.6rem;
        text-decoration: none; cursor: pointer; flex-shrink: 0;
    }
    .logo-icon {
        width: 38px; height: 38px; border-radius: 11px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; color: #fff;
        box-shadow: 0 4px 14px var(--primary-glow);
    }
    .logo-text { font-size: 1.05rem; font-weight: 800; color: var(--text-primary); letter-spacing: -0.02em; }
    .logo-text span { color: var(--primary); }

    .header-search {
        flex: 1; max-width: 420px;
        display: flex; align-items: center;
        background: var(--bg-raised); border: 1.5px solid var(--border);
        border-radius: 999px; overflow: hidden;
        transition: all 200ms;
    }
    .header-search:focus-within {
        border-color: var(--primary); background: var(--bg-surface);
        box-shadow: 0 0 0 4px var(--primary-soft);
    }
    .header-search i { padding: 0 0.8rem 0 1.1rem; color: var(--text-muted); font-size: 0.95rem; flex-shrink: 0; }
    .header-search input {
        flex: 1; border: none; background: transparent; outline: none;
        font-family: 'Outfit', sans-serif; font-size: 0.87rem; color: var(--text-primary);
        padding: 0.6rem 0;
    }
    .header-search input::placeholder { color: var(--text-muted); }
    .search-btn {
        background: var(--primary); color: #fff; border: none;
        padding: 0.52rem 1.1rem; font-family: 'Outfit', sans-serif;
        font-size: 0.82rem; font-weight: 700; cursor: pointer;
        border-radius: 0 999px 999px 0;
        transition: background var(--t-fast);
    }
    .search-btn:hover { background: var(--primary-dark); }

    .nav-right { display: flex; align-items: center; gap: 0.4rem; flex-shrink: 0; }
    .nav-btn {
        display: flex; align-items: center; gap: 0.4rem;
        padding: 0.42rem 0.85rem; border-radius: 999px;
        border: 1.5px solid transparent; background: transparent;
        font-family: 'Outfit', sans-serif; font-size: 0.82rem; font-weight: 600;
        color: var(--text-secondary); cursor: pointer;
        transition: all var(--t-fast); text-decoration: none;
    }
    .nav-btn:hover { color: var(--primary); background: var(--primary-soft); border-color: var(--border); }
    .nav-btn.active { color: var(--primary); }
    .nav-btn i { font-size: 0.95rem; }

    .nav-pill {
        padding: 0.5rem 1.1rem; border-radius: 999px;
        background: var(--primary); color: #fff; border: none;
        font-family: 'Outfit', sans-serif; font-size: 0.82rem; font-weight: 700;
        cursor: pointer; transition: all var(--t-fast); text-decoration: none;
        display: flex; align-items: center; gap: 0.35rem;
        box-shadow: 0 4px 14px var(--primary-glow);
    }
    .nav-pill:hover { background: var(--primary-dark); transform: translateY(-1px); }
    .nav-pill-outline {
        background: transparent; color: var(--primary);
        border: 1.5px solid var(--primary);
        box-shadow: none;
    }
    .nav-pill-outline:hover { background: var(--primary-soft); transform: none; }

    .theme-btn {
        width: 36px; height: 36px; border-radius: 10px;
        background: var(--bg-raised); border: 1.5px solid var(--border);
        display: flex; align-items: center; justify-content: center;
        color: var(--text-muted); font-size: 0.9rem;
        cursor: pointer; transition: all var(--t-fast);
    }
    .theme-btn:hover { color: var(--primary); border-color: var(--primary); background: var(--primary-soft); }

    .cart-btn { position: relative; }
    .cart-badge {
        position: absolute; top: -4px; right: -4px;
        width: 18px; height: 18px; border-radius: 50%;
        background: var(--danger); color: #fff;
        font-size: 0.62rem; font-weight: 800;
        display: flex; align-items: center; justify-content: center;
        border: 2px solid var(--bg-base);
    }

    .mob-menu-btn {
        display: none;
        width: 36px; height: 36px; border-radius: 10px;
        background: var(--bg-raised); border: 1.5px solid var(--border);
        align-items: center; justify-content: center;
        color: var(--text-secondary); font-size: 1.1rem; cursor: pointer;
    }

    .hero {
        position: relative; overflow: hidden;
        background: linear-gradient(155deg, #0f1a3a 0%, #1a1050 48%, #0d1030 100%);
        padding: 5.5rem 1.5rem 4.5rem;
        text-align: center;
        z-index: 1;
    }
    .hero::before {
        content: ''; position: absolute; inset: 0; pointer-events: none;
        background:
            radial-gradient(ellipse 80% 70% at 20% -10%, rgba(91,76,255,0.55) 0%, transparent 55%),
            radial-gradient(ellipse 55% 55% at 90% 110%, rgba(245,176,66,0.22) 0%, transparent 55%),
            radial-gradient(ellipse 40% 40% at 50% 120%, rgba(0,201,139,0.12) 0%, transparent 55%);
    }
    .hero-ring {
        position: absolute; border-radius: 50%; border: 1px solid rgba(255,255,255,0.05);
        pointer-events: none;
    }
    .hero-ring-1 { width: 500px; height: 500px; top: -200px; right: -150px; }
    .hero-ring-2 { width: 320px; height: 320px; bottom: -120px; left: -80px; border-color: rgba(245,176,66,0.07); }
    .hero-ring-3 { width: 180px; height: 180px; top: 30%; left: 8%; border-color: rgba(91,76,255,0.2); }

    .hero-inner { position: relative; z-index: 1; max-width: 760px; margin: 0 auto; }
    .hero-eyebrow {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.32rem 0.95rem; border-radius: 999px;
        background: rgba(255,255,255,0.10); border: 1px solid rgba(255,255,255,0.18);
        font-size: 0.75rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 1.1px; color: var(--accent);
        margin-bottom: 1.4rem; backdrop-filter: blur(6px);
    }
    .live-dot {
        width: 6px; height: 6px; border-radius: 50%; background: var(--success);
        box-shadow: 0 0 0 3px rgba(0,201,139,0.22);
        animation: blink 2s ease-in-out infinite;
    }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }

    .hero h1 {
        font-size: clamp(2.2rem, 5vw, 3.6rem); font-weight: 900;
        color: #fff; line-height: 1.08; letter-spacing: -0.035em;
        margin-bottom: 1.2rem;
    }
    .hero h1 em { font-style: normal; color: var(--accent); }
    .hero p {
        font-size: 1.05rem; color: rgba(255,255,255,0.58);
        max-width: 560px; margin: 0 auto 2.5rem; line-height: 1.7;
    }

    .hero-search {
        display: flex; max-width: 580px; margin: 0 auto 2rem;
        background: rgba(255,255,255,0.10); backdrop-filter: blur(12px);
        border: 1.5px solid rgba(255,255,255,0.18);
        border-radius: 999px; overflow: hidden;
        box-shadow: 0 12px 40px rgba(0,0,0,0.22);
    }
    .hero-search i {
        padding: 0 0.8rem 0 1.5rem; color: rgba(255,255,255,0.5); font-size: 1.05rem;
        display: flex; align-items: center; flex-shrink: 0;
    }
    .hero-search input {
        flex: 1; border: none; background: transparent; outline: none;
        font-family: 'Outfit', sans-serif; font-size: 0.95rem; color: #fff;
        padding: 1.05rem 0;
    }
    .hero-search input::placeholder { color: rgba(255,255,255,0.38); }
    .hero-search-btn {
        background: var(--primary); color: #fff; border: none;
        padding: 0 1.8rem; font-family: 'Outfit', sans-serif;
        font-size: 0.9rem; font-weight: 700; cursor: pointer;
        border-radius: 0 999px 999px 0;
        display: flex; align-items: center; gap: 0.45rem;
        transition: background var(--t-fast);
    }
    .hero-search-btn:hover { background: var(--primary-dark); }

    .hero-stats {
        display: flex; align-items: center; justify-content: center; gap: 2rem;
        flex-wrap: wrap;
    }
    .hero-stat {
        display: flex; align-items: center; gap: 0.5rem;
        font-size: 0.82rem; color: rgba(255,255,255,0.45); font-weight: 500;
    }
    .hero-stat strong { color: rgba(255,255,255,0.78); font-weight: 700; }
    .hero-stat i { color: var(--accent); font-size: 0.8rem; }
    .stat-sep { width: 4px; height: 4px; border-radius: 50%; background: rgba(255,255,255,0.14); }

    .page-body {
        max-width: 1340px; margin: 0 auto;
        padding: 2.5rem 1.5rem 4rem;
        position: relative; z-index: 1;
    }

    .filter-bar {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: var(--radius-lg); padding: 1.25rem 1.5rem;
        margin-bottom: 2.5rem;
        display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;
        box-shadow: var(--shadow-sm);
    }
    .filter-label {
        font-size: 0.72rem; font-weight: 800; text-transform: uppercase;
        letter-spacing: 1.1px; color: var(--text-muted); flex-shrink: 0;
    }
    .filter-tags { display: flex; gap: 0.55rem; flex-wrap: wrap; }
    .filter-tag {
        padding: 0.4rem 1rem; border-radius: 999px;
        border: 1.5px solid var(--border); background: var(--bg-raised);
        font-family: 'Outfit', sans-serif; font-size: 0.8rem; font-weight: 600;
        color: var(--text-secondary); cursor: pointer;
        transition: all var(--t-fast);
    }
    .filter-tag:hover { border-color: var(--primary); color: var(--primary); background: var(--primary-soft); }
    .filter-tag.active {
        background: var(--primary); color: #fff; border-color: var(--primary);
        box-shadow: 0 4px 12px var(--primary-glow);
    }

    .section-hd {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 1.5rem; gap: 1rem;
    }
    .section-hd-left { display: flex; align-items: center; gap: 0.7rem; }
    .section-icon {
        width: 36px; height: 36px; border-radius: 10px;
        background: var(--primary-soft); border: 1px solid var(--border);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem; color: var(--primary);
    }
    .section-title { font-size: 1.25rem; font-weight: 800; color: var(--text-primary); letter-spacing: -0.02em; }
    .section-sub { font-size: 0.8rem; color: var(--text-muted); margin-top: 0.1rem; }
    .view-all {
        display: inline-flex; align-items: center; gap: 0.35rem;
        font-size: 0.8rem; font-weight: 700; color: var(--primary);
        text-decoration: none; padding: 0.38rem 0.9rem;
        border-radius: 999px; border: 1.5px solid var(--border);
        background: var(--bg-raised); transition: all var(--t-fast); cursor: pointer;
    }
    .view-all:hover { background: var(--primary-soft); border-color: var(--primary); }

    .book-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(268px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3.5rem;
    }

    .book-card {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: var(--radius-lg); overflow: hidden;
        display: flex; flex-direction: column;
        transition: transform var(--t), box-shadow var(--t), border-color var(--t);
        box-shadow: var(--shadow-sm);
        cursor: pointer;
    }
    .book-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-lg);
        border-color: rgba(91,76,255,0.25);
    }

    .book-img { height: 240px; position: relative; overflow: hidden; background: var(--bg-raised); }
    .book-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.55s ease; }
    .book-card:hover .book-img img { transform: scale(1.07); }

    .book-img::after {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(14,10,35,0.55) 0%, transparent 55%);
        opacity: 0; transition: opacity var(--t);
    }
    .book-card:hover .book-img::after { opacity: 1; }

    .store-badge {
        position: absolute; top: 12px; left: 12px; z-index: 2;
        padding: 0.3rem 0.75rem; border-radius: 999px;
        background: rgba(14,10,35,0.65); backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.15);
        font-size: 0.68rem; font-weight: 700; color: #fff;
        letter-spacing: 0.3px;
    }

    .book-wish {
        position: absolute; top: 10px; right: 10px; z-index: 2;
        width: 32px; height: 32px; border-radius: 50%;
        background: rgba(14,10,35,0.55); backdrop-filter: blur(6px);
        border: 1px solid rgba(255,255,255,0.15);
        display: flex; align-items: center; justify-content: center;
        color: rgba(255,255,255,0.7); font-size: 0.85rem; cursor: pointer;
        transition: all var(--t-fast); opacity: 0;
    }
    .book-card:hover .book-wish { opacity: 1; }
    .book-wish:hover { background: var(--danger); color: #fff; border-color: var(--danger); }

    .quick-view {
        position: absolute; bottom: 0; left: 0; right: 0; z-index: 3;
        padding: 0.7rem 1rem;
        display: flex; align-items: center; justify-content: center;
        gap: 0.4rem; font-size: 0.78rem; font-weight: 700; color: #fff;
        transform: translateY(100%); transition: transform 0.22s ease;
        background: rgba(91,76,255,0.85); backdrop-filter: blur(6px);
    }
    .book-card:hover .quick-view { transform: translateY(0); }

    .book-body { padding: 1.2rem 1.25rem; flex: 1; display: flex; flex-direction: column; }
    .book-title { font-size: 1rem; font-weight: 800; color: var(--text-primary); letter-spacing: -0.015em; margin-bottom: 0.25rem; line-height: 1.3; }
    .book-author { font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.75rem; font-weight: 500; }
    .book-desc {
        font-size: 0.82rem; color: var(--text-secondary); line-height: 1.6;
        display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;
        overflow: hidden; flex: 1; margin-bottom: 1rem;
    }

    .book-rating { display: flex; align-items: center; gap: 0.35rem; margin-bottom: 0.9rem; }
    .stars { color: var(--accent); font-size: 0.75rem; letter-spacing: 1px; }
    .rating-num { font-size: 0.75rem; color: var(--text-muted); font-weight: 600; }

    .pricing-row {
        display: flex; gap: 0; margin-bottom: 1rem;
        border: 1px solid var(--border); border-radius: var(--radius-sm); overflow: hidden;
    }
    .price-pill {
        flex: 1; padding: 0.6rem 0.5rem; text-align: center;
        display: flex; flex-direction: column; align-items: center; gap: 0.1rem;
    }
    .price-pill:first-child { border-right: 1px solid var(--border); }
    .price-tag { font-size: 0.62rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.7px; color: var(--text-muted); }
    .price-amount { font-size: 1.05rem; font-weight: 900; letter-spacing: -0.02em; }
    .price-period { font-size: 0.6rem; color: var(--text-muted); }
    .price-rent .price-amount { color: var(--rent-color); }
    .price-buy  .price-amount { color: var(--buy-color); }

    .book-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 0.55rem; }
    .btn {
        padding: 0.65rem 0.5rem; border-radius: var(--radius-sm); border: none;
        font-family: 'Outfit', sans-serif; font-size: 0.8rem; font-weight: 700;
        cursor: pointer; display: flex; align-items: center; justify-content: center;
        gap: 0.4rem; transition: all var(--t-fast);
    }
    .btn i { font-size: 0.85rem; }
    .btn-rent { background: var(--primary-soft); color: var(--primary); border: 1.5px solid var(--border); }
    .btn-rent:hover { background: var(--primary); color: #fff; border-color: var(--primary); }
    .btn-buy { background: var(--success-soft); color: var(--success); border: 1.5px solid rgba(0,201,139,0.2); }
    .btn-buy:hover { background: var(--success); color: #fff; border-color: var(--success); }

    .categories-section { margin-bottom: 3.5rem; }
    .cat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 1rem;
    }
    .cat-card {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: var(--radius-lg); padding: 1.5rem 1rem;
        text-align: center; cursor: pointer;
        transition: all var(--t);
        box-shadow: var(--shadow-sm);
    }
    .cat-card:hover { border-color: var(--primary); transform: translateY(-4px); box-shadow: var(--shadow); }
    .cat-icon-wrap {
        width: 52px; height: 52px; border-radius: 14px; margin: 0 auto 0.85rem;
        background: var(--primary-soft); border: 1px solid var(--border);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; color: var(--primary);
        transition: all var(--t);
    }
    .cat-card:hover .cat-icon-wrap { background: var(--primary); color: #fff; box-shadow: 0 6px 18px var(--primary-glow); }
    .cat-name { font-size: 0.83rem; font-weight: 700; color: var(--text-secondary); transition: color var(--t); }
    .cat-card:hover .cat-name { color: var(--primary); }
    .cat-count { font-size: 0.72rem; color: var(--text-muted); margin-top: 0.2rem; }

    .promo-banner {
        background: linear-gradient(135deg, #1a1050 0%, var(--primary) 55%, #3d2fe0 100%);
        border-radius: var(--radius-xl); padding: 2.5rem 2.5rem;
        display: flex; align-items: center; justify-content: space-between;
        gap: 1.5rem; flex-wrap: wrap; margin-bottom: 3.5rem;
        position: relative; overflow: hidden;
        box-shadow: 0 12px 40px var(--primary-glow);
    }
    .promo-banner::before {
        content: ''; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(ellipse 60% 100% at 80% 50%, rgba(245,176,66,0.18) 0%, transparent 60%);
    }
    .promo-deco { position: absolute; border-radius: 50%; pointer-events: none; border: 1px solid rgba(255,255,255,0.08); }
    .promo-deco-1 { width: 220px; height: 220px; top: -80px; right: 22%; }
    .promo-deco-2 { width: 140px; height: 140px; bottom: -60px; right: 10%; border-color: rgba(245,176,66,0.1); }
    .promo-text { position: relative; z-index: 1; }
    .promo-eyebrow { font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.2px; color: var(--accent); margin-bottom: 0.6rem; }
    .promo-text h2 { font-size: clamp(1.4rem, 3vw, 2rem); font-weight: 900; color: #fff; letter-spacing: -0.03em; line-height: 1.15; margin-bottom: 0.5rem; }
    .promo-text p { font-size: 0.88rem; color: rgba(255,255,255,0.58); max-width: 380px; }
    .promo-actions { display: flex; gap: 0.75rem; flex-wrap: wrap; position: relative; z-index: 1; }
    .promo-btn {
        padding: 0.75rem 1.5rem; border-radius: 999px;
        font-family: 'Outfit', sans-serif; font-size: 0.88rem; font-weight: 700;
        cursor: pointer; transition: all var(--t-fast); border: none;
        display: flex; align-items: center; gap: 0.45rem;
    }
    .promo-btn-primary { background: #fff; color: var(--primary); }
    .promo-btn-primary:hover { background: var(--accent); color: #fff; }
    .promo-btn-outline { background: rgba(255,255,255,0.12); color: #fff; border: 1px solid rgba(255,255,255,0.25); backdrop-filter: blur(6px); }
    .promo-btn-outline:hover { background: rgba(255,255,255,0.22); }

    footer {
        background: var(--bg-card); border-top: 1px solid var(--border);
        padding: 3rem 1.5rem 1.5rem;
        position: relative; z-index: 1;
    }
    .footer-inner {
        max-width: 1340px; margin: 0 auto;
        display: grid; grid-template-columns: 1.4fr repeat(3, 1fr); gap: 2.5rem;
        margin-bottom: 2.5rem;
    }
    .footer-brand .logo { margin-bottom: 0.85rem; }
    .footer-brand p { font-size: 0.82rem; color: var(--text-muted); line-height: 1.65; max-width: 240px; }
    .footer-socials { display: flex; gap: 0.5rem; margin-top: 1.2rem; }
    .social-icon {
        width: 34px; height: 34px; border-radius: 9px;
        background: var(--bg-raised); border: 1.5px solid var(--border);
        display: flex; align-items: center; justify-content: center;
        color: var(--text-muted); font-size: 0.9rem; cursor: pointer;
        transition: all var(--t-fast); text-decoration: none;
    }
    .social-icon:hover { color: var(--primary); border-color: var(--primary); background: var(--primary-soft); }

    .footer-col h4 { font-size: 0.78rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: var(--text-primary); margin-bottom: 1rem; }
    .footer-links { list-style: none; display: flex; flex-direction: column; gap: 0.55rem; }
    .footer-links a { font-size: 0.82rem; color: var(--text-muted); text-decoration: none; font-weight: 500; transition: color var(--t-fast); cursor: pointer; }
    .footer-links a:hover { color: var(--primary); }

    .footer-bottom {
        max-width: 1340px; margin: 0 auto;
        padding-top: 1.5rem; border-top: 1px solid var(--border-soft);
        display: flex; align-items: center; justify-content: space-between;
        gap: 1rem; flex-wrap: wrap;
    }
    .footer-copy { font-size: 0.77rem; color: var(--text-muted); }
    .footer-badges { display: flex; gap: 0.5rem; flex-wrap: wrap; }
    .foot-badge {
        display: inline-flex; align-items: center; gap: 0.35rem;
        padding: 0.28rem 0.75rem; border-radius: 999px;
        background: var(--bg-raised); border: 1px solid var(--border);
        font-size: 0.7rem; font-weight: 700; color: var(--text-muted);
    }
    .foot-badge i { color: var(--success); }

    @media (max-width: 900px) {
        .footer-inner { grid-template-columns: 1fr 1fr; }
        .header-search { display: none; }
        .mob-menu-btn { display: flex; }
        .nav-right .nav-btn { display: none; }
    }
    @media (max-width: 600px) {
        .hero { padding: 3.5rem 1.25rem 3rem; }
        .hero-stats { gap: 1rem; }
        .stat-sep { display: none; }
        .footer-inner { grid-template-columns: 1fr; }
        .promo-banner { padding: 2rem 1.5rem; }
        .book-grid { grid-template-columns: 1fr; }
        .cat-grid { grid-template-columns: repeat(3, 1fr); }
    }
`;

export default function Welcome({ auth, laravelVersion, phpVersion }) {
    const redirectToLogin = () => {
        window.location.href = route('login');
    };

    const handleWishlistClick = (e) => {
        e.stopPropagation();
        const btn = e.currentTarget;
        const icon = btn.querySelector('i');
        if (icon.className.includes('bi-heart-fill')) {
            icon.className = 'bi bi-heart';
            btn.style.background = '';
            btn.style.color = '';
            btn.style.borderColor = '';
        } else {
            icon.className = 'bi bi-heart-fill';
            btn.style.background = 'var(--danger)';
            btn.style.color = '#fff';
            btn.style.borderColor = 'var(--danger)';
            btn.style.opacity = '1';
        }
    };

    const handleFilterClick = (e) => {
        document.querySelectorAll('.filter-tag').forEach(t => t.classList.remove('active'));
        e.currentTarget.classList.add('active');
        redirectToLogin();
    };

    const handleThemeToggle = () => {
        const html = document.documentElement;
        const themeIcon = document.getElementById('themeIcon');
        const isDark = html.getAttribute('data-theme') !== 'dark';
        html.setAttribute('data-theme', isDark ? 'dark' : 'light');
        if (themeIcon) themeIcon.className = isDark ? 'bi bi-moon-stars-fill' : 'bi bi-sun-fill';
        localStorage.setItem('bh-theme', isDark ? 'dark' : 'light');
    };

    const handleSearchKeyPress = (e) => {
        if (e.key === 'Enter') redirectToLogin();
    };

    // Apply saved theme on mount
    if (typeof window !== 'undefined') {
        const saved = localStorage.getItem('bh-theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const isDark = saved ? saved === 'dark' : prefersDark;
        document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
    }

    return (
        <>
            <Head title="BookHub Store · Find Your Next Read">
                <meta charSet="UTF-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <link rel="preconnect" href="https://fonts.googleapis.com" />
                <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
            </Head>

            <style dangerouslySetInnerHTML={{ __html: css }} />

            {/* HEADER */}
            <header>
                <div className="header-inner">
                    <a className="logo" onClick={redirectToLogin}>
                        <div className="logo-icon"><i className="bi bi-book-half"></i></div>
                        <span className="logo-text">Book<span>Hub</span></span>
                    </a>

                    <div className="header-search">
                        <i className="bi bi-search"></i>
                        <input
                            type="text"
                            id="headerSearch"
                            placeholder="Search books, authors, genres…"
                            onKeyPress={handleSearchKeyPress}
                        />
                        <button className="search-btn" onClick={redirectToLogin}>Search</button>
                    </div>

                    <nav className="nav-right">
                        <button className="nav-btn" onClick={redirectToLogin}>
                            <i className="bi bi-house"></i> Home
                        </button>
                        <button className="nav-btn" onClick={redirectToLogin}>
                            <i className="bi bi-shop"></i> Stores
                        </button>
                        <button className="nav-btn" onClick={redirectToLogin}>
                            <i className="bi bi-book-open"></i> My Books
                        </button>
                        <button className="nav-btn" onClick={() => window.location.href = route('contact')}>
                            <i className="bi bi-envelope"></i> Contact
                        </button>

                        <div style={{ width: '1px', height: '22px', background: 'var(--border)', margin: '0 0.2rem' }}></div>

                        <button className="cart-btn nav-btn" onClick={redirectToLogin}>
                            <i className="bi bi-bag"></i>
                            <span className="cart-badge"></span>
                        </button>

                        <a className="nav-pill nav-pill-outline" href={route('login')}>
                            Login
                        </a>
                        <a className="nav-pill" href={route('register')}>
                            <i className="bi bi-person-plus"></i> Register
                        </a>
                        <button className="theme-btn" id="themeToggle" aria-label="Toggle theme" onClick={handleThemeToggle}>
                            <i className="bi bi-sun-fill" id="themeIcon"></i>
                        </button>
                        <button className="mob-menu-btn" id="mobMenu"><i className="bi bi-list"></i></button>
                    </nav>
                </div>
            </header>

            {/* HERO */}
            <section className="hero">
                <div className="hero-ring hero-ring-1"></div>
                <div className="hero-ring hero-ring-2"></div>
                <div className="hero-ring hero-ring-3"></div>
                <div className="hero-inner">
                    <div className="hero-eyebrow">
                        <span className="live-dot"></span>
                        books · stores · updated daily
                    </div>
                    <h1>Discover Your Next<br /><em>Favourite</em> Book</h1>
                    <p>Browse thousands of books from multiple stores. Rent affordably or own forever — your literary journey starts right here.</p>

                    <div className="hero-search">
                        <i className="bi bi-search"></i>
                        <input
                            type="text"
                            id="heroSearch"
                            placeholder="Search for books, authors, or genres…"
                            onKeyPress={handleSearchKeyPress}
                        />
                        <button className="hero-search-btn" onClick={redirectToLogin}>
                            <i className="bi bi-arrow-right"></i> Search
                        </button>
                    </div>

                    <div className="hero-stats">
                        <div className="hero-stat"><i className="bi bi-book-fill"></i> <strong></strong> books</div>
                        <div className="stat-sep"></div>
                        <div className="hero-stat"><i className="bi bi-shop"></i> <strong></strong> partner stores</div>
                        <div className="stat-sep"></div>
                        <div className="hero-stat"><i className="bi bi-people-fill"></i> <strong></strong> readers</div>
                        <div className="stat-sep"></div>
                        <div className="hero-stat"><i className="bi bi-shield-check"></i> <strong>Secure</strong> checkout</div>
                    </div>
                </div>
            </section>

            {/* PAGE BODY */}
            <main className="page-body">

                {/* Store Filter Bar */}
                <div className="filter-bar">
                    <span className="filter-label">Filter by store</span>
                    <div className="filter-tags">
                        <button className="filter-tag active" onClick={handleFilterClick}>All Stores</button>
                        <button className="filter-tag" onClick={handleFilterClick}>Book Haven</button>
                        <button className="filter-tag" onClick={handleFilterClick}>Literary Lounge</button>
                        <button className="filter-tag" onClick={handleFilterClick}>Page Turners</button>
                        <button className="filter-tag" onClick={handleFilterClick}>Novel Nook</button>
                        <button className="filter-tag" onClick={handleFilterClick}>The Reading Room</button>
                    </div>
                </div>

                {/* Featured Books */}
                <section>
                    <div className="section-hd">
                        <div className="section-hd-left">
                            <div className="section-icon"><i className="bi bi-star-fill"></i></div>
                            <div>
                                <div className="section-title">Featured This Week</div>
                                <div className="section-sub">Hand-picked by our editors</div>
                            </div>
                        </div>
                        <a className="view-all" onClick={redirectToLogin}>View All <i className="bi bi-arrow-right"></i></a>
                    </div>

                    <div className="book-grid">
                        {/* Book 1 */}
                        <div className="book-card">
                            <div className="book-img">
                                <span className="store-badge">Book Haven</span>
                                <button className="book-wish" onClick={handleWishlistClick}><i className="bi bi-heart"></i></button>
                                <div className="quick-view"><i className="bi bi-eye"></i> Quick View</div>
                                <img src="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=600&q=80" alt="The Silent Patient" />
                            </div>
                            <div className="book-body">
                                <div className="book-title">The Silent Patient</div>
                                <div className="book-author">Alex Michaelides</div>
                                <div className="book-rating">
                                    <span className="stars">★★★★★</span>
                                    <span className="rating-num">4.9 · 2.1k reviews</span>
                                </div>
                                <div className="book-desc">A psychological thriller about a woman who shoots her husband and then stops speaking, and the criminal psychotherapist determined to uncover the truth.</div>
                                <div className="pricing-row">
                                    <div className="price-pill price-rent">
                                        <span className="price-tag">Rent</span>
                                        <span className="price-amount">$4.99</span>
                                        <span className="price-period">/month</span>
                                    </div>
                                    <div className="price-pill price-buy">
                                        <span className="price-tag">Buy</span>
                                        <span className="price-amount">$24.99</span>
                                        <span className="price-period">one-time</span>
                                    </div>
                                </div>
                                <div className="book-actions">
                                    <button className="btn btn-rent" onClick={redirectToLogin}><i className="bi bi-arrow-repeat"></i> Rent</button>
                                    <button className="btn btn-buy" onClick={redirectToLogin}><i className="bi bi-bag-check"></i> Buy</button>
                                </div>
                            </div>
                        </div>

                        {/* Book 2 */}
                        <div className="book-card">
                            <div className="book-img">
                                <span className="store-badge">Literary Lounge</span>
                                <button className="book-wish" onClick={handleWishlistClick}><i className="bi bi-heart"></i></button>
                                <div className="quick-view"><i className="bi bi-eye"></i> Quick View</div>
                                <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=600&q=80" alt="Project Hail Mary" />
                            </div>
                            <div className="book-body">
                                <div className="book-title">Project Hail Mary</div>
                                <div className="book-author">Andy Weir</div>
                                <div className="book-rating">
                                    <span className="stars">★★★★★</span>
                                    <span className="rating-num">4.8 · 3.4k reviews</span>
                                </div>
                                <div className="book-desc">A lone astronaut must save the earth from disaster in this incredible science-based thriller from the #1 NYT bestselling author of The Martian.</div>
                                <div className="pricing-row">
                                    <div className="price-pill price-rent">
                                        <span className="price-tag">Rent</span>
                                        <span className="price-amount">$5.99</span>
                                        <span className="price-period">/month</span>
                                    </div>
                                    <div className="price-pill price-buy">
                                        <span className="price-tag">Buy</span>
                                        <span className="price-amount">$28.99</span>
                                        <span className="price-period">one-time</span>
                                    </div>
                                </div>
                                <div className="book-actions">
                                    <button className="btn btn-rent" onClick={redirectToLogin}><i className="bi bi-arrow-repeat"></i> Rent</button>
                                    <button className="btn btn-buy" onClick={redirectToLogin}><i className="bi bi-bag-check"></i> Buy</button>
                                </div>
                            </div>
                        </div>

                        {/* Book 3 */}
                        <div className="book-card">
                            <div className="book-img">
                                <span className="store-badge">Page Turners</span>
                                <button className="book-wish" onClick={handleWishlistClick}><i className="bi bi-heart"></i></button>
                                <div className="quick-view"><i className="bi bi-eye"></i> Quick View</div>
                                <img src="https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&w=600&q=80" alt="Klara and the Sun" />
                            </div>
                            <div className="book-body">
                                <div className="book-title">Klara and the Sun</div>
                                <div className="book-author">Kazuo Ishiguro</div>
                                <div className="book-rating">
                                    <span className="stars">★★★★☆</span>
                                    <span className="rating-num">4.4 · 1.8k reviews</span>
                                </div>
                                <div className="book-desc">A magnificent novel from the Nobel laureate — author of Never Let Me Go and the Booker Prize-winning The Remains of the Day.</div>
                                <div className="pricing-row">
                                    <div className="price-pill price-rent">
                                        <span className="price-tag">Rent</span>
                                        <span className="price-amount">$3.99</span>
                                        <span className="price-period">/month</span>
                                    </div>
                                    <div className="price-pill price-buy">
                                        <span className="price-tag">Buy</span>
                                        <span className="price-amount">$22.50</span>
                                        <span className="price-period">one-time</span>
                                    </div>
                                </div>
                                <div className="book-actions">
                                    <button className="btn btn-rent" onClick={redirectToLogin}><i className="bi bi-arrow-repeat"></i> Rent</button>
                                    <button className="btn btn-buy" onClick={redirectToLogin}><i className="bi bi-bag-check"></i> Buy</button>
                                </div>
                            </div>
                        </div>

                        {/* Book 4 */}
                        <div className="book-card">
                            <div className="book-img">
                                <span className="store-badge">Novel Nook</span>
                                <button className="book-wish" onClick={handleWishlistClick}><i className="bi bi-heart"></i></button>
                                <div className="quick-view"><i className="bi bi-eye"></i> Quick View</div>
                                <img src="https://images.unsplash.com/photo-1516979187457-637abb4f9353?auto=format&fit=crop&w=600&q=80" alt="The Midnight Library" />
                            </div>
                            <div className="book-body">
                                <div className="book-title">The Midnight Library</div>
                                <div className="book-author">Matt Haig</div>
                                <div className="book-rating">
                                    <span className="stars">★★★★★</span>
                                    <span className="rating-num">4.7 · 5.2k reviews</span>
                                </div>
                                <div className="book-desc">Between life and death there is a library, and within that library the shelves go on forever. A novel about all the choices that go into a life well lived.</div>
                                <div className="pricing-row">
                                    <div className="price-pill price-rent">
                                        <span className="price-tag">Rent</span>
                                        <span className="price-amount">$4.50</span>
                                        <span className="price-period">/month</span>
                                    </div>
                                    <div className="price-pill price-buy">
                                        <span className="price-tag">Buy</span>
                                        <span className="price-amount">$26.75</span>
                                        <span className="price-period">one-time</span>
                                    </div>
                                </div>
                                <div className="book-actions">
                                    <button className="btn btn-rent" onClick={redirectToLogin}><i className="bi bi-arrow-repeat"></i> Rent</button>
                                    <button className="btn btn-buy" onClick={redirectToLogin}><i className="bi bi-bag-check"></i> Buy</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {/* Promo Banner */}
                <div className="promo-banner">
                    <div className="promo-deco promo-deco-1"></div>
                    <div className="promo-deco promo-deco-2"></div>
                    <div className="promo-text">
                        <div className="promo-eyebrow"><i className="bi bi-lightning-charge-fill"></i> Limited Time Offer</div>
                        <h2>Rent &amp; Buy &amp; Sell Books</h2>
                        <p>Sign up today and enjoy unlimited rentals from all 6 partner stores — no credit card needed to start.</p>
                    </div>
                    <div className="promo-actions">
                        <button className="promo-btn promo-btn-primary" onClick={() => window.location.href = route('register')}>
                            <i className="bi bi-person-plus"></i> Create Free Account
                        </button>
                        <button className="promo-btn promo-btn-outline" onClick={redirectToLogin}>
                            <i className="bi bi-arrow-right"></i> Browse Books
                        </button>
                    </div>
                </div>

                {/* Categories */}
                <section className="categories-section">
                    <div className="section-hd">
                        <div className="section-hd-left">
                            <div className="section-icon"><i className="bi bi-grid-fill"></i></div>
                            <div>
                                <div className="section-title">Browse by Category</div>
                                <div className="section-sub">Find your perfect genre</div>
                            </div>
                        </div>
                        <a className="view-all" onClick={redirectToLogin}>All Categories <i className="bi bi-arrow-right"></i></a>
                    </div>
                    <div className="cat-grid">
                        <div className="cat-card" onClick={redirectToLogin}>
                            <div className="cat-icon-wrap"><i className="bi bi-incognito"></i></div>
                            <div className="cat-name">Mystery &amp; Thriller</div>
                            <div className="cat-count">284 books</div>
                        </div>
                        <div className="cat-card" onClick={redirectToLogin}>
                            <div className="cat-icon-wrap"><i className="bi bi-rocket-takeoff-fill"></i></div>
                            <div className="cat-name">Science Fiction</div>
                            <div className="cat-count">197 books</div>
                        </div>
                        <div className="cat-card" onClick={redirectToLogin}>
                            <div className="cat-icon-wrap"><i className="bi bi-heart-fill"></i></div>
                            <div className="cat-name">Romance</div>
                            <div className="cat-count">341 books</div>
                        </div>
                        <div className="cat-card" onClick={redirectToLogin}>
                            <div className="cat-icon-wrap"><i className="bi bi-magic"></i></div>
                            <div className="cat-name">Fantasy</div>
                            <div className="cat-count">212 books</div>
                        </div>
                        <div className="cat-card" onClick={redirectToLogin}>
                            <div className="cat-icon-wrap"><i className="bi bi-mortarboard-fill"></i></div>
                            <div className="cat-name">Non-Fiction</div>
                            <div className="cat-count">256 books</div>
                        </div>
                        <div className="cat-card" onClick={redirectToLogin}>
                            <div className="cat-icon-wrap"><i className="bi bi-stars"></i></div>
                            <div className="cat-name">Young Adult</div>
                            <div className="cat-count">192 books</div>
                        </div>
                    </div>
                </section>

                {/* Popular This Month */}
                <section>
                    <div className="section-hd">
                        <div className="section-hd-left">
                            <div className="section-icon"><i className="bi bi-fire"></i></div>
                            <div>
                                <div className="section-title">Popular This Month</div>
                                <div className="section-sub">Trending across all stores</div>
                            </div>
                        </div>
                        <a className="view-all" onClick={redirectToLogin}>View All <i className="bi bi-arrow-right"></i></a>
                    </div>

                    <div className="book-grid">
                        {/* Book 5 */}
                        <div className="book-card">
                            <div className="book-img">
                                <span className="store-badge">The Reading Room</span>
                                <button className="book-wish" onClick={handleWishlistClick}><i className="bi bi-heart"></i></button>
                                <div className="quick-view"><i className="bi bi-eye"></i> Quick View</div>
                                <img src="https://images.unsplash.com/photo-1589998059171-988d887df646?auto=format&fit=crop&w=600&q=80" alt="Educated" />
                            </div>
                            <div className="book-body">
                                <div className="book-title">Educated: A Memoir</div>
                                <div className="book-author">Tara Westover</div>
                                <div className="book-rating"><span className="stars">★★★★★</span><span className="rating-num">4.9 · 7.6k reviews</span></div>
                                <div className="book-desc">A memoir about a young girl who, kept out of school, leaves her survivalist family and goes on to earn a PhD from Cambridge University.</div>
                                <div className="pricing-row">
                                    <div className="price-pill price-rent"><span className="price-tag">Rent</span><span className="price-amount">$4.25</span><span className="price-period">/month</span></div>
                                    <div className="price-pill price-buy"><span className="price-tag">Buy</span><span className="price-amount">$21.99</span><span className="price-period">one-time</span></div>
                                </div>
                                <div className="book-actions">
                                    <button className="btn btn-rent" onClick={redirectToLogin}><i className="bi bi-arrow-repeat"></i> Rent</button>
                                    <button className="btn btn-buy" onClick={redirectToLogin}><i className="bi bi-bag-check"></i> Buy</button>
                                </div>
                            </div>
                        </div>

                        {/* Book 6 */}
                        <div className="book-card">
                            <div className="book-img">
                                <span className="store-badge">Book Haven</span>
                                <button className="book-wish" onClick={handleWishlistClick}><i className="bi bi-heart"></i></button>
                                <div className="quick-view"><i className="bi bi-eye"></i> Quick View</div>
                                <img src="https://images.unsplash.com/photo-1541963463532-d68292c34b19?auto=format&fit=crop&w=600&q=80" alt="Where the Crawdads Sing" />
                            </div>
                            <div className="book-body">
                                <div className="book-title">Where the Crawdads Sing</div>
                                <div className="book-author">Delia Owens</div>
                                <div className="book-rating"><span className="stars">★★★★★</span><span className="rating-num">4.8 · 9.1k reviews</span></div>
                                <div className="book-desc">For years, rumors of the "Marsh Girl" have haunted Barkley Cove. A stunning debut novel about nature, love, and the delicate balance of survival.</div>
                                <div className="pricing-row">
                                    <div className="price-pill price-rent"><span className="price-tag">Rent</span><span className="price-amount">$5.25</span><span className="price-period">/month</span></div>
                                    <div className="price-pill price-buy"><span className="price-tag">Buy</span><span className="price-amount">$27.50</span><span className="price-period">one-time</span></div>
                                </div>
                                <div className="book-actions">
                                    <button className="btn btn-rent" onClick={redirectToLogin}><i className="bi bi-arrow-repeat"></i> Rent</button>
                                    <button className="btn btn-buy" onClick={redirectToLogin}><i className="bi bi-bag-check"></i> Buy</button>
                                </div>
                            </div>
                        </div>

                        {/* Book 7 */}
                        <div className="book-card">
                            <div className="book-img">
                                <span className="store-badge">Literary Lounge</span>
                                <button className="book-wish" onClick={handleWishlistClick}><i className="bi bi-heart"></i></button>
                                <div className="quick-view"><i className="bi bi-eye"></i> Quick View</div>
                                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=600&q=80" alt="The Four Winds" />
                            </div>
                            <div className="book-body">
                                <div className="book-title">The Four Winds</div>
                                <div className="book-author">Kristin Hannah</div>
                                <div className="book-rating"><span className="stars">★★★★★</span><span className="rating-num">4.7 · 4.3k reviews</span></div>
                                <div className="book-desc">An epic novel of love and heroism and hope, set against the backdrop of one of America's most defining eras — the Great Depression.</div>
                                <div className="pricing-row">
                                    <div className="price-pill price-rent"><span className="price-tag">Rent</span><span className="price-amount">$5.50</span><span className="price-period">/month</span></div>
                                    <div className="price-pill price-buy"><span className="price-tag">Buy</span><span className="price-amount">$29.99</span><span className="price-period">one-time</span></div>
                                </div>
                                <div className="book-actions">
                                    <button className="btn btn-rent" onClick={redirectToLogin}><i className="bi bi-arrow-repeat"></i> Rent</button>
                                    <button className="btn btn-buy" onClick={redirectToLogin}><i className="bi bi-bag-check"></i> Buy</button>
                                </div>
                            </div>
                        </div>

                        {/* Book 8 */}
                        <div className="book-card">
                            <div className="book-img">
                                <span className="store-badge">Page Turners</span>
                                <button className="book-wish" onClick={handleWishlistClick}><i className="bi bi-heart"></i></button>
                                <div className="quick-view"><i className="bi bi-eye"></i> Quick View</div>
                                <img src="https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?auto=format&fit=crop&w=600&q=80" alt="The Invisible Life of Addie LaRue" />
                            </div>
                            <div className="book-body">
                                <div className="book-title">The Invisible Life of Addie LaRue</div>
                                <div className="book-author">V.E. Schwab</div>
                                <div className="book-rating"><span className="stars">★★★★☆</span><span className="rating-num">4.6 · 6.7k reviews</span></div>
                                <div className="book-desc">A life no one will remember. A story you will never forget. France, 1714 — a young woman makes a Faustian bargain to live forever and be forgotten by all.</div>
                                <div className="pricing-row">
                                    <div className="price-pill price-rent"><span className="price-tag">Rent</span><span className="price-amount">$4.75</span><span className="price-period">/month</span></div>
                                    <div className="price-pill price-buy"><span className="price-tag">Buy</span><span className="price-amount">$25.50</span><span className="price-period">one-time</span></div>
                                </div>
                                <div className="book-actions">
                                    <button className="btn btn-rent" onClick={redirectToLogin}><i className="bi bi-arrow-repeat"></i> Rent</button>
                                    <button className="btn btn-buy" onClick={redirectToLogin}><i className="bi bi-bag-check"></i> Buy</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </main>

            {/* FOOTER */}
            <footer>
                <div className="footer-inner">
                    <div className="footer-brand">
                        <a className="logo" onClick={redirectToLogin}>
                            <div className="logo-icon"><i className="bi bi-book-half"></i></div>
                            <span className="logo-text">Book<span>Hub</span></span>
                        </a>
                        <p>Your one-stop destination for buying and renting books from multiple partner stores. Find your next read today.</p>
                        <div className="footer-socials">
                            <a className="social-icon" onClick={redirectToLogin}><i className="bi bi-facebook"></i></a>
                            <a className="social-icon" onClick={redirectToLogin}><i className="bi bi-twitter-x"></i></a>
                            <a className="social-icon" onClick={redirectToLogin}><i className="bi bi-instagram"></i></a>
                            <a className="social-icon" onClick={redirectToLogin}><i className="bi bi-goodreads"></i></a>
                        </div>
                    </div>
                    <div className="footer-col">
                        <h4>Quick Links</h4>
                        <ul className="footer-links">
                            <li><a onClick={redirectToLogin}>Home</a></li>
                            <li><a onClick={redirectToLogin}>All Books</a></li>
                            <li><a onClick={redirectToLogin}>Stores</a></li>
                            <li><a onClick={redirectToLogin}>Categories</a></li>
                            <li><a onClick={redirectToLogin}>My Account</a></li>
                        </ul>
                    </div>
                    <div className="footer-col">
                        <h4>Support</h4>
                        <ul className="footer-links">
                            <li><a onClick={redirectToLogin}>FAQ</a></li>
                            <li><a onClick={redirectToLogin}>Shipping Info</a></li>
                            <li><a onClick={redirectToLogin}>Return Policy</a></li>
                            <li><a onClick={redirectToLogin}>Rental Terms</a></li>
                            <li><a onClick={() => window.location.href = route('contact')}>Contact Us</a></li>
                        </ul>
                    </div>
                    <div className="footer-col">
                        <h4>Account</h4>
                        <ul className="footer-links">
                            <li><a onClick={redirectToLogin}>Sign In</a></li>
                            <li><a onClick={() => window.location.href = route('register')}>Create Account</a></li>
                            <li><a onClick={redirectToLogin}>My Reading List</a></li>
                            <li><a onClick={redirectToLogin}>Order History</a></li>
                            <li><a onClick={redirectToLogin}>Admin Login</a></li>
                        </ul>
                    </div>
                </div>
                <div className="footer-bottom">
                    <div className="footer-copy">© 2025 BookHub Store. All rights reserved. Books from multiple partner stores.</div>
                    <div className="footer-badges">
                        <span className="foot-badge"><i className="bi bi-shield-check"></i> SSL Secured</span>
                        <span className="foot-badge"><i className="bi bi-lock-fill"></i> 256-bit Encrypted</span>
                        <span className="foot-badge"><i className="bi bi-patch-check-fill"></i> Verified Stores</span>
                    </div>
                </div>
            </footer>
        </>
    );
}