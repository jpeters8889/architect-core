<?php

namespace Jpeters8889\Architect\Shared;

use Illuminate\Support\Str;
use Ramsey\Uuid\UuidInterface;

class Flash
{
    /** @return array{string, array{type: string, message:string, id: UuidInterface, metaData: array}} */
    public static function message(string $message, string $level = 'success', array $metaData = []): array
    {
        return ['flash', ['type' => $level, 'message' => $message, 'id' => Str::uuid(), 'metaData' => $metaData]];
    }
}
