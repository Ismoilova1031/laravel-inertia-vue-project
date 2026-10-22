<?php

namespace App\Domain\Assessment\Exception;

use App\Domain\Assessment\Submission\SubmissionId;

final class SubmissionNotFoundException extends \DomainException
{
    public static function withId(SubmissionId $id): self
    {
        return new self(sprintf('Submission with ID %s not found.', $id->value()));
    }
}
