<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ShowDemoFormTest extends TestCase
{
    #[Test]
    public function user_can_view_the_demo_form_page()
    {
        $response = $this->get('/demo');

        $response->assertStatus(200);
    }
}
