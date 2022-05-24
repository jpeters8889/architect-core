<?php

namespace Jpeters8889\Architect;

use Jpeters8889\Architect\Modules\Blueprints\Registrar as BlueprintRegistrar;
use Jpeters8889\Architect\Modules\Dashboards\Registrar as DashboardRegistrar;

class ArchitectCore
{
    public function __construct(
        protected DashboardRegistrar $dashboardManager,
        protected BlueprintRegistrar $blueprintRegistrar
    ) {
        //
    }
}
