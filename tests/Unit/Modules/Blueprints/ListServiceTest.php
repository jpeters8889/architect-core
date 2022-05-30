<?php

namespace Jpeters8889\Architect\Tests\Unit\Modules\Blueprints;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Jpeters8889\Architect\Modules\Blueprints\ListService;
use Jpeters8889\Architect\Modules\Blueprints\Paginator;
use Jpeters8889\Architect\Tests\AppClasses\UserBlueprint;
use Jpeters8889\Architect\Tests\Factories\UserFactory;
use Jpeters8889\Architect\Tests\TestCase;

class ListServiceTest extends TestCase
{
    protected ListService $listService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->listService = new ListService(new UserBlueprint());
    }

    /** @test */
    public function itCanReturnTheBlueprint(): void
    {
        $this->assertInstanceOf(UserBlueprint::class, $this->listService->blueprint());
    }

    /** @test */
    public function itCanReturnACollectionOfTableHeaders(): void
    {
        $headers = $this->listService->headers();

        $this->assertInstanceOf(Collection::class, $headers);
        $this->assertContains('Username', $headers);
        $this->assertContains('Email Address', $headers);
    }

    /** @test */
    public function itCanReturnACollectionOfTableRowColumns(): void
    {
        $columns = $this->listService->columns();

        $this->assertInstanceOf(Collection::class, $columns);
        $this->assertContains('username', $columns);
        $this->assertContains('email', $columns);
    }

    /** @test */
    public function itDoesntReturnAnItemInHeadersIfTheFieldIsSetToNotShowInTable(): void
    {
        $headers = $this->listService->headers();

        $this->assertNotContains('Password', $headers);
    }

    /** @test */
    public function itCanGetThePaginator(): void
    {
        $this->listService->load();

        $this->assertNotNull($this->listService->paginator());
        $this->assertInstanceOf(LengthAwarePaginator::class, $this->listService->paginator());
        $this->assertInstanceOf(Paginator::class, $this->listService->paginator());
    }

    /** @test */
    public function itCanReturnAFormattedDataProperty(): void
    {
        UserFactory::new()->create();

        $this->listService->load();

        $keys = ['currentPage', 'numberOfPages', 'hasNextPage', 'hasPreviousPage', 'items'];

        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $this->listService->data());
        }
    }

    /** @test */
    public function itKnowsTheCurrentPage(): void
    {
        UserFactory::new()->count(15)->create();

        $this->listService->load(2);

        $this->assertEquals(2, $this->listService->data()['currentPage']);
    }

    /** @test */
    public function itKnowsTheNumberOfPages(): void
    {
        UserFactory::new()->count(15)->create();

        $this->listService->load();

        $this->assertEquals(2, $this->listService->data()['numberOfPages']);
    }

    /** @test */
    public function itKnowsIfItHasANextPage(): void
    {
        UserFactory::new()->count(15)->create();

        $this->listService->load();

        $this->assertTrue($this->listService->data()['hasNextPage']);

        $this->listService->load(2);

        $this->assertFalse($this->listService->data()['hasNextPage']);
    }

    /** @test */
    public function itKnowsIfItHasAPreviousPage(): void
    {
        UserFactory::new()->count(15)->create();

        $this->listService->load();

        $this->assertFalse($this->listService->data()['hasPreviousPage']);

        $this->listService->load(2);

        $this->assertTrue($this->listService->data()['hasPreviousPage']);
    }

    /** @test */
    public function itCanLoadTheItems(): void
    {
        UserFactory::new()->count(15)->create();

        $this->listService->load();

        $this->assertInstanceOf(Collection::class, $this->listService->data()['items']);
    }

    /** @test */
    public function itFormatsEachItem(): void
    {
        //
    }
}