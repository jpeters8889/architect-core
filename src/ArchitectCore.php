<?php

namespace Jpeters8889\Architect;

use Jpeters8889\Architect\Modules\Dashboards\Manager as DashboardManager;

class ArchitectCore
{
    public function __construct(
        protected DashboardManager $dashboardManager,
    ) {
        //
    }
}
