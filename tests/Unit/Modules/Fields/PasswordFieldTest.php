<?php

namespace Jpeters8889\Architect\Tests\Unit\Modules\Fields;

use Jpeters8889\Architect\Modules\Fields\AbstractField;
use Jpeters8889\Architect\Modules\Fields\Password;

class PasswordFieldTest extends FieldTestCase
{
    protected function makeField(string $column, string $label = null): AbstractField
    {
        return Password::make($column, $label);
    }

    public function itCanGetTheCurrentValueForTabularDisplay(): void
    {
        $this->assertEquals('FooBar', $this->makeField('password')->getCurrentValueForTable());
    }

    /** @test */
    public function itIsHiddenOnTables(): void
    {
        $this->assertFalse($this->makeField('password')->shouldDisplayOnTable());
    }
}
