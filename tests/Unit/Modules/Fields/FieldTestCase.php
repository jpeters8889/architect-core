<?php

namespace Jpeters8889\Architect\Tests\Unit\Modules\Fields;

use Illuminate\Database\Eloquent\Model;
use Jpeters8889\Architect\Modules\Fields\AbstractField;
use Jpeters8889\Architect\Tests\Factories\UserFactory;
use Jpeters8889\Architect\Tests\TestCase;

abstract class FieldTestCase extends TestCase
{
    abstract protected function makeField(string $column, string $label = null): AbstractField;

    /** @test */
    public function itCanGetTheColumnName(): void
    {
        $field = $this->makeField('username');

        $this->assertEquals('username', $field->column());
    }

    /** @test */
    public function itCanGetTheLabel(): void
    {
        $field = $this->makeField('foo', 'bar');

        $this->assertEquals('bar', $field->label());
    }

    /** @test */
    public function itCanComputeALabelFromTheColumnName(): void
    {
        $field = $this->makeField('this-is-a-big-column');

        $this->assertEquals('This Is A Big Column', $field->label());
    }

    /** @test */
    public function itCanGetTheCurrentValueForTabularDisplay(): void
    {
        $user = UserFactory::new()->create(['username' => 'FooBar']);
        $field = $this->makeField('username');

        $this->assertEquals('FooBar', $field->getCurrentValueForTable($user));
    }

    /** @test */
    public function itCanGetTHeValueForTabularDisplayWithItsOwnGetter(): void
    {
        $user = UserFactory::new()->create(['username' => 'FooBar']);
        $field = $this->makeField('username')
            ->getValueForTableUsing(fn (Model $model, string $column) => "{$model->{$column}} Appended");

        $this->assertEquals('FooBar Appended', $field->getCurrentValueForTable($user));
    }
}
