<?php

namespace Jpeters8889\Architect\Tests\AppClasses;

use Jpeters8889\Architect\Modules\Blueprints\AbstractBlueprint;
use Jpeters8889\Architect\Modules\Fields\TextField;
use Jpeters8889\Architect\Tests\AppClasses\Models\User;

class UserBlueprint extends AbstractBlueprint
{
    protected function model(): string
    {
        return User::class;
    }

    public function fields(): array
    {
        return [
            TextField::make('id', 'ID'),

            TextField::make('username'),

            TextField::make('email', 'Email Address'),

            TextField::make('password')->hideOnTable(),

            TextField::make('created_at'),
        ];
    }
}
