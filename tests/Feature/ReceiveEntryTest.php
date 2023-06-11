<?php

namespace Tests\Feature;

use App\Models\Entry;
use App\Models\Form;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ReceiveEntryTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function form_can_receive_an_entry()
    {
        $form = Form::factory()->create();

        $response = $this
            ->withServerVariables([
                'REMOTE_ADDR' => '76.163.245.123',
                'HTTP_USER_AGENT' => 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_6_8 rv:4.0; sl-SI) AppleWebKit/532.33.2 (KHTML, like Gecko) Version/4.0.5 Safari/532.33.2',
            ])
            ->post("/f/{$form->uuid}", [
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
                'phone' => '(123) 456-7890',
                'message' => 'Lorem ipsum dolor sit amet.',
            ]);

        $this->assertDatabaseCount(Entry::class, 1);

        $entry = Entry::first();

        $response->assertRedirect('/success');

        $this->assertEquals('76.163.245.123', $entry->ip_address);
        $this->assertEquals(
            'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_6_8 rv:4.0; sl-SI) AppleWebKit/532.33.2 (KHTML, like Gecko) Version/4.0.5 Safari/532.33.2',
            $entry->user_agent,
        );
        $this->assertEquals(
            [
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
                'phone' => '(123) 456-7890',
                'message' => 'Lorem ipsum dolor sit amet.',
            ],
            $entry->data,
        );
        $this->assertTrue($entry->form->is($form));
    }

    #[Test]
    public function form_cannot_receive_json()
    {
        $form = Form::factory()->create();

        $response = $this
            ->withServerVariables([
                'REMOTE_ADDR' => '76.163.245.123',
                'HTTP_USER_AGENT' => 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_6_8 rv:4.0; sl-SI) AppleWebKit/532.33.2 (KHTML, like Gecko) Version/4.0.5 Safari/532.33.2',
            ])
            ->postJson("/f/{$form->uuid}", [
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
                'phone' => '(123) 456-7890',
                'message' => 'Lorem ipsum dolor sit amet.',
            ]);

        $response->assertStatus(Response::HTTP_UNSUPPORTED_MEDIA_TYPE);

        $this->assertDatabaseEmpty(Entry::class);
    }

    #[Test]
    public function redirects_to_form_success_url()
    {
        $form = Form::factory()->create([
            'success_url' => 'https://example.com/success.html',
        ]);

        $response = $this
            ->withServerVariables([
                'REMOTE_ADDR' => '76.163.245.123',
                'HTTP_USER_AGENT' => 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_6_8 rv:4.0; sl-SI) AppleWebKit/532.33.2 (KHTML, like Gecko) Version/4.0.5 Safari/532.33.2',
            ])
            ->post("/f/{$form->uuid}", [
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
                'phone' => '(123) 456-7890',
                'message' => 'Lorem ipsum dolor sit amet.',
            ]);

        $response->assertRedirect($form->success_url);
    }

    #[Test]
    public function guest_can_view_default_success_page()
    {
        $response = $this->get('/success');

        $response->assertStatus(Response::HTTP_OK);
    }
}
