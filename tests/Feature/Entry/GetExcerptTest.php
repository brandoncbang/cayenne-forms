<?php

namespace Tests\Feature\Entry;

use App\Models\Entry;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GetExcerptTest extends TestCase
{
    #[Test]
    public function it_chooses_a_field_to_use_as_an_excerpt()
    {
        $entry = new Entry([
            'data' => [
                'email' => 'johndoe@example.com',
                'message' => 'Lorem ipsum dolor sit amet.',
            ],
        ]);

        $this->assertEquals('Lorem ipsum dolor sit amet.', $entry->getExcerpt());
    }

    #[Test]
    public function it_falls_back_to_a_field_with_a_name_ending_in_message()
    {
        $entry = new Entry([
            'data' => [
                'contact_email' => 'johndoe@example.com',
                'contact_message' => 'Lorem ipsum dolor sit amet.',
            ],
        ]);

        $this->assertEquals('Lorem ipsum dolor sit amet.', $entry->getExcerpt());
    }

    #[Test]
    public function it_falls_back_to_a_field_named_description()
    {
        $entry = new Entry([
            'data' => [
                'title' => 'Hello, World!',
                'description' => 'Lorem ipsum dolor sit amet.',
            ],
        ]);

        $this->assertEquals('Lorem ipsum dolor sit amet.', $entry->getExcerpt());
    }

    #[Test]
    public function it_falls_back_to_a_field_with_a_name_ending_in_description()
    {
        $entry = new Entry([
            'data' => [
                'item_title' => 'Hello, World!',
                'item_description' => 'Lorem ipsum dolor sit amet.',
            ],
        ]);

        $this->assertEquals('Lorem ipsum dolor sit amet.', $entry->getExcerpt());
    }

    #[Test]
    public function it_defaults_to_null()
    {
        $entry = new Entry([
            'data' => [
                'foo' => 'Hello, World!',
                'bar' => 'Lorem ipsum dolor sit amet.',
            ],
        ]);

        $this->assertNull($entry->getExcerpt());
    }
}
