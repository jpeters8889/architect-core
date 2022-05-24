<?php

namespace Jpeters8889\Architect\Tests\Unit\Modules\Blueprints;

use Illuminate\Support\Collection;
use Jpeters8889\Architect\Modules\Blueprints\Registrar;
use Jpeters8889\Architect\Tests\AppClasses\UserBlueprint;
use Jpeters8889\Architect\Tests\TestCase;

class BlueprintRegistrarTest extends TestCase
{
    protected Registrar $registrar;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registrar = $this->app->make(Registrar::class);
    }

    /** @test */
    public function itCanReturnTheRegisteredBlueprints(): void
    {
        $this->assertInstanceOf(Collection::class, $this->registrar->all());
    }

    /** @test */
    public function itCanRegisterABlueprint(): void
    {
        $currentCount = $this->registrar->all()->count();

        $this->registrar->register(UserBlueprint::class);

        $this->assertCount($currentCount + 1, $this->registrar->all());
        $this->assertContains(UserBlueprint::class, $this->registrar->all());
    }
}
