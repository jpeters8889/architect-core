<?php

namespace Jpeters8889\Architect\Tests\Unit\Modules\Fields;

use Illuminate\Database\Eloquent\Model;
use Jpeters8889\Architect\Modules\Fields\AbstractField;
use Jpeters8889\Architect\Tests\AppClasses\Models\User;
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
            ->getValueForTableUsing(fn (Model $model) => "{$model->username} Appended");

        $this->assertEquals('FooBar Appended', $field->getCurrentValueForTable($user));
    }

    /** @test */
    public function itCanGetTheCurrentValueForDisplayOnForms(): void
    {
        $user = UserFactory::new()->create(['username' => 'FooBar']);
        $field = $this->makeField('username');

        $this->assertEquals('FooBar', $field->getCurrentValueForForm($user));
    }

    /** @test */
    public function itCanGetTHeValueForDisplayOnFormsWithItsOwnGetter(): void
    {
        $user = UserFactory::new()->create(['username' => 'FooBar']);
        $field = $this->makeField('username')
            ->getValueForFormsUsing(fn (Model $model) => "{$model->username} Appended");

        $this->assertEquals('FooBar Appended', $field->getCurrentValueForForm($user));
    }

    /** @test */
    public function itKnowsWhetherItsSortable(): void
    {
        $field = $this->makeField('username');

        $this->assertFalse($field->sortable());

        $field->isSortable();

        $this->assertTrue($field->sortable());
    }

    /** @test */
    public function itCanSetATableToHideOnTabularViews(): void
    {
        $username = $this->makeField('username')->hideOnTable();
        $email = $this->makeField('email')->formOnly();

        $this->assertFalse($username->shouldDisplayOnTable());
        $this->assertFalse($email->shouldDisplayOnTable());
    }

    /** @test */
    public function itCanSetATableToHideOnFormViews(): void
    {
        $username = $this->makeField('username')->hideOnForm();
        $email = $this->makeField('email')->tableOnly();

        $this->assertFalse($username->shouldDisplayOnForm());
        $this->assertFalse($email->shouldDisplayOnForm());
    }

    /** @test */
    public function itCanSetFieldsOnAModel(): void
    {
        $model = new User();

        $this->assertNull($model->username);

        $this->makeField('username')->setValue($model, 'foobar');

        $this->assertNotNull($model->username);
        $this->assertEquals('foobar', $model->username);
    }

    /** @test */
    public function itCanUseACustomSetterOnTheField(): void
    {
        $model = new User();

        $this->assertNull($model->username);

        $field = $this->makeField('username')->setValueUsing(function (Model $model, mixed $value) {
            $model->username = "{$value} - appended";
        });

        $field->setValue($model, 'foobar');

        $this->assertEquals('foobar - appended', $model->username);
    }
}
