<?php

namespace App\Domain\Course\Exception;

use App\Domain\Course\CourseId;
use DomainException;

final class CourseNotFoundException extends DomainException
{
    public static function withId(CourseId $courseId): self
    {
        return new self("Kurs topilmadi: {$courseId->value()}");
    }
}