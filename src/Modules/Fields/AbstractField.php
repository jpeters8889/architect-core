<?php

namespace Jpeters8889\Architect\Modules\Fields;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

abstract class AbstractField
{
    protected string $label;

    protected ?Closure $tableGetter = null;

    protected ?Closure $formGetter = null;

    protected ?Closure $customSetter = null;

    protected bool $displayOnTable = true;

    protected bool $displayOnForm = true;

    protected bool $sortable = false;

    protected array $formValidationRules = [];

    protected ?string $formHelpText = null;

    final public function __construct(protected string $column, string $label = null)
    {
        if (! $label) {
            $label = Str::of($this->column)->headline()->toString();
        }

        $this->label = $label;

        $this->booted();
    }

    protected function booted(): void
    {
        //
    }

    public static function make(string $column, string $label = null): static
    {
        return new static($column, $label);
    }

    public function column(): string
    {
        return $this->column;
    }

    public function label(): string
    {
        return $this->label;
    }

    protected function getCurrentValue(Model $model): mixed
    {
        return $model->{$this->column()};
    }

    public function getCurrentValueForTable(Model $model): mixed
    {
        if ($this->tableGetter) {
            return call_user_func($this->tableGetter, $model);
        }

        return $this->getCurrentValue($model);
    }

    public function getCurrentValueForForm(Model $model): mixed
    {
        if ($this->formGetter) {
            return call_user_func($this->formGetter, $model);
        }

        return $this->getCurrentValue($model);
    }

    public function setValue(Model $model, mixed $value): void
    {
        if ($this->customSetter) {
            call_user_func($this->customSetter, $model, $value);

            return;
        }

        $model->{$this->column()} = $value;
    }

    /** @phpstan-param Closure(Model $model, mixed $value): mixed $setter */
    public function setValueUsing(Closure $setter): static
    {
        $this->customSetter = $setter;

        return $this;
    }

    /** @phpstan-param Closure(Model $model): mixed $getter */
    public function getValueForTableUsing(Closure $getter): static
    {
        $this->tableGetter = $getter;

        return $this;
    }

    /** @phpstan-param Closure(Model $model): mixed $getter */
    public function getValueForFormsUsing(Closure $getter): static
    {
        $this->formGetter = $getter;

        return $this;
    }

    public function hideOnTable(): static
    {
        $this->displayOnTable = false;

        return $this;
    }

    public function formOnly(): static
    {
        return $this->hideOnTable();
    }

    public function hideOnForm(): static
    {
        $this->displayOnForm = false;

        return $this;
    }

    public function tableOnly(): static
    {
        return $this->hideOnForm();
    }

    public function shouldDisplayOnTable(): bool
    {
        return $this->displayOnTable;
    }

    public function shouldDisplayOnForm(): bool
    {
        return $this->displayOnForm;
    }

    public function component(): string
    {
        return class_basename($this);
    }

    public function sortable(): bool
    {
        return $this->sortable;
    }

    public function isSortable(): static
    {
        $this->sortable = true;

        return $this;
    }

    public function required(): static
    {
        $this->formValidationRules = array_merge(['required'], $this->formValidationRules);

        return $this;
    }

    public function validationRules(array $rules): static
    {
        $this->formValidationRules = $rules;

        return $this;
    }

    public function getValidationRules(): array
    {
        return $this->formValidationRules;
    }

    public function formHelpText(string $text): static
    {
        $this->formHelpText = $text;

        return $this;
    }

    public function getFormHelpText(): ?string
    {
        return $this->formHelpText;
    }

    public function metaData(): array
    {
        return [];
    }
}
