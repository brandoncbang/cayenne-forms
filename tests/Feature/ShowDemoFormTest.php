<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ShowDemoFormTest extends TestCase
{
    #[Test]
    public function user_cannot_view_the_demo_form_page_outside_a_demo_environment()
    {
        $response = $this->get('/demo');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    #[Test]
    public function user_can_view_the_demo_form_page_in_a_demo_environment()
    {
        App::detectEnvironment(fn () => 'demo');

        $response = $this->get('/demo');

        $response->assertStatus(Response::HTTP_OK);
    }
}
