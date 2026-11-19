1|<!DOCTYPE html>
2|<html lang="id">
3|<head>
4|    <meta charset="UTF-8">
5|    <meta name="viewport" content="width=device-width, initial-scale=1.0">
6|    <title>{{ $contents['site_title']->value ?? 'SIGMA - Sistem Informasi Terpadu' }}</title>
7|    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">
8|    <style>
9|        * { margin: 0; padding: 0; box-sizing: border-box; }
10|        :root {
11|            --ink: #181818;
12|            --ink-soft: #3f3a34;
13|            --muted: #5f574d;
14|            --paper: #f4f0e6;
15|            --card: #fffdf8;
16|            --line: #c8bda9;
17|            --accent: #9b1c1c;
18|            --shadow: 0 1px 3px rgba(24, 24, 24, 0.08), 0 8px 24px rgba(24, 24, 24, 0.06);
19|            --shadow-hover: 0 4px 10px rgba(24, 24, 24, 0.12), 0 16px 40px rgba(24, 24, 24, 0.1);
20|            --page-gradient-start: {{ $contents['page_gradient_start']->value ?? '#f4f0e6' }};
21|            --page-gradient-end: {{ $contents['page_gradient_end']->value ?? '#e8e0d1' }};
22|            --page-gradient-angle: {{ $contents['page_gradient_angle']->value ?? '180' }}deg;
23|            --hero-gradient-start: {{ $contents['hero_gradient_start']->value ?? '#f4f0e6' }};
24|            --hero-gradient-end: {{ $contents['hero_gradient_end']->value ?? '#faf7ef' }};
25|            --hero-gradient-angle: {{ $contents['hero_gradient_angle']->value ?? '90' }}deg;
26|            --about-gradient-start: {{ $contents['about_gradient_start']->value ?? '#fffaf0' }};
27|            --about-gradient-end: {{ $contents['about_gradient_end']->value ?? '#f4f0e6' }};
28|            --about-gradient-angle: {{ $contents['about_gradient_angle']->value ?? '180' }}deg;
29|            --members-gradient-start: {{ $contents['members_gradient_start']->value ?? '#e8e0d1' }};
30|            --members-gradient-end: {{ $contents['members_gradient_end']->value ?? '#f8f5ed' }};
31|            --members-gradient-angle: {{ $contents['members_gradient_angle']->value ?? '180' }}deg;
32|            --achievements-gradient-start: {{ $contents['achievements_gradient_start']->value ?? '#f8f5ed' }};
33|            --achievements-gradient-end: {{ $contents['achievements_gradient_end']->value ?? '#f4f0e6' }};
34|            --achievements-gradient-angle: {{ $contents['achievements_gradient_angle']->value ?? '180' }}deg;
35|            --footer-gradient-start: {{ $contents['footer_gradient_start']->value ?? '#e8e0d1' }};
36|            --footer-gradient-end: {{ $contents['footer_gradient_end']->value ?? '#f4f0e6' }};
37|            --footer-gradient-angle: {{ $contents['footer_gradient_angle']->value ?? '90' }}deg;
38|        }
39|        html { scroll-behavior: smooth; scroll-padding-top: 5rem; }
40|        body { font-family: 'Source Sans 3', Arial, sans-serif; color: var(--ink); background-color: var(--page-gradient-start); background-image: linear-gradient(var(--page-gradient-angle), var(--page-gradient-start), var(--page-gradient-end)); background-size: cover; background-position: center; background-attachment: fixed; line-height: 1.6; }
41|        @if($contents['page_background_image']->value ?? null)
42|        body { background-image: linear-gradient(rgba(244, 240, 230, 0.2), rgba(232, 224, 209, 0.82)), url('{{ $contents['page_background_image']->value }}'); }
43|        @endif
44|        .wrap { max-width: 1180px; margin: 0 auto; }
45|
46|        /* ===== NAVBAR ===== */
47|        .navbar { background: rgba(244, 240, 230, 0.95); backdrop-filter: blur(8px); border-bottom: 1px solid var(--line); padding: 0.875rem 2rem; position: sticky; top: 0; z-index: 100; }
48|        .nav-container { max-width: 1180px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; gap: 1rem; }
49|        .nav-brand { font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.375rem; font-weight: 700; color: var(--ink); text-decoration: none; letter-spacing: 0.08em; }
50|        .nav-links { display: flex; gap: 1.75rem; align-items: center; }
51|        .nav-links a { color: var(--ink); text-decoration: none; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; padding: 0.25rem 0; border-bottom: 2px solid transparent; transition: color 0.2s, border-color 0.2s; }
52|        .nav-links a:hover { color: var(--accent); border-bottom-color: var(--accent); }
53|        .page-notice { padding: 0.75rem 1.25rem; font-size: 0.875rem; text-align: center; }
54|        .page-notice.is-error { background: #fef2f2; color: #b91c1c; border-bottom: 1px solid #fecaca; }
55|        .page-notice.is-ok { background: #ecfdf5; color: #047857; border-bottom: 1px solid #a7f3d0; }
56|        .nav-toggle { display: none; background: none; border: 0; cursor: pointer; padding: 0.375rem; color: var(--ink); }
57|
58|        /* ===== HEADER + BANNER KABAR (horizontal, full width) ===== */
59|        .site-header { background-color: var(--hero-gradient-start); background-image: linear-gradient(var(--hero-gradient-angle), var(--hero-gradient-start), var(--hero-gradient-end)); border-bottom: 1px solid var(--line); }
60|        @if($contents['hero_background_image']->value ?? null)
61|        .site-header { background-image: linear-gradient(rgba(244, 240, 230, 0.25), rgba(244, 240, 230, 0.88)), url('{{ $contents['hero_background_image']->value }}'); background-size: cover; background-position: center; }
62|        @endif
63|        .site-head-inner { max-width: 1180px; margin: 0 auto; padding: 3.5rem 2rem 3rem; }
64|        .site-head h1 { font-family: 'Libre Baskerville', Georgia, serif; font-size: clamp(2.5rem, 6vw, 4.25rem); font-weight: 700; letter-spacing: -0.03em; line-height: 1.1; }
65|        .site-head .tagline { font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.15rem; color: var(--accent); font-weight: 700; margin-top: 0.5rem; }
66|        .site-head .lede { color: var(--ink-soft); font-size: 1.05rem; max-width: 640px; margin-top: 0.875rem; }
67|        .head-actions { display: flex; gap: 0.75rem; margin-top: 1.5rem; flex-wrap: wrap; }
68|
69|        /* ===== HERO BANNER (geser otomatis) =====
70|           Slide ditumpuk absolute, yang aktif opacity 1. Tidak pakai animasi
71|           transform supaya teks tetap selectable dan tidak bikin CLS. */
72|        .hero-banner { position: relative; }
73|        .hero-banner:focus-visible { outline: 2px solid var(--accent); outline-offset: -2px; }
74|        .hero-banner-slides { position: relative; }
75|
76|        /* Scrim terang di sisi kiri tempat teks berdiri. Warna header hanya
77|           25% opaque di atas, jadi tanpa ini judul panjang + ringkasan post
78|           jadi dark-on-dark di atas foto gelap. Ikut idiom section lain:
79|           kabut hangat di atas gambar, makin tembus ke kanan. */
80|        .hero-banner-slides::after {
81|            content: '';
82|            position: absolute;
83|            inset: 0;
84|            z-index: 1;
85|            pointer-events: none;
86|            background: linear-gradient(100deg, rgba(244, 240, 230, 0.95) 0%, rgba(244, 240, 230, 0.9) 40%, rgba(244, 240, 230, 0.55) 68%, rgba(244, 240, 230, 0.22) 100%);
87|        }
88|        /* Konten dan kontrol harus di atas scrim, bukan di bawahnya.
89|           z-index cuma berlaku kalau posisinya bukan static. */
90|        .hero-banner-inner { position: relative; z-index: 2; }
91|        .hero-banner-slide { position: absolute; inset: 0; opacity: 0; visibility: hidden; transition: opacity 0.55s ease; background-size: cover; background-position: center; background-repeat: no-repeat; }
92|        .hero-banner-slide.active { position: relative; opacity: 1; visibility: visible; }
93|        .hero-banner-inner { max-width: 1180px; margin: 0 auto; padding: 3.5rem 2rem 4.5rem; }
94|        .hero-banner-date { font-size: 0.68rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: var(--accent); margin-bottom: 0.625rem; }
95|        .hero-banner-title { font-family: 'Libre Baskerville', Georgia, serif; font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 700; letter-spacing: -0.03em; line-height: 1.15; }
96|        .hero-banner-text { color: var(--ink-soft); font-size: 1.05rem; max-width: 640px; margin-top: 0.875rem; }
97|
98|        /* Kontrol banner: satu blok untuk seluruh banner, bukan per slide.
99|           Tidak ada tombol panah; dots saja yang jadi penanda posisi sekaligus
100|           kontrol manual, jadi banner tetap bisa dijalankan tanpa mouse. */
101|        .carousel-nav { position: absolute; left: 0; right: 0; bottom: 1.5rem; z-index: 2; max-width: 1180px; margin: 0 auto; padding: 0 2rem; display: flex; align-items: center; gap: 0.875rem; }
102|        .carousel-dots { display: flex; align-items: center; gap: 0.5rem; }
103|        .carousel-dot { width: 26px; height: 4px; padding: 0; border: 0; border-radius: 2px; background: rgba(24, 24, 24, 0.25); cursor: pointer; transition: background 0.25s ease, width 0.25s ease; }
104|        .carousel-dot.active { background: var(--accent); width: 40px; }
105|        .carousel-dot:focus-visible { outline: 2px solid var(--accent); outline-offset: 3px; }
106|        .btn { display: inline-block; padding: 0.7rem 1.4rem; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; text-decoration: none; border: 1px solid var(--ink); transition: background 0.2s, color 0.2s, border-color 0.2s; }
107|        .btn-primary { background: var(--ink); color: var(--paper); }
108|        .btn-primary:hover { background: var(--accent); border-color: var(--accent); }
109|        .btn-ghost { background: transparent; color: var(--ink); }
110|        .btn-ghost:hover { background: var(--ink); color: var(--paper); }
111|
112|        /* Gerak otomatis dihentikan untuk pengguna yang memintanya. JS juga
113|           berhenti menggeser, bukan cuma CSS yang meredam animasinya. */
114|        @media (prefers-reduced-motion: reduce) {
115|            .hero-banner-slide, .carousel-dot, .btn, .nav-links a { transition-duration: 0.01ms !important; }
116|            .hero-banner { scroll-behavior: auto; }
117|        }
118|
119|        .news { background: var(--ink); color: var(--paper); padding: 2.25rem 0 2.5rem; }
120|        .news-head { max-width: 1180px; margin: 0 auto; padding: 0 2rem; display: flex; align-items: baseline; gap: 0.75rem; margin-bottom: 1.25rem; }
121|        .news-head h2 { font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.25rem; font-weight: 700; color: var(--paper); }
122|        .news-head span { font-size: 0.68rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #8a7f6d; }
123|        .news-rail { display: flex; gap: 1.25rem; overflow-x: auto; scroll-snap-type: x mandatory; padding: 0 max(2rem, calc((100% - 1180px) / 2)) 1rem; scrollbar-width: thin; scrollbar-color: #4a443c var(--ink); }
124|        .news-rail::-webkit-scrollbar { height: 6px; }
125|        .news-rail::-webkit-scrollbar-track { background: #2a2622; }
126|        .news-rail::-webkit-scrollbar-thumb { background: #4a443c; }
127|        .news-card { flex: 0 0 clamp(260px, 32%, 380px); scroll-snap-align: start; background: #221f1c; border: 1px solid #332f2a; border-top: 3px solid var(--accent); padding: 1.5rem; text-decoration: none; color: inherit; display: flex; flex-direction: column; min-height: 190px; transition: background 0.2s, transform 0.2s; }
128|        .news-card:hover { background: #2b2723; transform: translateY(-2px); }
129|        .news-card .label { font-size: 0.63rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: var(--accent); margin-bottom: 0.625rem; }
130|        .news-card h3 { font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.0625rem; font-weight: 700; line-height: 1.35; margin-bottom: 0.5rem; }
131|        .news-card h3 a { color: var(--paper); text-decoration: none; }
132|        .news-card h3 a:hover { color: var(--accent); }
133|        .news-card p { font-size: 0.8125rem; color: #b8ad9b; line-height: 1.6; flex: 1; }
134|        .news-card .meta { display: flex; gap: 0.75rem; font-size: 0.65rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #7d7263; margin-top: 0.875rem; }
135|        .news-card .meta a { color: inherit; text-decoration: none; margin-left: auto; transition: color 0.2s; }
136|        .news-card .meta a:hover, .news-card .meta a:focus-visible { color: var(--accent); }
137|        .news-empty { max-width: 1180px; margin: 0 auto; padding: 0 2rem; color: #7d7263; font-size: 0.875rem; }
138|
139|        /* ===== SECTIONS + CARD ===== */
140|        .section { padding: 4rem 0; }
141|        .section-head { max-width: 1180px; margin: 0 auto; padding: 0 2rem; margin-bottom: 2rem; }
142|        .section-title { font-family: 'Libre Baskerville', Georgia, serif; font-size: 2rem; font-weight: 700; line-height: 1.2; }
143|        .section-subtitle { color: var(--muted); font-size: 0.95rem; max-width: 680px; margin-top: 0.5rem; }
144|        .section > .wrap { padding: 0 2rem; }
145|
146|        #about { background-color: var(--about-gradient-start); background-image: linear-gradient(var(--about-gradient-angle), var(--about-gradient-start), var(--about-gradient-end)); }
147|        @if($contents['about_background_image']->value ?? null)
148|        #about { background-image: linear-gradient(rgba(255, 250, 240, 0.3), rgba(255, 250, 240, 0.86)), url('{{ $contents['about_background_image']->value }}'); background-size: cover; background-position: center; }
149|        @endif
150|        #members { background-color: var(--members-gradient-start); background-image: linear-gradient(var(--members-gradient-angle), var(--members-gradient-start), var(--members-gradient-end)); border-top: 1px solid var(--line); border-bottom: 1px solid var(--line); }
151|        @if($contents['members_background_image']->value ?? null)
152|        #members { background-image: linear-gradient(rgba(232, 224, 209, 0.3), rgba(232, 224, 209, 0.86)), url('{{ $contents['members_background_image']->value }}'); background-size: cover; background-position: center; }
153|        @endif
154|        #achievements { background-color: var(--achievements-gradient-start); background-image: linear-gradient(var(--achievements-gradient-angle), var(--achievements-gradient-start), var(--achievements-gradient-end)); }
155|        @if($contents['achievements_background_image']->value ?? null)
156|        #achievements { background-image: linear-gradient(rgba(248, 245, 237, 0.3), rgba(248, 245, 237, 0.86)), url('{{ $contents['achievements_background_image']->value }}'); background-size: cover; background-position: center; }
157|        @endif
158|
159|        .card { background: var(--card); border: 1px solid var(--line); box-shadow: var(--shadow); transition: box-shadow 0.2s, transform 0.2s, border-color 0.2s; }
160|        .card:hover { box-shadow: var(--shadow-hover); transform: translateY(-3px); border-color: var(--accent); }
161|
162|        /* ===== ABOUT: kartu profil + kartu statistik ===== */
163|        .about-cards { display: grid; grid-template-columns: 1.4fr 1fr; gap: 1.5rem; align-items: start; }
164|        .about-card { padding: 1.75rem; }
165|        .about-card h3 { font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.25rem; font-weight: 700; margin-bottom: 0.875rem; }
166|        .about-card p { color: var(--ink-soft); line-height: 1.75; }
167|        .about-card p + p { margin-top: 0.625rem; }
168|        .about-card .kicker { font-size: 0.65rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: var(--accent); margin-bottom: 0.5rem; }
169|        .tabs { display: flex; gap: 0.375rem; margin-bottom: 1rem; border-bottom: 1px solid var(--line); }
170|        .tab { background: none; border: 0; border-bottom: 2px solid transparent; padding: 0.5rem 0.875rem; font-family: inherit; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--muted); cursor: pointer; }
171|        .tab:hover { color: var(--ink); }
172|        .tab.active { color: var(--accent); border-bottom-color: var(--accent); }
173|        .tab-panel { display: none; }
174|        .tab-panel.active { display: block; }
175|        .stats-card { display: grid; grid-template-columns: repeat(2, 1fr); }
176|        .stat-box { padding: 1.25rem 1rem; text-align: center; border-right: 1px solid var(--line); border-bottom: 1px solid var(--line); }
177|        .stat-box:nth-child(2n) { border-right: 0; }
178|        .stat-box:nth-last-child(-n+2) { border-bottom: 0; }
179|        .stat-number { font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.625rem; font-weight: 700; color: var(--accent); line-height: 1.2; }
180|        .stat-label { font-size: 0.65rem; color: var(--muted); margin-top: 0.25rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; }
181|
182|        /* ===== MEMBERS: foto kiri, data kanan ===== */
183|        .members-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 1.25rem; }
184|        .member-card { display: flex; align-items: center; gap: 1.25rem; padding: 1.25rem; }
185|        .member-photo { flex: 0 0 96px; width: 96px; height: 96px; border-radius: 50%; overflow: hidden; background: var(--ink); color: var(--paper); display: flex; align-items: center; justify-content: center; font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.75rem; font-weight: 700; }
186|        .member-photo img { width: 100%; height: 100%; object-fit: cover; }
187|        .member-info { min-width: 0; }
188|        .member-no { font-size: 0.65rem; font-weight: 700; letter-spacing: 0.1em; color: var(--accent); text-transform: uppercase; }
189|        .member-name { font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.0625rem; font-weight: 700; line-height: 1.3; margin-top: 0.125rem; }
190|        .member-role { display: inline-block; font-size: 0.68rem; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; color: var(--muted); background: rgba(200, 189, 169, 0.28); padding: 0.2rem 0.5rem; margin-top: 0.375rem; }
191|        .member-motto { font-size: 0.8125rem; color: var(--ink-soft); line-height: 1.55; margin-top: 0.5rem; padding-left: 0.625rem; border-left: 2px solid var(--accent); font-style: italic; }
192|        .member-motto:empty { display: none; }
193|
194|        /* ===== ACHIEVEMENTS: gambar atas, penjelasan bawah ===== */
195|        .achievements-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; }
196|        .achievement-card { display: flex; flex-direction: column; overflow: hidden; }
197|        .achievement-media { position: relative; aspect-ratio: 16 / 10; background: var(--ink); display: flex; align-items: center; justify-content: center; overflow: hidden; }
198|        .achievement-media img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s; }
199|        .achievement-card:hover .achievement-media img { transform: scale(1.05); }
200|        .achievement-media .icon { font-size: 2.75rem; line-height: 1; }
201|        .achievement-year { position: absolute; top: 0.75rem; left: 0.75rem; background: var(--accent); color: var(--paper); font-size: 0.65rem; font-weight: 700; letter-spacing: 0.08em; padding: 0.25rem 0.5rem; }
202|        .achievement-body { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; }
203|        .achievement-body h4 { font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.0625rem; font-weight: 700; line-height: 1.35; }
204|        .achievement-body p { font-size: 0.8125rem; color: var(--muted); line-height: 1.65; margin-top: 0.5rem; }
205|
206|        /* ===== FOOTER ===== */
207|        footer { text-align: center; padding: 2.5rem 2rem; color: var(--muted); font-size: 0.8rem; border-top: 1px solid var(--line); background-color: var(--footer-gradient-start); background-image: linear-gradient(var(--footer-gradient-angle), var(--footer-gradient-start), var(--footer-gradient-end)); }
208|        @if($contents['footer_background_image']->value ?? null)
209|        footer { background-image: linear-gradient(rgba(232, 224, 209, 0.3), rgba(232, 224, 209, 0.86)), url('{{ $contents['footer_background_image']->value }}'); background-size: cover; background-position: center; }
210|        @endif
211|
212|        /* ===== RESPONSIVE ===== */
213|        @media (max-width: 860px) {
214|            .about-cards { grid-template-columns: 1fr; }
215|        }
216|        @media (max-width: 768px) {
217|            .navbar { padding: 0.75rem 1.25rem; }
218|            .nav-toggle { display: block; }
219|            .nav-links { display: none; position: absolute; top: 100%; left: 0; right: 0; flex-direction: column; align-items: stretch; gap: 0; background: var(--paper); border-bottom: 1px solid var(--line); padding: 0.5rem 1.25rem 1rem; }
220|            .nav-links.open { display: flex; }
221|            .nav-links a { padding: 0.625rem 0; border-bottom: 1px solid rgba(200, 189, 169, 0.4); }
222|            .site-head-inner { padding: 2.5rem 1.25rem 2.25rem; }
223|            /* Banner: ruang bawah lebih lega buat dots, panah geser ke tepi. */
224|            .hero-banner-inner { padding: 2.5rem 1.25rem 4.25rem; }
225|            .hero-banner-title { font-size: clamp(1.75rem, 7.5vw, 2.25rem); }
226|            .hero-banner-text { font-size: 0.95rem; }
227|            .carousel-nav { padding: 0 1.25rem; bottom: 1.25rem; }
228|            .carousel-dot { width: 20px; }
229|            .carousel-dot.active { width: 32px; }
230|            /* Di layar sempit teksnya memenuhi lebar layar, jadi scrim horizontal
231|               tidak menutupi sisi kanan — ganti jadi vertikal. */
232|            .hero-banner-slides::after { background: linear-gradient(180deg, rgba(244, 240, 230, 0.95) 0%, rgba(244, 240, 230, 0.9) 62%, rgba(244, 240, 230, 0.82) 100%); }
233|            .news-head, .news-rail, .news-empty, .section > .wrap, .section-head { padding-left: 1.25rem; padding-right: 1.25rem; }
234|            .news-card { flex-basis: 78%; }
235|            .section { padding: 3rem 0; }
236|            .section-title { font-size: 1.625rem; }
237|            .members-grid { grid-template-columns: 1fr; }
238|            .member-card { gap: 1rem; }
239|            .member-photo { flex-basis: 76px; width: 76px; height: 76px; font-size: 1.375rem; }
240|        }
241|    </style>
242|</head>
243|<body>
244|
245|    {{-- Pesan dari middleware: akun biasa yang mencoba membuka panel. --}}
246|    @if($errors->any() || session('status'))
247|        <div class="page-notice {{ $errors->any() ? 'is-error' : 'is-ok' }}" role="status">
248|            {{ $errors->first() ?: session('status') }}
249|        </div>
250|    @endif
251|
252|    <nav class="navbar">
253|        <div class="nav-container">
254|            <a href="/" class="nav-brand">{{ $contents['nav_brand']->value ?? 'SIGMA' }}</a>
255|            <button type="button" class="nav-toggle" id="navToggle" aria-label="Menu" aria-expanded="false">&#9776;</button>
256|            <div class="nav-links" id="navLinks">
257|                <a href="#about">{{ $contents['nav_about_label']->value ?? 'About' }}</a>
258|                <a href="#members">{{ $contents['nav_members_label']->value ?? 'Anggota' }}</a>
259|                <a href="#achievements">{{ $contents['nav_achievements_label']->value ?? 'Prestasi' }}</a>
260|                {{-- Tidak ada tombol Masuk/Daftar di sini: pendaftaran dan
261|                     login hanya untuk pengelola yang membuka /register atau
262|                     /login langsung. --}}
263|            </div>
264|        </div>
265|    </nav>
266|
267|    <header class="site-header" id="hero">
268|        @if($slides->count())
269|            @php $isRotating = $slides->count() > 1; @endphp
270|            <div class="hero-banner" data-hero-banner data-interval="6000" data-reduced-motion="no-rotate"
271|                 @if($isRotating) tabindex="0" role="region" aria-roledescription="banner" aria-label="{{ $contents['hero_title']->value ?? 'SIGMA' }}"@endif>
272|                <div class="hero-banner-slides">
273|                    @foreach($slides as $i => $slide)
274|                        <article class="hero-banner-slide{{ $i === 0 ? ' active' : '' }}"
275|                                 data-hero-slide
276|                                 @if(!empty($slide['image'])) style="background-image: url('{{ $slide['image'] }}');"@endif
277|                                 aria-hidden="{{ $i === 0 ? 'false' : 'true' }}">
278|                            <div class="hero-banner-inner">
279|                                @if(!empty($slide['date']))
280|                                    <p class="hero-banner-date">{{ $slide['date'] }}</p>
281|                                @endif
282|                                <h1 class="hero-banner-title">{{ $slide['title'] }}</h1>
283|                                @if(!empty($slide['text']))
284|                                    <p class="hero-banner-text">{{ $slide['text'] }}</p>
285|                                @endif
286|                                {{-- Banner header tanpa tombol: judul, tanggal, dan
287|                                     deskripsi saja. Tautan tetap tersedia di
288|                                     rail "Kabar Terbaru" di bawahnya. --}}
289|                            </div>
290|                        </article>
291|                    @endforeach
292|                </div>
293|
294|                @if($isRotating)
295|                    {{-- Satu blok kontrol untuk seluruh banner, bukan di dalam loop slide. --}}
296|                    <div class="carousel-nav">
297|                        <div class="carousel-dots" data-hero-dots>
298|                            @foreach($slides as $i => $slide)
299|                                <button type="button" class="carousel-dot{{ $i === 0 ? ' active' : '' }}"
300|                                        data-hero-dot="{{ $i }}"
301|                                        aria-label="Banner {{ $i + 1 }}: {{ \Illuminate\Support\Str::limit($slide['title'], 40) }}"
302|                                        @if($i === 0) aria-current="true"@endif></button>
303|                            @endforeach
304|                        </div>
305|                    </div>
306|                @endif
307|            </div>
308|        @else
309|            <div class="site-head-inner">
310|                <h1>{{ $contents['hero_title']->value ?? 'SIGMA' }}</h1>
311|                <p class="tagline">{{ $contents['hero_tagline']->value ?? 'Sistem Informasi dan Manajemen Terpadu' }}</p>
312|                <p class="lede">{{ $contents['hero_description']->value ?? 'Organisasi mahasiswa yang bergerak di bidang teknologi dan informasi.' }}</p>
313|                <div class="head-actions">
314|                    {{-- Label dan tujuan harus cocok: "Pelajari Lebih" ke
315|                         Tentang Kami, bukan ke daftar anggota. --}}
316|                    <a href="#about" class="btn btn-primary">{{ $contents['hero_cta_label']->value ?? 'Pelajari Lebih' }}</a>
317|                    <a href="#achievements" class="btn btn-ghost">{{ $contents['nav_achievements_label']->value ?? 'Prestasi' }}</a>
318|                </div>
319|            </div>
320|        @endif
321|    </header>
322|
323|    <section class="news" aria-labelledby="newsTitle" id="kabar">
324|        <div class="news-head">
325|            <h2 id="newsTitle">Kabar Terbaru</h2>
326|            <span>Geser ke samping &rsaquo;</span>
327|        </div>
328|        @if($posts->count())
329|            <div class="news-rail">
330|                @foreach($posts as $post)
331|                <article class="news-card">
332|                    <div class="label">{{ $post->created_at->format('d M Y') }}</div>
333|                    <h3><a href="{{ $post->readUrl() }}"@if($post->hasExternalLink()) target="_blank" rel="noopener noreferrer"@endif>{{ $post->title }}</a></h3>
334|                    <p>{{ Illuminate\Support\Str::limit(strip_tags($post->content), 130) }}</p>
335|                    <div class="meta">
336|                        <span>{{ $post->authorName() }}</span>
337|                        <a class="news-read" href="{{ $post->readUrl() }}"@if($post->hasExternalLink()) target="_blank" rel="noopener noreferrer"@endif>Baca &rsaquo;</a>
338|                    </div>
339|                </article>
340|                @endforeach
341|            </div>
342|        @else
343|            <p class="news-empty">Belum ada kabar yang dipublikasikan.</p>
344|        @endif
345|    </section>
346|
347|
397|
398|    <section id="members" class="section">
399|        <div class="section-head">
400|            <h2 class="section-title">{{ $contents['member_section_title']->value ?? 'Anggota Kami' }}</h2>
401|            <p class="section-subtitle">{{ $contents['member_section_subtitle']->value ?? 'Orang-orang hebat yang berada di balik setiap pencapaian organisasi.' }}</p>
402|        </div>
403|        <div class="wrap members-grid">
404|            @forelse($members as $member)
405|                <article class="card member-card">
406|                    <div class="member-photo">
407|                        @if($member['photo'])
408|                            <img src="{{ $member['photo'] }}" alt="{{ $member['name'] }}" loading="lazy">
409|                        @else
410|                            {{ $member['initial'] }}
411|                        @endif
412|                    </div>
413|                    <div class="member-info">
414|                        <div class="member-no">{{ $member['code'] }}</div>
415|                        <div class="member-name">{{ $member['name'] }}</div>
416|                        <div class="member-role">{{ $member['role'] }}</div>
417|                        <p class="member-motto">{{ $member['motto'] }}</p>
418|                    </div>
419|                </article>
420|            @empty
421|                <p class="section-subtitle">Belum ada anggota terdaftar.</p>
422|            @endforelse
423|        </div>
424|    </section>
425|
426|    <section id="achievements" class="section">
427|        <div class="section-head">
428|            <h2 class="section-title">{{ $contents['achievement_section_title']->value ?? 'Prestasi Kami' }}</h2>
429|            <p class="section-subtitle">{{ $contents['achievement_section_subtitle']->value ?? 'Bukti dedikasi dan kerja keras organisasi dalam mencapai berbagai penghargaan.' }}</p>
430|        </div>
431|        <div class="wrap achievements-grid">
432|            @forelse($achievements as $achievement)
433|                <article class="card achievement-card">
434|                    <div class="achievement-media">
435|                        @if($achievement['image'])
436|                            <img src="{{ $achievement['image'] }}" alt="{{ $achievement['title'] }}" loading="lazy">
437|                        @else
438|                            <span class="icon">{{ $achievement['icon'] ?: '🏆' }}</span>
439|                        @endif
440|                        @if($achievement['year'])
441|                            <span class="achievement-year">{{ $achievement['year'] }}</span>
442|                        @endif
443|                    </div>
444|                    <div class="achievement-body">
445|                        <h4>{{ $achievement['title'] }}</h4>
446|                        <p>{{ $achievement['desc'] }}</p>
447|                    </div>
448|                </article>
449|            @empty
450|                <p class="section-subtitle">Belum ada prestasi tercatat.</p>
451|            @endforelse
452|        </div>
453|    </section>
454|
455|    <section id="about" class="section">
348|        <div class="section-head">
349|            <h2 class="section-title">{{ $contents['about_title']->value ?? 'Tentang Kami' }}</h2>
350|            <p class="section-subtitle">{{ $contents['about_subtitle']->value ?? 'Kenali lebih dalam tentang organisasi kami, visi, misi, dan nilai-nilai yang kami pegang.' }}</p>
351|        </div>
352|        <div class="wrap about-cards">
353|            <article class="card about-card">
354|                <div class="tabs" role="tablist">
355|                    <button type="button" class="tab active" data-tab="about-profile" role="tab" aria-selected="true">{{ $contents['about_slide_title']->value ?? 'Tentang Kami' }}</button>
356|                    <button type="button" class="tab" data-tab="about-vm" role="tab" aria-selected="false">{{ $contents['vision_mission_title']->value ?? 'Visi & Misi' }}</button>
357|                </div>
358|                <div class="tab-panel active" id="about-profile">
359|                    <h3>{{ $contents['about_organization_title']->value ?? 'Organisasi SIGMA' }}</h3>
360|                    <p>{{ $contents['about_description']->value ?? 'SIGMA adalah organisasi mahasiswa yang mengembangkan teknologi dan informasi.' }}</p>
361|                </div>
362|                <div class="tab-panel" id="about-vm">
363|                    <div class="kicker">Visi</div>
364|                    <p>{{ $contents['vision']->value ?? 'Menjadi organisasi teknologi terdepan yang menghasilkan karya inovatif dan berdampak positif.' }}</p>
365|                    <div class="kicker" style="margin-top: 1rem;">Misi</div>
366|                    <p>{{ $contents['mission']->value ?? 'Membangun ekosistem pembelajaran teknologi yang kolaboratif dan inklusif.' }}</p>
367|                </div>
368|            </article>
369|            <div class="card stats-card">
370|                <div class="stat-box">
371|                    <div class="stat-number">{{ $contents['stat_active_members']->value ?? '25+' }}</div>
372|                    <div class="stat-label">{{ $contents['stat_active_members_label']->value ?? 'Anggota Aktif' }}</div>
373|                </div>
374|                <div class="stat-box">
375|                    <div class="stat-number">{{ $contents['stat_projects']->value ?? '12' }}</div>
376|                    <div class="stat-label">{{ $contents['stat_projects_label']->value ?? 'Proyek Selesai' }}</div>
377|                </div>
378|                <div class="stat-box">
379|                    <div class="stat-number">{{ $contents['stat_awards']->value ?? '8' }}</div>
380|                    <div class="stat-label">{{ $contents['stat_awards_label']->value ?? 'Penghargaan' }}</div>
381|                </div>
382|                <div class="stat-box">
383|                    <div class="stat-number">{{ $contents['stat_years']->value ?? '3' }}</div>
384|                    <div class="stat-label">{{ $contents['stat_years_label']->value ?? 'Tahun Berdiri' }}</div>
385|                </div>
386|                <div class="stat-box">
387|                    <div class="stat-number">{{ $contents['stat_partnerships']->value ?? '15+' }}</div>
388|                    <div class="stat-label">{{ $contents['stat_partnerships_label']->value ?? 'Kerjasama' }}</div>
389|                </div>
390|                <div class="stat-box">
391|                    <div class="stat-number">{{ $contents['stat_dedication']->value ?? '100%' }}</div>
392|                    <div class="stat-label">{{ $contents['stat_dedication_label']->value ?? 'Dedikasi' }}</div>
393|                </div>
394|            </div>
395|        </div>
396|    </section>

    <script>
456|        // ===== HERO BANNER: geser otomatis =====
457|        // Delegasi di document supaya tetap hidup kalau node-nya diganti, dan
458|        // satu handler untuk semua banner (halaman ini cuma punya satu).
459|        (function () {
460|            const banner = document.querySelector('[data-hero-banner]');
461|            const slides = banner ? banner.querySelectorAll('[data-hero-slide]') : [];
462|
463|            if (slides.length < 2) {
464|                return; // satu slide tidak perlu digeser, tidak ada kontrolnya.
465|            }
466|
467|            const dots = banner.querySelectorAll('[data-hero-dot]');
468|            const interval = parseInt(banner.dataset.interval, 10) || 6000;
469|            let index = 0;
470|            let timer = null;
471|
472|            const reducedMotion = function () {
473|                return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
474|            };
475|
476|            function show(next) {
477|                index = (next + slides.length) % slides.length;
478|
479|                slides[index].classList.add('active');
480|                slides[index].setAttribute('aria-hidden', 'false');
481|
482|                // Slide lain disembunyikan dari screen reader, bukan cuma transparan.
483|                slides.forEach(function (slide, i) {
484|                    if (i !== index) {
485|                        slide.classList.remove('active');
486|                        slide.setAttribute('aria-hidden', 'true');
487|                    }
488|                });
489|
490|                dots.forEach(function (dot, i) {
491|                    dot.classList.toggle('active', i === index);
492|                    if (i === index) {
493|                        dot.setAttribute('aria-current', 'true');
494|                    } else {
495|                        dot.removeAttribute('aria-current');
496|                    }
497|                });
498|            }
499|
500|            function stop() {
501|                if (timer !== null) {
502|                    window.clearInterval(timer);
503|                    timer = null;
504|                }
505|            }
506|
507|            // Dipakai baik oleh timer maupun oleh klik, jadi prefs dicek di
508|            // dalam fungsi: kalau pengguna berubah setelan di tengah jalan,
509|            // resume dari hover tidak boleh menghidupkan rotasi lagi.
510|            function play() {
511|                stop();
512|                if (reducedMotion()) {
513|                    return;
514|                }
515|                timer = window.setInterval(function () {
516|                    show(index + 1);
517|                }, interval);
518|            }
519|
520|            // Hanya dots yang bisa diklik; navigasi panah lewat keyboard
521|            // (ArrowLeft/ArrowRight) di bawah, jadi tetap ada jalan manual.
522|            document.addEventListener('click', function (event) {
523|                const target = event.target instanceof Element
524|                    ? event.target.closest('[data-hero-dot]')
525|                    : null;
526|
527|                if (!target || !banner.contains(target)) {
528|                    return;
529|                }
530|
531|                show(parseInt(target.dataset.heroDot, 10));
532|
533|                // Menahan rotasi sebentar setelah klik manual, lalu lanjut lagi.
534|                stop();
535|                window.setTimeout(play, interval);
536|            });
537|
538|            banner.addEventListener('mouseenter', stop);
539|            banner.addEventListener('mouseleave', play);
540|            banner.addEventListener('focusin', stop);
541|            banner.addEventListener('focusout', function (event) {
542|                if (!banner.contains(event.relatedTarget)) {
543|                    play();
544|                }
545|            });
546|
547|            banner.addEventListener('keydown', function (event) {
548|                if (event.key === 'ArrowLeft') {
549|                    show(index - 1);
550|                    stop();
551|                    window.setTimeout(play, interval);
552|                } else if (event.key === 'ArrowRight') {
553|                    show(index + 1);
554|                    stop();
555|                    window.setTimeout(play, interval);
556|                }
557|            });
558|
559|            document.addEventListener('visibilitychange', function () {
560|                if (document.hidden) {
561|                    stop();
562|                } else {
563|                    play();
564|                }
565|            });
566|
567|            play();
568|        })();
569|
570|        // Navbar: buka/tutup menu di layar kecil.
571|        const navToggle = document.getElementById('navToggle');
572|        const navLinks = document.getElementById('navLinks');
573|        navToggle.addEventListener('click', function () {
574|            const open = navLinks.classList.toggle('open');
575|            navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
576|        });
577|        navLinks.addEventListener('click', function (event) {
578|            if (event.target.tagName === 'A') {
579|                navLinks.classList.remove('open');
580|                navToggle.setAttribute('aria-expanded', 'false');
581|            }
582|        });
583|
584|        // Tab About:.about / Visi Misi.
585|        document.querySelectorAll('.tab').forEach(function (tab) {
586|            tab.addEventListener('click', function () {
587|                document.querySelectorAll('.tab').forEach(function (other) {
588|                    other.classList.remove('active');
589|                    other.setAttribute('aria-selected', 'false');
590|                });
591|                document.querySelectorAll('.tab-panel').forEach(function (panel) { panel.classList.remove('active'); });
592|                tab.classList.add('active');
593|                tab.setAttribute('aria-selected', 'true');
594|                document.getElementById(tab.dataset.tab).classList.add('active');
595|            });
596|        });
597|    </script>
598|
599|    <footer>
600|        <p>&copy; {{ date('Y') }} {{ $contents['footer_text']->value ?? 'Organisasi SIGMA. All rights reserved.' }}</p>
601|    </footer>
602|
603|</body>
604|</html>