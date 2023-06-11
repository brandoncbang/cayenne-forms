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

class UpdateEntryTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_update_an_entry()
    {
        $form = Form::factory()->create();
        $entry = Entry::factory()
            ->for($form)
            ->create([
                'archived_at' => null,
            ]);

        $response = $this
            ->actingAs($form->user)
            ->from("/forms/{$form->uuid}/entries")
            ->patch("/entries/{$entry->uuid}", [
                'archived_at' => now(),
            ]);

        $response->assertRedirect("/forms/{$form->uuid}/entries");

        $entry->refresh();
        $this->assertNotNull($entry->archived_at);
    }

    #[Test]
    public function guest_cannot_update_an_entry()
    {
        $form = Form::factory()->create();
        $entry = Entry::factory()
            ->for($form)
            ->create([
                'archived_at' => null,
            ]);

        $response = $this->patch("/entries/{$entry->uuid}", [
            'archived_at' => now(),
        ]);

        $response->assertRedirect('/login');

        $entry->refresh();
        $this->assertNull($entry->archived_at);
    }

    #[Test]
    public function user_cannot_update_the_entry_of_another_user()
    {
        $form = Form::factory()->create();
        $entry = Entry::factory()
            ->for($form)
            ->create([
                'archived_at' => null,
            ]);

        $nonOwner = User::factory()->create();

        $response = $this
            ->actingAs($nonOwner)
            ->patch("/entries/{$entry->uuid}", [
                'archived_at' => now(),
            ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $entry->refresh();
        $this->assertNull($entry->archived_at);
    }

    #[Test]
    public function archived_at_is_optional()
    {
        $form = Form::factory()->create();
        $entry = Entry::factory()->for($form)->create();

        $response = $this
            ->actingAs($form->user)
            ->patch("/entries/{$entry->uuid}", [
                'deleted_at' => now(),
            ]);

        $response->assertSessionDoesntHaveErrors(['archived_at']);
    }

    #[Test]
    public function archived_at_must_be_a_valid_date()
    {
        $form = Form::factory()->create();
        $entry = Entry::factory()->for($form)->create();

        $response = $this
            ->actingAs($form->user)
            ->patch("/entries/{$entry->uuid}", [
                'archived_at' => 'asdf',
            ]);

        $response->assertSessionHasErrors(['archived_at']);
    }

    #[Test]
    public function deleted_at_is_optional()
    {
        $form = Form::factory()->create();
        $entry = Entry::factory()->for($form)->create();

        $response = $this
            ->actingAs($form->user)
            ->patch("/entries/{$entry->uuid}", [
                'deleted_at' => now(),
            ]);

        $response->assertSessionDoesntHaveErrors(['deleted_at']);
    }

    #[Test]
    public function deleted_at_must_be_a_valid_date()
    {
        $form = Form::factory()->create();
        $entry = Entry::factory()->for($form)->create();

        $response = $this
            ->actingAs($form->user)
            ->patch("/entries/{$entry->uuid}", [
                'deleted_at' => 'asdf',
            ]);

        $response->assertSessionHasErrors(['deleted_at']);
    }
}
