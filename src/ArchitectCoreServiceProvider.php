<?php

namespace Jpeters8889\Architect;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Jpeters8889\Architect\Base\Http\Middleware\HandleInertiaRequests;
use Jpeters8889\Architect\Modules\Blueprints\Paginator;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class ArchitectCoreServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('architect-core')
            ->hasConfigFile()
            ->hasViews()
            ->hasAssets();
    }

    public function packageBooted(): void
    {
        $this->bootGate()
            ->bootRoutes()
            ->bootEvents()
            ->bootServices();
    }

    protected function bootEvents(): self
    {
        return $this;
    }

    protected function bootRoutes(): self
    {
        Route::macro('architect', function (string $basePath = 'architect') {
            /** @var array $baseMiddleware */
            $baseMiddleware = config('architect.middleware', []);
            config(['architect.base_path' => $basePath]);

            Route::prefix($basePath)
                ->middleware('web')
                ->group(function () use ($baseMiddleware) {
                    Route::middleware(array_merge($baseMiddleware, [HandleInertiaRequests::class]))
                        ->group(__DIR__ . '/../routes/architect.php');
                });
        });

        return $this;
    }

    protected function bootGate(): self
    {
        Gate::define('accessArchitect', fn () => $this->app->environment('local'));

        return $this;
    }

    protected function bootServices(): self
    {
        $this->app->bind(LengthAwarePaginator::class, fn ($app, $args) => new Paginator(...$args));

        return $this;
    }
}
