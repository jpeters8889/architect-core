<?php

namespace Jpeters8889\Architect\Modules\Blueprints\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Jpeters8889\Architect\Modules\Blueprints\DTO\ListServiceLoader;
use Jpeters8889\Architect\Modules\Blueprints\Paginator;
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
        return $fields->filter(fn(AbstractField $field) => $field->shouldDisplayOnTable());
    }

    public function paginator(): LengthAwarePaginator
    {
        return $this->paginator;
    }

    /** @param array{string, 'asc' | 'desc'} | null $sorting */
    public function load(ListServiceLoader $loader): self
    {
        if (!$loader->sorting) {
            $loader->sorting = $this->blueprint->orderBy();
        }

        $baseQuery = $this->blueprint->query();

        $this->applyFilters($baseQuery, $loader->filter);

        $this->paginator = $baseQuery
            ->orderBy(...$loader->sorting)
            ->paginate(
                $this->blueprint->perPage(),
                [$this->blueprint->modelKey()],
                page: $loader->page
            );

        $this->currentPage = $loader->page ?: 1;

        $this->currentSorting = $loader->sorting;

        return $this;
    }

    protected function applyFilters(Builder $builder, ?array $filters): void
    {
        if (!$filters) {
            return;
        }

        foreach ($filters as $key => $values) {
            $this->blueprint()->resolveFieldFromColumn($key)?->filter($builder, explode(',', $values));
        }
    }

    /** @return array{headers: Collection<int, array{label: string, column: string, component: string, sortable: bool}>} */
    protected function additionalMetas(): array
    {
        /** @var string[] $columns */
        $columns = $this->columns()->toArray();

        /** @var string[] $components */
        $components = $this->components()->toArray();

        return [
            'headers' => $this->headers()->map(fn(string $header, int $index) => [
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
            'totalItems' => $paginator->total(),
            'start' => ($paginator->currentPage() - 1) * $this->blueprint->perPage() + 1,
            'end' => $this->blueprint->perPage() * $paginator->currentPage(),
            'items' => $paginator->currentItems($this),
        ];
    }

    public function sortBy(): array
    {
        return $this->currentSorting;
    }
}
