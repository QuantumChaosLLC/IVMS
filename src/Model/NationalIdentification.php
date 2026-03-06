<?php

declare(strict_types=1);

namespace IVMS\Model;

use IVMS\Validatable;
use IVMS\Validation;

final readonly class NationalIdentification implements Validatable
{
    public function __construct(
        public string $nationalIdentifier,
        public string $nationalIdentifierType,
        public string $countryOfIssue,
        public ?string $registrationAuthority = null,
    ) {
        $this->validate();
    }

    public function validate(): void
    {
        Validation::ensureNotBlank($this->nationalIdentifier, 'nationalIdentifier');
        Validation::ensureInArray($this->nationalIdentifierType, ['ARNU', 'CCPT', 'RAID', 'DRLC', 'FIIN'], 'nationalIdentifierType');
        Validation::ensureCountryCode($this->countryOfIssue, 'countryOfIssue');
    }

    public function toArray(): array
    {
        return array_filter([
            'nationalIdentifier' => $this->nationalIdentifier,
            'nationalIdentifierType' => $this->nationalIdentifierType,
            'countryOfIssue' => $this->countryOfIssue,
            'registrationAuthority' => $this->registrationAuthority,
        ], static fn ($v): bool => $v !== null);
    }
}
