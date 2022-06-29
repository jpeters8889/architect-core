<?php

namespace Jpeters8889\Architect\Tests\Unit\Modules\Blueprints\Processors;

use Illuminate\Foundation\Testing\WithFaker;
use Jpeters8889\Architect\Modules\Blueprints\Http\Requests\CreateItemRequest;
use Jpeters8889\Architect\Modules\Blueprints\Processors\CreateNewProcessor;
use Jpeters8889\Architect\Modules\Fields\AbstractField;
use Jpeters8889\Architect\Tests\AppClasses\Models\User;
use Jpeters8889\Architect\Tests\AppClasses\UserBlueprint;
use Jpeters8889\Architect\Tests\TestCase;

class CreateNewProcessorTest extends TestCase
{
    use WithFaker;

    protected CreateNewProcessor $createNewProcessor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createNewProcessor = new CreateNewProcessor(new UserBlueprint());
    }

    /** @test */
    public function itCanReturnTheBlueprint(): void
    {
        $this->assertInstanceOf(UserBlueprint::class, $this->createNewProcessor->blueprint());
    }

    /** @test */
    public function itCanReturnTheValidationRules(): void
    {
        $this->assertIsArray($this->createNewProcessor->validationRules());
    }

    /** @test */
    public function itReturnsTheRequiredDataInTheValidationRules(): void
    {
        $rules = $this->createNewProcessor->validationRules();

        collect($this->createNewProcessor->blueprint()->fields())
            ->filter(fn (AbstractField $field) => $field->shouldDisplayOnForm())
            ->each(fn (AbstractField $field) => $this->assertArrayHasKey($field->column(), $rules))
            ->each(fn (AbstractField $field) => $this->assertEquals($rules[$field->column()], $field->getValidationRules()));
    }

    /** @test */
    public function itCanCreateANewItemFromARequest(): void
    {
        $request = CreateItemRequest::create('/', 'POST', [
            'username' => $this->faker->userName,
            'password' => 'password',
            'email' => $this->faker->email,
            'level' => 'Member',
            'active' => false,
        ]);

        $this->assertEmpty(User::all());

        $this->createNewProcessor->createFromRequest($request);

        $this->assertNotEmpty(User::all());
    }
}
