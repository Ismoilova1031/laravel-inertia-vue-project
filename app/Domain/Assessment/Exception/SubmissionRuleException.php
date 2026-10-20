<?php

namespace App\Domain\Assessment\Exception;

final class SubmissionRuleException extends AssessmentRuleViolation
{
    public static function because(string $reason): self
    {
        return new self($reason);
    }
}