<?php

namespace Jpeters8889\Architect\Modules\Blueprints;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Jpeters8889\Architect\Modules\Fields\AbstractField;

class Paginator extends LengthAwarePaginator
{
    /**
     * @param ListService $listService
     * @return Collection<int, Collection<string, mixed>>
     */
    public function currentItems(ListService $listService): Collection
    {
        $ids = collect($this->items());

        $columns = $listService->headers()
            ->map(fn (string $header) => $listService->blueprint()->resolveFieldFromLabel($header))
            ->filter(fn (AbstractField|null $field) => $field !== null);

        return $listService
            ->blueprint()
            ->query()
            ->orderBy(...$listService->sortBy())->findMany($ids)
            ->map(fn (Model $item) => collect([
                '$meta' => [
                    'canEdit' => $listService->blueprint()->canEdit($item),
                    'canDelete' => $listService->blueprint()->canDelete($item),
                    'isDeleted' => $listService->blueprint()->isItemDeleted($item),
                    'canDuplicate' => $listService->blueprint()->canDuplicate($item),
                    'publicUrl' => $listService->blueprint()->publicUrl($item),
                    'id' => $item->{$listService->blueprint()->modelKey()},
                ],
                ...$columns->mapWithKeys(
                    fn (AbstractField $field) => [
                        $field->column() => $field->getCurrentValueForTable($item),
                    ]
                ),
            ]));
    }
}
