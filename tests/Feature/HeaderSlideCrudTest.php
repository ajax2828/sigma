<?php

namespace Tests\Feature;

use App\Models\HeaderSlide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HeaderSlideCrudTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        return $user;
    }

    public function test_header_page_offers_crud_forms_with_image_input(): void
    {
        $this->admin();
        // Form edit hanya muncul kalau sudah ada slide, jadi buat satu dulu.
        HeaderSlide::create(['title' => 'Slide Existing']);

        $html = $this->get(route('admin.settings.header'))->assertOk()->getContent();

        $this->assertStringContainsString(route('admin.settings.header.store'), $html, 'form tambah slide');
        $this->assertStringContainsString('enctype="multipart/form-data"', $html, 'form ableh kirim file');
        foreach (['title', 'description', 'image', 'link', 'cta_label', 'sort_order', 'is_active'] as $field) {
            $this->assertStringContainsString('name="' . $field . '"', $html, "field {$field} harus ada");
        }
        $this->assertStringContainsString(route('admin.settings.header.update', HeaderSlide::first()), $html, 'form edit');
        $this->assertStringContainsString(route('admin.settings.header.destroy', HeaderSlide::first()), $html, 'form hapus');
    }

    public function test_new_slide_is_visible_in_the_banner_unless_told_otherwise(): void
    {
        $this->admin();

        $this->post(route('admin.settings.header.store'), [
            'title' => 'Tampil',
            'is_active' => '1',
        ])->assertRedirect();
        $this->assertTrue(HeaderSlide::first()->is_active);

        $this->post(route('admin.settings.header.store'), [
            'title' => 'Tidak Tampil',
        ])->assertRedirect();
        $this->assertFalse(HeaderSlide::where('title', 'Tidak Tampil')->first()->is_active);
    }

    public function test_slide_can_be_created_updated_and_deleted(): void
    {
        $this->admin();

        $this->post(route('admin.settings.header.store'), [
            'title' => 'Banner Satu',
            'description' => 'Ringkasan banner.',
            'cta_label' => 'Lihat Detail',
            'is_active' => '1',
        ])->assertRedirect(route('admin.settings.header'));

        $slide = HeaderSlide::first();
        $this->assertNotNull($slide);
        $this->assertSame('Banner Satu', $slide->title);
        $this->assertTrue($slide->is_active, 'slide baru aktif secara default');
        $this->assertSame(1, $slide->sort_order);

        $this->put(route('admin.settings.header.update', $slide), [
            'title' => 'Banner Satu (diubah)',
            'description' => 'Ringkasan baru.',
            'sort_order' => 5,
            'is_active' => '1',
        ])->assertRedirect(route('admin.settings.header'));

        $slide->refresh();
        $this->assertSame('Banner Satu (diubah)', $slide->title);
        $this->assertSame(5, $slide->sort_order);

        $this->delete(route('admin.settings.header.destroy', $slide))
            ->assertRedirect(route('admin.settings.header'));
        $this->assertDatabaseMissing('header_slides', ['id' => $slide->id]);
    }

    public function test_unchecking_is_active_actually_persists(): void
    {
        $this->admin();
        $slide = HeaderSlide::create(['title' => 'A', 'is_active' => true]);

        // Checkbox tidak mengirim apa pun kalau tidak dicentang.
        $this->put(route('admin.settings.header.update', $slide), ['title' => 'A'])->assertRedirect();

        $this->assertFalse($slide->fresh()->is_active, 'status harus benar-benar mati');
    }

    public function test_saving_without_a_new_file_keeps_the_existing_image(): void
    {
        $this->admin();
        $slide = HeaderSlide::create(['title' => 'A', 'image' => '/images/header/lama.jpg']);

        $this->put(route('admin.settings.header.update', $slide), ['title' => 'A baru'])
            ->assertRedirect();

        $this->assertSame('/images/header/lama.jpg', $slide->fresh()->image, 'gambar lama tidak boleh hilang');
    }

    public function test_image_upload_stores_the_file_and_its_path(): void
    {
        $this->admin();

        $this->post(route('admin.settings.header.store'), [
            'title' => 'Dengan Gambar',
            'image' => UploadedFile::fake()->image('slide.jpg', 400, 240),
        ])->assertRedirect(route('admin.settings.header'));

        $slide = HeaderSlide::first();
        $this->assertNotNull($slide->image);
        $this->assertStringStartsWith('/images/header/header-slide-', $slide->image);
        $this->assertFileExists(public_path($slide->image), 'file benar-benar tersimpan di disk');
    }

    public function test_title_is_required_and_link_is_optional(): void
    {
        $this->admin();

        $this->post(route('admin.settings.header.store'), ['description' => 'Tanpa judul'])
            ->assertSessionHasErrors('title');

        $this->assertSame(0, HeaderSlide::count());
    }

    public function test_link_is_normalised_and_unsafe_scheme_is_dropped(): void
    {
        $this->admin();

        $plain = HeaderSlide::create(['title' => 'A', 'link' => 'blog.sigma.id/artikel']);
        $this->assertSame('https://blog.sigma.id/artikel', $plain->externalLink());
        $this->assertTrue($plain->hasExternalLink());

        $unsafe = HeaderSlide::create(['title' => 'B', 'link' => 'javascript:alert(1)']);
        $this->assertNull($unsafe->externalLink());
        $this->assertFalse($unsafe->hasExternalLink());

        $none = HeaderSlide::create(['title' => 'C', 'link' => null]);
        $this->assertFalse($none->hasExternalLink());
    }

    public function test_fallback_text_is_still_editable(): void
    {
        $this->admin();

        $this->post(route('admin.settings.header.fallback'), [
            'hero_title' => 'Judul Cadangan',
            'hero_tagline' => 'Tagline',
            'hero_cta_label' => 'Mulai',
        ])->assertRedirect(route('admin.settings.header'));

        $this->assertDatabaseHas('landing_contents', ['key' => 'hero_title', 'value' => 'Judul Cadangan']);
        $this->assertDatabaseHas('landing_contents', ['key' => 'hero_cta_label', 'value' => 'Mulai']);
    }

    public function test_posts_page_has_a_visible_link_to_the_landing_page(): void
    {
        $this->actingAs(User::factory()->create());

        $html = $this->get(route('admin.posts.index'))->assertOk()->getContent();

        $this->assertStringContainsString('Lihat Landing Page', $html, 'header Posts punya tautan ke landing page');
        $this->assertStringContainsString('href="' . route('landing') . '"', $html, 'menunjuk ke landing page');
        $this->assertStringContainsString('target="_blank"', $html, 'dibuka di tab baru, jadi tidak kehilangan session admin');
        $this->assertStringContainsString('rel="noopener"', $html, 'rel=noopener untuk link keluar');
    }

    public function test_the_landing_link_lives_only_on_the_posts_header(): void
    {
        $this->actingAs(User::factory()->create());

        // Permintaan eksplisit: tautannya hanya di header Posts, bukan di
        // navbar dan bukan di halaman lain. Yang dicek adalah href ke landing
        // page — teks "Landing Page" sendiri masih ada sebagai menu pengaturan.
        $landingHref = 'href="' . route('landing') . '"';

        foreach (['admin.settings.header', 'admin.dashboard', 'admin.settings.members'] as $name) {
            $html = $this->get(route($name))->assertOk()->getContent();
            $this->assertStringNotContainsString($landingHref, $html, "halaman {$name} tidak boleh punya tautan landing");
        }
        $this->assertStringNotContainsString('Lihat Site', $this->get(route('admin.dashboard'))->assertOk()->getContent());
    }

    public function test_header_page_requires_login(): void
    {
        $this->get(route('admin.settings.header'))->assertRedirect(route('login'));
    }
}
