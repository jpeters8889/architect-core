<?php

namespace Jpeters8889\Architect\Tests\Features\Modules\Blueprints\Http;

use Inertia\Testing\AssertableInertia as Assert;
use Jpeters8889\Architect\Modules\Blueprints\ListService;
use Jpeters8889\Architect\Tests\AppClasses\UserBlueprint;
use Jpeters8889\Architect\Tests\FeatureTestCase;

class BlueprintListTest extends FeatureTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withExceptionHandling();
    }

    /** @test */
    public function itErrorsIfABlueprintDoesntExist(): void
    {
        $this->get('/architect/blueprint/foo')->assertNotFound();
    }

    /** @test */
    public function itReturnsOkIfABlueprintDoesExist(): void
    {
        $this->get('/architect/blueprint/users')->assertOk();
    }

    /** @test */
    public function itPassesTheListServiceDataToTheView(): void
    {
        $listService = new ListService(new UserBlueprint());
        $listService->load();

        $this->get('/architect/blueprint/users')
            ->assertInertia(fn (Assert $page) => $page->component('Blueprint/Index')->has('data'));
    }
}
