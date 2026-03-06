<?php

declare(strict_types=1);

namespace IVMS;

use IVMS\Exception\ValidationException;
use IVMS\Model\Person;

final readonly class IVMS implements Validatable
{
    /** @param list<Person> $originator */
    /** @param list<Person> $beneficiary */
    public function __construct(
        public array $originator,
        public array $beneficiary,
    ) {
        $this->validate();
    }

    public function validate(): void
    {
        if ($this->originator === []) {
            throw new ValidationException('originator must have at least one Person entry.');
        }

        if ($this->beneficiary === []) {
            throw new ValidationException('beneficiary must have at least one Person entry.');
        }

        foreach ([$this->originator, $this->beneficiary] as $group) {
            foreach ($group as $person) {
                $person->validate();
            }
        }
    }

    public function toArray(): array
    {
        return [
            'originator' => array_map(static fn (Person $person): array => $person->toArray(), $this->originator),
            'beneficiary' => array_map(static fn (Person $person): array => $person->toArray(), $this->beneficiary),
        ];
    }

    public function toJson(bool $pretty = false): string
    {
        $flags = JSON_THROW_ON_ERROR;
        if ($pretty) {
            $flags |= JSON_PRETTY_PRINT;
        }

        return json_encode($this->toArray(), $flags);
    }
}
