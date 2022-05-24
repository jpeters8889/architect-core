<?php

namespace Jpeters8889\Architect\Tests\AppClasses;

use Jpeters8889\Architect\Base\Providers\ArchitectAppServiceProvider as BaseServiceProvider;

class ArchitectAppServiceProvider extends BaseServiceProvider
{
    protected function dashboards(): array
    {
        return [
            TestDashboard::class,
        ];
    }

    protected function blueprints(): array
    {
        return [
            UserBlueprint::class,
            BlogBlueprint::class,
        ];
    }
}
