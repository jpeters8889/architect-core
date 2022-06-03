<?php

namespace Jpeters8889\Architect\Modules\Blueprints;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Jpeters8889\Architect\Modules\Fields\AbstractField;
use Jpeters8889\Architect\Shared\Contracts\Registerable;
use Jpeters8889\Architect\Shared\Traits\DisplaysOnNavigation;

/**
 * @implements Registerable<AbstractBlueprint>
 */
abstract class AbstractBlueprint implements Registerable
{
    use DisplaysOnNavigation;

    public function group(): string
    {
        return 'Blueprints';
    }

    protected function friendlyName(): string
    {
        return Str::of(class_basename(static::class))
            ->before('Blueprint')
            ->plural();
    }

    /**
     * @return class-string<Model>
     * @
     */
    abstract protected function model(): string;

    public function modelKey(): string
    {
        return 'id';
    }

    /** @return AbstractField[] */
    public function fields(): array
    {
        return [];
    }

    /** @return Builder<Model> */
    public function query(): Builder
    {
        return $this->model()::query();
    }

    public function perPage(): int
    {
        return 10;
    }

    public function resolveFieldFromColumn(string $column): ?AbstractField
    {
        return collect($this->fields())
            ->filter(fn (AbstractField $field) => $field->column() === $column)
            ->first();
    }

    public function resolveFieldFromLabel(string $label): ?AbstractField
    {
        return collect($this->fields())
            ->filter(fn (AbstractField $field) => $field->label() === $label)
            ->first();
    }

    /** @return array{string, 'asc|'desc'} */
    public function orderBy(): array
    {
        return ['id', 'desc'];
    }
}
