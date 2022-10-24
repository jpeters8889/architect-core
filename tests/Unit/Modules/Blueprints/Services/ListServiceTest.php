<?php

namespace Jpeters8889\Architect\Tests\Unit\Modules\Blueprints\Services;

use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Jpeters8889\Architect\Modules\Blueprints\DTO\ListServiceLoader;
use Jpeters8889\Architect\Modules\Blueprints\Paginator;
use Jpeters8889\Architect\Modules\Blueprints\Services\ListService;
use Jpeters8889\Architect\Tests\AppClasses\UserBlueprint;
use Jpeters8889\Architect\Tests\Factories\UserFactory;
use Jpeters8889\Architect\Tests\TestCase;
use Spatie\TestTime\TestTime;

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
    public function itCanReturnACollectionOfTableRowComponents(): void
    {
        $components = $this->listService->components();

        $this->assertInstanceOf(Collection::class, $components);
        $this->assertContains('TextField', $components);
    }

    /** @test */
    public function itDoesntReturnAnItemInHeadersIfTheFieldIsSetToNotShowInTable(): void
    {
        $headers = $this->listService->headers();

        $this->assertNotContains('Password', $headers);
    }

    /** @test */
    public function itDoesntReturnAnItemInColumnsIfTheFieldIsSetToNotShowInTable(): void
    {
        $columns = $this->listService->columns();

        $this->assertNotContains('Password', $columns);
    }

    /** @test */
    public function itCanGetThePaginator(): void
    {
        $this->listService->load(new ListServiceLoader());

        $this->assertNotNull($this->listService->paginator());
        $this->assertInstanceOf(LengthAwarePaginator::class, $this->listService->paginator());
        $this->assertInstanceOf(Paginator::class, $this->listService->paginator());
    }

    /** @test */
    public function itCanReturnTheListServiceMetaInformation(): void
    {
        $this->assertNotNull($this->listService->metas());
        $this->assertIsArray($this->listService->metas());
    }

    /** @test */
    public function itReturnsTheBlueprintTitleInTheMetas(): void
    {
        $metas = $this->listService->metas();

        $this->assertArrayHasKey('title', $metas);
        $this->assertEquals('Users', $metas['title']);
    }

    /** @test */
    public function itReturnsTheBlueprintSingularTitleInTheMetas(): void
    {
        $metas = $this->listService->metas();

        $this->assertArrayHasKey('singularTitle', $metas);
        $this->assertEquals('User', $metas['singularTitle']);
    }

    /** @test */
    public function itReturnsTheFormattedHeaderInformationInTheMetas(): void
    {
        $metas = $this->listService->metas();

        $this->assertArrayHasKey('headers', $metas);

        $headers = $this->listService->headers()->values();
        $columns = $this->listService->columns()->values();
        $components = $this->listService->components()->values();

        foreach ($metas['headers'] as $index => $header) {
            $this->assertArrayHasKey('label', $header);
            $this->assertEquals($headers[$index], $header['label']);

            $this->assertArrayHasKey('column', $header);
            $this->assertEquals($columns[$index], $header['column']);

            $this->assertArrayHasKey('component', $header);
            $this->assertEquals($components[$index], $header['component']);

            $this->assertArrayHasKey('sortable', $header);
            $this->assertEquals(
                $this->listService->blueprint()->resolveFieldFromLabel($header['label'])?->sortable(),
                $header['sortable']
            );
        }
    }

    /** @test */
    public function itCanReturnAFormattedDataProperty(): void
    {
        UserFactory::new()->create();

        $this->listService->load(new ListServiceLoader());

        $keys = ['currentPage', 'numberOfPages', 'hasNextPage', 'hasPreviousPage', 'items'];

        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $this->listService->data());
        }
    }

    /** @test */
    public function itKnowsTheCurrentPageInTheCurrentDataSet(): void
    {
        UserFactory::new()->count(15)->create();

        $this->listService->load(new ListServiceLoader(page: 2));

        $this->assertEquals(2, $this->listService->data()['currentPage']);
    }

    /** @test */
    public function itKnowsTheNumberOfPagesInTheCurrentDataSet(): void
    {
        UserFactory::new()->count(15)->create();

        $this->listService->load(new ListServiceLoader());

        $this->assertEquals(2, $this->listService->data()['numberOfPages']);
    }

    /** @test */
    public function itKnowsIfItHasANextPageInTheCurrentDataSet(): void
    {
        UserFactory::new()->count(15)->create();

        $this->listService->load(new ListServiceLoader());

        $this->assertTrue($this->listService->data()['hasNextPage']);

        $this->listService->load(new ListServiceLoader(page: 2));

        $this->assertFalse($this->listService->data()['hasNextPage']);
    }

    /** @test */
    public function itKnowsIfItHasAPreviousPageInTheCurrentDataSet(): void
    {
        UserFactory::new()->count(15)->create();

        $this->listService->load(new ListServiceLoader());

        $this->assertFalse($this->listService->data()['hasPreviousPage']);

        $this->listService->load(new ListServiceLoader(page: 2));

        $this->assertTrue($this->listService->data()['hasPreviousPage']);
    }

    /** @test */
    public function itCanLoadTheItemsInTheCurrentDataSet(): void
    {
        UserFactory::new()->count(15)->create();

        $this->listService->load(new ListServiceLoader());

        $this->assertInstanceOf(Collection::class, $this->listService->data()['items']);
        $this->assertCount($this->listService->blueprint()->perPage(), $this->listService->data()['items']);
    }

    /** @test */
    public function itReturnsTheRequiredKeysForEachItemInTheCurrentDataSet(): void
    {
        UserFactory::new()->create();

        $this->listService->load(new ListServiceLoader());
        $items = $this->listService->data()['items'];

        $this->listService->columns()->each(fn(string $column) => $this->assertArrayHasKey($column, $items[0]));
    }

    /** @test */
    public function itFormatsEachItemInTheCurrentDataSet(): void
    {
        $user = UserFactory::new()->create();

        $this->listService->load(new ListServiceLoader());
        $items = $this->listService->data()['items'];

        $this->listService->columns()->each(function (string $column) use ($items, $user) {
            $value = $this->listService->blueprint()->resolveFieldFromColumn($column)?->getCurrentValueForTable($user);

            $this->assertEquals($value, $items[0][$column]);
        });
    }

    /** @test */
    public function itOrdersTheItemsUsingTheGivenOrderingProperty(): void
    {
        TestTime::freeze();

        $user1 = UserFactory::new()->create(['created_at' => Carbon::now()->subYear(), 'updated_at' => Carbon::now()->subYear()]);
        $user2 = UserFactory::new()->create();

        $this->listService->load(new ListServiceLoader());
        $items = $this->listService->data()['items'];

        $this->assertSame($user2->id, $items[0]['id']);
    }

    /** @test */
    public function itHasAMetaPropertyInEachItem(): void
    {
        UserFactory::new()->count(15)->create();

        $this->listService->load(new ListServiceLoader());
        $items = $this->listService->data()['items'];

        foreach ($items as $item) {
            $this->assertArrayHasKey('$meta', $item);

            $metas = $item['$meta'];

            $this->assertArrayHasKey('canEdit', $metas);
            $this->assertArrayHasKey('canDelete', $metas);
            $this->assertArrayHasKey('isDeleted', $metas);
            $this->assertArrayHasKey('canDuplicate', $metas);
            $this->assertArrayHasKey('publicUrl', $metas);
            $this->assertArrayHasKey('id', $metas);
        }
    }

    /** @test */
    public function itFiltersTheItemsUsingTheGivenFilterParameters(): void
    {
        UserFactory::new()->create(['username' => 'NormalMember', 'level' => 'Member']);
        UserFactory::new()->create(['username' => 'PrivilegedMember', 'level' => 'Privileged']);
        UserFactory::new()->create(['username' => 'AdminMember', 'level' => 'Admin']);

        $memberOnly = $this->listService->load(new ListServiceLoader(filter: ['level' => 'Member']))->data();

        $this->assertEquals(1, $memberOnly['totalItems']);
        $this->assertEquals('NormalMember', data_get($memberOnly, 'items.0.username'));
    }

    /** @test */
    public function itFiltersTheMultipleItemsUsingTheGivenFilterParameters(): void
    {
        UserFactory::new()->create(['username' => 'NormalMember', 'level' => 'Member']);
        UserFactory::new()->create(['username' => 'PrivilegedMember', 'level' => 'Privileged']);
        UserFactory::new()->create(['username' => 'AdminMember', 'level' => 'Admin']);

        $multipleResults = $this->listService->load(new ListServiceLoader(filter: ['level' => 'Member,Admin']))->data();

        $this->assertEquals(2, $multipleResults['totalItems']);

        $results = $multipleResults['items']->map(fn(Collection $item) => $item->get('username'));

        $this->assertContains('NormalMember', $results);
        $this->assertContains('AdminMember', $results);
        $this->assertNotContains('PrivilegedMember', $results);
    }
}
