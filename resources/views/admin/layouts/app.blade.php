<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIGMA Admin')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #0a1628 0%, #0f2137 50%, #1a3a5c 100%); min-height: 100vh; color: #e8edf5; }
        
        /* Navbar */
        .navbar { background: rgba(10, 22, 40, 0.85); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(255,255,255,0.08); padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 100; }
        .navbar-brand { font-size: 1.25rem; font-weight: 700; color: #fff; text-decoration: none; letter-spacing: -0.3px; }
        .navbar-brand span { color: #38bdf8; }
        .navbar-nav { display: flex; gap: 0.25rem; align-items: center; }
        .navbar-nav a { color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.8125rem; font-weight: 500; padding: 0.5rem 0.875rem; border-radius: 8px; transition: all 0.2s; }
        .navbar-nav a:hover, .navbar-nav a.active { color: #fff; background: rgba(255,255,255,0.08); }
        .navbar-nav a.active { color: #38bdf8; border-bottom: 2px solid #38bdf8; border-radius: 0; }
        .nav-dropdown { position: relative; }
        .nav-dropdown-toggle { background: none; border: 0; font-family: inherit; cursor: pointer; color: rgba(255,255,255,0.7); font-size: 0.8125rem; font-weight: 500; padding: 0.5rem 0.875rem; border-radius: 8px; user-select: none; }
        .nav-dropdown-toggle::after { content: '▾'; margin-left: 0.35rem; font-size: 0.7rem; }
        .nav-dropdown-toggle.show, .nav-dropdown-toggle:hover, .nav-dropdown-toggle.active { color: #fff; background: rgba(255,255,255,0.08); }
        .nav-dropdown-toggle.active { color: #38bdf8; }
        .nav-dropdown-menu { display: none; position: absolute; top: calc(100% + 0.5rem); right: 0; min-width: 170px; padding: 0.35rem; background: #0f2137; border: 1px solid rgba(255,255,255,0.12); border-radius: 8px; box-shadow: 0 12px 30px rgba(0,0,0,0.3); }
        .nav-dropdown-menu.show { display: block; }
        .nav-dropdown-menu a { display: block; padding: 0.55rem 0.7rem; white-space: nowrap; border-radius: 5px; border-bottom: 0; }
        .nav-dropdown-menu a:hover, .nav-dropdown-menu a.active { color: #38bdf8; background: rgba(56, 189, 248, 0.1); }
        .nav-right { display: flex; align-items: center; gap: 1.25rem; }
        .notification-bell { position: relative; cursor: pointer; color: rgba(255,255,255,0.7); }
        .notification-badge { position: absolute; top: -6px; right: -6px; background: #ef4444; color: white; font-size: 0.625rem; font-weight: 700; padding: 0.125rem 0.375rem; border-radius: 9999px; min-width: 18px; text-align: center; }
        .user-profile { display: flex; align-items: center; gap: 0.625rem; cursor: pointer; }
        .user-avatar { width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #38bdf8, #818cf8); display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700; color: white; }
        .user-name { font-size: 0.8125rem; font-weight: 500; }
        .btn-logout { background: rgba(239, 68, 68, 0.15); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.3); padding: 0.4rem 0.875rem; border-radius: 6px; font-size: 0.75rem; cursor: pointer; transition: all 0.2s; }
        .btn-logout:hover { background: rgba(239, 68, 68, 0.25); }
        
        /* Container */
        .container { max-width: 1400px; margin: 0 auto; padding: 2rem; }
        .save-toast { position: fixed; top: 5.5rem; right: 1.5rem; z-index: 200; display: flex; align-items: center; gap: 0.65rem; max-width: 380px; padding: 0.85rem 1rem; border: 1px solid #4ade80; border-radius: 10px; background: rgba(6, 78, 59, 0.96); color: #bbf7d0; box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3); font-size: 0.875rem; animation: toast-in 0.25s ease-out; }
        .save-toast-error { border-color: #f87171; background: rgba(127, 29, 29, 0.96); color: #fecaca; }
        .save-toast-icon { display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; flex: 0 0 22px; border-radius: 50%; background: #4ade80; color: #052e16; font-weight: 800; }
        .save-toast-error .save-toast-icon { background: #f87171; color: #450a0a; }
        @keyframes toast-in { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
        @media (max-width: 768px) { .save-toast { top: 4.5rem; left: 1rem; right: 1rem; max-width: none; } }
        .page-title { font-size: 1.5rem; font-weight: 700; color: #fff; margin-bottom: 0.25rem; }
        .page-subtitle { color: rgba(255,255,255,0.5); font-size: 0.875rem; margin-bottom: 2rem; }
        
        /* Grid */
        .dashboard-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem; margin-bottom: 1.5rem; }
        .dashboard-grid-2 { display: grid; grid-template-columns: 2fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem; }
        .dashboard-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.25rem; }
        
        /* Cards */
        .card { background: rgba(255,255,255,0.04); backdrop-filter: blur(12px); border-radius: 16px; border: 1px solid rgba(255,255,255,0.08); padding: 1.5rem; position: relative; transition: all 0.3s; }
        .card:hover { border-color: rgba(255,255,255,0.15); background: rgba(255,255,255,0.06); }
        .card-close { position: absolute; top: 1rem; right: 1rem; color: rgba(255,255,255,0.3); cursor: pointer; font-size: 0.875rem; }
        .card-close:hover { color: rgba(255,255,255,0.7); }
        
        /* Stat Cards */
        .stat-label { font-size: 0.75rem; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem; }
        .stat-value { font-size: 2rem; font-weight: 800; color: #fff; letter-spacing: -1px; }
        .stat-value-sm { font-size: 1.5rem; }
        .stat-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; }
        .stat-icon.cyan { background: rgba(56, 189, 248, 0.15); color: #38bdf8; }
        .stat-icon.green { background: rgba(34, 197, 94, 0.15); color: #4ade80; }
        .stat-icon.purple { background: rgba(168, 85, 247, 0.15); color: #c084fc; }
        .stat-icon.orange { background: rgba(251, 146, 60, 0.15); color: #fb923c; }
        .stat-tag { display: inline-block; padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600; margin-top: 0.75rem; }
        .stat-tag.optimal { background: rgba(34, 197, 94, 0.15); color: #4ade80; }
        .stat-tag.up { background: rgba(34, 197, 94, 0.15); color: #4ade80; }
        .stat-tag.down { background: rgba(251, 146, 60, 0.15); color: #fb923c; }
        
        /* Progress Ring */
        .progress-ring { position: relative; width: 100px; height: 100px; margin: 0 auto 1rem; }
        .progress-ring svg { transform: rotate(-90deg); }
        .progress-ring circle { fill: none; stroke-width: 8; }
        .progress-ring .bg { stroke: rgba(255,255,255,0.08); }
        .progress-ring .progress { stroke: #4ade80; stroke-linecap: round; transition: stroke-dashoffset 1s ease; }
        .progress-ring .value { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 1.5rem; font-weight: 800; color: #fff; }
        
        /* Funnel */
        .funnel-row { display: flex; align-items: center; gap: 0.75rem; padding: 0.625rem 0; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .funnel-row:last-child { border-bottom: none; }
        .funnel-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.875rem; }
        .funnel-icon.cyan { background: rgba(56, 189, 248, 0.15); color: #38bdf8; }
        .funnel-icon.green { background: rgba(34, 197, 94, 0.15); color: #4ade80; }
        .funnel-text { flex: 1; font-size: 0.875rem; }
        .funnel-value { font-weight: 700; color: #fff; }
        .funnel-arrow { font-size: 0.625rem; }
        .funnel-arrow.up { color: #4ade80; }
        .funnel-arrow.down { color: #fb923c; }
        
        /* Milestones */
        .milestone { display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.75rem 0; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .milestone:last-child { border-bottom: none; }
        .milestone-check { width: 20px; height: 20px; border-radius: 4px; border: 2px solid rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px; }
        .milestone-check.done { background: #4ade80; border-color: #4ade80; }
        .milestone-check.done::after { content: '✓'; color: #fff; font-size: 0.75rem; font-weight: 700; }
        .milestone-text { font-size: 0.8125rem; line-height: 1.4; }
        .milestone-text.done { text-decoration: line-through; opacity: 0.6; }
        .milestone-meta { font-size: 0.6875rem; color: rgba(255,255,255,0.4); margin-top: 0.25rem; }
        
        /* Gauge */
        .gauge-container { text-align: center; }
        .gauge { position: relative; width: 120px; height: 60px; margin: 0 auto 1rem; overflow: hidden; }
        .gauge svg { transform: rotate(180deg); }
        .gauge-bg { fill: none; stroke: rgba(255,255,255,0.08); stroke-width: 12; }
        .gauge-fill { fill: none; stroke: #38bdf8; stroke-width: 12; stroke-linecap: round; transition: stroke-dasharray 1s ease; }
        .gauge-value { position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); font-size: 1.5rem; font-weight: 800; color: #fff; }
        .gauge-legend { display: flex; justify-content: center; gap: 1rem; margin-top: 0.75rem; }
        .gauge-legend-item { display: flex; align-items: center; gap: 0.375rem; font-size: 0.75rem; color: rgba(255,255,255,0.6); }
        .gauge-legend-dot { width: 8px; height: 8px; border-radius: 50%; }
        .gauge-legend-dot.cyan { background: #38bdf8; }
        .gauge-legend-dot.green { background: #4ade80; }
        
        /* Map */
        .map-container { position: relative; height: 180px; background: rgba(255,255,255,0.02); border-radius: 12px; overflow: hidden; margin-top: 1rem; }
        .map-container svg { width: 100%; height: 100%; opacity: 0.3; }
        .map-pin { position: absolute; width: 10px; height: 10px; background: #4ade80; border-radius: 50%; box-shadow: 0 0 10px rgba(74, 222, 128, 0.5); }
        .map-pin::after { content: attr(data-city); position: absolute; left: 14px; top: -4px; font-size: 0.6875rem; color: rgba(255,255,255,0.7); white-space: nowrap; }
        .map-pin.top::before { content: attr(data-users); position: absolute; right: 14px; top: -4px; font-size: 0.6875rem; color: #4ade80; font-weight: 600; }
        
        /* Chart */
        .chart-container { position: relative; height: 200px; margin-top: 1rem; }
        .chart-container canvas { width: 100% !important; height: 100% !important; }
        
        /* Responsive */
        @media (max-width: 1200px) {
            .dashboard-grid { grid-template-columns: repeat(2, 1fr); }
            .dashboard-grid-2 { grid-template-columns: 1fr; }
            .dashboard-grid-3 { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .dashboard-grid { grid-template-columns: 1fr; }
            .navbar-nav { display: none; }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div style="display:flex;align-items:center;gap:2rem;">
            <a href="{{ route('admin.dashboard') }}" class="navbar-brand">SIGMA<span>.</span></a>
            <div class="navbar-nav">
                <div class="nav-dropdown">
                    <button type="button" class="nav-dropdown-toggle dropdown-toggle {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">General</button>
                    <div class="nav-dropdown-menu dropdown-menu">
                        <a href="{{ route('admin.settings.hero') }}" class="dropdown-item {{ request()->routeIs('admin.settings.hero*') ? 'active' : '' }}">Hero</a>
                        <a href="{{ route('admin.settings.backgrounds', ['section' => 'hero']) }}" class="dropdown-item {{ request()->routeIs('admin.settings.backgrounds*') ? 'active' : '' }}">Background</a>
                        <a href="{{ route('admin.settings.members') }}" class="dropdown-item {{ request()->routeIs('admin.settings.members*') ? 'active' : '' }}">Member</a>
                        <a href="{{ route('admin.settings.achievements') }}" class="dropdown-item {{ request()->routeIs('admin.settings.achievements*') ? 'active' : '' }}">Achievements</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="nav-right">
            <div class="notification-bell">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                <span class="notification-badge">12</span>
            </div>
            <div class="user-profile">
                <div class="user-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                <span class="user-name">Welcome, {{ auth()->user()->name }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="display:inline">
                @csrf
                <button type="submit" class="btn-logout">Log Out</button>
            </form>
        </div>
    </nav>
        @if(session('success'))
            <div class="save-toast" role="status" aria-live="polite">
                <span class="save-toast-icon">✓</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="save-toast save-toast-error" role="alert">
                <span class="save-toast-icon">!</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="save-toast save-toast-error" role="alert">
                <span class="save-toast-icon">!</span>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <main class="container">
        @yield('content')
    </main>

    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        (function () {
            let currentMain = document.querySelector('main.container');

            if (!currentMain) {
                return;
            }

            function removeToasts() {
                document.querySelectorAll('.save-toast').forEach(function (toast) {
                    toast.remove();
                });
            }

            function showToast(message, type) {
                removeToasts();

                const toast = document.createElement('div');
                toast.className = 'save-toast' + (type === 'error' ? ' save-toast-error' : '');
                toast.dataset.ajaxToast = 'true';
                toast.setAttribute('role', type === 'error' ? 'alert' : 'status');

                const icon = document.createElement('span');
                icon.className = 'save-toast-icon';
                icon.textContent = type === 'error' ? '!' : '✓';

                const text = document.createElement('span');
                text.textContent = message;

                toast.appendChild(icon);
                toast.appendChild(text);
                document.body.appendChild(toast);
                window.setTimeout(removeToasts, 4500);
            }

            function responseMessage(document, fallback) {
                const toast = document.querySelector('.save-toast');
                if (!toast) {
                    return fallback;
                }

                const text = toast.querySelector('span:last-child');
                return {
                    message: (text ? text.textContent : toast.textContent).trim() || fallback,
                    error: toast.classList.contains('save-toast-error'),
                };
            }

            document.addEventListener('submit', async function (event) {
                const form = event.target;

                if (!(form instanceof HTMLFormElement) || !currentMain.contains(form)) {
                    return;
                }

                if (form.dataset.ajaxBusy === 'true') {
                    event.preventDefault();
                    return;
                }

                event.preventDefault();
                form.dataset.ajaxBusy = 'true';

                const submitButtons = Array.from(form.querySelectorAll('button[type="submit"], input[type="submit"]'));
                submitButtons.forEach(function (button) {
                    button.disabled = true;
                    button.dataset.originalText = button.value || button.textContent;
                    if (button.tagName === 'BUTTON') {
                        button.textContent = 'Menyimpan...';
                    } else {
                        button.value = 'Menyimpan...';
                    }
                });

                try {
                    const response = await fetch(form.action, {
                        method: form.method.toUpperCase() || 'POST',
                        body: new FormData(form),
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'text/html, application/xhtml+xml',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        redirect: 'follow',
                    });

                    const html = await response.text();
                    const parsed = new DOMParser().parseFromString(html, 'text/html');
                    const nextMain = parsed.querySelector('main.container');
                    const result = responseMessage(parsed, 'Perubahan berhasil disimpan.');

                    if (!nextMain) {
                        showToast('Sesi admin berakhir. Silakan login kembali.', 'error');
                        return;
                    }

                    currentMain.replaceWith(nextMain);
                    currentMain = nextMain;
                    window.history.replaceState({}, '', response.url || window.location.href);
                    document.title = parsed.title || document.title;
                    showToast(result.message, result.error || response.status >= 400 ? 'error' : 'success');
                } catch (error) {
                    showToast('Perubahan gagal disimpan. Periksa koneksi lalu coba lagi.', 'error');
                } finally {
                    form.dataset.ajaxBusy = 'false';
                    submitButtons.forEach(function (button) {
                        button.disabled = false;
                        if (button.tagName === 'BUTTON') {
                            button.textContent = button.dataset.originalText || 'Submit';
                        } else {
                            button.value = button.dataset.originalText || 'Submit';
                        }
                    });
                }
            });
        })();
    </script>
</body>
</html>
