<?php

namespace Jpeters8889\Architect\Modules\Blueprints;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
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

    public function listRoute(): string
    {
        /** @var string $basePath */
        $basePath = config('architect.base_path');

        return "{$basePath}/blueprint/{$this->slug()}";
    }

    public function createRoute(): string
    {
        return "{$this->listRoute()}/create";
    }

    protected function friendlyName(): Stringable
    {
        return $this->shortName()->plural();
    }

    public function shortName(): Stringable
    {
        return Str::of(class_basename(static::class))->before('Blueprint');
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

    public function newModel(): Model
    {
        $class = $this->model();

        return new $class();
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

    /** @return array{string, 'asc'|'desc'} */
    public function orderBy(): array
    {
        return ['id', 'desc'];
    }

    public function canEdit(Model $model): bool
    {
        return true;
    }

    public function canDelete(Model $model): bool
    {
        return true;
    }

    public function isItemDeleted(Model $model): bool
    {
        return $model->exists !== true;
    }

    public function canDuplicate(Model $model): bool
    {
        return true;
    }

    public function publicUrl(Model $model): string|null
    {
        return null;
    }

    public function handleDelete(Model $model): void
    {
        $model->delete();
    }

    public function handleRestore(Model $model): void
    {
        //
    }

    /** @return array<array{key: string, label: string, options: array<string>}> */
    public function availableFilters(): array
    {
        return collect($this->fields())
            ->filter(fn (AbstractField $field) => $field->isFilterable())
            ->values()
            ->map(function (AbstractField $field): array {
                return [
                    'key' => $field->column(),
                    'label' => $field->label(),
                    'options' => $field->filterKeys(),
                ];
            })
            ->all();
    }

    public function isSearchable(): bool
    {
        return false;
    }

    /**
     * @param Builder<Model> $builder
     * @param string $term
     */
    public function searchFor(Builder $builder, string $term): void
    {
        foreach ($this->fields() as $field) {
            $builder->where($field->column(), 'like', "%{$term}");
        }
    }
}
