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
    public function user_can_create_a_form()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/forms', [
                'name' => 'Contact Page',
                'success_url' => 'https://example.com/success.html',
                'sends_notifications' => true,
                'honeypot_field' => 'url',
            ]);

        $this->assertDatabaseCount(Form::class, 1);

        $form = Form::first();

        $response->assertRedirect("/forms/{$form->uuid}/edit");

        $this->assertEquals('Contact Page', $form->name);
        $this->assertEquals('https://example.com/success.html', $form->success_url);
        $this->assertTrue($form->sends_notifications);
        $this->assertEquals('url', $form->honeypot_field);
        $this->assertTrue($form->user->is($user));
    }

    #[Test]
    public function guest_cannot_create_form()
    {
        $response = $this->post('/forms', [
            'name' => 'Contact Page',
            'success_url' => 'https://example.com/success.html',
            'sends_notifications' => true,
            'honeypot_field' => 'url',
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
                'success_url' => 'https://example.com/success.html',
                'sends_notifications' => true,
                'honeypot_field' => 'url',
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
                'success_url' => 'https://example.com/success.html',
                'sends_notifications' => true,
                'honeypot_field' => 'url',
            ]);

        $response->assertSessionHasErrors(['name']);

        $this->assertDatabaseCount(Form::class, 1);
    }

    #[Test]
    public function name_is_only_unique_per_user()
    {
        [$userA, $userB] = User::factory()->count(2)->create();

        $formA = $userA->forms()->create([
            'name' => 'Contact Page',
        ]);

        $response = $this
            ->actingAs($userB)
            ->post('/forms', [
                'name' => 'Contact Page',
                'success_url' => 'https://example.com/success.html',
                'sends_notifications' => true,
                'honeypot_field' => 'url',
            ]);

        $formB = Form::latest('id')->first();

        $response->assertRedirect("/forms/{$formB->uuid}/edit");

        $this->assertEquals($formA->name, $formB->name);
        $this->assertFalse($formB->is($formA));
    }

    #[Test]
    public function success_url_is_optional()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/forms', [
                'name' => 'Contact Page',
                'success_url' => '',
                'sends_notifications' => true,
                'honeypot_field' => 'url',
            ]);

        $form = Form::first();

        $response->assertRedirect("/forms/{$form->uuid}/edit");

        $this->assertNull($form->success_url);
    }

    #[Test]
    public function honeypot_field_is_optional()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/forms', [
                'name' => 'Contact Page',
                'success_url' => 'https://example.com/success.html',
                'sends_notifications' => true,
                'honeypot_field' => '',
            ]);

        $form = Form::first();

        $response->assertRedirect("/forms/{$form->uuid}/edit");

        $this->assertNull($form->honeypot_field);
    }
}
