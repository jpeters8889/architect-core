<?php

namespace Jpeters8889\Architect\Tests\Unit\Modules\Fields;

use Jpeters8889\Architect\Modules\Fields\AbstractField;
use Jpeters8889\Architect\Modules\Fields\TextField;

class TextFieldTest extends FieldTestCase
{
    protected function makeField(string $column, string $label = null): AbstractField
    {
        return TextField::make($column, $label);
    }
}
