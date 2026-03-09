<?php

declare(strict_types=1);

namespace IVMS\Model;

use IVMS\Validatable;
use IVMS\Validation;

final readonly class LegalPersonName implements Validatable
{
    public function __construct(
        public string $nameIdentifierType,
        public string $legalPersonName,
    ) {
        $this->validate();
    }

    public function validate(): void
    {
        Validation::ensureInArray($this->nameIdentifierType, ['LEGL', 'SHRT', 'TRAD'], 'nameIdentifierType');
        Validation::ensureNotBlank($this->legalPersonName, 'legalPersonName');
    }

    public function toArray(): array
    {
        return [
            'nameIdentifierType' => $this->nameIdentifierType,
            'legalPersonName' => $this->legalPersonName,
        ];
    }
}
