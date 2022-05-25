<?php

namespace Jpeters8889\Architect\Modules\Dashboards\Exceptions;

use Exception;

class DashboardNotFoundException extends Exception
{
    public static function fromSlug(string $slug): self
    {
        return new self(`Dashboard with slug {{$slug}} not found`);
    }
}
