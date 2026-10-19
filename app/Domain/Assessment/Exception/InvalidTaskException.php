<?php

namespace App\Domain\Assessment\Exception;

final class InvalidTaskException extends AssessmentRuleViolation
{
    public static function because(string $reason): self
    {
        return new self($reason);
    }
}
