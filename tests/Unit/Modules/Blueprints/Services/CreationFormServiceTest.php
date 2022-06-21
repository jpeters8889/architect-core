<?php

namespace Jpeters8889\Architect\Tests\Unit\Modules\Blueprints\Services;

use Illuminate\Support\Collection;
use Jpeters8889\Architect\Modules\Blueprints\DTO\BlueprintFormField;
use Jpeters8889\Architect\Modules\Blueprints\Services\CreationFormService;
use Jpeters8889\Architect\Tests\AppClasses\UserBlueprint;
use Jpeters8889\Architect\Tests\TestCase;

class CreationFormServiceTest extends TestCase
{
    protected CreationFormService $creationService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->creationService = new CreationFormService(new UserBlueprint());
    }

    /** @test */
    public function itCanReturnTheBlueprint(): void
    {
        $this->assertInstanceOf(UserBlueprint::class, $this->creationService->blueprint());
    }

    /** @test */
    public function itCanReturnACollectionOfTableHeaders(): void
    {
        $headers = $this->creationService->headers();

        $this->assertInstanceOf(Collection::class, $headers);
        $this->assertContains('Username', $headers);
        $this->assertContains('Email Address', $headers);
    }

    /** @test */
    public function itCanReturnACollectionOfTableRowColumns(): void
    {
        $columns = $this->creationService->columns();

        $this->assertInstanceOf(Collection::class, $columns);
        $this->assertContains('username', $columns);
        $this->assertContains('email', $columns);
    }

    /** @test */
    public function itCanReturnACollectionOfTableRowComponents(): void
    {
        $components = $this->creationService->components();

        $this->assertInstanceOf(Collection::class, $components);
        $this->assertContains('TextField', $components);
    }

    /** @test */
    public function itDoesntReturnAnItemInHeadersIfTheFieldIsSetToNotShowInTable(): void
    {
        $headers = $this->creationService->headers();

        $this->assertNotContains('Created At', $headers);
    }

    /** @test */
    public function itDoesntReturnAnItemInColumnsIfTheFieldIsSetToNotShowInTable(): void
    {
        $columns = $this->creationService->columns();

        $this->assertNotContains('Created At', $columns);
    }

    /** @test */
    public function itCanReturnTheListServiceMetaInformation(): void
    {
        $this->assertNotNull($this->creationService->metas());
        $this->assertIsArray($this->creationService->metas());
    }

    /** @test */
    public function itReturnsTheBlueprintTitleInTheMetas(): void
    {
        $metas = $this->creationService->metas();

        $this->assertArrayHasKey('title', $metas);
        $this->assertEquals('Users', $metas['title']);
    }

    /** @test */
    public function itReturnsTheBlueprintSingularTitleInTheMetas(): void
    {
        $metas = $this->creationService->metas();

        $this->assertArrayHasKey('singularTitle', $metas);
        $this->assertEquals('User', $metas['singularTitle']);
    }

    /** @test */
    public function itReturnsTheFormObject(): void
    {
        $form = $this->creationService->formFields();

        $this->assertNotNull($form);
        $this->assertInstanceOf(Collection::class, $form);

        $form->each(function ($field) {
            $this->assertInstanceOf(BlueprintFormField::class, $field);
        });
    }
}
