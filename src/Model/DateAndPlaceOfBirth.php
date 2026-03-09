<?php

declare(strict_types=1);

namespace IVMS\Model;

use IVMS\Exception\ValidationException;
use IVMS\Validatable;
use IVMS\Validation;

final readonly class DateAndPlaceOfBirth implements Validatable
{
    public function __construct(
        public string $dateOfBirth,
        public string $placeOfBirth,
    ) {
        $this->validate();
    }

    public function validate(): void
    {
        Validation::ensureNotBlank($this->placeOfBirth, 'placeOfBirth');

        $date = \DateTimeImmutable::createFromFormat('Y-m-d', $this->dateOfBirth);
        if ($date === false || $date->format('Y-m-d') !== $this->dateOfBirth) {
            throw new ValidationException('dateOfBirth must use YYYY-MM-DD format.');
        }
    }

    public function toArray(): array
    {
        return [
            'dateOfBirth' => $this->dateOfBirth,
            'placeOfBirth' => $this->placeOfBirth,
        ];
    }
}
