<?php

namespace Jpeters8889\Architect\Modules\Blueprints\Services;

use Illuminate\Database\Eloquent\Model;
use Jpeters8889\Architect\Modules\Blueprints\DTO\BlueprintFormField;
use Jpeters8889\Architect\Modules\Fields\AbstractField;

class EditFormService extends CreationFormService
{
    protected Model $item;

    public function loadFromId(string|int $id): static
    {
        $this->item = $this->blueprint->query()->findOrFail($id);

        return $this;
    }

    public function item(): Model
    {
        return $this->item;
    }

    protected function createFormFieldPayload(AbstractField $field): BlueprintFormField
    {
        $payload = parent::createFormFieldPayload($field);

        $payload->value = $field->getCurrentValueForForm($this->item);

        return $payload;
    }
}
