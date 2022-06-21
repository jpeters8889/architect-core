<?php

namespace Jpeters8889\Architect\Modules\Blueprints\Services;

use Illuminate\Support\Collection;
use Jpeters8889\Architect\Modules\Blueprints\DTO\BlueprintFormField;
use Jpeters8889\Architect\Modules\Fields\AbstractField;

class CreationFormService extends BlueprintDisplayService
{
    /**
     * @param Collection<int, AbstractField> $fields
     * @return Collection<int, AbstractField>
     */
    protected function transformFields(Collection $fields): Collection
    {
        return $fields->filter(fn (AbstractField $field) => $field->shouldDisplayOnForm());
    }

    /** @return Collection<int, BlueprintFormField> */
    public function formFields(): Collection
    {
        return $this->fields->map(function (AbstractField $field) {
            return new BlueprintFormField(
                id: $field->column(),
                label: $field->label(),
                component: $field->component(),
                helpText: $field->getFormHelpText(),
                rules: $field->getValidationRules(),
                meta: $field->metaData(),
            );
        });
    }
}
