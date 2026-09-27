<?php

namespace Tests\Feature;

use App\Models\Achievement;
use App\Models\Member;
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
        $this->assertStringNotContainsString('carousel', $header, 'carousel sudah dihapus');
        $this->assertTrue(strpos($body, '</header>') < strpos($body, 'class="news-rail"'), 'news section setelah header');
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

        $this->assertStringNotContainsString('hero-banner', $body, 'banner lama diganti news rail');
        $this->assertStringNotContainsString('aboutSlider', $body, 'slider lama diganti tab');
        $this->assertStringNotContainsString('class="hero"', $body, 'section hero diganti header + news');
    }
}
