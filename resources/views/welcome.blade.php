<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGMA - Welcome</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #fff; overflow-x: hidden; }
        .bg-orb { position: fixed; border-radius: 50%; filter: blur(80px); opacity: 0.4; pointer-events: none; z-index: 0; animation: float 20s ease-in-out infinite; }
        .bg-orb.one { width: 600px; height: 600px; background: #667eea; top: -200px; left: -200px; }
        .bg-orb.two { width: 400px; height: 400px; background: #f0abfc; bottom: -100px; right: -100px; animation-delay: -5s; }
        .bg-orb.three { width: 300px; height: 300px; background: #7dd3fc; top: 50%; left: 50%; animation-delay: -10s; }
        @keyframes float { 0%, 100% { transform: translate(0, 0) scale(1); } 33% { transform: translate(30px, -30px) scale(1.05); } 66% { transform: translate(-20px, 20px) scale(0.95); } }
        .container { position: relative; z-index: 1; text-align: center; padding: 2rem; max-width: 900px; }
        .logo { font-size: 6rem; font-weight: 900; background: linear-gradient(135deg, #fff 0%, #a5b4fc 40%, #f0abfc 70%, #fff 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 1.5rem; letter-spacing: -3px; line-height: 1; }
        .tagline { font-size: 1.375rem; color: rgba(255,255,255,0.75); margin-bottom: 3.5rem; line-height: 1.7; font-weight: 300; max-width: 650px; margin-left: auto; margin-right: auto; }
        .actions { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; margin-bottom: 5rem; }
        .btn { padding: 1rem 2.5rem; border-radius: 100px; font-size: 1rem; font-weight: 600; text-decoration: none; transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1); border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 0.625rem; letter-spacing: 0.01em; }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; box-shadow: 0 8px 32px rgba(102, 126, 234, 0.45); }
        .btn-primary:hover { transform: translateY(-4px); box-shadow: 0 16px 48px rgba(102, 126, 234, 0.6); }
        .btn-ghost { background: rgba(255,255,255,0.08); color: white; border: 1px solid rgba(255,255,255,0.15); backdrop-filter: blur(12px); }
        .btn-ghost:hover { background: rgba(255,255,255,0.15); transform: translateY(-4px); }
        .features { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
        .feature-card { background: rgba(255,255,255,0.04); backdrop-filter: blur(24px); border: 1px solid rgba(255,255,255,0.08); border-radius: 24px; padding: 2rem 1.5rem; transition: all 0.4s ease; text-align: left; }
        .feature-card:hover { background: rgba(255,255,255,0.08); transform: translateY(-8px); border-color: rgba(255,255,255,0.15); box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        .feature-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.25rem; }
        .feature-card:nth-child(1) .feature-icon { background: linear-gradient(135deg, #667eea, #764ba2); }
        .feature-card:nth-child(2) .feature-icon { background: linear-gradient(135deg, #f093fb, #f5576c); }
        .feature-card:nth-child(3) .feature-icon { background: linear-gradient(135deg, #4facfe, #00f2fe); }
        .feature-title { font-size: 1.125rem; font-weight: 700; margin-bottom: 0.625rem; letter-spacing: -0.01em; }
        .feature-desc { font-size: 0.9375rem; color: rgba(255,255,255,0.6); line-height: 1.65; font-weight: 300; }
        footer { margin-top: 5rem; color: rgba(255,255,255,0.35); font-size: 0.875rem; font-weight: 300; }
        @media (max-width: 768px) {
            .logo { font-size: 3.5rem; }
            .features { grid-template-columns: 1fr; }
            .tagline { font-size: 1.125rem; }
        }
    </style>
</head>
<body>
    <div class="bg-orb one"></div>
    <div class="bg-orb two"></div>
    <div class="bg-orb three"></div>
    <div class="container">
        <h1 class="logo">SIGMA</h1>
        <p class="tagline">Sistem Informasi dan Manajemen Anggota Galeri Invetasi Univeritas Yatsi Madani.</p>
        <div class="actions">
            <a href="/login" class="btn btn-primary">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                Masuk ke Admin
            </a>
            <a href="#features" class="btn btn-ghost">Pelajari Lebih</a>
        </div>
        <div class="features" id="features">
            <div class="feature-card">
                <div class="feature-icon">📝</div>
                <h3 class="feature-title">Manajemen Konten</h3>
                <p class="feature-desc">Kelola artikel dan publikasi dengan editor yang ringkas. Draft, publish, atau arsipkan dalam satu klik.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3 class="feature-title">Dashboard Analitik</h3>
                <p class="feature-desc">Pantau metrik penting dan performa konten dalam satu tampilan yang informatif.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🔐</div>
                <h3 class="feature-title">Keamanan Terjamin</h3>
                <p class="feature-desc">Autentikasi dan otorisasi yang kuat untuk melindungi data sensitif Anda.</p>
            </div>
        </div>
        <footer>
            <p>&copy; 2026 SIGMA. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>
