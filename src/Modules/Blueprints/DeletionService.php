<?php

namespace Jpeters8889\Architect\Modules\Blueprints;

use Illuminate\Database\Eloquent\Model;

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
