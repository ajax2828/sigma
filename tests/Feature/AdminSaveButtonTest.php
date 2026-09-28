<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSaveButtonTest extends TestCase
{
    use RefreshDatabase;

    private function layoutCss(): string
    {
        $layout = file_get_contents(resource_path('views/admin/layouts/app.blade.php'));
        $start = strpos($layout, '<style>');

        return substr($layout, $start, strpos($layout, '</style>') - $start);
    }

    /** Halaman admin yang punya tombol simpan, dibangun dari route() bukan path tebakan. */
    private function savePages(): array
    {
        return [
            route('admin.posts.create'),
            route('admin.posts.edit', ['post' => $this->post->id]),
            route('admin.settings.landing'),
            route('admin.settings.header'),
            route('admin.settings.backgrounds', ['section' => 'hero']),
            route('admin.settings.members'),
            route('admin.settings.achievements'),
        ];
    }

    private User $admin;
    private Post $post;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create();
        $this->post = Post::create([
            'title' => 'Post untuk diedit',
            'content' => 'Isi.',
            'status' => 'published',
            'user_id' => $this->admin->id,
        ]);
    }

    private function html(string $url): string
    {
        $this->actingAs($this->admin);

        $response = $this->get($url);
        $this->assertSame(200, $response->getStatusCode(), "halaman {$url} tidak bisa diakses");

        return $response->getContent();
    }

    public function test_every_save_button_uses_the_single_component(): void
    {
        foreach ($this->savePages() as $url) {
            $html = $this->html($url);

            // btn-login pernah dipakai di halaman ini tapi tidak pernah ada di layout.
            $this->assertStringNotContainsString('class="btn-login', $html, "{$url} masih pakai btn-login");
            $this->assertStringContainsString('class="btn-save', $html, "{$url} tidak punya tombol btn-save");
        }
    }

    public function test_save_button_markup_has_icon_and_label(): void
    {
        foreach ($this->savePages() as $url) {
            $html = $this->html($url);

            preg_match_all('/<button[^>]*class="btn-save[^"]*"[^>]*>(.*?)<\/button>/s', $html, $m);
            $this->assertNotEmpty($m[1], "{$url} tidak punya tombol btn-save");

            foreach ($m[1] as $body) {
                $this->assertStringContainsString('btn-save-icon', $body, "{$url} tombol save tanpa ikon");
                $this->assertStringContainsString('btn-save-label', $body, "{$url} tombol save tanpa label span");
                $this->assertStringContainsString('aria-hidden="true"', $body, "ikon dekoratif harus disembunyikan dari screen reader");
            }
        }
    }

    public function test_no_duplicate_class_attributes_anywhere(): void
    {
        foreach ($this->savePages() as $url) {
            $html = $this->html($url);

            // Atribut class ganda = HTML tidak valid, browser mengabaikan yang kedua.
            $this->assertSame(
                0,
                preg_match_all('/<[a-z]+[^>]*\bclass="[^"]*"[^>]*\bclass="[^"]*"[^>]*>/i', $html),
                "{$url} punya atribut class ganda"
            );
        }
    }

    public function test_save_button_is_styled_in_layout(): void
    {
        $css = $this->layoutCss();

        $this->assertStringContainsString('.btn-save {', $css);
        $this->assertStringContainsString('.btn-save-icon', $css);
        $this->assertStringContainsString('.btn-save-label', $css);
        $this->assertStringContainsString('.btn-save-success', $css);
    }

    public function test_save_button_shows_saving_state_in_javascript(): void
    {
        $layout = file_get_contents(resource_path('views/admin/layouts/app.blade.php'));

        $this->assertStringContainsString("button.classList.contains('btn-save')", $layout);
        $this->assertStringContainsString("button.classList.add('is-saving')", $layout);
        $this->assertStringContainsString("label.textContent = 'Menyimpan'", $layout);
        $this->assertStringContainsString("button.classList.remove('is-saving')", $layout, 'state harus dikembalikan setelah request');
        $this->assertStringContainsString("label.dataset.originalLabel", $layout, 'label asli harus dipulihkan');
    }

    public function test_save_button_has_spinner_and_lift_on_hover(): void
    {
        $css = $this->layoutCss();

        $this->assertStringContainsString('.btn-save.is-saving .btn-save-icon { animation: save-spin', $css);
        $this->assertStringContainsString('@keyframes save-spin', $css);
        $this->assertStringContainsString('transform: translateY(-1px)', $css, 'hover harus lifted');
        $this->assertStringContainsString('.btn-save:active', $css, 'tekan harus turun');
    }

    public function test_save_button_disables_while_saving(): void
    {
        $layout = file_get_contents(resource_path('views/admin/layouts/app.blade.php'));

        $this->assertStringContainsString('button.disabled = true;', $layout);
        $this->assertStringContainsString('.btn-save:disabled {', $this->layoutCss());
        $this->assertStringContainsString('cursor: progress', $this->layoutCss());
    }

    public function test_section_gap_is_three_pixels(): void
    {
        $css = $this->layoutCss();

        $this->assertStringContainsString('--section-gap: 3px;', $css, 'token jarak section harus 3px');

        preg_match('/\.section \{[^}]*\}/', $css, $m);
        $this->assertStringContainsString('gap: var(--section-gap)', $m[0] ?? '', 'section harus pakai gap 3px');

        // Semua grid ikut aturan yang sama.
        preg_match('/\.f-grid-2, \.f-grid-3[^}]*\}/', $css, $g);
        $this->assertStringContainsString('gap: var(--section-gap)', $g[0] ?? '');
    }

    public function test_success_tone_exists_for_add_buttons(): void
    {
        $html = $this->html('/admin/settings/members');
        $this->assertStringContainsString('btn-save btn-save-success', $html);
    }
}
