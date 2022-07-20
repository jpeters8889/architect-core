<?php

namespace Jpeters8889\Architect\Tests\Features\Modules\Blueprints\Http;

use Inertia\Testing\AssertableInertia;
use Jpeters8889\Architect\Tests\AppClasses\Models\User;
use Jpeters8889\Architect\Tests\AppClasses\UserBlueprint;
use Jpeters8889\Architect\Tests\Factories\UserFactory;
use Jpeters8889\Architect\Tests\FeatureTestCase;

class BlueprintDuplicateFormTest extends FeatureTestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
    }

    /** @test */
    public function itPassesTheExistingValueToTheCreateView(): void
    {
        $this->get('/architect/blueprint/users/create/?from=1')
            ->assertInertia(function (AssertableInertia $page) {
                $page = $page->component('Blueprint/Create');

                $blueprint = new UserBlueprint();

                collect($page->toArray()['props']['fields'])
                    ->values()
                    ->each(function (array $prop) use ($blueprint) {
                        $field = $blueprint->resolveFieldFromColumn($prop['id']);
                        $value = $field->getCurrentValueForForm($this->user);

                        $this->assertNotNull($prop['value']);
                        $this->assertEquals($value, $prop['value']);
                    });
            });
    }
}
