<?php

declare(strict_types=1);

namespace IVMS;

use IVMS\Exception\ValidationException;

final class Validation
{
    public static function ensureNotBlank(string $value, string $field): void
    {
        if (trim($value) === '') {
            throw new ValidationException(sprintf('%s cannot be blank.', $field));
        }
    }

    public static function ensureInArray(string $value, array $allowed, string $field): void
    {
        if (!in_array($value, $allowed, true)) {
            throw new ValidationException(sprintf('%s must be one of: %s.', $field, implode(', ', $allowed)));
        }
    }

    public static function ensureCountryCode(?string $code, string $field): void
    {
        if ($code === null) {
            return;
        }

        if (!preg_match('/^[A-Z]{2}$/', $code)) {
            throw new ValidationException(sprintf('%s must be an ISO-3166 alpha-2 code.', $field));
        }
    }

    public static function ensureMaxCount(array $items, int $max, string $field): void
    {
        if (count($items) > $max) {
            throw new ValidationException(sprintf('%s cannot have more than %d entries.', $field, $max));
        }
    }
}
