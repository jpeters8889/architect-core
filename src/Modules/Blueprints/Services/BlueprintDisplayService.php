<?php

namespace Jpeters8889\Architect\Modules\Blueprints\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Jpeters8889\Architect\Modules\Blueprints\AbstractBlueprint;
use Jpeters8889\Architect\Modules\Fields\AbstractField;

abstract class BlueprintDisplayService
{
    /** @var Collection<int, AbstractField> */
    protected Collection $fields;

    public function __construct(protected AbstractBlueprint $blueprint)
    {
        $this->collectFields();
    }

    protected function collectFields(): void
    {
        $this->fields = $this->transformFields(collect($this->blueprint->fields()));
    }

    /**
     * @param Collection<int, AbstractField> $fields
     * @return Collection<int, AbstractField>
     */
    protected function transformFields(Collection $fields): Collection
    {
        return $fields;
    }

    /** @return Collection<int, string> */
    public function headers(): Collection
    {
        return $this->fields->map(fn (AbstractField $field) => $field->label());
    }

    /** @return Collection<int, string> */
    public function columns(): Collection
    {
        return $this->fields->map(fn (AbstractField $field) => $field->column());
    }

    /** @return Collection<int, string> */
    public function components(): Collection
    {
        return $this->fields->map(fn (AbstractField $field) => $field->component());
    }

    public function blueprint(): AbstractBlueprint
    {
        return $this->blueprint;
    }

    public function metas(): array
    {
        return [
            'title' => $this->blueprint()->label(),
            'singularTitle' => Str::singular($this->blueprint()->label()),
            'availableFilters' => $this->blueprint->availableFilters(),
            'searchable' => $this->blueprint->isSearchable(),
            ...$this->additionalMetas(),
        ];
    }

    protected function additionalMetas(): array
    {
        return [];
    }
}
