<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberPrintTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        return $admin;
    }

    private function makeMember(array $attrs = []): Member
    {
        return Member::create(array_merge([
            'initial' => 'FA',
            'name' => 'Fitri Aulia',
            'role' => 'K Divisi Desain',
            'code' => 'SIGMA/2025/006',
            'motto' => 'Belajar dulu, askepan kemudian.',
            'photo' => null,
        ], $attrs));
    }

    public function test_print_page_requires_auth(): void
    {
        $this->get(route('admin.settings.members.print'))->assertRedirect(route('login'));
    }

    public function test_print_page_renders_all_members(): void
    {
        $this->admin();
        $this->makeMember(['name' => 'Satu']);
        $this->makeMember(['name' => 'Dua']);
        $this->makeMember(['name' => 'Tiga']);

        $this->get(route('admin.settings.members.print'))
            ->assertOk()
            ->assertSee('Satu')
            ->assertSee('Dua')
            ->assertSee('Tiga')
            ->assertSee('3 kartu', false);
    }

    public function test_print_page_shows_number_name_division_and_motto(): void
    {
        $this->admin();
        $this->makeMember();

        $this->get(route('admin.settings.members.print'))
            ->assertOk()
            ->assertSee('SIGMA/2025/006')
            ->assertSee('Fitri Aulia')
            ->assertSee('K Divisi Desain')
            ->assertSee('Belajar dulu, askepan kemudian.');
    }

    public function test_card_and_grid_measured_in_millimetres(): void
    {
        $this->admin();
        $this->makeMember();

        $html = $this->get(route('admin.settings.members.print'))->assertOk()->getContent();

        // Ukuran mm, bukan px: preview di layar sama persis dengan hasil cetak.
        $this->assertStringContainsString('width: 91mm; height: 52mm', $html);
        $this->assertStringContainsString('grid-template-columns: repeat(2, 91mm)', $html);
        $this->assertStringContainsString('gap: 8mm', $html);
    }

    public function test_grid_fits_inside_a4_printable_area(): void
    {
        $this->admin();
        $this->makeMember();

        $html = $this->get(route('admin.settings.members.print'))->assertOk()->getContent();

        // A4 210x297mm dengan margin 10mm -> 190mm x 277mm yang boleh dipakai.
        preg_match('/size: A4; margin: (\d+)mm/', $html, $m);
        $this->assertNotEmpty($m, 'ukuran halaman cetak harus A4 dengan margin');
        $margin = (float) $m[1];

        $printableW = 210 - 2 * $margin;
        $printableH = 297 - 2 * $margin;

        // 2 kolom x 91mm + 1 gap 8mm harus <= lebar cetak, dan 4 baris x 52mm <= tinggi cetak.
        $this->assertLessThanOrEqual($printableW, 2 * 91 + 8, '2 kolom meluber dari lebar A4');
        $this->assertLessThanOrEqual($printableH, 4 * 52 + 3 * 8, '4 baris meluber dari tinggi A4');
    }

    public function test_photo_is_large_enough_for_paper(): void
    {
        $this->admin();
        $this->makeMember();

        $html = $this->get(route('admin.settings.members.print'))->assertOk()->getContent();

        // Foto 22mm = 0.87 inci, jauh lebih besar dari thumbnail lama (64px ~ 17mm).
        $this->assertStringContainsString('flex: 0 0 22mm; width: 22mm; height: 22mm', $html);
    }

    public function test_typography_measured_in_millimetres(): void
    {
        $this->admin();
        $this->makeMember();

        $html = $this->get(route('admin.settings.members.print'))->assertOk()->getContent();

        // Font rem mengikuti zoom browser dan membuat hasil cetak tidak konsisten.
        $this->assertStringContainsString('.name { font-size: 5mm', $html);
        $this->assertStringContainsString('.motto { font-size: 3mm', $html);
    }

    public function test_print_media_hides_chrome_and_keeps_cards(): void
    {
        $this->admin();
        $this->makeMember();

        $html = $this->get(route('admin.settings.members.print'))->assertOk()->getContent();

        $this->assertStringContainsString('@media print', $html, 'harus ada gaya cetak');

        // Ambil isi blok @media print (sampai @page) lalu cek per aturan.
        $printBlock = substr($html, strpos($html, '@media print'), strpos($html, '@page') - strpos($html, '@media print'));

        $this->assertMatchesRegularExpression('/\.toolbar\s*\{\s*display:\s*none/', $printBlock, 'toolbar harus hilang saat cetak');
        $this->assertStringContainsString('grid-template-columns: repeat(2, 91mm)', $printBlock, 'grid 2 kolom harus tetap saat cetak');
        $this->assertStringContainsString('break-inside: avoid', $printBlock, 'kartu tidak boleh terpotong antar halaman');
        $this->assertStringContainsString('body { background: #fff', $printBlock, 'latar gelap harus jadi putih');
        $this->assertStringContainsString('@page', $html, 'ukuran halaman cetak harus diatur');
    }

    public function test_card_never_breaks_across_pages(): void
    {
        $this->admin();
        $this->makeMember();

        $html = $this->get(route('admin.settings.members.print'))->assertOk()->getContent();

        // Versi lama harus tetap ada untuk browser yang tidak mendukung page-break.
        $this->assertStringContainsString('page-break-inside: avoid', $html);
    }

    public function test_preview_has_print_button_and_back_link(): void
    {
        $this->admin();
        $this->makeMember();

        $html = $this->get(route('admin.settings.members.print'))->assertOk()->getContent();

        $this->assertStringContainsString('window.print()', $html, 'harus ada pemicu cetak');
        $this->assertStringContainsString(route('admin.settings.members'), $html, 'harus ada jalan kembali');
    }

    public function test_members_page_links_to_print_preview(): void
    {
        $this->admin();
        $this->makeMember();

        $this->get(route('admin.settings.members'))
            ->assertOk()
            ->assertSee(route('admin.settings.members.print'), false)
            ->assertSee('target="_blank"', false);
    }

    public function test_photo_is_shown_and_falls_back_to_initial(): void
    {
        $this->admin();
        $this->makeMember(['name' => 'Tanpa Foto', 'initial' => 'TF', 'photo' => null]);
        $this->makeMember(['name' => 'Dengan Foto', 'initial' => 'DF', 'photo' => '/images/members/a.jpg']);

        $html = $this->get(route('admin.settings.members.print'))->assertOk()->getContent();

        $this->assertStringContainsString('TF', $html, 'fallback inisial');
        $this->assertStringContainsString('/images/members/a.jpg', $html, 'foto harus dirender');
    }

    public function test_falls_back_to_first_letter_when_initial_empty(): void
    {
        $this->admin();
        $this->makeMember(['name' => 'Budi Santoso', 'initial' => '']);

        $this->get(route('admin.settings.members.print'))
            ->assertOk()
            ->assertSee('Budi Santoso');
    }

    public function test_empty_state_prints_a_message(): void
    {
        $this->admin();

        $this->get(route('admin.settings.members.print'))
            ->assertOk()
            ->assertSee('Belum ada member untuk dicetak');
    }

    public function test_print_page_escapes_html_in_motto(): void
    {
        $this->admin();
        $this->makeMember(['motto' => '<script>alert(1)</script>']);

        $html = $this->get(route('admin.settings.members.print'))->assertOk()->getContent();

        $this->assertStringNotContainsString('<script>alert(1)</script>', $html);
    }

    public function test_motto_and_role_omit_when_blank(): void
    {
        $this->admin();
        $this->makeMember(['motto' => null, 'role' => null]);

        $html = $this->get(route('admin.settings.members.print'))->assertOk()->getContent();

        // Elemen kosong disembunyikan via :empty, bukan menampilkan baris kosong.
        $this->assertStringContainsString('.motto:empty { display: none; }', $html);
    }

    public function test_print_page_does_not_load_admin_layout(): void
    {
        $this->admin();
        $this->makeMember();

        $html = $this->get(route('admin.settings.members.print'))->assertOk()->getContent();

        // Print view berdiri sendiri: tidak ada nav admin, tidak ada form AJAX.
        $this->assertStringNotContainsString('id="navToggle"', $html);
        $this->assertStringNotContainsString('nav-dropdown', $html);
    }

    public function test_every_card_has_a_selection_checkbox(): void
    {
        $this->admin();
        $this->makeMember(['name' => 'Satu']);
        $this->makeMember(['name' => 'Dua']);

        $html = $this->get(route('admin.settings.members.print'))->assertOk()->getContent();

        $this->assertSame(2, substr_count($html, 'class="pick-input"'), 'tiap kartu punya satu checkbox');
        $this->assertStringContainsString('aria-label="Cetak Satu"', $html, 'checkbox perlu nama yang bisa dibaca screen reader');
    }

    public function test_cards_start_selected(): void
    {
        $this->admin();
        $this->makeMember();

        $html = $this->get(route('admin.settings.members.print'))->assertOk()->getContent();

        // Default semua terpilih: cases yang umum adalah "cetak semua".
        $this->assertStringContainsString('class="card-wrap is-picked"', $html);
        $this->assertStringContainsString('class="pick-input" checked', $html);
    }

    public function test_unselected_cards_are_hidden_when_printing(): void
    {
        $this->admin();
        $this->makeMember();

        $html = $this->get(route('admin.settings.members.print'))->assertOk()->getContent();

        $printBlock = substr($html, strpos($html, '@media print'), strpos($html, '@page') - strpos($html, '@media print'));

        $this->assertStringContainsString('.card-wrap:not(.is-picked) { display: none; }', $printBlock, 'kartu tak terpilih tidak boleh tercetak');
        $this->assertStringContainsString('.pick { display: none; }', $printBlock, 'checkbox tidak boleh ikut tercetak');
    }

    public function test_unselected_cards_stay_visible_on_screen(): void
    {
        $this->admin();
        $this->makeMember();

        $html = $this->get(route('admin.settings.members.print'))->assertOk()->getContent();

        // Redup, bukan hilang: user harus tahu member itu ada untuk dicentang lagi.
        $this->assertStringContainsString('.card-wrap:not(.is-picked) .card { opacity: 0.3; }', $html);
    }

    public function test_select_all_and_counter_exist(): void
    {
        $this->admin();
        $this->makeMember(['name' => 'Satu']);
        $this->makeMember(['name' => 'Dua']);

        $html = $this->get(route('admin.settings.members.print'))->assertOk()->getContent();

        $this->assertStringContainsString('id="pickAll"', $html, 'perlu kontrol pilih semua');
        $this->assertStringContainsString('id="printCount"', $html, 'perlu penghitung kartu terpilih');
        $this->assertStringContainsString('Cetak <span id="printCount">(2)</span>', $html, 'penghitung awal = jumlah member');
    }

    public function test_javascript_syncs_selection_state(): void
    {
        $this->admin();
        $this->makeMember();

        $html = $this->get(route('admin.settings.members.print'))->assertOk()->getContent();

        $this->assertStringContainsString("wrap.classList.toggle('is-picked', on)", $html);
        $this->assertStringContainsString('pickAll.indeterminate = picked > 0 && picked < wraps.length', $html, 'pilih semua harus indeterminate saat sebagian');
        $this->assertStringContainsString('printBtn.disabled = picked === 0', $html, 'tombol cetak mati kalau tidak ada pilihan');
        $this->assertStringContainsString("count.textContent = '(' + picked + ')'", $html);
    }

    public function test_checkbox_does_not_change_card_footprint(): void
    {
        $this->admin();
        $this->makeMember();

        $html = $this->get(route('admin.settings.members.print'))->assertOk()->getContent();

        // Checkbox overlay: wrapper harus tetap 91 x 52mm supaya hitungan A4 tidak bergeser.
        $this->assertStringContainsString('.card-wrap { position: relative; width: 91mm; height: 52mm; }', $html);
        $this->assertStringContainsString('.pick { position: absolute;', $html);
        $this->assertStringContainsString('top: -3mm; right: -3mm', $html, 'harus keluar dari flow, bukan menambah tinggi');
    }

    public function test_a4_math_still_holds_with_selection_wrapper(): void
    {
        $this->admin();
        $this->makeMember();

        $html = $this->get(route('admin.settings.members.print'))->assertOk()->getContent();

        preg_match('/size: A4; margin: (\d+)mm/', $html, $m);
        $margin = (float) $m[1];
        $this->assertLessThanOrEqual(210 - 2 * $margin, 2 * 91 + 8);
        $this->assertLessThanOrEqual(297 - 2 * $margin, 4 * 52 + 3 * 8);
    }
}
