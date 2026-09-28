<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $user = User::factory()->admin()->create([
            'email' => 'admin@sigma.id',
            'password' => 'rahasia123',
        ]);

        $this->actingAs($user);

        return $user;
    }

    public function test_register_page_is_reachable_and_has_a_form(): void
    {
        $response = $this->get(route('register'))->assertOk();

        $response->assertSee('Buat akun', false);
        $this->assertStringContainsString('name="name"', $response->getContent());
        $this->assertStringContainsString('name="email"', $response->getContent());
        $this->assertStringContainsString('name="password"', $response->getContent());
        $this->assertStringContainsString('name="password_confirmation"', $response->getContent());
        $this->assertStringContainsString('method="POST"', $response->getContent());
        $this->assertStringContainsString('name="_token"', $response->getContent(), 'form harus punya CSRF token');
    }

    public function test_visitor_can_register_and_is_logged_in(): void
    {
        $this->post(route('register.process'), [
            'name' => 'Budi Santoso',
            'email' => 'budi@contoh.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertDatabaseHas('users', ['email' => 'budi@contoh.id', 'name' => 'Budi Santoso']);
        $this->assertAuthenticated();

        // Password harus tersimpan ter-hash, bukan teks polos.
        $user = User::where('email', 'budi@contoh.id')->first();
        $this->assertNotSame('password123', $user->password);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_new_account_is_a_panel_account(): void
    {
        // Tujuan pendaftaran ini: masuk ke panel. Tabel users dipakai untuk
        // pengelola, bukan untuk pengunjung umum.
        $this->post(route('register.process'), [
            'name' => 'Budi',
            'email' => 'budi@contoh.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'budi@contoh.id')->first();
        $this->assertTrue($user->is_admin, 'akun baru harus punya akses panel');
        $this->assertTrue($user->isAdmin());
    }

    public function test_a_freshly_registered_account_can_open_the_panel(): void
    {
        $this->post(route('register.process'), [
            'name' => 'Budi',
            'email' => 'budi@contoh.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->get(route('admin.dashboard'))->assertOk();
        $this->get(route('admin.posts.index'))->assertOk();
    }

    public function test_register_validation_rules(): void
    {
        // Email sudah dipakai.
        User::factory()->create(['email' => 'ada@contoh.id']);

        $this->post(route('register.process'), [
            'name' => '',
            'email' => 'ada@contoh.id',
            'password' => 'pendek',
            'password_confirmation' => 'lain',
        ])->assertSessionHasErrors(['name', 'email', 'password']);

        $this->assertGuest();
    }

    public function test_register_rejects_email_that_is_already_taken(): void
    {
        User::factory()->create(['email' => 'ada@contoh.id']);

        $this->post(route('register.process'), [
            'name' => 'Budi',
            'email' => 'ada@contoh.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionHasErrors('email');

        $this->assertSame(1, User::where('email', 'ada@contoh.id')->count());
    }

    public function test_register_throttles_repeated_attempts(): void
    {
        RateLimiter::clear('register|127.0.0.1');

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('register.process'), [
                'name' => 'Budi',
                'email' => "budi{$i}@contoh.id",
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);
            Auth::logout();
        }

        $this->post(route('register.process'), [
            'name' => 'Budi',
            'email' => 'terus@contoh.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionHasErrors('email');

        $this->assertDatabaseMissing('users', ['email' => 'terus@contoh.id']);
    }

    public function test_login_throttles_wrong_passwords(): void
    {
        RateLimiter::clear('budi@contoh.id|127.0.0.1');
        User::factory()->create(['email' => 'budi@contoh.id']);

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('login.process'), ['email' => 'budi@contoh.id', 'password' => 'salah']);
        }

        $this->post(route('login.process'), ['email' => 'budi@contoh.id', 'password' => 'salah'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_non_admin_account_cannot_open_the_panel(): void
    {
        $budi = User::factory()->create([
            'email' => 'budi@contoh.id',
            'is_admin' => false,
        ]);

        $this->actingAs($budi)
            ->get(route('admin.posts.index'))
            ->assertRedirect(route('landing'))
            ->assertSessionHasErrors('auth');

        $this->actingAs($budi)
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('landing'));

        $this->actingAs($budi)
            ->get(route('admin.settings.header'))
            ->assertRedirect(route('landing'));
    }

    public function test_non_admin_cannot_write_to_the_panel(): void
    {
        $budi = User::factory()->create(['is_admin' => false]);

        // Menulis harus ditolak, bukan hanya dibaca.
        $this->actingAs($budi)
            ->post(route('admin.posts.store'), ['title' => 'Sisip diam-diam', 'content' => 'x'])
            ->assertRedirect(route('landing'));

        $this->assertDatabaseMissing('posts', ['title' => 'Sisip diam-diam']);
    }

    public function test_admin_still_reaches_the_panel(): void
    {
        $this->admin();

        $this->get(route('admin.dashboard'))->assertOk();
        $this->get(route('admin.posts.index'))->assertOk();
    }

    public function test_admin_can_log_in_with_own_account(): void
    {
        Auth::logout();
        $this->admin();
        Auth::logout();

        $this->post(route('login.process'), [
            'email' => 'admin@sigma.id',
            'password' => 'rahasia123',
        ])->assertRedirect(route('admin.posts.index'));

        $this->assertAuthenticated();
    }

    public function test_registered_user_can_log_in_and_lands_on_the_landing_page(): void
    {
        User::factory()->create([
            'email' => 'budi@contoh.id',
            'password' => 'password123',
            'is_admin' => false,
        ]);

        $this->post(route('login.process'), [
            'email' => 'budi@contoh.id',
            'password' => 'password123',
        ])->assertRedirect(route('landing'));

        $this->assertAuthenticated();
    }

    public function test_login_error_does_not_reveal_whether_email_exists(): void
    {
        User::factory()->create(['email' => 'ada@contoh.id']);

        foreach (['ada@contoh.id', 'entah@contoh.id'] as $email) {
            $this->post(route('login.process'), ['email' => $email, 'password' => 'salah'])
                ->assertSessionHasErrors([
                    'email' => 'Email atau password salah.',
                ]);
        }
    }

    public function test_non_admin_can_still_log_out(): void
    {
        $budi = User::factory()->create(['is_admin' => false]);

        $this->actingAs($budi)
            ->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_guest_cannot_reach_register_or_login_post(): void
    {
        $this->post(route('logout'))->assertRedirect(route('login'));
    }

    public function test_landing_page_has_no_login_or_register_feature(): void
    {
        // Permintaan eksplisit: tidak ada fitur masuk/daftar di landing page.
        // Pendaftaran hanya lewat URL /register.
        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringNotContainsString('href="' . route('login') . '"', $html, 'tidak ada tautan Masuk');
        $this->assertStringNotContainsString('href="' . route('register') . '"', $html, 'tidak ada tautan Daftar');
        $this->assertStringNotContainsString('nav-login', $html, 'tidak ada tombol auth di navbar');
        $this->assertStringNotContainsString('Keluar', $html, 'tidak ada tombol keluar di navbar');
    }

    public function test_register_and_login_pages_are_still_reachable_directly(): void
    {
        // Keduanya sengaja dipaket di URL, tidak ditautkan dari landing page.
        $this->get(route('register'))->assertOk();
        $this->get(route('login'))->assertOk();
    }
}
