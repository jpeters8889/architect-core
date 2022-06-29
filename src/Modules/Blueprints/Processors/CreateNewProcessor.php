<?php

namespace Jpeters8889\Architect\Modules\Blueprints\Processors;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Jpeters8889\Architect\Modules\Blueprints\AbstractBlueprint;
use Jpeters8889\Architect\Modules\Blueprints\Http\Requests\CreateItemRequest;
use Jpeters8889\Architect\Modules\Fields\AbstractField;

class CreateNewProcessor
{
    /** @var Collection<int, AbstractField> */
    protected Collection $fields;

    public function __construct(protected AbstractBlueprint $blueprint)
    {
        $this->fields = collect($this->blueprint->fields())
            ->filter(fn (AbstractField $field) => $field->shouldDisplayOnForm());
    }

    public function blueprint(): AbstractBlueprint
    {
        return $this->blueprint;
    }

    public function validationRules(): array
    {
        return $this->fields->mapWithKeys(fn (AbstractField $field) => [
            $field->column() => $field->getValidationRules(),
        ])->toArray();
    }

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
