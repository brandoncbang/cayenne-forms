<?php

namespace Tests\Feature;

use App\Models\Entry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson as Assert;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ViewEntryTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_view_an_entry()
    {
        $entry = Entry::factory()->create();

        $response = $this
            ->actingAs($entry->form->user)
            ->getJson("/entries/{$entry->uuid}");

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(fn (Assert $json) => $json
                ->has('entry', fn (Assert $json) => $json
                    ->missing('id')
                    ->missing('form_id')
                    ->where('uuid', $entry->uuid)
                    ->where('ip_address', $entry->ip_address)
                    ->where('user_agent', $entry->user_agent)
                    ->where('data', $entry->data)
                    ->where('archived_at', null)
                    ->etc()
                )
            );
    }

    #[Test]
    public function guest_cannot_view_an_entry()
    {
        $entry = Entry::factory()->create();

        $response = $this->getJson("/entries/{$entry->uuid}");

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    #[Test]
    public function user_cannot_view_the_entry_of_another_user()
    {
        $entry = Entry::factory()->create();

        $nonOwner = User::factory()->create();

        $response = $this
            ->actingAs($nonOwner)
            ->getJson("/entries/{$entry->uuid}");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    #[Test]
    public function user_can_view_an_archived_entry()
    {
        $archived = Entry::factory()->archived()->create();

        $response = $this
            ->actingAs($archived->form->user)
            ->getJson("/entries/{$archived->uuid}");

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(fn (Assert $json) => $json
                ->has('entry', fn (Assert $json) => $json
                    ->where('uuid', $archived->uuid)
                    ->whereNot('archived_at', null)
                    ->etc()
                )
            );
    }

    #[Test]
    public function user_can_view_a_trashed_entry()
    {
        $trashed = Entry::factory()->trashed()->create();

        $response = $this
            ->actingAs($trashed->form->user)
            ->getJson("/entries/{$trashed->uuid}");

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(fn (Assert $json) => $json
                ->has('entry', fn (Assert $json) => $json
                    ->where('uuid', $trashed->uuid)
                    ->whereNot('deleted_at', null)
                    ->etc()
                )
            );
    }
}
