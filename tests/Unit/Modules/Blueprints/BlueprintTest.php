<?php

namespace Jpeters8889\Architect\Tests\Unit\Modules\Blueprints;

use Jpeters8889\Architect\Modules\Blueprints\AbstractBlueprint;
use Jpeters8889\Architect\Tests\AppClasses\UserBlueprint;
use Jpeters8889\Architect\Tests\TestCase;

class BlueprintTest extends TestCase
{
    protected AbstractBlueprint $blueprint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->blueprint = new UserBlueprint();
    }

    /** @test */
    public function itResolvesTheLabel(): void
    {
        $this->assertEquals('Users', $this->blueprint->label());
    }

    /** @test */
    public function itResolvesTheSlug(): void
    {
        $this->assertEquals('users', $this->blueprint->slug());
    }
}
