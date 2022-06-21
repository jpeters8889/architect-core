<?php

namespace Jpeters8889\Architect\Tests\Unit\Modules\Blueprints\Services;

use Jpeters8889\Architect\Modules\Blueprints\Services\DeletionService;
use Jpeters8889\Architect\Tests\AppClasses\Models\User;
use Jpeters8889\Architect\Tests\AppClasses\UserBlueprint;
use Jpeters8889\Architect\Tests\Factories\UserFactory;
use Jpeters8889\Architect\Tests\TestCase;

class DeletionServiceTest extends TestCase
{
    protected DeletionService $deleteService;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();

        $this->deleteService = new DeletionService(new UserBlueprint(), $this->user);
    }

    /** @test */
    public function itHandlesTheModelDeleteAction(): void
    {
        $this->deleteService->handleDelete();

        $this->assertFalse($this->user->refresh()->exists);
    }
}
