<?php

namespace Jpeters8889\Architect\Modules\Dashboards;

use Illuminate\Support\Str;

abstract class AbstractDashboard
{
    public function label(): string
    {
        return Str::of(class_basename(static::class))
            ->headline()
            ->toString();
    }

    public function slug(): string
    {
        return Str::of(class_basename(static::class))
            ->snake()
            ->slug()
            ->toString();
    }
}
