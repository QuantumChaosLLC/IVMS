<?php

declare(strict_types=1);

namespace IVMS;

use IVMS\Model\Address;
use IVMS\Model\DateAndPlaceOfBirth;
use IVMS\Model\LegalPerson;
use IVMS\Model\LegalPersonName;
use IVMS\Model\NationalIdentification;
use IVMS\Model\NaturalPerson;
use IVMS\Model\NaturalPersonName;
use IVMS\Model\Person;

final class Hydrator
{
    /** @param array<string,mixed> $payload */
    public static function fromArray(array $payload): IVMS
    {
        return new IVMS(
            originator: array_map([self::class, 'person'], $payload['originator'] ?? []),
            beneficiary: array_map([self::class, 'person'], $payload['beneficiary'] ?? []),
        );
    }

    /** @param array<string,mixed> $data */
    private static function person(array $data): Person
    {
        return new Person(
            naturalPerson: isset($data['naturalPerson']) ? self::naturalPerson($data['naturalPerson']) : null,
            legalPerson: isset($data['legalPerson']) ? self::legalPerson($data['legalPerson']) : null,
        );
    }

    /** @param array<string,mixed> $data */
    private static function naturalPerson(array $data): NaturalPerson
    {
        $dob = $data['dateAndPlaceOfBirth'] ?? $data['dataAndPlaceOfBirth'] ?? null;

        return new NaturalPerson(
            name: array_map([self::class, 'naturalPersonName'], $data['name'] ?? []),
            geographicAddress: array_map([self::class, 'address'], $data['geographicAddress'] ?? []),
            nationalIdentification: isset($data['nationalIdentification']) ? self::nationalIdentification($data['nationalIdentification']) : null,
            customerIdentification: $data['customerIdentification'] ?? null,
            dateAndPlaceOfBirth: is_array($dob) ? self::dateAndPlaceOfBirth($dob) : null,
            countryOfResidence: $data['countryOfResidence'] ?? null,
            nationality: $data['nationality'] ?? null,
        );
    }

    /** @param array<string,mixed> $data */
    private static function legalPerson(array $data): LegalPerson
    {
        return new LegalPerson(
            name: array_map([self::class, 'legalPersonName'], $data['name'] ?? []),
            geographicAddress: array_map([self::class, 'address'], $data['geographicAddress'] ?? []),
            customerIdentification: $data['customerIdentification'] ?? null,
            nationalIdentification: isset($data['nationalIdentification']) ? self::nationalIdentification($data['nationalIdentification']) : null,
            countryOfRegistration: $data['countryOfRegistration'] ?? null,
        );
    }

    /** @param array<string,mixed> $data */
    private static function naturalPersonName(array $data): NaturalPersonName
    {
        return new NaturalPersonName(
            nameIdentifierType: (string) ($data['nameIdentifierType'] ?? ''),
            primaryIdentifier: (string) ($data['primaryIdentifier'] ?? ''),
            secondaryIdentifier: $data['secondaryIdentifier'] ?? null,
        );
    }

    /** @param array<string,mixed> $data */
    private static function legalPersonName(array $data): LegalPersonName
    {
        return new LegalPersonName(
            nameIdentifierType: (string) ($data['nameIdentifierType'] ?? ''),
            legalPersonName: (string) ($data['legalPersonName'] ?? ''),
        );
    }

    /** @param array<string,mixed> $data */
    private static function address(array $data): Address
    {
        return new Address(
            addressType: (string) ($data['addressType'] ?? ''),
            country: (string) ($data['country'] ?? ''),
            townName: (string) ($data['townName'] ?? ''),
            addressLine: $data['addressLine'] ?? null,
            buildingNumber: $data['buildingNumber'] ?? null,
            buildingName: $data['buildingName'] ?? null,
            postcode: $data['postcode'] ?? null,
            districtName: $data['districtName'] ?? null,
            subDivision: $data['subDivision'] ?? null,
        );
    }

    /** @param array<string,mixed> $data */
    private static function nationalIdentification(array $data): NationalIdentification
    {
        return new NationalIdentification(
            nationalIdentifier: (string) ($data['nationalIdentifier'] ?? ''),
            nationalIdentifierType: (string) ($data['nationalIdentifierType'] ?? ''),
            countryOfIssue: (string) ($data['countryOfIssue'] ?? ''),
            registrationAuthority: $data['registrationAuthority'] ?? null,
        );
    }

    /** @param array<string,mixed> $data */
    private static function dateAndPlaceOfBirth(array $data): DateAndPlaceOfBirth
    {
        return new DateAndPlaceOfBirth(
            dateOfBirth: (string) ($data['dateOfBirth'] ?? ''),
            placeOfBirth: (string) ($data['placeOfBirth'] ?? ''),
        );
    }
}
