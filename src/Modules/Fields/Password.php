<?php

namespace Jpeters8889\Architect\Modules\Fields;

class Password extends AbstractField
{
    protected bool $displayOnTable = false;

    protected function booted(): void
    {
        $this->getValueForTableUsing(fn () => '');
    }
}
