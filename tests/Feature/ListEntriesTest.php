<?php

namespace Tests\Feature;

use App\Models\Form;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ListEntriesTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_view_the_entries_list_page()
    {
        $form = Form::factory()->create();

        $response = $this
            ->actingAs($form->user)
            ->get("/forms/{$form->uuid}/entries");

        $response->assertStatus(Response::HTTP_OK);
    }

    #[Test]
    public function guest_cannot_view_the_entries_list_page()
    {
        $form = Form::factory()->create();

        $response = $this->get("/forms/{$form->uuid}/entries");

        $response->assertRedirect('/login');
    }

    #[Test]
    public function user_cannot_view_the_entries_list_page_of_another_user()
    {
        $form = Form::factory()->create();

        $nonOwner = User::factory()->create();

        $response = $this
            ->actingAs($nonOwner)
            ->get("/forms/{$form->uuid}/entries");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
