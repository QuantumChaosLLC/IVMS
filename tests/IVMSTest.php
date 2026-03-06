<?php

declare(strict_types=1);

namespace IVMS\Tests;

use IVMS\Exception\ValidationException;
use IVMS\Hydrator;
use IVMS\IVMS;
use IVMS\Model\Address;
use IVMS\Model\DateAndPlaceOfBirth;
use IVMS\Model\LegalPerson;
use IVMS\Model\LegalPersonName;
use IVMS\Model\NationalIdentification;
use IVMS\Model\NaturalPerson;
use IVMS\Model\NaturalPersonName;
use IVMS\Model\Person;
use PHPUnit\Framework\TestCase;

final class IVMSTest extends TestCase
{
    public function testValidPayloadCanSerializeToArrayAndJson(): void
    {
        $payload = new IVMS(
            originator: [new Person(naturalPerson: $this->sampleNaturalPerson())],
            beneficiary: [new Person(legalPerson: $this->sampleLegalPerson())],
        );

        self::assertSame('Doe', $payload->toArray()['originator'][0]['naturalPerson']['name'][0]['primaryIdentifier']);
        self::assertStringContainsString('"originator"', $payload->toJson());
    }

    public function testHydratorBuildsPayloadFromSpecStyleArray(): void
    {
        $payload = Hydrator::fromArray([
            'originator' => [[
                'naturalPerson' => [
                    'name' => [[
                        'nameIdentifierType' => 'LEGL',
                        'primaryIdentifier' => 'Lee',
                    ]],
                    'dataAndPlaceOfBirth' => [
                        'dateOfBirth' => '1990-01-10',
                        'placeOfBirth' => 'Seoul',
                    ],
                ],
            ]],
            'beneficiary' => [[
                'legalPerson' => [
                    'name' => [[
                        'nameIdentifierType' => 'LEGL',
                        'legalPersonName' => 'ACME Ltd',
                    ]],
                ],
            ]],
        ]);

        self::assertSame('1990-01-10', $payload->toArray()['originator'][0]['naturalPerson']['dateAndPlaceOfBirth']['dateOfBirth']);
    }

    public function testPersonRequiresExactlyOneType(): void
    {
        $this->expectException(ValidationException::class);
        new Person();
    }

    public function testTopLevelRequiresOriginator(): void
    {
        $this->expectException(ValidationException::class);
        new IVMS(originator: [], beneficiary: [new Person(naturalPerson: $this->sampleNaturalPerson())]);
    }

    public function testInvalidCountryCodeFailsValidation(): void
    {
        $this->expectException(ValidationException::class);
        new Address(addressType: 'HOME', country: 'KOR', townName: 'Seoul');
    }

    public function testInvalidDateFormatFailsValidation(): void
    {
        $this->expectException(ValidationException::class);
        new DateAndPlaceOfBirth(dateOfBirth: '01-30-1990', placeOfBirth: 'Seoul');
    }

    public function testNaturalPersonRequiresName(): void
    {
        $this->expectException(ValidationException::class);
        new NaturalPerson(name: []);
    }

    public function testLegalPersonRequiresName(): void
    {
        $this->expectException(ValidationException::class);
        new LegalPerson(name: []);
    }

    public function testNationalIdentificationTypeMustBeAllowed(): void
    {
        $this->expectException(ValidationException::class);
        new NationalIdentification('1234', 'FOO', 'KR');
    }

    private function sampleNaturalPerson(): NaturalPerson
    {
        return new NaturalPerson(
            name: [new NaturalPersonName('LEGL', 'Doe', 'John')],
            geographicAddress: [new Address('HOME', 'KR', 'Seoul', addressLine: 'Gangnam-daero')],
            nationalIdentification: new NationalIdentification('12345678', 'CCPT', 'KR'),
            customerIdentification: 'cust-1',
            dateAndPlaceOfBirth: new DateAndPlaceOfBirth('1990-01-10', 'Seoul'),
            countryOfResidence: 'KR',
            nationality: 'KR',
        );
    }

    private function sampleLegalPerson(): LegalPerson
    {
        return new LegalPerson(
            name: [new LegalPersonName('LEGL', 'Acme Co')],
            geographicAddress: [new Address('BIZZ', 'US', 'Austin')],
            customerIdentification: 'biz-99',
            nationalIdentification: new NationalIdentification('12-3434', 'FIIN', 'US'),
            countryOfRegistration: 'US',
        );
    }
}
