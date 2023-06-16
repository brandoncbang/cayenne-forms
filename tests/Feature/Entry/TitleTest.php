<?php

namespace Tests\Feature\Entry;

use App\Models\Entry;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TitleTest extends TestCase
{
    #[Test]
    public function it_chooses_a_field_to_use_as_a_title()
    {
        $entry = new Entry([
            'ip_address' => 'foo',
            'data' => [
                'email' => 'johndoe@example.com',
                'message' => 'Lorem ipsum dolor sit amet.',
            ],
        ]);

        $this->assertEquals('johndoe@example.com', $entry->title);
    }

    #[Test]
    public function it_falls_back_to_a_field_with_a_name_ending_in_email()
    {
        $entry = new Entry([
            'data' => [
                'contact_email' => 'johndoe@example.com',
                'contact_message' => 'Lorem ipsum dolor sit amet.',
            ],
        ]);

        $this->assertEquals('johndoe@example.com', $entry->title);
    }

    #[Test]
    public function it_falls_back_to_a_field_named_subject()
    {
        $entry = new Entry([
            'data' => [
                'subject' => 'Hello, World!',
                'message' => 'Lorem ipsum dolor sit amet.',
            ],
        ]);

        $this->assertEquals('Hello, World!', $entry->title);
    }

    #[Test]
    public function it_falls_back_to_a_field_with_a_name_ending_in_subject()
    {
        $entry = new Entry([
            'data' => [
                'contact_subject' => 'Hello, World!',
                'contact_message' => 'Lorem ipsum dolor sit amet.',
            ],
        ]);

        $this->assertEquals('Hello, World!', $entry->title);
    }

    #[Test]
    public function it_falls_back_to_a_field_named_title()
    {
        $entry = new Entry([
            'data' => [
                'title' => 'Hello, World!',
                'message' => 'Lorem ipsum dolor sit amet.',
            ],
        ]);

        $this->assertEquals('Hello, World!', $entry->title);
    }

    #[Test]
    public function it_falls_back_to_a_field_with_a_name_ending_in_title()
    {
        $entry = new Entry([
            'data' => [
                'contact_title' => 'Hello, World!',
                'contact_message' => 'Lorem ipsum dolor sit amet.',
            ],
        ]);

        $this->assertEquals('Hello, World!', $entry->title);
    }

    #[Test]
    public function it_defaults_to_untitled()
    {
        $entry = new Entry([
            'data' => [
                'foo' => 'Hello, World!',
                'bar' => 'Lorem ipsum dolor sit amet.',
            ],
        ]);

        $this->assertEquals('(Untitled)', $entry->title);
    }
}
