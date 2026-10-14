<?php

namespace App\Domain\Course\Exception;

use App\Domain\Course\CourseStatus;
use DomainException;

final class InvalidCourseStatusTransitionException extends DomainException
{
    public static function between(CourseStatus $from, CourseStatus $to): self
    {
        return new self("Kurs holatini {$from->label()} dan {$to->label()} ga o'zgartirib bo'lmaydi.");
    }
}
