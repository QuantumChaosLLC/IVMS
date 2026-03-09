<?php

declare(strict_types=1);

namespace IVMS\Model;

use IVMS\Validatable;
use IVMS\Validation;

final readonly class NaturalPersonName implements Validatable
{
    public function __construct(
        public string $nameIdentifierType,
        public string $primaryIdentifier,
        public ?string $secondaryIdentifier = null,
    ) {
        $this->validate();
    }

    public function validate(): void
    {
        Validation::ensureInArray($this->nameIdentifierType, ['LEGL', 'ALIA', 'MAID'], 'nameIdentifierType');
        Validation::ensureNotBlank($this->primaryIdentifier, 'primaryIdentifier');
    }

    public function toArray(): array
    {
        return array_filter([
            'nameIdentifierType' => $this->nameIdentifierType,
            'primaryIdentifier' => $this->primaryIdentifier,
            'secondaryIdentifier' => $this->secondaryIdentifier,
        ], static fn ($v): bool => $v !== null);
    }
}
