<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'app.name' => config('app.name'),
            'app.repoUrl' => config('cayenne.repo_url'),
            'auth.user' => $request->user(),
            'navigation.0' => [
                'name' => __('Forms'),
                'href' => route('forms.index'),
                'current' => $request->route()->named('forms.*'),
            ],
            'navigation.1' => fn () => config('cayenne.demo')
                ? [
                    'name' => __('Demo Form'),
                    'href' => route('demo.form'),
                    'current' => false,
                ]
                : null,
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
        ]);
    }
}
