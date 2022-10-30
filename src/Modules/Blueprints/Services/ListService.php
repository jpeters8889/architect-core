<?php

namespace Jpeters8889\Architect\Modules\Blueprints\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
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
        return $fields->filter(fn (AbstractField $field) => $field->shouldDisplayOnTable());
    }

    public function paginator(): LengthAwarePaginator
    {
        return $this->paginator;
    }

    public function load(ListServiceLoader $loader): self
    {
        if (! $loader->sorting) {
            $loader->sorting = $this->blueprint->orderBy();
        }

        $baseQuery = $this->blueprint->query();

        $this->applyFilters($baseQuery, $loader->filter);

        $baseQuery
            ->orderBy(...$loader->sorting)
            ->when($loader->hasSearch(), fn (Builder $builder) => $this->applySearch($builder, (string)$loader->search));

        $this->paginator = $baseQuery->paginate(
            $this->blueprint->perPage(),
            [$this->blueprint->modelKey()],
            page: $loader->page
        );

        $this->currentPage = $loader->page ?: 1;

        $this->currentSorting = $loader->sorting;

        return $this;
    }

    /**
     * @param Builder<Model> $builder
     * @return Builder<Model>
     */
    protected function applySearch(Builder $builder, string $term): Builder
    {
        foreach ($this->blueprint()->fields() as $field) {
            $builder = $field->searchFor($builder, $term);
        }

        return $builder;
    }

    /**
     * @param Builder<Model> $builder
     */
    protected function applyFilters(Builder $builder, ?array $filters): void
    {
        if (! $filters) {
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

    /** @return array{search: string, sort: array{column: string, direction: string}, filters: Collection<int, array{key: string, filters: string[]}>} */
    public function currentRequestValues(Request $request): array
    {
        /** @var string $search */
        $search = $request->get('search', '');

        return [
            'search' => $search,
            'sort' => $this->currentSorting($request),
            'filters' => $this->currentFilters($request),
        ];
    }

    /** @return array{column: string, direction: string} */
    protected function currentSorting(Request $request): array
    {
        /** @var string $column */
        $column = $request->get('sortItem', $this->blueprint()->orderBy()[0]);

        /** @var string $direction */
        $direction = $request->get('sortDirection', $this->blueprint()->orderBy()[1]);

        return [
            'column' => $column,
            'direction' => $direction,
        ];
    }

    /** @return array{key: string, filters: array<string>} */
    protected function currentFilters(Request $request): array
    {
        /** @var callable(array{key: string, label: string, options: string}): array{key: string, filters: array<string>} $map */
        $map = function (array $filter) use ($request) {
            /** @var string $filters */
            $filters = $request->input("filter.{$filter['key']}");

            return [
                'key' => $filter['key'],
                'filters' => explode(',', $filters),
            ];
        };

        return collect($this->blueprint()->availableFilters())
            ->transform($map)
            ->all();
    }
}
