<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGMA - Sistem Informasi Terpadu</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #0f172a; color: #f8fafc; }

        /* ===== NAVBAR ===== */
        .navbar { background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(255,255,255,0.1); padding: 1rem 2rem; position: sticky; top: 0; z-index: 100; }
        .nav-container { max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; }
        .nav-brand { font-size: 1.5rem; font-weight: 800; color: #38bdf8; text-decoration: none; }
        .nav-links { display: flex; gap: 2rem; }
        .nav-links a { color: #94a3b8; text-decoration: none; font-size: 0.875rem; transition: color 0.2s; }
        .nav-links a:hover { color: #38bdf8; }
        .nav-admin { background: #3b82f6; color: white; padding: 0.5rem 1.25rem; border-radius: 6px; text-decoration: none; font-size: 0.875rem; font-weight: 500; }
        .nav-admin:hover { background: #2563eb; }

        /* ===== HERO ===== */
        .hero { min-height: 80vh; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 4rem 2rem; background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%); position: relative; }
        .hero::before { content: ''; position: absolute; width: 600px; height: 600px; background: radial-gradient(circle, rgba(59,130,246,0.1) 0%, transparent 70%); top: -100px; right: -100px; border-radius: 50%; }
        .hero::after { content: ''; position: absolute; width: 400px; height: 400px; background: radial-gradient(circle, rgba(139,92,246,0.08) 0%, transparent 70%); bottom: -50px; left: -50px; border-radius: 50%; }
        .hero h1 { font-size: 4.5rem; font-weight: 900; margin-bottom: 1rem; background: linear-gradient(135deg, #3b82f6, #8b5cf6, #38bdf8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; position: relative; z-index: 1; }
        .hero .tagline { font-size: 1.5rem; color: #38bdf8; font-weight: 600; margin-bottom: 1rem; position: relative; z-index: 1; }
        .hero p { font-size: 1.125rem; color: #94a3b8; max-width: 600px; line-height: 1.7; margin-bottom: 2rem; position: relative; z-index: 1; }
        .cta { display: flex; gap: 1rem; position: relative; z-index: 1; }
        .cta a { padding: 0.875rem 2rem; border-radius: 8px; font-size: 1rem; text-decoration: none; transition: all 0.2s; font-weight: 500; }
        .cta-primary { background: #3b82f6; color: white; }
        .cta-primary:hover { background: #2563eb; transform: translateY(-2px); }
        .cta-secondary { background: transparent; color: #cbd5e1; border: 1px solid #475569; }
        .cta-secondary:hover { border-color: #94a3b8; color: #f8fafc; }

        /* ===== SECTIONS ===== */
        .section { padding: 5rem 2rem; max-width: 1200px; margin: 0 auto; }
        .section-title { text-align: center; font-size: 2.25rem; font-weight: 800; margin-bottom: 1rem; color: #fff; }
        .section-subtitle { text-align: center; color: #94a3b8; font-size: 1.125rem; max-width: 600px; margin: 0 auto 3rem; }

        /* ===== ABOUT ===== */
        .about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center; }
        .about-text h3 { font-size: 1.75rem; font-weight: 700; margin-bottom: 1.5rem; color: #38bdf8; }
        .about-text p { color: #cbd5e1; line-height: 1.8; margin-bottom: 1rem; }
        .about-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
        .stat-box { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 1.5rem; text-align: center; }
        .stat-number { font-size: 2rem; font-weight: 800; color: #38bdf8; }
        .stat-label { font-size: 0.75rem; color: #94a3b8; margin-top: 0.25rem; }

        /* ===== MEMBERS ===== */
        .members-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem; }
        .member-card { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 2rem; text-align: center; transition: all 0.3s; }
        .member-card:hover { border-color: #38bdf8; transform: translateY(-4px); background: rgba(255,255,255,0.06); }
        .member-avatar { width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6, #8b5cf6); display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 800; color: white; margin: 0 auto 1rem; }
        .member-name { font-size: 1.125rem; font-weight: 700; color: #fff; margin-bottom: 0.25rem; }
        .member-role { font-size: 0.875rem; color: #38bdf8; font-weight: 500; margin-bottom: 0.75rem; }
        .member-desc { font-size: 0.875rem; color: #94a3b8; line-height: 1.5; }

        /* ===== ACHIEVEMENTS ===== */
        .achievements-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem; }
        .achievement-card { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 1.5rem; display: flex; gap: 1rem; align-items: flex-start; transition: all 0.3s; }
        .achievement-card:hover { border-color: #4ade80; background: rgba(255,255,255,0.06); }
        .achievement-icon { width: 48px; height: 48px; border-radius: 12px; background: rgba(74, 222, 128, 0.15); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; }
        .achievement-content h4 { font-size: 1rem; font-weight: 700; color: #fff; margin-bottom: 0.25rem; }
        .achievement-content .year { font-size: 0.75rem; color: #4ade80; font-weight: 600; margin-bottom: 0.5rem; }
        .achievement-content p { font-size: 0.875rem; color: #94a3b8; line-height: 1.5; }

        /* ===== FOOTER ===== */
        footer { text-align: center; padding: 3rem 2rem; color: #64748b; font-size: 0.875rem; border-top: 1px solid rgba(255,255,255,0.05); }
        footer a { color: #38bdf8; text-decoration: none; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .hero h1 { font-size: 2.5rem; }
            .hero .tagline { font-size: 1.125rem; }
            .about-grid { grid-template-columns: 1fr; }
            .nav-links { display: none; }
        }
    </style>
</head>
<body>

    <!-- ========================================
         NAVBAR
         Edit: Ubah link navigasi di sini
    ======================================== -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="/landing" class="nav-brand">SIGMA</a>
            <div class="nav-links">
                <a href="#about">About</a>
                <a href="#members">Anggota</a>
                <a href="#achievements">Prestasi</a>
            </div>
            <a href="/login" class="nav-admin">Login Admin</a>
        </div>
    </nav>

    <!-- ========================================
         HERO SECTION
         Edit: Ubah judul, tagline, deskripsi
    ======================================== -->
    <section class="hero">
        <h1>SIGMA</h1>
        <p class="tagline">Sistem Informasi dan Manajemen Terpadu</p>
        <p>Organisasi mahasiswa yang bergerak di bidang teknologi dan informasi, berkomitmen menghasilkan karya inovatif dan berkontribusi untuk kemajuan teknologi di Indonesia.</p>
        <div class="cta">
            <a href="#about" class="cta-primary">Pelajari Lebih</a>
            <a href="/login" class="cta-secondary">Masuk Admin</a>
        </div>
    </section>

    <!-- ========================================
         ABOUT SECTION
         Edit: Ubah nama organisasi, deskripsi, visi/misi
    ======================================== -->
    <section id="about" class="section">
        <h2 class="section-title">Tentang Kami</h2>
        <p class="section-subtitle">Kenali lebih dalam tentang organisasi kami, visi, misi, dan nilai-nilai yang kami pegang.</p>
        <div class="about-grid">
            <div class="about-text">
                <h3>Organisasi SIGMA</h3>
                <p>SIGMA (Sistem Informasi dan Manajemen Terpadu) adalah sebuah organisasi mahasiswa yang berfokus pada pengembangan teknologi informasi. Kami beranggotakan para mahasiswa yang memiliki passion di bidang pemrograman, desain, dan manajemen proyek teknologi.</p>
                <p>Didirikan sejak tahun 2024, SIGMA telah menghasilkan berbagai produk digital dan telah berkontribusi dalam berbagai kompetisi teknologi tingkat nasional. Kami percaya bahwa teknologi dapat menjadi jembatan untuk menyelesaikan berbagai permasalahan di masyarakat.</p>
                <p><strong>Visi:</strong> Menjadi organisasi teknologi terdepan yang menghasilkan inovator-inovator muda berintegritas.</p>
                <p><strong>Misi:</strong> Membangun ekosistem pembelajaran teknologi yang kolaboratif, inklusif, dan berdampak positif bagi masyarakat.</p>
            </div>
            <div class="about-stats">
                <!-- Edit: Ubah angka statistik di bawah -->
                <div class="stat-box">
                    <div class="stat-number">25+</div>
                    <div class="stat-label">Anggota Aktif</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">12</div>
                    <div class="stat-label">Proyek Selesai</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">8</div>
                    <div class="stat-label">Penghargaan</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">3</div>
                    <div class="stat-label">Tahun Berdiri</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">15+</div>
                    <div class="stat-label">Kerjasama</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">Dedikasi</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================
         MEMBERS SECTION
         Edit: Ubah data anggota di bawah
         Format: Avatar Inisial, Nama, Jabatan, Deskripsi
    ======================================== -->
    <section id="members" class="section" style="background: rgba(255,255,255,0.02); border-radius: 24px; padding: 3rem 2rem;">
        <h2 class="section-title">Anggota Kami</h2>
        <p class="section-subtitle">Orang-orang hebat yang berada di balik setiap pencapaian organisasi.</p>
        <div class="members-grid">

            <!-- Anggota 1 - Ketua -->
            <div class="member-card">
                <div class="member-avatar">AI</div>
                <div class="member-name">Ahmad Ihsan</div>
                <div class="member-role">Ketua Organisasi</div>
                <div class="member-desc">Mahasiswa Teknik Informatika yang memiliki pengalaman memimpin tim pengembangan aplikasi web dan mobile selama 3 tahun.</div>
            </div>

            <!-- Anggota 2 -->
            <div class="member-card">
                <div class="member-avatar">BN</div>
                <div class="member-name">Bella Nadira</div>
                <div class="member-role">Wakil Ketua</div>
                <div class="member-desc">Spesialis UI/UX Design dengan portfolio lebih dari 20 proyek desain untuk startup dan perusahaan lokal.</div>
            </div>

            <!-- Anggota 3 -->
            <div class="member-card">
                <div class="member-avatar">CR</div>
                <div class="member-name">Candra Rizky</div>
                <div class="member-role">Sekretaris</div>
                <div class="member-desc">Mengelola administrasi organisasi dan koordinasi event, aktif di kepanitiaan tingkat universitas.</div>
            </div>

            <!-- Anggota 4 -->
            <div class="member-card">
                <div class="member-avatar">DP</div>
                <div class="member-name">Dian Permata</div>
                <div class="member-role">Bendahara</div>
                <div class="member-desc">Ahli keuangan yang mengatur anggaran dan sponsorship, berpengalaman di organisasi kemahasiswaan.</div>
            </div>

            <!-- Anggota 5 -->
            <div class="member-card">
                <div class="member-avatar">EH</div>
                <div class="member-name">Eka Hidayat</div>
                <div class="member-role">K Divisi Teknologi</div>
                <div class="member-desc">Full-stack developer dengan keahlian Laravel, React, dan Flutter. Juara 2 Hackathon Nasional 2025.</div>
            </div>

            <!-- Anggota 6 -->
            <div class="member-card">
                <div class="member-avatar">FA</div>
                <div class="member-name">Fitri Aulia</div>
                <div class="member-role">K Divisi Desain</div>
                <div class="member-desc">Desainer grafis profesional, berpengalaman membuat branding dan konten visual untuk berbagai komunitas.</div>
            </div>

            <!-- Anggota 7 -->
            <div class="member-card">
                <div class="member-avatar">GH</div>
                <div class="member-name">Galih Prasetyo</div>
                <div class="member-role">K Divisi Humas</div>
                <div class="member-desc">Menangani hubungan masyarakat, media sosial, dan kerjasama eksternal dengan berbagai stakeholder.</div>
            </div>

            <!-- Anggota 8 -->
            <div class="member-card">
                <div class="member-avatar">HN</div>
                <div class="member-name">Hana Nurhaliza</div>
                <div class="member-role">Staf Ahli</div>
                <div class="member-desc">Mahasiswa Ilmu Komputer yang aktif mengajar programming dasar untuk anggota baru setiap semester.</div>
            </div>

        </div>
    </section>

    <!-- ========================================
         ACHIEVEMENTS SECTION
         Edit: Ubah data prestasi di bawah
    ======================================== -->
    <section id="achievements" class="section">
        <h2 class="section-title">Prestasi Kami</h2>
        <p class="section-subtitle">Bukti dedikasi dan kerja keras organisasi dalam mencapai berbagai penghargaan.</p>
        <div class="achievements-grid">

            <!-- Prestasi 1 -->
            <div class="achievement-card">
                <div class="achievement-icon">🏆</div>
                <div class="achievement-content">
                    <h4>Juara 1 - Nasional Innovation Challenge 2025</h4>
                    <div class="year">2025</div>
                    <p>Kompetisi inovasi teknologi tingkat nasional yang diselenggarakan oleh Kementerian Pendidikan. Tim SIGMA membawa aplikasi manajemen sampah berbasis AI.</p>
                </div>
            </div>

            <!-- Prestasi 2 -->
            <div class="achievement-card">
                <div class="achievement-icon">🥈</div>
                <div class="achievement-content">
                    <h4>Juara 2 - Hackathon Indonesia Digital 2025</h4>
                    <div class="year">2025</div>
                    <p>Hackathon 48 jam dengan tema "Digital Solutions for Education". Tim berhasil membuat platform e-learning interaktif.</p>
                </div>
            </div>

            <!-- Prestasi 3 -->
            <div class="achievement-card">
                <div class="achievement-icon">🥉</div>
                <div class="achievement-content">
                    <h4>Juara 3 - Lomba Cipta Aplikasi 2024</h4>
                    <div class="year">2024</div>
                    <p>Lomba pembuatan aplikasi tingkat provinsi yang diikuti 50+ tim dari berbagai universitas ternama.</p>
                </div>
            </div>

            <!-- Prestasi 4 -->
            <div class="achievement-card">
                <div class="achievement-icon">🎖️</div>
                <div class="achievement-content">
                    <h4>Best Innovation Award - Tech Fest 2025</h4>
                    <div class="year">2025</div>
                    <p>Penghargaan inovasi terbaik dalam acara festival teknologi yang diselenggarakan universitas negeri ternama.</p>
                </div>
            </div>

            <!-- Prestasi 5 -->
            <div class="achievement-card">
                <div class="achievement-icon">🌟</div>
                <div class="achievement-content">
                    <h4>1000 Startup Digital - Top 100</h4>
                    <div class="year">2024</div>
                    <p>Berhasil masuk 100 besar program pencetakan startup digital yang diselenggarakan Kominfo Indonesia.</p>
                </div>
            </div>

            <!-- Prestasi 6 -->
            <div class="achievement-card">
                <div class="achievement-icon">📜</div>
                <div class="achievement-content">
                    <h4>Sertifikasi Internasional - Google IT Support</h4>
                    <div class="year">2025</div>
                    <p>15 anggota berhasil mendapatkan sertifikasi Google IT Support sebagai bukti kompetensi di bidang teknologi informasi.</p>
                </div>
            </div>

            <!-- Prestasi 7 -->
            <div class="achievement-card">
                <div class="achievement-icon">🚀</div>
                <div class="achievement-content">
                    <h4>Most Active Community - Developer Circle</h4>
                    <div class="year">2024</div>
                    <p>Diakui oleh Facebook Developer Circle sebagai komunitas teknologi paling aktif di regional masing-masing.</p>
                </div>
            </div>

            <!-- Prestasi 8 -->
            <div class="achievement-card">
                <div class="achievement-icon">💡</div>
                <div class="achievement-content">
                    <h4>Best Paper - Seminar Nasional Informatika</h4>
                    <div class="year">2024</div>
                    <p>Publikasi jurnal ilmiah tentang implementasi machine learning untuk klasifikasi penyakit tanaman.</p>
                </div>
            </div>

        </div>
    </section>

    <!-- ========================================
         FOOTER
         Edit: Ubah teks footer, copyright, atau link
    ======================================== -->
    <footer>
        <p>&copy; 2026 Organisasi SIGMA. All rights reserved.</p>
        <p style="margin-top: 0.5rem;">Designed and maintained by <a href="#">SIGMA Tech Team</a></p>
    </footer>

</body>
</html>
