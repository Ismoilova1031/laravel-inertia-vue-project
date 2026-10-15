<?php

namespace App\Domain\Lesson\Exception;

use App\Domain\Lesson\LessonId;
use DomainException;

final class LessonTaskRequiredException extends DomainException
{
    public static function create(): self
    {
        return new self("Task turidagi dars uchun topshiriq ma'lumotlari kerak");
    }
}