<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminEntryPointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Alamat panel admin adalah /admin/dashboard. Mengetik "/admin" harus
     * mengarah ke sana, bukan 404 — inilah yang bikin panel terlihat rusak.
     */
    public function test_admin_root_redirects_to_the_dashboard_instead_of_404(): void
    {
        $this->get('/admin')->assertRedirect(route('admin.dashboard'));
        $this->get('/dashboard')->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_root_redirect_still_ends_at_login_when_guest(): void
    {
        $this->get('/admin')->assertRedirect(route('admin.dashboard'));
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_admin_root_lands_on_a_working_page_when_logged_in(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $this->get('/admin')->assertRedirect(route('admin.dashboard'));
        $this->followingRedirects()->get('/admin')->assertOk()->assertSee('Dashboard', false);
    }

    /** Bookmark lama ke halaman Hero harus tetap hidup setelah di-rename jadi Header. */
    public function test_old_hero_url_redirects_to_the_header_page(): void
    {
        $this->get('/admin/settings/hero')->assertRedirect(route('admin.settings.header'));
    }

    public function test_old_hero_url_lands_on_a_working_page_when_logged_in(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $this->followingRedirects()
            ->get('/admin/settings/hero')
            ->assertOk()
            ->assertSee('Tambah Slide', false);
    }

    /** Semua halaman admin harus bisa dibuka, bukan cuma yang ada di nav. */
    public function test_every_admin_page_loads_for_a_logged_in_user(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        foreach ([
            'admin.dashboard',
            'admin.posts.index',
            'admin.posts.create',
            'admin.settings.landing',
            'admin.settings.header',
            'admin.settings.backgrounds',
            'admin.settings.members',
            'admin.settings.achievements',
        ] as $name) {
            $this->get(route($name))->assertOk();
        }
    }
}
