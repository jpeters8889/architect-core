<?php

namespace Jpeters8889\Architect\Tests\Features\Modules\Blueprints\Http;

use Jpeters8889\Architect\Tests\AppClasses\Models\User;
use Jpeters8889\Architect\Tests\Factories\UserFactory;
use Jpeters8889\Architect\Tests\FeatureTestCase;

class BlueprintDeleteTest extends FeatureTestCase
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
        $this->delete('/architect/blueprint/foo/1')->assertNotFound();
        $this->put('/architect/blueprint/foo/1')->assertNotFound();
    }

    /** @test */
    public function itErrorsIfAnItemDoesntExist(): void
    {
        $this->delete('/architect/blueprint/users/2')->assertNotFound();
        $this->put('/architect/blueprint/users/2')->assertNotFound();
    }

    /** @test */
    public function itDeletesTheItem(): void
    {
        $this->assertNotEmpty(User::all());

        $this->delete('/architect/blueprint/users/1');

        $this->assertNull($this->user->fresh());
        $this->assertEmpty(User::all());
    }

    /** @test */
    public function itReturnsOkIfABlueprintDoesExistAndRedirectsBack(): void
    {
        $this->delete('/architect/blueprint/users/1')->assertRedirect();
        $this->put('/architect/blueprint/users/1')->assertRedirect();
    }
}
