<?php

namespace Jpeters8889\Architect\Shared\Traits;

use Illuminate\Support\Str;

trait DisplaysOnNavigation
{
    protected function friendlyName(): string
    {
        return class_basename(static::class);
    }

    public function slug(): string
    {
        return Str::of($this->friendlyName())
            ->snake()
            ->slug()
            ->toString();
    }

    public function label(): string
    {
        return Str::of($this->friendlyName())
            ->headline()
            ->toString();
    }
}
