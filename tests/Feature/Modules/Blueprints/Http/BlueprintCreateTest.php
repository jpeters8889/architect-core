<?php

namespace Jpeters8889\Architect\Tests\Features\Modules\Blueprints\Http;

use Inertia\Testing\AssertableInertia as Assert;
use Jpeters8889\Architect\Tests\FeatureTestCase;

class BlueprintCreateTest extends FeatureTestCase
{
    /** @test */
    public function itErrorsIfABlueprintDoesntExist(): void
    {
        $this->get('/architect/blueprint/foo/create')->assertNotFound();
    }

    /** @test */
    public function itReturnsOkIfABlueprintDoesExist(): void
    {
        $this->get('/architect/blueprint/users/create')->assertOk();
    }

    /** @test */
    public function itPassesTheListServiceDataToTheView(): void
    {
        $this->get('/architect/blueprint/users/create')
            ->assertInertia(
                fn (Assert $page) => $page->component('Blueprint/Create')->has('metas')
            );
    }
}
