<?php

namespace Jpeters8889\Architect\Tests\Features\Modules\Blueprints\Http;

use Inertia\Testing\AssertableInertia as Assert;
use Jpeters8889\Architect\Tests\AppClasses\Models\User;
use Jpeters8889\Architect\Tests\Factories\UserFactory;
use Jpeters8889\Architect\Tests\FeatureTestCase;

class BlueprintEditFormTest extends FeatureTestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
    }

    /** @test */
    public function itErrorsIfABlueprintDoesntExist(): void
    {
        $this->get('/architect/blueprint/foo/1')->assertNotFound();
    }

    /** @test */
    public function itErrorsIfAnItemDoesntExist(): void
    {
        $this->get('/architect/blueprint/users/2')->assertNotFound();
    }

    /** @test */
    public function itPassesTheListServiceDataToTheView(): void
    {
        $this->get('/architect/blueprint/users/1')
            ->assertInertia(
                fn (Assert $page) => $page->component('Blueprint/Edit')->has('metas')->has('fields')
            );
    }
}
