<?php

namespace App\Domain\Enrollment\Exception;

use DomainException;

final class StudentNotActiveInCourseException extends DomainException
{
    public static function create(): self
    {
        return new self('Student bu kursda faol emasligi sababli, vazifani topshirolmaydi');
    }
}
