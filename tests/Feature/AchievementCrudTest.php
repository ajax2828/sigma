<?php

namespace Tests\Feature;

use App\Models\Achievement;
use App\Models\LandingContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AchievementCrudTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $user = User::factory()->admin()->create();
        $this->actingAs($user);

        return $user;
    }

    public function test_admin_page_lists_achievements(): void
    {
        $this->admin();
        Achievement::create(['icon' => '🏆', 'title' => 'Juara 1', 'year' => '2025', 'sort_order' => 1]);

        $this->get(route('admin.settings.achievements'))
            ->assertOk()
            ->assertSee('Achievement List (1)')
            ->assertSee('Juara 1');
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('admin.settings.achievements'))->assertRedirect(route('login'));
    }

    public function test_create_adds_achievement_at_end_of_order(): void
    {
        $this->admin();
        Achievement::create(['title' => 'Pertama', 'sort_order' => 1]);
        Achievement::create(['title' => 'Kedua', 'sort_order' => 5]);

        $this->post(route('admin.settings.achievements.store'), [
            'icon' => '🚀',
            'title' => 'Ketiga',
            'year' => '2026',
            'description' => 'Deskripsi.',
        ])->assertRedirect(route('admin.settings.achievements'));

        $created = Achievement::where('title', 'Ketiga')->first();
        $this->assertNotNull($created);
        $this->assertSame('🚀', $created->icon);
        $this->assertSame('2026', $created->year);
        $this->assertSame('Deskripsi.', $created->description);
        $this->assertSame(6, $created->sort_order);
    }

    public function test_create_requires_title(): void
    {
        $this->admin();

        $this->post(route('admin.settings.achievements.store'), ['icon' => '🏆'])
            ->assertSessionHasErrors('title');

        $this->assertSame(0, Achievement::count());
    }

    public function test_update_changes_only_that_achievement(): void
    {
        $this->admin();
        $target = Achievement::create(['title' => 'Lama', 'sort_order' => 1]);
        $other = Achievement::create(['title' => 'Tetap', 'sort_order' => 2]);

        $this->put(route('admin.settings.achievements.update', $target), [
            'icon' => '💡',
            'title' => 'Baru',
            'year' => '2027',
            'description' => 'Diupdate.',
        ])->assertRedirect(route('admin.settings.achievements'));

        $this->assertSame('Baru', $target->fresh()->title);
        $this->assertSame('💡', $target->fresh()->icon);
        $this->assertSame('Tetap', $other->fresh()->title);
        $this->assertSame(2, Achievement::count());
    }

    public function test_destroy_removes_only_that_achievement(): void
    {
        $this->admin();
        $target = Achievement::create(['title' => 'Hapus', 'sort_order' => 1]);
        Achievement::create(['title' => 'Tetap', 'sort_order' => 2]);

        $this->delete(route('admin.settings.achievements.destroy', $target))
            ->assertRedirect(route('admin.settings.achievements'));

        $this->assertNull(Achievement::find($target->id));
        $this->assertSame(1, Achievement::count());
    }

    public function test_update_returns_404_for_missing_achievement(): void
    {
        $this->admin();

        $this->put(route('admin.settings.achievements.update', 999999), ['title' => 'X'])
            ->assertNotFound();
    }

    public function test_section_header_saves_to_landing_contents(): void
    {
        $this->admin();

        $this->post(route('admin.settings.achievements.section'), [
            'achievement_section_title' => 'Prestasi Terre',
            'achievement_section_subtitle' => 'Subtitle terre',
        ])->assertRedirect(route('admin.settings.achievements'));

        $this->assertSame('Prestasi Terre', LandingContent::where('key', 'achievement_section_title')->value('value'));
        $this->assertSame('Subtitle terre', LandingContent::where('key', 'achievement_section_subtitle')->value('value'));
    }

    public function test_landing_page_shows_achievements_in_sort_order(): void
    {
        Achievement::create(['title' => 'Kedua', 'year' => '2024', 'sort_order' => 2]);
        Achievement::create(['title' => 'Pertama', 'year' => '2025', 'sort_order' => 1]);

        $response = $this->get(route('landing'))->assertOk();

        $html = $response->getContent();
        $this->assertLessThan(
            strpos($html, 'Kedua'),
            strpos($html, 'Pertama'),
            'Achievement harus tampil sesuai sort_order.',
        );
    }

    public function test_landing_page_omits_deleted_achievement(): void
    {
        $gone = Achievement::create(['title' => 'Sudah Dihapus', 'sort_order' => 1]);
        Achievement::create(['title' => 'Masih Ada', 'sort_order' => 2]);
        $gone->delete();

        $this->get(route('landing'))
            ->assertOk()
            ->assertDontSee('Sudah Dihapus')
            ->assertSee('Masih Ada');
    }
}
