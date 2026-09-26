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
        body { font-family: 'Source Sans 3', Arial, sans-serif; color: #181818; background-color: var(--page-gradient-start); background-image: linear-gradient(var(--page-gradient-angle), var(--page-gradient-start), var(--page-gradient-end)); background-size: cover; background-position: center; background-attachment: fixed; }
        @if($contents['page_background_image']->value ?? null)
        body { background-image: linear-gradient(rgba(244, 240, 230, 0.2), rgba(232, 224, 209, 0.82)), url('{{ $contents['page_background_image']->value }}'); }
        @endif

        /* ===== NAVBAR ===== */
        .navbar { background: #f4f0e6; border-bottom: 2px solid #181818; padding: 1rem 2rem; position: sticky; top: 0; z-index: 100; }
        .nav-container { max-width: 1180px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; }
        .nav-brand { font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.5rem; font-weight: 700; color: #181818; text-decoration: none; letter-spacing: 0.08em; }
        .nav-links { display: flex; gap: 1.5rem; align-items: center; }
        .nav-links a { color: #181818; text-decoration: none; font-size: 0.78rem; transition: color 0.2s; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; }
        .nav-links a:hover { color: #9b1c1c; }

        /* ===== HERO ===== */
        .hero { min-height: 72vh; display: flex; flex-direction: column; align-items: flex-start; justify-content: center; text-align: left; padding: 5rem max(2rem, calc((100% - 1180px) / 2)); background-color: var(--hero-gradient-start); background-image: linear-gradient(var(--hero-gradient-angle), var(--hero-gradient-start), var(--hero-gradient-end)); background-size: cover; background-position: center; border-bottom: 2px solid #181818; position: relative; overflow: hidden; }
        @if($contents['hero_background_image']->value ?? null)
        .hero { background-image: linear-gradient(rgba(244, 240, 230, 0.2), rgba(244, 240, 230, 0.82)), url('{{ $contents['hero_background_image']->value }}'); }
        @endif
        .hero::before { content: ''; position: absolute; width: 42%; height: 100%; right: 0; top: 0; background: linear-gradient(90deg, transparent, #faf7ef 30%); border-left: 0; }
        .hero::after { content: ''; position: absolute; width: 36%; height: 2px; right: 3%; bottom: 22%; background: #9b1c1c; }
        .hero h1 { font-family: 'Libre Baskerville', Georgia, serif; font-size: clamp(3.5rem, 8vw, 7rem); font-weight: 700; margin-bottom: 1.5rem; color: #181818; position: relative; z-index: 1; letter-spacing: -0.04em; }
        .hero .tagline { font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.35rem; color: #9b1c1c; font-weight: 700; margin-bottom: 1rem; position: relative; z-index: 1; }
        .hero p { font-size: 1.15rem; color: #3f3a34; max-width: 600px; line-height: 1.7; margin-bottom: 2.5rem; position: relative; z-index: 1; }
        .cta { position: relative; z-index: 1; }
        .cta a { padding: 0.8rem 1.5rem; border-radius: 0; font-size: 0.8rem; text-decoration: none; transition: all 0.2s; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; background: #181818; color: #f4f0e6; }
        .cta a:hover { background: #9b1c1c; transform: none; }

        /* ===== SECTIONS ===== */
        .section { padding: 4rem max(2rem, calc((100% - 1180px) / 2)); max-width: none; margin: 0; background-color: var(--about-gradient-start); background-image: linear-gradient(var(--about-gradient-angle), var(--about-gradient-start), var(--about-gradient-end)); background-size: cover; background-position: center; }
        .section > * { max-width: 1180px; margin-left: auto; margin-right: auto; }
        @if($contents['about_background_image']->value ?? null)
        #about { background-image: linear-gradient(rgba(255, 250, 240, 0.25), rgba(255, 250, 240, 0.82)), url('{{ $contents['about_background_image']->value }}'); }
        @endif
        #members { background-color: var(--members-gradient-start); background-image: linear-gradient(var(--members-gradient-angle), var(--members-gradient-start), var(--members-gradient-end)); background-size: cover; background-position: center; border-top: 1px solid #c8bda9; border-bottom: 1px solid #c8bda9; padding: 3rem max(2rem, calc((100% - 1180px) / 2)); }
        @if($contents['members_background_image']->value ?? null)
        #members { background-image: linear-gradient(rgba(232, 224, 209, 0.25), rgba(232, 224, 209, 0.82)), url('{{ $contents['members_background_image']->value }}'); }
        @endif
        #achievements { background-color: var(--achievements-gradient-start); background-image: linear-gradient(var(--achievements-gradient-angle), var(--achievements-gradient-start), var(--achievements-gradient-end)); background-size: cover; background-position: center; }
        @if($contents['achievements_background_image']->value ?? null)
        #achievements { background-image: linear-gradient(rgba(248, 245, 237, 0.25), rgba(248, 245, 237, 0.82)), url('{{ $contents['achievements_background_image']->value }}'); }
        @endif
        .section-title { text-align: left; font-family: 'Libre Baskerville', Georgia, serif; font-size: 2.5rem; font-weight: 700; margin-bottom: 0.75rem; color: #181818; border-bottom: 2px solid #181818; padding-bottom: 0.75rem; }
        .section-subtitle { text-align: left; color: #5f574d; font-size: 1rem; max-width: 700px; margin: 1rem 0 2.5rem; }

        /* ===== ABOUT ===== */
        .about-grid { display: grid; grid-template-columns: 1.25fr 0.75fr; gap: 4rem; align-items: start; }
        .about-text h3 { font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.75rem; font-weight: 700; margin-bottom: 1.5rem; color: #181818; }
        .about-text p { color: #3f3a34; line-height: 1.8; margin-bottom: 1rem; }
        .about-slider { position: relative; overflow: hidden; min-height: 250px; }
        .about-slides { display: flex; transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1); width: 100%; }
        .about-slide { width: 100%; flex: 0 0 100%; padding: 0.5rem 0; }
        .about-slide-title { font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.35rem; font-weight: 700; margin-bottom: 1rem; color: #181818; }
        .about-slide p { color: #3f3a34; line-height: 1.8; margin-bottom: 0.75rem; }
        .about-slide-icon { display: none; }
        .slider-nav { display: flex; align-items: center; justify-content: center; gap: 0.75rem; margin-top: 1.5rem; }
        .slider-dot { width: 8px; height: 8px; border-radius: 50%; background: #a9a094; cursor: pointer; transition: all 0.3s; border: none; padding: 0; }
        .slider-dot.active { background: #9b1c1c; transform: scale(1.3); }
        .slider-arrow { width: 32px; height: 32px; border-radius: 50%; background: transparent; border: 1px solid #181818; color: #181818; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1rem; }
        .about-stats { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0; border: 1px solid #c8bda9; background: #e8e0d1; }
        .stat-box { background: transparent; border: 0; border-right: 1px solid #c8bda9; border-bottom: 1px solid #c8bda9; border-radius: 0; padding: 1.25rem 0.75rem; text-align: center; }
        .stat-box:nth-child(2n) { border-right: 0; }
        .stat-box:nth-last-child(-n+2) { border-bottom: 0; }
        .stat-number { font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.75rem; font-weight: 700; color: #9b1c1c; }
        .stat-label { font-size: 0.72rem; color: #5f574d; margin-top: 0.25rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; }

        /* ===== MEMBERS ===== */
        .members-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; }
        .member-card { display: flex; align-items: center; gap: 1.25rem; background: #f8f5ed; border: 1px solid #c8bda9; border-radius: 0; padding: 1.25rem; text-align: left; transition: all 0.2s; }
        .member-card:hover { border-color: #9b1c1c; background: #fffdf8; }
        .member-avatar { width: 54px; height: 54px; border-radius: 50%; background: #181818; display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 700; color: #f4f0e6; flex-shrink: 0; overflow: hidden; }
        .member-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .member-body { flex: 1; }
        .member-number { display: inline-block; font-size: 0.65rem; font-weight: 700; color: #9b1c1c; background: transparent; padding: 0; border-radius: 0; margin-bottom: 0.4rem; letter-spacing: 0.08em; }
        .member-name { font-family: 'Libre Baskerville', Georgia, serif; font-size: 1rem; font-weight: 700; color: #181818; margin-bottom: 0.125rem; }
        .member-role { font-size: 0.75rem; color: #9b1c1c; font-weight: 700; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.06em; }
        .member-desc { font-size: 0.8125rem; color: #5f574d; line-height: 1.5; }

        /* ===== ACHIEVEMENTS ===== */
        .achievements-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem; }
        .achievement-card { background: #f8f5ed; border: 1px solid #c8bda9; border-radius: 0; padding: 1.25rem; display: flex; gap: 1rem; align-items: flex-start; transition: all 0.2s; }
        .achievement-card:hover { border-color: #9b1c1c; background: #fffdf8; }
        .achievement-icon { display: none; }
        .achievement-content h4 { font-family: 'Libre Baskerville', Georgia, serif; font-size: 1rem; font-weight: 700; color: #181818; margin-bottom: 0.25rem; }
        .achievement-content .year { font-size: 0.72rem; color: #9b1c1c; font-weight: 700; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.08em; }
        .achievement-content p { font-size: 0.875rem; color: #5f574d; line-height: 1.5; }

        /* ===== FOOTER ===== */
        footer { text-align: center; padding: 2.5rem 2rem; color: #5f574d; font-size: 0.8rem; border-top: 2px solid #181818; background-color: var(--footer-gradient-start); background-image: linear-gradient(var(--footer-gradient-angle), var(--footer-gradient-start), var(--footer-gradient-end)); background-size: cover; background-position: center; }
        @if($contents['footer_background_image']->value ?? null)
        footer { background-image: linear-gradient(rgba(232, 224, 209, 0.25), rgba(232, 224, 209, 0.82)), url('{{ $contents['footer_background_image']->value }}'); }
        @endif
        footer a { color: #9b1c1c; text-decoration: none; }
        footer a:hover { color: #181818; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .navbar { padding: 1rem 1.25rem; }
            .hero { min-height: auto; padding: 4rem 1.25rem 4.5rem; }
            .hero::before { width: 38%; opacity: 0.65; }
            .hero::after { width: 45%; right: 1.25rem; bottom: 2rem; }
            .hero h1 { font-size: clamp(3rem, 18vw, 5rem); }
            .hero .tagline { font-size: 1.05rem; line-height: 1.5rem; }
            .hero p { font-size: 1rem; margin-bottom: 2rem; }
            .about-grid { grid-template-columns: 1fr; }
            .nav-links { display: none; }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="nav-container">
            <a href="/" class="nav-brand">{{ $contents['nav_brand']->value ?? 'SIGMA' }}</a>
            <div class="nav-links">
                <a href="#about">{{ $contents['nav_about_label']->value ?? 'About' }}</a>
                <a href="#members">{{ $contents['nav_members_label']->value ?? 'Anggota' }}</a>
                <a href="#achievements">{{ $contents['nav_achievements_label']->value ?? 'Prestasi' }}</a>
                @if(request()->getPort() === 8000)
                <a href="/login" class="nav-login">{{ $contents['nav_login_label']->value ?? 'Login' }}</a>
            @endif
            </div>
        </div>
    </nav>

    <section class="hero">
        <h1>{{ $contents['hero_title']->value ?? 'SIGMA' }}</h1>
        <p class="tagline">{{ $contents['hero_tagline']->value ?? 'Sistem Informasi dan Manajemen Terpadu' }}</p>
        <p>{{ $contents['hero_description']->value ?? 'Organisasi mahasiswa yang bergerak di bidang teknologi dan informasi.' }}</p>
        <div class="cta">
            <a href="#about">{{ $contents['hero_cta_label']->value ?? 'Pelajari Lebih' }}</a>
        </div>
    </section>

    <section id="about" class="section">
        <h2 class="section-title">{{ $contents['about_title']->value ?? 'Tentang Kami' }}</h2>
        <p class="section-subtitle">{{ $contents['about_subtitle']->value ?? 'Kenali lebih dalam tentang organisasi kami, visi, misi, dan nilai-nilai yang kami pegang.' }}</p>
        <div class="about-grid">
            <div class="about-text">
                <h3>{{ $contents['about_organization_title']->value ?? 'Organisasi SIGMA' }}</h3>
                <div class="about-slider" id="aboutSlider">
                    <div class="about-slides">
                        <div class="about-slide">
                            <h3 class="about-slide-title">{{ $contents['about_slide_title']->value ?? 'Tentang Kami' }}</h3>
                            <p>{{ Illuminate\Support\Str::limit($contents['about_description']->value ?? 'SIGMA adalah organisasi mahasiswa yang mengembangkan teknologi dan informasi.', 150) }}</p>
                        </div>
                        <div class="about-slide">
                            <h3 class="about-slide-title">{{ $contents['vision_mission_title']->value ?? 'Visi & Misi' }}</h3>
                            <p><strong>Visi:</strong> {{ Illuminate\Support\Str::limit($contents['vision']->value ?? 'Menjadi organisasi teknologi terdepan yang menghasilkan karya inovatif dan berdampak positif.', 110) }}</p>
                            <p><strong>Misi:</strong> {{ Illuminate\Support\Str::limit($contents['mission']->value ?? 'Membangun ekosistem pembelajaran teknologi yang kolaboratif dan inklusif.', 110) }}</p>
                        </div>
                    </div>
                    <div class="slider-nav">
                        <button type="button" class="slider-arrow" onclick="sliderPrev()">&#8249;</button>
                        <div class="slider-dots">
                            <button type="button" class="slider-dot active" onclick="goToSlide(0)" aria-label="Tentang Kami"></button>
                            <button type="button" class="slider-dot" onclick="goToSlide(1)" aria-label="Visi dan Misi"></button>
                        </div>
                        <button type="button" class="slider-arrow" onclick="sliderNext()">&#8250;</button>
                    </div>
                </div>
            </div>
            <div class="about-stats">
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
        <h2 class="section-title">{{ $contents['member_section_title']->value ?? 'Anggota Kami' }}</h2>
        <p class="section-subtitle">{{ $contents['member_section_subtitle']->value ?? 'Orang-orang hebat yang berada di balik setiap pencapaian organisasi.' }}</p>
        <div class="members-grid">

            @foreach($members as $member)
            <div class="member-card">
                @if($member['photo'])
                    <div class="member-avatar"><img src="{{ $member['photo'] }}" alt="{{ $member['name'] }}"></div>
                @else
                    <div class="member-avatar">{{ $member['initial'] }}</div>
                @endif
                <div class="member-body">
                    <div class="member-number">{{ $member['code'] }}</div>
                    <div class="member-name">{{ $member['name'] }}</div>
                    <div class="member-role">{{ $member['role'] }}</div>
                    <div class="member-desc">{{ $member['desc'] }}</div>
                </div>
            </div>
            @endforeach

        </div>
    </section>

    <section id="achievements" class="section">
        <h2 class="section-title">{{ $contents['achievement_section_title']->value ?? 'Prestasi Kami' }}</h2>
        <p class="section-subtitle">{{ $contents['achievement_section_subtitle']->value ?? 'Bukti dedikasi dan kerja keras organisasi dalam mencapai berbagai penghargaan.' }}</p>
        <div class="achievements-grid">

            @foreach($achievements as $achievement)
            <div class="achievement-card">
                <div class="achievement-content">
                    <h4>{{ $achievement['title'] }}</h4>
                    <div class="year">{{ $achievement['year'] }}</div>
                    <p>{{ $achievement['desc'] }}</p>
                </div>
            </div>
            @endforeach

        </div>
    </section>

    <script>
        const aboutSlider = document.getElementById('aboutSlider');
        const aboutSlides = aboutSlider.querySelector('.about-slides');
        const aboutSlideItems = [...aboutSlider.querySelectorAll('.about-slide')];
        const aboutDots = [...aboutSlider.querySelectorAll('.slider-dot')];
        let aboutSlideIndex = 0;

        function updateAboutSlider() {
            aboutSlides.style.transform = 'translateX(-' + (aboutSlideIndex * 100) + '%)';
            aboutDots.forEach(function(dot, index) { dot.classList.toggle('active', index === aboutSlideIndex); });
        }
        function goToSlide(index) { aboutSlideIndex = index; updateAboutSlider(); }
        function sliderNext() { aboutSlideIndex = (aboutSlideIndex + 1) % aboutSlideItems.length; updateAboutSlider(); }
        function sliderPrev() { aboutSlideIndex = (aboutSlideIndex - 1 + aboutSlideItems.length) % aboutSlideItems.length; updateAboutSlider(); }

        setInterval(sliderNext, 3000);
    </script>

    <footer>
        <p>&copy; {{ date('Y') }} {{ $contents['footer_text']->value ?? 'Organisasi SIGMA. All rights reserved.' }}</p>
    </footer>

</body>
</html>
