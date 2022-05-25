<?php

namespace Jpeters8889\Architect\Modules\Blueprints\Exceptions;

use Exception;

class BlueprintNotFoundException extends Exception
{
    public static function fromSlug(string $slug): self
    {
        return new self(`Blueprint with slug {{$slug}} not found`);
    }
}
