<?php

namespace Jpeters8889\Architect\Modules\Fields;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

abstract class AbstractField
{
    protected string $label;

    protected ?Closure $tableGetter = null;

    final public function __construct(protected string $column, string $label = null)
    {
        if (! $label) {
            $label = Str::of($this->column)->headline();
        }

        $this->label = $label;
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
            return call_user_func($this->tableGetter, $model, $this->column);
        }

        return $this->getCurrentValue($model);
    }

    /** @phpstan-param Closure(Model $model, string $column): mixed $getter */
    public function getValueForTableUsing(Closure $getter): static
    {
        $this->tableGetter = $getter;

        return $this;
    }
}
