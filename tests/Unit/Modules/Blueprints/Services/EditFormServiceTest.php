<?php

namespace Jpeters8889\Architect\Tests\Unit\Modules\Blueprints\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Jpeters8889\Architect\Modules\Blueprints\DTO\BlueprintFormField;
use Jpeters8889\Architect\Modules\Blueprints\Services\EditFormService;
use Jpeters8889\Architect\Tests\AppClasses\Models\User;
use Jpeters8889\Architect\Tests\AppClasses\UserBlueprint;
use Jpeters8889\Architect\Tests\Factories\UserFactory;
use Jpeters8889\Architect\Tests\TestCase;

class EditFormServiceTest extends TestCase
{
    protected EditFormService $editFormService;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->editFormService = new EditFormService(new UserBlueprint());

        $this->user = UserFactory::new()->create();
    }

    /** @test */
    public function itCanLoadAnItemFromAnId(): void
    {
        $this->editFormService->loadFromId(1);

        $this->assertInstanceOf(User::class, $this->editFormService->item());
        $this->assertTrue($this->user->is($this->editFormService->item()));
    }

    /** @test */
    public function itErrorsIfTheItemDoesntExist(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->editFormService->loadFromId('foo');
    }

    /** @test */
    public function itCanReturnTheBlueprint(): void
    {
        $this->assertInstanceOf(UserBlueprint::class, $this->editFormService->blueprint());
    }

    /** @test */
    public function itCanReturnACollectionOfTableHeaders(): void
    {
        $headers = $this->editFormService->headers();

        $this->assertInstanceOf(Collection::class, $headers);
        $this->assertContains('Username', $headers);
        $this->assertContains('Email Address', $headers);
    }

    /** @test */
    public function itCanReturnACollectionOfTableRowColumns(): void
    {
        $columns = $this->editFormService->columns();

        $this->assertInstanceOf(Collection::class, $columns);
        $this->assertContains('username', $columns);
        $this->assertContains('email', $columns);
    }

    /** @test */
    public function itCanReturnACollectionOfTableRowComponents(): void
    {
        $components = $this->editFormService->components();

        $this->assertInstanceOf(Collection::class, $components);
        $this->assertContains('TextField', $components);
    }

    /** @test */
    public function itDoesntReturnAnItemInHeadersIfTheFieldIsSetToNotShowInTable(): void
    {
        $headers = $this->editFormService->headers();

        $this->assertNotContains('Created At', $headers);
    }

    /** @test */
    public function itDoesntReturnAnItemInColumnsIfTheFieldIsSetToNotShowInTable(): void
    {
        $columns = $this->editFormService->columns();

        $this->assertNotContains('Created At', $columns);
    }

    /** @test */
    public function itCanReturnTheListServiceMetaInformation(): void
    {
        $this->assertNotNull($this->editFormService->metas());
        $this->assertIsArray($this->editFormService->metas());
    }

    /** @test */
    public function itReturnsTheBlueprintTitleInTheMetas(): void
    {
        $metas = $this->editFormService->metas();

        $this->assertArrayHasKey('title', $metas);
        $this->assertEquals('Users', $metas['title']);
    }

    /** @test */
    public function itReturnsTheBlueprintSingularTitleInTheMetas(): void
    {
        $metas = $this->editFormService->metas();

        $this->assertArrayHasKey('singularTitle', $metas);
        $this->assertEquals('User', $metas['singularTitle']);
    }

    /** @test */
    public function itReturnsTheFormObject(): void
    {
        $this->editFormService->loadFromId(1);
        $form = $this->editFormService->formFields();

        $this->assertNotNull($form);
        $this->assertInstanceOf(Collection::class, $form);

        $form->each(function ($field) {
            $this->assertInstanceOf(BlueprintFormField::class, $field);
        });
    }

    /** @test */
    public function itAppendsTheCurrentValueOnTheFormObject(): void
    {
        $this->editFormService->loadFromId(1);
        $form = $this->editFormService->formFields();

        $this->assertNotNull($form);
        $this->assertInstanceOf(Collection::class, $form);

        $form->each(function (BlueprintFormField $field) {
            $this->assertNotNull($field->value);
        });
    }
}
