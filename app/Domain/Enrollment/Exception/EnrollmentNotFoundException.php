<?php

namespace App\Domain\Enrollment\Exception;

use DomainException;

final class EnrollmentNotFoundException extends DomainException
{
    public static function create(): self
    {
        return new self("Student bu kursga yozilmagan");
    }
}