<?php

namespace App\Domain\Course\Exception;

use DomainException;

final class CannotDeleteNonDraftCourseException extends DomainException
{
    public static function create(): self
    {
        return new self('Faqat Draft holatidagi kursni o\'chirish mumkin. E\'lon qilingan kursni Archived qiling.');
    }
}
