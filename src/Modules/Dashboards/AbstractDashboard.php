<?php

namespace Jpeters8889\Architect\Modules\Dashboards;

use Jpeters8889\Architect\Shared\Contracts\Registerable;
use Jpeters8889\Architect\Shared\Traits\DisplaysOnNavigation;

/** @implements Registerable<AbstractDashboard> */
abstract class AbstractDashboard implements Registerable
{
    use DisplaysOnNavigation;
}
