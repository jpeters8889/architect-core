<?php

namespace Jpeters8889\Architect\Modules\Blueprints\Services;

use Illuminate\Database\Eloquent\Model;
use Jpeters8889\Architect\Modules\Blueprints\AbstractBlueprint;

class DeletionService
{
    public function __construct(protected AbstractBlueprint $blueprint, protected Model $model)
    {
        //
    }

    public function handleDelete(): void
    {
        $this->blueprint->handleDelete($this->model);
    }

    public function handleRestore(): void
    {
        $this->blueprint->handleRestore($this->model);
    }
}
