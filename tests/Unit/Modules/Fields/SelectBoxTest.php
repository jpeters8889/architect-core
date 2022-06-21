<?php

namespace Jpeters8889\Architect\Tests\Unit\Modules\Fields;

use Jpeters8889\Architect\Modules\Fields\AbstractField;
use Jpeters8889\Architect\Modules\Fields\SelectBox;
use Jpeters8889\Architect\Tests\Factories\UserFactory;

class SelectBoxTest extends FieldTestCase
{
    protected function makeField(string $column, string $label = null): AbstractField
    {
        return SelectBox::make($column, $label)->options([
            ['key' => 1, 'value' => 'First Value'],
            ['key' => 2, 'value' => 'Second Value'],
        ]);
    }

    /** @test */
    public function itPassesTheOptionsThroughToTheMetaData(): void
    {
        $field = $this->makeField('level');

        $this->assertArrayHasKey('options', $field->metaData());
        $this->assertIsArray($field->metaData()['options']);

        foreach ($field->metaData()['options'] as $option) {
            $this->assertArrayHasKey('key', $option);
            $this->assertArrayHasKey('value', $option);
        }
    }

    /** @test */
    public function itCanFormatTheOptionsFromSimpleStrings(): void
    {
        $field = $this->makeField('level')->options(['one', 'two', 'three']);

        $this->assertArrayHasKey('options', $field->metaData());
        $this->assertIsArray($field->metaData()['options']);

        foreach ($field->metaData()['options'] as $option) {
            $this->assertArrayHasKey('key', $option);
            $this->assertArrayHasKey('value', $option);
        }
    }

    public function itCanGetTheCurrentValueForTabularDisplay(): void
    {
        $user = UserFactory::new()->create(['level' => 1]);
        $field = $this->makeField('level');

        $this->assertEquals('First Value', $field->getCurrentValueForTable($user));

        $user = UserFactory::new()->create(['level' => 2]);
        $field = $this->makeField('level');

        $this->assertEquals('Second Value', $field->getCurrentValueForTable($user));
    }
}
