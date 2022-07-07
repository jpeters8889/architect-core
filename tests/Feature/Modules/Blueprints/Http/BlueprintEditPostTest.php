<?php

namespace Jpeters8889\Architect\Tests\Features\Modules\Blueprints\Http;

use Illuminate\Foundation\Testing\WithFaker;
use Jpeters8889\Architect\Modules\Fields\AbstractField;
use Jpeters8889\Architect\Tests\AppClasses\Models\User;
use Jpeters8889\Architect\Tests\AppClasses\UserBlueprint;
use Jpeters8889\Architect\Tests\Factories\UserFactory;
use Jpeters8889\Architect\Tests\FeatureTestCase;

class BlueprintEditPostTest extends FeatureTestCase
{
    use WithFaker;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create(['active' => true]);
    }

    /** @test */
    public function itErrorsIfABlueprintDoesntExist(): void
    {
        $this->patch('/architect/blueprint/foo/1')->assertNotFound();
    }

    /** @test */
    public function itErrorsIfTheItemDoesntExist(): void
    {
        $this->patch('/architect/blueprint/users/999')->assertNotFound();
    }

    /** @test */
    public function itReturnsFoundIfABlueprintDoesExist(): void
    {
        $this->patch('/architect/blueprint/users/1')->assertRedirect();
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

        $this->patch('/architect/blueprint/users/1')->assertSessionHasErrors($requiredFields);
    }

    /** @test */
    public function itValidatesNonRequiredValidationRules(): void
    {
        UserFactory::new()->create(['username' => 'taken']);

        $this->patch('/architect/blueprint/users/1', [
            'username' => 'taken', // unique validation rule
            'email' => 'foo', // email validation rule
            'active' => 'bar', // bool validation rule
        ])->assertSessionHasErrors(['username', 'email', 'active']);
    }

    /** @test */
    public function itReturnsTheSubmittedDataWithTheValidationErrors(): void
    {
        UserFactory::new()->create(['username' => 'taken']);

        $this->patch('/architect/blueprint/users/1', [
            'username' => 'taken', // unique validation rule
            'email' => 'foo', // email validation rule
            'active' => 'bar', // bool validation rule
        ])->assertSessionHasInput(['username', 'email', 'active']);
    }

    /** @test */
    public function itDoesntReturnAUniqueErrorOnTheExistingValue(): void
    {
        $this->patch('/architect/blueprint/users/1', [
            'username' => $this->user->username,
            'email' => $this->user->email,
        ])->assertSessionDoesntHaveErrors(['username', 'email']);
    }

    /** @test */
    public function itUpdatesTheModel(): void
    {
        $payload = collect((new UserBlueprint())->fields())
            ->filter(fn (AbstractField $field) => $field->shouldDisplayOnForm())
            ->mapWithKeys(fn (AbstractField $field) => [$field->column() => $this->user->{$field->column()}])
            ->toArray();

        $payload['username'] = 'updated-username';
        $payload['email'] = 'updated-email@foo.com';
        $payload['active'] = false;

        $this->patch('/architect/blueprint/users/1', $payload);

        $this->user->refresh();

        $this->assertEquals('updated-username', $this->user->username);
        $this->assertEquals('updated-email@foo.com', $this->user->email);
        $this->assertFalse($this->user->active);
    }

    /** @test */
    public function itRedirectsToTheMainListPageWhenUpdated(): void
    {
        $payload = collect((new UserBlueprint())->fields())
            ->filter(fn (AbstractField $field) => $field->shouldDisplayOnForm())
            ->mapWithKeys(fn (AbstractField $field) => [$field->column() => $this->user->{$field->column()}])
            ->toArray();

        $payload['active'] = false;

        $this->withoutExceptionHandling()->patch('/architect/blueprint/users/1', $payload)
            ->assertRedirect('/architect/blueprint/users')->assertFlashMessage('User Updated!');
    }
}
