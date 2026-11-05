<?php

namespace App\Domain\Assessment\Exception;

use App\Domain\Assessment\VO\TaskId;
use DomainException;

class TaskNotFoundException extends DomainException
{
    public static function withId(TaskId $id): self
    {
        return new self("ID: {$id->value()} bo'lgan task topilmadi.");
    }
}
