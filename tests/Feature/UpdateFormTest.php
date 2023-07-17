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

    #[Test]
    public function user_can_update_the_demo_form_outside_a_demo_environment()
    {
        $form = Form::factory()->create([
            'name' => 'Demo Form',
            'success_url' => '/demo/success',
        ]);

        $response = $this
            ->actingAs($form->user)
            ->from("/forms/{$form->uuid}/edit")
            ->patch("/forms/{$form->uuid}", [
                'name' => 'Some Other Name',
                'success_url' => 'https://example.com/some-other-url',
            ]);

        $form->refresh();

        $response
            ->assertRedirect("/forms/{$form->uuid}/entries")
            ->assertSessionMissing('error');

        $this->assertEquals('Some Other Name', $form->name);
        $this->assertEquals('https://example.com/some-other-url', $form->success_url);
    }

    #[Test]
    public function user_cannot_update_the_demo_form_in_a_demo_environment()
    {
        // Since the demo is public, we at least don't want users to be able to update the Form used for the demo page,
        // since doing so could break it at best and redirect other users to harmful URLs at worst. Other forms can be
        // fair game though.

        $this->withEnvironment('demo');

        $form = Form::factory()->create([
            'name' => 'Demo Form',
            'success_url' => '/demo/success',
        ]);

        $response = $this
            ->actingAs($form->user)
            ->from("/forms/{$form->uuid}/edit")
            ->patch("/forms/{$form->uuid}", [
                'name' => 'Some Other Name',
                'success_url' => 'https://example.com/some-possibly-malicious-redirect',
            ]);

        $form->refresh();

        $response
            ->assertRedirect("/forms/{$form->uuid}/edit")
            ->assertSessionHas('error', 'Updating the demo form is not allowed.');

        $this->assertEquals('Demo Form', $form->name);
        $this->assertEquals('/demo/success', $form->success_url);
    }
}
