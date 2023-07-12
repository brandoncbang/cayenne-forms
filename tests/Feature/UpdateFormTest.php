<?php

namespace Tests\Feature;

use App\Models\Form;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UpdateFormTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_view_form_editing_page()
    {
        $form = Form::factory()->create([
            'name' => 'Contact Page',
        ]);

        $response = $this
            ->actingAs($form->user)
            ->get("/forms/{$form->uuid}/edit");

        $response->assertStatus(Response::HTTP_OK);
    }

    #[Test]
    public function guest_cannot_view_form_editing_page()
    {
        $form = Form::factory()->create([
            'name' => 'Contact Page'
        ]);

        $response = $this->get("/forms/{$form->uuid}/edit");

        $response->assertRedirect('/');
    }

    #[Test]
    public function user_cannot_view_form_editing_page_of_another_user()
    {
        $form = Form::factory()->create([
            'name' => 'Contact Page',
        ]);

        $nonOwner = User::factory()->create();

        $response = $this
            ->actingAs($nonOwner)
            ->get("/forms/{$form->uuid}/edit");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    #[Test]
    public function user_can_update_a_form()
    {
        $form = Form::factory()->create([
            'name' => 'Contact Us Page',
            'success_url' => 'https://example.com/success.html',
            'sends_notifications' => false,
            'honeypot_field' => null,
        ]);

        $response = $this
            ->actingAs($form->user)
            ->patch("/forms/{$form->uuid}", [
                'name' => 'Contact Page',
                'success_url' => 'https://example.com/contact/success.html',
                'sends_notifications' => true,
                'honeypot_field' => 'url',
            ]);

        $form->refresh();

        $response->assertRedirect("/forms/{$form->uuid}/entries");

        $this->assertEquals('Contact Page', $form->name);
        $this->assertEquals('https://example.com/contact/success.html', $form->success_url);
        $this->assertTrue($form->sends_notifications);
        $this->assertEquals('url', $form->honeypot_field);
    }

    #[Test]
    public function guest_cannot_create_a_form()
    {
        $form = Form::factory()->create([
            'name' => 'Contact Us Page',
            'success_url' => 'https://example.com/success.html',
            'sends_notifications' => false,
            'honeypot_field' => null,
        ]);

        $response = $this->patch("/forms/{$form->uuid}", [
            'name' => 'Contact Page',
            'success_url' => 'https://example.com/contact/success.html',
            'sends_notifications' => true,
            'honeypot_field' => 'url',
        ]);

        $form->refresh();

        $response->assertRedirect('/');

        $this->assertEquals('Contact Us Page', $form->name);
        $this->assertFalse($form->sends_notifications);
        $this->assertNull($form->honeypot_field);
    }

    #[Test]
    public function user_cannot_update_the_form_of_another_user()
    {
        $form = Form::factory()->create([
            'name' => 'Contact Us Page',
            'success_url' => 'https://example.com/success.html',
            'sends_notifications' => false,
            'honeypot_field' => null,
        ]);

        $nonOwner = User::factory()->create();

        $response = $this
            ->actingAs($nonOwner)
            ->patch("/forms/{$form->uuid}", [
                'name' => 'Contact Page',
                'success_url' => 'https://example.com/contact/success.html',
                'sends_notifications' => true,
                'honeypot_field' => 'url',
            ]);

        $form->refresh();

        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $this->assertEquals('Contact Us Page', $form->name);
        $this->assertFalse($form->sends_notifications);
        $this->assertNull($form->honeypot_field);
    }

    #[Test]
    public function name_is_required()
    {
        $form = Form::factory()->create([
            'name' => 'Contact Us Page',
        ]);

        $response = $this
            ->actingAs($form->user)
            ->patch("/forms/{$form->uuid}", [
                'name' => '',
                'success_url' => 'https://example.com/contact/success.html',
                'sends_notifications' => true,
                'honeypot_field' => 'url',
            ]);

        $form->refresh();

        $response->assertSessionHasErrors(['name']);

        $this->assertEquals('Contact Us Page', $form->name);
    }

    #[Test]
    public function name_must_be_unique()
    {
        $user = User::factory()->create();

        Form::factory()->for($user)->create([
            'name' => 'Contact Page',
        ]);
        $form = Form::factory()->for($user)->create([
            'name' => 'Jobs Page',
        ]);

        $response = $this
            ->actingAs($user)
            ->patch("/forms/{$form->uuid}", [
                'name' => 'Contact Page',
                'success_url' => 'https://example.com/contact/success.html',
                'sends_notifications' => true,
                'honeypot_field' => 'url',
            ]);

        $form->refresh();

        $response->assertSessionHasErrors(['name']);

        $this->assertEquals('Jobs Page', $form->name);
    }

    #[Test]
    public function name_is_only_unique_per_user()
    {
        [$userA, $userB] = User::factory()->count(2)->create();

        Form::factory()->for($userA)->create([
            'name' => 'Contact Page',
        ]);
        $form = Form::factory()->for($userB)->create([
            'name' => 'Jobs Page',
        ]);

        $response = $this
            ->actingAs($userB)
            ->patch("/forms/{$form->uuid}", [
                'name' => 'Contact Page',
                'success_url' => 'https://example.com/contact/success.html',
                'sends_notifications' => true,
                'honeypot_field' => 'url',
            ]);

        $form->refresh();

        $response->assertRedirect("/forms/{$form->uuid}/entries");

        $this->assertEquals('Contact Page', $form->name);
    }

    #[Test]
    public function name_may_be_unchanged()
    {
        $form = Form::factory()->create([
            'name' => 'Contact Us Page',
            'success_url' => 'https://example.com/success.html',
            'sends_notifications' => false,
            'honeypot_field' => null,
        ]);

        $response = $this
            ->actingAs($form->user)
            ->patch("/forms/{$form->uuid}", [
                'name' => 'Contact Page',
                'success_url' => 'https://example.com/contact/success.html',
                'sends_notifications' => true,
                'honeypot_field' => 'url',
            ]);

        $form->refresh();

        $response->assertRedirect("/forms/{$form->uuid}/entries");

        $this->assertTrue($form->sends_notifications);
        $this->assertEquals('url', $form->honeypot_field);
    }
    #[Test]
    public function success_url_is_optional()
    {
        $form = Form::factory()->create([
            'name' => 'Contact Us Page',
            'success_url' => 'https://example.com/success.html',
            'sends_notifications' => false,
            'honeypot_field' => null,
        ]);

        $response = $this
            ->actingAs($form->user)
            ->patch("/forms/{$form->uuid}", [
                'name' => 'Contact Page',
                'success_url' => '',
                'sends_notifications' => true,
                'honeypot_field' => 'url',
            ]);

        $form->refresh();

        $response->assertRedirect("/forms/{$form->uuid}/entries");

        $this->assertNull($form->success_url);
    }

    #[Test]
    public function honeypot_field_is_optional()
    {
        $form = Form::factory()->create([
            'name' => 'Contact Us Page',
            'success_url' => 'https://example.com/success.html',
            'sends_notifications' => false,
            'honeypot_field' => null,
        ]);

        $response = $this
            ->actingAs($form->user)
            ->patch("/forms/{$form->uuid}", [
                'name' => 'Contact Page',
                'success_url' => 'https://example.com/contact/success.html',
                'sends_notifications' => true,
                'honeypot_field' => '',
            ]);

        $form->refresh();

        $response->assertRedirect("/forms/{$form->uuid}/entries");

        $this->assertNull($form->honeypot_field);
    }
}
