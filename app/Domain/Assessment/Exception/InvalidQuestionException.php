<?php

namespace App\Domain\Assessment\Exception;

final class InvalidQuestionException extends AssessmentRuleViolation
{
    public static function because(string $reason): self
    {
        return new self($reason);
    }
}
