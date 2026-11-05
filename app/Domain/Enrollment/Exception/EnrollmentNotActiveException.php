<?php

namespace App\Domain\Enrollment\Exception;

use DomainException;

final class EnrollmentNotActiveException extends DomainException
{
    public static function create(): self
    {
        return new self('Student bu kursda faol emas.');
    }
}
