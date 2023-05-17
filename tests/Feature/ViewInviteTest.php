<?php

namespace Tests\Feature;

use App\Models\Invite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ViewInviteTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_view_invite()
    {
        $invite = Invite::factory()->create([
            'user_id' => null,
        ]);

        $response = $this->get("/invites/{$invite->code}");

        $response->assertStatus(Response::HTTP_OK);
    }

    #[Test]
    public function user_cannot_view_used_invite()
    {
        $invite = Invite::factory()->create([
            'user_id' => User::factory()->create(),
        ]);

        $response = $this->get("/invites/{$invite->code}");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
