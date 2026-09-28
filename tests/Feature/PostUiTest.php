<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostUiTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        return $user;
    }

    private function makePost(User $user, string $title, string $status = 'draft', string $content = 'Isi post.'): Post
    {
        return Post::create([
            'title' => $title,
            'content' => $content,
            'status' => $status,
            'user_id' => $user->id,
        ]);
    }

    public function test_every_class_used_by_posts_pages_exists_in_the_layout_stylesheet(): void
    {
        $admin = $this->admin();
        $this->makePost($admin, 'Post');

        $layout = file_get_contents(resource_path('views/admin/layouts/app.blade.php'));
        $css = substr($layout, strpos($layout, '<style>'), strpos($layout, '</style>') - strpos($layout, '<style>'));

        $used = [];
        foreach (['index', 'create', 'edit', 'show'] as $page) {
            $html = (string) file_get_contents(resource_path("views/admin/posts/{$page}.blade.php"));
            preg_match_all('/class="([^"{}]+)"/', $html, $m);
            foreach ($m[1] as $group) {
                foreach (explode(' ', $group) as $class) {
                    $used[$class] = $page;
                }
            }
        }

        foreach ($used as $class => $page) {
            $this->assertStringContainsString(
                '.' . $class,
                $css,
                "class .$class dipakai di posts/$page tapi tidak ada di layout CSS",
            );
        }
    }

    public function test_no_dark_text_on_dark_background(): void
    {
        $admin = $this->admin();
        $post = $this->makePost($admin, 'Judul Post');

        // Warna hardcoded gelap (from TutorialSM 'me' theme) tidak boleh muncul di view posts.
        foreach (['index', 'create', 'edit', 'show'] as $page) {
            $html = (string) file_get_contents(resource_path("views/admin/posts/{$page}.blade.php"));
            foreach (['#1e293b', '#374151'] as $dark) {
                $this->assertStringNotContainsString($dark, $html, "warna gelap $dark masih ada di posts/$page");
            }
        }

        $this->get(route('admin.posts.show', $post))->assertOk()->assertSee('Isi post.');
    }

    public function test_search_filters_by_title(): void
    {
        $admin = $this->admin();
        $this->makePost($admin, 'Artificial Intelligence Course');
        $this->makePost($admin, 'Cooking Recipe');

        $this->get(route('admin.posts.index', ['q' => 'Artificial']))
            ->assertOk()
            ->assertSee('Artificial Intelligence Course')
            ->assertDontSee('Cooking Recipe');
    }

    public function test_search_filters_by_content(): void
    {
        $admin = $this->admin();
        $this->makePost($admin, 'Post Alpha', 'draft', ' discusses machine learning');
        $this->makePost($admin, 'Post Beta', 'draft', ' discusses gardening');

        $this->get(route('admin.posts.index', ['q' => 'gardening']))
            ->assertOk()
            ->assertSee('Post Beta')
            ->assertDontSee('Post Alpha');
    }

    public function test_status_filter(): void
    {
        $admin = $this->admin();
        $this->makePost($admin, 'Draft One', 'draft');
        $this->makePost($admin, 'Published One', 'published');

        $this->get(route('admin.posts.index', ['status' => 'published']))
            ->assertOk()
            ->assertSee('Published One')
            ->assertDontSee('Draft One');
    }

    public function test_search_and_status_combine(): void
    {
        $admin = $this->admin();
        $this->makePost($admin, 'Alpha Draft', 'draft');
        $this->makePost($admin, 'Alpha Published', 'published');
        $this->makePost($admin, 'Beta Published', 'published');

        $this->get(route('admin.posts.index', ['q' => 'Alpha', 'status' => 'published']))
            ->assertOk()
            ->assertSee('Alpha Published')
            ->assertDontSee('Alpha Draft')
            ->assertDontSee('Beta Published');
    }

    public function test_search_with_no_match_shows_reset_not_create(): void
    {
        $admin = $this->admin();
        $this->makePost($admin, 'Ada Post');

        $html = $this->get(route('admin.posts.index', ['q' => 'tidakada']))
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('Tidak ada post yang cocok', $html);
        $this->assertStringContainsString('Reset Filter', $html);
        $this->assertStringNotContainsString('Create Post', $html);
    }

    public function test_empty_state_points_to_create_when_no_filters(): void
    {
        $this->admin();

        $html = $this->get(route('admin.posts.index'))->assertOk()->getContent();

        $this->assertStringContainsString('Belum ada post', $html);
        $this->assertStringContainsString('Create Post', $html);
        $this->assertStringNotContainsString('Reset Filter', $html);
    }

    public function test_pagination_keeps_query_string(): void
    {
        $admin = $this->admin();
        foreach (range(1, 12) as $i) {
            $this->makePost($admin, "Post Alpha {$i}", 'published');
        }

        $html = $this->get(route('admin.posts.index', ['q' => 'Alpha']))
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('q=Alpha', $html, 'pagination harus mempertahankan filter');
    }

    public function test_list_shows_excerpt_author_and_relative_actions(): void
    {
        $admin = $this->admin();
        $post = $this->makePost($admin, 'Judul Panjang Sekali', 'published', 'Konten yang akan dipotong di excerpt.');

        $html = $this->get(route('admin.posts.index'))->assertOk()->getContent();

        $this->assertStringContainsString('post-excerpt', $html);
        $this->assertStringContainsString('Konten yang akan dipotong', $html);
        $this->assertStringContainsString($admin->name, $html);
        $this->assertStringContainsString(route('admin.posts.edit', $post), $html);
        $this->assertStringContainsString(route('admin.posts.destroy', $post), $html);
        // View dobel sudah dihapus: title yang jadi link ke show.
        $this->assertStringContainsString('class="post-title"', $html);
        $this->assertStringNotContainsString('>View</a>', $html);
    }

    public function test_status_badge_is_color_coded(): void
    {
        $admin = $this->admin();
        $this->makePost($admin, 'Draft Post', 'draft');

        $html = $this->get(route('admin.posts.index'))->assertOk()->getContent();

        $this->assertStringContainsString('badge badge-draft', $html);
        $this->assertStringContainsString('badge-draft', file_get_contents(resource_path('views/admin/layouts/app.blade.php')));
    }

    // ===== LINK BLOG (kartu Kabar Terbaru menuju blog) =====

    public function test_edit_form_has_link_input_prefilled(): void
    {
        $admin = $this->admin();
        $post = $this->makePost($admin, 'Ada Link', 'published');
        $post->update(['link' => 'https://blog.sigma.id/ada-link']);

        $html = $this->get(route('admin.posts.edit', $post))->assertOk()->getContent();

        $this->assertStringContainsString('name="link"', $html, 'form edit harus punya input link');
        $this->assertStringContainsString('https://blog.sigma.id/ada-link', $html, 'link lama harus tampil di input');
    }

    public function test_create_form_has_link_input(): void
    {
        $this->admin();

        $this->get(route('admin.posts.create'))
            ->assertOk()
            ->assertSee('name="link"', false);
    }

    public function test_is_featured_survives_an_edit_and_unchecks_cleanly(): void
    {
        $admin = $this->admin();
        $post = $this->makePost($admin, 'Post Banner', 'published');

        $this->put(route('admin.posts.update', $post), [
            'title' => 'Post Banner',
            'content' => 'Isi post.',
            'status' => 'published',
            'is_featured' => '1',
        ])->assertRedirect(route('admin.posts.index'));

        $this->assertTrue($post->fresh()->is_featured, 'centang harus tersimpan');

        // Checkbox yang tidak dicentang tidak mengirim apa pun sama sekali.
        $this->put(route('admin.posts.update', $post), [
            'title' => 'Post Banner',
            'content' => 'Isi post.',
            'status' => 'published',
        ])->assertRedirect(route('admin.posts.index'));

        $this->assertFalse($post->fresh()->is_featured, 'centang harus hilang saat form disimpan tanpa is_featured');
    }

    public function test_edit_form_reflects_the_current_featured_state(): void
    {
        $admin = $this->admin();
        $post = $this->makePost($admin, 'Post Banner', 'published');
        $post->update(['is_featured' => true]);

        $html = $this->get(route('admin.posts.edit', $post))->assertOk()->getContent();

        $this->assertMatchesRegularExpression(
            '/name="is_featured"[^>]*value="1"[^>]*checked|checked[^>]*name="is_featured"/',
            $html,
            'checkbox harus tercentang kalau postnya memang ada di banner'
        );
    }

    public function test_update_saves_link_and_empty_link_clears_it(): void
    {
        $admin = $this->admin();
        $post = $this->makePost($admin, 'Post Link', 'published');

        $this->put(route('admin.posts.update', $post), [
            'title' => 'Post Link',
            'content' => 'Isi post.',
            'link' => 'https://blog.sigma.id/j artikel',
            'status' => 'published',
        ])->assertRedirect(route('admin.posts.index'));

        $this->assertSame('https://blog.sigma.id/j artikel', $post->fresh()->link);

        $this->put(route('admin.posts.update', $post), [
            'title' => 'Post Link',
            'content' => 'Isi post.',
            'link' => '',
            'status' => 'published',
        ])->assertRedirect(route('admin.posts.index'));

        $this->assertNull($post->fresh()->link, 'link kosong harus menjadi null, bukan string kosong');
    }

    public function test_link_too_long_is_rejected(): void
    {
        $admin = $this->admin();
        $post = $this->makePost($admin, 'Post Link Panjang', 'published');

        $this->from(route('admin.posts.edit', $post))->put(route('admin.posts.update', $post), [
            'title' => 'Post Link Panjang',
            'content' => 'Isi post.',
            'link' => 'https://blog.sigma.id/' . str_repeat('a', 2100),
            'status' => 'published',
        ])->assertSessionHasErrors('link');

        $this->assertNull($post->fresh()->link, 'link invalid tidak boleh tersimpan');
    }
}
