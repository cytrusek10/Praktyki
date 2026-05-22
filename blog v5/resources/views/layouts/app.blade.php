<!DOCTYPE html>
<html lang="pl" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mój Randomowy Blog')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        [data-theme="dark"] {
            --bg: #0f0f13;
            --surface: #1a1a24;
            --border: #2a2a3a;
            --accent: #f59e0b;
            --accent2: #ec4899;
            --text: #e8e8f0;
            --muted: #8888aa;
            --header-bg: rgba(15,15,19,0.95);
        }

        [data-theme="light"] {
            --bg: #f5f5f0;
            --surface: #ffffff;
            --border: #e0e0d8;
            --accent: #d97706;
            --accent2: #db2777;
            --text: #1a1a2e;
            --muted: #666680;
            --header-bg: rgba(245,245,240,0.95);
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Georgia', serif;
            min-height: 100vh;
            transition: background 0.2s, color 0.2s;
        }

        header {
            border-bottom: 1px solid var(--border);
            padding: 1.5rem 0;
            position: sticky;
            top: 0;
            background: var(--header-bg);
            backdrop-filter: blur(10px);
            z-index: 100;
        }

        .header-inner {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo { font-size: 1.4rem; font-weight: bold; color: var(--accent); text-decoration: none; letter-spacing: -0.5px; }
        .logo span { color: var(--accent2); }

        nav { display: flex; align-items: center; gap: 1.5rem; }

        nav a { color: var(--muted); text-decoration: none; font-size: 0.9rem; font-family: monospace; transition: color 0.2s; }
        nav a:hover { color: var(--accent); }

        .theme-toggle {
            background: none;
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 0.3rem 0.6rem;
            cursor: pointer;
            color: var(--muted);
            font-size: 1rem;
            transition: border-color 0.2s;
        }
        .theme-toggle:hover { border-color: var(--accent); }

        main { max-width: 900px; margin: 0 auto; padding: 3rem 1.5rem; }

        footer {
            border-top: 1px solid var(--border);
            text-align: center;
            padding: 2rem;
            color: var(--muted);
            font-size: 0.85rem;
            font-family: monospace;
        }

        .badge {
            display: inline-block;
            padding: 0.2rem 0.6rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-family: monospace;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .badge-technologia { background: #1e3a5f; color: #60a5fa; }
        .badge-zycie       { background: #1a3a2a; color: #4ade80; }
        .badge-jedzenie    { background: #3a2a10; color: #fb923c; }
        .badge-muzyka      { background: #2a1a3a; color: #c084fc; }
        .badge-sport       { background: #3a1a1a; color: #f87171; }
        .badge-inne        { background: #2a2a2a; color: #94a3b8; }
    </style>
    @stack('meta')
    @stack('styles')
</head>
<body>
    <header>
        <div class="header-inner">
            <a href="{{ route('home') }}" class="logo">random<span>blog</span>.pl</a>
            <nav>
                <a href="{{ route('home') }}">posty</a>
                @auth
                    <a href="{{ route('admin.posts.index') }}">admin</a>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline">
                        @csrf
                        <button type="submit" style="background:none;border:none;cursor:pointer;color:var(--muted);font-family:monospace;font-size:0.9rem;">wyloguj</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">zaloguj</a>
                    <a href="{{ route('register') }}">rejestracja</a>
                @endauth
                <button class="theme-toggle" id="themeToggle" title="Zmień motyw">🌙</button>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>zrobione na <strong style="color: var(--accent)">Laravel</strong> + <strong style="color: var(--accent2)">Filament</strong> &mdash; {{ date('Y') }}</p>
    </footer>

    <script>
        const html = document.documentElement;
        const btn = document.getElementById('themeToggle');
        const saved = localStorage.getItem('theme') || 'dark';
        html.setAttribute('data-theme', saved);
        btn.textContent = saved === 'dark' ? '🌙' : '☀️';
        btn.addEventListener('click', () => {
            const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
            btn.textContent = next === 'dark' ? '🌙' : '☀️';
        });
    </script>
</body>
</html>
