<?php

namespace Tests\Feature;

use App\Models\Form;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

        $response->assertRedirect("/forms");

        $this->assertDatabaseEmpty(Form::class);
    }

    #[Test]
    public function guest_cannot_delete_a_form()
    {
        $this->markTestIncomplete();
    }

    #[Test]
    public function user_cannot_delete_the_entry_of_another_user()
    {
        $this->markTestIncomplete();
    }
}
