<?php

namespace Jpeters8889\Architect\Modules\Blueprints;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Jpeters8889\Architect\Shared\Contracts\Registerable;
use Jpeters8889\Architect\Shared\Traits\DisplaysOnNavigation;

/** @implements Registerable<AbstractBlueprint> */
abstract class AbstractBlueprint implements Registerable
{
    use DisplaysOnNavigation;

    public function group(): string
    {
        return 'Blueprints';
    }

    protected function friendlyName(): string
    {
        return Str::of(class_basename(static::class))
            ->before('Blueprint')
            ->plural();
    }

    /** @return class-string<Model> */
    abstract protected function model(): string;
}
