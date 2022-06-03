<?php

namespace Jpeters8889\Architect\Modules\Fields;

use Illuminate\Database\Eloquent\Model;

class Checkbox extends AbstractField
{
    protected function booted(): void
    {
        $this->getValueForTableUsing(fn (Model $model) => $model->{$this->column} ? 'Yes' : 'No');
    }
}
