<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admin_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_admin_can_login_with_seeded_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@alterfoss.test',
            'password' => 'password',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@alterfoss.test',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('admin.home'));
    }

    public function test_wrong_password_is_rejected_and_email_is_kept(): void
    {
        User::factory()->create([
            'email' => 'admin@alterfoss.test',
            'password' => 'password',
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'admin@alterfoss.test',
            'password' => 'salah',
        ]);

        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertSame('admin@alterfoss.test', session()->getOldInput('email'));
    }

    public function test_admin_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
