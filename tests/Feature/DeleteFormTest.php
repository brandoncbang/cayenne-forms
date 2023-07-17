<?php

namespace Tests\Feature;

use App\Models\Form;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeleteFormTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_delete_a_form()
    {
        $form = Form::factory()->create();

        $response = $this
            ->actingAs($form->user)
            ->delete("/forms/{$form->uuid}");

        $response->assertRedirect('/forms');

        $this->assertDatabaseEmpty(Form::class);
    }

    #[Test]
    public function guest_cannot_delete_a_form()
    {
        $form = Form::factory()->create();

        $response = $this->delete("/forms/{$form->uuid}");

        $response->assertRedirect('/');

        $this->assertDatabaseCount(Form::class, 1);
    }

    #[Test]
    public function user_cannot_delete_the_entry_of_another_user()
    {
        $form = Form::factory()->create();

        $nonOwner = User::factory()->create();

        $response = $this
            ->actingAs($nonOwner)
            ->delete("/forms/{$form->uuid}");

        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $this->assertDatabaseCount(Form::class, 1);
    }

    #[Test]
    public function user_can_delete_the_demo_form_outside_a_demo_environment()
    {
        $form = Form::factory()->create([
            'name' => 'Demo Form',
        ]);

        $response = $this
            ->actingAs($form->user)
            ->delete("/forms/{$form->uuid}");

        $response
            ->assertRedirect('/forms')
            ->assertSessionMissing('error');

        $this->assertDatabaseEmpty(Form::class);
    }

    #[Test]
    public function user_cannot_delete_the_demo_form_in_a_demo_environment()
    {
        $this->withEnvironment('demo');

        $form = Form::factory()->create([
            'name' => 'Demo Form',
        ]);

        $response = $this
            ->actingAs($form->user)
            ->from("/forms/{$form->uuid}/edit")
            ->delete("/forms/{$form->uuid}");

        $response
            ->assertRedirect("/forms/{$form->uuid}/edit")
            ->assertSessionHas('error', 'Deleting the demo form is not allowed.');

        $this->assertDatabaseCount(Form::class, 1);
    }
}
