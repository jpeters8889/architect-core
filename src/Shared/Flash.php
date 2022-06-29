<?php

namespace Jpeters8889\Architect\Shared;

use Illuminate\Support\Str;

class Flash
{
    /** @return array{string, array{type: string, message: String, id: string, metaData: array{mixed}}} */
    public static function message(string $message, string $level = 'success', array $metaData = []): array
    {
        return ['flash', ['type' => $level, 'message' => $message, 'id' => Str::uuid(), 'metaData' => $metaData]];
    }
}
