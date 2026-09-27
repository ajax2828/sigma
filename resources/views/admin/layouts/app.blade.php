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

        /* Tema: slate gelap + aksen emas. Semua warna lewat variabel,
           jadi ganti satu nilai untuk re-theme seluruh admin. */
        :root {
            --bg: #0e1116;
            --surface: #151a21;
            --surface-2: #1b212a;
            --border: rgba(255, 255, 255, 0.09);
            --border-strong: rgba(255, 255, 255, 0.16);
            --text: #e6e9ee;
            --text-muted: #9aa4b2;
            --text-dim: #6b7684;
            --accent: #d4a24c;
            --accent-soft: rgba(212, 162, 76, 0.14);
            --accent-text: #e8c07a;
            --success: #5fb98a;
            --success-soft: rgba(95, 185, 138, 0.15);
            --danger: #e07a7a;
            --danger-soft: rgba(224, 122, 122, 0.15);
            --info: #7ba3d4;
            --accent-deep: #b4763a;
            --section-gap: 3px;
            --radius: 12px;
            --radius-sm: 8px;
            --ease: cubic-bezier(0.4, 0, 0.2, 1);
            --t-fast: 140ms var(--ease);
            --t-base: 220ms var(--ease);
        }

        html { -webkit-text-size-adjust: 100%; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); min-height: 100vh; color: var(--text); -webkit-font-smoothing: antialiased; }

        /* Fokus keyboard harus terlihat di seluruh tema gelap ini. */
        :focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; border-radius: 4px; }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: 0.01ms !important; transition-duration: 0.01ms !important; scroll-behavior: auto !important; }
        }
        
        /* Navbar */
        .navbar { background: rgba(14, 17, 22, 0.88); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border-bottom: 1px solid var(--border); padding: 0.875rem 2rem; display: flex; justify-content: space-between; align-items: center; gap: 1.5rem; position: sticky; top: 0; z-index: 100; }
        .navbar-brand { font-size: 1.25rem; font-weight: 700; color: var(--text); text-decoration: none; letter-spacing: -0.2px; transition: color var(--t-fast); }
        .navbar-brand span { color: var(--accent); }
        .navbar-brand:hover { color: #fff; }
        .navbar-nav { display: flex; gap: 0.25rem; align-items: center; }
        .navbar-nav a { color: var(--text-muted); text-decoration: none; font-size: 0.8125rem; font-weight: 500; padding: 0.5rem 0.875rem; border-radius: var(--radius-sm); transition: color var(--t-fast), background var(--t-fast); }
        .navbar-nav a:hover { color: var(--text); background: rgba(255, 255, 255, 0.05); }
        .navbar-nav a.active { color: var(--accent-text); background: var(--accent-soft); }
        .nav-dropdown { position: relative; }
        .nav-dropdown > summary { list-style: none; cursor: pointer; color: var(--text-muted); font-size: 0.8125rem; font-weight: 500; padding: 0.5rem 0.875rem; border-radius: var(--radius-sm); user-select: none; transition: color var(--t-fast), background var(--t-fast); }
        .nav-dropdown > summary::-webkit-details-marker { display: none; }
        .nav-dropdown > summary::after { content: '\25BE'; display: inline-block; margin-left: 0.35rem; font-size: 0.7rem; transition: transform var(--t-fast); }
        .nav-dropdown[open] > summary::after { transform: rotate(180deg); }
        .nav-dropdown[open] > summary, .nav-dropdown > summary:hover { color: var(--text); background: rgba(255, 255, 255, 0.05); }
        .nav-dropdown > summary.active { color: var(--accent-text); background: var(--accent-soft); }
        .nav-dropdown-menu { position: absolute; top: calc(100% + 0.5rem); left: 0; min-width: 180px; padding: 0.375rem; background: var(--surface-2); border: 1px solid var(--border-strong); border-radius: var(--radius); box-shadow: 0 16px 40px rgba(0, 0, 0, 0.45); }
        .nav-dropdown-menu a { display: block; color: var(--text-muted); text-decoration: none; font-size: 0.8125rem; font-weight: 500; padding: 0.55rem 0.7rem; white-space: nowrap; border-radius: 6px; transition: color var(--t-fast), background var(--t-fast); }
        .nav-dropdown-menu a:hover { color: var(--text); background: rgba(255, 255, 255, 0.06); }
        .nav-dropdown-menu a.active { color: var(--accent-text); background: var(--accent-soft); }
        .nav-toggle { display: none; background: none; border: 1px solid var(--border); border-radius: var(--radius-sm); color: var(--text); padding: 0.4rem 0.6rem; cursor: pointer; transition: background var(--t-fast), border-color var(--t-fast); }
        .nav-toggle:hover { background: rgba(255, 255, 255, 0.06); border-color: var(--border-strong); }
        .nav-left { display: flex; align-items: center; gap: 2rem; min-width: 0; }
        .nav-right { display: flex; align-items: center; gap: 1.25rem; margin-left: auto; }
        .nav-logout-form { display: inline; }
        .notification-bell { position: relative; cursor: pointer; color: var(--text-muted); display: flex; transition: color var(--t-fast); }
        .notification-bell:hover { color: var(--text); }
        .notification-badge { position: absolute; top: -6px; right: -6px; background: var(--danger); color: #2b0d0d; font-size: 0.625rem; font-weight: 700; padding: 0.125rem 0.375rem; border-radius: 9999px; min-width: 18px; text-align: center; }
        .user-profile { display: flex; align-items: center; gap: 0.625rem; cursor: pointer; }
        .user-avatar { width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, var(--accent), var(--accent-deep)); display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700; color: #1a1207; flex-shrink: 0; }
        .user-name { font-size: 0.8125rem; font-weight: 500; }
        .btn-logout { background: var(--danger-soft); color: var(--danger); border: 1px solid rgba(224, 122, 122, 0.32); padding: 0.4rem 0.875rem; border-radius: var(--radius-sm); font-family: inherit; font-size: 0.75rem; cursor: pointer; transition: background var(--t-fast), color var(--t-fast), border-color var(--t-fast); }
        .btn-logout:hover { background: rgba(224, 122, 122, 0.24); border-color: rgba(224, 122, 122, 0.5); }
        
        /* Container */
        .container { max-width: 1400px; margin: 0 auto; padding: 2rem; }
        .save-toast { position: fixed; top: 5.5rem; right: 1.5rem; z-index: 200; display: flex; align-items: center; gap: 0.65rem; max-width: 380px; padding: 0.85rem 1rem; border: 1px solid rgba(95, 185, 138, 0.5); border-radius: var(--radius); background: rgba(20, 42, 32, 0.97); color: #a7e3c4; box-shadow: 0 16px 40px rgba(0, 0, 0, 0.4); font-size: 0.875rem; animation: toast-in 0.25s var(--ease); }
        .save-toast-error { border-color: rgba(224, 122, 122, 0.55); background: rgba(48, 22, 22, 0.97); color: #f3b5b5; }
        .save-toast-icon { display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; flex: 0 0 22px; border-radius: 50%; background: var(--success); color: #06251a; font-weight: 800; }
        .save-toast-error .save-toast-icon { background: var(--danger); color: #2b0d0d; }
        @keyframes toast-in { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
        @media (max-width: 768px) { .save-toast { top: 4.75rem; left: 1rem; right: 1rem; max-width: none; } }
        .page-title { font-size: 1.5rem; font-weight: 700; color: #fff; margin-bottom: 0.25rem; letter-spacing: -0.02em; }
        .page-subtitle { color: var(--text-muted); font-size: 0.875rem; margin-bottom: 2rem; }
        
        /* Grid */
        .dashboard-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem; margin-bottom: 1.5rem; }
        .dashboard-grid-2 { display: grid; grid-template-columns: 2fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem; }
        .dashboard-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.25rem; }
        
        /* Cards */
        .card { background: var(--surface); border-radius: var(--radius); border: 1px solid var(--border); padding: 1.5rem; position: relative; transition: border-color var(--t-base), background var(--t-base), transform var(--t-base); }
        .card:hover { border-color: var(--border-strong); background: var(--surface-2); }
        .card-close { position: absolute; top: 1rem; right: 1rem; color: var(--text-dim); cursor: pointer; font-size: 0.875rem; transition: color var(--t-fast); }
        .card-close:hover { color: var(--text); }
        
        /* Stat Cards */
        .stat-label { font-size: 0.7rem; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.07em; font-weight: 600; margin-bottom: 0.75rem; }
        .stat-value { font-size: 2rem; font-weight: 700; color: #fff; letter-spacing: -0.03em; }
        .stat-value-sm { font-size: 1.5rem; }
        .stat-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; }
        .stat-icon { border-radius: 10px; }
        .stat-icon.cyan { background: var(--accent-soft); color: var(--accent-text); }
        .stat-icon.green { background: var(--success-soft); color: var(--success); }
        .stat-icon.purple { background: rgba(123, 163, 212, 0.15); color: var(--info); }
        .stat-icon.orange { background: var(--accent-soft); color: var(--accent-text); }
        .stat-tag { display: inline-block; padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600; margin-top: 0.75rem; }
        .stat-tag.optimal { background: var(--success-soft); color: var(--success); }
        .stat-tag.up { background: var(--success-soft); color: var(--success); }
        .stat-tag.down { background: var(--accent-soft); color: var(--accent-text); }
        
        /* Progress Ring */
        .progress-ring { position: relative; width: 100px; height: 100px; margin: 0 auto 1rem; }
        .progress-ring svg { transform: rotate(-90deg); }
        .progress-ring circle { fill: none; stroke-width: 8; }
        .progress-ring .bg { stroke: rgba(255, 255, 255, 0.07); }
        .progress-ring .progress { stroke: var(--success); stroke-linecap: round; transition: stroke-dashoffset 1s var(--ease); }
        .progress-ring .value { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 1.5rem; font-weight: 800; color: #fff; }
        
        /* Funnel */
        .funnel-row { display: flex; align-items: center; gap: 0.75rem; padding: 0.625rem 0; border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
        .funnel-row:last-child { border-bottom: none; }
        .funnel-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.875rem; }
        .funnel-icon.cyan { background: var(--accent-soft); color: var(--accent-text); }
        .funnel-icon.green { background: var(--success-soft); color: var(--success); }
        .funnel-text { flex: 1; font-size: 0.875rem; }
        .funnel-value { font-weight: 700; color: #fff; }
        .funnel-arrow { font-size: 0.625rem; }
        .funnel-arrow.up { color: var(--success); }
        .funnel-arrow.down { color: var(--accent-text); }
        
        /* Milestones */
        .milestone { display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.75rem 0; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .milestone:last-child { border-bottom: none; }
        .milestone-check { width: 20px; height: 20px; border-radius: 5px; border: 2px solid rgba(255, 255, 255, 0.2); display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px; }
        .milestone-check.done { background: var(--success); border-color: var(--success); }
        .milestone-check.done::after { content: '✓'; color: #fff; font-size: 0.75rem; font-weight: 700; }
        .milestone-text { font-size: 0.8125rem; line-height: 1.4; }
        .milestone-text.done { text-decoration: line-through; opacity: 0.6; }
        .milestone-meta { font-size: 0.6875rem; color: var(--text-dim); margin-top: 0.25rem; }
        
        /* Gauge */
        .gauge-container { text-align: center; }
        .gauge { position: relative; width: 120px; height: 60px; margin: 0 auto 1rem; overflow: hidden; }
        .gauge svg { transform: rotate(180deg); }
        .gauge-bg { fill: none; stroke: rgba(255, 255, 255, 0.07); stroke-width: 12; }
        .gauge-fill { fill: none; stroke: var(--accent); stroke-width: 12; stroke-linecap: round; transition: stroke-dasharray 1s var(--ease); }
        .gauge-value { position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); font-size: 1.5rem; font-weight: 800; color: #fff; }
        .gauge-legend { display: flex; justify-content: center; gap: 1rem; margin-top: 0.75rem; }
        .gauge-legend-item { display: flex; align-items: center; gap: 0.375rem; font-size: 0.75rem; color: var(--text-muted); }
        .gauge-legend-dot { width: 8px; height: 8px; border-radius: 50%; }
        .gauge-legend-dot.cyan { background: var(--accent); }
        .gauge-legend-dot.green { background: var(--success); }
        
        /* Map */
        .map-container { position: relative; height: 180px; background: rgba(255, 255, 255, 0.02); border-radius: var(--radius); overflow: hidden; margin-top: 1rem; }
        .map-container svg { width: 100%; height: 100%; opacity: 0.3; }
        .map-pin { position: absolute; width: 10px; height: 10px; background: var(--success); border-radius: 50%; box-shadow: 0 0 10px rgba(95, 185, 138, 0.5); }
        .map-pin::after { content: attr(data-city); position: absolute; left: 14px; top: -4px; font-size: 0.6875rem; color: var(--text-muted); white-space: nowrap; }
        .map-pin.top::before { content: attr(data-users); position: absolute; right: 14px; top: -4px; font-size: 0.6875rem; color: var(--success); font-weight: 600; }
        
        /* Chart */
        .chart-container { position: relative; height: 200px; margin-top: 1rem; }
        .chart-container canvas { width: 100% !important; height: 100% !important; }
        
        /* Components (dipakai halaman Posts) */
        .page-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
        .page-header .page-subtitle { margin-bottom: 0; }
        .btn { display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.55rem 1.1rem; border: 1px solid transparent; border-radius: var(--radius-sm); font-family: inherit; font-size: 0.8125rem; font-weight: 600; line-height: 1.2; text-decoration: none; cursor: pointer; transition: background var(--t-fast), border-color var(--t-fast), color var(--t-fast), transform var(--t-fast); }
        .btn:active:not(:disabled) { transform: translateY(1px); }
        .btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .btn-primary { background: var(--accent); color: #1a1207; }
        .btn-primary:hover:not(:disabled) { background: #e0b060; }
        .btn-secondary { background: rgba(255, 255, 255, 0.06); color: var(--text); border-color: var(--border-strong); }
        .btn-secondary:hover:not(:disabled) { background: rgba(255, 255, 255, 0.11); color: #fff; }
        .btn-danger { background: var(--danger-soft); color: var(--danger); border-color: rgba(224, 122, 122, 0.35); }
        .btn-danger:hover:not(:disabled) { background: rgba(224, 122, 122, 0.26); color: #f7c9c9; }
        .alert { padding: 0.75rem 1rem; border-radius: var(--radius-sm); font-size: 0.8125rem; }
        .alert-success { background: var(--success-soft); border: 1px solid rgba(95, 185, 138, 0.45); color: #a7e3c4; }
        .alert-danger { background: var(--danger-soft); border: 1px solid rgba(224, 122, 122, 0.45); color: #f3b5b5; }
        .badge { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; }
        .badge-draft { background: var(--accent-soft); color: var(--accent-text); }
        .badge-published { background: var(--success-soft); color: var(--success); }
        .badge-archived { background: rgba(255, 255, 255, 0.07); color: var(--text-muted); }
        .badge-count { display: inline-block; padding: 0.125rem 0.55rem; border-radius: 9999px; background: var(--accent-soft); color: var(--accent-text); font-size: 0.8125rem; font-weight: 600; vertical-align: middle; }
        .form-group { margin-bottom: 1.125rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-size: 0.75rem; font-weight: 500; color: var(--text-muted); }
        .form-group input[type="text"], .form-group textarea, .form-group select { width: 100%; padding: 0.7rem 0.875rem; background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-strong); border-radius: var(--radius-sm); color: #fff; font-family: inherit; font-size: 0.875rem; transition: border-color var(--t-fast), background var(--t-fast); }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus { outline: none; border-color: var(--accent); background: rgba(255, 255, 255, 0.06); }
        .form-group textarea { resize: vertical; min-height: 140px; line-height: 1.6; }
        .form-group input::placeholder, .form-group textarea::placeholder { color: var(--text-dim); }
        .field-hint { margin: 0.5rem 0 0; font-size: 0.75rem; color: var(--text-dim); }
        .form-group .alert { margin-top: 0.75rem; }
        .form-actions { display: flex; gap: 0.75rem; }
        .table { width: 100%; border-collapse: collapse; }
        .table th { padding: 0 1.25rem 0.75rem; font-size: 0.6875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: var(--text-dim); text-align: left; border-bottom: 1px solid var(--border-strong); }
        .table td { padding: 0.875rem 1.25rem; font-size: 0.875rem; color: var(--text-muted); border-bottom: 1px solid rgba(255, 255, 255, 0.05); vertical-align: middle; }
        .table tbody tr { transition: background var(--t-fast); }
        .table tbody tr:hover { background: rgba(255, 255, 255, 0.025); }
        .table tbody tr:last-child td { border-bottom: none; }
        .empty-state { padding: 3.5rem 1.5rem; text-align: center; }
        .empty-state p { color: var(--text-muted); font-size: 0.875rem; margin-bottom: 1.25rem; }
        .post-title { color: #fff; font-weight: 600; text-decoration: none; transition: color var(--t-fast); }
        .post-title:hover { color: var(--accent-text); }
        .post-excerpt { color: var(--text-dim); font-size: 0.75rem; margin-top: 0.25rem; }
        .actions { display: flex; gap: 0.5rem; justify-content: flex-end; }
        .actions form { display: inline; }
        .toolbar { display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap; padding: 1.25rem; border-bottom: 1px solid var(--border); }
        .toolbar input[type="search"], .toolbar select { padding: 0.55rem 0.875rem; background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-strong); border-radius: var(--radius-sm); color: #fff; font-family: inherit; font-size: 0.8125rem; transition: border-color var(--t-fast), background var(--t-fast); }
        .toolbar input[type="search"] { flex: 1; min-width: 180px; }
        .toolbar input:focus, .toolbar select:focus { outline: none; border-color: var(--accent); background: rgba(255, 255, 255, 0.06); }
        .post-body { line-height: 1.8; color: var(--text); font-size: 0.9375rem; max-width: 720px; white-space: pre-wrap; }
        .pagination { display: flex; gap: 0.375rem; list-style: none; }
        .pagination a, .pagination span { display: block; padding: 0.4rem 0.7rem; border-radius: var(--radius-sm); font-size: 0.8125rem; text-decoration: none; color: var(--text-muted); background: rgba(255, 255, 255, 0.04); border: 1px solid transparent; transition: background var(--t-fast), color var(--t-fast), border-color var(--t-fast); }
        .pagination a:hover { background: rgba(255, 255, 255, 0.08); color: #fff; }
        .pagination .active span { background: var(--accent-soft); color: var(--accent-text); font-weight: 600; border-color: rgba(212, 162, 76, 0.35); }
        .pagination .disabled span { opacity: 0.4; }


        /* Utilitas hasil sedimentasi inline style (dulu 235 style inline,
           sekarang tema bisa diubah dari satu tempat). */
        .mb-lg { margin-bottom: 1.5rem; }
        .mb-md { margin-bottom: 1rem; }
        .mt-md { margin-top: 1rem; }
        .text-right { text-align: right; }
        .flush { padding: 0; overflow: hidden; }
        .f-white { color: #fff; }
        .f-muted { color: var(--text-muted); }
        .f-muted-sm { color: var(--text-dim); font-size: 0.75rem; }
        .f-muted-md { color: var(--text-muted); font-size: 0.875rem; }
        .f-cover { width: 100%; height: 100%; object-fit: cover; }
        .f-label { display: block; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.5rem; }
        .f-label-sm { font-size: 0.6875rem; margin-bottom: 0.375rem; }
        .f-hint { color: var(--text-muted); font-size: 0.8125rem; margin: 0 0 1.25rem; }
        .f-hint-sm { color: var(--text-dim); font-size: 0.6875rem; margin: 0.35rem 0 0; }
        .f-input { width: 100%; padding: 0.625rem; background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-strong); border-radius: var(--radius-sm); color: #fff; font-family: inherit; font-size: 0.875rem; transition: border-color var(--t-fast), background var(--t-fast); }
        .f-input-sm { padding: 0.5rem; font-size: 0.8125rem; }
        .f-file { padding: 0.4rem; color: var(--text-muted); font-size: 0.75rem; }
        .f-input:focus { outline: none; border-color: var(--accent); background: rgba(255, 255, 255, 0.06); }
        .f-error { background: var(--danger-soft); border: 1px solid rgba(224, 122, 122, 0.4); color: #f3b5b5; padding: 0.875rem 1rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; font-size: 0.875rem; }
        .f-error-list { margin: 0; padding-left: 1.25rem; }
        .f-submit { font-family: inherit; font-size: 0.875rem; border-radius: var(--radius-sm); cursor: pointer; transition: background var(--t-fast), color var(--t-fast), border-color var(--t-fast), transform var(--t-fast); }
        .f-submit:active { transform: translateY(1px); }
        .f-submit-primary { background: var(--accent); color: #1a1207; border: 0; }
        .f-submit-primary:hover { background: #e0b060; }
        .f-submit-success { background: var(--success); color: #06251a; border: 0; }
        .f-submit-success:hover { background: #6fc99a; }
        .f-submit-danger { background: var(--danger-soft); color: var(--danger); border: 1px solid rgba(224, 122, 122, 0.35); }
        .f-submit-danger:hover { background: rgba(224, 122, 122, 0.26); color: #f7c9c9; }
        .f-submit-plain { background: rgba(255, 255, 255, 0.06); color: var(--text); border: 1px solid var(--border-strong); }
        .f-submit-plain:hover { background: rgba(255, 255, 255, 0.11); color: #fff; }
        .f-grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
        .f-grid-2.mb-md { margin-bottom: 1rem; }
        .f-grid-3 { display: grid; grid-template-columns: 60px 1fr 140px; gap: 0.75rem; align-items: start; }
        .f-grid-4 { display: grid; grid-template-columns: 100px 1fr 1fr 1fr; gap: 0.75rem; }
        .f-grid-5 { display: grid; grid-template-columns: repeat(5, 1fr); gap: 1rem; }
        .f-grid-150 { display: grid; grid-template-columns: 150px 1fr; gap: 1.25rem; align-items: start; }
        .f-span-all { grid-column: 1 / -1; }
        .f-span-rest { grid-column: 2 / -1; }
        .f-row { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1rem; }
        .f-strong { font-size: 1rem; font-weight: 700; color: #fff; }
        .f-h2 { font-size: 1.125rem; color: #fff; margin-bottom: 1rem; }
        .f-subhead { margin-bottom: 1rem; font-size: 1rem; }
        .f-preview { width: 100%; max-width: 420px; height: 180px; overflow: hidden; border: 1px solid var(--border-strong); border-radius: var(--radius-sm); }
        .f-preview-wide { max-width: 520px; margin-bottom: 0.75rem; }

        .f-history-row { display: flex; justify-content: space-between; gap: 1rem; padding: 0.75rem 0; border-bottom: 1px solid var(--border); font-size: 0.8125rem; }
        .f-history-tag { margin-left: 0.5rem; color: var(--text-muted); }
        .f-history-sub { margin-left: 0.5rem; color: var(--text-dim); }
        .f-history-date { color: var(--text-dim); white-space: nowrap; }
        .f-file-hint { margin-top: 0.5rem; font-size: 0.7rem; }
        /* Input warna native butuh ukuran penuh; styling inline lama tidak berlaku untuknya. */
        .f-color { width: 100%; padding: 0.25rem; background: rgba(255, 255, 255, 0.05); border: 1px solid var(--border-strong); border-radius: var(--radius-sm); cursor: pointer; }
        /* Tinggi baris input seragam: color, angka, dan teks semua 42px. */
        .f-h-md { height: 42px; }
        .f-where { color: var(--text-muted); font-size: 0.75rem; margin: -0.5rem 0 1.25rem; }
        .f-swatch-row { display: flex; align-items: center; gap: 0.625rem; margin-bottom: 1.25rem; }
        .f-swatch-label { font-size: 0.6875rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--text-dim); white-space: nowrap; }
        /* Tinggi sama dengan input lain (f-h-md) supaya satu baris rata. */
        .f-swatch { flex: 1; height: 42px; border: 1px solid var(--border-strong); border-radius: var(--radius-sm); }
        .f-input-xs { font-size: 0.7rem; }
        .f-thumb { width: 100px; height: 100px; object-fit: cover; border-radius: var(--radius); margin-bottom: 0.5rem; }
        .mt-xl { margin-top: 2rem; }


        /* ===== SAVE BUTTON =====
           Satu komponen untuk semua tombol simpan. .btn-login lama tidak
           pernah didefinisikan di layout admin, jadi tombolnya polos. */
        .btn-save { display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.7rem 1.5rem; border: 1px solid transparent; border-radius: var(--radius-sm); background: linear-gradient(180deg, #e0b060, var(--accent)); color: #1a1207; font-family: inherit; font-size: 0.8125rem; font-weight: 700; letter-spacing: 0.01em; cursor: pointer; box-shadow: 0 1px 0 rgba(255, 255, 255, 0.25) inset, 0 6px 16px rgba(212, 162, 76, 0.22); transition: transform var(--t-fast), box-shadow var(--t-fast), filter var(--t-fast), background var(--t-fast); }
        .btn-save:hover:not(:disabled) { filter: brightness(1.08); box-shadow: 0 1px 0 rgba(255, 255, 255, 0.25) inset, 0 10px 24px rgba(212, 162, 76, 0.32); transform: translateY(-1px); }
        .btn-save:active:not(:disabled) { transform: translateY(0); box-shadow: 0 1px 0 rgba(255, 255, 255, 0.2) inset, 0 3px 8px rgba(212, 162, 76, 0.22); }
        .btn-save:disabled { opacity: 0.6; cursor: progress; }
        .btn-save-icon { width: 16px; height: 16px; flex: 0 0 16px; }
        /* Tiap tombol simpan menampilkan spinner saat request berjalan. */
        .btn-save.is-saving .btn-save-icon { animation: save-spin 0.7s linear infinite; }
        .btn-save .btn-save-label { min-width: 6.5ch; }
        @keyframes save-spin { to { transform: rotate(360deg); } }
        .btn-save.is-success { background: linear-gradient(180deg, #6fc99a, var(--success)); box-shadow: 0 1px 0 rgba(255, 255, 255, 0.25) inset, 0 6px 16px rgba(95, 185, 138, 0.28); }
        .btn-save-success { background: linear-gradient(180deg, #6fc99a, var(--success)); color: #06251a; box-shadow: 0 1px 0 rgba(255, 255, 255, 0.22) inset, 0 6px 16px rgba(95, 185, 138, 0.22); }
        .btn-save-success:hover:not(:disabled) { box-shadow: 0 1px 0 rgba(255, 255, 255, 0.22) inset, 0 10px 24px rgba(95, 185, 138, 0.3); transform: translateY(-1px); }

        /* Jarak antar item section: 3px. */
        .f-grid-2, .f-grid-3, .f-grid-4, .f-grid-5, .f-grid-150, .dashboard-grid, .dashboard-grid-2, .dashboard-grid-3 { gap: var(--section-gap); }
        .section { display: flex; flex-direction: column; gap: var(--section-gap); }

        /* Baris gradient (Background Settings): Start dan End sama lebar, angle
           sempit karena isinya 3 digit. .f-grid-3 bawaan memakai 60px 1fr 140px
           untuk thumbnail + teks, jadi bentuknya tidak bisa dipakai di sini.
           Ditulis setelah aturan gap di atas supaya override-nya menang. */
        .f-gradient-row { gap: 1rem; }
        @media (min-width: 769px) {
            .f-gradient-row { grid-template-columns: repeat(2, minmax(0, 1fr)) 160px; }
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .dashboard-grid { grid-template-columns: repeat(2, 1fr); }
            .dashboard-grid-2 { grid-template-columns: 1fr; }
            .dashboard-grid-3 { grid-template-columns: 1fr; }
            .f-grid-5 { grid-template-columns: repeat(3, 1fr); }
            .f-grid-4 { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 900px) {
            .navbar { padding: 0.75rem 1.25rem; }
            /* Nav disembunyikan total tanpa pengganti -> tidak ada jalan ke halaman lain. */
            .nav-toggle { display: block; }
            .navbar-nav { display: none; position: absolute; top: 100%; left: 0; right: 0; flex-direction: column; align-items: stretch; gap: 0; padding: 0.5rem 1.25rem 0.75rem; background: var(--surface); border-bottom: 1px solid var(--border-strong); box-shadow: 0 16px 32px rgba(0, 0, 0, 0.4); }
            .navbar-nav.open { display: flex; }
            .navbar-nav a, .nav-dropdown > summary { padding: 0.7rem 0.5rem; border-radius: var(--radius-sm); }
            .nav-dropdown-menu { position: static; min-width: 0; margin: 0.25rem 0 0.5rem 0.75rem; box-shadow: none; background: rgba(255, 255, 255, 0.03); }
            .nav-right .user-name { display: none; }
            .container { padding: 1.5rem 1.25rem; }
        }
        @media (max-width: 768px) {
            .dashboard-grid { grid-template-columns: 1fr; }
            .page-header { flex-direction: column; align-items: stretch; }
            .page-title { font-size: 1.25rem; }
            /* Kolom tabel disembunyikan tanpa memberi cara melihat isinya. */
            .table th:nth-child(3), .table td:nth-child(3), .table th:nth-child(4), .table td:nth-child(4) { display: none; }
            .actions { justify-content: flex-start; }
            .toolbar input[type="search"] { min-width: 100%; }
            .f-grid-2, .f-grid-3, .f-grid-150, .f-grid-4, .f-grid-5 { grid-template-columns: 1fr; }
            .f-span-all, .f-span-rest { grid-column: auto; }
            .f-row { flex-direction: column; align-items: stretch; }
            .form-actions { flex-wrap: wrap; }
        }
        @media (max-width: 480px) {
            .nav-right { gap: 0.625rem; }
            .notification-bell { display: none; }
            .save-toast { top: 4.5rem; }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-left">
            <a href="{{ route('admin.dashboard') }}" class="navbar-brand">SIGMA<span>.</span></a>
            <div class="navbar-nav" id="navbarNav">
                @php($inPostsDropdown = request()->routeIs('admin.posts.*', 'admin.settings.landing*', 'admin.settings.hero*', 'admin.settings.backgrounds*'))
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                <details class="nav-dropdown" {{ $inPostsDropdown ? 'open' : '' }}>
                    <summary class="{{ $inPostsDropdown ? 'active' : '' }}">Posts</summary>
                    <div class="nav-dropdown-menu">
                        <a href="{{ route('admin.posts.index') }}" class="{{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">All Posts</a>
                        <a href="{{ route('admin.settings.landing') }}" class="{{ request()->routeIs('admin.settings.landing*') ? 'active' : '' }}">Landing Page</a>
                        <a href="{{ route('admin.settings.hero') }}" class="{{ request()->routeIs('admin.settings.hero*') ? 'active' : '' }}">Hero</a>
                        <a href="{{ route('admin.settings.backgrounds', ['section' => 'hero']) }}" class="{{ request()->routeIs('admin.settings.backgrounds*') ? 'active' : '' }}">Background</a>
                    </div>
                </details>
                <a href="{{ route('admin.settings.members') }}" class="{{ request()->routeIs('admin.settings.members*') ? 'active' : '' }}">Member</a>
                <a href="{{ route('admin.settings.achievements') }}" class="{{ request()->routeIs('admin.settings.achievements*') ? 'active' : '' }}">Achievements</a>
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
            <form method="POST" action="{{ route('logout') }}" class="nav-logout-form">
                @csrf
                <button type="submit" class="btn-logout">Log Out</button>
            </form>
        </div>
        <button type="button" class="nav-toggle" id="navToggle" aria-label="Menu" aria-expanded="false" aria-controls="navbarNav">&#9776;</button>
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

                    if (button.classList.contains('btn-save')) {
                        button.classList.add('is-saving');
                        const label = button.querySelector('.btn-save-label');
                        if (label) {
                            label.dataset.originalLabel = label.textContent;
                            label.textContent = 'Menyimpan';
                        }
                    } else if (button.tagName === 'BUTTON') {
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
                        if (button.classList.contains('btn-save')) {
                            button.classList.remove('is-saving');
                            const label = button.querySelector('.btn-save-label');
                            if (label) {
                                label.textContent = label.dataset.originalLabel || label.textContent;
                            }
                        } else if (button.tagName === 'BUTTON') {
                            button.textContent = button.dataset.originalText || 'Submit';
                        } else {
                            button.value = button.dataset.originalText || 'Submit';
                        }
                    });
                }
            });
        })();

        // Navbar mobile: buka/tutup menu, tutup lagi setelah pilih link.
        const navToggle = document.getElementById('navToggle');
        const navbarNav = document.getElementById('navbarNav');
        if (navToggle && navbarNav) {
            const closeNav = function () {
                navbarNav.classList.remove('open');
                navToggle.setAttribute('aria-expanded', 'false');
            };

            navToggle.addEventListener('click', function () {
                const open = navbarNav.classList.toggle('open');
                navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            });
            navbarNav.addEventListener('click', function (event) {
                if (event.target.closest('a')) closeNav();
            });
            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') closeNav();
            });
        }

        // <details> tidak menutup sendiri: tutup saat klik di luar atau tekan Escape.
        function closeDropdowns(except) {
            document.querySelectorAll('details.nav-dropdown[open]').forEach(function (dropdown) {
                if (dropdown !== except) {
                    dropdown.removeAttribute('open');
                }
            });
        }

        document.addEventListener('click', function (event) {
            // closest hanya ada di Element; target bisa Document atau teks.
            const target = event.target instanceof Element ? event.target : null;
            closeDropdowns(target ? target.closest('details.nav-dropdown') : null);
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeDropdowns(null);
            }
        });
    </script>
</body>
</html>
