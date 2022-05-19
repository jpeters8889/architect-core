<?php

namespace Jpeters8889\Architect\Tests\AppClasses;

use Jpeters8889\Architect\Base\Providers\ArchitectAppServiceProvider as BaseServiceProvider;
use Jpeters8889\Architect\Modules\Dashboards\AppDashboard;

class ArchitectAppServiceProvider extends BaseServiceProvider
{
    protected function dashboards(): array
    {
        return [
            AppDashboard::class,
        ];
    }
}
