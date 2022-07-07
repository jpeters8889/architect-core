<?php

namespace Jpeters8889\Architect\Modules\Blueprints\Processors;

use Illuminate\Support\Collection;
use Jpeters8889\Architect\Modules\Blueprints\AbstractBlueprint;
use Jpeters8889\Architect\Modules\Fields\AbstractField;

abstract class Processor
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
}
