<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $contents['site_title']->value ?? 'SIGMA - Sistem Informasi Terpadu' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --ink: #181818;
            --ink-soft: #3f3a34;
            --muted: #5f574d;
            --paper: #f4f0e6;
            --card: #fffdf8;
            --line: #c8bda9;
            --accent: #9b1c1c;
            --shadow: 0 1px 3px rgba(24, 24, 24, 0.08), 0 8px 24px rgba(24, 24, 24, 0.06);
            --shadow-hover: 0 4px 10px rgba(24, 24, 24, 0.12), 0 16px 40px rgba(24, 24, 24, 0.1);
            --page-gradient-start: {{ $contents['page_gradient_start']->value ?? '#f4f0e6' }};
            --page-gradient-end: {{ $contents['page_gradient_end']->value ?? '#e8e0d1' }};
            --page-gradient-angle: {{ $contents['page_gradient_angle']->value ?? '180' }}deg;
            --hero-gradient-start: {{ $contents['hero_gradient_start']->value ?? '#f4f0e6' }};
            --hero-gradient-end: {{ $contents['hero_gradient_end']->value ?? '#faf7ef' }};
            --hero-gradient-angle: {{ $contents['hero_gradient_angle']->value ?? '90' }}deg;
            --about-gradient-start: {{ $contents['about_gradient_start']->value ?? '#fffaf0' }};
            --about-gradient-end: {{ $contents['about_gradient_end']->value ?? '#f4f0e6' }};
            --about-gradient-angle: {{ $contents['about_gradient_angle']->value ?? '180' }}deg;
            --members-gradient-start: {{ $contents['members_gradient_start']->value ?? '#e8e0d1' }};
            --members-gradient-end: {{ $contents['members_gradient_end']->value ?? '#f8f5ed' }};
            --members-gradient-angle: {{ $contents['members_gradient_angle']->value ?? '180' }}deg;
            --achievements-gradient-start: {{ $contents['achievements_gradient_start']->value ?? '#f8f5ed' }};
            --achievements-gradient-end: {{ $contents['achievements_gradient_end']->value ?? '#f4f0e6' }};
            --achievements-gradient-angle: {{ $contents['achievements_gradient_angle']->value ?? '180' }}deg;
            --footer-gradient-start: {{ $contents['footer_gradient_start']->value ?? '#e8e0d1' }};
            --footer-gradient-end: {{ $contents['footer_gradient_end']->value ?? '#f4f0e6' }};
            --footer-gradient-angle: {{ $contents['footer_gradient_angle']->value ?? '90' }}deg;
        }
        html { scroll-behavior: smooth; scroll-padding-top: 5rem; }
        body { font-family: 'Source Sans 3', Arial, sans-serif; color: var(--ink); background-color: var(--page-gradient-start); background-image: linear-gradient(var(--page-gradient-angle), var(--page-gradient-start), var(--page-gradient-end)); background-size: cover; background-position: center; background-attachment: fixed; line-height: 1.6; }
        @if($contents['page_background_image']->value ?? null)
        body { background-image: linear-gradient(rgba(244, 240, 230, 0.2), rgba(232, 224, 209, 0.82)), url('{{ $contents['page_background_image']->value }}'); }
        @endif
        .wrap { max-width: 1180px; margin: 0 auto; }

        /* ===== NAVBAR ===== */
        .navbar { background: rgba(244, 240, 230, 0.95); backdrop-filter: blur(8px); border-bottom: 1px solid var(--line); padding: 0.875rem 2rem; position: sticky; top: 0; z-index: 100; }
        .nav-container { max-width: 1180px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; gap: 1rem; }
        .nav-brand { font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.375rem; font-weight: 700; color: var(--ink); text-decoration: none; letter-spacing: 0.08em; }
        .nav-links { display: flex; gap: 1.75rem; align-items: center; }
        .nav-links a { color: var(--ink); text-decoration: none; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; padding: 0.25rem 0; border-bottom: 2px solid transparent; transition: color 0.2s, border-color 0.2s; }
        .nav-links a:hover { color: var(--accent); border-bottom-color: var(--accent); }
        .nav-links a.nav-login { background: var(--ink); color: var(--paper); padding: 0.5rem 1.1rem; border-bottom: 0; }
        .nav-links a.nav-login:hover { background: var(--accent); }
        .nav-toggle { display: none; background: none; border: 0; cursor: pointer; padding: 0.375rem; color: var(--ink); }

        /* ===== HEADER + BANNER KABAR (horizontal, full width) ===== */
        .site-header { background-color: var(--hero-gradient-start); background-image: linear-gradient(var(--hero-gradient-angle), var(--hero-gradient-start), var(--hero-gradient-end)); border-bottom: 1px solid var(--line); }
        @if($contents['hero_background_image']->value ?? null)
        .site-header { background-image: linear-gradient(rgba(244, 240, 230, 0.25), rgba(244, 240, 230, 0.88)), url('{{ $contents['hero_background_image']->value }}'); background-size: cover; background-position: center; }
        @endif
        .site-head-inner { max-width: 1180px; margin: 0 auto; padding: 3.5rem 2rem 3rem; }
        .site-head h1 { font-family: 'Libre Baskerville', Georgia, serif; font-size: clamp(2.5rem, 6vw, 4.25rem); font-weight: 700; letter-spacing: -0.03em; line-height: 1.1; }
        .site-head .tagline { font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.15rem; color: var(--accent); font-weight: 700; margin-top: 0.5rem; }
        .site-head .lede { color: var(--ink-soft); font-size: 1.05rem; max-width: 640px; margin-top: 0.875rem; }
        .head-actions { display: flex; gap: 0.75rem; margin-top: 1.5rem; flex-wrap: wrap; }
        .btn { display: inline-block; padding: 0.7rem 1.4rem; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; text-decoration: none; border: 1px solid var(--ink); transition: background 0.2s, color 0.2s, border-color 0.2s; }
        .btn-primary { background: var(--ink); color: var(--paper); }
        .btn-primary:hover { background: var(--accent); border-color: var(--accent); }
        .btn-ghost { background: transparent; color: var(--ink); }
        .btn-ghost:hover { background: var(--ink); color: var(--paper); }

        .news { background: var(--ink); color: var(--paper); padding: 2.25rem 0 2.5rem; }
        .news-head { max-width: 1180px; margin: 0 auto; padding: 0 2rem; display: flex; align-items: baseline; gap: 0.75rem; margin-bottom: 1.25rem; }
        .news-head h2 { font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.25rem; font-weight: 700; color: var(--paper); }
        .news-head span { font-size: 0.68rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #8a7f6d; }
        .news-rail { display: flex; gap: 1.25rem; overflow-x: auto; scroll-snap-type: x mandatory; padding: 0 max(2rem, calc((100% - 1180px) / 2)) 1rem; scrollbar-width: thin; scrollbar-color: #4a443c var(--ink); }
        .news-rail::-webkit-scrollbar { height: 6px; }
        .news-rail::-webkit-scrollbar-track { background: #2a2622; }
        .news-rail::-webkit-scrollbar-thumb { background: #4a443c; }
        .news-card { flex: 0 0 clamp(260px, 32%, 380px); scroll-snap-align: start; background: #221f1c; border: 1px solid #332f2a; border-top: 3px solid var(--accent); padding: 1.5rem; text-decoration: none; color: inherit; display: flex; flex-direction: column; min-height: 190px; transition: background 0.2s, transform 0.2s; }
        .news-card:hover { background: #2b2723; transform: translateY(-2px); }
        .news-card .label { font-size: 0.63rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: var(--accent); margin-bottom: 0.625rem; }
        .news-card h3 { font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.0625rem; font-weight: 700; line-height: 1.35; margin-bottom: 0.5rem; }
        .news-card h3 a { color: var(--paper); text-decoration: none; }
        .news-card h3 a:hover { color: var(--accent); }
        .news-card p { font-size: 0.8125rem; color: #b8ad9b; line-height: 1.6; flex: 1; }
        .news-card .meta { display: flex; gap: 0.75rem; font-size: 0.65rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #7d7263; margin-top: 0.875rem; }
        .news-card .meta a { color: inherit; text-decoration: none; margin-left: auto; transition: color 0.2s; }
        .news-card .meta a:hover, .news-card .meta a:focus-visible { color: var(--accent); }
        .news-empty { max-width: 1180px; margin: 0 auto; padding: 0 2rem; color: #7d7263; font-size: 0.875rem; }

        /* ===== SECTIONS + CARD ===== */
        .section { padding: 4rem 0; }
        .section-head { max-width: 1180px; margin: 0 auto; padding: 0 2rem; margin-bottom: 2rem; }
        .section-title { font-family: 'Libre Baskerville', Georgia, serif; font-size: 2rem; font-weight: 700; line-height: 1.2; }
        .section-subtitle { color: var(--muted); font-size: 0.95rem; max-width: 680px; margin-top: 0.5rem; }
        .section > .wrap { padding: 0 2rem; }

        #about { background-color: var(--about-gradient-start); background-image: linear-gradient(var(--about-gradient-angle), var(--about-gradient-start), var(--about-gradient-end)); }
        @if($contents['about_background_image']->value ?? null)
        #about { background-image: linear-gradient(rgba(255, 250, 240, 0.3), rgba(255, 250, 240, 0.86)), url('{{ $contents['about_background_image']->value }}'); background-size: cover; background-position: center; }
        @endif
        #members { background-color: var(--members-gradient-start); background-image: linear-gradient(var(--members-gradient-angle), var(--members-gradient-start), var(--members-gradient-end)); border-top: 1px solid var(--line); border-bottom: 1px solid var(--line); }
        @if($contents['members_background_image']->value ?? null)
        #members { background-image: linear-gradient(rgba(232, 224, 209, 0.3), rgba(232, 224, 209, 0.86)), url('{{ $contents['members_background_image']->value }}'); background-size: cover; background-position: center; }
        @endif
        #achievements { background-color: var(--achievements-gradient-start); background-image: linear-gradient(var(--achievements-gradient-angle), var(--achievements-gradient-start), var(--achievements-gradient-end)); }
        @if($contents['achievements_background_image']->value ?? null)
        #achievements { background-image: linear-gradient(rgba(248, 245, 237, 0.3), rgba(248, 245, 237, 0.86)), url('{{ $contents['achievements_background_image']->value }}'); background-size: cover; background-position: center; }
        @endif

        .card { background: var(--card); border: 1px solid var(--line); box-shadow: var(--shadow); transition: box-shadow 0.2s, transform 0.2s, border-color 0.2s; }
        .card:hover { box-shadow: var(--shadow-hover); transform: translateY(-3px); border-color: var(--accent); }

        /* ===== ABOUT: kartu profil + kartu statistik ===== */
        .about-cards { display: grid; grid-template-columns: 1.4fr 1fr; gap: 1.5rem; align-items: start; }
        .about-card { padding: 1.75rem; }
        .about-card h3 { font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.25rem; font-weight: 700; margin-bottom: 0.875rem; }
        .about-card p { color: var(--ink-soft); line-height: 1.75; }
        .about-card p + p { margin-top: 0.625rem; }
        .about-card .kicker { font-size: 0.65rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: var(--accent); margin-bottom: 0.5rem; }
        .tabs { display: flex; gap: 0.375rem; margin-bottom: 1rem; border-bottom: 1px solid var(--line); }
        .tab { background: none; border: 0; border-bottom: 2px solid transparent; padding: 0.5rem 0.875rem; font-family: inherit; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--muted); cursor: pointer; }
        .tab:hover { color: var(--ink); }
        .tab.active { color: var(--accent); border-bottom-color: var(--accent); }
        .tab-panel { display: none; }
        .tab-panel.active { display: block; }
        .stats-card { display: grid; grid-template-columns: repeat(2, 1fr); }
        .stat-box { padding: 1.25rem 1rem; text-align: center; border-right: 1px solid var(--line); border-bottom: 1px solid var(--line); }
        .stat-box:nth-child(2n) { border-right: 0; }
        .stat-box:nth-last-child(-n+2) { border-bottom: 0; }
        .stat-number { font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.625rem; font-weight: 700; color: var(--accent); line-height: 1.2; }
        .stat-label { font-size: 0.65rem; color: var(--muted); margin-top: 0.25rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; }

        /* ===== MEMBERS: foto kiri, data kanan ===== */
        .members-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 1.25rem; }
        .member-card { display: flex; align-items: center; gap: 1.25rem; padding: 1.25rem; }
        .member-photo { flex: 0 0 96px; width: 96px; height: 96px; border-radius: 50%; overflow: hidden; background: var(--ink); color: var(--paper); display: flex; align-items: center; justify-content: center; font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.75rem; font-weight: 700; }
        .member-photo img { width: 100%; height: 100%; object-fit: cover; }
        .member-info { min-width: 0; }
        .member-no { font-size: 0.65rem; font-weight: 700; letter-spacing: 0.1em; color: var(--accent); text-transform: uppercase; }
        .member-name { font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.0625rem; font-weight: 700; line-height: 1.3; margin-top: 0.125rem; }
        .member-role { display: inline-block; font-size: 0.68rem; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; color: var(--muted); background: rgba(200, 189, 169, 0.28); padding: 0.2rem 0.5rem; margin-top: 0.375rem; }
        .member-motto { font-size: 0.8125rem; color: var(--ink-soft); line-height: 1.55; margin-top: 0.5rem; padding-left: 0.625rem; border-left: 2px solid var(--accent); font-style: italic; }
        .member-motto:empty { display: none; }

        /* ===== ACHIEVEMENTS: gambar atas, penjelasan bawah ===== */
        .achievements-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; }
        .achievement-card { display: flex; flex-direction: column; overflow: hidden; }
        .achievement-media { position: relative; aspect-ratio: 16 / 10; background: var(--ink); display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .achievement-media img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s; }
        .achievement-card:hover .achievement-media img { transform: scale(1.05); }
        .achievement-media .icon { font-size: 2.75rem; line-height: 1; }
        .achievement-year { position: absolute; top: 0.75rem; left: 0.75rem; background: var(--accent); color: var(--paper); font-size: 0.65rem; font-weight: 700; letter-spacing: 0.08em; padding: 0.25rem 0.5rem; }
        .achievement-body { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; }
        .achievement-body h4 { font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.0625rem; font-weight: 700; line-height: 1.35; }
        .achievement-body p { font-size: 0.8125rem; color: var(--muted); line-height: 1.65; margin-top: 0.5rem; }

        /* ===== FOOTER ===== */
        footer { text-align: center; padding: 2.5rem 2rem; color: var(--muted); font-size: 0.8rem; border-top: 1px solid var(--line); background-color: var(--footer-gradient-start); background-image: linear-gradient(var(--footer-gradient-angle), var(--footer-gradient-start), var(--footer-gradient-end)); }
        @if($contents['footer_background_image']->value ?? null)
        footer { background-image: linear-gradient(rgba(232, 224, 209, 0.3), rgba(232, 224, 209, 0.86)), url('{{ $contents['footer_background_image']->value }}'); background-size: cover; background-position: center; }
        @endif

        /* ===== RESPONSIVE ===== */
        @media (max-width: 860px) {
            .about-cards { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .navbar { padding: 0.75rem 1.25rem; }
            .nav-toggle { display: block; }
            .nav-links { display: none; position: absolute; top: 100%; left: 0; right: 0; flex-direction: column; align-items: stretch; gap: 0; background: var(--paper); border-bottom: 1px solid var(--line); padding: 0.5rem 1.25rem 1rem; }
            .nav-links.open { display: flex; }
            .nav-links a { padding: 0.625rem 0; border-bottom: 1px solid rgba(200, 189, 169, 0.4); }
            .nav-links a.nav-login { margin-top: 0.5rem; text-align: center; border-bottom: 0; }
            .site-head-inner { padding: 2.5rem 1.25rem 2.25rem; }
            .news-head, .news-rail, .news-empty, .section > .wrap, .section-head { padding-left: 1.25rem; padding-right: 1.25rem; }
            .news-card { flex-basis: 78%; }
            .section { padding: 3rem 0; }
            .section-title { font-size: 1.625rem; }
            .members-grid { grid-template-columns: 1fr; }
            .member-card { gap: 1rem; }
            .member-photo { flex-basis: 76px; width: 76px; height: 76px; font-size: 1.375rem; }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="nav-container">
            <a href="/" class="nav-brand">{{ $contents['nav_brand']->value ?? 'SIGMA' }}</a>
            <button type="button" class="nav-toggle" id="navToggle" aria-label="Menu" aria-expanded="false">&#9776;</button>
            <div class="nav-links" id="navLinks">
                <a href="#about">{{ $contents['nav_about_label']->value ?? 'About' }}</a>
                <a href="#members">{{ $contents['nav_members_label']->value ?? 'Anggota' }}</a>
                <a href="#achievements">{{ $contents['nav_achievements_label']->value ?? 'Prestasi' }}</a>
                @if(request()->getPort() === 8000)
                    <a href="/login" class="nav-login">{{ $contents['nav_login_label']->value ?? 'Login' }}</a>
                @endif
            </div>
        </div>
    </nav>

    <header class="site-header" id="kabar">
        <div class="site-head-inner">
            <h1>{{ $contents['hero_title']->value ?? 'SIGMA' }}</h1>
            <p class="tagline">{{ $contents['hero_tagline']->value ?? 'Sistem Informasi dan Manajemen Terpadu' }}</p>
            <p class="lede">{{ $contents['hero_description']->value ?? 'Organisasi mahasiswa yang bergerak di bidang teknologi dan informasi.' }}</p>
            <div class="head-actions">
                <a href="#members" class="btn btn-primary">{{ $contents['hero_cta_label']->value ?? 'Pelajari Lebih' }}</a>
                <a href="#achievements" class="btn btn-ghost">Prestasi</a>
            </div>
        </div>
        </div>
    </header>

    <section class="news" aria-labelledby="newsTitle" id="kabar">
        <div class="news-head">
            <h2 id="newsTitle">Kabar Terbaru</h2>
            <span>Geser ke samping &rsaquo;</span>
        </div>
        @if($posts->count())
            <div class="news-rail">
                @foreach($posts as $post)
                <article class="news-card">
                    <div class="label">{{ $post->created_at->format('d M Y') }}</div>
                    <h3><a href="{{ $post->readUrl() }}"@if($post->hasExternalLink()) target="_blank" rel="noopener noreferrer"@endif>{{ $post->title }}</a></h3>
                    <p>{{ Illuminate\Support\Str::limit(strip_tags($post->content), 130) }}</p>
                    <div class="meta">
                        <span>{{ $post->user->name }}</span>
                        <a class="news-read" href="{{ $post->readUrl() }}"@if($post->hasExternalLink()) target="_blank" rel="noopener noreferrer"@endif>Baca &rsaquo;</a>
                    </div>
                </article>
                @endforeach
            </div>
        @else
            <p class="news-empty">Belum ada kabar yang dipublikasikan.</p>
        @endif
    </section>

    <section id="about" class="section">
        <div class="section-head">
            <h2 class="section-title">{{ $contents['about_title']->value ?? 'Tentang Kami' }}</h2>
            <p class="section-subtitle">{{ $contents['about_subtitle']->value ?? 'Kenali lebih dalam tentang organisasi kami, visi, misi, dan nilai-nilai yang kami pegang.' }}</p>
        </div>
        <div class="wrap about-cards">
            <article class="card about-card">
                <div class="tabs" role="tablist">
                    <button type="button" class="tab active" data-tab="about-profile" role="tab" aria-selected="true">{{ $contents['about_slide_title']->value ?? 'Tentang Kami' }}</button>
                    <button type="button" class="tab" data-tab="about-vm" role="tab" aria-selected="false">{{ $contents['vision_mission_title']->value ?? 'Visi & Misi' }}</button>
                </div>
                <div class="tab-panel active" id="about-profile">
                    <h3>{{ $contents['about_organization_title']->value ?? 'Organisasi SIGMA' }}</h3>
                    <p>{{ $contents['about_description']->value ?? 'SIGMA adalah organisasi mahasiswa yang mengembangkan teknologi dan informasi.' }}</p>
                </div>
                <div class="tab-panel" id="about-vm">
                    <div class="kicker">Visi</div>
                    <p>{{ $contents['vision']->value ?? 'Menjadi organisasi teknologi terdepan yang menghasilkan karya inovatif dan berdampak positif.' }}</p>
                    <div class="kicker" style="margin-top: 1rem;">Misi</div>
                    <p>{{ $contents['mission']->value ?? 'Membangun ekosistem pembelajaran teknologi yang kolaboratif dan inklusif.' }}</p>
                </div>
            </article>
            <div class="card stats-card">
                <div class="stat-box">
                    <div class="stat-number">{{ $contents['stat_active_members']->value ?? '25+' }}</div>
                    <div class="stat-label">{{ $contents['stat_active_members_label']->value ?? 'Anggota Aktif' }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">{{ $contents['stat_projects']->value ?? '12' }}</div>
                    <div class="stat-label">{{ $contents['stat_projects_label']->value ?? 'Proyek Selesai' }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">{{ $contents['stat_awards']->value ?? '8' }}</div>
                    <div class="stat-label">{{ $contents['stat_awards_label']->value ?? 'Penghargaan' }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">{{ $contents['stat_years']->value ?? '3' }}</div>
                    <div class="stat-label">{{ $contents['stat_years_label']->value ?? 'Tahun Berdiri' }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">{{ $contents['stat_partnerships']->value ?? '15+' }}</div>
                    <div class="stat-label">{{ $contents['stat_partnerships_label']->value ?? 'Kerjasama' }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">{{ $contents['stat_dedication']->value ?? '100%' }}</div>
                    <div class="stat-label">{{ $contents['stat_dedication_label']->value ?? 'Dedikasi' }}</div>
                </div>
            </div>
        </div>
    </section>

    <section id="members" class="section">
        <div class="section-head">
            <h2 class="section-title">{{ $contents['member_section_title']->value ?? 'Anggota Kami' }}</h2>
            <p class="section-subtitle">{{ $contents['member_section_subtitle']->value ?? 'Orang-orang hebat yang berada di balik setiap pencapaian organisasi.' }}</p>
        </div>
        <div class="wrap members-grid">
            @forelse($members as $member)
                <article class="card member-card">
                    <div class="member-photo">
                        @if($member['photo'])
                            <img src="{{ $member['photo'] }}" alt="{{ $member['name'] }}" loading="lazy">
                        @else
                            {{ $member['initial'] }}
                        @endif
                    </div>
                    <div class="member-info">
                        <div class="member-no">{{ $member['code'] }}</div>
                        <div class="member-name">{{ $member['name'] }}</div>
                        <div class="member-role">{{ $member['role'] }}</div>
                        <p class="member-motto">{{ $member['motto'] }}</p>
                    </div>
                </article>
            @empty
                <p class="section-subtitle">Belum ada anggota terdaftar.</p>
            @endforelse
        </div>
    </section>

    <section id="achievements" class="section">
        <div class="section-head">
            <h2 class="section-title">{{ $contents['achievement_section_title']->value ?? 'Prestasi Kami' }}</h2>
            <p class="section-subtitle">{{ $contents['achievement_section_subtitle']->value ?? 'Bukti dedikasi dan kerja keras organisasi dalam mencapai berbagai penghargaan.' }}</p>
        </div>
        <div class="wrap achievements-grid">
            @forelse($achievements as $achievement)
                <article class="card achievement-card">
                    <div class="achievement-media">
                        @if($achievement['image'])
                            <img src="{{ $achievement['image'] }}" alt="{{ $achievement['title'] }}" loading="lazy">
                        @else
                            <span class="icon">{{ $achievement['icon'] ?: '🏆' }}</span>
                        @endif
                        @if($achievement['year'])
                            <span class="achievement-year">{{ $achievement['year'] }}</span>
                        @endif
                    </div>
                    <div class="achievement-body">
                        <h4>{{ $achievement['title'] }}</h4>
                        <p>{{ $achievement['desc'] }}</p>
                    </div>
                </article>
            @empty
                <p class="section-subtitle">Belum ada prestasi tercatat.</p>
            @endforelse
        </div>
    </section>

    <script>
        // Navbar: buka/tutup menu di layar kecil.
        const navToggle = document.getElementById('navToggle');
        const navLinks = document.getElementById('navLinks');
        navToggle.addEventListener('click', function () {
            const open = navLinks.classList.toggle('open');
            navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
        navLinks.addEventListener('click', function (event) {
            if (event.target.tagName === 'A') {
                navLinks.classList.remove('open');
                navToggle.setAttribute('aria-expanded', 'false');
            }
        });

        // Tab About:.about / Visi Misi.
        document.querySelectorAll('.tab').forEach(function (tab) {
            tab.addEventListener('click', function () {
                document.querySelectorAll('.tab').forEach(function (other) {
                    other.classList.remove('active');
                    other.setAttribute('aria-selected', 'false');
                });
                document.querySelectorAll('.tab-panel').forEach(function (panel) { panel.classList.remove('active'); });
                tab.classList.add('active');
                tab.setAttribute('aria-selected', 'true');
                document.getElementById(tab.dataset.tab).classList.add('active');
            });
        });
    </script>

    <footer>
        <p>&copy; {{ date('Y') }} {{ $contents['footer_text']->value ?? 'Organisasi SIGMA. All rights reserved.' }}</p>
    </footer>

</body>
</html>
