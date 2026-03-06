<?php

declare(strict_types=1);

namespace IVMS\Model;

use IVMS\Exception\ValidationException;
use IVMS\Validatable;
use IVMS\Validation;

final readonly class NaturalPerson implements Validatable
{
    /** @param list<NaturalPersonName> $name */
    /** @param list<Address> $geographicAddress */
    public function __construct(
        public array $name,
        public array $geographicAddress = [],
        public ?NationalIdentification $nationalIdentification = null,
        public ?string $customerIdentification = null,
        public ?DateAndPlaceOfBirth $dateAndPlaceOfBirth = null,
        public ?string $countryOfResidence = null,
        public ?string $nationality = null,
    ) {
        $this->validate();
    }

    public function validate(): void
    {
        if ($this->name === []) {
            throw new ValidationException('NaturalPerson requires at least one name entry.');
        }

        Validation::ensureMaxCount($this->name, 10, 'name');
        Validation::ensureMaxCount($this->geographicAddress, 16, 'geographicAddress');
        Validation::ensureCountryCode($this->countryOfResidence, 'countryOfResidence');
        Validation::ensureCountryCode($this->nationality, 'nationality');

        foreach ($this->name as $name) {
            $name->validate();
        }

        foreach ($this->geographicAddress as $address) {
            $address->validate();
        }
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => array_map(static fn (NaturalPersonName $n): array => $n->toArray(), $this->name),
            'geographicAddress' => array_map(static fn (Address $a): array => $a->toArray(), $this->geographicAddress),
            'nationalIdentification' => $this->nationalIdentification?->toArray(),
            'customerIdentification' => $this->customerIdentification,
            'dateAndPlaceOfBirth' => $this->dateAndPlaceOfBirth?->toArray(),
            'countryOfResidence' => $this->countryOfResidence,
            'nationality' => $this->nationality,
        ], static fn ($v): bool => $v !== null && $v !== []);
    }
}
