<?php

namespace Jpeters8889\Architect\Shared\Contracts;

use Illuminate\Support\Collection;

/** @template TType */
interface RegistrarContract
{
    /** @param class-string<Registerable<TType>> $item */
    public function register(string $item): void;

    /** @return Collection<int, class-string<Registerable<TType>>> */
    public function all(): Collection;
}
