<?php

namespace Jpeters8889\Architect\Modules\Navigation\DTO;

use Spatie\DataTransferObject\Attributes\Strict;
use Spatie\DataTransferObject\DataTransferObject;

#[Strict]
/**
 * @props string $label
 * @props string $slug
 */
class NavigationItem extends DataTransferObject
{
    public string $label;

    public string $slug;
}
