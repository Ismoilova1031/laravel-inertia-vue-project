<?php

namespace App\Domain\Course\Exception;

use DomainException;

final class CannotPublishWithoutLessonsException extends DomainException
{
    public static function create(): self
    {
        return new self("Kamida 1 ta dars bo'lmasa, kursni e'lon qilib bo'lmaydi.");
    }
}
