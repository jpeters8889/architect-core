<?php

namespace Jpeters8889\Architect\Tests\AppClasses;

use Jpeters8889\Architect\Modules\Blueprints\AbstractBlueprint;
use Jpeters8889\Architect\Modules\Fields\Checkbox;
use Jpeters8889\Architect\Modules\Fields\DateTime;
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

            Checkbox::make('active')->isSortable(),

            DateTime::make('created_at')
                ->tableOnly()
                ->isSortable(),
        ];
    }

    public function orderBy(): array
    {
        return ['created_at', 'desc'];
    }
}
