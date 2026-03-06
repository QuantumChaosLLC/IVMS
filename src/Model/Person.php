<?php

declare(strict_types=1);

namespace IVMS\Model;

use IVMS\Exception\ValidationException;
use IVMS\Validatable;

final readonly class Person implements Validatable
{
    public function __construct(
        public ?NaturalPerson $naturalPerson = null,
        public ?LegalPerson $legalPerson = null,
    ) {
        $this->validate();
    }

    public function validate(): void
    {
        if (($this->naturalPerson === null) === ($this->legalPerson === null)) {
            throw new ValidationException('Person must contain exactly one of naturalPerson or legalPerson.');
        }

        $this->naturalPerson?->validate();
        $this->legalPerson?->validate();
    }

    public function toArray(): array
    {
        return array_filter([
            'naturalPerson' => $this->naturalPerson?->toArray(),
            'legalPerson' => $this->legalPerson?->toArray(),
        ], static fn ($v): bool => $v !== null);
    }
}
