<?php

namespace Jpeters8889\Architect\Tests\AppClasses;

use Jpeters8889\Architect\Modules\Blueprints\AbstractBlueprint;
use Jpeters8889\Architect\Tests\AppClasses\Models\User;

class UserBlueprint extends AbstractBlueprint
{
    protected function model(): string
    {
        return User::class;
    }
}
