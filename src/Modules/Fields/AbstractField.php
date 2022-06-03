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

    protected bool $displayOnTable = true;

    protected bool $sortable = false;

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

    public function shouldDisplayOnTable(): bool
    {
        return $this->displayOnTable;
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
}
