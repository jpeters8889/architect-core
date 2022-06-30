<?php

namespace Jpeters8889\Architect\Modules\Fields;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Password extends AbstractField
{
    protected bool $displayOnTable = false;

    protected function booted(): void
    {
        $this->getValueForTableUsing(fn () => '');

        $this->setValueUsing(function (Model $model, mixed $value) {
            /** @var string $value */

            $model->{$this->column} = Hash::make($value);
        });
    }
}
