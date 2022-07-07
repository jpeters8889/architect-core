<?php

namespace Jpeters8889\Architect\Tests\AppClasses;

use Jpeters8889\Architect\Modules\Blueprints\AbstractBlueprint;
use Jpeters8889\Architect\Modules\Fields\Checkbox;
use Jpeters8889\Architect\Modules\Fields\DateTime;
use Jpeters8889\Architect\Modules\Fields\Password;
use Jpeters8889\Architect\Modules\Fields\SelectBox;
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
            TextField::make('id', 'ID')->tableOnly(),

            TextField::make('username')->validationRules(['required', 'unique:users']),

            TextField::make('email', 'Email Address')->validationRules(['required', 'email', 'unique:users']),

            Password::make('password')->hideOnTable()->required(),

            SelectBox::make('level')
                ->options(['Member', 'Privileged', 'Admin'])
                ->required(),

            Checkbox::make('active')->isSortable()->validationRules(['required', 'bool']),

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
