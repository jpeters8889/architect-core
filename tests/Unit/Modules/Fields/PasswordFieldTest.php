<?php

namespace Jpeters8889\Architect\Tests\Unit\Modules\Fields;

use Illuminate\Support\Facades\Hash;
use Jpeters8889\Architect\Modules\Fields\AbstractField;
use Jpeters8889\Architect\Modules\Fields\Password;
use Jpeters8889\Architect\Tests\AppClasses\Models\User;
use Jpeters8889\Architect\Tests\Factories\UserFactory;

class PasswordFieldTest extends FieldTestCase
{
    protected function makeField(string $column, string $label = null): AbstractField
    {
        return Password::make($column, $label);
    }

    /** @test */
    public function itCanGetTheCurrentValueForDisplayOnForms(): void
    {
        $user = UserFactory::new()->create(['password' => 'secret']);
        $field = $this->makeField('password');

        $this->assertEquals('', $field->getCurrentValueForForm($user));
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

    public function itCanSetFieldsOnAModel(): void
    {
        $model = new User();

        $this->assertNull($model->password);

        $this->makeField('password')->setValue($model, 'foobar');

        $this->assertNotNull($model->username);
        $this->assertTrue(Hash::check('foobar', $model->password));
    }
}
