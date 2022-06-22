<?php

namespace Jpeters8889\Architect\Tests\Features\Modules\Blueprints\Http;

use Jpeters8889\Architect\Modules\Fields\AbstractField;
use Jpeters8889\Architect\Tests\AppClasses\UserBlueprint;
use Jpeters8889\Architect\Tests\Factories\UserFactory;
use Jpeters8889\Architect\Tests\FeatureTestCase;

class BlueprintCreatePostTest extends FeatureTestCase
{
    /** @test */
    public function itErrorsIfABlueprintDoesntExist(): void
    {
        $this->post('/architect/blueprint/foo')->assertNotFound();
    }

    /** @test */
    public function itReturnsFoundIfABlueprintDoesExist(): void
    {
        $this->post('/architect/blueprint/users')->assertRedirect();
    }

    /** @test */
    public function itReturnsErrorsInSessionForRequiredFields(): void
    {
        /** @var string[] $requiredFields */
        $requiredFields = collect((new UserBlueprint())->fields())
            ->filter(fn (AbstractField $field) => $field->shouldDisplayOnForm())
            ->mapWithKeys(fn (AbstractField $field) => [$field->column() => $field->getValidationRules()])
            ->filter(fn (array $rules) => in_array('required', $rules, true))
            ->keys()
            ->toArray();

        $this->post('/architect/blueprint/users')->assertSessionHasErrors($requiredFields);
    }

    /** @test */
    public function itValidatesNonRequiredValidationRules(): void
    {
        UserFactory::new()->create(['username' => 'taken']);

        $this->post('/architect/blueprint/users', [
            'username' => 'taken', // unique validation rule
            'email' => 'foo', // email validation rule
            'active' => 'bar', // bool validation rule
        ])->assertSessionHasErrors(['username', 'email', 'active']);
    }

    /** @test */
    public function itReturnsTheSubmittedDataWithTheValidationErrors(): void
    {
        UserFactory::new()->create(['username' => 'taken']);

        $this->post('/architect/blueprint/users', [
            'username' => 'taken', // unique validation rule
            'email' => 'foo', // email validation rule
            'active' => 'bar', // bool validation rule
        ])->assertSessionHasInput(['username', 'email', 'active']);
    }
}
