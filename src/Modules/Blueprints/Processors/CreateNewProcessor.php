<?php

namespace Jpeters8889\Architect\Modules\Blueprints\Processors;

use Illuminate\Support\Facades\DB;
use Jpeters8889\Architect\Modules\Blueprints\Http\Requests\CreateItemRequest;
use Jpeters8889\Architect\Modules\Fields\AbstractField;

class CreateNewProcessor extends Processor
{
    public function createFromRequest(CreateItemRequest $request): void
    {
        DB::transaction(function () use ($request) {
            $model = $this->blueprint->newModel();

            $this->fields->each(function (AbstractField $field) use ($request, $model) {
                $field->setValue($model, $request->input($field->column()));
            });

            $model->save();
        });
    }
}
