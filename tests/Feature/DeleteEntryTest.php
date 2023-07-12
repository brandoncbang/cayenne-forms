<?php

namespace Tests\Feature;

use App\Models\Entry;
use App\Models\Form;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeleteEntryTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_delete_an_entry()
    {
        $form = Form::factory()->create();
        $entry = Entry::factory()->for($form)->create();

        $response = $this
            ->actingAs($form->user)
            ->from("/forms/{$form->uuid}/entries")
            ->delete("/entries/{$entry->uuid}");

        $response->assertRedirect("/forms/{$form->uuid}/entries");

        $this->assertDatabaseEmpty(Entry::class);
    }

    #[Test]
    public function guest_cannot_delete_an_entry()
    {
        $form = Form::factory()->create();
        $entry = Entry::factory()->for($form)->create();

        $response = $this->delete("/entries/{$entry->uuid}");

        $response->assertRedirect('/');

        $this->assertDatabaseCount(Entry::class, 1);
    }

    #[Test]
    public function user_cannot_delete_the_entry_of_another_user()
    {
        $form = Form::factory()->create();
        $entry = Entry::factory()->for($form)->create();

        $nonOwner = User::factory()->create();

        $response = $this
            ->actingAs($nonOwner)
            ->delete("/entries/{$entry->uuid}");

        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $this->assertDatabaseCount(Entry::class, 1);
    }
}
