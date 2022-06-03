<?php

namespace Jpeters8889\Architect\Tests\Unit\Modules\Blueprints;

use Illuminate\Database\Eloquent\Builder;
use Jpeters8889\Architect\Modules\Blueprints\AbstractBlueprint;
use Jpeters8889\Architect\Modules\Fields\TextField;
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

    /** @test */
    public function itCanReturnAQueryInstance(): void
    {
        $this->assertInstanceOf(Builder::class, $this->blueprint->query());
    }

    /** @test */
    public function itCanGetTheModelKeyName(): void
    {
        $this->assertEquals('id', $this->blueprint->modelKey());
    }

    /** @test */
    public function itCanResolveAFieldForAGivenColumn(): void
    {
        $this->assertInstanceOf(TextField::class, $field = $this->blueprint->resolveFieldFromColumn('username'));
        $this->assertEquals('username', $field->column());
    }

    /** @test */
    public function itCanResolveAFieldForAGivenLabel(): void
    {
        $this->assertInstanceOf(TextField::class, $field = $this->blueprint->resolveFieldFromLabel('Email Address'));
        $this->assertEquals('email', $field->column());
    }

    /** @test */
    public function itCanSpecifyHowToOrderTheQueryResults(): void
    {
        $query = $this->blueprint->query()->orderBy(...$this->blueprint->orderBy());

        $ordering = $query->toBase()->orders;

        $this->assertEquals('created_at', $ordering[0]['column']);
        $this->assertEquals('desc', $ordering[0]['direction']);
    }
}
