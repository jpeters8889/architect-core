<?php

namespace Jpeters8889\Architect\Base\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Jpeters8889\Architect\Modules\Navigation\NavigationResolver;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'architect-core::architect';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param Request $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param Request $request
     * @return array
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'basePath' => config('architect.base_path'),
            'navigation' => $this->navigation(),
            'flash' => fn () => $request->hasSession() ? $request->session()->get('flash') : null,
        ]);
    }

    protected function navigation(): array
    {
        return resolve(NavigationResolver::class)->build();
    }
}
