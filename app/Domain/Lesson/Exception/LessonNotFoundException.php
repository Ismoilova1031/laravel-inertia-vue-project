<?php

namespace App\Domain\Lesson\Exception;

use App\Domain\Lesson\VO\LessonId;
use DomainException;

final class LessonNotFoundException extends DomainException
{
    public static function withId(LessonId $id): self
    {
        return new self("Dars topilmadi: {$id->value()}");
    }
}
