<?php

namespace Jpeters8889\Architect\Tests\Unit\Modules\Blueprints\Processors;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\Rules\Unique;
use Jpeters8889\Architect\Modules\Blueprints\Http\Requests\UpdateItemRequest;
use Jpeters8889\Architect\Modules\Blueprints\Processors\EditItemProcessor;
use Jpeters8889\Architect\Modules\Fields\AbstractField;
use Jpeters8889\Architect\Tests\AppClasses\Models\User;
use Jpeters8889\Architect\Tests\AppClasses\UserBlueprint;
use Jpeters8889\Architect\Tests\Factories\UserFactory;
use Jpeters8889\Architect\Tests\TestCase;

class EditItemProcessorTest extends TestCase
{
    use WithFaker;

    protected EditItemProcessor $editItemProcessor;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create(['active' => true]);

        $this->editItemProcessor = new EditItemProcessor(new UserBlueprint());
        $this->editItemProcessor->resolveItem($this->user);
    }

    /** @test */
    public function itCanReturnTheBlueprint(): void
    {
        $this->assertInstanceOf(UserBlueprint::class, $this->editItemProcessor->blueprint());
    }

    /** @test */
    public function itCanReturnTheValidationRules(): void
    {
        $this->assertIsArray($this->editItemProcessor->validationRules());
    }

    /** @test */
    public function itReturnsTheRequiredDataInTheValidationRules(): void
    {
        $rules = $this->editItemProcessor->validationRules();

        collect($this->editItemProcessor->blueprint()->fields())
            ->filter(fn (AbstractField $field) => $field->shouldDisplayOnForm())
            ->each(fn (AbstractField $field) => $this->assertArrayHasKey($field->column(), $rules));
    }

    /** @test */
    public function itConvertsUniqueRulesToExcludeTheCurrentItem(): void
    {
        $rules = $this->editItemProcessor->validationRules();

        $this->assertInstanceOf(Unique::class, $rules['username'][1]);
        $this->assertInstanceOf(Unique::class, $rules['email'][2]);
    }

    /** @test */
    public function itCanUpdateTheItemFromARequest(): void
    {
        $request = UpdateItemRequest::create('/', 'PATCH', [
            'username' => 'updated',
            'password' => 'password',
            'email' => 'updated@foo.com',
            'level' => 'Member',
            'active' => false,
        ]);

        $this->editItemProcessor->updateItemFromRequest($request);

        $this->user->refresh();

        $this->assertEquals('updated', $this->user->username);
        $this->assertEquals('updated@foo.com', $this->user->email);
        $this->assertFalse($this->user->active);
    }
}
