<?php

namespace Tests;

use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function withEnvironment(string $environment): static
    {
        $this->app->detectEnvironment(
            fn () => $environment,
        );

        $this->withoutMiddleware([VerifyCsrfToken::class]);

        return $this;
    }
}
