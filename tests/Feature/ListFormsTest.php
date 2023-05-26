<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ListFormsTest extends TestCase
{
    #[Test]
    public function user_can_view_forms_list_page()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/forms');

        $response->assertStatus(Response::HTTP_OK);
    }

    #[Test]
    public function guest_cannot_view_forms_list_page()
    {
        $response = $this->get('/forms');

        $response->assertRedirect('/login');
    }
}
