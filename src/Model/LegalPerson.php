<?php

declare(strict_types=1);

namespace IVMS\Model;

use IVMS\Exception\ValidationException;
use IVMS\Validatable;
use IVMS\Validation;

final readonly class LegalPerson implements Validatable
{
    /** @param list<LegalPersonName> $name */
    /** @param list<Address> $geographicAddress */
    public function __construct(
        public array $name,
        public array $geographicAddress = [],
        public ?string $customerIdentification = null,
        public ?NationalIdentification $nationalIdentification = null,
        public ?string $countryOfRegistration = null,
    ) {
        $this->validate();
    }

    public function validate(): void
    {
        if ($this->name === []) {
            throw new ValidationException('LegalPerson requires at least one name entry.');
        }

        Validation::ensureCountryCode($this->countryOfRegistration, 'countryOfRegistration');

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
            'name' => array_map(static fn (LegalPersonName $n): array => $n->toArray(), $this->name),
            'geographicAddress' => array_map(static fn (Address $a): array => $a->toArray(), $this->geographicAddress),
            'customerIdentification' => $this->customerIdentification,
            'nationalIdentification' => $this->nationalIdentification?->toArray(),
            'countryOfRegistration' => $this->countryOfRegistration,
        ], static fn ($v): bool => $v !== null && $v !== []);
    }
}
