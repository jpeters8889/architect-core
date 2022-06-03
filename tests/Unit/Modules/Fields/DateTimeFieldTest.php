<?php

namespace Jpeters8889\Architect\Tests\Unit\Modules\Fields;

use Carbon\Carbon;
use Jpeters8889\Architect\Modules\Fields\AbstractField;
use Jpeters8889\Architect\Modules\Fields\DateTime;
use Jpeters8889\Architect\Tests\Factories\UserFactory;
use Spatie\TestTime\TestTime;

class DateTimeFieldTest extends FieldTestCase
{
    protected function makeField(string $column, string $label = null): AbstractField
    {
        return DateTime::make($column, $label);
    }

    /** @test */
    public function itCanGetTheCurrentValueForTabularDisplay(): void
    {
        TestTime::freeze();

        $user = UserFactory::new()->create();
        $field = $this->makeField('created_at');

        $this->assertEquals(Carbon::now()->format('Y-m-d H:i:s'), $field->getCurrentValueForTable($user));
    }

    /** @test */
    public function itCanCustomiseTheFormatOfTheFieldForTabularDisplay(): void
    {
        TestTime::freeze();

        $user = UserFactory::new()->create();
        /** @var DateTime $field */
        $field = $this->makeField('created_at');

        $field->format('Y-m-d');

        $this->assertEquals(Carbon::now()->format('Y-m-d'), $field->getCurrentValueForTable($user));

        $field->format('H:i:s');

        $this->assertEquals(Carbon::now()->format('H:i:s'), $field->getCurrentValueForTable($user));
    }
}
