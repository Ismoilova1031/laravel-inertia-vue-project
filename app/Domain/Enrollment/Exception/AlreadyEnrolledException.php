<?php

namespace App\Domain\Enrollment\Exception;

use DomainException;

final class AlreadyEnrolledException extends DomainException
{
    public static function create(): self
    {
        return new self('Student bu kursga allaqachon yozilgan.');
    }
}
