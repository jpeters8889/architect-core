<?php

namespace Jpeters8889\Architect\Modules\Blueprints\DTO;

use Spatie\DataTransferObject\Attributes\Strict;
use Spatie\DataTransferObject\DataTransferObject;

#[Strict]
class BlueprintFormField extends DataTransferObject
{
    public string $id;

    public string $label;

    public string $component;

    public ?array $rules = [];

    public ?string $helpText;
}
