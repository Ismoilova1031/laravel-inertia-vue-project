<?php

namespace App\Domain\Course\Exception;

use App\Domain\Course\VO\CourseId;
use DomainException;

final class CourseNotPublishedException extends DomainException
{
    public static function withId(CourseId $courseId): self
    {
        return new self("Kurs e'lon qilinmagan: {$courseId->value()}");
    }
}
