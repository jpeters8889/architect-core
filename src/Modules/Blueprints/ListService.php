<?php

namespace Jpeters8889\Architect\Modules\Blueprints;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Jpeters8889\Architect\Modules\Fields\AbstractField;

class ListService extends BlueprintDisplayService
{
    protected LengthAwarePaginator $paginator;

    protected int $currentPage = 1;

    protected array $currentSorting;

    /**
     * @param Collection<int, AbstractField> $fields
     * @return Collection<int, AbstractField>
     */
    protected function transformFields(Collection $fields): Collection
    {
        return $fields->filter(fn (AbstractField $field) => $field->shouldDisplayOnTable());
    }

    public function paginator(): LengthAwarePaginator
    {
        return $this->paginator;
    }

    /** @param array{string, 'asc' | 'desc'} | null $sorting */
    public function load(int $page = null, array $sorting = null): self
    {
        if (! $sorting) {
            $sorting = $this->blueprint->orderBy();
        }

        $this->paginator = $this
            ->blueprint
            ->query()
            ->orderBy(...$sorting)
            ->paginate(
                $this->blueprint->perPage(),
                [$this->blueprint->modelKey()],
                page: $page
            );

        $this->currentPage = $page ?: 1;

        $this->currentSorting = $sorting;

        return $this;
    }

    /** @return array{headers: Collection<int, array{label: string, column: string, component: string, sortable: bool}>} */
    protected function additionalMetas(): array
    {
        /** @var string[] $columns */
        $columns = $this->columns()->toArray();

        /** @var string[] $components */
        $components = $this->components()->toArray();

        return [
            'headers' => $this->headers()->map(fn (string $header, int $index) => [
                'label' => $header,
                'column' => $columns[$index],
                'component' => $components[$index],
                'sortable' => (bool)$this->blueprint->resolveFieldFromLabel($header)?->sortable(),
            ])->values(),
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

    public function sortBy(): array
    {
        return $this->currentSorting;
    }
}
