<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_links_to_forgot_password(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee(route('password.request'), false);
    }

    public function test_forgot_password_page_renders(): void
    {
        $this->get(route('password.request'))
            ->assertOk()
            ->assertSee('Lupa password?', false);
    }

    public function test_temporary_password_is_shown_on_the_page(): void
    {
        $this->withSession(['temporary_password' => 'Rahasia123'])
            ->get(route('password.request'))
            ->assertOk()
            ->assertSee('Rahasia123');
    }

    public function test_known_email_resets_password_without_sending_a_link(): void
    {
        $user = User::factory()->create();

        $this->post(route('password.update'), ['email' => $user->email])
            ->assertSessionHas('status')
            ->assertSessionHas('temporary_password');

        $temporaryPassword = session('temporary_password');

        $this->assertNotEmpty($temporaryPassword);
        $this->assertTrue(Hash::check($temporaryPassword, $user->fresh()->password));

        $this->post(route('login.process'), [
            'email' => $user->email,
            'password' => $temporaryPassword,
        ])->assertRedirect();
    }

    public function test_unknown_email_is_rejected_and_changes_nothing(): void
    {
        $user = User::factory()->create(['email' => 'ada@sigma.id']);
        $passwordBefore = $user->password;

        $this->post(route('password.update'), ['email' => 'tidakada@sigma.id'])
            ->assertSessionHasErrors('email')
            ->assertSessionMissing('temporary_password');

        $this->assertSame($passwordBefore, $user->fresh()->password);
    }

    public function test_email_field_is_required_and_must_be_valid(): void
    {
        $this->post(route('password.update'), ['email' => 'bukan-email'])
            ->assertSessionHasErrors('email');
    }
}
