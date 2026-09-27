<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminNavTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_admin_page_is_reachable_from_the_navbar(): void
    {
        $this->actingAs(User::factory()->create());

        $pages = [
            'admin.dashboard' => 'Dashboard',
            'admin.posts.index' => 'Posts',
            'admin.settings.landing' => 'Landing Page',
            'admin.settings.hero' => 'Hero',
            'admin.settings.backgrounds' => 'Background',
            'admin.settings.members' => 'Member',
            'admin.settings.achievements' => 'Achievements',
        ];

        foreach ($pages as $name => $label) {
            $response = $this->get(route($name));
            $response->assertOk();

            // Halaman harus punya link kembali ke navbar, dan tidak boleh error.
            $html = $response->getContent();
            $this->assertStringNotContainsString('Whoops', $html, "$name merender halaman error");
            $this->assertStringContainsString(route($name), $html, "$name tidak punya link navbar");
        }
    }

    public function test_posts_dropdown_holds_posts_landing_hero_and_background(): void
    {
        $this->actingAs(User::factory()->create());

        $html = $this->get(route('admin.dashboard'))->assertOk()->getContent();

        $this->assertSame(1, substr_count($html, 'class="nav-dropdown"'), 'nav harus punya tepat satu dropdown');
        $this->assertStringContainsString('<summary', $html);
        $this->assertStringNotContainsString('data-bs-toggle', $html, 'dropdown tidak lagi pakai Bootstrap');
        $this->assertStringNotContainsString('bootstrap.bundle.min.js', $html);

        // Isi dropdown: All Posts, Landing Page, Hero, Background.
        $start = strpos($html, '<details class="nav-dropdown"');
        $end = strpos($html, '</details>');
        $menu = substr($html, $start, $end - $start);

        foreach (['admin.posts.index', 'admin.settings.landing', 'admin.settings.hero', 'admin.settings.backgrounds'] as $name) {
            $this->assertStringContainsString(route($name), $menu, "$name harus ada di dropdown Posts");
        }

        // Member & Achievements tetap link datar, bukan di dalam dropdown.
        $this->assertStringNotContainsString(route('admin.settings.members'), $menu);
        $this->assertStringNotContainsString(route('admin.settings.achievements'), $menu);
        $this->assertStringContainsString(route('admin.settings.members'), $html);
        $this->assertStringContainsString(route('admin.settings.achievements'), $html);
    }

    public function test_posts_dropdown_stays_open_on_its_child_pages(): void
    {
        $this->actingAs(User::factory()->create());

        foreach (['admin.posts.index', 'admin.settings.landing', 'admin.settings.hero', 'admin.settings.backgrounds'] as $name) {
            $this->get(route($name))
                ->assertOk()
                ->assertSee('<details class="nav-dropdown" open', false);
        }

        foreach (['admin.dashboard', 'admin.settings.members', 'admin.settings.achievements'] as $name) {
            $this->get(route($name))
                ->assertOk()
                ->assertSee('<details class="nav-dropdown"', false)
                ->assertDontSee('<details class="nav-dropdown" open', false);
        }
    }

    public function test_dropdown_closes_on_outside_click_and_escape(): void
    {
        $this->actingAs(User::factory()->create());
        $html = $this->get(route('admin.dashboard'))->assertOk()->getContent();

        // Handler harus menjaga target yang bukan Element (Document/text) sebelum memanggil closest.
        $this->assertStringContainsString("event.target instanceof Element ? event.target : null", $html);
        $this->assertStringContainsString("closeDropdowns(target ? target.closest('details.nav-dropdown') : null)", $html);
        $this->assertStringContainsString("event.key === 'Escape'", $html);
    }

    public function test_landing_page_settings_no_longer_edits_hero_or_achievement_fields(): void
    {
        $this->actingAs(User::factory()->create());

        $html = $this->get(route('admin.settings.landing'))->assertOk()->getContent();

        foreach ([
            'hero_title', 'hero_tagline', 'hero_description', 'hero_cta_label', 'hero_background_image',
            'achievement_section_title', 'achievement_section_subtitle',
        ] as $field) {
            $this->assertStringNotContainsString('name="' . $field . '"', $html, "$field masih ada di halaman ini");
        }

        // Field yang tersisa harus tetap bisa disimpan.
        $this->post(route('admin.settings.landing.update'), [
            'site_title' => 'Situs Baru',
            'about_title' => 'Tentang Baru',
            'stat_projects' => '42',
        ])->assertRedirect(route('admin.settings.landing'));

        $this->assertDatabaseHas('landing_contents', ['key' => 'site_title', 'value' => 'Situs Baru']);
        $this->assertDatabaseHas('landing_contents', ['key' => 'stat_projects', 'value' => '42']);
    }

    public function test_landing_page_update_ignores_hero_fields(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('admin.settings.landing.update'), [
            'site_title' => 'Situs Baru',
            'hero_title' => 'HARUS DIABAIKAN',
        ])->assertRedirect(route('admin.settings.landing'));

        $this->assertDatabaseMissing('landing_contents', ['key' => 'hero_title', 'value' => 'HARUS DIABAIKAN']);
    }
}
