# PHP IVMS

![CI](https://github.com/OWNER/IVMS/actions/workflows/ci.yml/badge.svg)

A production-ready **PHP 8.4** library for creating and validating [IVMS](https://intervasp.org/) payloads used by Travel Rule integrations.

> This package is versioned for Composer release as **v1.0.0**.

## Features

- Strictly typed IVMS domain objects.
- Built-in validation for key IVMS constraints:
  - required top-level originator + beneficiary,
  - natural vs legal person exclusivity,
  - supported code-list values (`addressType`, identifier types),
  - ISO alpha-2 country checks,
  - date validation (`YYYY-MM-DD`).
- Array + JSON serialization.
- Hydrator utility for incoming associative arrays.
- PHPUnit test suite with coverage reporting.
- GitHub Actions CI for linting and tests.

## Installation

```bash
composer require ivms/php-ivms
```

## Quick start

```php
<?php

declare(strict_types=1);

use IVMS\IVMS;
use IVMS\Model\Address;
use IVMS\Model\NaturalPerson;
use IVMS\Model\NaturalPersonName;
use IVMS\Model\Person;

$originator = new Person(
    naturalPerson: new NaturalPerson(
        name: [new NaturalPersonName('LEGL', 'Doe', 'John')],
        geographicAddress: [new Address('HOME', 'KR', 'Seoul')],
        countryOfResidence: 'KR',
        nationality: 'KR',
    )
);

$beneficiary = new Person(
    naturalPerson: new NaturalPerson(
        name: [new NaturalPersonName('LEGL', 'Roe', 'Jane')]
    )
);

$ivms = new IVMS([$originator], [$beneficiary]);

echo $ivms->toJson(pretty: true);
```

## Hydrating from arrays

```php
use IVMS\Hydrator;

$ivms = Hydrator::fromArray($payload);
```

## Validation behavior

All model constructors validate on creation and throw `IVMS\Exception\ValidationException` when data is invalid.

## Development

```bash
composer install
composer lint
composer test
composer coverage
```

## Composer publishing checklist

- Package name/version set in `composer.json` (`1.0.0`).
- PSR-4 autoload configured.
- CI configured under `.github/workflows/ci.yml`.
- README includes setup and usage.

## Notes on IVMS compatibility

The implementation follows the IVMS object model and core constraints used in the VerifyVASP guide and the 21 Analytics `ivms` reference implementation.
