<?php

namespace Jpeters8889\Architect\Modules\Blueprints\Processors;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Jpeters8889\Architect\Modules\Blueprints\Http\Requests\UpdateItemRequest;
use Jpeters8889\Architect\Modules\Fields\AbstractField;

class EditItemProcessor extends Processor
{
    protected Model $item;

    public function resolveItem(Model|string|int $id): self
    {
        if (! $id instanceof Model) {
            $this->item = $this->blueprint()->query()->findOrFail($id);

            return $this;
        }

        $this->item = $id;

        return $this;
    }

    public function validationRules(): array
    {
        $rules = parent::validationRules();

        return collect($rules)
            ->map(fn ($rules) => array_map(function ($rule) {
                if (! Str::contains($rule, 'unique')) {
                    return $rule;
                }

                $table = Str::after($rule, ':');

                return Rule::unique($table)->ignore($this->item);
            }, $rules))->toArray();
    }

    public function updateItemFromRequest(UpdateItemRequest $request): void
    {
        DB::transaction(function () use ($request) {
            $this->fields->each(function (AbstractField $field) use ($request) {
                $field->setValue($this->item, $request->input($field->column()));
            });

            $this->item->save();
        });
    }
}
