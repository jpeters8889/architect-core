<?php

namespace Jpeters8889\Architect\Modules\Blueprints\DTO;

use Spatie\DataTransferObject\Attributes\Strict;
use Spatie\DataTransferObject\DataTransferObject;

#[Strict]
/**
 * @props string $label
 * @props string $slug
 */
class BlueprintFormField extends DataTransferObject
{
    public string $label;

    public string $component;

    public ?array $rules = [];

    public ?string $helpText;
}
