<?php

namespace Tests\Feature;

use App\Models\Entry;
use App\Models\Form;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Inertia\Testing\AssertableInertia as Assert;
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

        $response->assertRedirect('/');
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

    #[Test]
    public function user_cannot_see_archived_entries_without_a_filter()
    {
        $form = Form::factory()->create();

        [$archived, $nonArchived] = Entry::factory()
            ->count(2)
            ->sequence(
                ['archived_at' => now()],
                ['archived_at' => null],
            )
            ->for($form)
            ->create();

        $response = $this
            ->actingAs($form->user)
            ->get("/forms/{$form->uuid}/entries");

        $response->assertInertia(fn (Assert $page) => $page
            ->has('entries.data', 1)
            ->where('entries.data.0.uuid', $nonArchived->uuid)
        );
    }

    #[Test]
    public function user_can_see_archived_entries_with_a_filter()
    {
        $form = Form::factory()->create();

        [$archived, $nonArchived] = Entry::factory()
            ->count(2)
            ->sequence(
                ['archived_at' => now()],
                ['archived_at' => null],
            )
            ->for($form)
            ->create();

        $response = $this
            ->actingAs($form->user)
            ->get("/forms/{$form->uuid}/entries?filter=archived");

        $response->assertInertia(fn (Assert $page) => $page
            ->has('entries.data', 1)
            ->where('entries.data.0.uuid', $archived->uuid)
        );
    }

    #[Test]
    public function user_can_see_archived_entries_in_trash()
    {
        $form = Form::factory()->create();

        $archivedAndTrashed = Entry::factory()
            ->for($form)
            ->create([
                'archived_at' => now(),
                'deleted_at' => now(),
            ]);

        $response = $this
            ->actingAs($form->user)
            ->get("/forms/{$form->uuid}/entries?filter=trashed");

        $response->assertInertia(fn (Assert $page) => $page
            ->has('entries.data', 1)
            ->where('entries.data.0.uuid', $archivedAndTrashed->uuid)
        );
    }
}
