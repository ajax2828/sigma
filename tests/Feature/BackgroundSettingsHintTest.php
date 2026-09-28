<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BackgroundSettingsHintTest extends TestCase
{
    use RefreshDatabase;

    private const PREFIXES = [
        'page' => 'Full Page',
        'hero' => 'Hero',
        'about' => 'About',
        'members' => 'Members',
        'achievements' => 'Achievements',
        'footer' => 'Footer',
    ];

    private function layoutCss(): string
    {
        $layout = file_get_contents(resource_path('views/admin/layouts/app.blade.php'));
        $start = strpos($layout, '<style>');

        return substr($layout, $start, strpos($layout, '</style>') - $start);
    }

    private function viewSource(): string
    {
        return (string) file_get_contents(resource_path('views/admin/settings/backgrounds.blade.php'));
    }

    private function html(): string
    {
        $this->actingAs(User::factory()->admin()->create());

        return $this->get(route('admin.settings.backgrounds', ['section' => 'hero']))->assertOk()->getContent();
    }

    /** Satu kartu background, diiris dari labelnya sampai label kartu berikutnya. */
    private function card(string $html, string $label): string
    {
        $start = strpos($html, $label . ' Background');
        $this->assertNotFalse($start, "kartu {$label} tidak ada");

        $tail = substr($html, $start + 1);
        $offset = PHP_INT_MAX;

        foreach (self::PREFIXES as $other) {
            if ($other === $label) {
                continue;
            }
            $next = strpos($tail, $other . ' Background');
            if ($next !== false && $next < $offset) {
                $offset = $next;
            }
        }

        return $offset === PHP_INT_MAX ? $tail : substr($tail, 0, $offset);
    }

    public function test_every_gradient_and_image_input_has_an_explanation(): void
    {
        $html = $this->html();

        foreach (self::PREFIXES as $prefix => $label) {
            $card = $this->card($html, $label);

            // Baris penjelasan di bawah tiap input, bukan cuma label.
            // 5 = 3 gradient + 1 gambar + 1 aturan format file.
            $this->assertSame(
                5,
                substr_count($card, 'class="f-hint-sm"'),
                "kartu {$label} harus punya penjelasan di setiap input"
            );

            $this->assertStringContainsString('Warna awal gradient', $card, "{$label}: Start");
            $this->assertStringContainsString('Warna akhir gradient', $card, "{$label}: End");
            $this->assertStringContainsString('Arah penyinaran (derajat)', $card, "{$label}: Angle");
            $this->assertStringContainsString('Dibiarkan kosong, section memakai gradient', $card, "{$label}: Image");
        }
    }

    public function test_each_card_says_where_the_value_is_used(): void
    {
        $html = $this->html();

        $this->assertSame(6, substr_count($html, '<strong class="f-white">Dipakai di:</strong>'));

        // Teks HTML ter-escape, jadi kutip ikut jadi &quot;.
        foreach ([
            'Latar belakang seluruh halaman',
            'Header paling atas',
            'Section &quot;Tentang Kami&quot;',
            'Section &quot;Anggota&quot;',
            'Section &quot;Prestasi&quot;',
            'Footer paling bawah halaman',
        ] as $where) {
            $this->assertStringContainsString($where, $html, "penjelasan lokasi: {$where}");
        }
    }

    public function test_labels_are_wired_to_their_inputs(): void
    {
        $source = $this->viewSource();

        foreach (['start', 'end', 'angle', 'image'] as $field) {
            $this->assertStringContainsString(
                'for="{{ $section[\'' . $field . '\'] }}"',
                $source,
                "label harus pakai for untuk field {$field}"
            );
        }
    }

    public function test_each_card_has_a_live_gradient_preview(): void
    {
        $html = $this->html();

        // Panchor ke tag penuh: string 'data-gradient-preview' juga muncul di
        // selector querySelectorAll di script yang ikut ter-render.
        $this->assertSame(6, substr_count($html, '<span class="f-swatch" data-gradient-preview'), 'satu pratinjau per kartu');

        $this->assertStringContainsString('data-start="page_gradient_start"', $html);
        $this->assertStringContainsString('data-end="page_gradient_end"', $html);
        $this->assertStringContainsString('data-angle="page_gradient_angle"', $html);
    }

    public function test_preview_starts_painted_with_current_values(): void
    {
        $html = $this->html();

        // Nilai default section pertama, jadi pratinjau harus langsung terisi tanpa interaksi.
        $this->assertStringContainsString(
            'linear-gradient(180deg, #f4f0e6, #e8e0d1)',
            $html,
            'pratinjau harus sudah berisi gradient tersimpan'
        );
    }

    public function test_preview_script_is_delegated_so_ajax_main_swap_keeps_it_working(): void
    {
        $source = $this->viewSource();

        $this->assertStringContainsString("document.addEventListener('input'", $source, 'listener harus di document');
        $this->assertStringContainsString("preview.style.backgroundImage = 'linear-gradient('", $source);
        $this->assertStringContainsString('isNaN(angle)', $source, 'input rusak tidak boleh bikin NaNdeg');
        $this->assertStringContainsString('/^#[0-9a-fA-F]{6}$/', $source, 'warna harus divalidasi sebelum masuk CSS');
    }

    public function test_new_helper_classes_are_defined_in_the_layout(): void
    {
        $css = $this->layoutCss();

        foreach (['f-color', 'f-h-md', 'f-where', 'f-swatch', 'f-swatch-row', 'f-swatch-label', 'f-preview-wide', 'f-gradient-row'] as $class) {
            $this->assertStringContainsString('.' . $class . ' {', $css, "class .{$class} harus ada di layout CSS");
        }
    }

    public function test_start_and_end_inputs_get_equal_width_columns(): void
    {
        $css = $this->layoutCss();

        // .f-grid-3 bawaan = 60px 1fr 140px (thumbnail + teks + field sempit),
        // membuat Start jauh lebih kecil dari End walau keduanya width:100%.
        preg_match('/@media \(min-width: 769px\) \{\s*\.f-gradient-row \{[^}]*\}/', $css, $m);
        $rule = $m[0] ?? '';

        $this->assertStringContainsString('repeat(2, minmax(0, 1fr))', $rule, 'Start dan End harus dua kolom sama lebar');
        // '60px' polos akan match di dalam '160px'.
        $this->assertDoesNotMatchRegularExpression('/(?<![0-9])60px/', $rule, 'kolom thumbnail 60px tidak boleh dipakai untuk input warna');
        $this->assertStringContainsString('160px', $rule, 'angle cukup 3 digit');
    }

    public function test_the_equal_column_override_does_not_break_the_mobile_collapse(): void
    {
        $css = $this->layoutCss();

        // Override harus di dalam min-width, kalau tidak grid 3 kolom tidak runtuh di mobile.
        $this->assertStringContainsString('@media (min-width: 769px)', $css);
        $this->assertStringContainsString('.f-grid-2, .f-grid-3, .f-grid-150, .f-grid-4, .f-grid-5 { grid-template-columns: 1fr; }', $css);
    }

    public function test_the_three_gradient_inputs_share_one_height_class(): void
    {
        $html = $this->html();

        // 6 kartu: start + end + angle + file input, semuanya kelas tinggi yang sama.
        // Dihitung dari atribut class utuh, bukan 'f-h-md' polos yang ikut kena
        // stylesheet layout di halaman yang sama.
        $this->assertSame(12, substr_count($html, 'class="f-color f-h-md"'), 'kedua input warna satu tinggi');
        $this->assertSame(6, substr_count($html, 'class="f-input f-h-md"'), 'input angle satu tinggi');
        $this->assertSame(6, substr_count($html, 'class="f-input f-file f-h-md"'), 'input file satu tinggi');

        $this->assertStringContainsString('.f-h-md { height: 42px; }', $this->layoutCss());

        // Swatch pratinjau satu baris dengan input, bukan 44px.
        preg_match('/\.f-swatch \{[^}]*\}/', $this->layoutCss(), $m);
        $this->assertStringContainsString('height: 42px', $m[0] ?? '');
    }

    public function test_duplicate_description_line_was_removed(): void
    {
        $source = $this->viewSource();

        // Baris "Background untuk ..." dan "Dipakai di: ..." mengatakan hal yang sama, jadi cukup satu.
        $this->assertStringNotContainsString("'description' =>", $source, 'key description tidak terpakai lagi');
        $this->assertStringNotContainsString('class="f-hint"', $source, 'baris f-hint yang menduplikasi f-where harus hilang');
    }

    public function test_color_inputs_no_longer_carry_inline_styles(): void
    {
        $source = $this->viewSource();

        // Inline style panjang tidak pernah berlaku untuk input[type=color] di semua browser.
        $this->assertStringNotContainsString('type="color" name="{{ $section[\'start\'] }}" value="{{ $start }}" style=', $source);
        $this->assertLessThanOrEqual(2, substr_count($source, 'style="'), 'hanya style pratinjau yang boleh inline');
    }
}
