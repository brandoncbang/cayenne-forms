<?php

namespace Tests\Feature\Auth;

use App\Models\Invite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_register()
    {
        $invite = Invite::factory()->create([
            'email' => 'johndoe@example.com',
        ]);

        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'invite_code' => $invite->code,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/dashboard');

        $invite->refresh();

        $this->assertDatabaseCount(User::class, 1);
        $this->assertTrue($invite->user->is(User::first()));
    }

    #[Test]
    public function user_cannot_register_without_invite_code(): void
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'invite_code' => '',
        ]);

        $this->assertGuest();
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    #[Test]
    public function user_cannot_register_with_nonexistent_invite_code()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'invite_code' => 'FAKECODE1234',
        ]);

        $this->assertGuest();
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    #[Test]
    public function user_cannot_register_with_used_invite_code()
    {
        $invite = Invite::factory()->create([
            'user_id' => User::factory()->create([
                'email' => 'johndoe@example.com',
            ]),
            'email' => 'johndoe@example.com',
        ]);

        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'invite_code' => $invite->code,
        ]);

        $this->assertGuest();
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
