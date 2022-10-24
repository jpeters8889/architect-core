<?php

namespace Jpeters8889\Architect\Tests\Unit\Modules\Blueprints;

use Illuminate\Database\Eloquent\Builder;
use Jpeters8889\Architect\Modules\Blueprints\AbstractBlueprint;
use Jpeters8889\Architect\Modules\Fields\TextField;
use Jpeters8889\Architect\Tests\AppClasses\UserBlueprint;
use Jpeters8889\Architect\Tests\Factories\UserFactory;
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

    /** @test */
    public function itCanDetermineWhetherARowCanBeEdited(): void
    {
        $this->assertTrue($this->blueprint->canEdit(UserFactory::new()->create()));
    }

    /** @test */
    public function itCanDetermineWhetherARowCanBeDuplicated(): void
    {
        $this->assertTrue($this->blueprint->canDuplicate(UserFactory::new()->create()));
    }

    /** @test */
    public function itCanDetermineWhetherARowCanBeDeleted(): void
    {
        $this->assertTrue($this->blueprint->canDelete(UserFactory::new()->create()));
    }

    /** @test */
    public function itCanDetermineWhetherAIsDeleted(): void
    {
        $model = UserFactory::new()->create();
        $this->assertFalse($this->blueprint->isItemDeleted($model));
    }

    /** @test */
    public function itCanGetThePublicUrlForAnItem(): void
    {
        $this->assertNull($this->blueprint->publicUrl(UserFactory::new()->create()));
    }

    /** @test */
    public function itCanDeleteAModel(): void
    {
        $model = UserFactory::new()->create();

        $this->blueprint->handleDelete($model);

        $this->assertFalse($model->refresh()->exists);
    }

    /** @test */
    public function itCanListTheAvailableFilters(): void
    {
        $availableFilters = $this->blueprint->availableFilters();

        $this->assertIsArray($availableFilters);
        $this->assertCount(2, $availableFilters);

        foreach ($availableFilters as $filter) {
            $this->assertIsArray($filter);
            $this->assertArrayHasKey('key', $filter);
            $this->assertArrayHasKey('label', $filter);
            $this->assertArrayHasKey('options', $filter);
            $this->assertIsArray($filter['options']);
        }


    }
}
