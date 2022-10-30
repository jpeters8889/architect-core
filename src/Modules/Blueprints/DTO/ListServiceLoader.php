<?php

namespace Jpeters8889\Architect\Modules\Blueprints\DTO;

use Illuminate\Http\Request;
use Spatie\DataTransferObject\DataTransferObject;

class ListServiceLoader extends DataTransferObject
{
    public int|null $page;

    public array|null $sorting;

    public array|null $filter;

    public string|null $search;

    public static function parseFromRequest(Request $request): self
    {
        return new self(
            page: $request->query('page'),
            sorting: $request->has('sorting') ? [$request->query('sorting'), $request->query('sortDirection', 'asc')] : null,
            filter: $request->query('filter'),
            search: $request->query('search'),
        );
    }

    public function hasSearch(): bool
    {
        return (bool)$this->search;
    }
}
