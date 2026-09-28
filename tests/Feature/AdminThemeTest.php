<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminThemeTest extends TestCase
{
    use RefreshDatabase;

    private function layoutCss(): string
    {
        $layout = file_get_contents(resource_path('views/admin/layouts/app.blade.php'));
        $start = strpos($layout, '<style>');

        return substr($layout, $start, strpos($layout, '</style>') - $start);
    }

    private function adminHtml(): string
    {
        $this->actingAs(User::factory()->admin()->create());

        return $this->get(route('admin.dashboard'))->assertOk()->getContent();
    }

    public function test_layout_defines_theme_tokens(): void
    {
        $css = $this->layoutCss();

        foreach (['--bg', '--surface', '--text', '--accent', '--ease', '--t-fast', '--t-base'] as $token) {
            $this->assertStringContainsString($token . ':', $css, "token {$token} harus ada");
        }
    }

    public function test_no_hardcoded_old_brand_colors_in_layout_css(): void
    {
        $css = $this->layoutCss();

        // Warna biru/cyan lama harus hilang, diganti variabel.
        foreach (['#38bdf8', '#4ade80', '#f87171', '#1d4ed8', '#0f2137'] as $old) {
            $this->assertStringNotContainsString($old, $css, "warna lama {$old} masih di layout");
        }
    }

    public function test_every_color_in_layout_css_comes_from_a_token(): void
    {
        // Warna literal yang tersisa hanya netral + turunan token, bukan warna tema yang di-hardcode.
        $css = $this->layoutCss();

        // Buang blok :root: nilai token punya definisi warna sendiri.
        $body = preg_replace('/:root\s*\{[^}]*\}/', '', $css);

        preg_match_all('/#[0-9a-fA-F]{3,6}\b/', $body, $m);
        $literals = array_unique($m[0]);

        $allowed = [
            '#fff', '#000', // netral
            '#1a1207', '#06251a', '#2b0d0d', // teks di atas aksen solid
            '#e0b060', '#6fc99a', '#f7c9c9', // hover aksen
            '#a7e3c4', '#f3b5b5', // teks toast
        ];

        foreach ($literals as $hex) {
            $this->assertContains(
                strtolower($hex),
                $allowed,
                "warna literal {$hex} sebaiknya lewat token"
            );
        }
    }

    public function test_inline_styles_were_moved_to_classes(): void
    {
        $layout = file_get_contents(resource_path('views/admin/layouts/app.blade.php'));

        // Hanya 2 inline style yang boleh tersisa di layout (sisa tak generik).
        $this->assertLessThanOrEqual(4, substr_count($layout, 'style="'), 'layout jangan menambah inline style');
    }

    public function test_admin_views_have_far_fewer_inline_styles_than_before(): void
    {
        $views = glob(resource_path('views/admin/**/*.blade.php'));
        $total = 0;
        foreach ($views as $view) {
            $total += substr_count(file_get_contents($view), 'style="');
        }

        // Sebelum tema ini: 235 inline style.
        $this->assertLessThan(60, $total, "inline style admin masih {$total}, target < 60");
    }

    public function test_utilities_from_migration_exist_in_css(): void
    {
        $css = $this->layoutCss();

        foreach ([
            'f-label', 'f-input', 'f-hint', 'f-error', 'f-submit', 'f-grid-2', 'f-grid-3',
            'f-grid-4', 'f-grid-5', 'f-grid-150', 'f-span-all', 'f-span-rest', 'f-row',
            'f-strong', 'f-h2', 'f-subhead', 'f-preview', 'f-cover', 'mb-lg', 'mb-md', 'text-right',
        ] as $class) {
            $this->assertStringContainsString('.' . $class . ' {', $css, "class .{$class} harus ada");
        }
    }

    public function test_every_class_used_in_admin_views_is_defined(): void
    {
        $this->adminHtml();
        $this->actingAs(User::factory()->admin()->create());

        $views = glob(resource_path('views/admin/**/*.blade.php'));
        $skip = ['brand', 'btn-login', 'error', 'form-options', 'left-panel', 'login-form', 'remember', 'right-panel', 'subtitle'];

        $missing = [];
        foreach ($views as $view) {
            // Halaman auth (login/register) dan preview cetak punya stylesheet
            // sendiri, bukan layout admin.
            if (str_contains($view, 'auth/login') || str_contains($view, 'auth/register') || str_contains($view, 'members-print')) {
                continue;
            }
            preg_match_all('/class="([^"{}]+)"/', file_get_contents($view), $m);
            foreach ($m[1] as $attr) {
                foreach (preg_split('/\s+/', $attr) as $class) {
                    if ($class === '' || in_array($class, $skip, true)) {
                        continue;
                    }
                    if (! preg_match('/\.' . preg_quote($class, '/') . '\b/', $this->layoutCss())) {
                        $missing[] = $class;
                    }
                }
            }
        }

        $this->assertSame([], array_values(array_unique($missing)), 'class tanpa styling: ' . implode(', ', array_unique($missing)));
    }

    public function test_mobile_nav_has_a_toggle_button(): void
    {
        $html = $this->adminHtml();

        $this->assertStringContainsString('id="navToggle"', $html, 'tombol toggle wajib ada untuk layar kecil');
        $this->assertStringContainsString('id="navbarNav"', $html, 'nav butuh id untuk aria-controls');
        $this->assertStringContainsString('aria-expanded="false"', $html);
        $this->assertStringContainsString('aria-controls="navbarNav"', $html);
    }

    public function test_nav_is_not_hidden_without_a_replacement(): void
    {
        $css = $this->layoutCss();

        // Bug lama: .navbar-nav disembunyikan di 768px tanpa tombol pengganti.
        $this->assertDoesNotMatchRegularExpression(
            '/@media[^{]*max-width:\s*768px[^{]*\{[^}]*\.navbar-nav\s*\{\s*display:\s*none/',
            $css,
            'nav tidak boleh hilang di mobile tanpa pengganti'
        );
        $this->assertStringContainsString('.nav-toggle { display: block; }', $css, 'toggle harus tampil di mobile');
        $this->assertStringContainsString('.navbar-nav.open { display: flex; }', $css);
    }

    public function test_mobile_nav_script_is_present(): void
    {
        $layout = file_get_contents(resource_path('views/admin/layouts/app.blade.php'));

        $this->assertStringContainsString("document.getElementById('navToggle')", $layout);
        $this->assertStringContainsString("navbarNav.classList.toggle('open')", $layout);
    }

    public function test_reduced_motion_is_respected(): void
    {
        $css = $this->layoutCss();

        $this->assertStringContainsString('prefers-reduced-motion: reduce', $css, 'harus ada dukungan reduced motion');
        $this->assertStringContainsString('transition-duration: 0.01ms !important', $css);
    }

    public function test_keyboard_focus_is_visible(): void
    {
        $css = $this->layoutCss();

        $this->assertStringContainsString(':focus-visible', $css, 'focus keyboard harus terlihat di tema gelap');
    }

    public function test_responsive_breakpoints_cover_tables_forms_and_grids(): void
    {
        $css = $this->layoutCss();

        foreach (['max-width: 1200px', 'max-width: 900px', 'max-width: 768px', 'max-width: 480px'] as $bp) {
            $this->assertStringContainsString($bp, $css, "breakpoint {$bp} hilang");
        }
        // Grid form harus runtuh jadi satu kolom di mobile.
        $this->assertStringContainsString('.f-grid-2, .f-grid-3, .f-grid-150, .f-grid-4, .f-grid-5 { grid-template-columns: 1fr; }', $css);
    }

    public function test_no_element_relies_on_transition_all(): void
    {
        $css = $this->layoutCss();

        // transition: all bikin layout ikut teranimasi -> Bluespec.
        $this->assertStringNotContainsString('transition: all', $css, 'transition all harus diganti properti spesifik');
    }

    public function test_dashboard_charts_use_the_new_palette(): void
    {
        $dashboard = file_get_contents(resource_path('views/admin/dashboard.blade.php'));

        foreach (['#38bdf8', '#4ade80'] as $old) {
            $this->assertStringNotContainsString($old, $dashboard, "chart masih pakai warna lama {$old}");
        }
        $this->assertStringContainsString('#d4a24c', $dashboard, 'chart harus pakai aksen emas');
    }
}
