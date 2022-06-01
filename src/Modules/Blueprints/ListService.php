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

    /** @return Collection<int, string> */
    public function components(): Collection
    {
        return $this->fields
            ->filter(fn (AbstractField $field) => $field->shouldDisplayOnTable())
            ->map(fn (AbstractField $field) => $field->component());
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

    /** @return array{title: string, headers: Collection<int, array{label: string, column: string, component: string}>} */
    public function metas(): array
    {
        /** @var string[] $columns */
        $columns = $this->columns()->toArray();

        /** @var string[] $components */
        $components = $this->components()->toArray();

        return [
            'title' => $this->blueprint()->label(),
            'headers' => $this->headers()->map(fn (string $header, int $index) => [
                'label' => $header,
                'column' => $columns[$index],
                'component' => $components[$index],
            ]),
        ];
    }

    /** @return array{currentPage: numeric, numberOfPages: numeric, hasNextPage: bool, hasPreviousPage: bool, items: Collection<int, Collection<string, mixed>>} */
    public function data(): array
    {
        /** @var Paginator $paginator */
        $paginator = $this->paginator();

        return [
            'currentPage' => $paginator->currentPage(),
            'numberOfPages' => $paginator->lastPage(),
            'hasNextPage' => $paginator->hasMorePages(),
            'hasPreviousPage' => $paginator->currentPage() > 1,
            'items' => $paginator->currentItems($this),
        ];
    }
}
