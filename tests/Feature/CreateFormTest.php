<?php

namespace Tests\Feature;

use App\Models\Form;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateFormTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_view_form_creation_page()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/forms/create');

        $response->assertStatus(Response::HTTP_OK);
    }

    #[Test]
    public function guest_cannot_view_form_creation_page()
    {
        $response = $this->get('/forms/create');

        $response->assertRedirect('/login');
    }

    #[Test]
    public function user_can_create_form()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/forms', [
                'name' => 'Contact Page',
            ]);

        $this->assertDatabaseCount(Form::class, 1);

        $form = Form::first();

        $response->assertRedirect("/forms/{$form->uuid}");

        $this->assertEquals('Contact Page', $form->name);
        $this->assertTrue($form->user->is($user));
    }

    #[Test]
    public function guest_cannot_create_form()
    {
        $response = $this->post('/forms', [
            'name' => 'Contact Page',
        ]);

        $response->assertRedirect('/login');
    }

    #[Test]
    public function name_is_required()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/forms', [
                'name' => '',
            ]);

        $response->assertSessionHasErrors(['name']);

        $this->assertDatabaseEmpty(Form::class);
    }

    #[Test]
    public function name_must_be_unique()
    {
        $user = User::factory()->create();

        $user->forms()->create([
            'name' => 'Contact Page',
        ]);

        $response = $this
            ->actingAs($user)
            ->post('/forms', [
                'name' => 'Contact Page',
            ]);

        $response->assertSessionHasErrors(['name']);

        $this->assertDatabaseCount(Form::class, 1);
    }
}
