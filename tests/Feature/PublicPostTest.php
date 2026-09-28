<?php

namespace Tests\Feature;

use App\Models\Achievement;
use App\Models\Member;
use App\Models\HeaderSlide;
use App\Models\LandingContent;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPostTest extends TestCase
{
    use RefreshDatabase;

    private function makePost(string $title, string $status, string $content = 'Isi artikel.'): Post
    {
        return Post::create([
            'title' => $title,
            'content' => $content,
            'status' => $status,
            'user_id' => User::factory()->create()->id,
        ]);
    }

    private function makeMember(array $attrs = []): Member
    {
        return Member::create(array_merge([
            'initial' => 'FA',
            'name' => 'Fitri Aulia',
            'role' => 'K Divisi Desain',
            'code' => 'SIGMA/2025/006',
            'motto' => 'Belajar dulu, askepan kemudian.',
            'description' => 'Desainer grafis profesional.',
        ], $attrs));
    }

    private function makeAchievement(array $attrs = []): Achievement
    {
        return Achievement::create(array_merge([
            'icon' => '🏆',
            'title' => 'Juara 1 Innovation Challenge',
            'year' => '2025',
            'description' => 'Menang dengan proyek waste tracker.',
        ], $attrs));
    }

    /** Body HTML tanpa blok <style>, supaya pencarian class tidak kena definisi CSS. */
    private function landingBody(): string
    {
        $html = $this->get(route('landing'))->assertOk()->getContent();

        return substr($html, strpos($html, '<body>'));
    }

    private function css(): string
    {
        $layout = file_get_contents(resource_path('views/public/landing.blade.php'));
        $start = strpos($layout, '<style>');

        return substr($layout, $start, strpos($layout, '</style>') - $start);
    }

    private function section(string $id): string
    {
        $body = $this->landingBody();
        $start = strpos($body, 'id="' . $id . '"');
        $end = strpos($body, '</section>', $start);

        return substr($body, $start, $end - $start);
    }

    // ===== NAVBAR =====

    public function test_navbar_is_the_first_element_on_the_page(): void
    {
        $this->makeMember();

        $body = $this->landingBody();

        $this->assertSame(1, substr_count($body, '<nav class="navbar">'));
        $this->assertTrue(
            strpos($body, '<nav class="navbar">') < strpos($body, '<header'),
            'navbar harus sebelum header'
        );
    }

    public function test_navbar_links_point_to_existing_anchors(): void
    {
        $this->makeMember();
        $this->makeAchievement();

        $body = $this->landingBody();

        foreach (['about', 'members', 'achievements'] as $id) {
            $this->assertStringContainsString('href="#' . $id . '"', $body, "nav link #{$id}");
            $this->assertStringContainsString('id="' . $id . '"', $body, "section #{$id} harus ada");
        }
    }

    // ===== BANNER KABAR (rail horizontal) =====

    public function test_news_banner_is_a_horizontal_scrolling_rail(): void
    {
        foreach (range(1, 5) as $i) {
            $this->makePost("Kabar {$i}", 'published');
        }

        $body = $this->landingBody();

        $this->assertStringContainsString('class="news-rail"', $body);
        $this->assertSame(5, substr_count($body, '<article class="news-card">'));

        $css = $this->css();
        $this->assertStringContainsString('overflow-x: auto', $css, 'rail harus bisa digeser horizontal');
        $this->assertStringContainsString('scroll-snap-type: x mandatory', $css);
    }

    public function test_news_rail_spans_the_full_page_width(): void
    {
        $css = $this->css();

        preg_match('/\.news-rail \{[^}]*\}/', $css, $m);
        $rule = $m[0] ?? '';

        $this->assertStringNotContainsString('max-width', $rule, 'rail tidak boleh dibatasi lebar, harus membentang penuh');
        $this->assertStringNotContainsString('margin: 0 auto', $rule, 'tanpa pemusatan');
        $this->assertStringContainsString('overflow-x: auto', $rule);
    }

    public function test_news_banner_is_not_narrowed_by_a_container(): void
    {
        $this->makePost('Kabar Satu', 'published');

        $news = $this->section('kabar');

        $this->assertStringContainsString('class="news-rail"', $news);
        $this->assertStringNotContainsString('wrap', $news, 'banner harus full width, bukan di dalam container sempit');
    }

    public function test_news_banner_shows_only_published_posts(): void
    {
        $this->makePost('Kabar Terbit', 'published');
        $this->makePost('Draft Tersembunyi', 'draft');
        $this->makePost('Arsip Tersembunyi', 'archived');

        $this->get(route('landing'))
            ->assertOk()
            ->assertSee('Kabar Terbit')
            ->assertDontSee('Draft Tersembunyi')
            ->assertDontSee('Arsip Tersembunyi');
    }

    public function test_news_cards_show_date_and_link_to_detail(): void
    {
        $post = $this->makePost('Kabar Detail', 'published', 'Isi kabar.');

        $this->get(route('landing'))
            ->assertOk()
            ->assertSee(now()->format('d M Y'))
            ->assertSee(route('posts.show', $post), false);
    }

    public function test_read_link_goes_to_external_blog_when_link_is_set(): void
    {
        $post = $this->makePost('Kabar Ke Blog', 'published');
        $post->update(['link' => 'https://blog.sigma.id/kabar-ke-blog']);

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringContainsString('class="news-read" href="https://blog.sigma.id/kabar-ke-blog"', $html);
        $this->assertStringContainsString('target="_blank" rel="noopener noreferrer"', $html);
        $this->assertStringNotContainsString('href="' . route('posts.show', $post) . '"', $html, 'link internal tidak dipakai saat link blog ada');
    }

    public function test_read_link_falls_back_to_internal_detail_without_link(): void
    {
        $post = $this->makePost('Kabar Internal', 'published');

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringContainsString('class="news-read" href="' . route('posts.show', $post) . '"', $html);
    }

    public function test_link_without_scheme_gets_https_prefix(): void
    {
        $post = $this->makePost('Kabar Tanpa Skema', 'published');
        $post->update(['link' => 'blog.sigma.id/tanpa-skema']);

        $this->get(route('landing'))
            ->assertOk()
            ->assertSee('https://blog.sigma.id/tanpa-skema', false);
    }

    public function test_dangerous_link_scheme_falls_back_to_internal_detail(): void
    {
        $post = $this->makePost('Kabar Berbahaya', 'published');
        $post->update(['link' => 'javascript:alert(1)']);

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringNotContainsString('javascript:alert(1)', $html);
        $this->assertStringContainsString('href="' . route('posts.show', $post) . '"', $html);
    }

    public function test_news_read_link_is_styled_not_browser_default(): void
    {
        preg_match('/\.news-card \.meta a \{[^}]*\}/', $this->css(), $m);
        $rule = $m[0] ?? '';

        $this->assertStringContainsString('text-decoration: none', $rule, 'link Baca tidak boleh biru bawaan browser');
        $this->assertStringContainsString('color: inherit', $rule);
    }

    public function test_empty_news_shows_message_not_broken_rail(): void
    {
        $body = $this->landingBody();

        $this->assertStringNotContainsString('class="news-rail"', $body);
        $this->assertStringContainsString('Belum ada kabar', $body);
    }

    public function test_news_section_is_separate_from_the_hero_header(): void
    {
        $this->makePost('Kabar Satu', 'published');

        $body = $this->landingBody();
        $headerStart = strpos($body, '<header class="site-header"');
        $headerEnd = strpos($body, '</header>', $headerStart);
        $header = substr($body, $headerStart, $headerEnd - $headerStart);

        $this->assertStringNotContainsString('news-rail', $header, 'rail tidak lagi di dalam hero');
        $this->assertStringContainsString('hero-banner', $header, 'banner ada di header');
        $this->assertTrue(strpos($body, '</header>') < strpos($body, 'class="news-rail"'), 'news section setelah header');
    }

    // ===== HERO BANNER (geser otomatis) =====

    public function test_hero_banner_shows_one_slide_per_post_with_a_single_control_block(): void
    {
        foreach (range(1, 4) as $i) {
            $this->makePost("Banner {$i}", 'published');
        }

        $body = $this->landingBody();

        $this->assertSame(4, substr_count($body, '<article class="hero-banner-slide'), 'satu slide per kabar');
        // Kontrol di luar loop: 4 slide harus tetap 1 blok nav dan 4 dots.
        $this->assertSame(1, substr_count($body, '<div class="carousel-nav">'), 'satu blok kontrol, bukan per slide');
        $this->assertSame(4, substr_count($body, 'data-hero-dot='), 'dots sama banyak dengan slide');
    }

    public function test_only_the_active_slide_is_exposed_to_screen_readers(): void
    {
        foreach (range(1, 3) as $i) {
            $this->makePost("Banner {$i}", 'published');
        }

        $body = $this->landingBody();

        $this->assertSame(1, substr_count($body, 'aria-hidden="false"'), 'tepat satu slide terbaca');
        $this->assertSame(2, substr_count($body, 'aria-hidden="true"'), 'sisanya disembunyikan');
        $this->assertSame(1, substr_count($body, 'aria-current="true"'), 'tepat satu dot aktif');
    }

    public function test_featured_posts_are_the_only_ones_in_the_banner(): void
    {
        $admin = User::factory()->create();
        foreach (['Ditonton', 'Tidak Ditonton'] as $title) {
            $this->makePost($title, 'published', 'Isi.');
        }

        $featured = Post::where('title', 'Ditonton')->first();
        $featured->update(['is_featured' => true]);

        $body = $this->landingBody();

        $this->assertSame(1, substr_count($body, '<article class="hero-banner-slide'), 'hanya post yang dicentang');
        $this->assertStringContainsString('Ditonton', $body);
    }

    public function test_banner_slides_are_absolute_and_active_one_is_relative(): void
    {
        $css = $this->css();

        preg_match('/\.hero-banner-slide \{[^}]*\}/', $css, $m);
        $this->assertStringContainsString('position: absolute', $m[0] ?? '', 'slide non-aktif ditumpuk, bukan menambah tinggi banner');

        preg_match('/\.hero-banner-slide\.active \{[^}]*\}/', $css, $a);
        $this->assertStringContainsString('position: relative', $a[0] ?? '', 'slide aktif yang memegang tinggi banner');
    }

    public function test_banner_respects_reduced_motion_in_css_and_javascript(): void
    {
        $css = $this->css();
        $this->assertStringContainsString('@media (prefers-reduced-motion: reduce)', $css, 'CSS harus meredam animasi');

        $view = file_get_contents(resource_path('views/public/landing.blade.php'));
        // Prefs dicek di dalam play(), bukan hanya sekali di load: kalau dicek
        // sekali, resume dari hover akan menghidupkan rotasi lagi.
        preg_match('/function play\(\) \{\s*stop\(\);\s*if \(reducedMotion\(\)\)/', $view, $m);
        $this->assertNotEmpty($m, 'resume rotasi harus mengecek prefers-reduced-motion');
        $this->assertStringContainsString("window.matchMedia('(prefers-reduced-motion: reduce)')", $view);
    }

    public function test_banner_pauses_on_hover_and_focus_and_keeps_a_keyboard_way_out(): void
    {
        $view = file_get_contents(resource_path('views/public/landing.blade.php'));

        foreach (["banner.addEventListener('mouseenter', stop)", "banner.addEventListener('mouseleave', play)",
                  "banner.addEventListener('focusin', stop)"] as $hook) {
            $this->assertStringContainsString($hook, $view);
        }
        $this->assertStringContainsString("'visibilitychange'", $view, 'rotasi berhenti saat tab disembunyikan');

        // Tombol panah dihapus, tapi rotasi wajib masih punya jalan
        // manual: klik dot dan tombol panah keyboard di banner yang terfokus.
        $this->assertStringContainsString('[data-hero-dot]', $view, 'dot bisa diklik');
        $this->assertStringContainsString("'ArrowLeft'", $view);
        $this->assertStringContainsString("'ArrowRight'", $view);
    }

    public function test_banner_text_sits_on_a_scrim_so_it_stays_readable_over_any_photo(): void
    {
        $css = $this->css();

        // Warna header hanya 25% opaque di atas; tanpa scrim, teks banner
        // jadi dark-on-dark di foto gelap (terukur 1.87:1 sebelum scrim).
        preg_match('/\.hero-banner-slides::after \{[^}]*\}/', $css, $m);
        $this->assertNotEmpty($m, 'banner harus punya scrim di atas gambar');
        preg_match_all('/rgba\(244, 240, 230, (0\.\d+)\)/', $m[0], $op);
        $this->assertNotEmpty($op[1], 'scrim memakai kabut hangat, bukan hitam');
        $this->assertGreaterThanOrEqual(0.9, (float) max($op[1]), 'scrim di sisi teks harus hampir opaque');

        // Konten dan kontrol harus berada di atas scrim.
        $this->assertMatchesRegularExpression(
            '/\.hero-banner-inner \{[^}]*position: relative[^}]*z-index: 2/',
            $css,
            'z-index tanpa position tidak berlaku, teks akan tenggelam di bawah scrim'
        );
    }

    public function test_header_slides_win_over_posts_as_banner_content(): void
    {
        $this->makePost('Kabar Latest', 'published', 'Isi kabar.');

        HeaderSlide::create([
            'title' => 'Slide Header',
            'description' => 'Deskripsi header.',
            'image' => '/images/header/satu.jpg',
            'link' => 'blog.sigma.id/artikel',
            'cta_label' => 'Lihat Detail',
            'sort_order' => 1,
        ]);

        $body = $this->landingBody();

        $this->assertStringContainsString('Slide Header', $body, 'slide header yang dipakai');
        $this->assertStringContainsString('Lihat Detail', $body, 'label tombol ikut dari slide');
        $this->assertStringContainsString('/images/header/satu.jpg', $body, 'gambar slide dipakai sebagai latar');
        $this->assertStringNotContainsString('Kabar Latest', substr($body, 0, strpos($body, 'news-rail')), 'kabar tidak lagi jadi banner');
    }

    public function test_inactive_header_slide_is_skipped(): void
    {
        HeaderSlide::create(['title' => 'Aktif', 'is_active' => true, 'sort_order' => 1]);
        HeaderSlide::create(['title' => 'Mati', 'is_active' => false, 'sort_order' => 2]);

        $body = $this->landingBody();

        $this->assertStringContainsString('Aktif', $body);
        $this->assertStringNotContainsString('Mati', $body, 'slide nonaktif tidak boleh tampil');
    }

    public function test_header_slides_are_ordered_by_sort_order(): void
    {
        HeaderSlide::create(['title' => 'Kedua', 'sort_order' => 2]);
        HeaderSlide::create(['title' => 'Pertama', 'sort_order' => 1]);

        $body = $this->landingBody();
        $this->assertLessThan(
            strpos($body, 'Kedua'),
            strpos($body, 'Pertama'),
            'urutan kecil harus tampil lebih dulu'
        );
    }

    public function test_banner_falls_back_to_latest_posts_when_there_is_no_header_slide(): void
    {
        foreach (range(1, 7) as $i) {
            $this->makePost("Kabar {$i}", 'published', 'Isi.');
        }

        $this->assertSame(5, substr_count($this->landingBody(), '<article class="hero-banner-slide'), 'tetap 5 slide dari kabar');
    }

    public function test_header_slide_without_link_shows_no_button_at_all(): void
    {
        HeaderSlide::create(['title' => 'Tanpa Link', 'is_active' => true]);

        $body = $this->landingBody();
        $header = substr($body, strpos($body, '<header class="site-header"'), strpos($body, '</header>'));

        // Tanpa link, tidak ada tombol sama sekali — lebih baik daripada
        // tombol generik yang tidak berasal dari data slide.
        $this->assertStringNotContainsString('btn-primary', $header, 'tidak ada tombol utama kalau slide tanpa link');
        $this->assertStringNotContainsString('btn-ghost', $header, 'tidak ada tombol tambahan yang hardcode');
        // Wadah tombol kosong menyisakan ruang kosong di bawah deskripsi.
        $this->assertStringNotContainsString('head-actions', $header, 'wadah tombol tidak perlu dirender');
    }

    public function test_header_buttons_come_from_the_slide_and_not_from_hardcoded_anchors(): void
    {
        foreach (range(1, 2) as $i) {
            HeaderSlide::create([
                'title' => "Slide {$i}",
                'link' => "blog.sigma.id/artikel-{$i}",
                'cta_label' => "Buka {$i}",
                'is_active' => true,
            ]);
        }

        $body = $this->landingBody();
        $header = substr($body, strpos($body, '<header class="site-header"'), strpos($body, '</header>'));

        // Tombol Member lama di-hardcode ke #members dan tidak bisa diatur dari
        // admin; sekarang tidak boleh ada.
        $this->assertStringNotContainsString('href="#members"', $header, 'tidak ada tombol anchor hardcode di header');
        $this->assertStringContainsString('https://blog.sigma.id/artikel-1', $header, 'tombol ikut link slide');
        $this->assertStringContainsString('Buka 1', $header, 'label tombol ikut slide');
    }

    public function test_post_slides_do_not_borrow_the_fallback_hero_cta_label(): void
    {
        // hero_cta_label = "Pelajari Lebih" milik hero cadangan. Kalau dipakai
        // untuk slide berita, tombolnya(label) tidak cocok dengan tujuan(artikel).
        LandingContent::updateOrCreate(['key' => 'hero_cta_label'], ['value' => 'Pelajari']);

        Post::create([
            'user_id' => User::factory()->create()->id,
            'title' => 'Kabar Baru SIGMA',
            'content' => 'Isi kabar.',
            'status' => 'published',
            'is_featured' => true,
        ]);

        $body = $this->landingBody();
        $this->assertStringContainsString('Baca Selengkapnya', $body, 'slide berita memakai label yang cocok');
        $this->assertStringNotContainsString('>Pelajari<', $body, 'label hero cadangan tidak ikut dipakai di slide berita');
    }

    public function test_fallback_hero_cta_points_where_its_label_promises(): void
    {
        // Tanpa post dan tanpa slide header, hero statis yang tampil.
        $body = $this->landingBody();
        $header = substr($body, strpos($body, '<header class="site-header"'), strpos($body, '</header>'));

        // "Pelajari Lebih" harus ke Tentang Kami, bukan ke daftar anggota.
        $this->assertStringContainsString('href="#about"', $header, 'CTA "Pelajari Lebih" menuju Tentang Kami');
        $this->assertStringNotContainsString('href="#members"', $header, 'tidak lagi melompat ke daftar anggota');
        $this->assertStringContainsString('href="#achievements"', $header, 'tombol Prestasi tetap ada');
    }

    public function test_carousel_has_no_arrow_buttons_only_dots(): void
    {
        foreach (range(1, 3) as $i) {
            $this->makePost("Banner {$i}", 'published');
        }

        $body = $this->landingBody();
        $css = $this->css();

        $this->assertStringNotContainsString('data-hero-prev', $body, 'tidak ada tombol panah kiri');
        $this->assertStringNotContainsString('data-hero-next', $body, 'tidak ada tombol panah kanan');
        $this->assertStringNotContainsString('carousel-arrow', $body, 'tidak ada markup tombol panah');
        $this->assertStringNotContainsString('.carousel-arrow', $css, 'CSS tombol panah ikut dibuang');
        $this->assertStringNotContainsString('.carousel-arrows', $css, 'grup panah ikut dibuang');

        // Dots tetap ada: penanda posisi sekaligus satu-satunya kontrol manual.
        // Pakai atributnya — prefix "class=\"carousel-dot" juga cocok dengan
        // elemen pembungkus carousel-dots.
        $this->assertSame(3, substr_count($body, 'data-hero-dot="'), 'satu dot per slide');
        $this->assertSame(1, substr_count($body, '<div class="carousel-dots"'), 'satu blok dots');
    }

    public function test_banner_is_a_landmark_with_a_focus_ring(): void
    {
        foreach (range(1, 2) as $i) {
            $this->makePost("Banner {$i}", 'published');
        }

        $body = $this->landingBody();

        $this->assertStringContainsString('role="region"', $body);
        $this->assertStringContainsString('aria-roledescription="banner"', $body);
        $this->assertStringContainsString('tabindex="0"', $body, 'banner bisa difokus untuk kontrol keyboard');
        $this->assertStringContainsString('.hero-banner:focus-visible', $this->css());
    }

    // ===== ABOUT SEBAGAI CARD =====

    public function test_about_uses_cards(): void
    {
        $this->assertStringContainsString('card about-card', $this->section('about'), 'about harus card');
        $this->assertStringContainsString('stats-card', $this->section('about'), 'statistik juga card');
    }

    public function test_about_tabs_switch_panels(): void
    {
        $body = $this->landingBody();

        $this->assertSame(2, substr_count($body, '<button type="button" class="tab'), 'dua tab');
        $this->assertStringContainsString('tab-panel active', $body, 'panel pertama aktif');
        $this->assertStringContainsString('data-tab="about-vm"', $body);
    }

    // ===== MEMBER: FOTO KIRI, DATA KANAN =====

    public function test_member_card_has_photo_before_info(): void
    {
        $this->makeMember();

        $body = $this->landingBody();
        $card = substr($body, strpos($body, '<article class="card member-card">'), 400);

        $photo = strpos($card, 'member-photo');
        $info = strpos($card, 'member-info');

        $this->assertNotFalse($photo, 'kartu harus punya member-photo');
        $this->assertTrue($photo < $info, 'foto harus sebelum info (di sebelah kiri)');
    }

    public function test_member_card_lays_out_horizontally(): void
    {
        preg_match('/\.member-card \{[^}]*\}/', $this->css(), $m);
        $rule = $m[0] ?? '';

        $this->assertStringContainsString('display: flex', $rule, 'bukan column');
        $this->assertStringNotContainsString('flex-direction: column', $rule);
    }

    public function test_member_card_shows_number_name_division_and_motto(): void
    {
        $this->makeMember();

        $body = $this->landingBody();

        $this->assertStringContainsString('member-no">SIGMA/2025/006', $body, 'nomor');
        $this->assertStringContainsString('member-name">Fitri Aulia', $body, 'nama');
        $this->assertStringContainsString('member-role">K Divisi Desain', $body, 'divisi');
        $this->assertStringContainsString('Belajar dulu, askepan kemudian.', $body, 'kata motivasi');
    }

    public function test_member_photo_falls_back_to_initial(): void
    {
        $this->makeMember(['photo' => null, 'initial' => 'ZA']);

        $this->get(route('landing'))->assertOk()->assertSee('ZA');
    }

    public function test_member_motto_is_optional(): void
    {
        $this->makeMember(['motto' => null]);

        $this->get(route('landing'))->assertOk()->assertSee('Fitri Aulia');
    }

    public function test_bio_is_not_shown_on_member_card(): void
    {
        // Kartu hanya menampilkan no/nama/divisi/motto, bukan bio panjang.
        $this->makeMember();

        $this->get(route('landing'))->assertOk()->assertDontSee('Desainer grafis profesional.');
    }

    // ===== PRESTASI: GAMBAR ATAS, PENJELASAN BAWAH =====

    public function test_achievement_card_has_media_before_body(): void
    {
        $this->makeAchievement();

        $body = $this->landingBody();
        $card = substr($body, strpos($body, '<article class="card achievement-card">'), 500);

        $media = strpos($card, 'achievement-media');
        $cardBody = strpos($card, 'achievement-body');

        $this->assertNotFalse($media, 'kartu prestasi harus punya media');
        $this->assertTrue($media < $cardBody, 'gambar harus di atas penjelasan');
    }

    public function test_achievement_card_stacks_vertically(): void
    {
        preg_match('/\.achievement-card \{[^}]*\}/', $this->css(), $m);
        $rule = $m[0] ?? '';

        $this->assertStringContainsString('flex-direction: column', $rule, 'gambar atas, teks bawah');
    }

    public function test_achievement_media_has_fixed_ratio_and_cover_fit(): void
    {
        $css = $this->css();

        preg_match('/\.achievement-media \{[^}]*\}/', $css, $m);
        $rule = $m[0] ?? '';

        $this->assertStringContainsString('aspect-ratio', $rule, 'rasio tetap supaya tinggi kartu seragam');
        $this->assertStringContainsString('object-fit: cover', $css, 'gambar tidak gepeng');
    }

    public function test_achievement_shows_image_when_set(): void
    {
        $this->makeAchievement(['image' => '/images/achievements/juara-1.jpg']);

        $this->get(route('landing'))
            ->assertOk()
            ->assertSee('/images/achievements/juara-1.jpg', false);
    }

    public function test_achievement_falls_back_to_icon_without_image(): void
    {
        $this->makeAchievement(['image' => null, 'icon' => '🥈']);

        $this->get(route('landing'))->assertOk()->assertSee('🥈');
    }

    public function test_achievement_shows_year_badge_and_description(): void
    {
        $this->makeAchievement(['year' => '2025', 'description' => 'Menang dengan proyek waste tracker.']);

        $this->get(route('landing'))
            ->assertOk()
            ->assertSee('2025')
            ->assertSee('Menang dengan proyek waste tracker.');
    }

    // ===== DETAIL POST & KEAMANAN (regression) =====

    public function test_published_post_has_public_detail_page(): void
    {
        $post = $this->makePost('Artikel Migrasi', 'published', 'Paragraf pertama.\n\nParagraf kedua.');

        $this->get(route('posts.show', $post))
            ->assertOk()
            ->assertSee('Artikel Migrasi')
            ->assertSee('Paragraf kedua.')
            ->assertSee(route('landing'), false, 'harus ada link kembali ke beranda');
    }

    public function test_draft_and_archived_posts_are_404_for_public(): void
    {
        foreach (['draft', 'archived'] as $status) {
            $post = $this->makePost("Rahasia {$status}", $status);

            $this->get(route('posts.show', $post))->assertNotFound();
            $this->assertGuest();
        }
    }

    public function test_post_detail_escapes_html_in_content(): void
    {
        $post = $this->makePost('XSS Test', 'published', '<script>alert(1)</script>');

        $html = $this->get(route('posts.show', $post))->assertOk()->getContent();

        $this->assertStringNotContainsString('<script>alert(1)</script>', $html);
    }

    public function test_news_excerpt_strips_html(): void
    {
        $this->makePost('Excerpt Test', 'published', '<p>Paragraf <b>tebal</b> yang panjang.</p>');

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringNotContainsString('<b>tebal</b>', $html);
    }

    public function test_news_eager_loads_user_to_avoid_n_plus_one(): void
    {
        $source = file_get_contents(app_path('Http/Controllers/LandingPageController.php'));

        $this->assertStringContainsString("Post::with('user')", $source);
    }

    public function test_no_legacy_hero_or_slider_markup_left(): void
    {
        $body = $this->landingBody();

        $this->assertStringNotContainsString('aboutSlider', $body, 'slider lama diganti tab');
        $this->assertStringNotContainsString('class="hero"', $body, 'section hero diganti header + news');
    }
}
