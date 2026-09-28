<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGMA - Daftar Pengelola</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; min-height: 100vh; display: flex; }
        .left-panel { flex: 1; background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 3rem; position: relative; overflow: hidden; }
        .left-panel::before { content: ''; position: absolute; width: 500px; height: 500px; background: radial-gradient(circle, rgba(102,126,234,0.15) 0%, transparent 70%); top: -100px; right: -100px; border-radius: 50%; }
        .left-panel::after { content: ''; position: absolute; width: 300px; height: 300px; background: radial-gradient(circle, rgba(240,171,252,0.1) 0%, transparent 70%); bottom: -50px; left: -50px; border-radius: 50%; }
        .brand { position: relative; z-index: 1; text-align: center; }
        .brand h1 { font-size: 3.5rem; font-weight: 800; color: #fff; letter-spacing: -2px; margin-bottom: 0.75rem; }
        .brand p { color: rgba(255,255,255,0.6); font-size: 1.125rem; font-weight: 300; }
        .right-panel { flex: 1; background: #f8fafc; display: flex; align-items: center; justify-content: center; padding: 3rem; }
        .login-form { width: 100%; max-width: 400px; }
        .login-form h2 { font-size: 1.75rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem; }
        .login-form .subtitle { color: #64748b; margin-bottom: 2.5rem; font-size: 0.9375rem; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem; }
        .form-group input { width: 100%; padding: 0.875rem 1rem; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem; transition: all 0.2s; background: #fff; }
        .form-group input:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 4px rgba(102,126,234,0.1); }
        .form-options { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .remember { display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: #64748b; }
        .remember input { width: 16px; height: 16px; accent-color: #667eea; }
        .btn-login { width: 100%; padding: 1rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 10px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 15px rgba(102,126,234,0.4); }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(102,126,234,0.5); }
        .error { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 0.875rem 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.875rem; }
        .hint { background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.8125rem; line-height: 1.5; }
        .alt { text-align: center; margin-top: 1.5rem; font-size: 0.875rem; color: #64748b; }
        .alt a { color: #667eea; font-weight: 600; text-decoration: none; }
        .alt a:hover { text-decoration: underline; }
        @media (max-width: 768px) {
            .left-panel { display: none; }
            .right-panel { padding: 2rem; }
        }
    </style>
</head>
<body>
    <div class="left-panel">
        <div class="brand">
            <h1>SIGMA</h1>
            <p>Sistem Informasi dan Manajemen Galeri Investasi Universitas Yatsi Madani</p>
        </div>
    </div>
    <div class="right-panel">
        <div class="login-form">
            <h2>Buat akun</h2>
            <p class="subtitle">Akun pengelola untuk masuk ke panel SIGMA</p>
            @if ($errors->any())
                <div class="error">{{ $errors->first() }}</div>
            @endif
            <div class="hint">Akun yang dibuat di sini langsung bisa masuk ke panel admin. Buat satu akun untuk setiap pengelola.</div>
            <form method="POST" action="{{ route('register.process') }}">
                @csrf
                <div class="form-group">
                    <label for="name">Nama lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Nama kamu" required autofocus autocomplete="name">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="nama@giyama.id" required autocomplete="email">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" required autocomplete="new-password">
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Ulangi password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ketik ulang password" required autocomplete="new-password">
                </div>
                <button type="submit" class="btn-login">Daftar</button>
            </form>
            <p class="alt">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
        </div>
    </div>
</body>
</html>
