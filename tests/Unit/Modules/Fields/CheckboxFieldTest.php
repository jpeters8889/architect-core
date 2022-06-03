<?php

namespace Jpeters8889\Architect\Tests\Unit\Modules\Fields;

use Jpeters8889\Architect\Modules\Fields\AbstractField;
use Jpeters8889\Architect\Modules\Fields\Checkbox;
use Jpeters8889\Architect\Tests\Factories\UserFactory;

class CheckboxFieldTest extends FieldTestCase
{
    protected function makeField(string $column, string $label = null): AbstractField
    {
        return Checkbox::make($column, $label);
    }

    /** @test */
    public function itCanGetTheCurrentValueForTabularDisplay(): void
    {
        $user = UserFactory::new()->create(['active' => true]);
        $field = $this->makeField('username');

        $this->assertEquals('Yes', $field->getCurrentValueForTable($user));
    }
}
