<?php

declare(strict_types=1);

namespace IVMS\Model;

use IVMS\Validatable;
use IVMS\Validation;

final readonly class Address implements Validatable
{
    public function __construct(
        public string $addressType,
        public string $country,
        public string $townName,
        public ?string $addressLine = null,
        public ?string $buildingNumber = null,
        public ?string $buildingName = null,
        public ?string $postcode = null,
        public ?string $districtName = null,
        public ?string $subDivision = null,
    ) {
        $this->validate();
    }

    public function validate(): void
    {
        Validation::ensureInArray($this->addressType, ['HOME', 'BIZZ', 'GEOG'], 'addressType');
        Validation::ensureCountryCode($this->country, 'country');
        Validation::ensureNotBlank($this->townName, 'townName');
    }

    public function toArray(): array
    {
        return array_filter([
            'addressType' => $this->addressType,
            'country' => $this->country,
            'townName' => $this->townName,
            'addressLine' => $this->addressLine,
            'buildingNumber' => $this->buildingNumber,
            'buildingName' => $this->buildingName,
            'postcode' => $this->postcode,
            'districtName' => $this->districtName,
            'subDivision' => $this->subDivision,
        ], static fn ($v): bool => $v !== null);
    }
}
