<?php

declare(strict_types=1);

namespace IVMS;

interface Validatable
{
    public function validate(): void;

    /** @return array<string, mixed> */
    public function toArray(): array;
}
