<?php

namespace Jpeters8889\Architect\Modules\Blueprints\Processors;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Jpeters8889\Architect\Modules\Blueprints\Http\Requests\UpdateItemRequest;
use Jpeters8889\Architect\Modules\Fields\AbstractField;
use Jpeters8889\Architect\Modules\Fields\Password;

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

    protected function updateUniqueValidationRules(array $rules): array
    {
        return array_map(function ($rule) {
            if (! Str::contains($rule, 'unique')) {
                return $rule;
            }

            $table = Str::after($rule, ':');

            return Rule::unique($table)->ignore($this->item);
        }, $rules);
    }

    protected function updatePasswordRequiredRules(array $rules, string $field): array
    {
        $passwordFields = $this->fields->filter(fn (AbstractField $field) => $field instanceof Password)
            ->map(fn (Password $field) => $field->column())
            ->values()
            ->toArray();

        if (! in_array($field, $passwordFields, true)) {
            return $rules;
        }

        return array_filter($rules, fn ($rule) => $rule !== 'required');
    }

    public function validationRules(): array
    {
        return collect(parent::validationRules())
            ->map(fn ($rules) => $this->updateUniqueValidationRules($rules))
            ->map(fn ($rules, $field) => $this->updatePasswordRequiredRules($rules, $field))
            ->toArray();
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
