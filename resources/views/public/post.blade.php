<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }} - {{ $contents['site_title']->value ?? 'SIGMA' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Source Sans 3', Arial, sans-serif; color: #181818; background: linear-gradient(180deg, #f4f0e6 0%, #e8e0d1 100%); background-attachment: fixed; min-height: 100vh; }

        .navbar { background: #f4f0e6; border-bottom: 2px solid #181818; padding: 1rem 2rem; position: sticky; top: 0; z-index: 100; }
        .nav-container { max-width: 1180px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; }
        .nav-brand { font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.5rem; font-weight: 700; color: #181818; text-decoration: none; letter-spacing: 0.08em; }
        .nav-links { display: flex; gap: 1.5rem; align-items: center; }
        .nav-links a { color: #181818; text-decoration: none; font-size: 0.78rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; }
        .nav-links a:hover { color: #9b1c1c; }

        .wrap { max-width: 760px; margin: 0 auto; padding: 3rem 2rem 4rem; }
        .back { display: inline-block; color: #9b1c1c; font-size: 0.78rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; text-decoration: none; margin-bottom: 2rem; }
        .back:hover { color: #181818; }
        .post-meta { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; }
        .post-date { color: #9b1c1c; }
        .post-tag { color: #5f574d; border: 1px solid #c8bda9; padding: 0.15rem 0.5rem; }
        h1 { font-family: 'Libre Baskerville', Georgia, serif; font-size: 2.5rem; font-weight: 700; line-height: 1.25; margin-bottom: 2rem; padding-bottom: 1.25rem; border-bottom: 2px solid #181818; }
        .post-body { color: #3f3a34; font-size: 1.0625rem; line-height: 1.9; white-space: pre-wrap; }

        footer { border-top: 2px solid #181818; padding: 1.5rem 2rem; text-align: center; color: #5f574d; font-size: 0.8125rem; }

        @media (max-width: 768px) {
            .navbar { padding: 1rem 1.25rem; }
            .nav-links { display: none; }
            .wrap { padding: 2rem 1.25rem 3rem; }
            h1 { font-size: 1.75rem; }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ route('landing') }}" class="nav-brand">{{ $contents['nav_brand']->value ?? 'SIGMA' }}</a>
            <div class="nav-links">
                <a href="{{ route('landing') }}#about">About</a>
                <a href="{{ route('landing') }}#members">Anggota</a>
                <a href="{{ route('landing') }}#achievements">Prestasi</a>
            </div>
        </div>
    </nav>

    <main class="wrap">
        <a href="{{ route('landing') }}" class="back">&lsaquo; Kembali ke Beranda</a>

        <div class="post-meta">
            <span class="post-date">{{ $post->created_at->format('d M Y') }}</span>
            <span class="post-tag">{{ ucfirst($post->status) }}</span>
        </div>

        <h1>{{ $post->title }}</h1>

        <div class="post-body">{{ $post->content }}</div>
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} {{ $contents['footer_text']->value ?? 'Organisasi SIGMA. All rights reserved.' }}</p>
    </footer>

</body>
</html>
