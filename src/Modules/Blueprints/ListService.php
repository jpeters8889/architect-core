<?php

namespace Jpeters8889\Architect\Modules\Blueprints;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Jpeters8889\Architect\Modules\Fields\AbstractField;

class ListService
{
    /** @var Collection<int, AbstractField> */
    protected Collection $fields;

    protected LengthAwarePaginator $paginator;

    protected int $currentPage = 1;

    public function __construct(protected AbstractBlueprint $blueprint)
    {
        $this->collectFields();
    }

    protected function collectFields(): void
    {
        $this->fields = collect($this->blueprint->fields());
    }

    /** @return Collection<int, string> */
    public function headers(): Collection
    {
        return $this->fields
            ->filter(fn (AbstractField $field) => $field->shouldDisplayOnTable())
            ->map(fn (AbstractField $field) => $field->label());
    }

    /** @return Collection<int, string> */
    public function columns(): Collection
    {
        return $this->fields
            ->filter(fn (AbstractField $field) => $field->shouldDisplayOnTable())
            ->map(fn (AbstractField $field) => $field->column());
    }

    public function blueprint(): AbstractBlueprint
    {
        return $this->blueprint;
    }

    public function paginator(): LengthAwarePaginator
    {
        return $this->paginator;
    }

    public function load(int $page = null): self
    {
        $this->paginator = $this
            ->blueprint
            ->query()
            ->paginate(
                $this->blueprint->perPage(),
                [$this->blueprint->modelKey()],
                page: $page
            );

        $this->currentPage = $page ?: 1;

        return $this;
    }

    /** @return array{currentPage: numeric, numberOfPages: numeric, hasNextPage: bool, hasPreviousPage: bool, items: Collection<int, Collection<string, mixed>>} */
    public function data(): array
    {
        return [
            'currentPage' => $this->paginator()->currentPage(),
            'numberOfPages' => $this->paginator->lastPage(),
            'hasNextPage' => $this->paginator()->hasMorePages(),
            'hasPreviousPage' => $this->paginator->currentPage() > 1,
            'items' => $this->paginator()->currentItems($this),
        ];
    }
}
