<?php

namespace Jpeters8889\Architect\Shared\Contracts;

use Illuminate\Support\Collection;

/** @template TType */
interface RegistrarContract
{
    public function register(string $item): void;

    /** @phpstan-ignore-next-line */
    public function all(): Collection;
}
