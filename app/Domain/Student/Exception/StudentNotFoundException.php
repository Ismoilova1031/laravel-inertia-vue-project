<?php

namespace App\Domain\Student\Exception;

use App\Domain\Student\StudentId;
use DomainException;

final class StudentNotFoundException extends DomainException
{
    public static function withId(StudentId $id): self
    {
        return new self("Student topilmadi: {$id->value()}");
    }
}